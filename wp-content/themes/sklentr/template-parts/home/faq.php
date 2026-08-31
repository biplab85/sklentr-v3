<?php
/**
 * Home / Section 14 — FAQ.
 * Split layout: sticky heading + "still have questions" panel on the left, a
 * single-open accordion on the right. Fully dynamic (FAQ CPT + Sklentr
 * Settings). Emits FAQPage JSON-LD for SEO. Accessible: real <button>s with
 * aria-expanded/aria-controls; no-JS shows all answers open. (Blueprint §14.)
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$skl_eyebrow  = skl_opt( 'faq_eyebrow', __( 'FAQ', 'sklentr' ) );
$skl_title    = skl_opt( 'faq_title', __( 'Questions?', 'sklentr' ) );
$skl_accent   = skl_opt( 'faq_title_accent', __( 'Answered.', 'sklentr' ) );
$skl_intro    = skl_opt( 'faq_intro', '' );
$skl_h_title  = skl_opt( 'faq_help_title', __( 'Still have questions?', 'sklentr' ) );
$skl_h_text   = skl_opt( 'faq_help_text', '' );
$skl_h_cta    = skl_opt( 'faq_help_cta_text', __( 'Book a Free Call', 'sklentr' ) );
$skl_h_link   = skl_opt( 'faq_help_cta_link', '#contact' );

$skl_q = new WP_Query( array(
	'post_type'      => 'skl_faq',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );

$skl_faqs = array();
if ( $skl_q->have_posts() ) {
	while ( $skl_q->have_posts() ) {
		$skl_q->the_post();
		$skl_faqs[] = array(
			'q' => get_the_title(),
			'a' => (string) get_post_meta( get_the_ID(), '_skl_answer', true ),
		);
	}
	wp_reset_postdata();
}

if ( empty( $skl_faqs ) ) {
	return;
}

// FAQPage structured data (SEO).
$skl_schema = array(
	'@context'   => 'https://schema.org',
	'@type'      => 'FAQPage',
	'mainEntity' => array(),
);
foreach ( $skl_faqs as $skl_f ) {
	$skl_schema['mainEntity'][] = array(
		'@type'          => 'Question',
		'name'           => $skl_f['q'],
		'acceptedAnswer' => array(
			'@type' => 'Answer',
			'text'  => $skl_f['a'],
		),
	);
}
?>

<section class="faq" id="faq" aria-labelledby="faq-title">

	<div class="faq__atmos" aria-hidden="true">
		<span class="faq__glow faq__glow--green"></span>
		<span class="faq__glow faq__glow--gold"></span>
	</div>

	<!-- Ambient IT/dev icons drifting in the margins -->
	<div class="faq__deco" aria-hidden="true">
		<?php
		$skl_faq_deco = array(
			'a' => '<circle cx="12" cy="12" r="3.2"/><path d="M12 2.5v3M12 18.5v3M2.5 12h3M18.5 12h3M5.2 5.2l2.1 2.1M16.7 16.7l2.1 2.1M18.8 5.2l-2.1 2.1M7.3 16.7l-2.1 2.1"/>', // gear
			'b' => '<path d="M8 8l-4 4 4 4"/><path d="M16 8l4 4-4 4"/><path d="M13.5 6.5l-3 11"/>',                                                    // code
			'c' => '<ellipse cx="12" cy="5.5" rx="7.5" ry="2.8"/><path d="M4.5 5.5v6c0 1.55 3.36 2.8 7.5 2.8s7.5-1.25 7.5-2.8v-6"/><path d="M4.5 11.5v6c0 1.55 3.36 2.8 7.5 2.8s7.5-1.25 7.5-2.8v-6"/>', // database
			'd' => '<path d="M8 4c-2 0-2 2-2 4s0 3-2 3c2 0 2 1 2 3s0 4 2 4"/><path d="M16 4c2 0 2 2 2 4s0 3 2 3c-2 0-2 1-2 3s0 4-2 4"/>',            // braces
		);
		foreach ( $skl_faq_deco as $skl_pos => $skl_paths ) {
			echo '<span class="faq__deco-icon faq__deco-icon--' . esc_attr( $skl_pos ) . '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">' . $skl_paths . '</svg></span>'; // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG paths.
		}
		?>
	</div>

	<div class="skl-container">
		<div class="faq__inner">

			<!-- Left: heading + help panel (sticky) -->
			<div class="faq__aside">
				<?php if ( $skl_eyebrow ) : ?>
					<p class="faq__eyebrow skl-eyebrow"><?php echo esc_html( $skl_eyebrow ); ?></p>
				<?php endif; ?>
				<h2 class="faq__title" id="faq-title" data-char-fill="dark"><?php echo esc_html( trim( $skl_title . ' ' . $skl_accent ) ); ?></h2>
				<?php if ( $skl_intro ) : ?>
					<p class="faq__intro"><?php echo esc_html( $skl_intro ); ?></p>
				<?php endif; ?>

				<?php if ( $skl_h_title || ( $skl_h_cta && $skl_h_link ) ) : ?>
					<div class="faq__help">
						<span class="faq__help-icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M9.2 9.3a2.8 2.8 0 0 1 5.4 1c0 1.9-2.8 2.5-2.8 2.5"/><path d="M12 17h.01"/></svg>
						</span>
						<?php if ( $skl_h_title ) : ?>
							<h3 class="faq__help-title"><?php echo esc_html( $skl_h_title ); ?></h3>
						<?php endif; ?>
						<?php if ( $skl_h_text ) : ?>
							<p class="faq__help-text"><?php echo esc_html( $skl_h_text ); ?></p>
						<?php endif; ?>
						<?php if ( $skl_h_cta && $skl_h_link ) : ?>
							<a class="skl-btn skl-btn--gold" target="_blank" href="<?php echo esc_url( $skl_h_link ); ?>">
								<?php echo esc_html( $skl_h_cta ); ?>
								<span class="skl-btn__arrow" aria-hidden="true">&rarr;</span>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

			<!-- Right: accordion -->
			<div class="faq__list" data-faq-list>
				<?php foreach ( $skl_faqs as $skl_idx => $skl_f ) : ?>
					<?php $skl_pid = 'faq-panel-' . $skl_idx; ?>
					<div class="faq-item" data-reveal>
						<h3 class="faq-item__heading">
							<button class="faq-item__q" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $skl_pid ); ?>">
								<span class="faq-item__q-text"><?php echo esc_html( $skl_f['q'] ); ?></span>
								<span class="faq-item__icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
								</span>
							</button>
						</h3>
						<div class="faq-item__panel" id="<?php echo esc_attr( $skl_pid ); ?>" role="region">
							<div class="faq-item__answer">
								<p><?php echo esc_html( $skl_f['a'] ); ?></p>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

		</div>
	</div>

	<script type="application/ld+json"><?php echo wp_json_encode( $skl_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); ?></script>
</section>
