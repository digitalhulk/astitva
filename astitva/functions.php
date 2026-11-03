<?php
/**
 * Theme bootstrap.
 *
 * @package Astitva
 */

defined( 'ABSPATH' ) || exit;

define( 'AGI_VERSION', '1.0.0' );

require_once get_template_directory() . '/inc/options.php';
require_once get_template_directory() . '/inc/template-tags.php';

/**
 * Theme supports and menus.
 */
function agi_setup() {
	load_theme_textdomain( 'astitva', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array( 'height' => 90, 'width' => 216, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/main.css' );
	register_nav_menus(
		array(
			'primary'  => __( 'Primary Menu', 'astitva' ),
			'launcher' => __( 'Launcher Sidebar', 'astitva' ),
			'footer-1' => __( 'Footer Column 1', 'astitva' ),
			'footer-2' => __( 'Footer Column 2', 'astitva' ),
		)
	);
	$GLOBALS['content_width'] = 1280;
}
add_action( 'after_setup_theme', 'agi_setup' );

/**
 * Google Fonts stylesheet URL built from the selected families.
 *
 * @return string
 */
function agi_fonts_url() {
	$families = array_unique( array( agi_option( 'font_heading' ), agi_option( 'font_body' ) ) );
	$query    = array();
	foreach ( $families as $family ) {
		$query[] = 'family=' . str_replace( ' ', '+', $family ) . ':wght@400;600';
	}
	return 'https://fonts.googleapis.com/css2?' . implode( '&', $query ) . '&display=swap';
}

/**
 * Front-end assets.
 */
function agi_enqueue() {
	$dir = get_template_directory();
	$uri = get_template_directory_uri();
	wp_enqueue_style( 'agi-fonts', agi_fonts_url(), array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	wp_enqueue_style( 'agi-main', $uri . '/assets/css/main.css', array( 'agi-fonts' ), filemtime( $dir . '/assets/css/main.css' ) );
	wp_add_inline_style( 'agi-main', agi_dynamic_css() );
	wp_enqueue_script( 'agi-main', $uri . '/assets/js/main.js', array(), filemtime( $dir . '/assets/js/main.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'agi_enqueue' );

/**
 * Preconnect for Google Fonts.
 *
 * @param array  $urls Resource hints.
 * @param string $type Hint type.
 * @return array
 */
function agi_resource_hints( $urls, $type ) {
	if ( 'preconnect' === $type ) {
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'agi_resource_hints', 10, 2 );

/**
 * Body classes used by the header and page layout.
 *
 * @param array $classes Classes.
 * @return array
 */
function agi_body_class( $classes ) {
	if ( agi_is_builder_page() ) {
		$classes[] = 'agi-builder-page';
	}
	if ( agi_option( 'header_blur' ) ) {
		$classes[] = 'agi-header-blur';
	}
	return $classes;
}
add_filter( 'body_class', 'agi_body_class' );

/**
 * Serve the chosen 404 page content.
 *
 * @param string $template Template path.
 * @return string
 */
function agi_404_template( $template ) {
	return is_404() && agi_option( 'page_404' ) ? get_template_directory() . '/404.php' : $template;
}
add_filter( 'template_include', 'agi_404_template' );

/**
 * Print admin custom JS. Only users with unfiltered_html can save it.
 */
function agi_custom_js() {
	$js = agi_option( 'custom_js' );
	if ( $js ) {
		wp_print_inline_script_tag( $js );
	}
}
add_action( 'wp_footer', 'agi_custom_js', 100 );
