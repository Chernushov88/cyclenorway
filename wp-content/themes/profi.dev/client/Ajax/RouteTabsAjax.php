<?php

namespace ProfiDev\Client\Ajax;

use ProfiDev\Foundation\Ajax;
use WP_Term;

class RouteTabsAjax extends Ajax {
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
	 * Disable nonce validation because wp rocket dont work with that
	 *
	 * @var bool
	 */
	public static bool $is_nonce_enabled = true;

	/**
	 * Action name if set use his, if not use ClassName in this example is "ExampleAjax"
	 *
	 * @var string|null
	 */
	public ?string $action = 'route_tabs_ajax';

	public function handle(): void {
		$term_slug = !empty($_REQUEST['term']) ? sanitize_text_field($_REQUEST['term']) : null;

		$tax = null;
		if ($term_slug && $term_slug !== 'all') {
			$tax = get_term_by('slug', $term_slug, 'route_tag');
		}

		$category = null;
		if (!empty($_REQUEST['category']) && !empty($_REQUEST['category_taxonomy'])) {
			$category = get_term_by('slug', $_REQUEST['category'], $_REQUEST['category_taxonomy']);
			if (empty($category)) {
				$category = null;
			}
		}

		$result = get_route_tabs_content('ajax-request', $tax, $category);

		if (!empty($result)) {
			$this->success(trim($result));
		} else {
			$this->error('No content generated.');
		}
	}
}
