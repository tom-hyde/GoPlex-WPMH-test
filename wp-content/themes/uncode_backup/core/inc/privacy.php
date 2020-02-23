<?php
/**
 * Privacy related functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'uncode_privacy_has_consent' ) ) :

	/**
	 * Function that checks if we have
	 * the consent for a specific option
	 */
	function uncode_privacy_has_consent( $consent_id ) {
		// Developers can hook into here
		$has_consent = apply_filters( 'uncode_privacy_has_consent', true, $consent_id );

		return $has_consent;
	}

endif;

if ( ! function_exists( 'uncode_privacy_allow_content' ) ) :

	/**
	 * Function that allows to display content
	 * after checking for its consent
	 */
	function uncode_privacy_allow_content( $consent_id ) {

		$consent_types = is_array( get_option( 'uncode_privacy_consent_types' ) ) ? get_option( 'uncode_privacy_consent_types' ) : array();

		if ( ( !array_key_exists( $consent_id, $consent_types ) ) || ! uncode_is_uncode_privacy_active() ) {
			return 'none';
		}

		$allow = uncode_privacy_has_consent( $consent_id );

		return $allow;
	}

endif;

if ( ! function_exists( 'uncode_privacy_check_needed' ) ) :

	/**
	 * Function that checks
	 * if consent is checked
	 */
	function uncode_privacy_check_needed( $consent_id ) {

		if ( ! uncode_is_uncode_privacy_active() ) {
			return false;
		}

		$consent_types = is_array( get_option( 'uncode_privacy_consent_types' ) ) ? get_option( 'uncode_privacy_consent_types' ) : array();
		$check = array_key_exists( $consent_id, $consent_types ) && isset($consent_types[$consent_id]) && !$consent_types[$consent_id]['required'];

		if ( apply_filters( 'uncode_stop_consent', false, $consent_id ) ) {
			return false;
		}

		if ( $check ) {
			apply_filters( 'uncode_checking_consent', true, $consent_id );
		}

		return $check;
	}

endif;
