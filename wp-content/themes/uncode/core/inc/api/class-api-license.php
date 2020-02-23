<?php
/**
 * API License Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Uncode_API_License' ) ) :

/**
 * Uncode_API_License Class
 */
class Uncode_API_License {

	/**
	 * Endpoint.
	 */
	private $endpoint = 'https://api.undsgn.com/uncode-license/';

	/**
	 * Get things going
	 */
	function __construct() {
		// Do nothing
	}

	/**
	 * Add new license.
	 */
	public function save_license( $purchase_code ) {
		// Make the request
		$response = wp_remote_post( $this->endpoint . 'save-license.php', array(
			'body'       => array(
				'purchase_code' => $purchase_code
			),
			'timeout'    => 45
		) );

		// Check response
		$response_code = uncode_api_check_response( $response );

		return $response_code;
	}
}

endif;
