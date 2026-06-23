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
		'profidev-route-cards',
		!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
	]),
	'id' => $block['anchor'] ?? null
]);
?>
<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<div class="theme-heading">
			<div class="heading-text">
				<?php if (!empty($fields['title'])): ?>
					<h2 class="theme-title"><?php echo wp_kses_post($fields['title']); ?></h2>
				<?php endif; ?>
				<?php if (!empty($fields['description'])): ?>
					<p class="no-margin"><?php echo wp_kses_post($fields['description']); ?></p>
				<?php endif; ?>
			</div>
			<?php if (!empty($fields['map'])): ?>
				<a href="<?php echo esc_url($fields['map']); ?>" class="theme-button-primary heading-btn">
					<?php echo __('Map overview', 'profidev-theme'); ?>
				</a>
			<?php endif; ?>
		</div>
		<div class="theme-grid route-list">

			<?php foreach ($fields['routes'] as $route): ?>
				<div class="item">
					<?php get_template_part('template-parts/route', 'item', ['route' => $route]); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
