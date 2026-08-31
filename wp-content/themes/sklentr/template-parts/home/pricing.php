<?php
/**
 * Home / Section 09 — Transparent Pricing.
 * Fully dynamic: heading/note/CTA from Sklentr Settings, tiers from the
 * "Pricing Tiers" CPT (price, currency, timeline, features, popular flag).
 * Content mirrors the live sklentr.com pricing section. Three cards, the
 * "popular" one elevated with a ribbon; each card lists its features with green
 * ticks and a booking CTA. Micro-animations: staggered card reveal, hover lift,
 * a floating/glowing popular card, and cascading feature ticks. (Blueprint §09.)
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$skl_eyebrow  = skl_opt( 'pricing_eyebrow', __( 'Pricing', 'sklentr' ) );
$skl_title    = skl_opt( 'pricing_title', __( 'Transparent pricing.', 'sklentr' ) );
$skl_accent   = skl_opt( 'pricing_title_accent', __( 'No surprises.', 'sklentr' ) );
$skl_intro    = skl_opt( 'pricing_intro', '' );
$skl_note     = skl_opt( 'pricing_note', '' );
$skl_cta_text = skl_opt( 'pricing_cta_text', __( 'View Full Pricing', 'sklentr' ) );
$skl_cta_link = skl_resolve_link( skl_opt( 'pricing_cta_link', '/pricing' ) );

$skl_tiers = new WP_Query( array(
	'post_type'      => 'skl_pricing',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
?>

<section class="pricing" id="pricing" aria-labelledby="pricing-title">

	<div class="pricing__atmos" aria-hidden="true">
		<span class="pricing__glow pricing__glow--gold"></span>
		<span class="pricing__glow pricing__glow--green"></span>
	</div>

	<!-- Ambient IT/dev icons drifting around the section -->
	<div class="pricing__deco" aria-hidden="true">
		<?php
		$skl_deco = array(
			// key => SVG inner paths (24×24, stroked).
			'a' => '<path d="M8 8l-4 4 4 4"/><path d="M16 8l4 4-4 4"/><path d="M13.5 6.5l-3 11"/>',                                                                                   // code brackets
			'b' => '<ellipse cx="12" cy="5.5" rx="7.5" ry="2.8"/><path d="M4.5 5.5v6c0 1.55 3.36 2.8 7.5 2.8s7.5-1.25 7.5-2.8v-6"/><path d="M4.5 11.5v6c0 1.55 3.36 2.8 7.5 2.8s7.5-1.25 7.5-2.8v-6"/>', // database
			'c' => '<circle cx="12" cy="12" r="3.2"/><path d="M12 2.5v3M12 18.5v3M2.5 12h3M18.5 12h3M5.2 5.2l2.1 2.1M16.7 16.7l2.1 2.1M18.8 5.2l-2.1 2.1M7.3 16.7l-2.1 2.1"/>',        // gear
			'd' => '<path d="M8 4c-2 0-2 2-2 4s0 3-2 3c2 0 2 1 2 3s0 4 2 4"/><path d="M16 4c2 0 2 2 2 4s0 3 2 3c-2 0-2 1-2 3s0 4-2 4"/>',                                                // braces {}
			'e' => '<rect x="3" y="4.5" width="18" height="15" rx="2"/><path d="M7 10l3 2.5-3 2.5"/><path d="M12.5 15.5h4.5"/>',                                                        // terminal
			'f' => '<rect x="6.5" y="6.5" width="11" height="11" rx="2"/><rect x="9.5" y="9.5" width="5" height="5"/><path d="M9 2.5v2M15 2.5v2M9 19.5v2M15 19.5v2M2.5 9h2M2.5 15h2M19.5 9h2M19.5 15h2"/>', // chip
			'g' => '<rect x="3" y="4" width="18" height="7" rx="1.5"/><rect x="3" y="13" width="18" height="7" rx="1.5"/><path d="M7 7.5h.01M7 16.5h.01"/>',                            // server stack
			'h' => '<circle cx="6" cy="6" r="2.5"/><circle cx="6" cy="18" r="2.5"/><circle cx="18" cy="7.5" r="2.5"/><path d="M6 8.5v7"/><path d="M18 10v1.5a4 4 0 0 1-4 4H8.5"/>',     // git branch
		);
		foreach ( $skl_deco as $skl_pos => $skl_paths ) {
			echo '<span class="pricing__deco-icon pricing__deco-icon--' . esc_attr( $skl_pos ) . '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">' . $skl_paths . '</svg></span>'; // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG paths.
		}
		?>
	</div>

	<div class="skl-container">

		<div class="pricing__head" data-reveal>
			<?php if ( $skl_eyebrow ) : ?>
				<p class="pricing__eyebrow skl-eyebrow"><?php echo esc_html( $skl_eyebrow ); ?></p>
			<?php endif; ?>
			<h2 class="pricing__title" id="pricing-title" data-char-fill="dark">
				<?php echo esc_html( trim( $skl_title . ' ' . $skl_accent ) ); ?>
			</h2>
			<?php if ( $skl_intro ) : ?>
				<p class="pricing__intro"><?php echo esc_html( $skl_intro ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $skl_tiers->have_posts() ) : ?>
			<ul class="pricing__grid" data-advance>
				<?php
				$skl_i = 0;
				while ( $skl_tiers->have_posts() ) :
					$skl_tiers->the_post();
					++$skl_i;
					$skl_prefix   = get_post_meta( get_the_ID(), '_skl_prefix', true );
					$skl_price    = get_post_meta( get_the_ID(), '_skl_price', true );
					$skl_currency = get_post_meta( get_the_ID(), '_skl_currency', true );
					$skl_period   = get_post_meta( get_the_ID(), '_skl_period', true );
					$skl_popular  = 'yes' === get_post_meta( get_the_ID(), '_skl_popular', true );
					$skl_badge    = get_post_meta( get_the_ID(), '_skl_badge', true );
					$skl_btn_text = get_post_meta( get_the_ID(), '_skl_cta_text', true );
					$skl_btn_link = get_post_meta( get_the_ID(), '_skl_cta_link', true );
					$skl_btn_text = $skl_btn_text ? $skl_btn_text : __( 'Book a Free Consultation', 'sklentr' );
					$skl_btn_link = $skl_btn_link ? skl_resolve_link( $skl_btn_link ) : 'https://calendly.com/sklentr';
					$skl_btn_ext  = ( 0 === strpos( $skl_btn_link, 'http' ) && false === strpos( $skl_btn_link, home_url() ) );
					$skl_badge    = $skl_badge ? $skl_badge : __( 'Popular', 'sklentr' );
					$skl_features = get_post_meta( get_the_ID(), '_skl_features', true );
					$skl_features = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $skl_features ) ) );
					?>
					<li class="pricing-card<?php echo $skl_popular ? ' pricing-card--popular' : ''; ?>" style="--i:<?php echo esc_attr( $skl_i - 1 ); ?>" data-reveal>
						<?php if ( $skl_popular ) : ?>
							<span class="pricing-card__ribbon"><?php echo esc_html( $skl_badge ); ?></span>
						<?php endif; ?>

						<h3 class="pricing-card__name"><?php the_title(); ?></h3>

						<div class="pricing-card__price">
							<?php if ( $skl_prefix ) : ?>
								<span class="pricing-card__prefix"><?php echo esc_html( $skl_prefix ); ?></span>
							<?php endif; ?>
							<span class="pricing-card__amount">
								<span class="pricing-card__value"><?php echo esc_html( $skl_price ); ?></span>
								<?php if ( $skl_currency ) : ?>
									<span class="pricing-card__currency"><?php echo esc_html( $skl_currency ); ?></span>
								<?php endif; ?>
							</span>
						</div>

						<?php if ( $skl_period ) : ?>
							<span class="pricing-card__period">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9"/><path d="M12 7.5V12l3 1.8"/></svg>
								<?php echo esc_html( $skl_period ); ?>
							</span>
						<?php endif; ?>

						<?php if ( ! empty( $skl_features ) ) : ?>
							<ul class="pricing-card__features">
								<?php foreach ( $skl_features as $skl_feature ) : ?>
									<li class="pricing-card__feature">
										<span class="pricing-card__tick" aria-hidden="true"><?php echo skl_check_icon_svg(); // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG. ?></span>
										<?php echo esc_html( $skl_feature ); ?>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<a class="skl-btn <?php echo $skl_popular ? 'skl-btn--gold' : 'skl-btn--ghost-dark'; ?> pricing-card__cta" href="<?php echo esc_url( $skl_btn_link ); ?>"<?php echo $skl_btn_ext ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
							<?php echo esc_html( $skl_btn_text ); ?>
							<span class="skl-btn__arrow" aria-hidden="true">&rarr;</span>
						</a>
					</li>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</ul>
		<?php endif; ?>

		<?php if ( $skl_note || ( $skl_cta_text && $skl_cta_link ) ) : ?>
			<div class="pricing__foot" data-reveal>
				<?php if ( $skl_note ) : ?>
					<p class="pricing__note"><?php echo esc_html( $skl_note ); ?></p>
				<?php endif; ?>
				<?php if ( $skl_cta_text && $skl_cta_link ) : ?>
					<a class="skl-textlink" href="<?php echo esc_url( $skl_cta_link ); ?>">
						<?php echo esc_html( $skl_cta_text ); ?>
						<span class="skl-textlink__arrow" aria-hidden="true">&rarr;</span>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

	</div>
</section>
