<?php
/**
 * Template for the standalone Blog index page (slug: blog).
 *
 * Mirrors https://www.sklentr.com/blog — a fully DARK layout: hero, a featured
 * post, a category-filterable post grid, and a newsletter band. Fully dynamic:
 * every string reads from Sklentr Settings → "Blog Page" (bl_* keys) via
 * skl_opt(). Reuses the shared header + footer. Post thumbnails use bundled
 * theme imagery (assets/images/work/*.jpg) by slug — no third-party photos.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/* Helpers: textarea → trimmed non-empty lines; a line → piped columns. */
$bl_lines = static function ( $raw ) {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $raw ) ) ) );
};
$bl_cols = static function ( $line ) {
	return array_map( 'trim', explode( '|', (string) $line ) );
};
$bl_slug = static function ( $s ) {
	return sanitize_title( (string) $s );
};
/* Resolve a post's thumbnail: a bundled slug (assets/images/work/<slug>.jpg) or a full URL. */
$bl_img = static function ( $ref ) {
	$ref = trim( (string) $ref );
	if ( '' === $ref ) {
		return get_theme_file_uri( 'assets/images/work/data.jpg' );
	}
	if ( false !== strpos( $ref, '://' ) || 0 === strpos( $ref, '/' ) ) {
		return $ref;
	}
	return get_theme_file_uri( 'assets/images/work/' . preg_replace( '/[^a-z0-9_-]/i', '', $ref ) . '.jpg' );
};

/* ------------------------------------------------------------------ *
 * Content
 * ------------------------------------------------------------------ */
$bl_h_eyebrow = skl_opt( 'bl_hero_eyebrow', __( 'The Sklentr Blog', 'sklentr' ) );
$bl_h_lead    = skl_opt( 'bl_hero_lead', __( 'Insights for', 'sklentr' ) );
$bl_h_accent  = skl_opt( 'bl_hero_accent', __( 'Founders', 'sklentr' ) );
$bl_h_sub     = skl_opt( 'bl_hero_sub', __( 'Strategies, guides, and lessons from building MVPs for startups across Canada. No fluff, just actionable insights.', 'sklentr' ) );

$bl_posts_title = skl_opt( 'bl_posts_title', __( 'All Posts', 'sklentr' ) );
$bl_more_text   = skl_opt( 'bl_more_text', __( 'Load More Articles', 'sklentr' ) );

/* ---- Real WordPress posts power the blog now ---- */
$bl_query = new WP_Query( array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => 30,
	'ignore_sticky_posts' => true,
) );

/* Build the post rows from the query. */
$bl_rows = array();
if ( $bl_query->have_posts() ) {
	while ( $bl_query->have_posts() ) {
		$bl_query->the_post();
		$bl_pid = get_the_ID();
		$bl_pc  = sklentr_post_primary_cat( $bl_pid );
		$bl_rows[] = array(
			'cat'      => $bl_pc ? $bl_pc->name : '',
			'cat_slug' => $bl_pc ? $bl_pc->slug : '',
			'title'    => get_the_title(),
			'excerpt'  => wp_strip_all_tags( get_the_excerpt() ),
			'author'   => sklentr_post_byline( $bl_pid ),
			'date'     => get_the_date(),
			'read'     => sklentr_post_readtime( $bl_pid ),
			'img'      => sklentr_post_cover_url( $bl_pid ),
			'link'     => get_permalink(),
		);
	}
	wp_reset_postdata();
}

/* The newest post headlines the Featured band, so keep it out of the grid below
   — otherwise it renders twice on the page. */
$bl_grid_rows = array_slice( $bl_rows, 1 );

/* Filter chips are built from the grid only, so every chip has something to
   show when clicked. */
$bl_cats_seen = array();
foreach ( $bl_grid_rows as $bl_row ) {
	if ( ! empty( $bl_row['cat_slug'] ) ) {
		$bl_cats_seen[ $bl_row['cat_slug'] ] = $bl_row['cat'];
	}
}
$bl_cats = array_values( $bl_cats_seen );

/* Small inline SVGs. */
$bl_ic_user = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>';
$bl_ic_clock = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>';
$bl_ic_arrow = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
?>

<?php
/* ================================================================== *
 * 01 — HERO
 * ================================================================== */
?>
<section class="bl-hero" aria-labelledby="bl-hero-title">
	<div class="bl-hero__atmos" aria-hidden="true">
		<span class="bl-hero__aurora bl-hero__aurora--gold"></span>
		<span class="bl-hero__aurora bl-hero__aurora--green"></span>
		<span class="bl-hero__grid"></span>
	</div>
	<div class="skl-container">
		<div class="bl-hero__inner" data-reveal>
			<?php if ( $bl_h_eyebrow ) : ?>
				<p class="bl-hero__eyebrow"><span class="bl-hero__eyebrow-dot" aria-hidden="true"></span><?php echo esc_html( $bl_h_eyebrow ); ?></p>
			<?php endif; ?>
			<h1 class="bl-hero__title" id="bl-hero-title">
				<?php echo esc_html( $bl_h_lead ); ?> <span class="bl-hero__accent"><?php echo esc_html( $bl_h_accent ); ?></span>
			</h1>
			<?php if ( $bl_h_sub ) : ?><p class="bl-hero__sub"><?php echo esc_html( $bl_h_sub ); ?></p><?php endif; ?>
		</div>
	</div>
</section>

<?php
/* ================================================================== *
 * 02 — FEATURED (first post, wide)
 * ================================================================== */
if ( ! empty( $bl_rows ) ) :
	$f = $bl_rows[0];
	?>
	<section class="bl-featured" aria-label="Featured article">
		<div class="skl-container">
			<a class="bl-featured__card" href="<?php echo esc_url( $f['link'] ); ?>" data-reveal>
				<div class="bl-featured__media">
					<img src="<?php echo esc_url( $f['img'] ); ?>" alt="<?php echo esc_attr( $f['title'] ); ?>" loading="lazy" />
					<span class="bl-featured__badge"><?php esc_html_e( 'Featured', 'sklentr' ); ?></span>
				</div>
				<div class="bl-featured__body">
					<?php if ( $f['cat'] ) : ?><span class="bl-featured__cat"><?php echo esc_html( $f['cat'] ); ?></span><?php endif; ?>
					<h2 class="bl-featured__title"><?php echo esc_html( $f['title'] ); ?></h2>
					<?php if ( $f['excerpt'] ) : ?><p class="bl-featured__excerpt"><?php echo esc_html( $f['excerpt'] ); ?></p><?php endif; ?>
					<div class="bl-featured__meta">
						<?php if ( $f['author'] ) : ?><span class="bl-meta"><?php echo $bl_ic_user; // phpcs:ignore WordPress.Security.EscapingOutput ?><?php echo esc_html( $f['author'] ); ?></span><?php endif; ?>
						<?php if ( $f['read'] ) : ?><span class="bl-meta"><?php echo $bl_ic_clock; // phpcs:ignore WordPress.Security.EscapingOutput ?><?php echo esc_html( $f['read'] ); ?></span><?php endif; ?>
					</div>
					<span class="bl-featured__more"><?php esc_html_e( 'Read Article', 'sklentr' ); ?> <?php echo $bl_ic_arrow; // phpcs:ignore WordPress.Security.EscapingOutput ?></span>
				</div>
			</a>
		</div>
	</section>
<?php endif; ?>

<?php
/* ================================================================== *
 * 03 — ALL POSTS (filter chips + grid)
 * ================================================================== */
?>
<section class="bl-posts" aria-labelledby="bl-posts-title">
	<div class="skl-container">
		<div class="bl-posts__head" data-reveal>
			<h2 class="bl-posts__title" id="bl-posts-title" data-char-fill><?php echo esc_html( $bl_posts_title ); ?></h2>
			<?php if ( ! empty( $bl_cats ) ) : ?>
				<div class="bl-filters" role="tablist" aria-label="Filter posts by category">
					<button type="button" class="bl-chip is-active" data-filter="all" aria-pressed="true"><?php esc_html_e( 'All', 'sklentr' ); ?></button>
					<?php foreach ( $bl_cats_seen as $bl_cslug => $bl_cname ) : ?>
						<button type="button" class="bl-chip" data-filter="<?php echo esc_attr( $bl_cslug ); ?>" aria-pressed="false"><?php echo esc_html( $bl_cname ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="bl-grid" data-blog-grid>
			<?php foreach ( $bl_grid_rows as $bl_k => $r ) : ?>
				<a class="bl-card" href="<?php echo esc_url( $r["link"] ); ?>" data-cat="<?php echo esc_attr( $r["cat_slug"] ); ?>" data-reveal style="--i:<?php echo esc_attr( (int) $bl_k % 3 ); ?>">
					<div class="bl-card__media">
						<img src="<?php echo esc_url( $r["img"] ); ?>" alt="<?php echo esc_attr( $r['title'] ); ?>" loading="lazy" />
						<?php if ( $r['cat'] ) : ?><span class="bl-card__cat"><?php echo esc_html( $r['cat'] ); ?></span><?php endif; ?>
					</div>
					<div class="bl-card__body">
						<h3 class="bl-card__title"><?php echo esc_html( $r['title'] ); ?></h3>
						<?php if ( $r['excerpt'] ) : ?><p class="bl-card__excerpt"><?php echo esc_html( $r['excerpt'] ); ?></p><?php endif; ?>
						<div class="bl-card__meta">
							<?php if ( $r['author'] ) : ?><span class="bl-meta"><?php echo $bl_ic_user; // phpcs:ignore WordPress.Security.EscapingOutput ?><?php echo esc_html( $r['author'] ); ?></span><?php endif; ?>
							<span class="bl-card__dot" aria-hidden="true"></span>
							<?php if ( $r['date'] ) : ?><span class="bl-meta"><?php echo esc_html( $r['date'] ); ?></span><?php endif; ?>
							<span class="bl-card__dot" aria-hidden="true"></span>
							<?php if ( $r['read'] ) : ?><span class="bl-meta"><?php echo esc_html( $r['read'] ); ?></span><?php endif; ?>
						</div>
					</div>
				</a>
			<?php endforeach; ?>
		</div>

		<div class="bl-posts__more">
			<button type="button" class="bl-load skl-btn skl-btn--ghost-light" data-blog-more hidden><?php echo esc_html( $bl_more_text ); ?></button>
		</div>
	</div>
</section>

<?php
/* Shared Final-CTA band (dynamic via Sklentr Settings → Global → Final CTA Band). */
get_template_part( 'template-parts/home/final-cta' );

get_footer();
