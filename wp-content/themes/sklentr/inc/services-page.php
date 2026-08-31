<?php
/**
 * Sklentr — Services page support.
 *
 * (1) Backfills the richer per-service meta (category, price, timeline, full
 *     feature list) on the existing "Services" CPT posts using the live
 *     sklentr.com/services data — WITHOUT touching _skl_desc / _skl_tags, so the
 *     homepage §04 row-list stays byte-for-byte identical.
 * (2) Seeds the "Services — Why Us" points (skl_svc_perk) from the live copy.
 * (3) Ensures a published `services` page exists so page-services.php resolves.
 *
 * All content stays fully editable from wp-admin. Idempotent (version-flagged),
 * and every write only fills EMPTY values so admin edits are never overwritten.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fill a post-meta value only when it is currently empty (preserves admin edits).
 *
 * @param int    $post_id Post ID.
 * @param string $key     Meta key.
 * @param string $value   Value to set when empty.
 */
function sklentr_fill_meta( $post_id, $key, $value ) {
	if ( '' === (string) get_post_meta( $post_id, $key, true ) ) {
		update_post_meta( $post_id, $key, $value );
	}
}

add_action( 'init', function () {
	if ( get_option( 'sklentr_svcpage_v1' ) ) {
		return;
	}

	// (1) Per-service data mirrored from https://www.sklentr.com/services — keyed by title.
	$skl_svc = array(
		'MVP Development' => array(
			'cat'   => 'Web & Mobile Applications',
			'price' => '$5,000',
			'cur'   => 'CAD',
			'time'  => '2–8 weeks',
			'feat'  => "Web applications (Next.js, React)\nMobile apps (React Native, Flutter)\nAPI development & integrations\nDatabase architecture\nUser authentication & security\nAdmin dashboards",
		),
		'Website Design' => array(
			'cat'   => 'WordPress & Custom Development',
			'price' => '$2,500',
			'cur'   => 'CAD',
			'time'  => '1–4 weeks',
			'feat'  => "Custom website design\nWordPress development\nNext.js static sites\nE-commerce solutions\nLanding pages\nWebsite maintenance",
		),
		'SEO & Marketing' => array(
			'cat'   => 'Search & Social Media',
			'price' => '$1,500',
			'cur'   => '/month',
			'time'  => 'Ongoing',
			'feat'  => "Technical SEO audits\nOn-page optimization\nContent strategy\nSocial media management\nLink building\nLocal SEO",
		),
		'Paid Ads' => array(
			'cat'   => 'Google & Meta Ads',
			'price' => '$1,000',
			'cur'   => '/month + ad spend',
			'time'  => 'Ongoing',
			'feat'  => "Google Ads management\nMeta (Facebook/Instagram) Ads\nCampaign strategy\nA/B testing\nConversion tracking\nMonthly reporting",
		),
		'Video Production' => array(
			'cat'   => 'Promo & Social Content',
			'price' => '$1,500',
			'cur'   => 'CAD',
			'time'  => '1–2 weeks',
			'feat'  => "Promotional videos\nProduct demos\nSocial media content\nExplainer videos\nTestimonial videos\nMotion graphics",
		),
		'Business Consultation' => array(
			'cat'   => 'Strategy & Growth Planning',
			'price' => '$200',
			'cur'   => '/hour',
			'time'  => 'Flexible',
			'feat'  => "Product strategy\nMarket research\nTechnical roadmapping\nGrowth planning\nInvestor pitch prep\nMVP scoping",
		),
	);

	foreach ( get_posts( array( 'post_type' => 'skl_service', 'numberposts' => -1, 'post_status' => 'any' ) ) as $skl_s ) {
		if ( ! isset( $skl_svc[ $skl_s->post_title ] ) ) {
			continue;
		}
		$d = $skl_svc[ $skl_s->post_title ];
		sklentr_fill_meta( $skl_s->ID, '_skl_category', $d['cat'] );
		sklentr_fill_meta( $skl_s->ID, '_skl_price', $d['price'] );
		sklentr_fill_meta( $skl_s->ID, '_skl_currency', $d['cur'] );
		sklentr_fill_meta( $skl_s->ID, '_skl_timeline', $d['time'] );
		sklentr_fill_meta( $skl_s->ID, '_skl_features', $d['feat'] );
		sklentr_fill_meta( $skl_s->ID, '_skl_cta_link', '#contact' );
	}

	// (2) "Why Sklentr" points — seed once, only if none exist yet.
	$skl_have_perks = get_posts( array( 'post_type' => 'skl_svc_perk', 'numberposts' => 1, 'post_status' => 'any', 'fields' => 'ids' ) );
	if ( empty( $skl_have_perks ) ) {
		$skl_perks = array(
			array( 'title' => '2-Week MVPs',                  '_skl_icon' => 'bolt',   '_skl_desc' => 'Launch fast. Iterate faster. We’ve shipped products in as little as 14 days.' ),
			array( 'title' => 'One Team, Full Service',       '_skl_icon' => 'grid',   '_skl_desc' => 'Dev, design, SEO, marketing, video — no juggling vendors.' ),
			array( 'title' => 'Canadian Quality, Smart Pricing', '_skl_icon' => 'globe', '_skl_desc' => 'Toronto-managed, globally powered. Premium work without the premium markup.' ),
			array( 'title' => 'Built to Scale',               '_skl_icon' => 'scale',  '_skl_desc' => 'Real architecture, not duct tape. Your MVP becomes your product.' ),
			array( 'title' => 'AI-Powered Workflow',          '_skl_icon' => 'ai',     '_skl_desc' => 'We integrate Claude Code into our workflow. Faster, smarter builds.' ),
			array( 'title' => 'No Flight Risk',               '_skl_icon' => 'shield', '_skl_desc' => 'Unlike traditional outsourcing, our Canadian team owns your delivery.' ),
		);
		$order = 0;
		foreach ( $skl_perks as $skl_p ) {
			$title = $skl_p['title'];
			unset( $skl_p['title'] );
			$pid = wp_insert_post( array(
				'post_type'   => 'skl_svc_perk',
				'post_status' => 'publish',
				'post_title'  => $title,
				'menu_order'  => $order++,
			) );
			if ( $pid && ! is_wp_error( $pid ) ) {
				foreach ( $skl_p as $mk => $mv ) {
					update_post_meta( $pid, $mk, $mv );
				}
			}
		}
	}

	// (3) Ensure a published `services` page exists (page-services.php renders it).
	if ( ! get_page_by_path( 'services' ) ) {
		wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => 'Services',
			'post_name'    => 'services',
			'post_content' => '',
		) );
	}

	update_option( 'sklentr_svcpage_v1', 1 );
}, 21 );

/**
 * Tag the <body> on the Services page so its CSS can scope the full-bleed hero
 * (which paints behind the transparent fixed header).
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
add_filter( 'body_class', function ( $classes ) {
	if ( is_page( 'services' ) ) {
		$classes[] = 'svcpage';
	}
	if ( is_page( 'startup-visa' ) ) {
		$classes[] = 'svpage';
	}
	if ( is_page( 'portfolio' ) ) {
		$classes[] = 'portfoliopage';
	}
	if ( is_page( 'pricing' ) ) {
		$classes[] = 'pricingpage';
	}
	if ( is_page( 'about' ) ) {
		$classes[] = 'aboutpage';
	}
	if ( is_page( 'blog' ) ) {
		$classes[] = 'blogpage';
	}
	if ( is_single() ) {
		$classes[] = 'blogdetailpage';
	}
	return $classes;
} );

/**
 * Ensure a published `blog` page exists (page-blog.php renders it).
 * Content is settings-driven (Sklentr Settings → Blog Page). Idempotent.
 */
add_action( 'init', function () {
	if ( ! get_page_by_path( 'blog' ) ) {
		wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => 'Blog',
			'post_name'    => 'blog',
			'post_content' => '',
		) );
	}
}, 26 );


/**
 * Ensure a published `startup-visa` page exists (page-startup-visa.php renders
 * it). Idempotent — only creates it once.
 */
add_action( 'init', function () {
	if ( ! get_page_by_path( 'startup-visa' ) ) {
		wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => 'Startup Visa',
			'post_name'    => 'startup-visa',
			'post_content' => '',
		) );
	}
}, 22 );

/**
 * Ensure a published `portfolio` page exists (page-portfolio.php renders it).
 * The page's content is static (see the template), so nothing else is seeded.
 * Idempotent — only creates it once.
 */
add_action( 'init', function () {
	if ( ! get_page_by_path( 'portfolio' ) ) {
		wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => 'Portfolio',
			'post_name'    => 'portfolio',
			'post_content' => '',
		) );
	}
}, 23 );

/**
 * Ensure a published `pricing` page exists (page-pricing.php renders it).
 * Content is settings-driven (Sklentr Settings → Pricing Page). Idempotent.
 */
add_action( 'init', function () {
	if ( ! get_page_by_path( 'pricing' ) ) {
		wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => 'Pricing',
			'post_name'    => 'pricing',
			'post_content' => '',
		) );
	}
}, 24 );

/**
 * Ensure a published `about` page exists (page-about.php renders it).
 * Content is settings-driven (Sklentr Settings → About Page). Idempotent.
 */
add_action( 'init', function () {
	if ( ! get_page_by_path( 'about' ) ) {
		wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => 'About',
			'post_name'    => 'about',
			'post_content' => '',
		) );
	}
}, 25 );
