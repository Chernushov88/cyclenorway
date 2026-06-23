<?php

/**
 * Form Block Template.
 *
 * @var   array $block The block settings and attributes.
 * @var   string $content The block inner HTML (empty).
 * @var   bool $is_preview True during backend preview render.
 * @var   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @var   array $context The context provided to the block by the post or its parent block.
 *
 */

if (!defined('ABSPATH')) {
	exit;
}

$fields = get_fields() ?? [];
$attributes = get_block_wrapper_attributes([
	'class' => join(' ', [
		'profidev-pricing-plans',
		!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
	]),
	'id' => $block['anchor'] ?? null
]);
$plans = $fields['plans'] ?? [];
?>
<section <?php echo $attributes ?>>
	<profidev-pricing-plans>
		<div class="theme-container">
			<div class="theme-grid">
				<?php foreach ($plans as $plan): ?>
				<div class="item <?php echo !empty($plan['is_popular']) && $plan['is_popular'] ? 'popular' : ''; ?>">
					<?php if (!empty($plan['is_popular']) && $plan['is_popular']): ?>
						<span class="badge">Popular</span>
					<?php endif; ?>
					<?php if (!empty($plan['heading'])): ?>
					<div class="head">
						<?php echo wp_kses_post($plan['heading']) ?>
					</div>
					<?php endif; ?>
					<div class="content">
						<?php if (!empty($plan['details']['price'])): ?>
							<p class="plan-price"><?php echo wp_kses_post($plan['details']['price']); ?></p>
						<?php endif; ?>
						<?php if (!empty($plan['details']['description'])): ?>
							<p class="plan-description"><?php echo wp_kses_post($plan['details']['description']); ?></p>
						<?php endif; ?>
						<ul class="list">
							<?php foreach ($plan['information'] as $information): ?>
							<li class="list-item">
								<?php if (!empty($information['icon']) && is_numeric($information['icon'])): ?>
									<?php echo wp_get_attachment_image($information['icon'], 'full', false); ?>
								<?php endif; ?>
								<?php if (!empty($information['text'])): ?>
									<p><?php echo wp_kses_post($information['text']); ?></p>
								<?php endif; ?>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php if (!empty($plan['link']) && is_array($plan['link'])): ?>
						<?php echo get_navigation_link($plan['link'], ['class' => 'theme-button-primary-outline']); ?>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php if (!empty($fields['background_image']) && is_numeric($fields['background_image'])): ?>
			<?php echo wp_get_attachment_image($fields['background_image'], 'full', false, ['class' => 'bg-image']); ?>
		<?php endif; ?>
	</profidev-pricing-plans>
</section>
