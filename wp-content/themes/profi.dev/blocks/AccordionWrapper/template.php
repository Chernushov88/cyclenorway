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
	'core/gallery',
	'profidev/accordion',
];

$attributes = get_block_wrapper_attributes(['class' => join(' ', [
	'accordions-wrapper',
	!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
]), 'id' => $block['anchor'] ?? null]);
$fields = get_value_or_default(get_fields(), []);
?>
<InnerBlocks <?php echo $attributes; ?> allowedBlocks="<?php echo esc_attr(json_encode($allowedBlocks)); ?>" template="<?php echo esc_attr(json_encode($innerTemplate)); ?>" />
