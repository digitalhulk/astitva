<?php
/**
 * Single post and custom post type entry.
 *
 * @package Astitva
 */

defined( 'ABSPATH' ) || exit;

get_header();
while ( have_posts() ) {
	the_post();
	if ( agi_is_builder_page() ) {
		the_content();
		continue;
	}
	agi_page_hero( get_the_title(), 'post' === get_post_type() ? esc_html( get_the_date() ) . ' &middot; ' . esc_html( get_the_author() ) : get_the_excerpt() );
	echo '<article class="content-wrap content-wrap--narrow entry-content">';
	if ( has_post_thumbnail() ) {
		the_post_thumbnail( 'large', array( 'class' => 'entry-image' ) );
	}
	the_content();
	wp_link_pages();
	if ( 'post' === get_post_type() ) {
		the_tags( '<p class="entry-tags">', ' ', '</p>' );
		the_post_navigation();
	}
	echo '</article>';
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
}
get_footer();
