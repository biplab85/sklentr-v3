<?php
/**
 * Sklentr — real WordPress Posts power the blog.
 *
 * (1) Helpers used by page-blog.php + single.php (cover, byline, read time).
 * (2) A small "Article details" meta box (byline + cover slug/URL) so those are
 *     editable in the normal post editor — everything else uses native WP fields
 *     (title, category, excerpt, content, featured image, author, date).
 * (3) A one-time seeder that creates the categories + 6 starter posts so the blog
 *     is populated out of the box. Idempotent (version-flagged); never overwrites.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ------------------------------------------------------------------ *
 * 1 · Helpers
 * ------------------------------------------------------------------ */

/** Category-slug → bundled fallback image, for posts without a featured image. */
function sklentr_post_cover_map() {
	return array(
		'strategy'     => 'data',
		'development'  => 'fintech',
		'startup-visa' => 'healthcare',
		'mindset'      => 'fashion',
		'marketing'    => 'agritech',
		'case-study'   => 'care',
	);
}

/** Resolve a post's cover: featured image → _skl_cover meta → category fallback → default. */
function sklentr_post_cover_url( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail_url( $post_id, 'large' );
	}
	$cover = trim( (string) get_post_meta( $post_id, '_skl_cover', true ) );
	if ( '' === $cover ) {
		$cats = get_the_category( $post_id );
		$slug = $cats ? $cats[0]->slug : '';
		$map  = sklentr_post_cover_map();
		$cover = isset( $map[ $slug ] ) ? $map[ $slug ] : 'data';
	}
	if ( false !== strpos( $cover, '://' ) || 0 === strpos( $cover, '/' ) ) {
		return $cover;
	}
	return get_theme_file_uri( 'assets/images/work/' . preg_replace( '/[^A-Za-z0-9_-]/', '', $cover ) . '.jpg' );
}

/** Byline: _skl_byline meta if set, else the post author's display name. */
function sklentr_post_byline( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$b = trim( (string) get_post_meta( $post_id, '_skl_byline', true ) );
	if ( '' !== $b ) {
		return $b;
	}
	return get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) );
}

/** Estimated read time from the content length (~200 wpm). */
function sklentr_post_readtime( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$words   = str_word_count( wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) ) );
	$min     = max( 1, (int) ceil( $words / 200 ) );
	/* translators: %d: minutes. */
	return sprintf( _n( '%d min read', '%d min read', $min, 'sklentr' ), $min );
}

/** A post's primary category name (first assigned). */
function sklentr_post_primary_cat( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$cats = get_the_category( $post_id );
	return $cats ? $cats[0] : null;
}

/* ------------------------------------------------------------------ *
 * 2 · "Article details" meta box (byline + cover slug/URL)
 * ------------------------------------------------------------------ */
add_action( 'add_meta_boxes', function () {
	add_meta_box(
		'skl_article_details',
		__( 'Article details (Sklentr)', 'sklentr' ),
		'sklentr_article_metabox',
		'post',
		'side',
		'default'
	);
} );

function sklentr_article_metabox( $post ) {
	wp_nonce_field( 'skl_article_meta', 'skl_article_nonce' );
	$byline = get_post_meta( $post->ID, '_skl_byline', true );
	$cover  = get_post_meta( $post->ID, '_skl_cover', true );
	?>
	<div class="skl-meta skl-meta--side">
		<p class="skl-meta__field">
			<label class="skl-meta__label" for="skl_byline"><?php esc_html_e( 'Byline (author label)', 'sklentr' ); ?></label>
			<input type="text" id="skl_byline" name="skl_byline" value="<?php echo esc_attr( $byline ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'e.g. Sklentr Team', 'sklentr' ); ?>" />
			<span class="description"><?php esc_html_e( 'Blank = post author’s name.', 'sklentr' ); ?></span>
		</p>
		<p class="skl-meta__field">
			<label class="skl-meta__label" for="skl_cover"><?php esc_html_e( 'Cover image (slug or URL)', 'sklentr' ); ?></label>
			<input type="text" id="skl_cover" name="skl_cover" value="<?php echo esc_attr( $cover ); ?>" class="widefat" placeholder="data, fintech, healthcare…" />
			<span class="description"><?php esc_html_e( 'Overridden by a Featured image if set. Blank = image by category.', 'sklentr' ); ?></span>
		</p>
	</div>
	<?php
}

add_action( 'save_post_post', function ( $post_id ) {
	if ( ! isset( $_POST['skl_article_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['skl_article_nonce'] ) ), 'skl_article_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	update_post_meta( $post_id, '_skl_byline', sanitize_text_field( wp_unslash( $_POST['skl_byline'] ?? '' ) ) );
	update_post_meta( $post_id, '_skl_cover', sanitize_text_field( wp_unslash( $_POST['skl_cover'] ?? '' ) ) );
} );

/* ------------------------------------------------------------------ *
 * 3 · One-time seeder — categories + 6 starter posts
 * ------------------------------------------------------------------ */
add_action( 'init', function () {
	if ( get_option( 'sklentr_blog_seed_v1' ) ) {
		return;
	}

	$cat_ids = array();
	foreach ( array( 'Strategy', 'Development', 'Startup Visa', 'Mindset', 'Marketing', 'Case Study' ) as $c ) {
		$term = term_exists( $c, 'category' );
		if ( ! $term ) {
			$term = wp_insert_term( $c, 'category' );
		}
		if ( ! is_wp_error( $term ) ) {
			$cat_ids[ $c ] = (int) ( is_array( $term ) ? $term['term_id'] : $term );
		}
	}

	$admins  = get_users( array( 'role' => 'administrator', 'number' => 1, 'fields' => 'ID' ) );
	$author  = $admins ? (int) $admins[0] : 1;

	$posts = array(
		array(
			'title'   => 'How to Validate Your Startup Idea Before Building',
			'cat'     => 'Strategy',
			'date'    => '2026-01-20 09:00:00',
			'cover'   => 'data',
			'byline'  => 'Rishad Wahid',
			'excerpt' => 'Before investing time and money into development, here’s how to test if your idea has real market potential.',
			'content' => "<p>Before you invest weeks of your life and thousands of dollars into building, spend a few days proving people actually want what you’re about to make. Here’s the exact process we run with founders.</p>\n<h2>Start with the problem, not the product</h2>\n<p>A real problem is one people already spend time, money, or effort trying to solve. If your idea only works once you’ve explained why someone should care, that’s a warning sign. Talk to ten potential users and ask what they do today — not whether they’d use your app.</p>\n<h2>Run cheap experiments first</h2>\n<p>You don’t need a finished MVP to validate demand. A landing page, a short survey, or a manual “concierge” version of your service can tell you most of what you need to know in a week.</p>\n<ul><li>A landing page measures whether the promise resonates.</li><li>A waitlist measures intent, not just curiosity.</li><li>A concierge test measures whether people will actually pay.</li></ul>\n<h2>Watch what people do, not what they say</h2>\n<p>People are polite. They’ll tell you an idea is great and never come back. Behaviour is the only honest signal — sign-ups, pre-orders, repeat usage, or money on the table.</p>\n<blockquote>If it’s hard to get someone to spend five minutes on your idea today, it’ll be impossible to get them to spend five dollars tomorrow.</blockquote>\n<h2>Then build the smallest thing that proves the model</h2>\n<p>Once demand is clear, scope ruthlessly. Your first version exists to prove one core assumption — nothing more. Everything else can wait until real users ask for it.</p>",
		),
		array(
			'title'   => 'The 2-Week MVP: What’s Actually Possible',
			'cat'     => 'Development',
			'date'    => '2026-01-15 09:00:00',
			'cover'   => 'fintech',
			'byline'  => 'Sklentr Team',
			'excerpt' => 'Breaking down what you can realistically build in two weeks and how to prioritize features that matter.',
			'content' => "<p>Two weeks isn’t much time — which is exactly why it forces the right decisions. Here’s how we scope a build that ships fast without falling apart.</p>\n<h2>Cut to one core loop</h2>\n<p>Every product has one loop that delivers its value. Find it, build it end to end, and defer everything that isn’t on that path. Settings, edge cases, and “nice to haves” can wait.</p>\n<h2>Buy, don’t build, the boring parts</h2>\n<p>Auth, payments, email, hosting — use proven services. The two weeks belong to your unique value, not to reinventing infrastructure.</p>\n<h2>Ship to real users on day fourteen</h2>\n<p>A private launch to ten real users beats another week of polish. Their reactions decide what you build next — not your roadmap.</p>",
		),
		array(
			'title'   => 'Startup Visa Canada: Technical Requirements Explained',
			'cat'     => 'Startup Visa',
			'date'    => '2026-01-10 09:00:00',
			'cover'   => 'healthcare',
			'byline'  => 'Sklentr Team',
			'excerpt' => 'A comprehensive guide to the technical documentation and MVP requirements for your visa application.',
			'content' => "<p>The Canada Startup Visa rewards real, working products — not slide decks. Here’s what the technical side of a strong application actually looks like.</p>\n<h2>A working MVP, not a mockup</h2>\n<p>Designated organizations want to see something people can use. A functional MVP that demonstrates your core value is far more convincing than polished screens with no product behind them.</p>\n<h2>Documentation that proves ownership</h2>\n<p>Keep a clear record of your architecture, repositories, and the work your team has done. It shows the venture is genuinely yours and technically sound.</p>\n<h2>Scalability the reviewers can believe</h2>\n<p>You don’t need to be at scale — you need an architecture that clearly could scale. Sensible infrastructure choices signal that this is a real, fundable business.</p>",
		),
		array(
			'title'   => 'Why Your MVP Doesn’t Need to Be Perfect',
			'cat'     => 'Mindset',
			'date'    => '2026-01-05 09:00:00',
			'cover'   => 'fashion',
			'byline'  => 'Rishad Wahid',
			'excerpt' => 'Perfectionism kills startups. Here’s why shipping fast matters more than shipping perfect.',
			'content' => "<p>The founders who win aren’t the ones with the most polished first version. They’re the ones who learn fastest — and learning requires shipping.</p>\n<h2>Perfect is a guess</h2>\n<p>Every hour spent perfecting a feature nobody has used yet is an hour spent guessing. Real usage tells you what to improve; polish before launch is just expensive opinion.</p>\n<h2>Speed compounds</h2>\n<p>Ship, learn, adjust, repeat. A team on its third iteration understands its users better than a team still perfecting its first. That gap only widens over time.</p>\n<h2>Quality still matters — where it counts</h2>\n<p>“Not perfect” doesn’t mean sloppy. Make the core experience solid and trustworthy; leave the edges rough until users tell you which ones matter.</p>",
		),
		array(
			'title'   => 'SEO for Startups: A No-BS Guide',
			'cat'     => 'Marketing',
			'date'    => '2025-12-28 09:00:00',
			'cover'   => 'agritech',
			'byline'  => 'Sklentr Team',
			'excerpt' => 'Forget the fluff. Here’s what actually moves the needle for startup SEO in 2026.',
			'content' => "<p>Most startup SEO advice is noise. A few things actually work — and they’re less complicated than the agencies want you to believe.</p>\n<h2>Answer real questions</h2>\n<p>Write the pages your future customers are already searching for. One genuinely useful article that answers a real question beats fifty thin posts chasing keywords.</p>\n<h2>Earn a little authority</h2>\n<p>A handful of relevant, credible links does more than a hundred low-quality ones. Be worth linking to, then ask the people who’d naturally cite you.</p>\n<h2>Fix the technical basics</h2>\n<p>Fast pages, clean URLs, proper titles, and a site Google can crawl. It’s unglamorous, but it’s the foundation everything else sits on.</p>",
		),
		array(
			'title'   => 'How We Built an MVP in 10 Days',
			'cat'     => 'Case Study',
			'date'    => '2025-12-20 09:00:00',
			'cover'   => 'care',
			'byline'  => 'Rishad Wahid',
			'excerpt' => 'A behind-the-scenes look at our fastest project ever. What made it possible and what we learned.',
			'content' => "<p>Ten days from kickoff to a live product used by real customers. Here’s how it happened — and what we’d do the same next time.</p>\n<h2>A ruthless scope on day one</h2>\n<p>We agreed on a single outcome the product had to deliver and wrote down everything we would <em>not</em> build. That one document saved us days of drift.</p>\n<h2>One team, no handoffs</h2>\n<p>Design, development, and decisions sat together. No waiting on approvals across vendors meant we moved at the speed of one conversation.</p>\n<h2>Real feedback before polish</h2>\n<p>We put the rough version in front of users on day seven. The last three days went into the things they actually cared about — not the things we assumed they would.</p>",
		),
	);

	foreach ( $posts as $p ) {
		if ( get_page_by_title( $p['title'], OBJECT, 'post' ) ) {
			continue; // already exists — don't duplicate
		}
		$post_id = wp_insert_post( array(
			'post_type'     => 'post',
			'post_status'   => 'publish',
			'post_title'    => $p['title'],
			'post_content'  => $p['content'],
			'post_excerpt'  => $p['excerpt'],
			'post_date'     => $p['date'],
			'post_author'   => $author,
			'post_category' => isset( $cat_ids[ $p['cat'] ] ) ? array( $cat_ids[ $p['cat'] ] ) : array(),
		) );
		if ( $post_id && ! is_wp_error( $post_id ) ) {
			update_post_meta( $post_id, '_skl_cover', $p['cover'] );
			update_post_meta( $post_id, '_skl_byline', $p['byline'] );
		}
	}

	update_option( 'sklentr_blog_seed_v1', 1 );
}, 30 );
