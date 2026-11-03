<?php
/**
 * Text and interface widgets.
 *
 * @package Astitva_Core
 */

namespace Astitva;

use Elementor\Controls_Manager as C;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Button extends Base {
	protected $slug = 'button'; protected $label = 'AI Button'; protected $eicon = 'eicon-button';
	protected function content_controls() {
		$this->text( 'text', 'Text', 'Get started' );
		$this->link();
		$this->icons( 'icon', 'Icon', 'fas fa-arrow-right' );
		$this->choice( 'variant', 'Variant', array( 'primary' => 'Primary', 'black' => 'Black', 'white' => 'White', 'tint' => 'Tint', 'glass' => 'Glass', 'link' => 'Link' ), 'primary' );
		$this->choice( 'size', 'Size', array( 'lg' => 'Large', 'md' => 'Medium', 'sm' => 'Small' ), 'md' );
	}
	protected function view( $s ) {
		echo '<a class="agi-root btn btn--' . esc_attr( $s['variant'] ) . ' btn--' . esc_attr( $s['size'] ) . '" ' . $this->link_attrs( $s['link'] ) . '>' . esc_html( $s['text'] ) . ' '; // phpcs:ignore
		$this->icon( $s['icon'] );
		echo '</a>';
	}
}

class Text extends Base {
	protected $slug = 'text'; protected $label = 'AI Text'; protected $eicon = 'eicon-text';
	protected function content_controls() {
		$this->text( 'text', 'Text', 'Add your text here.', C::WYSIWYG );
	}
	protected function view( $s ) {
		echo '<div class="agi-root agi-text">' . wp_kses_post( $s['text'] ) . '</div>';
	}
}

class Heading extends Base {
	protected $slug = 'heading'; protected $label = 'AI Heading'; protected $eicon = 'eicon-t-letter';
	protected function content_controls() {
		$this->text( 'title', 'Title', 'Add your heading', C::TEXTAREA );
		$this->choice( 'tag', 'HTML tag', array_combine( array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ), array( 'H1', 'H2', 'H3', 'H4', 'H5', 'H6' ) ), 'h2' );
	}
	protected function view( $s ) {
		$tag = in_array( $s['tag'], array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ? $s['tag'] : 'h2';
		echo '<' . $tag . ' class="agi-root agi-heading">' . wp_kses_post( nl2br( $s['title'] ) ) . '</' . $tag . '>'; // phpcs:ignore
	}
}

class Sliding_Text extends Base {
	protected $slug = 'sliding-text'; protected $label = 'AI Sliding Heading'; protected $eicon = 'eicon-animation-text';
	protected function content_controls() {
		$this->text( 'title', 'Title', 'Words slide up one by one', C::TEXTAREA );
		$this->choice( 'tag', 'HTML tag', array( 'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3' ), 'h2' );
		$this->number( 'stagger', 'Delay between words (ms)', 60 );
	}
	protected function view( $s ) {
		$tag   = in_array( $s['tag'], array( 'h1', 'h2', 'h3' ), true ) ? $s['tag'] : 'h2';
		$lines = preg_split( '/\r?\n/', (string) $s['title'] );
		$index = 0;
		echo '<' . $tag . ' class="agi-root agi-sliding" aria-label="' . esc_attr( str_replace( array( "\r", "\n" ), ' ', $s['title'] ) ) . '">'; // phpcs:ignore
		foreach ( $lines as $line_no => $line ) {
			foreach ( preg_split( '/\s+/', trim( $line ) ) as $word ) {
				echo '<span class="agi-word" aria-hidden="true"><span style="transition-delay:' . absint( $index++ * (int) $s['stagger'] ) . 'ms">' . esc_html( $word ) . '</span></span> ';
			}
			if ( $line_no < count( $lines ) - 1 ) {
				echo '<br>';
			}
		}
		echo '</' . $tag . '>'; // phpcs:ignore
	}
}

class Highlighted_Text extends Base {
	protected $slug = 'highlighted-text'; protected $label = 'AI Highlighted Text'; protected $eicon = 'eicon-t-letter-bold';
	protected function content_controls() {
		$this->text( 'before', 'Before', 'We reply within ' );
		$this->text( 'mark', 'Highlight', '1 business day' );
		$this->text( 'after', 'After', '' );
	}
	protected function view( $s ) {
		echo '<p class="agi-root agi-highlight">' . esc_html( $s['before'] ) . '<strong>' . esc_html( $s['mark'] ) . '</strong>' . esc_html( $s['after'] ) . '</p>';
	}
}

class Badge extends Base {
	protected $slug = 'badge'; protected $label = 'AI Badge'; protected $eicon = 'eicon-tags';
	protected function content_controls() {
		$this->text( 'text', 'Text', 'New' );
		$this->icons( 'icon', 'Icon', 'fas fa-check-circle' );
		$this->choice( 'variant', 'Variant', array( 'plain' => 'Plain', 'tint' => 'Tint', 'gray' => 'Gray', 'dark' => 'Dark' ), 'plain' );
	}
	protected function view( $s ) {
		echo '<span class="agi-root agi-badge agi-badge--' . esc_attr( $s['variant'] ) . '">';
		$this->icon( $s['icon'] );
		echo ' ' . esc_html( $s['text'] ) . '</span>';
	}
}

class Icon extends Base {
	protected $slug = 'icon'; protected $label = 'AI Icon'; protected $eicon = 'eicon-favorite';
	protected function content_controls() {
		$this->icons();
		$this->link();
		$this->choice( 'shape', 'Shape', array( 'tile' => 'Gray tile', 'circle' => 'Blue circle', 'none' => 'None' ), 'tile' );
	}
	protected function view( $s ) {
		echo '<a class="agi-root agi-icon-box agi-icon-box--' . esc_attr( $s['shape'] ) . '" ' . $this->link_attrs( $s['link'] ) . ' aria-label="' . esc_attr( $s['icon']['value'] ?? '' ) . '">'; // phpcs:ignore
		$this->icon( $s['icon'] );
		echo '</a>';
	}
}

class Feature extends Base {
	protected $slug = 'feature'; protected $label = 'AI Feature'; protected $eicon = 'eicon-icon-box';
	protected function content_controls() {
		$this->icons();
		$this->media( 'image', 'Image (replaces icon)' );
		$this->text( 'title', 'Title', 'Feature title' );
		$this->text( 'text', 'Text', 'Short description of this feature.', C::TEXTAREA );
		$this->choice( 'layout', 'Layout', array( 'top' => 'Icon on top', 'left' => 'Icon on left', 'center' => 'Centered' ), 'top' );
		$this->choice( 'title_tag', 'Title tag', array( 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5' ), 'h4' );
		$this->link();
	}
	protected function view( $s ) {
		$tag = in_array( $s['title_tag'], array( 'h3', 'h4', 'h5' ), true ) ? $s['title_tag'] : 'h4';
		echo '<div class="agi-root agi-feature agi-feature--' . esc_attr( $s['layout'] ) . '"><div class="agi-feature__icon">';
		if ( ! empty( $s['image']['url'] ) ) {
			$this->img( $s['image'], $s['title'] );
		} else {
			$this->icon( $s['icon'] );
		}
		echo '</div><div class="agi-feature__body"><' . $tag . '>'; // phpcs:ignore
		if ( ! empty( $s['link']['url'] ) ) {
			echo '<a ' . $this->link_attrs( $s['link'] ) . '>' . esc_html( $s['title'] ) . '</a>'; // phpcs:ignore
		} else {
			echo esc_html( $s['title'] );
		}
		echo '</' . $tag . '><p>' . esc_html( $s['text'] ) . '</p></div></div>'; // phpcs:ignore
	}
}

class Feature_List extends Base {
	protected $slug = 'feature-list'; protected $label = 'AI Feature List'; protected $eicon = 'eicon-bullet-list';
	protected function content_controls() {
		$r = new Repeater();
		$r->add_control( 'title', array( 'label' => 'Title', 'type' => C::TEXT, 'default' => 'Included item' ) );
		$r->add_control( 'text', array( 'label' => 'Text', 'type' => C::TEXT, 'default' => '' ) );
		$r->add_control( 'off', array( 'label' => 'Not included', 'type' => C::SWITCHER ) );
		$this->add_control( 'items', array( 'label' => 'Items', 'type' => C::REPEATER, 'fields' => $r->get_controls(), 'title_field' => '{{{ title }}}', 'default' => array( array( 'title' => 'Included item' ) ) ) );
	}
	protected function view( $s ) {
		echo '<ul class="agi-root agi-list">';
		foreach ( (array) $s['items'] as $item ) {
			$off = ! empty( $item['off'] );
			echo '<li class="' . ( $off ? 'is-off' : '' ) . '"><span class="agi-list__mark" aria-hidden="true">' . ( $off ? '&times;' : '&#10003;' ) . '</span><span><strong>' . esc_html( $item['title'] ) . '</strong>';
			if ( $item['text'] ) {
				echo '<small>' . esc_html( $item['text'] ) . '</small>';
			}
			echo '</span></li>';
		}
		echo '</ul>';
	}
}

class Numbers extends Base {
	protected $slug = 'numbers'; protected $label = 'AI Counter'; protected $eicon = 'eicon-counter';
	protected function content_controls() {
		$this->text( 'prefix', 'Prefix', '' );
		$this->number( 'to', 'Number', 25 );
		$this->text( 'suffix', 'Suffix', ',000+' );
		$this->number( 'duration', 'Duration (ms)', 1000 );
		$this->text( 'label', 'Label', '' );
		$this->choice( 'size', 'Size', array( 'xl' => '96px', 'lg' => '56px', 'md' => '36px' ), 'xl' );
	}
	protected function view( $s ) {
		echo '<div class="agi-root agi-numbers agi-numbers--' . esc_attr( $s['size'] ) . '"><div class="agi-numbers__value">' . esc_html( $s['prefix'] ) . '<span data-agi-count="' . esc_attr( (float) $s['to'] ) . '" data-duration="' . absint( $s['duration'] ) . '">' . esc_html( (float) $s['to'] ) . '</span>' . esc_html( $s['suffix'] ) . '</div>';
		if ( $s['label'] ) {
			echo '<div class="agi-numbers__label">' . esc_html( $s['label'] ) . '</div>';
		}
		echo '</div>';
	}
}

class Levels extends Base {
	protected $slug = 'levels'; protected $label = 'AI Level Steps'; protected $eicon = 'eicon-skill-bar';
	protected function content_controls() {
		$r = new Repeater();
		$r->add_control( 'title', array( 'label' => 'Step', 'type' => C::TEXT, 'default' => 'Step' ) );
		$this->add_control( 'items', array( 'label' => 'Steps', 'type' => C::REPEATER, 'fields' => $r->get_controls(), 'title_field' => '{{{ title }}}', 'default' => array( array( 'title' => 'Plan' ), array( 'title' => 'Build' ), array( 'title' => 'Launch' ) ) ) );
		$this->number( 'active', 'Completed steps', 2 );
	}
	protected function view( $s ) {
		$items = (array) $s['items'];
		$count = max( 1, count( $items ) );
		$fill  = max( 0, min( 100, ( ( (int) $s['active'] - 1 ) / max( 1, $count - 1 ) ) * 100 ) );
		echo '<div class="agi-root agi-levels" style="--steps:' . absint( $count ) . ';--fill-n:' . esc_attr( $fill ) . '" data-agi-levels>';
		foreach ( $items as $i => $item ) {
			echo '<div class="agi-levels__step' . ( $i < (int) $s['active'] ? ' is-done' : '' ) . '"><span>' . esc_html( ( $i + 1 ) . '. ' . $item['title'] ) . '</span><i></i></div>';
		}
		echo '<b class="agi-levels__line"></b></div>';
	}
}

class Marquee extends Base {
	protected $slug = 'marquee'; protected $label = 'AI Marquee'; protected $eicon = 'eicon-slider-push';
	protected function content_controls() {
		$r = new Repeater();
		$r->add_control( 'text', array( 'label' => 'Text', 'type' => C::TEXT, 'default' => 'Brand' ) );
		$r->add_control( 'image', array( 'label' => 'Image (optional)', 'type' => C::MEDIA ) );
		$this->add_control( 'items', array( 'label' => 'Items', 'type' => C::REPEATER, 'fields' => $r->get_controls(), 'title_field' => '{{{ text }}}', 'default' => array( array( 'text' => 'Brand One' ), array( 'text' => 'Brand Two' ), array( 'text' => 'Brand Three' ) ) ) );
		$this->number( 'speed', 'Seconds per loop', 30 );
		$this->choice( 'direction', 'Direction', array( 'normal' => 'Left', 'reverse' => 'Right' ), 'normal' );
	}
	protected function view( $s ) {
		echo '<div class="agi-root agi-marquee" style="--speed:' . absint( $s['speed'] ) . 's;--dir:' . esc_attr( $s['direction'] ) . '">';
		for ( $copy = 0; $copy < 2; $copy++ ) {
			echo '<div class="agi-marquee__track"' . ( $copy ? ' aria-hidden="true"' : '' ) . '>';
			for ( $repeat = 0; $repeat < 3; $repeat++ ) {
				foreach ( (array) $s['items'] as $item ) {
					echo '<span class="agi-marquee__item">';
					$this->img( $item['image'] ?? array(), $item['text'] );
					echo esc_html( $item['text'] ) . '</span>';
				}
			}
			echo '</div>';
		}
		echo '</div>';
	}
}

class Social_Icons extends Base {
	protected $slug = 'social-icons'; protected $label = 'AI Social Icons'; protected $eicon = 'eicon-social-icons';
	protected function content_controls() {
		$r = new Repeater();
		$r->add_control( 'icon', array( 'label' => 'Icon', 'type' => C::ICONS, 'default' => array( 'value' => 'fab fa-instagram', 'library' => 'fa-brands' ) ) );
		$r->add_control( 'link', array( 'label' => 'Link', 'type' => C::URL ) );
		$this->add_control( 'items', array( 'label' => 'Icons', 'type' => C::REPEATER, 'fields' => $r->get_controls(), 'default' => array( array( 'icon' => array( 'value' => 'fab fa-instagram', 'library' => 'fa-brands' ) ) ) ) );
		$this->choice( 'style', 'Style', array( 'plain' => 'Plain', 'tile' => 'Tiles' ), 'plain' );
	}
	protected function view( $s ) {
		echo '<div class="agi-root agi-social agi-social--' . esc_attr( $s['style'] ) . '">';
		foreach ( (array) $s['items'] as $item ) {
			echo '<a ' . $this->link_attrs( $item['link'] ) . ' aria-label="' . esc_attr( $item['icon']['value'] ?? 'social' ) . '">'; // phpcs:ignore
			$this->icon( $item['icon'] );
			echo '</a>';
		}
		echo '</div>';
	}
}

class Blog_Slider extends Base {
	protected $slug = 'blog-slider'; protected $label = 'AI Blog Slider'; protected $eicon = 'eicon-posts-carousel';
	protected function content_controls() {
		$this->number( 'count', 'Posts', 6 );
		$this->number( 'autoplay', 'Autoplay (ms, 0 = off)', 0 );
	}
	protected function view( $s ) {
		$query = new \WP_Query( array( 'post_type' => 'post', 'posts_per_page' => absint( $s['count'] ), 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
		echo '<div class="agi-root agi-carousel agi-carousel--cards" data-agi-carousel data-autoplay="' . absint( $s['autoplay'] ) . '"><div class="agi-carousel__track">';
		while ( $query->have_posts() ) {
			$query->the_post();
			echo '<div class="agi-carousel__slide">';
			if ( function_exists( 'agi_card' ) ) {
				agi_card();
			}
			echo '</div>';
		}
		wp_reset_postdata();
		echo '</div><div class="agi-carousel__dots"></div></div>';
	}
}
