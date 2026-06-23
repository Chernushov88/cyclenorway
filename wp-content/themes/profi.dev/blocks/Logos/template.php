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
	'profidev-logos',
	!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
]), 'id' => $block['anchor'] ?? null]);

$template = get_value_or_default($fields['template'] ?? 'logos-grid', 'logos-grid');
?>

<section <?php echo $attributes; ?>>
	<profidev-logos-slider>
		<div class="theme-container <?php echo esc_attr($template); ?>">
			<?php if (!empty($fields['title'])): ?>
			<div class="theme-text-element">
				<h2 class="theme-title"><?php echo wp_kses_post($fields['title']); ?></h2>
				<?php if( $fields['link'] ): 
					$link_url = $fields['link']['url'];
					$link_title = $fields['link']['title'];
					$link_target = $fields['link']['target'] ? $fields['link']['target'] : '_self';
					?>
					<a class="theme-button-primary" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			
			<?php if (is_array($fields['images'])): ?>
			<div class="swiper">
				<div class="<?php echo $template === 'logos-grid' ? 'theme-grid' : 'swiper-wrapper' ?>">
					<?php foreach ($fields['images'] as $item):
					if( !empty($item['link']) ): ?>
						<div class="swiper-slide item" style="--logo-width: <?php echo esc_attr($item['width']); ?>%;">
							<a
								class="logo"
								href="<?php echo esc_url($item['link']['url']); ?>"
								target="<?php echo esc_attr($item['link']['target'] ?: '_self'); ?>"
								<?php echo ($item['link']['target'] === '_blank') ? 'rel="noopener noreferrer"' : ''; ?>
								title="<?php echo esc_attr($item['link']['title']); ?>"
							>
								<?php echo wp_get_attachment_image($item['image'], 'full', false); ?>
							</a>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
				</div>
				<?php if($template !== 'logos-grid'): ?>
					<div class="theme-slider-controls">
						<div class="slider-pagination"></div>
					</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
	</profidev-testimonials-slider>
</section>
