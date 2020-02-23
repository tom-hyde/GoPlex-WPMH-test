<?php

/**
 * uncode functions and definitions
 *
 * @package uncode
 */
update_option( 'envato_purchase_code_13373220', 'ef26****-****-****-****-********3e9d' );
$ok_php = true;
if ( function_exists( 'phpversion' ) ) {
	$php_version = phpversion();
	if (version_compare($php_version,'5.3.0') < 0) {
		$ok_php = false;
	}
}
if (!$ok_php && !is_admin()) {
	$title = esc_html__( 'PHP version obsolete','uncode' );
	$html = '<h2>' . esc_html__( 'Ooops, obsolete PHP version' ,'uncode' ) . '</h2>';
	$html .= '<p>' . sprintf( wp_kses( 'We have coded the Uncode theme to run with modern technology and we have decided not to support the PHP version 5.2.x just because we want to challenge our customer to adopt what\'s best for their interests.%sBy running obsolete version of PHP like 5.2 your server will be vulnerable to attacks since it\'s not longer supported and the last update was done the 06 January 2011.%sSo please ask your host to update to a newer PHP version for FREE.%sYou can also check for reference this post of WordPress.org <a href="https://wordpress.org/about/requirements/">https://wordpress.org/about/requirements/</a>' ,'uncode', array('a' => 'href') ), '</p><p>', '</p><p>', '</p><p>') . '</p>';

	wp_die( $html, $title, array('response' => 403) );
}

/**
 * Load core utilities functions.
 */
require_once get_template_directory() . '/core/inc/core-utilities.php';

/**
 * Install functions.
 */
require_once get_template_directory() . '/core/inc/install.php';

/**
 * Load the main functions.
 */
require_once get_template_directory() . '/core/inc/main.php';

/**
 * Load privacy functions
 */
require_once get_template_directory() . '/core/inc/privacy.php';

/**
 * Load API functions.
 */
require_once get_template_directory() . '/core/inc/api/loader.php';

/**
 * Load the admin functions.
 */
require_once get_template_directory() . '/core/inc/admin.php';

/**
 * Load the uncode export file.
 */
require_once get_template_directory() . '/core/inc/export/uncode_export.php';

/**
 * Load the color system.
 */
require_once get_template_directory() . '/core/inc/colors.php';

/**
 * Load TGM plugins activation.
 */
require_once get_template_directory() . '/core/plugins_activation/init.php';

/**
 * Load the media enhanced function.
 */
require_once( ABSPATH . WPINC . '/class-oembed.php' );
require_once get_template_directory() . '/core/inc/media-enhanced.php';

/**
 * Load the bootstrap navwalker.
 */
require_once get_template_directory() . '/core/inc/wp-bootstrap-navwalker.php';

/**
 * Load the bootstrap navwalker.
 */
require_once get_template_directory() . '/core/inc/uncode-comment-walker.php';

/**
 * Load menu builder.
 */
if ($ok_php) {
	require_once get_template_directory() . '/partials/menus.php';
}

/**
 * Load header builder.
 */
if ($ok_php) {
	require_once get_template_directory() . '/partials/headers.php';
}

/**
 * Load elements partial.
 */
if ($ok_php) {
	require_once get_template_directory() . '/partials/elements.php';
}

/**
 * Custom template tags for this theme.
 */
require_once get_template_directory() . '/core/inc/template-tags.php';

/**
 * Helpers functions.
 */
require_once get_template_directory() . '/core/inc/helpers.php';
require_once get_template_directory() . '/core/inc/adaptive-images.php';

/**
 * Customizer additions.
 */
require_once get_template_directory() . '/core/inc/customizer.php';

/**
 * Customizer WooCommerce additions.
 */
if (class_exists( 'WooCommerce' )) {
	require_once get_template_directory() . '/core/inc/customizer-woocommerce.php';
}

/**
 * Load Jetpack compatibility file.
 */
require_once get_template_directory() . '/core/inc/jetpack.php';

/**
 * Load gallery functions
 */
require_once get_template_directory() . '/core/inc/galleries.php';

/**
 * Load third-party compatibility file.
 */
require_once get_template_directory() . '/core/inc/compatibility/compatibility.php';

/**
 * Deprecated functions.
 */
require_once get_template_directory() . '/core/inc/deprecated-functions.php';

add_action( 'after_setup_theme', 'uncode_related_post_call' );
if ( ! function_exists( 'uncode_related_post_call' ) ) :
/**
 * @since Uncode 1.5.0
 * Additional post type for related posts plugin
 */
function uncode_related_post_call() {
	if ( class_exists( 'RP4WP' ) ) {
		require_once get_template_directory() . '/core/inc/related-posts.php';
	}
}
endif;
