<?php
/**
 * Template for the standalone About page (slug: about).
 *
 * Content mirrors https://www.sklentr.com/about and is FULLY dynamic — every
 * section reads from Sklentr Settings → "About Page" (ab_* keys) via skl_opt(),
 * so nothing is hard-coded. The hero is a deliberately PREMIUM split treatment
 * (editorial copy + a "global presence" arc motif tying Toronto ⇄ Dhaka),
 * distinct from the homepage, Services, Startup-Visa, Portfolio, and Pricing
 * heroes. Reuses the shared header, the shared Final-CTA band, and footer.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/* Helpers: split a textarea into trimmed non-empty lines, and a line into piped columns. */
$ab_lines = static function ( $raw ) {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $raw ) ) ) );
};
$ab_cols = static function ( $line ) {
	return array_map( 'trim', explode( '|', (string) $line ) );
};
/* Monogram initials from a name (e.g. "Rishad Wahid" → "RW", "Design Team" → "DT"). */
$ab_initials = static function ( $name ) {
	$parts = preg_split( '/\s+/', trim( (string) $name ) );
	$out   = '';
	foreach ( $parts as $p ) {
		if ( '' !== $p ) {
			$out .= strtoupper( substr( $p, 0, 1 ) );
		}
		if ( strlen( $out ) >= 2 ) {
			break;
		}
	}
	return $out;
};

/* ================================================================== *
 * 01 — HERO (premium split: editorial copy + global-presence arc)
 * ================================================================== */
$ab_h_eyebrow = skl_opt( 'ab_hero_eyebrow', __( 'About Sklentr', 'sklentr' ) );
$ab_h_lead    = skl_opt( 'ab_hero_lead', __( 'We’re', 'sklentr' ) );
$ab_h_accent  = skl_opt( 'ab_hero_accent', __( 'Sklentr', 'sklentr' ) );
$ab_h_sub     = skl_opt( 'ab_hero_sub', __( 'A Toronto-based MVP studio that helps founders launch faster. Canadian management, global talent, and a relentless focus on getting you to market.', 'sklentr' ) );
$ab_c1_text   = skl_opt( 'ab_hero_cta1_text', __( 'Book a Free Consultation', 'sklentr' ) );
$ab_c1_link   = skl_opt( 'ab_hero_cta1_link', 'https://calendly.com/sklentr' );
$ab_c2_text   = skl_opt( 'ab_hero_cta2_text', __( 'See Our Work', 'sklentr' ) );
$ab_c2_link   = skl_opt( 'ab_hero_cta2_link', home_url( '/portfolio/' ) );
$ab_pin1      = skl_opt( 'ab_hero_pin1', __( 'Toronto', 'sklentr' ) );
$ab_pin2      = skl_opt( 'ab_hero_pin2', __( 'Dhaka', 'sklentr' ) );
$ab_viz_kick  = skl_opt( 'ab_hero_viz_kicker', __( 'Est. 2023', 'sklentr' ) );
$ab_viz_note  = skl_opt( 'ab_hero_viz_note', __( 'Canadian management · Global talent', 'sklentr' ) );
$ab_c1_ext    = ( 0 === strpos( $ab_c1_link, 'http' ) && false === strpos( $ab_c1_link, home_url() ) );

/* Stats band — "Number | Label" per row. Number keeps its suffix (+/%) for count-up. */
$ab_stats = $ab_lines( skl_opt( 'ab_hero_stats', "50+ | Projects Delivered\n15+ | SUV MVPs Built\n2 | Offices Worldwide\n100% | Client Satisfaction" ) );
?>

<?php
/* Derived floating-card values (from the stats list) — keeps the collage in sync with edits. */
$ab_card_a   = ( isset( $ab_stats[0] ) ) ? $ab_cols( $ab_stats[0] ) : array( '50+', '' );
$ab_card_num = isset( $ab_card_a[0] ) ? $ab_card_a[0] : '';
$ab_card_lab = isset( $ab_card_a[1] ) ? $ab_card_a[1] : '';
$ab_ring_raw = ( ! empty( $ab_stats ) ) ? $ab_cols( $ab_stats[ count( $ab_stats ) - 1 ] ) : array( '100%', '' );
$ab_ring_num = isset( $ab_ring_raw[0] ) ? $ab_ring_raw[0] : '';
$ab_ring_lab = isset( $ab_ring_raw[1] ) ? $ab_ring_raw[1] : '';
$ab_ring_pct = ( preg_match( '/\d+/', $ab_ring_num, $rm ) ) ? min( 100, (int) $rm[0] ) : 100;
$ab_ring_c   = 2 * M_PI * 26;
$ab_ring_off = $ab_ring_c * ( 1 - $ab_ring_pct / 100 );
?>

<section class="ab-hero" aria-labelledby="ab-hero-title">
	<div class="ab-hero__atmos" aria-hidden="true">
		<span class="ab-hero__glow ab-hero__glow--gold"></span>
		<span class="ab-hero__glow ab-hero__glow--green"></span>
		<svg class="ab-hero__grid" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
			<defs>
				<pattern id="abGrid" width="72" height="72" patternUnits="userSpaceOnUse">
					<path d="M72 0H0V72" fill="none" stroke="#ffffff" stroke-opacity=".045" stroke-width="1"/>
				</pattern>
			</defs>
			<rect width="100%" height="100%" fill="url(#abGrid)"/>
		</svg>
	</div>

	<div class="skl-container">

		<div class="ab-hero__head" data-reveal>
			<?php if ( $ab_h_eyebrow ) : ?><p class="ab-hero__eyebrow skl-eyebrow"><?php echo esc_html( $ab_h_eyebrow ); ?></p><?php endif; ?>
			<h1 class="ab-hero__title" id="ab-hero-title">
				<?php echo esc_html( $ab_h_lead ); ?> <span class="ab-hero__accent"><?php echo esc_html( $ab_h_accent ); ?><svg class="ab-hero__swoosh" viewBox="0 0 300 24" preserveAspectRatio="none" aria-hidden="true"><path d="M4 15 C 70 5, 150 5, 210 11 C 245 14, 275 12, 296 8" fill="none" stroke="url(#abSwoosh)" stroke-width="5" stroke-linecap="round"/><defs><linearGradient id="abSwoosh" x1="0" y1="0" x2="300" y2="0" gradientUnits="userSpaceOnUse"><stop stop-color="#F3B351"/><stop offset="1" stop-color="#1EFF85"/></linearGradient></defs></svg></span>
			</h1>
		</div>

		<div class="ab-hero__body">

			<div class="ab-hero__visual" data-reveal aria-hidden="true">
				<span class="ab-hero__blob"></span>

				<div class="ab-hero__card">
					<span class="ab-hero__card-kicker"><?php echo esc_html( $ab_viz_kick ); ?></span>
					<div class="ab-hero__map">
						<svg viewBox="0 0 320 200" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path class="ab-hero__arc" d="M56 138 C 120 40, 210 40, 268 96" stroke="url(#abArc)" stroke-width="2" stroke-linecap="round" stroke-dasharray="5 7"/>
							<defs><linearGradient id="abArc" x1="56" y1="138" x2="268" y2="96" gradientUnits="userSpaceOnUse"><stop stop-color="#F3B351"/><stop offset="1" stop-color="#1EFF85"/></linearGradient></defs>
							<circle class="ab-hero__pin ab-hero__pin--a" cx="56" cy="138" r="7" fill="#F3B351"/>
							<circle class="ab-hero__pin ab-hero__pin--b" cx="268" cy="96" r="7" fill="#1EFF85"/>
						</svg>
						<span class="ab-hero__pin-label ab-hero__pin-label--a"><?php echo esc_html( $ab_pin1 ); ?></span>
						<span class="ab-hero__pin-label ab-hero__pin-label--b"><?php echo esc_html( $ab_pin2 ); ?></span>
					</div>
					<span class="ab-hero__card-note"><?php echo esc_html( $ab_viz_note ); ?></span>
				</div>

				<div class="ab-hero__float ab-hero__float--chart">
					<span class="ab-hero__mini-bars"><i></i><i></i><i></i></span>
					<div><strong class="ab-hero__float-num"><?php echo esc_html( $ab_card_num ); ?></strong><span class="ab-hero__float-lab"><?php echo esc_html( $ab_card_lab ); ?></span></div>
				</div>

				<div class="ab-hero__float ab-hero__float--ring">
					<span class="ab-hero__ring-wrap">
						<svg class="ab-hero__ring" viewBox="0 0 64 64" style="--c:<?php echo esc_attr( round( $ab_ring_c, 2 ) ); ?>;--off:<?php echo esc_attr( round( $ab_ring_off, 2 ) ); ?>">
							<circle class="ab-hero__ring-track" cx="32" cy="32" r="26"/>
							<circle class="ab-hero__ring-bar" cx="32" cy="32" r="26"/>
						</svg>
						<span class="ab-hero__ring-val"><?php echo esc_html( $ab_ring_num ); ?></span>
					</span>
					<span class="ab-hero__ring-lab"><?php echo esc_html( $ab_ring_lab ); ?></span>
				</div>

			</div>

			<div class="ab-hero__copy" data-reveal>
				<p class="ab-hero__sub"><?php echo esc_html( $ab_h_sub ); ?></p>

				<div class="ab-hero__actions">
					<a class="skl-btn skl-btn--gold" href="<?php echo esc_url( $ab_c1_link ); ?>"<?php echo $ab_c1_ext ? ' target="_blank" rel="noopener"' : ''; ?>>
						<?php echo esc_html( $ab_c1_text ); ?>
						<span class="skl-btn__arrow" aria-hidden="true">&rarr;</span>
					</a>
					<a class="skl-btn skl-btn--ghost-light" href="<?php echo esc_url( $ab_c2_link ); ?>">
						<?php echo esc_html( $ab_c2_text ); ?>
					</a>
				</div>

				<?php if ( $ab_stats ) : ?>
					<ul class="ab-hero__stats">
						<?php foreach ( $ab_stats as $ab_sl ) :
							$s        = $ab_cols( $ab_sl );
							$s_num    = isset( $s[0] ) ? $s[0] : '';
							$s_label  = isset( $s[1] ) ? $s[1] : '';
							$s_count  = ( preg_match( '/^(\d+)(.*)$/', $s_num, $m ) ) ? $m[1] : '';
							$s_suffix = ( '' !== $s_count ) ? $m[2] : $s_num;
							?>
							<li class="ab-stat">
								<span class="ab-stat__num">
									<?php if ( '' !== $s_count ) : ?>
										<span class="ab-stat__count" data-count-to="<?php echo esc_attr( $s_count ); ?>"><?php echo esc_html( $s_count ); ?></span><?php echo esc_html( $s_suffix ); ?>
									<?php else : ?>
										<?php echo esc_html( $s_num ); ?>
									<?php endif; ?>
								</span>
								<span class="ab-stat__label"><?php echo esc_html( $s_label ); ?></span>
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
 * 02 — OUR STORY (editorial two-column: sticky heading + body)
 * ================================================================== */
$ab_st_eyebrow = skl_opt( 'ab_story_eyebrow', __( 'Our Story', 'sklentr' ) );
$ab_st_title   = skl_opt( 'ab_story_title', __( 'Built by Founders, for Founders', 'sklentr' ) );
$ab_st_badge   = skl_opt( 'ab_story_badge', __( 'Est. 2023 · Toronto', 'sklentr' ) );
$ab_st_body    = $ab_lines( skl_opt( 'ab_story_body', "We started Sklentr because we lived the pain. As founders ourselves, we knew how hard it was to find reliable development partners who understood startup realities.\nMost agencies charge a fortune and take forever. Freelancers disappear or deliver broken code. We built Sklentr to be different — fast, transparent, and genuinely invested in your success.\nToday, we’ve helped 50+ founders launch their products. From healthcare AI to blockchain fintech, from Startup Visa applicants to funded startups — we build what matters." ) );
$ab_st_image   = skl_opt( 'ab_story_image', '' );
if ( ! $ab_st_image ) {
	$ab_st_image = get_theme_file_uri( 'assets/images/sklentr-story.jpg' );
}
/* Split the heading so the final word can carry the gold accent (both parts
 * animate with the site-wide char-fill sweep, like the home/pricing titles). */
$ab_st_words = preg_split( '/\s+/', trim( $ab_st_title ) );
$ab_st_last  = (string) array_pop( $ab_st_words );
$ab_st_first = implode( ' ', $ab_st_words );
?>
<section class="ab-story" aria-labelledby="ab-story-title">
	<div class="skl-container">
		<div class="ab-story__grid">
			<div class="ab-story__content" data-reveal>
				<?php if ( $ab_st_eyebrow ) : ?><p class="skl-eyebrow ab-eyebrow"><?php echo esc_html( $ab_st_eyebrow ); ?></p><?php endif; ?>
				<h2 class="ab-story__title" id="ab-story-title"><?php
					if ( '' !== $ab_st_first ) {
						echo '<span data-char-fill>' . esc_html( $ab_st_first ) . '</span>';
					}
					if ( '' !== $ab_st_last ) {
						echo ( '' !== $ab_st_first ? ' ' : '' ) . '<span class="ab-story__accent" data-char-fill="gold">' . esc_html( $ab_st_last ) . '</span>';
					}
				?></h2>
				<div class="ab-story__body">
					<?php foreach ( $ab_st_body as $ab_p ) : ?>
						<p><?php echo esc_html( $ab_p ); ?></p>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="ab-story__media" data-reveal>
				<span class="ab-story__deco ab-story__deco--dots" aria-hidden="true" data-story-speed="22"></span>
				<span class="ab-story__deco ab-story__deco--ring" aria-hidden="true" data-story-speed="-28"></span>
				<div class="ab-story__frame" data-story-speed="-12">
					<img src="<?php echo esc_url( $ab_st_image ); ?>" alt="<?php echo esc_attr( $ab_st_title ); ?>" loading="lazy" />
				</div>
				<?php if ( $ab_st_badge ) : ?>
					<span class="ab-story__badge" data-story-speed="32"><span class="ab-story__badge-dot" aria-hidden="true"></span><?php echo esc_html( $ab_st_badge ); ?></span>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php
/* ================================================================== *
 * 03 — VALUES (What We Stand For — numbered premium cards)
 * ================================================================== */
$ab_v_eyebrow = skl_opt( 'ab_val_eyebrow', __( 'Our Values', 'sklentr' ) );
$ab_v_title   = skl_opt( 'ab_val_title', __( 'What We Stand For', 'sklentr' ) );
$ab_v_intro   = skl_opt( 'ab_val_intro', __( 'The principles behind every product we ship — how we work, what we protect, and why founders trust us with their vision.', 'sklentr' ) );
$ab_v_image   = skl_opt( 'ab_val_image', '' );
if ( ! $ab_v_image ) {
	$ab_v_image = get_theme_file_uri( 'assets/images/work/data.jpg' );
}
$ab_values    = $ab_lines( skl_opt( 'ab_val_items', "Speed Without Sacrifice | We move fast, but never at the expense of quality. Every line of code is built to last.\nFounder-First Mentality | We’ve been in your shoes. We build what you need to succeed, not what pads our invoice.\nRadical Transparency | No hidden fees. No surprises. You know exactly what you’re getting and when.\nOwnership & Accountability | Your success is our success. We don’t disappear after launch — we’re partners." ) );

/* One stroke line-icon per value (bolt, heart, eye, shield-check) — styled like the
 * Services "Built to launch" perks: distinct shaped + colour-tinted box per card. */
$ab_v_icons = array(
	'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M13 2 4 14h6l-1 8 9-12h-6z"/></svg>',
	'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 21s-7-4.5-9.5-8.5C.9 9.9 2.3 6.5 5.5 6.5c2 0 3.2 1.2 4 2.3.8-1.1 2-2.3 4-2.3 3.2 0 4.6 3.4 3 6C19 16.5 12 21 12 21z"/></svg>',
	'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>',
	'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6z"/><path d="M9 12l2 2 4-4"/></svg>',
);
?>
<section class="ab-values" aria-labelledby="ab-values-title">
	<div class="skl-container">
		<div class="ab-section-head" data-reveal>
			<?php if ( $ab_v_eyebrow ) : ?><p class="skl-eyebrow ab-eyebrow"><?php echo esc_html( $ab_v_eyebrow ); ?></p><?php endif; ?>
			<h2 class="ab-section-head__title" id="ab-values-title" data-char-fill><?php echo esc_html( $ab_v_title ); ?></h2>
			<?php if ( $ab_v_intro ) : ?><p class="ab-values__intro"><?php echo esc_html( $ab_v_intro ); ?></p><?php endif; ?>
		</div>

		<div class="ab-values__cols">
			<?php if ( $ab_v_image ) : ?>
				<div class="ab-values__media" data-reveal>
					<img src="<?php echo esc_url( $ab_v_image ); ?>" alt="<?php echo esc_attr( $ab_v_title ); ?>" loading="lazy" />
					<span class="ab-values__media-ring" aria-hidden="true"></span>
				</div>
			<?php endif; ?>

			<ul class="ab-values__grid" data-values-parallax>
				<?php foreach ( $ab_values as $ab_vk => $ab_vl ) :
					$v       = $ab_cols( $ab_vl );
					$v_title = isset( $v[0] ) ? $v[0] : '';
					$v_text  = isset( $v[1] ) ? $v[1] : '';
					$v_icon  = isset( $ab_v_icons[ $ab_vk ] ) ? $ab_v_icons[ $ab_vk ] : $ab_v_icons[0];
					?>
					<li class="ab-value" data-value-card style="--i:<?php echo esc_attr( (int) $ab_vk ); ?>">
						<span class="ab-value__icon" aria-hidden="true"><?php echo $v_icon; // phpcs:ignore WordPress.Security.EscapingOutput -- trusted static SVG. ?></span>
						<span class="ab-value__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', (int) $ab_vk + 1 ) ); ?></span>
						<h3 class="ab-value__title"><?php echo esc_html( $v_title ); ?></h3>
						<?php if ( $v_text ) : ?><p class="ab-value__text"><?php echo esc_html( $v_text ); ?></p><?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>

<?php
/* ================================================================== *
 * 04 — TEAM (Meet the Team — monogram cards)
 * "Name | Role | Location | Bio" per line.
 * ================================================================== */
$ab_t_eyebrow = skl_opt( 'ab_team_eyebrow', __( 'The Team', 'sklentr' ) );
$ab_t_title   = skl_opt( 'ab_team_title', __( 'Meet the Team', 'sklentr' ) );
$ab_team      = $ab_lines( skl_opt( 'ab_team_members', "Rishad Wahid | Founder & CEO | Toronto, Canada | Serial entrepreneur with 10+ years building digital products. Passionate about helping founders bring their visions to life.\nDevelopment Team | Engineering | Dhaka, Bangladesh | World-class engineers specializing in React, Next.js, Laravel, and mobile development. Fast, reliable, and detail-oriented.\nDesign Team | UI/UX Design | Global | Creative designers who understand that great UX is invisible. We make complex simple and beautiful functional.\nMarketing Team | Growth & SEO | Toronto & Dhaka | Data-driven marketers who’ve helped startups rank #1 for competitive keywords. We don’t just build — we grow." ) );
?>
<section class="ab-team" aria-labelledby="ab-team-title">
	<div class="skl-container">
		<div class="ab-section-head" data-reveal>
			<?php if ( $ab_t_eyebrow ) : ?><p class="skl-eyebrow ab-eyebrow"><?php echo esc_html( $ab_t_eyebrow ); ?></p><?php endif; ?>
			<h2 class="ab-section-head__title" id="ab-team-title" data-char-fill><?php echo esc_html( $ab_t_title ); ?></h2>
		</div>

		<ul class="ab-team__grid">
			<?php foreach ( $ab_team as $ab_tk => $ab_tl ) :
				$t          = $ab_cols( $ab_tl );
				$t_name     = isset( $t[0] ) ? $t[0] : '';
				$t_role     = isset( $t[1] ) ? $t[1] : '';
				$t_location = isset( $t[2] ) ? $t[2] : '';
				$t_bio      = isset( $t[3] ) ? $t[3] : '';
				?>
				<li class="ab-member" data-reveal style="--i:<?php echo esc_attr( (int) $ab_tk ); ?>">
					<div class="ab-member__top">
						<span class="ab-member__avatar" aria-hidden="true"><?php echo esc_html( $ab_initials( $t_name ) ); ?></span>
						<div class="ab-member__id">
							<h3 class="ab-member__name"><?php echo esc_html( $t_name ); ?></h3>
							<?php if ( $t_role ) : ?><p class="ab-member__role"><?php echo esc_html( $t_role ); ?></p><?php endif; ?>
						</div>
					</div>
					<?php if ( $t_bio ) : ?><p class="ab-member__bio"><?php echo esc_html( $t_bio ); ?></p><?php endif; ?>
					<?php if ( $t_location ) : ?>
						<p class="ab-member__loc">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-7-6-7-11a7 7 0 0 1 14 0c0 5-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
							<?php echo esc_html( $t_location ); ?>
						</p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<?php
/* ================================================================== *
 * 04b — TOP SERVICES (iteck "Discover our top services" — Swiper slider)
 * "Icon-slug | Title | Description" per line.
 * ================================================================== */
$ab_sv_title  = skl_opt( 'ab_svc_title', __( 'Discover our top', 'sklentr' ) );
$ab_sv_accent = skl_opt( 'ab_svc_accent', __( 'services', 'sklentr' ) );
$ab_sv_sub    = skl_opt( 'ab_svc_sub', __( 'Our strategy includes consistently evolving, to ensure we’re producing exceptional SEO for business.', 'sklentr' ) );
$ab_sv_btn_t  = skl_opt( 'ab_svc_btn_text', __( 'See All Our Services', 'sklentr' ) );
$ab_sv_btn_l  = skl_opt( 'ab_svc_btn_link', '/services/' );
$ab_services  = $ab_lines( skl_opt( 'ab_svc_items', "16 | Content Strategy | You can provide the answers that your potential customers are trying to find, so you can become the industry.\n15 | Google/FB Ads | Get more website traffic, more customers & more online visibility with powerful SEO services.\n14 | Email Marketing | Your website has to impress your visitors within just a few seconds\n17 | Social Media | Get more website traffic, more customers for your social chanel\n18 | Website Design and Development | Your website has to impress your visitors within just a few seconds. If it runs slow, if it feels outdated\n19 | TVC/ Viral Clip | Optimized your website on google result with PPC Marketing\n20 | PPC Ads | Optimized your website on google result with PPC Marketing" ) );
$ab_sv_btn_l  = ( '' !== $ab_sv_btn_l && false === strpos( $ab_sv_btn_l, '://' ) ) ? home_url( $ab_sv_btn_l ) : $ab_sv_btn_l;
?>
<section class="ab-svc" aria-labelledby="ab-svc-title">
	<div class="skl-container">
		<div class="ab-svc__head" data-reveal>
			<h2 class="ab-svc__title" id="ab-svc-title" data-char-fill><?php echo esc_html( trim( $ab_sv_title . ' ' . $ab_sv_accent ) ); ?></h2>
			<?php if ( $ab_sv_sub ) : ?><p class="ab-svc__sub"><?php echo esc_html( $ab_sv_sub ); ?></p><?php endif; ?>
		</div>
	</div>

	<div class="ab-svc__slider swiper" data-reveal>
		<div class="swiper-wrapper">
			<?php
			// Render the roster twice — Swiper's loop needs > slidesPerView (6) slides.
			$ab_svc_loop = ( count( $ab_services ) < 12 ) ? array_merge( $ab_services, $ab_services ) : $ab_services;
			foreach ( $ab_svc_loop as $ab_sl ) :
				$s       = $ab_cols( $ab_sl );
				$s_slug  = isset( $s[0] ) ? preg_replace( '/[^A-Za-z0-9_-]/', '', $s[0] ) : '';
				$s_title = isset( $s[1] ) ? $s[1] : '';
				$s_desc  = isset( $s[2] ) ? $s[2] : '';
				$s_icon  = get_theme_file_uri( 'assets/images/serv-icons/' . $s_slug . '.png' );
				?>
				<div class="swiper-slide">
					<a href="<?php echo esc_url( $ab_sv_btn_l ); ?>" class="ab-svc-card">
						<span class="ab-svc-card__icon"><img src="<?php echo esc_url( $s_icon ); ?>" alt="" /></span>
						<span class="ab-svc-card__info">
							<h5 class="ab-svc-card__title"><?php echo esc_html( $s_title ); ?></h5>
							<?php if ( $s_desc ) : ?><span class="ab-svc-card__text"><?php echo esc_html( $s_desc ); ?></span><?php endif; ?>
						</span>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<?php if ( $ab_sv_btn_t ) : ?>
		<div class="skl-container">
			<div class="ab-svc__cta" data-reveal>
				<a class="ab-svc__btn" href="<?php echo esc_url( $ab_sv_btn_l ); ?>">
					<?php echo esc_html( $ab_sv_btn_t ); ?>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
				</a>
			</div>
		</div>
	<?php endif; ?>
</section>

<?php
/* ================================================================== *
 * 05 — OFFICES (Global Presence — dark band, "City | Tag | Description")
 * ================================================================== */
$ab_o_eyebrow = skl_opt( 'ab_off_eyebrow', __( 'Our Offices', 'sklentr' ) );
$ab_o_title   = skl_opt( 'ab_off_title', __( 'Global Presence', 'sklentr' ) );
$ab_o_tagline = skl_opt( 'ab_off_tagline', __( 'Canadian management. Global talent. The best of both worlds.', 'sklentr' ) );
$ab_offices   = $ab_lines( skl_opt( 'ab_off_items', "Toronto, Canada | Headquarters | Client relationships, strategy, and project management\nDhaka, Bangladesh | Development Center | Engineering, design, and technical implementation" ) );
?>
<section class="ab-offices" aria-labelledby="ab-offices-title">
	<div class="ab-offices__atmos" aria-hidden="true">
		<span class="ab-offices__glow ab-offices__glow--gold"></span>
		<span class="ab-offices__glow ab-offices__glow--green"></span>
	</div>
	<div class="skl-container">
		<div class="ab-section-head ab-section-head--on-dark" data-reveal>
			<?php if ( $ab_o_eyebrow ) : ?><p class="skl-eyebrow ab-eyebrow ab-eyebrow--on-dark"><?php echo esc_html( $ab_o_eyebrow ); ?></p><?php endif; ?>
			<h2 class="ab-section-head__title ab-section-head__title--on-dark" id="ab-offices-title"><?php echo esc_html( $ab_o_title ); ?></h2>
		</div>

		<ul class="ab-offices__grid">
			<?php foreach ( $ab_offices as $ab_ok => $ab_ol ) :
				$o       = $ab_cols( $ab_ol );
				$o_city  = isset( $o[0] ) ? $o[0] : '';
				$o_tag   = isset( $o[1] ) ? $o[1] : '';
				$o_desc  = isset( $o[2] ) ? $o[2] : '';
				?>
				<li class="ab-office" data-reveal style="--i:<?php echo esc_attr( (int) $ab_ok ); ?>">
					<span class="ab-office__pin" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6-7-11a7 7 0 0 1 14 0c0 5-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
					</span>
					<?php if ( $o_tag ) : ?><span class="ab-office__tag"><?php echo esc_html( $o_tag ); ?></span><?php endif; ?>
					<h3 class="ab-office__city"><?php echo esc_html( $o_city ); ?></h3>
					<?php if ( $o_desc ) : ?><p class="ab-office__desc"><?php echo esc_html( $o_desc ); ?></p><?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php if ( $ab_o_tagline ) : ?>
			<p class="ab-offices__tagline" data-reveal><?php echo esc_html( $ab_o_tagline ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php
/* Shared Final-CTA band (dynamic via Sklentr Settings → Global → Final CTA Band). */
get_template_part( 'template-parts/home/final-cta' );

get_footer();
