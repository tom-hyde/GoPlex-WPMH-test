<?php

/**
 * Class Radium_Theme_Importer
 *
 * This class provides the capability to import demo content as well as import widgets and WordPress menus
 *
 * @since 2.2.0
 *
 * @category RadiumFramework
 * @package  NewsCore WP
 * @author   Franklin M Gitonga
 * @link     http://radiumthemes.com/
 *
 */
class Radium_Theme_Importer {

	/**
	 * Holds a copy of the object for easy reference.
	 *
	 * @since 2.2.0
	 *
	 * @var object
	 */
	public $theme_options_file;

	/**
	 * Holds a copy of the object for easy reference.
	 *
	 * @since 2.2.0
	 *
	 * @var object
	 */
	public $widgets;

	/**
	 * Holds a copy of the object for easy reference.
	 *
	 * @since 2.2.0
	 *
	 * @var object
	 */
	public $content_demo;
	public $import_menu;

	/**
	 * Flag imported to prevent duplicates
	 *
	 * @since 2.2.0
	 *
	 * @var object
	 */
	public $flag_as_imported = array();

	/**
	 * Holds a copy of the object for easy reference.
	 *
	 * @since 2.2.0
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Default font stack.
	 */
	private $font_stack = array(
		'font_stack' => '[{&quot;family&quot;:&quot;Poppins&quot;,&quot;familyID&quot;:&quot;&quot;,&quot;source&quot;:&quot;Google Web Fonts&quot;,&quot;stub&quot;:&quot;&quot;,&quot;generic&quot;:&quot;&quot;,&quot;variants&quot;:&quot;300,regular,500,600,700&quot;,&quot;selvariants&quot;:&quot;300,regular,500,600,700&quot;,&quot;variantselectors&quot;:&quot;&quot;,&quot;files&quot;:&quot;&quot;,&quot;subsets&quot;:&quot;devanagari,latin-ext,latin-ext&quot;,&quot;selsubsets&quot;:&quot;devanagari,latin-ext,latin-ext&quot;},{&quot;family&quot;:&quot;Droid Serif&quot;,&quot;familyID&quot;:&quot;&quot;,&quot;source&quot;:&quot;Google Web Fonts&quot;,&quot;stub&quot;:&quot;&quot;,&quot;generic&quot;:&quot;&quot;,&quot;variants&quot;:&quot;regular,italic,700,700italic&quot;,&quot;selvariants&quot;:&quot;regular,italic,700,700italic&quot;,&quot;variantselectors&quot;:&quot;&quot;,&quot;files&quot;:&quot;&quot;,&quot;subsets&quot;:&quot;latin&quot;,&quot;selsubsets&quot;:&quot;latin&quot;},{&quot;family&quot;:&quot;Playfair Display&quot;,&quot;familyID&quot;:&quot;&quot;,&quot;source&quot;:&quot;Google Web Fonts&quot;,&quot;stub&quot;:&quot;&quot;,&quot;generic&quot;:&quot;&quot;,&quot;variants&quot;:&quot;regular,italic,700,700italic,900,900italic&quot;,&quot;selvariants&quot;:&quot;regular,italic,700,700italic,900,900italic&quot;,&quot;variantselectors&quot;:&quot;&quot;,&quot;files&quot;:&quot;&quot;,&quot;subsets&quot;:&quot;latin,latin-ext,cyrillic&quot;,&quot;selsubsets&quot;:&quot;latin,latin-ext,cyrillic&quot;},{&quot;family&quot;:&quot;Roboto&quot;,&quot;familyID&quot;:&quot;&quot;,&quot;source&quot;:&quot;Google Web Fonts&quot;,&quot;stub&quot;:&quot;&quot;,&quot;generic&quot;:&quot;&quot;,&quot;variants&quot;:&quot;100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&quot;,&quot;selvariants&quot;:&quot;100,100italic,300,300italic,regular,italic,500,500italic,700,700italic,900,900italic&quot;,&quot;variantselectors&quot;:&quot;&quot;,&quot;files&quot;:&quot;&quot;,&quot;subsets&quot;:&quot;vietnamese,greek,cyrillic-ext,cyrillic,greek-ext,latin,latin-ext&quot;,&quot;selsubsets&quot;:&quot;vietnamese,greek,cyrillic-ext,cyrillic,greek-ext,latin,latin-ext&quot;},{&quot;family&quot;:&quot;Quicksand&quot;,&quot;familyID&quot;:&quot;&quot;,&quot;source&quot;:&quot;Google Web Fonts&quot;,&quot;stub&quot;:&quot;&quot;,&quot;generic&quot;:&quot;&quot;,&quot;variants&quot;:&quot;300,regular,500,700&quot;,&quot;selvariants&quot;:&quot;300,regular,500,700&quot;,&quot;variantselectors&quot;:&quot;&quot;,&quot;files&quot;:&quot;&quot;,&quot;subsets&quot;:&quot;vietnamese,latin,latin-ext&quot;,&quot;selsubsets&quot;:&quot;vietnamese,latin,latin-ext&quot;},{&quot;family&quot;:&quot;Lora&quot;,&quot;familyID&quot;:&quot;&quot;,&quot;source&quot;:&quot;Google Web Fonts&quot;,&quot;stub&quot;:&quot;&quot;,&quot;generic&quot;:&quot;&quot;,&quot;variants&quot;:&quot;regular,italic,700,700italic&quot;,&quot;selvariants&quot;:&quot;regular,italic,700,700italic&quot;,&quot;variantselectors&quot;:&quot;&quot;,&quot;files&quot;:&quot;&quot;,&quot;subsets&quot;:&quot;vietnamese,cyrillic,latin,cyrillic-ext,latin-ext&quot;,&quot;selsubsets&quot;:&quot;vietnamese,cyrillic,latin,cyrillic-ext,latin-ext&quot;},{&quot;family&quot;:&quot;Roboto Condensed&quot;,&quot;familyID&quot;:&quot;&quot;,&quot;source&quot;:&quot;Google Web Fonts&quot;,&quot;stub&quot;:&quot;&quot;,&quot;generic&quot;:&quot;&quot;,&quot;variants&quot;:&quot;300,300italic,regular,italic,700,700italic&quot;,&quot;selvariants&quot;:&quot;300,300italic,regular,italic,700,700italic&quot;,&quot;variantselectors&quot;:&quot;&quot;,&quot;files&quot;:&quot;&quot;,&quot;subsets&quot;:&quot;greek,vietnamese,cyrillic,greek-ext,latin,cyrillic-ext,latin-ext&quot;,&quot;selsubsets&quot;:&quot;greek,vietnamese,cyrillic,greek-ext,latin,cyrillic-ext,latin-ext&quot;}]'
	);

	/**
	 * Constructor. Hooks all interactions to initialize the class.
	 *
	 * @since 2.2.0
	 */
	public function __construct() {

		self::$instance = $this;

		$this->theme_options_file = $this->demo_files_path . $this->theme_options_file_name;
		$this->widgets = $this->demo_files_path . $this->widgets_file_name;
		$this->content_demo = $this->demo_files_path . $this->content_demo_file_name;

		add_action( 'admin_menu', array($this, 'add_admin'), 20 );

	}

	/**
	 * Add Panel Page
	 *
	 * @since 2.2.0
	 */
	public function add_admin() {
		if ( ! defined( 'UNCODE_SLIM' ) ) {
			return;
		}

		add_submenu_page('uncode-system-status', esc_html__('Import Demo','uncode'), esc_html__('Import Demo','uncode'), 'switch_themes', 'uncode-import-demo', array($this, 'demo_installer'));

	}

	/**
	 * [demo_installer description]
	 *
	 * @since 2.2.0
	 *
	 * @return [type] [description]
	 */
	public function demo_installer() {

		$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
		?>

		<div class="wrap uncode-wrap" id="uncode-import-demo">

			<?php echo uncode_admin_panel_page_title( 'import' ); ?>

			<div class="uncode-admin-panel">
				<?php echo uncode_admin_panel_menu( 'import' ); ?>

				<div class="uncode-admin-panel__content">
					<?php if ($action === '') : ?>
						<div id="import-area">
							<div class="uncode-admin-panel__left">
								<h2 class="uncode-admin-panel__heading"><?php esc_html_e( 'Import Notes', 'uncode' ); ?></h2>

								<div class="uncode-info-box">
									<p class="uncode-admin-panel__description"><?php printf(esc_html__( 'Uncode\'s main demo (200+ pages), which includes placeholder media files, can be fully imported with Import Demo or partially imported with Import Single Layouts. %s', 'uncode' ), '<a href="//support.undsgn.com/hc/en-us/articles/214001065">'.esc_html__('More info here','uncode').'</a>'); ?></p>

									<h4 class="uncode-import-description__heading"><?php echo esc_html__( 'Important', 'uncode' ); ?></h4>

									<ul class="uncode-import-description__list checklist">
										<li><?php echo esc_html__( 'Using this import feature is only recommended for fresh installations.', 'uncode' ); ?></li>
										<li><?php echo esc_html__( 'The import will merge any existing content with the Uncode demo content.', 'uncode' ); ?></li>
										<li><?php echo esc_html__( 'Make sure there are no red messages within the System Status section before proceeding.', 'uncode' ); ?></li>
										<li><?php echo esc_html__( 'Deactivate all plugins before importing, except for the theme\'s official plugins.', 'uncode' ); ?></li>
										<li><?php echo esc_html__( 'The importer will create an exact replica of the demo site, with placeholder images included.', 'uncode' ); ?></li>
										<li><?php echo esc_html__( 'No existing content or any other data will be deleted or modified during the import process.', 'uncode' ); ?></li>
										<li><?php echo esc_html__( 'The time it takes for demo imports to complete can vary, based on your server’s performance.', 'uncode' ); ?></li>
									</ul>
								</div><!-- .uncode-info-box -->
							</div><!-- .uncode-admin-panel__left -->

							<div class="uncode-admin-panel__right">
								<div class="uncode-import-methods-wrap">
									<div class="uncode-import-method uncode-import-method--import-all">
										<div>
											<?php
											global $wp_filesystem;
											if (empty($wp_filesystem)) {
											  require_once (ABSPATH . '/wp-admin/includes/file.php');
											}
											$demo_file = $this->content_demo;
											$can_read_file = true;
											if (false === ($creds = request_filesystem_credentials($demo_file, '', false, false))) {
												$can_read_file = false;
											}
											/* initialize the API */
											if ( ! WP_Filesystem($creds) ) {
												/* any problems and we exit */
												$can_read_file = false;
											}
											$response = $wp_filesystem->get_contents($demo_file);
											if($response && $can_read_file) {
												?>
												<div class="uncode-import-method-left">
													<h3 class="box-card__title"><?php esc_html_e('Import Demo', 'uncode'); ?></h3>
													<p><?php esc_html_e('Import all demos layouts and create a replica of the Uncode demo site.', 'uncode'); ?></p>
												</div><!-- .uncode-import-method-left -->
												<div class="uncode-import-method-right">
													<form class="uncode-import-form import-demo">
														<input class="uncode-import-button button button-primary uncode-ui-button" type="submit" value="<?php echo esc_html__('Import Demo', 'uncode'); ?>" />
													</form>
												</div><!-- .uncode-import-method-right -->
												<?php
											} else { ?>
												<p class="uncode-import-perms-error"><?php printf( esc_html__( 'The file %s can\'t be read. Please change file permission to 775.','uncode'), $this->content_demo ); ?></p>
											<?php
												die();
											}
											?>
										</div><!-- .uncode-import-method.uncode-import-method--import-all -->
									</div><!-- .uncode-import-methods-wrap -->

									<?php
									// include WXR file parsers
									require_once dirname( __FILE__ ) . '/parsers.php';
									$parser = new Uncode_WXR_Parser();
									$parsed_xml = $parser->parse( $this->content_demo );
									$post_array = array();
									$page_array = array();
									$portfolio_array = array();
									$gallery_array = array();
									$product_array = array();
									$original_array = array();
									foreach ($parsed_xml['posts'] as $key => $value) {
										$original_array[$value['post_id']] = $value['post_title'];
									}
									foreach ($parsed_xml['posts'] as $key => $value) {
										switch ($value['post_type']) {
											case 'post':
												$ids = array($value['post_id']);
												if (isset($value['postmeta'])) {
													foreach ($value['postmeta'] as $meta_key => $meta_value) {
														if ($meta_value['key'] === '_uncode_blocks_list') $ids[] = $meta_value['value'];
													}
												}
												$post_array[$value['post_title']] = array(
													'ids' => $ids,
												);
												break;
											case 'page':
												$ids = array($value['post_id']);
												$parent = $value['post_parent'];
												if (isset($value['postmeta'])) {
													foreach ($value['postmeta'] as $meta_key => $meta_value) {
														if ($meta_value['key'] === '_uncode_blocks_list') $ids[] = $meta_value['value'];
													}
												}
												$value_post_title = $value['post_title'];
												if ( isset( $original_array[$parent] ) && $original_array[$parent] === 'Homepages' ) {
													$value_post_title = $value_post_title . ' (Homepage)';
												}
												$page_array[$value_post_title] = array(
													'ids' => $ids,
												);
												break;
											case 'portfolio':
												$ids = array($value['post_id']);
												if (isset($value['postmeta'])) {
													foreach ($value['postmeta'] as $meta_key => $meta_value) {
														if ($meta_value['key'] === '_uncode_blocks_list') $ids[] = $meta_value['value'];
													}
												}
												$portfolio_array[$value['post_title']] = array(
													'ids' => $ids,
												);
												break;
											case 'uncode_gallery':
												$ids = array($value['post_id']);
												if (isset($value['postmeta'])) {
													foreach ($value['postmeta'] as $meta_key => $meta_value) {
														if ($meta_value['key'] === '_uncode_blocks_list') $ids[] = $meta_value['value'];
													}
												}
												$gallery_array[$value['post_title']] = array(
													'ids' => $ids,
												);
												break;
											case 'product':
												$ids = array($value['post_id']);
												if (isset($value['postmeta'])) {
													foreach ($value['postmeta'] as $meta_key => $meta_value) {
														if ($meta_value['key'] === '_uncode_blocks_list') $ids[] = $meta_value['value'];
													}
												}
												$product_array[$value['post_title']] = array(
													'ids' => $ids,
												);
												break;
										}
									}

									$is_woocommerce = class_exists( 'WooCommerce' );
									?>

									<div class="uncode-import-methods-wrap">
										<div class="uncode-import-method uncode-import-method--import-selective">
											<div class="uncode-import-method-left">
												<h3 class="box-card__title"><?php esc_html_e('Import Single Layouts', 'uncode'); ?></h3>
												<p><?php esc_html_e('Import selected layouts and create your own custom Uncode import.', 'uncode'); ?></p>
											</div><!-- .uncode-import-method-left -->
											<div class="uncode-import-method-right">
												<input id="import-single-switch" type="button" class="uncode-import-button button button-primary uncode-ui-button" value="<?php echo esc_html__('Import Layouts', 'uncode'); ?>" />
											</div><!-- .uncode-import-method-right -->
										</div><!-- .uncode-import-method.uncode-import-method--import-selective -->
									</div><!-- .uncode-import-methods-wrap -->

									<div class="uncode-import-methods-wrap">
										<div class="uncode-import-method uncode-import-method--import-menu">
											<div class="uncode-import-method-left">
												<h3 class="box-card__title"><?php esc_html_e('Import Menu', 'uncode'); ?></h3>
												<p><?php esc_html_e('Import the menus from the Uncode demo site.', 'uncode'); ?></p>
											</div><!-- .uncode-import-method-left -->
											<div class="uncode-import-method-right">
												<form class="uncode-import-form import-menu">
													<input class="uncode-import-button button button-primary uncode-ui-button" type="submit" value="<?php echo esc_html__('Import Menu', 'uncode'); ?>" />
												</form>
											</div><!-- .uncode-import-method-right -->
										</div><!-- .uncode-import-method.uncode-import-method--import-menu -->
									</div><!-- .uncode-import-methods-wrap -->

									<div class="uncode-import-methods-wrap">
										<div class="uncode-import-method uncode-import-method--import-options">
											<div class="uncode-import-method-left">
												<h3 class="box-card__title"><?php esc_html_e('Import Theme Options', 'uncode'); ?></h3>
												<p><?php esc_html_e('Import the theme options from the Uncode demo site.', 'uncode'); ?></p>
											</div><!-- .uncode-import-method-left -->
											<div class="uncode-import-method-right">
												<form class="uncode-import-form import-ot">
													<input class="uncode-import-button button button-primary uncode-ui-button" type="submit" value="<?php echo esc_html__('Import Options', 'uncode'); ?>" />
												</form>
											</div><!-- .uncode-import-method-right -->
										</div><!-- .uncode-import-method.uncode-import-method--import-options -->
									</div><!-- .uncode-import-methods-wrap -->

									<div class="uncode-import-methods-wrap">
										<div class="uncode-import-method uncode-import-method--import-widgets">
											<div class="uncode-import-method-left">
												<h3 class="box-card__title"><?php esc_html_e('Import Widgets', 'uncode'); ?></h3>
												<p><?php esc_html_e('Import the widgets from the Uncode demo site.', 'uncode'); ?></p>
											</div><!-- .uncode-import-method-left -->
											<div class="uncode-import-method-right">
												<form class="uncode-import-form import-widgets">
													<input class="uncode-import-button button button-primary uncode-ui-button" type="submit" value="<?php echo esc_html__('Import Widgets', 'uncode'); ?>" />
												</form>
											</div><!-- .uncode-import-method-right -->
										</div><!-- .uncode-import-method.uncode-import-method--import-widgets -->
									</div><!-- .uncode-import-methods-wrap -->

									<div class="uncode-import-methods-wrap">
										<div class="uncode-import-method uncode-import-method--delete-media">
											<?php /*<div class="uncode-import-method-left">
												<h3 class="box-card__title"><?php esc_html_e('Delete media', 'uncode'); ?></h3>
												<p><?php esc_html_e('Import the widgets of the Uncode demo site.', 'uncode'); ?></p>
											</div><!-- .uncode-import-method-left --> */?>
											<div class="uncode-import-method-right">
												<form class="uncode-import-form delete-media">
													<input class="uncode-import-button button button-secondary uncode-ui-button uncode-import-button--delete" type="submit" value="<?php echo esc_html__('Delete Demo Media', 'uncode'); ?>" />
												</form>
											</div><!-- .uncode-import-method-right -->
										</div><!-- .uncode-import-method.uncode-import-method--delete-media -->
									</div><!-- .uncode-import-methods-wrap -->

									<div class="uncode-singles-import-wrap" style="display: none;">

										<form class="uncode-import-form uncode-import-form--singles">
											<div class="uncode-import-single-blocks uncode-ui-layout uncode-ui-layout--<?php echo ($is_woocommerce) ? 'four' : 'three'; ?>-cols">
												<div class="uncode-import-single-block uncode-ui-layout__item uncode-ui-layout__item--<?php echo ($is_woocommerce) ? 'four' : 'three'; ?>-cols">
													<h4 class="uncode-import-single-block__title"><?php esc_html_e( 'Pages', 'uncode' ); ?></h4>
													<select class="uncode-import-single-block__select" name="post[]" multiple>
														<?php
														ksort($page_array);
														foreach ($page_array as $key => $value) {
															echo '<option value="'.esc_attr(implode(',', $value['ids'])).'">'.$key.'</option>';
														}
														?>
													</select>
												</div><!-- .uncode-import-single-block -->
												<div class="uncode-import-single-block uncode-ui-layout__item uncode-ui-layout__item--<?php echo ($is_woocommerce) ? 'four' : 'three'; ?>-cols">
													<h4 class="uncode-import-single-block__title"><?php esc_html_e( 'Posts', 'uncode' ); ?></h4>
													<select class="uncode-import-single-block__select" name="post[]" multiple>
														<?php
														ksort($post_array);
														foreach ($post_array as $key => $value) {
															echo '<option value="'.esc_attr(implode(',', $value['ids'])).'">'.$key.'</option>';
														}
														?>
													</select>
												</div><!-- .uncode-import-single-block -->
												<div class="uncode-import-single-block uncode-ui-layout__item uncode-ui-layout__item--<?php echo ($is_woocommerce) ? 'four' : 'three'; ?>-cols">
													<h4 class="uncode-import-single-block__title"><?php esc_html_e( 'Portfolios', 'uncode' ); ?></h4>
													<select class="uncode-import-single-block__select" name="post[]" multiple>
														<?php
														ksort($portfolio_array);
														foreach ($portfolio_array as $key => $value) {
															echo '<option value="'.esc_attr(implode(',', $value['ids'])).'">'.$key.'</option>';
														}
														?>
													</select>
												</div><!-- .uncode-import-single-block -->
												<?php /*<div class="uncode-import-single-block uncode-ui-layout__item uncode-ui-layout__item--<?php echo ($is_woocommerce) ? 'four' : 'three'; ?>-cols">
													<h4 class="uncode-import-single-block__title"><?php esc_html_e( 'Galleries', 'uncode' ); ?></h4>
													<select class="uncode-import-single-block__select" name="post[]" multiple>
														<?php
														ksort($gallery_array);
														foreach ($gallery_array as $key => $value) {
															echo '<option value="'.esc_attr(implode(',', $value['ids'])).'">'.$key.'</option>';
														}
														?>
													</select>
												</div><!-- .uncode-import-single-block --> */ ?>
												<?php if ( $is_woocommerce ) : ?>
													<div class="uncode-import-single-block uncode-ui-layout__item uncode-ui-layout__item--<?php echo ($is_woocommerce) ? 'four' : 'three'; ?>-cols">
														<h4 class="uncode-import-single-block__title"><?php esc_html_e( 'Products', 'uncode' ); ?></h4>
														<select class="uncode-import-single-block__select" name="post[]" multiple>
															<?php
															ksort($product_array);
															foreach ($product_array as $key => $value) {
																echo '<option value="'.esc_attr(implode(',', $value['ids'])).'">'.$key.'</option>';
															}
															?>
														</select>
													</div><!-- .uncode-import-single-block -->
												<?php endif; ?>
											</div><!-- .uncode-import-single-blocks -->

											<input class="uncode-import-button button button-primary uncode-ui-button" type="submit" style="display: none;" value="<?php echo esc_attr( 'Import Singles', 'uncode' ); ?>" />
										</form>

										<p><strong><?php esc_html_e('NB.', 'uncode'); ?></strong> <?php esc_html_e('When you import a single layout, you’re only importing the Page Builder layout. If you need to import a page that includes external elements such as a blog or portfolio, please also select those elements. To select multiple options on Windows hold down the control (ctrl) button, on Mac hold down the command button.', 'uncode'); ?></p>

									</div><!-- .uncode-singles-import-wrap -->

								</div><!-- .uncode-box-wrap -->

							</div><!-- .uncode-admin-panel__right -->

						</div><!-- #import-area -->

						<div class="uncode-import-response"></div>

						<input id="uncode-import-back" class="uncode-import-button button uncode-ui-button" type="button" value="<?php echo esc_attr__( 'Back', 'uncode' ); ?>" style="display:none;" />

					<?php elseif ( 'demo-data' == $action && check_admin_referer('radium-demo-code' , 'demononce') ) :
						$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : '';
						$theme_options = isset($_REQUEST['options']) ? $_REQUEST['options'] : '';
						$import_menu = isset($_REQUEST['menu']) ? $_REQUEST['menu'] : '';
						$widgets = isset($_REQUEST['widgets']) ? $_REQUEST['widgets'] : '';
						$delete = isset($_REQUEST['delete']) ? $_REQUEST['delete'] : '';

						$this->import_menu = ($import_menu !== '' && $import_menu === 'true') ? true : false;

						$partial_import_done_title = ( $delete !== '' && $delete === 'true' ) ? esc_html__( 'All demo medias deleted!', 'uncode' ): esc_html__( 'Import completed!', 'uncode' );

						$partial_import_done = '<div class="uncode-import-response">
							<div class="uncode-import-response-content">
								<div class="uncode-svg-success"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="-263.5 236.5 26 26"><g class="svg-success"><circle cx="-250.5" cy="249.5" r="12"/><path d="M-256.46 249.65l3.9 3.74 8.02-7.8"/></g></svg></div>
								<h4 class=" uncode-import-response__title">' . $partial_import_done_title . '</h4><div id="import-fine" style="display: none;"></div>
							</div>
						</div>';

						if ($theme_options !== '' && $theme_options === 'true') {
							$this->set_demo_theme_options( $this->theme_options_file );
							echo $partial_import_done;
						} else if ($widgets !== '' && $widgets === 'true') {
							$this->process_widget_import_file( $this->widgets );
							echo $partial_import_done;
						} else if ($delete !== '' && $delete === 'true') {
							$this->delete_demo_media();
							echo $partial_import_done;
						} else if ($this->import_menu) {
							$this->set_demo_data( $this->content_demo, '');
							if ($this->import_menu) $this->set_demo_menus();
						} else {
							if ($ids === '' || (string) $ids === '-1') {
								$this->set_demo_theme_options( $this->theme_options_file ); //import before widgets incase we need more sidebars
								$this->set_demo_data( $this->content_demo, '');
								if ($this->import_menu) $this->set_demo_menus();
								$this->process_widget_import_file( $this->widgets );
								$homepage = get_page_by_title( 'Index' );
								if ( $homepage )
								{
							    update_option( 'page_on_front', $homepage->ID );
							    update_option( 'show_on_front', 'page' );
								}
							} else {
								$this->set_demo_data( $this->content_demo, $ids );
							}
						}
					endif; ?>
				</div><!-- .uncode-admin-panel__content -->
			</div><!-- .uncode-admin-panel -->

		</div><!-- .uncode-wrap -->

		<script type="text/javascript">
			jQuery( function ( $ ) {
				'use strict';
				var runned = 0;

				function show_import_result(result, error) {
					// $('.uncode-import-response').html(result);
					// $('.uncode-import-response').show();

					// if (error) {
					// 	$('.uncode-import-response').addClass('uncode-import-response--error');
					// }

					// $('.uncode-import-loader').remove();
					// $('#uncode-import-back').show();

					$('.uncode-import-demo-modal .ui-dialog-content').html(result);
					$('.uncode-import-demo-modal .ui-dialog-titlebar button').show();
				}

				$('#import-single-switch').on('click', function(event) {
					/*$('.uncode-secondary-import').hide();
					$('.uncode-singles-import-wrap').show();
					$('#uncode-import-back').show();*/
					var import_single_content = $('.uncode-singles-import-wrap').html();
					$("<div />").html(import_single_content).dialog({
						autoOpen: true,
						modal: true,
						dialogClass: 'uncode-modal',
						title: "<?php echo esc_html__('Import Single Layouts', 'uncode'); ?>",
						minHeight: 500,
						minWidth: 500,
						width: 1200,
						position: { my: "center", at: "center", of: window },
						buttons : {
							"<?php echo esc_html__('Import', 'uncode'); ?>" : function() {
								var $form = $('form.uncode-import-form', this).submit();
								//confirmImportRun();
								$(this).dialog("close");
							},
							"<?php echo esc_html__('Cancel', 'uncode'); ?>" : function() {
								$(this).dialog("close");
							}
						},
						open: function( event, ui ) {
							$('body').addClass('overflow_hidden');
						},
						close: function( event, ui ) {
							$('body').removeClass('overflow_hidden');
						}
					});
				});
				/*$('#uncode-import-back').on('click', function() {
					//$('#import-area').show();
					$('.uncode-secondary-import').show();
					$('.uncode-singles-import-wrap').hide();
					$('#uncode-import-back').hide();
				});*/

				$(document).on('submit', '.uncode-import-form', function(e) {
					e.preventDefault();
					var _form = $(this);
					var modal_content = ''; // Will hold the modal content
					var dialog_title;

					// Title
					modal_content += '<h4><?php esc_html_e( 'Important', 'uncode' ); ?></h4>';

					// Default message
					var default_message_text = '<p><?php esc_html_e( 'This action will replace your settings.', 'uncode' ); ?></p>';
					var menu_message_text = '<p><?php esc_html_e( 'This action will import the Uncode\'s demo site menus.', 'uncode' ); ?></p>';
					var widget_message_text = '<p><?php esc_html_e( 'This action will import the Uncode\'s demo site widgets.', 'uncode' ); ?></p>';

					// When the user clicks on the import button (demo or singles), show some instructions
					var import_warning_text = '<ol>' +
						'<li><?php esc_html_e( 'This action will not import the Uncode demo site\'s main menu. If you want to import that element as well, please use the "Import Menu" button.', 'uncode' ); ?></li>' +
						'<li><?php esc_html_e( 'If you are importing demos to an existing installation of Uncode, please note that this action will also overwrite your "Theme Options". If you need to import specific layouts to your existing installation, please use the "Single Layouts" button.', 'uncode' ); ?></li>';

					// Show a different message when the user deletes the uncode medias
					var delete_media_text = '<p><?php esc_html_e('This action will delete your images.', 'uncode'); ?></p>';

					// Show a list of inactive plugins when the user clicks on the import button
					var cf7_active_text = '<?php echo ( class_exists( 'WPCF7' ) ) ? '' : esc_html__( 'Contact Form 7 (recommended)', 'uncode' ); ?>';
					var woo_active_text = '<?php echo ( defined( 'WC_VERSION' ) ) ? '' : esc_html__('WooCommerce (recommended)', 'uncode'); ?>';
					var inactive_plugins_text;

					if (cf7_active_text || woo_active_text) {
						var inactive_plugins_desc = '<span class="inactive-plugins__sep"> - </span><span class="inactive-plugins__desc"><?php esc_html_e( 'Plugin is not active', 'uncode'); ?></span>'

						inactive_plugins_text = '<li><?php esc_html_e('The following plugins are inactive, which will prevent the relevant content from being imported:', 'uncode'); ?>';

						inactive_plugins_text += '<ul class="inactive-plugins__list">';

						if (cf7_active_text) {
							inactive_plugins_text += '<li><span class="inactive-plugins__name">' + cf7_active_text + '</span>' + inactive_plugins_desc + '</li>';
						}

						if (woo_active_text) {
							inactive_plugins_text += '<li><span class="inactive-plugins__name">' + woo_active_text + '</span>' + inactive_plugins_desc + '</li>';
						}

						inactive_plugins_text += '</ul></li>';
					}

					// Create a specific message according to the selected action
					if ($(e.currentTarget).hasClass('delete-media')) {
						modal_content += delete_media_text;
						dialog_title = '<?php esc_html_e('Delete Demo Media', 'uncode'); ?>';
					} else if ($(e.currentTarget).hasClass('import-ot')) {
						modal_content += default_message_text;
						dialog_title = '<?php esc_html_e('Import Theme Options', 'uncode'); ?>';
					} else if ($(e.currentTarget).hasClass('import-widgets')) {
						modal_content += widget_message_text;
						dialog_title = '<?php esc_html_e('Import Widgets', 'uncode'); ?>';
					} else if ($(e.currentTarget).hasClass('import-menu')) {
						modal_content += menu_message_text;
						dialog_title = '<?php esc_html_e('Import Menu', 'uncode'); ?>';
					} else {
						modal_content += import_warning_text;
						if (inactive_plugins_text) {
							modal_content += inactive_plugins_text;
						}

						modal_content += '</ol>';
						if ($(e.currentTarget).hasClass('uncode-import-form--singles'))
							dialog_title = '<?php esc_html_e('Import Single Layouts', 'uncode'); ?>';
						else
							dialog_title = '<?php esc_html_e('Import Demo', 'uncode'); ?>';

					}

					modal_content += '<p><?php esc_html_e( 'Are you sure?', 'uncode' ); ?></p>';

					// $().uncode_modal('open', modal_content);

					// $(document).on('click', '#uncode-cancel-modal, .uncode-ui-overlay', function() {
					// 	$().uncode_modal('close');
					// });

					var confirmImportRun = function(){
					//$(document).on('click', '#uncode-confirm-modal', function() {
						var is_delete_form = false;

						// Loader title
						var loader_title = '<?php esc_html_e( 'Import Demo', 'uncode' ); ?>';

						if (_form.hasClass('uncode-import-form--singles')) {
							loader_title = '<?php esc_html_e( 'Import Single Layouts', 'uncode' ); ?>';
						} else if (_form.hasClass('import-ot')) {
							loader_title = '<?php esc_html_e( 'Import Theme Options', 'uncode' ); ?>';
						} else if (_form.hasClass('import-menu')) {
							loader_title = '<?php esc_html_e( 'Import Menu', 'uncode' ); ?>';
						} else if (_form.hasClass('import-widgets')) {
							loader_title = '<?php esc_html_e( 'Import Widgets', 'uncode' ); ?>';
						} else if (_form.hasClass('delete-media')) {
							is_delete_form = true;
							loader_title = '<?php esc_html_e( 'Delete Demo Media', 'uncode' ); ?>';
						}

						if (! _form.hasClass('delete-media')) {
							is_delete_form = true;
						}

						//$().uncode_modal('close');
						//$('#import-area').hide();
						//$('#uncode-import-back').hide();

						var import_loader = '<div class="uncode-import-loader">' +
							'<div class="uncode-ot-spinner"></div>' +
							//'<h4 class="uncode-import-loader__title">' + loader_title + '</h4>' +
							'<div class="uncode-import-loader__description">' +
								'<p><strong><?php echo esc_html__( 'Do not close the browser or navigate away from this page.', 'uncode' ); ?></strong></p>' +
								'<p><?php echo esc_html__( 'Please be patient. The import procedure can take up to a few minutes, based on your server\'s performance.', 'uncode' ); ?></p>';

						if (!is_delete_form) {
							import_loader += '<?php printf( '<p class="uncode-import-loader__tip"><strong>%s:</strong> %s <strong>%s</strong> %s</p>', esc_html__( 'Tips', 'uncode' ), esc_html__( 'Did you know that you can delete all the imported demo media using the', 'uncode' ), esc_html__( 'delete demo media', 'uncode' ), esc_html__( 'button inside this page?', 'uncode' ) ); ?>';
						}

						import_loader += '</div>'; // close description
						import_loader += '</div>'; // close loader

						//$(import_loader).insertAfter('#import-area');

						$('.uncode-import-demo-modal .ui-dialog-content').html(import_loader);
						$('.uncode-import-demo-modal .ui-dialog-buttonpane').hide();
						$('.uncode-import-demo-modal .ui-dialog-title').text(loader_title);
						$('.uncode-import-demo-modal .ui-dialog-titlebar button').hide();

						var data = {
							action: 'demo-data',
							dataType: "html",
							ids: _form.hasClass('uncode-import-form--singles') ? _form.serialize() : '-1',
							options: _form.hasClass('import-ot') ? true : false,
							menu: _form.hasClass('import-menu') ? true : false,
							widgets: _form.hasClass('import-widgets') ? true : false,
							delete: _form.hasClass('delete-media') ? true : false,
							demononce: '<?php echo wp_create_nonce('radium-demo-code'); ?>'
						};
						uncode_import_demo(data);
					};

					$("<div />").html(modal_content).dialog({
						autoOpen: true,
						modal: true,
						dialogClass: 'uncode-modal uncode-import-demo-modal',
						title: dialog_title,
						minHeight: 500,
						minWidth: 500,
						position: { my: "center", at: "center", of: window },
						buttons : {
							"<?php echo esc_html__('Confirm', 'uncode'); ?>" : function() {
								confirmImportRun();
								//$(this).dialog("close");
							},
							"<?php echo esc_html__('Cancel', 'uncode'); ?>" : function() {
								$(this).dialog("close");
							}
						}
					});

					return false;
				});
				function uncode_import_demo(data) {
					var this_data = data;
					$.ajax({
						type : "post",
						dataType : "html",
						url : '<?php echo admin_url("admin.php?page=uncode-import-demo"); ?>',
						data : data,
						success: function(response, textStatus, xhr) {
							var result = $(response).find('.uncode-import-response').html(),
								is_fine = $(response).find('#import-fine');
							if (!$(is_fine).length) {
								if ($(response).find('.post-imported').length > 0 && runned < 20) {
									runned++;
									uncode_import_demo(this_data);
								} else {
									result = '<div class="uncode-import-response"><div class="uncode-import-response-content"><div class="uncode-svg-error"><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="40px" height="40px" viewBox="-263.5 236.5 26 26"><g class="svg-error"><circle cx="-250.5" cy="249.5" r="12"/><path d="M-254.51,253.391l8.02-7.801"/><path d="M-246.6,253.5l-7.801-8.02"/></g></svg></div><h4><?php echo esc_html__('Ooops, the Demo Content couldn\'t be imported all in once','uncode'); ?></h4><p><?php printf(esc_html__('Please refer to this %s for a possible workaround.','uncode'), '<a href="' . esc_url('support.undsgn.com/hc/en-us/articles/213459629') . '" target="_blank">'.esc_html__('troubleshoot thread','uncode').'</a>'); ?></p></div></div>';
									show_import_result(result, true);
								}
							} else {
								show_import_result(result, false);
								$('.uncode-wrap .log-link').on('click', function() {
									$('#import-log').show();
								});
								//save CSS with custom options
								var css_data = { action: 'css_compile_ajax' };
								$.post(ajaxurl, css_data);
							}
						},
						error: function (xhr, ajaxOptions, thrownError) {
							thrownError = '<div class="uncode-import-response"><div class="uncode-import-response-content"><div class="uncode-svg-error"><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="40px" height="40px" viewBox="-263.5 236.5 26 26"><g class="svg-error"><circle cx="-250.5" cy="249.5" r="12"/><path d="M-254.51,253.391l8.02-7.801"/><path d="M-246.6,253.5l-7.801-8.02"/></g></svg></div><h4><?php echo esc_html__('Ooops, the Demo Content couldn\'t be imported all in once','uncode'); ?></h4><p><?php printf(esc_html__('Please refer to this %s for a possible workaround.','uncode'), '<a href="' . esc_url('support.undsgn.com/hc/en-us/articles/213459629') . '" target="_blank">'.esc_html__('troubleshoot thread','uncode').'</a>'); ?></p></div></div>';
							if (runned < 10) {
								runned++;
								uncode_import_demo(this_data);
							} else {
								var result = '<b>' + thrownError + '</b>';
								show_import_result(result, true);
							}
						}
					});
				}
			});
		</script>
		<?php
	}

	/**
	 * add_widget_to_sidebar Import sidebars
	 * @param  string $sidebar_slug    Sidebar slug to add widget
	 * @param  string $widget_slug     Widget slug
	 * @param  string $count_mod       position in sidebar
	 * @param  array  $widget_settings widget settings
	 *
	 * @since 2.2.0
	 *
	 * @return null
	 */
	public function add_widget_to_sidebar($sidebar_slug, $widget_slug, $count_mod, $widget_settings = array()){

		$sidebars_widgets = get_option('sidebars_widgets');

		if(!isset($sidebars_widgets[$sidebar_slug]))
		   $sidebars_widgets[$sidebar_slug] = array('_multiwidget' => 1);

		$newWidget = get_option('widget_'.$widget_slug);

		if(!is_array($newWidget))
			$newWidget = array();

		$count = count($newWidget)+1+$count_mod;
		$sidebars_widgets[$sidebar_slug][] = $widget_slug.'-'.$count;

		$newWidget[$count] = $widget_settings;

		update_option('sidebars_widgets', $sidebars_widgets);
		update_option('widget_'.$widget_slug, $newWidget);

	}

	public function set_demo_data( $file, $ids = '' ) {

		if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true);

		require_once ABSPATH . 'wp-admin/includes/import.php';

		// Remove uncode gallery action hook to avoid duplicates
		remove_action( 'save_post_uncode_gallery', 'uncode_save_gallery_media', 10, 3 );

		$importer_error = false;

		//if ( ! uncode_core_is_registered() ) {
		//	wp_die(
		//		esc_html__( 'Please register your copy of Uncode Theme to import premium contents.', 'uncode' )
		//	);
		//}

		if ( !class_exists( 'WP_Importer' ) ) {

			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

			if ( file_exists( $class_wp_importer ) ){

				require_once($class_wp_importer);

			} else {

				$importer_error = true;

			}

		}

		if ( !class_exists( 'WP_Import' ) ) {

			$class_wp_import = dirname( __FILE__ ) .'/wordpress-importer.php';

			if ( file_exists( $class_wp_import ) )
				require_once($class_wp_import);
			else
				$importer_error = true;

		}

		if ( $importer_error ) {

			die( "Error on import" );

		} else {

			global $wp_filesystem;
			if (empty($wp_filesystem)) {
			  require_once (ABSPATH . '/wp-admin/includes/file.php');
			}
			$creds = request_filesystem_credentials($file, '', false, false);
			WP_Filesystem($creds);
			$response = $wp_filesystem->get_contents($file);
			if($response){

				$wp_import = new WP_Import();
				$wp_import->import_menu = $this->import_menu;
				$wp_import->fetch_attachments = true;
				$wp_import->import( $file, $ids );

				$this->after_xml_demo_import();

			} else {

				echo "The XML file containing the dummy content is not available or could not be read .. You might want to try to set the file permission to chmod 755.<br/>If this doesn't work please use the WordPress importer and import the XML file (should be located in your download .zip: Sample Content folder) manually ";

			}

			// Re-hook uncode gallery action
			add_action( 'save_post_uncode_gallery', 'uncode_save_gallery_media', 10, 3 );

		}

		// Re-hook uncode gallery action
		add_action( 'save_post_uncode_gallery', 'uncode_save_gallery_media', 10, 3 );

	}

	public function set_demo_menus() {
		// Menus to Import and assign - you can remove or add as many as you want
		$top_menu    = get_term_by('name', 'Secondary Menu', 'nav_menu');
		$main_menu   = get_term_by('name', 'Main Menu', 'nav_menu');

		set_theme_mod( 'nav_menu_locations', array(
				'secondary' => $top_menu->term_id,
				'primary' => $main_menu->term_id,
			)
		);

		$this->flag_as_imported['menus'] = true;
	}

	public function delete_demo_media() {
		//if ( ! uncode_core_is_registered() ) {
		//	return;
		//}

		global $wpdb;
		$s_string = $wpdb->esc_like( 'demo media' );
		$s_string = '%' . $s_string . '%';
		$sql = "SELECT ID FROM $wpdb->posts WHERE post_title LIKE %s";
		$sql = $wpdb->prepare( $sql, $s_string );
		$matching_ids = $wpdb->get_results( $sql,OBJECT );
		foreach ($matching_ids as $key => $value) {
			wp_delete_attachment($value->ID, true);
		}
	}

	public function set_demo_theme_options( $file ) {
		//if ( ! uncode_core_is_registered() ) {
		//	wp_die(
		//		esc_html__( 'Please register your copy of Uncode Theme to import premium contents.', 'uncode' )
		//	);
		//}

		global $wp_filesystem;
		if (empty($wp_filesystem)) {
		  require_once (ABSPATH . '/wp-admin/includes/file.php');
		}
		$creds = request_filesystem_credentials($file, '', false, false);
		WP_Filesystem($creds);
		/* Will result in $api_response being an array of data,
		parsed from the JSON response of the API listed above */
		$data = $wp_filesystem->get_contents($file);

		// Have valid data?
		// If no data or could not decode
		if ( empty( $data ) ) {
			wp_die(
				esc_html__( 'Theme options import data could not be read. Please try a different file.', 'uncode' ),
				'',
				array( 'back_link' => true )
			);
		}

		/* textarea value */
    $options = unserialize( base64_decode( $data ) );

    /* get settings array */
    $settings = get_option( ot_settings_id() );

    /* has options */
    if ( is_array( $options ) ) {

      /* validate options */
      if ( is_array( $settings ) ) {

        foreach( $settings['settings'] as $setting ) {

          if ( isset( $options[$setting['id']] ) ) {

            $content = ot_stripslashes( $options[$setting['id']] );

            $options[$setting['id']] = ot_validate_setting( $content, $setting['type'], $setting['id'] );

          }

        }

      }

      /* update the option tree array */
      update_option( ot_options_id(), $options );

      /* execute the action hook and pass the theme options to it */
      do_action( 'ot_after_theme_options_save', $options );

    }

		// Update font stack
		update_option( 'uncode_font_options', $this->font_stack );

	}

	/**
	 * Available widgets
	 *
	 * Gather site's widgets into array with ID base, name, etc.
	 * Used by export and import functions.
	 *
	 * @since 2.2.0
	 *
	 * @global array $wp_registered_widget_updates
	 * @return array Widget information
	 */
	function available_widgets() {

		global $wp_registered_widget_controls;

		$widget_controls = $wp_registered_widget_controls;

		$available_widgets = array();

		foreach ( $widget_controls as $widget ) {

			if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ) { // no dupes

				$available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
				$available_widgets[$widget['id_base']]['name'] = $widget['name'];

			}

		}

		return apply_filters( 'radium_theme_import_widget_available_widgets', $available_widgets );

	}


	/**
	 * Process import file
	 *
	 * This parses a file and triggers importation of its widgets.
	 *
	 * @since 2.2.0
	 *
	 * @param string $file Path to .wie file uploaded
	 * @global string $widget_import_results
	 */
	function process_widget_import_file( $file ) {

		global $wp_filesystem;
		if (empty($wp_filesystem)) {
		  require_once (ABSPATH . '/wp-admin/includes/file.php');
		}
		$creds = request_filesystem_credentials($file, '', false, false);
		WP_Filesystem($creds);
		$response = $wp_filesystem->get_contents($file);
		/* Will result in $api_response being an array of data,
		parsed from the JSON response of the API listed above */
		$data = json_decode( $response, false );

		// Import the widget data
		// Make results available for display on import/export page
		$this->widget_import_results = $this->import_widgets( $data );

	}


	/**
	 * Import widget JSON data
	 *
	 * @since 2.2.0
	 * @global array $wp_registered_sidebars
	 * @param object $data JSON widget data from .wie file
	 * @return array Results array
	 */
	public function import_widgets( $data ) {
		//if ( ! uncode_core_is_registered() ) {
		//	wp_die(
		//		esc_html__( 'Please register your copy of Uncode Theme to import premium contents.', 'uncode' )
		//	);
		//}

		global $wp_registered_sidebars;

		$settings = ot_get_option( '_uncode_sidebars' );
		foreach ($settings as $key => $value) {
			$wp_registered_sidebars[$value['_uncode_sidebar_unique_id']] = array(
				'name' => $value['title'],
				'id' => $value['_uncode_sidebar_unique_id']
			);
		}

		// Have valid data?
		// If no data or could not decode
		if ( empty( $data ) || ! is_object( $data ) ) {
			wp_die(
				esc_html__( 'Widget import data could not be read. Please try a different file.', 'uncode' ),
				'',
				array( 'back_link' => true )
			);
		}

		// Hook before import
		$data = apply_filters( 'radium_theme_import_widget_data', $data );

		// Get all available widgets site supports
		$available_widgets = $this->available_widgets();

		// Get all existing widget instances
		$widget_instances = array();
		foreach ( $available_widgets as $widget_data ) {
			$widget_instances[$widget_data['id_base']] = get_option( 'widget_' . $widget_data['id_base'] );
		}

		// Begin results
		$results = array();

		// Loop import data's sidebars
		foreach ( $data as $sidebar_id => $widgets ) {

			// Skip inactive widgets
			// (should not be in export file)
			if ( 'wp_inactive_widgets' == $sidebar_id ) {
				continue;
			}

			// Check if sidebar is available on this site
			// Otherwise add widgets to inactive, and say so
			if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
				$sidebar_available = true;
				$use_sidebar_id = $sidebar_id;
				$sidebar_message_type = 'success';
				$sidebar_message = '';
			} else {
				$sidebar_available = false;
				$use_sidebar_id = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
				$sidebar_message_type = 'error';
				$sidebar_message = esc_html__( 'Sidebar does not exist in theme (using Inactive)', 'uncode' );
			}

			// Result for sidebar
			$results[$sidebar_id]['name'] = ! empty( $wp_registered_sidebars[$sidebar_id]['name'] ) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
			$results[$sidebar_id]['message_type'] = $sidebar_message_type;
			$results[$sidebar_id]['message'] = $sidebar_message;
			$results[$sidebar_id]['widgets'] = array();

			// Loop widgets
			foreach ( $widgets as $widget_instance_id => $widget ) {

				$fail = false;

				// Get id_base (remove -# from end) and instance ID number
				$id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
				$instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

				// Does site support this widget?
				if ( ! $fail && ! isset( $available_widgets[$id_base] ) ) {
					$fail = true;
					$widget_message_type = 'error';
					$widget_message = esc_html__( 'Site does not support widget', 'uncode' ); // explain why widget not imported
				}

				// Filter to modify settings before import
				// Do before identical check because changes may make it identical to end result (such as URL replacements)
				$widget = apply_filters( 'radium_theme_import_widget_settings', $widget );

				// Does widget with identical settings already exist in same sidebar?
				if ( ! $fail && isset( $widget_instances[$id_base] ) ) {

					// Get existing widgets in this sidebar
					$sidebars_widgets = get_option( 'sidebars_widgets' );
					$sidebar_widgets = isset( $sidebars_widgets[$use_sidebar_id] ) ? $sidebars_widgets[$use_sidebar_id] : array(); // check Inactive if that's where will go

					// Loop widgets with ID base
					$single_widget_instances = ! empty( $widget_instances[$id_base] ) ? $widget_instances[$id_base] : array();
					foreach ( $single_widget_instances as $check_id => $check_widget ) {

						// Is widget in same sidebar and has identical settings?
						if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {

							$fail = true;
							$widget_message_type = 'warning';
							$widget_message = esc_html__( 'Widget already exists', 'uncode' ); // explain why widget not imported

							break;

						}

					}

				}

				// No failure
				if ( ! $fail ) {

					// Add widget instance
					$single_widget_instances = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time
					$single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 ); // start fresh if have to
					$single_widget_instances[] = (array) $widget; // add it

						// Get the key it was given
						end( $single_widget_instances );
						$new_instance_id_number = key( $single_widget_instances );

						// If key is 0, make it 1
						// When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
						if ( '0' === strval( $new_instance_id_number ) ) {
							$new_instance_id_number = 1;
							$single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
							unset( $single_widget_instances[0] );
						}

						// Move _multiwidget to end of array for uniformity
						if ( isset( $single_widget_instances['_multiwidget'] ) ) {
							$multiwidget = $single_widget_instances['_multiwidget'];
							unset( $single_widget_instances['_multiwidget'] );
							$single_widget_instances['_multiwidget'] = $multiwidget;
						}

						// Update option with new widget
						update_option( 'widget_' . $id_base, $single_widget_instances );

					// Assign widget instance to sidebar
					$sidebars_widgets = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
					$new_instance_id = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
					$sidebars_widgets[$use_sidebar_id][] = $new_instance_id; // add new instance to sidebar
					update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data

					// Success message
					if ( $sidebar_available ) {
						$widget_message_type = 'success';
						$widget_message = esc_html__( 'Imported', 'uncode' );
					} else {
						$widget_message_type = 'warning';
						$widget_message = esc_html__( 'Imported to Inactive', 'uncode' );
					}

				}

				// Result for widget instance
				$results[$sidebar_id]['widgets'][$widget_instance_id]['name'] = isset( $available_widgets[$id_base]['name'] ) ? $available_widgets[$id_base]['name'] : $id_base; // widget name or ID if name not available (not supported by site)
				$results[$sidebar_id]['widgets'][$widget_instance_id]['title'] = ! empty( $widget->title ) ? $widget->title : esc_html__( 'No Title', 'uncode' ); // show "No Title" if widget instance is untitled
				$results[$sidebar_id]['widgets'][$widget_instance_id]['message_type'] = $widget_message_type;
				$results[$sidebar_id]['widgets'][$widget_instance_id]['message'] = $widget_message;

			}

		}

		// Hook after import
		do_action( 'radium_theme_import_widget_after_import' );

		// Return results
		return apply_filters( 'radium_theme_import_widget_results', $results );

	}

	/**
	 * Run custom actions after succesful import
	 */
	public function after_xml_demo_import() {
		global $wpdb;

		// Ensure that the three SVG icons have the correct width/height. For some reason in
		// some servers the metadata is not saved during the import.
		// And yes, this is really an ugly workaround!
		update_post_meta( 44569, '_wp_attachment_metadata', array( 'width' => '80', 'height' => '80' ) );
		update_post_meta( 44810, '_wp_attachment_metadata', array( 'width' => '80', 'height' => '80' ) );
		update_post_meta( 44814, '_wp_attachment_metadata', array( 'width' => '80', 'height' => '80' ) );
		update_post_meta( 72363, '_wp_attachment_metadata', array( 'width' => '80', 'height' => '80' ) );

		// Ensure correct count of page categories
		$wpdb->query(
			"UPDATE $wpdb->term_taxonomy tt
			SET count = (
				SELECT count(p.ID)
				FROM $wpdb->term_relationships tr
				LEFT JOIN $wpdb->posts p ON p.ID = tr.object_id
				WHERE tr.term_taxonomy_id = tt.term_taxonomy_id
			);
		");
	}
}
