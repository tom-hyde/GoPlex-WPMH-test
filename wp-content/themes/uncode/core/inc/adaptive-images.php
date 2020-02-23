<?php
/**
 * Adpative images functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'wp_ajax_get_adaptive_async', 'uncode_get_adaptive_async' );
add_action( 'wp_ajax_nopriv_get_adaptive_async', 'uncode_get_adaptive_async' );

function uncode_get_adaptive_async() {
	if ( isset( $_POST[ 'nonce_adaptive_images' ] ) ) {
		// Check nonce if enabled
		if ( apply_filters( 'uncode_enable_nonce_adaptive_images', false ) && ! wp_verify_nonce( $_POST[ 'nonce_adaptive_images' ], 'uncode-adaptive-images-nonce' ) ) {
			// Invalid nonce
			wp_send_json_error();
		}

		$posted_images = isset( $_POST[ 'images' ] ) ? $_POST[ 'images' ] : array();

		// Sanitize data
		$posted_images = uncode_sanitize_adaptive_async_data( $posted_images );
		$images        = array();

		foreach( $posted_images as $d ){
			$media_id            = explode( '-', $d[ 'unique' ] );
			$media_id            = $media_id[ 0 ];
			$resized             = uncode_resize_image( $media_id, $d[ 'url' ], $d[ 'path' ], $d[ 'origwidth' ], $d[ 'origheight' ], $d[ 'singlew' ], $d[ 'singleh' ], $d[ 'crop' ], $d[ 'fixed' ], array('images' => $d[ 'images' ], 'screen' => $d[ 'screen' ] ) );
			$resized[ 'unique' ] = $d[ 'unique' ];
			$images[]            = $resized;
		}

		$response = array(
	        'images' => $images
	    );

	    wp_send_json_success($response);

	} else {
		// Invalid data
		wp_send_json_error();
	}
}

/**
 * Sanitize posted async_data
 */
function uncode_sanitize_adaptive_async_data( $data ) {
	$sanitized_data = array();

	$data = json_decode( stripslashes( $data ) );

	foreach ( $data as $value ) {
		$sanitized_data[] = uncode_sanitize_adaptive_async_image( $value );
	}

	return $sanitized_data;
}

/**
 * Loop through each image object and sanitize it
 */
function uncode_sanitize_adaptive_async_image( $data ) {
	$image_data = array();

	foreach ( $data as $key => $value ) {
		if ( $key == 'unique' ) {
			$image_data[ 'unique' ] = sanitize_text_field( $value );
		} else if ( $key == 'url' ) {
			$image_data[ 'url' ] = esc_url( $value );
		} else if ( $key == 'path' ) {
			$image_data[ 'path' ] = sanitize_text_field( $value );
		} else if ( $key == 'singlew' ) {
			$image_data[ 'singlew' ] = sanitize_text_field( $value );
		} else if ( $key == 'singleh' ) {
			$image_data[ 'singleh' ] = sanitize_text_field( $value );
		} else if ( $key == 'origwidth' ) {
			$image_data[ 'origwidth' ] = absint( $value );
		} else if ( $key == 'origheight' ) {
			$image_data[ 'origheight' ] = absint( $value );
		} else if ( $key == 'crop' ) {
			$image_data[ 'crop' ] = $value ? absint( $value ) : null;
		} else if ( $key == 'fixed' ) {
			$image_data[ 'fixed' ] = $value ? sanitize_text_field( $value ) : null;
		} else if ( $key == 'screen' ) {
			$image_data[ 'screen' ] = absint( $value );
		} else if ( $key == 'images' ) {
			$image_data[ 'images' ] = absint( $value );
		}
	}

	return $image_data;
}
