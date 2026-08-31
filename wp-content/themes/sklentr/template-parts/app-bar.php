<?php
/**
 * Mobile app bar — a native-app-style floating bottom navigation.
 *
 * Shown ONLY on mobile/tablet (≤ 980px, matching the header breakpoint) via
 * CSS in scss/components/_app-bar.scss. On desktop it is `display:none`, so the
 * desktop header/nav is completely unaffected.
 *
 * Layout: 4 destination tabs around a raised centre "Book a Call" FAB —
 *   Home · Services · (Book) · Pricing · Menu
 * The "Menu" tab opens the existing full nav overlay (see assets/js/app-bar.js),
 * so every page (About, Blog, Portfolio, Startup Visa …) stays reachable.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Destination URLs — mirror the primary-nav fallback so nothing 404s. */
$skl_ab_svc_page = get_page_by_path( 'services' );
$skl_ab_svc_url  = $skl_ab_svc_page ? get_permalink( $skl_ab_svc_page->ID ) : home_url( '/services/' );
$skl_ab_pr_page  = get_page_by_path( 'pricing' );
$skl_ab_pr_url   = $skl_ab_pr_page ? get_permalink( $skl_ab_pr_page->ID ) : home_url( '/pricing/' );

/* The centre FAB reuses the global "Book a Call" destination. */
$skl_ab_book_url = skl_opt( 'cta_calendly', 'https://calendly.com/sklentr' );

/* Side tabs: [ href, label, is-current ]. Icons come from sklentr_nav_icon(). */
$skl_ab_left = array(
	array( home_url( '/' ),   __( 'Home', 'sklentr' ),     is_front_page() ),
	array( $skl_ab_svc_url,   __( 'Services', 'sklentr' ), is_page( 'services' ) ),
);
$skl_ab_right = array(
	array( $skl_ab_pr_url,    __( 'Pricing', 'sklentr' ),  is_page( 'pricing' ) ),
);

/**
 * Render one app-bar tab link.
 *
 * @param array $item [ href, label, is-current ].
 */
$skl_ab_tab = static function ( $item ) {
	list( $href, $label, $current ) = $item;
	printf(
		'<li class="app-bar__cell"><a class="app-bar__item%1$s" href="%2$s"%3$s>%4$s<span class="app-bar__label">%5$s</span></a></li>',
		$current ? ' is-active' : '',
		esc_url( $href ),
		$current ? ' aria-current="page"' : '',
		sklentr_nav_icon( $label ), // phpcs:ignore WordPress.Security.EscapingOutput -- pre-escaped SVG.
		esc_html( $label )
	);
};
?>

<nav class="app-bar" aria-label="<?php esc_attr_e( 'Mobile navigation', 'sklentr' ); ?>" data-app-bar>
	<ul class="app-bar__list">

		<?php foreach ( $skl_ab_left as $skl_ab_item ) { $skl_ab_tab( $skl_ab_item ); } ?>

		<!-- Centre: raised "Book a Call" action -->
		<li class="app-bar__cell app-bar__cell--center">
			<a class="app-bar__fab" href="<?php echo esc_url( $skl_ab_book_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Book a Call', 'sklentr' ); ?>">
				<span class="app-bar__fab-icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M5 4h3.5l1.5 4-2 1.5a11 11 0 0 0 5 5l1.5-2 4 1.5V19a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg>
				</span>
			</a>
			<span class="app-bar__fab-label"><?php esc_html_e( 'Book', 'sklentr' ); ?></span>
		</li>

		<?php foreach ( $skl_ab_right as $skl_ab_item ) { $skl_ab_tab( $skl_ab_item ); } ?>

		<!-- Menu: opens the full nav overlay (wired in app-bar.js) -->
		<li class="app-bar__cell">
			<button type="button" class="app-bar__item app-bar__more" data-app-bar-more aria-controls="site-nav">
				<span class="nav-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M4 7h16M4 12h16M4 17h16"/></svg></span>
				<span class="app-bar__label"><?php esc_html_e( 'Menu', 'sklentr' ); ?></span>
			</button>
		</li>

	</ul>
</nav>
