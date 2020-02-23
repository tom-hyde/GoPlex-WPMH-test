<?php
/**
 * Theme/Plugins Updater Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Uncode_Updater' ) ) :

/**
 * Uncode_Updater Class
 */
class Uncode_Updater {

	/**
	 * Get things going
	 */
	function __construct() {
		// Premium plugins updates
		add_action( 'init', array( $this, 'update_premium_plugins' ) );

		// Theme update
		add_filter( 'pre_set_site_transient_update_themes', array( $this, 'update_theme' ) );
		add_filter( 'pre_set_transient_update_themes', array( $this, 'update_theme' ) );
	}

	/**
	 * Update premium plugins
	 */
	public function update_premium_plugins() {
		// Return early if not in the admin.
		if ( ! is_admin() ) {
			return;
		}

		$premium_plugins = uncode_get_premium_plugins();

		foreach ( $premium_plugins as $slug => $plugin_info ) {
			// The plugin must be installed of course
			if ( ! file_exists( trailingslashit( WP_PLUGIN_DIR ) . $plugin_info[ 'plugin_path' ] ) ) {
				continue;
			}

			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			$plugin_data = get_plugin_data( trailingslashit( WP_PLUGIN_DIR ) . $plugin_info[ 'plugin_path' ] );

			$args = array(
				'plugin_name' => $plugin_info[ 'plugin_name' ],
				'plugin_slug' => $plugin_info[ 'plugin_slug' ],
				'plugin_path' => $plugin_info[ 'plugin_path' ],
				'plugin_url'  => $plugin_info[ 'plugin_url' ],
				'remote_url'  => apply_filters( 'uncode_api_update_remote_json_url' , $plugin_info[ 'remote_url' ], $plugin_info ),
				'version'     => $plugin_data[ 'Version' ],
				'key'         => ''
			);

			$tgm_updater = new TGM_Updater( $args );
		}
	}

	/**
	 * Update theme
	 */
	public function update_theme( $transient ) {
		// Return early if not in the admin.
		if ( ! is_admin() ) {
			return;
		}

		// Check new versions
		$response = wp_remote_post( 'https://api.undsgn.com/downloads/uncode/theme/api.json', array(
			'timeout' => 45
		) );

		// Return early on WP Error
		if ( is_wp_error( $response ) ) {
			return $transient;
		}

		// Get response data
		$response_data = json_decode( wp_remote_retrieve_body( $response ), true );

		// Return early on WP Error
		if ( is_wp_error( $response_data ) ) {
			return $transient;
		}

		// Check JSON content
		if ( ! isset( $response_data[ 'new_version' ] ) || ! isset( $response_data[ 'wp_version' ] ) || ! isset( $response_data[ 'url' ] ) || ! isset( $response_data[ 'package' ] ) ) {
			return $transient;
		}

		global $wp_version;

		// Check WP version
		if ( version_compare( $wp_version, $response_data[ 'wp_version' ], '<' ) ) {
			return $transient;
		}

		// Get theme data
		$theme_data = wp_get_theme( 'uncode' );

		// Check if we have a newer version
		if ( version_compare( $theme_data->get( 'Version' ), $response_data[ 'new_version' ], '<' ) ) {
			$transient->response[ 'uncode' ] = array(
				'theme'       => 'uncode',
				'new_version' => $response_data[ 'new_version' ],
				'url'         => $response_data[ 'url' ],
				'package'     => $response_data[ 'package' ],
			);
		}

		return $transient;
	}
}

endif;

return new Uncode_Updater();
