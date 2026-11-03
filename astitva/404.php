<?php
/**
 * Not found page. Uses the page chosen in Theme Options when set.
 *
 * @package Astitva
 */

defined( 'ABSPATH' ) || exit;

get_header();
$agi_page = absint( agi_option( 'page_404' ) );
if ( $agi_page && class_exists( '\Elementor\Plugin' ) && 'builder' === get_post_meta( $agi_page, '_elementor_edit_mode', true ) ) {
	echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $agi_page ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
} else {
	echo '<section class="not-found"><p class="not-found__code">404</p><h1>' . esc_html__( 'Page not found', 'astitva' ) . '</h1><p>' . esc_html__( 'The page you are looking for has moved or never existed.', 'astitva' ) . '</p><a class="btn btn--primary btn--lg" href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Back to home', 'astitva' ) . '</a></section>';
}
get_footer();
