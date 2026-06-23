<?php declare(strict_types=1);

add_action('template_redirect', function() {
    if (is_author()) {
        wp_redirect(get_option('siteurl'), 301);
        die();
    }
});
