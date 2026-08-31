<?php
/**
 * 404 template.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="skl-container" style="padding-block: 140px; text-align: center;">
	<p class="skl-eyebrow">404</p>
	<h1><?php esc_html_e( 'Page not found', 'sklentr' ); ?></h1>
	<p style="max-width: 42ch; margin-inline: auto;">
		<?php esc_html_e( 'The page you are looking for has moved or no longer exists.', 'sklentr' ); ?>
	</p>
	<p style="margin-top: 32px;">
		<a class="skl-btn skl-btn--dark" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php esc_html_e( 'Back home', 'sklentr' ); ?>
		</a>
	</p>
</div>

<?php
get_footer();
