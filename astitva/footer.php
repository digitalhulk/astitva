<?php
/**
 * Site footer, floating buttons, popups and cookie banner.
 *
 * @package Astitva
 */

defined( 'ABSPATH' ) || exit;
?>
</main>
<?php if ( ! agi_print_template( 'footer_template' ) ) : ?>
<footer class="site-footer">
	<div class="footer-box">
		<div class="footer-brand">
			<?php agi_logo(); ?>
			<h3><?php echo esc_html( agi_option( 'footer_title' ) ); ?></h3>
			<p><?php echo esc_html( agi_option( 'footer_text' ) ); ?></p>
			<a class="btn btn--primary btn--sm" href="<?php echo esc_url( agi_option( 'cta_url' ) ?: home_url( '/contact/' ) ); ?>"><?php echo esc_html( agi_option( 'cta_label' ) ); ?> <?php agi_the_icon( 'arrow' ); ?></a>
			<?php agi_social( 'social-links' ); ?>
		</div>
		<?php
		foreach ( array( 'footer-1', 'footer-2' ) as $location ) {
			wp_nav_menu(
				array(
					'theme_location'  => $location,
					'container'       => 'nav',
					'container_class' => 'footer-menu',
					'fallback_cb'     => false,
					'depth'           => 1,
				)
			);
		}
		?>
	</div>
	<p class="footer-copy">&copy; <?php echo esc_html( gmdate( 'Y' ) . ' ' . agi_option( 'copyright' ) ); ?></p>
</footer>
<?php endif; ?>

<button class="float-top" type="button" data-agi-top aria-label="<?php esc_attr_e( 'Back to top', 'astitva' ); ?>"><?php agi_the_icon( 'up' ); ?></button>
<button class="float-launcher" type="button" data-agi-open="launcher" aria-label="<?php esc_attr_e( 'Open menu', 'astitva' ); ?>"><?php agi_the_icon( 'menu' ); ?></button>

<aside class="agi-drawer" id="agi-launcher" aria-hidden="true" aria-label="<?php esc_attr_e( 'Quick links', 'astitva' ); ?>" tabindex="-1">
	<button class="agi-close" type="button" data-agi-close aria-label="<?php esc_attr_e( 'Close', 'astitva' ); ?>"><?php agi_the_icon( 'close' ); ?></button>
	<?php agi_logo(); ?>
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'launcher',
			'container'      => false,
			'menu_class'     => 'drawer-menu',
			'fallback_cb'    => false,
			'depth'          => 1,
		)
	);
	?>
	<?php agi_social( 'social-links' ); ?>
</aside>
<div class="agi-scrim" data-agi-close></div>

<?php
$agi_cookie_page = absint( agi_option( 'cookie_page' ) );
if ( agi_option( 'cookie_enable' ) ) :
	?>
	<div class="cookie-bar" id="agi-cookie" hidden>
		<span><?php echo esc_html( agi_option( 'cookie_text' ) ); ?>
			<?php if ( $agi_cookie_page ) : ?>
				<a href="<?php echo esc_url( get_permalink( $agi_cookie_page ) ); ?>"><?php echo esc_html( agi_option( 'cookie_link' ) ); ?></a>
			<?php endif; ?>
		</span>
		<button type="button" data-agi-cookie aria-label="<?php esc_attr_e( 'Close', 'astitva' ); ?>"><?php agi_the_icon( 'close' ); ?></button>
	</div>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
