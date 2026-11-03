<?php
/**
 * Theme Options panel.
 *
 * @package Astitva
 */

defined( 'ABSPATH' ) || exit;

/**
 * Option groups and fields. Keys are stored in the agi_options array.
 *
 * @return array
 */
function agi_fields() {
	$fonts = array( 'Bricolage Grotesque', 'Inter', 'DM Sans', 'Manrope', 'Outfit', 'Plus Jakarta Sans', 'Poppins', 'Roboto', 'Sora', 'Space Grotesk', 'Urbanist', 'Work Sans' );
	$type  = array();
	foreach ( array( 'h1' => 56, 'h2' => 48, 'h3' => 36, 'h4' => 24, 'h5' => 18, 'h6' => 16, 'body' => 16, 'button' => 14, 'nav' => 16 ) as $key => $size ) {
		$label                  = strtoupper( $key );
		$type[ 'size_' . $key ]   = array( 'label' => $label . ' ' . __( 'size (px)', 'astitva' ), 'type' => 'number', 'default' => $size );
		$type[ 'weight_' . $key ] = array( 'label' => $label . ' ' . __( 'weight', 'astitva' ), 'type' => 'select', 'choices' => array( '400' => '400', '600' => '600' ), 'default' => 'body' === $key ? '400' : '600' );
	}
	return array(
		'branding'   => array(
			'title'  => __( 'Logo', 'astitva' ),
			'fields' => array(
				'logo'        => array( 'label' => __( 'Desktop logo', 'astitva' ), 'type' => 'image', 'default' => '' ),
				'logo_mobile' => array( 'label' => __( 'Mobile logo', 'astitva' ), 'type' => 'image', 'default' => '' ),
				'logo_sticky' => array( 'label' => __( 'Sticky logo', 'astitva' ), 'type' => 'image', 'default' => '' ),
			),
		),
		'colors'     => array(
			'title'  => __( 'Colors', 'astitva' ),
			'fields' => array(
				'color_primary'   => array( 'label' => __( 'Primary', 'astitva' ), 'type' => 'color', 'default' => '#007aff' ),
				'color_secondary' => array( 'label' => __( 'Secondary', 'astitva' ), 'type' => 'color', 'default' => '#312ecb' ),
				'color_heading'   => array( 'label' => __( 'Heading text', 'astitva' ), 'type' => 'color', 'default' => '#000000' ),
				'color_body'      => array( 'label' => __( 'Body text', 'astitva' ), 'type' => 'color', 'default' => '#52525b' ),
				'color_bg'        => array( 'label' => __( 'Background', 'astitva' ), 'type' => 'color', 'default' => '#ffffff' ),
				'color_border'    => array( 'label' => __( 'Borders', 'astitva' ), 'type' => 'color', 'default' => '#e4e4e7' ),
			),
		),
		'typography' => array(
			'title'  => __( 'Typography', 'astitva' ),
			'fields' => array_merge(
				array(
					'font_heading' => array( 'label' => __( 'Heading, button and nav font', 'astitva' ), 'type' => 'select', 'choices' => array_combine( $fonts, $fonts ), 'default' => 'Bricolage Grotesque' ),
					'font_body'    => array( 'label' => __( 'Body font', 'astitva' ), 'type' => 'select', 'choices' => array_combine( $fonts, $fonts ), 'default' => 'Inter' ),
				),
				$type
			),
		),
		'layout'     => array(
			'title'  => __( 'Header and Footer', 'astitva' ),
			'fields' => array(
				'header_template' => array( 'label' => __( 'Header template (Elementor)', 'astitva' ), 'type' => 'template', 'default' => 0 ),
				'footer_template' => array( 'label' => __( 'Footer template (Elementor)', 'astitva' ), 'type' => 'template', 'default' => 0 ),
				'header_blur'     => array( 'label' => __( 'Blur header background on scroll', 'astitva' ), 'type' => 'checkbox', 'default' => 0 ),
				'cta_label'       => array( 'label' => __( 'Header link label', 'astitva' ), 'type' => 'text', 'default' => 'Start a project' ),
				'cta_url'         => array( 'label' => __( 'Header link URL', 'astitva' ), 'type' => 'url', 'default' => '' ),
				'launcher_label'  => array( 'label' => __( 'Header button label', 'astitva' ), 'type' => 'text', 'default' => 'Open menu' ),
				'location'        => array( 'label' => __( 'Location shown under header', 'astitva' ), 'type' => 'text', 'default' => 'Your City, Country' ),
				'phone'           => array( 'label' => __( 'Phone', 'astitva' ), 'type' => 'text', 'default' => '+00 000 000 000' ),
				'footer_title'    => array( 'label' => __( 'Footer heading', 'astitva' ), 'type' => 'text', 'default' => 'Smart work for ambitious teams' ),
				'footer_text'     => array( 'label' => __( 'Footer text', 'astitva' ), 'type' => 'textarea', 'default' => 'Have a project in mind? Let us talk.' ),
				'copyright'       => array( 'label' => __( 'Copyright text', 'astitva' ), 'type' => 'text', 'default' => 'Your Company. All rights reserved.' ),
			),
		),
		'social'     => array(
			'title'  => __( 'Social Links', 'astitva' ),
			'fields' => array(
				'social_facebook'  => array( 'label' => 'Facebook', 'type' => 'url', 'default' => '' ),
				'social_x'         => array( 'label' => 'X', 'type' => 'url', 'default' => '' ),
				'social_instagram' => array( 'label' => 'Instagram', 'type' => 'url', 'default' => '' ),
				'social_linkedin'  => array( 'label' => 'LinkedIn', 'type' => 'url', 'default' => '' ),
				'social_youtube'   => array( 'label' => 'YouTube', 'type' => 'url', 'default' => '' ),
			),
		),
		'advanced'   => array(
			'title'  => __( 'Cookie, 404 and Code', 'astitva' ),
			'fields' => array(
				'cookie_enable' => array( 'label' => __( 'Show cookie banner', 'astitva' ), 'type' => 'checkbox', 'default' => 1 ),
				'cookie_text'   => array( 'label' => __( 'Cookie banner text', 'astitva' ), 'type' => 'text', 'default' => 'By using this website, you agree to our' ),
				'cookie_link'   => array( 'label' => __( 'Cookie link label', 'astitva' ), 'type' => 'text', 'default' => 'cookie policy' ),
				'cookie_page'   => array( 'label' => __( 'Cookie policy page', 'astitva' ), 'type' => 'page', 'default' => 0 ),
				'page_404'      => array( 'label' => __( '404 page', 'astitva' ), 'type' => 'page', 'default' => 0 ),
				'custom_css'    => array( 'label' => __( 'Custom CSS', 'astitva' ), 'type' => 'code', 'default' => '' ),
				'custom_js'     => array( 'label' => __( 'Custom JS', 'astitva' ), 'type' => 'code', 'default' => '' ),
			),
		),
	);
}

/**
 * Flat map of field key to definition.
 *
 * @return array
 */
function agi_flat_fields() {
	static $flat = null;
	if ( null === $flat ) {
		$flat = array();
		foreach ( agi_fields() as $group ) {
			$flat += $group['fields'];
		}
	}
	return $flat;
}

/**
 * Read one option with its default.
 *
 * @param string $key Field key.
 * @return mixed
 */
function agi_option( $key ) {
	$saved  = get_option( 'agi_options', array() );
	$fields = agi_flat_fields();
	if ( isset( $saved[ $key ] ) ) {
		return $saved[ $key ];
	}
	return $fields[ $key ]['default'] ?? '';
}

/**
 * Sanitize the whole options array by field type.
 *
 * @param array $input Raw values.
 * @return array
 */
function agi_sanitize( $input ) {
	$clean = array();
	$input = is_array( $input ) ? $input : array();
	foreach ( agi_flat_fields() as $key => $field ) {
		$value = $input[ $key ] ?? '';
		switch ( $field['type'] ) {
			case 'color':
				$clean[ $key ] = sanitize_hex_color( $value ) ?: $field['default'];
				break;
			case 'number':
				$clean[ $key ] = max( 8, min( 200, (float) $value ) );
				break;
			case 'select':
				$clean[ $key ] = isset( $field['choices'][ $value ] ) ? $value : $field['default'];
				break;
			case 'checkbox':
				$clean[ $key ] = empty( $value ) ? 0 : 1;
				break;
			case 'url':
				$clean[ $key ] = esc_url_raw( $value );
				break;
			case 'image':
			case 'page':
			case 'template':
				$clean[ $key ] = absint( $value );
				break;
			case 'textarea':
				$clean[ $key ] = sanitize_textarea_field( $value );
				break;
			case 'code':
				if ( 'custom_js' === $key ) {
					$clean[ $key ] = current_user_can( 'unfiltered_html' ) ? (string) $value : agi_option( $key );
				} else {
					$clean[ $key ] = wp_strip_all_tags( (string) $value );
				}
				break;
			default:
				$clean[ $key ] = sanitize_text_field( $value );
		}
	}
	return $clean;
}

/**
 * Register menu and setting.
 */
function agi_admin_menu() {
	add_theme_page( __( 'Theme Options', 'astitva' ), __( 'Theme Options', 'astitva' ), 'edit_theme_options', 'agi-options', 'agi_render_options' );
}
add_action( 'admin_menu', 'agi_admin_menu' );

/**
 * Register the options setting.
 */
function agi_register_setting() {
	register_setting( 'agi_options_group', 'agi_options', array( 'sanitize_callback' => 'agi_sanitize', 'type' => 'array' ) );
}
add_action( 'admin_init', 'agi_register_setting' );

/**
 * Admin assets for the options screen.
 *
 * @param string $hook Screen hook.
 */
function agi_admin_assets( $hook ) {
	if ( 'appearance_page_agi-options' !== $hook ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'agi-admin', get_template_directory_uri() . '/assets/js/admin.js', array( 'jquery', 'wp-color-picker' ), AGI_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'agi_admin_assets' );

/**
 * Render one field.
 *
 * @param string $key   Field key.
 * @param array  $field Definition.
 */
function agi_render_field( $key, $field ) {
	$name  = 'agi_options[' . $key . ']';
	$value = agi_option( $key );
	switch ( $field['type'] ) {
		case 'select':
			echo '<select name="' . esc_attr( $name ) . '">';
			foreach ( $field['choices'] as $val => $label ) {
				echo '<option value="' . esc_attr( $val ) . '" ' . selected( $value, $val, false ) . '>' . esc_html( $label ) . '</option>';
			}
			echo '</select>';
			break;
		case 'checkbox':
			echo '<input type="checkbox" name="' . esc_attr( $name ) . '" value="1" ' . checked( $value, 1, false ) . '>';
			break;
		case 'color':
			echo '<input class="agi-color" type="text" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" data-default-color="' . esc_attr( $field['default'] ) . '">';
			break;
		case 'number':
			echo '<input class="small-text" type="number" step="0.1" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">';
			break;
		case 'textarea':
			echo '<textarea class="large-text" rows="3" name="' . esc_attr( $name ) . '">' . esc_textarea( $value ) . '</textarea>';
			break;
		case 'code':
			echo '<textarea class="large-text code" rows="10" name="' . esc_attr( $name ) . '">' . esc_textarea( $value ) . '</textarea>';
			break;
		case 'image':
			$src = $value ? wp_get_attachment_image_url( $value, 'medium' ) : '';
			echo '<div class="agi-media"><input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">';
			echo '<img alt="" src="' . esc_url( (string) $src ) . '" style="max-height:60px;display:' . ( $src ? 'block' : 'none' ) . ';margin-bottom:8px">';
			echo '<button type="button" class="button agi-media-pick">' . esc_html__( 'Choose image', 'astitva' ) . '</button> ';
			echo '<button type="button" class="button-link agi-media-clear">' . esc_html__( 'Remove', 'astitva' ) . '</button></div>';
			break;
		case 'page':
			wp_dropdown_pages(
				array(
					'name'              => esc_attr( $name ),
					'selected'          => absint( $value ),
					'show_option_none'  => esc_html__( 'None', 'astitva' ),
					'option_none_value' => '0',
				)
			);
			break;
		case 'template':
			$templates = get_posts( array( 'post_type' => 'elementor_library', 'posts_per_page' => 100, 'post_status' => 'publish' ) );
			echo '<select name="' . esc_attr( $name ) . '"><option value="0">' . esc_html__( 'Theme default', 'astitva' ) . '</option>';
			foreach ( $templates as $template ) {
				echo '<option value="' . esc_attr( $template->ID ) . '" ' . selected( $value, $template->ID, false ) . '>' . esc_html( $template->post_title ) . '</option>';
			}
			echo '</select>';
			break;
		default:
			echo '<input class="regular-text" type="' . ( 'url' === $field['type'] ? 'url' : 'text' ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">';
	}
}

/**
 * Options screen. settings_fields() adds the nonce; options.php checks it.
 */
function agi_render_options() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	echo '<div class="wrap"><h1>' . esc_html__( 'Theme Options', 'astitva' ) . '</h1><form method="post" action="options.php">';
	settings_fields( 'agi_options_group' );
	foreach ( agi_fields() as $group ) {
		echo '<h2 class="title">' . esc_html( $group['title'] ) . '</h2><table class="form-table" role="presentation">';
		foreach ( $group['fields'] as $key => $field ) {
			echo '<tr><th scope="row">' . esc_html( $field['label'] ) . '</th><td>';
			agi_render_field( $key, $field );
			echo '</td></tr>';
		}
		echo '</table>';
	}
	submit_button();
	echo '</form></div>';
}

/**
 * CSS variables from saved options, plus admin custom CSS.
 *
 * @return string
 */
function agi_dynamic_css() {
	$vars = array(
		'--color-primary'   => agi_option( 'color_primary' ),
		'--color-secondary' => agi_option( 'color_secondary' ),
		'--color-heading'   => agi_option( 'color_heading' ),
		'--color-body'      => agi_option( 'color_body' ),
		'--color-bg'        => agi_option( 'color_bg' ),
		'--color-border'    => agi_option( 'color_border' ),
		'--font-heading'    => '"' . agi_option( 'font_heading' ) . '", sans-serif',
		'--font-body'       => '"' . agi_option( 'font_body' ) . '", sans-serif',
	);
	$desktop = '';
	$mobile  = '';
	foreach ( array( 'h1' => .6, 'h2' => .6, 'h3' => .65, 'h4' => .85, 'h5' => 1, 'h6' => 1, 'body' => 1, 'button' => 1, 'nav' => 1 ) as $key => $scale ) {
		$size     = (float) agi_option( 'size_' . $key );
		$desktop .= '--' . $key . '-size:' . $size . 'px;--' . $key . '-weight:' . (int) agi_option( 'weight_' . $key ) . ';';
		if ( 1.0 !== (float) $scale ) {
			$mobile .= '--' . $key . '-size:' . round( $size * $scale, 2 ) . 'px;';
		}
	}
	$css = ':root{';
	foreach ( $vars as $name => $value ) {
		$css .= $name . ':' . $value . ';';
	}
	$css .= $desktop . '}@media (max-width:1024px){:root{' . $mobile . '}}';
	return $css . agi_option( 'custom_css' );
}
