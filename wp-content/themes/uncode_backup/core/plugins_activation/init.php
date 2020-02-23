<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.5.2
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';
require_once dirname( __FILE__ ) . '/class-tgm-updater.php';

add_action( 'tgmpa_register', 'uncode_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function uncode_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		array(
			'name'               => 'Uncode Core', // The plugin name.
			'slug'               => 'uncode-core', // The plugin slug (typically the folder name).
			'source'             => get_template_directory() . '/core/plugins_activation/plugins/uncode-core.zip', // The plugin source.
			'required'           => true, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '2.0.3', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
			'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
		),
		array(
			'name'               => 'Uncode WPBakery Page Builder', // The plugin name.
			'slug'               => 'uncode-js_composer', // The plugin slug (typically the folder name).
			'source'             => get_template_directory() . '/core/plugins_activation/plugins/uncode-js_composer.zip', // The plugin source.
			'required'           => true, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '5.7', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
			'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
		),
		array(
			'name'      => 'Contact Form 7',
			'slug'      => 'contact-form-7',
			'required'  => false,
		),
		array(
			'name'      => 'WooCommerce',
			'slug'      => 'woocommerce',
			'required'  => false,
		),
		array(
			'name'      => 'Category Order and Taxonomy Terms Order',
			'slug'      => 'taxonomy-terms-order',
			'required'  => false,
		),
	);

	if ( ! class_exists( 'RP4WP' ) ) {
		$plugins[] = array(
			'name'      => 'Related Posts for WordPress',
			'slug'      => 'related-posts-for-wp',
			'required'  => false,
		);

	}

	$premium_plugins = uncode_get_premium_plugins();

	foreach ( $premium_plugins as $premium_plugin_data ) {
		$plugins[] = array(
			'name'               => $premium_plugin_data[ 'plugin_name' ], // The plugin name.
			'slug'               => $premium_plugin_data[ 'plugin_slug' ], // The plugin slug (typically the folder name).
			'source'             => $premium_plugin_data[ 'zip_url' ], // The plugin source.
			'required'           => $premium_plugin_data[ 'required' ], // If false, the plugin is only 'recommended' instead of required.
			'version'            => $premium_plugin_data[ 'version' ], // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
			'force_activation'   => $premium_plugin_data[ 'force_activation' ], // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => $premium_plugin_data[ 'force_deactivation' ], // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
			'is_premium'         => true,
		);
	}

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'uncode-plugins', 			// Menu slug.
		'parent_slug'  => 'admin.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}

function uncode_plugins_menu_args($args) {
    $args['parent_slug'] = 'uncode-system-status';
    return $args;
}

add_filter( 'tgmpa_admin_menu_args', 'uncode_plugins_menu_args' );

/**
 * Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
 */
add_action( 'vc_before_init', 'uncode_vcSetAsTheme' );
function uncode_vcSetAsTheme() {
	vc_set_as_theme($disable_updater = true);
}

// Register your custom function to override some LayerSlider data
add_action('layerslider_ready', 'uncode_layerslider_overrides');
function uncode_layerslider_overrides()
{
	// Disable auto-updates
	$GLOBALS['lsAutoUpdateBox'] = false;
}

function uncode_layerslider_notification() {
	if( defined( 'LS_PLUGIN_BASE' ) ) {
		remove_action( 'after_plugin_row_' . LS_PLUGIN_BASE, 'layerslider_plugins_purchase_notice', 10, 3 );
	}
}
add_action( 'layerslider_ready', 'uncode_layerslider_notification' );

add_filter('site_transient_update_plugins', 'remove_update_notifications');
function remove_update_notifications($value) {
    if ( isset( $value ) && is_object( $value ) ) {
       unset($value->response[ 'js_composer/js_composer.php' ]);
       unset($value->response[ 'js_composer_theme/js_composer.php' ]);
    }
    return $value;
}

add_action('init', 'uncode_remove_sliders_notices');
if ( ! function_exists( 'uncode_remove_sliders_notices' ) ) :
/**
 * User profile socials.
 * @since Uncode 1.9.1
 */
function uncode_remove_sliders_notices(){
	remove_action('admin_notices', array('RevSliderAdmin', 'add_plugins_page_notices'));
	remove_action('admin_init', 'layerslider_check_notices');
}
endif;//uncode_remove_sliders_notices
