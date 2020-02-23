<?php
/**
 * VC related functions support
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Uncode_VC_Functions' ) ) :

/**
 * Uncode_VC_Functions Class
 */
class Uncode_VC_Functions {

	/**
	 * Construct.
	 */
	public function __construct() {
		// Disable Gutenberg when VC is active
		add_action( 'uncode_upgraded', array( $this, 'disable_gutenberg' ) );
	}

	/**
	 * Disable Gutenberg when VC is active
	 * Just check the "Disable Gutenberg" option
	 */
	public function disable_gutenberg() {
		global $wp_version;

		if ( ! get_option( 'wpb_js_gutenberg_disable' ) && ! get_option( 'uncode_check_for_vc_gutenberg_disable_option' ) ) {
			update_option( 'wpb_js_gutenberg_disable', true );
		}

		update_option( 'uncode_check_for_vc_gutenberg_disable_option', true );
	}
}

endif;

return new Uncode_VC_Functions();
