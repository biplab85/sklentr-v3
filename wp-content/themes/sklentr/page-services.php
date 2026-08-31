<?php
/**
 * Template for the standalone Services page (slug: services).
 *
 * Follows the redesign blueprint (§04 Services + §05 Why Us + §08 Process) as a
 * dedicated page: a premium LIGHT hero, a light-mode service card grid, the
 * "Why Sklentr" grid, a process stepper, then the shared Final-CTA band + footer.
 *
 * Content mirrors https://www.sklentr.com/services and is fully editable from
 * wp-admin (Sklentr Settings → "Services Page", the "Services" CPT with its
 * services-page fields, and "Services — Why Us"). The homepage is untouched.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/* ------------------------------------------------------------------ *
 * SECTION 01 — Hero (light, premium)
 * ------------------------------------------------------------------ */
$svc_h_eyebrow = skl_opt( 'svc_hero_eyebrow', __( 'Full-Service Development Studio', 'sklentr' ) );
$svc_h_title   = skl_opt( 'svc_hero_title', __( 'Everything You Need to', 'sklentr' ) );
$svc_h_accent  = skl_opt( 'svc_hero_accent', __( 'Launch.', 'sklentr' ) );
$svc_h_sub     = skl_opt( 'svc_hero_sub', __( 'From MVP development to marketing, we’re your one-stop shop for turning ideas into launched products. Canadian management. Global talent. No excuses.', 'sklentr' ) );
$svc_c1_text   = skl_opt( 'svc_hero_cta1_text', __( 'Book a Free Consultation', 'sklentr' ) );
$svc_c1_link   = skl_opt( 'svc_hero_cta1_link', 'https://calendly.com/sklentr' );
$svc_c2_text   = skl_opt( 'svc_hero_cta2_text', __( 'See Pricing', 'sklentr' ) );
$svc_c2_link   = skl_opt( 'svc_hero_cta2_link', home_url( '/' ) . '#pricing' );
$svc_c1_ext    = ( 0 === strpos( $svc_c1_link, 'http' ) && false === strpos( $svc_c1_link, home_url() ) );

$svc_stats = new WP_Query( array(
	'post_type'      => 'skl_stat',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
?>

<section class="svc-hero" aria-labelledby="svc-hero-title">
	<div class="svc-hero__atmos" aria-hidden="true">
		<span class="svc-hero__glow svc-hero__glow--gold"></span>
		<span class="svc-hero__glow svc-hero__glow--green"></span>
		<svg class="svc-hero__hexes" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
			<defs>
				<pattern id="svcHexPat" width="58" height="66" patternUnits="userSpaceOnUse">
					<path d="M29 8 L50.65 20.5 L50.65 45.5 L29 58 L7.35 45.5 L7.35 20.5 Z" fill="none" stroke="#F3B351" stroke-opacity=".55" stroke-width="1"/>
				</pattern>
			</defs>
			<rect width="100%" height="100%" fill="url(#svcHexPat)"/>
		</svg>
	</div>

	<div class="skl-container">
		<div class="svc-hero__inner">

			<div class="svc-hero__copy" data-reveal>
				<?php if ( $svc_h_eyebrow ) : ?>
					<p class="svc-hero__eyebrow">
						<span class="svc-hero__eyebrow-dot" aria-hidden="true"></span>
						<?php echo esc_html( $svc_h_eyebrow ); ?>
					</p>
				<?php endif; ?>

				<h1 class="svc-hero__title" id="svc-hero-title">
					<?php echo esc_html( $svc_h_title ); ?>
					<?php if ( $svc_h_accent ) : ?>
						<span class="svc-hero__accent"><?php echo esc_html( $svc_h_accent ); ?></span>
					<?php endif; ?>
				</h1>

				<?php if ( $svc_h_sub ) : ?>
					<p class="svc-hero__sub"><?php echo esc_html( $svc_h_sub ); ?></p>
				<?php endif; ?>

				<div class="svc-hero__actions">
					<?php if ( $svc_c1_text ) : ?>
						<a class="skl-btn skl-btn--gold svc-hero__cta" href="<?php echo esc_url( $svc_c1_link ); ?>"<?php echo $svc_c1_ext ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
							<?php echo esc_html( $svc_c1_text ); ?>
							<span class="skl-btn__arrow" aria-hidden="true">&rarr;</span>
						</a>
					<?php endif; ?>
					<?php if ( $svc_c2_text ) : ?>
						<a class="skl-btn skl-btn--ghost-dark svc-hero__cta-alt" href="<?php echo esc_url( $svc_c2_link ); ?>">
							<?php echo esc_html( $svc_c2_text ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>

			<div class="svc-hero__visual" data-reveal aria-hidden="true">
				<span class="svc-orb__aura"></span>
				<svg class="svc-orb" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
					<defs>
						<radialGradient id="svcCore" cx="50%" cy="42%" r="60%">
							<stop offset="0%" stop-color="#1EFF85" stop-opacity=".9"/>
							<stop offset="46%" stop-color="#F3B351" stop-opacity=".85"/>
							<stop offset="100%" stop-color="#E0912B" stop-opacity="0"/>
						</radialGradient>
						<linearGradient id="svcWire" x1="0" y1="0.08" x2="1" y2="0.96">
							<stop offset="0%" stop-color="#F3B351" stop-opacity=".9"/>
							<stop offset="55%" stop-color="#F3B351" stop-opacity=".3"/>
							<stop offset="100%" stop-color="#1EFF85" stop-opacity=".6"/>
						</linearGradient>
						<radialGradient id="svcDepth" cx="36%" cy="30%" r="72%">
							<stop offset="0%" stop-color="#1B2A44" stop-opacity=".6"/>
							<stop offset="100%" stop-color="#0B1120" stop-opacity=".55"/>
						</radialGradient>
						<radialGradient id="svcSheen" cx="34%" cy="28%" r="40%">
							<stop offset="0%" stop-color="#ffffff" stop-opacity=".22"/>
							<stop offset="100%" stop-color="#ffffff" stop-opacity="0"/>
						</radialGradient>
						<clipPath id="svcSphereClip"><circle cx="200" cy="200" r="150"/></clipPath>
					</defs>

					<!-- Sphere body -->
					<circle cx="200" cy="200" r="150" fill="url(#svcDepth)"/>

					<!-- Wireframe globe (clipped to the sphere) -->
					<g clip-path="url(#svcSphereClip)" stroke="url(#svcWire)" stroke-width="1" class="svc-orb__wire">
						<ellipse cx="200" cy="200" rx="150" ry="44"/>
						<ellipse cx="200" cy="125" rx="130" ry="38"/>
						<ellipse cx="200" cy="275" rx="130" ry="38"/>
						<ellipse cx="200" cy="70"  rx="75"  ry="22"/>
						<ellipse cx="200" cy="330" rx="75"  ry="22"/>
						<ellipse cx="200" cy="200" rx="112" ry="150"/>
						<ellipse cx="200" cy="200" rx="60"  ry="150"/>
						<line x1="200" y1="50" x2="200" y2="350"/>
					</g>

					<!-- Surface data points -->
					<circle cx="150" cy="132" r="3"   class="svc-orb__node svc-orb__node--green"/>
					<circle cx="262" cy="252" r="2.6" class="svc-orb__node svc-orb__node--gold"/>
					<circle cx="248" cy="150" r="2.2" class="svc-orb__node svc-orb__node--gold"/>

					<!-- Sphere outline + glassy sheen -->
					<circle cx="200" cy="200" r="150" stroke="url(#svcWire)" stroke-width="1.6" class="svc-orb__sphere"/>
					<circle cx="200" cy="200" r="150" fill="url(#svcSheen)" clip-path="url(#svcSphereClip)"/>

					<!-- Glowing core + code glyph -->
					<circle class="svc-orb__core" cx="200" cy="200" r="50" fill="url(#svcCore)"/>
					<circle cx="200" cy="200" r="30" fill="#0B1120" fill-opacity=".5"/>
					<g class="svc-orb__glyph" stroke="#ffffff" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
						<path d="M188 191l-10 9 10 9"/>
						<path d="M212 191l10 9-10 9"/>
						<path d="M204 186l-8 28"/>
					</g>

					<!-- Orbit ring + travelling satellite -->
					<circle cx="200" cy="200" r="182" stroke="#ffffff" stroke-opacity=".1" stroke-width="1" stroke-dasharray="2 11" class="svc-orb__orbit"/>
					<g class="svc-orb__sat">
						<circle cx="200" cy="18" r="11" fill="none" stroke="#F3B351" stroke-opacity=".35" stroke-width="1"/>
						<circle cx="200" cy="18" r="5" class="svc-orb__node svc-orb__node--gold"/>
					</g>
				</svg>

				<span class="svc-chip svc-chip--a">Next.js</span>
				<span class="svc-chip svc-chip--b svc-chip--green">API&nbsp;&check;</span>
				<span class="svc-chip svc-chip--c svc-chip--gold">Deploy&nbsp;&rarr;</span>
				<span class="svc-chip svc-chip--d">AI</span>
			</div>

		</div>

		<?php if ( $svc_stats->have_posts() ) : ?>
			<ul class="svc-hero__stats" data-reveal>
					<?php
					while ( $svc_stats->have_posts() ) :
						$svc_stats->the_post();
						$svc_num = get_post_meta( get_the_ID(), '_skl_number', true );
						$svc_suf = get_post_meta( get_the_ID(), '_skl_suffix', true );
						?>
						<li class="svc-hero__stat">
							<span class="svc-hero__stat-num"><span class="svc-hero__stat-val" data-count-to="<?php echo esc_attr( $svc_num ); ?>"><?php echo esc_html( $svc_num ); ?></span><?php echo esc_html( $svc_suf ); ?></span>
							<span class="svc-hero__stat-label"><?php the_title(); ?></span>
						</li>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
			</ul>
		<?php endif; ?>
	</div>
</section>

<?php
/* ------------------------------------------------------------------ *
 * SECTION 02 — Services grid ("What We Do")
 * Lives in a shared part because the Pricing page renders it too,
 * directly after its Packages section.
 * ------------------------------------------------------------------ */
get_template_part( 'template-parts/section-what-we-do' );

/* ------------------------------------------------------------------ *
 * SECTION 03 — Why Sklentr (light grid)
 * ------------------------------------------------------------------ */
$svc_w_eyebrow = skl_opt( 'svc_why_eyebrow', __( 'Why Sklentr', 'sklentr' ) );
$svc_w_title   = skl_opt( 'svc_why_title', __( 'Built to launch —', 'sklentr' ) );
$svc_w_accent  = skl_opt( 'svc_why_accent', __( 'and to last.', 'sklentr' ) );
$svc_w_intro   = skl_opt( 'svc_why_intro', __( 'What you get when Canadian management meets a full-stack delivery team.', 'sklentr' ) );

$svc_perks = new WP_Query( array(
	'post_type'      => 'skl_svc_perk',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
?>

<?php if ( $svc_perks->have_posts() ) : ?>
	<section class="svc-why" aria-labelledby="svc-why-title">
		<div class="skl-container">
			<div class="svc-section-head" data-reveal>
				<?php if ( $svc_w_eyebrow ) : ?>
					<p class="skl-eyebrow svc-section-head__eyebrow"><?php echo esc_html( $svc_w_eyebrow ); ?></p>
				<?php endif; ?>
				<h2 class="svc-section-head__title" id="svc-why-title" data-char-fill><?php echo esc_html( trim( $svc_w_title . ' ' . $svc_w_accent ) ); ?></h2>
				<?php if ( $svc_w_intro ) : ?>
					<p class="svc-section-head__intro"><?php echo esc_html( $svc_w_intro ); ?></p>
				<?php endif; ?>
			</div>

			<ul class="svc-why__grid">
				<?php
				while ( $svc_perks->have_posts() ) :
					$svc_perks->the_post();
					$svc_p_icon = get_post_meta( get_the_ID(), '_skl_icon', true );
					$svc_p_icon = $svc_p_icon ? $svc_p_icon : 'bolt';
					$svc_p_desc = get_post_meta( get_the_ID(), '_skl_desc', true );
					?>
					<li class="svc-perk">
						<span class="svc-perk__icon"><?php echo skl_perk_icon_svg( $svc_p_icon ); // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG. ?></span>
						<h3 class="svc-perk__title"><?php the_title(); ?></h3>
						<?php if ( $svc_p_desc ) : ?>
							<p class="svc-perk__desc"><?php echo esc_html( $svc_p_desc ); ?></p>
						<?php endif; ?>
					</li>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</ul>
		</div>
	</section>
<?php endif; ?>

<?php
/* ------------------------------------------------------------------ *
 * SECTION 04 — How We Work — identical to the homepage process section
 * (same markup + scroll animation). Rendered from the shared part so the
 * two stay in lock-step; process.js is enqueued for this page too.
 * ------------------------------------------------------------------ */
get_template_part( 'template-parts/home/process' );
?>

<?php
/* Shared closing band + footer (unchanged from the homepage). */
get_template_part( 'template-parts/home/final-cta' );

get_footer();
