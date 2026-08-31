<?php
/**
 * Front page — the Sklentr homepage.
 * Sections are modular template parts in /template-parts/home/.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part( 'template-parts/home/hero' );
get_template_part( 'template-parts/home/trust' );
get_template_part( 'template-parts/home/problem' );
get_template_part( 'template-parts/home/services' );
get_template_part( 'template-parts/home/why' );
get_template_part( 'template-parts/home/startup-visa' );
get_template_part( 'template-parts/home/work' );
get_template_part( 'template-parts/home/process' );
get_template_part( 'template-parts/home/pricing' );
get_template_part( 'template-parts/home/technology' );
get_template_part( 'template-parts/home/about' );
get_template_part( 'template-parts/home/insights' );
get_template_part( 'template-parts/home/faq' );
get_template_part( 'template-parts/home/final-cta' );

get_footer();
