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
		'profidev-hero-banner',
		$fields['align_vertical'],
		$fields['align_horizontal'],
		$fields['template'],
		!empty($fields['enable_header_overlap']) ? 'has-overlap-header' : '',
		!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
	]),
	'id' => $block['anchor'] ?? null
]);

$innerTemplate = [
	[
		'core/heading',
		[
			'level'     => 1,
			'content'   => 'Members blog',
			'className' => 'has-text-align-center'
		]
	]
];
$innerblocks = [
	'core/heading',
	'core/paragraph',
	'core/buttons'
];

?>
<section <?php echo $attributes; ?>>
	<?php if (!empty($fields['background_image'])): ?>
		<?php echo wp_get_attachment_image($fields['background_image'], 'full', false, ['class' => 'bg-image', 'loading' => 'eager']); ?>
	<?php endif; ?>
	<div class="theme-container">
		<InnerBlocks class="theme-text-element" allowedBlocks="<?php echo esc_attr(json_encode($innerblocks)); ?>" template="<?php echo esc_attr(json_encode($innerTemplate)); ?>" />
	</div>
</section>
