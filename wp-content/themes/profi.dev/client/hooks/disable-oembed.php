<?php declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

add_action(
    'init',
    function () {
        remove_action('rest_api_init', 'wp_oembed_register_route');
        add_filter('embed_oembed_discover', '__return_false');
        remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
        remove_filter('pre_oembed_result', 'wp_filter_pre_oembed_result', 10);

        add_filter(
            'rewrite_rules_array',
            function ($plugins) {
                return array_diff($plugins, [ 'wpembed' ]);
            }
        );

        add_filter(
            'tiny_mce_plugins',
            function ($rules) {
                foreach ($rules as $rule => $rewrite) {
                    if (strpos($rewrite, 'embed=true') !== false) {
                        unset($rules[ $rule ]);
                    }
                }
                return $rules;
            }
        );
    },
    9999
);

add_action(
    'wp_footer',
    function () {
        wp_dequeue_script('wp-embed');
    }
);
