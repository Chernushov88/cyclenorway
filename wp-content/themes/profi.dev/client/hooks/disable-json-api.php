<?php declare(strict_types=1);
add_action('after_setup_theme', function () {
	if (is_admin()) {
		return;
	}

	remove_action('wp_head', 'rest_output_link_wp_head', 10);
	remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

	add_filter('json_enabled', '__return_false');
	add_filter('json_jsonp_enabled', '__return_false');
	add_filter('rest_jsonp_enabled', '__return_false');
});


add_filter('rest_authentication_errors', function ($access) {
	if (is_user_logged_in()) {
		return $access;
	}

	return new WP_Error('rest_disabled', __('The WordPress REST API has been disabled.', 'profidev-theme'), array('status' => rest_authorization_required_code()));
});
