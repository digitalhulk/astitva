<?php
/**
 * Search results.
 *
 * @package Astitva
 */

defined( 'ABSPATH' ) || exit;

get_header();
/* translators: %s: search term. */
agi_page_hero( sprintf( esc_html__( 'Results for: %s', 'astitva' ), esc_html( get_search_query() ) ) );
echo '<div class="content-wrap">';
get_search_form();
agi_card_loop();
echo '</div>';
get_footer();
