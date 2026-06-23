<?php

/**
 * Form Block Template.
 *
 * @var   array $block The block settings and attributes.
 * @var   string $content The block inner HTML (empty).
 * @var   bool $is_preview True during backend preview render.
 * @var   int $post_id The post ID the block is rendering content against.
 * @var   array $context The context provided to the block by the post or its parent block.
 */

if (!defined('ABSPATH')) {
	exit;
}

$fields = get_fields() ?? [];

$can_see_errors = current_user_can('edit_posts');

if (empty($fields['template'])) {
	if ($can_see_errors) {
		echo '<div style="padding: 15px; background: #fff8e5; border-left: 4px solid #f0b849; color: #3c434a; font-family: sans-serif;">';
		echo '<strong>⚠️ Block Notice:</strong> No template selected. Please choose a template in the block settings.';
		echo '</div>';
	}

} elseif (!file_exists(get_theme_file_path('template-parts/' . $fields['template'] . '.php'))) {
	if ($can_see_errors) {
		echo '<div style="padding: 15px; background: #fcf0f1; border-left: 4px solid #d63638; color: #3c434a; font-family: sans-serif;">';
		echo '<strong>🚨 Block Error:</strong> The selected template file (<code>template-parts/' . esc_html($fields['template']) . '.php</code>) was not found in your theme directory.';
		echo '</div>';
	}
} else {
	$status = get_template_part('template-parts/' . $fields['template'], null, [
		'id'         => $block['anchor'] ?? null,
		'fields'     => $fields,
		'block'      => $block,
		'content'    => $content,
		'is_preview' => $is_preview,
		'post_id'    => $post_id,
		'context'    => $context,
	]);
	if ($status === false && $can_see_errors) {
		echo '<div style="padding: 15px; background: #fcf0f1; border-left: 4px solid #d63638; color: #3c434a; font-family: sans-serif;">';
		echo '<strong>🚨 Block Error:</strong> get_template_part() failed to load the template.';
		echo '</div>';
	}
}
