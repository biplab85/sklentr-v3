<?php
/**
 * Home / Section 10 — Technology & AI Capability.
 * Fully dynamic: heading/AI-band/CTA from Sklentr Settings, stack from the
 * "Tech Stack" CPT (grouped by category). A categorized grid of monochrome
 * "logo" marks that colour on hover, plus a distinct AI-native band. Blueprint
 * §10. Micro-animations mirror the rest of the site (data-reveal fades,
 * char-fill heading, hover-colour tiles).
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$skl_eyebrow  = skl_opt( 'tech_eyebrow', __( 'Technology & AI', 'sklentr' ) );
$skl_title    = skl_opt( 'tech_title', __( 'Modern stack.', 'sklentr' ) );
$skl_accent   = skl_opt( 'tech_title_accent', __( 'AI-native builds.', 'sklentr' ) );
$skl_intro    = skl_opt( 'tech_intro', '' );
$skl_ai_title = skl_opt( 'tech_ai_title', __( 'AI-native, not bolted on.', 'sklentr' ) );
$skl_ai_note  = skl_opt( 'tech_ai_note', '' );
$skl_cta_text = skl_opt( 'tech_cta_text', __( 'Explore Our Services', 'sklentr' ) );
$skl_cta_link = skl_resolve_link( skl_opt( 'tech_cta_link', '/services' ) );

// Bucket the stack by category (preserving menu_order within each).
$skl_q       = new WP_Query( array(
	'post_type'      => 'skl_tech',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
$skl_buckets = array();
if ( $skl_q->have_posts() ) {
	while ( $skl_q->have_posts() ) {
		$skl_q->the_post();
		$skl_cat                   = get_post_meta( get_the_ID(), '_skl_category', true );
		$skl_cat                   = $skl_cat ? $skl_cat : 'frontend';
		$skl_buckets[ $skl_cat ][] = array(
			'name' => get_the_title(),
			'key'  => get_post_meta( get_the_ID(), '_skl_key', true ),
		);
	}
	wp_reset_postdata();
}

$skl_cat_labels = array(
	'frontend' => __( 'Frontend', 'sklentr' ),
	'backend'  => __( 'Backend', 'sklentr' ),
	'mobile'   => __( 'Mobile', 'sklentr' ),
	'database' => __( 'Database', 'sklentr' ),
	'cms'      => __( 'CMS', 'sklentr' ),
);

/**
 * Render one tech tile (mark + name).
 *
 * @param array $t {name, key}.
 */
$skl_tile = function ( $t ) {
	printf(
		'<li class="tech-tile"><span class="tech-tile__mark">%1$s</span><span class="tech-tile__name">%2$s</span></li>',
		skl_tech_icon_svg( $t['key'] ), // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG.
		esc_html( $t['name'] )
	);
};
?>

<section class="tech" id="technology" aria-labelledby="tech-title">

	<div class="tech__atmos" aria-hidden="true">
		<span class="tech__grid-lines"></span>
	</div>

	<div class="skl-container">

		<div class="tech__head" data-reveal>
			<?php if ( $skl_eyebrow ) : ?>
				<p class="tech__eyebrow skl-eyebrow"><?php echo esc_html( $skl_eyebrow ); ?></p>
			<?php endif; ?>
			<h2 class="tech__title" id="tech-title" data-char-fill>
				<?php echo esc_html( trim( $skl_title . ' ' . $skl_accent ) ); ?>
			</h2>
			<?php if ( $skl_intro ) : ?>
				<p class="tech__intro"><?php echo esc_html( $skl_intro ); ?></p>
			<?php endif; ?>
		</div>

		<?php
		// Flatten the stack (non-AI) into one list, preserving category order.
		$skl_stack = array();
		foreach ( array_keys( $skl_cat_labels ) as $skl_c ) {
			if ( ! empty( $skl_buckets[ $skl_c ] ) ) {
				foreach ( $skl_buckets[ $skl_c ] as $skl_t ) {
					$skl_stack[] = $skl_t;
				}
			}
		}
		?>

		<?php if ( ! empty( $skl_stack ) ) : ?>
			<div class="tech__marquee" data-reveal>
				<p class="screen-reader-text"><?php echo esc_html( implode( ', ', wp_list_pluck( $skl_stack, 'name' ) ) ); ?></p>

				<?php foreach ( array( 'left', 'right' ) as $skl_dir ) : ?>
					<div class="tech-row tech-row--<?php echo esc_attr( $skl_dir ); ?>" aria-hidden="true">
						<div class="tech-row__track">
							<?php for ( $skl_copy = 0; $skl_copy < 2; $skl_copy++ ) : ?>
								<div class="tech-row__group">
									<?php foreach ( $skl_stack as $skl_t ) : ?>
										<span class="tech-logo">
											<span class="tech-logo__mark"><?php echo skl_tech_icon_svg( $skl_t['key'] ); // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG. ?></span>
											<span class="tech-logo__tip"><?php echo esc_html( $skl_t['name'] ); ?></span>
										</span>
									<?php endforeach; ?>
								</div>
							<?php endfor; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $skl_buckets['ai'] ) ) : ?>
			<div class="tech__ai" data-reveal>
				<div class="tech__ai-copy">
					<span class="tech__ai-spark" aria-hidden="true"><?php echo skl_tech_icon_svg( 'gemini' ); // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG. ?></span>
					<?php if ( $skl_ai_title ) : ?>
						<h3 class="tech__ai-title"><?php echo esc_html( $skl_ai_title ); ?></h3>
					<?php endif; ?>
					<?php if ( $skl_ai_note ) : ?>
						<p class="tech__ai-note"><?php echo esc_html( $skl_ai_note ); ?></p>
					<?php endif; ?>
				</div>
				<ul class="tech__ai-chips">
					<?php foreach ( $skl_buckets['ai'] as $skl_t ) : ?>
						<li class="tech-chip">
							<span class="tech-chip__mark"><?php echo skl_tech_icon_svg( $skl_t['key'] ); // phpcs:ignore WordPress.Security.EscapingOutput -- static trusted SVG. ?></span>
							<span class="tech-chip__name"><?php echo esc_html( $skl_t['name'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<?php if ( $skl_cta_text && $skl_cta_link ) : ?>
			<div class="tech__cta" data-reveal>
				<a class="skl-btn skl-btn--dark" href="<?php echo esc_url( $skl_cta_link ); ?>">
					<?php echo esc_html( $skl_cta_text ); ?>
					<span class="skl-btn__arrow" aria-hidden="true">&rarr;</span>
				</a>
			</div>
		<?php endif; ?>

	</div>
</section>
