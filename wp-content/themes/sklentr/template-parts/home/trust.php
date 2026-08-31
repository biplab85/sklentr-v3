<?php
/**
 * Home / Section 02 — Trust Bar / Social-Proof Strip.
 * Fully dynamic: stats from the "Trust Stats" CPT, projects from the "Trusted
 * Projects" CPT, labels from Sklentr Settings. Markup/animation unchanged.
 * (Blueprint §7 SECTION 02.)
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$skl_heading     = skl_opt( 'trust_heading', __( 'Sklentr by the numbers', 'sklentr' ) );
$skl_proof_label = skl_opt( 'trust_proof_label', __( 'Trusted by founders building', 'sklentr' ) );

$skl_stats = new WP_Query( array(
	'post_type'      => 'skl_stat',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );

// Collect project names into an array (rendered twice for the seamless loop).
$skl_projects   = array();
$skl_projects_q = new WP_Query( array(
	'post_type'      => 'skl_project',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
if ( $skl_projects_q->have_posts() ) {
	while ( $skl_projects_q->have_posts() ) {
		$skl_projects_q->the_post();
		$skl_projects[] = get_the_title();
	}
	wp_reset_postdata();
}
?>

<section class="trust" aria-labelledby="trust-heading">
	<div class="skl-container">

		<h2 id="trust-heading" class="screen-reader-text"><?php echo esc_html( $skl_heading ); ?></h2>

		<?php if ( $skl_stats->have_posts() ) : ?>
			<ul class="trust__stats">
				<?php
				while ( $skl_stats->have_posts() ) :
					$skl_stats->the_post();
					$skl_num    = get_post_meta( get_the_ID(), '_skl_number', true );
					$skl_suffix = get_post_meta( get_the_ID(), '_skl_suffix', true );
					?>
					<li class="stat">
						<span class="stat__num">
							<span class="stat__val" data-count-to="<?php echo esc_attr( $skl_num ); ?>"><?php echo esc_html( $skl_num ); ?></span><span class="stat__suffix"><?php echo esc_html( $skl_suffix ); ?></span>
						</span>
						<span class="stat__label"><?php the_title(); ?></span>
					</li>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</ul>
		<?php endif; ?>

		<?php if ( ! empty( $skl_projects ) ) : ?>
			<div class="trust__proof">
				<p class="trust__proof-label"><?php echo esc_html( $skl_proof_label ); ?></p>

				<div class="marquee">
					<div class="marquee__track">
						<?php for ( $skl_copy = 0; $skl_copy < 2; $skl_copy++ ) : ?>
							<ul class="marquee__group"<?php echo $skl_copy ? ' aria-hidden="true"' : ''; ?>>
								<?php foreach ( $skl_projects as $skl_project ) : ?>
									<li class="marquee__item"><?php echo esc_html( $skl_project ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endfor; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

	</div>
</section>
