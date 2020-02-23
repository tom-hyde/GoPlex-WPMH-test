<?php
/**
 * Uncode deprecated functions.
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
function uncode_deprecated_function( $function, $version, $replacement = null ) {
	if ( WP_DEBUG && apply_filters( 'deprecated_function_trigger_error', true ) ) {
		if ( ! headers_sent() && did_action( 'wp_loaded' ) ) {
			if ( ! is_null( $replacement ) ) {
				trigger_error( sprintf( wp_kses_post( __( '<strong>Make sure all your plugins are up to date:</strong> %1$s is <strong>deprecated</strong> since version %2$s! Use %3$s instead.', 'uncode' ) ), $function, $version, $replacement ) );
			} else {
				trigger_error( sprintf( wp_kses_post( __('<strong>Make sure all your plugins are up to date:</strong> %1$s is <strong>deprecated</strong> since version %2$s with no alternative available.', 'uncode' ) ), $function, $version ) );
			}
		}
	}
}

/**
 * @deprecated 2.0
 */
function uncode_remove_wpautop() {
	uncode_deprecated_function( 'uncode_remove_wpautop', '2.0', 'uncode_remove_p_tag' );
}

/**
 * @deprecated 2.0
 */
function big_rand() {
	uncode_deprecated_function( 'big_rand', '2.0', 'uncode_big_rand' );
}

/**
 * @deprecated 2.0
 */
function uncode_id() {
	uncode_deprecated_function( 'uncode_id', '2.0', 'ot_options_id' );
}
