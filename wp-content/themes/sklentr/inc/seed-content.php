<?php
/**
 * Sklentr — one-time content seeding.
 *
 * Copies the templates' inline skl_opt() DEFAULTS into the saved
 * `sklentr_settings` option so the admin fields show the current content
 * (editable) instead of appearing blank. Only fills keys that are currently
 * empty — it never overwrites an admin edit. Version-flagged + idempotent.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pull the default value passed to skl_opt( 'key', DEFAULT ) out of a template's
 * source. Handles  __( 'text', 'sklentr' ) ,  "text with \n" , and  'text' .
 *
 * @param string $src Template file contents.
 * @param string $key Option key.
 * @return string|null
 */
function sklentr_extract_default( $src, $key ) {
	$k = preg_quote( $key, '/' );

	// Form A:  skl_opt( 'key', __( 'STRING', 'sklentr' ) )
	if ( preg_match( "/skl_opt\(\s*'{$k}'\s*,\s*__\(\s*'((?:[^'\\\\]|\\\\.)*)'\s*,\s*'sklentr'\s*\)/s", $src, $m ) ) {
		return stripcslashes( $m[1] );
	}

	// Form B:  skl_opt( 'key', "STRING" )   (double-quoted, may contain \n)
	if ( preg_match( "/skl_opt\(\s*'{$k}'\s*,\s*\"((?:[^\"\\\\]|\\\\.)*)\"\s*\)/s", $src, $m ) ) {
		return stripcslashes( $m[1] );
	}

	// Form C:  skl_opt( 'key', 'STRING' )   (bare single-quoted, e.g. links)
	if ( preg_match( "/skl_opt\(\s*'{$k}'\s*,\s*'((?:[^'\\\\]|\\\\.)*)'\s*\)/s", $src, $m ) ) {
		return stripcslashes( $m[1] );
	}

	return null;
}

add_action( 'init', function () {
	if ( get_option( 'sklentr_seed_content_v2' ) ) {
		return;
	}

	// Which keys to seed, and the template each default lives in.
	$sources = array(
		'page-startup-visa.php' => array( 'sv_faq_title', 'sv_faq_items' ),
		'page-pricing.php'      => array(
			'pr_hero_eyebrow', 'pr_hero_title', 'pr_hero_accent', 'pr_hero_sub',
			'pr_hero_cta1_text', 'pr_hero_cta1_link', 'pr_hero_cta2_text', 'pr_hero_cta2_link', 'pr_hero_chips',
			'pr_plans_eyebrow', 'pr_plans_title', 'pr_plans_intro', 'pr_plans',
			'pr_guar_eyebrow', 'pr_guar_title', 'pr_guar_items',
			'pr_faq_title', 'pr_faq_items',
			'pr_faq_eyebrow', 'pr_faq_help_title', 'pr_faq_help_text', 'pr_faq_help_cta_text', 'pr_faq_help_cta_link',
		),
	);

	$opts = get_option( 'sklentr_settings', array() );

	foreach ( $sources as $file => $keys ) {
		$src = @file_get_contents( get_theme_file_path( $file ) ); // phpcs:ignore
		if ( ! $src ) {
			continue;
		}
		foreach ( $keys as $key ) {
			if ( isset( $opts[ $key ] ) && '' !== $opts[ $key ] ) {
				continue; // never overwrite an existing value.
			}
			$val = sklentr_extract_default( $src, $key );
			if ( null !== $val ) {
				$opts[ $key ] = $val;
			}
		}
	}

	update_option( 'sklentr_settings', $opts );
	update_option( 'sklentr_seed_content_v2', 1 );
}, 30 );
