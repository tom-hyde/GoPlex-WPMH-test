<?php
/**
 * Shared functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Check if a new version of Uncode Core has been installed or updated.
 */
function uncode_check_if_plugin_was_updated() {
	if ( defined( 'UNCODE_SLIM' ) && ( ! get_option( 'uncode_core_latest_version' ) || version_compare( get_option( 'uncode_core_latest_version' ), UncodeCore_Plugin::VERSION, '<' ) ) ) {
		update_option( 'uncode_core_latest_version', UncodeCore_Plugin::VERSION );
		do_action( 'uncode_core_upgraded' );
	}
}
add_action( 'admin_init', 'uncode_check_if_plugin_was_updated' );

/**
 * Add Media meta box
 */
function uncode_core_display_metabox() {

	global $post;

	wp_enqueue_script( 'media_items_js', UNCODE_CORE_PLUGIN_URL . 'vc_extend/assets/js/media_items.js', array( 'jquery' ), UncodeCore_Plugin::VERSION );

	$ids = get_post_meta( $post->ID, '_uncode_featured_media', 1);

	if ( function_exists( 'vc_editor_post_types' ) ) {
		$vc_post_type = vc_editor_post_types();
		if (!in_array($post->post_type, $vc_post_type)) $vc_message = esc_html__('WPBakery Page Builder is not active for this post type. Please activate it inside "WPBakery Page Builder > Role Manager"','uncode');
		else $vc_message = '';
	} else {
		$vc_message = esc_html__('WPBakery Page Builder is not active. Please activate it inside "Uncode > Install Plugins > Uncode WPBakery Page Builder"','uncode');
	}

	if ( $vc_message !== '' ) $vc_message = '<p class="notice notice-warning"><b>' . $vc_message . '</b></p>';

	?>

	<input type="hidden" name="uncode_medias_noncedata" id="uncode_medias_noncedata" value="<?php echo wp_create_nonce( 'uncode_medias_noncedata' ); ?>" />

	<div class="edit_form_line">
		<input type="hidden" class="wpb_vc_param_value uncode_gallery_attached_images_ids medias media_element" name="medias" value="<?php echo esc_attr($ids); ?>">
		<div class="gallery_widget_site_images"></div>
			<?php echo $vc_message; ?>
   		<a class="add_media_widget vc_btn vc_btn-sm vc_btn-primary add_media_widget--with-galleries" href="#" use-single="false" title="Add media"><?php esc_html_e( 'Select medias', 'uncode' ); ?></a>
   		<a href="#" class="vc_btn vc_btn-sm vc_btn-grey btn-remove-all"<?php if ($ids === '') echo ' style="display:none;"'; ?>><?php esc_html_e( 'Remove All', 'uncode' ); ?></a>
   		<div class="uncode_widget_attached_images">
			<ul class="uncode_widget_attached_images_list">
				<?php echo (( $ids != '' && function_exists('uncode_fieldAttachedMedia') ) ? uncode_fieldAttachedMedia( explode( ",", $ids ) ) : ''); ?>
			</ul>
			<div style="clear:both;"></div>
		</div>
   	</div>

   	<?php if ( $post->post_type != 'uncode_gallery' ) : ?>
		<?php
			$media_display = get_post_meta( $post->ID, '_uncode_featured_media_display', 1);
		?>
		<hr />
		<div class="edit_form_line">
			<p>
				<strong><?php esc_html_e( 'Post media display', 'uncode' )?></strong>
				<label class="screen-reader-text" for="media_display"><?php esc_html_e( 'Post media display', 'uncode' )?></label>
			</p>
			<p>
				<select name="media_display">
					<option value="carousel" <?php if ( isset ( $media_display ) ) selected( $media_display, 'carousel' ); ?>><?php esc_html_e( 'Carousel', 'uncode' )?></option>';
					<option value="stack" <?php if ( isset ( $media_display ) ) selected( $media_display, 'stack' ); ?>><?php esc_html_e( 'Stack', 'uncode' )?></option>';
					<option value="isotope" <?php if ( isset ( $media_display ) ) selected( $media_display, 'isotope' ); ?>><?php esc_html_e( 'Isotope', 'uncode' )?></option>';
				</select>
			</p>
		</div>
	<?php endif; ?>
	<?php
}

/**
 * Register Media meta box
 */
function uncode_core_register_metabox() {
	// Return early if Uncode is not active
	if ( ! function_exists( 'uncode_get_post_types' ) || ! function_exists( 'uncode_is_gutenberg_current_editor' ) ) {
		return;
	}

	$uncode_post_types   = uncode_get_post_types(true);
	$uncode_post_types[] = 'uncode_gallery';

	foreach ( $uncode_post_types as $post_type ) {
		if ( ! uncode_is_gutenberg_current_editor( $post_type ) ) {
			add_meta_box( 'uncode_gallery_div', esc_html__( 'Medias', 'uncode' ), 'uncode_core_display_metabox', $post_type, 'normal', 'default' );
		}
	}
}
add_action( 'add_meta_boxes', 'uncode_core_register_metabox' );

/**
 * Save Media meta box
 */
function uncode_core_save_media_metadata( $post_id, $post ) {
	if ( empty( $_POST['uncode_medias_noncedata'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['uncode_medias_noncedata'], 'uncode_medias_noncedata' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return;
	}

	$value_id = $_POST['medias'];
	$key_id = '_uncode_featured_media';

	$value_display = $_POST['media_display'];
	$key_display = '_uncode_featured_media_display';

	if ( $post->post_type == 'revision' ) {
		return;
	}

	if ( get_post_meta( $post->ID, $key_id, FALSE ) ) {
		update_post_meta( $post->ID, $key_id, $value_id );
	} else {
		add_post_meta( $post->ID, $key_id, $value_id );
	}
	if ( ! $value_id ) {
		delete_post_meta( $post->ID, $key_id );
	}

	if ( get_post_meta( $post->ID, $key_display, FALSE ) ) {
		update_post_meta( $post->ID, $key_display, $value_display );
	} else {
		add_post_meta( $post->ID, $key_display, $value_display );
	}
	if ( ! $value_display ) {
		delete_post_meta( $post->ID, $key_display );
	}

}
add_action( 'save_post', 'uncode_core_save_media_metadata', 1, 2 );

/**
 * Check if author module exists from previous version, otherwise set default values.
 * @since Uncode 1.6.1
 */
function uncode_check_for_author_module() {
	if ( defined( 'UNCODE_SLIM' ) && ! get_option('uncode_check_for_author_module') ) {
		$options = get_option( ot_options_id() );

		if ( is_array( $options ) ) {
			foreach ( $options as $option => $value ) {
				if ( strpos( $option, '_uncode_post_index_' ) === 0 ) {
					$new_option           = str_replace( '_post_index_', '_author_index_', $option );
					$options[$new_option] = $value;
				}
			}
		}

		update_option( 'uncode_check_for_author_module', true );
		update_option( ot_options_id(), $options );
	}
}
add_action( 'admin_init', 'uncode_check_for_author_module' );

/**
 * Add admin bar Uncode support button
 */
function uncode_support_admin_bar_menu( $wp_admin_bar ) {
	if ( ! function_exists( '_uncode_admin_help' ) || ! is_admin_bar_showing() || ot_get_option('_uncode_admin_help') === 'off' || defined('ENVATO_HOSTED_SITE') ) {
		return;
	}

	$wp_admin_bar->add_node( array(
		'id'      => 'uncode-help',
		'title'   => esc_html__( 'Uncode Help Center', 'uncode' ),
		'href'    => 'https://support.undsgn.com/hc/',
		'meta'    => array( 'class' => 'uncode-support', 'target' => '_blank' )
	) );
}

add_action( 'admin_bar_menu', 'uncode_support_admin_bar_menu', 9999 );

/**
 * Add support for SVG uploads
 */
function uncode_core_mime_types($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter('upload_mimes', 'uncode_core_mime_types');

function uncode_core_fix_mime_type_svg($data=null, $file=null, $filename=null, $mimes=null) {
    $ext = isset($data['ext']) ? $data['ext'] : '';
	if(strlen($ext) < 1) {
		$ext = strtolower(end(explode('.', $filename)));
	}
	if($ext === 'svg') {
		$data['type'] = 'image/svg+xml';
		$data['ext'] = 'svg';
	}
	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'uncode_core_fix_mime_type_svg', 75, 4 );

/**
* Register menu widget
*/
function uncode_custom_menu_widget() {
	register_widget("Uncode_Nav_Menu_Widget");
}
