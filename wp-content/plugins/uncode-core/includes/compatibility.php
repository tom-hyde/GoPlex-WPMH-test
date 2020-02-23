<?php
/**
 * Third-party related functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Skip LayerSlider updates.
 */
function uncode_core_skip_layerslider_updates() {
	if ( isset( $GLOBALS[ 'LS_AutoUpdate' ] ) ) {
		$ls_slider = $GLOBALS[ 'LS_AutoUpdate' ];
		remove_filter( 'pre_set_site_transient_update_plugins', array( $ls_slider, 'set_update_transient' ) );
		remove_filter( 'plugins_api', array( $ls_slider, 'set_updates_api_results' ), 10, 3 );
		remove_filter('upgrader_pre_download', array( $ls_slider, 'pre_download_filter' ), 10, 4);
	}
}
add_action( 'init', 'uncode_core_skip_layerslider_updates' );

/**
 * Translate content for qTranslate.
 */
function uncode_core_qtranslate_support( $content ) {
	if ( function_exists( 'qtranxf_getLanguage' ) ) {
		return __( $content );
	} else {
		return $content;
	}
}
add_filter( 'uncode_filter_for_translation', 'uncode_core_qtranslate_support' );

/**
 * Remove Jetpack related posts from content.
 */
function jetpackme_remove_rp() {
	if ( class_exists( 'Jetpack_RelatedPosts' ) ) {
		$jprp = Jetpack_RelatedPosts::init();
		$callback = array( $jprp, 'filter_add_target_to_dom' );
		remove_filter( 'the_content', $callback, 40 );
	}
}
add_filter( 'wp', 'jetpackme_remove_rp', 20 );

/**
 * Load RP4WP scripts (Related Posts For WordPress).
 */
function uncode_load_rp4wp_scripts( $hook ) {
	if ( 'uncode_page_rp4wp' === $hook && class_exists( 'RP4WP' ) && class_exists('RP4WP_Hook_Settings_Page') ) {
		wp_enqueue_style( 'rp4wp-settings-css', plugins_url( '/assets/css/settings.css', RP4WP::get_plugin_file() ), array(), RP4WP::VERSION );

		// // Main settings JS
		wp_enqueue_script(
			'rp4wp_settings_js',
			plugins_url( '/assets/js/settings' . ( ( ! SCRIPT_DEBUG ) ? '.min' : '' ) . '.js', RP4WP::get_plugin_file() ),
			array( 'jquery' ),
			RP4WP::VERSION
		);
	}
}
add_action( 'admin_enqueue_scripts', 'uncode_load_rp4wp_scripts' );
