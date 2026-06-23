<?php declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

add_action('wp_enqueue_scripts', function () {
	if (!is_admin()) {
		wp_deregister_script('jquery-core');
		wp_deregister_script('jquery-migrate');
	}
});
