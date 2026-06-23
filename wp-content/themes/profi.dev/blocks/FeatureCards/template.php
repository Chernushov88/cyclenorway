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
$template = get_value_or_default($fields['template'] ?? 'template-1', 'template-1');
$columns = get_value_or_default($fields['columns'] ?? 'three-columns', 'three-columns');

$attributes = get_block_wrapper_attributes([
	'class' => join(' ', array_filter([
		'profidev-feature-cards', 
		$template, 
		!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : '',
		$columns
	])),
	'id' => $block['anchor'] ?? null
]);
$cards = $fields['cards'] ?? [];
$cards = array_filter(is_array($cards) ? $cards : [] , function ($card) {
	return !empty($card['title']) && !empty($card['description']);
});
?>
<section <?php echo $attributes; ?>>
	<?php if (!empty($fields['image']) && is_numeric($fields['image'])): ?>
		<?php echo wp_get_attachment_image($fields['image'], 'full', false, ['loading' => 'lazy']); ?>
	<?php endif; ?>

	<div class="theme-container cards-list">

		<?php if (!empty($fields['title']) && ($template === 'template-2')): ?>
			<h2 class="theme-title"><?php echo wp_kses_post($fields['title']); ?></h2>
		<?php endif; ?>

		<div class="theme-grid cards-list-wrapper">
			<?php foreach ($cards as $card): ?>
			<div class="item">
				<profidev-feature-card class="cards-item">
					<?php if (!empty($card['image']) && is_numeric($card['image'])): ?>
						<?php echo wp_get_attachment_image($card['image'], 'full', false, ['loading' => 'lazy']); ?>
					<?php endif; ?>
					<div class="description">
						<h2 class="no-margin theme-h5 title"><?php echo wp_kses_post($card['title']); ?></h2>

						<?php if (!empty($card['subtitle'])): ?>
						<p class="no-margin theme-h6 subtitle"><?php echo wp_kses_post($card['subtitle']); ?></p>
						<?php endif; ?>

						<div class="theme-text-element">
							<?php echo $card['description']; ?>
						</div>

						<?php if (!empty($card['link']) && is_array($card['link'])): ?>
							<?php echo get_navigation_link($card['link'], ['class' => 'theme-button-primary']); ?>
						<?php endif; ?>
						<?php if ($template === 'template-2'): ?>
							<button type="button" class="no-btn read-more" data-text-less="<?php echo __('Read less', 'profidev-theme'); ?>"><?php echo __('Read more', 'profidev-theme'); ?></button>
						<?php endif; ?>
					</div>
				</profidev-feature-card>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
