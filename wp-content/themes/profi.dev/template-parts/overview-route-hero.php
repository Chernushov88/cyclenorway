<?php
/**
 * @var array $args
 */
$fields = get_fields_or_template($args['post_id'], $args['is_preview'], 'overview_route_fields');
$images = get_value_or_default($fields['overview_gallery'], []);
$overview_header_card = get_value_or_default($fields['overview_header_card'], []);
$overview_heading_table = get_value_or_default($fields['overview_heading']['table'], []);
$overview_weather = get_value_or_default($fields['overview_heading']['weather'], "");
$overview_description = get_value_or_default($fields['overview_heading']['description'], "");
$is_table_exists = !empty(array_filter(array_values($overview_heading_table)));
$thumb_id = get_post_thumbnail_id();
$classNames = ['profidev-route-hero-overview'];

if (!empty($args['fields']['mepr_rule_id'])) {
	$hasAccess = current_user_can( 'mepr-active', 'rule:' . $args['fields']['mepr_rule_id'] );
	if (!$hasAccess) {
		if ( preg_match_all( '/<p[^>]*>.*?<\/p>/is', $overview_description, $matches ) ) {
			$overview_description = implode( '', array_slice( $matches[0], 0, 2 ) );
		}
		$classNames[] = 'paywall-protected-content';
	}
}
$attributes = get_block_wrapper_attributes(['class' => join(' ', $classNames), 'id' => $args['id'] ?? null]);
?>
<section <?php echo $attributes; ?>>
	<div class="main-info">
		<div class="main-info-hero <?php echo empty($images) ? 'desctop-show' : '' ?>">
			<?php if(!empty($thumb_id)): ?>
				<?php echo wp_get_attachment_image( $thumb_id, 'full', false, ['loading' => 'eager'] ); ?>
			<?php else: ?>
				<div class="theme-image-wrapper">
					<img class="image" src="<?php echo get_theme_file_uri('assets/img/placeholder.svg'); ?>" alt="placeholder" loading="eager">
				</div>
			<?php endif; ?>
		</div>
		<div class="theme-container">
			<div class="main-info-content">
				<img class="image-status" src="<?php echo get_template_directory_uri() . '/assets/img/overview.svg'; ?>" width="114" height="113px" alt="placeholder" loading="eager">
				<h1 class="theme-h3 title"><?php the_title(); ?></h1>
				<?php if (!empty($overview_header_card['subtitle'])): ?>
					<p class="theme-h5 date"><?php echo $overview_header_card['subtitle']; ?></p>
				<?php endif; ?>
				<div class="main-info-btns">
					<?php if (!empty($overview_header_card['full_story']) && is_numeric($overview_header_card['full_story']) &&
					get_post_status($overview_header_card['full_story']) === 'publish'): ?>
						<a href="<?php echo get_the_permalink($overview_header_card['full_story']); ?>" class="theme-button-primary">
							<?php echo __('Go to the full story', 'profidev-theme'); ?>
						</a>
					<?php endif; ?>
					<?php if (!empty($overview_header_card['blog_post']) && is_numeric($overview_header_card['blog_post'])): ?>
						<a href="<?php echo get_the_permalink($overview_header_card['blog_post']); ?>" class="theme-button-primary post-link">
							<?php echo __('Read the blog post', 'profidev-theme'); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
			<?php if (is_array($images) && count($images)): ?>
				<profidev-gallery-grid>
					<div class="gallery-grid js-gallery">
						<?php
							$desktop_limit = 5;
							$mobile_limit = 3;

							$desktop_index = $desktop_limit - 1;
							$mobile_index = $mobile_limit - 1;

							$total = count($images);
						?>
						<?php foreach ($images as $index => $image): ?>
							<?php if(is_numeric($image)): ?>
								<?php $image_meta = wp_get_attachment_image_src($image, 'full'); ?>
								<?php if(is_array($image_meta)): ?>
								<div class="<?php echo implode(' ', ['gallery-item', $index > 4 ? 'is-limited-item' : '']); ?>">
									<a
										href="<?php echo esc_url($image_meta[0]); ?>"
										data-pswp-width="<?php echo esc_attr($image_meta[1]); ?>"
										data-pswp-height="<?php echo esc_attr($image_meta[2]); ?>"
									>
										<?php echo wp_get_attachment_image($image, 'full', false, ['loading' => 'lazy']); ?>
										<?php if (
											($index === $desktop_index && $total > $desktop_limit) ||
											($index === $mobile_index && $total > $mobile_limit)
										): ?>
											<div class="gallery-more-overlay <?php echo ($index === 2 && count($images) > 3) ? 'is-mobile' : (($index === 4 && count($images) > 5) ? 'is-desktop' : ''); ?>">
												<span class="counter-text">
													<?php
													$hidden = ($index === $desktop_index)
														? ($total - $desktop_limit)
														: ($total - $mobile_limit);

													echo sprintf(
														_n(
															'+%s image',
															'+%s images',
															$hidden,
															'profidev-theme'
														),
														number_format_i18n($hidden)
													);
													?>
												</span>
											</div>
										<?php endif; ?>
									</a>
								</div>
								<?php endif; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</profidev-gallery-grid>
			<?php endif; ?>
		</div>
	</div>
	<?php if ($is_table_exists || !empty($overview_weather) || !empty($overview_description)): ?>
		<div class="theme-container">
			<?php if ($is_table_exists): ?>
				<div class="theme-grid route-stats">
					<?php if (!empty($overview_heading_table['distance'])): ?>
						<div class="item route-stats-item">
							<div class="icon distance" aria-hidden="true"></div>
							<p class="theme-h6 value"><?php echo wp_kses_post($overview_heading_table['distance']); ?></p>
							<p class="label"><?php echo __('Distance', 'profidev-theme'); ?></p>
						</div>
					<?php endif; ?>
					<?php if (!empty($overview_heading_table['elev_gain'])): ?>
						<div class="item route-stats-item">
							<div class="icon ascent" aria-hidden="true"></div>
							<p class="theme-h6 value"><?php echo wp_kses_post($overview_heading_table['elev_gain']); ?></p>
							<p class="label"><?php echo __('Elev. Gain', 'profidev-theme'); ?></p>
						</div>
					<?php endif; ?>
					<?php if (!empty($overview_heading_table['days'])): ?>
						<div class="item route-stats-item">
							<div class="icon time" aria-hidden="true"></div>
							<p class="theme-h6 value"><?php echo wp_kses_post($overview_heading_table['days']); ?></p>
							<p class="label"><?php echo __('Days', 'profidev-theme'); ?></p>
						</div>
					<?php endif; ?>
					<?php if (!empty($overview_heading_table['unpaved'])): ?>
						<div class="item route-stats-item">
							<div class="icon surface" aria-hidden="true"></div>
							<p class="theme-h6 value"><?php echo wp_kses_post($overview_heading_table['unpaved']); ?></p>
							<p class="label"><?php echo __('Unpaved', 'profidev-theme'); ?></p>
						</div>
					<?php endif; ?>
					<?php if (!empty($overview_heading_table['difficulty'])): ?>
						<div class="item route-stats-item">
							<div class="icon difficulty" aria-hidden="true"></div>
							<p class="theme-h6 value"><?php echo wp_kses_post($overview_heading_table['difficulty']); ?></p>
							<p class="label"><?php echo __('Difficulty', 'profidev-theme'); ?></p>
						</div>
					<?php endif; ?>
					<?php if (!empty($overview_heading_table['ideal_tyres'])): ?>
						<div class="item route-stats-item">
							<div class="icon types" aria-hidden="true"></div>
							<p class="theme-h6 value"><?php echo wp_kses_post($overview_heading_table['ideal_tyres']); ?></p>
							<p class="label"><?php echo __('Ideal Types', 'profidev-theme'); ?></p>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if (!empty($overview_weather) || !empty($overview_description)): ?>
			<div class="description">
				<?php if (!empty($overview_weather)): ?>
					<div class="description-weather">
						<div class="weather-frame">
							<?php echo strip_tags($overview_weather, ['iframe']); ?>
						</div>
					</div>
				<?php endif; ?>
				<?php if (!empty($overview_description)): ?>
				<div class="description-text">
					<div class="theme-text-element">
						<h2><?php the_title(); ?></h2>
						<?php echo $overview_description; ?>
					</div>
				</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</section>
