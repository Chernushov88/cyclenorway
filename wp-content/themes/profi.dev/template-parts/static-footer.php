<?php
$subscribe_form = get_field('subscribe_form', 'option');
?>
<footer class="theme-footer">
	<div class="theme-container">
		<div class="main-footer">
			<div class="footer-info">
				<?php if (!empty(get_theme_mod('custom_logo'))): ?>
					<?php the_custom_logo() ?>
				<?php else: ?>
					<a class="custom-logo-link" rel="<?php echo _('home') ?>" aria-current="page" href="<?php echo esc_url(get_bloginfo('url')) ?>">
						<?php echo esc_html(get_bloginfo('name', 'raw')) ?>
					</a>
				<?php endif; ?>

				<p class="footer-text"><?php _e('Cycle Norway is dedicated to making Norway, safer and more enjoyable to experience by bike and to inspire and inform a growing audience of the opportunities available.', 'profidev-theme');?></p>
			</div>
			<div class="footer-menus">
				<?php
				wp_nav_menu([
					'theme_location' => 'footer_menu',
					'container' => 'div',
					'container_class' => 'theme-container',
					'depth' => 2,
					'menu_class' => 'theme-grid',
					'items_wrap' => '<div id="%1$s" class="%2$s">%3$s</div>',
					'walker' => new \ProfiDev\Client\Walkers\FooterMenu(),
				]);
				?>
			</div>
		</div>
		<div class="footer-newsletter">
			<?php if (!empty($subscribe_form) && is_string($subscribe_form)): ?>
				<?php echo do_shortcode($subscribe_form); ?>
			<?php endif; ?>
		</div>
		<div class="footer-copyright">
			<p class="footer-copyright-left"><?php _e('All designs, text, photos (unless stated), & articles are property of CycleNorway.com', 'profidev-theme');?></p>
			<p class="footer-copyright-right"><?php _e('© 2026 CycleNorway.com', 'profidev-theme');?></p>
		</div>
	</div>
</footer>
