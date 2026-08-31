<?php
/**
 * Home / Hero — light hero with the dark scroll-tilt launch panel.
 * Fully dynamic: text from Sklentr Settings, plan steps/tiles from their CPTs.
 * Markup, styling and all animations are unchanged from the static version.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$skl_eyebrow   = skl_opt( 'hero_eyebrow', __( 'Toronto-based MVP Studio', 'sklentr' ) );
$skl_t_main    = skl_opt( 'hero_title_main', __( 'Launch-ready MVPs', 'sklentr' ) );
$skl_t_hl      = skl_opt( 'hero_title_highlight', __( 'in weeks,', 'sklentr' ) );
$skl_t_strike  = skl_opt( 'hero_title_strike', __( 'not months.', 'sklentr' ) );
$skl_sub       = skl_opt( 'hero_sub', __( 'We build MVPs that get you funded, validated, and to market — fast.', 'sklentr' ) );
$skl_cta1_text = skl_opt( 'hero_cta1_text', __( 'Book a Free Consultation', 'sklentr' ) );
$skl_cta1_link = skl_opt( 'hero_cta1_link', 'https://calendly.com/sklentr' );
$skl_cta1_ext  = ( 0 === strpos( $skl_cta1_link, 'http' ) && false === strpos( $skl_cta1_link, home_url() ) );
$skl_cta2_text = skl_opt( 'hero_cta2_text', __( 'See our work', 'sklentr' ) );
$skl_cta2_link = skl_opt( 'hero_cta2_link', '#work' );
$skl_note      = skl_opt( 'hero_note', __( 'Canadian expertise. Competitive pricing. No excuses.', 'sklentr' ) );
$skl_chips     = array(
	skl_opt( 'hero_chip_1', 'Figma' ),
	skl_opt( 'hero_chip_2', 'React' ),
	skl_opt( 'hero_chip_3', 'Funded ✓' ),
	skl_opt( 'hero_chip_4', 'Ship →' ),
);
$skl_panel_title = skl_opt( 'hero_panel_title', __( 'MVP Launch Plan', 'sklentr' ) );
$skl_badge_load  = skl_opt( 'hero_badge_loading', __( 'Building…', 'sklentr' ) );
$skl_badge_ok    = skl_opt( 'hero_badge_ok', __( 'On track', 'sklentr' ) );

$skl_steps = new WP_Query( array(
	'post_type'      => 'skl_hero_step',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
$skl_tiles = new WP_Query( array(
	'post_type'      => 'skl_hero_tile',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
?>

<section class="hero" id="hero" aria-labelledby="hero-title">

	<!-- Ambient background: radial glows + dotted grid -->
	<div class="hero__bg" aria-hidden="true">
		<span class="hero__glow hero__glow--gold"></span>
		<span class="hero__glow hero__glow--green"></span>
		<span class="hero__grid"></span>
	</div>

	<!-- Decorative floating shapes -->
	<div class="hero__shapes" aria-hidden="true">
		<span class="hero__ring"></span>
		<span class="hero__blob"></span>
		<span class="hero__chip hero__chip--a"><?php echo esc_html( $skl_chips[0] ); ?></span>
		<span class="hero__chip hero__chip--b"><?php echo esc_html( $skl_chips[1] ); ?></span>
		<span class="hero__chip hero__chip--c"><?php echo esc_html( $skl_chips[2] ); ?></span>
		<span class="hero__chip hero__chip--d"><?php echo esc_html( $skl_chips[3] ); ?></span>
	</div>

	<div class="skl-container hero__inner">

		<p class="hero__eyebrow">
			<span class="hero__eyebrow-dot"></span>
			<?php echo esc_html( $skl_eyebrow ); ?>
		</p>

		<h1 class="hero__title" id="hero-title">
			<?php echo esc_html( $skl_t_main ); ?>
			<span class="hero__hl"><?php echo esc_html( $skl_t_hl ); ?></span>
			<span class="hero__strike"><?php echo esc_html( $skl_t_strike ); ?></span>
		</h1>

		<p class="hero__sub">
			<?php echo esc_html( $skl_sub ); ?>
		</p>

		<div class="hero__actions">
			<a class="skl-btn skl-btn--gold" href="<?php echo esc_url( $skl_cta1_link ); ?>"<?php echo $skl_cta1_ext ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
				<?php echo esc_html( $skl_cta1_text ); ?>
				<span class="skl-btn__arrow" aria-hidden="true">&rarr;</span>
			</a>
			<a class="skl-btn skl-btn--ghost-light" href="<?php echo esc_url( $skl_cta2_link ); ?>">
				<?php echo esc_html( $skl_cta2_text ); ?>
			</a>
		</div>

		<p class="hero__note">
			<?php echo esc_html( $skl_note ); ?>
		</p>

		<!-- Launch-plan panel — dark card with a scroll-driven 3D tilt (perspective stage → tilt → panel) -->
		<div class="hero__panel-stage">
		<div class="hero__panel-tilt" data-hero-tilt>
		<div class="hero__panel" role="img"
			aria-label="<?php esc_attr_e( 'Sample MVP launch plan', 'sklentr' ); ?>">
			<div class="panel__bar">
				<span class="panel__dots"><i></i><i></i><i></i></span>
				<span class="panel__title"><?php echo esc_html( $skl_panel_title ); ?></span>
				<span class="panel__badge">
					<span class="panel__badge-state panel__badge-state--loading">
						<span class="panel__spin" aria-hidden="true"></span><?php echo esc_html( $skl_badge_load ); ?>
					</span>
					<span class="panel__badge-state panel__badge-state--ok"><?php echo esc_html( $skl_badge_ok ); ?></span>
				</span>
			</div>

			<div class="panel__body">
				<div class="panel__track"><span class="panel__progress"></span></div>

				<?php if ( $skl_steps->have_posts() ) : ?>
					<ol class="panel__steps">
						<?php
						while ( $skl_steps->have_posts() ) :
							$skl_steps->the_post();
							$skl_week  = get_post_meta( get_the_ID(), '_skl_week', true );
							$skl_state = get_post_meta( get_the_ID(), '_skl_state', true );
							$skl_cls   = 'done' === $skl_state ? 'is-done' : ( 'active' === $skl_state ? 'is-active' : '' );
							?>
							<li<?php echo $skl_cls ? ' class="' . esc_attr( $skl_cls ) . '"' : ''; ?>>
								<span class="panel__wk"><?php echo esc_html( $skl_week ); ?></span>
								<span class="panel__st"><?php the_title(); ?></span>
							</li>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</ol>
				<?php endif; ?>

				<?php if ( $skl_tiles->have_posts() ) : ?>
					<div class="panel__foot">
						<?php
						while ( $skl_tiles->have_posts() ) :
							$skl_tiles->the_post();
							$skl_count   = get_post_meta( get_the_ID(), '_skl_count', true );
							$skl_suffix  = get_post_meta( get_the_ID(), '_skl_suffix', true );
							$skl_display = get_post_meta( get_the_ID(), '_skl_display', true );
							?>
							<div class="panel__tile">
								<b><?php
								if ( '' !== $skl_count ) {
									echo '<span class="panel__count" data-to="' . esc_attr( $skl_count ) . '">' . esc_html( $skl_count ) . '</span>' . esc_html( $skl_suffix );
								} else {
									echo esc_html( $skl_display );
								}
								?></b>
								<span><?php the_title(); ?></span>
							</div>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		</div>
		</div>
	</div>
</section>
