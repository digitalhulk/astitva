<?php
/**
 * Post types for services, projects, team and careers.
 *
 * @package Astitva_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registered post type definitions: key => array( plural, singular, slug, archive slug ).
 *
 * @return array
 */
function agic_post_types() {
	return array(
		'agi_service' => array( __( 'Services', 'astitva-core' ), __( 'Service', 'astitva-core' ), 'service', 'all-services', 'dashicons-admin-tools' ),
		'agi_project' => array( __( 'Projects', 'astitva-core' ), __( 'Project', 'astitva-core' ), 'project', 'projects', 'dashicons-portfolio' ),
		'agi_team'    => array( __( 'Team', 'astitva-core' ), __( 'Team Member', 'astitva-core' ), 'team-member', 'team', 'dashicons-groups' ),
		'agi_career'  => array( __( 'Careers', 'astitva-core' ), __( 'Job', 'astitva-core' ), 'job', 'careers', 'dashicons-businessperson' ),
	);
}

/**
 * Register post types.
 */
function agic_register_post_types() {
	foreach ( agic_post_types() as $type => $def ) {
		register_post_type(
			$type,
			array(
				'labels'       => array( 'name' => $def[0], 'singular_name' => $def[1], 'add_new_item' => $def[1], 'edit_item' => $def[1] ),
				'public'       => true,
				'has_archive'  => $def[3],
				'rewrite'      => array( 'slug' => $def[2], 'with_front' => false ),
				'menu_icon'    => $def[4],
				'show_in_rest' => true,
				'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			)
		);
	}
}
add_action( 'init', 'agic_register_post_types' );

/**
 * Make the new post types editable with Elementor.
 */
function agic_elementor_cpt_support() {
	$types = get_option( 'elementor_cpt_support', array( 'page', 'post' ) );
	$merged = array_unique( array_merge( (array) $types, array_keys( agic_post_types() ) ) );
	update_option( 'elementor_cpt_support', $merged );
}

/**
 * Activation: register types, flush rewrite rules, enable Elementor on them.
 */
function agic_activate() {
	agic_register_post_types();
	agic_elementor_cpt_support();
	flush_rewrite_rules();
}
register_activation_hook( AGIC_DIR . 'astitva-core.php', 'agic_activate' );
