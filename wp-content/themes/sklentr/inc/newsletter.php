<?php
/**
 * Sklentr — newsletter opt-in storage.
 *
 * The footer form (footer-news__form) used to be display-only: newsletter.js
 * called preventDefault() and swapped in the success message without sending
 * anything anywhere. This wires it to a real endpoint that stores the address.
 *
 * Only the email address is stored — it is kept as the post_title of a private
 * "Subscribers" CPT. No name, IP, or user-agent is recorded.
 *
 * @package Sklentr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const SKL_SUBSCRIBER_CPT = 'skl_subscriber';

/**
 * Register the Subscribers CPT (private — admin-only listing, no front end).
 */
add_action( 'init', function () {
	register_post_type(
		SKL_SUBSCRIBER_CPT,
		array(
			'labels'           => array(
				'name'          => __( 'Subscribers', 'sklentr' ),
				'singular_name' => __( 'Subscriber', 'sklentr' ),
				'menu_name'     => __( 'Subscribers', 'sklentr' ),
				'search_items'  => __( 'Search subscribers', 'sklentr' ),
				'not_found'     => __( 'No subscribers yet.', 'sklentr' ),
			),
			'public'           => false,
			'show_ui'          => true,
			'show_in_menu'     => 'sklentr-settings', // Sits under the Sklentr menu.
			'show_in_rest'     => false,
			'has_archive'      => false,
			'rewrite'          => false,
			'query_var'        => false,
			'supports'         => array( 'title' ),
			'capability_type'  => 'post',
			'map_meta_cap'     => true,
			'capabilities'     => array( 'create_posts' => 'do_not_allow' ), // Sign-ups come from the form.
			'delete_with_user' => false,
		)
	);
} );

/**
 * Label the title column "Email" and add a "Subscribed" date column.
 */
add_filter( 'manage_' . SKL_SUBSCRIBER_CPT . '_posts_columns', function ( $cols ) {
	return array(
		'cb'         => isset( $cols['cb'] ) ? $cols['cb'] : '',
		'title'      => __( 'Email', 'sklentr' ),
		'subscribed' => __( 'Subscribed', 'sklentr' ),
	);
} );

add_action( 'manage_' . SKL_SUBSCRIBER_CPT . '_posts_custom_column', function ( $col, $post_id ) {
	if ( 'subscribed' === $col ) {
		echo esc_html( get_the_date( 'Y-m-d H:i', $post_id ) );
	}
}, 10, 2 );

/**
 * Hand the front end the endpoint + a nonce.
 *
 * @return array
 */
function sklentr_newsletter_js_data() {
	return array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'skl_subscribe' ),
		'i18n'    => array(
			'invalid' => __( 'Please enter a valid email address.', 'sklentr' ),
			'error'   => __( 'Something went wrong. Please try again.', 'sklentr' ),
		),
	);
}

/**
 * Store a submitted address.
 *
 * Responds with JSON. Re-submitting an address that already exists is treated
 * as success — the visitor is subscribed either way, and it avoids leaking
 * which addresses are on the list.
 */
function sklentr_handle_subscribe() {
	if ( ! check_ajax_referer( 'skl_subscribe', 'nonce', false ) ) {
		wp_send_json_error( array( 'message' => __( 'Your session expired. Please reload the page.', 'sklentr' ) ), 403 );
	}

	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

	if ( ! $email || ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'sklentr' ) ), 400 );
	}

	$existing = get_posts(
		array(
			'post_type'              => SKL_SUBSCRIBER_CPT,
			'post_status'            => 'any',
			'title'                  => $email,
			'numberposts'            => 1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	if ( ! empty( $existing ) ) {
		wp_send_json_success( array( 'message' => __( 'You are already subscribed.', 'sklentr' ) ) );
	}

	$id = wp_insert_post(
		array(
			'post_type'   => SKL_SUBSCRIBER_CPT,
			'post_status' => 'publish',
			'post_title'  => $email,
		),
		true
	);

	if ( is_wp_error( $id ) || ! $id ) {
		wp_send_json_error( array( 'message' => __( 'Something went wrong. Please try again.', 'sklentr' ) ), 500 );
	}

	/**
	 * Fires once an address has been stored — hook an ESP sync here.
	 *
	 * @param string $email The stored address.
	 * @param int    $id    Subscriber post ID.
	 */
	do_action( 'sklentr_newsletter_subscribed', $email, $id );

	wp_send_json_success( array( 'message' => __( 'Thanks — you are on the list.', 'sklentr' ) ) );
}

add_action( 'wp_ajax_skl_subscribe', 'sklentr_handle_subscribe' );
add_action( 'wp_ajax_nopriv_skl_subscribe', 'sklentr_handle_subscribe' );
