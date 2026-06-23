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

$innerTemplate = [
	[
		'core/heading',
		[
			'level'     => 2,
			'content'   => 'The ultimate adventure playground...'
		]
	],
	[
		'core/paragraph',
		[
			'content'   => 'Norway is a breathtaking country best explored on two wheels, but it’s not always easy. Tunnels, fjords, mountains, and unpredictable weather can make cycling here a real adventure. That’s why I created Cycle Norway, to help you plan, stay safe, and get the most out of your journey. Study the site, get inspired, and join our community for the ultimate cycling experience.',
		]
	]
];

$innerblocks = [
	'core/heading',
	'core/paragraph',
	'core/buttons',
	'core/embed',
];

$class_name = ['profidev-content-cards'];

$fields = get_fields() ?? [];

if (!empty($fields['template'])) {
	$class_name[] = $fields['template'];
}
if (!empty($fields['margin'])) {
	$class_name[] = is_array( $fields['margin'] ) ? join( ' ', $fields['margin'] ) : '';
}

$attributes = get_block_wrapper_attributes([
	'class' => implode(' ', $class_name),
	'id' => $block['anchor'] ?? null
]); ?>

<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<div class="wrapper">
			<div class="content">
				<InnerBlocks class=" theme-text-element" allowedBlocks="<?php echo esc_attr(json_encode($innerblocks)); ?>" template="<?php echo esc_attr(json_encode($innerTemplate)); ?>" />
			</div>
			<div class="cards-list">
				<?php if (!empty($fields['cards']) && is_array($fields['cards'])): ?>
					<?php foreach ($fields['cards'] as $card): ?>
						<a href="<?php echo esc_url($card['navigation']) ?? '#'; ?>" class="no-underline cards-item">
							<?php echo wp_get_attachment_image($card['image'], 'full', false, ['class' => 'cards-item-image']); ?>
							<div class="cards-item-content">
								<h3 class="theme-h5"><?php echo wp_kses_post($card['title']); ?></h3>
								<?php if(!empty($card['description'])): ?>
									<p><?php echo wp_kses_post($card['description']); ?></p>
								<?php endif; ?>
							</div>
						</a>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php if (!empty($fields['image'])): ?>
					<?php echo wp_get_attachment_image($fields['image'], 'full', false, ['loading' => 'lazy']); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
