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
		'profidev-reviews',
		!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
	]),
	'id' => $block['anchor'] ?? null
]);
$reviews = $fields['reviews'] ?? [];
?>
<section <?php echo $attributes ?>>
	<profidev-review>
		<div class="theme-container">
			<?php if (!empty($fields['title'])): ?>
				<h2><?php echo wp_kses_post($fields['title']); ?></h2>
			<?php endif; ?>
			<div class="theme-grid">
				<?php foreach ($reviews as $review): ?>
					<div class="item">
						<div class="bg-element-top" aria-hidden="true">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2600 131.1" preserveAspectRatio="none">
								<path d="M0 0L2600 0 2600 69.1 0 0z"></path>
								<path style="opacity:0.5" d="M0 0L2600 0 2600 69.1 0 69.1z"></path>
								<path style="opacity:0.25" d="M2600 0L0 0 0 130.1 2600 69.1z"></path>
							</svg>
						</div>
						<?php if (!empty($review['review'])): ?>
							<p class="review"><?php echo wp_kses_post($review['review']); ?></p>
						<?php endif; ?>
						<?php if (!empty($review['star']) && is_numeric($review['star'])): ?>
						<div class="stars">
							<?php for ($i = 1; $i <= $review['star']; $i++): ?>
								<span class="star"></span>
							<?php endfor; ?>
						</div>
						<?php endif; ?>
						<?php if (!empty($review['author'])): ?>
							<p class="author"><?php echo wp_kses_post($review['author']); ?></p>
						<?php endif; ?>
						<?php if (!empty($review['location'])): ?>
							<p class="location"><?php echo wp_kses_post($review['location']); ?></p>
						<?php endif; ?>
						<div class="bg-element-bottom" aria-hidden="true">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 283.5 19.6" preserveAspectRatio="none">
								<path style="opacity:0.33" d="M0 0L0 18.8 141.8 4.1 283.5 18.8 283.5 0z"></path>
								<path style="opacity:0.33" d="M0 0L0 12.6 141.8 4 283.5 12.6 283.5 0z"></path>
								<path style="opacity:0.33" d="M0 0L0 6.4 141.8 4 283.5 6.4 283.5 0z"></path>
								<path d="M0 0L0 1.2 141.8 4 283.5 1.2 283.5 0z"></path>
							</svg>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php if (!empty($fields['background_image']) && is_numeric($fields['background_image'])): ?>
			<?php echo wp_get_attachment_image($fields['background_image'], 'full', false, ['class' => 'bg-image']); ?>
		<?php endif; ?>
	</profidev-review>
</section>
