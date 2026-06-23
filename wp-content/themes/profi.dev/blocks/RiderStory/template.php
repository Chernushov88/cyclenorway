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
	'profidev-rider-story',
	!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
]), 'id' => $block['anchor'] ?? null]);

$innerTemplate = array(
	array(
		'core/paragraph',
		array(
			'content' => 'Bjørn is a bikepacker who dabbles in ultra-cycling. Last year he rode the self-supported <a href="#">NorthCape 4000km</a> race finishing in just 11 days! During the pandemic, Bjørn raced <a href="#">3400km around Norway</a> and finished second. In 2023 he head to Belgium for the <a href="#">Transcontinental race</a>.'
		)
	),

	array(
		'core/paragraph',
		array(
			'content' => 'Living in Trondheim, Bjørn knows he’s spoilt for choice when it comes to adventure. Fjords, forests, gravel, and mountains are all on his doorstep. He can basically do it all in one ride. Bjørn is always on the lookout for new roads to ride and test out. He enjoys the planning part of his adventures just as much as he loves riding them. Marking the spots on the map, and connecting the route is part of the fun.'
		)
	),

	array(
		'profidev/quote',
		array(
			'data' => array(
				'content' => '"A common theme on my rides is that I am overly optimistic and way too confident in my own abilities, but it seems to be a good recipe for adventure."​',
				'image' => '',
			)
		)
	),

	array(
		'core/paragraph',
		array(
			'content' => 'In the winter of 2023, Bjørn decided to cycle up to Nordkapp (the top of Europe) from his hometown of Trondheim. It took him just 10 days! His Viking blood runs deep! We look forward to sharing some of his favorite routes with you!'
		)
	)
);

$allowedBlocks = array(
	'core/paragraph',
	'profidev/quote',
);
?>

<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<div class="wrapper">
			<div class="raider-bio">
				<div class="raider-bio-heading">
					<?php if (!empty($fields['title'])): ?>
					<h2 class="no-margin theme-h3 raider-full-name"><?php echo wp_kses_post($fields['title']); ?></h2>
					<?php endif; ?>
					<?php if (!empty($fields['subtitle'])): ?>
					<p class="no-margin theme-h5 raider-location"><?php echo wp_kses_post($fields['subtitle']); ?></p>
					<?php endif; ?>
					<?php if (!empty($fields['socials'])): ?>
					<ul class="no-list raider-bio-socials">
						<?php foreach ($fields['socials'] as $social): ?>
							<?php if (!empty($social['icon']) && !empty($social['link'])): ?>
								<li>
									<a href="<?php echo esc_url($social['link']); ?>" rel="nofollow" title="<?php echo esc_attr($social['link']); ?>" class="no-underine raider-bio-social">
										<img src="<?php echo esc_url($social['icon']); ?>" alt="social" width="24" height="24" loading="lazy">
									</a>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
				</div>
				<?php if (!empty($fields['image']) && is_numeric($fields['image'])): ?>
					<?php echo wp_get_attachment_image($fields['image'], 'full', false, ['class' => 'raider-bio-thumb']); ?>
				<?php endif; ?>
			</div>

			<InnerBlocks class="theme-text-element raider-story" allowedBlocks="<?php echo esc_attr(json_encode($allowedBlocks)); ?>" template="<?php echo esc_attr(json_encode($innerTemplate)); ?>" />
		</div>
	</div>
</section>
