<?php
/**
 * UpdraftPlus support
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Check if UpdraftPlus is active
if ( ! class_exists( 'UpdraftPlus' ) ) {
	return;
}

if ( ! class_exists( 'Uncode_UpdraftPlus_Functions' ) ) :

/**
 * Uncode_UpdraftPlus_Functions Class
 */
class Uncode_UpdraftPlus_Functions {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Filters
		add_action( 'updraftplus_restored_db', array( $this, 'regenerate_css_after_restore' ) );
	}

	/**
	 * After the DB restore, regenerate the CSS
	 */
	public function regenerate_css_after_restore() {
		uncode_define_global_colors();
		uncode_create_dynamic_css();
	}
}

endif;

return new Uncode_UpdraftPlus_Functions();
