<?php
/**
 * @var array $args
 */
$attributes = get_block_wrapper_attributes(['class' => 'profidev-map-overview', 'id' => $args['id'] ?? null]);
$fields = get_fields_or_template($args['post_id'], $args['is_preview'], 'overview_route_fields');
$overview_map = get_value_or_default($fields['overview_map'], []);
$has_buttons = !empty($overview_map['download_gpx']) &&
							 !empty($overview_map['gpx_help_video']) &&
							 !empty($overview_map['full_size_map']);

$coreFields = $args['fields'];
$protected_content = !empty($coreFields['protected_content']) && is_array($coreFields['protected_content']) ? $coreFields['protected_content'] : [];
?>
<?php if (!empty($overview_map)): ?>
<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<h2 class="title"><?php echo __('Route map', 'profidev-theme'); ?></h2>

		<?php echo strip_tags($overview_map['content'], ['iframe']); ?>

		<?php if ($has_buttons) : ?>
			<div class="map-btns">
				<?php if (!empty($overview_map['download_gpx'])): ?>
					<a href="<?php echo esc_url($overview_map['download_gpx']); ?>" download class="theme-button-primary download">
						<?php echo __('Download GPX', 'profidev-theme'); ?>
					</a>
				<?php endif; ?>
				<?php if (!empty($overview_map['gpx_help_video'])): ?>
					<a href="<?php echo esc_url($overview_map['gpx_help_video']); ?>" class="theme-button-primary-outline help">
						<?php echo __('GPX Help Video', 'profidev-theme'); ?>
					</a>
				<?php endif; ?>
				<?php if (!empty($overview_map['full_size_map'])): ?>
					<a href="<?php echo esc_url($overview_map['full_size_map']); ?>" class="theme-button-primary-outline map" target="_blank">
						<?php echo __('Full Size Map', 'profidev-theme'); ?>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>
