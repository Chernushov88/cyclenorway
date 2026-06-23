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

$attributes = get_block_wrapper_attributes(['class' => join(' ', [
	'profidev-author',
	!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
]), 'id' => $block['anchor'] ?? null]);

$fields = get_fields() ?? [];
?>

<div <?php echo $attributes; ?>>
    <?php echo wp_get_attachment_image($fields['avatar'], 'full', false, ['class' => 'author-thumb']); ?>
	<div class="author-info">
		<h3 class="theme-h6 author-name"><?php echo wp_kses_post($fields['name']); ?></h3>
		<p class="author-role"><?php echo wp_kses_post($fields['position']); ?></p>
	</div>
</div>
