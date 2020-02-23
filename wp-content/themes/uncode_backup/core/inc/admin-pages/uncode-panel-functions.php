<?php
/**
 * Functions for the Uncode panel menu.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get panel menu pages.
 *
 * @return array
 */
function uncode_get_admin_panel_menu_pages() {
	$welcome_desc = sprintf( esc_html__( "Thank you for choosing %s theme from ThemeForest. Please register your purchase and make sure that you've fulfilled all of the requirements.", "uncode" ), UNCODE_NAME );
	if ( defined('ENVATO_HOSTED_SITE') ) {
		$welcome_desc = sprintf( esc_html__( "%s - Creative Multiuse WordPress Theme is now installed and ready to use!", "uncode" ), UNCODE_NAME );
	}
	$pages = array(
		'welcome' => array(
			'title'       => esc_html__( 'Welcome', 'uncode' ),
			'page_title'  => esc_html__( 'Welcome to Uncode', 'uncode' ),
			'description' => $welcome_desc,
			'url'         => admin_url( 'admin.php?page=uncode-system-status' )
		),
		'plugins' => array(
			'title'       => esc_html__( 'Plugins', 'uncode' ),
			'page_title'  => esc_html__( 'Plugins', 'uncode' ),
			'description' => esc_html__( 'Uncode Core and Uncode WPBakery Page Builder are the only required plugins. Any other plugins are optional.', 'uncode' ),
			'url'         => admin_url( 'admin.php?page=uncode-plugins' )
		),
	);

	if ( class_exists( 'UncodeCore_Plugin' ) && defined( 'UNCODE_CORE_ADVANCED' ) ) {
		$pages[ 'import' ] = array(
			'title'       => esc_html__( 'Import Demo', 'uncode' ),
			'page_title'  => esc_html__( 'Import Demo', 'uncode' ),
			'description' => esc_html__( 'Here you can import demo layouts. This is the easiest way to start building your site. Before you install any demos, please read through the following information.', 'uncode' ),
			'url'         => admin_url( 'admin.php?page=uncode-import-demo' )
		);

		$pages[ 'fonts' ] = array(
			'title'       => esc_html__( 'Font Stacks', 'uncode' ),
			'page_title'  => esc_html__( 'Font Stacks', 'uncode' ),
			'description' => esc_html__( 'Import fonts from the most popular fonts libraries and create your Font Stacks.', 'uncode' ),
			'url'         => admin_url( 'admin.php?page=uncode-font-stacks' )
		);

		$pages[ 'utils' ] = array(
			'title'       => esc_html__( 'Options Utils', 'uncode' ),
			'page_title'  => esc_html__( 'Options Utils', 'uncode' ),
			'description' => esc_html__( 'Find useful tools to save as manual backup or to export/import your Theme Options.', 'uncode' ),
			'url'         => admin_url( 'admin.php?page=uncode-settings' )
		);
	}

	if ( ot_get_option('_uncode_admin_help') !== 'off' && ! defined('ENVATO_HOSTED_SITE') ) {
		$pages['support'] = array(
			'title'       => esc_html__( 'Support', 'uncode' ),
			'page_title'  => esc_html__( 'Support', 'uncode' ),
			'description' => esc_html__( 'Our online documentation is an incredible resource for learning how to use Uncode. We also offer private support throughout our Help Center.', 'uncode' ),
			'url'         => admin_url( 'admin.php?page=uncode-support' )
		);
	}

	return apply_filters( 'uncode_get_admin_panel_menu_pages', $pages );
}

/**
 * Output uncode admin pages title.
 *
 * @return string
 */
function uncode_admin_panel_page_title( $page_id, $data = false ) {
	$pages = uncode_get_admin_panel_menu_pages();

	ob_start();
	?>

	<h2></h2><!-- empty h2 for admin notices -->

	<h1><?php echo esc_html( $data ? $data[ 'page_title' ] : $pages[ $page_id ][ 'page_title' ] ); ?></h1>

	<div class="about-text">
		<?php echo esc_html( $data ? $data[ 'description' ] : $pages[ $page_id ][ 'description' ] ); ?>
	</div>

	<?php
	return ob_get_clean();
}

/**
 * Output uncode panel menu.
 *
 * @param  string $active_tab
 * @return string
 */
function uncode_admin_panel_menu( $active_tab ) {
	$pages = uncode_get_admin_panel_menu_pages();

	ob_start();
	?>

	<div class="uncode-admin-panel-menu">
		<ul class="uncode-admin-panel-menu__list">

			<?php foreach ( $pages as $page_id => $page ) : ?>
				<li class="uncode-admin-panel-menu__item uncode-admin-panel-menu__item--<?php echo esc_attr( $page_id ); ?>">

					<?php if ( $active_tab == $page_id ) : ?>

						<span class="uncode-admin-panel-menu__link uncode-admin-panel-menu__link--<?php echo esc_attr( $page_id ); ?> uncode-admin-panel-menu__link--active"><?php echo esc_attr( $page[ 'title' ] ); ?></span>
					<?php else : ?>

						<a href="<?php echo esc_url( $page[ 'url' ] ) ?>" class="uncode-admin-panel-menu__link uncode-admin-panel-menu__link--<?php echo esc_attr( $page_id ); ?>"><?php echo esc_attr( $page[ 'title' ] ); ?></a>
					<?php endif; ?>

				</li>
			<?php endforeach; ?>

		</ul>
	</div>

	<?php
	return ob_get_clean();
}

/**
 * Output markup before TGMPA form.
 * We are using an action to have less changes in the original TGMPA class.
 *
 * This markup replaces the opening <div class="tgmpa wrap"> div
 *
 * @return string
 */
function uncode_open_tgmpa_form() {
	ob_start();
	?>
	<div class="tgmpa wrap uncode-wrap">
		<?php echo uncode_admin_panel_page_title( 'plugins' ); ?>

		<div class="uncode-admin-panel">
			<?php echo uncode_admin_panel_menu( 'plugins' ); ?>

			<div class="uncode-admin-panel__content">
				<h2 class="uncode-admin-panel__heading"><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<?php
	echo ob_get_clean();
}
add_action( 'uncode_before_tgmpa_form', 'uncode_open_tgmpa_form' );

/**
 * Output markup after TGMPA form.
 * We are using an action to have less changes in the original TGMPA class.
 *
 * This markup replaces the closing <div class="tgmpa wrap"> div
 *
 * @return string
 */
function uncode_close_tgmpa_form() {
	ob_start();
	?>
			</div><!-- .uncode-admin-panel__content -->
		</div><!-- .uncode-admin-panel -->
	</div><!-- .uncode-wrap -->

	<?php
	echo ob_get_clean();
}
add_action( 'uncode_after_tgmpa_form', 'uncode_close_tgmpa_form' );

/**
 * iFrame style on plugin update
 *
 * @since 1.9
 */
add_action( 'admin_head', 'uncode_remove_iframe_style' );
function uncode_remove_iframe_style(){
	$screen = get_current_screen();
	if ( isset( $screen->id ) && $screen->id==='update' ) {
		?>
<style type="text/css">
	body.iframe {
		background: none !important;
		height: auto !important;
	}
	body.iframe p {
		color: #666666;
		margin-top: 0;
	}
</style>
		<?php
	}
}
