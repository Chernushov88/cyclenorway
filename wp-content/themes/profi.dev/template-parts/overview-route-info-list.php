<?php
/**
 * @var array $args
 */
$attributes = get_block_wrapper_attributes(['class' => 'info-list', 'id' => $args['id'] ?? null]);
$fields = get_fields_or_template($args['post_id'], $args['is_preview'], 'overview_route_fields');
$content = get_value_or_default($fields['overview_content_overview'], []);
$info_list = get_value_or_default($content['info_lists'], []);
?>
<?php if (!empty($info_list)): ?>
	<div <?php echo $attributes; ?>>
		<?php foreach ($info_list as $info): ?>
		<div class="info-list-item">
			<?php if (is_numeric($info['image'])): ?>
				<?php echo wp_get_attachment_image($info['image'], 'full', false, ['class' => 'icon', 'loading' => 'lazy']); ?>
			<?php endif; ?>
			<div class="item-description">
				<?php if (!empty($info['title'])): ?>
					<h2 class="no-margin theme-h4"><?php echo wp_kses_post($info['title']); ?></h2>
				<?php endif; ?>
				<div class="theme-text-element">
					<?php echo wp_kses_post($info['content']); ?>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
