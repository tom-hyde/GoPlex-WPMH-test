<?php
/**
 * Helper Functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Gets an array of consent types.
 */
function uncode_toolkit_privacy_get_consent_types() {
	$consent_types = is_array( get_option( 'uncode_privacy_consent_types' ) ) ? get_option( 'uncode_privacy_consent_types' ) : array();

	return $consent_types;
}

/**
 * Checks if a user gave consent.
 */
function uncode_toolkit_privacy_has_consent( $consent ) {
	$is_consent_on_by_default = uncode_toolkit_privacy_is_consent_on_by_default( $consent );

	if ( is_user_logged_in() ) {
		$user     = wp_get_current_user();
		$consents = (array) get_user_meta( $user->ID, 'uncode_privacy_consents' );
	} else if ( isset( $_COOKIE[ 'uncode_privacy' ][ 'consent_types' ] ) && ! empty( $_COOKIE[ 'uncode_privacy' ][ 'consent_types' ] ) ) {
		$consents = array_map( 'sanitize_text_field', (array) json_decode( wp_unslash( $_COOKIE[ 'uncode_privacy' ][ 'consent_types' ] ) ) );
	}

	if ( isset( $consents ) && ! empty( $consents ) ) {
		// Handle default enabled consents. Consent is disabled only if is set to off
		// (ie. we have the record 'consent_id-off'). Otherwise is always enabled.
		if ( $is_consent_on_by_default && ! in_array( $consent . '-off', $consents ) ) {
			return true;
		}

		if ( in_array( $consent, $consents ) ) {
			return true;
		}

	} else if ( $is_consent_on_by_default ) {
		// Empty consents but consent enabled by default = allowed
		return true;
	}

	return false;
}

/**
 * Create consent shortcode.
 */
function uncode_toolkit_privacy_consent_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'id'    => '',
		'logic' => 'include',
	), $atts, 'uncode_privacy_consent' );

	if ( ! function_exists( 'uncode_privacy_check_needed' ) || ! function_exists( 'uncode_privacy_has_consent' ) ) {
		return; // ???
	}

	if ( $atts['id'] !== '' && uncode_privacy_check_needed( $atts['id'] ) ) {
		uncode_privacy_check_needed($atts['id']);
		if ( $atts['logic'] == 'include' && ! uncode_privacy_has_consent( $atts['id'] ) ) {
			return;
		} else if ( $atts['logic'] == 'exclude' && uncode_privacy_has_consent( $atts['id'] ) ) {
			return;
		}
	}

	return do_shortcode( $content );
}
add_shortcode( 'uncode_privacy_consent', 'uncode_toolkit_privacy_consent_shortcode' );

/**
 * Create privacy box shortcode.
 */
function uncode_toolkit_privacy_box_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'class'   => '',
		'content' => esc_html__( 'Privacy Preferences', 'uncode-privacy' ),
	), $atts, 'uncode_privacy_box' );

	return '<a href="#" class="gdpr-preferences ' . esc_attr( $atts[ 'class' ] ) . '">' . esc_html( $atts[ 'content' ] ) . '</a>';
}
add_shortcode( 'uncode_privacy_box', 'uncode_toolkit_privacy_box_shortcode' );

/**
 * Checks if a consent is on by default.
 */
function uncode_toolkit_privacy_is_consent_on_by_default( $consent_id ) {
	$consent_types = uncode_toolkit_privacy_get_consent_types();

	if ( array_key_exists( $consent_id, $consent_types ) && $consent_types[ $consent_id ][ 'state' ] ) {
		return true;
	}

	return false;
}
