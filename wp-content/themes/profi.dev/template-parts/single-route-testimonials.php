<?php
/**
 * @var array $args
 */
$attributes = get_block_wrapper_attributes(['class' => 'profidev-testimonials', 'id' => $args['id'] ?? null]);
$fields = get_fields_or_template($args['post_id'], $args['is_preview'], 'single_route_fields');
$testimonial = $fields['testimonial'];
if (empty($testimonial) || (empty($testimonial['quote']) && empty($testimonial['user_details']))) {
	if (!$args['post_id'] && $args['is_preview']) {
		get_template_part('template-parts/preview-warning', null);
	}
	return;
}
?>
<section <?php echo $attributes; ?>>
	<?php if (!empty($testimonial['background_image'])): ?>
		<?php echo wp_get_attachment_image($testimonial['background_image'], 'full', false, ['aria-hidden' => 'true']); ?>
	<?php endif; ?>
	<div class="theme-container">
		<div class="testimonial">
			<?php if (!empty($testimonial['image'])): ?>
				<?php echo wp_get_attachment_image($testimonial['image'], 'full', false, ['aria-hidden' => 'true']); ?>
			<?php endif; ?>
			<?php if (!empty($testimonial['quote'])): ?>
				<p class="theme-h3 testimonial-text"><?php echo nl2br($testimonial['quote']); ?></p>
			<?php endif; ?>
			<?php if (!empty($testimonial['user_details'])): ?>
				<p class="testimonial-author"><?php echo $testimonial['user_details']; ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
