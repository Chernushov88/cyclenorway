<?php declare(strict_types=1);

use ProfiDev\Contracts\Hook\HookActivation;
use ProfiDev\Foundation\Application;
use ProfiDev\Foundation\Hook;
use ProfiDev\Foundation\ThemeCustomization;
use ProfiDev\Foundation\Vite;

if (!function_exists('make_application')) {
	function make_application()
	{
		static $instance;
		if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
			return null;
		}
		if (null === $instance) {
			require_once( __DIR__ . '/vendor/autoload.php' );
			$instance = Application::configure(get_theme_file_path())->withVite(function ( Vite $vite ) {
				$vite
					->addStyle('profidev-constants', 'assets/css/_constants.scss', [], '1.0')
					->addAdminStyle('profidev-constants-editor', 'assets/css/_constants_editor.scss', [], null)
					->addAdminStyle('profidev-admin', 'assets/css/admin.scss', [], null)
					->addAdminStyle('profidev-admin-main', 'assets/css/style-editor.scss', [], null)
					->addStyle('profidev-main', 'assets/css/style.scss', [], null)
					->addScript('profidev-main', 'assets/js/scripts.js')
					->addStyle('profidev-blocks', 'assets/css/blocks.scss', ['profidev-main'], null)
					->addScript('profidev-blocks', 'assets/js/blocks.js', ['profidev-main'], null)
					->addLocalizeScript('profidev-main', 'ProfiDevThemeVars', [
						'url' => admin_url('admin-ajax.php'),
						'nonce' => wp_create_nonce('profidev-ajax-nonce'),
					]);
			})->withAcf()->withCustomizations(function ( ThemeCustomization $customizations) {
				$customizations
					->registerHook(new Hook('fix-deepl-formality-loop', 'Fix Deepl Formality Loop'))
					->registerHook(new Hook('cleanup', 'Cleanup'))
					->registerHook(new Hook('disable-authors-archive', 'Disable authors archive'))
					->registerHook(new Hook('disable-auto-update', 'Disable auto update'))
					->registerHook(new Hook('disable-emoji', 'Disable emoji'))
//					->registerHook(new Hook('disable-jquery', 'Disable jQuery'))
					->registerHook(new Hook('disable-wp-css-library', 'Disable wp css library', HookActivation::DISABLED, true))
					->registerHook(new Hook('enable-cls-reporter', 'Enable CLS reporter', HookActivation::ENABLED_DEV, true))
					->registerHook(new Hook('enable-debug', 'Enable debug', HookActivation::DISABLED, true))
					->registerHook(new Hook('enable-smtp', 'Enable smtp', HookActivation::ENABLED, true))
					->registerHook(new Hook('enable-woocommerce-gutenberg', 'Enable woocommerce gutenberg', HookActivation::DISABLED, true));
				$customizations
					->addColor('color-1', 'First Color')
					->addColor('color-2', 'Second Color')
					->addColor('color-3', 'Third Color')
					->addColor('color-4', 'Fourth Color')
					->addColor('color-5', 'Fifth Color')
					->addColor('color-6', 'Sixth Color')
					->addColor('color-7', 'Seventh Color')
					->addColor('color-8', 'Eighth Color')
					->addColor('color-9', 'Ninth Color')
					->addColor('color-10', 'Ten Color')
					->addColor('color-11', 'Eleven Color')
					->addColor('color-12', 'Twelve Color')
					->addColor('color-13', 'Thirteen Color')
					->addColor('white', 'White Color')
					->addColor('black', 'Black Color');
				$customizations
					->addFont('primary', 'Primary Font')
					->addFont('secondary', 'Secondary Font');
				$customizations
					->addCustomTemplate('single-overview', 'Overview', ['routes'])
					->addCustomTemplate('single-overview-paywall', 'Overview Paywall', ['routes'])
					->addCustomTemplate('full-story', 'Full Story', ['full-story'])
					->addCustomTemplate('full-story-paywall', 'Full Story Paywall', ['full-story'])
					->addCustomTemplate('single-paywall', 'Paywall', ['post']);
				$customizations
					->setAllowedFonts([
						'Inter, sans-serif' => 'Inter',
						'ABC Marist, sans-serif' => 'ABC Marist',
						'Arial, sans-serif' => 'Arial',
						'"Helvetica Neue", sans-serif' => 'Helvetica Neue',
						'Georgia, serif' => 'Georgia',
						'"Times New Roman", serif' => 'Times New Roman',
					]);
			});

			try {
				$instance->create(function () {
					require_once( __DIR__ . '/client/helpers.php' );
//					var_dump(Application::getInstance()->make('blocks')->block_list);
					add_filter( 'mepr_block_protection_exclude', function ( $blocks ) {
						return array_merge($blocks, Application::getInstance()->make('blocks')->block_list);
					} );
				});
			} catch ( Exception $e ) {
				error_log( $e->getMessage() );
			}
		}

		return $instance;
	}
}

make_application();

add_action('init', function () {
	load_theme_textdomain( 'profidev-theme', get_template_directory() . '/languages' );
});

add_action('template_redirect', function () {
	if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
		wp_die(
			__('Required dependencies are missing. Please run "composer install" in the theme directory.', 'profidev-theme'),
			__('Missing Dependencies', 'profidev-theme'),
			array('response' => 403)
		);
	}
});

if (!function_exists('get_fields_or_template')) {
	function get_fields_or_template( $post_id, $is_preview = false, $group_name = 'default' ) {
		if ( ! $post_id && $is_preview ) {
			return get_field( $group_name, 'option' ) ?? [];
		}

		return get_fields( $post_id ) ?? [];
	}
}

if (!function_exists('get_value_or_default')) {
	function get_value_or_default( $value, mixed $default = "" ) {
		if ( gettype( $value ) === gettype( $default ) ) {
			return $value;
		}

		return $default;
	}
}

if (!function_exists('can_see_protected_content')) {
	function can_see_protected_content($rules) {
		if (empty($rules)) {
			return true;
		}

		if (!is_array($rules)) {
			return true;
		}

		$rules = array_filter($rules, function ($rule) {
			return is_numeric($rule);
		});

		return current_user_can('mepr-active','rules:'.join(',', $rules));
	}
}

add_filter( 'pre_render_block', function ($pre_render, $parsed_block) {
	if ( is_admin() && function_exists( 'get_current_screen' ) ) {
		return $pre_render;
	}

	$temp_block_id = uniqid();
	acf_setup_meta( $parsed_block['attrs']['data'] ?? [], $temp_block_id, true );
	$formatted_fields = get_fields();
	acf_reset_meta( $temp_block_id );
	if (!is_array($formatted_fields)) {
		return $pre_render;
	}


	$data = $formatted_fields; // $parsed_block['attrs']['data'];
	$mepr_rule_id = !empty($data['mepr_rule_id']) ? $data['mepr_rule_id'] : false;
	$mepr_if_allowed = !empty($data['mepr_if_allowed']) ? $data['mepr_if_allowed'] : 'show';
	$mepr_unauthorized_access = !empty($data['mepr_unauthorized_access']) ? $data['mepr_unauthorized_access'] : 'hide';

	$mepr_title = !empty($data['mepr_title']) ? $data['mepr_title'] : '';
	$mepr_content = !empty($data['mepr_content']) ? $data['mepr_content'] : '';
	$mepr_background_image = !empty($data['mepr_background_image']) ? wp_get_attachment_image($data['mepr_background_image'], 'full', false, ['bg-image']) : '';

	if (empty($mepr_rule_id)) {
		return $pre_render;
	}

	$hasAccess = current_user_can( 'mepr-active', 'rule:' . $mepr_rule_id );
	if ($hasAccess) {
		if ( $mepr_if_allowed === 'show' ) {
			return $pre_render;
		} else if ( $mepr_if_allowed === 'hide' ) {
			return '';
		}
	} else {
		if ($mepr_unauthorized_access === 'show') {
			return $pre_render;
		} else if ( $mepr_unauthorized_access === 'hide' ) {
			return '';
		} else if ( $mepr_unauthorized_access === 'display_message' ) {
			ob_start();
			get_template_part('template-parts/paywall', 'protected', [
				'title' => $mepr_title,
				'content' => $mepr_content,
				'background_image' => $mepr_background_image,
			]);
			return ob_get_clean();
		}
	}

	return $pre_render;
}, 10, 2 );

//add_filter( 'mepr_block_protection_enabled', function ($value) {
//	return false;
//} );

add_filter( 'trp_flags_path', 'custom_trp_flags_path', 10, 2 );

function custom_trp_flags_path( $original_flags_path, $language_code ) {

    if ( $language_code === 'en_US' ) {
        return get_stylesheet_directory_uri() . '/assets/img/en_GB.svg';
    }

    return $original_flags_path;
}

add_filter('comment_form_default_fields', function ($fields) {
    if (isset($fields['url'])) {
        unset($fields['url']);
    }
    return $fields;
} );

add_filter('mepr_countries', function($countries, $prioritize_my_country) {
	return array(
		'AU' => 'Australia',
		'AT' => 'Austria',
		'BE' => 'Belgium',
		'BG' => 'Bulgaria',
		'CA' => 'Canada',
		'HR' => 'Croatia',
		'CY' => 'Cyprus',
		'CZ' => 'Czech Republic',
		'DK' => 'Denmark',
		'EE' => 'Estonia',
		'FI' => 'Finland',
		'FR' => 'France',
		'DE' => 'Germany',
		'GR' => 'Greece',
		'HK' => 'Hong Kong',
		'HU' => 'Hungary',
		'IS' => 'Iceland',
		'IE' => 'Republic of Ireland',
		'IT' => 'Italy',
		'JP' => 'Japan',
		'LV' => 'Latvia',
		'LI' => 'Liechtenstein',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourg',
		'MT' => 'Malta',
		'NL' => 'Netherlands',
		'NZ' => 'New Zealand',
		'NO' => 'Norway',
		'PL' => 'Poland',
		'PT' => 'Portugal',
		'RO' => 'Romania',
		'SG' => 'Singapore',
		'SK' => 'Slovakia',
		'SI' => 'Slovenia',
		'ZA' => 'South Africa',
		'ES' => 'Spain',
		'SE' => 'Sweden',
		'CH' => 'Switzerland',
		'AE' => 'United Arab Emirates',
		'GB' => 'United Kingdom',
		'US' => 'United States'
	);
}, 10, 2);
