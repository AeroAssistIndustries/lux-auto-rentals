<?php
/**
 * Meridian Chrono Group theme: settings and the form-delivery endpoint.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'title-tag' );
	}
);

/* ---------- Appearance > Customize > Meridian site ---------- */
add_action(
	'customize_register',
	function ( $wp_customize ) {
		$wp_customize->add_section(
			'meridian_site',
			array(
				'title'       => __( 'Meridian site', 'meridian-chrono' ),
				'priority'    => 30,
				'description' => __( 'Form delivery and sample inventory. Watches, prices and page text are edited in app.html inside the theme folder.', 'meridian-chrono' ),
			)
		);

		$wp_customize->add_setting(
			'meridian_forms_live',
			array(
				'default'           => false,
				'sanitize_callback' => 'rest_sanitize_boolean',
			)
		);
		$wp_customize->add_control(
			'meridian_forms_live',
			array(
				'type'        => 'checkbox',
				'section'     => 'meridian_site',
				'label'       => __( 'Send form submissions by email', 'meridian-chrono' ),
				'description' => __( 'Off: forms show a preview message and nothing is sent.', 'meridian-chrono' ),
			)
		);

		$wp_customize->add_setting(
			'meridian_inquiry_email',
			array(
				'default'           => get_option( 'admin_email' ),
				'sanitize_callback' => 'sanitize_email',
			)
		);
		$wp_customize->add_control(
			'meridian_inquiry_email',
			array(
				'type'    => 'email',
				'section' => 'meridian_site',
				'label'   => __( 'Send submissions to', 'meridian-chrono' ),
			)
		);

		$wp_customize->add_setting(
			'meridian_demo_mode',
			array(
				'default'           => true,
				'sanitize_callback' => 'rest_sanitize_boolean',
			)
		);
		$wp_customize->add_control(
			'meridian_demo_mode',
			array(
				'type'        => 'checkbox',
				'section'     => 'meridian_site',
				'label'       => __( 'Show sample watches', 'meridian-chrono' ),
				'description' => __( 'Turn off once the real inventory is in app.html. Watches marked as samples are then hidden.', 'meridian-chrono' ),
			)
		);
	}
);

/* ---------- POST /wp-json/meridian/v1/inquiry ---------- */
add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'meridian/v1',
			'/inquiry',
			array(
				'methods'             => 'POST',
				'callback'            => 'meridian_handle_inquiry',
				'permission_callback' => '__return_true', // Public form; checks are in the handler.
			)
		);
	}
);

/**
 * Validates a storefront form submission and emails it to Meridian.
 *
 * @param WP_REST_Request $request Incoming request.
 * @return WP_REST_Response|WP_Error
 */
function meridian_handle_inquiry( WP_REST_Request $request ) {
	if ( ! get_theme_mod( 'meridian_forms_live', false ) ) {
		return new WP_Error( 'meridian_off', 'Form delivery is turned off.', array( 'status' => 403 ) );
	}

	// Only accept submissions from this site's own pages.
	$origin = $request->get_header( 'origin' );
	if ( $origin ) {
		$site_host = wp_parse_url( home_url(), PHP_URL_HOST );
		if ( wp_parse_url( $origin, PHP_URL_HOST ) !== $site_host ) {
			return new WP_Error( 'meridian_origin', 'Not accepted.', array( 'status' => 403 ) );
		}
	}

	// Rate limit: 5 submissions per 10 minutes per visitor address.
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$key = 'meridian_rl_' . md5( $ip );
	$hits = (int) get_transient( $key );
	if ( $hits >= 5 ) {
		return new WP_Error( 'meridian_rate', 'Too many requests. Please try again in a few minutes.', array( 'status' => 429 ) );
	}
	set_transient( $key, $hits + 1, 10 * MINUTE_IN_SECONDS );

	$data = $request->get_json_params();
	if ( ! is_array( $data ) ) {
		return new WP_Error( 'meridian_bad', 'Not accepted.', array( 'status' => 400 ) );
	}

	// Hidden spam trap: people never fill this in.
	if ( ! empty( $data['website'] ) ) {
		return rest_ensure_response( array( 'ok' => true ) );
	}

	$raw   = isset( $data['email'] ) && is_scalar( $data['email'] ) ? trim( (string) $data['email'] ) : '';
	$email = sanitize_email( $raw );
	$phone = isset( $data['phone'] ) && is_scalar( $data['phone'] ) ? sanitize_text_field( (string) $data['phone'] ) : '';
	if ( '' !== $raw && ( ! $email || ! is_email( $email ) ) ) {
		return new WP_Error( 'meridian_email', 'Check the email address.', array( 'status' => 400 ) );
	}
	if ( ! $email && ! $phone ) {
		return new WP_Error( 'meridian_contact', 'Add an email or phone number.', array( 'status' => 400 ) );
	}
	if ( empty( $data['consent'] ) ) {
		return new WP_Error( 'meridian_consent', 'Consent is required.', array( 'status' => 400 ) );
	}

	$labels = array(
		'contact'           => 'inquiry',
		'watch-inquiry'     => 'watch inquiry',
		'purchase-request'  => 'purchase request',
		'sell-trade'        => 'sell or trade request',
		'source'            => 'sourcing request',
		'alert'             => 'new-arrivals sign-up',
	);
	$type  = isset( $data['formType'] ) ? sanitize_key( $data['formType'] ) : 'contact';
	$label = isset( $labels[ $type ] ) ? $labels[ $type ] : 'inquiry';
	$name  = isset( $data['name'] ) ? sanitize_text_field( (string) $data['name'] ) : '';

	$lines = array();
	$count = 0;
	foreach ( $data as $field => $value ) {
		if ( in_array( $field, array( 'website', 'formType' ), true ) ) {
			continue;
		}
		if ( ++$count > 40 ) {
			break;
		}
		if ( is_array( $value ) ) {
			$value = implode( ', ', array_map( 'strval', array_filter( $value, 'is_scalar' ) ) );
		}
		if ( ! is_scalar( $value ) ) {
			continue;
		}
		$field = sanitize_key( $field );
		$value = sanitize_textarea_field( (string) $value );
		$value = mb_substr( $value, 0, 2000 );
		if ( '' === $value ) {
			continue;
		}
		$lines[] = $field . ': ' . $value;
	}

	$to      = sanitize_email( get_theme_mod( 'meridian_inquiry_email', get_option( 'admin_email' ) ) );
	$subject = sprintf( '[%s] New %s%s', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ), $label, $name ? ' from ' . $name : '' );
	$body    = 'Sent from ' . home_url( '/' ) . ' on ' . wp_date( 'F j, Y g:i a T' ) . "\n\n" . implode( "\n", $lines ) . "\n";
	$headers = array();
	if ( $email ) {
		$headers[] = 'Reply-To: ' . ( $name ? str_replace( array( "\r", "\n", '<', '>', '"' ), '', $name ) . ' ' : '' ) . '<' . $email . '>';
	}

	if ( ! $to || ! wp_mail( $to, $subject, $body, $headers ) ) {
		return new WP_Error( 'meridian_mail', 'The request could not be sent. Please call or text instead.', array( 'status' => 500 ) );
	}

	return rest_ensure_response( array( 'ok' => true ) );
}
