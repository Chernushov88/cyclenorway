<?php
/**
 * @var array $args
 */
$attributes = get_block_wrapper_attributes(['class' => 'profidev-route-video-overview', 'id' => $args['id'] ?? null]);
$fields = get_fields_or_template($args['post_id'], $args['is_preview'], 'overview_route_fields');

$overview_video = get_value_or_default($fields['overview_video']['video_link'], "");

?>
<?php if (!empty($overview_video)): ?>
	<section <?php echo $attributes; ?>>
			<div class="description-video">
				<div class="video-frame">
					<?php echo $overview_video; ?>
				</div>
			</div>
	</section>
<?php endif; ?>