<?php
/**
 * Header — opens <html>, prints <head>, opens <body>, renders the site header/nav.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'sklentr' ); ?></a>

<header class="site-header" id="site-header" data-header>
	<div class="site-header__inner skl-container">

		<?php
		if ( has_custom_logo() ) {
			the_custom_logo();
		} else {
			echo sklentr_wordmark(); // phpcs:ignore WordPress.Security.EscapingOutput -- returns pre-escaped markup.
		}
		?>

		<nav class="site-nav" id="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'sklentr' ); ?>">

			<?php // Drawer header (mobile only): brand logo + close button. ?>
			<div class="site-nav__head">
				<span class="site-nav__logo">
					<?php
					if ( has_custom_logo() ) {
						the_custom_logo();
					} else {
						echo sklentr_wordmark(); // phpcs:ignore WordPress.Security.EscapingOutput -- returns pre-escaped markup.
					}
					?>
				</span>
				<button type="button" class="site-nav__close" data-nav-close aria-label="<?php esc_attr_e( 'Close menu', 'sklentr' ); ?>">
					<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18"/></svg>
				</button>
			</div>

			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_id'        => 'primary-menu',
				'menu_class'     => 'nav-menu',
				'depth'          => 2,
				'fallback_cb'    => 'sklentr_primary_menu_fallback',
			) );
			?>

			<a class="skl-btn skl-btn--gold skl-btn--sm site-nav__cta" href="https://calendly.com/sklentr" target="_blank" rel="noopener noreferrer">
				<?php esc_html_e( 'Book a Call', 'sklentr' ); ?>
			</a>

			<?php // Drawer social row (mobile only). ?>
			<?php $skl_nav_social = sklentr_social_links( 'site-nav__socials' ); ?>
			<?php if ( $skl_nav_social ) : ?>
				<div class="site-nav__social">
					<span class="site-nav__social-label"><?php esc_html_e( 'Follow us', 'sklentr' ); ?></span>
					<?php echo $skl_nav_social; // phpcs:ignore WordPress.Security.EscapingOutput -- pre-escaped markup. ?>
				</div>
			<?php endif; ?>
		</nav>

		<div class="site-header__actions">
			<a class="skl-btn skl-btn--gold skl-btn--sm site-header__cta" href="https://calendly.com/sklentr" target="_blank" rel="noopener noreferrer">
				<span class="skl-btn__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M5 4h3.5l1.5 4-2 1.5a11 11 0 0 0 5 5l1.5-2 4 1.5V19a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg></span>
				<?php esc_html_e( 'Book a Call', 'sklentr' ); ?>
			</a>

			<button
				type="button"
				class="nav-toggle"
				data-nav-toggle
				aria-expanded="false"
				aria-controls="site-nav"
				aria-label="<?php esc_attr_e( 'Toggle menu', 'sklentr' ); ?>">
				<span class="nav-toggle__bars" aria-hidden="true"></span>
			</button>
		</div>

	</div>
</header>

<?php // Backdrop scrim for the mobile nav drawer (styled/shown only ≤980px). ?>
<div class="nav-scrim" data-nav-scrim aria-hidden="true"></div>

<main id="content" class="site-main">
