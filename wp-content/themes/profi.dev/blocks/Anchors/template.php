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

$attributes = get_block_wrapper_attributes([
	'class' => join(' ', [
		'profidev-anchors',
		!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
	]),
	'id' => $block['anchor'] ?? null
]);
$fields = get_fields() ?? [];
$items = array_filter(get_value_or_default($fields['items'], []), function ($item) {
	return is_array($item['link']);
});
?>
<div <?php echo $attributes; ?>>
	<div class="theme-container">
		<div class="wrapper">
			<ul class="no-list anchor-list">
				<?php foreach ($items as $item): ?>
					<li><?php echo get_navigation_link($item['link'], ['class' => 'no-underline']); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>
