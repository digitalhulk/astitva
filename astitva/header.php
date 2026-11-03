<?php
/**
 * Site header.
 *
 * @package Astitva
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#content"><?php esc_html_e( 'Skip to content', 'astitva' ); ?></a>
<?php if ( ! agi_print_template( 'header_template' ) ) : ?>
<header class="site-header" id="site-header">
	<div class="island-wrap">
		<div class="island">
			<?php agi_logo(); ?>
			<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-menu" aria-label="<?php esc_attr_e( 'Toggle menu', 'astitva' ); ?>"><span></span><span></span><span></span></button>
			<nav class="primary-nav" id="primary-menu" aria-label="<?php esc_attr_e( 'Primary', 'astitva' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'menu',
						'fallback_cb'    => false,
						'depth'          => 2,
						'link_after'     => '',
					)
				);
				?>
				<div class="island-actions">
					<?php if ( agi_option( 'cta_label' ) ) : ?>
						<a class="island-link" href="<?php echo esc_url( agi_option( 'cta_url' ) ?: home_url( '/contact/' ) ); ?>"><?php echo esc_html( agi_option( 'cta_label' ) ); ?> <?php agi_the_icon( 'arrow' ); ?></a>
					<?php endif; ?>
					<button class="btn btn--black btn--md" type="button" data-agi-open="launcher"><?php echo esc_html( agi_option( 'launcher_label' ) ); ?> <?php agi_the_icon( 'chat' ); ?></button>
				</div>
			</nav>
		</div>
		<div class="island-sub">
			<span class="island-sub__group">
				<span><?php agi_the_icon( 'pin' ); ?> <?php echo esc_html( agi_option( 'location' ) ); ?></span>
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', agi_option( 'phone' ) ) ); ?>"><?php agi_the_icon( 'phone' ); ?> <?php esc_html_e( 'Call us', 'astitva' ); ?></a>
			</span>
			<?php agi_social( 'island-sub__social' ); ?>
		</div>
	</div>
</header>
<?php endif; ?>
<main id="content" class="site-main">
