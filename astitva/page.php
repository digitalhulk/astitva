<?php
/**
 * Pages.
 *
 * @package Astitva
 */

defined( 'ABSPATH' ) || exit;

get_header();
while ( have_posts() ) {
	the_post();
	if ( agi_is_builder_page() ) {
		the_content();
	} else {
		agi_page_hero( get_the_title() );
		echo '<article class="content-wrap content-wrap--narrow entry-content">';
		the_content();
		wp_link_pages();
		echo '</article>';
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	}
}
get_footer();
