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

$fields = get_fields() ?? [];
$attributes = get_block_wrapper_attributes([
	'class' => join(' ', [
		'profidev-route-map',
		$fields['template'] ?? 'template-1',
		!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
	]),
	'id'    => $block['anchor'] ?? null,
]);
?>
<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<?php if (!empty($fields['title'])): ?>
			<h2 class="theme-h3 title"><?php echo wp_kses_post($fields['title']); ?></h2>
		<?php endif; ?>

		<?php if (!empty($fields['text'])): ?>
			<div class="text"><?php echo wp_kses_post($fields['text']); ?></div>
		<?php endif; ?>
		<?php echo strip_tags($fields['map'], ['iframe']); ?>
	</div>
</section>
