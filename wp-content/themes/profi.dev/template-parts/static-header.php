<?php
$account_page_url = '';
$logic_page = '';
$dashboard_page = '';
if (class_exists('MeprOptions')) {
	$mepr_options     = MeprOptions::fetch();
	$account_page_url = $mepr_options->account_page_url();
	$logic_page       = $mepr_options->login_page_url();
}
if (is_user_logged_in()) {
	$dashboard = get_page_by_path( 'dashboard' );
	if ( $dashboard ) {
		$dashboard_page = get_permalink( $dashboard );
	}
} else {
	$dashboard = get_page_by_path( 'membership' );
	if ( $dashboard ) {
		$dashboard_page = get_permalink( $dashboard );
		$account_page_url = $dashboard_page;
	}
}
?>
<header-element>
	<header class="theme-header">
		<div class="theme-container">
			<div class="header-wrapper">
				<?php if (!empty(get_theme_mod('custom_logo'))): ?>
					<?php the_custom_logo() ?>
				<?php else: ?>
					<a class="custom-logo-link" rel="<?php echo _('home', 'profidev-theme') ?>" aria-current="page" href="<?php echo esc_url(get_bloginfo('url')) ?>">
						<?php echo esc_html(get_bloginfo('name', 'raw')) ?>
					</a>
				<?php endif; ?>

				<nav class="header-menu">
					<?php
					wp_nav_menu([
						'theme_location' => 'header_menu',
						'menu' => 'Header Menu',
						'menu_class' => 'no-list header-menu-list',
						'container'            => '',
						'container_class'      => '',
						'container_id'         => '',
						'depth'           => 2,
						'walker'         => new \ProfiDev\Client\Walkers\HeaderMenu(),
					]);
					?>
				</nav>

				<div class="header-controls">
					<?php if ( shortcode_exists( 'language-switcher' ) ):
						echo do_shortcode( '[language-switcher]' );
					endif; ?>
					<div class="header-search">
						<?php get_search_form(); ?>
						<button class="no-btn header-btn-search" aria-label="<?php echo esc_attr('search', 'profidev-theme'); ?>"></button>
					</div>
					<?php if (!empty($account_page_url)): ?>
						<a href="<?php echo esc_url($account_page_url); ?>" class="no-underline header-btn-account" title="<?php echo esc_attr('Account', 'profidev-theme'); ?>"></a>
					<?php endif; ?>
					<?php if (!empty($dashboard_page)): ?>
						<a href="<?php echo esc_url($dashboard_page); ?>" class="no-underline header-btn-membership">
							<?php echo is_user_logged_in() ? esc_html__('Members', 'profidev-theme') : esc_html__('Membership', 'profidev-theme'); ?>
						</a>
					<?php endif; ?>
					<button class="no-btn header-mobile-menu" aria-label="<?php echo esc_attr('Mobile menu', 'profidev-theme')?>"><span aria-hidden="true" class="line"></span></button>
				</div>
			</div>
		</div>
	</header>
</header-element>
