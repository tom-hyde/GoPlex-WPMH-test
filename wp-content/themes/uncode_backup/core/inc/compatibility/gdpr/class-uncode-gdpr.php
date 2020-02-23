<?php
/**
 * Uncode Privacy support
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Return early if Uncode Privacy is not active
if ( ! uncode_is_uncode_privacy_active() ) {
	return;
}

if ( ! class_exists( 'Uncode_GDPR_Support' ) ) :

/**
 * Uncode_GDPR_Support Class
 */
class Uncode_GDPR_Support {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Filters
		add_filter( 'uncode_privacy_has_consent', array( $this, 'privacy_gdpr_consent' ), 10, 2 );
	}

	/**
	 * Filter each consent individually
	 */
	public function privacy_gdpr_consent( $ret, $consent_id ) {
		if ( ! function_exists( 'uncode_toolkit_privacy_has_consent' ) ) {
			return true;
		}

		$consent_types = is_array( get_option( 'uncode_privacy_consent_types' ) ) ? get_option( 'uncode_privacy_consent_types' ) : array();

		if ( ! count( $consent_types ) > 0 ) {
			return true;
		}

		// Check consent
		if ( array_key_exists( $consent_id, $consent_types ) ) {

			return uncode_toolkit_privacy_has_consent( $consent_id ) || $consent_types[$consent_id]['required'] ? true : false;
		}

		return true;
	}
}

endif;

return new Uncode_GDPR_Support();
