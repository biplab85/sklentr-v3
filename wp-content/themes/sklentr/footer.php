<?php
/**
 * Footer — Section 16: mega-footer (dark anchor), Ritovex-style layout.
 *   1. Newsletter hero row  — big heading + underline email field
 *   2. 4-column grid         — 3 link columns (menus) + a Contact column w/ icons
 *   3. Middle bar            — credit line + "Follow Us" social icons
 *   4. Giant wordmark        — oversized decorative SKL=NTR at the very bottom
 * Fully dynamic: columns from Appearance → Menus, text from Sklentr Settings,
 * socials/contact/newsletter reuse existing options (no duplicate data entry).
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Column titles. */
$skl_col_serv  = skl_opt( 'footer_col_services', __( 'Services', 'sklentr' ) );
$skl_col_comp  = skl_opt( 'footer_col_company', __( 'Company', 'sklentr' ) );
$skl_col_res   = skl_opt( 'footer_col_resources', __( 'Resources', 'sklentr' ) );
$skl_col_cont  = skl_opt( 'footer_contact_title', __( 'Contact Us', 'sklentr' ) );

/* Contact (reused global contact options). */
$skl_address   = skl_opt( 'footer_address', __( 'Toronto, Ontario, Canada', 'sklentr' ) );
$skl_email     = skl_opt( 'cta_email', '' );
$skl_phone     = skl_opt( 'cta_phone', '' );
$skl_whatsapp  = skl_opt( 'cta_whatsapp', '' );

/* Bottom bar. */
$skl_credit    = skl_opt( 'footer_credit', '' );
$skl_follow    = skl_opt( 'footer_follow_label', __( 'Follow Us', 'sklentr' ) );
$skl_megamark  = skl_opt( 'footer_megamark', '' );

/* Newsletter (reused Insights options; big heading has its own footer key). */
$skl_news_title = skl_opt( 'footer_news_title', __( 'Get founder-grade MVP & Startup Visa tips straight to your inbox.', 'sklentr' ) );
$skl_news_ph    = skl_opt( 'news_placeholder', __( 'you@company.com', 'sklentr' ) );
$skl_news_btn   = skl_opt( 'news_button', __( 'Subscribe Now', 'sklentr' ) );
$skl_news_ok    = skl_opt( 'news_success', __( 'Thanks! Check your inbox to confirm.', 'sklentr' ) );

/* Socials (reused About options). */
$skl_socials = array(
	'linkedin'  => skl_opt( 'social_linkedin', '' ),
	'x'         => skl_opt( 'social_x', '' ),
	'facebook'  => skl_opt( 'social_facebook', '' ),
	'instagram' => skl_opt( 'social_instagram', '' ),
);
/* Lucide icons — matched to the live sklentr.com footer. */
$skl_social_svg = array(
	'linkedin'  => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>',
	'x'         => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>',
	'facebook'  => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
	'instagram' => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>',
);
$skl_social_labels = array(
	'linkedin'  => __( 'LinkedIn', 'sklentr' ),
	'x'         => __( 'X', 'sklentr' ),
	'facebook'  => __( 'Facebook', 'sklentr' ),
	'instagram' => __( 'Instagram', 'sklentr' ),
);
?>
</main><!-- #content -->

<footer class="site-footer"<?php echo is_front_page() ? '' : ' id="contact"'; ?>><?php // On the front page the Final CTA band owns #contact; elsewhere the footer does. ?>

	<div class="skl-container">

		<!-- 1 — Newsletter hero row -->
		<div class="footer-news">
			<?php if ( $skl_news_title ) : ?>
				<h2 class="footer-news__title"><?php echo esc_html( $skl_news_title ); ?></h2>
			<?php endif; ?>

			<form class="footer-news__form" data-news-form novalidate>
				<div class="news-form__row footer-news__row">
					<label class="screen-reader-text" for="footer-news-email"><?php echo esc_html( $skl_news_ph ); ?></label>
					<input class="footer-news__input" type="email" id="footer-news-email" name="email" placeholder="<?php echo esc_attr( $skl_news_ph ); ?>" autocomplete="email" required>
					<button class="footer-news__submit" type="submit">
						<?php echo esc_html( $skl_news_btn ); ?>
						<span class="footer-news__arrow" aria-hidden="true">&rarr;</span>
					</button>
				</div>
				<p class="footer-news__success" data-news-success hidden><?php echo esc_html( $skl_news_ok ); ?></p>
			</form>
		</div>

		<!-- 2 — Column grid -->
		<div class="footer-cols">

			<nav class="footer-col" aria-label="<?php echo esc_attr( $skl_col_serv ); ?>">
				<h3 class="footer-col__title"><?php echo esc_html( $skl_col_serv ); ?></h3>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer-services',
					'container'      => false,
					'menu_class'     => 'footer-menu',
					'depth'          => 1,
					'fallback_cb'    => 'sklentr_footer_services_menu_fallback',
				) );
				?>
			</nav>

			<nav class="footer-col" aria-label="<?php echo esc_attr( $skl_col_comp ); ?>">
				<h3 class="footer-col__title"><?php echo esc_html( $skl_col_comp ); ?></h3>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer-company',
					'container'      => false,
					'menu_class'     => 'footer-menu',
					'depth'          => 1,
					'fallback_cb'    => 'sklentr_footer_company_menu_fallback',
				) );
				?>
			</nav>

			<nav class="footer-col" aria-label="<?php echo esc_attr( $skl_col_res ); ?>">
				<h3 class="footer-col__title"><?php echo esc_html( $skl_col_res ); ?></h3>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer-resources',
					'container'      => false,
					'menu_class'     => 'footer-menu',
					'depth'          => 1,
					'fallback_cb'    => 'sklentr_footer_resources_menu_fallback',
				) );
				?>
			</nav>

			<div class="footer-col footer-contact">
				<h3 class="footer-col__title"><?php echo esc_html( $skl_col_cont ); ?></h3>
				<ul class="footer-contact__list">
					<?php if ( $skl_phone ) : ?>
						<li class="footer-contact__item">
							<span class="footer-contact__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M5 4h3.5l1.5 4-2 1.5a11 11 0 0 0 5 5l1.5-2 4 1.5V19a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg></span>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $skl_phone ) ); ?>"><?php echo esc_html( $skl_phone ); ?></a>
						</li>
					<?php endif; ?>
					<?php if ( $skl_email ) : ?>
						<li class="footer-contact__item">
							<span class="footer-contact__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" focusable="false"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="M4 7l8 6 8-6"/></svg></span>
							<a href="mailto:<?php echo esc_attr( sanitize_email( $skl_email ) ); ?>"><?php echo esc_html( $skl_email ); ?></a>
						</li>
					<?php endif; ?>
					<?php if ( $skl_whatsapp ) : ?>
						<li class="footer-contact__item">
							<span class="footer-contact__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="currentColor" focusable="false"><path d="M12 2.2A9.8 9.8 0 0 0 3.6 17l-1.3 4.8 4.9-1.3A9.8 9.8 0 1 0 12 2.2zm0 1.8a8 8 0 0 1 6.8 12.2l-.2.3.8 2.9-3-.8-.3.2A8 8 0 1 1 12 4zm-3.1 3.7c-.2 0-.5.1-.7.4-.3.3-.9.9-.9 2.1s.9 2.4 1 2.6c.1.2 1.7 2.7 4.2 3.7 2.1.8 2.5.7 3 .6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.2-1.2l-.6-.3s-1.4-.7-1.6-.8c-.2-.1-.4-.1-.5.1l-.7.9c-.1.2-.3.2-.5.1-.2-.1-1-.4-1.9-1.2-.7-.6-1.2-1.4-1.3-1.6-.1-.2 0-.3.1-.4l.4-.4c.1-.2.2-.3.2-.5.1-.2 0-.3 0-.5l-.7-1.7c-.2-.4-.4-.4-.5-.4h-.4z"/></svg></span>
							<a href="<?php echo esc_url( $skl_whatsapp ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'WhatsApp', 'sklentr' ); ?></a>
						</li>
					<?php endif; ?>
					<?php if ( $skl_address ) : ?>
						<li class="footer-contact__item footer-contact__item--address">
							<span class="footer-contact__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.6"/></svg></span>
							<span><?php echo nl2br( esc_html( $skl_address ) ); ?></span>
						</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>

		<!-- 3 — Middle bar: credit + socials -->
		<div class="footer-mid">
			<p class="footer-mid__credit">
				<?php
				if ( $skl_credit ) {
					echo esc_html( $skl_credit );
				} else {
					printf(
						/* translators: %1$s: year, %2$s: site name. */
						esc_html__( '© %1$s %2$s — Toronto-based MVP studio. All rights reserved.', 'sklentr' ),
						esc_html( wp_date( 'Y' ) ),
						esc_html( get_bloginfo( 'name' ) )
					);
				}
				?>
			</p>

			<?php if ( array_filter( $skl_socials ) ) : ?>
				<div class="footer-social">
					<?php if ( $skl_follow ) : ?>
						<span class="footer-social__label"><?php echo esc_html( $skl_follow ); ?></span>
					<?php endif; ?>
					<?php foreach ( $skl_socials as $skl_key => $skl_url ) : ?>
						<?php if ( $skl_url ) : ?>
							<a class="footer-social__link" href="<?php echo esc_url( $skl_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $skl_social_labels[ $skl_key ] ); ?>">
								<?php echo $skl_social_svg[ $skl_key ]; // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG. ?>
							</a>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

	</div>

	<!-- 4 — Giant decorative wordmark -->
	<div class="footer-mega-wrap">
		<?php echo sklentr_footer_megamark( $skl_megamark ); // phpcs:ignore WordPress.Security.EscapingOutput -- returns pre-escaped markup. ?>
	</div>

</footer>

<?php
/* Mobile-only app bar (floating bottom nav). Hidden on desktop via CSS. */
get_template_part( 'template-parts/app-bar' );
?>

<?php wp_footer(); ?>
</body>
</html>
