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
$attributes = get_block_wrapper_attributes(['class' => join(' ', [
	'profidev-terms-notice',
	!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : '',
]), 'id' => $block['anchor'] ?? null]);
?>
<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<div class="wrapper">
			<div class="theme-text-element left">
				<div class="heading">
					<div class="description">
						<?php if (!empty($fields['subtitle'])): ?>
							<p class="no-margin subtitle"><?php echo wp_kses_post($fields['subtitle']); ?></p>
						<?php endif; ?>
						<?php if (!empty($fields['title'])): ?>
							<h2 class="no-margin theme-h4"><?php echo wp_kses_post($fields['title']); ?></h2>
						<?php endif; ?>
					</div>
					<div class="theme-image-wrapper">
						<img alt="Warning" src="<?php echo get_template_directory_uri() . '/assets/img/warning.svg'; ?>" width="50" height="50" loading="lazy">
					</div>
				</div>
				<?php if (!empty($fields['description'])): ?>
					<p><?php echo nl2br(wp_kses_post($fields['description'])); ?></p>
				<?php endif; ?>
			</div>
			<div class="right">
				<?php if (!empty($fields['columns']) && is_array($fields['columns'])): ?>
					<ul class="no-list terms-list">
						<?php foreach ($fields['columns'] as $column): ?>
						<li class="item">
							<?php if (!empty($column['icon']) && is_numeric($column['icon'])): ?>
								<div class="item-icon">
									<?php echo wp_get_attachment_image($column['icon'], 'full', false, ['loading' => 'lazy']); ?>
								</div>
							<?php endif; ?>
							<div class="item-description">
								<?php if (!empty($column['title'])): ?>
									<h3 class="item-title">
										<?php echo wp_kses_post($column['title']); ?>
									</h3>
								<?php endif; ?>
								<?php if (!empty($column['description'])): ?>
									<p class="item-text"><?php echo wp_kses_post($column['description']); ?></p>
								<?php endif; ?>
							</div>

							<?php if( $column['link'] ): 
								$link_url = $column['link']['url'];
								$link_title = $column['link']['title'];
								$link_target = $column['link']['target'] ? $column['link']['target'] : '_self';
								?>
								<a class="no-underline item-link" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>" title="<?php echo esc_attr( $link_title ); ?>"></a>
							<?php endif; ?>
						</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
