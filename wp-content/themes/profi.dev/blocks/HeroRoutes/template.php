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
		'profidev-hero-routes',
		$fields['align_vertical'],
		$fields['align_horizontal'],
		$fields['template'],
		!empty($fields['enable_header_overlap']) ? 'has-overlap-header' : '',
		!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
	]),
	'id' => $block['anchor'] ?? null
]);

$innerTemplate = [
	[
		'core/heading',
		[
			'level'     => 1,
			'content'   => 'Members blog',
			'className' => 'has-text-align-center'
		]
	]
];
$innerblocks = [
	'core/heading',
	'core/paragraph',
	'core/buttons'
];

?>

<section class="profidev-hero-routes">
	<img src="<?php echo get_template_directory_uri() . '/assets/img/hero-bg.jpg'; ?>" alt="" loading="lazy" class="hero-routes-bg" />
	<div class="theme-container">
		<div class="theme-grid">

			<div class=" theme-text-element">

				<h1 class="wp-block-heading">Your cycling guide in <span>Norway</span></h1>
				<p>Routes, local knowledge, transport advice and planning tools — everything an independent cyclist needs, in one place.</p>

				<div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">
					<div class="wp-block-button is-style-white">
						<a class="wp-block-button__link wp-element-button">Explore Routes</a>
					</div>
					<div class="wp-block-button is-style-primary-btn">
						<a class="wp-block-button__link wp-element-button">Plan My Trip</a>
					</div>
				</div>

				<div class="wp-block-text">
					<span>Trusted by 9,000+ touring cyclists</span>
					<span>500+ route guides</span>
				</div>


				<div class="popular-blocks">
					<div class="title">Popular</div>

					<div class="wp-block-buttons wp-block-buttons-is-layout-flex">
						<div class="wp-block-button is-style-outline is-layout-flex"><a class="wp-block-button__link wp-element-button">Mjølkevegen</a></div>
						<div class="wp-block-button is-style-outline is-layout-flex"><a class="wp-block-button__link wp-element-button">Lofoten</a></div>
						<div class="wp-block-button is-style-outline is-layout-flex"><a class="wp-block-button__link wp-element-button">Gravel Routes</a></div>
						<div class="wp-block-button is-style-outline is-layout-flex"><a class="wp-block-button__link wp-element-button">Northern Norway</a></div>
						<div class="wp-block-button is-style-outline is-layout-flex"><a class="wp-block-button__link wp-element-button">Fjords</a></div>
					</div>
				</div>

			</div>

			<profidev-hero-slider>
				<div class="swiper hero-routes-slider">
					<div class="swiper-wrapper">
						<article class="profidev-hero-route-card swiper-slide">

							<div class="route-media">
								<span class="route-badge">Most Popular</span>
								<img src="<?php echo get_template_directory_uri() . '/assets/img/card-slide-bg.jpg'; ?>" alt="" loading="lazy" class="route-img" />
							</div>
							<div class="route-content">
								<h3 class="route-title">Lofoten, Andøya, & Senja</h3>

								<ul class="route-specs no-list">
									<li class="spec-item">
										<img src="<?php echo get_template_directory_uri() . '/assets/img/spec-distance.svg'; ?>" alt="" class="icon">
										516km
									</li>
									<li class="spec-item ">
										<img src="<?php echo get_template_directory_uri() . '/assets/img/spec-difficulty.svg'; ?>" alt="" class="icon">
										7/10 difficulty
									</li>
									<li class="spec-item ">
										<img src="<?php echo get_template_directory_uri() . '/assets/img/spec-duration.svg'; ?>" alt="" class="icon">
										4-9 days
									</li>
								</ul>

								<div class="route-review">
									<div class="review-stars" aria-label="Rating: 5 stars">
										<span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
									</div>
									<p class="review-text">
										"The route info, maps and practical advice are unbeatable — best trip of my life."
									</p>
									<cite class="review-author">Paul · United Kingdom</cite>
								</div>

								<div class="route-actions">
									<a href="#" class="theme-button-primary">
										<span>View route</span>
									</a>
								</div>
							</div>
						</article>

						<article class="profidev-hero-route-card swiper-slide">

							<div class="route-media">
								<span class="route-badge">Most Popular</span>
								<img src="<?php echo get_template_directory_uri() . '/assets/img/card-slide-bg.jpg'; ?>" alt="" loading="lazy" class="route-img" />
							</div>
							<div class="route-content">
								<h3 class="route-title">Lofoten, Andøya, & Senja 2</h3>

								<ul class="route-specs no-list">
									<li class="spec-item">
										<img src="<?php echo get_template_directory_uri() . '/assets/img/spec-distance.svg'; ?>" alt="" class="icon">
										516km
									</li>
									<li class="spec-item ">
										<img src="<?php echo get_template_directory_uri() . '/assets/img/spec-difficulty.svg'; ?>" alt="" class="icon">
										7/10 difficulty
									</li>
									<li class="spec-item ">
										<img src="<?php echo get_template_directory_uri() . '/assets/img/spec-duration.svg'; ?>" alt="" class="icon">
										4-9 days
									</li>
								</ul>

								<div class="route-review">
									<div class="review-stars" aria-label="Rating: 5 stars">
										<span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
									</div>
									<p class="review-text">
										"The route info, maps and practical advice are unbeatable — best trip of my life."
									</p>
									<cite class="review-author">Paul · United Kingdom</cite>
								</div>

								<div class="route-actions">
									<a href="#" class="theme-button-primary">
										<span>View route</span>
									</a>
								</div>
							</div>
						</article>

					</div>
					<div class="theme-slider-controls">
						<div class="slider-button prev"></div>
						<div class="slider-pagination"></div>
						<div class="slider-button next"></div>
					</div>
				</div>
			</profidev-hero-slider>

		</div>

	</div>

</section>
