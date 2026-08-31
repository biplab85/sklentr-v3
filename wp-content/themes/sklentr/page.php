<?php
/**
 * Single page template.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="skl-container" style="padding-block: 120px; max-width: 820px;">
	<?php
	while ( have_posts() ) {
		the_post();
		?>
		<article <?php post_class( 'skl-page' ); ?>>
			<h1><?php the_title(); ?></h1>
			<div class="skl-page__content">
				<?php
				the_content();
				wp_link_pages();
				?>
			</div>
		</article>
		<?php
	}
	?>
</div>

<?php
get_footer();
