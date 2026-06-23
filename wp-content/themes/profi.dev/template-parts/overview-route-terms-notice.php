<?php
/**
 * @var array $args
 */
$attributes = get_block_wrapper_attributes(['class' => 'terms-notice', 'id' => $args['id'] ?? null]);
$terms_notice = get_value_or_default($args['fields']['terms_notice'], []);
$has_content = !empty($terms_notice['title']) && !empty($terms_notice['content']);
?>
<?php if ($has_content): ?>
	<div class="theme-terms-notice-container">
		<div <?php echo $attributes; ?>>
			<div class="theme-text-element description">
				<h2 class="no-margin theme-h4"><?php echo wp_kses_post($terms_notice['title']); ?></h2>
				<p><?php echo wp_kses_post($terms_notice['content']); ?></p>
			</div>
			<div class="theme-image-wrapper">
				<img src="<?php echo esc_url(get_theme_file_uri('/assets/img/warning.svg')); ?>" alt="Warning" width="50" height="50" loading="lazy">
			</div>
		</div>
	</div>
<?php endif; ?>
