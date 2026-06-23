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
		'profidev-testimonials-slider',
		!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : '',
	]),
	'id' => $block['anchor'] ?? null
]);
$slides = array_filter($fields['slides'] ?? [], function ($slide) {
	return !empty($slide['image']) && is_numeric($slide['image']) && !empty($slide['name']) && !empty($slide['quote']);
});
?>
<section class="profidev-testimonials-slider">
	<?php if (!empty($fields['background_image']) && is_numeric($fields['background_image'])): ?>
		<?php echo wp_get_attachment_image($fields['background_image'], 'full', false, ['loading' => 'lazy']); ?>
	<?php endif; ?>
	<profidev-testimonials-slider>
		<div class="theme-container">
			<div class="swiper testimonials-slider">
				<div class="swiper-wrapper">
					<?php foreach ($slides as $slide): ?>
					<div class="swiper-slide">
						<div class="testimonial">
							<?php echo wp_get_attachment_image($slide['image'], 'full', false, ['loading' => 'lazy']); ?>
							<h2 class="no-margin theme-h3 blockquote"><?php echo wp_kses_post($slide['quote']); ?></h2>
							<div class="author">
								<span class="name"><?php echo wp_kses_post($slide['name']); ?></span>
								<?php if (!empty($slide['position'])): ?>
									<span class="position"><?php echo wp_kses_post($slide['position']); ?></span>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="theme-slider-controls">
				<div class="slider-button prev"></div>
				<div class="slider-pagination"></div>
				<div class="slider-button next"></div>
			</div>
		</div>
	</profidev-testimonials-slider>
</section>
