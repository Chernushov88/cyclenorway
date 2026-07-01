<?php

/**
 * Form Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or its parent block.
 *
 */

if (!defined('ABSPATH')) {
	exit;
}

$innerblocks = [
	'profidev/route-map'
];

$id = $block['anchor'] ?? uniqid();
$fields = get_fields();
$attributes = get_block_wrapper_attributes(['class' => join(' ', [
	'profidev-route-tabs template-2',
	!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : '',
]), 'id' => $id]);

$tags = $fields['tabs_route_tags'] ?? [];
$hide_all_routes_tab = !empty($fields['hide_all_routes_tab']);
$hide_title = !empty($fields['hide_title']);

if (is_wp_error($tags) || empty($tags)) {
    $tags = [];
}

$category = null;
if (get_queried_object() instanceof WP_Term) {
	$category = get_queried_object();
}
?>

<section <?php echo $attributes; ?>>
	<route-map-tabs>
		<div class="theme-container">
			<div class="top-element">
				<a class="wp-block-button__link wp-element-button">EXPLORE ROUTES</a>
			</div>
			<?php if (!$hide_title): ?>
				<h2 class="theme-title"><?php _e('Routes', 'profidev-theme') ?></h2>
			<?php endif; ?>
			<profidev-theme-tabs>
				<div id="tabs-<?php echo esc_attr($id); ?>" class="theme-tabs">
					<div class="route-buttons" role="tablist" aria-label="Tabs">
						<?php if (!$hide_all_routes_tab): ?>
							<button class="no-btn" role="tab" aria-selected="true" id="<?php echo esc_attr($id); ?>-tab-all" data-parent-id="tabs-<?php echo esc_attr($id); ?>">
								<?php _e('All routes', 'profidev-theme') ?>
							</button>
						<?php endif; ?>
						<?php foreach ($tags as $tag): ?>
							<button class="no-btn" role="tab" aria-selected="true" id="<?php echo esc_attr($id); ?>-tab-<?php echo esc_attr($tag->slug); ?>" data-parent-id="tabs-<?php echo esc_attr($id); ?>"><?php echo $tag->name; ?></button>
						<?php endforeach; ?>
					</div>
					<?php if (!$hide_all_routes_tab): ?>
						<?php echo get_route_tabs_content($id, null, $category); ?>
					<?php endif; ?>
					<?php foreach ($tags as $tag): ?>
						<?php echo get_route_tabs_content($id, $tag, $category); ?>
					<?php endforeach; ?>
				</div>
			</profidev-theme-tabs>
		</div>

		<InnerBlocks class="theme-maps-tabs" allowedBlocks="<?php echo esc_attr(json_encode($innerblocks)); ?>" />
	</route-map-tabs>
</section>
