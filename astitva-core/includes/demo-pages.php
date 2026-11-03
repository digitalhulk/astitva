<?php
/**
 * Demo layouts and placeholder copy. All text here is original sample copy
 * meant to be replaced with your own content.
 *
 * @package Astitva_Core
 */

defined( 'ABSPATH' ) || exit;

/** Random element id. */
function agic_id() {
	return substr( md5( uniqid( '', true ) ), 0, 7 );
}

/** Generic nested container. */
function agic_c( $classes, $children, $settings = array() ) {
	return array(
		'id'       => agic_id(),
		'elType'   => 'container',
		'isInner'  => true,
		'settings' => array_merge( array( 'content_width' => 'full', 'css_classes' => $classes ), $settings ),
		'elements' => array_values( array_filter( $children ) ),
	);
}

/** Boxed top-level section. */
function agic_sec( $children, $classes = '', $settings = array() ) {
	$el            = agic_c( 'agi-sec ' . $classes, $children, array_merge( array( 'content_width' => 'boxed' ), $settings ) );
	$el['isInner'] = false;
	return $el;
}

/** Full-width top-level container. */
function agic_top( $classes, $children, $settings = array() ) {
	$el            = agic_c( $classes, $children, $settings );
	$el['isInner'] = false;
	return $el;
}

/** Widget. */
function agic_w( $type, $settings = array(), $anim = '', $delay = 0 ) {
	if ( $anim ) {
		$settings['agi_anim']  = $anim;
		$settings['agi_delay'] = $delay;
	}
	$native = in_array( $type, array( 'google_maps', 'shortcode' ), true );
	return array( 'id' => agic_id(), 'elType' => 'widget', 'widgetType' => $native ? $type : 'agi-' . $type, 'settings' => $settings, 'elements' => array() );
}

/** Font Awesome solid icon value. */
function agic_i( $name ) {
	// Elementor bundles Font Awesome 5 names for these icons.
	$map  = array( 'circle-check' => 'check-circle', 'location-dot' => 'map-marker-alt', 'gears' => 'cogs', 'compass-drafting' => 'drafting-compass', '1' => 'dice-one', '2' => 'dice-two', '3' => 'dice-three' );
	$name = $map[ $name ] ?? $name;
	return array( 'value' => 'fas fa-' . $name, 'library' => 'fa-solid' );
}

/** Font Awesome brands icon value. */
function agic_b( $name ) {
	return array( 'value' => 'fab fa-' . $name, 'library' => 'fa-brands' );
}

/** No icon. */
function agic_no_icon() {
	return array( 'value' => '', 'library' => '' );
}

/** Internal link value. */
function agic_l( $path ) {
	return array( 'url' => home_url( '/' . $path ), 'is_external' => '', 'nofollow' => '' );
}

/** Button shortcut. */
function agic_btn( $text, $path, $variant = 'primary', $size = 'md', $icon = 'arrow-right' ) {
	return agic_w( 'button', array( 'text' => $text, 'link' => agic_l( $path ), 'variant' => $variant, 'size' => $size, 'icon' => agic_i( $icon ) ), 'fade-in-up', 100 );
}

/** Paragraph widget. */
function agic_p( $text, $anim = 'fade-in-up', $delay = 100 ) {
	return agic_w( 'text', array( 'text' => '<p>' . $text . '</p>' ), $anim, $delay );
}

/** Badge + animated heading + text block. */
function agic_head( $badge, $title, $text = '', $center = true, $tag = 'h2', $extra = array() ) {
	return agic_c(
		'agi-stack ' . ( $center ? 'agi-center agi-mw' : '' ),
		array_merge(
			array(
				$badge ? agic_w( 'badge', array( 'text' => $badge, 'icon' => agic_i( 'circle-check' ) ), 'fade-in' ) : null,
				agic_w( 'sliding-text', array( 'title' => $title, 'tag' => $tag ) ),
				$text ? agic_p( $text ) : null,
			),
			$extra
		)
	);
}

/** Three service cards with staggered entrance. */
function agic_cards() {
	$rows = array(
		array( 'Clear Reporting', 'dash.jpg', 'See what is working with reports your whole team can read.', '', 'Book a call' ),
		array( 'Connected Tools', 'chart.jpg', 'Bring your systems together so work moves without manual steps.', 'Popular', 'See services' ),
		array( 'Smart Automation', 'wave.jpg', 'Replace repetitive tasks with reliable workflows that save hours.', 'New', 'Learn more' ),
	);
	$out  = array();
	foreach ( $rows as $i => $r ) {
		$out[] = agic_w( 'card', array( 'title' => $r[0], 'image' => agic_media( $r[1] ), 'text' => $r[2], 'badge' => $r[3], 'button' => $r[4], 'link' => agic_l( 'services/' ) ), 'fade-in-up', 100 * $i );
	}
	return agic_c( 'agi-g3', $out );
}

/** Split row: copy on one side, image on the other. */
function agic_split( $number, $title, $text, $image, $label, $flip = false ) {
	$copy = agic_c(
		'agi-stack',
		array(
			$number ? agic_w( 'numbers', array( 'to' => $number, 'suffix' => ',000+', 'size' => 'lg', 'label' => 'Hours of client work' ), 'fade-in-up' ) : null,
			agic_w( 'sliding-text', array( 'title' => $title, 'tag' => 'h3' ) ),
			agic_p( $text ),
			agic_c( 'agi-inline', array( agic_btn( 'See our work', 'showcase/' ), agic_btn( 'Talk to us', 'contact/', 'black' ) ) ),
		)
	);
	$img  = agic_w( 'img', array( 'image' => agic_media( $image ), 'label' => $label ), 'fade-in-up', 100 );
	return agic_c( 'agi-g2', $flip ? array( $img, $copy ) : array( $copy, $img ) );
}

/** Testimonials as three boxes. */
function agic_quotes() {
	$rows = array(
		array( 'Client Name', 'Founder, Company One', 'person1.jpg', 'Replace this with a real client quote about the results you delivered.' ),
		array( 'Client Name', 'Marketing Lead, Company Two', 'person2.jpg', 'Replace this with a real client quote about working with your team.' ),
		array( 'Client Name', 'Operations, Company Three', 'person3.jpg', 'Replace this with a real client quote about your support and reliability.' ),
	);
	$out  = array();
	foreach ( $rows as $r ) {
		$out[] = agic_c(
			'agi-box agi-stack',
			array(
				agic_w( 'feature', array( 'image' => agic_media( $r[2] ), 'title' => $r[0], 'text' => $r[1], 'layout' => 'left', 'icon' => agic_no_icon() ) ),
				agic_p( $r[3], '' ),
				agic_w( 'text', array( 'text' => '<p style="color:#f5a623;letter-spacing:2px;font-size:18px" aria-label="Rated 5 out of 5">&#9733;&#9733;&#9733;&#9733;&#9733;</p>' ) ),
			)
		);
	}
	return agic_c( 'agi-g3', $out );
}

/** Closing call to action, shown above the footer. */
function agic_cta() {
	return agic_sec(
		array(
			agic_c(
				'agi-box agi-g2',
				array(
					agic_c( 'agi-stack', array( agic_w( 'badge', array( 'text' => 'Ready when you are', 'icon' => agic_i( 'bolt' ) ) ), agic_w( 'sliding-text', array( 'title' => "Let us build something\nyour customers will love", 'tag' => 'h3' ) ), agic_btn( 'Start your project', 'contact/', 'black' ) ) ),
					agic_w( 'img', array( 'image' => agic_media( 'dash.jpg' ), 'hover' => 'none' ) ),
				)
			),
		),
		'agi-sec--tight'
	);
}

/** Inner page hero on a soft gradient band. */
function agic_soft_hero( $badge, $title, $text, $button = 'Get in touch', $path = 'contact/' ) {
	return agic_top(
		'agi-hero--soft',
		array(
			agic_w( 'badge', array( 'text' => $badge, 'icon' => agic_i( 'bolt' ) ), 'fade-in' ),
			agic_w( 'sliding-text', array( 'title' => $title, 'tag' => 'h1' ) ),
			agic_p( $text ),
			$button ? agic_btn( $button, $path, 'black' ) : null,
		)
	);
}

/** Background-video hero for the home page. */
function agic_video_hero() {
	return agic_top(
		'agi-hero',
		array(
			agic_c(
				'agi-g2',
				array(
					agic_c(
						'agi-stack',
						array(
							agic_btn( 'Explore our services', 'services/', 'glass', 'sm' ),
							agic_w( 'sliding-text', array( 'title' => "Helping Ambitious\nBrands Grow", 'tag' => 'h1' ) ),
						)
					),
					agic_c(
						'agi-stack',
						array(
							agic_w( 'video-popup', array( 'url' => AGIC_URL . 'assets/img/hero.webm' ), 'zoom-in', 200 ),
							agic_p( 'We plan, design and build digital experiences that turn visitors into customers.', 'fade-in-up', 300 ),
							agic_btn( 'Start today', 'contact/', 'white' ),
						)
					),
				)
			),
		),
		array(
			'background_background'     => 'video',
			'background_video_link'     => AGIC_URL . 'assets/img/hero.webm',
			'background_play_on_mobile' => 'yes',
		)
	);
}

/** Marquee of placeholder brands. */
function agic_marquee( $reverse = false ) {
	return agic_w( 'marquee', array( 'items' => array_map( static fn( $t ) => array( 'text' => $t ), array( 'Northwind', 'Lumen', 'Atlas', 'Horizon', 'Summit', 'Vertex' ) ), 'speed' => 30, 'direction' => $reverse ? 'reverse' : 'normal' ) );
}

/** Pricing plans. */
function agic_plans() {
	$plans = array(
		array( 'Starter', 9, 'tint', array( 'Up to 1 user', 'Core features', 'Email support' ) ),
		array( 'Growth', 99, 'primary', array( 'Up to 5 users', 'All features', 'Priority support' ) ),
		array( 'Scale', 199, 'black', array( 'Up to 20 users', 'All features', 'Dedicated manager' ) ),
	);
	$out   = array();
	foreach ( $plans as $p ) {
		$out[] = agic_c(
			'agi-box agi-stack',
			array(
				agic_w( 'badge', array( 'text' => $p[0] . ' Plan', 'variant' => 'gray', 'icon' => agic_no_icon() ) ),
				agic_w( 'numbers', array( 'prefix' => '$', 'to' => $p[1], 'suffix' => '', 'size' => 'lg', 'label' => 'per month' ) ),
				agic_btn( 'Get started', 'contact/', $p[2] ),
				agic_w( 'feature-list', array( 'items' => array_map( static fn( $t ) => array( 'title' => $t, 'text' => '' ), $p[3] ) ) ),
			)
		);
	}
	return agic_c( 'agi-g3', $out );
}

/** Static FAQ. */
function agic_faq() {
	$rows = array(
		array( 'How long does a typical project take?', 'Most websites take four to eight weeks, depending on scope and how quickly content is ready.' ),
		array( 'Do you work with small businesses?', 'Yes. We scale our process to fit your team, whether you are launching or expanding.' ),
		array( 'Can I update the site myself?', 'Yes. Everything is built so your team can change text, images and pages without code.' ),
		array( 'What happens after launch?', 'We offer support plans for updates, security and ongoing improvements.' ),
		array( 'How do we get started?', 'Send us a message. We will set up a short call to understand what you need.' ),
	);
	$out  = array();
	foreach ( $rows as $i => $r ) {
		$out[] = agic_c( 'agi-box agi-box--gray', array( agic_w( 'feature', array( 'title' => $r[0], 'text' => $r[1], 'icon' => agic_no_icon() ), 'fade-in-up', 50 * $i ) ) );
	}
	return agic_c( 'agi-stack agi-mw', $out );
}

/** Three reasons row. */
function agic_reasons() {
	return agic_c(
		'agi-g3',
		array(
			agic_w( 'feature', array( 'icon' => agic_i( 'certificate' ), 'title' => 'Proven Expertise', 'text' => 'Years of hands-on work across industries, applied to your project.', 'layout' => 'center' ), 'fade-in-up', 0 ),
			agic_w( 'feature', array( 'icon' => agic_i( 'lightbulb' ), 'title' => 'Research Led', 'text' => 'Every decision starts with evidence about your customers.', 'layout' => 'center' ), 'fade-in-up', 100 ),
			agic_w( 'feature', array( 'icon' => agic_i( 'layer-group' ), 'title' => 'Solid Foundations', 'text' => 'Fast, secure builds that keep working as you grow.', 'layout' => 'center' ), 'fade-in-up', 200 ),
		)
	);
}

/** Heading beside four feature tiles. */
function agic_four() {
	$rows  = array(
		array( 'seedling', 'Established Process', 'A proven path from brief to launch.' ),
		array( 'cloud', 'Reliable Delivery', 'Clear milestones and no surprises.' ),
		array( 'gears', 'Automated Workflows', 'Less busywork for your team.' ),
		array( 'handshake', 'True Partnership', 'We stay with you after launch.' ),
	);
	$tiles = array();
	foreach ( $rows as $i => $r ) {
		$tiles[] = agic_c( 'agi-box agi-box--gray', array( agic_w( 'feature', array( 'icon' => agic_i( $r[0] ), 'title' => $r[1], 'text' => $r[2] ), 'fade-in-up', 50 * $i ) ) );
	}
	return agic_c(
		'agi-g2',
		array(
			agic_head( 'Measured Results', "Work That\nMoves the Numbers", 'Keep every project visible, on schedule and tied to a business goal.', false, 'h2', array( agic_btn( 'See what we do', 'services/', 'tint' ) ) ),
			agic_c( 'agi-g2', $tiles ),
		)
	);
}

/** Team grid plus hiring box. */
function agic_team() {
	$people = array( array( 'Creative Director', 'person1.jpg' ), array( 'Strategy Lead', 'person2.jpg' ), array( 'Marketing Lead', 'person3.jpg' ), array( 'Operations', 'person4.jpg' ), array( 'Developer', 'person5.jpg' ) );
	$out    = array();
	foreach ( $people as $i => $m ) {
		$out[] = agic_w( 'photo-box', array( 'image' => agic_media( $m[1] ), 'title' => 'Team Member', 'subtitle' => $m[0], 'link' => agic_l( 'team/' ) ), 'fade-in-up', 50 * $i );
	}
	$out[] = agic_c( 'agi-box agi-stack agi-center', array( agic_w( 'heading', array( 'title' => 'Join our team', 'tag' => 'h4' ) ), agic_p( 'We are always looking for talented people.', '' ), agic_btn( 'See open roles', 'careers/', 'primary', 'sm' ) ), array( 'flex_justify_content' => 'center' ) );
	return agic_c( 'agi-g3', $out );
}

/** Home page. */
function agic_page_home() {
	$tools = array_map( static fn( $n ) => array( 'icon' => agic_b( $n ), 'link' => array( 'url' => '' ) ), array( 'youtube', 'x-twitter', 'whatsapp', 'google', 'slack' ) );
	return array(
		agic_video_hero(),
		agic_sec( array( agic_reasons() ), 'agi-sec--tight' ),
		agic_sec( array( agic_head( 'Trusted Partners', "Tailored Work for\nGrowing Businesses", 'Strategy, design and technology under one roof, shaped around your goals.' ), agic_cards() ) ),
		agic_sec( array( agic_split( 25, 'Digital Work That Builds Lasting Growth', 'We look at the whole customer journey, then improve the parts that matter most.', 'device1.jpg', 'New release' ) ) ),
		agic_sec( array( agic_split( 40, 'Consulting That Delivers Real Outcomes', 'Practical advice from people who build, not just present.', 'device2.jpg', 'Trending', true ) ) ),
		agic_sec( array( agic_c( 'agi-box agi-stack agi-center', array( agic_head( 'Works With Your Stack', "Built Around the\nTools You Use", 'We connect your website to the platforms your team relies on every day.', true, 'h2', array( agic_btn( 'Start today', 'contact/', 'black' ) ) ), agic_w( 'social-icons', array( 'style' => 'tile', 'items' => $tools ) ) ) ) ) ),
		agic_sec( array( agic_four() ) ),
		agic_sec(
			array(
				agic_head( 'Strategic Edge', "Setting the Standard\nThrough Strategy", 'A selection of recent work and what it achieved for our clients.' ),
				agic_c(
					'agi-g2',
					array(
						agic_c( 'agi-stack', array( agic_w( 'badge', array( 'text' => 'Client Project', 'icon' => agic_i( 'briefcase' ) ) ), agic_w( 'heading', array( 'title' => 'A Better Way to Work Across Teams', 'tag' => 'h3' ) ), agic_p( 'A new platform that brought sales, service and operations together.' ), agic_btn( 'View project', 'project-page/', 'tint', 'sm' ) ) ),
						agic_w( 'img', array( 'image' => agic_media( 'device3.jpg' ), 'label' => 'Featured project' ), 'fade-in-up' ),
					)
				),
				agic_c( 'agi-g3', array( agic_w( 'img', array( 'image' => agic_media( 'device1.jpg' ) ), 'fade-in-up', 0 ), agic_w( 'img', array( 'image' => agic_media( 'device2.jpg' ) ), 'fade-in-up', 100 ), agic_w( 'img', array( 'image' => agic_media( 'wave.jpg' ) ), 'fade-in-up', 200 ) ) ),
				agic_c( 'agi-stack agi-center', array( agic_btn( 'See all projects', 'showcase/', 'black' ) ) ),
			)
		),
		agic_sec( array( agic_head( 'What Clients Say', "A Record of Excellent\nClient Work", 'We measure success by the results our clients see.', false ), agic_quotes() ) ),
		agic_sec(
			array(
				agic_c( 'agi-g2', array( agic_c( 'agi-stack', array( agic_w( 'numbers', array( 'to' => 25, 'suffix' => ',000+', 'size' => 'xl' ) ), agic_w( 'sliding-text', array( 'title' => "Grow Sustainably\nWith a Clear Plan", 'tag' => 'h2' ) ), agic_btn( 'Get started', 'contact/' ) ) ), agic_w( 'img', array( 'image' => agic_media( 'chart.jpg' ), 'hover' => 'none' ), 'fade-in-up' ) ) ),
				agic_c( 'agi-g2', array( agic_c( 'agi-box agi-box--dark agi-stack', array( agic_w( 'heading', array( 'title' => 'Replace this with a short client quote.', 'tag' => 'h3' ) ) ) ), agic_c( 'agi-box agi-box--dark agi-stack', array( agic_w( 'badge', array( 'text' => 'Get Started Now', 'variant' => 'dark', 'icon' => agic_i( 'bolt' ) ) ), agic_w( 'heading', array( 'title' => 'Strategic Insight, Careful Execution', 'tag' => 'h3' ) ), agic_btn( 'Schedule a consultation', 'contact/' ) ) ) ) ),
			),
			'agi-sec--black'
		),
		agic_sec( array( agic_marquee(), agic_head( '', "Trusted by Teams at\nLeading Companies", 'Businesses of every size rely on our work.', true, 'h2', array( agic_btn( 'Start your journey', 'contact/' ) ) ) ) ),
		agic_sec( array( agic_c( 'agi-g2', array( agic_head( 'Daily News', 'Latest Updates', '', false ), agic_c( 'agi-stack', array( agic_p( 'Ideas and practical advice from our team.', '' ), agic_btn( 'Visit our blog', 'blog/', 'black' ) ) ) ) ), agic_w( 'blog-slider', array( 'count' => 5, 'autoplay' => 0 ) ) ) ),
		agic_cta(),
	);
}

/** About page. */
function agic_page_about() {
	$steps = array_map( static fn( $t ) => array( 'title' => $t ), array( 'Discover', 'Plan', 'Design', 'Build', 'Launch', 'Grow' ) );
	return array(
		agic_top( 'agi-hero agi-hero--dark agi-hero--inner', array( agic_c( 'agi-g2', array( agic_c( 'agi-stack', array( agic_w( 'sliding-text', array( 'title' => "Guiding Brands\nToward Growth", 'tag' => 'h1' ) ), agic_p( 'We are a team of strategists, designers and developers working as one.' ), agic_btn( 'Book a consultation', 'contact/', 'white' ) ) ), agic_w( 'img', array( 'image' => agic_media( 'device3.jpg' ), 'hover' => 'none' ), 'fade-in-up', 200 ) ) ) ) ),
		agic_sec( array( agic_head( '', 'We partner with ambitious leaders to simplify complex problems, open new opportunities and build work that lasts.', 'From our first client to today, our work has been shaped by one idea: make a measurable difference.', false, 'h3' ), agic_w( 'img', array( 'image' => agic_media( 'wave.jpg' ), 'hover' => 'none' ), 'fade-in-up' ) ) ),
		agic_sec( array( agic_c( 'agi-g2', array( agic_w( 'photo-stack', array( 'front' => agic_media( 'chart.jpg' ), 'back' => agic_media( 'dash.jpg' ) ), 'fade-in-up' ), agic_head( 'Our Approach', "Personal Advice for\nGrowing Companies", '', false, 'h2', array( agic_btn( 'Start today', 'contact/' ), agic_marquee() ) ) ) ) ) ),
		agic_sec( array( agic_head( 'Our People', 'Meet Our Team', 'Experienced people who care about your results.', true, 'h2', array( agic_btn( 'Client stories', 'reviews/', 'black' ) ) ), agic_team() ), 'agi-sec--gray' ),
		agic_sec( array( agic_c( 'agi-box agi-box--gray agi-stack agi-center', array( agic_w( 'levels', array( 'items' => $steps, 'active' => 4 ) ), agic_head( 'How We Work', 'Six Steps to Launch', 'A simple process that keeps everyone aligned.', true, 'h2', array( agic_btn( 'Start your project', 'contact/' ) ) ) ) ) ) ),
		agic_cta(),
	);
}

/** Services page. */
function agic_page_services() {
	$steps = array();
	foreach ( array( array( '1', 'Order Service' ), array( '2', 'Work in Progress' ), array( '3', 'Delivery' ), array( 'circle-check', 'Done' ) ) as $i => $r ) {
		$steps[] = agic_c( 'agi-box', array( agic_w( 'feature', array( 'icon' => agic_i( $r[0] ), 'title' => $r[1], 'text' => '', 'layout' => 'center' ), 'fade-in-up', 100 * $i ) ) );
	}
	$rows = array();
	foreach ( array( array( 'Brand and Web Design', 'Identity and websites that make a strong first impression.', 'device1.jpg', 'Featured project' ), array( 'Web Development', 'Fast, secure sites your team can update without code.', 'device2.jpg', 'Fast delivery' ), array( 'Growth Marketing', 'Search, content and ads focused on qualified leads.', 'device3.jpg', 'Fast delivery' ) ) as $r ) {
		$rows[] = agic_c( 'agi-box', array( agic_split( 0, $r[0], $r[1], $r[2], $r[3] ) ) );
	}
	return array(
		agic_soft_hero( 'What We Do', "Services Built to\nGrow Your Business", 'Strategy, design, development and marketing from one team.', 'Book a consultation' ),
		agic_sec( array( agic_cards() ), 'agi-sec--tight' ),
		agic_sec( array( agic_head( 'Our Process', 'Simple Steps', 'A clear path from first call to finished project.', true, 'h2', array( agic_btn( 'Order services', 'contact/' ) ) ), agic_c( 'agi-g4', $steps ) ) ),
		agic_sec( $rows ),
		agic_sec( array( agic_head( 'Client Feedback', "What Our\nClients Say", '' ), agic_quotes(), agic_marquee() ) ),
		agic_cta(),
	);
}

/**
 * All Elementor pages.
 *
 * @param string $form CF7 shortcode.
 * @return array
 */
function agic_demo_pages( $form ) {
	$clients  = array_map( static fn( $t ) => array( 'name' => $t, 'link' => array( 'url' => '' ) ), array( 'Northwind', 'Lumen', 'Atlas', 'Horizon', 'Vertex' ) );
	$gallery  = array_map( static fn( $f ) => array( 'image' => agic_media( $f ), 'title' => 'Project' ), array( 'device1.jpg', 'device2.jpg', 'device3.jpg', 'dash.jpg', 'chart.jpg', 'wave.jpg' ) );
	$carousel = array_map( static fn( $f ) => array( 'image' => agic_media( $f ) ), array( 'device1.jpg', 'device2.jpg', 'device3.jpg' ) );
	$avatars  = array_map( static fn( $f ) => array( 'image' => agic_media( $f ) ), array( 'person1.jpg', 'person2.jpg', 'person3.jpg' ) );
	$socials  = array_map( static fn( $n ) => array( 'icon' => agic_b( $n ), 'link' => array( 'url' => '' ) ), array( 'telegram', 'x-twitter', 'instagram', 'facebook', 'dribbble', 'linkedin' ) );
	$contact  = array();
	foreach ( array( array( 'envelope', 'Send us an email', 'hello@example.com' ), array( 'phone', 'Call us', '+00 000 000 000' ), array( 'location-dot', 'Office hours', '9am to 5pm, Monday to Friday' ) ) as $r ) {
		$contact[] = agic_c( 'agi-box', array( agic_w( 'feature', array( 'icon' => agic_i( $r[0] ), 'title' => $r[1], 'text' => $r[2], 'layout' => 'left' ), 'fade-in-up' ) ) );
	}

	$pages = array(
		'home'         => array( 'Home', agic_page_home() ),
		'about'        => array( 'About', agic_page_about() ),
		'services'     => array( 'Services', agic_page_services() ),
		'features'     => array(
			'Features',
			array(
				agic_soft_hero( 'Features', "Everything You Need\nto Move Faster", 'A complete set of tools and services for modern teams.' ),
				agic_sec( array( agic_cards() ), 'agi-sec--tight' ),
				agic_sec( array( agic_four() ) ),
				agic_sec( array( agic_split( 25, 'Numbers That Show the Difference', 'Track progress with honest reporting from day one.', 'chart.jpg', 'Live data' ) ) ),
				agic_sec( array( agic_head( 'Our Clients', 'Teams We Work With', '' ), agic_w( 'clients', array( 'items' => $clients ) ), agic_marquee( true ) ) ),
				agic_cta(),
			),
		),
		'showcase'     => array(
			'Showcase',
			array(
				agic_soft_hero( 'Our Work', "Projects We Are\nProud Of", 'A selection of recent client work.', 'Start a project' ),
				agic_sec( array( agic_w( 'video', array( 'url' => AGIC_URL . 'assets/img/hero.webm', 'poster' => agic_media( 'wave.jpg' ), 'background' => 'yes' ) ) ), 'agi-sec--tight' ),
				agic_sec( array( agic_w( 'gallery', array( 'items' => $gallery ) ) ) ),
				agic_sec( array( agic_c( 'agi-g2', array( agic_w( 'numbers', array( 'to' => 40, 'suffix' => '+', 'size' => 'xl', 'label' => 'Projects launched' ) ), agic_w( 'numbers', array( 'to' => 98, 'suffix' => '%', 'size' => 'xl', 'label' => 'Client satisfaction' ) ) ) ) ) ),
				agic_cta(),
			),
		),
		'project-page' => array(
			'Project Page',
			array(
				agic_soft_hero( 'Case Study', "A New Digital Home\nfor a Growing Brand", 'How a clearer website improved enquiries across the business.', 'Start a project' ),
				agic_sec( array( agic_w( 'img-carousel', array( 'items' => $carousel, 'autoplay' => 4000 ) ) ), 'agi-sec--tight' ),
				agic_sec( array( agic_c( 'agi-g3', array( agic_w( 'feature', array( 'icon' => agic_i( 'bullseye' ), 'title' => 'The Challenge', 'text' => 'An outdated site that no longer reflected the business.' ) ), agic_w( 'feature', array( 'icon' => agic_i( 'compass-drafting' ), 'title' => 'Our Approach', 'text' => 'Research, a new structure and a fresh visual system.' ) ), agic_w( 'feature', array( 'icon' => agic_i( 'chart-line' ), 'title' => 'The Result', 'text' => 'A faster site that is easier for the team to manage.' ) ) ) ) ) ),
				agic_cta(),
			),
		),
		'reviews'      => array(
			'Reviews',
			array(
				agic_soft_hero( 'Reviews', "What Our Clients\nSay About Us", 'Honest feedback from the teams we work with.', 'Work with us' ),
				agic_sec( array( agic_quotes(), agic_quotes() ), 'agi-sec--tight' ),
				agic_sec( array( agic_w( 'numbers', array( 'to' => 4.9, 'suffix' => ' / 5', 'size' => 'lg', 'label' => 'Average client rating' ) ), agic_marquee() ) ),
				agic_cta(),
			),
		),
		'pricing'      => array(
			'Pricing',
			array(
				agic_soft_hero( 'Pricing', "Simple Plans for\nEvery Stage of Growth", 'Pick the plan that fits today and change it whenever you need.', 'See our work', 'showcase/' ),
				agic_sec( array( agic_plans() ), 'agi-sec--tight' ),
				agic_sec( array( agic_c( 'agi-box agi-box--gray agi-stack agi-center', array( agic_marquee(), agic_w( 'heading', array( 'title' => 'Replace this with a short client quote.', 'tag' => 'h3' ) ), agic_btn( 'Start your journey', 'contact/' ) ) ) ) ),
				agic_sec( array( agic_head( 'Questions', 'Frequently Asked Questions', 'Quick answers to the things people ask most.' ), agic_faq() ) ),
				agic_cta(),
			),
		),
		'faq'          => array(
			'FAQ',
			array(
				agic_soft_hero( 'Help', 'Frequently Asked Questions', 'Everything you need to know about working with us.', 'Ask a question' ),
				agic_sec( array( agic_faq() ) ),
				agic_cta(),
			),
		),
		'contact'      => array(
			'Contact',
			array(
				agic_soft_hero( 'Talk to Us', 'Get in Touch', 'Tell us about your project and we will reply shortly.', '' ),
				agic_sec( array( agic_c( 'agi-g3', $contact ) ), 'agi-sec--tight' ),
				agic_sec( array( agic_c( 'agi-g2', array( agic_w( 'img', array( 'image' => agic_media( 'wave.jpg' ), 'hover' => 'none' ), 'fade-in' ), agic_c( 'agi-box agi-stack', array( agic_w( 'heading', array( 'title' => 'Send us a message', 'tag' => 'h4' ) ), $form ? agic_w( 'shortcode', array( 'shortcode' => $form ) ) : agic_p( 'Install Contact Form 7 and run the import again.', '' ), agic_w( 'highlighted-text', array( 'before' => 'Our usual reply time: ', 'mark' => '1 business day' ) ) ) ) ) ) ) ),
				agic_sec( array( agic_c( 'agi-box agi-stack', array( agic_w( 'heading', array( 'title' => 'Visit Our Office', 'tag' => 'h2' ) ), agic_w( 'google_maps', array( 'address' => 'London, United Kingdom', 'zoom' => array( 'unit' => 'px', 'size' => 11 ), 'height' => array( 'unit' => 'px', 'size' => 320 ), 'css_classes' => 'agi-map' ) ) ) ) ) ),
				agic_sec( array( agic_head( 'Stay Connected', "Follow Us on\nSocial Networks", 'New work and useful ideas.' ), agic_c( 'agi-stack agi-center', array( agic_w( 'circles', array( 'items' => $avatars ) ), agic_w( 'social-icons', array( 'style' => 'tile', 'items' => $socials ) ) ) ) ) ),
				agic_cta(),
			),
		),
		'coming-soon'  => array( 'Coming Soon', array( agic_soft_hero( 'Coming Soon', "Something New Is\nOn the Way", 'We are working on it. Check back soon.', 'Contact us' ) ) ),
		'404-page'     => array( 'Page Not Found', array( agic_soft_hero( 'Error 404', 'Page Not Found', 'The page you are looking for has moved or never existed.', 'Back to home', '' ) ) ),
	);
	return array_map( static fn( $p ) => array( 'title' => $p[0], 'layout' => $p[1] ), $pages );
}

/**
 * Legal pages. Sample wording only; have these reviewed before going live.
 *
 * @return array
 */
function agic_demo_legal() {
	$note = '<p><strong>Sample text. Replace it with wording reviewed for your business.</strong></p>';
	return array(
		'privacy-policy' => array( 'title' => 'Privacy Policy', 'content' => $note . '<h2>What we collect</h2><p>We collect the details you send through our forms, and basic usage data needed to run this website.</p><h2>How we use it</h2><p>We use your details to reply to you and to provide our services. We do not sell personal data.</p><h2>Your rights</h2><p>You can ask us to access, correct or delete your personal data at any time.</p>' ),
		'terms-of-use'   => array( 'title' => 'Terms of Use', 'content' => $note . '<h2>Using this site</h2><p>By using this website you agree to these terms.</p><h2>Content</h2><p>Content on this site belongs to us unless stated otherwise.</p><h2>Liability</h2><p>This website is provided as it is, without warranties of any kind.</p>' ),
		'cookie-policy'  => array( 'title' => 'Cookie Policy', 'content' => $note . '<h2>What cookies are</h2><p>Cookies are small files stored by your browser.</p><h2>How we use them</h2><p>We use cookies needed for the site to work, and we remember if you closed the cookie notice.</p><h2>Managing cookies</h2><p>You can block or delete cookies in your browser settings.</p>' ),
	);
}

/**
 * Sample posts and post type entries.
 *
 * @return array
 */
function agic_demo_entries() {
	$body    = '<p>Replace this sample text with your own content. Good pages answer the question a visitor came with, quickly and clearly.</p><h2>Start with the goal</h2><p>Decide what you want a reader to do next, then remove anything that gets in the way.</p><h2>Keep improving</h2><p>Review the results each month and make one small change at a time.</p>';
	$entries = array();
	$posts   = array(
		array( 'five-signs-your-website-needs-a-refresh', 'Five Signs Your Website Needs a Refresh', 'Design', array( 'website', 'design' ), 'wave.jpg' ),
		array( 'how-to-write-a-better-project-brief', 'How to Write a Better Project Brief', 'Articles', array( 'business', 'tutorial' ), 'device1.jpg' ),
		array( 'what-to-measure-after-launch', 'What to Measure After Launch', 'Articles', array( 'business', 'article' ), 'chart.jpg' ),
		array( 'small-changes-that-lift-conversions', 'Small Changes That Lift Conversions', 'New', array( 'website', 'article' ), 'device2.jpg' ),
		array( 'choosing-the-right-partner', 'Choosing the Right Digital Partner', 'New', array( 'business', 'modern' ), 'device3.jpg' ),
	);
	foreach ( $posts as $i => $p ) {
		$entries[] = array( 'type' => 'post', 'slug' => $p[0], 'title' => $p[1], 'category' => $p[2], 'tags' => $p[3], 'image' => $p[4], 'excerpt' => 'A practical guide for growing teams.', 'content' => $body, 'date' => gmdate( 'Y-m-d H:i:s', time() - ( $i + 1 ) * WEEK_IN_SECONDS ) );
	}
	$items = array(
		array( 'agi_service', 'brand-web-design', 'Brand and Web Design', 'Identity and websites that make a strong first impression.', 'device1.jpg' ),
		array( 'agi_service', 'web-development', 'Web Development', 'Fast, secure sites your team can update.', 'device2.jpg' ),
		array( 'agi_service', 'growth-marketing', 'Growth Marketing', 'Search, content and ads focused on qualified leads.', 'chart.jpg' ),
		array( 'agi_project', 'retail-redesign', 'Retail Website Redesign', 'A clearer path from browsing to buying.', 'device1.jpg' ),
		array( 'agi_project', 'saas-launch', 'Software Launch Campaign', 'A launch site and campaign for a new product.', 'device2.jpg' ),
		array( 'agi_project', 'clinic-rebrand', 'Healthcare Rebrand', 'A calmer, more trustworthy brand and website.', 'device3.jpg' ),
		array( 'agi_team', 'team-member-1', 'Team Member One', 'Creative Director', 'person1.jpg' ),
		array( 'agi_team', 'team-member-2', 'Team Member Two', 'Strategy Lead', 'person2.jpg' ),
		array( 'agi_team', 'team-member-3', 'Team Member Three', 'Marketing Lead', 'person3.jpg' ),
		array( 'agi_career', 'senior-designer', 'Senior Designer', 'Full time, remote.', 'wave.jpg' ),
		array( 'agi_career', 'wordpress-developer', 'WordPress Developer', 'Full time, hybrid.', 'dash.jpg' ),
		array( 'agi_career', 'account-manager', 'Account Manager', 'Full time, on site.', 'chart.jpg' ),
	);
	foreach ( $items as $e ) {
		$entries[] = array( 'type' => $e[0], 'slug' => $e[1], 'title' => $e[2], 'excerpt' => $e[3], 'image' => $e[4], 'content' => $body );
	}
	return $entries;
}
