<?php
/**
 * Comments.
 *
 * @package Astitva
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
echo '<section class="content-wrap content-wrap--narrow comments-area" id="comments">';
if ( have_comments() ) {
	echo '<h2>' . esc_html( get_comments_number_text() ) . '</h2><ol class="comment-list">';
	wp_list_comments( array( 'style' => 'ol', 'short_ping' => true, 'avatar_size' => 48 ) );
	echo '</ol>';
	the_comments_navigation();
}
comment_form();
echo '</section>';
