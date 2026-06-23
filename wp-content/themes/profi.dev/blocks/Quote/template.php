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

$class_name = ['profidev-quote'];

$fields = get_fields() ?? [];

if (!empty($fields['reverse_block'])) {
	$class_name[] = 'reverse-block';
}

if (!empty($fields['margin'])) {
	$class_name[] = is_array( $fields['margin'] ) ? join( ' ', $fields['margin'] ) : '';
}

$attributes = get_block_wrapper_attributes([
	'class' => implode(' ', $class_name),
	'id' => $block['anchor'] ?? null
]); ?>

<div <?php echo $attributes; ?>>
	<div class="quote-wrapper">
		<p class="no-margin quote-text"><?php echo wp_kses_post($fields['content']); ?></p>
		<?php if (!empty($fields['image']) && is_numeric($fields['image'])): ?>
			<?php echo wp_get_attachment_image($fields['image'], 'full', false, ['class' => 'quote-image']); ?>
		<?php endif; ?>
	</div>
</div>
