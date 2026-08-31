<?php
/**
 * Sklentr theme — bootstrap.
 *
 * Standalone hand-coded theme for the SKL Entr (Sklentr) redesign.
 * No page builder: sections are PHP templates in /template-parts with
 * CSS/JS enqueued below. Design tokens live in style.css :root.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

define( 'SKLENTR_VERSION', '1.0.0' );

/**
 * Native admin/data layer (no plugins): Theme Options page + Custom Post Types.
 * All front-end content is editable from wp-admin.
 */
// Loaded first: it opens the duplicate guard on `init` priority 0, before any
// of the seeders below run, and repairs slugs/homepage/permalinks on activation.
require_once get_theme_file_path( 'inc/migration-safety.php' );

require_once get_theme_file_path( 'inc/options.php' );
require_once get_theme_file_path( 'inc/post-types.php' );
require_once get_theme_file_path( 'inc/services-page.php' );
require_once get_theme_file_path( 'inc/seed-content.php' );
require_once get_theme_file_path( 'inc/portfolio-content.php' );
require_once get_theme_file_path( 'inc/about-content.php' );
require_once get_theme_file_path( 'inc/blog-posts.php' );
require_once get_theme_file_path( 'inc/newsletter.php' );
require_once get_theme_file_path( 'inc/admin-menu.php' );

/**
 * Is the current request one of the Sklentr admin screens?
 *
 * Covers all four surfaces: the Settings page, the Sklentr CPT list tables,
 * the Sklentr CPT editors, and the blog-post editor (which carries the
 * "Article details (Sklentr)" meta box).
 *
 * @param string $hook Current admin page hook.
 * @return bool
 */
function sklentr_is_admin_screen( $hook ) {
	if ( false !== strpos( $hook, 'sklentr-settings' ) ) {
		return true;
	}

	if ( ! in_array( $hook, array( 'post.php', 'post-new.php', 'edit.php' ), true ) ) {
		return false;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen ) {
		return false;
	}

	// Sklentr CPTs (list + editor), plus the post editor for the article box.
	return 0 === strpos( $screen->post_type, 'skl_' )
		|| ( 'post' === $screen->post_type && 'edit.php' !== $hook );
}

/**
 * Admin-only stylesheet for every Sklentr screen.
 *
 * Hooked to admin_enqueue_scripts, so it never loads on the front end.
 *
 * @param string $hook Current admin page hook.
 */
add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( ! sklentr_is_admin_screen( $hook ) ) {
		return;
	}

	wp_enqueue_style(
		'sklentr-admin',
		get_theme_file_uri( 'assets/css/admin.css' ),
		array(),
		sklentr_asset_ver( 'assets/css/admin.css' )
	);
} );

/**
 * Tag Sklentr screens so list-table styling can be scoped and never leak into
 * other admin screens.
 *
 * @param string $classes Existing body classes.
 * @return string
 */
add_filter( 'admin_body_class', function ( $classes ) {
	global $hook_suffix;

	if ( $hook_suffix && sklentr_is_admin_screen( $hook_suffix ) ) {
		$classes .= ' skl-admin';
	}

	return $classes;
} );

/**
 * Theme supports & nav menus.
 */
function sklentr_setup() {
	load_theme_textdomain( 'sklentr', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-logo', array(
		'height'      => 40,
		'width'       => 160,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
		'navigation-widgets',
	) );
	add_theme_support( 'responsive-embeds' );

	register_nav_menus( array(
		'primary'         => esc_html__( 'Primary Menu', 'sklentr' ),
		'footer'          => esc_html__( 'Footer Menu', 'sklentr' ),
		'footer-services'  => esc_html__( 'Footer — Column 1 (Services)', 'sklentr' ),
		'footer-company'   => esc_html__( 'Footer — Column 2 (Company)', 'sklentr' ),
		'footer-resources' => esc_html__( 'Footer — Column 3 (Resources)', 'sklentr' ),
	) );
}
add_action( 'after_setup_theme', 'sklentr_setup' );

/**
 * Cache-busting version for a theme asset (filemtime in dev, theme version fallback).
 *
 * @param string $relative_path Path relative to the theme root, e.g. 'assets/css/hero.css'.
 * @return string|int
 */
function sklentr_asset_ver( $relative_path ) {
	$file = get_theme_file_path( $relative_path );
	return file_exists( $file ) ? filemtime( $file ) : SKLENTR_VERSION;
}

/**
 * Enqueue brand fonts, styles, and scripts.
 */
function sklentr_assets() {

	// Canonical brand fonts — Sora (headings) + Inter (body).
	wp_enqueue_style(
		'sklentr-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Sora:wght@600;700;800&display=swap',
		array(),
		null
	);

	// Compiled stylesheet (Dart Sass: scss/main.scss → assets/css/main.css).
	// style.css holds only the WP theme header and is intentionally not enqueued.
	wp_enqueue_style(
		'sklentr-main',
		get_theme_file_uri( 'assets/css/main.css' ),
		array( 'sklentr-fonts' ),
		sklentr_asset_ver( 'assets/css/main.css' )
	);

	// Navigation behaviour (mobile toggle + sticky state).
	wp_enqueue_script(
		'sklentr-nav',
		get_theme_file_uri( 'assets/js/nav.js' ),
		array(),
		sklentr_asset_ver( 'assets/js/nav.js' ),
		true
	);

	// Generic scroll reveal (site-wide).
	wp_enqueue_script(
		'sklentr-reveal',
		get_theme_file_uri( 'assets/js/reveal.js' ),
		array(),
		sklentr_asset_ver( 'assets/js/reveal.js' ),
		true
	);

	// Smooth scrolling scoped to in-page anchor clicks (site-wide) — replaces the
	// global CSS scroll-behavior that animated scroll restoration / caused jumps.
	wp_enqueue_script(
		'sklentr-smooth-anchors',
		get_theme_file_uri( 'assets/js/smooth-anchors.js' ),
		array(),
		sklentr_asset_ver( 'assets/js/smooth-anchors.js' ),
		true
	);

	// Character "fill" heading reveal (site-wide; opt-in per heading via data-char-fill).
	wp_enqueue_script(
		'sklentr-char-fill',
		get_theme_file_uri( 'assets/js/char-fill.js' ),
		array(),
		sklentr_asset_ver( 'assets/js/char-fill.js' ),
		true
	);

	// "Shuffle" card reveal (site-wide; opt-in per grid via data-advance).
	wp_enqueue_script(
		'sklentr-cards-advance',
		get_theme_file_uri( 'assets/js/cards-advance.js' ),
		array(),
		sklentr_asset_ver( 'assets/js/cards-advance.js' ),
		true
	);

	// Newsletter opt-in (site-wide: the footer form appears on every page).
	wp_enqueue_script(
		'sklentr-newsletter',
		get_theme_file_uri( 'assets/js/newsletter.js' ),
		array(),
		sklentr_asset_ver( 'assets/js/newsletter.js' ),
		true
	);
	wp_localize_script( 'sklentr-newsletter', 'sklNewsletter', sklentr_newsletter_js_data() );

	// Footer column accordion — mobile only (<=560px); site-wide footer.
	wp_enqueue_script(
		'sklentr-footer-accordion',
		get_theme_file_uri( 'assets/js/footer-accordion.js' ),
		array(),
		sklentr_asset_ver( 'assets/js/footer-accordion.js' ),
		true
	);

	// Mobile app bar — smart hide-on-scroll + "Menu" opens the nav overlay.
	// Site-wide (the bar is rendered on every page; CSS shows it only ≤980px).
	wp_enqueue_script(
		'sklentr-app-bar',
		get_theme_file_uri( 'assets/js/app-bar.js' ),
		array(),
		sklentr_asset_ver( 'assets/js/app-bar.js' ),
		true
	);

	// Front-page enhancements: hero launch-panel count-up + scroll-triggered stat counters.
	if ( is_front_page() ) {
		wp_enqueue_script(
			'sklentr-hero',
			get_theme_file_uri( 'assets/js/hero.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/hero.js' ),
			true
		);
		wp_enqueue_script(
			'sklentr-counters',
			get_theme_file_uri( 'assets/js/counters.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/counters.js' ),
			true
		);
		wp_enqueue_script(
			'sklentr-hero-tilt',
			get_theme_file_uri( 'assets/js/hero-tilt.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/hero-tilt.js' ),
			true
		);
		wp_enqueue_script(
			'sklentr-visa-stack',
			get_theme_file_uri( 'assets/js/visa-stack.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/visa-stack.js' ),
			true
		);
		wp_enqueue_script(
			'sklentr-work-slider',
			get_theme_file_uri( 'assets/js/work-slider.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/work-slider.js' ),
			true
		);
		wp_enqueue_script(
			'sklentr-process',
			get_theme_file_uri( 'assets/js/process.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/process.js' ),
			true
		);
		wp_enqueue_script(
			'sklentr-faq',
			get_theme_file_uri( 'assets/js/faq.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/faq.js' ),
			true
		);
	}

	// Services page: hero typewriter + count-up stats (reuses the homepage counter).
	if ( is_page( 'services' ) ) {
		wp_enqueue_script(
			'sklentr-counters',
			get_theme_file_uri( 'assets/js/counters.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/counters.js' ),
			true
		);
		wp_enqueue_script(
			'sklentr-svc-hero',
			get_theme_file_uri( 'assets/js/svc-hero.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/svc-hero.js' ),
			true
		);
		wp_enqueue_script(
			'sklentr-svc-tabs',
			get_theme_file_uri( 'assets/js/svc-tabs.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/svc-tabs.js' ),
			true
		);
		wp_enqueue_script(
			'sklentr-svc-stack',
			get_theme_file_uri( 'assets/js/svc-stack.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/svc-stack.js' ),
			true
		);
		// How We Work is the shared homepage part — load its step animation here too.
		wp_enqueue_script(
			'sklentr-process',
			get_theme_file_uri( 'assets/js/process.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/process.js' ),
			true
		);
	}

	// Portfolio page: reuse the scroll-triggered count-up for the hero stats band,
	// plus the manifesto section's scroll-scrubbed photo reveal.
	if ( is_page( 'portfolio' ) ) {
		wp_enqueue_script(
			'sklentr-counters',
			get_theme_file_uri( 'assets/js/counters.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/counters.js' ),
			true
		);
		wp_enqueue_script(
			'sklentr-pf-manifesto',
			get_theme_file_uri( 'assets/js/pf-manifesto.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/pf-manifesto.js' ),
			true
		);
		wp_enqueue_script(
			'sklentr-pf-featured',
			get_theme_file_uri( 'assets/js/pf-featured.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/pf-featured.js' ),
			true
		);
	}

	// Pricing page: hero typewriter + the shared FAQ accordion (same UI as home / startup-visa).
	if ( is_page( 'pricing' ) ) {
		wp_enqueue_script(
			'sklentr-pr-hero',
			get_theme_file_uri( 'assets/js/pr-hero.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/pr-hero.js' ),
			true
		);
		wp_enqueue_script(
			'sklentr-faq',
			get_theme_file_uri( 'assets/js/faq.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/faq.js' ),
			true
		);
		// Guarantees reuse the homepage "How We Work" staircase — load its step animation here too.
		wp_enqueue_script(
			'sklentr-process',
			get_theme_file_uri( 'assets/js/process.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/process.js' ),
			true
		);
		// "What We Do" is the shared Services part — load its sticky tab nav here too.
		wp_enqueue_script(
			'sklentr-svc-tabs',
			get_theme_file_uri( 'assets/js/svc-tabs.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/svc-tabs.js' ),
			true
		);
	}

	// Startup Visa page: hero stat count-up + the shared FAQ accordion (same UI as home).
	if ( is_page( 'startup-visa' ) ) {
		wp_enqueue_script(
			'sklentr-counters',
			get_theme_file_uri( 'assets/js/counters.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/counters.js' ),
			true
		);
		wp_enqueue_script(
			'sklentr-faq',
			get_theme_file_uri( 'assets/js/faq.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/faq.js' ),
			true
		);
		wp_enqueue_script(
			'sklentr-process',
			get_theme_file_uri( 'assets/js/process.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/process.js' ),
			true
		);
	}
	// About page: count-up for the hero stat band (reuses the homepage counter)
	// + scroll-driven parallax on the Our Values 2×2 card grid.
	if ( is_page( 'about' ) ) {
		wp_enqueue_script(
			'sklentr-counters',
			get_theme_file_uri( 'assets/js/counters.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/counters.js' ),
			true
		);
		wp_enqueue_script(
			'sklentr-about-values',
			get_theme_file_uri( 'assets/js/about-values.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/about-values.js' ),
			true
		);
		wp_enqueue_script(
			'sklentr-about-story',
			get_theme_file_uri( 'assets/js/about-story.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/about-story.js' ),
			true
		);
		// Top Services slider (bundled Swiper, scoped to this page).
		wp_enqueue_style(
			'swiper',
			get_theme_file_uri( 'assets/vendor/swiper/swiper-bundle.min.css' ),
			array(),
			'11.2.10'
		);
		wp_enqueue_script(
			'swiper',
			get_theme_file_uri( 'assets/vendor/swiper/swiper-bundle.min.js' ),
			array(),
			'11.2.10',
			true
		);
		wp_enqueue_script(
			'sklentr-about-services',
			get_theme_file_uri( 'assets/js/about-services.js' ),
			array( 'swiper' ),
			sklentr_asset_ver( 'assets/js/about-services.js' ),
			true
		);
	}

	// Blog index: category filter / load-more + newsletter opt-in.
	if ( is_page( 'blog' ) ) {
		wp_enqueue_script(
			'sklentr-blog',
			get_theme_file_uri( 'assets/js/blog.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/blog.js' ),
			true
		);
	}

	// Article page (single post): TOC scroll-spy + copy-link share.
	if ( is_single() ) {
		wp_enqueue_script(
			'sklentr-blog-detail',
			get_theme_file_uri( 'assets/js/blog-detail.js' ),
			array(),
			sklentr_asset_ver( 'assets/js/blog-detail.js' ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'sklentr_assets' );

/**
 * Preconnect to Google Fonts hosts.
 *
 * @param array  $urls          URLs to hint.
 * @param string $relation_type Relation type.
 * @return array
 */
function sklentr_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'sklentr_resource_hints', 10, 2 );

/**
 * Output the brand favicon (bundled in the theme). Skipped if a Site Icon is set
 * in the Customizer, so we never double up.
 */
add_action( 'wp_head', function () {
	if ( function_exists( 'has_site_icon' ) && has_site_icon() ) {
		return;
	}
	$icon  = get_theme_file_uri( 'assets/images/icon.png' ) . '?v=' . sklentr_asset_ver( 'assets/images/icon.png' );
	$apple = get_theme_file_uri( 'assets/images/apple-icon.png' ) . '?v=' . sklentr_asset_ver( 'assets/images/apple-icon.png' );
	printf(
		"<link rel=\"icon\" href=\"%1\$s\" sizes=\"512x512\" type=\"image/png\">\n<link rel=\"apple-touch-icon\" href=\"%2\$s\" sizes=\"512x512\" type=\"image/png\">\n",
		esc_url( $icon ),
		esc_url( $apple )
	);
}, 5 );

/**
 * A small leading icon for a primary-nav item, chosen by its label.
 * Unknown labels (admin-added items) get a neutral dot, so every item has one.
 *
 * @param string $label Menu item label.
 * @return string Pre-escaped, safe SVG markup wrapped in a .nav-icon span.
 */
function sklentr_nav_icon( $label ) {
	$key = strtolower( trim( wp_strip_all_tags( (string) $label ) ) );

	$line = 'viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"';

	$icons = array(
		'home'         => '<svg ' . $line . '><path d="M4 11l8-7 8 7"/><path d="M6.5 9.5V20h11V9.5"/></svg>',
		'services'     => '<svg ' . $line . '><rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/></svg>',
		'work'         => '<svg ' . $line . '><rect x="3" y="7.5" width="18" height="12.5" rx="2.2"/><path d="M8.5 7.5V6a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v1.5"/><path d="M3 12h18"/></svg>',
		'portfolio'    => '<svg ' . $line . '><rect x="3" y="7.5" width="18" height="12.5" rx="2.2"/><path d="M8.5 7.5V6a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v1.5"/><path d="M3 12h18"/></svg>',
		'startup visa' => '<svg viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 2.5l1.7 4.9 4.9 1.7-4.9 1.7L12 15.7l-1.7-4.9L5.4 9.1l4.9-1.7z"/><path d="M18.5 14l.8 2.2 2.2.8-2.2.8-.8 2.2-.8-2.2-2.2-.8 2.2-.8z"/></svg>',
		'pricing'      => '<svg ' . $line . '><path d="M3.5 12.5l8-8H20V13l-8 8z"/><circle cx="8.2" cy="8.2" r="1.3"/></svg>',
		'faq'          => '<svg ' . $line . '><circle cx="12" cy="12" r="9"/><path d="M9.6 9.3a2.4 2.4 0 1 1 3.4 2.2c-.8.4-1.1.9-1.1 1.8"/><path d="M12 16.6h.01"/></svg>',
		'about'        => '<svg ' . $line . '><circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 7.6h.01"/></svg>',
		'blog'         => '<svg ' . $line . '><path d="M6 3h8l4 4v14H6z"/><path d="M14 3v4h4"/><path d="M9 13h6M9 16.5h6"/></svg>',
		'insights'     => '<svg ' . $line . '><path d="M6 3h8l4 4v14H6z"/><path d="M14 3v4h4"/><path d="M9 13h6M9 16.5h6"/></svg>',
		'pages'        => '<svg ' . $line . '><path d="M6 3h8l4 4v14H6z"/><path d="M14 3v4h4"/></svg>',
		'contact'      => '<svg ' . $line . '><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="M4 7l8 6 8-6"/></svg>',
	);

	$svg = isset( $icons[ $key ] )
		? $icons[ $key ]
		: '<svg viewBox="0 0 24 24" fill="currentColor" stroke="none"><circle cx="12" cy="12" r="2.6"/></svg>';

	return '<span class="nav-icon" aria-hidden="true">' . $svg . '</span>';
}

/**
 * Prepend the matching icon to each top-level primary-nav item (for real menus
 * assigned under Appearance → Menus). The fallback menu adds its own below.
 */
add_filter( 'nav_menu_item_title', function ( $title, $item, $args, $depth ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location && 0 === (int) $depth ) {
		return sklentr_nav_icon( $item->title ) . $title;
	}
	return $title;
}, 10, 4 );

/**
 * Resolve a page URL by slug, tolerating the numeric suffix an import is forced
 * to use when a slug collides ("privacy-policy-2"). Falls back to the tidy URL
 * so a link is never empty.
 *
 * @param string $slug Page slug, e.g. 'privacy-policy'.
 * @return string
 */
function sklentr_page_url( $slug ) {
	$page = get_page_by_path( $slug );

	// Nothing on the clean slug — look for the suffixed variant the importer made.
	if ( ! $page || 'publish' !== get_post_status( $page->ID ) ) {
		global $wpdb;
		$id = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT ID FROM {$wpdb->posts}
				 WHERE post_type = 'page' AND post_status = 'publish'
				   AND post_name REGEXP %s
				 ORDER BY ID ASC LIMIT 1",
				'^' . preg_quote( $slug, '' ) . '(-[0-9]+)?$'
			)
		);
		if ( $id ) {
			return get_permalink( (int) $id );
		}
	}

	return $page ? get_permalink( $page->ID ) : home_url( '/' . $slug . '/' );
}

/**
 * Fallback for the primary menu — renders sensible default links so the header
 * is usable before an admin assigns a menu under Appearance → Menus.
 * Only shown to logged-out visitors / when no menu is assigned.
 */
function sklentr_primary_menu_fallback() {
	// "Services" points to the standalone Services page (fall back to its slug
	// URL if the page isn't created yet). Homepage sections use absolute URLs so
	// they still work when the visitor is on another page (e.g. /services/).
	// Each item: [ href, label, is-current ].
	$items = array(
		array( sklentr_page_url( 'services' ),     __( 'Services', 'sklentr' ),     is_page( 'services' ) ),
		array( sklentr_page_url( 'startup-visa' ), __( 'Startup Visa', 'sklentr' ), is_page( 'startup-visa' ) ),
		array( sklentr_page_url( 'portfolio' ),    __( 'Portfolio', 'sklentr' ),    is_page( 'portfolio' ) ),
		array( sklentr_page_url( 'pricing' ),      __( 'Pricing', 'sklentr' ),      is_page( 'pricing' ) ),
		array( sklentr_page_url( 'about' ),        __( 'About', 'sklentr' ),        is_page( 'about' ) ),
		array( sklentr_page_url( 'blog' ),         __( 'Blog', 'sklentr' ),         is_page( 'blog' ) ),
	);

	echo '<ul id="primary-menu" class="nav-menu">';
	foreach ( $items as $item ) {
		list( $href, $label, $current ) = $item;
		$li_class = 'menu-item' . ( $current ? ' current-menu-item current_page_item' : '' );
		printf(
			'<li class="%1$s"><a href="%2$s"%3$s>%4$s%5$s</a></li>',
			esc_attr( $li_class ),
			esc_url( $href ),
			$current ? ' aria-current="page"' : '',
			sklentr_nav_icon( $label ), // phpcs:ignore WordPress.Security.EscapingOutput -- pre-escaped SVG.
			esc_html( $label )
		);
	}
	echo '</ul>';
}

/**
 * Render a simple footer link list from a list of [ href, label ] pairs.
 * Shared by the footer-column fallbacks so they match wp_nav_menu markup.
 * A list (not a map) so several items may share the same href.
 *
 * @param array  $items      List of array( href, label ) pairs.
 * @param string $menu_class CSS class for the <ul>.
 */
function sklentr_footer_menu_list( $items, $menu_class = 'footer-menu' ) {
	echo '<ul class="' . esc_attr( $menu_class ) . '">';
	foreach ( $items as $item ) {
		printf(
			'<li class="menu-item"><a href="%1$s">%2$s</a></li>',
			esc_url( $item[0] ),
			esc_html( $item[1] )
		);
	}
	echo '</ul>';
}

/**
 * Fallback for the footer "Services" column (Blueprint §6.3): the 6 services +
 * the Startup Visa wedge. Defaults deep-link to homepage sections so nothing
 * 404s before an admin assigns a menu under Appearance → Menus.
 */
function sklentr_footer_services_menu_fallback() {
	sklentr_footer_menu_list( array(
		array( home_url( '/#services' ),     __( 'MVP Development', 'sklentr' ) ),
		array( home_url( '/#services' ),     __( 'Website Design', 'sklentr' ) ),
		array( home_url( '/#services' ),     __( 'SEO & Marketing', 'sklentr' ) ),
		array( home_url( '/#services' ),     __( 'Paid Ads', 'sklentr' ) ),
		array( home_url( '/#services' ),     __( 'Video Production', 'sklentr' ) ),
		array( home_url( '/#services' ),     __( 'Business Consultation', 'sklentr' ) ),
		array( home_url( '/#startup-visa' ), __( 'Startup Visa', 'sklentr' ) ),
	), 'footer-menu' );
}

/**
 * Fallback for the footer "Company" column (Blueprint §6.3).
 */
function sklentr_footer_company_menu_fallback() {
	sklentr_footer_menu_list( array(
		array( sklentr_page_url( 'about' ),     __( 'About', 'sklentr' ) ),
		array( sklentr_page_url( 'portfolio' ), __( 'Portfolio', 'sklentr' ) ),
		array( sklentr_page_url( 'blog' ),      __( 'Blog', 'sklentr' ) ),
		array( home_url( '/#pricing' ),         __( 'Pricing', 'sklentr' ) ),
		array( home_url( '/#contact' ),         __( 'Contact', 'sklentr' ) ),
	), 'footer-menu' );
}

/**
 * Fallback for the footer "Resources" column (Ritovex-style Utilities column).
 */
function sklentr_footer_resources_menu_fallback() {
	sklentr_footer_menu_list( array(
		array( home_url( '/#startup-visa' ), __( 'Startup Visa', 'sklentr' ) ),
		array( home_url( '/#faq' ),          __( 'FAQ', 'sklentr' ) ),
		array( home_url( '/#how-we-work' ),  __( 'How We Work', 'sklentr' ) ),
		array( sklentr_page_url( 'privacy-policy' ), __( 'Privacy Policy', 'sklentr' ) ),
		array( sklentr_page_url( 'terms' ),          __( 'Terms', 'sklentr' ) ),
	), 'footer-menu' );
}

/**
 * Brand wordmark markup (used in the header and footer when no custom logo is set).
 *
 * @param bool $linked Whether to wrap the mark in a home link.
 * @return string Escaped, safe HTML.
 */
function sklentr_wordmark( $linked = true ) {
	$mark  = '<span class="site-logo__mark" aria-hidden="true">';
	$mark .= '<span class="site-logo__word">SKL</span>';
	$mark .= '<span class="site-logo__eq">&#8801;</span>';
	$mark .= '<span class="site-logo__word">NTR</span>';
	$mark .= '</span>';
	$mark .= '<span class="screen-reader-text">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';

	if ( ! $linked ) {
		return $mark;
	}

	return sprintf(
		'<a class="site-logo" href="%1$s" rel="home">%2$s</a>',
		esc_url( home_url( '/' ) ),
		$mark
	);
}

/**
 * Social icon links, built from the shared Sklentr social options.
 * Returns pre-escaped, safe markup (empty string if no socials are set).
 *
 * @param string $list_class CSS class for the wrapping <ul>.
 * @return string
 */
function sklentr_social_links( $list_class = 'social-links' ) {
	$socials = array(
		'linkedin'  => skl_opt( 'social_linkedin', '' ),
		'x'         => skl_opt( 'social_x', '' ),
		'facebook'  => skl_opt( 'social_facebook', '' ),
		'instagram' => skl_opt( 'social_instagram', '' ),
	);

	if ( ! array_filter( $socials ) ) {
		return '';
	}

	$line = 'viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"';
	$svg  = array(
		'linkedin'  => '<svg ' . $line . '><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>',
		'x'         => '<svg ' . $line . '><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>',
		'facebook'  => '<svg ' . $line . '><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
		'instagram' => '<svg ' . $line . '><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>',
	);
	$labels = array(
		'linkedin'  => __( 'LinkedIn', 'sklentr' ),
		'x'         => __( 'X', 'sklentr' ),
		'facebook'  => __( 'Facebook', 'sklentr' ),
		'instagram' => __( 'Instagram', 'sklentr' ),
	);

	$out = '<ul class="' . esc_attr( $list_class ) . '">';
	foreach ( $socials as $key => $url ) {
		if ( ! $url ) {
			continue;
		}
		$out .= sprintf(
			'<li><a class="%1$s__link" href="%2$s" target="_blank" rel="noopener noreferrer" aria-label="%3$s">%4$s</a></li>',
			esc_attr( $list_class ),
			esc_url( $url ),
			esc_attr( $labels[ $key ] ),
			$svg[ $key ] // Static trusted SVG.
		);
	}
	$out .= '</ul>';

	return $out;
}

/**
 * Giant decorative footer wordmark (Ritovex-style oversized brand text).
 * Purely decorative (aria-hidden); the real logo/home link lives above it.
 *
 * @param string $text Optional override; blank renders the SKL=NTR brand mark.
 * @return string Escaped, safe HTML.
 */
function sklentr_footer_megamark( $text = '' ) {
	$text = trim( (string) $text );

	if ( '' !== $text ) {
		// Split the custom word into per-character spans for the staggered reveal.
		$chars = preg_split( '//u', $text, -1, PREG_SPLIT_NO_EMPTY );
		$inner = '';
		foreach ( $chars as $n => $ch ) {
			$inner .= '<span class="footer-mega__part footer-mega__char" style="--i:' . (int) $n . '">' . esc_html( $ch ) . '</span>';
		}
	} else {
		// SKL (0,1,2) · three-bar "=" (3) · NTR (4,5,6) — each a step in the reveal.
		$inner = '';
		foreach ( array( 'S', 'K', 'L' ) as $n => $ch ) {
			$inner .= '<span class="footer-mega__part footer-mega__char" style="--i:' . (int) $n . '">' . esc_html( $ch ) . '</span>';
		}
		$inner .= '<span class="footer-mega__eq footer-mega__char" style="--i:3"><i></i><i></i><i></i></span>';
		foreach ( array( 'N', 'T', 'R' ) as $n => $ch ) {
			$inner .= '<span class="footer-mega__part footer-mega__char" style="--i:' . ( (int) $n + 4 ) . '">' . esc_html( $ch ) . '</span>';
		}
	}

	return '<div class="footer-mega" data-reveal aria-hidden="true">' . $inner . '</div>';
}
