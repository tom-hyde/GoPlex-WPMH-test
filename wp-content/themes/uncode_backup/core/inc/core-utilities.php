<?php
/**
 * Core utilities functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Check if a dependency is active.
 */
function uncode_check_for_dependency( $plugin ) {
	return in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) || uncode_check_for_network_dependency( $plugin );
}

/**
 * Check if a dependency is active on network.
 */
function uncode_check_for_network_dependency( $plugin ) {
	if ( ! is_multisite() ) {
		return false;
	}

	$plugins = get_site_option( 'active_sitewide_plugins');

	if ( isset( $plugins[ $plugin ] ) ) {
		return true;
	}

	return false;
}

/**
 * Get Option.
 *
 * Helper function to return the option value.
 * If no value has been saved, it returns $default.
 *
 * This is a copy of ot_get_option() included in Uncode Core.
 * We need it when Uncode Core is not active.
 */
if ( ! function_exists( 'ot_get_option' ) ) {
	function ot_get_option( $option_id, $default = '' ) {
		/* get the saved options */
		$options = get_option( ot_options_id() );

		/* look for the saved value */
		if ( isset( $options[$option_id] ) && '' != $options[$option_id] ) {

			return ot_wpml_filter( $options, $option_id );
		}

		return $default;
	}
}

/**
 * Theme Options ID
 *
 * This is a copy of ot_options_id() included in Uncode Core.
 * We need it when Uncode Core is not active.
 */
if ( ! function_exists( 'ot_options_id' ) ) {
	function ot_options_id() {
		return apply_filters( 'ot_options_id', 'uncode' );
	}
}

/**
 * Theme Settings ID
 *
 * This is a copy of ot_settings_id() included in Uncode Core.
 * We need it when Uncode Core is not active.
 */
if ( ! function_exists( 'ot_settings_id' ) ) {
	function ot_settings_id() {
		return apply_filters( 'ot_settings_id', 'uncode_settings' );
	}
}

/**
 * Filter the return values through WPML
 *
 * This is a copy of ot_wpml_filter() included in Uncode Core.
 * We need it when Uncode Core is not active.
 */
if ( ! function_exists( 'ot_wpml_filter' ) ) {
	function ot_wpml_filter( $options, $option_id ) {

		// Return translated strings using WMPL
		if ( function_exists('icl_t') ) {

			$settings = get_option( ot_settings_id() );

			if ( isset( $settings['settings'] ) ) {

				foreach( $settings['settings'] as $setting ) {

					// List Item & Slider
					if ( $option_id == $setting['id'] && in_array( $setting['type'], array( 'list-item', 'slider' ) ) ) {

						foreach( $options[$option_id] as $key => $value ) {

							foreach( $value as $ckey => $cvalue ) {

								$id = $option_id . '_' . $ckey . '_' . $key;
								$_string = icl_t( 'Theme Options', $id, $cvalue );

								if ( ! empty( $_string ) ) {

									$options[$option_id][$key][$ckey] = $_string;

								}

							}

						}

					// List Item & Slider
					} else if ( $option_id == $setting['id'] && $setting['type'] == 'social-links' ) {

						foreach( $options[$option_id] as $key => $value ) {

							foreach( $value as $ckey => $cvalue ) {

								$id = $option_id . '_' . $ckey . '_' . $key;
								$_string = icl_t( 'Theme Options', $id, $cvalue );

								if ( ! empty( $_string ) ) {

									$options[$option_id][$key][$ckey] = $_string;

								}

							}

						}

					// All other acceptable option types
					} else if ( $option_id == $setting['id'] && in_array( $setting['type'], apply_filters( 'ot_wpml_option_types', array( 'text', 'textarea', 'textarea-simple' ) ) ) ) {

						$_string = icl_t( 'Theme Options', $option_id, $options[$option_id] );

						if ( ! empty( $_string ) ) {

							$options[$option_id] = $_string;

						}

					}

				}

			}

		}

		return $options[$option_id];

	}
}

/**
 * Get server headers
 */
function uncode_get_server_headers() {
	$headers = isset( $GLOBALS[ '_SERVER' ] ) ? $GLOBALS[ '_SERVER' ] : array();

	return $headers;
}

/**
 * Get server hostname
 */
function uncode_get_server_hostname() {
	$headers  = uncode_get_server_headers();
	$hostname = '';

	if ( isset( $headers[ 'SERVER_NAME' ] ) && $headers[ 'SERVER_NAME' ] ) {
		$hostname = $headers[ 'SERVER_NAME' ];
	} else if ( isset( $headers[ 'HTTP_HOST' ] ) && $headers[ 'HTTP_HOST' ] ) {
		$hostname = $headers[ 'HTTP_HOST' ];
	}

	return $hostname;
}

/**
 * Generate big random ID
 */
if ( ! function_exists( 'uncode_big_rand' ) ) {
	function uncode_big_rand( $len = 6 ) {
		$rand = '';

		while ( ! ( isset( $rand[ $len-1 ] ) ) ) {
			$rand .= mt_rand( );
		}

		return substr( $rand, 0, $len );
	}
}

/**
 * Print a string and optionally validate it
 */
function uncode_switch_stock_string( $string, $check = false ) {
	if ( $check ) {
		switch ( $check ) {
			case 'esc_html':
				$string = esc_html( $string );
				break;

			case 'esc_attr':
				$string = esc_attr( $string );
				break;

			case 'wp_kses_post':
				$string = wp_kses_post( $string );
				break;
		}
	}

	return $string;
}

/**
 * Callback for inner widths
 */
class Uncode_Single_Width_Callback {
	private $key;
	private $shortcode;

	function __construct( $key, $shortcode ) {
		$this->key       = $key;
		$this->shortcode = $shortcode;
	}

	public function calculate_single_width( $matches ) {
		preg_match( "|\d+|", reset( $matches), $m );
		return $this->shortcode . ' col_width="' . ( ( $this->key / 12 ) * $m[0] ) . '"';
	}
}

/**
 * Pass wrapper width to inner columns
 */
function uncode_vc_replace_inner_single_width( $content, $width_media, $shortcode ) {
	$pattern  = "/{$shortcode} col_width=\"[0-9]*\"/";
	$callback = new Uncode_Single_Width_Callback( $width_media, $shortcode );
	$content  = preg_replace_callback( $pattern, array( $callback, 'calculate_single_width' ), $content );
	return $content;
}

/**
 * Generate dynamic CSS
 *
 * This is a copy of uncode_create_dynamic_css() included in Uncode Core.
 * We need it when Uncode Core is not active.
 */
if ( ! function_exists( 'uncode_create_dynamic_css' ) ) {
	function uncode_create_dynamic_css() {

		$css_dir = get_template_directory() . '/library/css/';
		ob_start(); // Capture all output (output buffering)

		require(get_template_directory() . '/core/inc/style-custom.css.php'); // Generate CSS

		$css = ob_get_clean(); // Get generated CSS (output buffering)

		if ($css === 'exit') {
			return;
		}

		if ( uncode_append_custom_styles_to_head() ) {
			return array(
				'custom' => $css,
				'admin' => $admin_css,
			);
		}

		global $wp_filesystem;
		if (empty($wp_filesystem)) {
			require_once (ABSPATH . '/wp-admin/includes/file.php');
		}
		if (false === ($creds = request_filesystem_credentials($css_dir, '', false, false))) {
			return array(
				'custom' => $css,
				'admin' => $admin_css,
			);
		}
		/* initialize the API */
		if ( ! WP_Filesystem($creds) ) {
			/* any problems and we exit */
			return array(
				'custom' => $css,
				'admin' => $admin_css,
			);
		}
		$ot_id = is_multisite() ? get_current_blog_id() : '';
		/* do our file manipulations below */
		$mod_file = (defined('FS_CHMOD_FILE')) ? FS_CHMOD_FILE : false;
		if (!$wp_filesystem->put_contents( $css_dir . 'style-custom'.$ot_id.'.css', $css, $mod_file ) || !$wp_filesystem->put_contents( get_template_directory() . '/core/assets/css/admin-custom'.$ot_id.'.css', $admin_css, $mod_file )) {
			return array(
				'custom' => $css,
				'admin' => $admin_css,
			);
		}
	}
}

if ( ! defined( 'UNCODE_CORE_ADVANCED' ) && ! defined( 'OT_DIR' ) ) {
	define( 'OT_DIR', get_template_directory() . '/core/assets/icons/' );
}
/**
 * Get post layout settings.
 *
 * Returns an array:
 * 		- layout_width (array)
 * 		- content_width (string)
 * 		- content_width_limit (array)
 * 		- with_sidebar (bool)
 * 		- sidebar_size (int)
 */
function uncode_get_post_layout_settings( $post, $uncode_option ) {
	if ( is_numeric( $post ) ) {
		$post = get_post( $post );
	}

	$post_id   = isset( $post->ID ) ? $post->ID : false;
	$post_type = isset( $post->post_type ) ? $post->post_type : 'post';
	$settings  = array(
		'layout_width'        => array(
			'width' => 1200,
			'type'  => 'px'
		),
		'content_width'       => 'inherit',
		'content_width_limit' => array(
			'width' => '',
			'type'  => ''
		),
		'with_sidebar'        => false,
		'sidebar_size'        => 4,
	);

	if ( ! $post_id ) {
		return $settings;
	}

	// Get default width (general)
	$general_width = ot_get_option( '_uncode_main_width' );

	if ( is_array( $general_width ) && 2 === count( $general_width ) ) {
		$settings[ 'layout_width' ] = array(
			'width' => $general_width[ 0 ],
			'type'  => $general_width[ 1 ]
		);
	}

	// Get layout width from page options (if custom)
	if ( 'custom' === get_post_meta( $post->ID, '_uncode_specific_main_width_inherit', true ) ) {
		$custom_layout_width = get_post_meta( $post->ID, '_uncode_specific_main_width', true );

		if ( is_array( $custom_layout_width ) && 2 === count( $custom_layout_width ) ) {
			$settings[ 'layout_width' ] = array(
				'width' => $custom_layout_width[ 0 ],
				'type'  => $custom_layout_width[ 1 ]
			);
		}
	}

	// Get content width from page options (if custom)
	$custom_content_width = get_post_meta( $post->ID, '_uncode_specific_layout_width', true );

	if ( 'limit' === $custom_content_width ) {
		$custom_content_width_limit = get_post_meta( $post->ID, '_uncode_specific_layout_width_custom', true );

		if ( is_array( $custom_content_width_limit ) && 2 === count( $custom_content_width_limit ) ) {

			$settings[ 'content_width' ]       = 'limit';
			$settings[ 'content_width_limit' ] = array(
				'width' => $custom_content_width_limit[ 0 ],
				'type'  => $custom_content_width_limit[ 1 ]
			);
		}
	} else if ( 'full' === $custom_content_width ) {
		// Full layout
		$settings[ 'content_width' ] = 'full';

	// Get content width from Theme Options
	} else if ( is_array( $uncode_option ) ) {
		$post_width_type = ot_get_option( '_uncode_' . $post_type . '_layout_width' );

		if ( 'full' === $post_width_type ) {
			// Full layout
			$settings[ 'content_width' ] = 'full';
		} else if ( 'limit' === $post_width_type ) {
			// Custom limit
			$cpt_layout_width = ot_get_option( '_uncode_' . $post_type . '_layout_width_custom' );

			if ( is_array( $cpt_layout_width ) && 2 === count( $cpt_layout_width ) ) {

				$settings[ 'content_width' ]       = 'limit';
				$settings[ 'content_width_limit' ] = array(
					'width' => $cpt_layout_width[ 0 ],
					'type'  => $cpt_layout_width[ 1 ]
				);
			}
		} else {
			// Check if default content width is full
			if ( 'on' === ot_get_option( '_uncode_body_full' ) ) {
				$settings[ 'content_width' ] = 'full';
			}
		}
	}

	// Get custom sidebar settings (if custom)
	if ( 'portfolio' === $post_type ) {
		$custom_is_active_sidebar = get_post_meta( $post->ID, '_uncode_portfolio_active', true );
	} else {
		$custom_is_active_sidebar = get_post_meta( $post->ID, '_uncode_active_sidebar', true );
	}

	if ( 'off' === $custom_is_active_sidebar ) {
		// Sidebar off
		$settings[ 'with_sidebar' ] = false;

	} else if ( 'on' === $custom_is_active_sidebar ) {
		// Sidebar on

		// Portfolios have details on the left/right (that's our sidebar)
		if ( $post_type === 'portfolio' ) {
			$details_position = get_post_meta( $post->ID, '_uncode_portfolio_position', true );

			if ( $details_position === 'sidebar_right' || $details_position === 'sidebar_left' ) {
				$settings[ 'with_sidebar' ] = true;
				$settings[ 'sidebar_size' ] = get_post_meta( $post->ID, '_uncode_portfolio_sidebar_size', true );
			}
		} else {
			if ( $post_type === 'page' || $post_type === 'post' ) {
				// Check if we have at least one widget on the sidebar
				$sidebar_name = get_post_meta( $post->ID, '_uncode_sidebar', true );

				if ( is_active_sidebar( $sidebar_name ) ) {
					$settings[ 'with_sidebar' ] = true;
					$settings[ 'sidebar_size' ] = get_post_meta( $post->ID, '_uncode_sidebar_size', true );
				}

			} else {
				$settings[ 'with_sidebar' ] = true;
				$settings[ 'sidebar_size' ] = get_post_meta( $post->ID, '_uncode_sidebar_size', true );
			}
		}

	// Get sidebar settings from Theme Options
	} else if ( is_array( $uncode_option ) ) {
		if ( 'portfolio' === $post_type ) {
			$details_position = ot_get_option( '_uncode_portfolio_position' );

			if ( $details_position === 'sidebar_right' || $details_position === 'sidebar_left' ) {
				$settings[ 'with_sidebar' ] = true;
				$settings[ 'sidebar_size' ] = ot_get_option( '_uncode_portfolio_sidebar_size' );
			} else {
				$settings[ 'with_sidebar' ] = false;
			}

		} else {
			$cpt_is_active_sidebar = ot_get_option( '_uncode_' . $post_type . '_activate_sidebar' );

			if ( 'off' === $cpt_is_active_sidebar ) {
				// CPT sidebar off
				$settings[ 'with_sidebar' ] = false;
			} else {

				if ( $post_type === 'page' || $post_type === 'post' ) {
					// Check if we have at least one widget on the sidebar
					$sidebar_name = ot_get_option( '_uncode_' . $post_type . '_sidebar' );

					if ( is_active_sidebar( $sidebar_name ) ) {
						$settings[ 'with_sidebar' ] = true;
						$settings[ 'sidebar_size' ] = ot_get_option( '_uncode_' . $post_type . '_sidebar_size' );
					}

				} else {
					$settings[ 'with_sidebar' ] = true;
					$settings[ 'sidebar_size' ] = ot_get_option( '_uncode_' . $post_type . '_sidebar_size' );
				}
			}
		}
	}

	return $settings;
}

/**
 * Append custom CSS inline or not
 */
if ( ! function_exists( 'uncode_append_custom_styles_to_head' ) ) {
	function uncode_append_custom_styles_to_head() {
		$append_inline = apply_filters( 'uncode_append_custom_styles_to_head', false ) ? true : false;

		return $append_inline;
	}
}

/**
 * Get max input vars
 */
if ( ! function_exists( 'uncode_get_minimum_max_input_vars' ) ) {
	function uncode_get_minimum_max_input_vars() {
		$saved_max_input_vars = intval( get_option( 'uncode_test_max_input_vars' ) );
		$conf_max_input_vars  = ini_get( 'max_input_vars' );
		$conf_max_input_vars  = $conf_max_input_vars ? intval( $conf_max_input_vars ) : 1;

		// Return conf max vars if we don't have a saved value
		if ( ! $saved_max_input_vars ) {
			$saved_max_input_vars = 1; // Set to at least 1

			return $conf_max_input_vars;
		}

		// Return conf max vars if it is smaller than the saved value
		// This handles the possibility of downgrades (ie. for some reason
		// now the server has a smaller max_input_vars value in php.ini)
		if ( $conf_max_input_vars < $saved_max_input_vars ) {
			return $conf_max_input_vars;
		}

		return $saved_max_input_vars;
	}
}

/**
 * Get recommended max input vars
 */
if ( ! function_exists( 'uncode_get_recommended_max_input_vars' ) ) {
	function uncode_get_recommended_max_input_vars() {
		$max_vars = 3000;
		$theme_options_number_of_inputs = intval( get_option( 'uncode_theme_options_number_of_inputs', false ) );

		// Round to nearest (up) thousand if > 3000
		if ( $theme_options_number_of_inputs && $theme_options_number_of_inputs > 0 && $theme_options_number_of_inputs > 3000) {
			$max_vars = (int) ceil( $theme_options_number_of_inputs / 1000 ) * 1000;
		}

		return apply_filters( 'uncode_get_recommended_max_input_vars', $max_vars );
	}
}
