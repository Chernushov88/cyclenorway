<?php
/**
 * @var array $args
 */
$attributes = get_block_wrapper_attributes(['class' => 'theme-text-element useful-links', 'id' => $args['id'] ?? null]);
$useful_links_content = $args['fields']['useful_links_content'] ?? "";
?>
<?php if(!empty($useful_links_content)): ?>
	<div <?php echo $attributes; ?>>
		<h2 class="no-margin theme-h4 title"><?php echo __('Useful links', 'profidev-theme'); ?></h2>
		<?php echo wp_kses_post($useful_links_content); ?>
	</div>
<?php endif; ?>
