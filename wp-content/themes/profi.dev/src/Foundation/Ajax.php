<?php declare(strict_types=1);

namespace ProfiDev\Foundation;

abstract class Ajax {
	/**
	 * Is public ajax
	 *
	 * @var bool
	 */
	public static bool $is_public = true;

	/**
	 * Is private ajax
	 *
	 * @var bool
	 */
	public static bool $is_private = true;

	/**
	 * Enable nonce validation
	 *
	 * @var bool
	 */
	public static bool $is_nonce_enabled = false;

	/**
	 * Action name
	 *
	 * @var string|null
	 */
	public ?string $action = null;

	/**
	 * Execution time calculation
	 *
	 * @var float
	 */
	public float $start_execution_time = 0.0;

	/**
	 * Initialized ajax instances
	 *
	 * @var array
	 */
	private static array $instances = [];

	/**
	 * Handle requests method
	 *
	 * @return void
	 */
	abstract public function handle(): void;

	/**
	 * Get action name
	 *
	 * @param string $prefix
	 *
	 * @return string
	 */
	public function getActionName(string $prefix = 'wp_ajax'): string {
		return sprintf( '%s_%s', $prefix, $this->action ?? basename( basename( str_replace('\\', '/', get_called_class()) ) ) );
	}

	/**
	 * Initialize and bind instances
	 *
	 * @return static
	 */
	public static function init(): static {
		$cls = static::class;
		if (!isset(self::$instances[$cls])) {
			$instance = new static();
			if ($instance::$is_private) {
				add_action( $instance->getActionName(), [ $instance, 'execute' ] );
			}
			if ($instance::$is_public) {
				add_action( $instance->getActionName( 'wp_ajax_nopriv' ), [ $instance, 'execute' ] );
			}
			self::$instances[$cls] = $instance;
		}

		return self::$instances[$cls];
	}

	/**
	 * Execute action
	 *
	 * @return void
	 */
	public function execute(): void {
		header('Content-Type: application/json; charset=utf-8');
		$this->start_execution_time = microtime(true);
		if (self::$is_nonce_enabled && !wp_verify_nonce($_REQUEST['nonce'] ?? '', 'profidev-ajax-nonce')) {
			$this->error(['ncn' => self::$is_private]);
			$this->error(__('For security reasons, the submitted request could not be processed. Please refresh the page and try again.', 'profidev-theme'));
		} else {
			$this->handle();
		}
		exit();
	}

	/**
	 * Success response
	 *
	 * @param mixed|null $data
	 * @param int $status
	 *
	 * @return void
	 */
	public function success(mixed $data = null, int $status = 200): void {
		http_response_code($status);
		echo json_encode([
			'content' => $data,
			'response' => [
				'status' => 'success',
				'execution_time' => round(microtime(true) - $this->start_execution_time, 6),
			]
		]);
	}

	/**
	 * Bad request
	 *
	 * @param mixed|null $data
	 * @param int $status
	 *
	 * @return void
	 */
	public function error(mixed $data = null, int $status = 400): void {
		http_response_code($status);
		echo json_encode([
			'content' => $data,
			'response' => [
				'status' => 'error',
				'execution_time' => round(microtime(true) - $this->start_execution_time, 6),
			]
		]);
	}
}
