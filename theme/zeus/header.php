<?php
/**
 * Document head + opening <body> + site header.
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'zeus-has-mobile-bar' ); ?>>
<?php wp_body_open(); ?>

<a class="zeus-skip-link" href="#zeus-main-content"><?php esc_html_e( 'Skip to content', 'zeus' ); ?></a>

<?php get_template_part( 'template-parts/header/site-header' ); ?>
<?php get_template_part( 'template-parts/header/mobile-nav' ); ?>

<main id="zeus-main-content">
