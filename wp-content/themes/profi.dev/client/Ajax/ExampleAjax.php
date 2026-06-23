<?php

namespace ProfiDev\Client\Ajax;

use ProfiDev\Foundation\Ajax;

class ExampleAjax extends Ajax {
	/**
	 * Is private ajax wp_ajax_{action}
	 *
	 * @var bool
	 */
	public static bool $is_private = true;

	/**
	 * Is public ajax wp_ajax_nopriv_{action}
	 *
	 * @var bool
	 */
	public static bool $is_public = true;

	/**
	 * Action name if set use his, if not use ClassName in this example is "ExampleAjax"
	 *
	 * @var string|null
	 */
	public ?string $action = 'example_ajax';

	/**
	 * Handle
	 *
	 * @return void
	 */
	public function handle(): void {
		$this->success([
			'SOMETHING THAT NEED RETURN'
		]);
	}
}