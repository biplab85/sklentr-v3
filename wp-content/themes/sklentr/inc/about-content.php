<?php
/**
 * Sklentr — About page content seeding.
 *
 * Seeds the About-page section text (ab_* option keys) once, from the values the
 * template uses as inline defaults. Idempotent + version-flagged; only fills
 * empty keys, so front-end output is unchanged whether or not seeding has run
 * (skl_opt() falls back to the same defaults). Seeding just pre-fills the admin
 * fields under Sklentr Settings → "About Page" so they aren't blank.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	if ( get_option( 'sklentr_about_seed_v1' ) ) {
		return;
	}

	$opts     = get_option( 'sklentr_settings', array() );
	$defaults = array(
		// Hero.
		'ab_hero_eyebrow'    => 'About Sklentr',
		'ab_hero_lead'       => 'We’re',
		'ab_hero_accent'     => 'Sklentr',
		'ab_hero_sub'        => 'A Toronto-based MVP studio that helps founders launch faster. Canadian management, global talent, and a relentless focus on getting you to market.',
		'ab_hero_cta1_text'  => 'Book a Free Consultation',
		'ab_hero_cta1_link'  => 'https://calendly.com/sklentr',
		'ab_hero_cta2_text'  => 'See Our Work',
		'ab_hero_cta2_link'  => home_url( '/portfolio/' ),
		'ab_hero_pin1'       => 'Toronto',
		'ab_hero_pin2'       => 'Dhaka',
		'ab_hero_viz_kicker' => 'Est. 2023',
		'ab_hero_viz_note'   => 'Canadian management · Global talent',
		'ab_hero_stats'      => "50+ | Projects Delivered\n15+ | SUV MVPs Built\n2 | Offices Worldwide\n100% | Client Satisfaction",
		// Story.
		'ab_story_eyebrow'   => 'Our Story',
		'ab_story_title'     => 'Built by Founders, for Founders',
		'ab_story_badge'     => 'Est. 2023 · Toronto',
		'ab_story_body'      => "We started Sklentr because we lived the pain. As founders ourselves, we knew how hard it was to find reliable development partners who understood startup realities.\nMost agencies charge a fortune and take forever. Freelancers disappear or deliver broken code. We built Sklentr to be different — fast, transparent, and genuinely invested in your success.\nToday, we’ve helped 50+ founders launch their products. From healthcare AI to blockchain fintech, from Startup Visa applicants to funded startups — we build what matters.",
		'ab_story_image'      => '',
		// Values.
		'ab_val_eyebrow'     => 'Our Values',
		'ab_val_title'       => 'What We Stand For',
		'ab_val_intro'       => 'The principles behind every product we ship — how we work, what we protect, and why founders trust us with their vision.',
		'ab_val_image'       => '',
		'ab_val_items'       => "Speed Without Sacrifice | We move fast, but never at the expense of quality. Every line of code is built to last.\nFounder-First Mentality | We’ve been in your shoes. We build what you need to succeed, not what pads our invoice.\nRadical Transparency | No hidden fees. No surprises. You know exactly what you’re getting and when.\nOwnership & Accountability | Your success is our success. We don’t disappear after launch — we’re partners.",
		// Team.
		'ab_team_eyebrow'    => 'The Team',
		'ab_team_title'      => 'Meet the Team',
		'ab_team_members'    => "Rishad Wahid | Founder & CEO | Toronto, Canada | Serial entrepreneur with 10+ years building digital products. Passionate about helping founders bring their visions to life.\nDevelopment Team | Engineering | Dhaka, Bangladesh | World-class engineers specializing in React, Next.js, Laravel, and mobile development. Fast, reliable, and detail-oriented.\nDesign Team | UI/UX Design | Global | Creative designers who understand that great UX is invisible. We make complex simple and beautiful functional.\nMarketing Team | Growth & SEO | Toronto & Dhaka | Data-driven marketers who’ve helped startups rank #1 for competitive keywords. We don’t just build — we grow.",
		// Top Services (iteck-style slider).
		'ab_svc_title'       => 'Discover our top',
		'ab_svc_accent'      => 'services',
		'ab_svc_sub'         => 'Our strategy includes consistently evolving, to ensure we’re producing exceptional SEO for business.',
		'ab_svc_items'       => "16 | Content Strategy | You can provide the answers that your potential customers are trying to find, so you can become the industry.\n15 | Google/FB Ads | Get more website traffic, more customers & more online visibility with powerful SEO services.\n14 | Email Marketing | Your website has to impress your visitors within just a few seconds\n17 | Social Media | Get more website traffic, more customers for your social chanel\n18 | Website Design and Development | Your website has to impress your visitors within just a few seconds. If it runs slow, if it feels outdated\n19 | TVC/ Viral Clip | Optimized your website on google result with PPC Marketing\n20 | PPC Ads | Optimized your website on google result with PPC Marketing",
		'ab_svc_btn_text'    => 'See All Our Services',
		'ab_svc_btn_link'    => '/services/',
		// Offices.
		'ab_off_eyebrow'     => 'Our Offices',
		'ab_off_title'       => 'Global Presence',
		'ab_off_tagline'     => 'Canadian management. Global talent. The best of both worlds.',
		'ab_off_items'       => "Toronto, Canada | Headquarters | Client relationships, strategy, and project management\nDhaka, Bangladesh | Development Center | Engineering, design, and technical implementation",
	);
	foreach ( $defaults as $k => $v ) {
		if ( ! isset( $opts[ $k ] ) || '' === $opts[ $k ] ) {
			$opts[ $k ] = $v;
		}
	}
	update_option( 'sklentr_settings', $opts );

	update_option( 'sklentr_about_seed_v1', 1 );
}, 26 );
