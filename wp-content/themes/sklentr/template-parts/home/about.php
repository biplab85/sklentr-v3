<?php
/**
 * Home / Section 12 — About / Team Snapshot.
 * Creative "collage" layout (inspired by the Quiety "Since 2013" section) in
 * Sklentr's on-brand colours: heading + founder quote card with a cut-out
 * portrait (grayscale → colour on hover), a numbered highlight card, and a
 * story paragraph with the "Meet the Team" CTA + social links. A hand-drawn
 * squiggle ties it together. Fully dynamic (Sklentr Settings). (Blueprint §12.)
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$skl_eyebrow  = skl_opt( 'about_eyebrow', __( 'About Sklentr', 'sklentr' ) );
$skl_title    = skl_opt( 'about_title', __( 'Since 2023, we’ve built launch-ready MVPs', 'sklentr' ) );
$skl_accent   = skl_opt( 'about_title_accent', __( 'founders actually own.', 'sklentr' ) );
$skl_story    = skl_opt( 'about_story', '' );
$skl_f_name   = skl_opt( 'founder_name', __( 'Rishad Wahid', 'sklentr' ) );
$skl_f_role   = skl_opt( 'founder_role', __( 'Founder & CEO, Sklentr', 'sklentr' ) );
$skl_quote    = skl_opt( 'founder_quote', '' );
$skl_photo    = skl_opt( 'founder_photo', '' );
$skl_photo    = $skl_photo ? $skl_photo : get_theme_file_uri( 'assets/images/rishad-wahid.jpg' );
$skl_hl_num   = skl_opt( 'about_hl_num', '01' );
$skl_hl_title = skl_opt( 'about_hl_title', '' );
$skl_hl_desc  = skl_opt( 'about_hl_desc', '' );
$skl_cta_text = skl_opt( 'about_cta_text', __( 'Meet the Full Team', 'sklentr' ) );
$skl_cta_link = skl_resolve_link( skl_opt( 'about_cta_link', '/about' ) );
$skl_follow   = skl_opt( 'about_follow_label', __( 'Follow Us', 'sklentr' ) );

$skl_socials = array(
	'linkedin'  => skl_opt( 'social_linkedin', '' ),
	'x'         => skl_opt( 'social_x', '' ),
	'facebook'  => skl_opt( 'social_facebook', '' ),
	'instagram' => skl_opt( 'social_instagram', '' ),
);
$skl_social_svg = array(
	'linkedin'  => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M4.5 3.6a1.6 1.6 0 1 0 0 3.2 1.6 1.6 0 0 0 0-3.2zM3.2 8.5h2.6V20H3.2V8.5zM8.6 8.5h2.5v1.6h.04c.35-.66 1.2-1.36 2.48-1.36 2.65 0 3.14 1.74 3.14 4V20h-2.6v-5.5c0-1.31-.02-3-1.83-3-1.83 0-2.11 1.43-2.11 2.9V20H8.6V8.5z"/></svg>',
	'x'         => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M17.5 3h3l-6.55 7.48L21.5 21h-5.9l-4.62-6.04L5.7 21H2.7l7-8.02L2.3 3h6.05l4.18 5.52L17.5 3zm-1.05 16.1h1.66L7.63 4.8H5.85l10.6 14.3z"/></svg>',
	'facebook'  => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M14 8.5V6.9c0-.85.18-1.32 1.45-1.32H17V2.7C16.6 2.6 15.6 2.55 14.5 2.55c-2.36 0-3.98 1.44-3.98 4.08V8.5H8v3.05h2.52V21H14v-9.45h2.6l.4-3.05H14z"/></svg>',
	'instagram' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true" focusable="false"><rect x="3.5" y="3.5" width="17" height="17" rx="4.6"/><circle cx="12" cy="12" r="3.9"/><circle cx="16.9" cy="7.1" r="1.1" fill="currentColor" stroke="none"/></svg>',
);
$skl_social_labels = array(
	'linkedin'  => __( 'LinkedIn', 'sklentr' ),
	'x'         => __( 'X', 'sklentr' ),
	'facebook'  => __( 'Facebook', 'sklentr' ),
	'instagram' => __( 'Instagram', 'sklentr' ),
);
?>

<section class="about" id="about" aria-labelledby="about-title">

	<div class="about__atmos" aria-hidden="true">
		<span class="about__glow about__glow--gold"></span>
		<span class="about__glow about__glow--green"></span>
	</div>

	<div class="skl-container">
		<div class="about__inner">

			<!-- Heading (top-left) -->
			<div class="about__head" data-reveal>
				<?php if ( $skl_eyebrow ) : ?>
					<p class="about__eyebrow skl-eyebrow"><?php echo esc_html( $skl_eyebrow ); ?></p>
				<?php endif; ?>
				<h2 class="about__title" id="about-title" data-char-fill>
					<?php echo esc_html( trim( $skl_title . ' ' . $skl_accent ) ); ?>
				</h2>
			</div>

			<!-- Quote card + cut-out portrait (bottom-left) -->
			<div class="about__quote-wrap" data-reveal>
				<figure class="about__quote">
					<span class="about__quote-mark" aria-hidden="true">&ldquo;</span>
					<?php if ( $skl_quote ) : ?>
						<blockquote class="about__quote-text"><?php echo esc_html( $skl_quote ); ?></blockquote>
					<?php endif; ?>
					<figcaption class="about__quote-by">
						<span class="about__quote-name"><?php echo esc_html( $skl_f_name ); ?></span>
						<?php if ( $skl_f_role ) : ?>
							<span class="about__quote-role"><?php echo esc_html( $skl_f_role ); ?></span>
						<?php endif; ?>
					</figcaption>
				</figure>

				<div class="about__photo">
					<img class="about__photo-img" src="<?php echo esc_url( $skl_photo ); ?>" alt="<?php echo esc_attr( $skl_f_name ); ?>" width="440" height="440" loading="lazy" decoding="async">
				</div>
			</div>

			<!-- Numbered highlight card (top-right) -->
			<?php if ( $skl_hl_title || $skl_hl_desc ) : ?>
				<div class="about__highlight" data-reveal>
					<span class="about__hl-num" aria-hidden="true"><?php echo esc_html( $skl_hl_num ); ?></span>
					<div class="about__hl-body">
						<?php if ( $skl_hl_title ) : ?>
							<h3 class="about__hl-title"><?php echo esc_html( $skl_hl_title ); ?></h3>
						<?php endif; ?>
						<?php if ( $skl_hl_desc ) : ?>
							<p class="about__hl-desc"><?php echo esc_html( $skl_hl_desc ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<!-- Story + actions (bottom-right) -->
			<div class="about__foot" data-reveal>
				<?php if ( $skl_story ) : ?>
					<p class="about__story"><?php echo esc_html( $skl_story ); ?></p>
				<?php endif; ?>

				<div class="about__actions">
					<?php if ( $skl_cta_text && $skl_cta_link ) : ?>
						<a class="skl-btn skl-btn--dark" href="<?php echo esc_url( $skl_cta_link ); ?>">
							<?php echo esc_html( $skl_cta_text ); ?>
							<span class="skl-btn__arrow" aria-hidden="true">&rarr;</span>
						</a>
					<?php endif; ?>

					<?php
					$skl_has_social = array_filter( $skl_socials );
					if ( ! empty( $skl_has_social ) ) :
						?>
						<div class="about__social">
							<span class="about__social-label"><?php echo esc_html( $skl_follow ); ?></span>
							<ul class="about__social-list">
								<?php foreach ( $skl_socials as $skl_key => $skl_url ) : ?>
									<?php if ( $skl_url ) : ?>
										<li>
											<a class="about__social-link" href="<?php echo esc_url( $skl_url ); ?>" aria-label="<?php echo esc_attr( $skl_social_labels[ $skl_key ] ); ?>"<?php echo '#' === $skl_url ? '' : ' target="_blank" rel="noopener noreferrer"'; ?>>
												<?php echo $skl_social_svg[ $skl_key ]; // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG. ?>
											</a>
										</li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
				</div>
			</div>

		</div>
	</div>

	<img class="about__squiggle" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/squiggle.png' ) ); ?>" alt="" aria-hidden="true" width="190" height="120" loading="lazy" decoding="async">
</section>
