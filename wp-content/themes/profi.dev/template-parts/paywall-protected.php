<?php
/**
 * @var array $args
 */
?>
<section class="profidev-section-protected">
	<div class="theme-container">
		<?php if (!empty($args['title'])): ?>
			<h2 class="title"><?php echo $args['title']; ?></h2>
		<?php endif; ?>
		<div class="paywall-protected">
			<?php if (!empty($args['background_image'])): ?>
				<?php echo $args['background_image']; ?>
			<?php endif; ?>

			<?php if (!empty($args['content'])): ?>
			<div class="theme-text-element">
				<?php echo wpautop(wp_kses_post($args['content'])); ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>
