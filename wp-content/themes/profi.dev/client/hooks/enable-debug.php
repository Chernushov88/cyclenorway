<?php declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

// Sets additional debug properties.
function profidev_debug_set()
{
    // Turn on error reporting.
    error_reporting(E_ALL);

    // Sets to display errors on screen. Use 0 to turn off.
    ini_set('display_errors', 1);

    // Sets to log errors. Use 0 (or omit) to not log errors.
    ini_set('log_errors', 1);

    // Sets a log file path you can access in the theme editor.
    ini_set('error_log', get_template_directory() . '/debug.txt');
}

// Allows to dump debug trace and catch what us causing redirect.
function profidev_debug_redirect()
{
    add_filter(
        'wp_redirect',
        function () {
            echo '<pre>';
            var_dump(debug_backtrace());
            exit();
        },
        9999
    );
}

// Prints out all hooks process went through during lyfecycle.
function profidev_get_all_do_action_hooks()
{
    add_action(
        'all',
        function ($tag) {
            static $hooks = [];

            // Only do_action / do_action_ref_array hooks.
            if (did_action($tag)) {
                $hooks[] = $tag;
            }
            if ($tag === 'shutdown') {
                echo '<pre>';
                print_r($hooks);
            }
        }
    );
}

// Prints out all hooks being used during lyfecycle and how much times they has been called.
function profidev_get_hooks_freq()
{
    add_action(
        'shutdown',
        function () {
            global $wp_actions;
            echo '<pre>';
            print_r($wp_actions);
        }
    );
}
