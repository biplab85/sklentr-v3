<?php
/**
 * Sklentr — native Theme Options ("Sklentr Settings" admin page).
 * Stores singular section text (headings, eyebrows, CTAs) in one option array.
 * No plugin required. Read values in templates with skl_opt().
 *
 * Structure is two levels: PAGE → SECTION → fields. The admin renders each
 * page as a collapsible card containing collapsible sections. Field KEYS are
 * unchanged, so saved data and skl_opt() calls are unaffected by the grouping.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get a saved option value with a fallback default.
 *
 * @param string $key     Field key.
 * @param string $default Fallback when empty/unset.
 * @return string
 */
function skl_opt( $key, $default = '' ) {
	$opts = get_option( 'sklentr_settings', array() );
	return ( isset( $opts[ $key ] ) && '' !== $opts[ $key ] ) ? $opts[ $key ] : $default;
}

/**
 * Resolve a stored link. Root-relative paths (e.g. "/services") are routed
 * through home_url() so they respect this install's subdirectory; absolute
 * URLs, anchors, mailto:/tel: and protocol-relative links pass through as-is.
 *
 * @param string $link Stored link value.
 * @return string Resolved URL.
 */
function skl_resolve_link( $link ) {
	$link = trim( (string) $link );
	if ( '' === $link ) {
		return $link;
	}
	if ( preg_match( '~^(https?:)?//~i', $link ) || preg_match( '~^(#|mailto:|tel:)~i', $link ) ) {
		return $link;
	}
	if ( '/' === $link[0] ) {
		return home_url( $link );
	}
	return $link;
}

/**
 * Field definitions, grouped PAGE → SECTION → fields.
 * Each field: key => array( 'Label', 'text|textarea|url' ).
 *
 * @return array
 */
function sklentr_settings_fields() {
	return array(

		/* ============================ HOMEPAGE ============================ */
		'homepage' => array(
			'label'  => __( 'Homepage', 'sklentr' ),
			'groups' => array(
				'hero' => array(
					'label'  => __( 'Hero', 'sklentr' ),
					'fields' => array(
						'hero_eyebrow'         => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'hero_title_main'      => array( __( 'Headline — first part', 'sklentr' ), 'text' ),
						'hero_title_highlight' => array( __( 'Headline — highlighted (gold)', 'sklentr' ), 'text' ),
						'hero_title_strike'    => array( __( 'Headline — struck-through', 'sklentr' ), 'text' ),
						'hero_sub'             => array( __( 'Subheading', 'sklentr' ), 'textarea' ),
						'hero_cta1_text'       => array( __( 'Primary button — text', 'sklentr' ), 'text' ),
						'hero_cta1_link'       => array( __( 'Primary button — link', 'sklentr' ), 'text' ),
						'hero_cta2_text'       => array( __( 'Secondary button — text', 'sklentr' ), 'text' ),
						'hero_cta2_link'       => array( __( 'Secondary button — link', 'sklentr' ), 'text' ),
						'hero_note'            => array( __( 'Note under buttons', 'sklentr' ), 'text' ),
						'hero_chip_1'          => array( __( 'Floating chip 1', 'sklentr' ), 'text' ),
						'hero_chip_2'          => array( __( 'Floating chip 2', 'sklentr' ), 'text' ),
						'hero_chip_3'          => array( __( 'Floating chip 3 (green)', 'sklentr' ), 'text' ),
						'hero_chip_4'          => array( __( 'Floating chip 4 (gold)', 'sklentr' ), 'text' ),
						'hero_panel_title'     => array( __( 'Panel title', 'sklentr' ), 'text' ),
						'hero_badge_loading'   => array( __( 'Panel badge — loading', 'sklentr' ), 'text' ),
						'hero_badge_ok'        => array( __( 'Panel badge — done', 'sklentr' ), 'text' ),
					),
				),
				'trust' => array(
					'label'  => __( 'Trust Bar', 'sklentr' ),
					'fields' => array(
						'trust_heading'     => array( __( 'Screen-reader heading', 'sklentr' ), 'text' ),
						'trust_proof_label' => array( __( '“Trusted by” label', 'sklentr' ), 'text' ),
					),
				),
				'services' => array(
					'label'  => __( 'Services', 'sklentr' ),
					'fields' => array(
						'services_eyebrow'      => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'services_title'        => array( __( 'Heading — main', 'sklentr' ), 'text' ),
						'services_title_accent' => array( __( 'Heading — accent word (gradient)', 'sklentr' ), 'text' ),
						'services_intro'        => array( __( 'Intro paragraph (optional)', 'sklentr' ), 'textarea' ),
						'services_cta_text'     => array( __( 'CTA text', 'sklentr' ), 'text' ),
						'services_cta_link'     => array( __( 'CTA link', 'sklentr' ), 'text' ),
					),
				),
				'pillar' => array(
					'label'  => __( 'Why Sklentr', 'sklentr' ),
					'fields' => array(
						'pillar_eyebrow'  => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'pillar_title'    => array( __( 'Heading', 'sklentr' ), 'textarea' ),
						'pillar_intro'    => array( __( 'Intro paragraph', 'sklentr' ), 'textarea' ),
						'pillar_cta_text' => array( __( 'CTA text', 'sklentr' ), 'text' ),
						'pillar_cta_link' => array( __( 'CTA link', 'sklentr' ), 'text' ),
					),
				),
				'problem' => array(
					'label'  => __( 'Problem', 'sklentr' ),
					'fields' => array(
						'problem_eyebrow'  => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'problem_title'    => array( __( 'Heading', 'sklentr' ), 'textarea' ),
						'problem_intro'    => array( __( 'Intro paragraph', 'sklentr' ), 'textarea' ),
						'problem_cta_text' => array( __( 'CTA text', 'sklentr' ), 'text' ),
						'problem_cta_link' => array( __( 'CTA link', 'sklentr' ), 'text' ),
					),
				),
				'visa' => array(
					'label'  => __( 'Startup Visa Spotlight', 'sklentr' ),
					'fields' => array(
						'visa_eyebrow'      => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'visa_title'        => array( __( 'Headline — line 1', 'sklentr' ), 'text' ),
						'visa_title_accent' => array( __( 'Headline — line 2 (accent)', 'sklentr' ), 'text' ),
						'visa_body'         => array( __( 'Body paragraph', 'sklentr' ), 'textarea' ),
						'visa_cta_text'     => array( __( 'Button — text', 'sklentr' ), 'text' ),
						'visa_cta_link'     => array( __( 'Button — link', 'sklentr' ), 'text' ),
					),
				),
				'work' => array(
					'label'  => __( 'Featured Work', 'sklentr' ),
					'fields' => array(
						'work_eyebrow'  => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'work_title'    => array( __( 'Heading', 'sklentr' ), 'text' ),
						'work_intro'    => array( __( 'Intro paragraph', 'sklentr' ), 'textarea' ),
						'work_cta_text' => array( __( 'CTA text', 'sklentr' ), 'text' ),
						'work_cta_link' => array( __( 'CTA link', 'sklentr' ), 'text' ),
					),
				),
				'process' => array(
					'label'  => __( 'How We Work', 'sklentr' ),
					'fields' => array(
						'process_eyebrow'  => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'process_title'    => array( __( 'Heading', 'sklentr' ), 'text' ),
						'process_intro'    => array( __( 'Intro paragraph', 'sklentr' ), 'textarea' ),
						'process_cta_text' => array( __( 'CTA text', 'sklentr' ), 'text' ),
						'process_cta_link' => array( __( 'CTA link', 'sklentr' ), 'text' ),
					),
				),
				'pricing' => array(
					'label'  => __( 'Transparent Pricing', 'sklentr' ),
					'fields' => array(
						'pricing_eyebrow'      => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'pricing_title'        => array( __( 'Heading — main', 'sklentr' ), 'text' ),
						'pricing_title_accent' => array( __( 'Heading — accent (gold)', 'sklentr' ), 'text' ),
						'pricing_intro'        => array( __( 'Intro paragraph (optional)', 'sklentr' ), 'textarea' ),
						'pricing_note'         => array( __( 'Note under the cards', 'sklentr' ), 'textarea' ),
						'pricing_cta_text'     => array( __( 'CTA text', 'sklentr' ), 'text' ),
						'pricing_cta_link'     => array( __( 'CTA link', 'sklentr' ), 'text' ),
					),
				),
				'tech' => array(
					'label'  => __( 'Technology & AI', 'sklentr' ),
					'fields' => array(
						'tech_eyebrow'      => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'tech_title'        => array( __( 'Heading — main', 'sklentr' ), 'text' ),
						'tech_title_accent' => array( __( 'Heading — accent (gold)', 'sklentr' ), 'text' ),
						'tech_intro'        => array( __( 'Intro paragraph (optional)', 'sklentr' ), 'textarea' ),
						'tech_ai_title'     => array( __( 'AI band — title', 'sklentr' ), 'text' ),
						'tech_ai_note'      => array( __( 'AI band — note', 'sklentr' ), 'textarea' ),
						'tech_cta_text'     => array( __( 'CTA text', 'sklentr' ), 'text' ),
						'tech_cta_link'     => array( __( 'CTA link', 'sklentr' ), 'text' ),
					),
				),
				'about' => array(
					'label'  => __( 'About / Team', 'sklentr' ),
					'fields' => array(
						'about_eyebrow'      => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'about_title'        => array( __( 'Heading — main', 'sklentr' ), 'text' ),
						'about_title_accent' => array( __( 'Heading — accent (gold)', 'sklentr' ), 'text' ),
						'about_story'        => array( __( 'Story paragraph', 'sklentr' ), 'textarea' ),
						'founder_name'       => array( __( 'Founder — name', 'sklentr' ), 'text' ),
						'founder_role'       => array( __( 'Founder — role', 'sklentr' ), 'text' ),
						'founder_quote'      => array( __( 'Founder — quote', 'sklentr' ), 'textarea' ),
						'founder_photo'      => array( __( 'Founder — photo URL (blank = bundled)', 'sklentr' ), 'url' ),
						'about_hl_num'       => array( __( 'Highlight card — number', 'sklentr' ), 'text' ),
						'about_hl_title'     => array( __( 'Highlight card — title', 'sklentr' ), 'text' ),
						'about_hl_desc'      => array( __( 'Highlight card — description', 'sklentr' ), 'textarea' ),
						'about_cta_text'     => array( __( 'CTA text', 'sklentr' ), 'text' ),
						'about_cta_link'     => array( __( 'CTA link', 'sklentr' ), 'text' ),
						'about_follow_label' => array( __( 'Follow label', 'sklentr' ), 'text' ),
						'social_linkedin'    => array( __( 'LinkedIn URL', 'sklentr' ), 'text' ),
						'social_x'           => array( __( 'X (Twitter) URL', 'sklentr' ), 'text' ),
						'social_facebook'    => array( __( 'Facebook URL', 'sklentr' ), 'text' ),
						'social_instagram'   => array( __( 'Instagram URL', 'sklentr' ), 'text' ),
					),
				),
				'insights' => array(
					'label'  => __( 'Insights / Blog', 'sklentr' ),
					'fields' => array(
						'insights_eyebrow'      => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'insights_title'        => array( __( 'Heading — main', 'sklentr' ), 'text' ),
						'insights_title_accent' => array( __( 'Heading — accent (gold)', 'sklentr' ), 'text' ),
						'insights_intro'        => array( __( 'Intro paragraph (optional)', 'sklentr' ), 'textarea' ),
						'insights_cta_text'     => array( __( 'CTA text', 'sklentr' ), 'text' ),
						'insights_cta_link'     => array( __( 'CTA link', 'sklentr' ), 'text' ),
						'news_title'            => array( __( 'Newsletter — title', 'sklentr' ), 'text' ),
						'news_text'             => array( __( 'Newsletter — text', 'sklentr' ), 'textarea' ),
						'news_placeholder'      => array( __( 'Newsletter — input placeholder', 'sklentr' ), 'text' ),
						'news_button'           => array( __( 'Newsletter — button', 'sklentr' ), 'text' ),
						'news_success'          => array( __( 'Newsletter — success message', 'sklentr' ), 'text' ),
					),
				),
				'faq' => array(
					'label'  => __( 'FAQ', 'sklentr' ),
					'fields' => array(
						'faq_eyebrow'       => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'faq_title'         => array( __( 'Heading — main', 'sklentr' ), 'text' ),
						'faq_title_accent'  => array( __( 'Heading — accent (gold)', 'sklentr' ), 'text' ),
						'faq_intro'         => array( __( 'Intro paragraph', 'sklentr' ), 'textarea' ),
						'faq_help_title'    => array( __( 'Help panel — title', 'sklentr' ), 'text' ),
						'faq_help_text'     => array( __( 'Help panel — text', 'sklentr' ), 'textarea' ),
						'faq_help_cta_text' => array( __( 'Help panel — button text', 'sklentr' ), 'text' ),
						'faq_help_cta_link' => array( __( 'Help panel — button link', 'sklentr' ), 'text' ),
					),
				),
			),
		),

		/* ======================= SERVICES PAGE =========================== */
		'services_page' => array(
			'label'  => __( 'Services Page (/services)', 'sklentr' ),
			'groups' => array(
				'hero' => array(
					'label'  => __( 'Hero', 'sklentr' ),
					'fields' => array(
						'svc_hero_eyebrow'   => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'svc_hero_title'     => array( __( 'Heading (main)', 'sklentr' ), 'text' ),
						'svc_hero_accent'    => array( __( 'Heading accent (gold)', 'sklentr' ), 'text' ),
						'svc_hero_sub'       => array( __( 'Paragraph', 'sklentr' ), 'textarea' ),
						'svc_hero_cta1_text' => array( __( 'Primary button text', 'sklentr' ), 'text' ),
						'svc_hero_cta1_link' => array( __( 'Primary button link', 'sklentr' ), 'text' ),
						'svc_hero_cta2_text' => array( __( 'Secondary button text', 'sklentr' ), 'text' ),
						'svc_hero_cta2_link' => array( __( 'Secondary button link', 'sklentr' ), 'text' ),
					),
				),
				'grid' => array(
					'label'  => __( 'Services grid', 'sklentr' ),
					'fields' => array(
						'svc_list_eyebrow' => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'svc_list_title'   => array( __( 'Heading (main)', 'sklentr' ), 'text' ),
						'svc_list_accent'  => array( __( 'Heading accent (gold)', 'sklentr' ), 'text' ),
						'svc_list_intro'   => array( __( 'Intro', 'sklentr' ), 'textarea' ),
					),
				),
				'whyus' => array(
					'label'  => __( 'Why-Us', 'sklentr' ),
					'fields' => array(
						'svc_why_eyebrow' => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'svc_why_title'   => array( __( 'Heading (main)', 'sklentr' ), 'text' ),
						'svc_why_accent'  => array( __( 'Heading accent (gold)', 'sklentr' ), 'text' ),
						'svc_why_intro'   => array( __( 'Intro', 'sklentr' ), 'textarea' ),
					),
				),
				'process' => array(
					'label'  => __( 'Process', 'sklentr' ),
					'fields' => array(
						'svc_proc_eyebrow' => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'svc_proc_title'   => array( __( 'Heading (main)', 'sklentr' ), 'text' ),
						'svc_proc_accent'  => array( __( 'Heading accent (gold)', 'sklentr' ), 'text' ),
						'svc_proc_intro'   => array( __( 'Intro', 'sklentr' ), 'textarea' ),
					),
				),
			),
		),

		/* ====================== STARTUP VISA PAGE ======================== */
		'startup_visa' => array(
			'label'  => __( 'Startup Visa Page (/startup-visa)', 'sklentr' ),
			'groups' => array(
				'hero' => array(
					'label'  => __( 'Hero', 'sklentr' ),
					'fields' => array(
						'sv_hero_eyebrow' => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'sv_hero_title'   => array( __( 'Headline', 'sklentr' ), 'text' ),
						'sv_hero_body'    => array( __( 'Paragraph', 'sklentr' ), 'textarea' ),
						'sv_cta1_text'    => array( __( 'Primary button text', 'sklentr' ), 'text' ),
						'sv_cta1_link'    => array( __( 'Primary button link', 'sklentr' ), 'text' ),
						'sv_cta2_text'    => array( __( 'Secondary button text', 'sklentr' ), 'text' ),
						'sv_cta2_link'    => array( __( 'Secondary button link', 'sklentr' ), 'text' ),
						'sv_stats'        => array( __( 'Stats (one/line: number | suffix | label)', 'sklentr' ), 'textarea' ),
					),
				),
				'truth' => array(
					'label'  => __( 'Hard Truth', 'sklentr' ),
					'fields' => array(
						'sv_truth_title'  => array( __( 'Heading', 'sklentr' ), 'text' ),
						'sv_truth_intro'  => array( __( 'Intro', 'sklentr' ), 'textarea' ),
						'sv_truth_points' => array( __( 'Points (one per line)', 'sklentr' ), 'textarea' ),
					),
				),
				'why' => array(
					'label'  => __( 'Why It Matters', 'sklentr' ),
					'fields' => array(
						'sv_why_title'  => array( __( 'Heading', 'sklentr' ), 'text' ),
						'sv_why_points' => array( __( 'Points (one per line)', 'sklentr' ), 'textarea' ),
					),
				),
				'get' => array(
					'label'  => __( 'What You Get', 'sklentr' ),
					'fields' => array(
						'sv_get_title'    => array( __( 'Heading', 'sklentr' ), 'text' ),
						'sv_get_items'    => array( __( 'Items (Title | Description per line)', 'sklentr' ), 'textarea' ),
						'sv_get_cta_text' => array( __( 'Button text', 'sklentr' ), 'text' ),
						'sv_get_cta_link' => array( __( 'Button link', 'sklentr' ), 'text' ),
					),
				),
				'work' => array(
					'label'  => __( 'Track Record', 'sklentr' ),
					'fields' => array(
						'sv_work_title' => array( __( 'Heading', 'sklentr' ), 'text' ),
						'sv_work_items' => array( __( 'Cards (Name | Tag | Description per line)', 'sklentr' ), 'textarea' ),
					),
				),
				'visa' => array(
					'label'  => __( 'Visa Benefits', 'sklentr' ),
					'fields' => array(
						'sv_visa_title'  => array( __( 'Heading', 'sklentr' ), 'text' ),
						'sv_visa_badge'  => array( __( 'Badge', 'sklentr' ), 'text' ),
						'sv_visa_points' => array( __( 'Points (one per line)', 'sklentr' ), 'textarea' ),
					),
				),
				'fund' => array(
					'label'  => __( 'Funding Benefits', 'sklentr' ),
					'fields' => array(
						'sv_fund_title'  => array( __( 'Heading', 'sklentr' ), 'text' ),
						'sv_fund_points' => array( __( 'Points (one per line)', 'sklentr' ), 'textarea' ),
					),
				),
				'process' => array(
					'label'  => __( 'Process', 'sklentr' ),
					'fields' => array(
						'sv_proc_title'     => array( __( 'Heading', 'sklentr' ), 'text' ),
						'sv_proc_steps'     => array( __( 'Steps (Week | Title | Description per line)', 'sklentr' ), 'textarea' ),
						'sv_proc_guarantee' => array( __( 'Guarantee line', 'sklentr' ), 'textarea' ),
					),
				),
				'price' => array(
					'label'  => __( 'Pricing', 'sklentr' ),
					'fields' => array(
						'sv_price_title'    => array( __( 'Heading', 'sklentr' ), 'text' ),
						'sv_price_badge'    => array( __( 'Badge', 'sklentr' ), 'text' ),
						'sv_price_name'     => array( __( 'Package name', 'sklentr' ), 'text' ),
						'sv_price_amount'   => array( __( 'Amount', 'sklentr' ), 'text' ),
						'sv_price_currency' => array( __( 'Currency', 'sklentr' ), 'text' ),
						'sv_price_tagline'  => array( __( 'Tagline', 'sklentr' ), 'textarea' ),
						'sv_price_timeline' => array( __( 'Timeline', 'sklentr' ), 'text' ),
						'sv_price_note'     => array( __( 'Note under button', 'sklentr' ), 'text' ),
						'sv_price_compare'  => array( __( 'Comparison line', 'sklentr' ), 'textarea' ),
						'sv_price_included' => array( __( 'What\'s included (one per line)', 'sklentr' ), 'textarea' ),
						'sv_price_cta_text' => array( __( 'Button text', 'sklentr' ), 'text' ),
						'sv_price_cta_link' => array( __( 'Button link', 'sklentr' ), 'text' ),
					),
				),
				'testi' => array(
					'label'  => __( 'Testimonials', 'sklentr' ),
					'fields' => array(
						'sv_testi_title' => array( __( 'Heading', 'sklentr' ), 'text' ),
						'sv_testi_items' => array( __( 'Testimonials (Quote | Name | Role per line)', 'sklentr' ), 'textarea' ),
					),
				),
				'faq' => array(
					'label'  => __( 'FAQ', 'sklentr' ),
					'fields' => array(
						'sv_faq_title' => array( __( 'Heading', 'sklentr' ), 'text' ),
						'sv_faq_items' => array( __( 'FAQ (Question | Answer per line)', 'sklentr' ), 'textarea' ),
					),
				),
			),
		),

		/* ========================= PRICING PAGE ========================== */
		'pricing_page' => array(
			'label'  => __( 'Pricing Page (/pricing)', 'sklentr' ),
			'groups' => array(
				'hero' => array(
					'label'  => __( 'Hero', 'sklentr' ),
					'fields' => array(
						'pr_hero_eyebrow'   => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'pr_hero_title'     => array( __( 'Heading', 'sklentr' ), 'text' ),
						'pr_hero_accent'    => array( __( 'Heading accent (gold)', 'sklentr' ), 'text' ),
						'pr_hero_sub'       => array( __( 'Subheading', 'sklentr' ), 'textarea' ),
						'pr_hero_cta1_text' => array( __( 'Primary button text', 'sklentr' ), 'text' ),
						'pr_hero_cta1_link' => array( __( 'Primary button link', 'sklentr' ), 'text' ),
						'pr_hero_cta2_text' => array( __( 'Secondary button text', 'sklentr' ), 'text' ),
						'pr_hero_cta2_link' => array( __( 'Secondary button link', 'sklentr' ), 'text' ),
						'pr_hero_chips'     => array( __( 'Trust chips (one per line)', 'sklentr' ), 'textarea' ),
					),
				),
				'plans' => array(
					'label'  => __( 'Plans', 'sklentr' ),
					'fields' => array(
						'pr_plans_eyebrow' => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'pr_plans_title'   => array( __( 'Heading', 'sklentr' ), 'text' ),
						'pr_plans_intro'   => array( __( 'Intro', 'sklentr' ), 'textarea' ),
						'pr_plans'         => array( __( 'Plans', 'sklentr' ), 'plansrepeater' ),
					),
				),
				'guar' => array(
					'label'  => __( 'Guarantees', 'sklentr' ),
					'fields' => array(
						'pr_guar_eyebrow' => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'pr_guar_title'   => array( __( 'Heading', 'sklentr' ), 'text' ),
						'pr_guar_items'   => array( __( 'Guarantees — title & text', 'sklentr' ), 'repeater', array( 'Title', 'Text' ) ),
					),
				),
				'faq' => array(
					'label'  => __( 'FAQ', 'sklentr' ),
					'fields' => array(
						'pr_faq_title' => array( __( 'Heading', 'sklentr' ), 'text' ),
						'pr_faq_items' => array( __( 'FAQ — questions & answers', 'sklentr' ), 'repeater', array( 'Question', 'Answer' ) ),
						'pr_faq_eyebrow'       => array( __( 'Help card — eyebrow (“FAQ”)', 'sklentr' ), 'text' ),
						'pr_faq_help_title'    => array( __( 'Help card — title', 'sklentr' ), 'text' ),
						'pr_faq_help_text'     => array( __( 'Help card — text', 'sklentr' ), 'textarea' ),
						'pr_faq_help_cta_text' => array( __( 'Help card — button text', 'sklentr' ), 'text' ),
						'pr_faq_help_cta_link' => array( __( 'Help card — button link', 'sklentr' ), 'text' ),
					),
				),
			),
		),

		/* ======================= PORTFOLIO PAGE ========================== */
		'portfolio_page' => array(
			'label'  => __( 'Portfolio Page (/portfolio)', 'sklentr' ),
			'groups' => array(
				'hero' => array(
					'label'  => __( 'Hero', 'sklentr' ),
					'fields' => array(
						'pf_hero_lead'      => array( __( 'Headline (lead text)', 'sklentr' ), 'text' ),
						'pf_hero_accent'    => array( __( 'Headline accent word (gold)', 'sklentr' ), 'text' ),
						'pf_hero_sub'       => array( __( 'Subheading', 'sklentr' ), 'textarea' ),
						'pf_hero_cta1_text' => array( __( 'Primary button text', 'sklentr' ), 'text' ),
						'pf_hero_cta1_link' => array( __( 'Primary button link', 'sklentr' ), 'text' ),
						'pf_hero_cta2_text' => array( __( 'Secondary button text', 'sklentr' ), 'text' ),
						'pf_hero_cta2_link' => array( __( 'Secondary button link', 'sklentr' ), 'text' ),
						'pf_hero_collage'   => array( __( 'Collage thumbnails (Image-slug | Name | Tag per line)', 'sklentr' ), 'textarea' ),
					),
				),
				'manifesto' => array(
					'label'  => __( 'Manifesto', 'sklentr' ),
					'fields' => array(
						'pf_man_l1'        => array( __( 'Statement line 1', 'sklentr' ), 'text' ),
						'pf_man_l2'        => array( __( 'Statement line 2', 'sklentr' ), 'text' ),
						'pf_man_l3'        => array( __( 'Statement line 3', 'sklentr' ), 'text' ),
						'pf_man_accent'    => array( __( 'Accent word in line 3 (gold)', 'sklentr' ), 'text' ),
						'pf_man_link_text' => array( __( 'Link text', 'sklentr' ), 'text' ),
						'pf_man_link_url'  => array( __( 'Link URL', 'sklentr' ), 'text' ),
						'pf_man_photos'    => array( __( 'Fanning photos (one image-slug per line)', 'sklentr' ), 'textarea' ),
					),
				),
				'featured' => array(
					'label'  => __( 'Featured Works (projects live under “Portfolio Projects”)', 'sklentr' ),
					'fields' => array(
						'pf_feat_eyebrow'      => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'pf_feat_title'        => array( __( 'Heading', 'sklentr' ), 'text' ),
						'pf_feat_viewall_text' => array( __( 'View-all link text', 'sklentr' ), 'text' ),
						'pf_feat_viewall_link' => array( __( 'View-all link URL', 'sklentr' ), 'text' ),
						'pf_feat_challenge_label' => array( __( '“Challenge” label', 'sklentr' ), 'text' ),
						'pf_feat_solution_label'  => array( __( '“Solution” label', 'sklentr' ), 'text' ),
					),
				),
			),
		),

		/* ========================= ABOUT PAGE ============================ */
		'about_page' => array(
			'label'  => __( 'About Page (/about)', 'sklentr' ),
			'groups' => array(
				'hero' => array(
					'label'  => __( 'Hero', 'sklentr' ),
					'fields' => array(
						'ab_hero_eyebrow'    => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'ab_hero_lead'       => array( __( 'Heading — lead text', 'sklentr' ), 'text' ),
						'ab_hero_accent'     => array( __( 'Heading — accent word (gold)', 'sklentr' ), 'text' ),
						'ab_hero_sub'        => array( __( 'Subheading', 'sklentr' ), 'textarea' ),
						'ab_hero_cta1_text'  => array( __( 'Primary button text', 'sklentr' ), 'text' ),
						'ab_hero_cta1_link'  => array( __( 'Primary button link', 'sklentr' ), 'text' ),
						'ab_hero_cta2_text'  => array( __( 'Secondary button text', 'sklentr' ), 'text' ),
						'ab_hero_cta2_link'  => array( __( 'Secondary button link', 'sklentr' ), 'text' ),
						'ab_hero_pin1'       => array( __( 'Map pin 1 label (e.g. Toronto)', 'sklentr' ), 'text' ),
						'ab_hero_pin2'       => array( __( 'Map pin 2 label (e.g. Dhaka)', 'sklentr' ), 'text' ),
						'ab_hero_viz_kicker' => array( __( 'Card kicker (e.g. Est. 2023)', 'sklentr' ), 'text' ),
						'ab_hero_viz_note'   => array( __( 'Card note', 'sklentr' ), 'text' ),
						'ab_hero_stats'      => array( __( 'Stats — number & label', 'sklentr' ), 'repeater', array( 'Number', 'Label' ) ),
					),
				),
				'story' => array(
					'label'  => __( 'Our Story', 'sklentr' ),
					'fields' => array(
						'ab_story_eyebrow'    => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'ab_story_title'      => array( __( 'Heading (last word is accent-coloured)', 'sklentr' ), 'text' ),
						'ab_story_badge'      => array( __( 'Badge (e.g. Est. 2023 · Toronto)', 'sklentr' ), 'text' ),
						'ab_story_body'       => array( __( 'Body — one paragraph per line', 'sklentr' ), 'textarea' ),
						'ab_story_image'      => array( __( 'Photo URL (blank = bundled)', 'sklentr' ), 'url' ),
					),
				),
				'values' => array(
					'label'  => __( 'Values', 'sklentr' ),
					'fields' => array(
						'ab_val_eyebrow' => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'ab_val_title'   => array( __( 'Heading', 'sklentr' ), 'text' ),
						'ab_val_intro'   => array( __( 'Intro paragraph', 'sklentr' ), 'textarea' ),
						'ab_val_image'   => array( __( 'Left image URL (blank = bundled)', 'sklentr' ), 'url' ),
						'ab_val_items'   => array( __( 'Values — title & text', 'sklentr' ), 'repeater', array( 'Title', 'Text' ) ),
					),
				),
				'team' => array(
					'label'  => __( 'Team', 'sklentr' ),
					'fields' => array(
						'ab_team_eyebrow' => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'ab_team_title'   => array( __( 'Heading', 'sklentr' ), 'text' ),
						'ab_team_members' => array( __( 'Members (Name | Role | Location | Bio per line)', 'sklentr' ), 'textarea' ),
					),
				),
				'topservices' => array(
					'label'  => __( 'Top Services (slider)', 'sklentr' ),
					'fields' => array(
						'ab_svc_title'    => array( __( 'Heading (before accent)', 'sklentr' ), 'text' ),
						'ab_svc_accent'   => array( __( 'Heading accent word', 'sklentr' ), 'text' ),
						'ab_svc_sub'      => array( __( 'Subtitle', 'sklentr' ), 'textarea' ),
						'ab_svc_items'    => array( __( 'Cards (Icon-slug | Title | Description per line)', 'sklentr' ), 'textarea' ),
						'ab_svc_btn_text' => array( __( 'Button text', 'sklentr' ), 'text' ),
						'ab_svc_btn_link' => array( __( 'Button link', 'sklentr' ), 'text' ),
					),
				),
				'offices' => array(
					'label'  => __( 'Offices', 'sklentr' ),
					'fields' => array(
						'ab_off_eyebrow' => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'ab_off_title'   => array( __( 'Heading', 'sklentr' ), 'text' ),
						'ab_off_items'   => array( __( 'Offices (City | Tag | Description per line)', 'sklentr' ), 'textarea' ),
						'ab_off_tagline' => array( __( 'Closing tagline', 'sklentr' ), 'text' ),
					),
				),
			),
		),

				/* ========================= BLOG PAGE ============================= */
		'blog_page' => array(
			'label'  => __( 'Blog Page (/blog)', 'sklentr' ),
			'groups' => array(
				'hero' => array(
					'label'  => __( 'Hero', 'sklentr' ),
					'fields' => array(
						'bl_hero_eyebrow' => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'bl_hero_lead'    => array( __( 'Heading — lead text', 'sklentr' ), 'text' ),
						'bl_hero_accent'  => array( __( 'Heading — accent word (gradient)', 'sklentr' ), 'text' ),
						'bl_hero_sub'     => array( __( 'Subheading', 'sklentr' ), 'textarea' ),
					),
				),
				'posts' => array(
					'label'  => __( 'Posts', 'sklentr' ),
					'fields' => array(
						'bl_posts_title' => array( __( 'Section heading', 'sklentr' ), 'text' ),
						'bl_more_text'   => array( __( 'Load-more button text', 'sklentr' ), 'text' ),
					),
				),
			),
		),

				/* ====================== GLOBAL (all pages) ======================= */
		'global' => array(
			'label'  => __( 'Global (all pages)', 'sklentr' ),
			'groups' => array(
				'cta' => array(
					'label'  => __( 'Final CTA Band', 'sklentr' ),
					'fields' => array(
						'cta_eyebrow'      => array( __( 'Eyebrow', 'sklentr' ), 'text' ),
						'cta_title'        => array( __( 'Heading — main', 'sklentr' ), 'text' ),
						'cta_title_accent' => array( __( 'Heading — accent (gold)', 'sklentr' ), 'text' ),
						'cta_subtitle'     => array( __( 'Subheading', 'sklentr' ), 'textarea' ),
						'cta_points'       => array( __( 'Reassurance points (one per line)', 'sklentr' ), 'textarea' ),
						'cta_primary_text' => array( __( 'Primary button — text', 'sklentr' ), 'text' ),
						'cta_primary_link' => array( __( 'Primary button — link', 'sklentr' ), 'text' ),
						'cta_email'        => array( __( 'Contact email', 'sklentr' ), 'text' ),
						'cta_phone'        => array( __( 'Contact phone', 'sklentr' ), 'text' ),
						'cta_whatsapp'     => array( __( 'WhatsApp link', 'sklentr' ), 'text' ),
					),
				),
				'footer' => array(
					'label'  => __( 'Footer (mega)', 'sklentr' ),
					'fields' => array(
						'footer_news_title'    => array( __( 'Newsletter — big heading', 'sklentr' ), 'textarea' ),
						'footer_col_services'  => array( __( 'Column 1 — title', 'sklentr' ), 'text' ),
						'footer_col_company'   => array( __( 'Column 2 — title', 'sklentr' ), 'text' ),
						'footer_col_resources' => array( __( 'Column 3 — title', 'sklentr' ), 'text' ),
						'footer_contact_title' => array( __( 'Contact column — title', 'sklentr' ), 'text' ),
						'footer_address'       => array( __( 'Contact — address', 'sklentr' ), 'textarea' ),
						'footer_credit'        => array( __( 'Bottom credit line (blank = auto ©)', 'sklentr' ), 'text' ),
						'footer_follow_label'  => array( __( 'Social — “Follow Us” label', 'sklentr' ), 'text' ),
						'footer_megamark'      => array( __( 'Giant wordmark text (blank = SKL=NTR)', 'sklentr' ), 'text' ),
					),
				),
			),
		),
	);
}

/**
 * Register the settings + sanitize callback.
 */
add_action( 'admin_init', function () {
	register_setting(
		'sklentr_settings_group',
		'sklentr_settings',
		array( 'sanitize_callback' => 'sklentr_settings_sanitize' )
	);
} );

/**
 * Sanitize every field by its declared type (walks Page → Section → fields).
 *
 * @param array $input Raw input.
 * @return array
 */
function sklentr_settings_sanitize( $input ) {
	$input  = is_array( $input ) ? $input : array();
	$output = get_option( 'sklentr_settings', array() ); // preserve keys not in this form.

	foreach ( sklentr_settings_fields() as $page ) {
		foreach ( $page['groups'] as $section ) {
			foreach ( $section['fields'] as $key => $field ) {
				$type = $field[1];
				$val  = isset( $input[ $key ] ) ? wp_unslash( $input[ $key ] ) : '';

				if ( 'url' === $type ) {
					$output[ $key ] = esc_url_raw( $val );
				} elseif ( 'textarea' === $type || 'repeater' === $type || 'plansrepeater' === $type ) {
					$output[ $key ] = sanitize_textarea_field( $val );
				} else {
					$output[ $key ] = sanitize_text_field( $val );
				}
			}
		}
	}
	return $output;
}

/**
 * Add the admin menu page.
 */
add_action( 'admin_menu', function () {
	add_menu_page(
		__( 'Sklentr Settings', 'sklentr' ),
		__( 'Sklentr', 'sklentr' ),
		'manage_options',
		'sklentr-settings',
		'sklentr_settings_page',
		'dashicons-admin-customizer',
		59
	);
	add_submenu_page(
		'sklentr-settings',
		__( 'Sklentr Settings', 'sklentr' ),
		__( 'Settings', 'sklentr' ),
		'manage_options',
		'sklentr-settings',
		'sklentr_settings_page'
	);
} );

/**
 * Keep the theme's own Settings page as the first, clearly-labelled item in the
 * nested Sklentr submenu (the CPTs attach to this parent via show_in_menu).
 */
add_action( 'admin_menu', function () {
	global $submenu;
	if ( empty( $submenu['sklentr-settings'] ) ) {
		return;
	}
	$settings = null;
	foreach ( $submenu['sklentr-settings'] as $i => $item ) {
		if ( isset( $item[2] ) && 'sklentr-settings' === $item[2] ) {
			$item[0] = __( 'Settings', 'sklentr' );
			$settings = $item;
			unset( $submenu['sklentr-settings'][ $i ] );
			break;
		}
	}
	if ( $settings ) {
		array_unshift( $submenu['sklentr-settings'], $settings );
	}
	$submenu['sklentr-settings'] = array_values( $submenu['sklentr-settings'] );
}, 999 );

/**
 * Render the settings page (Page cards → Section cards → fields).
 */
function sklentr_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$opts = get_option( 'sklentr_settings', array() );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Sklentr Settings', 'sklentr' ); ?></h1>
		<p><?php esc_html_e( 'Content is grouped by page, then by section. Open a page, then a section, to edit its text. Repeatable content (stats, cards, etc.) is managed under its own menu.', 'sklentr' ); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'sklentr_settings_group' ); ?>

			<p class="skl-opt-tools">
				<button type="button" class="button" data-skl-expand>Expand all</button>
				<button type="button" class="button" data-skl-collapse>Collapse all</button>
			</p>
			<style>
			.skl-opt-page{border:1px solid #c3c4c7;border-radius:10px;background:#f6f7f7;margin:0 0 14px;max-width:960px;overflow:hidden}
			.skl-opt-page>.skl-opt-page__head{cursor:pointer;list-style:none;display:flex;align-items:center;gap:12px;padding:15px 18px;font-size:16px;font-weight:700;color:#1d2327;background:#eef0f1}
			.skl-opt-page>.skl-opt-page__head:hover{background:#e8eaec}
			.skl-opt-page__head::-webkit-details-marker{display:none}
			.skl-opt-page__head::after{content:"\25B8";margin-left:6px;color:#50575e;transition:transform .15s ease}
			.skl-opt-page[open]>.skl-opt-page__head::after{transform:rotate(90deg)}
			.skl-opt-page__body{padding:12px 14px}
			.skl-opt-page__count{margin-left:auto;font-weight:400;font-size:12px;color:#646970}
			.skl-opt-sec{border:1px solid #dcdcde;border-radius:8px;background:#fff;margin:0 0 10px}
			.skl-opt-sec:last-child{margin-bottom:0}
			.skl-opt-sec>.skl-opt-sec__head{cursor:pointer;list-style:none;display:flex;align-items:center;gap:12px;padding:12px 16px;font-size:14px;font-weight:600;color:#1d2327}
			.skl-opt-sec>.skl-opt-sec__head:hover{background:#f6f7f7}
			.skl-opt-sec__head::-webkit-details-marker{display:none}
			.skl-opt-sec__head::after{content:"\25B8";margin-left:6px;color:#787c82;transition:transform .15s ease}
			.skl-opt-sec[open]>.skl-opt-sec__head::after{transform:rotate(90deg)}
			.skl-opt-sec[open]>.skl-opt-sec__head{border-bottom:1px solid #f0f0f1}
			.skl-opt-sec__count{margin-left:auto;font-weight:400;font-size:12px;color:#787c82}
			.skl-opt-sec .form-table{margin:0;padding:4px 16px 12px}
			.skl-opt-tools{margin:0 0 16px}
			</style>
			<script>
			(function(){
				function all(o){document.querySelectorAll('.skl-opt-page,.skl-opt-sec').forEach(function(d){d.open=o;});}
				var e=document.querySelector('[data-skl-expand]'),c=document.querySelector('[data-skl-collapse]');
				if(e){e.addEventListener('click',function(){all(true);});}
				if(c){c.addEventListener('click',function(){all(false);});}
			})();
			</script>
			<style>
			.skl-faqrep__row{display:flex;gap:8px;align-items:flex-start;margin:0 0 8px;padding:10px;background:#fff;border:1px solid #dcdcde;border-radius:6px}
			.skl-faqrep__q{flex:0 0 32%}
			.skl-faqrep__a{flex:1 1 auto;min-height:54px}
			.skl-faqrep__del{flex:0 0 auto;color:#b32d2e;font-size:18px;line-height:1;height:32px;min-width:34px}
			.skl-faqrep__add{margin-top:2px}
			</style>
			<script>
			document.addEventListener('DOMContentLoaded',function(){
				document.querySelectorAll('[data-skl-faqrep]').forEach(function(root){
					var store=root.querySelector('[data-skl-faqrep-store]'),rows=root.querySelector('.skl-faqrep__rows'),addBtn=root.querySelector('.skl-faqrep__add');var c1=root.getAttribute('data-c1')||'Question',c2=root.getAttribute('data-c2')||'Answer';
					function serialize(){var out=[];rows.querySelectorAll('.skl-faqrep__row').forEach(function(r){var q=r.querySelector('.skl-faqrep__q').value.trim(),a=r.querySelector('.skl-faqrep__a').value.trim();if(q||a){out.push(q+' | '+a);}});store.value=out.join(String.fromCharCode(10));}
					function makeRow(q,a){var row=document.createElement('div');row.className='skl-faqrep__row';var qi=document.createElement('input');qi.type='text';qi.className='skl-faqrep__q regular-text';qi.placeholder=c1;qi.value=q||'';var ai=document.createElement('textarea');ai.className='skl-faqrep__a large-text';ai.rows=2;ai.placeholder=c2;ai.value=a||'';var del=document.createElement('button');del.type='button';del.className='button skl-faqrep__del';del.title='Remove';del.innerHTML='&minus;';row.appendChild(qi);row.appendChild(ai);row.appendChild(del);rows.appendChild(row);return row;}
					(store.value||'').split(/\r?\n/).forEach(function(line){if(!line.trim()){return;}var i=line.indexOf('|');makeRow(i>=0?line.slice(0,i).trim():line.trim(),i>=0?line.slice(i+1).trim():'');});
					if(!rows.children.length){makeRow('','');}
					addBtn.addEventListener('click',function(){var r=makeRow('','');r.querySelector('.skl-faqrep__q').focus();serialize();});
					rows.addEventListener('input',serialize);
					rows.addEventListener('click',function(e){var d=e.target.closest('.skl-faqrep__del');if(d){d.closest('.skl-faqrep__row').remove();serialize();}});
					serialize();
				});
			});
			</script>

			<style>
.skl-plan{border:1px solid #c3c4c7;border-radius:8px;background:#fff;padding:12px;margin:0 0 12px}
.skl-plan__grid{display:grid;grid-template-columns:1fr 1fr;gap:8px 12px}
.skl-plan__f{display:flex;flex-direction:column;font-size:12px;color:#50575e;gap:3px}
.skl-plan__f.wide{grid-column:1 / -1}
.skl-plan__feats{margin-top:10px;padding-top:10px;border-top:1px solid #f0f0f1}
.skl-plan__feats-lbl{display:block;font-size:12px;font-weight:600;color:#50575e;margin-bottom:6px}
.skl-plan__featrow{display:flex;align-items:center;gap:8px;margin-bottom:6px}
.skl-plan__feat-text{flex:1 1 auto}
.skl-plan__feat-exc-lab{flex:0 0 auto;font-size:12px;color:#50575e;white-space:nowrap}
.skl-plan__feat-del{flex:0 0 auto;color:#b32d2e;min-width:32px}
.skl-plan__del{margin-top:10px;color:#b32d2e}
.skl-plans__add{margin-top:2px}
</style>
<script>
document.addEventListener('DOMContentLoaded',function(){
	document.querySelectorAll('[data-skl-plans]').forEach(function(root){
		var store=root.querySelector('[data-skl-plans-store]');
		var list=root.querySelector('.skl-plans__list');
		var addBtn=root.querySelector('.skl-plans__add');
		var COLS=[['name','Name'],['price','Price'],['range','Range'],['timeline','Timeline'],['badge','Badge (blank = normal card)'],['ctaText','CTA text'],['ctaLink','CTA link'],['tagline','Tagline']];
		function ce(t,c){var e=document.createElement(t);if(c){e.className=c;}return e;}
		function featRow(text,exc){
			var r=ce('div','skl-plan__featrow');
			var ti=ce('input','skl-plan__feat-text regular-text');ti.type='text';ti.placeholder='Feature';ti.value=text||'';
			var lab=ce('label','skl-plan__feat-exc-lab');
			var cb=ce('input','skl-plan__feat-exc');cb.type='checkbox';cb.checked=!!exc;
			lab.appendChild(cb);lab.appendChild(document.createTextNode(' excluded'));
			var del=ce('button','button skl-plan__feat-del');del.type='button';del.title='Remove feature';del.innerHTML='&minus;';
			r.appendChild(ti);r.appendChild(lab);r.appendChild(del);
			return r;
		}
		function planCard(p){
			p=p||{};
			var card=ce('div','skl-plan');
			var grid=ce('div','skl-plan__grid');
			COLS.forEach(function(c){
				var lab=ce('label','skl-plan__f'+(c[0]==='tagline'?' wide':''));
				lab.appendChild(document.createTextNode(c[1]));
				var inp=ce('input','regular-text');inp.type='text';inp.setAttribute('data-k',c[0]);inp.value=p[c[0]]||'';
				lab.appendChild(inp);grid.appendChild(lab);
			});
			card.appendChild(grid);
			var fwrap=ce('div','skl-plan__feats');
			var flab=ce('span','skl-plan__feats-lbl');flab.textContent='Features (tick “excluded” to show it struck-through)';fwrap.appendChild(flab);
			var frows=ce('div','skl-plan__featrows');fwrap.appendChild(frows);
			(p.feats||[]).forEach(function(fe){frows.appendChild(featRow(fe.text,fe.exc));});
			var fadd=ce('button','button skl-plan__featadd');fadd.type='button';fadd.textContent='+ Add feature';fwrap.appendChild(fadd);
			card.appendChild(fwrap);
			var del=ce('button','button skl-plan__del');del.type='button';del.textContent='Remove plan';card.appendChild(del);
			list.appendChild(card);
			return card;
		}
		function serialize(){
			var lines=[];
			list.querySelectorAll('.skl-plan').forEach(function(card){
				var v={};COLS.forEach(function(c){v[c[0]]=(card.querySelector('[data-k="'+c[0]+'"]').value||'').trim();});
				var feats=[];
				card.querySelectorAll('.skl-plan__featrow').forEach(function(fr){
					var t=(fr.querySelector('.skl-plan__feat-text').value||'').trim();
					if(!t){return;}
					feats.push((fr.querySelector('.skl-plan__feat-exc').checked?'!':'')+t);
				});
				lines.push([v.name,v.price,v.range,v.timeline,v.badge,v.tagline,v.ctaText,v.ctaLink,feats.join(';')].join(' | '));
			});
			store.value=lines.join('\n');
		}
		function parse(){
			return (store.value||'').split(/\r?\n/).filter(function(l){return l.trim();}).map(function(line){
				var parts=line.split('|').map(function(s){return s.trim();});
				var feats=(parts[8]||'').split(';').map(function(s){return s.trim();}).filter(Boolean).map(function(s){
					var exc=s.charAt(0)==='!';return {text:exc?s.slice(1).trim():s,exc:exc};
				});
				return {name:parts[0]||'',price:parts[1]||'',range:parts[2]||'',timeline:parts[3]||'',badge:parts[4]||'',tagline:parts[5]||'',ctaText:parts[6]||'',ctaLink:parts[7]||'',feats:feats};
			});
		}
		parse().forEach(planCard);
		if(!list.children.length){planCard({});}
		addBtn.addEventListener('click',function(){planCard({feats:[]});serialize();});
		list.addEventListener('input',serialize);
		list.addEventListener('change',serialize);
		list.addEventListener('click',function(e){
			if(e.target.closest('.skl-plan__del')){e.target.closest('.skl-plan').remove();serialize();return;}
			if(e.target.closest('.skl-plan__feat-del')){e.target.closest('.skl-plan__featrow').remove();serialize();return;}
			var fa=e.target.closest('.skl-plan__featadd');
			if(fa){fa.parentNode.querySelector('.skl-plan__featrows').appendChild(featRow('',false));serialize();return;}
		});
		serialize();
	});
});
</script>
			<?php foreach ( sklentr_settings_fields() as $page ) : ?>
				<details class="skl-opt-page"><summary class="skl-opt-page__head"><span class="skl-opt-page__title"><?php echo esc_html( $page['label'] ); ?></span><span class="skl-opt-page__count"><?php echo count( $page['groups'] ); ?> sections</span></summary>
				<div class="skl-opt-page__body">
				<?php foreach ( $page['groups'] as $section ) : ?>
					<details class="skl-opt-sec"><summary class="skl-opt-sec__head"><span class="skl-opt-sec__title"><?php echo esc_html( $section['label'] ); ?></span><span class="skl-opt-sec__count"><?php echo count( $section['fields'] ); ?> fields</span></summary>
					<table class="form-table" role="presentation"><tbody>
					<?php
					foreach ( $section['fields'] as $key => $field ) :
						$label = $field[0];
						$type  = $field[1];
						$val   = isset( $opts[ $key ] ) ? $opts[ $key ] : '';
						?>
						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
							<td>
								<?php if ( 'repeater' === $type ) : $rc = isset( $field[2] ) ? $field[2] : array( 'Question', 'Answer' ); ?>
									<div class="skl-faqrep" data-skl-faqrep data-c1="<?php echo esc_attr( $rc[0] ); ?>" data-c2="<?php echo esc_attr( $rc[1] ); ?>">
										<div class="skl-faqrep__rows"></div>
										<button type="button" class="button skl-faqrep__add">+ Add row</button>
										<textarea name="sklentr_settings[<?php echo esc_attr( $key ); ?>]" data-skl-faqrep-store hidden><?php echo esc_textarea( $val ); ?></textarea>
									</div>
								<?php elseif ( 'plansrepeater' === $type ) : ?>
									<div class="skl-plans" data-skl-plans>
										<div class="skl-plans__list"></div>
										<button type="button" class="button skl-plans__add">+ Add plan</button>
										<textarea name="sklentr_settings[<?php echo esc_attr( $key ); ?>]" data-skl-plans-store hidden><?php echo esc_textarea( $val ); ?></textarea>
									</div>
								<?php elseif ( 'textarea' === $type ) : ?>
									<textarea id="<?php echo esc_attr( $key ); ?>" name="sklentr_settings[<?php echo esc_attr( $key ); ?>]" rows="2" class="large-text"><?php echo esc_textarea( $val ); ?></textarea>
								<?php else : ?>
									<input type="<?php echo 'url' === $type ? 'url' : 'text'; ?>" id="<?php echo esc_attr( $key ); ?>" name="sklentr_settings[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $val ); ?>" class="regular-text" />
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody></table></details>
				<?php endforeach; ?>
				</div>
				</details>
			<?php endforeach; ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
