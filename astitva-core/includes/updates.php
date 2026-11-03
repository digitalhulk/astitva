<?php
/**
 * Automatic updates for the Astitva theme and this plugin through WordPress's
 * normal update screens. Reads one update.json file, so any free static host works.
 *
 * update.json format:
 * { "theme":  { "version": "1.0.1", "package": "https://.../astitva.zip", "url": "https://...", "requires": "6.5", "requires_php": "8.1" },
 *   "plugin": { "version": "1.0.1", "package": "https://.../astitva-core.zip", "url": "https://...", "requires": "6.5", "requires_php": "8.1", "tested": "6.9", "changelog": "<ul><li>...</li></ul>" } }
 *
 * @package Astitva_Core
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'AGIC_UPDATE_URL' ) ) {
	define( 'AGIC_UPDATE_URL', 'https://raw.githubusercontent.com/digitalhulk/astitva/main/update.json' );
}

/**
 * Update feed address.
 *
 * @return string
 */
function agic_update_url() {
	$saved = get_option( 'astitva_update_url', '' );
	return (string) apply_filters( 'agic_update_url', $saved ? $saved : AGIC_UPDATE_URL );
}

/** Forget cached update data. */
function agic_update_flush() {
	delete_site_transient( 'agic_update_info' );
	delete_site_transient( 'update_themes' );
	delete_site_transient( 'update_plugins' );
}

/**
 * Latest version data, cached for 12 hours (1 hour after a failure).
 *
 * @return array
 */
function agic_update_info() {
	$cached = get_site_transient( 'agic_update_info' );
	if ( is_array( $cached ) ) {
		return $cached;
	}
	$info     = array();
	$response = wp_safe_remote_get( agic_update_url(), array( 'timeout' => 10 ) );
	if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
		$json = json_decode( wp_remote_retrieve_body( $response ), true );
		foreach ( array( 'theme', 'plugin' ) as $part ) {
			if ( isset( $json[ $part ]['version'], $json[ $part ]['package'] ) && 0 === strpos( (string) $json[ $part ]['package'], 'https://' ) ) {
				$info[ $part ] = array_map( 'strval', array_intersect_key( (array) $json[ $part ], array_flip( array( 'version', 'package', 'url', 'requires', 'requires_php', 'tested', 'changelog' ) ) ) );
			}
		}
	}
	set_site_transient( 'agic_update_info', $info, $info ? 12 * HOUR_IN_SECONDS : HOUR_IN_SECONDS );
	return $info;
}

/**
 * Whether a remote entry is newer than what is installed and this site may use it.
 *
 * @param array  $remote    Remote entry.
 * @param string $installed Installed version.
 * @return bool
 */
function agic_update_ready( $remote, $installed ) {
	return ! empty( $remote['version'] ) && version_compare( $remote['version'], $installed, '>' ) && agic_license_status()['valid']
		&& ( empty( $remote['requires_php'] ) || version_compare( PHP_VERSION, $remote['requires_php'], '>=' ) );
}

/**
 * Add the theme update.
 *
 * @param object $transient update_themes transient.
 * @return object
 */
function agic_theme_update( $transient ) {
	$theme = wp_get_theme( 'astitva' );
	if ( ! is_object( $transient ) || ! $theme->exists() ) {
		return $transient;
	}
	$remote = agic_update_info()['theme'] ?? array();
	$item   = array(
		'theme'        => 'astitva',
		'new_version'  => $remote['version'] ?? $theme->get( 'Version' ),
		'url'          => $remote['url'] ?? '',
		'package'      => $remote['package'] ?? '',
		'requires'     => $remote['requires'] ?? '',
		'requires_php' => $remote['requires_php'] ?? '',
	);
	if ( agic_update_ready( $remote, $theme->get( 'Version' ) ) ) {
		$transient->response['astitva'] = $item;
	} else {
		unset( $transient->response['astitva'] );
		$transient->no_update['astitva'] = $item;
	}
	return $transient;
}
add_filter( 'pre_set_site_transient_update_themes', 'agic_theme_update' );

/**
 * Add the plugin update.
 *
 * @param object $transient update_plugins transient.
 * @return object
 */
function agic_plugin_update( $transient ) {
	if ( ! is_object( $transient ) ) {
		return $transient;
	}
	$file   = plugin_basename( AGIC_DIR . 'astitva-core.php' );
	$remote = agic_update_info()['plugin'] ?? array();
	$item   = (object) array(
		'id'           => 'astitva-core',
		'slug'         => 'astitva-core',
		'plugin'       => $file,
		'new_version'  => $remote['version'] ?? AGIC_VERSION,
		'url'          => $remote['url'] ?? '',
		'package'      => $remote['package'] ?? '',
		'requires'     => $remote['requires'] ?? '',
		'requires_php' => $remote['requires_php'] ?? '',
		'tested'       => $remote['tested'] ?? '',
	);
	if ( agic_update_ready( $remote, AGIC_VERSION ) ) {
		$transient->response[ $file ] = $item;
	} else {
		unset( $transient->response[ $file ] );
		$transient->no_update[ $file ] = $item;
	}
	return $transient;
}
add_filter( 'pre_set_site_transient_update_plugins', 'agic_plugin_update' );

/**
 * "View details" popup for the plugin.
 *
 * @param false|object $result Result.
 * @param string       $action Action.
 * @param object       $args   Arguments.
 * @return false|object
 */
function agic_plugin_details( $result, $action, $args ) {
	if ( 'plugin_information' !== $action || empty( $args->slug ) || 'astitva-core' !== $args->slug ) {
		return $result;
	}
	$remote = agic_update_info()['plugin'] ?? array();
	return (object) array(
		'name'          => 'Astitva Core',
		'slug'          => 'astitva-core',
		'version'       => $remote['version'] ?? AGIC_VERSION,
		'requires'      => $remote['requires'] ?? '',
		'requires_php'  => $remote['requires_php'] ?? '',
		'tested'        => $remote['tested'] ?? '',
		'download_link' => $remote['package'] ?? '',
		'sections'      => array( 'changelog' => wp_kses_post( $remote['changelog'] ?? __( 'No changelog provided.', 'astitva-core' ) ) ),
	);
}
add_filter( 'plugins_api', 'agic_plugin_details', 10, 3 );
