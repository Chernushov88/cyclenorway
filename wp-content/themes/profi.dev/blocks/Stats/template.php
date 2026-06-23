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
$attributes = get_block_wrapper_attributes(['class' => join(' ', [
	'profidev-stats',
	!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : '',
]), 'id' => $block['anchor'] ?? null]);
?>

<section <?php echo $attributes; ?>>
	<?php if (!empty($fields['background_image'])): ?>
		<?php echo wp_get_attachment_image($fields['background_image'], 'full', false, ['class' => 'stats-bg', 'alt' => '', 'aria-hidden' => 'true']); ?>
	<?php endif; ?>
	<div class="theme-container">
		<?php if (!empty($fields['title'])): ?>
			<h2 class="stats-heading"><?php echo nl2br(wp_kses_post($fields['title'])); ?></h2>
		<?php endif; ?>
		<div class="theme-grid stats-list">
			<?php if (is_array($fields['statistics'])): ?>
				<?php foreach ($fields['statistics'] as $stat): ?>
					<div class="item">
						<div class="stats-item">
							<h3 class="no-margin stats-title">
								<b class="stats-number"><?php echo wp_kses_post($stat['count']); ?></b>
								<span class="stats-text"><?php echo wp_kses_post($stat['title']); ?></span>
							</h3>
							<p class="no-margin stats-description"><?php echo wp_kses_post($stat['description']); ?></p>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>
