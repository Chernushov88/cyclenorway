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

// Settting up allowed inner blocks.
$innerTemplate = [];

$allowedBlocks = [
	'core/heading',
	'core/paragraph',
	'core/buttons',
	'core/image',
	'core/list',
	'core/gallery',
	'core/embed',
	'profidev/accordion',
];

$attributes = get_block_wrapper_attributes(['class' => join(' ', [
	'accordion',
	!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
]), 'id' => $block['anchor'] ?? null]);

$fields = get_value_or_default(get_fields(), []);
?>
<div <?php echo $attributes; ?>>
	<button class="no-btn accordion-head" type="button" aria-expanded="false">
		<span class="accordion-title">
			<?php if (!empty($fields['title'])): ?>
				<?php echo wp_kses_post($fields['title']); ?>
			<?php else: ?>
				Is Norway good for biking?
			<?php endif; ?>
		</span>
		<i class="icon" aria-hidden="true"></i>
	</button>
	<div class="accordion-content">
		<InnerBlocks class="theme-text-element" allowedBlocks="<?php echo esc_attr(json_encode($allowedBlocks)); ?>" template="<?php echo esc_attr(json_encode($innerTemplate)); ?>" />
	</div>
</div>
