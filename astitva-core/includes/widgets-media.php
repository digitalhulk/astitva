<?php
/**
 * Image, video and card widgets, plus the widget class list.
 *
 * @package Astitva_Core
 */

namespace Astitva;

use Elementor\Controls_Manager as C;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Image extends Base {
	protected $slug = 'img'; protected $label = 'AI Image'; protected $eicon = 'eicon-image';
	protected function content_controls() {
		$this->media();
		$this->text( 'alt', 'Alt text', '' );
		$this->text( 'label', 'Corner label', '' );
		$this->link();
		$this->choice( 'hover', 'Hover', array( 'none' => 'None', 'zoom' => 'Zoom' ), 'zoom' );
		$this->choice( 'lightbox', 'Open in lightbox', array( 'no' => 'No', 'yes' => 'Yes' ), 'no' );
	}
	protected function view( $s ) {
		$box = ! empty( $s['image']['url'] ) && 'yes' === $s['lightbox'];
		$tag = $box || ! empty( $s['link']['url'] ) ? 'a' : 'div';
		$att = $box ? 'href="' . esc_url( $s['image']['url'] ) . '" data-agi-lightbox' : ( 'a' === $tag ? $this->link_attrs( $s['link'] ) : '' );
		echo '<' . $tag . ' class="agi-root agi-img agi-img--' . esc_attr( $s['hover'] ) . '" ' . $att . '>'; // phpcs:ignore
		$this->img( $s['image'], $s['alt'] );
		if ( $s['label'] ) {
			echo '<span class="agi-img__label">' . esc_html( $s['label'] ) . '</span>';
		}
		echo '</' . $tag . '>'; // phpcs:ignore
	}
}

class Card extends Base {
	protected $slug = 'card'; protected $label = 'AI Card'; protected $eicon = 'eicon-info-box';
	protected function content_controls() {
		$this->media();
		$this->text( 'title', 'Title', 'Card title' );
		$this->text( 'text', 'Text', 'Short supporting text.', C::TEXTAREA );
		$this->text( 'badge', 'Badge', '' );
		$this->text( 'button', 'Button text', '' );
		$this->link();
	}
	protected function view( $s ) {
		echo '<div class="agi-root agi-card"><div class="agi-card__head"><h4>' . esc_html( $s['title'] ) . '</h4>';
		if ( $s['badge'] ) {
			echo '<span class="agi-badge agi-badge--tint">' . esc_html( $s['badge'] ) . '</span>';
		}
		echo '</div>';
		if ( ! empty( $s['image']['url'] ) ) {
			echo '<div class="agi-card__media">';
			$this->img( $s['image'], $s['title'] );
			echo '</div>';
		}
		echo '<p>' . esc_html( $s['text'] ) . '</p>';
		if ( $s['button'] ) {
			echo '<a class="btn btn--tint btn--sm" ' . $this->link_attrs( $s['link'] ) . '>' . esc_html( $s['button'] ) . '</a>'; // phpcs:ignore
		}
		echo '</div>';
	}
}

class Photo_Box extends Base {
	protected $slug = 'photo-box'; protected $label = 'AI Photo Box'; protected $eicon = 'eicon-image-box';
	protected function content_controls() {
		$this->media();
		$this->text( 'title', 'Name or title', 'Team member' );
		$this->text( 'subtitle', 'Role or subtitle', '' );
		$this->link();
	}
	protected function view( $s ) {
		echo '<a class="agi-root agi-photo-box" ' . $this->link_attrs( $s['link'] ) . '><span class="agi-photo-box__img">'; // phpcs:ignore
		$this->img( $s['image'], $s['title'] );
		echo '</span><strong>' . esc_html( $s['title'] ) . '</strong>';
		if ( $s['subtitle'] ) {
			echo '<small>' . esc_html( $s['subtitle'] ) . '</small>';
		}
		echo '</a>';
	}
}

class Photo_Stack extends Base {
	protected $slug = 'photo-stack'; protected $label = 'AI Photo Stack'; protected $eicon = 'eicon-gallery-grid';
	protected function content_controls() {
		$this->media( 'front', 'Front image' );
		$this->media( 'back', 'Back image' );
	}
	protected function view( $s ) {
		echo '<div class="agi-root agi-stack">';
		$this->img( $s['back'], '', 'agi-stack__back' );
		$this->img( $s['front'], '', 'agi-stack__front' );
		echo '</div>';
	}
}

class Circles extends Base {
	protected $slug = 'circles'; protected $label = 'AI Avatar Circles'; protected $eicon = 'eicon-person';
	protected function content_controls() {
		$r = new Repeater();
		$r->add_control( 'image', array( 'label' => 'Image', 'type' => C::MEDIA ) );
		$this->add_control( 'items', array( 'label' => 'Avatars', 'type' => C::REPEATER, 'fields' => $r->get_controls(), 'default' => array( array(), array(), array() ) ) );
	}
	protected function view( $s ) {
		echo '<div class="agi-root agi-circles">';
		foreach ( (array) $s['items'] as $item ) {
			echo '<span>';
			$this->img( $item['image'] );
			echo '</span>';
		}
		echo '</div>';
	}
}

class Clients extends Base {
	protected $slug = 'clients'; protected $label = 'AI Client Logos'; protected $eicon = 'eicon-logo';
	protected function content_controls() {
		$r = new Repeater();
		$r->add_control( 'image', array( 'label' => 'Logo', 'type' => C::MEDIA ) );
		$r->add_control( 'name', array( 'label' => 'Name', 'type' => C::TEXT, 'default' => 'Client' ) );
		$r->add_control( 'link', array( 'label' => 'Link', 'type' => C::URL ) );
		$this->add_control( 'items', array( 'label' => 'Logos', 'type' => C::REPEATER, 'fields' => $r->get_controls(), 'title_field' => '{{{ name }}}', 'default' => array( array( 'name' => 'Client' ) ) ) );
	}
	protected function view( $s ) {
		echo '<div class="agi-root agi-clients">';
		foreach ( (array) $s['items'] as $item ) {
			echo '<a ' . $this->link_attrs( $item['link'] ) . ' aria-label="' . esc_attr( $item['name'] ) . '">'; // phpcs:ignore
			if ( ! empty( $item['image']['url'] ) ) {
				$this->img( $item['image'], $item['name'] );
			} else {
				echo esc_html( $item['name'] );
			}
			echo '</a>';
		}
		echo '</div>';
	}
}

class Gallery extends Base {
	protected $slug = 'gallery'; protected $label = 'AI Gallery'; protected $eicon = 'eicon-gallery-grid';
	protected function content_controls() {
		$r = new Repeater();
		$r->add_control( 'image', array( 'label' => 'Image', 'type' => C::MEDIA ) );
		$r->add_control( 'title', array( 'label' => 'Title', 'type' => C::TEXT ) );
		$this->add_control( 'items', array( 'label' => 'Images', 'type' => C::REPEATER, 'fields' => $r->get_controls(), 'title_field' => '{{{ title }}}', 'default' => array( array( 'title' => 'One' ) ) ) );
		$this->add_responsive_control( 'cols', array( 'label' => 'Columns', 'type' => C::NUMBER, 'default' => 3, 'tablet_default' => 2, 'mobile_default' => 1, 'selectors' => array( '{{WRAPPER}} .agi-gallery' => 'grid-template-columns: repeat({{VALUE}}, 1fr);' ) ) );
	}
	protected function view( $s ) {
		echo '<div class="agi-root agi-gallery">';
		foreach ( (array) $s['items'] as $item ) {
			$url = $item['image']['url'] ?? '';
			echo '<a class="agi-img agi-img--zoom" href="' . esc_url( $url ) . '" data-agi-lightbox aria-label="' . esc_attr( $item['title'] ) . '">';
			$this->img( $item['image'], $item['title'] );
			echo '</a>';
		}
		echo '</div>';
	}
}

class Img_Carousel extends Base {
	protected $slug = 'img-carousel'; protected $label = 'AI Image Carousel'; protected $eicon = 'eicon-slider-album';
	protected function content_controls() {
		$r = new Repeater();
		$r->add_control( 'image', array( 'label' => 'Image', 'type' => C::MEDIA ) );
		$this->add_control( 'items', array( 'label' => 'Images', 'type' => C::REPEATER, 'fields' => $r->get_controls(), 'default' => array( array() ) ) );
		$this->number( 'autoplay', 'Autoplay (ms, 0 = off)', 4000 );
	}
	protected function view( $s ) {
		echo '<div class="agi-root agi-carousel" data-agi-carousel data-autoplay="' . absint( $s['autoplay'] ) . '"><div class="agi-carousel__track">';
		foreach ( (array) $s['items'] as $item ) {
			echo '<div class="agi-carousel__slide">';
			$this->img( $item['image'] );
			echo '</div>';
		}
		echo '</div><div class="agi-carousel__dots"></div></div>';
	}
}

class Video extends Base {
	protected $slug = 'video'; protected $label = 'AI Video'; protected $eicon = 'eicon-play';
	protected function content_controls() {
		$this->text( 'url', 'Video file URL', '' );
		$this->media( 'poster', 'Poster image' );
		$this->choice( 'background', 'Background loop', array( 'yes' => 'Yes: autoplay, muted, loop', 'no' => 'No: show controls' ), 'yes' );
	}
	protected function view( $s ) {
		$poster = esc_url( $s['poster']['url'] ?? '' );
		$flags  = 'yes' === $s['background'] ? 'autoplay muted loop playsinline' : 'controls playsinline preload="none"';
		echo '<div class="agi-root agi-video"><video src="' . esc_url( $s['url'] ) . '" poster="' . $poster . '" ' . $flags . '></video></div>'; // phpcs:ignore
	}
}

class Video_Popup extends Base {
	protected $slug = 'video-popup'; protected $label = 'AI Video Popup'; protected $eicon = 'eicon-youtube';
	protected function content_controls() {
		$this->text( 'url', 'Video URL (MP4 or YouTube embed)', '' );
		$this->text( 'label', 'Accessible label', 'Play video' );
	}
	protected function view( $s ) {
		echo '<button type="button" class="agi-root agi-play" data-agi-video="' . esc_url( $s['url'] ) . '" aria-label="' . esc_attr( $s['label'] ) . '"><svg viewBox="0 0 24 24" width="28" height="28" aria-hidden="true"><path d="M7 4.500v15a1 1 0 0 0 1.500.9l12-7.500a1 1 0 0 0 0-1.800l-12-7.500A1 1 0 0 0 7 4.500Z" fill="currentColor"/></svg></button>';
	}
}

/**
 * All widget classes, used for registration.
 *
 * @return array
 */
function agic_widget_classes_list() {
	return array( Button::class, Text::class, Heading::class, Sliding_Text::class, Highlighted_Text::class, Badge::class, Icon::class, Feature::class, Feature_List::class, Numbers::class, Levels::class, Marquee::class, Social_Icons::class, Blog_Slider::class, Image::class, Card::class, Photo_Box::class, Photo_Stack::class, Circles::class, Clients::class, Gallery::class, Img_Carousel::class, Video::class, Video_Popup::class );
}
