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


$innerTemplate = array(
	// Left Element
	array(
		'core/group',
		array(
			'className' => 'theme-text-element left',
			'lock' => array(
				'remove' => true,
				'move'   => true,
			),
			'templateLock' => false,
		),
		array(
			array( 'core/heading', array(
				'level' => 2,
				'className' => 'has-h-3-font-size with-border',
				'content' => 'Norway\'s Bikepackers'
			) )
		)
	),
	// Right Element
	array(
		'core/group',
		array(
			'className' => 'theme-text-element right',
			'lock' => array(
				'remove' => true,
				'move'   => true,
			),
			'templateLock' => false,
		),
		array(
			array( 'core/paragraph', array(
				'content' => 'Norwegians embody an active lifestyle, evident in their profound connection with nature and the outdoors. Popular recreational pursuits like hiking and skiing are deeply ingrained in their culture, with nearly everyone I know owning a pair of hiking boots and skis. While bikepacking or bike touring is still emerging, there’s anticipation for its growth. Will more Norwegians embrace this trend and add cleated shoes to their outdoor gear collection? Only time will reveal the answer.'
			) ),
			array( 'core/paragraph', array(
				'content' => 'Here, we introduce the pioneers of Norwegian bikepacking, who have ventured into uncharted territories, experimented with bike setups, and revolutionized cycling in this beautiful land. Their extensive expertise forms the foundation of this website, as recreational cycling undergoes unprecedented evolution. Our mission is to guide and shape this evolution.'
			) ),
			array( 'core/image', array() )
		)
	)
);

$allowedBlocks = array(
	'core/group'
);

$fields = get_fields() ?? [];
$template = get_value_or_default($fields['template'] ?? 'template-1', 'template-1');
$attributes = get_block_wrapper_attributes([
	'class' => join(' ', array_filter(['profidev-left-right', $template, !empty($fields['margin']) && is_array($fields['margin']) ? join(' ', $fields['margin']) : ''])),
	'id' => $block['anchor'] ?? null
]);
?>

<section <?php echo $attributes; ?>>
	<div class="theme-container">
		<InnerBlocks class="wrapper" allowedBlocks="<?php echo esc_attr(json_encode($allowedBlocks)); ?>" template="<?php echo esc_attr(json_encode($innerTemplate)); ?>" />

		<?php if (!empty($fields['gallery']) && is_array($fields['gallery'])) : ?>
		<div class="theme-grid gallery-images">
			<?php foreach ($fields['gallery'] as $item) : ?>
				<?php if(!empty($item['image'])): ?>
				<div class="item">
					<?php echo wp_get_attachment_image( $item['image'], [668, 420], false, ['loading' => 'lazy'] ) ?>
					<?php if(!empty($item['text'])): ?>
					<p class="no-margin"><?php echo wp_kses_post($item['text']); ?></p>
					<?php endif; ?>
				</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
</section>
