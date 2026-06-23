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

// Settting up allowed inner blocks.
$innerTemplate = [
	[
		'core/heading',
		[
			'level'     => 1,
			'content'   => 'Bikepacking Norway'
		]
	],
	[
		'core/paragraph',
		[
			'content'   => 'Discover Europe’s most underrated cycling destination. ',
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
					'text'      => 'Join for free',
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
	'core/buttons'
];

$attributes = get_block_wrapper_attributes(['class' => join(' ', [
	'swiper-slide',
	!empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''
]), 'id' => $block['anchor'] ?? null]);

$fields = get_fields(); ?>

<div <?php echo $attributes; ?>>
	<div class="slide-content">
		<?php if ( ! empty( $fields['bg_image'] ) ) {
			$is_priority = ! empty( $fields['disable_lazy_loading'] );

			echo wp_get_attachment_image( $fields['bg_image'], 'full', false, [
				'class'         => 'bg-image',
				'loading'       => $is_priority ? 'eager' : 'lazy',
				'fetchpriority' => $is_priority ? 'high' : null,
			] );
		} ?>
		<div class="theme-container">
			<InnerBlocks class=" theme-text-element" allowedBlocks="<?php echo esc_attr(json_encode($innerblocks)); ?>" template="<?php echo esc_attr(json_encode($innerTemplate)); ?>" />
		</div>
	</div>
</div>
