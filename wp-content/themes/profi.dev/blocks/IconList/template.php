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
$template = get_value_or_default($fields['template'], 'template-1');
$attributes = get_block_wrapper_attributes(['class' => join(' ', ['profidev-icon-list', $template, !empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : '']), 'id' => $block['anchor'] ?? null]);
?>
<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<div class="wrapper">
			<div class="left">
				<?php if (!empty($fields['title'])): ?>
					<h2 class="theme-h4 title"><?php echo wp_kses_post($fields['title']); ?></h2>
				<?php endif; ?>

				<?php if (!empty($fields['columns_left']) && is_array($fields['columns_left'])): ?>
				<ul class="no-list theme-grid icon-list">
					<?php foreach ($fields['columns_left'] as $column): ?>
					<li class="item">
						<a href="<?php echo !empty($column['link']) ? esc_url($column['link']) : '#'; ?>" class="list-item">
							<?php if (!empty($column['icon']) && is_numeric($column['icon'])): ?>
								<div class="icon">
									<?php echo wp_get_attachment_image($column['icon'], 'full', false, ['loading' => 'lazy']); ?>
								</div>
							<?php endif; ?>
							<?php if (!empty($column['title'])): ?>
								<h3 class="theme-h6"><?php echo wp_kses_post($column['title']); ?></h3>
							<?php endif; ?>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</div>

			<div class="right">
				<?php if (!empty($fields['columns']) && is_array($fields['columns'])): ?>
				<ul class="no-list theme-grid icon-list">
					<?php foreach ($fields['columns'] as $column): ?>
					<li class="item">
						<a href="<?php echo !empty($column['link']) ? esc_url($column['link']) : '#'; ?>" class="list-item">
							<?php if (!empty($column['icon']) && is_numeric($column['icon'])): ?>
								<div class="icon">
									<?php echo wp_get_attachment_image($column['icon'], 'full', false, ['loading' => 'lazy']); ?>
								</div>
							<?php endif; ?>
							<?php if (!empty($column['title'])): ?>
								<h3 class="theme-h6"><?php echo wp_kses_post($column['title']); ?></h3>
							<?php endif; ?>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</div>
		</div>
		<?php if( $fields['link'] ):
			$link_url = $fields['link']['url'];
			$link_title = $fields['link']['title'];
			$link_target = $fields['link']['target'] ? $fields['link']['target'] : '_self';
			?>
			<a class="theme-button-primary-outline" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
		<?php endif; ?>
	</div>
</section>
