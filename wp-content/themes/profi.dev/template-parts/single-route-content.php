<?php
/**
 * @var array $args
 */
$attributes = get_block_wrapper_attributes(['class' => 'profidev-route-content', 'id' => $args['id'] ?? null]);
$fields = get_fields_or_template($args['post_id'], $args['is_preview'], 'single_route_fields');
$title = null;
$content = null;

$template = $args['fields']['template'];
$single_route_content = $args['fields']['single_route_content'] ?? null;
if ($template === 'single-route-content') {
	switch ($single_route_content) {
		case 'more-about':
			$title = !empty($fields['more_about']['title']) ? $fields['more_about']['title'] : null;
			$content = !empty($fields['more_about']['content']) ? $fields['more_about']['content'] : null;
			break;
		case 'more-about-second':
			$title = !empty($fields['more_about_second']['title']) ? $fields['more_about_second']['title'] : null;
			$content = !empty($fields['more_about_second']['content']) ? $fields['more_about_second']['content'] : null;
			break;
		case 'highlights':
			$title = !empty($fields['highlights']['title']) ? $fields['highlights']['title'] : __('Highlights', 'profidev-theme');
			$content = !empty($fields['highlights']['content']) ? $fields['highlights']['content'] : null;
			break;
		case 'must-know':
			$title = !empty($fields['must_know']['title']) ? $fields['must_know']['title'] : __('Must know', 'profidev-theme');
			$content = !empty($fields['must_know']['content']) ? $fields['must_know']['content'] : null;
			break;
		case 'accommodation':
			$title = !empty($fields['accommodation']['title']) ? $fields['accommodation']['title'] :  __('Accommodation', 'profidev-theme');
			$content = !empty($fields['accommodation']['content']) ? $fields['accommodation']['content'] : null;
			break;
		case 'transport':
			$title = !empty($fields['transport']['title']) ? $fields['transport']['title'] : __('Transport', 'profidev-theme');
			$content = !empty($fields['transport']['content']) ? $fields['transport']['content'] : null;
			break;
		case 'safety':
			$title = !empty($fields['safety']['title']) ? $fields['safety']['title'] : __('Safety', 'profidev-theme');
			$content = !empty($fields['safety']['content']) ? $fields['safety']['content'] : null;
			break;
	}
}

if (empty($content)) {
	if (!$args['post_id'] && $args['is_preview']) {
		get_template_part('template-parts/preview-warning', null);
	}
	return;
}
?>
<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<div class="wrapper">
			<div class="theme-text-element right">
				<?php if (!empty($title)): ?>
				<div class="heading">
					<h2 class="title"><?php echo wp_kses_post($title); ?></h2>
				</div>
				<?php endif; ?>
				<?php if (!empty($content)): ?>
					<?php echo wp_kses_post($content); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
