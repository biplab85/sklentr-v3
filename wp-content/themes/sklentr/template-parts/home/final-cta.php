<?php
/**
 * Home / Section 15 — Final CTA Band (light gradient).
 * The closing conversion moment. Carries id="contact" so every "#contact" /
 * "Book a Call" link on the site lands here. Fully dynamic (Sklentr Settings).
 * (Blueprint §15.) Micro-animations: reveal, drifting ambient glow, CTA hover.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$skl_eyebrow  = skl_opt( 'cta_eyebrow', '' );
$skl_title    = skl_opt( 'cta_title', __( 'Ready to launch your MVP', 'sklentr' ) );
$skl_accent   = skl_opt( 'cta_title_accent', __( 'in weeks?', 'sklentr' ) );
$skl_subtitle = skl_opt( 'cta_subtitle', '' );
$skl_p_text   = skl_opt( 'cta_primary_text', __( 'Book a Free Consultation', 'sklentr' ) );
$skl_p_link   = skl_opt( 'cta_primary_link', 'https://calendly.com/sklentr' );
$skl_email    = skl_opt( 'cta_email', '' );
$skl_phone    = skl_opt( 'cta_phone', '' );
$skl_whatsapp = skl_opt( 'cta_whatsapp', '' );

$skl_p_ext = ( 0 === strpos( $skl_p_link, 'http' ) );
?>

<section class="final-cta" id="contact" aria-labelledby="final-cta-title">

	<div class="final-cta__atmos" aria-hidden="true">
		<span class="final-cta__glow final-cta__glow--gold"></span>
		<span class="final-cta__glow final-cta__glow--green"></span>
		<span class="final-cta__grid"></span>
	</div>

	<div class="skl-container">
		<div class="final-cta__inner" data-reveal>

			<?php if ( $skl_eyebrow ) : ?>
				<p class="final-cta__eyebrow">
					<span class="final-cta__eyebrow-dot"></span>
					<?php echo esc_html( $skl_eyebrow ); ?>
				</p>
			<?php endif; ?>

			<h2 class="final-cta__title" id="final-cta-title">
				<?php echo esc_html( $skl_title ); ?>
				<?php if ( $skl_accent ) : ?>
					<span class="final-cta__accent"><?php echo esc_html( $skl_accent ); ?></span>
				<?php endif; ?>
			</h2>

			<?php if ( $skl_subtitle ) : ?>
				<p class="final-cta__subtitle"><?php echo esc_html( $skl_subtitle ); ?></p>
			<?php endif; ?>

			<div class="final-cta__actions">
				<a class="skl-btn skl-btn--gold final-cta__primary" href="<?php echo esc_url( $skl_p_link ); ?>"<?php echo $skl_p_ext ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
					<?php echo esc_html( $skl_p_text ); ?>
					<span class="skl-btn__arrow" aria-hidden="true">&rarr;</span>
				</a>
			</div>

			<?php if ( $skl_whatsapp || $skl_email || $skl_phone ) : ?>
				<div class="final-cta__contact">
					<span class="final-cta__contact-label"><?php esc_html_e( 'Or reach us directly', 'sklentr' ); ?></span>
					<ul class="final-cta__contact-list">
						<?php if ( $skl_whatsapp ) : ?>
							<li>
								<a class="final-cta__contact-link" href="<?php echo esc_url( $skl_whatsapp ); ?>" target="_blank" rel="noopener noreferrer">
									<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 2.2A9.8 9.8 0 0 0 3.6 17l-1.3 4.8 4.9-1.3A9.8 9.8 0 1 0 12 2.2zm0 1.8a8 8 0 0 1 6.8 12.2l-.2.3.8 2.9-3-.8-.3.2A8 8 0 1 1 12 4zm-3.1 3.7c-.2 0-.5.1-.7.4-.3.3-.9.9-.9 2.1s.9 2.4 1 2.6c.1.2 1.7 2.7 4.2 3.7 2.1.8 2.5.7 3 .6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.2-1.2l-.6-.3s-1.4-.7-1.6-.8c-.2-.1-.4-.1-.5.1l-.7.9c-.1.2-.3.2-.5.1-.2-.1-1-.4-1.9-1.2-.7-.6-1.2-1.4-1.3-1.6-.1-.2 0-.3.1-.4l.4-.4c.1-.2.2-.3.2-.5.1-.2 0-.3 0-.5l-.7-1.7c-.2-.4-.4-.4-.5-.4h-.4z"/></svg>
									<?php esc_html_e( 'WhatsApp', 'sklentr' ); ?>
								</a>
							</li>
						<?php endif; ?>
						<?php if ( $skl_email ) : ?>
							<li>
								<a class="final-cta__contact-link" href="mailto:<?php echo esc_attr( sanitize_email( $skl_email ) ); ?>">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="M4 7l8 6 8-6"/></svg>
									<?php echo esc_html( $skl_email ); ?>
								</a>
							</li>
						<?php endif; ?>
						<?php if ( $skl_phone ) : ?>
							<li>
								<a class="final-cta__contact-link" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $skl_phone ) ); ?>">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 4h3.5l1.5 4-2 1.5a11 11 0 0 0 5 5l1.5-2 4 1.5V19a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg>
									<?php echo esc_html( $skl_phone ); ?>
								</a>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
