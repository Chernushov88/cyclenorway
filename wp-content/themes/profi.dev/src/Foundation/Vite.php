<?php declare(strict_types=1);

namespace ProfiDev\Foundation;

class Vite {
	/**
	 * The path to the build directory.
	 *
	 * @var string
	 */
	protected string $buildDirectory = 'build';

	/**
	 * The name of the manifest file.
	 *
	 * @var string
	 */
	protected string $manifestFilename = 'manifest.json';

	/**
	 * Current env run in production mode
	 *
	 * @var bool
	 */
	private bool $is_production;

	/**
	 * Registered style handles to files
	 *
	 * @var array
	 */
	public array $style_handles = [];

	/**
	 * Script handles
	 *
	 * @var array
	 */
	public array $script_handles = [];

	/**
	 * Registered styles in site
	 *
	 * @var array|array[]
	 */
	public array $enqueue_styles = [
		'global' => [],
		'admin' => [],
		'site' => []
	];

	/**
	 * Registered scripts in site
	 *
	 * @var array|array[]
	 */
	public array $enqueue_scripts = [
		'global' => [],
		'admin' => [],
		'site' => []
	];

	/**
	 * Create vite instance
	 *
	 * @param Application $app
	 */
	public function __construct(public Application $app) {
		$this->is_production = $this->app->env('SITE_ENV', 'production') === 'production';

		add_action('style_loader_tag', [$this, 'styleLoaderTag'], 10, 2);
		add_action('script_loader_tag', [$this, 'scriptLoaderTag'], 10, 3);
		add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
		add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
		add_action('enqueue_block_assets', [$this, 'enqueueScripts']);
		add_action('enqueue_block_editor_assets', [$this, 'enqueueScripts']);
	}

	/**
	 * Change style loader
	 * @param $html
	 * @param $handle
	 *
	 * @return mixed|string
	 */
	public function styleLoaderTag($html, $handle): mixed {
		if (!$this->is_production) {
			if (array_key_exists($handle, $this->style_handles)) {
				return sprintf( '<script type="module">import("%s");</script>', $this->getAssetFilePath( $this->style_handles[ $handle ] ) );
			}
		}
		return $html;
	}

	/**
	 * Change scripts loader
	 *
	 * @param $tag
	 * @param $handle
	 * @param $src
	 *
	 * @return mixed
	 */
	public function scriptLoaderTag($tag, $handle, $src): mixed {
		if (array_key_exists($handle, $this->script_handles)) {
			return str_replace( '<script ', '<script type="module" ', $tag );
		}
		return $tag;
	}

	/**
	 * Enqueue theme assets
	 *
	 * @return void
	 */
	public function enqueueScripts(): void {
		foreach ($this->enqueue_styles['global'] as $handle => $meta) {
			wp_enqueue_style($handle);
		}
		foreach ($this->enqueue_scripts['global'] as $handle => $meta) {
			wp_register_script(...$meta);
			wp_enqueue_script($handle);
		}
		if (is_admin()) {
			foreach ( $this->enqueue_styles['admin'] as $handle => $meta ) {
				wp_enqueue_style( $handle );
			}
			foreach ( $this->enqueue_scripts['admin'] as $handle => $meta ) {
				wp_enqueue_script( $handle );
			}
		} else {
			foreach ( $this->enqueue_styles['site'] as $handle => $meta ) {
				wp_enqueue_style( $handle );
			}
			foreach ( $this->enqueue_scripts['site'] as $handle => $meta ) {
				wp_enqueue_script( $handle );
			}
		}
	}

	/**
	 * @param string $path
	 *
	 * @return string
	 */
	public function getAssetPath(string $path): string {
		if ($this->is_production && $this->getManifest($path)) {
			return $this->getAssetFilePath($this->getManifest($path)['file']);
		}

		return $this->getAssetFilePath($path);
	}

	/**
	 * @param string $handle
	 * @param string $src
	 * @param string[] $deps
	 * @param string|bool|null $ver
	 * @param array|bool $args
	 *
	 * @return $this
	 */
	public function addScript($handle, $src, $deps = array(), $ver = false, $args = array()): static {
		add_action('init', function () use ($handle, $src, $deps, $ver, $args) {
			wp_register_script($handle, $this->getAssetPath($src), $deps, $ver, $args);
		});
		$this->enqueue_scripts['global'][$handle] = [
			$handle, $this->getAssetPath($src), $deps, $ver, $args
		];
		$this->script_handles[$handle] = [
			$handle, $this->getAssetPath($src), $deps, $ver, $args
		];

		return $this;
	}

	/**
	 * Add site scripts
	 *
	 * @param string $handle
	 * @param string $src
	 * @param string[] $deps
	 * @param string|bool|null $ver
	 * @param array|bool $args
	 *
	 * @return $this
	 */
	public function addSiteScript($handle, $src, $deps = array(), $ver = false, $args = array()): static {
		add_action('init', function () use ($handle, $src, $deps, $ver, $args) {
			wp_register_script($handle, $this->getAssetPath($src), $deps, $ver, $args);
		});
		$this->enqueue_scripts['site'][$handle] = [
			$handle, $this->getAssetPath($src), $deps, $ver, $args
		];
		$this->script_handles[$handle] = [
			$handle, $this->getAssetPath($src), $deps, $ver, $args
		];

		return $this;
	}

	/**
	 * Add admin scripts
	 *
	 * @param string $handle
	 * @param string $src
	 * @param string[] $deps
	 * @param string|bool|null $ver
	 * @param array|bool $args
	 *
	 * @return $this
	 */
	public function addAdminScript($handle, $src, $deps = array(), $ver = false, $args = array()): static {
		add_action('init', function () use ($handle, $src, $deps, $ver, $args) {
			wp_register_script($handle, $this->getAssetPath($src), $deps, $ver, $args);
		});
		$this->enqueue_scripts['admin'][$handle] = [
			$handle, $this->getAssetPath($src), $deps, $ver, $args
		];
		$this->script_handles[$handle] = [
			$handle, $this->getAssetPath($src), $deps, $ver, $args
		];

		return $this;
	}

	/**
	 * @param string $handle
	 * @param string $object_name
	 * @param array $l10n
	 *
	 * @return $this
	 */
	public function addLocalizeScript( string $handle, string $object_name, array $l10n ): static {
		add_action( 'init', function () use ($handle, $object_name, $l10n) {
			wp_localize_script($handle, $object_name, $l10n);
		} );

		return $this;
	}

	/**
	 * Add global styles
	 *
	 * @param string $handle
	 * @param string $path
	 * @param string[] $deps
	 * @param string|bool|null $ver
	 * @param string $media
	 *
	 * @return $this
	 */
	public function addStyle($handle, $path, $deps = array(), $ver = false, $media = 'all'): static {
		add_action('init', function () use ($handle, $path, $deps, $ver, $media) {
			wp_register_style($handle, $this->getAssetPath($path), $deps, $ver, $media);
		});
		$this->enqueue_styles['global'][$handle] = [
			$handle, $this->getAssetPath($path), $deps, $ver, $media
		];
		$this->style_handles[$handle] = $path;

		return $this;
	}

	/**
	 * Add site styles
	 *
	 * @param string $handle
	 * @param string $path
	 * @param string[] $deps
	 * @param string|bool|null $ver
	 * @param string $media
	 *
	 * @return $this
	 */
	public function addSiteStyle($handle, $path, $deps = array(), $ver = false, $media = 'all'): static {
		add_action('init', function () use ($handle, $path, $deps, $ver, $media) {
			wp_register_style($handle, $this->getAssetPath($path), $deps, $ver, $media);
		});
		$this->enqueue_styles['site'][$handle] = [
			$handle, $this->getAssetPath($path), $deps, $ver, $media
		];
		$this->style_handles[$handle] = $path;

		return $this;
	}

	/**
	 * Add admin styles
	 *
	 * @param string $handle
	 * @param string $path
	 * @param string[] $deps
	 * @param string|bool|null $ver
	 * @param string $media
	 *
	 * @return $this
	 */
	public function addAdminStyle($handle, $path, $deps = array(), $ver = false, $media = 'all'): static {
		add_action('init', function () use ($handle, $path, $deps, $ver, $media) {
			wp_register_style($handle, $this->getAssetPath($path), $deps, $ver, $media);
		});
		$this->enqueue_styles['admin'][$handle] = [
			$handle, $this->getAssetPath($path), $deps, $ver, $media
		];
		$this->style_handles[$handle] = $path;

		return $this;
	}

	/**
	 * Get build directory
	 *
	 * @return string
	 */
	public function getBuildDirectory(): string {
		return $this->app->getBasePath() . $this->buildDirectory . DIRECTORY_SEPARATOR;
	}

	/**
	 * Get manifest
	 *
	 * @return mixed|null
	 */
	public function getManifest($path = null): mixed {
		static $manifest;
		if (!isset($manifest)) {
			$file = $this->getBuildDirectory() . '.vite/' . $this->manifestFilename;
			if (is_file($file)) {
				$manifest = json_decode(file_get_contents($file), true);
			}
		}

		if ($path && is_array($manifest)) {
			if (array_key_exists($path, $manifest)) {
				return $manifest[ $path ];
			}

			return null;
		}

		return $manifest;
	}

	/**
	 * Register block assets
	 * @param $metadata
	 * @param $block_file
	 *
	 * @return void
	 */
	public function registerAssets($metadata, $block_file): void {
		static $script_fields = array(
			'editorScript' => 'editor_script_handles',
			'script'       => 'script_handles',
			'viewScript'   => 'view_script_handles',
		);

		foreach ( $script_fields as $metadata_field_name => $settings_field_name ) {
			if ( ! empty( $settings[ $metadata_field_name ] ) ) {
				$metadata[ $metadata_field_name ] = $settings[ $metadata_field_name ];
			}
			if ( ! empty( $metadata[ $metadata_field_name ] ) ) {
				$scripts           = $metadata[ $metadata_field_name ];
				if ( is_array( $scripts ) ) {
					for ( $index = 0; $index < count( $scripts ); $index++ ) {
						$this->registerScript( $block_file, $metadata, $metadata_field_name, $index );
					}
				} else {
					$this->registerScript( $block_file, $metadata, $metadata_field_name );
				}
			}
		}

		static $style_fields = array(
			'editorStyle' => 'editor_style_handles',
			'style'       => 'style_handles',
			'viewStyle'   => 'view_style_handles',
		);

		foreach ( $style_fields as $metadata_field_name => $settings_field_name ) {
			if ( ! empty( $settings[ $metadata_field_name ] ) ) {
				$metadata[ $metadata_field_name ] = $settings[ $metadata_field_name ];
			}
			if ( ! empty( $metadata[ $metadata_field_name ] ) ) {
				$styles = $metadata[ $metadata_field_name ];
				if ( is_array( $styles ) ) {
					for ( $index = 0; $index < count( $styles ); $index++ ) {
						$this->registerStyle( $block_file, $metadata, $metadata_field_name, $index );
					}
				} else {
					$this->registerStyle( $block_file, $metadata, $metadata_field_name );
				}
			}
		}
	}

	/**
	 * Register scripts
	 *
	 * @param string $block_file
	 * @param array $metadata
	 * @param string $field_name
	 * @param integer $index
	 *
	 * @return bool
	 */
	public function registerScript( string $block_file, array $metadata, string $field_name, int $index = 0): bool {
		if ( empty( $metadata[ $field_name ] ) ) {
			return false;
		}

		$script_handle_or_path = $metadata[ $field_name ];
		if ( is_array( $script_handle_or_path ) ) {
			if ( empty( $script_handle_or_path[ $index ] ) ) {
				return false;
			}
			$script_handle_or_path = $script_handle_or_path[ $index ];
		}

		$script_path = remove_block_asset_path_prefix( $script_handle_or_path );
		if ( $script_handle_or_path === $script_path ) {
			return false;
		}

		$path                  = dirname( $block_file );
		$script_asset_raw_path = $path . '/' . substr_replace( $script_path, '.asset.php', - strlen( '.js' ) );
		$script_asset_path     = wp_normalize_path( realpath( $script_asset_raw_path ) );

		$script_asset          = ! empty( $script_asset_path ) ? require $script_asset_path : array();
		$script_handle         = $script_asset['handle'] ?? generate_block_asset_handle( $metadata['name'], $field_name, $index );

		$script_path_norm      = str_replace($this->app->getBasePath(), '', wp_normalize_path( realpath( $path . '/' . $script_path ) ));
		$script_dependencies   = $script_asset['dependencies'] ?? array();
		$block_version         = $metadata['version'] ?? false;
		$script_version        = $script_asset['version'] ?? $block_version;
		$script_args           = array();

		$this->script_handles[$script_handle] = $script_path_norm;
		if ($this->is_production) {
			if (is_array($this->getManifest()) &&
			    array_key_exists($script_path_norm, $this->getManifest()) &&
			    array_key_exists('file', $this->getManifest($script_path_norm))) {
				if (array_key_exists('imports', $this->getManifest($script_path_norm))) {
					foreach ($this->getManifest($script_path_norm)['imports'] as $import) {
						if ($this->getManifest($import)) {
							$handle = array_search($import, $this->script_handles);
							if ( $handle !== false ) {
								$script_dependencies[] = $handle;
							} else {
								wp_register_script(
									$import,
									$this->getAssetFilePath( $this->getManifest( $import )['file'] ),
									[]
								);
								$this->script_handles[ $import ] = $this->getManifest( $import )['file'];
								$script_dependencies[] = $import;
							}
						}
					}
				}

				wp_register_script(
					$script_handle,
					$this->getAssetFilePath($this->getManifest($script_path_norm)['file']),
					$script_dependencies,
					$script_version,
					$script_args
				);
			}
		} else {
			wp_register_script(
				$script_handle,
				$this->getAssetFilePath($script_path_norm),
				$script_dependencies,
				$script_version,
				$script_args
			);
		}
		return false;
	}

	/**
	 * Register styles
	 *
	 * @param string $block_file
	 * @param array $metadata
	 * @param string $field_name
	 * @param integer $index
	 *
	 * @return bool
	 */
	public function registerStyle( string $block_file, array $metadata, string $field_name, int $index = 0): bool {
		if ( empty( $metadata[ $field_name ] ) ) {
			return false;
		}

		$style_handle = $metadata[ $field_name ];
		if ( is_array( $style_handle ) ) {
			if ( empty( $style_handle[ $index ] ) ) {
				return false;
			}
			$style_handle = $style_handle[ $index ];
		}

		$style_handle_name  = generate_block_asset_handle( $metadata['name'], $field_name, $index );
		$style_path         = remove_block_asset_path_prefix( $style_handle );

		$style_path_norm = str_replace($this->app->getBasePath(), '', wp_normalize_path( realpath( dirname( $block_file ) . '/' . $style_path ) ));

		$this->style_handles[$style_handle_name] = $style_path_norm;
		if ($this->is_production) {
			if (is_array($this->getManifest()) &&
			    array_key_exists( $style_path_norm, $this->getManifest() ) &&
			    array_key_exists( 'file', $this->getManifest()[ $style_path_norm ] ) ) {
				wp_register_style(
					$style_handle_name,
					$this->getAssetFilePath( $this->getManifest()[ $style_path_norm ]['file'] ),
					array(),
					$metadata['version'] ?? false
				);
				return true;
			}
		}
		else {
			wp_register_style(
				$style_handle_name,
				$this->getAssetFilePath( $style_path_norm ),
				array(),
				$metadata['version'] ?? false
			);
			return true;
		}

		return false;
	}

	/**
	 * Get asset file path
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	public function getAssetFilePath( string $path ): string {
		if (!$this->is_production) {
			static $vite_path = null;
			if (is_null( $vite_path )) {
				$vite_path = str_replace( ABSPATH, '', $this->app->getBasePath());
			}
			return sprintf(
				'https://%s:%s/%s%s',
				$this->app->env('SITE_URL'),
				$this->app->env('DOCKER_VITE_PORT'),
				$vite_path,
				$path
			);
		}

		return get_theme_file_uri($this->buildDirectory . '/' . $path);
	}
}
