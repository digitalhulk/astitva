<?php
/**
 * Shared widget base: Content + Style + Advanced tabs, responsive style
 * controls, entrance animation and per-widget Custom CSS.
 *
 * @package Astitva_Core
 */

namespace Astitva;

use Elementor\Controls_Manager as C;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

/**
 * Base class for every Astitva widget.
 */
abstract class Base extends Widget_Base {

	/** Widget slug without prefix. @var string */
	protected $slug = '';
	/** Editor title. @var string */
	protected $label = '';
	/** Editor icon. @var string */
	protected $eicon = 'eicon-star';

	public function get_name() {
		return 'agi-' . $this->slug;
	}

	public function get_title() {
		return $this->label;
	}

	public function get_icon() {
		return $this->eicon;
	}

	public function get_categories() {
		return array( 'astitva' );
	}

	public function get_style_depends() {
		return array( 'agic-widgets' );
	}

	public function get_script_depends() {
		return array( 'agic-widgets' );
	}

	/** Content tab fields. */
	abstract protected function content_controls();

	/** Markup. @param array $s Display settings. */
	abstract protected function view( $s );

	protected function register_controls() {
		$this->start_controls_section( 'content_section', array( 'label' => __( 'Content', 'astitva-core' ) ) );
		$this->content_controls();
		$this->end_controls_section();
		$this->style_controls();
		$this->advanced_controls();
	}

	/** Text color, typography, spacing, background, border and shadow; responsive. */
	protected function style_controls() {
		$sel = '{{WRAPPER}} .agi-root';
		$this->start_controls_section( 'style_section', array( 'label' => __( 'Style', 'astitva-core' ), 'tab' => C::TAB_STYLE ) );
		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'astitva-core' ),
				'type'      => C::CHOOSE,
				'options'   => array(
					'left'   => array( 'title' => 'Left', 'icon' => 'eicon-text-align-left' ),
					'center' => array( 'title' => 'Center', 'icon' => 'eicon-text-align-center' ),
					'right'  => array( 'title' => 'Right', 'icon' => 'eicon-text-align-right' ),
				),
				'selectors' => array( '{{WRAPPER}}' => 'text-align: {{VALUE}};' ),
			)
		);
		$this->add_control( 'text_color', array( 'label' => __( 'Text color', 'astitva-core' ), 'type' => C::COLOR, 'selectors' => array( $sel => 'color: {{VALUE}};', "$sel :is(h1,h2,h3,h4,h5,h6,a)" => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'typo', 'selector' => "$sel, $sel :is(h1,h2,h3,h4,h5,h6,p)" ) );
		$this->add_responsive_control( 'pad', array( 'label' => __( 'Padding', 'astitva-core' ), 'type' => C::DIMENSIONS, 'size_units' => array( 'px', 'em', '%' ), 'selectors' => array( $sel => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'rad', array( 'label' => __( 'Border radius', 'astitva-core' ), 'type' => C::DIMENSIONS, 'size_units' => array( 'px', '%' ), 'selectors' => array( $sel => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->add_group_control( Group_Control_Background::get_type(), array( 'name' => 'bg', 'types' => array( 'classic', 'gradient' ), 'selector' => $sel ) );
		$this->add_group_control( Group_Control_Border::get_type(), array( 'name' => 'bd', 'selector' => $sel ) );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), array( 'name' => 'sh', 'selector' => $sel ) );
		$this->end_controls_section();
	}

	/** Entrance animation and Custom CSS, shown on the Advanced tab. */
	protected function advanced_controls() {
		$this->start_controls_section( 'agi_motion', array( 'label' => __( 'Entrance Animation', 'astitva-core' ), 'tab' => C::TAB_ADVANCED ) );
		$this->add_control(
			'agi_anim',
			array(
				'label'   => __( 'Animation', 'astitva-core' ),
				'type'    => C::SELECT,
				'default' => '',
				'options' => array( '' => 'None', 'fade-in' => 'Fade in', 'fade-in-up' => 'Fade in up', 'fade-in-right' => 'Slide from right', 'zoom-in' => 'Zoom in' ),
			)
		);
		$this->add_control( 'agi_delay', array( 'label' => __( 'Delay (ms)', 'astitva-core' ), 'type' => C::NUMBER, 'default' => 0, 'min' => 0, 'step' => 50 ) );
		$this->end_controls_section();

		$this->start_controls_section( 'agi_css', array( 'label' => __( 'Custom CSS', 'astitva-core' ), 'tab' => C::TAB_ADVANCED ) );
		$this->add_control(
			'agi_custom_css',
			array(
				'label'       => __( 'CSS', 'astitva-core' ),
				'type'        => C::CODE,
				'language'    => 'css',
				'rows'        => 12,
				'description' => __( 'Use "selector" to target this widget, for example: selector { opacity: .9; }', 'astitva-core' ),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		if ( ! empty( $s['agi_anim'] ) ) {
			$this->add_render_attribute( '_wrapper', array( 'data-agi-anim' => esc_attr( $s['agi_anim'] ), 'data-agi-delay' => absint( $s['agi_delay'] ) ) );
		}
		if ( ! empty( $s['agi_custom_css'] ) ) {
			$css = str_replace( 'selector', '.elementor-element-' . $this->get_id(), wp_strip_all_tags( $s['agi_custom_css'] ) );
			echo '<style>' . $css . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		$this->view( $s );
	}

	/* Render helpers */

	protected function icon( $icon, $attrs = array( 'aria-hidden' => 'true' ) ) {
		if ( ! empty( $icon['value'] ) ) {
			Icons_Manager::render_icon( $icon, $attrs );
		}
	}

	protected function link_attrs( $link ) {
		$url = is_array( $link ) ? ( $link['url'] ?? '' ) : '';
		if ( '' === $url ) {
			return 'href="#"';
		}
		$out = 'href="' . esc_url( $url ) . '"';
		if ( ! empty( $link['is_external'] ) ) {
			$out .= ' target="_blank" rel="noopener"';
		}
		return $out;
	}

	protected function img( $media, $alt = '', $class = '' ) {
		$url = is_array( $media ) ? ( $media['url'] ?? '' ) : '';
		if ( ! $url ) {
			return;
		}
		$id = absint( $media['id'] ?? 0 );
		if ( $id ) {
			echo wp_get_attachment_image( $id, 'large', false, array( 'class' => $class, 'alt' => $alt, 'loading' => 'lazy' ) );
			return;
		}
		echo '<img class="' . esc_attr( $class ) . '" src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy">';
	}

	/* Control helpers */

	protected function text( $id, $label, $default = '', $type = C::TEXT ) {
		$this->add_control( $id, array( 'label' => $label, 'type' => $type, 'default' => $default, 'dynamic' => array( 'active' => true ) ) );
	}

	protected function link( $id = 'link', $label = 'Link' ) {
		$this->add_control( $id, array( 'label' => $label, 'type' => C::URL, 'dynamic' => array( 'active' => true ) ) );
	}

	protected function media( $id = 'image', $label = 'Image' ) {
		$this->add_control( $id, array( 'label' => $label, 'type' => C::MEDIA, 'dynamic' => array( 'active' => true ) ) );
	}

	protected function icons( $id = 'icon', $label = 'Icon', $default = 'fas fa-star' ) {
		$this->add_control( $id, array( 'label' => $label, 'type' => C::ICONS, 'default' => array( 'value' => $default, 'library' => 'fa-solid' ) ) );
	}

	protected function choice( $id, $label, $options, $default ) {
		$this->add_control( $id, array( 'label' => $label, 'type' => C::SELECT, 'options' => $options, 'default' => $default ) );
	}

	protected function number( $id, $label, $default ) {
		$this->add_control( $id, array( 'label' => $label, 'type' => C::NUMBER, 'default' => $default ) );
	}
}
