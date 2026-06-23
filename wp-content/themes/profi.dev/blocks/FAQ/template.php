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

$innerTemplate = [
	[
		'profidev/accordion-wrapper',
		[],
		[
			['profidev/accordion'],
			['profidev/accordion'],
			['profidev/accordion']
		]
	],
	[
		'profidev/accordion-wrapper',
		[],
		[
			['profidev/accordion'],
			['profidev/accordion'],
			['profidev/accordion']
		]
	]
];

$allowedBlocks = [
	'profidev/accordion-wrapper'
];

$fields = get_fields();
$mode = get_value_or_default($fields['mode'] ?? 'data-only-one', 'data-only-one');
$attributes = get_block_wrapper_attributes([
	'class' => join(' ', [
		'profidev-faq',
		!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
	]),
	'id' => $block['anchor'] ?? null
]); ?>
<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<?php if (!empty($fields['content'])): ?>
			<div class="theme-text-element heading">
				<?php echo $fields['content']; ?>
			</div>
		<?php endif; ?>

		<profidev-accordions <?php echo $mode; ?> class="theme-accordions">
			<InnerBlocks class="accordions" allowedBlocks="<?php echo esc_attr(json_encode($allowedBlocks)); ?>" template="<?php echo esc_attr(json_encode($innerTemplate)); ?>" />
		</profidev-accordions>
	</div>
</section>

