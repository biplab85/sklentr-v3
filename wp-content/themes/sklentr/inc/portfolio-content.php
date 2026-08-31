<?php
/**
 * Sklentr — Portfolio page content seeding.
 *
 * Seeds the five Portfolio Projects (skl_portfolio CPT) and the Portfolio-page
 * section text (pf_* option keys) once, from the values the template used to
 * hard-code. Idempotent + version-flagged; only fills empty settings and only
 * seeds the CPT when it has no posts. Front-end output is unchanged.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	if ( get_option( 'sklentr_pf_seed_v2' ) ) {
		return;
	}

	/* ---- 1) Seed the five portfolio projects (only if the CPT is empty). ---- */
	$have = get_posts( array( 'post_type' => 'skl_portfolio', 'numberposts' => 1, 'post_status' => 'any', 'fields' => 'ids' ) );
	if ( empty( $have ) ) {
		$projects = array(
			array(
				'title' => 'AI Farming', 'slug' => 'aifarming', 'tag1' => 'AgriTech', 'tag2' => 'AI', 'status' => 'In funding talks',
				'desc' => 'A comprehensive AI-based plant management system for urban farmers in Canada. From seed to harvest, the platform provides real-time recommendations based on location, weather, and plant status.',
				'challenge' => 'Urban farmers lack expertise and real-time guidance. Food waste is rampant, and local produce demand goes unmet.',
				'solution' => 'We created an end-to-end platform with AI-powered growing guides, a neighborhood marketplace for selling produce, and a vast plant database with regional growing data.',
				'results' => array( '3-month development', 'Currently in funding talks', 'Large plant database built', 'Community marketplace launched' ),
				'stack' => array( 'Laravel', 'Next.js', 'WordPress', 'Google Gemini' ),
			),
			array(
				'title' => 'Get Takaful', 'slug' => 'gettakaful', 'tag1' => 'FinTech', 'tag2' => 'Blockchain', 'status' => 'Launching soon',
				'desc' => 'A Shariah-compliant insurance alternative for Muslims in Canada. Built on blockchain for transparency, users see exactly where their money goes and can vote on claim approvals.',
				'challenge' => 'Muslims in Canada lack access to ethical, Shariah-compliant insurance options. Traditional insurance conflicts with Islamic principles.',
				'solution' => 'We built a transparent, community-driven Takaful platform on blockchain. Investments go only into halal businesses, and the community votes on approvals.',
				'results' => array( '#1 SEO rankings for Islamic insurance keywords', 'Weekly user inquiries', 'Launching soon', 'Strong community traction' ),
				'stack' => array( 'Laravel', 'Next.js', 'Blockchain', 'WordPress' ),
			),
			array(
				'title' => 'KindredCare', 'slug' => 'kindredcare', 'tag1' => 'Healthcare', 'tag2' => 'Elderly Care', 'status' => 'Onboarding caregivers',
				'desc' => 'An interactive marketplace connecting families with pre-vetted caregivers. Features AI-powered granular matching beyond basics — hobbies, interests, cultural similarities, food habits, even movie preferences — ensuring long-term care contracts.',
				'challenge' => 'Families struggle to find reliable, compatible caregivers. Traditional matching is surface-level and leads to short-term relationships and caregiver turnover.',
				'solution' => 'We built an intelligent matching platform with granular AI matching using ChatGPT and Gemini. Includes interview scheduling with guidance and fully customizable service packages.',
				'results' => array( '2.5-month development', 'MVP complete', 'Currently onboarding caregivers', 'Granular AI matching live' ),
				'stack' => array( 'Laravel', 'Next.js', 'ChatGPT', 'Google Gemini' ),
			),
			array(
				'title' => 'Agile Sourcing', 'slug' => 'agilesourcing', 'tag1' => 'Fashion', 'tag2' => 'Sustainable Design', 'status' => 'Platform launched',
				'desc' => 'Helps sustainable designers validate designs before production via social-media publishing and data analysis. AI-powered design creation and clothing-image generation with Instagram analytics to predict market success.',
				'challenge' => 'Sustainable fashion designers risk producing items that don’t sell. Without market validation, they waste resources on unpopular designs.',
				'solution' => 'We built a platform that generates AI clothing images, publishes to Instagram, and analyzes engagement (views, comments, shares) to predict market viability. Also includes supplier sourcing with sustainability verification.',
				'results' => array( '3-month development', 'Platform launched', 'Onboarding designers', 'Instagram validation active' ),
				'stack' => array( 'Laravel', 'Next.js', 'AI', 'Instagram API' ),
			),
			array(
				'title' => 'GAinData', 'slug' => 'gaindata', 'tag1' => 'SaaS', 'tag2' => 'Data Analytics', 'status' => 'MVP launched',
				'desc' => 'AI-powered data-intelligence platform solving the small-data problem for startups and SMEs. Features an AI Survey Generator, a Synthetic Data Engine, and a dual-dashboard experience for comprehensive data workflows.',
				'challenge' => 'Startups and SMEs lack quality data to make confident decisions. Small sample sizes, poor data quality, and inability to afford data scientists hold them back.',
				'solution' => 'We built a platform with an AI Survey Generator (creates questionnaires based on goals), a Synthetic Data Engine (expands small datasets), and a dual-dashboard for account and project insights.',
				'results' => array( '3-month development', 'MVP launched', 'Users actively using surveys', 'Scalable architecture ready' ),
				'stack' => array( 'Laravel', 'Next.js', 'ChatGPT', 'Gemini', 'Claude' ),
			),
		);
		$order = 0;
		foreach ( $projects as $p ) {
			$id = wp_insert_post( array(
				'post_type'   => 'skl_portfolio',
				'post_status' => 'publish',
				'post_title'  => $p['title'],
				'menu_order'  => $order++,
			) );
			if ( $id && ! is_wp_error( $id ) ) {
				update_post_meta( $id, '_skl_slug', $p['slug'] );
				update_post_meta( $id, '_skl_tag1', $p['tag1'] );
				update_post_meta( $id, '_skl_tag2', $p['tag2'] );
				update_post_meta( $id, '_skl_status', $p['status'] );
				update_post_meta( $id, '_skl_desc', $p['desc'] );
				update_post_meta( $id, '_skl_challenge', $p['challenge'] );
				update_post_meta( $id, '_skl_solution', $p['solution'] );
				update_post_meta( $id, '_skl_results', implode( "\n", $p['results'] ) );
				update_post_meta( $id, '_skl_stack', implode( "\n", $p['stack'] ) );
			}
		}
	}

	/* ---- 2) Seed the Portfolio-page section text (only empty keys). ---- */
	$opts     = get_option( 'sklentr_settings', array() );
	$defaults = array(
		// Hero.
		'pf_hero_lead'      => 'Ideas we’ve brought to',
		'pf_hero_accent'    => 'life',
		'pf_hero_sub'       => 'From healthcare AI to blockchain fintech, we’ve helped founders across industries launch products that matter. Here’s proof we deliver.',
		'pf_hero_cta1_text' => 'Start a Project',
		'pf_hero_cta1_link' => '#contact',
		'pf_hero_cta2_text' => 'See the Work',
		'pf_hero_cta2_link' => '#pf-featured',
		'pf_hero_collage'   => "gettakaful | Get Takaful | FinTech / Blockchain\nkindredcare | KindredCare | Elderly Care\ngaindata | GAinData | Data / SaaS",
		// Manifesto.
		'pf_man_l1'         => 'We build meaningful products and',
		'pf_man_l2'         => 'intuitive digital experiences — through',
		'pf_man_l3'         => 'strategy, craft & technology that ships.',
		'pf_man_accent'     => 'ships',
		'pf_man_link_text'  => 'How We Work',
		'pf_man_photos'     => "gettakaful\naifarming\nkindredcare\ngaindata",
		// Featured Works.
		'pf_feat_eyebrow'      => 'Case Studies',
		'pf_feat_title'        => 'Featured Works',
		'pf_feat_viewall_text' => 'View all work',
		'pf_feat_viewall_link' => '#contact',
		'pf_feat_challenge_label' => 'Challenge',
		'pf_feat_solution_label'  => 'Solution',
	);
	foreach ( $defaults as $k => $v ) {
		if ( ! isset( $opts[ $k ] ) || '' === $opts[ $k ] ) {
			$opts[ $k ] = $v;
		}
	}
	update_option( 'sklentr_settings', $opts );

	update_option( 'sklentr_pf_seed_v2', 1 );
}, 25 );
