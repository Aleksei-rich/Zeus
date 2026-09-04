<?php
/**
 * Core theme setup: supports, menus, image sizes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zeus_setup() {
	load_theme_textdomain( 'zeus', ZEUS_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'appearance-tools' );
	add_theme_support( 'custom-line-height' );
	add_theme_support( 'custom-spacing' );

	// theme.json supplies the palette/typography scales; disable the
	// legacy custom-color/custom-font-size UI so editors stay inside
	// the design system instead of picking arbitrary values.
	add_theme_support( 'disable-custom-colors' );
	add_theme_support( 'disable-custom-font-sizes' );
	add_theme_support( 'disable-custom-gradients' );

	add_editor_style( 'assets/css/editor-style.css' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'zeus' ),
			'footer'  => __( 'Footer Navigation', 'zeus' ),
			'mobile'  => __( 'Mobile Conversion Bar', 'zeus' ),
		)
	);

	add_image_size( 'zeus-card', 640, 480, true );
	add_image_size( 'zeus-hero', 1600, 900, true );
	add_image_size( 'zeus-gallery', 1200, 900, true );
	add_image_size( 'zeus-thumb', 320, 240, true );
	add_image_size( 'zeus-square', 400, 400, true ); // door/finish swatches (source is already square)

	$GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'zeus_setup' );

/**
 * Browser/site icon fallback. If WordPress has an explicit Site Icon set,
 * core outputs that instead. Otherwise use the approved ZEUS round icon
 * bundled with the theme rather than the generic letter favicon.
 */
function zeus_output_favicon_fallback() {
	if ( has_site_icon() ) {
		return;
	}

	$icon = ZEUS_THEME_URI . '/assets/img/favicon-source-512.png';
	?>
	<link rel="icon" href="<?php echo esc_url( $icon ); ?>" sizes="32x32">
	<link rel="icon" href="<?php echo esc_url( $icon ); ?>" sizes="192x192">
	<link rel="apple-touch-icon" href="<?php echo esc_url( $icon ); ?>">
	<meta name="msapplication-TileImage" content="<?php echo esc_url( $icon ); ?>">
	<?php
}
add_action( 'wp_head', 'zeus_output_favicon_fallback', 1 );

/**
 * Register widget-free footer areas are not used; footer is fully templated.
 * Kept intentionally minimal — no sidebar/widget system for a marketing site.
 */

/**
 * Local-dev-only guardrail: this theme must never hard-code the
 * production domain. See .claude/rules/wordpress-development.md.
 */
function zeus_assert_no_hardcoded_production_domain() {
	// Intentionally empty in production code paths — enforced by review,
	// not runtime. See docs/SECURITY-AND-DEPLOYMENT.md.
}
