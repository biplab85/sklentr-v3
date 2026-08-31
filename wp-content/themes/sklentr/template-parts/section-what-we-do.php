<?php
/**
 * Section — "What We Do" (services grid with sticky tab nav).
 *
 * Shared part: rendered on the Services page and again on the Pricing page
 * directly after the Packages section. Pulls the same `skl_service` posts and
 * `svc_list_*` options in both places, so editing either updates both.
 *
 * Needs assets/js/svc-tabs.js on any page that renders it (see functions.php).
 *
 * @package Sklentr
 */

defined( 'ABSPATH' ) || exit;

/* ------------------------------------------------------------------ *
 * SECTION 02 — Services grid (light cards)
 * ------------------------------------------------------------------ */
$svc_l_eyebrow = skl_opt( 'svc_list_eyebrow', __( 'What We Do', 'sklentr' ) );
$svc_l_title   = skl_opt( 'svc_list_title', __( 'Everything you need, under', 'sklentr' ) );
$svc_l_accent  = skl_opt( 'svc_list_accent', __( 'one roof.', 'sklentr' ) );
$svc_l_intro   = skl_opt( 'svc_list_intro', __( 'Six services, one accountable team — from the first line of code to launch-day marketing.', 'sklentr' ) );

$svc_items = new WP_Query( array(
	'post_type'      => 'skl_service',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
?>

<section class="svc-list" id="services" aria-labelledby="svc-list-title">

	<!-- Ambient, continuously-floating IT/software icons (decorative only). -->
	<div class="svc-list__deco" aria-hidden="true">
		<?php
		$svc_deco = array(
			'i1'  => array( 'gold',  '<path d="M8 8l-4 4 4 4"/><path d="M16 8l4 4-4 4"/><path d="M13.5 6.5l-3 11"/>' ),
			'i2'  => array( 'slate', '<circle cx="12" cy="12" r="3.2"/><path d="M12 2.5v3M12 18.5v3M2.5 12h3M18.5 12h3M5.2 5.2l2.1 2.1M16.7 16.7l2.1 2.1M18.8 5.2l-2.1 2.1M7.3 16.7l-2.1 2.1"/>' ),
			'i3'  => array( 'green', '<ellipse cx="12" cy="5.5" rx="7.5" ry="2.8"/><path d="M4.5 5.5v6c0 1.55 3.36 2.8 7.5 2.8s7.5-1.25 7.5-2.8v-6"/><path d="M4.5 11.5v6c0 1.55 3.36 2.8 7.5 2.8s7.5-1.25 7.5-2.8v-6"/>' ),
			'i4'  => array( 'slate', '<rect x="3" y="4.5" width="18" height="15" rx="2"/><path d="M7 10l3 2.5-3 2.5"/><path d="M12.5 15.5h4.5"/>' ),
			'i5'  => array( 'gold',  '<path d="M17.5 18.5a4 4 0 0 0 .4-8 6 6 0 0 0-11.5-1.4A3.6 3.6 0 0 0 6.5 18.5z"/>' ),
			'i6'  => array( 'slate', '<rect x="6.5" y="6.5" width="11" height="11" rx="2"/><rect x="9.5" y="9.5" width="5" height="5"/><path d="M9 2.5v2M15 2.5v2M9 19.5v2M15 19.5v2M2.5 9h2M2.5 15h2M19.5 9h2M19.5 15h2"/>' ),
			'i7'  => array( 'green', '<circle cx="6" cy="6" r="2.4"/><circle cx="6" cy="18" r="2.4"/><circle cx="18" cy="8" r="2.4"/><path d="M6 8.4v7.2M8.4 6.4c6.4 0 3.2 3.8 7.2 3.8"/>' ),
			'i8'  => array( 'slate', '<path d="M8 4c-2 0-2 2-2 4s0 3-2 3c2 0 2 1 2 3s0 4 2 4"/><path d="M16 4c2 0 2 2 2 4s0 3 2 3c-2 0-2 1-2 3s0 4-2 4"/>' ),
			'i9'  => array( 'gold',  '<path d="M12 2 2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>' ),
			'i10' => array( 'green', '<path d="M4 16.5l6-6 4 4 6-8"/><path d="M14 6.5h6v6"/>' ),
			'i11' => array( 'slate', '<circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a15 15 0 0 1 4 9 15 15 0 0 1-4 9 15 15 0 0 1-4-9 15 15 0 0 1 4-9z"/>' ),
			'i12' => array( 'gold',  '<rect x="3" y="4" width="18" height="7" rx="1.5"/><rect x="3" y="13" width="18" height="7" rx="1.5"/><path d="M7 7.5h.01M7 16.5h.01"/>' ),
		);
		foreach ( $svc_deco as $svc_pos => $svc_d ) {
			echo '<span class="svc-list__deco-icon svc-list__deco-icon--' . esc_attr( $svc_pos ) . ' is-' . esc_attr( $svc_d[0] ) . '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">' . $svc_d[1] . '</svg></span>'; // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG paths.
		}
		?>
	</div>

	<div class="skl-container">
		<div class="svc-section-head" data-reveal>
			<?php if ( $svc_l_eyebrow ) : ?>
				<p class="skl-eyebrow svc-section-head__eyebrow"><?php echo esc_html( $svc_l_eyebrow ); ?></p>
			<?php endif; ?>
			<h2 class="svc-section-head__title" id="svc-list-title" data-char-fill><?php echo esc_html( trim( $svc_l_title . ' ' . $svc_l_accent ) ); ?></h2>
			<?php if ( $svc_l_intro ) : ?>
				<p class="svc-section-head__intro"><?php echo esc_html( $svc_l_intro ); ?></p>
			<?php endif; ?>
		</div>

		<?php
		// Collect services once so the sticky nav and the cards render from the
		// same source (order = menu_order). Nothing here changes the header above.
		$svc_cards = array();
		if ( $svc_items->have_posts() ) {
			while ( $svc_items->have_posts() ) {
				$svc_items->the_post();
				$svc_cid  = get_the_ID();
				$svc_rev  = get_post_meta( $svc_cid, '_skl_reveal_icon', true );
				$svc_rev  = $svc_rev ? $svc_rev : 'layers';
				$svc_feat = get_post_meta( $svc_cid, '_skl_features', true );
				if ( '' === (string) $svc_feat ) {
					$svc_feat = get_post_meta( $svc_cid, '_skl_tags', true );
				}
				$svc_icon = get_post_meta( $svc_cid, '_skl_icon', true );
				$svc_link = get_post_meta( $svc_cid, '_skl_cta_link', true );
				$svc_cards[] = array(
					'title' => get_the_title(),
					'icon'  => $svc_icon ? $svc_icon : 'rocket',
					'cat'   => get_post_meta( $svc_cid, '_skl_category', true ),
					'desc'  => get_post_meta( $svc_cid, '_skl_desc', true ),
					'price' => get_post_meta( $svc_cid, '_skl_price', true ),
					'cur'   => get_post_meta( $svc_cid, '_skl_currency', true ),
					'time'  => get_post_meta( $svc_cid, '_skl_timeline', true ),
					'link'  => $svc_link ? $svc_link : '#contact',
					'feat'  => array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $svc_feat ) ) ),
					'img'   => has_post_thumbnail() ? get_the_post_thumbnail_url( $svc_cid, 'medium_large' ) : get_theme_file_uri( 'assets/images/services/' . $svc_rev . '.jpg' ),
				);
			}
			wp_reset_postdata();
		}
		?>

			<?php if ( ! empty( $svc_cards ) ) : ?>
				<div class="svc-layout" data-svc-tabs>

					<nav class="svc-nav" aria-label="<?php esc_attr_e( 'Services', 'sklentr' ); ?>">
						<ul class="svc-nav__list">
							<?php foreach ( $svc_cards as $svc_k => $svc_c ) : $svc_n = $svc_k + 1; ?>
								<li>
									<a class="svc-nav__tab<?php echo 0 === $svc_k ? ' is-active' : ''; ?>" href="#svc-card-<?php echo (int) $svc_n; ?>" data-svc-tab="<?php echo (int) $svc_n; ?>"<?php echo 0 === $svc_k ? ' aria-current="true"' : ''; ?>>
										<span class="svc-nav__num"><?php echo esc_html( str_pad( (string) $svc_n, 2, '0', STR_PAD_LEFT ) ); ?></span>
										<span class="svc-nav__label"><?php echo esc_html( $svc_c['title'] ); ?></span>
										<span class="svc-nav__arrow" aria-hidden="true">&rarr;</span>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</nav>

					<ul class="svc-cards">
						<?php foreach ( $svc_cards as $svc_k => $svc_c ) : $svc_n = $svc_k + 1; ?>
							<li class="svc-card" id="svc-card-<?php echo (int) $svc_n; ?>" data-svc-card="<?php echo (int) $svc_n; ?>" data-reveal>
								<div class="svc-card__media">
									<img class="svc-card__img" src="<?php echo esc_url( $svc_c['img'] ); ?>" alt="<?php echo esc_attr( $svc_c['title'] ); ?>" width="640" height="360" loading="lazy" decoding="async">
									<span class="svc-card__num"><?php echo esc_html( str_pad( (string) $svc_n, 2, '0', STR_PAD_LEFT ) ); ?></span>
									<span class="svc-card__icon"><?php echo skl_service_icon_svg( $svc_c['icon'] ); // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG. ?></span>
								</div>

								<div class="svc-card__body">
									<?php if ( $svc_c['cat'] ) : ?>
										<p class="svc-card__cat"><?php echo esc_html( $svc_c['cat'] ); ?></p>
									<?php endif; ?>
									<h3 class="svc-card__title"><?php echo esc_html( $svc_c['title'] ); ?></h3>

									<?php if ( $svc_c['price'] || $svc_c['time'] ) : ?>
										<div class="svc-card__meta">
											<?php if ( $svc_c['price'] ) : ?>
												<span class="svc-card__price">
													<span class="svc-card__price-from"><?php esc_html_e( 'From', 'sklentr' ); ?></span>
													<?php echo esc_html( $svc_c['price'] ); ?>
													<?php if ( $svc_c['cur'] ) : ?><span class="svc-card__price-cur"><?php echo esc_html( $svc_c['cur'] ); ?></span><?php endif; ?>
												</span>
											<?php endif; ?>
											<?php if ( $svc_c['time'] ) : ?>
												<span class="svc-card__time">
													<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9"/><path d="M12 7.5V12l3 1.8"/></svg>
													<?php echo esc_html( $svc_c['time'] ); ?>
												</span>
											<?php endif; ?>
										</div>
									<?php endif; ?>

									<?php if ( $svc_c['desc'] ) : ?>
										<p class="svc-card__desc"><?php echo esc_html( $svc_c['desc'] ); ?></p>
									<?php endif; ?>

									<?php if ( ! empty( $svc_c['feat'] ) ) : ?>
										<ul class="svc-card__features">
											<?php foreach ( $svc_c['feat'] as $svc_f ) : ?>
												<li>
													<span class="svc-card__check" aria-hidden="true"><?php echo skl_check_icon_svg(); // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG. ?></span>
													<?php echo esc_html( $svc_f ); ?>
												</li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>

									<a class="svc-card__cta" href="<?php echo esc_url( $svc_c['link'] ); ?>">
										<?php esc_html_e( 'Get Started', 'sklentr' ); ?>
										<span class="skl-btn__arrow" aria-hidden="true">&rarr;</span>
									</a>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>

				</div>
			<?php endif; ?>
		</div>
	</section>
