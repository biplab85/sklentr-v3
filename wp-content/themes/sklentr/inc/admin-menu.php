<?php
/**
 * Sklentr — admin menu polish.
 *
 * Gives every item under the "Sklentr" menu its own dashicon, an active state,
 * and hairline separators. WordPress renders submenu items as bare text links,
 * so the icon is injected into the item's title (core passes submenu titles
 * through unescaped, which is how core itself renders update-count bubbles).
 *
 * Admin-only: both hooks below are admin-side, so nothing reaches the front end.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Dashicon for each item in the Sklentr menu, keyed by post type.
 *
 * Defaults to the CPT's own 'icon', with explicit overrides where several CPTs
 * share one (skl_project / skl_work / skl_portfolio are all dashicons-portfolio)
 * so no two menu items look alike.
 *
 * @return array<string,string>
 */
function sklentr_menu_icons() {
	$icons = array();

	if ( function_exists( 'sklentr_cpts' ) ) {
		foreach ( sklentr_cpts() as $slug => $c ) {
			if ( ! empty( $c['icon'] ) ) {
				$icons[ $slug ] = $c['icon'];
			}
		}
	}

	// Distinct icons where the CPT defaults collide, plus the non-CPT entries.
	return array_merge(
		$icons,
		array(
			'sklentr-settings' => 'dashicons-admin-settings',
			'skl_project'      => 'dashicons-images-alt2',   // Trusted Logos.
			'skl_work'         => 'dashicons-portfolio',     // Featured Work.
			'skl_portfolio'    => 'dashicons-format-gallery', // Portfolio Projects.
			'skl_subscriber'   => 'dashicons-email-alt',
		)
	);
}

/**
 * Prepend a dashicon to every Sklentr submenu label.
 *
 * Runs late so every CPT has registered its menu entry first.
 */
add_action( 'admin_menu', function () {
	global $submenu;

	if ( empty( $submenu['sklentr-settings'] ) ) {
		return;
	}

	$icons = sklentr_menu_icons();

	foreach ( $submenu['sklentr-settings'] as $i => $item ) {
		if ( ! isset( $item[2] ) ) {
			continue;
		}

		$slug = $item[2];
		$key  = 'sklentr-settings';

		if ( preg_match( '/post_type=([a-z_]+)/', $slug, $m ) ) {
			$key = $m[1];
		}

		$icon = isset( $icons[ $key ] ) ? $icons[ $key ] : 'dashicons-marker';

		// Already processed (admin_menu can fire more than once in some flows).
		if ( false !== strpos( $item[0], 'skl-menu__ico' ) ) {
			continue;
		}

		$submenu['sklentr-settings'][ $i ][0] =
			'<span class="skl-menu__ico dashicons ' . esc_attr( $icon ) . '" aria-hidden="true"></span>'
			. '<span class="skl-menu__txt">' . $item[0] . '</span>';
	}
}, 999 );

/**
 * Menu styling.
 *
 * Enqueued on every admin screen — unlike the rest of the Sklentr admin CSS —
 * because the menu itself is present on every admin screen. Every rule is
 * scoped to #toplevel_page_sklentr-settings, so no other menu is affected.
 */
add_action( 'admin_enqueue_scripts', function () {
	wp_enqueue_style(
		'sklentr-admin-menu',
		get_theme_file_uri( 'assets/css/admin-menu.css' ),
		array( 'dashicons' ),
		sklentr_asset_ver( 'assets/css/admin-menu.css' )
	);

	// Collapse toggle for the 18-item child list.
	wp_enqueue_script(
		'sklentr-admin-menu',
		get_theme_file_uri( 'assets/js/admin-menu.js' ),
		array(),
		sklentr_asset_ver( 'assets/js/admin-menu.js' ),
		true
	);
} );
