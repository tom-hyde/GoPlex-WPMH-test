<?php
/**
 * Multiple galleries functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'uncode_save_gallery_media' ) ) :
/**
 * Create/update a new media when the gallery cpt is published
 */
function uncode_save_gallery_media( $post_id, $post, $update ) {
	// Don't create the attachment for revisions or autosaves
	if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
		return;
	}

	// Check user has permission to edit
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Don't create the attachemnt during the trash action or for auto drafts
	if ( 'trash' === $post->post_status || 'auto-draft' === $post->post_status ) {
        return;
    }

	// Unhook this function so it doesn't loop infinitely
	remove_action( 'save_post_uncode_gallery', 'uncode_save_gallery_media', 10, 3 );

	try {
		$author  = $post->post_author;
		$title   = $post->post_title;
		$content = $post->post_content;
		$excerpt = $post->post_excerpt;

		// Check if the media attachment is associated to the post and if it exists
		$post_gallery_media_id = uncode_get_gallery_attached_media( $post_id );

		// We are updating an existing post and the media exists
		if ( $update && $post_gallery_media_id ) {
			// Get gallery's featured thumbnail (if any)
			$thumb = get_post_thumbnail_id( $post_id, 'thumbnail' );

			$args = array(
				'ID'         => $post_gallery_media_id,
				'post_title' => wp_strip_all_tags( $title ),
				'post_content'   => $content,
				'post_excerpt'   => $excerpt,
				// 'post_status'    => 'publish',
				'post_name'      => wp_unique_post_slug( 'gallery-attachment-' . $title, $post_id, 'inherit', 'attachment', $post_id ),
				'meta_input'     => array(
					'_uncode_gallery_cover'     => $thumb,
					'_uncode_is_oembed_gallery' => true,
				)
			);

			$media_id = wp_update_post( $args );

			if ( is_wp_error( $media_id ) ){
				throw new Exception( $media_id->get_error_message() );
			}

		// New post
		} else {
			// Get gallery's featured thumbnail (if any)
			$thumb = get_post_thumbnail_id( $post_id, 'thumbnail' );

			$args = array(
				'post_title'     => wp_strip_all_tags( $title ),
				'post_content'   => $content,
				'post_excerpt'   => $excerpt,
				// 'post_status'    => 'publish',
				'post_author'    => $author,
				'post_type'      => 'attachment',
				'post_parent'    => $post_id,
				'post_name'      => wp_unique_post_slug( 'gallery-attachment-' . $title, $post_id, 'inherit', 'attachment', $post_id ),
				'post_mime_type' => 'oembed/gallery',
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
				'meta_input'     => array(
					'_uncode_gallery_cover'     => $thumb,
					'_uncode_is_oembed_gallery' => true,
				)
			);

			$media_id = wp_insert_post( $args );

			if ( is_wp_error( $media_id ) ){
				throw new Exception( $media_id->get_error_message() );
			}

			update_post_meta( $post_id, '_uncode_gallery_media', $media_id );
		}

	} catch ( Exception $e ) {
		return new WP_Error( 'uncode_gallery_media_error', $e->getMessage() );
	}

	// Re-hook this function
	add_action( 'save_post_uncode_gallery', 'uncode_save_gallery_media', 10, 3 );
}
endif;
add_action( 'save_post_uncode_gallery', 'uncode_save_gallery_media', 10, 3 );

if ( ! function_exists( 'uncode_get_gallery_attached_media' ) ) :
/**
 * Get the ID of the media (oembed/gallery) attached to a specific gallery cpt
 *
 * Returns the ID if the media exists, false otherwise
 */
function uncode_get_gallery_attached_media( $post_id ) {
	$post_gallery_media_id = get_post_meta( $post_id, '_uncode_gallery_media', true );

	$media_exists = $post_gallery_media_id && is_string( get_post_status( $post_gallery_media_id ) ) ? $post_gallery_media_id : false;

	return $media_exists;
}
endif;

if ( ! function_exists( 'uncode_trash_gallery_media' ) ) :
/**
 * When a gallery cpt is trashed, delete the gallery media attachment permanently
 */
function uncode_trash_gallery_media( $post_id, $post ) {
	$post_gallery_media_id = uncode_get_gallery_attached_media( $post_id );

	if ( $post_gallery_media_id ) {
		wp_delete_attachment( $post_gallery_media_id, true );
	}
}
endif;
add_action( 'trash_uncode_gallery', 'uncode_trash_gallery_media', 10, 2 );

if ( ! function_exists( 'uncode_media_attachment_data' ) ) :
/**
 * Filter the gallery cpt attachment and pass:
 * - a custom URL (the URL of the featured image if exists) - this displays the cover
 *   in the media upload
 * - a custom class (used to show/hide the media via CSS on unwanted editors)
 * - the URL of the parent post, to display a button in the attchment details section
 */
function uncode_media_attachment_data( $response, $attachment, $meta ) {
	if ( $response[ 'mime' ] == 'oembed/gallery' ) {
		// Check if the attachment has a gallery cover
		$gallery_cover_id     = get_post_meta( $attachment->ID, '_uncode_gallery_cover', true );
		$gallery_thumb        = wp_get_attachment_image_src( $gallery_cover_id, 'thumbnail' );
		$gallery_medium_thumb = wp_get_attachment_image_src( $gallery_cover_id, 'medium' );

		// Add featured image URL
		if ( $gallery_thumb ) {
			$response[ 'url' ]           = esc_url( $gallery_thumb[0] );
			$response[ 'cover_medium' ]  = $gallery_medium_thumb ? esc_url( $gallery_medium_thumb[0] ) : esc_url( $gallery_thumb[0] );
		}

		// Add custom CSS class
		$response[ 'customClass' ] = 'oembed-gallery-container';

		// Strip tags
		$response[ 'description' ] = wp_strip_all_tags( $response[ 'description' ] );
		$response[ 'caption' ]    = wp_strip_all_tags( $response[ 'caption' ] );

		// Add post parent URL
		if ( $response[ 'uploadedTo' ] ) {
			$url = get_edit_post_link( $response[ 'uploadedTo' ], false );
			$response[ 'parentPostEditLink' ] = $url;
		}
	}

	return $response;
}
endif;
add_filter( 'wp_prepare_attachment_for_js', 'uncode_media_attachment_data', 10, 3 );

if ( ! function_exists( 'uncode_hide_gallery_attachments_grid_view' ) ) :
/**
 * Grid View
 *
 * Filter the media upload query to hide gallery attachements.
 * Gallery attachments will be visible only in the VC modules
 * and in the Uncode "Select medias" uploaders
 */
function uncode_hide_gallery_attachments_grid_view( $query ) {
	$post_id = isset( $_REQUEST[ 'post_id' ] ) ? $_REQUEST[ 'post_id' ] : false;

	// Return early (ie. do not filter galleries) if the uploader
	// was opened by an allowed post type
	if ( $post_id ) {
		$accepted_pts = array(
			'page',
			'post',
			'uncodeblock',
			'portfolio',
		);

		$post_object = get_post( $post_id );

		if ( in_array( $post_object->post_type , $accepted_pts ) ) {
			return $query;
		}
	}

	$media_attachments_ids = uncode_get_gallery_attachment_ids();

	$query[ 'post__not_in' ] = $media_attachments_ids;

	return $query;
}
endif;
add_filter( 'ajax_query_attachments_args', 'uncode_hide_gallery_attachments_grid_view' );

if ( ! function_exists( 'uncode_hide_gallery_attachments_list_view' ) ) :
/**
 * List View
 *
 * Filter the media upload query to hide gallery attachements when
 * we are on the upload page (WP > Media) and we are viewing the
 * medias in List mode
 */
function uncode_hide_gallery_attachments_list_view( $query ){
    if ( ! is_admin() ) {
        return;
    }

    global $pagenow;

    if ( 'upload.php' != $pagenow && 'media-upload.php' != $pagenow ) {
        return;
    }

    if ( $query->is_main_query() ) {
    	$media_attachments_ids = uncode_get_gallery_attachment_ids();
        $query->set( 'post__not_in', $media_attachments_ids );
    }

    return $query;
}
endif;
add_action( 'pre_get_posts' , 'uncode_hide_gallery_attachments_list_view' );

if ( ! function_exists( 'uncode_get_gallery_attachment_ids' ) ) :
/**
 * Get the IDs of all the media (oembed/gallery) attachments
 *
 * @TODO: Maybe use a transient instead of WP Query?
 */
function uncode_get_gallery_attachment_ids() {
	$media_attachments_ids = array();

	$media_attachments_query = new WP_Query( 'post_type=attachment&post_mime_type=oembed/gallery&posts_per_page=-1&post_status=any' );

	if ( $media_attachments_query->have_posts() ) {
		foreach ( $media_attachments_query->posts as $media_attachment ) {
			$media_attachments_ids[] = $media_attachment->ID;
		}
	}

	return $media_attachments_ids;
}
endif;

if ( ! function_exists( 'uncode_before_media_send_to_editor' ) ) :
/**
 * Before to send to editor the selected medias. We don't want our media
 * gallery attachments in the WP editor. So skip them.
 */
function uncode_before_media_send_to_editor( $html, $id, $attachment ) {
	if ( in_array( $id, uncode_get_gallery_attachment_ids() ) ) {
		return '';
	}

	return $html;
}
endif;
add_filter( 'media_send_to_editor', 'uncode_before_media_send_to_editor', 10, 3 );

if ( ! function_exists( 'uncode_get_album_item' ) ) :
/**
 * Print album item
 */
function uncode_get_album_item( $id ) {
	$album_item_attributes = uncode_get_media_info($id);
	if ( !isset($album_item_attributes->post_mime_type) )
		return;

	$mime = $album_item_attributes->post_mime_type;
	$return = array();
	switch ($mime) {
		case 'oembed/flickr':
		case 'oembed/Imgur':
		case 'oembed/photobucket':
		case 'oembed/youtube':
		case 'oembed/vimeo':
		case 'oembed/soundcloud':
		case 'oembed/spotify':
		case 'oembed/twitter':
		case 'oembed/instagram':
		case 'oembed/html':
		case 'oembed/facebook':
		case 'audio/mpeg':
		case 'video/mp4':
			$media_oembed = uncode_get_oembed($id, $album_item_attributes->guid, $album_item_attributes->post_mime_type, true, $album_item_attributes->post_excerpt, $album_item_attributes->post_content);
			$return['url'] = $media_oembed['code'];
			$return['width'] = $media_oembed['width'];
			$return['height'] = $media_oembed['height'];
			$return['poster'] = $media_oembed['poster'];
			$return['title'] = $album_item_attributes->post_title;
			$return['caption'] = $album_item_attributes->post_excerpt;
			$return['mime_type'] = $mime;
			break;
		default:
			$media_metavalues = unserialize($album_item_attributes->metadata);
			$return['url'] = wp_get_attachment_url($id);
			$return['width'] = $media_metavalues['width'];
			$return['height'] = $media_metavalues['height'];
			$return['poster'] = $id;
			$return['title'] = $album_item_attributes->post_title;
			$return['caption'] = $album_item_attributes->post_excerpt;
			$return['mime_type'] = $mime;
		break;
	}
	return $return;
}
endif;//uncode_get_album_item

if ( ! function_exists( 'uncode_delete_translated_galleries' ) ) :
/**
 * Delete unwanted translations for the oembed/galleries created by WPML Media
 */
function uncode_delete_translated_galleries() {
	global $wpdb;

	// Check if WPML Media is active
	if ( ! class_exists( 'WPML_Media' ) ) {
		return;
	}

	// Return early during AJAX requests
	// (for example, the 'Start' WPML Media wizard)
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}

	// Get all oembed galleries
	$attachment_ids = $wpdb->get_results( "SELECT ID FROM {$wpdb->prefix}posts WHERE post_mime_type = 'oembed/gallery'", ARRAY_A );

	if ( count( $attachment_ids ) > 0 ) {
		foreach ( $attachment_ids as $row ) {
			$is_oembed_gallery = get_post_meta( $row[ 'ID' ], '_uncode_is_oembed_gallery', true );

			// Skip our default oembed
			if ( $is_oembed_gallery ) {
				continue;
			}

			// Delete the rest of them
			wp_delete_attachment( $row[ 'ID' ], true );
		}
	}
}
endif;
add_action( 'wp_loaded', 'uncode_delete_translated_galleries' );
