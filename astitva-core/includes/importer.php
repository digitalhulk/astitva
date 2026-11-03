<?php
/**
 * One-click demo import: pages, menus, posts, post types, form and options.
 * Safe to run again: existing items are updated by slug, not duplicated.
 *
 * @package Astitva_Core
 */

defined( 'ABSPATH' ) || exit;

require_once AGIC_DIR . 'includes/demo-pages.php';

/** Add the Tools screen. */
function agic_import_menu() {
	add_management_page( __( 'Import Astitva Demo', 'astitva-core' ), __( 'Import Astitva Demo', 'astitva-core' ), 'manage_options', 'agic-import', 'agic_import_screen' );
}
add_action( 'admin_menu', 'agic_import_menu' );

/** Render the Tools screen and handle the form. */
function agic_import_screen() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	echo '<div class="wrap"><h1>' . esc_html__( 'Import Astitva Demo', 'astitva-core' ) . '</h1>';
	if ( ! did_action( 'elementor/loaded' ) ) {
		echo '<div class="notice notice-error"><p>' . esc_html__( 'Install and activate Elementor first.', 'astitva-core' ) . '</p></div></div>';
		return;
	}
	if ( isset( $_POST['agic_run'] ) ) {
		check_admin_referer( 'agic_import' );
		$pages = agic_import_all();
		/* translators: %d: number of pages. */
		echo '<div class="notice notice-success"><p>' . esc_html( sprintf( __( 'Imported %d pages. Your site is ready.', 'astitva-core' ), $pages ) ) . '</p></div>';
	}
	echo '<p>' . esc_html__( 'Creates all pages, menus, sample posts, services, projects, team, jobs and the contact form. The front page and blog settings will change. Pages with the same address are overwritten.', 'astitva-core' ) . '</p>';
	echo '<form method="post">';
	wp_nonce_field( 'agic_import' );
	submit_button( __( 'Import demo content', 'astitva-core' ), 'primary', 'agic_run' );
	echo '</form></div>';
}

/**
 * Copy a bundled image into the media library once.
 *
 * @param string $file File name in assets/img.
 * @return array Elementor media value.
 */
function agic_media( $file ) {
	static $cache = array();
	if ( isset( $cache[ $file ] ) ) {
		return $cache[ $file ];
	}
	$found = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'meta_key' => '_agic_source', 'meta_value' => $file, 'posts_per_page' => 1, 'fields' => 'ids' ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
	if ( $found ) {
		$id = (int) $found[0];
	} else {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$upload = wp_upload_bits( $file, null, file_get_contents( AGIC_DIR . 'assets/img/' . $file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$type   = wp_check_filetype( $upload['file'] );
		$id     = wp_insert_attachment( array( 'post_mime_type' => $type['type'], 'post_title' => pathinfo( $file, PATHINFO_FILENAME ), 'post_status' => 'inherit' ), $upload['file'] );
		wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
		update_post_meta( $id, '_agic_source', $file );
	}
	$cache[ $file ] = array( 'id' => $id, 'url' => wp_get_attachment_url( $id ) );
	return $cache[ $file ];
}

/**
 * Create or update a page or post by slug.
 *
 * @param string $slug  Slug.
 * @param string $title Title.
 * @param array  $args  Extra wp_insert_post fields.
 * @return int
 */
function agic_upsert( $slug, $title, $args = array() ) {
	$type  = $args['post_type'] ?? 'page';
	$found = get_posts( array( 'name' => $slug, 'post_type' => $type, 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ) );
	$data  = array_merge( array( 'post_title' => $title, 'post_name' => $slug, 'post_status' => 'publish', 'post_type' => $type ), $args );
	if ( $found ) {
		$data['ID'] = $found[0];
	}
	return wp_insert_post( wp_slash( $data ) );
}

/**
 * Store Elementor layout on a post.
 *
 * @param int   $id       Post ID.
 * @param array $elements Elements tree.
 */
function agic_set_layout( $id, $elements ) {
	update_post_meta( $id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $id, '_elementor_template_type', 'wp-page' );
	update_post_meta( $id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );
	update_post_meta( $id, '_elementor_data', wp_slash( wp_json_encode( $elements ) ) );
}

/**
 * Build a nav menu.
 *
 * @param string $name     Menu name.
 * @param string $location Theme location.
 * @param array  $items    Label => URL, or label => array of children.
 * @return int
 */
function agic_menu( $name, $location, $items ) {
	$existing = wp_get_nav_menu_object( $name );
	if ( $existing ) {
		wp_delete_nav_menu( $existing->term_id );
	}
	$menu = wp_create_nav_menu( $name );
	foreach ( $items as $label => $target ) {
		$children = is_array( $target ) ? $target : array();
		$parent   = wp_update_nav_menu_item( $menu, 0, array( 'menu-item-title' => $label, 'menu-item-url' => $children ? '#' : $target, 'menu-item-status' => 'publish', 'menu-item-type' => 'custom' ) );
		foreach ( $children as $child => $url ) {
			wp_update_nav_menu_item( $menu, 0, array( 'menu-item-title' => $child, 'menu-item-url' => $url, 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $parent ) );
		}
	}
	$locations              = get_theme_mod( 'nav_menu_locations', array() );
	$locations[ $location ] = $menu;
	set_theme_mod( 'nav_menu_locations', $locations );
	return $menu;
}

/**
 * Create the Contact Form 7 form.
 *
 * @return string Shortcode, or empty when CF7 is missing.
 */
function agic_contact_form() {
	if ( ! function_exists( 'wpcf7_save_contact_form' ) ) {
		return '';
	}
	$found = get_posts( array( 'post_type' => 'wpcf7_contact_form', 'name' => 'agency-contact', 'posts_per_page' => 1, 'fields' => 'ids' ) );
	// CF7's own save function rebuilds the validation schema from these fields.
	$form = wpcf7_save_contact_form(
		array(
			'id'    => $found ? $found[0] : -1,
			'title' => 'Agency Contact',
			'form'  => "[email* your-email placeholder \"Email address\"]\n[text* your-subject placeholder \"Subject\"]\n[textarea* your-message placeholder \"Write your message\"]\n[submit \"Send message\"]",
		)
	);
	$id   = $form->id();
	wp_update_post( array( 'ID' => $id, 'post_name' => 'agency-contact' ) );
	return '[contact-form-7 id="' . absint( $id ) . '" title="Agency Contact"]';
}

/**
 * Run the whole import.
 *
 * @return int Number of pages created or updated.
 */
function agic_import_all() {
	// Elementor saves check capabilities, and WP-CLI runs with no user.
	if ( ! current_user_can( 'manage_options' ) && defined( 'WP_CLI' ) && WP_CLI ) {
		$admins = get_users( array( 'role' => 'administrator', 'number' => 1, 'fields' => 'ids' ) );
		wp_set_current_user( $admins ? (int) $admins[0] : 0 );
	}

	$home = home_url( '/' );
	$url  = static fn( $path ) => $home . $path;

	// Remove the WordPress starter content so it does not appear in the demo.
	foreach ( array( 'hello-world' => 'post', 'sample-page' => 'page' ) as $slug => $type ) {
		$starter = get_page_by_path( $slug, OBJECT, $type );
		if ( $starter ) {
			wp_delete_post( $starter->ID, true );
		}
	}

	$form  = agic_contact_form();
	$pages = agic_demo_pages( $form );
	$ids   = array();
	foreach ( $pages as $slug => $page ) {
		$ids[ $slug ] = agic_upsert( $slug, $page['title'] );
		agic_set_layout( $ids[ $slug ], $page['layout'] );
	}
	foreach ( agic_demo_legal() as $slug => $page ) {
		$ids[ $slug ] = agic_upsert( $slug, $page['title'], array( 'post_content' => $page['content'] ) );
	}
	$ids['blog'] = agic_upsert( 'blog', 'Blog' );

	foreach ( agic_demo_entries() as $entry ) {
		$args = array( 'post_type' => $entry['type'], 'post_excerpt' => $entry['excerpt'], 'post_content' => $entry['content'] );
		if ( ! empty( $entry['date'] ) ) {
			$args['post_date'] = $entry['date'];
		}
		$id = agic_upsert( $entry['slug'], $entry['title'], $args );
		set_post_thumbnail( $id, agic_media( $entry['image'] )['id'] );
		if ( 'post' === $entry['type'] ) {
			wp_set_object_terms( $id, $entry['category'], 'category' );
			wp_set_object_terms( $id, $entry['tags'], 'post_tag' );
		}
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $ids['home'] );
	update_option( 'page_for_posts', $ids['blog'] );
	update_option( 'elementor_disable_color_schemes', 'yes' );
	update_option( 'elementor_disable_typography_schemes', 'yes' );

	$options = (array) get_option( 'agi_options', array() );
	update_option(
		'agi_options',
		array_merge(
			$options,
			array(
				'cookie_page'      => $ids['cookie-policy'],
				'page_404'         => $ids['404-page'],
				'cta_url'          => $url( 'contact/' ),
				'social_facebook'  => $options['social_facebook'] ?? 'https://facebook.com/',
				'social_x'         => $options['social_x'] ?? 'https://x.com/',
				'social_instagram' => $options['social_instagram'] ?? 'https://instagram.com/',
				'social_linkedin'  => $options['social_linkedin'] ?? 'https://linkedin.com/',
			)
		)
	);

	agic_menu(
		'Primary',
		'primary',
		array(
			'Home'     => $home,
			'Services' => $url( 'services/' ),
			'Pages'    => array(
				'Features'     => $url( 'features/' ),
				'Showcase'     => $url( 'showcase/' ),
				'Project Page' => $url( 'project-page/' ),
				'Pricing'      => $url( 'pricing/' ),
				'Reviews'      => $url( 'reviews/' ),
				'Blog'         => $url( 'blog/' ),
				'Team'         => $url( 'team/' ),
				'Careers'      => $url( 'careers/' ),
				'FAQ'          => $url( 'faq/' ),
				'Coming Soon'  => $url( 'coming-soon/' ),
			),
			'About'    => $url( 'about/' ),
			'Contact'  => $url( 'contact/' ),
		)
	);
	agic_menu( 'Launcher', 'launcher', array( 'Services' => $url( 'services/' ), 'Showcase' => $url( 'showcase/' ), 'Pricing' => $url( 'pricing/' ), 'About' => $url( 'about/' ), 'Blog' => $url( 'blog/' ), 'Contact' => $url( 'contact/' ) ) );
	agic_menu( 'Footer One', 'footer-1', array( 'Services' => $url( 'services/' ), 'Pricing' => $url( 'pricing/' ), 'Projects' => $url( 'projects/' ), 'Reviews' => $url( 'reviews/' ), 'FAQ' => $url( 'faq/' ) ) );
	agic_menu( 'Footer Two', 'footer-2', array( 'About' => $url( 'about/' ), 'Blog' => $url( 'blog/' ), 'Careers' => $url( 'careers/' ), 'Contact' => $url( 'contact/' ), 'Terms' => $url( 'terms-of-use/' ), 'Privacy' => $url( 'privacy-policy/' ), 'Cookies' => $url( 'cookie-policy/' ) ) );

	agic_elementor_cpt_support();
	// Use the rewrite object: updating the option alone leaves stale rules in this request.
	global $wp_rewrite;
	$wp_rewrite->set_permalink_structure( '/%postname%/' );
	$wp_rewrite->flush_rules( true );
	if ( class_exists( '\Elementor\Plugin' ) ) {
		\Elementor\Plugin::instance()->files_manager->clear_cache();
		// Also drop stored CSS records, so a repeat import never points at deleted files.
		delete_post_meta_by_key( '_elementor_css' );
		delete_option( '_elementor_global_css' );
	}
	return count( $ids );
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command(
		'agi-import',
		static function () {
			WP_CLI::success( agic_import_all() . ' pages imported.' );
		}
	);
}
