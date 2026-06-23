<?php
/**
 * @var   array $block The block settings and attributes.
 * @var   string $content The block inner HTML (empty).
 * @var   bool $is_preview True during backend preview render.
 * @var   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @var   array $context The context provided to the block by the post or its parent block.
 */

if (!defined('ABSPATH')) {
	exit;
}

$fields = get_fields();
$template = get_value_or_default($fields['template'] ?? 'template-1', 'template-1');
$attributes = get_block_wrapper_attributes(['class' => join(' ', [
	'profidev-related-posts',
	$template,
	!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
]), 'id' => $block['anchor'] ?? null]);
$categories = get_the_category( $post_id );
$cat_ids = wp_list_pluck( $categories, 'term_id' );

$args = array(
	'post__not_in'   => array( $post_id ),
	'posts_per_page' => 3,
	'ignore_sticky_posts' => 1
);

if ( $template === 'template-2' ) {
    $args['posts_per_page'] = 2;
    $args['orderby']        = 'date';
    $args['order']          = 'DESC';
} else {
    $categories = get_the_category();
    $cat_ids    = wp_list_pluck( $categories, 'term_id' );
    $args['posts_per_page'] = 3;
    $args['category__in']   = $cat_ids;
}

$related = new WP_Query( $args );

?>
<?php if ( $related->have_posts() ) : ?>
<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<?php if(!empty($fields['title'])): ?>
			<h2 class="theme-title"><?php echo esc_html($fields['title']); ?></h2>
		<?php endif; ?>

		<div class="theme-grid post-list">
			<?php foreach ($related->posts as $post): ?>
			<div class="item">
				<div class="theme-post-item vertical">
					<a href="<?php echo get_the_permalink($post); ?>" class="no-underline post-img">
						<?php if (has_post_thumbnail($post)): ?>
							<?php echo get_the_post_thumbnail($post, 'full', ['class' => 'post-thumb', 'loading' => 'lazy']); ?>
						<?php else: ?>
							<div class="theme-image-wrapper">
								<img class="post-thumb" src="<?php echo get_theme_file_uri('/assets/img/placeholder.svg'); ?>" alt="placeholder" loading="lazy">
							</div>
						<?php endif; ?>
					</a>
					<div class="post-description">
						<h3 class="theme-h5 post-title">
							<a href="<?php echo get_the_permalink($post); ?>" class="no-underline"><?php echo get_the_title($post); ?></a>
						</h3>
						<time class="post-date" datetime="<?php echo get_the_date('Y-m-d', $post); ?>"><?php echo get_the_date('F j, Y', $post); ?></time>
						<?php if($template !== 'template-2'): ?>
							<p class="post-excerpt"><?php echo get_the_excerpt($post); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>
