<?php
/**
 * Sklentr — Custom Post Types + meta boxes (native, no plugin) and one-time
 * default content seeding. All repeatable homepage content is managed as CPTs
 * so everything is editable from wp-admin. DRY, config-driven.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CPT config. Each CPT: labels, menu icon/position, and its meta fields.
 * Field: key => array( 'Label', 'text|textarea|select', [choices for select] ).
 * The post title is always the primary label.
 *
 * @return array
 */
function sklentr_cpts() {
	return array(
		'skl_service'   => array(
			'name'     => __( 'Services (Home, /services)', 'sklentr' ),
			'singular' => __( 'Service', 'sklentr' ),
			'icon'     => 'dashicons-screenoptions',
			'position' => 55,
			'supports' => array( 'title', 'page-attributes', 'thumbnail' ), // Featured Image = hover-reveal image.
			'fields'   => array(
				'_skl_icon'        => array( __( 'Row icon (circle, right)', 'sklentr' ), 'select', array( 'rocket' => 'Rocket', 'layout' => 'Layout', 'search' => 'Search', 'target' => 'Target', 'video' => 'Video', 'users' => 'Users' ) ),
				'_skl_reveal_icon' => array( __( 'Hover-card icon (different from row icon)', 'sklentr' ), 'select', array( 'layers' => 'Layers', 'monitor' => 'Monitor', 'chart' => 'Chart', 'megaphone' => 'Megaphone', 'film' => 'Film', 'bulb' => 'Bulb' ) ),
				'_skl_desc'        => array( __( 'Description (one line)', 'sklentr' ), 'textarea' ),
				'_skl_tags'        => array( __( 'Feature tags (one per line)', 'sklentr' ), 'textarea' ),
				// Services-page-only fields (the homepage §04 row-list ignores these).
				'_skl_category'    => array( __( 'Category label — Services page (e.g. “Web & Mobile Applications”)', 'sklentr' ), 'text' ),
				'_skl_price'       => array( __( 'Starting price — Services page (e.g. $5,000)', 'sklentr' ), 'text' ),
				'_skl_currency'    => array( __( 'Currency / unit — Services page (e.g. CAD, /month)', 'sklentr' ), 'text' ),
				'_skl_timeline'    => array( __( 'Timeline — Services page (e.g. 2–8 weeks)', 'sklentr' ), 'text' ),
				'_skl_features'    => array( __( 'Full feature list — Services page (one per line)', 'sklentr' ), 'textarea' ),
				'_skl_cta_link'    => array( __( 'Card button link — Services page (default #contact)', 'sklentr' ), 'text' ),
			),
		),
		'skl_svc_perk'  => array(
			'name'     => __( 'Why-Us Points (/services)', 'sklentr' ),
			'singular' => __( 'Why-Us Point', 'sklentr' ),
			'icon'     => 'dashicons-yes',
			'position' => 68,
			'fields'   => array(
				'_skl_icon' => array( __( 'Icon', 'sklentr' ), 'select', array( 'bolt' => 'Speed / Bolt', 'grid' => 'Full Service / Grid', 'globe' => 'Canadian / Globe', 'scale' => 'Built to Scale', 'ai' => 'AI-Powered', 'shield' => 'No Flight Risk / Shield' ) ),
				'_skl_desc' => array( __( 'Description', 'sklentr' ), 'textarea' ),
			),
		),
		'skl_stat'      => array(
			'name'     => __( 'Trust Stats (Home, /services)', 'sklentr' ),
			'singular' => __( 'Stat', 'sklentr' ),
			'icon'     => 'dashicons-chart-bar',
			'position' => 56,
			'fields'   => array(
				'_skl_number' => array( __( 'Number', 'sklentr' ), 'text' ),
				'_skl_suffix' => array( __( 'Suffix (e.g. +, %, -day)', 'sklentr' ), 'text' ),
			),
		),
		'skl_project'   => array(
			'name'     => __( 'Trusted Logos (Home)', 'sklentr' ),
			'singular' => __( 'Project', 'sklentr' ),
			'icon'     => 'dashicons-portfolio',
			'position' => 57,
			'fields'   => array(), // title only.
		),
		'skl_problem'   => array(
			'name'     => __( 'Problem Cards (Home)', 'sklentr' ),
			'singular' => __( 'Problem Card', 'sklentr' ),
			'icon'     => 'dashicons-warning',
			'position' => 58,
			'supports' => array( 'title', 'page-attributes', 'thumbnail' ),
			'fields'   => array(
				'_skl_problem_text'  => array( __( 'Problem text', 'sklentr' ), 'textarea' ),
				'_skl_solution_text' => array( __( 'Solution (“The Sklentr way”)', 'sklentr' ), 'textarea' ),
				'_skl_icon'          => array( __( 'Icon', 'sklentr' ), 'select', array( 'clock' => 'Clock', 'tag' => 'Tag', 'target' => 'Target' ) ),
				'_skl_tone'          => array( __( 'Colour tone', 'sklentr' ), 'select', array( 'gold' => 'Gold', 'green' => 'Green', 'blue' => 'Blue' ) ),
			),
		),
		'skl_hero_step' => array(
			'name'     => __( 'Hero Plan Steps (Home)', 'sklentr' ),
			'singular' => __( 'Plan Step', 'sklentr' ),
			'icon'     => 'dashicons-list-view',
			'position' => 59,
			'fields'   => array(
				'_skl_week'  => array( __( 'Week label (e.g. Wk 1)', 'sklentr' ), 'text' ),
				'_skl_state' => array( __( 'State', 'sklentr' ), 'select', array( '' => 'Upcoming', 'done' => 'Done (✓)', 'active' => 'Active' ) ),
			),
		),
		'skl_hero_tile' => array(
			'name'     => __( 'Hero Plan Tiles (Home)', 'sklentr' ),
			'singular' => __( 'Plan Tile', 'sklentr' ),
			'icon'     => 'dashicons-grid-view',
			'position' => 60,
			'fields'   => array(
				'_skl_count'   => array( __( 'Count-up number (blank = static)', 'sklentr' ), 'text' ),
				'_skl_suffix'  => array( __( 'Suffix after number (e.g.  wks, %)', 'sklentr' ), 'text' ),
				'_skl_display' => array( __( 'Static value (used when no count)', 'sklentr' ), 'text' ),
			),
		),
		'skl_pillar'    => array(
			'name'     => __( 'Why-Us Pillars (Home)', 'sklentr' ),
			'singular' => __( 'Pillar', 'sklentr' ),
			'icon'     => 'dashicons-awards',
			'position' => 54,
			'fields'   => array(
				'_skl_icon'   => array( __( 'Icon', 'sklentr' ), 'select', array( 'bolt' => 'Bolt', 'eye' => 'Eye', 'globe' => 'Globe', 'key' => 'Key', 'shield' => 'Shield', 'lock' => 'Lock', 'gauge' => 'Gauge' ) ),
				'_skl_desc'   => array( __( 'Description', 'sklentr' ), 'textarea' ),
				'_skl_points' => array( __( 'Highlights (one per line)', 'sklentr' ), 'textarea' ),
			),
		),
		'skl_visa_feature' => array(
			'name'     => __( 'Visa Features (Home)', 'sklentr' ),
			'singular' => __( 'Visa Feature', 'sklentr' ),
			'icon'     => 'dashicons-yes-alt',
			'position' => 61,
			'fields'   => array(
				'_skl_icon' => array( __( 'Icon', 'sklentr' ), 'select', array( 'product' => 'Working Product', 'clock' => 'Deadline / Clock', 'budget' => 'Budget / Wallet', 'shield' => 'Shield', 'rocket' => 'Rocket' ) ),
				'_skl_sub'  => array( __( 'Subtitle (one line)', 'sklentr' ), 'textarea' ),
			),
		),
		'skl_work' => array(
			'name'     => __( 'Featured Work (Home)', 'sklentr' ),
			'singular' => __( 'Case Study', 'sklentr' ),
			'icon'     => 'dashicons-portfolio',
			'position' => 62,
			'supports' => array( 'title', 'page-attributes', 'thumbnail' ),
			'fields'   => array(
				'_skl_industry' => array( __( 'Industry tag', 'sklentr' ), 'text' ),
				'_skl_outcome'  => array( __( 'Outcome / metric (one line)', 'sklentr' ), 'text' ),
				'_skl_tags'     => array( __( 'Tech tags (one per line)', 'sklentr' ), 'textarea' ),
				'_skl_img'      => array( __( 'Default image (if no Featured Image)', 'sklentr' ), 'select', array( 'healthcare' => 'Healthcare', 'agritech' => 'AgriTech', 'fintech' => 'FinTech', 'care' => 'Care', 'fashion' => 'Fashion', 'data' => 'Data / SaaS' ) ),
				'_skl_link'     => array( __( 'Case-study link', 'sklentr' ), 'text' ),
			),
		),
		'skl_process' => array(
			'name'     => __( 'Process Steps (Home)', 'sklentr' ),
			'singular' => __( 'Process Step', 'sklentr' ),
			'icon'     => 'dashicons-editor-ol',
			'position' => 63,
			'fields'   => array(
				'_skl_icon'     => array( __( 'Icon', 'sklentr' ), 'select', array( 'discovery' => 'Discovery', 'design' => 'Design', 'build' => 'Build', 'launch' => 'Launch' ) ),
				'_skl_desc'     => array( __( 'One-line description', 'sklentr' ), 'textarea' ),
				'_skl_duration' => array( __( 'Typical duration', 'sklentr' ), 'text' ),
			),
		),
		'skl_faq' => array(
			'name'     => __( 'FAQ (Home)', 'sklentr' ),
			'singular' => __( 'FAQ', 'sklentr' ),
			'icon'     => 'dashicons-editor-help',
			'position' => 67,
			'fields'   => array(
				'_skl_answer' => array( __( 'Answer', 'sklentr' ), 'textarea' ),
			),
		),
		'skl_location' => array(
			'name'     => __( 'Team Locations (unused)', 'sklentr' ),
			'singular' => __( 'Location', 'sklentr' ),
			'icon'     => 'dashicons-location',
			'position' => 66,
			'fields'   => array(
				'_skl_role'   => array( __( 'Role / focus (one line)', 'sklentr' ), 'text' ),
				'_skl_region' => array( __( 'Region', 'sklentr' ), 'select', array( 'canada' => 'Canada (gold)', 'asia' => 'Asia (green)', 'global' => 'Global (violet)' ) ),
			),
		),
		'skl_tech' => array(
			'name'     => __( 'Tech Stack (Home)', 'sklentr' ),
			'singular' => __( 'Technology', 'sklentr' ),
			'icon'     => 'dashicons-editor-code',
			'position' => 65,
			'fields'   => array(
				'_skl_key'      => array( __( 'Logo mark', 'sklentr' ), 'select', array( 'nextjs' => 'Next.js', 'react' => 'React', 'typescript' => 'TypeScript', 'tailwind' => 'Tailwind', 'laravel' => 'Laravel', 'nodejs' => 'Node.js', 'flutter' => 'Flutter', 'wordpress' => 'WordPress', 'postgresql' => 'PostgreSQL', 'gemini' => 'Gemini', 'openai' => 'OpenAI', 'claude' => 'Claude' ) ),
				'_skl_category' => array( __( 'Category', 'sklentr' ), 'select', array( 'frontend' => 'Frontend', 'backend' => 'Backend', 'mobile' => 'Mobile', 'database' => 'Database', 'cms' => 'CMS', 'ai' => 'AI' ) ),
			),
		),
		'skl_pricing' => array(
			'name'     => __( 'Pricing Tiers (Home)', 'sklentr' ),
			'singular' => __( 'Pricing Tier', 'sklentr' ),
			'icon'     => 'dashicons-tag',
			'position' => 64,
			'fields'   => array(
				'_skl_prefix'    => array( __( 'Price prefix (e.g. “Starting at”)', 'sklentr' ), 'text' ),
				'_skl_price'     => array( __( 'Price (e.g. $5,000)', 'sklentr' ), 'text' ),
				'_skl_currency'  => array( __( 'Currency (e.g. CAD)', 'sklentr' ), 'text' ),
				'_skl_period'    => array( __( 'Timeline (e.g. 2 weeks)', 'sklentr' ), 'text' ),
				'_skl_features'  => array( __( 'Features (one per line)', 'sklentr' ), 'textarea' ),
				'_skl_popular'   => array( __( 'Highlight as popular?', 'sklentr' ), 'select', array( '' => 'No', 'yes' => 'Yes' ) ),
				'_skl_badge'     => array( __( 'Popular badge text', 'sklentr' ), 'text' ),
				'_skl_cta_text'  => array( __( 'Button text', 'sklentr' ), 'text' ),
				'_skl_cta_link'  => array( __( 'Button link', 'sklentr' ), 'text' ),
			),
		),
		'skl_portfolio' => array(
			'name'     => __( 'Portfolio Projects (/portfolio)', 'sklentr' ),
			'singular' => __( 'Portfolio Project', 'sklentr' ),
			'icon'     => 'dashicons-portfolio',
			'position' => 53,
			'supports' => array( 'title', 'page-attributes', 'thumbnail' ), // Featured Image = case-study shot.
			'fields'   => array(
				'_skl_slug'      => array( __( 'Default image (if no Featured Image)', 'sklentr' ), 'select', array( 'aifarming' => 'AI Farming', 'gettakaful' => 'Get Takaful', 'kindredcare' => 'KindredCare', 'agilesourcing' => 'Agile Sourcing', 'gaindata' => 'GAinData' ) ),
				'_skl_tag1'      => array( __( 'Tag 1', 'sklentr' ), 'text' ),
				'_skl_tag2'      => array( __( 'Tag 2', 'sklentr' ), 'text' ),
				'_skl_status'    => array( __( 'Status label', 'sklentr' ), 'text' ),
				'_skl_desc'      => array( __( 'Description', 'sklentr' ), 'textarea' ),
				'_skl_challenge' => array( __( 'Challenge', 'sklentr' ), 'textarea' ),
				'_skl_solution'  => array( __( 'Solution', 'sklentr' ), 'textarea' ),
				'_skl_results'   => array( __( 'Results (one per line)', 'sklentr' ), 'textarea' ),
				'_skl_stack'     => array( __( 'Tech stack (one per line)', 'sklentr' ), 'textarea' ),
			),
		),
	);
}

/**
 * Register all CPTs.
 */
add_action( 'init', function () {
	foreach ( sklentr_cpts() as $slug => $c ) {
		register_post_type( $slug, array(
			'labels'              => array(
				'name'          => $c['name'],
				'singular_name' => $c['singular'],
				'menu_name'     => $c['name'],
				/* translators: %s: singular CPT name. */
				'add_new_item'  => sprintf( __( 'Add %s', 'sklentr' ), $c['singular'] ),
				/* translators: %s: singular CPT name. */
				'edit_item'     => sprintf( __( 'Edit %s', 'sklentr' ), $c['singular'] ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => 'sklentr-settings',
			'menu_icon'           => $c['icon'],
			'menu_position'       => $c['position'],
			'supports'            => isset( $c['supports'] ) ? $c['supports'] : array( 'title', 'page-attributes' ),
			'has_archive'         => false,
			'rewrite'             => false,
			'exclude_from_search' => true,
		) );
	}
} );

/**
 * Add one meta box per CPT that has fields.
 */
add_action( 'add_meta_boxes', function () {
	foreach ( sklentr_cpts() as $slug => $c ) {
		if ( empty( $c['fields'] ) ) {
			continue;
		}
		add_meta_box( $slug . '_meta', __( 'Details', 'sklentr' ), 'sklentr_meta_render', $slug, 'normal', 'high' );
	}
} );

/**
 * Render meta fields for the current CPT.
 *
 * @param WP_Post $post Current post.
 */
function sklentr_meta_render( $post ) {
	$cpts = sklentr_cpts();
	if ( empty( $cpts[ $post->post_type ]['fields'] ) ) {
		return;
	}
	wp_nonce_field( 'sklentr_meta_save', 'sklentr_meta_nonce' );

	echo '<div class="skl-meta">';
	foreach ( $cpts[ $post->post_type ]['fields'] as $key => $field ) {
		$label = $field[0];
		$type  = $field[1];
		$val   = get_post_meta( $post->ID, $key, true );

		echo '<p class="skl-meta__field"><label class="skl-meta__label" for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label>';
		if ( 'textarea' === $type ) {
			echo '<textarea id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" rows="2" class="large-text">' . esc_textarea( $val ) . '</textarea>';
		} elseif ( 'select' === $type ) {
			echo '<select id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '">';
			foreach ( $field[2] as $ok => $ov ) {
				echo '<option value="' . esc_attr( $ok ) . '" ' . selected( $val, $ok, false ) . '>' . esc_html( $ov ) . '</option>';
			}
			echo '</select>';
		} else {
			echo '<input type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" class="regular-text" />';
		}
		echo '</p>';
	}
	echo '<p class="description skl-meta__note">' . esc_html__( 'The post title is the main label. Reorder items with the Order field in the Page Attributes box.', 'sklentr' ) . '</p>';
	echo '</div>';
}

/**
 * Save meta for any Sklentr CPT.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
add_action( 'save_post', function ( $post_id, $post ) {
	if ( ! isset( $_POST['sklentr_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sklentr_meta_nonce'] ) ), 'sklentr_meta_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$cpts = sklentr_cpts();
	if ( empty( $cpts[ $post->post_type ]['fields'] ) ) {
		return;
	}
	foreach ( $cpts[ $post->post_type ]['fields'] as $key => $field ) {
		$type = $field[1];
		$raw  = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
		if ( 'textarea' === $type ) {
			$val = sanitize_textarea_field( $raw );
		} elseif ( 'select' === $type ) {
			$val = sanitize_key( $raw );
		} else {
			$val = sanitize_text_field( $raw );
		}
		update_post_meta( $post_id, $key, $val );
	}
}, 10, 2 );

/**
 * Problem-card line icon SVG for a stored key (SVGs live in code as assets).
 *
 * @param string $key clock|tag|target.
 * @return string Trusted SVG markup.
 */
function skl_problem_icon_svg( $key ) {
	$open = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';
	switch ( $key ) {
		case 'tag':
			return $open . '<path d="M3.5 12.9V4.8A1.3 1.3 0 0 1 4.8 3.5h8.1c.35 0 .68.14.92.38l6.6 6.6a1.3 1.3 0 0 1 0 1.84l-6.6 6.6a1.3 1.3 0 0 1-1.84 0l-6.6-6.6a1.3 1.3 0 0 1-.38-.92z"/><circle cx="7.8" cy="7.8" r="1.15"/></svg>';
		case 'target':
			return $open . '<circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="4.5"/><circle cx="12" cy="12" r="1"/></svg>';
		case 'clock':
		default:
			return $open . '<circle cx="12" cy="12" r="9"/><path d="M12 7.5V12l3 1.8"/></svg>';
	}
}

/**
 * Service line icon SVG for a stored key.
 *
 * @param string $key code|browser|search|megaphone|video|chat.
 * @return string Trusted SVG markup.
 */
function skl_service_icon_svg( $key ) {
	$open = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';
	switch ( $key ) {
		case 'layout':
			return $open . '<rect x="3.5" y="4.5" width="17" height="15" rx="2"/><path d="M3.5 9.5h17"/><path d="M9 9.5v10"/></svg>';
		case 'search':
			return $open . '<circle cx="11" cy="11" r="6.5"/><path d="M20 20l-4.2-4.2"/></svg>';
		case 'target':
			return $open . '<circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="4.5"/><circle cx="12" cy="12" r="1"/></svg>';
		case 'video':
			return $open . '<rect x="3.5" y="6.5" width="12" height="11" rx="2"/><path d="M15.5 10l5-2.5v9l-5-2.5z"/></svg>';
		case 'users':
			return $open . '<path d="M16 19v-1.5a3.5 3.5 0 0 0-3.5-3.5h-5A3.5 3.5 0 0 0 4 17.5V19"/><circle cx="10" cy="8" r="3.3"/><path d="M20 19v-1.5a3.5 3.5 0 0 0-2.7-3.4"/><path d="M15.6 5.2a3.3 3.3 0 0 1 0 6.1"/></svg>';
		case 'rocket':
		default:
			return $open . '<path d="M4.6 16.4c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="M12 15l-3-3a22 22 0 0 1 2-3.95A12.9 12.9 0 0 1 22 2c0 2.72-.78 7.5-6 11a22 22 0 0 1-4 2z"/><path d="M9 12H4s.55-3 2-4c1.6-1.08 5 0 5 0"/><path d="M12 15v5s3-.55 4-2c1.08-1.6 0-5 0-5"/></svg>';
	}
}

/**
 * Why-Us pillar icon SVG.
 *
 * @param string $key bolt|eye|globe|key|shield|lock|gauge.
 * @return string Trusted SVG markup.
 */
function skl_pillar_icon_svg( $key ) {
	$open = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';
	switch ( $key ) {
		case 'eye':
			return $open . '<path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12z"/><circle cx="12" cy="12" r="3"/></svg>';
		case 'globe':
			return $open . '<circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a15 15 0 0 1 4 9 15 15 0 0 1-4 9 15 15 0 0 1-4-9 15 15 0 0 1 4-9z"/></svg>';
		case 'key':
			return $open . '<circle cx="7.5" cy="15.5" r="4"/><path d="M10.5 12.5 20 3"/><path d="M16.5 6.5l2 2M14.5 8.5l2 2"/></svg>';
		case 'shield':
			return $open . '<path d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6z"/><path d="M9 12l2 2 4-4"/></svg>';
		case 'lock':
			return $open . '<rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>';
		case 'gauge':
			return $open . '<circle cx="12" cy="12" r="9"/><path d="M12 12l4-3"/><path d="M12 3v2M21 12h-2M3 12h2"/></svg>';
		case 'bolt':
		default:
			return $open . '<path d="M13 2 4.5 13.5H11l-1 8.5 8.5-11.5H12z"/></svg>';
	}
}

/**
 * Hover-card ("reveal") icon SVG — a set distinct from the row icons so the
 * card glyph never matches the row's circle icon.
 *
 * @param string $key layers|monitor|chart|megaphone|film|bulb.
 * @return string Trusted SVG markup.
 */
function skl_reveal_icon_svg( $key ) {
	$open = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';
	switch ( $key ) {
		case 'monitor':
			return $open . '<rect x="2.5" y="3.5" width="19" height="13" rx="2"/><path d="M8.5 20.5h7"/><path d="M12 16.5v4"/></svg>';
		case 'chart':
			return $open . '<path d="M4 16l4.5-5 3.5 2.6L20 6"/><path d="M15 6h5v5"/></svg>';
		case 'megaphone':
			return $open . '<path d="M3 11v2a1 1 0 0 0 1 1h1.8l4.2 3.4V6.6L5.8 10H4a1 1 0 0 0-1 1z"/><path d="M14 8.6a4 4 0 0 1 0 6.8"/></svg>';
		case 'film':
			return $open . '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 4v16M17 4v16"/><path d="M3 9h4M3 15h4M17 9h4M17 15h4"/></svg>';
		case 'bulb':
			return $open . '<path d="M9.5 18h5"/><path d="M10 21h4"/><path d="M12 3a6 6 0 0 0-3.8 10.6c.7.6 1.3 1.4 1.3 2.4h5c0-1 .6-1.8 1.3-2.4A6 6 0 0 0 12 3z"/></svg>';
		case 'layers':
		default:
			return $open . '<path d="M12 2 2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>';
	}
}

/**
 * The green check icon used in resolution rows.
 *
 * @return string
 */
function skl_check_icon_svg() {
	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M20 6.5 9.5 17 4 11.5"/></svg>';
}

/**
 * Services-page "Why Us" perk icon SVG for a stored key.
 *
 * @param string $key bolt|grid|globe|scale|ai|shield.
 * @return string Trusted SVG markup.
 */
function skl_perk_icon_svg( $key ) {
	$open = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';
	switch ( $key ) {
		case 'grid':
			return $open . '<rect x="3.5" y="3.5" width="7" height="7" rx="1.6"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.6"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.6"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.6"/></svg>';
		case 'globe':
			return $open . '<circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a15 15 0 0 1 4 9 15 15 0 0 1-4 9 15 15 0 0 1-4-9 15 15 0 0 1 4-9z"/></svg>';
		case 'scale':
			return $open . '<path d="M4 17l5-5 3.2 3 6.8-7.4"/><path d="M16 7.6h3.4V11"/></svg>';
		case 'ai':
			return $open . '<path d="M12 3.2c.55 4.1 3.2 6.75 7.3 7.3-4.1.55-6.75 3.2-7.3 7.3-.55-4.1-3.2-6.75-7.3-7.3 4.1-.55 6.75-3.2 7.3-7.3z"/><path d="M18.6 16.4l.5 2 2 .5-2 .5-.5 2-.5-2-2-.5 2-.5z"/></svg>';
		case 'shield':
			return $open . '<path d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6z"/><path d="M9 12l2 2 4-4"/></svg>';
		case 'bolt':
		default:
			return $open . '<path d="M13 2 4.5 13.5H11l-1 8.5 8.5-11.5H12z"/></svg>';
	}
}

/**
 * Technology / AI "logo" mark SVG for a stored key. Stylized, single-colour
 * marks (monochrome by default, tinted on hover in CSS). The tech name label
 * always accompanies the mark.
 *
 * @param string $key nextjs|react|typescript|tailwind|laravel|nodejs|flutter|wordpress|postgresql|gemini|openai|claude.
 * @return string Trusted SVG markup.
 */
function skl_tech_icon_svg( $key ) {
	$o = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';
	switch ( $key ) {
		case 'react':
			return $o . '<circle cx="12" cy="12" r="1.7" fill="currentColor" stroke="none"/><ellipse cx="12" cy="12" rx="9.2" ry="3.6"/><ellipse cx="12" cy="12" rx="9.2" ry="3.6" transform="rotate(60 12 12)"/><ellipse cx="12" cy="12" rx="9.2" ry="3.6" transform="rotate(120 12 12)"/></svg>';
		case 'nextjs':
			return $o . '<circle cx="12" cy="12" r="9.2"/><path d="M9 16V8l6.2 8.2"/><path d="M15.2 15V8"/></svg>';
		case 'typescript':
			return $o . '<rect x="3.4" y="3.4" width="17.2" height="17.2" rx="3.2"/><path d="M6.8 11.4h4.8M9.2 11.4V17.2"/><path d="M17.8 11.9a2.2 2.2 0 0 0-3.4 1.7c0 2.1 3.4 1.4 3.4 3.4a2.1 2.1 0 0 1-3.4.4"/></svg>';
		case 'tailwind':
			return $o . '<path fill="currentColor" stroke="none" d="M12 8.6c-2.67 0-4.33 1.33-5 4 1-1.33 2.17-1.83 3.5-1.5.76.19 1.3.74 1.9 1.35.98.98 2.12 2.15 4.6 2.15 2.67 0 4.33-1.33 5-4-1 1.33-2.17 1.83-3.5 1.5-.76-.19-1.3-.74-1.9-1.35C15.62 9.77 14.48 8.6 12 8.6zM7 14.6c-2.67 0-4.33 1.33-5 4 1-1.33 2.17-1.83 3.5-1.5.76.19 1.3.74 1.9 1.35.98.98 2.12 2.15 4.6 2.15 2.67 0 4.33-1.33 5-4-1 1.33-2.17 1.83-3.5 1.5-.76-.19-1.3-.74-1.9-1.35C10.62 15.77 9.48 14.6 7 14.6z"/></svg>';
		case 'laravel':
			return $o . '<rect x="3.5" y="3.5" width="17" height="17" rx="4"/><path d="M9 7.5v9h6.2"/></svg>';
		case 'nodejs':
			return $o . '<path d="M12 2.7l8.1 4.65v9.3L12 21.3 3.9 16.65v-9.3z"/><path d="M9.7 16v-5a2 2 0 0 1 4 0v.4"/><path d="M14.4 15.8a2.1 2.1 0 0 0 3.2-.2"/></svg>';
		case 'flutter':
			return $o . '<path fill="currentColor" stroke="none" d="M13.9 2.6L5.3 11.2l2.75 2.75L19.5 2.6zM13.9 10.4l-4.2 4.2 4.2 4.2h5.6l-4.2-4.2 4.2-4.2z"/></svg>';
		case 'wordpress':
			return $o . '<circle cx="12" cy="12" r="9.2"/><path d="M6.4 9.2l2.15 6.3 1.9-4.9 1.9 4.9L14 11l1.55 4.5L17.7 9.2"/></svg>';
		case 'postgresql':
			return $o . '<ellipse cx="12" cy="6.2" rx="7" ry="2.6"/><path d="M5 6.2v11.6c0 1.44 3.13 2.6 7 2.6s7-1.16 7-2.6V6.2"/><path d="M5 12c0 1.44 3.13 2.6 7 2.6s7-1.16 7-2.6"/></svg>';
		case 'gemini':
			return $o . '<path fill="currentColor" stroke="none" d="M12 2.4c.66 5 3.9 8.24 8.9 8.9-5 .66-8.24 3.9-8.9 8.9-.66-5-3.9-8.24-8.9-8.9 5-.66 8.24-3.9 8.9-8.9z"/></svg>';
		case 'openai':
			return $o . '<ellipse cx="12" cy="12" rx="3.3" ry="8"/><ellipse cx="12" cy="12" rx="3.3" ry="8" transform="rotate(60 12 12)"/><ellipse cx="12" cy="12" rx="3.3" ry="8" transform="rotate(120 12 12)"/></svg>';
		case 'claude':
			return $o . '<path d="M12 2.6v18.8M3.4 7.3l17.2 9.4M3.4 16.7l17.2-9.4M2.8 12h18.4"/></svg>';
		default:
			return $o . '<path d="M8 8l-4 4 4 4M16 8l4 4-4 4M13.5 6.5l-3 11"/></svg>';
	}
}

/**
 * How-We-Work process step icon SVG for a stored key.
 *
 * @param string $key discovery|design|build|launch.
 * @return string Trusted SVG markup.
 */
function skl_process_icon_svg( $key ) {
	$open = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';
	switch ( $key ) {
		case 'design':
			return $open . '<path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>';
		case 'build':
			return $open . '<path d="M16 18l6-6-6-6"/><path d="M8 6l-6 6 6 6"/></svg>';
		case 'launch':
			return $open . '<path d="M4.5 16.4c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="M12 15l-3-3a22 22 0 0 1 2-3.95A12.9 12.9 0 0 1 22 2c0 2.72-.78 7.5-6 11a22 22 0 0 1-4 2z"/><path d="M9 12H4s.55-3 2-4c1.6-1.08 5 0 5 0"/><path d="M12 15v5s3-.55 4-2c1.08-1.6 0-5 0-5"/></svg>';
		case 'discovery':
		default:
			return $open . '<circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/><path d="M11 8v6M8 11h6"/></svg>';
	}
}

/**
 * Maple-leaf mark for the Startup Visa spotlight (SVG lives in code as an asset).
 *
 * @return string Trusted SVG markup.
 */
function skl_maple_leaf_svg() {
	
return '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 2.2l1.45 3.1 2.2-.8-.65 2.3 2.55-.2-1.55 2.05 3.2.75-2.75 1.55 1.35 2.35-3.05-.6.35 2.7-2.3-1.35v2.55h-1.9v-2.55l-2.3 1.35.35-2.7-3.05.6 1.35-2.35-2.75-1.55 3.2-.75-1.55-2.05 2.55.2-.65-2.3 2.2.8z"/></svg>';
}

/**
 * Startup Visa feature icon SVG for a stored key.
 *
 * @param string $key product|clock|budget|shield|rocket.
 * @return string Trusted SVG markup.
 */
function skl_visa_icon_svg( $key ) {
	$open = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';
	switch ( $key ) {
		case 'clock':
			return $open . '<circle cx="12" cy="12" r="9"/><path d="M12 7.5V12l3 1.8"/></svg>';
		case 'budget':
			return $open . '<rect x="3" y="6" width="18" height="13" rx="2.5"/><path d="M3 10h18"/><path d="M15.5 14.5H18"/></svg>';
		case 'shield':
			return $open . '<path d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6z"/><path d="M9 12l2 2 4-4"/></svg>';
		case 'rocket':
			return $open . '<path d="M4.6 16.4c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="M12 15l-3-3a22 22 0 0 1 2-3.95A12.9 12.9 0 0 1 22 2c0 2.72-.78 7.5-6 11a22 22 0 0 1-4 2z"/></svg>';
		case 'product':
		default:
			return $open . '<rect x="3.5" y="4.5" width="17" height="13" rx="2"/><path d="M3.5 9h17"/><path d="M9.5 13.5l2 2 3.5-3.5"/></svg>';
	}
}

/**
 * One-time seeding of default content (idempotent per collection). Runs on
 * `init` (prio 20, after CPTs register), guarded by a version flag.
 */
add_action( 'init', function () {
	if ( get_option( 'sklentr_seeded_v18' ) ) {
		return;
	}

	// One-time (v4/v5): refresh Services to the sklentr.com data/design.
	if ( ! get_option( 'sklentr_svc_refreshed' ) ) {
		foreach ( get_posts( array( 'post_type' => 'skl_service', 'numberposts' => -1, 'post_status' => 'any', 'fields' => 'ids' ) ) as $skl_old ) {
			wp_delete_post( $skl_old, true );
		}
		$skl_o = get_option( 'sklentr_settings', array() );
		foreach ( array( 'services_eyebrow', 'services_title', 'services_title_accent', 'services_intro', 'services_cta_text', 'services_cta_link' ) as $skl_k ) {
			unset( $skl_o[ $skl_k ] );
		}
		update_option( 'sklentr_settings', $skl_o );
		update_option( 'sklentr_svc_refreshed', 1 );
	}

	// v9: migrate the Startup Visa section to mirror the live sklentr.com copy —
	// drop the earlier stat/deliverable model + its options, then reseed features.
	if ( ! get_option( 'sklentr_visa_refreshed' ) ) {
		foreach ( array( 'skl_visa_stat', 'skl_visa_incl' ) as $skl_vt ) {
			foreach ( get_posts( array( 'post_type' => $skl_vt, 'numberposts' => -1, 'post_status' => 'any', 'fields' => 'ids' ) ) as $skl_vp ) {
				wp_delete_post( $skl_vp, true );
			}
		}
		$skl_vo = get_option( 'sklentr_settings', array() );
		foreach ( array( 'visa_eyebrow', 'visa_title', 'visa_title_accent', 'visa_body', 'visa_included_title', 'visa_scarcity', 'visa_cta1_text', 'visa_cta1_link', 'visa_cta2_text', 'visa_cta2_link', 'visa_whatsapp_text', 'visa_whatsapp_link', 'visa_card_kicker', 'visa_card_title', 'visa_card_status', 'visa_card_stamp', 'visa_card_foot' ) as $skl_vk ) {
			unset( $skl_vo[ $skl_vk ] );
		}
		update_option( 'sklentr_settings', $skl_vo );
		update_option( 'sklentr_visa_refreshed', 1 );
	}

	// v7: backfill pillar highlights on existing pillars (keeps admin edits).
	$skl_point_map = array(
		'Speed without compromise'              => "14-day average delivery\n98% on-time record\nWeekly progress demos",
		'Radical transparency'                  => "Published fixed pricing\nNo hidden fees, ever\nYou approve every scope",
		'Canadian management, global talent'    => "Toronto-based project leads\nExpert global engineering\nOne accountable team",
		'You own everything'                    => "100% source-code handover\nFull IP rights\n1-month post-launch support",
	);
	foreach ( get_posts( array( 'post_type' => 'skl_pillar', 'numberposts' => -1, 'post_status' => 'any' ) ) as $skl_pl ) {
		if ( '' === (string) get_post_meta( $skl_pl->ID, '_skl_points', true ) && isset( $skl_point_map[ $skl_pl->post_title ] ) ) {
			update_post_meta( $skl_pl->ID, '_skl_points', $skl_point_map[ $skl_pl->post_title ] );
		}
	}

	// One-time: seed the Insights/Blog with the three founding articles (native
	// WP posts, so they're managed under Posts). Own flag so future re-seeds
	// never duplicate them.
	if ( ! get_option( 'sklentr_blog_seeded' ) ) {
		$skl_blog = array(
			array(
				'title'   => 'How to Validate Your Startup Idea Before Building',
				'cat'     => 'MVP Strategy',
				'excerpt' => 'Before you write a line of code, prove people actually want it. A practical, no-fluff framework for validating demand fast.',
				'body'    => "The most expensive MVP is the one nobody wanted. Before you build, your job is to reduce risk — cheaply and quickly.\n\nStart with the problem, not the product. Talk to ten people who live the pain you're solving and listen for the words they use. If they can't describe the problem without prompting, you don't have a market yet.\n\nNext, test willingness to pay. A landing page with a clear promise and a \"Get early access\" button tells you more than any survey. Real signal is an email address, a pre-order, or a booked call — not a thumbs-up.\n\nOnly then do you scope the smallest thing that delivers the core value. One or two features, done well, beat ten features half-built. Ship it, watch behaviour, and let real usage — not opinions — decide what comes next.",
			),
			array(
				'title'   => 'The 2-Week MVP: What’s Actually Possible',
				'cat'     => 'Product',
				'excerpt' => 'A two-week build isn’t magic — it’s ruthless scope. Here’s what fits, what doesn’t, and how we ship it on time.',
				'body'    => "Two weeks sounds impossible until you realise most of an MVP is scope, not code. The teams that ship fast aren’t faster typists — they’re better editors.\n\nA two-week build works when the goal is a single, sharp value loop: a user signs up, does the one thing that matters, and gets the payoff. Everything else — settings, edge cases, admin polish — waits.\n\nWhat fits: a focused web or mobile flow, auth, a core feature, and a clean, credible design. What doesn’t: five personas, three integrations, and a dashboard for data you don’t have yet.\n\nThe secret is a fixed scope agreed up front, a proven stack, and daily progress you can see. Constraints don’t lower quality — they force the decisions that make a product feel done.",
			),
			array(
				'title'   => 'Startup Visa Canada: Technical Requirements Explained',
				'cat'     => 'Startup Visa',
				'excerpt' => 'Designated organizations see hundreds of decks. A working product is what proves execution — here’s what IRCC-ready looks like.',
				'body'    => "For the Canada Startup Visa, ideas don’t get visas — evidence of execution does. Designated organizations review hundreds of pitches; a functional product cuts through the noise.\n\nYou don’t need a finished company. You need a working MVP that demonstrates the concept is real and buildable: a live product a reviewer can open, a clear problem-solution fit, and proof you can ship.\n\nPractically, that means a functional app, an admin view, and assets that make the demo effortless — a short walkthrough video and a tidy pitch deck. Own 100% of the source code and IP so there are no questions about who built what.\n\nTimeline and budget matter as much as the build. A predictable four-week delivery on a fixed price keeps your application on schedule and your runway intact.",
			),
		);
		foreach ( $skl_blog as $skl_bp ) {
			// Front-end safe (wp_create_category is admin-only): find or create the term.
			$skl_term   = term_exists( $skl_bp['cat'], 'category' );
			if ( ! $skl_term ) {
				$skl_term = wp_insert_term( $skl_bp['cat'], 'category' );
			}
			$skl_cat_id = ( ! is_wp_error( $skl_term ) && isset( $skl_term['term_id'] ) ) ? (int) $skl_term['term_id'] : 0;
			wp_insert_post( array(
				'post_type'     => 'post',
				'post_status'   => 'publish',
				'post_title'    => $skl_bp['title'],
				'post_excerpt'  => $skl_bp['excerpt'],
				'post_content'  => $skl_bp['body'],
				'post_category' => $skl_cat_id ? array( $skl_cat_id ) : array(),
			) );
		}
		update_option( 'sklentr_blog_seeded', 1 );
	}

	$seed = array(
		'skl_service'   => array(
			array( 'title' => 'MVP Development',       '_skl_icon' => 'rocket', '_skl_reveal_icon' => 'layers',    '_skl_desc' => 'Build your product fast with our expert team.', '_skl_tags' => "Web Apps\nMobile Apps\nAPI Development" ),
			array( 'title' => 'Website Design',        '_skl_icon' => 'layout', '_skl_reveal_icon' => 'monitor',   '_skl_desc' => 'Professional websites that convert visitors.',  '_skl_tags' => "WordPress\nNext.js\nCustom Development" ),
			array( 'title' => 'SEO & Marketing',       '_skl_icon' => 'search', '_skl_reveal_icon' => 'chart',     '_skl_desc' => 'Get found and grow your audience online.',      '_skl_tags' => "Search Optimization\nSocial Media\nContent Strategy" ),
			array( 'title' => 'Paid Ads',              '_skl_icon' => 'target', '_skl_reveal_icon' => 'megaphone', '_skl_desc' => 'Drive targeted traffic with precision.',        '_skl_tags' => "Google Ads\nMeta Ads\nCampaign Management" ),
			array( 'title' => 'Video Production',       '_skl_icon' => 'video',  '_skl_reveal_icon' => 'film',      '_skl_desc' => 'Engaging video content for your brand.',        '_skl_tags' => "Promo Videos\nSocial Content\nProduct Demos" ),
			array( 'title' => 'Business Consultation', '_skl_icon' => 'users',  '_skl_reveal_icon' => 'bulb',      '_skl_desc' => 'Strategic guidance to help you scale.',         '_skl_tags' => "Market Research\nProduct Strategy\nGrowth Planning" ),
		),
		'skl_stat'      => array(
			array( 'title' => 'Projects shipped',      '_skl_number' => '50',  '_skl_suffix' => '+' ),
			array( 'title' => 'Startup Visa MVPs',     '_skl_number' => '15',  '_skl_suffix' => '+' ),
			array( 'title' => 'On-time delivery',      '_skl_number' => '98',  '_skl_suffix' => '%' ),
			array( 'title' => 'Avg. delivery',         '_skl_number' => '14',  '_skl_suffix' => '-day' ),
			array( 'title' => 'Source-code ownership', '_skl_number' => '100', '_skl_suffix' => '%' ),
		),
		'skl_project'   => array(
			array( 'title' => 'AI Farming' ),
			array( 'title' => 'Get Takaful' ),
			array( 'title' => 'KindredCare' ),
			array( 'title' => 'Agile Sourcing' ),
			array( 'title' => 'GAinData' ),
		),
		'skl_problem'   => array(
			array( 'title' => 'Too slow',          '_skl_tone' => 'gold',  '_skl_icon' => 'clock',  '_skl_problem_text' => 'Agencies take 6+ months while your funding window quietly closes.', '_skl_solution_text' => 'A working MVP in 2–4 weeks — while the opportunity is still open.' ),
			array( 'title' => 'Too expensive',     '_skl_tone' => 'green', '_skl_icon' => 'tag',    '_skl_problem_text' => '$50k+ quotes just for a first version — before you’ve validated a thing.', '_skl_solution_text' => 'Transparent fixed pricing from $5,000. You know the number up front.' ),
			array( 'title' => 'Wrong deliverable', '_skl_tone' => 'blue',  '_skl_icon' => 'target', '_skl_problem_text' => 'Months later you get something you didn’t ask for — and can’t even edit.', '_skl_solution_text' => 'Founder-led discovery keeps us aligned, and you own 100% of the code.' ),
		),
		'skl_hero_step' => array(
			array( 'title' => 'Discovery', '_skl_week' => 'Wk 1',   '_skl_state' => 'done' ),
			array( 'title' => 'Design',    '_skl_week' => 'Wk 2–3', '_skl_state' => 'done' ),
			array( 'title' => 'Build',     '_skl_week' => 'Wk 4–5', '_skl_state' => 'active' ),
			array( 'title' => 'Launch',    '_skl_week' => 'Wk 6',   '_skl_state' => '' ),
		),
		'skl_hero_tile' => array(
			array( 'title' => 'to launch',         '_skl_count' => '6',   '_skl_suffix' => ' wks', '_skl_display' => '' ),
			array( 'title' => 'transparent price', '_skl_count' => '',    '_skl_suffix' => '',     '_skl_display' => 'Fixed' ),
			array( 'title' => 'Canadian mgmt',     '_skl_count' => '100', '_skl_suffix' => '%',    '_skl_display' => '' ),
		),
		'skl_pillar'    => array(
			array( 'title' => 'Speed without compromise',       '_skl_icon' => 'bolt',  '_skl_desc' => '14-day average delivery, 98% on-time. We move fast without cutting corners.', '_skl_points' => "14-day average delivery\n98% on-time record\nWeekly progress demos" ),
			array( 'title' => 'Radical transparency',           '_skl_icon' => 'eye',   '_skl_desc' => 'Fixed, published pricing. No hidden costs and no surprise invoices — ever.', '_skl_points' => "Published fixed pricing\nNo hidden fees, ever\nYou approve every scope" ),
			array( 'title' => 'Canadian management, global talent', '_skl_icon' => 'globe', '_skl_desc' => 'Toronto strategy meets expert global delivery: premium quality at a competitive cost.', '_skl_points' => "Toronto-based project leads\nExpert global engineering\nOne accountable team" ),
			array( 'title' => 'You own everything',              '_skl_icon' => 'key',   '_skl_desc' => '100% source-code ownership and a real post-launch partnership. It’s yours.', '_skl_points' => "100% source-code handover\nFull IP rights\n1-month post-launch support" ),
		),
		'skl_visa_feature' => array(
			array( 'title' => 'Working Product', '_skl_icon' => 'product', '_skl_sub' => 'Prove business viability' ),
			array( 'title' => 'Meet Deadlines',  '_skl_icon' => 'clock',   '_skl_sub' => 'Timeline that fits your visa process' ),
			array( 'title' => 'Budget Friendly', '_skl_icon' => 'budget',  '_skl_sub' => 'Pricing that respects your runway' ),
		),
		'skl_work' => array(
			array( 'title' => 'AI Farming', '_skl_industry' => 'AgriTech', '_skl_outcome' => 'In funding discussions', '_skl_tags' => "Platform\nMarketplace", '_skl_img' => 'agritech', '_skl_link' => '/portfolio' ),
			array( 'title' => 'Get Takaful', '_skl_industry' => 'FinTech / Blockchain', '_skl_outcome' => '#1 SEO rankings · weekly inquiries', '_skl_tags' => "Shariah-compliant\nBlockchain", '_skl_img' => 'fintech', '_skl_link' => '/portfolio' ),
			array( 'title' => 'KindredCare', '_skl_industry' => 'Healthcare', '_skl_outcome' => 'MVP complete · onboarding live', '_skl_tags' => "AI Matching\nMobile", '_skl_img' => 'care', '_skl_link' => '/portfolio' ),
			array( 'title' => 'Agile Sourcing', '_skl_industry' => 'Fashion', '_skl_outcome' => 'Launched', '_skl_tags' => "Design Validation\nWeb App", '_skl_img' => 'fashion', '_skl_link' => '/portfolio' ),
			array( 'title' => 'GAinData', '_skl_industry' => 'SaaS', '_skl_outcome' => 'MVP launched', '_skl_tags' => "AI\nData Intelligence", '_skl_img' => 'data', '_skl_link' => '/portfolio' ),
		),
		'skl_process' => array(
			array( 'title' => 'Discovery', '_skl_icon' => 'discovery', '_skl_desc' => 'A free 30-min consultation to clarify your vision and scope.', '_skl_duration' => 'Day 1' ),
			array( 'title' => 'Design', '_skl_icon' => 'design', '_skl_desc' => 'UI/UX design plus the technical architecture.', '_skl_duration' => 'Week 1' ),
			array( 'title' => 'Build', '_skl_icon' => 'build', '_skl_desc' => 'Agile sprints with regular progress demos.', '_skl_duration' => 'Weeks 2–5' ),
			array( 'title' => 'Launch', '_skl_icon' => 'launch', '_skl_desc' => 'QA, deployment, and a month of post-launch support.', '_skl_duration' => 'Week 6' ),
		),
		'skl_location' => array(
			array( 'title' => 'Toronto, Canada',    '_skl_role' => 'Strategy & client relationships', '_skl_region' => 'canada' ),
			array( 'title' => 'Dhaka, Bangladesh',  '_skl_role' => 'Engineering & design',            '_skl_region' => 'asia' ),
			array( 'title' => 'Global talent',      '_skl_role' => 'Design, marketing & delivery',    '_skl_region' => 'global' ),
		),
		'skl_faq' => array(
			array( 'title' => 'How can you deliver an MVP in 2–4 weeks?', '_skl_answer' => 'Ruthless scope and a proven stack. We build one sharp value loop first — the core feature, done well — and defer everything non-essential. A fixed scope agreed up front plus daily, visible progress keeps it on time.' ),
			array( 'title' => 'What does “Canadian management, global talent” mean for me?', '_skl_answer' => 'Toronto-based project leads own strategy, communication, and accountability, while an expert global team handles engineering and design. You get premium quality and clear communication — without the premium markup.' ),
			array( 'title' => 'Do I own the source code?', '_skl_answer' => 'Yes — 100%. You receive full source-code ownership and IP rights on delivery. No lock-in and no licensing games.' ),
			array( 'title' => 'What’s included in each pricing tier — are there hidden costs?', '_skl_answer' => 'Pricing is fixed and published. Each tier lists exactly what’s included, and you approve the scope before we start. No surprise invoices, ever.' ),
			array( 'title' => 'Can you help with a Canada Startup Visa application?', '_skl_answer' => 'Absolutely — it’s our specialty. We build working MVPs that prove business viability to designated organizations, on a timeline that fits your visa process.' ),
			array( 'title' => 'What happens after launch?', '_skl_answer' => 'Every project includes a post-launch support window (2 weeks to 3 months, depending on tier). We’re a partner, not a vendor — many clients stay on for the next build.' ),
			array( 'title' => 'Which technologies do you use?', '_skl_answer' => 'A modern, consistent stack — Next.js, React, Laravel, React Native, Flutter, PostgreSQL, WordPress — with AI (Gemini, OpenAI, Claude) woven into product workflows where it adds real value.' ),
		),
		'skl_tech' => array(
			array( 'title' => 'Next.js',       '_skl_key' => 'nextjs',     '_skl_category' => 'frontend' ),
			array( 'title' => 'React',         '_skl_key' => 'react',      '_skl_category' => 'frontend' ),
			array( 'title' => 'TypeScript',    '_skl_key' => 'typescript', '_skl_category' => 'frontend' ),
			array( 'title' => 'Tailwind CSS',  '_skl_key' => 'tailwind',   '_skl_category' => 'frontend' ),
			array( 'title' => 'Laravel',       '_skl_key' => 'laravel',    '_skl_category' => 'backend' ),
			array( 'title' => 'Node.js',       '_skl_key' => 'nodejs',     '_skl_category' => 'backend' ),
			array( 'title' => 'React Native',  '_skl_key' => 'react',      '_skl_category' => 'mobile' ),
			array( 'title' => 'Flutter',       '_skl_key' => 'flutter',    '_skl_category' => 'mobile' ),
			array( 'title' => 'PostgreSQL',    '_skl_key' => 'postgresql', '_skl_category' => 'database' ),
			array( 'title' => 'WordPress',     '_skl_key' => 'wordpress',  '_skl_category' => 'cms' ),
			array( 'title' => 'Google Gemini', '_skl_key' => 'gemini',     '_skl_category' => 'ai' ),
			array( 'title' => 'OpenAI',        '_skl_key' => 'openai',     '_skl_category' => 'ai' ),
			array( 'title' => 'Claude',        '_skl_key' => 'claude',     '_skl_category' => 'ai' ),
		),
		'skl_pricing' => array(
			array( 'title' => 'Starter MVP',  '_skl_prefix' => 'Starting at', '_skl_price' => '$5,000',   '_skl_currency' => 'CAD', '_skl_period' => '2 weeks',  '_skl_popular' => '',    '_skl_badge' => '',        '_skl_cta_text' => 'Book a Free Consultation', '_skl_cta_link' => 'https://calendly.com/sklentr', '_skl_features' => "1-3 core features\nTemplate-based design\nBasic SEO setup\n2 weeks support" ),
			array( 'title' => 'Growth MVP',   '_skl_prefix' => 'Starting at', '_skl_price' => '$15,000',  '_skl_currency' => 'CAD', '_skl_period' => '4 weeks',  '_skl_popular' => 'yes', '_skl_badge' => 'Popular', '_skl_cta_text' => 'Book a Free Consultation', '_skl_cta_link' => 'https://calendly.com/sklentr', '_skl_features' => "5-7 features\nCustom UI design\nFull SEO included\n1 month support" ),
			array( 'title' => 'Full-Service', '_skl_prefix' => 'Starting at', '_skl_price' => '$30,000+', '_skl_currency' => 'CAD', '_skl_period' => '8+ weeks', '_skl_popular' => '',    '_skl_badge' => '',        '_skl_cta_text' => 'Book a Free Consultation', '_skl_cta_link' => 'https://calendly.com/sklentr', '_skl_features' => "Full product build\nCustom UI/UX\nMarketing included\n3 months support" ),
		),
	);

	foreach ( $seed as $slug => $rows ) {
		$existing = get_posts( array( 'post_type' => $slug, 'numberposts' => 1, 'post_status' => 'any', 'fields' => 'ids' ) );
		if ( ! empty( $existing ) ) {
			continue; // already has content — don't duplicate.
		}
		$order = 0;
		foreach ( $rows as $row ) {
			$title = $row['title'];
			unset( $row['title'] );
			$id = wp_insert_post( array(
				'post_type'   => $slug,
				'post_status' => 'publish',
				'post_title'  => $title,
				'menu_order'  => $order++,
			) );
			if ( $id && ! is_wp_error( $id ) ) {
				foreach ( $row as $mk => $mv ) {
					update_post_meta( $id, $mk, $mv );
				}
			}
		}
	}

	// Seed section option text (only fills empty keys).
	$opts     = get_option( 'sklentr_settings', array() );
	$defaults = array(
		// Services.
		'services_eyebrow'      => 'Services',
		'services_title'        => 'Everything you need to',
		'services_title_accent' => 'launch.',
		'services_intro'        => '',
		'services_cta_text'     => 'See Our Services',
		'services_cta_link'     => '/services',
		// Hero.
		'hero_eyebrow'        => 'Toronto-based MVP Studio',
		'hero_title_main'     => 'Launch-ready MVPs',
		'hero_title_highlight'=> 'in weeks,',
		'hero_title_strike'   => 'not months.',
		'hero_sub'            => 'We build MVPs that get you funded, validated, and to market — fast.',
		'hero_cta1_text'      => 'Book a Free Consultation',
		'hero_cta1_link'      => 'https://calendly.com/sklentr',
		'hero_cta2_text'      => 'See our work',
		'hero_cta2_link'      => '#work',
		'hero_note'           => 'Canadian expertise. Competitive pricing. No excuses.',
		'hero_chip_1'         => 'Figma',
		'hero_chip_2'         => 'React',
		'hero_chip_3'         => 'Funded ✓',
		'hero_chip_4'         => 'Ship →',
		'hero_panel_title'    => 'MVP Launch Plan',
		'hero_badge_loading'  => 'Building…',
		'hero_badge_ok'       => 'On track',
		// Trust.
		'trust_heading'       => 'Sklentr by the numbers',
		'trust_proof_label'   => 'Trusted by founders building',
		// Why Sklentr.
		'pillar_eyebrow'      => 'Why Sklentr',
		'pillar_title'        => 'Built different — on purpose.',
		'pillar_intro'        => 'Four reasons founders trust us with their first product — and their next one.',
		'pillar_cta_text'     => 'Meet the team',
		'pillar_cta_link'     => '/about',
		// Problem.
		'problem_eyebrow'     => 'Why founders come to us',
		'problem_title'       => 'Building an MVP shouldn’t take months — or cost a fortune.',
		'problem_intro'       => 'Most founders reach us already burned by an agency. Here’s what usually goes wrong — and how we do it differently.',
		'problem_cta_text'    => 'See how we work',
		'problem_cta_link'    => '#how-we-work',
		// Startup Visa (content mirrors the live sklentr.com section).
		'visa_eyebrow'      => 'For Canada Startup Visa Applicants',
		'visa_title'        => 'Need an MVP for your Startup Visa?',
		'visa_title_accent' => 'We’ve got you.',
		'visa_body'         => 'Tight deadline. Limited budget. High stakes. We help startup visa applicants build working products that prove business viability — on time and on budget.',
		'visa_cta_text'     => 'Learn More',
		'visa_cta_link'     => '/startup-visa',
		// Featured Work.
		'work_eyebrow'  => 'Featured Work',
		'work_title'    => 'Real products, real outcomes.',
		'work_intro'    => 'A few of the MVPs we’ve shipped for founders — live, funded, and in market.',
		'work_cta_text' => 'View All Work',
		'work_cta_link' => '/portfolio',
		// How We Work.
		'process_eyebrow'  => 'How We Work',
		'process_title'    => 'A clear path from idea to launch.',
		'process_intro'    => 'No black boxes — four simple steps, predictable from day one.',
		'process_cta_text' => 'Start With a Free Discovery Call',
		'process_cta_link' => '#contact',
		// Transparent Pricing (content mirrors the live sklentr.com section).
		'pricing_eyebrow'      => 'Pricing',
		'pricing_title'        => 'Transparent pricing.',
		'pricing_title_accent' => 'No surprises.',
		'pricing_intro'        => '',
		'pricing_note'         => 'Every project starts with a free 30-minute consultation. We’ll scope your idea and recommend the right package.',
		'pricing_cta_text'     => 'View Full Pricing',
		'pricing_cta_link'     => '/pricing',
		// Technology & AI.
		'tech_eyebrow'      => 'Technology & AI',
		'tech_title'        => 'Modern stack.',
		'tech_title_accent' => 'AI-native builds.',
		'tech_intro'        => 'A consistent, modern toolchain — built to scale, and shipped fast.',
		'tech_ai_title'     => 'AI-native, not bolted on.',
		'tech_ai_note'      => 'We weave Gemini, OpenAI, and Claude into real product workflows — powering features that matter, not gimmicks.',
		'tech_cta_text'     => 'Explore Our Services',
		'tech_cta_link'     => '/services',
		// About / Team.
		'about_eyebrow'      => 'About Sklentr',
		'about_title'        => 'Since 2023, we’ve built launch-ready MVPs',
		'about_title_accent' => 'founders actually own.',
		'about_story'        => 'Founded out of frustration with slow, overpriced agencies, Sklentr pairs Toronto-based project leadership with an expert global team — premium quality, fast, without the premium markup.',
		'founder_name'       => 'Rishad Wahid',
		'founder_role'       => 'Founder & CEO, Sklentr',
		'founder_quote'      => 'We started Sklentr to give founders what agencies wouldn’t: speed, transparency, and code they actually own.',
		'founder_photo'      => '',
		'about_hl_num'       => '01',
		'about_hl_title'     => 'Founder-led, start to finish',
		'about_hl_desc'      => 'Toronto strategy paired with an expert global team — one accountable partner from first call to launch.',
		'about_cta_text'     => 'Meet the Full Team',
		'about_cta_link'     => '/about',
		'about_follow_label' => 'Follow Us',
		'social_linkedin'    => 'https://www.linkedin.com/company/sklentr/',
		'social_x'           => '',
		'social_facebook'    => '',
		'social_instagram'   => '',
		// Insights / Blog.
		'insights_eyebrow'      => 'Insights',
		'insights_title'        => 'Playbooks for founders,',
		'insights_title_accent' => 'not fluff.',
		'insights_intro'        => 'Practical takes on validation, MVPs, and the Canada Startup Visa — from the team shipping them.',
		'insights_cta_text'     => 'Read All Insights',
		'insights_cta_link'     => '/blog/',
		'news_title'            => 'Get the founder’s playbook',
		'news_text'             => 'Practical MVP and Startup Visa insights — once a month, no spam.',
		'news_placeholder'      => 'you@company.com',
		'news_button'           => 'Subscribe',
		'news_success'          => 'Thanks! Check your inbox to confirm.',
		// FAQ.
		'faq_eyebrow'       => 'FAQ',
		'faq_title'         => 'Questions?',
		'faq_title_accent'  => 'Answered.',
		'faq_intro'         => 'The things founders ask us most — before the first call.',
		'faq_help_title'    => 'Still have questions?',
		'faq_help_text'     => 'Book a free 30-minute call — no pressure, just a clear plan and timeline.',
		'faq_help_cta_text' => 'Book a Free Call',
		'faq_help_cta_link' => '#contact',
		// Final CTA band.
		'cta_eyebrow'      => 'Let’s build',
		'cta_title'        => 'Ready to launch your MVP',
		'cta_title_accent' => 'in weeks?',
		'cta_subtitle'     => 'Book a free 30-minute consultation. No pressure, no jargon — just a clear plan and timeline.',
		'cta_points'       => "Free discovery call\nFixed pricing\nYou own the code",
		'cta_primary_text' => 'Book a Free Consultation',
		'cta_primary_link' => 'https://calendly.com/sklentr',
		'cta_email'        => 'info@sklentr.com',
		'cta_phone'        => '+1 647-997-0557',
		'cta_whatsapp'     => 'https://wa.me/16479970557',
	);
	foreach ( $defaults as $k => $v ) {
		if ( ! isset( $opts[ $k ] ) || '' === $opts[ $k ] ) {
			$opts[ $k ] = $v;
		}
	}
	update_option( 'sklentr_settings', $opts );

	update_option( 'sklentr_seeded_v18', 1 );
}, 20 );
