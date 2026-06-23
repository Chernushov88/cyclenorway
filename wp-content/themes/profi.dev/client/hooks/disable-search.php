<?php declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

add_action(
    'parse_query',
    function ($query) {
        if (!is_search() || is_admin()) {
            return;
        }
        $query->is_search       = false;
        $query->query_vars['s'] = false;
        $query->query['s']      = false;
        $query->is_404          = true;
    }
);

add_filter('get_search_form', '__return_false');

add_action(
    'widgets_init',
    function () {
        unregister_widget('WP_Widget_Search');
    }
);
