<?php
/**
 * @var array $args
 */
$attributes = get_block_wrapper_attributes(['class' => 'testimonial', 'id' => $args['id'] ?? null]);
$fields = get_fields_or_template($args['post_id'], $args['is_preview'], 'overview_route_fields');
$content = get_value_or_default($fields['overview_content_overview'], []);
$testimonials = get_value_or_default($content['testimonials'], []);
$has_content = !empty($testimonials['quote']) && !empty($testimonials['user_details']);
?>
<?php if ($has_content): ?>
<div <?php echo $attributes; ?>>
	<?php if (!empty($testimonials['image']) && is_numeric($testimonials['image'])): ?>
		<?php echo wp_get_attachment_image($testimonials['image'], 'full', false, ['loading' => 'lazy']); ?>
	<?php endif; ?>
	<?php if (!empty($testimonials['quote'])): ?>
		<p class="theme-h4 testimonial-text"><?php echo wp_kses_post($testimonials['quote']); ?></p>
	<?php endif; ?>
	<?php if (!empty($testimonials['user_details'])): ?>
		<p class="no-margin testimonial-author"><?php echo wp_kses_post($testimonials['user_details']); ?></p>
	<?php endif; ?>
</div>
<?php endif; ?>
