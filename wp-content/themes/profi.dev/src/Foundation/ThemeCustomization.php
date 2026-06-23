<?php declare(strict_types=1);

namespace ProfiDev\Foundation;

use ProfiDev\Contracts\Hook\HookActivation;
use WP_Customize_Color_Control;
use WP_Customize_Manager;
use WP_Theme_JSON_Data;

class ThemeCustomization {
	/**
	 * Registered colors
	 *
	 * @var array
	 */
	public array $colors = [];

	/**
	 * Registered fonts types
	 *
	 * @var array
	 */
	public array $fonts = [];

	/**
	 * List of allowed fonts
	 *
	 * @var array
	 */
	public array $allowed_fonts = [];

	/**
	 * List fo registered hooks
	 *
	 * @var Hook[]
	 */
	public array $hooks = [];

	/**
	 * List of registered parts
	 *
	 * @var array
	 */
	public array $parts = [];

	/**
	 * List of custom templates
	 *
	 * @var array
	 */
	public array $customTemplates = [];

	/**
	 * Create customizer
	 *
	 * @param Application $app
	 */
	public function __construct(public Application $app) {
		add_action( 'customize_register', [ $this, 'register' ] );
		add_filter( 'wp_theme_json_data_theme', [ $this, 'theme_json' ], PHP_INT_MIN );
	}

	/**
	 * Customize theme jon
	 *
	 * @param WP_Theme_JSON_Data $theme_json
	 * @return WP_Theme_JSON_Data
	 */
	public function theme_json( WP_Theme_JSON_Data $theme_json): WP_Theme_JSON_Data {
		$attributes = $theme_json->get_data();
		if (!array_key_exists('settings', $attributes)) {
			$attributes['settings'] = [];
		}
		if (!array_key_exists('color', $attributes['settings'])) {
			$attributes['settings']['color'] = [];
		}
		$attributes['settings']['color']['palette'] = array_map(function ($slug, $name) {
			return [
				'slug' => str_replace(['color_', '_'], ['', '-'], $slug),
				'name' => $name,
				'color' => get_theme_mod($slug, '#000000'),
			];
		}, array_keys($this->colors), $this->colors);

		if (!array_key_exists('typography', $attributes['settings'])) {
			$attributes['settings']['typography'] = [];
		}
		$attributes['settings']['typography']['fontFamilies'] = array_map(function ($slug, $name) {
			return [
				'slug' => str_replace(['font_', '_'], ['', '-'], $slug),
				'name' => $name,
				'fontFamily' => get_theme_mod($slug, 'Arial, sans-serif'),
			];
		}, array_keys($this->fonts), $this->fonts);

		$attributes['templateParts'] = $this->parts;
		$attributes['customTemplates'] = $this->customTemplates;

		return $theme_json->update_with($attributes);
	}

	/**
	 * Boot
	 *
	 * @return void
	 */
	public function booted():void {
		foreach ( $this->hooks as $hook ) {
			if ($hook->isActive()) {
				$hook_file = $this->app->getBasePath() . 'client/hooks/' . $hook->fileName . '.php';
				if ( file_exists( $hook_file ) && is_readable( $hook_file ) ) {
					require_once $hook_file;
				}
			}
		}
	}

	/**
	 * Register hooks
	 *
	 * @param Hook $hook
	 *
	 * @return $this
	 */
	public function registerHook(Hook $hook): static {
		$this->hooks[] = $hook;

		return $this;
	}

	/**
	 * Register part
	 *
	 * @param string $area
	 * @param string $name
	 * @param string $title
	 *
	 * @return $this
	 */
	public function addPart(string $area, string $name, string $title): static {
		$this->parts[] = [
			'area' => $area,
			'name' => $name,
			'title' => $title,
		];

		return $this;
	}

	/**
	 * Add custom template
	 *
	 * @param string $name
	 * @param string $title
	 * @param array $postTypes
	 *
	 * @return $this
	 */
	public function addCustomTemplate(string $name, string $title, array $postTypes = []): static {
		$this->customTemplates[] = [
			'name' => $name,
			'title' => $title,
			'postTypes' => empty($postTypes) ? get_post_types(['public' => true], 'names') : array_filter($postTypes, function ($postType) {
				return is_string($postType);
			}),
		];

		return $this;
	}

	/**
	 * Add color
	 *
	 * @param string $variable
	 * @param string $label
	 *
	 * @return $this
	 */
	public function addColor( string $variable, string $label ): static {
		$this->colors['color_' . $variable] = $label;

		return $this;
	}

	/**
	 * Add font
	 *
	 * @param string $variable
	 * @param string $label
	 *
	 * @return $this
	 */
	public function addFont( string $variable, string $label ): static {
		$this->fonts['font_' . $variable] = $label;

		return $this;
	}

	/**
	 * Set allowed fonts
	 *
	 * @param array $fonts
	 *
	 * @return $this
	 */
	public function setAllowedFonts( array $fonts ): static {
		$this->allowed_fonts = $fonts;

		return $this;
	}

	/**
	 * Register theme settings
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	public function register(WP_Customize_Manager $wp_customize): void {
		$wp_customize->add_section('colors_profidev', [
			'title'      => __( 'Colors', 'profidev-theme' ),
			'priority'   => 30
		]);

		$wp_customize->add_section('typography_profidev', [
			'title'      => __('Typography', 'profidev-theme'),
			'priority'   => 30
		]);

		$wp_customize->add_section('hooks_profidev', [
			'title'      => __('Hooks', 'profidev-theme'),
			'priority'   => 31
		]);

		foreach ($this->colors as $variable => $label) {
			$wp_customize->add_setting($variable, [
				'default' => '#000000',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport' => 'refresh'
			]);

			$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $variable, [
				'label' => $label,
				'section' => 'colors_profidev',
				'settings' => $variable
			]));
		}

		foreach ($this->fonts as $variable => $label) {
			$wp_customize->add_setting($variable, [
				'default'           => 'Arial, sans-serif',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh'
			]);

			$wp_customize->add_control($variable, [
				'label'    => $label,
				'section'  => 'typography_profidev',
				'type'     => 'select',
				'choices'  => $this->allowed_fonts
			]);
		}

		foreach ($this->hooks as $hook) {
			if ($hook->showInCustomizer) {
				$wp_customize->add_setting( $hook->fileName, [
					'default'           => $hook->defaultValue->value,
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'refresh'
				] );

				$wp_customize->add_control( $hook->fileName, [
					'label'   => $hook->label,
					'section' => 'hooks_profidev',
					'type'    => 'select',
					'choices' => HookActivation::options()
				] );
			}
		}

		$wp_customize->remove_section('custom_css');
	}
}
