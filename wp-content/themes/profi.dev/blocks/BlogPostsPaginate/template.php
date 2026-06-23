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
$categories = get_categories([
	'hide_empty' => false,
	'orderby'    => 'name',
	'order'      => 'ASC',
	'exclude'    => [17]
]);
global $wp_query;
$queried = get_queried_object();
?>

<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<?php if (!empty($categories) && is_array($categories)): ?>
			<ul class="no-list category-list">
				<li class="<?php echo $queried instanceof WP_Post ? 'active' : ''; ?>">
					<a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>">
						<?php echo __('All', 'profidev-theme'); ?>
					</a>
				</li>
				<?php foreach ($categories as $category): ?>
					<li class="<?php echo $queried instanceof WP_Term && $queried->term_id === $category->term_id ? 'active' : ''; ?>"><a class="no-underline" href="<?php echo esc_url(get_term_link($category)); ?>"><?php echo wp_kses_post($category->name); ?></a></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<div class="post-list">

			<?php if ($wp_query->have_posts()): ?>
				<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<div class="post-item">
						<div class="theme-post-item">
							<a href="<?php echo esc_url(get_the_permalink()); ?>" class="no-underline post-img">
								<?php $thumbnail_id = get_post_thumbnail_id(get_the_ID());

								if ($thumbnail_id) :
									echo wp_get_attachment_image($thumbnail_id, [207, 141], false, ['loading'  => 'lazy']);
								else : ?>
									<img src="<?php echo get_template_directory_uri() . '/assets/img/placeholder.svg'; ?>" alt="" loading="lazy" width="207" height="141">
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

		<nav class="theme-pagination navigation pagination" aria-label="Page numbering">
			<div class="nav-links">
				<?php echo paginate_links(); ?>
			</div>
		</nav>
	</div>
</section>
