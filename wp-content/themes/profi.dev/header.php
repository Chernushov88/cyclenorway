<!DOCTYPE html>
<html lang="<?php echo esc_attr(get_bloginfo('language')); ?>">
<head>
	<meta charset="<?php echo esc_attr(get_bloginfo('charset')); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<?php get_template_part('template-parts/static', 'header'); ?>