<?php

/**
 * Form Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or its parent block.
 *
 */

if (!defined('ABSPATH')) {
	exit;
}


$attributes = get_block_wrapper_attributes(['class' => join(' ', [
	'profidev-blog-posts',
	!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
]), 'id' => $block['anchor'] ?? null]);

$fields = get_fields();
$tax_query = array();
if (!empty($fields['category']) && is_array($fields['category'])) {
	$tax_query[] = array(
		'taxonomy' => 'category',
		'field'    => 'id',
		'terms'    => $fields['category'],
	);
}
$wp_query = new WP_Query([
	'posts_per_page' => 4,
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'tax_query'      => $tax_query,
]);
?>

<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<?php if (!empty($fields['title'])): ?>
			<h2 class="theme-title"><?php echo wp_kses_post($fields['title']); ?></h2>
		<?php endif; ?>
		<div class="post-list">

			<?php if ($wp_query->have_posts()): ?>
				<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<div class="post-item">
						<div class="theme-post-item">
							<a href="<?php echo esc_url(get_the_permalink()); ?>" class="no-underline post-img">
								<?php $thumbnail_id = get_post_thumbnail_id(get_the_ID());

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
									<a href="<?php echo esc_url(get_the_permalink()); ?>" class="no-underline"><?php echo get_the_title(); ?></a>
								</h3>
								<time class="post-date" datetime="<?php echo get_the_date('c'); ?>">
									<?php echo get_the_date('F j, Y'); ?>
								</time>
								<p class="post-excerpt"><?php echo get_the_excerpt(); ?></p>
							</div>
						</div>
					</div>
				<?php endwhile; ?>
			<?php else: ?>
				<p><?php esc_html_e( 'It looks like there are no posts here yet. Check back soon!', 'profidev-theme' ); ?></p>
			<?php endif; ?>

		</div>
		<?php if (!empty($fields['navigation'])): ?>
			<?php echo get_navigation_link( $fields['navigation'], ['class' => 'theme-button-primary-outline more-posts']); ?>
		<?php endif; ?>
	</div>
</section>
