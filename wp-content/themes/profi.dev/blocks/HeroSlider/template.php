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
	['profidev/hero-slide']
];

$innerblocks = [
	'profidev/hero-slide'
];

$class_name = ['profidev-hero-slider'];

$fields = get_fields();

if (!empty($fields['enable_header_overlap'])) {
	$class_name[] = 'has-overlap-header';
}
if (!empty($fields['margin'])) {
	$class_name[] = is_array( $fields['margin'] ) ? join( ' ', $fields['margin'] ) : '';
}

$attributes = get_block_wrapper_attributes([
	'class' => implode(' ', $class_name),
	'id' => $block['anchor'] ?? null
]); ?>

<section <?php echo $attributes; ?>>
	<profidev-hero-slider>
		<div class="swiper hero-slider">
			<InnerBlocks class="swiper-wrapper" allowedBlocks="<?php echo esc_attr(json_encode($innerblocks)); ?>" template="<?php echo esc_attr(json_encode($innerTemplate)); ?>" />
			<div class="theme-slider-controls">
				<div class="slider-button prev"></div>
				<div class="slider-pagination"></div>
				<div class="slider-button next"></div>
			</div>
		</div>
	</profidev-hero-slider>
</section>
