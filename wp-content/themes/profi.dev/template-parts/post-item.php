<?php
/**
 * @var array $args
 */
$post_id = $args['route'] ?? null;
if (!$post_id) {
	return;
}
?>
<div class="theme-post-item vertical">
	<a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="no-underline post-img">
		<?php $thumbnail_id = get_post_thumbnail_id($post_id);

		if ($thumbnail_id) :
			echo wp_get_attachment_image($thumbnail_id, [668, 440], false, ['loading'  => 'lazy']);
		else : ?>
			<div class="theme-image-wrapper">
				<img src="<?php echo get_template_directory_uri() . '/assets/img/placeholder.svg'; ?>" alt="" loading="lazy" width="668" height="440">
			</div>
		<?php endif; ?>
	</a>
	<div class="post-description">
		<h3 class="theme-h5 post-title">
			<a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="no-underline"><?php echo get_the_title($post_id); ?></a>
		</h3>
		<time class="post-date" datetime="<?php echo get_the_date('c'); ?>">
			<?php echo get_the_date('F j, Y'); ?>
		</time>
		<p class="post-excerpt"><?php echo get_the_excerpt($post_id); ?></p>
	</div>
</div>
