<?php
/**
 * @var array $args
 */
$attributes = get_block_wrapper_attributes(['class' => 'profidev-gallery', 'id' => $args['id'] ?? null]);
$post_id = $args['post_id'];
$fields = get_fields_or_template($args['post_id'], $args['is_preview'], 'single_route_fields');
$images = $fields['gallery'] ?? [];
?>
<?php if (!$args['post_id'] && $args['is_preview'] && (!is_array($images) || count($images) == 0)): ?>
	<?php get_template_part('template-parts/preview-warning', null); ?>
<?php elseif (is_array($images) && count($images) > 0) : ?>
<section <?php echo $attributes; ?>>
	<profidev-gallery-justified>
		<div class="theme-container">
			<h2 class="title"><?php echo __('Gallery', 'profidev-theme'); ?></h2>

			<div class="gallery-grid js-gallery">
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
									</a>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</profidev-gallery-justified>
</section>
<?php endif; ?>
