<?php
/**
 * @var array $args
 */
$post_id = $args['route'] ?? null;
if (!$post_id) {
	return;
}
$fields = get_fields($post_id);
$overview_header_card = $fields['overview_header_card'] ?? null;
$overview_heading_table = $fields['overview_heading']['table'] ?? null;
$access_status = is_user_logged_in() ? 'full' : 'must-login';

?>
<div class="theme-route-item" data-access="full">
	<div class="route-image">
		<?php if (has_post_thumbnail($post_id)): ?>
			<?php echo get_the_post_thumbnail($post_id, 'full', ['class' => 'image']); ?>
		<?php else: ?>
			<div class="theme-image-wrapper">
				<img class="image" src="<?php echo get_theme_file_uri('/assets/img/placeholder.svg'); ?>" alt="placeholder" loading="eager">
			</div>
		<?php endif; ?>
	</div>
	<div class="route-content">
		<h3 class="theme-h6 route-title"><?php echo get_the_title($post_id); ?></h3>
		<p class="route-description">
			<?php echo get_the_excerpt($post_id); ?>
		</p>
		<ul class="no-list route-info">
			<?php if (!empty($overview_heading_table['distance'])): ?>
				<li class="route-distance"><?php echo $overview_heading_table['distance']; ?></li>
			<?php endif; ?>
			<?php if (!empty($overview_heading_table['unpaved'])): ?>
				<li class="route-surface"><?php echo $overview_heading_table['unpaved']; ?> <?php echo __('unpaved', 'profidev-theme'); ?></li>
			<?php endif; ?>
			<?php if (!empty($overview_heading_table['days'])): ?>
				<li class="route-time"><?php echo $overview_heading_table['days']; ?> <?php echo __('days', 'profidev-theme'); ?></li>
			<?php endif; ?>
			<?php if (!empty($overview_heading_table['difficulty'])): ?>
				<li class="route-difficulty"><?php echo $overview_heading_table['difficulty']; ?> <?php echo __('difficulty', 'profidev-theme'); ?></li>
			<?php endif; ?>
		</ul>
		<div class="route-btns">
			<?php if (!empty($overview_header_card['full_story']) &&
				get_post_status($overview_header_card['full_story']) === 'publish'): ?>
				<a href="<?php echo esc_url(get_the_permalink($overview_header_card['full_story'])); ?>"
					<?php if (str_contains(get_page_template_slug($overview_header_card['full_story']), 'paywall') && !is_user_logged_in()): ?> data-tooltip="<?php echo esc_attr__('Available to all premium users/members', 'profidev-theme'); ?>" <?php endif ?>
					 class="theme-button-primary"><?php echo __('Full story', 'profidev-theme'); ?></a>
			<?php endif; ?>
			<a href="<?php echo esc_url(get_the_permalink($post_id)); ?>"
				 <?php if (str_contains(get_page_template_slug($post_id), 'paywall') && !is_user_logged_in()): ?> data-tooltip="<?php echo esc_attr__('Available to all premium users/members', 'profidev-theme'); ?>" <?php endif ?>
				 class="theme-button-primary-outline" ><?php echo __('Overview', 'profidev-theme'); ?></a>
		</div>
		<?php if (!empty($fields['overview_notice'])): ?>
			<p class="route-notice"><?php echo wp_kses_post($fields['overview_notice']); ?></p>
		<?php endif; ?>
	</div>
</div>
