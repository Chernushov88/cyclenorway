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
$attributes = get_block_wrapper_attributes(['class' => join(' ', [
	'profidev-related-routes',
	!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
]), 'id' => $block['anchor'] ?? null]);
$blockFields = get_field('related_routes');
$postFields = get_field('related_routes', $post_id);
$fields = [
	'title' => $postFields['title'] ?? $blockFields['title'] ?? '',
	'routes' => $postFields['routes'] ?? $blockFields['routes'] ?? [],
];
?>

<?php if(!empty($fields['routes'])): ?>
<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<h2 class="theme-title"><?php echo wp_kses_post($fields['title']); ?></h2>
		<div class="theme-grid post-list">
			<?php foreach ($fields['routes'] as $route): ?>
				<div class="item">
					<?php get_template_part('template-parts/route', 'item', ['route' => $route]); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>
