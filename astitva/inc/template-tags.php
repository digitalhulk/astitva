<?php
/**
 * Template helpers.
 *
 * @package Astitva
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the current singular page is built with Elementor.
 *
 * @return bool
 */
function agi_is_builder_page() {
	return is_singular() && 'builder' === get_post_meta( get_queried_object_id(), '_elementor_edit_mode', true );
}

/**
 * Print an Elementor template when one is assigned.
 *
 * @param string $key Option key.
 * @return bool True when a template was printed.
 */
function agi_print_template( $key ) {
	$id = absint( agi_option( $key ) );
	if ( ! $id || ! class_exists( '\Elementor\Plugin' ) ) {
		return false;
	}
	echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return true;
}

/**
 * Small inline SVG icon drawn for this theme.
 *
 * @param string $name Icon name.
 * @return string
 */
function agi_icon( $name ) {
	$paths = array(
		'arrow'     => '<path d="M7 17 17 7M8 7h9v9" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
		'chevron'   => '<path d="m6 9 6 6 6-6" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>',
		'up'        => '<path d="m5 15 7-7 7 7" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>',
		'menu'      => '<path d="M4 7h16M4 12h16M4 17h10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'close'     => '<path d="m6 6 12 12M18 6 6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'pin'       => '<path d="M12 22s7-6.6 7-12a7 7 0 1 0-14 0c0 5.4 7 12 7 12Zm0-9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5Z" fill="currentColor"/>',
		'phone'     => '<path d="M6 3h3l2 5-2.5 1.5a11 11 0 0 0 6 6L16 13l5 2v3a3 3 0 0 1-3 3C10 21 3 14 3 6a3 3 0 0 1 3-3Z" fill="currentColor"/>',
		'chat'      => '<path d="M4 4h16a1 1 0 0 1 1 1v11a1 1 0 0 1-1 1H9l-5 4V5a1 1 0 0 1 1-1Z" fill="currentColor"/>',
		'facebook'  => '<path d="M14 8h3V4h-3a5 5 0 0 0-5 5v2H6v4h3v7h4v-7h3l1-4h-4V9a1 1 0 0 1 1-1Z" fill="currentColor"/>',
		'x'         => '<path d="M4 4h4.5l11.5 16h-4.500zM19 4l-6.200 7M11 13.200 5 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
		'instagram' => '<rect x="4" y="4" width="16" height="16" rx="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3.500" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="16.800" cy="7.200" r="1.200" fill="currentColor"/>',
		'linkedin'  => '<path d="M5 9h3v11H5zM6.500 4a1.800 1.800 0 1 1 0 3.600 1.800 1.800 0 0 1 0-3.600ZM10.500 9h3v1.500c.7-1 1.900-1.800 3.400-1.800 3 0 4.100 2 4.100 5V20h-3v-5.500c0-1.500-.5-2.800-2-2.800s-2.500 1.200-2.500 2.800V20h-3z" fill="currentColor"/>',
		'youtube'   => '<path d="M21.500 7.500a2.700 2.700 0 0 0-1.900-1.900C17.900 5 12 5 12 5s-5.900 0-7.600.6a2.700 2.700 0 0 0-1.900 1.900A28 28 0 0 0 2 12c0 1.500.2 3 .5 4.500a2.700 2.700 0 0 0 1.900 1.900C6.100 19 12 19 12 19s5.900 0 7.600-.6a2.700 2.700 0 0 0 1.900-1.900c.3-1.500.5-3 .5-4.500s-.2-3-.5-4.500ZM10 15V9l5 3z" fill="currentColor"/>',
	);
	return '<svg class="agi-icon" viewBox="0 0 24 24" width="1em" height="1em" aria-hidden="true">' . ( $paths[ $name ] ?? '' ) . '</svg>';
}

/**
 * Allowed tags for the theme's own SVG icons.
 *
 * @return array
 */
function agi_svg_kses() {
	$shape = array( 'd' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true, 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'cx' => true, 'cy' => true, 'r' => true );
	return array(
		'svg'    => array( 'class' => true, 'viewbox' => true, 'width' => true, 'height' => true, 'aria-hidden' => true ),
		'path'   => $shape,
		'rect'   => $shape,
		'circle' => $shape,
	);
}

/**
 * Echo an icon.
 *
 * @param string $name Icon name.
 */
function agi_the_icon( $name ) {
	echo wp_kses( agi_icon( $name ), agi_svg_kses() );
}

/**
 * Logo markup with mobile and sticky variants.
 */
function agi_logo() {
	echo '<a class="site-logo" href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
	$main = absint( agi_option( 'logo' ) ) ?: absint( get_theme_mod( 'custom_logo' ) );
	if ( $main ) {
		$variants = array(
			'logo-desktop' => $main,
			'logo-mobile'  => absint( agi_option( 'logo_mobile' ) ) ?: $main,
			'logo-sticky'  => absint( agi_option( 'logo_sticky' ) ) ?: $main,
		);
		foreach ( $variants as $class => $id ) {
			echo wp_get_attachment_image( $id, 'medium', false, array( 'class' => $class, 'alt' => get_bloginfo( 'name' ) ) );
		}
	} else {
		echo '<svg class="logo-mark" viewBox="0 0 40 30" width="40" height="30" aria-hidden="true"><path d="M14 2h24l-12 12H2z" fill="var(--color-primary)"/><path d="M12 16h20L20 28H0z" fill="var(--color-secondary)"/></svg>';
		echo '<span class="logo-text">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
	}
	echo '</a>';
}

/**
 * Social links from Theme Options.
 *
 * @param string $class Wrapper class.
 */
function agi_social( $class = 'social-links' ) {
	$items = '';
	foreach ( array( 'facebook', 'x', 'instagram', 'linkedin', 'youtube' ) as $network ) {
		$url = agi_option( 'social_' . $network );
		if ( $url ) {
			$items .= '<a href="' . esc_url( $url ) . '" aria-label="' . esc_attr( ucfirst( $network ) ) . '" rel="noopener" target="_blank">' . agi_icon( $network ) . '</a>';
		}
	}
	if ( $items ) {
		$allowed      = agi_svg_kses();
		$allowed['a'] = array( 'href' => true, 'aria-label' => true, 'rel' => true, 'target' => true );
		echo '<div class="' . esc_attr( $class ) . '">' . wp_kses( $items, $allowed ) . '</div>';
	}
}

/**
 * Inner-page hero used by non-Elementor templates.
 *
 * @param string $title Title.
 * @param string $text  Optional subtitle.
 */
function agi_page_hero( $title, $text = '' ) {
	echo '<header class="page-hero"><div class="page-hero__inner"><h1>' . wp_kses_post( $title ) . '</h1>';
	if ( $text ) {
		echo '<p>' . wp_kses_post( $text ) . '</p>';
	}
	echo '</div></header>';
}

/**
 * Post card used in blog, search and post type archives.
 */
function agi_card() {
	$categories = 'post' === get_post_type() ? get_the_category() : array();
	echo '<article id="post-' . esc_attr( get_the_ID() ) . '" class="' . esc_attr( implode( ' ', get_post_class( 'post-card' ) ) ) . '">';
	echo '<a class="post-card__media" href="' . esc_url( get_permalink() ) . '" aria-label="' . esc_attr( get_the_title() ) . '">';
	if ( has_post_thumbnail() ) {
		the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) );
	}
	echo '</a><div class="post-card__body">';
	if ( $categories ) {
		echo '<span class="post-card__cat">' . esc_html( $categories[0]->name ) . '</span>';
	}
	echo '<h3 class="post-card__title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3>';
	if ( 'post' === get_post_type() ) {
		echo '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
	} else {
		echo '<p>' . esc_html( get_the_excerpt() ) . '</p>';
	}
	echo '</div></article>';
}

/**
 * Loop of cards with pagination.
 */
function agi_card_loop() {
	if ( have_posts() ) {
		echo '<div class="post-grid">';
		while ( have_posts() ) {
			the_post();
			agi_card();
		}
		echo '</div>';
		the_posts_pagination( array( 'mid_size' => 1 ) );
	} else {
		echo '<p class="no-results">' . esc_html__( 'Nothing found. Try a different search.', 'astitva' ) . '</p>';
		get_search_form();
	}
}
