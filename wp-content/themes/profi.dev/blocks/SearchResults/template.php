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

$search = $_REQUEST['s'];
$paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
$fields = get_fields();
$attributes = get_block_wrapper_attributes(['class' => join(' ', [
	'profidev-search-results',
	!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : '',
]), 'id' => $block['anchor'] ?? null]);

$query = new WP_Query([
	'posts_per_page' => get_option( 'posts_per_page' ),
	'post_type' => ['post', 'routes'],
	'paged' => $paged,
	's' => $search,
]);

?>
<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<h1 class="theme-title"><?php echo sprintf(__('Search results for: “%s”', 'profidev-theme'), esc_html($search)); ?></h1>

		<?php if ($query->have_posts()): ?>
			<div class="theme-grid results-list">
				<?php foreach ($query->posts as $post): ?>
					<?php if ($post->post_type === 'routes'): ?>
					<div class="item">
						<?php get_template_part('template-parts/route', 'item', ['route' => $post->ID]); ?>
					</div>
					<?php elseif ($post->post_type === 'post'): ?>
					<div class="item">
						<?php get_template_part('template-parts/post', 'item', ['route' => $post->ID]); ?>
					</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

			<nav class="theme-pagination navigation pagination" aria-label="Page numbering">
				<div class="nav-links">
					<?php echo paginate_links([
						'base'         => '%_%',
						'format'       => '?page=%#%',
						'total' => $query->max_num_pages,
						'current' => $paged
					]); ?>
				</div>
			</nav>
		<?php else: ?>
			<p class="theme-h5 no-found-results">No found</p>
		<?php endif; ?>
	</div>
</section>
