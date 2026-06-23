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

$fields = get_fields() ?? [];
$attributes = get_block_wrapper_attributes(['class' => join(' ', [
	'profidev-single-post',
	!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : '',
]), 'id' => $block['anchor'] ?? null]);
$post = get_post($post_id);
$author_id = $post->post_author;
?>
<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<ul class="no-list post-info">
			<li itemprop="datePublished"><time class="post-date" datetime="<?php echo get_the_date('Y-m-d', $post_id); ?>"><?php echo get_the_date('F j, Y', $post_id); ?></time></li>
			<li itemprop="author"><span class="post-author"><?php echo get_the_author_meta('display_name', $author_id); ?></span></li>
		</ul>
		<h1 class="theme-h3 post-title"><?php echo get_the_title(); ?></h1>

		<?php if (has_post_thumbnail($post_id)): ?>
			<?php echo get_the_post_thumbnail($post_id, 'full', ['class' => 'post-thumb', 'loading' => 'eager']); ?>
		<?php else: ?>
			<div class="theme-image-wrapper">
				<img class="post-thumb" src="<?php echo get_theme_file_uri('/assets/img/placeholder.svg'); ?>" alt="placeholder" loading="eager">
			</div>
		<?php endif; ?>

		<InnerBlocks class="theme-text-element post-content" />
	</div>
</section>
