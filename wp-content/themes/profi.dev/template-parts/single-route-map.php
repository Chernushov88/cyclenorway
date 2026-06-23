<?php
/**
 * @var array $args
 */
$attributes = get_block_wrapper_attributes(['class' => 'profidev-map', 'id' => $args['id'] ?? null]);
$fields = get_fields_or_template($args['post_id'], $args['is_preview'], 'single_route_fields');
$map = $fields['main_map'] ?? null;
$coreFields = $args['fields'];
$protected_content = !empty($coreFields['protected_content']) && is_array($coreFields['protected_content']) ? $coreFields['protected_content'] : [];
?>
<?php if (!$args['post_id'] && $args['is_preview'] && empty($map)): ?>
	<?php get_template_part('template-parts/preview-warning', null); ?>
<?php elseif (!empty($map)): ?>
<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<h2 class="title"><?php echo __('Map', 'profidev-theme'); ?></h2>
		<?php if (can_see_protected_content($protected_content)): ?>
			<?php echo $map; ?>
		<?php else: ?>
			<div class="paywall-map">
				<img src="<?php echo get_template_directory_uri(); ?>/assets/img/map-placeholder.jpg" alt="" loading="lazy">

				<div class="theme-text-element">
					<h3 class="title"><?php echo __('Unlock the full route map', 'profidev-theme'); ?></h3>
					<p><?php echo __('Join Cycle Norway to access the full interactive map, elevation details and GPX files.', 'profidev-theme'); ?></p>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>
