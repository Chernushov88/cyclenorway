<?php
/**
 * @var array $args
 */
$fields = get_fields_or_template($args['post_id'], $args['is_preview'], 'single_route_fields');
?>
<?php if (!$args['post_id'] && $args['is_preview'] && (!is_array($fields['more_information']) || count($fields['more_information']) == 0)): ?>
	<?php get_template_part('template-parts/preview-warning', null); ?>
<?php else: ?>
	<?php foreach ($fields['more_information'] as $index => $field): ?>
	<section <?php echo get_block_wrapper_attributes(['class' => 'profidev-route-content', 'id' => ($args['id'] ?? uniqid()).'-'.$index]); ?>>
		<div class="theme-container">
			<div class="wrapper">
				<div class="theme-text-element right">
					<?php if (!empty($field['title']) || !empty($field['gpx_button'])): ?>
					<div class="heading">
						<?php if (!empty($field['title'])): ?>
							<h2 class="title"><?php echo $field['title']; ?></h2>
						<?php endif; ?>
						<?php if (!empty($field['gpx_button'])): ?>
							<a href="<?php echo esc_url($field['gpx_button']); ?>" download class="theme-button-primary-outline download">
								<?php echo __('Download GPX', 'profidev-theme'); ?>
							</a>
						<?php endif; ?>
					</div>
					<?php endif; ?>

					<?php echo wp_kses_post($field['description']); ?>
					<?php echo $field['map']; ?>
				</div>
			</div>
		</div>
	</section>
	<?php endforeach; ?>
<?php endif; ?>
