<?php declare(strict_types=1);
use ProfiDev\Client\Walkers\HeaderMenu;
use ProfiDev\Client\Walkers\FooterMenu;

/**
 * Here you need to describe your own functions, calls, etc.
 * that are necessary for the client's website to work.
 */

\ProfiDev\Client\Ajax\ExampleAjax::init();
\ProfiDev\Client\Ajax\RouteTabsAjax::init();

add_filter('after_setup_theme', function () {
	add_theme_support('title-tag');
	add_theme_support('menus');
	add_theme_support('html5', ['script', 'style']);
	add_theme_support('custom-logo');
	add_theme_support('post-thumbnails');
	add_theme_support( 'wp-block-styles' );
  add_theme_support('editor-styles');
	add_editor_style( 'style-editor.css' );
});
add_theme_support( 'block-template-parts' );

add_filter('image_size_names_choose', function ($sizes) {
	return array_merge($sizes, array(
		'medium-width' => __('Medium Width'),
		'medium-height' => __('Medium Height'),
		'medium-something' => __('Medium Something'),
	));
});

add_filter('wp_theme_json_data_default', function ($theme_json) {
    $data = $theme_json->get_data();

    unset(
        $data['settings']['color']['palette'],
        $data['settings']['color']['gradients'],
        $data['settings']['color']['duotone'],

        $data['settings']['spacing']['spacingSizes'],
        $data['settings']['spacing']['spacingScale'],
        $data['settings']['spacing']['spacingSteps'],

        $data['settings']['typography']['fontSizes'],

        $data['settings']['shadow']['presets'],
        // $data['settings']['dimensions']['aspectRatios']
    );

    $data['settings']['spacing']['defaultSpacingSizes'] = false;
    $data['settings']['spacing']['customSpacingSize']   = false;

    $data['settings']['typography']['defaultFontSizes'] = false;
    $data['settings']['typography']['customFontSize']   = false;

    return new WP_Theme_JSON_Data($data, 'default');
}, 20);


add_action('after_setup_theme', function () {
	register_nav_menus([
		'header_menu' => __('Header Menu', 'profidev-theme'),
        'footer_menu'  => __( 'Footer Menu', 'profidev-theme' ),
	]);
});

add_filter('upload_mimes', 'custom_allow_gpx_upload');
function custom_allow_gpx_upload($mimes) {
    $mimes['gpx'] = 'application/gpx+xml';
    return $mimes;
}

add_filter('wp_check_filetype_and_ext', 'custom_gpx_file_validation', 10, 4);
function custom_gpx_file_validation($data, $file, $filename, $mimes) {
    $file_info = pathinfo($filename);
    $extension = isset($file_info['extension']) ? $file_info['extension'] : '';

    if ($extension === 'gpx') {
        $data['ext']  = 'gpx';
        $data['type'] = 'application/gpx+xml';
    }

    return $data;
}

if (!function_exists('wrap_attachment_image_copyright')) {
	/**
	 * Wrap attachment image with copyright
	 *
	 * @param string $html
	 * @param $attachment_id
	 *
	 * @return string
	 */
	function wrap_attachment_image_copyright( string $html, $attachment_id ): string {
		$copyright = get_field( 'copyright', $attachment_id );
		// Regex breakdown:
		// <img      : Match the start of the tag
		// \s+       : Match one or more spaces
		// (?<attrs> : Start a named capture group for attributes
		// [^>]+     : Match anything that isn't the closing '>'
		// )         : End capture group
		// \/? >        : Match optional self-closing slash and the closing '>'
		$pattern = '/<img\s+(?<attrs>[^>]+)\/?>/i';
		if ( ! empty( $copyright ) ) {
			return preg_replace_callback( $pattern, function ( $matches ) use ( $copyright ) {
				$attributes = $matches['attrs'];

				return sprintf(
					'<div class="theme-image-wrapper"><img %1$s><div class="copyright"><span>%2$s</span></div></div>',
					trim( $attributes ),
					$copyright
				);
			}, $html );
		} else {
			return preg_replace_callback( $pattern, function ( $matches ) use ( $copyright ) {
				$attributes = $matches['attrs'];

				return sprintf(
					'<div class="theme-image-wrapper"><img %1$s></div>',
					trim( $attributes )
				);
			}, $html );
		}
	}
}

add_filter('wp_get_attachment_image', function ($html, $attachment_id, $size, $icon, $attr) {
	return wrap_attachment_image_copyright($html, $attachment_id);
}, 10, 5);

add_filter( 'render_block', function (string $block_content, array $block, WP_Block $instance) {
	if ($block['blockName'] === 'core/image' &&
	    array_key_exists('attrs', $block) &&
	    is_array($block['attrs']) &&
	    array_key_exists('id', $block['attrs']) &&
	    is_numeric($block['attrs']['id']) &&
	    $block['attrs']['id'] != 0
	) {
		return wrap_attachment_image_copyright($block_content, $block['attrs']['id']);
	}
	return $block_content;
}, 10, 3 );

if (!function_exists('get_navigation_link')) {
	/**
	 * Convert navigation link to string link
	 *
	 * @param mixed $navigation
	 * @param array $attributes
	 *
	 * @return string
	 */
	function get_navigation_link( mixed $navigation, array $attributes = [] ): string {
		if ( ! is_array( $navigation ) || empty( $navigation['title'] ) || empty( $navigation['url'] ) ) {
			return '';
		}

		$extra_attributes = $attributes;
		if ( ! empty( $navigation['target'] ) ) {
			$extra_attributes['target'] = $navigation['target'];
		}

		return sprintf(
			'<a href="%1$s" %3$s>%2$s</a>',
			$navigation['url'],
			$navigation['title'],
			get_block_wrapper_attributes( $extra_attributes )
		);
	}
}

if (!function_exists('get_route_tabs_content')) {
	/**
	 * @param string $id
	 * @param WP_Term $tag
	 * @param WP_Term|null $category
	 *
	 * @return string
	 */
	function get_route_tabs_content( string $id, WP_Term $tag = null, WP_Term $category = null ): string {
		ob_start();
		$currentPage = $_REQUEST['paged'] ?? 1;

		$tax_query = array();

		if ( $tag ) {
			$tax_query[] = array(
				'taxonomy' => 'route_tag',
				'field'    => 'slug',
				'terms'    => $tag->slug,
			);
		}

		if ( $category ) {
			$tax_query[] = array(
				'taxonomy' => $category->taxonomy,
				'field' => 'slug',
				'terms' => $category->slug,
			);
		}
		$query = new WP_Query([
			'post_type' => 'routes',
			'post_status' => 'publish',
			'posts_per_page' => 6,
			'tax_query' => $tax_query,
			'paged' => $currentPage,
		]);

		$hasLoadMore = $query->max_num_pages > 1 && $currentPage < $query->max_num_pages;
		$nextPage = $currentPage + 1;
		$queryArgs = [
			'action' => 'route_tabs_ajax',
			'paged'  => $nextPage,
			'term' => $tag->slug,
			'nonce' => wp_create_nonce('profidev-ajax-nonce')
		];

		if ( $tag ) {
			$queryArgs['term'] = $tag->slug;
		}

		if ( !is_null($category) ) {
			$queryArgs['category'] = $category->slug;
			$queryArgs['category_taxonomy'] = $category->taxonomy;
		}

		$next = add_query_arg($queryArgs, admin_url('admin-ajax.php'));
		
		$current_slug = $tag ? $tag->slug : 'all';
		?>
		<route-tabs class="tab-block" role="tabpanel" aria-labelledby="<?php echo esc_attr($id); ?>-tab-<?php echo esc_attr($current_slug); ?>"  data-parent-id="tabs-<?php echo esc_attr($id); ?>">
			<div class="theme-grid route-list">
				<?php if ($query->have_posts()): ?>
					<?php foreach ($query->posts as $post): ?>
						<div class="item">
							<?php get_template_part('template-parts/route', 'item', ['route' => $post->ID]); ?>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<p><?php echo __('It looks like there are no routes here yet. Check back soon!', 'profidev-theme'); ?></p>
				<?php endif; ?>
			</div>

			<?php if (is_archive() || !is_null($category) || wp_doing_ajax()): ?>
				<?php if ($hasLoadMore): ?>
					<button data-type="load-more" data-href="<?php echo esc_url($next); ?>" class="no-btn theme-button-primary-outline"><?php echo __('See more', 'profidev-theme'); ?></button>
				<?php endif; ?>
			<?php else: ?>
				<a href="<?php echo get_post_type_archive_link('routes'); ?>" class="theme-button-primary-outline all-routes"><?php echo __('All routes', 'profidev-theme'); ?></a>
			<?php endif; ?>
		</route-tabs>
		<?php
		return ob_get_clean();
	}
}