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

$attributes = get_block_wrapper_attributes([
	'class' => join(' ', [
		'profidev-gallery',
		!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
	]),
	'id' => $block['anchor'] ?? null
]);
$fields = get_fields() ?? [];
$images = $fields['gallery'] ?? [];
?>
<section <?php echo $attributes; ?>>
	<profidev-gallery-justified>
		<div class="theme-container">
			<?php if (!empty($fields['title'])): ?>
				<h2 class="theme-h3 title"><?php echo wp_kses_post($fields['title']); ?></h2>
			<?php endif; ?>
			<div class="gallery-grid js-gallery" data-type="load-more">
				<?php if (is_array($images)): ?>
					<?php foreach ($images as $image): ?>
						<?php if(is_numeric($image)): ?>
							<?php $image_meta = wp_get_attachment_image_src($image, 'full'); ?>
							<?php if(is_array($image_meta)): ?>
								<div class="gallery-item">
									<a
										href="<?php echo esc_url($image_meta[0]); ?>"
										data-pswp-width="<?php echo esc_attr($image_meta[1]); ?>"
										data-pswp-height="<?php echo esc_attr($image_meta[2]); ?>"
									>
										<?php echo wp_get_attachment_image($image, 'full', false, ['loading' => 'lazy']); ?>
										<span class="gallery-item-title"><?php echo get_the_title($image); ?></span>
									</a>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<button type="button" class="theme-button-primary-outline more-photos"><?php _e('More photos', 'profidev-theme') ?></button>
		</div>
	</profidev-gallery-justified>
</section>
