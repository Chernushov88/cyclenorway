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
$template = $fields['template'] ?? 'template-1';
$attributes = get_block_wrapper_attributes([
	'class' => join(' ', ['profidev-cards-list', $template, !empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : '']),
	'id' => $block['anchor'] ?? null
]);
$cards_data = is_array($fields['cards'] ?? null) ? $fields['cards'] : [];

$cards = array_filter($cards_data, function ($item) {
    return !empty($item['image']) && is_numeric($item['image']) && !empty($item['title']) && !empty($item['description']);
});
?>
<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<div class="theme-heading">
			<div class="heading-text">
				<?php if (!empty($fields['title'])): ?>
					<h2 class="theme-title"><?php echo wp_kses_post($fields['title']); ?></h2>
				<?php endif; ?>
				<?php if (!empty($fields['description'])): ?>
					<p class="no-margin"><?php echo wp_kses_post($fields['description']); ?></p>
				<?php endif; ?>
			</div>
		</div>
		<div class="theme-grid cards-list">
			<?php foreach ($cards as $card): ?>
			<div class="item">
				<?php 
					$link = $card['link'] ?? null;
					$is_link = is_array($link) && !empty($link['url']);
					
					$tag = $is_link ? 'a' : 'div';
					
					$tag_attributes = 'class="card-item"';
					if ($is_link) {
						$href   = esc_url($link['url']);
						$target = esc_attr($link['target'] ?: '_self');
						$tag_attributes .= " href=\"{$href}\" target=\"{$target}\"";
					}
				?>
				<<?php echo $tag; ?> <?php echo $tag_attributes; ?>>
					<?php echo wp_get_attachment_image($card['image'], 'full', false, ['loading' => 'lazy']); ?>
					<h3 class="no-margin theme-h5"><?php echo wp_kses_post($card['title']); ?></h3>
					<p class="no-margin"><?php echo wp_kses_post($card['description']); ?></p>
				</<?php echo $tag; ?>>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
