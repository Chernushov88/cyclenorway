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
			'content'   => 'A Unique Cycling Service'
		]
	],
	[
		'core/paragraph',
		[
			'content'   => 'Thinking about cycling in Norway but not sure where to start? Maybe you’ve already mapped out a route but want an expert to review it and flag any potential issues or improvements. ',
		]
	],
	[
		'core/buttons',
		[
			'layout' => [
				'type'           => 'flex',
			]
		],
		[
			[
				'core/button',
				[
					'text'      => 'Book a call',
					'className' => 'is-style-primary-btn',
					'url'       => '#',
				]
			]
		]
	]
];

$innerblocks = [
	'core/heading',
	'core/paragraph',
	'core/list',
	'core/buttons',
	'core/image'
];

$class_name = ['content'];

if (!empty($block['className'])) {
	array_push($class_name, $block['className']);
}

if (!empty($block['backgroundColor'])) {
    $class_name[] = 'has-' . $block['backgroundColor'] . '-background-color';
}

if (!empty($block['textColor'])) {
    $class_name[] = 'has-' . $block['textColor'] . '-color';
}

$style = '';
if (!empty($block['style']['color']['background']) || !empty($block['style']['color']['text'])) {
$style = 'style="'
	. (!empty($block['style']['color']['background']) ? 'background-color: ' . $block['style']['color']['background'] . ';' : '')
	. (!empty($block['style']['color']['text']) ? 'color: ' . $block['style']['color']['text'] . ';' : '')
	. '"';
}

$fields = get_fields();

$image_template = ['media'];
if (!empty($fields['media_type'])) {
	$image_template[] = $fields['media_type'];

	if (!empty($fields['image_template']) && $fields['media_type'] == 'images') {
		$image_template[] = $fields['image_template'];
	}
}

if (!empty($fields['media_height'])) {
	$image_template[] = 'is-height-' . $fields['media_height'];
}

if (!empty($fields['object_fit'])) {
	$image_template[] = $fields['object_fit'];
}

$template     = $fields['template'] ?? 'template-1';
$class_name[] = $template;

$width_vars = '';
if (!empty($fields['content_width']) || !empty($fields['images_width'])) {
    $style_parts = [];

    if (!empty($fields['content_width'])) {
        $style_parts[] = '--content-width: ' . $fields['content_width'].'%';
    }

    if (!empty($fields['images_width'])) {
        $style_parts[] = '--images-width: ' . $fields['images_width'].'%';
    }

    $width_vars = 'style="' . implode('; ', $style_parts) . '"';
}

if (!empty($fields['reverse_blocks'])) {
	$class_name[] = 'reverse-blocks';
}

if (!empty($fields['image_below_mobile'])) {
	$class_name[] = 'reverse-mobile';
}

if (!empty($fields['disable_aspect_ratio_mobile'])) {
    $class_name[] = 'disable-aspect-ratio-mobile';
}

if (!empty($fields['rounded_block'])) {
    array_push($class_name, 'rounded-block');
}

if (!empty($fields['rounded_image'])) {
    array_push($class_name, 'rounded-image');
}

if (!empty($fields['margin'])) {
	$class_name[] = is_array( $fields['margin'] ) ? join( ' ', $fields['margin'] ) : '';
} ?>

<section <?php echo $anchor; ?> class="profidev-text-image">
	<div class="theme-container">
		<div class="<?php echo esc_attr(join(' ', $class_name)); ?>" <?php echo $style; ?>>
			<div class="wrapper" <?php echo $width_vars; ?>>
				<div class="text-wrapper">
					<InnerBlocks class="theme-text-element" allowedBlocks="<?php echo esc_attr(json_encode($innerblocks)); ?>" template="<?php echo esc_attr(json_encode($innerTemplate)); ?>" />
				</div>
				<?php if(!empty($fields['image']) || !empty($fields['embed_link'])): ?>
				<div class="<?php echo esc_attr(join(' ', $image_template)); ?>">
					<?php if(!empty($fields['image'])): ?>
						<?php echo wp_get_attachment_image($fields['image'], 'full', '', ['loading' => 'lazy']); ?>
					<?php endif; ?>

					<?php if(!empty($fields['embed_link'])): ?>
						<iframe width="560" height="315"
						src="<?php echo esc_url($fields['embed_link']); ?>"
						<?php if(!empty($fields['title_for_iframe'])): ?>
						title="<?php echo esc_attr($fields['title_for_iframe']); ?>"
						<?php endif; ?>
						loading="lazy"
						frameborder="0"
						allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
						referrerpolicy="strict-origin-when-cross-origin"
						allowfullscreen></iframe>
					<?php endif; ?>

					<?php if(!empty($fields['texts_on_image'])): ?>
						<div class="content <?php echo count($fields['texts_on_image']) === 2 ? 'two-columns' : ''; ?>">
							<?php foreach($fields['texts_on_image'] as $text_item): ?>
								<div class="theme-text-element">
									<p class="text"><?php echo esc_html($text_item['text']); ?></p>
									<p class="price"><?php echo esc_html($text_item['price']); ?></p>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
