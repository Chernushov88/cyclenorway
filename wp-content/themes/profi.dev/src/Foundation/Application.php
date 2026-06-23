<?php declare(strict_types=1);

namespace ProfiDev\Foundation;

use Mustache\Engine;
use Mustache\Loader\FilesystemLoader;
use ProfiDev\Container\Container;
use Exception;

class Application extends Container {
	/**
	 * The current globally available container (if any).
	 *
	 * @var static
	 */
	protected static Application $instance;

	/**
	 * The base path for the theme installation.
	 *
	 * @var string
	 */
	protected string $basePath;

	public bool $hasMustache = false;

	/**
	 * Get base path
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	public function getBasePath(string $path = ''): string {
		return $this->basePath . DIRECTORY_SEPARATOR . $path;
	}

	/**
	 * Get blocks path
	 *
	 * @return string
	 */
	public function getBlocksPath(): string {
		return $this->getBasePath('blocks');
	}

	/**
	 * Create a new theme instance
	 *
	 * @param string|null $basePath
	 */
	public function __construct(?string $basePath = null) {
		$this->basePath = match (true) {
			is_string($basePath) => $basePath,
			default => get_theme_file_path(),
		};

		$this->registerBaseBindings();
	}

	/**
	 * Begin configuring a new theme instance.
	 *
	 * @param string|null $basePath
	 *
	 * @return Application
	 */
	public static function configure(?string $basePath = null): Application {
		return Application::setInstance(new Application($basePath));
	}

	/**
	 * Get the globally available instance of the container.
	 *
	 * @return static
	 */
	public static function getInstance(): static {
		return static::$instance ??= new static(null);
	}

	/**
	 * Set the shared instance of the container.
	 *
	 * @param  Application $container
	 * @return Application|static
	 */
	public static function setInstance(Application $container ): Application|static {
		return static::$instance = $container;
	}

	/**
	 * Register base bindings
	 *
	 * @return void
	 */
	protected function registerBaseBindings(): void {
		$this->alias('app', Application::class);
		$this->singleton('vite', fn () => new Vite($this));
		$this->singleton('blocks', fn () => new Blocks($this));
		$this->singleton('customizations', fn () => new ThemeCustomization($this));
	}

	/**
	 * Get ENV variable
	 *
	 * @param string $name
	 * @param mixed|null $default
	 *
	 * @return mixed
	 */
	public function env(string $name, mixed $default = null): mixed {
		$result = getenv($name);

		return !empty($result) ? $result : $default;
	}

	/**
	 * With vite
	 *
	 * @param callable $callback
	 *
	 * @return $this
	 */
	public function withVite(callable $callback): static {
		try {
			$callback($this->make('vite'));
		} catch (Exception $exception) {}

		return $this;
	}

	/**
	 * Enable acf json loader
	 *
	 * @return $this
	 */
	public function withAcf(): static {
		add_action('acf/settings/load_json', function ($paths) {
			return array_merge($paths, [get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'acf-json']);
		});

		add_filter( 'acf/settings/save_json', function () {
			return get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'acf-json';
		}, 1 );
		return $this;
	}

	/**
	 * With customizations
	 *
	 * @param callable $callback
	 *
	 * @return $this
	 */
	public function withCustomizations(callable $callback): static {
		try {
			$customizations = $this->make('customizations');
			$callback($customizations);
			if (method_exists($customizations, 'booted')) {
				$customizations->booted();
			}
		} catch (Exception $exception) {
			error_log($exception->getMessage());
		}

		return $this;
	}

	/**
	 * Create theme application
	 *
	 * @param callable|null $callback
	 *
	 * @return void
	 * @throws Exception
	 */
	public function create(callable|null $callback = null): void {
		$this->make('blocks');
		if ($callback) {
			$callback($this);
		}
	}
}
