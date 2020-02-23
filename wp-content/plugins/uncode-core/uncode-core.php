<?php
/*
Plugin Name: Uncode Core
Plugin URI: http://www.undsgn.com
Description: Uncode Core Plugin for Undsgn Themes.
Version: 2.1.0
Author: Undsgn
Author URI: http://www.undsgn.com
*/

define( 'UNCODE_CORE_FILE', __FILE__ );
define( 'UNCODE_CORE_PLUGIN_DIR', dirname(__FILE__) );
define( 'UNCODE_CORE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'UNCODE_CORE_ADVANCED', true );

// Blocking direct access
if( ! function_exists( 'uncode_block_direct_access' ) ) {
	function uncode_block_direct_access() {
		if( ! defined( 'ABSPATH' ) ) {
			exit( 'Direct access denied.' );
		}
	}
}

if( ! class_exists( 'UncodeCore_Plugin' ) ) {
	class UncodeCore_Plugin {

		const VERSION = '2.1.0';
		protected static $instance = null;

		private function __construct() {
			add_action('admin_init', array(&$this, 'admin_init'));
		}

		function admin_init() {
			load_theme_textdomain( 'uncode', plugin_dir_path( __FILE__ ) . '/languages' );
		}

		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}
	}
}

// Init the plugin
add_action( 'plugins_loaded', array( 'UncodeCore_Plugin', 'get_instance' ) );

/**
* Custom posts type.
*/
require_once dirname(__FILE__) . '/custom-post-type.php';

/**
* Customizer Visual Composer
*/
function before_visual_composer() {
	if ( ! defined( 'UNCODE_SLIM' ) ) {
		return;
	}

	$ok_php = true;
	if ( function_exists( 'phpversion' ) ) {
		$php_version = phpversion();
		if (version_compare($php_version,'5.3.0') < 0) $ok_php = false;
	}
	if ($ok_php) require_once dirname(__FILE__) . '/vc_extend/init.php';
}
add_action( 'vc_before_init', 'before_visual_composer' );

//////////////////////////////
// Add page category filter //
//////////////////////////////

add_action('restrict_manage_posts', 'uncode_page_filter_post_type_by_taxonomy');

function uncode_page_filter_post_type_by_taxonomy() {
	global $typenow;
	$post_type = 'page'; // change to your post type
	$taxonomy  = 'page_category'; // change to your taxonomy
	if ($typenow == $post_type) {
		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => sprintf(esc_html__("Show All %s", 'uncode'), $info_taxonomy->label),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
		));
	};
}

add_filter('parse_query', 'uncode_page_convert_id_to_term_in_query');
function uncode_page_convert_id_to_term_in_query($query) {
	global $pagenow;
	$post_type = 'page'; // change to your post type
	$taxonomy  = 'page_category'; // change to your taxonomy
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}

///////////////////////////////////
// Add portfolio category filter //
///////////////////////////////////

add_action('restrict_manage_posts', 'uncode_portfolio_filter_post_type_by_taxonomy');

function uncode_portfolio_filter_post_type_by_taxonomy() {
	global $typenow;
	$post_type = 'portfolio'; // change to your post type
	$taxonomy  = 'portfolio_category'; // change to your taxonomy
	if ($typenow == $post_type) {
		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => sprintf(esc_html__("Show All %s", 'uncode'), $info_taxonomy->label),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
		));
	};
}

add_filter('parse_query', 'uncode_portfolio_convert_id_to_term_in_query');
function uncode_portfolio_convert_id_to_term_in_query($query) {
	global $pagenow;
	$post_type = 'portfolio'; // change to your post type
	$taxonomy  = 'portfolio_category'; // change to your taxonomy
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}

///////////////////////////////////////
// Add content block category filter //
///////////////////////////////////////

add_action('restrict_manage_posts', 'uncode_cblock_filter_post_type_by_taxonomy');

function uncode_cblock_filter_post_type_by_taxonomy() {
	global $typenow;
	$post_type = 'uncodeblock'; // change to your post type
	$taxonomy  = 'uncodeblock_category'; // change to your taxonomy
	if ($typenow == $post_type) {
		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => sprintf(esc_html__("Show All %s", 'uncode'), $info_taxonomy->label),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
		));
	};
}

add_filter('parse_query', 'uncode_cblock_convert_id_to_term_in_query');
function uncode_cblock_convert_id_to_term_in_query($query) {
	global $pagenow;
	$post_type = 'uncodeblock'; // change to your post type
	$taxonomy  = 'uncodeblock_category'; // change to your taxonomy
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}

function uncode_core_override_woocommerce_widgets() {
	// Ensure our parent class exists to avoid fatal error (thanks Wilgert!)
	if ( class_exists( 'WC_Widget_Price_Filter' ) ) {
		unregister_widget( 'WC_Widget_Price_Filter' );
		include_once dirname(__FILE__) . '/woocommerce/widgets/widget-price_filter.php';
		register_widget( 'Uncode_WC_Widget_Price_Filter' );
	}
}
add_action( 'widgets_init', 'uncode_core_override_woocommerce_widgets', 15 );

/**
 * Shared functions.
 */
require_once UNCODE_CORE_PLUGIN_DIR . '/includes/shared-functions.php';

/**
 * Admin functions.
 */
require_once UNCODE_CORE_PLUGIN_DIR . '/includes/admin.php';

/**
* I recommend this implementation.
*/
require_once UNCODE_CORE_PLUGIN_DIR . '/i-recommend-this/i-recommend-this.php';

/**
 * System status page.
 */
require_once UNCODE_CORE_PLUGIN_DIR . '/includes/system-status.php';

/**
 * Required: set 'ot_theme_mode' filter to true.
 */
require_once UNCODE_CORE_PLUGIN_DIR . '/includes/theme-options/assets/theme-mode/functions.php';

/**
 * Required: include OptionTree.
 */
require_once UNCODE_CORE_PLUGIN_DIR . '/includes/theme-options/ot-loader.php';

/**
 * Load the theme options.
 */
require_once UNCODE_CORE_PLUGIN_DIR . '/includes/theme-options/assets/theme-mode/theme-options.php';

/**
 * Performance functions.
 */
require_once UNCODE_CORE_PLUGIN_DIR . '/includes/performance/performance.php';

/**
 * Load the theme meta boxes.
 */
require_once UNCODE_CORE_PLUGIN_DIR . '/includes/theme-options/assets/theme-mode/meta-boxes.php';

/**
 * Load one click demo
 */
require_once UNCODE_CORE_PLUGIN_DIR . '/includes/one-click-demo/init.php';

/**
 * Font system.
 */
require_once UNCODE_CORE_PLUGIN_DIR . '/includes/font-system/font-system.php';

/**
 * Third-party related functions.
 */
require_once UNCODE_CORE_PLUGIN_DIR . '/includes/compatibility.php';

/**
 * Native shortcodes.
 */
require_once UNCODE_CORE_PLUGIN_DIR . '/includes/shortcodes.php';

/**
 * Deprecated function.
 */
require_once UNCODE_CORE_PLUGIN_DIR . '/includes/deprecated-functions.php';
