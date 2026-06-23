<?php

/**
 * Form Block Template.
 *
 * @var   array $block The block settings and attributes.
 * @var   string $content The block inner HTML (empty).
 * @var   bool $is_preview True during backend preview render.
 * @var   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @var   array $context The context provided to the block by the post or its parent block.
 *
 */

if (!defined('ABSPATH')) {
	exit;
}

$innerTemplate = [];
$fields = get_fields_or_template($post_id, $is_preview, 'overview_route_fields');
$attributes = get_block_wrapper_attributes(['class' => join(' ', [
	'profidev-route-content-overview',
	!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
]), 'id' => $block['anchor'] ?? null]);
?>
<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<InnerBlocks class="wrapper" template="<?php echo esc_attr(json_encode($innerTemplate)); ?>" />
		<?php if (!empty($fields['overview_header_card']) && !empty($fields['overview_header_card']['full_story']) && is_numeric($fields['overview_header_card']['full_story'])): ?>
		<div class="wrapper">
			<a href="<?php echo get_the_permalink($fields['overview_header_card']['full_story']); ?>" class="theme-button-primary">
				<?php echo __('Go to the full story', 'profidev-theme'); ?>
			</a>
		</div>
		<?php endif; ?>
	</div>
</section>

