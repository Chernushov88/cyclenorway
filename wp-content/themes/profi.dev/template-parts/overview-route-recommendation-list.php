<?php
/**
 * @var array $args
 */
$attributes = get_block_wrapper_attributes(['class' => 'recommendation-list', 'id' => $args['id'] ?? null]);
$fields = get_fields_or_template($args['post_id'], $args['is_preview'], 'overview_route_fields');
$content = get_value_or_default($fields['overview_content_overview'], []);
$recommendations = array_filter(get_value_or_default($content['local_recommendations'], []), function ($item) {
	return !empty($item['image']) && !empty($item['title']) && !empty($item['content']);
});
$has_content = count($recommendations) > 0;
?>
<?php if ($has_content): ?>
<div <?php echo $attributes; ?>>
	<h2 class="no-margin theme-h4 title"><?php echo __('Local recommendations', 'profidev-theme'); ?></h2>
	<?php foreach ($recommendations as $recommendation): ?>
	<div class="recommendation-list-item">
		<?php echo wp_get_attachment_image($recommendation['image'], 'full', false, ['loading' => 'lazy']); ?>
		<div class="item-description">
			<h2 class="no-margin theme-h5"><?php echo wp_kses_post($recommendation['title']); ?></h2>
			<div class="theme-text-element">
				<?php echo wp_kses_post($recommendation['content']); ?>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>
<?php endif; ?>
