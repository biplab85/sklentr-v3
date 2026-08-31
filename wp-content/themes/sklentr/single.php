<?php
/**
 * Single post template — the article page for real WordPress posts.
 *
 * Renders each post with the editorial layout: dark header, cover image, a
 * two-column body with a sticky Table of Contents + social share rail, the post
 * content, a "Related Articles" grid (same category), and the shared CTA. The
 * body class `blogdetailpage` (added in inc/services-page.php) makes the fixed
 * header paint over the dark header.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$bd_ic_user  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>';
$bd_ic_clock = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>';
$bd_ic_cal   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4.5" width="18" height="16" rx="2"/><path d="M3 9h18M8 3v3M16 3v3"/></svg>';

while ( have_posts() ) :
	the_post();

	$bd_pid   = get_the_ID();
	$bd_cat   = sklentr_post_primary_cat( $bd_pid );
	$bd_cover = sklentr_post_cover_url( $bd_pid );
	$bd_url   = get_permalink();
	$bd_enc_u = rawurlencode( $bd_url );
	$bd_enc_t = rawurlencode( get_the_title() );
	$bd_share = array(
		'x'        => 'https://twitter.com/intent/tweet?url=' . $bd_enc_u . '&text=' . $bd_enc_t,
		'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $bd_enc_u,
		'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $bd_enc_u,
	);
	?>
	<article <?php post_class( 'bd' ); ?>>
		<header class="bd-hero">
			<div class="bd-hero__atmos" aria-hidden="true">
				<span class="bd-hero__glow bd-hero__glow--gold"></span>
				<span class="bd-hero__glow bd-hero__glow--green"></span>
			</div>
			<div class="skl-container">
				<h1 class="bd-hero__title"><?php the_title(); ?></h1>
				<div class="bd-hero__meta">
					<span class="bl-meta"><?php echo $bd_ic_user; // phpcs:ignore WordPress.Security.EscapingOutput ?><?php echo esc_html( sklentr_post_byline( $bd_pid ) ); ?></span>
					<span class="bl-meta"><?php echo $bd_ic_cal; // phpcs:ignore WordPress.Security.EscapingOutput ?><?php echo esc_html( get_the_date() ); ?></span>
					<span class="bl-meta"><?php echo $bd_ic_clock; // phpcs:ignore WordPress.Security.EscapingOutput ?><?php echo esc_html( sklentr_post_readtime( $bd_pid ) ); ?></span>
				</div>
			</div>
		</header>

		<?php if ( $bd_cover ) : ?>
			<div class="bd-cover">
				<div class="skl-container">
					<div class="bd-cover__frame"><img src="<?php echo esc_url( $bd_cover ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" /></div>
				</div>
			</div>
		<?php endif; ?>

		<div class="bd-main">
			<div class="skl-container">
				<div class="bd-layout">
					<aside class="bd-aside">
						<div class="bd-aside__sticky">
							<nav class="bd-toc" data-bd-toc aria-label="Table of contents">
								<p class="bd-toc__title"><?php esc_html_e( 'On this page', 'sklentr' ); ?></p>
								<ul class="bd-toc__list"></ul>
							</nav>
							<div class="bd-share">
								<p class="bd-share__title"><?php esc_html_e( 'Share', 'sklentr' ); ?></p>
								<div class="bd-share__links">
									<a class="bd-share__btn" href="<?php echo esc_url( $bd_share['x'] ); ?>" target="_blank" rel="noopener" aria-label="Share on X">
										<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.9 2H22l-7.3 8.3L23 22h-6.8l-5.3-6.9L4.8 22H1.7l7.8-8.9L1 2h7l4.8 6.3L18.9 2zm-2.4 18h1.9L7.6 4H5.6l10.9 16z"/></svg>
									</a>
									<a class="bd-share__btn" href="<?php echo esc_url( $bd_share['linkedin'] ); ?>" target="_blank" rel="noopener" aria-label="Share on LinkedIn">
										<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.98 3.5A2.5 2.5 0 1 1 0 3.5a2.5 2.5 0 0 1 4.98 0zM.5 8h4V24h-4V8zM8 8h3.8v2.2h.06c.53-1 1.83-2.2 3.77-2.2 4.03 0 4.77 2.65 4.77 6.1V24h-4v-7.1c0-1.7-.03-3.9-2.38-3.9-2.38 0-2.75 1.86-2.75 3.78V24H8V8z"/></svg>
									</a>
									<a class="bd-share__btn" href="<?php echo esc_url( $bd_share['facebook'] ); ?>" target="_blank" rel="noopener" aria-label="Share on Facebook">
										<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13.5 22v-8h2.7l.4-3.1h-3.1V8.9c0-.9.25-1.5 1.55-1.5H17V4.6c-.3-.04-1.3-.13-2.47-.13-2.45 0-4.13 1.5-4.13 4.24v2.19H7.7V14h2.7v8h3.1z"/></svg>
									</a>
									<button type="button" class="bd-share__btn bd-share__copy" data-bd-copy aria-label="Copy link">
										<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7 0l2-2a5 5 0 0 0-7-7l-1 1"/><path d="M14 11a5 5 0 0 0-7 0l-2 2a5 5 0 0 0 7 7l1-1"/></svg>
									</button>
									<span class="bd-share__copied" data-bd-copied hidden><?php esc_html_e( 'Link copied', 'sklentr' ); ?></span>
								</div>
							</div>
						</div>
					</aside>

					<div class="bd-body" data-bd-content>
						<?php the_content(); ?>
					</div>
				</div>
			</div>
		</div>

		<?php
		/* Related — other posts in the same category (fall back to recent). */
		$bd_rel_args = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 3,
			'post__not_in'        => array( $bd_pid ),
			'ignore_sticky_posts' => true,
			'orderby'             => 'date',
			'order'               => 'DESC',
		);
		if ( $bd_cat ) {
			$bd_rel_args['category__in'] = array( $bd_cat->term_id );
		}
		$bd_related = new WP_Query( $bd_rel_args );
		if ( $bd_related->post_count < 3 ) { // top up with recent posts if the category is thin
			$bd_related = new WP_Query( array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => 3,
				'post__not_in'        => array( $bd_pid ),
				'ignore_sticky_posts' => true,
			) );
		}
		if ( $bd_related->have_posts() ) : ?>
			<section class="bd-related" aria-labelledby="bd-related-title">
				<div class="skl-container">
					<h2 class="bd-related__title" id="bd-related-title" data-char-fill><?php esc_html_e( 'Related Articles', 'sklentr' ); ?></h2>
					<div class="bl-grid">
						<?php
						while ( $bd_related->have_posts() ) :
							$bd_related->the_post();
							$r_pid = get_the_ID();
							$r_cat = sklentr_post_primary_cat( $r_pid );
							?>
							<a class="bl-card" href="<?php the_permalink(); ?>" data-reveal>
								<div class="bl-card__media">
									<img src="<?php echo esc_url( sklentr_post_cover_url( $r_pid ) ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" />
									<?php if ( $r_cat ) : ?><span class="bl-card__cat"><?php echo esc_html( $r_cat->name ); ?></span><?php endif; ?>
								</div>
								<div class="bl-card__body">
									<h3 class="bl-card__title"><?php the_title(); ?></h3>
									<p class="bl-card__excerpt"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), 20 ) ); ?></p>
									<div class="bl-card__meta">
										<span class="bl-meta"><?php echo $bd_ic_user; // phpcs:ignore WordPress.Security.EscapingOutput ?><?php echo esc_html( sklentr_post_byline( $r_pid ) ); ?></span>
										<span class="bl-card__dot" aria-hidden="true"></span>
										<span class="bl-meta"><?php echo esc_html( get_the_date() ); ?></span>
									</div>
								</div>
							</a>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
				</div>
			</section>
		<?php endif; ?>
	</article>
	<?php

endwhile;

/* Shared Final-CTA band. */
get_template_part( 'template-parts/home/final-cta' );

get_footer();
