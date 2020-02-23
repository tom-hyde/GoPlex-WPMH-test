<?php
/**
 * Uncode Core deprecated functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Wrapper for deprecated functions.
 *
 * @param string $function Function used.
 * @param string $version Version the message was added in.
 * @param string $replacement Replacement for the called function.
 */
function uncode_core_deprecated_function( $function, $version, $replacement = null ) {
	if ( WP_DEBUG && apply_filters( 'deprecated_function_trigger_error', true ) ) {
		if ( ! headers_sent() && did_action( 'wp_loaded' ) ) {
			if ( ! is_null( $replacement ) ) {
				trigger_error( sprintf( wp_kses_post( __( '%1$s is <strong>deprecated</strong> since version %2$s! Use %3$s instead.', 'uncode-core' ) ), $function, $version, $replacement ) );
			} else {
				trigger_error( sprintf( wp_kses_post( __('%1$s is <strong>deprecated</strong> since version %2$s with no alternative available.', 'uncode-core' ) ), $function, $version ) );
			}
		}
	}
}

/**
 * @deprecated 2.0
 */
function uncode_replace_inner_single_width() {
	uncode_core_deprecated_function( 'uncode_replace_inner_single_width', '2.0', 'uncode_vc_replace_inner_single_width' );
}
