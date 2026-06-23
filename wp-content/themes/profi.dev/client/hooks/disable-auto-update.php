<?php declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

if (!defined('WP_AUTO_UPDATE_CORE'))
	define('WP_AUTO_UPDATE_CORE', false);
if (!defined('AUTOMATIC_UPDATER_DISABLED'))
	define('AUTOMATIC_UPDATER_DISABLED', false);
add_filter('auto_update_plugin', '__return_false');
add_filter('auto_update_theme', '__return_false');
