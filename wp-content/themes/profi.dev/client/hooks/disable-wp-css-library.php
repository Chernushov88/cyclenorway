<?php
add_action('init', function () {
    // Removes blocks directory that provides ability to install custom blocks from the wp blocks directory.
    remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );

    // Remove wp default css variables.
    remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');

    // Remove block library default css.
    add_action('wp_enqueue_scripts', function () {
        global $wp_styles;

        foreach ( $wp_styles->registered as $style ) {
            if (str_starts_with($style->handle, "wp-block")) {
                wp_dequeue_style($style->handle);
                wp_deregister_style($style->handle);
            }
        }
        wp_dequeue_style('classic-theme-styles');
    });
});