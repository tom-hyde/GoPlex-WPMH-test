<?php
/**
 * Gutenberg helpers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'uncode_gutenberg_can_edit_post_type' ) ) :

	/**
	 * Check if Gutenberg or block editor can edit this post type
	 */
	function uncode_gutenberg_can_edit_post_type( $post_type ) {
		global $wp_version;

		if ( version_compare( $wp_version, '5', '>=' ) ) {
			// WP 5+, check for block editor
			return use_block_editor_for_post_type( $post_type );
		} else {
			// WP 4 or older
			// If Gutenberg plugin is not active return false
			// otherwise check if Gutenberg can edit the post type
			if ( ! function_exists( 'gutenberg_can_edit_post_type' ) ) {
				return false;
			}

			return gutenberg_can_edit_post_type( $post_type );
		}

		return false;
	}

endif;

if ( ! function_exists( 'uncode_is_wpb_content' ) ) :
/**
 * @since Uncode 2.0.0
 */
function uncode_is_wpb_content() {
	$post_id = isset( $_GET['post'] ) ? $_GET['post'] : ( isset( $_POST['post_ID'] ) ? $_POST['post_ID'] : false );

	if ( ! $post_id ) {
		return false;
	}

	$post = get_post($post_id);
	if ( ! empty( $post ) && isset( $post->post_content ) && preg_match( '/\[vc_row/', $post->post_content ) ) {
		return true;
	}

	return false;
}
endif; //endif;

if ( ! function_exists( 'uncode_is_gutenberg_current_editor' ) ) :

	/**
	 * Check if Gutenberg is the active editor
	 */
	function uncode_is_gutenberg_current_editor( $post_type ) {
		// Gutenberg is not active
		if ( ! uncode_is_gutenberg_active() ) {
			return false;
		}

		// Gutenberg can't edit this post type
		if ( ! uncode_gutenberg_can_edit_post_type( $post_type ) ) {
			return false;
		}

		if ( isset( $_REQUEST ) && isset( $_REQUEST[ 'classic-editor' ] ) ) {
			return false;
		}

		if ( function_exists( 'vc_is_wpb_content' ) && uncode_is_wpb_content() ) {
			return false;
		}

		return true;
	}

endif;

if ( ! function_exists( 'uncode_gutenberg_content_block_skin_classes' ) ) :

	/**
	 * Get skin classes for content block
	 */
	function uncode_gutenberg_content_block_skin_classes() {
		global $metabox_data;

		if (isset($metabox_data['_uncode_specific_style'][0]) && $metabox_data['_uncode_specific_style'][0] !== '') {
			$style = $metabox_data['_uncode_specific_style'][0];
			if (isset($metabox_data['_uncode_specific_bg_color'][0]) && $metabox_data['_uncode_specific_bg_color'][0] !== '') {
				$bg_color = $metabox_data['_uncode_specific_bg_color'][0];
			}
		} else {
			$style = ot_get_option('_uncode_general_style');
			if (isset($metabox_data['_uncode_specific_bg_color'][0]) && $metabox_data['_uncode_specific_bg_color'][0] !== '') {
				$bg_color = $metabox_data['_uncode_specific_bg_color'][0];
			} else $bg_color = ot_get_option('_uncode_general_bg_color');
		}

		$bg_color = ($bg_color == '') ? ' style-'.$style.'-bg' : ' style-'.$bg_color.'-bg';
		$skin     = $style ? ' style-' . $style : ' style-light';
		$class    = $bg_color . $skin;

		return $class;
	}

endif;
