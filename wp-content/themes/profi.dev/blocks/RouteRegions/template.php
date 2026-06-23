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
$fields = get_fields() ?? [];
$attributes = get_block_wrapper_attributes([
	'class' => join(' ', [
		'profidev-route-regions',
		!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : '',
	]),
	'id' => $block['anchor'] ?? null
]);
$regions = array_filter($fields['regions'] ?? [], function ($region) {
		return !empty($region['title']) &&
				!empty($region['description']) &&
				!empty($region['image']) &&
				is_numeric($region['image']) &&
				!empty($region['location']) &&
				is_numeric($region['location']);
});
?>
<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<?php if (!empty($fields['title'])): ?>
			<h2 class="theme-title"><?php echo wp_kses_post($fields['title']); ?></h2>
		<?php endif; ?>
		<div class="theme-grid regions-list">
			<?php foreach ($regions as $region): ?>
			<div class="item">
				<a href="<?php echo esc_url(get_term_link($region['location'])); ?>" class="no-underline region-item">
					<?php echo wp_get_attachment_image($region['image'], 'full', false, ['loading' => 'lazy']); ?>
					<h3 class="no-margin"><?php echo wp_kses_post($region['title']); ?></h3>
					<p class="no-margin"><?php echo wp_kses_post($region['description']); ?></p>
				</a>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
