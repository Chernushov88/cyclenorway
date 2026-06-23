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
$attributes = get_block_wrapper_attributes(['class' => join(' ', [
	'profidev-post-slider',
	!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
]), 'id' => $block['anchor'] ?? null]);
?>

<section <?php echo $attributes; ?>>
	<profidev-post-slider>
		<div class="theme-container">
			<?php if(!empty($fields['title'])): ?>
				<h2 class="theme-title"><?php echo wp_kses_post($fields['title']); ?></h2>
			<?php endif; ?>

			<div class="swiper post-slider">
				<div class="swiper-wrapper">
					<?php if (is_array($fields['posts'])): ?>
						<?php foreach ($fields['posts'] as $slide): ?>
							<div class="swiper-slide">
								<p class="post-meta">
									<?php echo wp_date('d. M -', strtotime($slide['date'])); ?>
									<?php if (!empty($slide['tag'])): ?>
										<span><?php echo wp_kses_post($slide['tag']); ?></span>
									<?php endif; ?>

									<?php if (!empty($slide['restriction'])): ?>
										<em><?php echo wp_kses_post($slide['restriction']); ?></em>
									<?php endif; ?>
								</p>
								<div class="post-content">
									<?php echo wp_get_attachment_image($slide['image'], 'full', false, ['class' => 'post-image']); ?>
									<div class="post-info">
										<h3 class="no-margin"><?php echo wp_kses_post($slide['title']); ?></h3>
										<p class="no-margin"><?php echo wp_kses_post($slide['description']); ?></p>
										<?php echo get_navigation_link($slide['navigation'], ['class' => 'theme-button-primary-outline']); ?>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>

				<div class="theme-slider-controls">
					<div class="slider-button prev"></div>
					<div class="slider-pagination"></div>
					<div class="slider-button next"></div>
				</div>
			</div>
		</div>
	</profidev-post-slider>
</section>
