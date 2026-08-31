<?php
/**
 * Home / Section 08 — How We Work (Process).
 * Fully dynamic: heading/CTA from Sklentr Settings, steps from the "Process
 * Steps" CPT. Design mirrors the iteck "process section": a minimal 4-across
 * staircase — each step fronted by an oversized light-grey numeral, steps 1 & 3
 * on the top rail and 2 & 4 dropped, joined by a faint grey connector (top rail
 * + short drop-ticks). Steps fade up in a stagger on scroll. No-JS /
 * reduced-motion shows the finished state. (Blueprint §7 SECTION 08.)
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$skl_eyebrow  = skl_opt( 'process_eyebrow', __( 'How We Work', 'sklentr' ) );
$skl_title    = skl_opt( 'process_title', __( 'A clear path from idea to launch.', 'sklentr' ) );
$skl_intro    = skl_opt( 'process_intro', '' );
$skl_cta_text = skl_opt( 'process_cta_text', __( 'Start With a Free Discovery Call', 'sklentr' ) );
$skl_cta_link = skl_opt( 'process_cta_link', '#contact' );

$skl_steps = new WP_Query( array(
	'post_type'      => 'skl_process',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
$skl_count = max( 1, (int) $skl_steps->post_count );
?>

<section class="process" id="how-we-work" aria-labelledby="process-title">
	<div class="skl-container">

		<div class="process__head" data-reveal>
			<?php if ( $skl_eyebrow ) : ?>
				<p class="process__eyebrow skl-eyebrow"><?php echo esc_html( $skl_eyebrow ); ?></p>
			<?php endif; ?>
			<h2 class="process__title" id="process-title" data-char-fill><?php echo esc_html( $skl_title ); ?></h2>
			<?php if ( $skl_intro ) : ?>
				<p class="process__intro"><?php echo esc_html( $skl_intro ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $skl_steps->have_posts() ) : ?>
			<div class="process__timeline">
				<ol class="process__steps" style="--count:<?php echo esc_attr( $skl_count ); ?>">
					<?php
					$skl_i = 0;
					while ( $skl_steps->have_posts() ) :
						$skl_steps->the_post();
						++$skl_i;
						$skl_desc = get_post_meta( get_the_ID(), '_skl_desc', true );
						?>
						<li class="process-step" style="--i:<?php echo esc_attr( $skl_i - 1 ); ?>">
							<span class="process-step__num" aria-hidden="true"><?php echo esc_html( (string) $skl_i ); ?></span>
							<h3 class="process-step__title"><?php the_title(); ?></h3>
							<?php if ( $skl_desc ) : ?>
								<p class="process-step__desc"><?php echo esc_html( $skl_desc ); ?></p>
							<?php endif; ?>
						</li>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</ol>
			</div>
		<?php endif; ?>

		<?php if ( $skl_cta_text && $skl_cta_link ) : ?>
			<div class="process__cta" data-reveal>
				<a class="skl-btn skl-btn--dark" href="<?php echo esc_url( $skl_cta_link ); ?>">
					<?php echo esc_html( $skl_cta_text ); ?>
					<span class="skl-btn__arrow" aria-hidden="true">&rarr;</span>
				</a>
			</div>
		<?php endif; ?>

	</div>
</section>
