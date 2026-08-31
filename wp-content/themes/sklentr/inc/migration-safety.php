<?php
/**
 * Sklentr — migration safety.
 *
 * Everything in this file exists to make one workflow reliable:
 *
 *     fresh WordPress  ->  import the WXR  ->  activate this theme
 *
 * A WXR export contains posts, terms and postmeta. It does NOT contain the
 * `wp_options` table. Several things this theme depends on live in options,
 * so on a fresh install they come back missing or wrong:
 *
 *   1. The seeders in post-types.php / services-page.php / portfolio-content.php
 *      / blog-posts.php are guarded by option flags (sklentr_seeded_v18, etc.).
 *      With the flags gone they re-seed content the import already delivered,
 *      producing an exact duplicate of every CPT item. -> sklentr_seed_guard()
 *
 *   2. show_on_front / page_on_front are options, so the homepage reverts to the
 *      blog index and front-page.php never runs. -> sklentr_restore_front_page()
 *
 *   3. Rewrite rules are an option. Without a flush the theme's skl_* CPT
 *      permalinks 404. -> sklentr_flush_rewrites()
 *
 *   4. If a page slug collides with WordPress' own boilerplate the import is
 *      forced to use "about-2" etc. That silently breaks the UI, because this
 *      theme selects templates by slug (page-about.php) and enqueues per-page
 *      JS with is_page('about'). -> sklentr_normalize_slugs()
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Post types this theme seeds. Used to scope the duplicate guard so it can
 * never interfere with ordinary editing.
 *
 * @return string[]
 */
function sklentr_seeded_post_types() {
	return array(
		'post',
		// services-page.php seeds the standalone Services / Portfolio / Pricing /
		// About / Blog / Startup Visa pages, so pages duplicate exactly like CPTs do.
		'page',
		'skl_faq',
		'skl_hero_step',
		'skl_hero_tile',
		'skl_location',
		'skl_pillar',
		'skl_portfolio',
		'skl_pricing',
		'skl_problem',
		'skl_process',
		'skl_project',
		'skl_service',
		'skl_stat',
		'skl_svc_perk',
		'skl_tech',
		'skl_visa_feature',
		'skl_work',
	);
}

/* -------------------------------------------------------------------------
 * 1. Duplicate guard
 * ---------------------------------------------------------------------- */

/**
 * True only while the theme's seeders are running.
 *
 * @var bool
 */
$GLOBALS['sklentr_seeding'] = false;

/**
 * Open the guard window just before the seeders (all of which hook `init`)
 * and close it once they are done. Scoping it this tightly means an admin can
 * still deliberately create two items with the same title from wp-admin.
 */
add_action( 'init', function () {
	$GLOBALS['sklentr_seeding'] = true;
}, 0 );

add_action( 'init', function () {
	$GLOBALS['sklentr_seeding'] = false;
}, PHP_INT_MAX );

/**
 * Refuse a seeder insert when an item of the same type and title already exists.
 *
 * This is the durable fix for the duplication. The option flags the seeders use
 * cannot survive a WXR migration, but the content itself can — so we test the
 * content instead of the flag, and it no longer matters whether the theme is
 * activated before or after the import.
 *
 * @param bool  $maybe_empty Whether the post should be considered empty.
 * @param array $postarr     Post data.
 * @return bool
 */
function sklentr_block_duplicate_seed( $maybe_empty, $postarr ) {
	if ( $maybe_empty || empty( $GLOBALS['sklentr_seeding'] ) ) {
		return $maybe_empty;
	}

	$type  = isset( $postarr['post_type'] ) ? $postarr['post_type'] : 'post';
	$title = isset( $postarr['post_title'] ) ? trim( $postarr['post_title'] ) : '';

	if ( '' === $title || ! in_array( $type, sklentr_seeded_post_types(), true ) ) {
		return $maybe_empty;
	}

	// Updates carry an ID; only brand-new inserts can duplicate.
	if ( ! empty( $postarr['ID'] ) ) {
		return $maybe_empty;
	}

	global $wpdb;
	$exists = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT ID FROM {$wpdb->posts}
			 WHERE post_type = %s AND post_title = %s
			   AND post_status IN ('publish','draft','pending','private')
			 LIMIT 1",
			$type,
			$title
		)
	);

	// Returning true makes wp_insert_post() bail out and return 0.
	return $exists ? true : $maybe_empty;
}
add_filter( 'wp_insert_post_empty_content', 'sklentr_block_duplicate_seed', 10, 2 );

/**
 * Same problem one level down: a seeder re-running over already-imported posts
 * appends a second copy of each meta row instead of replacing it.
 *
 * @param mixed  $check      Short-circuit value.
 * @param int    $object_id  Post ID.
 * @param string $meta_key   Meta key.
 * @param mixed  $meta_value Meta value.
 * @param bool   $unique     Whether the key should be unique.
 * @return mixed
 */
function sklentr_block_duplicate_seed_meta( $check, $object_id, $meta_key, $meta_value, $unique ) {
	if ( null !== $check || $unique || empty( $GLOBALS['sklentr_seeding'] ) ) {
		return $check;
	}
	if ( '_skl_' !== substr( $meta_key, 0, 5 ) && '_wp_page_template' !== $meta_key ) {
		return $check;
	}
	if ( metadata_exists( 'post', $object_id, $meta_key ) ) {
		update_post_meta( $object_id, $meta_key, $meta_value );
		return true; // Short-circuit add_post_meta().
	}
	return $check;
}
add_filter( 'add_post_metadata', 'sklentr_block_duplicate_seed_meta', 10, 5 );

/* -------------------------------------------------------------------------
 * 1b. Import reconciliation
 *
 * The guard above stops a *seeder* duplicating imported content. It cannot stop
 * the reverse: activate the theme first (which you must, or the importer rejects
 * every skl_* item as "Invalid post type") and the seeders legitimately fill an
 * empty site — then the WXR import adds its own copy of everything alongside.
 *
 * WordPress' importer will not spot those as duplicates: it matches on title +
 * date + content, and the seeded copies carry today's date rather than the
 * original. So we tag everything the seeders create and, once the import has
 * finished, drop any tagged item that the import has since superseded.
 * ---------------------------------------------------------------------- */

/**
 * Marker meta key stamped on every post the theme's seeders create.
 */
const SKLENTR_SEED_MARK = '_sklentr_seeded';

/**
 * Tag seeded posts so they can be told apart from imported ones later.
 *
 * @param int $post_id Inserted post ID.
 */
function sklentr_mark_seeded_post( $post_id ) {
	if ( empty( $GLOBALS['sklentr_seeding'] ) ) {
		return;
	}
	if ( ! in_array( get_post_type( $post_id ), sklentr_seeded_post_types(), true ) ) {
		return;
	}
	update_post_meta( $post_id, SKLENTR_SEED_MARK, 1 );
}
add_action( 'wp_insert_post', 'sklentr_mark_seeded_post', 10, 1 );

/**
 * Drop seeded placeholder content that an import has replaced with the real thing.
 *
 * Imported content always wins: it carries the author's actual edits, its
 * original dates, and its own meta. The seeded copy was only ever a stand-in for
 * an empty site.
 *
 * @return int Number of superseded placeholders removed.
 */
function sklentr_resolve_seed_conflicts() {
	global $wpdb;

	$seeded = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s",
			SKLENTR_SEED_MARK
		)
	);

	$removed = 0;
	foreach ( $seeded as $id ) {
		$post = get_post( $id );
		if ( ! $post ) {
			continue;
		}

		// Is there now a same-type, same-title item that did NOT come from a seeder?
		$rival = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT p.ID FROM {$wpdb->posts} p
				 LEFT JOIN {$wpdb->postmeta} m
				        ON m.post_id = p.ID AND m.meta_key = %s
				 WHERE p.post_type = %s
				   AND p.post_title = %s
				   AND p.ID != %d
				   AND p.post_status IN ('publish','draft','pending','private')
				   AND m.post_id IS NULL
				 LIMIT 1",
				SKLENTR_SEED_MARK,
				$post->post_type,
				$post->post_title,
				$post->ID
			)
		);

		if ( ! $rival ) {
			continue; // Nothing replaced it — this seeded item is still the only copy.
		}

		wp_delete_post( (int) $id, true );
		$removed++;
	}

	return $removed;
}

/**
 * After an import, run the whole repair — not just conflict resolution.
 *
 * The homepage matters here as much as the duplicates: "Home" arrives *with* the
 * import, so at theme-activation time there was nothing for
 * sklentr_restore_front_page() to point at. This is the first moment the site is
 * complete enough to wire up correctly.
 */
add_action( 'import_end', function () {
	// The import just added content the earlier repair could not have seen.
	delete_option( 'sklentr_repair_version' );
	sklentr_repair_site();
} );

/* -------------------------------------------------------------------------
 * 2. Activation repair
 * ---------------------------------------------------------------------- */

/**
 * Strip the numeric suffix WordPress appends when an imported slug collides
 * with existing content, wherever the clean slug is now free.
 *
 * This matters more than it looks: page-about.php, page-services.php and the
 * is_page('about') enqueue conditions in functions.php all key off the slug,
 * so an "about-2" page loses both its template and its JavaScript.
 *
 * @return int Number of slugs corrected.
 */
function sklentr_normalize_slugs() {
	global $wpdb;

	$types = array_merge( sklentr_seeded_post_types(), array( 'page' ) );
	$in    = "'" . implode( "','", array_map( 'esc_sql', $types ) ) . "'";

	$rows = $wpdb->get_results(
		"SELECT ID, post_name, post_type FROM {$wpdb->posts}
		 WHERE post_status = 'publish'
		   AND post_type IN ({$in})
		   AND post_name REGEXP '-[0-9]+$'"
	);

	$fixed = 0;
	foreach ( $rows as $row ) {
		$base = preg_replace( '/-[0-9]+$/', '', $row->post_name );
		if ( '' === $base ) {
			continue;
		}

		// Only a *published* item can legitimately keep the clean slug. WordPress'
		// own unpublished boilerplate (the auto-created Privacy Policy draft) is
		// what usually squats on it, forcing real imported content onto "-2".
		$blocker = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT ID, post_status FROM {$wpdb->posts}
				 WHERE post_name = %s AND post_type = %s AND ID != %d
				   AND post_status IN ('publish','draft','pending','private','future')
				 ORDER BY FIELD(post_status,'publish','private','future','pending','draft')
				 LIMIT 1",
				$base,
				$row->post_type,
				$row->ID
			)
		);

		if ( $blocker && 'publish' === $blocker->post_status ) {
			continue; // A live page owns this slug; leave both alone.
		}

		if ( $blocker ) {
			// Park the draft on a suffixed slug so the live page can take the real one.
			$wpdb->update(
				$wpdb->posts,
				array( 'post_name' => $base . '-draft' ),
				array( 'ID' => $blocker->ID )
			);
			clean_post_cache( $blocker->ID );

			// If that draft was the designated privacy page, hand the role over too,
			// otherwise wp_privacy_policy_url() keeps pointing at unpublished content.
			if ( (int) get_option( 'wp_page_for_privacy_policy' ) === (int) $blocker->ID ) {
				update_option( 'wp_page_for_privacy_policy', $row->ID );
			}
		}

		$wpdb->update( $wpdb->posts, array( 'post_name' => $base ), array( 'ID' => $row->ID ) );
		clean_post_cache( $row->ID );
		$fixed++;
	}

	return $fixed;
}

/**
 * Point the site at the static "Home" page. show_on_front / page_on_front are
 * options, so they never survive a WXR import — without this the homepage
 * falls back to the blog index and front-page.php never runs.
 *
 * Only acts when the current setting is missing or dangling, so an admin's
 * deliberate choice is never overwritten.
 *
 * @return bool Whether the front page was (re)assigned.
 */
function sklentr_restore_front_page() {
	$current = (int) get_option( 'page_on_front' );

	if ( $current && 'page' === get_option( 'show_on_front' ) && 'publish' === get_post_status( $current ) ) {
		return false; // Already valid.
	}

	$home = get_page_by_path( 'home' );

	if ( ! $home ) {
		$found = get_posts( array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'title'          => 'Home',
			'posts_per_page' => 1,
		) );
		$home = $found ? $found[0] : null;
	}

	if ( ! $home ) {
		return false;
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home->ID );

	return true;
}

/**
 * Rebuild rewrite rules (they live in an option, so they never survive either)
 * and write .htaccess when the server allows it. Without this the theme's
 * skl_* CPT permalinks 404 until someone re-saves Settings -> Permalinks.
 */
function sklentr_flush_rewrites() {
	// A brand-new install with no .htaccess falls back to PATHINFO permalinks
	// ("/index.php/%year%/..."), which makes every pretty URL 404.
	$structure = get_option( 'permalink_structure' );
	if ( ! $structure || false !== strpos( $structure, 'index.php' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}

	global $wp_rewrite;
	$wp_rewrite->init();
	$wp_rewrite->flush_rules( true );

	sklentr_write_htaccess();
}

/**
 * Make sure .htaccess actually contains the rewrite rules.
 *
 * We deliberately do not use save_mod_rewrite_rules() alone: it is gated behind
 * got_mod_rewrite(), which reads the $is_apache global. That global is false
 * whenever WordPress is not being served through Apache *in this request* — so
 * under WP-CLI, or any CLI/cron bootstrap, it silently writes nothing and every
 * pretty permalink 404s. Writing the block ourselves closes that gap.
 *
 * insert_with_markers() only replaces the region between the WordPress markers,
 * so any hand-written directives in the file are preserved.
 *
 * @return bool Whether the file now contains rules.
 */
function sklentr_write_htaccess() {
	if ( ! function_exists( 'insert_with_markers' ) ) {
		require_once ABSPATH . 'wp-admin/includes/misc.php';
	}
	if ( ! function_exists( 'get_home_path' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}

	// get_home_path() derives from $_SERVER['SCRIPT_FILENAME'], which is the
	// wp-cli phar (or nothing) under CLI — fall back to ABSPATH when it is bogus.
	$home = get_home_path();
	if ( ! $home || ! is_dir( $home ) || ! file_exists( $home . 'wp-load.php' ) ) {
		$home = ABSPATH;
	}

	$file = $home . '.htaccess';

	// Bail rather than fatal on a read-only deploy (or a non-Apache host).
	if ( file_exists( $file ) ? ! is_writable( $file ) : ! is_writable( dirname( $file ) ) ) {
		return false;
	}

	global $wp_rewrite;
	$rules = explode( "\n", $wp_rewrite->mod_rewrite_rules() );

	$ok = insert_with_markers( $file, 'WordPress', $rules );

	// Apache content negotiation competes with extensionless permalinks.
	insert_with_markers( $file, 'Sklentr', array(
		'<IfModule mod_negotiation.c>',
		'Options -MultiViews',
		'</IfModule>',
	) );

	return $ok;
}

/**
 * Remove the second copy of any meta row a re-run seeder appended.
 *
 * @return int Rows removed.
 */
function sklentr_dedupe_postmeta() {
	global $wpdb;

	$dupes = $wpdb->get_results(
		"SELECT post_id, meta_key FROM {$wpdb->postmeta}
		 GROUP BY post_id, meta_key HAVING COUNT(*) > 1"
	);

	$removed = 0;
	foreach ( $dupes as $d ) {
		// Keep the lowest meta_id, drop the rest.
		$keep = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT MIN(meta_id) FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s",
				$d->post_id,
				$d->meta_key
			)
		);
		$removed += (int) $wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s AND meta_id != %d",
				$d->post_id,
				$d->meta_key,
				$keep
			)
		);
		clean_post_cache( $d->post_id );
	}

	return $removed;
}

/**
 * Run the full repair. Hooked to theme activation, and exposed so it can be
 * triggered manually from wp-admin after an import.
 *
 * @return array Summary of what changed.
 */
function sklentr_repair_site() {
	$result = array(
		// First: retire seeded placeholders an import has superseded, so the
		// slug pass below can hand their clean slugs to the imported copies.
		'seeds_removed' => sklentr_resolve_seed_conflicts(),
		'slugs_fixed'   => sklentr_normalize_slugs(),
		'front_page'    => sklentr_restore_front_page(),
		'meta_removed'  => sklentr_dedupe_postmeta(),
	);

	// Must run last: slug changes alter the rules that get compiled in.
	sklentr_flush_rewrites();

	update_option( 'sklentr_repair_version', SKLENTR_VERSION );

	return $result;
}
add_action( 'after_switch_theme', 'sklentr_repair_site' );

/**
 * Safety net for the case the theme was already active when the import ran —
 * `after_switch_theme` will not fire again, so repair once per theme version.
 */
add_action( 'admin_init', function () {
	if ( get_option( 'sklentr_repair_version' ) === SKLENTR_VERSION ) {
		return;
	}
	sklentr_repair_site();
} );

/**
 * Manual trigger: Tools -> Sklentr Repair.
 */
add_action( 'admin_menu', function () {
	add_management_page(
		__( 'Sklentr Repair', 'sklentr' ),
		__( 'Sklentr Repair', 'sklentr' ),
		'manage_options',
		'sklentr-repair',
		'sklentr_repair_screen'
	);
} );

/**
 * Render the manual repair screen.
 */
function sklentr_repair_screen() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$ran = false;
	$res = array();

	if ( isset( $_POST['sklentr_repair'] ) && check_admin_referer( 'sklentr_repair' ) ) {
		$res = sklentr_repair_site();
		$ran = true;
	}

	echo '<div class="wrap">';
	echo '<h1>' . esc_html__( 'Sklentr Repair', 'sklentr' ) . '</h1>';

	echo '<div class="notice notice-warning inline"><p><strong>' .
		esc_html__( 'Activate this theme BEFORE importing a WXR file.', 'sklentr' ) .
		'</strong><br>' .
		esc_html__( 'The importer rejects any post type that is not registered at the moment it runs. This theme registers skl_service, skl_portfolio, skl_tech and the rest, so importing while a different theme is active silently discards every one of those items with an "Invalid post type" error — 82 of them in a full export. Order: install WordPress, activate Sklentr, then import.', 'sklentr' ) .
		'</p></div>';

	echo '<p>' . esc_html__( 'Run this after importing content into a fresh install. It removes seeded placeholders the import has replaced, corrects slugs, restores the static homepage, removes duplicate meta, and rebuilds permalinks and .htaccess.', 'sklentr' ) . '</p>';

	if ( $ran ) {
		echo '<div class="notice notice-success"><p>';
		printf(
			/* translators: 1: placeholder count, 2: slug count, 3: yes/no, 4: meta row count */
			esc_html__( 'Done. Superseded placeholders removed: %1$d. Slugs corrected: %2$d. Front page restored: %3$s. Duplicate meta rows removed: %4$d. Permalinks rebuilt.', 'sklentr' ),
			(int) $res['seeds_removed'],
			(int) $res['slugs_fixed'],
			$res['front_page'] ? esc_html__( 'yes', 'sklentr' ) : esc_html__( 'not needed', 'sklentr' ),
			(int) $res['meta_removed']
		);
		echo '</p></div>';
	}

	echo '<form method="post">';
	wp_nonce_field( 'sklentr_repair' );
	submit_button( __( 'Run repair', 'sklentr' ), 'primary', 'sklentr_repair' );
	echo '</form>';
	echo '</div>';
}
