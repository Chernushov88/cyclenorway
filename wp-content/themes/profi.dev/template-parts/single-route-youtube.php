<?php
/**
 * @var array $args
 */
$attributes = get_block_wrapper_attributes(['class' => 'profidev-route-youtube', 'id' => $args['id'] ?? null]);
$fields = get_fields_or_template($args['post_id'], $args['is_preview'], 'single_route_fields');
$url = $fields['youtube'] ?? null;
if (empty($url)) {
	if (!$args['post_id'] && $args['is_preview']) {
		get_template_part('template-parts/preview-warning', null);
	}
	return;
}

parse_str(parse_url($url, PHP_URL_QUERY), $vars);
if (!isset($vars['v'])) {
	if (!$args['post_id'] && $args['is_preview']) {
		get_template_part('template-parts/preview-warning', null);
	}
	return;
}
$video_id = $vars['v'];
$embed_url = "https://www.youtube.com/embed/" . $video_id;
?>
<section class="profidev-route-youtube">
	<div class="theme-container">
		<h2 class="title"><?php echo __('Video', 'profidev-theme'); ?></h2>
		<iframe
			width="640"
			height="360"
			src="<?php echo $embed_url; ?>"
			frameborder="0"
			allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
			referrerpolicy="strict-origin-when-cross-origin"
			allowfullscreen>
		</iframe>
	</div>
</section>
