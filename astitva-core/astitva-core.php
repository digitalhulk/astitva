<?php
/**
 * Plugin Name: Astitva Core
 * Description: Elementor widgets, post types and one-click demo content for the Astitva theme.
 * Version: 1.0.0
 * Requires at least: 6.5
 * Requires PHP: 8.1
 * Author: Your Company
 * License: GPL-2.0-or-later
 * Text Domain: astitva-core
 *
 * @package Astitva_Core
 */

defined( 'ABSPATH' ) || exit;

define( 'AGIC_VERSION', '1.0.0' );
define( 'AGIC_DIR', plugin_dir_path( __FILE__ ) );
define( 'AGIC_URL', plugin_dir_url( __FILE__ ) );

require_once AGIC_DIR . 'includes/post-types.php';
require_once AGIC_DIR . 'includes/importer.php';
require_once AGIC_DIR . 'includes/license.php';
require_once AGIC_DIR . 'includes/updates.php';

/**
 * Register front-end assets; widgets declare them as dependencies.
 */
function agic_register_assets() {
	wp_register_style( 'agic-widgets', AGIC_URL . 'assets/css/widgets.css', array(), filemtime( AGIC_DIR . 'assets/css/widgets.css' ) );
	wp_register_script( 'agic-widgets', AGIC_URL . 'assets/js/widgets.js', array(), filemtime( AGIC_DIR . 'assets/js/widgets.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'agic_register_assets', 5 );
add_action( 'elementor/editor/before_enqueue_scripts', 'agic_register_assets' );
add_action( 'elementor/preview/enqueue_styles', 'agic_register_assets' );

/**
 * Widget category.
 *
 * @param \Elementor\Elements_Manager $manager Manager.
 */
function agic_category( $manager ) {
	$manager->add_category( 'astitva', array( 'title' => __( 'Astitva', 'astitva-core' ), 'icon' => 'eicon-star' ) );
}
add_action( 'elementor/elements/categories_registered', 'agic_category' );

/**
 * Register all widgets.
 *
 * @param \Elementor\Widgets_Manager $manager Manager.
 */
function agic_register_widgets( $manager ) {
	require_once AGIC_DIR . 'includes/widget-base.php';
	require_once AGIC_DIR . 'includes/widgets-basic.php';
	require_once AGIC_DIR . 'includes/widgets-media.php';
	foreach ( \Astitva\agic_widget_classes_list() as $class ) {
		$manager->register( new $class() );
	}
}
add_action( 'elementor/widgets/register', 'agic_register_widgets' );

/**
 * Admin notice when Elementor is missing.
 */
function agic_requires_elementor() {
	if ( did_action( 'elementor/loaded' ) || ! current_user_can( 'install_plugins' ) ) {
		return;
	}
	echo '<div class="notice notice-warning"><p>' . esc_html__( 'Astitva Core needs the free Elementor plugin.', 'astitva-core' ) . '</p></div>';
}
add_action( 'admin_notices', 'agic_requires_elementor' );
