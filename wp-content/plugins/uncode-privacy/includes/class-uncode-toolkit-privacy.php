<?php
/**
 * Main Class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Uncode_Toolkit_Privacy' ) ) :

/**
 * Uncode_Toolkit_Privacy Class
 */
class Uncode_Toolkit_Privacy {

	/**
	 * The unique identifier of this plugin.
	 */
	protected $plugin_name;

	/**
	 * Plugin's version.
	 */
	protected $version;

	/**
	 * Get things going
	 */
	public function __construct() {
		if ( defined( 'UNCODE_TOOLKIT_PRIVACY_VERSION' ) ) {
			$this->version = UNCODE_TOOLKIT_PRIVACY_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		$this->plugin_name = 'uncode-privacy';

		$this->load_dependencies();
		$this->admin_hooks();
		$this->public_hooks();

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) OR ( defined( 'DOING_CRON' ) && DOING_CRON ) OR ( defined( 'DOING_AJAX' ) && DOING_AJAX ) OR ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) ) {
			return;
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 */
	private function load_dependencies() {
		/**
		 * Admin related fucntions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-uncode-toolkit-privacy-admin.php';

		/**
		 * Frontend related fucntions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-uncode-toolkit-privacy-public.php';
	}

	/**
	 * Admin related hooks
	 */
	private function admin_hooks() {
		$plugin_admin   = new Uncode_Toolkit_Privacy_Admin( $this->get_plugin_name(), $this->get_version() );

		add_action( 'plugins_loaded', array( $this, 'set_locale' ) );
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $plugin_admin, 'add_menu' ), 89 );
		add_action( 'admin_init', array( $plugin_admin, 'register_settings' ) );
	}

	/**
	 * Frontend related hooks
	 */
	private function public_hooks() {
		$plugin_public   = new Uncode_Toolkit_Privacy_Public( $this->get_plugin_name(), $this->get_version() );

		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_scripts' ) );
		add_action( 'init', array( $plugin_public, 'set_plugin_cookies' ) );
		add_action( 'wp_footer', array( $plugin_public, 'overlay' ) );
		add_action( 'wp_footer', array( $plugin_public, 'privacy_bar' ) );
		add_action( 'wp_footer', array( $plugin_public, 'privacy_preferences_modal' ) );
		add_action( 'admin_post_uncode_privacy_update_privacy_preferences', array( $plugin_public, 'update_privacy_preferences' ) );
		add_action( 'admin_post_nopriv_uncode_privacy_update_privacy_preferences', array( $plugin_public, 'update_privacy_preferences' ) );
	}

	/**
	 * Load Text Domain
	 */
	public function set_locale() {
		load_plugin_textdomain( 'uncode-privacy', false, plugin_dir_url( dirname( __FILE__ ) ) . 'languages/' );
	}

	/**
	 * The name of the plugin
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}

endif;
