<?php
/**
 * Home / Section 05 — Why Sklentr (the 4 "why us" pillars).
 * Light gradient band with micro-animations. Fully dynamic: heading/CTA from
 * Sklentr Settings, pillars from the "Why-Us Pillars" CPT. (Blueprint §7 SECTION 05.)
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$skl_eyebrow  = skl_opt( 'pillar_eyebrow', __( 'Why Sklentr', 'sklentr' ) );
$skl_title    = skl_opt( 'pillar_title', __( 'Built different — on purpose.', 'sklentr' ) );
$skl_intro    = skl_opt( 'pillar_intro', '' );
$skl_cta_text = skl_opt( 'pillar_cta_text', __( 'Meet the team', 'sklentr' ) );
$skl_cta_link = skl_resolve_link( skl_opt( 'pillar_cta_link', '/about' ) );

$skl_pillars = new WP_Query( array(
	'post_type'      => 'skl_pillar',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
?>

<section class="why" id="why-sklentr" aria-labelledby="why-title">
	<div class="skl-container">

		<div class="why__head" data-reveal>
			<?php if ( $skl_eyebrow ) : ?>
				<p class="why__eyebrow skl-eyebrow"><?php echo esc_html( $skl_eyebrow ); ?></p>
			<?php endif; ?>
			<h2 class="why__title" id="why-title" data-char-fill><?php echo esc_html( $skl_title ); ?></h2>
			<?php if ( $skl_intro ) : ?>
				<p class="why__intro"><?php echo esc_html( $skl_intro ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $skl_pillars->have_posts() ) : ?>
			<ul class="why__stack">
				<?php
				$skl_i = 0;
				while ( $skl_pillars->have_posts() ) :
					$skl_pillars->the_post();
					++$skl_i;
					$skl_icon   = get_post_meta( get_the_ID(), '_skl_icon', true );
					$skl_desc   = get_post_meta( get_the_ID(), '_skl_desc', true );
					$skl_points = get_post_meta( get_the_ID(), '_skl_points', true );
					$skl_icon   = $skl_icon ? $skl_icon : 'bolt';
					// One highlight per line → trimmed, non-empty list.
					$skl_point_list = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $skl_points ) ) );
					?>
					<li class="pillar" style="--i: <?php echo (int) ( $skl_i - 1 ); ?>;">
						<div class="pillar__body">
							<span class="pillar__num"><?php echo esc_html( str_pad( (string) $skl_i, 2, '0', STR_PAD_LEFT ) ); ?></span>
							<h3 class="pillar__title"><?php the_title(); ?></h3>
							<?php if ( $skl_desc ) : ?>
								<p class="pillar__desc"><?php echo esc_html( $skl_desc ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $skl_point_list ) ) : ?>
								<ul class="pillar__points">
									<?php foreach ( $skl_point_list as $skl_point ) : ?>
										<li class="pillar__point">
											<span class="pillar__point-tick" aria-hidden="true">
												<?php echo skl_check_icon_svg(); // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG. ?>
											</span>
											<span class="pillar__point-text"><?php echo esc_html( $skl_point ); ?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>
						<span class="pillar__icon"><?php echo skl_pillar_icon_svg( $skl_icon ); // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG. ?></span>
					</li>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</ul>
		<?php endif; ?>

		<?php if ( $skl_cta_text && $skl_cta_link ) : ?>
			<p class="why__cta" data-reveal>
				<a class="skl-textlink" href="<?php echo esc_url( $skl_cta_link ); ?>">
					<?php echo esc_html( $skl_cta_text ); ?>
					<span class="skl-textlink__arrow" aria-hidden="true">&rarr;</span>
				</a>
			</p>
		<?php endif; ?>

	</div>
</section>
