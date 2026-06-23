<?php
/**
 * @var array $args
 */
$attributes = get_block_wrapper_attributes(['class' => 'profidev-route-hero-full', 'id' => $args['id'] ?? null]);
$post_id = $args['post_id'];
$fields = get_fields_or_template($args['post_id'], $args['is_preview'], 'single_route_fields');
$navigation = [];
if (!empty($fields['highlights']['title']) || !empty($fields['highlights']['content'])) {
	$navigation[] = [
		'href' => '#highlights',
		'text' => __('Highlights', 'profidev-theme')
	];
}
if (!empty($fields['must_know']['title']) || !empty($fields['must_know']['content'])) {
	$navigation[] = [
		'href' => '#must_known',
		'text' => __('Must know', 'profidev-theme')
	];
}
if (!empty($fields['transport']['title']) || !empty($fields['transport']['content'])) {
	$navigation[] = [
		'href' => '#transport',
		'text' => __('Transport', 'profidev-theme')
	];
}
if (!empty($fields['accommodation']['title']) || !empty($fields['accommodation']['content'])) {
	$navigation[] = [
		'href' => '#accommodation',
		'text' => __('Accommodation', 'profidev-theme')
	];
}
if (!empty($fields['safety']['title']) || !empty($fields['safety']['content'])) {
	$navigation[] = [
		'href' => '#safety',
		'text' => __('Safety', 'profidev-theme')
	];
}

$is_paywall = str_contains(get_page_template_slug($post_id), 'paywall') && !is_user_logged_in();
?>
<section <?php echo $attributes; ?>>
	<div class="main-info">
		<?php if (!$args['post_id'] && $args['is_preview']): ?>
			<div class="theme-image-wrapper">
				<img class="image" src="<?php echo get_template_directory_uri() . '/assets/img/placeholder.svg'; ?>" alt="placeholder" loading="eager">
			</div>
		<?php else: ?>
			<?php echo get_the_post_thumbnail($post_id, 'full', ['class' => 'image', 'loading' => 'eager']); ?>
		<?php endif; ?>
		<div class="theme-container">
			<div class="main-info-content">
				<img class="image-status" src="<?php echo get_template_directory_uri() . '/assets/img/full-story.svg'; ?>" width="114" height="113px" alt="placeholder" loading="eager">
				<h1 class="theme-h3 title"><?php echo get_the_title($post_id); ?></h1>
				<p class="theme-h5 location"><?php echo wp_kses_post($fields['header_card']['subtitle']); ?></p>
				<p class="short-description"><?php echo wp_kses_post($fields['header_card']['short_description']); ?></p>
				<div class="main-info-btns">
					<?php if (!empty($fields['main_map'])): ?>
						<a href="#main-map" class="theme-button-primary <?php echo $is_paywall ? 'disabled' : ''; ?>"><?php echo __('Route', 'profidev-theme'); ?></a>
					<?php endif; ?>
					<?php if (!empty($fields['header_card']['gpx_button'])): ?>
						<a href="<?php echo is_user_logged_in() ? esc_url($fields['header_card']['gpx_button']) : '#'; ?>" rel="nofollow" class="theme-button-primary-outline download <?php echo $is_paywall ? 'disabled' : ''; ?>">
							<?php echo __('Download GPX', 'profidev-theme'); ?>
						</a>

						<?php
						$link = $fields['header_card']['info_link'];
						if( $link ):
							$link_url = $link['url'];
							$link_title = $link['title'];
							$link_target = $link['target'] ? $link['target'] : '_self';
							?>
							<a class="info" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>" title="<?php echo esc_attr__('More information', 'profidev-theme'); ?>"></a>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="theme-container description">
		<div class="description-meta">
			<div class="meta-head">
				<?php if (!empty($fields['heading']['table']['when'])): ?>
				<div class="date">
					<p class="label"><?php echo __('When', 'profidev-theme'); ?></p>
					<p class="value"><?php echo wp_kses_post($fields['heading']['table']['when']); ?></p>
				</div>
				<?php endif; ?>
				<?php if (!empty($fields['heading']['table']['author_name'])): ?>
				<div class="author">
					<p class="label"><?php echo __('Written by', 'profidev-theme'); ?></p>
					<a href="<?php echo empty($fields['heading']['table']['author_url']) ? '#' : esc_url($fields['heading']['table']['author_url']); ?>" class="value"><?php echo wp_kses_post($fields['heading']['table']['author_name']); ?></a>
				</div>
				<?php endif; ?>
			</div>
			<div class="meta-body">
				<?php if (!empty($fields['heading']['table']['distance'])): ?>
				<div class="meta-item">
					<div class="icon distance" aria-hidden="true"></div>
					<div class="info">
						<p class="label"><?php echo __('Distance', 'profidev-theme'); ?></p>
						<p class="theme-h6 value"><?php echo wp_kses_post($fields['heading']['table']['distance']); ?></p>
					</div>
				</div>
				<?php endif; ?>
				<?php if (!empty($fields['heading']['table']['days'])): ?>
					<div class="meta-item">
						<div class="icon time" aria-hidden="true"></div>
						<div class="info">
							<p class="label"><?php echo __('Days', 'profidev-theme'); ?></p>
							<p class="theme-h6 value"><?php echo wp_kses_post($fields['heading']['table']['days']); ?></p>
						</div>
					</div>
				<?php endif; ?>
				<?php if (!empty($fields['heading']['table']['high_point'])): ?>
					<div class="meta-item">
						<div class="icon hight-point" aria-hidden="true"></div>
						<div class="info">
							<p class="label"><?php echo __('High point', 'profidev-theme'); ?></p>
							<p class="theme-h6 value"><?php echo wp_kses_post($fields['heading']['table']['high_point']); ?></p>
						</div>
					</div>
				<?php endif; ?>
				<?php if (!empty($fields['heading']['table']['difficulty'])): ?>
					<div class="meta-item">
						<div class="icon difficulty" aria-hidden="true"></div>
						<div class="info">
							<p class="label"><?php echo __('Difficulty', 'profidev-theme'); ?></p>
							<p class="theme-h6 value"><?php echo wp_kses_post($fields['heading']['table']['difficulty']); ?></p>
						</div>
					</div>
				<?php endif; ?>
				<?php if (!empty($fields['heading']['table']['unpaved'])): ?>
					<div class="meta-item">
						<div class="icon surface" aria-hidden="true"></div>
						<div class="info">
							<p class="label"><?php echo __('Unpaved', 'profidev-theme'); ?></p>
							<p class="theme-h6 value"><?php echo wp_kses_post($fields['heading']['table']['unpaved']); ?></p>
						</div>
					</div>
				<?php endif; ?>
				<?php if (!empty($fields['heading']['table']['total_elevation'])): ?>
					<div class="meta-item">
						<div class="icon ascent" aria-hidden="true"></div>
						<div class="info">
							<p class="label"><?php echo __('Total Ascent', 'profidev-theme'); ?></p>
							<p class="theme-h6 value"><?php echo wp_kses_post($fields['heading']['table']['total_elevation']); ?></p>
						</div>
					</div>
				<?php endif; ?>
				<?php if (!empty($fields['heading']['table']['ferries'])): ?>
					<div class="meta-item">
						<div class="icon ferries" aria-hidden="true"></div>
						<div class="info">
							<p class="label"><?php echo __('Ferries', 'profidev-theme'); ?></p>
							<p class="theme-h6 value"><?php echo wp_kses_post($fields['heading']['table']['ferries']); ?></p>
						</div>
					</div>
				<?php endif; ?>
				<?php if (!empty($fields['heading']['table']['tunnels'])): ?>
					<div class="meta-item">
						<div class="icon tunnels" aria-hidden="true"></div>
						<div class="info">
							<p class="label"><?php echo __('Tunnels', 'profidev-theme'); ?></p>
							<p class="theme-h6 value"><?php echo wp_kses_post($fields['heading']['table']['tunnels']); ?></p>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="description-text">
			<div class="theme-text-element">
				<?php if (!empty($fields['heading']['details']['title'])): ?>
					<h2><?php echo wp_kses_post($fields['heading']['details']['title']); ?></h2>
				<?php endif; ?>
				<?php if (!empty($fields['heading']['details']['description'])): ?>
					<?php echo wp_kses_post($fields['heading']['details']['description']); ?>
				<?php endif; ?>
			</div>
			<!-- TODO: Create navigation -->
			<?php if (!empty($navigation)): ?>
			<div class="post-navigation">
				<p class="heading"><?php echo __('Jump to:', 'profidev-theme'); ?></p>
				<ul class="no-list navigations">
					<?php foreach ($navigation as $item): ?>
						<li><a href="<?php echo esc_attr($item['href']); ?>"><?php echo $item['text']; ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>
