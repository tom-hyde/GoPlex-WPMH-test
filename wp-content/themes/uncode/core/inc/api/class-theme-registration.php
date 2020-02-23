<?php
/**
 * Theme Registration Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Uncode_Theme_Registration' ) ) :

/**
 * Uncode_Theme_Registration Class
 */
class Uncode_Theme_Registration {

	/**
	 * Error code.
	 */
	private $error_code = 0;

	/**
	 * Flag for accepted terms (DB).
	 */
	private $terms_option_name = 'uncode_registration_accepted_terms';

	/**
	 * Flag for legacy purchase code check (DB option).
	 */
	private $legacy_check_option = 'uncode_registration_legacy_check';

	/**
	 * Get things going
	 */
	function __construct() {
		// Save purchase code
		add_action( 'wp_ajax_save_purchase_code', array( $this, 'save_purchase_code' ) );

		// Save legacy purchase code
		add_action( 'uncode_upgraded', array( $this, 'save_legacy_purchase_code' ) );

		// Delete purchase code
		add_action( 'wp_loaded', array( $this, 'delete_purchase_code' ) );
	}

	/**
	 * Save the purchase code.
	 */
	public function save_purchase_code() {
		if ( isset( $_POST[ 'uncode_theme_registration_purchase_code' ] ) && wp_verify_nonce( $_POST[ 'uncode_theme_registration_nonce' ], 'uncode-theme-registration-form-nonce' ) ) {

			// Purchase code
			$purchase_code = isset( $_POST[ 'uncode_theme_registration_purchase_code' ] ) ? sanitize_text_field( $_POST[ 'uncode_theme_registration_purchase_code' ] ) : '';

			// Save the license
			$is_valid_purchase_code = $this->save_license( $purchase_code );

			if ( $is_valid_purchase_code ) {
				// Save purchase code
				update_option( uncode_get_purchase_code_option_name(), $purchase_code );

				wp_send_json_success(
					array(
						'message' => esc_html__( 'Purchase Code saved.', 'uncode' )
					)
				);

			} else {
				$message = $this->get_error_message( $this->error_code );

				// Purchase code not saved
				wp_send_json_error(
					array(
						'message' => $message
					)
				);
			}
		}

		// Invalid nonce or data
		wp_send_json_error(
			array(
				'message' => esc_html__( 'Invalid nonce or empty data.', 'uncode' )
			)
		);
	}

	/**
	 * Save license.
	 */
	private function save_license( $purchase_code ) {
		// Return early if the purchase code is empty
		if ( ! $purchase_code  ) {
			$this->error_code = 100;
			return false;
		}

		$uncode_api_license         = new Uncode_API_License();
		$save_license_response_data = $uncode_api_license->save_license( $purchase_code );

		// Check response code
		switch ( $save_license_response_data ) {
			case 1: // New purchase code saved
			case 3: // Same domain (or subdomain)
				return true;
				break;

			default:
				$this->error_code = $save_license_response_data;
				return false;
				break;
		}

		return false;
	}

	/**
	 * Display a specific message based on the error code.
	 * See uncode_api_check_response() for the error codes.
	 */
	private function get_error_message( $code ) {
		switch ( $code ) {
			case 4:
				$message = __( "Your Envato Purchase Code doesn't seem to be valid. Please double-check it, and try again.", 'uncode' );
				break;

			case 5:
				$message = __( 'According to the Envato License Terms, you have exceeded the maximum number of activations permitted for a single Purchase Code. Please purchase an additional license. <a href="https://support.undsgn.com/hc/en-us/articles/360000849998" target="_blank">More info</a>', 'uncode' );
				break;

			case 6:
				$message = __( 'There was a problem contacting the Envato API server for Purchase Code verification. Please try again later, or contact the theme author.', 'uncode' );
				break;

			case 7:
				$message = __( "There is a problem contacting the Undsgn server for the Purchase Code verification. Please disable any firewall service, deactivate any possible server authentication, verify you have an updated cURL module, verify if your host is blocking connections to external domains and make sure to whitelist 'api.undsgn.com'. Please consult <a href=\"https://support.undsgn.com/hc/en-us/articles/115004287985\" target=\"_blank\">this page</a> and if the problem persists, <a href=\"https://support.undsgn.com/hc/en-us/articles/360000836318\" target=\"_blank\"> contact the theme author.</a>", 'uncode' );
				break;

			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 16:
			case 17:
			case 18:
			case 19:
			case 21:
			case 22:
			case 24:
				$message = sprintf( __( 'There was an unexpected problem with your Purchase Code verification. <a href="https://support.undsgn.com/hc/en-us/articles/360000836318" target="_blank">Please contact the theme author.</a> (Error %s)', 'uncode' ), $code );
				break;

			case 15:
				$message = __( "The Envato Purchase Code doesn't seem to be properly formatted. Please double check it and try again.", 'uncode' );
				break;

			case 20:
				$message = __( "The Envato Purchase Code doesn't seem to be associated with an Uncode Theme purchase. Please double check it and try again.", 'uncode' );
				break;

			default:
				$message = '';
				break;
		}

		return wp_kses_post( $message );
	}

	/**
	 * Delete the purchase code.
	 */
	public function delete_purchase_code() {
		if ( isset( $_POST[ 'uncode_deregister_product' ] ) && wp_verify_nonce( $_POST[ 'uncode_deregister_product_nonce' ], 'uncode-deregister-product-nonce' ) ) {

			// Delete it
			delete_option( uncode_get_purchase_code_option_name() );
			delete_option( $this->terms_option_name );
		}
	}

	/**
	 * Try to save the legacy purchase code.
	 */
	public function save_legacy_purchase_code() {
		// Don't save old purchase code if we already have the new one
		if ( uncode_get_purchase_code() ) {
			return;
		}

		// Already tried to save this purchase code
		if ( get_option( $this->legacy_check_option, false ) ) {
			return;
		}

		// Add flag (we try to save the legacy purchase code one time only)
		update_option( $this->legacy_check_option, true );

		// Legacy options
		$envato_token         = get_option( 'uncode_registration_token', false );
		$envato_purchase_code = get_option( 'uncode_registration_purchase_code', false );
		$valid_token          = get_option( 'uncode_registration_valid_token', false );
		$valid_purchase_code  = get_option( 'uncode_registration_valid_purchase_code', false );
		$registration_error   = get_option( 'uncode_registration_error', false ) ? true : false;
		$registration_terms   = get_option( 'uncode_registration_accepted_terms', false ) ? true : false;

		if ( $envato_token && $envato_purchase_code && $valid_token && $valid_purchase_code && ! $registration_error && $registration_terms ) {

			// Save the license
			$is_valid_purchase_code = $this->save_license( $envato_purchase_code );

			if ( $is_valid_purchase_code ) {
				// Save purchase code
				update_option( uncode_get_purchase_code_option_name(), $envato_purchase_code );
			}

			// Delete old legacy options
			delete_option( 'uncode_registration_valid_token' );
			delete_option( 'uncode_registration_token' );
			delete_option( 'uncode_registration_error' );
			delete_option( 'uncode_registration_purchase_code' );
			delete_option( 'uncode_registration_valid_purchase_code' );
			delete_option( 'uncode_registration_purchase_code_not_already_in_use' );
		}

		return;
	}
}

endif;

return new Uncode_Theme_Registration();
