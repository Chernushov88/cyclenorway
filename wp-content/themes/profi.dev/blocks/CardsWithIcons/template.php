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

$attributes = get_block_wrapper_attributes([
	'class' => join(' ', [
		'profidev-cards-with-icons',
		!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
	]),
	'id' => $block['anchor'] ?? null
]);
$fields = get_fields() ?? [];
$cards = array_filter(get_value_or_default($fields['cards'], []), function ($item) {
	return !empty($item['title']) && !empty($item['image']) && is_numeric($item['image']);
});
?>
<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<div class="wrapper">
			<div class="theme-text-element">
				<?php if (!empty($fields['title'])): ?>
					<h2 class="theme-h3 title"><?php echo wp_kses_post($fields['title']); ?></h2>
				<?php endif; ?>
				<?php if (!empty($fields['description'])): ?>
					<p class="has-text-xl-font-size"><?php echo wp_kses_post($fields['description']); ?></p>
				<?php endif; ?>
			</div>
			<div class="cards <?php echo esc_attr($fields['cards_bg_color']); ?>">
				<ul class="no-list theme-grid">
					<?php foreach ($cards as $card): ?>
					<li class="item">
						<a href="<?php echo empty($card['link']) ? '#' : esc_url($card['link']); ?>" class="list-item">
							<div class="icon">
								<?php echo wp_get_attachment_image($card['image'], 'full', false, ['loading' => 'lazy']); ?>
								<?php if (!empty($card['is_locked']) && !is_user_logged_in()): ?>
									<span class="restricted-tooltip" data-tooltip="<?php echo esc_attr__('Available to Planner/Annual users only', 'profidev-theme'); ?>"><span class="restricted-icon"></span></span>
								<?php endif; ?>
							</div>
							<h3 class="theme-h6"><?php echo wp_kses_post($card['title']); ?></h3>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</section>
