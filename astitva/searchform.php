<?php
/**
 * Search form.
 *
 * @package Astitva
 */

defined( 'ABSPATH' ) || exit;
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="agi-s"><?php esc_html_e( 'Search', 'astitva' ); ?></label>
	<input id="agi-s" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search', 'astitva' ); ?>">
	<button class="btn btn--primary btn--md" type="submit"><?php esc_html_e( 'Search', 'astitva' ); ?></button>
</form>
