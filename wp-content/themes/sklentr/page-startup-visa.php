<?php
/**
 * Template for the standalone Startup Visa page (slug: startup-visa).
 *
 * Content mirrors https://www.sklentr.com/startup-visa. Built in the Sklentr
 * design system with a hero that is deliberately DIFFERENT from the homepage
 * and services heroes (a "visa document + APPROVED stamp" visual). Reuses the
 * shared header, Final-CTA band, and footer. All copy is editable from wp-admin
 * (Sklentr Settings → "Startup Visa Page"); list rows are one-per-line, some
 * pipe-delimited ("A | B | C"). Other pages/sections are untouched.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/* Helpers: split a textarea into trimmed lines, and a line into piped columns. */
$sv_lines = static function ( $raw ) {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $raw ) ) ) );
};
$sv_cols = static function ( $line ) {
	return array_map( 'trim', explode( '|', (string) $line ) );
};
$sv_check = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6.5 9.5 17 4 11.5"/></svg>';
$sv_leaf  = function_exists( 'skl_maple_leaf_svg' ) ? skl_maple_leaf_svg() : '';

/* ================================================================== *
 * 01 — HERO (distinct: visa document + APPROVED stamp)
 * ================================================================== */
$sv_h_eyebrow = skl_opt( 'sv_hero_eyebrow', __( 'Startup Visa Program', 'sklentr' ) );
$sv_h_title   = skl_opt( 'sv_hero_title', __( 'Your Startup Visa Application Needs More Than an Idea', 'sklentr' ) );
$sv_h_body    = skl_opt( 'sv_hero_body', __( 'The SUV program is paused. When it resumes, competition will be fierce. We build launch-ready MVPs that prove business viability, impress designated organizations, and strengthen your path to Canadian PR.', 'sklentr' ) );
$sv_c1_text   = skl_opt( 'sv_cta1_text', __( 'Book My Free Consultation', 'sklentr' ) );
$sv_c1_link   = skl_opt( 'sv_cta1_link', '#contact' );
$sv_c2_text   = skl_opt( 'sv_cta2_text', __( 'See What We Build', 'sklentr' ) );
$sv_c2_link   = skl_opt( 'sv_cta2_link', '#sv-work' );
$sv_stats     = $sv_lines( skl_opt( 'sv_stats', "15|+|SUV MVPs Built\n4|-Week|Delivery\n100|%|On-Time Delivery" ) );
$sv_c1_ext    = ( 0 === strpos( $sv_c1_link, 'http' ) && false === strpos( $sv_c1_link, home_url() ) );
?>

<section class="svisa-hero" aria-labelledby="svisa-hero-title">
	<div class="svisa-hero__atmos" aria-hidden="true">
		<svg class="svisa-hero__pattern" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
			<defs>
				<pattern id="svPlusPat" width="48" height="48" patternUnits="userSpaceOnUse">
					<path d="M24 17.5v13M17.5 24h13" stroke="#F3B351" stroke-opacity=".55" stroke-width="1.1" stroke-linecap="round"/>
				</pattern>
			</defs>
			<rect width="100%" height="100%" fill="url(#svPlusPat)"/>
		</svg>
		<span class="svisa-hero__glow svisa-hero__glow--gold"></span>
		<span class="svisa-hero__glow svisa-hero__glow--green"></span>
		<span class="svisa-hero__leaf svisa-hero__leaf--1"><?php echo $sv_leaf; // phpcs:ignore WordPress.Security.EscapingOutput -- trusted SVG. ?></span>
		<span class="svisa-hero__leaf svisa-hero__leaf--2"><?php echo $sv_leaf; // phpcs:ignore WordPress.Security.EscapingOutput -- trusted SVG. ?></span>
	</div>

	<div class="skl-container">
		<div class="svisa-hero__inner">

			<div class="svisa-hero__copy" data-reveal>
				<p class="svisa-hero__eyebrow">
					<span class="svisa-hero__eyebrow-leaf" aria-hidden="true"><?php echo $sv_leaf; // phpcs:ignore WordPress.Security.EscapingOutput -- trusted SVG. ?></span>
					<?php echo esc_html( $sv_h_eyebrow ); ?>
				</p>
				<h1 class="svisa-hero__title" id="svisa-hero-title"><?php echo esc_html( $sv_h_title ); ?></h1>
				<p class="svisa-hero__body"><?php echo esc_html( $sv_h_body ); ?></p>

				<div class="svisa-hero__actions">
					<a class="skl-btn skl-btn--gold" href="<?php echo esc_url( $sv_c1_link ); ?>"<?php echo $sv_c1_ext ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
						<?php echo esc_html( $sv_c1_text ); ?>
						<span class="skl-btn__arrow" aria-hidden="true">&rarr;</span>
					</a>
					<a class="skl-btn skl-btn--ghost-dark" href="<?php echo esc_url( $sv_c2_link ); ?>"><?php echo esc_html( $sv_c2_text ); ?></a>
				</div>

				<?php if ( ! empty( $sv_stats ) ) : ?>
					<ul class="svisa-hero__stats">
						<?php foreach ( $sv_stats as $sv_s ) :
							$sv_p = $sv_cols( $sv_s );
							$sv_num = isset( $sv_p[0] ) ? $sv_p[0] : '';
							$sv_suf = isset( $sv_p[1] ) ? $sv_p[1] : '';
							$sv_lab = isset( $sv_p[2] ) ? $sv_p[2] : '';
							?>
							<li class="svisa-hero__stat">
								<span class="svisa-hero__stat-num"><span class="svisa-hero__stat-val" data-count-to="<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $sv_num ) ); ?>"><?php echo esc_html( $sv_num ); ?></span><?php echo esc_html( $sv_suf ); ?></span>
								<span class="svisa-hero__stat-label"><?php echo esc_html( $sv_lab ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

		</div>
	</div>
</section>

<?php
/* ================================================================== *
 * 02 — THE HARD TRUTH
 * ================================================================== */
$sv_truth_title = skl_opt( 'sv_truth_title', __( 'Ideas Don’t Get Visas. Products Do.', 'sklentr' ) );
$sv_truth_intro = skl_opt( 'sv_truth_intro', __( 'The program is paused — and when it resumes, the bar goes up. Here’s what founders are up against.', 'sklentr' ) );
$sv_truth = $sv_lines( skl_opt( 'sv_truth_points', "The program is paused — when it resumes, IRCC will scrutinize harder\nDesignated organizations see hundreds of pitch decks monthly\nWithout a working product, you’re just another idea\nNo technical co-founder means you’re stuck\nCheap freelancers deliver broken code that embarrasses you" ) );
?>

<section class="svisa-section svisa-section--dark svisa-truth" aria-labelledby="svisa-truth-title">
	<div class="skl-container">
		<div class="svisa-head svisa-head--tight" data-reveal>
			<p class="skl-eyebrow svisa-eyebrow svisa-eyebrow--on-dark">The Hard Truth</p>
			<h2 class="svisa-head__title svisa-head__title--on-dark" id="svisa-truth-title" data-char-fill="dark"><?php echo esc_html( $sv_truth_title ); ?></h2>
			<?php if ( $sv_truth_intro ) : ?><p class="svisa-head__intro svisa-head__intro--on-dark"><?php echo esc_html( $sv_truth_intro ); ?></p><?php endif; ?>
		</div>
		<ul class="svisa-truth__list">
			<?php foreach ( $sv_truth as $sv_t ) : ?>
				<li class="svisa-truth__item" data-reveal>
					<span class="svisa-truth__x" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></span>
					<?php echo esc_html( $sv_t ); ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<?php
/* ================================================================== *
 * 03 — WHY A WORKING MVP CHANGES EVERYTHING
 * ================================================================== */
$sv_why_title = skl_opt( 'sv_why_title', __( 'Why a Working MVP Changes Everything', 'sklentr' ) );
$sv_why = $sv_lines( skl_opt( 'sv_why_points', "IRCC wants proof of a “qualifying business”\nDesignated organizations invest in execution\nShows market validation & technical feasibility\nProves founder commitment beyond words\nDifferentiates you from 95% of applicants" ) );
?>

<section class="svisa-section svisa-why" aria-labelledby="svisa-why-title">
	<div class="skl-container">
		<div class="svisa-head" data-reveal>
			<p class="skl-eyebrow svisa-eyebrow">Why It Matters</p>
			<h2 class="svisa-head__title" id="svisa-why-title" data-char-fill><?php echo esc_html( $sv_why_title ); ?></h2>
		</div>
		<ul class="svisa-why__grid">
			<?php foreach ( $sv_why as $sv_wk => $sv_w ) : ?>
				<li class="svisa-why__item" data-reveal style="--i:<?php echo esc_attr( $sv_wk ); ?>">
					<span class="svisa-why__index" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $sv_wk + 1 ) ); ?></span>
					<span class="svisa-why__check" aria-hidden="true"><?php echo $sv_check; // phpcs:ignore WordPress.Security.EscapingOutput -- trusted SVG. ?></span>
					<p class="svisa-why__text"><?php echo esc_html( $sv_w ); ?></p>
					<span class="svisa-why__bar" aria-hidden="true"></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<?php
/* ================================================================== *
 * PRICING (Growth MVP package) — "The Startup Visa MVP Package"
 * Placed directly after "Why It Matters" per content order.
 * ================================================================== */
$sv_pr_title    = skl_opt( 'sv_price_title', __( 'The Startup Visa MVP Package', 'sklentr' ) );
$sv_pr_badge    = skl_opt( 'sv_price_badge', __( 'Recommended for SUV', 'sklentr' ) );
$sv_pr_name     = skl_opt( 'sv_price_name', __( 'Growth MVP', 'sklentr' ) );
$sv_pr_amount   = skl_opt( 'sv_price_amount', __( '$15,000', 'sklentr' ) );
$sv_pr_cur      = skl_opt( 'sv_price_currency', __( 'CAD', 'sklentr' ) );
$sv_pr_tagline  = skl_opt( 'sv_price_tagline', __( 'Everything you need to impress IRCC and designated organizations.', 'sklentr' ) );
$sv_pr_timeline = skl_opt( 'sv_price_timeline', __( '4 weeks delivery', 'sklentr' ) );
$sv_pr_note     = skl_opt( 'sv_price_note', __( 'Payment plans available', 'sklentr' ) );
$sv_pr_compare  = skl_opt( 'sv_price_compare', __( 'Save money & time: agencies charge $50,000+. Freelancers take 6+ months.', 'sklentr' ) );
$sv_pr_cta_t    = skl_opt( 'sv_price_cta_text', __( 'Book My Free Consultation', 'sklentr' ) );
$sv_pr_cta_l    = skl_opt( 'sv_price_cta_link', '#contact' );
$sv_pr_incl     = $sv_lines( skl_opt( 'sv_price_included', "Working Web/Mobile Application\nUser Authentication & Core Features\nAdmin Dashboard\nTechnical Documentation\nPitch Deck Assets\nFull Source Code\nProduction Deployment\n1 Month Post-Launch Support" ) );
$sv_pr_ext      = ( 0 === strpos( $sv_pr_cta_l, 'http' ) && false === strpos( $sv_pr_cta_l, home_url() ) );
?>

<section class="svisa-section svisa-section--dark svisa-price" id="sv-pricing" aria-labelledby="svisa-price-title">
	<div class="skl-container">
		<div class="svisa-head svisa-head--tight" data-reveal>
			<p class="skl-eyebrow svisa-eyebrow svisa-eyebrow--on-dark">Investment</p>
			<h2 class="svisa-head__title svisa-head__title--on-dark" id="svisa-price-title" data-char-fill="dark"><?php echo esc_html( $sv_pr_title ); ?></h2>
		</div>

		<div class="svisa-price__card" data-reveal>
			<div class="svisa-price__main">
				<?php if ( $sv_pr_badge ) : ?><span class="svisa-price__badge"><span class="svisa-price__badge-leaf"><?php echo $sv_leaf; // phpcs:ignore WordPress.Security.EscapingOutput -- trusted SVG. ?></span><?php echo esc_html( $sv_pr_badge ); ?></span><?php endif; ?>
				<h3 class="svisa-price__name"><?php echo esc_html( $sv_pr_name ); ?></h3>
				<p class="svisa-price__amount"><?php echo esc_html( $sv_pr_amount ); ?> <span class="svisa-price__cur"><?php echo esc_html( $sv_pr_cur ); ?></span></p>
				<?php if ( $sv_pr_tagline ) : ?><p class="svisa-price__tagline"><?php echo esc_html( $sv_pr_tagline ); ?></p><?php endif; ?>
				<?php if ( $sv_pr_timeline ) : ?><p class="svisa-price__timeline"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7.5V12l3 1.8"/></svg><?php echo esc_html( $sv_pr_timeline ); ?></p><?php endif; ?>
				<a class="skl-btn skl-btn--gold svisa-price__cta" target="_blank" href="https://calendly.com/sklentr"<?php echo $sv_pr_ext ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $sv_pr_cta_t ); ?><span class="skl-btn__arrow" aria-hidden="true">&rarr;</span></a>
				<?php if ( $sv_pr_note ) : ?><p class="svisa-price__pay"><?php echo esc_html( $sv_pr_note ); ?></p><?php endif; ?>
			</div>
			<div class="svisa-price__aside">
				<p class="svisa-price__incl-label">What’s included</p>
				<ul class="svisa-price__incl">
					<?php foreach ( $sv_pr_incl as $sv_in ) : ?>
						<li><span class="svisa-price__check" aria-hidden="true"><?php echo $sv_check; // phpcs:ignore WordPress.Security.EscapingOutput -- trusted SVG. ?></span><?php echo esc_html( $sv_in ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php if ( $sv_pr_compare ) : ?><p class="svisa-price__compare" data-reveal><?php echo esc_html( $sv_pr_compare ); ?></p><?php endif; ?>
	</div>
</section>

<?php
/* ================================================================== *
 * 04 — WHAT YOU GET (complete MVP package)
 * ================================================================== */
$sv_get_title = skl_opt( 'sv_get_title', __( 'Your Complete MVP Package', 'sklentr' ) );
$sv_get_cta_t = skl_opt( 'sv_get_cta_text', __( 'See Full Package Details', 'sklentr' ) );
$sv_get_cta_l = skl_opt( 'sv_get_cta_link', '#sv-pricing' );
$sv_get = $sv_lines( skl_opt( 'sv_get_items', "Working Web/Mobile Application | Fully functional product ready to demo\nUser Authentication & Core Features | Secure login and key features\nAdmin Dashboard | Manage your product with ease\nTechnical Documentation | Complete docs for your visa application\nPitch Deck Assets | Screenshots, demo video, presentation materials\nFull Source Code | You own it 100% — no strings attached\nProduction Deployment | Live and accessible to anyone\n1 Month Post-Launch Support | We’ve got your back after launch" ) );

// Distinct, content-matched glyphs — one per card. Styled (clipped shape +
// colour + hover scale) in CSS, mirroring the Services "Built to last" perks.
$sv_get_o     = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';
$sv_get_icons = array(
	$sv_get_o . '<rect x="3" y="4" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4"/></svg>',                                                   // Working app
	$sv_get_o . '<path d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6z"/><path d="M9 12l2 2 4-4"/></svg>',                                             // Auth / shield-check
	$sv_get_o . '<rect x="3.5" y="3.5" width="7" height="7" rx="1.6"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.6"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.6"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.6"/></svg>', // Dashboard / grid
	$sv_get_o . '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/><path d="M9 13h6M9 17h4"/></svg>',          // Docs / file
	$sv_get_o . '<rect x="3" y="4" width="18" height="12" rx="1.5"/><path d="M12 16v4M8 20h8"/><path d="M7.5 11l2.5-2.5 2 2 3.5-3.5"/></svg>',          // Pitch deck
	$sv_get_o . '<path d="M8.5 8.5 4 12l4.5 3.5"/><path d="M15.5 8.5 20 12l-4.5 3.5"/><path d="M13.5 6l-3 12"/></svg>',                                 // Source code
	$sv_get_o . '<path d="M12 3c3 1 5 4 5 8l-2.5 2.5H9.5L7 13c0-4 2-7 5-10z"/><circle cx="12" cy="9.5" r="1.3"/><path d="M9.5 15.5 8 20l3-1.5M14.5 15.5 16 20l-3-1.5"/></svg>', // Deployment / rocket
	$sv_get_o . '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="3.4"/><path d="M5 5l4.2 4.2M14.8 14.8 19 19M19 5l-4.2 4.2M9.2 14.8 5 19"/></svg>', // Support / lifebuoy
);
?>

<section class="svisa-section svisa-section--alt svisa-get" aria-labelledby="svisa-get-title">
	<div class="skl-container">
		<div class="svisa-head" data-reveal>
			<p class="skl-eyebrow svisa-eyebrow">What You Get</p>
			<h2 class="svisa-head__title" id="svisa-get-title" data-char-fill><?php echo esc_html( $sv_get_title ); ?></h2>
		</div>
		<ul class="svisa-get__grid">
			<?php foreach ( $sv_get as $sv_gk => $sv_g ) :
				$sv_gp = $sv_cols( $sv_g );
					$sv_gico = isset( $sv_get_icons[ $sv_gk % count( $sv_get_icons ) ] ) ? $sv_get_icons[ $sv_gk % count( $sv_get_icons ) ] : $sv_check;
				?>
				<li class="svisa-get__card" data-reveal>
					<span class="svisa-get__check" aria-hidden="true"><?php echo $sv_gico; // phpcs:ignore WordPress.Security.EscapingOutput -- trusted SVG. ?></span>
					<h3 class="svisa-get__title"><?php echo esc_html( $sv_gp[0] ); ?></h3>
					<?php if ( isset( $sv_gp[1] ) ) : ?><p class="svisa-get__desc"><?php echo esc_html( $sv_gp[1] ); ?></p><?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php if ( $sv_get_cta_t ) : ?>
			<div class="svisa-head__cta" data-reveal>
				<a class="skl-btn skl-btn--dark" href="<?php echo esc_url( $sv_get_cta_l ); ?>"><?php echo esc_html( $sv_get_cta_t ); ?><span class="skl-btn__arrow" aria-hidden="true">&rarr;</span></a>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
/* ================================================================== *
 * 05 — OUR TRACK RECORD (MVPs for visa applicants)
 * ================================================================== */
$sv_work_title = skl_opt( 'sv_work_title', __( 'MVPs We Built for Visa Applicants', 'sklentr' ) );
$sv_work = $sv_lines( skl_opt( 'sv_work_items', "AI Farming | AgriTech | Urban-farming solution with AI-powered guides. Currently in funding talks.\nKindredCare | Healthcare | AI caregiver-matching platform connecting families with qualified care providers\nGet Takaful | FinTech / Blockchain | Shariah-compliant insurance platform with blockchain transparency" ) );
$sv_work_imgs = array( 'agritech', 'care', 'fintech' );
?>

<section class="svisa-section svisa-work" id="sv-work" aria-labelledby="svisa-work-title">
	<div class="skl-container">
		<div class="svisa-head" data-reveal>
			<p class="skl-eyebrow svisa-eyebrow">Our Track Record</p>
			<h2 class="svisa-head__title" id="svisa-work-title" data-char-fill><?php echo esc_html( $sv_work_title ); ?></h2>
		</div>
		<ul class="svisa-work__grid">
			<?php foreach ( $sv_work as $sv_k => $sv_w_line ) :
				$sv_wp  = $sv_cols( $sv_w_line );
				$sv_img = get_theme_file_uri( 'assets/images/work/' . ( isset( $sv_work_imgs[ $sv_k ] ) ? $sv_work_imgs[ $sv_k ] : 'data' ) . '.jpg' );
				?>
				<li class="svisa-work__card" data-reveal>
					<span class="svisa-work__media"><img src="<?php echo esc_url( $sv_img ); ?>" alt="<?php echo esc_attr( $sv_wp[0] ); ?>" width="640" height="360" loading="lazy" decoding="async"></span>
					<div class="svisa-work__body">
						<?php if ( isset( $sv_wp[1] ) ) : ?><span class="svisa-work__tag"><?php echo esc_html( $sv_wp[1] ); ?></span><?php endif; ?>
						<h3 class="svisa-work__title"><?php echo esc_html( $sv_wp[0] ); ?></h3>
						<?php if ( isset( $sv_wp[2] ) ) : ?><p class="svisa-work__desc"><?php echo esc_html( $sv_wp[2] ); ?></p><?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<?php
/* ================================================================== *
 * 06 — VISA BENEFITS + 07 FUNDING BENEFITS (two columns)
 * ================================================================== */
$sv_visa_title = skl_opt( 'sv_visa_title', __( 'How Your MVP Strengthens Your Visa', 'sklentr' ) );
$sv_visa_badge = skl_opt( 'sv_visa_badge', __( 'IRCC Approved Evidence', 'sklentr' ) );
$sv_visa = $sv_lines( skl_opt( 'sv_visa_points', "Proves business viability to IRCC\nDemonstrates execution capability\nProvides evidence for interviews\nTechnical docs support your business plan\nShows commitment beyond just an idea" ) );
$sv_fund_title = skl_opt( 'sv_fund_title', __( 'VCs and Angels Don’t Fund Ideas. They Fund Traction.', 'sklentr' ) );
$sv_fund = $sv_lines( skl_opt( 'sv_fund_points', "Demo beats slide deck every time\nValidates market fit with real users\nShows you can ship, not just talk\nCreates investor confidence\nEarly traction = better valuations" ) );
?>

<section class="svisa-section svisa-section--alt svisa-benefits" aria-labelledby="svisa-benefits-title">
	<div class="skl-container">
		<h2 id="svisa-benefits-title" class="screen-reader-text"><?php esc_html_e( 'Benefits', 'sklentr' ); ?></h2>
		<div class="svisa-benefits__grid">

			<div class="svisa-benefit svisa-benefit--visa" data-reveal>
				<span class="svisa-benefit__badge"><span class="svisa-benefit__badge-leaf"><?php echo $sv_leaf; // phpcs:ignore WordPress.Security.EscapingOutput -- trusted SVG. ?></span><?php echo esc_html( $sv_visa_badge ); ?></span>
				<h3 class="svisa-benefit__title"><?php echo esc_html( $sv_visa_title ); ?></h3>
				<ul class="svisa-benefit__list">
					<?php foreach ( $sv_visa as $sv_v ) : ?>
						<li><span class="svisa-benefit__check" aria-hidden="true"><?php echo $sv_check; // phpcs:ignore WordPress.Security.EscapingOutput -- trusted SVG. ?></span><?php echo esc_html( $sv_v ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="svisa-benefit svisa-benefit--fund" data-reveal>
				<span class="svisa-benefit__badge svisa-benefit__badge--green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 16l5-5 3.5 3 6.5-8"/><path d="M15 6h5v5"/></svg><?php esc_html_e( 'Investor Traction', 'sklentr' ); ?></span>
				<h3 class="svisa-benefit__title"><?php echo esc_html( $sv_fund_title ); ?></h3>
				<ul class="svisa-benefit__list">
					<?php foreach ( $sv_fund as $sv_f ) : ?>
						<li><span class="svisa-benefit__check svisa-benefit__check--green" aria-hidden="true"><?php echo $sv_check; // phpcs:ignore WordPress.Security.EscapingOutput -- trusted SVG. ?></span><?php echo esc_html( $sv_f ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>

		</div>
	</div>
</section>

<?php
/* ================================================================== *
 * 08 — OUR PROCESS (4 weeks)
 * ================================================================== */
$sv_proc_title = skl_opt( 'sv_proc_title', __( 'From Idea to Launch in 4 Weeks', 'sklentr' ) );
$sv_proc_guar  = skl_opt( 'sv_proc_guarantee', __( 'We guarantee delivery before your visa deadline.', 'sklentr' ) );
$sv_proc = $sv_lines( skl_opt( 'sv_proc_steps', "Week 1 | Discovery & Planning | We understand your vision and define the MVP scope\nWeek 2 | Design & Architecture | UI/UX design and technical architecture finalized\nWeek 3 | Development Sprint | Core features built with regular progress updates\nWeek 4 | Testing & Launch | QA, deployment, and your product goes live" ) );
?>

<?php /* Same UI as the homepage "How We Work" (.process staircase + process.js), with the Startup Visa content. */ ?>
<section class="process" id="how-we-work" aria-labelledby="sv-proc-title">
	<div class="skl-container">

		<div class="process__head" data-reveal>
			<p class="process__eyebrow skl-eyebrow"><?php esc_html_e( 'Our Process', 'sklentr' ); ?></p>
			<h2 class="process__title" id="sv-proc-title" data-char-fill><?php echo esc_html( $sv_proc_title ); ?></h2>
		</div>

		<?php if ( ! empty( $sv_proc ) ) : ?>
			<div class="process__timeline">
				<ol class="process__steps" style="--count:<?php echo esc_attr( count( $sv_proc ) ); ?>">
					<?php foreach ( $sv_proc as $sv_k => $sv_step ) :
						$sv_sp    = $sv_cols( $sv_step );
						$sv_stitle = isset( $sv_sp[1] ) && '' !== $sv_sp[1] ? $sv_sp[1] : ( isset( $sv_sp[0] ) ? $sv_sp[0] : '' );
						?>
						<li class="process-step" style="--i:<?php echo esc_attr( $sv_k ); ?>">
							<span class="process-step__num" aria-hidden="true"><?php echo esc_html( (string) ( $sv_k + 1 ) ); ?></span>
							<h3 class="process-step__title"><?php echo esc_html( $sv_stitle ); ?></h3>
							<?php if ( isset( $sv_sp[2] ) ) : ?>
								<p class="process-step__desc"><?php echo esc_html( $sv_sp[2] ); ?></p>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ol>
			</div>
		<?php endif; ?>

		<?php if ( $sv_proc_guar ) : ?>
			<p class="svisa-proc__guarantee" data-reveal><span class="svisa-proc__guarantee-leaf"><?php echo $sv_leaf; // phpcs:ignore WordPress.Security.EscapingOutput -- trusted SVG. ?></span><?php echo esc_html( $sv_proc_guar ); ?></p>
		<?php endif; ?>

	</div>
</section>

<?php
/* ================================================================== *
 * 10 — TESTIMONIALS
 * ================================================================== */
$sv_testi_title = skl_opt( 'sv_testi_title', __( 'Founders Who Trusted Us', 'sklentr' ) );
$sv_testi = $sv_lines( skl_opt( 'sv_testi_items', "Sklentr understood our vision for AI-powered caregiving matching from day one. They asked the right questions during discovery and built exactly what we needed to test our concept. | Tanzila Rawnack | CEO, KindredCare\nBuilding a regulatory compliance platform felt overwhelming until we found Sklentr. They simplified our complex requirements into a clean, functional MVP that we can show to clients. | Sudhir Biswas | CEO, Roboreg\nSklentr translated our food-waste solution into a real product we could touch and feel. The urban-farming guides and community features work seamlessly. | Monzur Khan | CEO, AI Farming" ) );
?>

<section class="svisa-section svisa-testi" aria-labelledby="svisa-testi-title">
	<div class="skl-container">
		<div class="svisa-head" data-reveal>
			<p class="skl-eyebrow svisa-eyebrow">Testimonials</p>
			<h2 class="svisa-head__title" id="svisa-testi-title"><?php echo esc_html( $sv_testi_title ); ?></h2>
		</div>
		<ul class="svisa-testi__grid">
			<?php $sv_testi_count = count( $sv_testi );
				foreach ( $sv_testi as $sv_tk => $sv_tl ) :
				$sv_tp = $sv_cols( $sv_tl );
				$sv_name = isset( $sv_tp[1] ) ? $sv_tp[1] : '';
				$sv_role = isset( $sv_tp[2] ) ? $sv_tp[2] : '';
				$sv_init = $sv_name ? mb_strtoupper( mb_substr( $sv_name, 0, 1 ) ) : '';
					// Spotlight the middle card as a dark anchor when there are exactly three.
					$sv_feat = ( 3 === $sv_testi_count && 1 === $sv_tk ) ? ' svisa-testi__card--featured' : '';
				?>
				<li class="svisa-testi__card<?php echo esc_attr( $sv_feat ); ?>" data-reveal style="--i:<?php echo esc_attr( $sv_tk ); ?>">
					<span class="svisa-testi__quote" aria-hidden="true">&ldquo;</span>
						<span class="svisa-testi__stars" role="img" aria-label="<?php esc_attr_e( 'Rated 5 out of 5', 'sklentr' ); ?>"><?php for ( $sv_s = 0; $sv_s < 5; $sv_s++ ) : ?><svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" focusable="false"><path d="M10 1.5l2.6 5.27 5.82.85-4.21 4.1.99 5.8L10 14.77 4.99 17.5l.99-5.8L1.77 7.62l5.82-.85z"/></svg><?php endfor; ?></span>
					<p class="svisa-testi__text"><?php echo esc_html( $sv_tp[0] ); ?></p>
					<div class="svisa-testi__who">
						<span class="svisa-testi__avatar" aria-hidden="true"><?php echo esc_html( $sv_init ); ?></span>
						<span class="svisa-testi__meta">
							<span class="svisa-testi__name"><?php echo esc_html( $sv_name ); ?></span>
							<?php if ( $sv_role ) : ?><span class="svisa-testi__role"><?php echo esc_html( $sv_role ); ?></span><?php endif; ?>
						</span>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<?php
/* ================================================================== *
 * 11 — FAQ
 * ================================================================== */
$sv_faq_title = skl_opt( 'sv_faq_title', __( 'Common Questions', 'sklentr' ) );
$sv_faq = $sv_lines( skl_opt( 'sv_faq_items', "How long does it take to build my MVP? | Four weeks, start to finish. We agree the scope up front, build in weekly sprints with visible progress, and deliver before your visa deadline.\nWhat if I don’t have technical specifications? | That’s normal — most founders don’t. Our free discovery call turns your idea into a clear, scoped MVP plan; you don’t need to write a single line of spec.\nDo I own the source code? | Yes — 100%. You receive full source-code ownership and IP rights on delivery. No lock-in, no licensing games.\nWhat tech stack do you use? | A modern, proven stack — Next.js, React, React Native, Flutter, Laravel, PostgreSQL — with AI (Gemini, OpenAI, Claude) woven in where it adds real value.\nWhat happens after launch? | Every package includes one month of post-launch support for fixes and tweaks. We’re a partner, not a vendor — many founders stay on for the next build.\nCan you help with my pitch deck? | Yes. Your package includes pitch-deck assets — product screenshots, a demo video, and presentation materials — ready to show designated organizations and investors.\nWhat if the SUV program stays paused? | A working product is an asset either way: it validates your business, attracts investors and customers now, and puts you first in line the moment the program resumes." ) );
?>

<?php
/* Same UI as the homepage FAQ (.faq* / faq.js), with the Startup Visa content. */
$sv_faq_help_title = skl_opt( 'sv_faq_help_title', __( 'Still have questions?', 'sklentr' ) );
$sv_faq_help_text  = skl_opt( 'sv_faq_help_text', __( 'Book a free 30-minute call — we’ll review your concept and show you exactly what we can build.', 'sklentr' ) );
$sv_faq_help_cta   = skl_opt( 'sv_faq_help_cta_text', __( 'Book My Free Consultation', 'sklentr' ) );
$sv_faq_help_link  = skl_opt( 'sv_faq_help_cta_link', '#contact' );

$sv_faq_pairs = array();
foreach ( $sv_faq as $sv_fl ) {
	$sv_fp = $sv_cols( $sv_fl );
	$sv_faq_pairs[] = array( 'q' => $sv_fp[0], 'a' => isset( $sv_fp[1] ) ? $sv_fp[1] : '' );
}

$sv_faq_schema = array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array() );
foreach ( $sv_faq_pairs as $sv_f ) {
	$sv_faq_schema['mainEntity'][] = array( '@type' => 'Question', 'name' => $sv_f['q'], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $sv_f['a'] ) );
}
?>

<section class="faq" id="faq" aria-labelledby="sv-faq-title">

	<div class="faq__atmos" aria-hidden="true">
		<span class="faq__glow faq__glow--green"></span>
		<span class="faq__glow faq__glow--gold"></span>
	</div>

	<div class="faq__deco" aria-hidden="true">
		<?php
		$sv_faq_deco = array(
			'a' => '<circle cx="12" cy="12" r="3.2"/><path d="M12 2.5v3M12 18.5v3M2.5 12h3M18.5 12h3M5.2 5.2l2.1 2.1M16.7 16.7l2.1 2.1M18.8 5.2l-2.1 2.1M7.3 16.7l-2.1 2.1"/>',
			'b' => '<path d="M8 8l-4 4 4 4"/><path d="M16 8l4 4-4 4"/><path d="M13.5 6.5l-3 11"/>',
			'c' => '<ellipse cx="12" cy="5.5" rx="7.5" ry="2.8"/><path d="M4.5 5.5v6c0 1.55 3.36 2.8 7.5 2.8s7.5-1.25 7.5-2.8v-6"/><path d="M4.5 11.5v6c0 1.55 3.36 2.8 7.5 2.8s7.5-1.25 7.5-2.8v-6"/>',
			'd' => '<path d="M8 4c-2 0-2 2-2 4s0 3-2 3c2 0 2 1 2 3s0 4 2 4"/><path d="M16 4c2 0 2 2 2 4s0 3 2 3c-2 0-2 1-2 3s0 4-2 4"/>',
		);
		foreach ( $sv_faq_deco as $sv_pos => $sv_paths ) {
			echo '<span class="faq__deco-icon faq__deco-icon--' . esc_attr( $sv_pos ) . '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">' . $sv_paths . '</svg></span>'; // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG.
		}
		?>
	</div>

	<div class="skl-container">
		<div class="faq__inner">

			<div class="faq__aside">
				<p class="faq__eyebrow skl-eyebrow"><?php esc_html_e( 'FAQ', 'sklentr' ); ?></p>
				<h2 class="faq__title" id="sv-faq-title" data-char-fill="dark"><?php echo esc_html( $sv_faq_title ); ?></h2>

				<?php if ( $sv_faq_help_title || ( $sv_faq_help_cta && $sv_faq_help_link ) ) : ?>
					<div class="faq__help">
						<span class="faq__help-icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M9.2 9.3a2.8 2.8 0 0 1 5.4 1c0 1.9-2.8 2.5-2.8 2.5"/><path d="M12 17h.01"/></svg>
						</span>
						<?php if ( $sv_faq_help_title ) : ?><h3 class="faq__help-title"><?php echo esc_html( $sv_faq_help_title ); ?></h3><?php endif; ?>
						<?php if ( $sv_faq_help_text ) : ?><p class="faq__help-text"><?php echo esc_html( $sv_faq_help_text ); ?></p><?php endif; ?>
						<?php if ( $sv_faq_help_cta && $sv_faq_help_link ) : ?>
							<a class="skl-btn skl-btn--gold" href="<?php echo esc_url( $sv_faq_help_link ); ?>"><?php echo esc_html( $sv_faq_help_cta ); ?><span class="skl-btn__arrow" aria-hidden="true">&rarr;</span></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="faq__list" data-faq-list>
				<?php foreach ( $sv_faq_pairs as $sv_idx => $sv_f ) : $sv_pid = 'sv-faq-panel-' . $sv_idx; ?>
					<div class="faq-item" data-reveal>
						<h3 class="faq-item__heading">
							<button class="faq-item__q" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $sv_pid ); ?>">
								<span class="faq-item__q-text"><?php echo esc_html( $sv_f['q'] ); ?></span>
								<span class="faq-item__icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
								</span>
							</button>
						</h3>
						<div class="faq-item__panel" id="<?php echo esc_attr( $sv_pid ); ?>" role="region">
							<div class="faq-item__answer"><p><?php echo esc_html( $sv_f['a'] ); ?></p></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

		</div>
	</div>

	<script type="application/ld+json"><?php echo wp_json_encode( $sv_faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); ?></script>
</section>

<?php
/* Shared closing band + footer — same as the rest of the site (unchanged). */
get_template_part( 'template-parts/home/final-cta' );

get_footer();
