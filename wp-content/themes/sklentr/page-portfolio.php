<?php
/**
 * Template for the standalone Portfolio page (slug: portfolio).
 *
 * Fully editable from wp-admin: the section text lives in Sklentr Settings →
 * "Portfolio Page" (pf_* keys), and the six projects are the "Portfolio
 * Projects" list (skl_portfolio CPT). The rendered markup, styles, and
 * animations are unchanged — only the data source moved from hard-coded arrays
 * to settings + CPT. Reuses the shared header, Final-CTA band, and footer.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$pf_img   = static function ( $slug ) {
	return get_theme_file_uri( 'assets/images/portfolio/portfolio-' . $slug . '.jpg' );
};
$pf_check = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6.5 9.5 17 4 11.5"/></svg>';
$pf_lines = static function ( $raw ) {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $raw ) ) ) );
};

/* ================================================================== *
 * HERO — headline, sub, buttons, tilted thumbnail collage
 * ================================================================== */
$pf_hero_lead   = skl_opt( 'pf_hero_lead', __( 'Ideas we’ve brought to', 'sklentr' ) );
$pf_hero_accent = skl_opt( 'pf_hero_accent', __( 'life', 'sklentr' ) );
$pf_hero_sub    = skl_opt( 'pf_hero_sub', __( 'From healthcare AI to blockchain fintech, we’ve helped founders across industries launch products that matter. Here’s proof we deliver.', 'sklentr' ) );
$pf_c1_text     = skl_opt( 'pf_hero_cta1_text', __( 'Start a Project', 'sklentr' ) );
$pf_c1_link     = skl_opt( 'pf_hero_cta1_link', '#contact' );
$pf_c2_text     = skl_opt( 'pf_hero_cta2_text', __( 'See the Work', 'sklentr' ) );
$pf_c2_link     = skl_opt( 'pf_hero_cta2_link', '#pf-featured' );

/* Collage thumbnails — [ image-slug, name, tag ] per line. */
$pf_hero_shots = array();
foreach ( $pf_lines( skl_opt( 'pf_hero_collage', "gettakaful | Get Takaful | FinTech / Blockchain\nkindredcare | KindredCare | Elderly Care\ngaindata | GAinData | Data / SaaS" ) ) as $pf_cl ) {
	$pf_cc = array_map( 'trim', explode( '|', $pf_cl ) );
	$pf_hero_shots[] = array( $pf_cc[0], isset( $pf_cc[1] ) ? $pf_cc[1] : '', isset( $pf_cc[2] ) ? $pf_cc[2] : '' );
}

/* ================================================================== *
 * PROJECTS — the "Portfolio Projects" CPT (menu_order), mapped into the
 * same shape the template used to hard-code.
 * ================================================================== */
$pf_projects = array();
$pf_q = new WP_Query( array(
	'post_type'      => 'skl_portfolio',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
foreach ( $pf_q->posts as $pf_post ) {
	$pid  = $pf_post->ID;
	$tags = array_values( array_filter( array( get_post_meta( $pid, '_skl_tag1', true ), get_post_meta( $pid, '_skl_tag2', true ) ) ) );
	$pf_slug = get_post_meta( $pid, '_skl_slug', true );
	$pf_projects[] = array(
		'slug'      => $pf_slug,
		// Featured Image when set, else the bundled per-project default.
		'image'     => has_post_thumbnail( $pid )
			? get_the_post_thumbnail_url( $pid, 'large' )
			: $pf_img( $pf_slug ),
		'title'     => get_the_title( $pid ),
		'tags'      => $tags,
		'status'    => get_post_meta( $pid, '_skl_status', true ),
		'desc'      => get_post_meta( $pid, '_skl_desc', true ),
		'challenge' => get_post_meta( $pid, '_skl_challenge', true ),
		'solution'  => get_post_meta( $pid, '_skl_solution', true ),
		'results'   => $pf_lines( get_post_meta( $pid, '_skl_results', true ) ),
		'stack'     => $pf_lines( get_post_meta( $pid, '_skl_stack', true ) ),
	);
}
wp_reset_postdata();
?>

<section class="pf-hero" aria-labelledby="pf-hero-title">
	<div class="pf-hero__atmos" aria-hidden="true">
		<span class="pf-hero__glow pf-hero__glow--gold"></span>
		<span class="pf-hero__glow pf-hero__glow--green"></span>
		<svg class="pf-hero__grid" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
			<defs>
				<pattern id="pfGrid" width="64" height="64" patternUnits="userSpaceOnUse">
					<path d="M64 0H0V64" fill="none" stroke="#ffffff" stroke-opacity=".05" stroke-width="1"/>
				</pattern>
			</defs>
			<rect width="100%" height="100%" fill="url(#pfGrid)"/>
		</svg>
	</div>

	<div class="skl-container">
		<div class="pf-hero__inner">

			<div class="pf-hero__copy" data-reveal>
				<h1 class="pf-hero__title" id="pf-hero-title">
					<?php echo esc_html( $pf_hero_lead ); ?> <span class="pf-hero__accent"><?php echo esc_html( $pf_hero_accent ); ?><span class="pf-hero__accent-underline" aria-hidden="true"></span></span>
				</h1>

				<p class="pf-hero__sub"><?php echo esc_html( $pf_hero_sub ); ?></p>

				<div class="pf-hero__actions">
					<a class="skl-btn skl-btn--gold" href="<?php echo esc_url( $pf_c1_link ); ?>">
						<?php echo esc_html( $pf_c1_text ); ?>
						<span class="skl-btn__arrow" aria-hidden="true">&rarr;</span>
					</a>
					<a class="skl-btn skl-btn--ghost-dark" href="<?php echo esc_url( $pf_c2_link ); ?>"><?php echo esc_html( $pf_c2_text ); ?></a>
				</div>
			</div>

			<div class="pf-hero__collage" aria-hidden="true">
				<?php foreach ( $pf_hero_shots as $pf_i => $pf_shot ) : ?>
					<figure class="pf-hero__shot pf-hero__shot--<?php echo esc_attr( $pf_i + 1 ); ?>">
						<img src="<?php echo esc_url( $pf_img( $pf_shot[0] ) ); ?>" alt="" width="480" height="320" loading="eager" decoding="async">
						<figcaption>
							<span class="pf-hero__shot-name"><?php echo esc_html( $pf_shot[1] ); ?></span>
							<span class="pf-hero__shot-tag"><?php echo esc_html( $pf_shot[2] ); ?></span>
						</figcaption>
					</figure>
				<?php endforeach; ?>
			</div>

		</div>
	</div>
</section>

<?php
/* ================================================================== *
 * MANIFESTO — centered statement with photos that fan out from behind
 * the text on scroll (scroll-scrubbed via pf-manifesto.js → --p).
 * ================================================================== */
$pf_manifesto_shots = $pf_lines( skl_opt( 'pf_man_photos', "gettakaful\naifarming\nkindredcare\ngaindata" ) );
$pf_man_l1   = skl_opt( 'pf_man_l1', __( 'We build meaningful products and', 'sklentr' ) );
$pf_man_l2   = skl_opt( 'pf_man_l2', __( 'intuitive digital experiences — through', 'sklentr' ) );
$pf_man_l3   = skl_opt( 'pf_man_l3', __( 'strategy, craft & technology that ships.', 'sklentr' ) );
$pf_man_acc  = skl_opt( 'pf_man_accent', __( 'ships', 'sklentr' ) );
$pf_man_lt   = skl_opt( 'pf_man_link_text', __( 'How We Work', 'sklentr' ) );
$pf_man_lurl = skl_opt( 'pf_man_link_url', home_url( '/#process' ) );

/* Wrap the accent word inside line 3 (first match only), keeping output identical. */
$pf_l3_html = esc_html( $pf_man_l3 );
$pf_acc_e   = esc_html( $pf_man_acc );
if ( '' !== $pf_acc_e && false !== strpos( $pf_l3_html, $pf_acc_e ) ) {
	$pf_l3_html = preg_replace( '/' . preg_quote( $pf_acc_e, '/' ) . '/', '<span class="pf-manifesto__accent">' . $pf_acc_e . '</span>', $pf_l3_html, 1 );
}
?>
<section class="pf-manifesto" aria-labelledby="pf-manifesto-title">
	<div class="pf-manifesto__pin">
		<div class="pf-manifesto__photos" aria-hidden="true">
			<?php foreach ( $pf_manifesto_shots as $pf_mi => $pf_ms ) : ?>
				<figure class="pf-manifesto__photo pf-manifesto__photo--<?php echo esc_attr( $pf_mi + 1 ); ?>">
					<img src="<?php echo esc_url( $pf_img( $pf_ms ) ); ?>" alt="" width="272" height="288" loading="lazy" decoding="async">
				</figure>
			<?php endforeach; ?>
		</div>

		<div class="skl-container">
			<div class="pf-manifesto__inner">
				<h2 class="pf-manifesto__statement" id="pf-manifesto-title">
					<span class="pf-manifesto__line"><?php echo esc_html( $pf_man_l1 ); ?></span>
					<span class="pf-manifesto__line"><?php echo esc_html( $pf_man_l2 ); ?></span>
					<span class="pf-manifesto__line"><?php echo $pf_l3_html; // phpcs:ignore WordPress.Security.EscapingOutput -- built from esc_html parts. ?></span>
				</h2>
				<a class="pf-manifesto__more" href="<?php echo esc_url( $pf_man_lurl ); ?>">
					<?php echo esc_html( $pf_man_lt ); ?>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7"/><path d="M8 7h9v9"/></svg>
				</a>
			</div>
		</div>
	</div>
</section>

<?php
/* ================================================================== *
 * FEATURED WORKS — sticky index (project names + rolling number +
 * swapping title/description) synced to a scrolling stack of the
 * project images. Cloned from the Novatra reference, on the ink brand.
 * Driven by assets/js/pf-featured.js (IntersectionObserver scrollspy).
 * ================================================================== */
$pf_feat_eyebrow = skl_opt( 'pf_feat_eyebrow', __( 'Case Studies', 'sklentr' ) );
$pf_feat_title   = skl_opt( 'pf_feat_title', __( 'Featured Works', 'sklentr' ) );
$pf_feat_va_text = skl_opt( 'pf_feat_viewall_text', __( 'View all work', 'sklentr' ) );
$pf_feat_va_link = skl_opt( 'pf_feat_viewall_link', '#contact' );
$pf_feat_cl      = skl_opt( 'pf_feat_challenge_label', __( 'Challenge', 'sklentr' ) );
$pf_feat_sl      = skl_opt( 'pf_feat_solution_label', __( 'Solution', 'sklentr' ) );
?>
<section class="pf-featured" id="pf-featured" aria-labelledby="pf-featured-title">
	<div class="pf-featured__atmos" aria-hidden="true">
		<span class="pf-featured__glow pf-featured__glow--gold"></span>
		<span class="pf-featured__glow pf-featured__glow--green"></span>
	</div>

	<div class="skl-container">
		<div class="pf-featured__grid">

			<div class="pf-featured__aside">
				<div class="pf-featured__sticky">
					<div class="pf-featured__head">
						<p class="skl-eyebrow pf-featured__eyebrow"><?php echo esc_html( $pf_feat_eyebrow ); ?></p>
						<div class="pf-featured__title-row">
							<h2 class="pf-featured__title" id="pf-featured-title"><?php echo esc_html( $pf_feat_title ); ?></h2>
							<div class="pf-featured__num" aria-hidden="true">
								<div class="pf-featured__num-track" style="--i:0">
									<?php foreach ( $pf_projects as $pf_fk => $pf_fp ) : ?>
										<span><?php echo esc_html( str_pad( (string) ( $pf_fk + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
						<!-- <a class="pf-featured__viewall " href="<?php //echo esc_url( $pf_feat_va_link ); ?>"><?php echo esc_html( $pf_feat_va_text ); ?>
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7"/><path d="M8 7h9v9"/></svg>
						</a> -->
					</div>

					<div class="pf-featured__index">
						<ol class="pf-featured__names">
							<?php foreach ( $pf_projects as $pf_fk => $pf_fp ) : ?>
								<li>
									<button type="button" class="pf-featured__name<?php echo 0 === $pf_fk ? ' is-active' : ''; ?>" data-name data-jump="<?php echo esc_attr( $pf_fk ); ?>">
										<?php echo esc_html( $pf_fp['title'] ); ?>
									</button>
								</li>
							<?php endforeach; ?>
						</ol>
					</div>

					<div class="pf-featured__active">
						<?php foreach ( $pf_projects as $pf_fk => $pf_fp ) : ?>
							<div class="pf-featured__panel<?php echo 0 === $pf_fk ? ' is-active' : ''; ?>" data-panel>
								<ul class="pf-featured__panel-tags">
									<?php foreach ( $pf_fp['tags'] as $pf_ft ) : ?><li><?php echo esc_html( $pf_ft ); ?></li><?php endforeach; ?>
								</ul>
								<h3 class="pf-featured__panel-title"><?php echo esc_html( $pf_fp['title'] ); ?></h3>
								<p class="pf-featured__panel-desc"><?php echo esc_html( $pf_fp['desc'] ); ?></p>

								<div class="pf-featured__panel-cs">
									<div class="pf-featured__panel-block">
										<span class="pf-featured__panel-label"><?php echo esc_html( $pf_feat_cl ); ?></span>
										<p><?php echo esc_html( $pf_fp['challenge'] ); ?></p>
									</div>
									<div class="pf-featured__panel-block">
										<span class="pf-featured__panel-label pf-featured__panel-label--green"><?php echo esc_html( $pf_feat_sl ); ?></span>
										<p><?php echo esc_html( $pf_fp['solution'] ); ?></p>
									</div>
								</div>

								<ul class="pf-featured__panel-results">
									<?php foreach ( $pf_fp['results'] as $pf_fr ) : ?>
										<li><span class="pf-featured__panel-check" aria-hidden="true"><?php echo $pf_check; // phpcs:ignore WordPress.Security.EscapingOutput -- trusted static SVG. ?></span><?php echo esc_html( $pf_fr ); ?></li>
									<?php endforeach; ?>
								</ul>

								<ul class="pf-featured__panel-stack">
									<?php foreach ( $pf_fp['stack'] as $pf_fs ) : ?><li><?php echo esc_html( $pf_fs ); ?></li><?php endforeach; ?>
								</ul>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<div class="pf-featured__stack">
				<?php foreach ( $pf_projects as $pf_fk => $pf_fp ) : ?>
					<figure class="pf-featured__shot" data-shot data-index="<?php echo esc_attr( $pf_fk ); ?>">
						<span class="pf-featured__shot-num" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $pf_fk + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
						<img src="<?php echo esc_url( $pf_fp['image'] ); ?>" alt="<?php echo esc_attr( $pf_fp['title'] . ' — ' . implode( ' / ', $pf_fp['tags'] ) ); ?>" width="800" height="600" loading="lazy" decoding="async">
						<figcaption class="pf-featured__shot-cap">
							<h3><?php echo esc_html( $pf_fp['title'] ); ?></h3>
							<p><?php echo esc_html( $pf_fp['desc'] ); ?></p>
						</figcaption>
					</figure>
				<?php endforeach; ?>
			</div>

		</div>
	</div>
</section>

<?php
/* Shared closing band + footer — same as the rest of the site (unchanged). */
get_template_part( 'template-parts/home/final-cta' );

get_footer();
