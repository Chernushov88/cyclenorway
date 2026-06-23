<?php declare(strict_types=1);

namespace ProfiDev\Foundation;

use Exception;
use Mustache\Engine;
use Mustache\Loader\FilesystemLoader;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use WP_Post;


class Blocks {
	/**
	 * Current list of blocks
	 *
	 * @var array
	 */
	public array $blocks = [];

	public array $block_settings = [];

	public array $block_list = [];

	/**
	 * @var Vite
	 */
	public Vite $vite;

	/**
	 * @throws Exception
	 */
	public function __construct(public Application $app) {
		$recursiveIterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator(
				$app->getBlocksPath()
			)
		);
		foreach ($recursiveIterator as $file) {
			/** @var SplFileInfo $file */
			if ($file->isFile() && $file->getExtension() === 'json' && $file->getFilename() === 'block.json') {
				$this->blocks[] = $file->getPath();

				$metadata = json_decode(file_get_contents($file->getPathname()), true);
				if (is_array($metadata)) {
					$this->block_list[] = $metadata['name'];
				}
			}
		}
		$recursiveIterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator(
				$this->app->getBasePath('config' . DIRECTORY_SEPARATOR . 'blocks')
			)
		);
		foreach ($recursiveIterator as $file) {
			/** @var SplFileInfo $file */
			if ($file->isFile() && $file->getExtension() === 'json') {
				if (!$file->getRealPath()) {
					continue;
				}
				$blockName = $file->getBasename('.json');
				$settings = json_decode(file_get_contents($file->getRealPath()), true);
				if (is_array($settings)) {
					$this->block_settings[ $blockName ] = $settings;
				}
			}
		}
		$this->vite = $app->make('vite');

		add_action('init', [$this, 'registerBlockSettings']);
		add_action('init', [$this, 'registerBlocks'], 20);
		add_action('acf/settings/load_json', [$this, 'acfPaths']);
		add_action('acf/settings/save_json', [$this, 'acfSaveFile']);
		add_action('acf/pre_save_block', [$this, 'acfPreSaveBlock']);
		add_action('save_post', [$this, 'saveBlockSettings']);
		add_action('pre_delete_post', [$this, 'preDeletePost'], 10, 3);
		if ($this->app->hasMustache) {
			remove_action( 'acf_block_render_template', 'acf_block_render_template', 10 );
			add_action( 'acf_block_render_template', [ $this, 'renderCallback' ], 10, 6 );
		}
	}

	public function renderCallback( $block, $content, $is_preview, $post_id, $wp_block, $context ): void {
		if ( isset( $block['path'] ) && file_exists( $block['path'] . '/' . $block['render_template'] ) ) {
			$path = $block['path'] . '/' . $block['render_template'];
		} elseif ( file_exists( $block['render_template'] ) ) {
			$path = $block['render_template'];
		} else {
			$path = locate_template( $block['render_template'] );
		}

		do_action( 'acf/blocks/pre_block_template_render', $block, $content, $is_preview, $post_id, $wp_block, $context );

		// DefaultInclude template.
		if ( file_exists( $path ) && str_ends_with($path, '.hbs') ) {
			$block_dir = basename( $block['path'] );
			echo $this->app->make('template')->render(
				$block_dir . '/' . 'template',
				[
					'block' => $block,
					'content' => $content,
					'is_preview' => $is_preview,
					'post_id' => $post_id,
					'wp_block' => $wp_block,
					'context' => $context,
					'fields' => get_fields() ?? []
				]
			);
		}
		else if ( file_exists( $path ) ) {
			include $path;
		} elseif ( $is_preview ) {
			echo acf_esc_html( apply_filters( 'acf/blocks/template_not_found_message', '<p>' . __( 'The render template for this ACF Block was not found', 'acf' ) . '</p>' ) );
		}

		do_action( 'acf/blocks/post_block_template_render', $block, $content, $is_preview, $post_id, $wp_block, $context );
	}

	/**
	 * Register block settings
	 *
	 * @return void
	 */
	public function registerBlockSettings(): void {
		$labels = [
			'name'                  => __('Block Settings', 'profidev-theme'),
			'singular_name'         => __('Block Setting', 'profidev-theme'),
			'menu_name'             => __('Block Settings', 'profidev-theme'),
			'name_admin_bar'        => __('Block Setting', 'profidev-theme'),
			'add_new'               => __('Add New', 'profidev-theme'),
			'add_new_item'          => __('Add New Block Setting', 'profidev-theme'),
			'edit_item'             => __('Edit Block Setting', 'profidev-theme'),
			'new_item'              => __('New Block Setting', 'profidev-theme'),
			'view_item'             => __('View Block Setting', 'profidev-theme'),
			'search_items'          => __('Search Block Settings', 'profidev-theme'),
			'not_found'             => __('No block settings found', 'profidev-theme'),
			'not_found_in_trash'    => __('No block settings found in Trash', 'profidev-theme'),
			'all_items'             => __('All Block Settings', 'profidev-theme'),
		];

		register_post_type('block_fields_cpt', [
			'labels'                => $labels,

			// Backend only
			'public'                => false,
			'publicly_queryable'    => false,
			'exclude_from_search'   => true,
			'has_archive'           => false,
			'rewrite'               => false,

			// Admin UI
			'show_ui'               => true,
			'show_in_menu'          => 'edit.php?post_type=acf-field-group',
			'show_in_admin_bar'     => true,
			'show_in_rest'          => true, // enable only if you need Gutenberg or API

			// Capabilities
			'capability_type' => 'post',
			'map_meta_cap'    => false,
			'capabilities'   => [
				'edit_posts'   => 'manage_options',
				'edit_post'    => 'manage_options',
				'read_post'    => 'manage_options',
				'delete_post'  => 'manage_options',
				'publish_posts'=> 'manage_options',
				'read_private_posts' => 'manage_options',
			],

			// Other
			'supports'              => ['title', 'editor', 'excerpt'],
			'menu_icon'             => 'dashicons-admin-generic',
		]);
	}

	/**
	 * @param $post_id
	 *
	 * @return void
	 */
	public function saveBlockSettings( $post_id ): void {
		$post = get_post( $post_id );
		if (!($post instanceof WP_Post) || $post->post_type !== 'block_fields_cpt') {
			return;
		}

		$settings = [];
		$blocks = parse_blocks($post->post_content);
		foreach ($blocks as $block) {
			$blockName = sanitize_title($block['blockName']);
			$settings[$blockName][] = array_intersect_key(
				$block,
				array_flip(['attrs', 'innerBlocks', 'innerHTML', 'innerContent'])
			);
		}

		foreach ($settings as $blockName => $blockData) {
			$this->updateBlockSettings($blockName, $post, $blockData);
		}
	}

	/**
	 * Pre delete post
	 * @param mixed $delete
	 * @param WP_Post $post
	 * @param bool $force_delete
	 *
	 * @return mixed
	 */
	public function preDeletePost(mixed $delete, WP_Post $post, bool $force_delete): mixed {
		if ($post->post_type !== 'block_fields_cpt') {
			return $delete;
		}

		$blocks = parse_blocks($post->post_content);
		foreach ($blocks as $block) {
			$blockName = sanitize_title($block['blockName']);
			$this->deleteBlockSettings($blockName, $post);
		}

		return $delete;
	}

	/**
	 * Delete block settings
	 *
	 * @param string $blockName
	 * @param WP_Post $post
	 *
	 * @return void
	 */
	public function deleteBlockSettings(string $blockName, WP_Post $post): void {
		$path = $this->app->getBasePath('config' . DIRECTORY_SEPARATOR . 'blocks') . DIRECTORY_SEPARATOR . $blockName . '.json';
		if (file_exists($path)) {
			$attributes = [];
			if (file_exists($path)) {
				$attributes = json_decode(file_get_contents($path), true);
			}
			if (!is_array($attributes)) {
				$attributes = [];
			}

			unset($attributes[$post->ID]);
			file_put_contents($path, json_encode($attributes, JSON_PRETTY_PRINT));
		}
	}

	/**
	 * Save block settings
	 *
	 * @param string $blockName
	 * @param WP_Post $post
	 * @param array $blockData
	 *
	 * @return void
	 */
	public function updateBlockSettings(string $blockName, WP_Post $post, array $blockData = []): void {
		$path = $this->app->getBasePath('config' . DIRECTORY_SEPARATOR . 'blocks') . DIRECTORY_SEPARATOR . $blockName . '.json';
		$attributes = [];
		if (file_exists($path)) {
			$attributes = json_decode(file_get_contents($path), true);
		}
		if (!is_array($attributes)) {
			$attributes = [];
		}
		$attributes[$post->ID] = [
			'title' => $post->post_title,
			'description' => $post->post_excerpt,
			'data' => $blockData
		];

		file_put_contents($path, json_encode($attributes, JSON_PRETTY_PRINT));
	}

	/**
	 * Get block settings
	 * @param string $blockName
	 *
	 * @return array
	 */
	public function getBlockSettings(string $blockName): array {
		$blockName = sanitize_title($blockName);
		if (array_key_exists($blockName, $this->block_settings)) {
			$attributes = $this->block_settings[$blockName];
			$attributes = array_shift($attributes);
			if (is_array($attributes) && array_key_exists('data', $attributes)) {
				return array_shift($attributes['data']);
			}
		}

		return [];
	}

	/**
	 * Get block variations
	 *
	 * @param string $blockName
	 *
	 * @return array
	 */
	public function getBlockVariations(string $blockName): array {
		$sanitizedBlockName = sanitize_title($blockName);
		$variations = [];

		if (!array_key_exists($sanitizedBlockName, $this->block_settings)) {
			return $variations;
		}

		$firstSkipped = false;

		foreach ($this->block_settings[$sanitizedBlockName] as $post_id => $variation) {
			foreach ($variation['data'] as $index => $settings) {

				// 1. Format for actual insertion (Tuple Array)
				$innerBlocksTemplate = isset($settings['innerBlocks'])
					? $this->mapBlocksForVariation($settings['innerBlocks'])
					: [];

				// 2. Format for the hover preview (Associative Array)
				$innerBlocksExample = isset($settings['innerBlocks'])
					? $this->mapBlocksForExample($settings['innerBlocks'])
					: [];

				$variations[] = [
					'name'        => sprintf('%s-%s-%s', $sanitizedBlockName, $post_id, $index),
					'title'       => $variation['title'],
					'description' => $variation['description'],
					'attributes'  => $settings['attrs'] ?? [],
					'innerBlocks' => $innerBlocksTemplate, // <--- Used when clicked
					'isDefault'   => !$firstSkipped,
					'example'     => [
						'attributes'  => $settings['attrs'] ?? [],
						'innerBlocks' => $innerBlocksExample, // <--- Used on hover
					],
					'scope'       => ['inserter'],
				];

				$firstSkipped = true;
			}
		}

		return $variations;
	}

	/**
	 * Recursive helper to format blocks for Variation API
	 * @param array $blocks
	 *
	 * @return array
	 */
	private function mapBlocksForVariation(array $blocks): array {
		$mapped = [];

		foreach ($blocks as $block) {
			// Resolve the block name. If it's missing, skip to avoid the "" error.
			$name = $block['blockName'] ?? ($block['attrs']['name'] ?? null);

			if (!$name) {
				continue;
			}

			$attrs = $block['attrs'] ?? [];
			if (isset($attrs['anchor'])) {
				unset($attrs['anchor']);
			}

			// Ensure the innerBlocks is always an indexed array of these triplets
			$inner = isset($block['innerBlocks']) && is_array($block['innerBlocks'])
				? $this->mapBlocksForVariation($block['innerBlocks'])
				: [];

			$mapped[] = [
				$name,
				$attrs,
				$inner
			];
		}

		return $mapped;
	}

	/**
	 * Recursive helper to format blocks strictly for the "example" Preview API
	 */
	private function mapBlocksForExample(array $blocks): array {
		$mapped = [];

		foreach ($blocks as $block) {
			$name = $block['blockName'] ?? ($block['attrs']['name'] ?? null);

			if (!$name) {
				continue;
			}

			$attrs = $block['attrs'] ?? [];
			if (isset($attrs['anchor'])) {
				unset($attrs['anchor']);
			}

			$mapped[] = [
				'name'        => $name, // Note the explicit 'name' key
				'attributes'  => $attrs, // Note the explicit 'attributes' key
				'innerBlocks' => isset($block['innerBlocks']) && is_array($block['innerBlocks'])
					? $this->mapBlocksForExample($block['innerBlocks'])
					: []
			];
		}

		return $mapped;
	}

	/**
	 * Pre-save block set attributes
	 *
	 * @param array $attributes
	 * @return array
	 */
	public function acfPreSaveBlock(array $attributes): array {
		if (!array_key_exists('anchor', $attributes) or $attributes['anchor'] === '') {
			$attributes['anchor'] = 'block-' . uniqid();
		}

		return $attributes;
	}

	/**
	 * Register paths
	 *
	 * @param array $paths
	 *
	 * @return array
	 */
	public function acfPaths($paths): array {
		return array_merge($paths, $this->blocks);
	}

	/**
	 * Save acf file block settings to correct path
	 *
	 * @param string $path
	 *
	 * @return mixed
	 */
	public function acfSaveFile($path): mixed {
		if (array_key_exists('acf_field_group', $_REQUEST) &&
		    array_key_exists('key', $_REQUEST['acf_field_group'])) {
			$paths = acf_get_setting( 'load_json' );
			$file = sprintf('/%s.json', $_REQUEST['acf_field_group']['key']);

			foreach($paths as $_path) {
				if (file_exists($_path . $file)) {
					return $_path;
				}
			}
		}
		return $path;
	}

	/**
	 * Register blocks
	 *
	 * @return void
	 */
	public function registerBlocks(): void {
		foreach ($this->blocks as $path) {
			$block_file = $path . DIRECTORY_SEPARATOR . 'block.json';
			$block_functions = $path . DIRECTORY_SEPARATOR . 'functions.php';
			$block_class = $path . DIRECTORY_SEPARATOR . 'Block.php';
			if (!file_exists($block_file)) {
				continue;
			}
			$metadata = json_decode(file_get_contents($block_file), true);
			if (!is_array($metadata)) {
				continue;
			}
			if (file_exists($block_functions)) {
				require_once $block_functions;
			}
			$this->vite->registerAssets($metadata, $block_file);

			$settings = $this->getBlockSettings($metadata['name']);
			$variations = $this->getBlockVariations($metadata['name']);

			$args = [
				'variations' => $variations,
			];

			$defaultVariation = null;
			foreach ($variations as $variation) {
				if (isset($variation['isDefault']) && $variation['isDefault'] === true) {
					$defaultVariation = $variation;
					break;
				}
			}

			if ($defaultVariation) {
				$args['example'] = [
					'attributes'  => array_merge($defaultVariation['attributes'], ['mode' => 'preview']),
					'innerBlocks' => $defaultVariation['example']['innerBlocks'],
					'viewportWidth' => 1920,
				];
			} else {
				$args['example'] = [
					'attributes' => ['mode' => 'preview'],
					'viewportWidth' => 1920,
				];
			}

			register_block_type( $block_file, $args );
		}
	}
}
