<?php
/**
 * @var array $args
 */
$attributes = get_block_wrapper_attributes(['class' => 'recommendation-list', 'id' => $args['id'] ?? null]);
$fields = get_fields_or_template($args['post_id'], $args['is_preview'], 'overview_route_fields');
$content = get_value_or_default($fields['overview_content_overview'], []);
$further_readings = array_filter(get_value_or_default($content['further_readings'], []), function ($item) {
	return !empty($item['icon']) && !empty($item['title']);
});
$has_content = count($further_readings) > 0;
?>
<?php if ($has_content): ?>
	<div class="icon-list">
		<h2 class="no-margin theme-h4 title"><?php echo __('Further Reading', 'profidev-theme'); ?></h2>
		<ul class="no-list theme-grid icon-list">
			<?php foreach ($further_readings as $further_reading): ?>
				<li class="item">
					<a href="<?php echo empty($further_reading['link']) ? '#' : esc_url($further_reading['link']); ?>" class="list-item">
						<div class="icon">
							<?php echo wp_get_attachment_image($further_reading['icon'], 'full', false, ['loading' => 'lazy']); ?>
						</div>
						<p class="no-margin"><?php echo wp_kses_post($further_reading['title']); ?></p>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
