<?php
/**
 * Home / Section 06 — Startup Visa (dark anchor).
 * Content mirrors the live sklentr.com "Startup Visa" section. Fully dynamic:
 * copy + CTA from Sklentr Settings ("Startup Visa Spotlight"), the feature
 * cards from the "Visa Features" CPT. (Blueprint §7 SECTION 06.)
 *
 * Layout: a sticky left column (heading + CTA) beside a scrolling stack of
 * process cards that rise/fade in on scroll (see assets/js/visa-parallax.js).
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$skl_eyebrow  = skl_opt( 'visa_eyebrow', __( 'For Canada Startup Visa Applicants', 'sklentr' ) );
$skl_title    = skl_opt( 'visa_title', __( 'Need an MVP for your Startup Visa?', 'sklentr' ) );
$skl_accent   = skl_opt( 'visa_title_accent', __( 'We’ve got you.', 'sklentr' ) );
$skl_body     = skl_opt( 'visa_body', '' );
$skl_cta_text = skl_opt( 'visa_cta_text', __( 'Learn More', 'sklentr' ) );
$skl_cta_link = skl_resolve_link( skl_opt( 'visa_cta_link', '/startup-visa' ) );

$skl_features = new WP_Query( array(
	'post_type'      => 'skl_visa_feature',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
?>

<section class="visa" id="startup-visa" aria-labelledby="visa-title">

	<div class="visa__atmos" aria-hidden="true">
		<span class="visa__glow visa__glow--gold"></span>
		<span class="visa__glow visa__glow--green"></span>
		<span class="visa__leaf-mark"><?php echo skl_maple_leaf_svg(); // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG. ?></span>
	</div>

	<div class="skl-container">
		<div class="visa__inner">

			<div class="visa__head">
				<?php if ( $skl_eyebrow ) : ?>
					<p class="visa__eyebrow skl-eyebrow">
						<span class="visa__eyebrow-leaf"><?php echo skl_maple_leaf_svg(); // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG. ?></span>
						<?php echo esc_html( $skl_eyebrow ); ?>
					</p>
				<?php endif; ?>

				<h2 class="visa__title" id="visa-title">
					<span class="visa__title-main" data-char-fill="dark"><?php echo esc_html( $skl_title ); ?></span>
					<?php if ( $skl_accent ) : ?>
						<span class="visa__title-accent" data-char-fill="gold"><?php echo esc_html( $skl_accent ); ?></span>
					<?php endif; ?>
				</h2>

				<?php if ( $skl_body ) : ?>
					<p class="visa__body"><?php echo esc_html( $skl_body ); ?></p>
				<?php endif; ?>

				<?php if ( $skl_cta_text && $skl_cta_link ) : ?>
					<div class="visa__actions">
						<a class="skl-btn skl-btn--gold" href="<?php echo esc_url( $skl_cta_link ); ?>">
							<?php echo esc_html( $skl_cta_text ); ?>
							<span class="skl-btn__arrow" aria-hidden="true">&rarr;</span>
						</a>
					</div>
				<?php endif; ?>
			</div><!-- /.visa__head (sticky) -->

			<?php if ( $skl_features->have_posts() ) : ?>
				<ul class="visa__features">
					<?php
					$skl_i = 0;
					while ( $skl_features->have_posts() ) :
						$skl_features->the_post();
						++$skl_i;
						$skl_icon = get_post_meta( get_the_ID(), '_skl_icon', true );
						$skl_sub  = get_post_meta( get_the_ID(), '_skl_sub', true );
						$skl_icon = $skl_icon ? $skl_icon : 'product';
						?>
						<li class="visa-feature" style="--i:<?php echo esc_attr( $skl_i - 1 ); ?>">
							<div class="visa-feature__head">
								<span class="visa-feature__icon"><?php echo skl_visa_icon_svg( $skl_icon ); // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG. ?></span>
								<span class="visa-feature__num" aria-hidden="true">
									<svg class="visa-feature__num-svg" viewBox="0 0 68 50" preserveAspectRatio="xMidYMid meet" focusable="false">
										<text x="34" y="39" text-anchor="middle"><?php echo esc_html( str_pad( (string) $skl_i, 2, '0', STR_PAD_LEFT ) ); ?></text>
									</svg>
								</span>
							</div>
							<h3 class="visa-feature__title"><?php the_title(); ?></h3>
							<?php if ( $skl_sub ) : ?>
								<p class="visa-feature__sub"><?php echo esc_html( $skl_sub ); ?></p>
							<?php endif; ?>
						</li>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</ul>
			<?php endif; ?>

		</div><!-- /.visa__inner -->
	</div>
</section>
