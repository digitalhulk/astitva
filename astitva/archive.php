<?php
/**
 * Archive template for posts and custom post types.
 *
 * @package Astitva
 */

defined( 'ABSPATH' ) || exit;

get_header();
agi_page_hero( get_the_archive_title(), get_the_archive_description() );
echo '<div class="content-wrap">';
agi_card_loop();
echo '</div>';
get_footer();
