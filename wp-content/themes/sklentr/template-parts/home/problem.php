<?php
/**
 * Home / Section 03 — Problem / Empathy ("Why founders come to us").
 * Content is fully dynamic: heading text from Sklentr Settings, cards from the
 * "Problem Cards" CPT. Fallbacks keep the section from ever rendering blank.
 * (Blueprint §7 SECTION 03.)
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$skl_eyebrow  = skl_opt( 'problem_eyebrow', __( 'Why founders come to us', 'sklentr' ) );
$skl_title    = skl_opt( 'problem_title', __( 'Building an MVP shouldn’t take months — or cost a fortune.', 'sklentr' ) );
$skl_intro    = skl_opt( 'problem_intro', __( 'Most founders reach us already burned by an agency. Here’s what usually goes wrong — and how we do it differently.', 'sklentr' ) );
$skl_cta_text = skl_opt( 'problem_cta_text', __( 'See how we work', 'sklentr' ) );
$skl_cta_link = skl_opt( 'problem_cta_link', '#how-we-work' );

$skl_cards = new WP_Query( array(
	'post_type'      => 'skl_problem',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
?>

<section class="problem" id="why-founders" aria-labelledby="problem-title">
	<div class="skl-container">

		<div class="problem__head" data-reveal>
			<?php if ( $skl_eyebrow ) : ?>
				<p class="problem__eyebrow skl-eyebrow"><?php echo esc_html( $skl_eyebrow ); ?></p>
			<?php endif; ?>
			<h2 class="problem__title" id="problem-title" data-char-fill><?php echo esc_html( $skl_title ); ?></h2>
			<?php if ( $skl_intro ) : ?>
				<p class="problem__intro"><?php echo esc_html( $skl_intro ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $skl_cards->have_posts() ) : ?>
			<ul class="problem__grid" data-advance>
				<?php
				while ( $skl_cards->have_posts() ) :
					$skl_cards->the_post();
					$skl_tone     = get_post_meta( get_the_ID(), '_skl_tone', true );
					$skl_icon     = get_post_meta( get_the_ID(), '_skl_icon', true );
					$skl_problem  = get_post_meta( get_the_ID(), '_skl_problem_text', true );
					$skl_tone     = $skl_tone ? $skl_tone : 'gold';
					$skl_icon     = $skl_icon ? $skl_icon : 'clock';
					?>
					<li class="problem-card problem-card--<?php echo esc_attr( $skl_tone ); ?>">
						<div class="problem-card__main">
							<span class="problem-card__icon problem-card__icon--<?php echo esc_attr( $skl_tone ); ?>"><?php echo skl_problem_icon_svg( $skl_icon ); // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG. ?></span>
							<div class="problem-card__body">
								<h3 class="problem-card__label"><?php the_title(); ?></h3>
								<?php if ( $skl_problem ) : ?>
									<p class="problem-card__problem"><?php echo esc_html( $skl_problem ); ?></p>
								<?php endif; ?>
							</div>
						</div>
						<?php
						// Real photo for the reveal panel: the card's Featured Image if
						// one is set in wp-admin, otherwise the bundled per-tone default.
						$skl_media = has_post_thumbnail()
							? get_the_post_thumbnail_url( get_the_ID(), 'large' )
							: get_theme_file_uri( 'assets/images/problem/' . $skl_tone . '.jpg' );
						?>
						<span class="problem-card__media problem-card__media--<?php echo esc_attr( $skl_tone ); ?>" aria-hidden="true">
							<img class="problem-card__img" src="<?php echo esc_url( $skl_media ); ?>" alt="" width="800" height="700" loading="lazy" decoding="async">
						</span>
					</li>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</ul>
		<?php endif; ?>

		<?php if ( $skl_cta_text && $skl_cta_link ) : ?>
			<p class="problem__cta" data-reveal>
				<a class="skl-textlink" href="<?php echo esc_url( $skl_cta_link ); ?>">
					<?php echo esc_html( $skl_cta_text ); ?>
					<span class="skl-textlink__arrow" aria-hidden="true">&rarr;</span>
				</a>
			</p>
		<?php endif; ?>

	</div>
</section>
