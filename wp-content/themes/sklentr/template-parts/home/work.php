<?php
/**
 * Home / Section 07 — Featured Work / Case Studies.
 * Fully dynamic: heading/CTA from Sklentr Settings, cards from the "Featured
 * Work" CPT (industry, outcome, tech tags, image, link). Each card's thumbnail
 * is its Featured Image if set, else the bundled per-industry default.
 *
 * On desktop the card row is a pinned, scroll-driven horizontal slider with a
 * cursor-following "View" badge (assets/js/work-slider.js); on touch / reduced
 * motion it falls back to a native horizontal swipe. (Blueprint §7 SECTION 07.)
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$skl_eyebrow  = skl_opt( 'work_eyebrow', __( 'Featured Work', 'sklentr' ) );
$skl_title    = skl_opt( 'work_title', __( 'Real products, real outcomes.', 'sklentr' ) );
$skl_intro    = skl_opt( 'work_intro', '' );
$skl_cta_text = skl_opt( 'work_cta_text', __( 'View All Work', 'sklentr' ) );
$skl_cta_link = skl_opt( 'work_cta_link', '/portfolio' );

$skl_cta_link = skl_resolve_link( $skl_cta_link );

$skl_work = new WP_Query( array(
	'post_type'      => 'skl_work',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
?>

<section class="work" id="work" aria-labelledby="work-title">

	<div class="skl-container">
		<div class="work__head" data-reveal>
			<?php if ( $skl_eyebrow ) : ?>
				<p class="work__eyebrow skl-eyebrow"><?php echo esc_html( $skl_eyebrow ); ?></p>
			<?php endif; ?>
			<h2 class="work__title" id="work-title" data-char-fill><?php echo esc_html( $skl_title ); ?></h2>
			<?php if ( $skl_intro ) : ?>
				<p class="work__intro"><?php echo esc_html( $skl_intro ); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( $skl_work->have_posts() ) : ?>
		<div class="work__scroll">
			<div class="work__sticky">
				<ul class="work__grid">
					<?php
					while ( $skl_work->have_posts() ) :
						$skl_work->the_post();
						$skl_industry = get_post_meta( get_the_ID(), '_skl_industry', true );
						$skl_outcome  = get_post_meta( get_the_ID(), '_skl_outcome', true );
						$skl_link     = get_post_meta( get_the_ID(), '_skl_link', true );
						$skl_key      = get_post_meta( get_the_ID(), '_skl_img', true );
						$skl_key      = $skl_key ? $skl_key : 'data';
						$skl_tags     = get_post_meta( get_the_ID(), '_skl_tags', true );
						$skl_tags     = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $skl_tags ) ) );
						$skl_link     = $skl_link ? skl_resolve_link( $skl_link ) : $skl_cta_link;
						$skl_src      = has_post_thumbnail()
							? get_the_post_thumbnail_url( get_the_ID(), 'medium_large' )
							: get_theme_file_uri( 'assets/images/work/' . $skl_key . '.jpg' );
						?>
						<li class="work-card">
							<a class="work-card__link" href="<?php echo esc_url( $skl_link ); ?>">
								<span class="work-card__media">
									<img class="work-card__img" src="<?php echo esc_url( $skl_src ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" width="760" height="500" loading="lazy" decoding="async">
									<?php if ( $skl_industry ) : ?>
										<span class="work-card__industry"><?php echo esc_html( $skl_industry ); ?></span>
									<?php endif; ?>
									<span class="work-card__overlay" aria-hidden="true"></span>
								</span>
								<span class="work-card__body">
									<h3 class="work-card__title"><?php the_title(); ?></h3>
									<?php if ( $skl_outcome ) : ?>
										<span class="work-card__outcome"><?php echo esc_html( $skl_outcome ); ?></span>
									<?php endif; ?>
									<?php if ( ! empty( $skl_tags ) ) : ?>
										<ul class="work-card__tags">
											<?php foreach ( $skl_tags as $skl_tag ) : ?>
												<li><?php echo esc_html( $skl_tag ); ?></li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</span>
							</a>
						</li>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</ul>
			</div><!-- /.work__sticky -->
		</div><!-- /.work__scroll -->
	<?php endif; ?>

	<?php if ( $skl_cta_text && $skl_cta_link ) : ?>
		<div class="skl-container">
			<div class="work__cta" data-reveal>
				<a class="skl-btn skl-btn--dark" href="<?php echo esc_url( $skl_cta_link ); ?>">
					<?php echo esc_html( $skl_cta_text ); ?>
					<span class="skl-btn__arrow" aria-hidden="true">&rarr;</span>
				</a>
			</div>
		</div>
	<?php endif; ?>

	<div class="work__cursor" aria-hidden="true">
		<span class="work__cursor-text"><?php esc_html_e( 'View', 'sklentr' ); ?> <span class="work__cursor-arrow">&rarr;</span></span>
	</div>
</section>
