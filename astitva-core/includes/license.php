<?php
/**
 * Astitva license keys. A key is signed for one domain and checked on the site
 * itself, so no license server is needed. A valid key unlocks automatic updates.
 *
 * @package Astitva_Core
 */

defined( 'ABSPATH' ) || exit;

/** Public half of the signing key; the private half never leaves the owner's computer. */
const AGIC_LICENSE_PUBLIC_KEY = 'Ccra4q6ByRX4vPBhasnJfBETIjetHb+b13L+ISp1Bdw=';

/**
 * Decode base64url.
 *
 * @param string $s Input.
 * @return string
 */
function agic_b64u_decode( $s ) {
	return (string) base64_decode( strtr( $s, '-_', '+/' ) . str_repeat( '=', ( 4 - strlen( $s ) % 4 ) % 4 ), true );
}

/**
 * Verify a key's signature and return its data.
 *
 * @param string $key License key.
 * @return array|null
 */
function agic_license_parse( $key ) {
	if ( ! preg_match( '/^ASTV-([A-Za-z0-9_-]+)\.([A-Za-z0-9_-]+)$/', trim( (string) $key ), $m ) || ! function_exists( 'sodium_crypto_sign_verify_detached' ) ) {
		return null;
	}
	$payload = agic_b64u_decode( $m[1] );
	$sig     = agic_b64u_decode( $m[2] );
	if ( SODIUM_CRYPTO_SIGN_BYTES !== strlen( $sig ) ) {
		return null;
	}
	try {
		if ( ! sodium_crypto_sign_verify_detached( $sig, $payload, base64_decode( AGIC_LICENSE_PUBLIC_KEY ) ) ) {
			return null;
		}
	} catch ( SodiumException $e ) {
		return null;
	}
	$data = json_decode( $payload, true );
	return is_array( $data ) ? $data : null;
}

/**
 * This site's host name without www.
 *
 * @return string
 */
function agic_site_host() {
	return preg_replace( '/^www\./', '', strtolower( (string) wp_parse_url( home_url(), PHP_URL_HOST ) ) );
}

/**
 * Local development hosts never need a key.
 *
 * @param string $host Host.
 * @return bool
 */
function agic_is_dev_host( $host ) {
	return in_array( $host, array( 'localhost', '127.0.0.1', '[::1]' ), true ) || (bool) preg_match( '/\.(localhost|test|local)$/', $host );
}

/**
 * License state for this site.
 *
 * @return array valid (bool), message (string), owner, domain.
 */
function agic_license_status() {
	$host = agic_site_host();
	if ( agic_is_dev_host( $host ) ) {
		return array( 'valid' => true, 'message' => __( 'Local development site: no key needed.', 'astitva-core' ), 'owner' => '', 'domain' => $host );
	}
	$key = get_option( 'astitva_license_key', '' );
	if ( '' === $key ) {
		return array( 'valid' => false, 'message' => __( 'No license key entered.', 'astitva-core' ), 'owner' => '', 'domain' => '' );
	}
	$data = agic_license_parse( $key );
	if ( ! $data ) {
		return array( 'valid' => false, 'message' => __( 'This key is not a genuine Astitva key.', 'astitva-core' ), 'owner' => '', 'domain' => '' );
	}
	$domain = preg_replace( '/^www\./', '', strtolower( (string) ( $data['d'] ?? '' ) ) );
	$owner  = (string) ( $data['n'] ?? '' );
	if ( '*' !== $domain && $host !== $domain && ! str_ends_with( $host, '.' . $domain ) ) {
		/* translators: 1: domain in the key, 2: this site's domain. */
		return array( 'valid' => false, 'message' => sprintf( __( 'This key is for %1$s, but this site is %2$s.', 'astitva-core' ), $domain, $host ), 'owner' => $owner, 'domain' => $domain );
	}
	if ( ! empty( $data['e'] ) && time() > (int) $data['e'] ) {
		return array( 'valid' => false, 'message' => __( 'This key has expired.', 'astitva-core' ), 'owner' => $owner, 'domain' => $domain );
	}
	return array( 'valid' => true, 'message' => __( 'License active.', 'astitva-core' ), 'owner' => $owner, 'domain' => $domain );
}

/** Settings screen. */
function agic_license_menu() {
	add_options_page( __( 'Astitva License', 'astitva-core' ), __( 'Astitva License', 'astitva-core' ), 'manage_options', 'astitva-license', 'agic_license_screen' );
}
add_action( 'admin_menu', 'agic_license_menu' );

/** Render and handle the license screen. */
function agic_license_screen() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$notice = '';
	if ( isset( $_POST['agic_license_save'] ) ) {
		check_admin_referer( 'agic_license' );
		$key = isset( $_POST['astitva_license_key'] ) ? sanitize_text_field( wp_unslash( $_POST['astitva_license_key'] ) ) : '';
		update_option( 'astitva_license_key', $key, false );
		$url = isset( $_POST['astitva_update_url'] ) ? esc_url_raw( wp_unslash( $_POST['astitva_update_url'] ), array( 'https' ) ) : '';
		update_option( 'astitva_update_url', $url, false );
		agic_update_flush();
		$notice = __( 'Saved.', 'astitva-core' );
	}
	if ( isset( $_POST['agic_update_check'] ) ) {
		check_admin_referer( 'agic_license' );
		agic_update_flush();
		wp_update_themes();
		wp_update_plugins();
		$notice = __( 'Checked for updates.', 'astitva-core' );
	}
	$status = agic_license_status();
	$info   = agic_update_info();
	echo '<div class="wrap"><h1>' . esc_html__( 'Astitva License', 'astitva-core' ) . '</h1>';
	if ( $notice ) {
		echo '<div class="notice notice-success"><p>' . esc_html( $notice ) . '</p></div>';
	}
	echo '<p><strong>' . esc_html__( 'Status:', 'astitva-core' ) . '</strong> <span style="color:' . ( $status['valid'] ? '#008a20' : '#b32d2e' ) . '">' . esc_html( $status['message'] ) . '</span>';
	if ( $status['owner'] ) {
		echo ' ' . esc_html__( 'Licensed to', 'astitva-core' ) . ' ' . esc_html( $status['owner'] ) . '.';
	}
	echo '</p><form method="post">';
	wp_nonce_field( 'agic_license' );
	echo '<table class="form-table" role="presentation"><tr><th scope="row"><label for="astitva_license_key">' . esc_html__( 'License key', 'astitva-core' ) . '</label></th><td><input class="large-text code" id="astitva_license_key" name="astitva_license_key" value="' . esc_attr( get_option( 'astitva_license_key', '' ) ) . '" autocomplete="off"></td></tr>';
	echo '<tr><th scope="row"><label for="astitva_update_url">' . esc_html__( 'Update server', 'astitva-core' ) . '</label></th><td><input class="large-text code" id="astitva_update_url" name="astitva_update_url" value="' . esc_attr( get_option( 'astitva_update_url', '' ) ) . '" placeholder="' . esc_attr( AGIC_UPDATE_URL ) . '"><p class="description">' . esc_html__( 'Leave empty to use the default. Must be an https link to update.json.', 'astitva-core' ) . '</p></td></tr></table>';
	submit_button( __( 'Save', 'astitva-core' ), 'primary', 'agic_license_save', false );
	echo ' ';
	submit_button( __( 'Check for updates now', 'astitva-core' ), 'secondary', 'agic_update_check', false );
	echo '</form><h2>' . esc_html__( 'Versions', 'astitva-core' ) . '</h2><table class="widefat striped" style="max-width:640px"><thead><tr><th></th><th>' . esc_html__( 'Installed', 'astitva-core' ) . '</th><th>' . esc_html__( 'Latest', 'astitva-core' ) . '</th></tr></thead><tbody>';
	$theme = wp_get_theme( 'astitva' );
	echo '<tr><td>Astitva theme</td><td>' . esc_html( $theme->exists() ? $theme->get( 'Version' ) : '-' ) . '</td><td>' . esc_html( $info['theme']['version'] ?? __( 'unknown', 'astitva-core' ) ) . '</td></tr>';
	echo '<tr><td>Astitva Core plugin</td><td>' . esc_html( AGIC_VERSION ) . '</td><td>' . esc_html( $info['plugin']['version'] ?? __( 'unknown', 'astitva-core' ) ) . '</td></tr></tbody></table></div>';
}

/** Remind admins when updates are blocked by a missing key. */
function agic_license_notice() {
	$screen = get_current_screen();
	if ( ! current_user_can( 'manage_options' ) || ! $screen || ! in_array( $screen->id, array( 'themes', 'plugins', 'update-core', 'dashboard' ), true ) || agic_license_status()['valid'] ) {
		return;
	}
	echo '<div class="notice notice-warning"><p>' . esc_html__( 'Astitva updates are off until you add a license key.', 'astitva-core' ) . ' <a href="' . esc_url( admin_url( 'options-general.php?page=astitva-license' ) ) . '">' . esc_html__( 'Add license key', 'astitva-core' ) . '</a></p></div>';
}
add_action( 'admin_notices', 'agic_license_notice' );
