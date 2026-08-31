<?php
/**
 * Template for the standalone Pricing page (slug: pricing).
 *
 * Content mirrors https://www.sklentr.com/pricing and is fully editable from
 * wp-admin (Sklentr Settings → "Pricing Page"). The hero is a deliberately
 * PREMIUM, centered "aurora" treatment — distinct from the homepage, Services,
 * Startup-Visa, and Portfolio heroes. Reuses the shared header, the homepage
 * FAQ accordion UI (faq.js), the Final-CTA band, and footer.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/* Helpers: split a textarea into trimmed non-empty lines, and a line into piped columns. */
$pr_lines = static function ( $raw ) {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $raw ) ) ) );
};
$pr_cols = static function ( $line ) {
	return array_map( 'trim', explode( '|', (string) $line ) );
};
$pr_check = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6.5 9.5 17 4 11.5"/></svg>';
$pr_x     = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>';

/* ================================================================== *
 * 01 — HERO (premium, centered, aurora)
 * ================================================================== */
$pr_h_eyebrow = skl_opt( 'pr_hero_eyebrow', __( 'Transparent Pricing', 'sklentr' ) );
$pr_h_title   = skl_opt( 'pr_hero_title', __( 'Simple pricing.', 'sklentr' ) );
$pr_h_accent  = skl_opt( 'pr_hero_accent', __( 'No surprises.', 'sklentr' ) );
$pr_h_sub     = skl_opt( 'pr_hero_sub', __( 'Every project starts with a free 30-minute consultation. We’ll scope your idea and recommend the right package. No hidden fees, no surprises.', 'sklentr' ) );
$pr_c1_text   = skl_opt( 'pr_hero_cta1_text', __( 'Book a Free Consultation', 'sklentr' ) );
$pr_c1_link   = skl_opt( 'pr_hero_cta1_link', 'https://calendly.com/sklentr' );
$pr_c2_text   = skl_opt( 'pr_hero_cta2_text', __( 'See Plans', 'sklentr' ) );
$pr_c2_link   = skl_opt( 'pr_hero_cta2_link', '#pr-plans' );
$pr_chips     = $pr_lines( skl_opt( 'pr_hero_chips', "On-time or you get a discount\nYou own 100% of the code\nMilestone-based payments\nFree 30-minute consultation" ) );
$pr_c1_ext    = ( 0 === strpos( $pr_c1_link, 'http' ) && false === strpos( $pr_c1_link, home_url() ) );
?>

<section class="pr-hero" aria-labelledby="pr-hero-title">
	<div class="pr-hero__atmos" aria-hidden="true">
		<span class="pr-hero__aurora pr-hero__aurora--gold"></span>
		<span class="pr-hero__aurora pr-hero__aurora--green"></span>
		<span class="pr-hero__aurora pr-hero__aurora--violet"></span>
		<svg class="pr-hero__grid" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
			<defs>
				<pattern id="prGrid" width="72" height="72" patternUnits="userSpaceOnUse">
					<path d="M72 0H0V72" fill="none" stroke="#ffffff" stroke-opacity=".045" stroke-width="1"/>
				</pattern>
			</defs>
			<rect width="100%" height="100%" fill="url(#prGrid)"/>
		</svg>
		<div class="pr-hero__deco" aria-hidden="true">
			<span class="pr-hero__deco-item pr-hero__deco--tri"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l9 16H3z"/></svg></span>
			<span class="pr-hero__deco-item pr-hero__deco--square"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2.5"/></svg></span>
			<span class="pr-hero__deco-item pr-hero__deco--circle"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/></svg></span>
			<span class="pr-hero__deco-item pr-hero__deco--code"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9 8l-4 4 4 4"/><path d="M15 8l4 4-4 4"/></svg></span>
			<span class="pr-hero__deco-item pr-hero__deco--braces"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M8 4c-2 0-2 2-2 4s0 3-2 3c2 0 2 1 2 3s0 4 2 4"/><path d="M16 4c2 0 2 2 2 4s0 3 2 3c-2 0-2 1-2 3s0 4-2 4"/></svg></span>
			<span class="pr-hero__deco-item pr-hero__deco--terminal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4.5" width="18" height="15" rx="2"/><path d="M7 9.5l3 3-3 3"/><path d="M13 15.5h4"/></svg></span>
			<span class="pr-hero__deco-item pr-hero__deco--db"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5.5" rx="7.5" ry="2.8"/><path d="M4.5 5.5v6c0 1.55 3.36 2.8 7.5 2.8s7.5-1.25 7.5-2.8v-6"/><path d="M4.5 11.5v6c0 1.55 3.36 2.8 7.5 2.8s7.5-1.25 7.5-2.8v-6"/></svg></span>
			<span class="pr-hero__deco-item pr-hero__deco--gear"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3.2"/><path d="M12 2.5v3M12 18.5v3M2.5 12h3M18.5 12h3M5.2 5.2l2.1 2.1M16.7 16.7l2.1 2.1M18.8 5.2l-2.1 2.1M7.3 16.7l-2.1 2.1"/></svg></span>
			<span class="pr-hero__deco-item pr-hero__deco--cloud"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M7 18h10a4 4 0 0 0 .5-7.97A5.5 5.5 0 0 0 6.5 9 4 4 0 0 0 7 18z"/></svg></span>
		</div>

	</div>

	<div class="skl-container">
		<div class="pr-hero__inner" data-reveal>
			<p class="pr-hero__eyebrow"><span class="pr-hero__eyebrow-dot" aria-hidden="true"></span><?php echo esc_html( $pr_h_eyebrow ); ?></p>

			<h1 class="pr-hero__title" id="pr-hero-title">
				<?php echo esc_html( $pr_h_title ); ?>
				<?php if ( $pr_h_accent ) : ?><span class="pr-hero__accent"><?php echo esc_html( $pr_h_accent ); ?></span><?php endif; ?>
			</h1>

			<?php if ( $pr_h_sub ) : ?><p class="pr-hero__sub"><?php echo esc_html( $pr_h_sub ); ?></p><?php endif; ?>

			<div class="pr-hero__actions">
				<a class="skl-btn skl-btn--gold" href="<?php echo esc_url( $pr_c1_link ); ?>"<?php echo $pr_c1_ext ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
					<?php echo esc_html( $pr_c1_text ); ?><span class="skl-btn__arrow" aria-hidden="true">&rarr;</span>
				</a>
				<a class="skl-btn skl-btn--ghost-dark" href="<?php echo esc_url( $pr_c2_link ); ?>"><?php echo esc_html( $pr_c2_text ); ?></a>
			</div>

			<?php if ( ! empty( $pr_chips ) ) : ?>
				<ul class="pr-hero__chips">
					<?php foreach ( $pr_chips as $pr_chip ) : ?>
						<li class="pr-hero__chip"><span class="pr-hero__chip-check" aria-hidden="true"><?php echo $pr_check; // phpcs:ignore WordPress.Security.EscapingOutput -- trusted SVG. ?></span><?php echo esc_html( $pr_chip ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php
/* ================================================================== *
 * 02 — PLANS (3 cards; the badged one is the featured/dark card)
 * ================================================================== */
$pr_p_eyebrow = skl_opt( 'pr_plans_eyebrow', __( 'Packages', 'sklentr' ) );
$pr_p_title   = skl_opt( 'pr_plans_title', __( 'Pick the package that fits your stage.', 'sklentr' ) );
$pr_p_intro   = skl_opt( 'pr_plans_intro', __( 'Fixed scope, fixed price. Prices in CAD — final quote confirmed on your free consultation.', 'sklentr' ) );

$pr_plans_raw = skl_opt( 'pr_plans', "Starter MVP | $5,000 | $5,000 – $10,000 | 2 weeks |  | Perfect for validating your idea quickly | Get Started | https://calendly.com/sklentr | Core Features: 1–3 features;Template-based design;Up to 5 pages / screens;Standard tech stack;2 rounds of revisions;Basic SEO setup;2 weeks post-launch support;Social media setup;!Video production;!Custom UI/UX design\nGrowth MVP | $15,000 | $15,000 – $25,000 | 4 weeks | Most Popular | Most popular for serious founders | Get Started | https://calendly.com/sklentr | Core Features: 5–7 features;Custom UI design;Up to 15 pages / screens;Standard tech stack;3 rounds of revisions;Full SEO setup;1 month post-launch support;Technical documentation;Admin dashboard;Social media setup\nFull-Service | $30,000 | $30,000 – $60,000+ | 8+ weeks |  | Complete product + marketing package | Go Full-Service | https://calendly.com/sklentr | Full product build;Custom UI/UX design;Unlimited pages / screens;Custom tech stack;Unlimited revisions;Full SEO & marketing;3 months post-launch support;Social media management;1 promo video;Priority support" );
$pr_plans = $pr_lines( $pr_plans_raw );
?>

<section class="pr-plans" id="pr-plans" aria-labelledby="pr-plans-title">
	<div class="skl-container">
		<div class="pr-section-head" data-reveal>
			<?php if ( $pr_p_eyebrow ) : ?><p class="skl-eyebrow pr-eyebrow"><?php echo esc_html( $pr_p_eyebrow ); ?></p><?php endif; ?>
			<h2 class="pr-section-head__title" id="pr-plans-title" data-char-fill><?php echo esc_html( $pr_p_title ); ?></h2>
			<?php if ( $pr_p_intro ) : ?><p class="pr-section-head__intro"><?php echo esc_html( $pr_p_intro ); ?></p><?php endif; ?>
		</div>

		<ul class="pr-plans__grid">
			<?php foreach ( $pr_plans as $pr_pl ) :
				$c = $pr_cols( $pr_pl );
				$pl_name     = isset( $c[0] ) ? $c[0] : '';
				$pl_price    = isset( $c[1] ) ? $c[1] : '';
				$pl_range    = isset( $c[2] ) ? $c[2] : '';
				$pl_time     = isset( $c[3] ) ? $c[3] : '';
				$pl_badge    = isset( $c[4] ) ? $c[4] : '';
				$pl_tag      = isset( $c[5] ) ? $c[5] : '';
				$pl_cta_t    = isset( $c[6] ) ? $c[6] : __( 'Get Started', 'sklentr' );
				$pl_cta_l    = isset( $c[7] ) ? $c[7] : 'https://calendly.com/sklentr';
				$pl_feats    = isset( $c[8] ) ? array_filter( array_map( 'trim', explode( ';', $c[8] ) ) ) : array();
				$pl_featured = '' !== $pl_badge;
				$pl_ext      = ( 0 === strpos( $pl_cta_l, 'http' ) && false === strpos( $pl_cta_l, home_url() ) );
				?>
				<li class="pr-plan<?php echo $pl_featured ? ' pr-plan--featured' : ''; ?>" data-reveal>
					<?php if ( $pl_badge ) : ?><span class="pr-plan__badge"><?php echo esc_html( $pl_badge ); ?></span><?php endif; ?>

					<h3 class="pr-plan__name"><?php echo esc_html( $pl_name ); ?></h3>
					<?php if ( $pl_tag ) : ?><p class="pr-plan__tag"><?php echo esc_html( $pl_tag ); ?></p><?php endif; ?>

					<p class="pr-plan__price"><?php echo esc_html( $pl_price ); ?><span class="pr-plan__cur">CAD</span></p>
					<?php if ( $pl_range ) : ?><p class="pr-plan__range"><?php echo esc_html( $pl_range ); ?></p><?php endif; ?>

					<?php if ( $pl_time ) : ?>
						<p class="pr-plan__time"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7.5V12l3 1.8"/></svg><?php echo esc_html( $pl_time ); ?> delivery</p>
					<?php endif; ?>

					<a class="skl-btn <?php echo $pl_featured ? 'skl-btn--gold' : 'skl-btn--dark'; ?> pr-plan__cta" href="<?php echo esc_url( $pl_cta_l ); ?>"<?php echo $pl_ext ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $pl_cta_t ); ?><span class="skl-btn__arrow" aria-hidden="true">&rarr;</span></a>

					<?php if ( ! empty( $pl_feats ) ) : ?>
						<p class="pr-plan__incl-label">What’s included</p>
						<ul class="pr-plan__features">
							<?php foreach ( $pl_feats as $pl_f ) :
								$excluded = ( '!' === substr( $pl_f, 0, 1 ) );
								$label    = $excluded ? trim( substr( $pl_f, 1 ) ) : $pl_f;
								?>
								<li class="pr-plan__feature<?php echo $excluded ? ' is-excluded' : ''; ?>">
									<span class="pr-plan__feature-ico" aria-hidden="true"><?php echo $excluded ? $pr_x : $pr_check; // phpcs:ignore WordPress.Security.EscapingOutput -- trusted SVG. ?></span>
									<?php echo esc_html( $label ); ?>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<?php
/* ================================================================== *
 * 02b — WHAT WE DO (shared with the Services page)
 * ================================================================== */
get_template_part( 'template-parts/section-what-we-do' );

/* ================================================================== *
 * 03 — GUARANTEES (the trust signals)
 * ================================================================== */
$pr_g_eyebrow = skl_opt( 'pr_guar_eyebrow', __( 'Every Plan Includes', 'sklentr' ) );
$pr_g_title   = skl_opt( 'pr_guar_title', __( 'Guarantees, not fine print.', 'sklentr' ) );
$pr_guars = $pr_lines( skl_opt( 'pr_guar_items', "On-Time Delivery | We guarantee our timelines. If we’re late, you get a discount.\nCode Ownership | You own everything. No licensing, no strings attached.\nFlexible Payments | Split payments across milestones. No full amount upfront.\nFree Consultation | A 30-minute call to scope your project. No obligations." ) );

?>

<section class="process pr-guarantees" aria-labelledby="pr-guar-title">
	<div class="skl-container">

		<div class="process__head" data-reveal>
			<?php if ( $pr_g_eyebrow ) : ?><p class="process__eyebrow skl-eyebrow"><?php echo esc_html( $pr_g_eyebrow ); ?></p><?php endif; ?>
			<h2 class="process__title" id="pr-guar-title" data-char-fill><?php echo esc_html( $pr_g_title ); ?></h2>
		</div>

		<?php if ( $pr_guars ) : ?>
			<div class="process__timeline">
				<ol class="process__steps" style="--count:<?php echo esc_attr( max( 1, count( $pr_guars ) ) ); ?>">
					<?php foreach ( $pr_guars as $pr_gk => $pr_gl ) :
						$g       = $pr_cols( $pr_gl );
						$g_title = isset( $g[0] ) ? $g[0] : '';
						$g_text  = isset( $g[1] ) ? $g[1] : '';
						?>
						<li class="process-step" style="--i:<?php echo esc_attr( (int) $pr_gk ); ?>">
							<span class="process-step__num" aria-hidden="true"><?php echo esc_html( (string) ( (int) $pr_gk + 1 ) ); ?></span>
							<h3 class="process-step__title"><?php echo esc_html( $g_title ); ?></h3>
							<?php if ( $g_text ) : ?><p class="process-step__desc"><?php echo esc_html( $g_text ); ?></p><?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ol>
			</div>
		<?php endif; ?>

	</div>
</section>

<?php
/* ================================================================== *
 * 04 — FAQ (same accordion UI as the homepage / startup-visa; faq.js)
 * ================================================================== */
$pr_faq_title = skl_opt( 'pr_faq_title', __( 'Common Questions', 'sklentr' ) );
$pr_faq = $pr_lines( skl_opt( 'pr_faq_items', "How do I know which package is right for me? | Book a free 30-minute consultation. We’ll discuss your idea, timeline, and budget, then recommend the best package. Most founders validating an idea start with Starter; those building for investors or visa applications go with Growth; established businesses needing the full package choose Full-Service.\nWhat’s included in the price? | Everything needed to launch: design, development, the agreed feature set, deployment, and post-launch support — plus full source-code ownership. Each package’s inclusions are listed above; we confirm the exact scope on your free consultation.\nDo you offer payment plans? | Yes. Payments are split across project milestones — no full amount upfront. We map the schedule to your build on the consultation call.\nWhat if I need features not listed? | No problem. Every build is scoped to your idea. If you need something beyond a package, we quote it transparently on the free consultation and fold it into your roadmap.\nHow fast can you really deliver? | Starter ships in about 2 weeks, Growth in about 4 weeks, and Full-Service in 8+ weeks. We guarantee our timelines — if we’re late, you get a discount.\nWhat happens after launch? | Every package includes post-launch support (2 weeks to 3 months depending on the tier) for fixes and tweaks. Many founders stay on for the next build.\nDo I own the code? | Yes — 100%. You receive full source-code ownership and IP rights on delivery. No licensing, no lock-in, no strings attached.\nWhat tech stack do you use? | A modern, proven stack — Next.js, React, React Native, Flutter, Laravel, PostgreSQL — with AI (Gemini, OpenAI, Claude) woven in where it adds real value." ) );

$pr_faq_help_cta  = skl_opt( 'pr_faq_help_cta_text', __( 'Book My Free Consultation', 'sklentr' ) );
$pr_faq_help_link = skl_opt( 'pr_faq_help_cta_link', 'https://calendly.com/sklentr' );
$pr_faq_eyebrow    = skl_opt( 'pr_faq_eyebrow', __( 'FAQ', 'sklentr' ) );
$pr_faq_help_title = skl_opt( 'pr_faq_help_title', __( 'Still deciding?', 'sklentr' ) );
$pr_faq_help_text  = skl_opt( 'pr_faq_help_text', __( 'Book a free 30-minute call — we’ll scope your idea and recommend the right package.', 'sklentr' ) );

$pr_faq_pairs = array();
foreach ( $pr_faq as $pr_fl ) {
	$fp = $pr_cols( $pr_fl );
	$pr_faq_pairs[] = array( 'q' => $fp[0], 'a' => isset( $fp[1] ) ? $fp[1] : '' );
}
$pr_faq_schema = array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array() );
foreach ( $pr_faq_pairs as $fp ) {
	$pr_faq_schema['mainEntity'][] = array( '@type' => 'Question', 'name' => $fp['q'], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $fp['a'] ) );
}
$pr_faq_ext = ( 0 === strpos( $pr_faq_help_link, 'http' ) && false === strpos( $pr_faq_help_link, home_url() ) );
?>

<section class="faq pr-faq" id="faq" aria-labelledby="pr-faq-title">
	<div class="faq__atmos" aria-hidden="true">
		<span class="faq__glow faq__glow--green"></span>
		<span class="faq__glow faq__glow--gold"></span>
	</div>

	<div class="skl-container">
		<div class="faq__inner">

			<div class="faq__aside">
				<p class="faq__eyebrow skl-eyebrow"><?php echo esc_html( $pr_faq_eyebrow ); ?></p>
				<h2 class="faq__title" id="pr-faq-title" data-char-fill="dark"><?php echo esc_html( $pr_faq_title ); ?></h2>

				<div class="faq__help">
					<span class="faq__help-icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M9.2 9.3a2.8 2.8 0 0 1 5.4 1c0 1.9-2.8 2.5-2.8 2.5"/><path d="M12 17h.01"/></svg>
					</span>
					<h3 class="faq__help-title"><?php echo esc_html( $pr_faq_help_title ); ?></h3>
					<p class="faq__help-text"><?php echo esc_html( $pr_faq_help_text ); ?></p>
					<?php if ( $pr_faq_help_cta && $pr_faq_help_link ) : ?>
						<a class="skl-btn skl-btn--gold" href="<?php echo esc_url( $pr_faq_help_link ); ?>"<?php echo $pr_faq_ext ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $pr_faq_help_cta ); ?><span class="skl-btn__arrow" aria-hidden="true">&rarr;</span></a>
					<?php endif; ?>
				</div>
			</div>

			<div class="faq__list" data-faq-list>
				<?php foreach ( $pr_faq_pairs as $pr_idx => $fp ) : $pid = 'pr-faq-panel-' . $pr_idx; ?>
					<div class="faq-item" data-reveal>
						<h3 class="faq-item__heading">
							<button class="faq-item__q" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $pid ); ?>">
								<span class="faq-item__q-text"><?php echo esc_html( $fp['q'] ); ?></span>
								<span class="faq-item__icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
								</span>
							</button>
						</h3>
						<div class="faq-item__panel" id="<?php echo esc_attr( $pid ); ?>" role="region">
							<div class="faq-item__answer"><p><?php echo esc_html( $fp['a'] ); ?></p></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

		</div>
	</div>

	<script type="application/ld+json"><?php echo wp_json_encode( $pr_faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); ?></script>
</section>

<?php
/* Shared closing band + footer — same as the rest of the site (unchanged). */
get_template_part( 'template-parts/home/final-cta' );

get_footer();
