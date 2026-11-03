<?php
/**
 * Default fallback template.
 *
 * @package Astitva
 */

defined( 'ABSPATH' ) || exit;

get_header();
agi_page_hero( is_home() ? single_post_title( '', false ) : get_the_archive_title(), '' );
echo '<div class="content-wrap">';
agi_card_loop();
echo '</div>';
get_footer();
