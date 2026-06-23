<?php declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

add_filter( 'use_block_editor_for_post_type', function ($can_edit, $post_type) {
	if ( $post_type == 'product' ) {
		$can_edit = true;
	}
	return $can_edit;
}, 10, 2 );

add_filter( 'woocommerce_taxonomy_args_product_cat', function ($args) {
	$args['show_in_rest'] = true;
	return $args;
} );
add_filter( 'woocommerce_taxonomy_args_product_tag', function ($args) {
	$args['show_in_rest'] = true;
	return $args;
} );
