<?php
/**
 * Home / Section 13 — Insights / Blog (Thought Leadership + SEO).
 * Shows the 3 latest WordPress posts as cards (image/placeholder, category,
 * date, read-time, excerpt, read-more), a "Read All Insights" CTA, and a
 * newsletter opt-in. Fully dynamic: posts are managed under Posts; section text
 * from Sklentr Settings. (Blueprint §13.) Micro-animations: staggered card
 * reveal, hover lift + image zoom, read-more arrow.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$skl_eyebrow  = skl_opt( 'insights_eyebrow', __( 'Insights', 'sklentr' ) );
$skl_title    = skl_opt( 'insights_title', __( 'Playbooks for founders,', 'sklentr' ) );
$skl_accent   = skl_opt( 'insights_title_accent', __( 'not fluff.', 'sklentr' ) );
$skl_intro    = skl_opt( 'insights_intro', '' );
$skl_cta_text = skl_opt( 'insights_cta_text', __( 'Read All Insights', 'sklentr' ) );
$skl_cta_link = skl_opt( 'insights_cta_link', '/blog/' );
// Resolve a root-relative path (e.g. "/blog/") against the WP install so it
// respects the /sklentr/sklentr-v2/ subdirectory instead of hitting domain root.
if ( '' !== $skl_cta_link && false === strpos( $skl_cta_link, '://' ) ) {
	$skl_cta_link = home_url( '/' . ltrim( $skl_cta_link, '/' ) );
}

$skl_news_title = skl_opt( 'news_title', __( 'Get the founder’s playbook', 'sklentr' ) );
$skl_news_text  = skl_opt( 'news_text', '' );
$skl_news_ph    = skl_opt( 'news_placeholder', __( 'you@company.com', 'sklentr' ) );
$skl_news_btn   = skl_opt( 'news_button', __( 'Subscribe', 'sklentr' ) );
$skl_news_ok    = skl_opt( 'news_success', __( 'Thanks! Check your inbox to confirm.', 'sklentr' ) );

$skl_posts = new WP_Query( array(
	'post_type'           => 'post',
	'posts_per_page'      => 3,
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
) );
?>

<section class="insights" id="insights" aria-labelledby="insights-title">
	<div class="skl-container">

		<div class="insights__head" data-reveal>
			<div class="insights__head-text">
				<?php if ( $skl_eyebrow ) : ?>
					<p class="insights__eyebrow skl-eyebrow"><?php echo esc_html( $skl_eyebrow ); ?></p>
				<?php endif; ?>
				<h2 class="insights__title" id="insights-title" data-char-fill><?php echo esc_html( trim( $skl_title . ' ' . $skl_accent ) ); ?></h2>
				<?php if ( $skl_intro ) : ?>
					<p class="insights__intro"><?php echo esc_html( $skl_intro ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $skl_cta_text && $skl_cta_link ) : ?>
				<a class="skl-btn skl-btn--ghost-light insights__cta" href="<?php echo esc_url( $skl_cta_link ); ?>">
					<?php echo esc_html( $skl_cta_text ); ?>
					<span class="skl-btn__arrow" aria-hidden="true">&rarr;</span>
				</a>
			<?php endif; ?>
		</div>

		<?php if ( $skl_posts->have_posts() ) : ?>
			<ul class="insights__grid" data-advance>
				<?php
				$skl_i = 0;
				while ( $skl_posts->have_posts() ) :
					$skl_posts->the_post();
					++$skl_i;
					$skl_cats     = get_the_category();
					$skl_cat_name = ! empty( $skl_cats ) ? $skl_cats[0]->name : '';
					$skl_words    = str_word_count( wp_strip_all_tags( get_the_content() ) );
					$skl_read     = max( 1, (int) ceil( $skl_words / 200 ) );
					// Image: the post's Featured Image if set, else a content-matched
					// bundled photo keyed by category (falls back to 'idea').
					$skl_img_map  = array( 'startup visa' => 'visa', 'product' => 'mvp', 'mvp strategy' => 'idea' );
					$skl_fallback = isset( $skl_img_map[ strtolower( $skl_cat_name ) ] ) ? $skl_img_map[ strtolower( $skl_cat_name ) ] : 'idea';
					$skl_src      = has_post_thumbnail()
						? get_the_post_thumbnail_url( get_the_ID(), 'medium_large' )
						: get_theme_file_uri( 'assets/images/blog/' . $skl_fallback . '.jpg' );
					?>
					<li class="insight-card" style="--i:<?php echo esc_attr( $skl_i - 1 ); ?>">
						<a class="insight-card__link" href="<?php the_permalink(); ?>">
							<span class="insight-card__media">
								<img class="insight-card__img" src="<?php echo esc_url( $skl_src ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" width="1000" height="625" loading="lazy" decoding="async">
								<?php if ( $skl_cat_name ) : ?>
									<span class="insight-card__cat"><?php echo esc_html( $skl_cat_name ); ?></span>
								<?php endif; ?>
							</span>

							<span class="insight-card__body">
								<span class="insight-card__meta">
									<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
									<span class="insight-card__dot" aria-hidden="true"></span>
									<?php
									/* translators: %d: estimated reading time in minutes. */
									echo esc_html( sprintf( _n( '%d min read', '%d min read', $skl_read, 'sklentr' ), $skl_read ) );
									?>
								</span>
								<h3 class="insight-card__title"><?php the_title(); ?></h3>
								<p class="insight-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
								<span class="insight-card__more">
									<?php esc_html_e( 'Read More', 'sklentr' ); ?>
									<span class="insight-card__more-arrow" aria-hidden="true">&rarr;</span>
								</span>
							</span>
						</a>
					</li>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</ul>
		<?php endif; ?>

	</div>
</section>
