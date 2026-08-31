<?php
/**
 * Home / Section 04 — Services (dark anchor, row-list — matches sklentr.com).
 * Fully dynamic: heading/CTA from Sklentr Settings, rows from the "Services"
 * CPT (title, description, feature tags, icon). (Blueprint §7 SECTION 04.)
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$skl_eyebrow  = skl_opt( 'services_eyebrow', __( 'Services', 'sklentr' ) );
$skl_title    = skl_opt( 'services_title', __( 'Everything you need to', 'sklentr' ) );
$skl_accent   = skl_opt( 'services_title_accent', __( 'launch.', 'sklentr' ) );
$skl_intro    = skl_opt( 'services_intro', '' );
$skl_cta_text = skl_opt( 'services_cta_text', __( 'See Our Services', 'sklentr' ) );
$skl_cta_link = skl_resolve_link( skl_opt( 'services_cta_link', '/services' ) );

$skl_services = new WP_Query( array(
	'post_type'      => 'skl_service',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
?>

<section class="services" id="services" aria-labelledby="services-title">

	<!-- Decorative IT/software icons: 4 on the left, 4 on the right (ambient) -->
	<div class="services__deco" aria-hidden="true">
		<?php
		$skl_deco = array(
			// Left column.
			'l1' => '<path d="M8 8l-4 4 4 4"/><path d="M16 8l4 4-4 4"/><path d="M13.5 6.5l-3 11"/>',
			'l2' => '<rect x="6.5" y="6.5" width="11" height="11" rx="2"/><rect x="9.5" y="9.5" width="5" height="5"/><path d="M9 2.5v2M15 2.5v2M9 19.5v2M15 19.5v2M2.5 9h2M2.5 15h2M19.5 9h2M19.5 15h2"/>',
			'l3' => '<rect x="3" y="4.5" width="18" height="15" rx="2"/><path d="M7 10l3 2.5-3 2.5"/><path d="M12.5 15.5h4.5"/>',
			'l4' => '<path d="M17.5 18.5a4 4 0 0 0 .4-8 6 6 0 0 0-11.5-1.4A3.6 3.6 0 0 0 6.5 18.5z"/>',
			// Right column.
			'r1' => '<ellipse cx="12" cy="5.5" rx="7.5" ry="2.8"/><path d="M4.5 5.5v6c0 1.55 3.36 2.8 7.5 2.8s7.5-1.25 7.5-2.8v-6"/><path d="M4.5 11.5v6c0 1.55 3.36 2.8 7.5 2.8s7.5-1.25 7.5-2.8v-6"/>',
			'r2' => '<circle cx="12" cy="12" r="3.2"/><path d="M12 2.5v3M12 18.5v3M2.5 12h3M18.5 12h3M5.2 5.2l2.1 2.1M16.7 16.7l2.1 2.1M18.8 5.2l-2.1 2.1M7.3 16.7l-2.1 2.1"/>',
			'r3' => '<rect x="3" y="4" width="18" height="7" rx="1.5"/><rect x="3" y="13" width="18" height="7" rx="1.5"/><path d="M7 7.5h.01M7 16.5h.01"/>',
			'r4' => '<path d="M8 4c-2 0-2 2-2 4s0 3-2 3c2 0 2 1 2 3s0 4 2 4"/><path d="M16 4c2 0 2 2 2 4s0 3 2 3c-2 0-2 1-2 3s0 4-2 4"/>',
		);
		foreach ( $skl_deco as $skl_pos => $skl_paths ) {
			echo '<span class="services__deco-icon services__deco-icon--' . esc_attr( $skl_pos ) . '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">' . $skl_paths . '</svg></span>'; // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG paths.
		}
		?>
	</div>

	<div class="skl-container">

		<div class="services__head" data-reveal>
			<?php if ( $skl_eyebrow ) : ?>
				<p class="services__eyebrow skl-eyebrow"><?php echo esc_html( $skl_eyebrow ); ?></p>
			<?php endif; ?>
			<h2 class="services__title" id="services-title" data-char-fill="dark"><?php echo esc_html( trim( $skl_title . ' ' . $skl_accent ) ); ?></h2>
			<?php if ( $skl_intro ) : ?>
				<p class="services__intro"><?php echo esc_html( $skl_intro ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $skl_services->have_posts() ) : ?>
			<ul class="services__list">
				<?php
				$skl_i = 0;
				while ( $skl_services->have_posts() ) :
					$skl_services->the_post();
					++$skl_i;
					$skl_icon        = get_post_meta( get_the_ID(), '_skl_icon', true );
					$skl_reveal_icon = get_post_meta( get_the_ID(), '_skl_reveal_icon', true );
					$skl_desc        = get_post_meta( get_the_ID(), '_skl_desc', true );
					$skl_tags        = get_post_meta( get_the_ID(), '_skl_tags', true );
					$skl_tags        = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $skl_tags ) ) );
					$skl_icon        = $skl_icon ? $skl_icon : 'rocket';
					$skl_reveal_icon = $skl_reveal_icon ? $skl_reveal_icon : 'layers';
					// Hover-card visual: the service's Featured Image if set, else the
					// bundled per-service default photo (keyed by the reveal-icon value).
					$skl_reveal_img  = has_post_thumbnail()
						? get_the_post_thumbnail_url( get_the_ID(), 'medium_large' )
						: get_theme_file_uri( 'assets/images/services/' . $skl_reveal_icon . '.jpg' );
					?>
					<li class="service-row" data-reveal>
						<span class="service-row__num"><?php echo esc_html( str_pad( (string) $skl_i, 2, '0', STR_PAD_LEFT ) ); ?></span>
						<h3 class="service-row__title"><?php the_title(); ?></h3>
						<div class="service-row__body">
							<?php if ( $skl_desc ) : ?>
								<p class="service-row__desc"><?php echo esc_html( $skl_desc ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $skl_tags ) ) : ?>
								<ul class="service-row__tags">
									<?php foreach ( $skl_tags as $skl_tag ) : ?>
										<li><?php echo esc_html( $skl_tag ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>
						<span class="service-row__icon"><?php echo skl_service_icon_svg( $skl_icon ); // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG. ?></span>

						<span class="service-row__reveal" aria-hidden="true">
							<span class="service-row__reveal-svg"><img class="service-row__reveal-img" src="<?php echo esc_url( $skl_reveal_img ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" width="640" height="430" loading="lazy" decoding="async"></span>
							<span class="service-row__reveal-content">
								<span class="service-row__reveal-title"><?php echo esc_html( get_the_title() ); ?></span>
								<?php if ( $skl_desc ) : ?>
									<span class="service-row__reveal-desc"><?php echo esc_html( $skl_desc ); ?></span>
								<?php endif; ?>
								<?php if ( ! empty( $skl_tags ) ) : ?>
									<ul class="service-row__reveal-tags">
										<?php foreach ( $skl_tags as $skl_tag ) : ?>
											<li><?php echo esc_html( $skl_tag ); ?></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</span>
						</span>
					</li>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</ul>
		<?php endif; ?>

		<?php if ( $skl_cta_text && $skl_cta_link ) : ?>
			<div class="services__cta" data-reveal>
				<a class="skl-btn skl-btn--ghost-dark" href="<?php echo esc_url( $skl_cta_link ); ?>">
					<?php echo esc_html( $skl_cta_text ); ?>
					<span class="skl-btn__arrow" aria-hidden="true">&rarr;</span>
				</a>
			</div>
		<?php endif; ?>

	</div>
</section>
