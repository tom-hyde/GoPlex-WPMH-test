<?php

/**
 * Plugin Name: WPBakery Page Builder (Visual Composer) Clipboard
 * Description: Clipboard and template manager for WPBakery Page Builder (Visual Composer)
 * Version: 4.5.1
 * Author: bitorbit
 * Author URI: http://codecanyon.net/user/bitorbit
 */

function vc_clipboard() {
	wp_enqueue_style( 'vc_clipboard', plugins_url( 'style.css', __FILE__ ), [], filemtime( plugin_dir_path( __FILE__ ) . 'style.css' ) );
	wp_register_script( 'vc_clipboard', plugins_url( 'script.min.js', __FILE__ ), [], filemtime( plugin_dir_path( __FILE__ ) . 'script.min.js' ) );
	
	// Localize the script with new data
	$translation_array = array(
		'copy' => __( 'Copy', 'vc_clipboard' ),
		'copy_plus' => __( 'Copy+', 'vc_clipboard' ),
		'cut' => __( 'Cut', 'vc_clipboard' ),
		'cut_plus' => __( 'Cut+', 'vc_clipboard' ),
		'paste' => __( 'Paste', 'vc_clipboard' ),
		
		'copy_s' => __( 'C', 'vc_clipboard' ),
		'copy_plus_s' => __( 'C+', 'vc_clipboard' ),
		'cut_s' => __( 'X', 'vc_clipboard' ),
		'cut_plus_s' => __( 'X+', 'vc_clipboard' ),
		'paste_s' => __( 'P', 'vc_clipboard' ),
		
		'copy_this_section' => __( 'Copy this section', 'vc_clipboard' ),
		'copy_to_clipboard_stack' => __( 'Copy to clipboard stack', 'vc_clipboard' ),
		'cut_this_section' => __( 'Cut this section', 'vc_clipboard' ),
		'move_to_clipboard_stack' => __( 'Move to clipboard stack', 'vc_clipboard' ),
		'paste_inside_after_this_section' => __( 'Paste inside/after this section', 'vc_clipboard' ),
		'click_to_clear_clipboard' => __( 'Click to clear clipboard', 'vc_clipboard' ),
		'copy_this_row' => __( 'Copy this row', 'vc_clipboard' ),
		'cut_this_row' => __( 'Cut this row', 'vc_clipboard' ),
		'paste_after_this_row' => __( 'Paste after this row', 'vc_clipboard' ),
		'copy_content_this_column' => __( 'Copy content of this column', 'vc_clipboard' ),
		'copy_content_this_section' => __( 'Copy content of this section', 'vc_clipboard' ),
		'cut_content_this_column' => __( 'Cut content of this column', 'vc_clipboard' ),
		'cut_content_this_section' => __( 'Cut content of this section', 'vc_clipboard' ),
		'paste_inside_this_column' => __( 'Paste inside this column', 'vc_clipboard' ),
		'copy_this_element' => __( 'Copy this element', 'vc_clipboard' ),
		'cut_this_element' => __( 'Cut this element', 'vc_clipboard' ),
		'paste_after_this_element' => __( 'Paste after this element', 'vc_clipboard' ),
		'paste_inside_this_section' => __( 'Paste inside this section', 'vc_clipboard' ),
		
		'exp' => __( 'Export', 'vc_clipboard' ),
		'imp' => __( 'Import', 'vc_clipboard' ),
		'load_from_google_cloud' => __( 'Load from Google Cloud', 'vc_clipboard' ),
		'gc_load' => __( 'GC Load', 'vc_clipboard' ),
		'save_to_google_cloud' => __( 'Save to Google Cloud', 'vc_clipboard' ),
		'gc_save' => __( 'GC Save', 'vc_clipboard' ),
		'name' => __( 'Name:', 'vc_clipboard' ),
		'submit' => __( 'Submit', 'vc_clipboard' ),
		'cancel' => __( 'Cancel', 'vc_clipboard' ),
		'deactivate' => __( 'Deactivate', 'vc_clipboard' ),
		'license' => __( 'License', 'vc_clipboard' ),
		'activate_product_license' => __( 'Activate product license', 'vc_clipboard' ),
		'purchase_code' => __( 'Item Purchase Code:', 'vc_clipboard' ),
		'email' => __( 'Email (optional) - enter to get important info and special offers:', 'vc_clipboard' ),
		'license_text' => __( 'A valid license qualifies you for support and enables Google Cloud template manager. One license may only be used for one WPBakery Page Builder (Visual Composer) Clipboard installation on one WordPress site at a time. If you have activated your license on another site, then you should <a href="http://codecanyon.net/item/visual-composer-clipboard/8897711" class="vcc_obtain" target="_blank">obtain a new license</a>.', 'vc_clipboard' ),
		'preferences' => __( 'Preferences', 'vc_clipboard' ),
		'prefs' => __( 'Prefs', 'vc_clipboard' ),
		'short_commands' => __( 'Short commands', 'vc_clipboard' ),
		'toolbar_initially_closed' => __( 'Toolbar Initially Closed', 'vc_clipboard' ),
		'hide_cut_buttons' => __( 'Hide Cut/Cut+ Buttons', 'vc_clipboard' ),
		'hide_paste_button' => __( 'Hide Paste Button', 'vc_clipboard' ),
		'hide_export_import' => __( 'Hide Export/Import', 'vc_clipboard' ),
		'hide_gc_buttons' => __( 'Hide GC Buttons', 'vc_clipboard' ),
		'hide_license_button' => __( 'Hide License Button', 'vc_clipboard' ),
		
		'pasting' => __( 'Pasting, please wait...', 'vc_clipboard' ),
		
		'cant_mix' => __( 'Can\'t mix ', 'vc_clipboard' ),
		'with_text' => __( ' with ', 'vc_clipboard' ),
		'exclamation_mark_start' => __( '', 'vc_clipboard' ),
		'exclamation_mark_end' => __( '!', 'vc_clipboard' ),
		
		'error_01' => __( 'Error 01. Please try later.', 'vc_clipboard' ),
		'error_02' => __( 'Error 02. Please try later.', 'vc_clipboard' ),
		'error_03' => __( 'Error 03. Please try later.', 'vc_clipboard' ),
		'error_04' => __( 'Error 04. Please try later.', 'vc_clipboard' ),
		'error_05' => __( 'Error 05. Please try later.', 'vc_clipboard' ),
		'error_06' => __( 'Error. Please check purchase code and try again.', 'vc_clipboard' ),
		'error_06_d' => __( 'Error 06. Please try later.', 'vc_clipboard' ),
		'error_07' => __( 'Error 07. Please try later.', 'vc_clipboard' ),
		'error_08' => __( 'Error 08. Please try later.', 'vc_clipboard' ),
		'error_09' => __( 'Error 09. Please try later.', 'vc_clipboard' ),
		'error_10' => __( 'Error 10. Please try later.', 'vc_clipboard' ),
		
		'clear_clipboard' => __( 'Clear clipboard?', 'vc_clipboard' ),
		
		'no_saved_templates' => __( 'No saved templates.', 'vc_clipboard' ),
		'load' => __( 'Load ', 'vc_clipboard' ),
		'delete_text' => __( 'Delete ', 'vc_clipboard' ),
		'enter_template_name' => __( 'Enter template name.', 'vc_clipboard' ),
		
		'clipboard_template_saved' => __( 'Clipboard template saved.', 'vc_clipboard' ),
		'name_already_taken' => __( 'Name already taken.', 'vc_clipboard' ),
		'nothing_to_save' => __( 'Nothing to save.', 'vc_clipboard' ),
		'now_activated' => __( 'WPBakery Page Builder (Visual Composer) Clipboard license is now activated!', 'vc_clipboard' ),
		'now_deactivated' => __( 'WPBakery Page Builder (Visual Composer) Clipboard license has been deactivated!', 'vc_clipboard' ),
		
		'cant_paste' => __( 'Can\'t paste ', 'vc_clipboard' ),
		'inside_after_vc_section' => __( ' inside/after vc_section!', 'vc_clipboard' ),
		'after_vc_row' => __( ' after vc_row!', 'vc_clipboard' ),
		'after_vc_row_inner' => __( ' after vc_row_inner!', 'vc_clipboard' ),
		'inside' => __( ' inside ', 'vc_clipboard' ),
		'to_root' => __( ' to root!\n\nYou can only paste it inside a column.', 'vc_clipboard' ),
		'content_after' => __( ' content after ', 'vc_clipboard' ),
		'you_can_only' => __( '\n\nYou can only paste it inside a column.', 'vc_clipboard' ),
		'cant_paste_vc_row_inner_as_root' => __( 'Can\'t paste vc_row_inner as root element!\n\nYou can only paste it inside a column.', 'vc_clipboard' ),
		'cant_paste_vc_column_content_to_root' => __( 'Can\'t paste vc_column content to root!\n\nYou can only paste it inside a column.', 'vc_clipboard' ),
		'cant_paste_vc_column_inner_content_to_root' => __( 'Can\'t paste vc_column_inner content to root!\n\nYou can only paste it inside a column.', 'vc_clipboard' ),
		'cant_paste_vc_column_content_to_vc_column_inner' => __( 'Can\'t paste vc_column content to vc_column_inner!\n\nYou can only paste it inside a column.', 'vc_clipboard' ),
		'cant_paste_vc_row_inner_to_vc_column_inner' => __( 'Can\'t paste vc_row_inner to vc_column_inner!\n\nYou can only paste it inside a column.', 'vc_clipboard' ),
		
		'column_empty' => 'Column is empty!',
		'section_empty' => 'Section is empty!',
		'tab_empty' => 'Tab is empty!',
		'clipboard_empty' => 'Clipboard is empty!',
		
		'license_already_activated_on' => 'This purhase code has been already used on: ',

	);
	wp_localize_script( 'vc_clipboard', 'vc_clipboard_text', $translation_array );

	// Enqueued script with localized data.
	wp_enqueue_script( 'vc_clipboard' );	
}
function vc_clipboard_custom_js() {
	echo '<script>';
	echo 'window.vc_clipboard_plugins_url="' . plugins_url() . '";window.vc_clipboard_site="' . ( isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'] ) . '";';
	echo '</script>';
}
add_action( 'admin_enqueue_scripts', 'vc_clipboard' );
add_action( 'admin_head', 'vc_clipboard_custom_js' );

/**
 * Load plugin textdomain.
 *
 * @since 4.5.0
 */
function vc_clipboard_load_textdomain() {
  load_plugin_textdomain( 'vc_clipboard', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}
add_action( 'plugins_loaded', 'vc_clipboard_load_textdomain' );

/**
 * AJAX activation.
 *
 * @since 4.5.0
 */
function vc_clipboard_activate() {
	$license_key = $_POST['license_key'];
	$email = $_POST['email'];
	update_option( 'envato_purchase_code_8897711', $license_key );
	update_option( 'vc_clipboard_email', $email );
	echo 'ok';
	wp_die();
}
add_action( 'wp_ajax_vc_clipboard_activate', 'vc_clipboard_activate' );

/**
 * AJAX deactivation.
 *
 * @since 4.5.0
 */
function vc_clipboard_deactivate() {
	delete_option( 'envato_purchase_code_8897711' );
	delete_option( 'vc_clipboard_email' );
	echo 'ok';
	wp_die();
}
add_action( 'wp_ajax_vc_clipboard_deactivate', 'vc_clipboard_deactivate' );

function vc_clipboard_javascript() {
	$license_key = get_option( 'envato_purchase_code_8897711' );
	$email = get_option( 'vc_clipboard_email' );
	?>
	<script>
		window.vc_clipboard_license_key = <?php echo $license_key ? '"' . $license_key . '"' : 'false'; ?>;
		window.vc_clipboard_email = <?php echo $email ? '"' . $email . '"' : '""'; ?>;
	</script><?php
}
add_action( 'admin_footer', 'vc_clipboard_javascript' ); // Write our JS below here