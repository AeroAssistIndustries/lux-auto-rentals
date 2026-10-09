<?php
/**
 * Serves the Meridian storefront (app.html) for every front-end request.
 * The store uses hash routes (#/shop, #/watch/...), so WordPress only ever
 * needs to deliver this one page.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$meridian_file = get_template_directory() . '/app.html';
if ( ! is_readable( $meridian_file ) ) {
	wp_die( esc_html__( 'The Meridian theme is missing app.html. Re-upload the theme zip.', 'meridian-chrono' ) );
}

$meridian_html = file_get_contents( $meridian_file );

// Point relative logo paths at the theme folder.
$meridian_base = trailingslashit( get_template_directory_uri() );
$meridian_html = str_replace(
	array( "'logos/", '"logos/' ),
	array( "'" . $meridian_base . 'logos/', '"' . $meridian_base . 'logos/' ),
	$meridian_html
);

// Settings from Appearance > Customize > Meridian site.
$meridian_config = array(
	'formsLive'       => (bool) get_theme_mod( 'meridian_forms_live', false ),
	'inquiryEndpoint' => esc_url_raw( rest_url( 'meridian/v1/inquiry' ) ),
	'demoMode'        => (bool) get_theme_mod( 'meridian_demo_mode', true ),
);
$meridian_inject = '<script>window.MERIDIAN_CONFIG=' . wp_json_encode( $meridian_config ) . ";</script>\n";

$meridian_marker = "<script>\n'use strict';";
$meridian_pos    = strpos( $meridian_html, $meridian_marker );
if ( false !== $meridian_pos ) {
	$meridian_html = substr_replace( $meridian_html, $meridian_inject, $meridian_pos, 0 );
} else {
	$meridian_html = str_replace( '</head>', $meridian_inject . '</head>', $meridian_html );
}

// The storefront is a complete, static HTML document built by the theme author.
echo $meridian_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
