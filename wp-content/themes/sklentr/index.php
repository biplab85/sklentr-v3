<?php
/**
 * Fallback template (required). Used for the blog index and any request
 * without a more specific template.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="skl-container" style="padding-block: 120px;">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			?>
			<article <?php post_class( 'skl-post' ); ?>>
				<h2>
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h2>
				<div class="skl-post__excerpt"><?php the_excerpt(); ?></div>
			</article>
			<?php
		}
		the_posts_pagination();
	} else {
		echo '<p>' . esc_html__( 'Nothing found.', 'sklentr' ) . '</p>';
	}
	?>
</div>

<?php
get_footer();
