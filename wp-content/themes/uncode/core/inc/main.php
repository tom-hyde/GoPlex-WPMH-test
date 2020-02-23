<?php

$theme = wp_get_theme();

if ( $theme->parent() ) {
	$parent_theme_version = $theme->parent()->version;
} else {
	$parent_theme_version = $theme->get( 'Version' );
}

define('UNCODE_PARENT_VERSION', $parent_theme_version);
define('UNCODE_VERSION', $theme->get( 'Version' ));
define('UNCODE_SLIM', true);
define('UNCODE_NAME', $theme->get( 'Name' ));
define('UNCODE_ICONS_PATH', get_template_directory_uri() . '/core/assets/icons/selection.json');

if ( ! function_exists( 'uncode_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function uncode_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on uncode, use a find and replace
	 * to change 'uncode' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'uncode', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/*
	 * Enable support for Custom Logo.
	 */
	$logo_defaults = array(
		'height'      => 50,
		'width'       => 150,
		'flex-height' => true,
		'flex-width'  => true,
		'header-text' => array(),
	);
	add_theme_support( 'custom-logo', $logo_defaults );

	/*
	 * Enable support for Custom Headers.
	 */
	$header_defaults = array(
		'height'      => 475,
		'header-text' => false,
		'flex-width'  => true,
		'flex-height' => true,
    );
    add_theme_support( 'custom-header', $header_defaults );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'uncode' ),
		'secondary' => esc_html__( 'Secondary Menu', 'uncode' ),
		'cta' => esc_html__( 'Call To Action Menu', 'uncode' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'uncode_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

}
endif; // uncode_setup
add_action( 'after_setup_theme', 'uncode_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function uncode_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'uncode_content_width', 840 );
}
add_action( 'after_setup_theme', 'uncode_content_width', 0 );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function uncode_widgets_init()
{
	$sidebars_array = ot_get_option('_uncode_sidebars');
	if (isset($sidebars_array) && $sidebars_array !== '') {
		$sidebars = is_array($sidebars_array) ? $sidebars_array : array($sidebars_array);
		foreach ($sidebars as $key => $value)
		{
			if (isset($value['_uncode_sidebar_unique_id']) && $value['_uncode_sidebar_unique_id'] !== '') {
				$sidebar_name = $value['_uncode_sidebar_unique_id'];
				register_sidebar(array(
					'name' => $value['title'],
					'id' => $sidebar_name,
					'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'uncode' ),
					'before_widget' => '<aside id="%1$s" class="widget %2$s widget-container sidebar-widgets">',
					'after_widget' => '</aside>',
					'before_title' => '<h3 class="widget-title">',
					'after_title' => '</h3>',
				));
			}
		}
	}

	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'uncode' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'uncode' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s widget-container sidebar-widgets">',
		'after_widget'  => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

}
add_action('widgets_init', 'uncode_widgets_init');

function uncode_unclean_url( $good_protocol_url, $original_url, $_context){
    if (false !== strpos($original_url, 'ai-uncode')){
    	global $ai_bpoints, $adaptive_images_async;
    	$ai_sizes = implode(',', $ai_bpoints);
      $url_parts = parse_url($good_protocol_url);
      $url_home = parse_url(home_url());
      $url_home = (isset($url_home['path'])) ? '/' . trim($url_home['path'], '/') . '/' : '/';
      $explode_path = explode('/', trim($url_parts['path'], '/'));
      $is_content = false;
      foreach ($explode_path as $key => $value) {
      	if ($value === 'wp-content') {
      		$is_content = true;
      	}
      	if ($is_content) {
      		unset($explode_path[$key]);
      	}
      }
      if (count($explode_path) > 0) {
      	$path_domain = '/' . implode('/', $explode_path) . '/';
      } else $path_domain = '/';
      $ai_async = ($adaptive_images_async === 'on') ? " data-async='true'" : "";

      // Mobile advanced settings
      $mobile_advanced = ot_get_option('_uncode_adaptive_mobile_advanced');
      $limit_density = ot_get_option('_uncode_adaptive_limit_density');
      $use_current_device_width = ot_get_option('_uncode_adaptive_use_orientation_width');

      // Mobile advanced settings
      $data_mobile_advanced = '';

      if ( $mobile_advanced == 'on' ) {
      	if ( $limit_density == 'on' ) {
      		$data_mobile_advanced .= "data-limit-density='true' ";
      	}

      	if ( $use_current_device_width == 'on' ) {
      		$data_mobile_advanced .= "data-use-orientation-width='true' ";
      	}
      }

      return $url_parts['path'] . "' " . $data_mobile_advanced . "id='uncodeAI'".$ai_async." data-home='".$url_home."' data-path='".$path_domain."' data-breakpoints-images='" . $ai_sizes;
    }
    return $good_protocol_url;
}

add_filter('clean_url','uncode_unclean_url',10,3);

function uncode_oembed_result($html, $url, $args) {
	if(strpos($url, 'youtu.be') !== false || strpos($url, 'youtube.com') !== false){
		if (gettype($args) === 'array') {
			$args = http_build_query($args);
		}
		if ($args !== '') {
			$html = str_replace("?feature=oembed", "?feature=oembed&" . $args, $html);
		}
  }
  if(strpos($url, 'vimeo.com') !== false){
  	if (gettype($args) === 'array') {
  		$args = http_build_query($args);
  	}
  	$parse_url = parse_url($url);
  	if ($args !== '') {
  		$html = str_replace($parse_url['path'], $parse_url['path'] . "?" . $args, $html);
  	}
  }
  return $html;
}

add_filter('oembed_result','uncode_oembed_result', 10, 3);

/**
 * Enqueue scripts and styles.
 */
function uncode_equeue()
{

	global $LOGO, $adaptive_images, $adaptive_images_async, $adaptive_images_async_blur, $ai_bpoints, $general_style, $menutype, $post;

	$LOGO = new stdClass;
	$logo_switchable = ot_get_option('_uncode_logo_switch');
	$logo_mobile_switch = ot_get_option('_uncode_logo_mobile_switch');
	if ($logo_switchable === 'on') {
		$logo_light = ot_get_option('_uncode_logo_light');
		$logo_dark = ot_get_option('_uncode_logo_dark');
		$LOGO->logo_id = array($logo_light,$logo_dark);

		if ( $logo_mobile_switch === 'on' ) {
			$logo_mobile_light = ot_get_option('_uncode_logo_mobile_light');
			$logo_mobile_dark = ot_get_option('_uncode_logo_mobile_dark');
			$LOGO->logo_mobile_id = array($logo_mobile_light,$logo_mobile_dark);
		}
	} else {
		$LOGO->logo_id = ot_get_option('_uncode_logo');
		if ( $logo_mobile_switch === 'on' ) {
			$LOGO->logo_mobile_id = ot_get_option('_uncode_logo_mobile');
		}
	}
	$LOGO->logo_min = ot_get_option('_uncode_min_logo');
	$LOGO->logo_height = ot_get_option('_uncode_logo_height');

	$general_style = ot_get_option('_uncode_general_style');
	if ($general_style === '') {
		$general_style = 'light';
	}
	$menutype = ot_get_option('_uncode_headers');

	$production_mode = ot_get_option('_uncode_production');
	$resources_version = ($production_mode === 'on') ? null : rand();
	if ( function_exists('get_rocket_option') && ( get_rocket_option( 'remove_query_strings' ) || get_rocket_option( 'minify_css' ) || get_rocket_option( 'minify_js' ) ) ) {
		$resources_version = null;
	}

	/** CSS */
	wp_enqueue_style('uncode-style', get_template_directory_uri() . '/library/css/style.css', array() , $resources_version, 'all');
	wp_enqueue_style('uncode-icons', get_template_directory_uri() . '/library/css/uncode-icons.css', array() , $resources_version, 'all');
	$front_css = get_template_directory() . '/library/css/';
	$ot_id = is_multisite() ? get_current_blog_id() : '';
	if ( apply_filters( 'uncode_force_dynamic_style_load', false ) || file_exists($front_css . 'style-custom'.$ot_id.'.css') && wp_is_writable($front_css . 'style-custom'.$ot_id.'.css') && ! uncode_append_custom_styles_to_head() ) {
		$dynamic_css_exists = true;
		wp_enqueue_style('uncode-custom-style', get_template_directory_uri() . '/library/css/style-custom'.$ot_id.'.css', array() , $resources_version, 'all');
	} else {
		$dynamic_css_exists = false;
		$styles = uncode_create_dynamic_css();
		wp_add_inline_style( 'uncode-style', uncode_compress_css_inline($styles['custom']));
	}

	/** Add JS parameters to frontend */
	$parallax_factor = ot_get_option('_uncode_parallax_factor');
	if ($parallax_factor === '') {
		$parallax_factor = 2.5;
	}
	$constant_scroll = ot_get_option('_uncode_scroll_constant');
	if ($constant_scroll === '') {
		$constant_scroll = 'on';
	}
	$constant_factor = ot_get_option('_uncode_scroll_constant_factor');
	if ($constant_factor === '') {
		$constant_factor = 2;
	}
	$scroll_speed_value = ot_get_option('_uncode_scroll_speed_value');
	if ($scroll_speed_value === '') {
		$scroll_speed_value = 1000;
	}
	$scroll_speed = ($constant_scroll === 'on') ? $constant_factor : $scroll_speed_value;
	if ($scroll_speed == 0 && $constant_scroll === 'on') {
		$scroll_speed = 0.1;
	}
	$site_parameters = array(
		'days'                       => esc_html__( 'days', 'uncode' ),
		'hours'                      => esc_html__( 'hours', 'uncode' ),
		'minutes'                    => esc_html__( 'minutes', 'uncode' ),
		'seconds'                    => esc_html__( 'seconds', 'uncode' ),
		'constant_scroll'            => $constant_scroll ,
		'scroll_speed'               => $scroll_speed ,
		'parallax_factor'            => ($parallax_factor / 10) ,
		'loading'                    => esc_html__( 'Loading…', 'uncode' ),
		'slide_name'                 => esc_html__( 'slide', 'uncode' ),
		'slide_footer'               => esc_html__( 'footer', 'uncode' ),
		'ajax_url'                   => admin_url( 'admin-ajax.php' ),
		'nonce_adaptive_images'      => wp_create_nonce( 'uncode-adaptive-images-nonce' ),
		'enable_debug'               => apply_filters( 'uncode_enable_debug_on_js_scripts', false ),
		'block_mobile_videos'        => apply_filters( 'uncode_block_mobile_videos', false ),
		'mobile_parallax_allowed'    => apply_filters( 'uncode_mobile_parallax_allowed', false ),
	);

	/** JS */
	wp_enqueue_script('wp-mediaelement');

	$ai_active    = ot_get_option('_uncode_adaptive');
	$is_ai_active = false;
	$suffix       = $production_mode === 'on' ? '.min' : '';
	$folder       = $production_mode === 'on' ? 'min/' : '';

	if ($ai_active === 'on' || $ai_active === '') {
		$is_ai_active = true;
		wp_enqueue_script('ai-uncode', get_template_directory_uri() . '/library/js/' . $folder . 'ai-uncode' . $suffix . '.js', array() , $resources_version, false);
	}

	wp_enqueue_script('uncode-init', get_template_directory_uri() . '/library/js/' . $folder . 'init' . $suffix . '.js', array() , $resources_version, false);
	wp_enqueue_script('uncode-plugins', get_template_directory_uri() . '/library/js/' . $folder . 'plugins' . $suffix . '.js', array('jquery') , $resources_version, true);
	wp_enqueue_script('uncode-app', get_template_directory_uri() . '/library/js/' . $folder . 'app' . $suffix . '.js', array('jquery') , $resources_version, true);

	wp_localize_script( 'uncode-init', 'SiteParameters', $site_parameters );

	if (is_singular() && comments_open() && get_option('thread_comments'))
	{
		wp_enqueue_script('comment-reply');
	}

	/** Deregister CSS */
	global $wp_styles, $wp_scripts;
	if (isset($wp_styles->registered['media-views'])) {
		$wp_styles->registered['media-views']->deps = array_diff($wp_styles->registered['media-views']->deps, array('wp-mediaelement'));
	}
	if (isset($wp_styles->registered['mediaelement'])) {
		wp_deregister_style('mediaelement');
	}
	if (isset($wp_styles->registered['wp-mediaelement'])) {
		wp_deregister_style('wp-mediaelement');
	}

	$adaptive_images = ot_get_option('_uncode_adaptive');
	$adaptive_images_async = ot_get_option('_uncode_adaptive_async');
	$adaptive_images_async_blur = ot_get_option('_uncode_adaptive_async_blur');
	$ai_sizes = ot_get_option('_uncode_adaptive_sizes');
	if ($ai_sizes === '') {
		$ai_sizes = '258,516,720,1032,1440,2064,2880';
	}
	$ai_sizes = preg_replace('/\s+/', '', $ai_sizes);
	$ai_bpoints = explode(',', $ai_sizes);

	/** Main CSS **/
	$output_css = '';
	$main_width = ot_get_option('_uncode_main_width');
	$main_align = ot_get_option('_uncode_main_align');
	$boxed = ot_get_option('_uncode_boxed');
	if ($main_align == 'left')
	{
		$main_align_css = 'margin-right: auto;';
	}
	elseif ($main_align == 'right')
	{
		$main_align_css = 'margin-left: auto;';
	}
	else
	{
		$main_align_css = 'margin: auto;';
	}

	$logo_height_mobile = ot_get_option('_uncode_logo_height_mobile');
	if ($logo_height_mobile !== '') {
		$logo_height_mobile = preg_replace('/[^0-9.]+/', '', $logo_height_mobile);
		$output_css .= "\n@media (max-width: 959px) { .navbar-brand > * { height: " . $logo_height_mobile . "px !important;}}";
	}

	$id = get_the_ID();
	$metabox_data = get_post_custom($id);
	if ( isset($metabox_data['_uncode_specific_main_width_inherit'][0]) && $metabox_data['_uncode_specific_main_width_inherit'][0] != ''
		&&
		isset($metabox_data['_uncode_specific_main_width'][0]) && isset($metabox_data['_uncode_specific_main_width_inherit'][0])) {
		$main_width = unserialize($metabox_data['_uncode_specific_main_width'][0]);
	}

	if ((isset($main_width[0]) && $main_width[0] !== '') || (!is_array($main_width) && $main_width !== '')) {
		if (is_array($main_width)) {
			if ($main_width[1] === 'px') {
				$output_width = round($main_width[0] / 12) * 12;
				$output_unit = 'px';
			} else {
				$output_width = $main_width[0];
				$output_unit = '%';
				$output_css .= "\n@media (min-width: 960px) { .limit-width { max-width: " . $main_width[0] . "%; " . $main_align_css . "}}";
			}
		} else {
			if (strpos($main_width, 'px') !== false) {
				$output_width = preg_replace('/[^0-9,.]/', '', $main_width);
				$output_unit = 'px';
			} else {
				$output_width = preg_replace('/[^0-9,.]/', '', $main_width);
				$output_unit = '%';
			}
		}
		if ($main_width[1] === 'px') {
			$output_css .= "\n@media (min-width: 960px) { .limit-width { max-width: " . $output_width . $output_unit . "; " . $main_align_css . "}}";
		} else {
			$output_css .= "\n@media (min-width: 960px) { .limit-width { max-width: " . $output_width . $output_unit . "; " . $main_align_css . "}}";
		}
	}

  /** Menu CSS **/
	if (strpos($menutype, 'vmenu') !== false)
	{
		$vmenu_width = ot_get_option('_uncode_vmenu_width');
		$vmenu_position = ot_get_option('_uncode_vmenu_position');
		$body_border = ot_get_option('_uncode_body_border');
		$body_border = ($body_border !== '' && $body_border !== 0) ? $body_border : 0;

		if ($vmenu_width == '') {
			$vmenu_width = '200';
		}
		$output_css .= "\n@media (min-width: 960px) { .main-header, .vmenu-container { width: " . ($vmenu_width) . "px; }}";

		$vmenu_border_offset = $vmenu_width + $body_border;

		if ($menutype === 'vmenu-offcanvas')
		{
			if ($vmenu_position === 'left')
			{
				$output_css .= "\n@media (min-width: 960px) { .vmenu-container { transform: translateX(-" . $vmenu_width . "px); -webkit-transform: translateX(-" . $vmenu_width . "px); -ms-transform: translateX(-" . $vmenu_width . "px);} .off-opened .vmenu-container { transform: translateX(0px); -webkit-transform: translateX(0px); -ms-transform: translateX(0px);}}";
				$output_css .= "\n@media (min-width: 960px) { .off-opened .row-offcanvas, .off-opened .main-container { transform: translateX(" . $vmenu_width . "px); -webkit-transform: translateX(" . $vmenu_width . "px); -ms-transform: translateX(" . $vmenu_width . "px);}}";
				$output_css .= "\n@media (min-width: 960px) { .chrome .main-header, .firefox .main-header, .ie .main-header, .edge .main-header { clip: rect(0px, auto, auto, 0px); } }";
			}
			else
			{
				$output_css .= "\n@media (min-width: 960px) { .vmenu-container { transform: translateX(0px); -webkit-transform: translateX(0px); -ms-transform: translateX(0px);} .off-opened .vmenu-container { transform: translateX(-" . $vmenu_width . "px); -webkit-transform: translateX(-" . $vmenu_width . "px); -ms-transform: translateX(-" . $vmenu_width . "px);}}";
				$output_css .= "\n@media (min-width: 960px) { .off-opened .row-offcanvas, .off-opened .main-container { transform: translateX(-" . $vmenu_width . "px); -webkit-transform: translateX(-" . $vmenu_width . "px); -ms-transform: translateX(-" . $vmenu_width . "px);}}";
				$output_css .= "\n@media (min-width: 960px) { .chrome .main-header, .firefox .main-header, .ie .main-header, .edge .main-header { clip: rect(0px, 0px, 99999999999px, -" . $vmenu_width . "px); } }";
			}
		} /*else {
			if ($vmenu_position == 'right' && $boxed !== 'on' ) {
				$output_css .= "\n@media (min-width: 960px) { .vmenu-container { left: 100%; transform: translateX(-" . $vmenu_border_offset . "px); -webkit-transform: translateX(-" . $vmenu_border_offset . "px); -ms-transform: translateX(-" . $vmenu_border_offset . "px);}}";
			} elseif ($vmenu_position == 'left' && $boxed !== 'on' ) {
				$output_css .= "\n@media (min-width: 960px) { .vmenu-container { right: 100%; transform: translateX(" . $vmenu_border_offset . "px); -webkit-transform: translateX(" . $vmenu_border_offset . "px); -ms-transform: translateX(" . $vmenu_border_offset . "px);} }";
			}
		}*/
	}

	$menu_first_uppercase = ot_get_option('_uncode_menu_first_uppercase');
	$menu_other_uppercase = ot_get_option('_uncode_menu_other_uppercase');

	if ($menu_first_uppercase === 'on') {
		$output_css .= "\n.menu-primary ul.menu-smart > li > a, .menu-primary ul.menu-smart li.dropdown > a, .menu-primary ul.menu-smart li.mega-menu > a, .vmenu-container ul.menu-smart > li > a, .vmenu-container ul.menu-smart li.dropdown > a { text-transform: uppercase; }";
	}

	if ($menu_other_uppercase === 'on') {
		$output_css .= "\n.menu-primary ul.menu-smart ul a, .vmenu-container ul.menu-smart ul a { text-transform: uppercase; }";
	}

	$menu_custom_padding = ot_get_option('_uncode_menu_custom_padding');
	$custom_menu_padding_desktop = ot_get_option('_uncode_menu_custom_padding_desktop');
	$custom_menu_padding_mobile = ot_get_option('_uncode_menu_custom_padding_mobile');
	$custom_menu_padding_desktop_shrinked = $custom_menu_padding_desktop > 9 ? $custom_menu_padding_desktop - 9 : 0;

	if ($menu_custom_padding === 'on') {
		$output_css .= "\nbody.menu-custom-padding .col-lg-0.logo-container, body.menu-custom-padding .col-lg-12 .logo-container, body.menu-custom-padding .col-lg-4.logo-container { padding-top: " . esc_attr( intval( $custom_menu_padding_desktop ) ) . "px; padding-bottom: " . esc_attr( intval( $custom_menu_padding_desktop ) ) . "px; }";
		$output_css .= "\nbody.menu-custom-padding .col-lg-0.logo-container.shrinked, body.menu-custom-padding .col-lg-12 .logo-container.shrinked, body.menu-custom-padding .col-lg-4.logo-container.shrinked { padding-top: " . esc_attr( intval( $custom_menu_padding_desktop_shrinked ) ) . "px; padding-bottom: " . esc_attr( intval( $custom_menu_padding_desktop_shrinked ) ) . "px; }";
		$output_css .= "\n@media (max-width: 959px) { body.menu-custom-padding .menu-container .logo-container { padding-top: " . esc_attr( intval( $custom_menu_padding_mobile ) ) . "px !important; padding-bottom: " . esc_attr( intval( $custom_menu_padding_mobile ) ) . "px !important; } }";

	}

	if ($output_css !== '') {
		wp_add_inline_style('uncode-style', $output_css);
	}

	$custom_css = ot_get_option('_uncode_custom_css');
	if ($custom_css !== '') {
		if ($dynamic_css_exists) {
			wp_add_inline_style('uncode-custom-style', uncode_compress_css_inline($custom_css));
		} else {
			wp_add_inline_style('uncode-style', uncode_compress_css_inline($custom_css));
		}
	}
}

add_action('wp_enqueue_scripts', 'uncode_equeue');


function uncode_add_excerpts_to_pages() {
     add_post_type_support( 'page', 'excerpt' );
}

add_action( 'init', 'uncode_add_excerpts_to_pages' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function uncode_body_classes($classes){
	global $LOGO, $post, $menutype, $metabox_data, $adaptive_images, $adaptive_images_async;

	// Adds a class of group-blog to blogs with more than 1 published author.
	if (is_multi_author())
	{
		$classes[] = 'group-blog';
	}

	$boxed = ot_get_option('_uncode_boxed');
	$main_align = ot_get_option('_uncode_main_align');

	if ($menutype === '') {
		$menutype = 'hmenu-right';
	}
	if (strpos($menutype, 'vmenu') !== false) {
		$vmenu_v_position = ot_get_option('_uncode_vmenu_v_position');
		if ($menutype === 'vmenu-offcanvas') {
			$classes[] = 'menu-offcanvas';
			$classes[] = 'vmenu-' . $vmenu_v_position;
		} else {
			$classes[] = 'vmenu';
			$classes[] = $menutype . '-' . $vmenu_v_position;
		}
		$horizontal_align = ot_get_option('_uncode_vmenu_align');
		$classes[] = 'vmenu-' . $horizontal_align;
		$vmenu_position = ot_get_option('_uncode_vmenu_position');
		$classes[] = 'vmenu-position-' . $vmenu_position;
	} else {
		$vmenu_position = ot_get_option('_uncode_hmenu_position') == '' ? 'left' : ot_get_option('_uncode_hmenu_position');
		$classes[] = 'hormenu-position-' . $vmenu_position;
		switch ($menutype)
		{
			case 'hmenu-right':
				$classes[] = 'hmenu';
				$classes[] = 'hmenu-position-right';
			break;
			case 'hmenu-left':
				$classes[] = 'hmenu';
				$classes[] = 'hmenu-position-left';
			break;
			case 'hmenu-justify':
				$classes[] = 'hmenu';
				$classes[] = 'hmenu-position-center';
			break;
			case 'hmenu-center':
				$classes[] = 'hmenu-center';
			break;
			case 'hmenu-center-split':
				$classes[] = 'hmenu';
				$classes[] = 'hmenu-center-split';
			break;
			case 'hmenu-center-double':
				$classes[] = 'hmenu';
				$classes[] = 'hmenu-center-double';
			break;
		}
	}

	if ($boxed === 'on') {
		$classes[] = 'boxed-width';
	} else {
		$classes[] = 'header-full-width';
	}

	if ($menutype == 'menu-overlay' || $menutype == 'menu-overlay-center') {
		$vmenu_v_position = ot_get_option('_uncode_vmenu_v_position');
		$vmenu_position = ot_get_option('_uncode_vmenu_position');
		if ($vmenu_position === 'left') {
			$classes[] = 'menu-overlay-left';
		}
		$horizontal_align = ot_get_option('_uncode_vmenu_align');
		$classes[] = 'vmenu-' . $horizontal_align;
		$classes[] = 'vmenu-' . $vmenu_v_position;
		$classes[] = 'vmenu-middle';
		$classes[] = 'menu-overlay';
		if ($menutype == 'menu-overlay-center') {
			$classes[] = 'menu-overlay-center';
		}
	}

	if (ot_get_option('_uncode_input_underline') === 'on') {
		$classes[] = 'input-underline';
	}

	$classes[] = 'main-' . $main_align . '-align';

	$menu_mobile_animation = ot_get_option('_uncode_menu_mobile_animation');
	if ($menu_mobile_animation === 'scale') {
		$classes[] = 'menu-mobile-animated';
	}

	$menu_mobile_transparency = ot_get_option('_uncode_menu_mobile_transparency');
	if ($menu_mobile_transparency === 'on') {
		$classes[] = 'menu-mobile-transparent';
	}

	$menu_custom_padding = ot_get_option('_uncode_menu_custom_padding');
	if ($menu_custom_padding === 'on') {
		$classes[] = 'menu-custom-padding';
	}

	$link_color = ot_get_option('_uncode_body_link_color');
	if ($link_color === 'accent') {
		$classes[] = 'textual-accent-color';
	}

	$menu_mobile_overlay = ot_get_option('_uncode_menu_mobile_centered');
	$menu_stick_mobile = ot_get_option('_uncode_menu_sticky_mobile');
	if ($menu_mobile_overlay === 'on' && $menu_stick_mobile === 'on') {
		$classes[] = 'menu-mobile-centered';
	}

	if (uncode_is_full_page()) {
		$classes[] = 'uncode-fullpage fp-waiting';

		if ( uncode_is_full_page() == 'slide'  ) {
			$classes[] = 'uncode-fullpage-slide';
			if ( isset($metabox_data['_uncode_fullpage_type'][0]) && $metabox_data['_uncode_fullpage_type'][0] != '' ) {
				$classes[] = 'uncode-fullpage-' . $metabox_data['_uncode_fullpage_type'][0];
			}

			if ( !isset($metabox_data['_uncode_scroll_safe_padding'][0]) || $metabox_data['_uncode_scroll_safe_padding'][0] == 'on' ) {
				$classes[] = 'uncode-scroll-safe-padding';
			}

			if ( isset($metabox_data['_uncode_fullpage_menu'][0]) && $metabox_data['_uncode_fullpage_menu'][0] !== '' ) {
				$classes[] = 'uncode-fp-menu-' . $metabox_data['_uncode_fullpage_menu'][0];
			}

			if ( isset($metabox_data['_uncode_fullpage_opacity'][0]) && $metabox_data['_uncode_fullpage_opacity'][0] === 'on' ) {
				$classes[] = 'uncode-fp-opacity';
			}
		}

	}

	if (uncode_is_full_page() || (isset($metabox_data['_uncode_page_scroll'][0]) && $metabox_data['_uncode_page_scroll'][0] === 'on')) {

		if ( isset($metabox_data['_uncode_scroll_dots'][0]) && $metabox_data['_uncode_scroll_dots'][0] == 'on' ) {
			$classes[] = 'uncode-scroll-no-dots';
		}

		if ( isset($metabox_data['_uncode_scroll_history'][0]) && $metabox_data['_uncode_scroll_history'][0] == 'on' ) {
			$classes[] = 'uncode-scroll-no-history';
		}

		if ( uncode_is_full_page() ) {
			if ( isset($metabox_data['_uncode_empty_dots'][0]) && $metabox_data['_uncode_empty_dots'][0] == 'on' ) {
				$classes[] = 'uncode-empty-dots';
			}

			if ( isset($metabox_data['_uncode_fullpage_mobile'][0]) && $metabox_data['_uncode_fullpage_mobile'][0] == 'on' ) {
				$classes[] = 'uncode-fp-mobile-disable';
			}

		}

	}

	if ( uncode_is_scroll_snap() ) {
		$classes[] = 'uncode-scroll-snap fp-waiting';
	}

	if ( function_exists('uncode_woocommerce_single_product_zoom_enabled') && uncode_woocommerce_single_product_zoom_enabled(true) ) {
		$classes[] = 'wc-zoom-enabled';
	}

	if ( $menutype === 'vmenu' && ot_get_option('_uncode_menu_accordion_active') == 'on' ) {
		$classes[] = 'menu-accordion-active';
	}

	$no_cta = apply_filters( 'uncode_cta_menu_hide', ot_get_option('_uncode_menu_no_cta') );
	$theme_locations = get_nav_menu_locations();
	if ($no_cta === 'off' && isset($theme_locations['cta'])) {
		$classes[] = 'menu-has-cta';
	}

	if ( class_exists( 'WooCommerce' ) ) {
		$woo_cart = apply_filters( 'uncode_woo_cart', ot_get_option('_uncode_woocommerce_cart') );
		$woo_icon = apply_filters( 'uncode_woo_icon', ot_get_option('_uncode_woocommerce_cart_icon') );
		if ($woo_cart === 'on' && $woo_icon !== '') {
			if ($menutype === 'menu-overlay' || $menutype === 'menu-overlay-center' || $menutype === 'offcanvas_head' || $menutype === 'vmenu-offcanvas') {
				$woo_cart_desktop = apply_filters( 'uncode_woo_cart_desktop', ot_get_option('_uncode_woocommerce_cart_desktop') );
			} else {

				$woo_cart_desktop = '';
			}

			if ($woo_cart_desktop === 'on') {
				$classes[] = 'menu-cart-desktop';
			}
		}
	}

	if ( ! apply_filters( 'uncode_mobile_parallax_allowed', false ) ) {
		$classes[] = 'mobile-parallax-not-allowed';
	}

	if ( apply_filters( 'uncode_mobile_no_bounce', true ) ) {
	    $classes[] = 'ilb-no-bounce';
	}

	if ( uncode_get_purchase_code() ) {
		$classes[] = 'unreg';
	}

	if ($adaptive_images === 'on' && $adaptive_images_async === 'on') {
		$classes[] = 'adaptive-images-async';
	}

	if ( isset( $LOGO->logo_mobile_id ) && $LOGO->logo_mobile_id !== '' ) {
		$classes[] = 'uncode-logo-mobile';
	}

	$search_active = apply_filters( 'uncode_search_active', ot_get_option( '_uncode_menu_search') );
	$socials_active = apply_filters( 'uncode_socials_active', ot_get_option( '_uncode_menu_socials') );
	$socials = ot_get_option( '_uncode_social_list');

	$post_type = isset( $post->post_type ) ? $post->post_type : 'post';
	if (isset($metabox_data['_uncode_specific_menu'][0]) && $metabox_data['_uncode_specific_menu'][0] !== '') {
		$primary_menu = $metabox_data['_uncode_specific_menu'][0];
	} else {
		$menu_generic = ot_get_option( '_uncode_'.$post_type.'_menu');
		if ($menu_generic !== '') {
			$primary_menu = $menu_generic;
		} else {
			$primary_menu = '';
			if (isset($theme_locations['primary'])) {
				$menu_obj = get_term( $theme_locations['primary'], 'nav_menu' );
				if (isset($menu_obj->name)) {
					$primary_menu = $menu_obj->name;
				}
			}
		}
	}
	$menu_count = wp_get_nav_menu_items($primary_menu);

	$no_cta = apply_filters( 'uncode_cta_menu_hide', ot_get_option('_uncode_menu_no_cta') );
	if ($no_cta === 'off' && isset($theme_locations['cta'])) {
		$cta_obj = get_term( $theme_locations['cta'], 'nav_menu' );
		$cta_menu = $cta_obj->name;
	} else {
		$cta_menu = false;
	}

	$footer_copyright = ot_get_option('_uncode_footer_copyright');
	$footer_copyright_hide = ot_get_option('_uncode_copy_hide');
	$footer_text = ot_get_option('_uncode_footer_text');

	$empty_menu = false;

	switch( $menutype ) {
		case 'vmenu-offcanvas':
			if ( ( $footer_copyright === 'off' || $footer_copyright_hide === 'on' ) && $footer_text === '' && $search_active !== 'on' && ( $socials_active !== 'on' || empty( $socials ) ) && !$primary_menu && !$cta_menu ) {
				$empty_menu = true;
			}
			break;

		case 'menu-overlay':
		case 'menu-overlay-center':
			if ( $search_active !== 'on' && ( $socials_active !== 'on' || empty( $socials ) ) && !$menu_count && !$cta_menu ) {
				$empty_menu = true;
			}
			break;
	}

	if ( $empty_menu === true ) {
		$classes[] = 'uncode-empty-menu';
	}
	if ( $search_active !== 'on' && ( $socials_active !== 'on' || empty( $socials ) ) && !$menu_count && !$cta_menu ) {
		$classes[] = 'uncode-empty-menu-mobile';
	}

	return $classes;
}
add_filter('body_class', 'uncode_body_classes');

function uncode_inline_script() {
	$custom_js = ot_get_option('_uncode_custom_js');
	if ($custom_js !== '') {
		echo uncode_remove_p_tag( $custom_js );
	}
}
add_action( 'wp_footer', 'uncode_inline_script' );

function uncode_inline_tracking() {
	$custom_tracking = ot_get_option('_uncode_custom_tracking');
	if ($custom_tracking !== '' && uncode_privacy_allow_content( 'tracking' ) !== false ) {
		uncode_privacy_check_needed( 'tracking' );
		echo uncode_remove_p_tag( $custom_tracking );
	}
}
add_action( 'wp_footer', 'uncode_inline_tracking' );

function uncode_redirect_page($original_template) {
	global $post;
	$privacy_page = get_option( 'uncode_privacy_privacy_policy_page', 0 );
	if (! is_user_logged_in() && ! ( $post && $post->ID == $privacy_page ) ) {
		global $is_redirect,$redirect_page;
		$is_redirect_active = ot_get_option('_uncode_redirect');
		if ($is_redirect_active === 'on') {
			$redirect_page = ot_get_option('_uncode_redirect_page');
			if($redirect_page !== '') {
	    		$is_redirect = true;
	      		return get_template_directory() . '/redirect-page.php';
	    	}
		}
	}
	return $original_template;
}
add_filter('template_include', 'uncode_redirect_page');

add_filter( 'the_content_more_link', 'uncode_modify_read_more_link' );

function uncode_modify_read_more_link() {
	return '<a class="more-link btn-link" href="' . get_permalink() . '">'.esc_html__('Read more','uncode').'<i class="fa fa-angle-right"></i></a>';
}

if (!class_exists('WPBakeryShortCode')) {

	class uncode_index {
		protected $filter_categories = array();
		protected $query = false;
		protected $loop_args = array();
		protected $taxonomies = false;

		public function getCategoriesCss($post_id)
		{
			$categories_css = '';
			$categories_name = array();
			$tag_name = array();
			$categories_id = array();
			$taxonomy_type = array();
			$post_categories = wp_get_object_terms($post_id, $this->getTaxonomies());
			foreach ($post_categories as $cat)
			{
				if (is_taxonomy_hierarchical($cat->taxonomy) && substr( $cat->taxonomy, 0, 3 ) !== 'pa_') {
					if (!in_array($cat->term_id, $this->filter_categories)) {
						$this->filter_categories[] = $cat->term_id;
					}
					$categories_name[] = $cat->name;
					$categories_id[] = $cat->term_id;
				} else if ($cat->taxonomy === 'post_tag') {
					$categories_id[] = $cat->term_id;
					$categories_name[] = $cat->name;
					$tag_name[] = $cat->name;
				}
				$taxonomy_type[] = $cat->taxonomy;
			}

			return array('cat_css' => $categories_css, 'cat_name' => $categories_name, 'cat_id' => $categories_id, 'tag' => $tag_name, 'taxonomy' => $taxonomy_type );
		}
		protected function getTaxonomies()
		{
			if ($this->taxonomies === false) {
				$this->taxonomies = get_object_taxonomies(!empty($this->loop_args['post_type']) ? $this->loop_args['post_type'] : get_post_types(array(
					'public' => false,
					'name' => 'attachment'
				) , 'names', 'NOT'));
			}

			return $this->taxonomies;
		}
		public function getCategoriesLink( $post_id ) {
			$categories_link = array();
			$args = array('orderby' => 'term_group', 'order' => 'DESC', 'fields' => 'all');

			$post_categories = wp_get_object_terms( $post_id, $this->getTaxonomies(), $args);
			foreach ( $post_categories as $cat ) {
				if (is_taxonomy_hierarchical($cat->taxonomy) && substr( $cat->taxonomy, 0, 3 ) !== 'pa_') {
					$categories_link[] = array('link' => '<a href="'.get_term_link($cat->term_id, $cat->taxonomy).'">'.$cat->name.'</a>', 'tax' => $cat->taxonomy, 'cat_id' => $cat->term_id);
				} else if ($cat->taxonomy === 'post_tag') {
					$categories_link[] = array('link' => '<a href="'.get_term_link($cat->term_id, $cat->taxonomy).'">'.$cat->name.'</a>', 'tax' => $cat->taxonomy, 'cat_id' => $cat->term_id);
				}
			}
			return $categories_link;
		}
	}
}

if ( ! function_exists( 'uncode_is_full_page' ) ) :
/**
 * @since Uncode 1.7.0
 */
function uncode_is_full_page() {

	global $metabox_data;
	$return = false;
	if ( isset($metabox_data['_uncode_page_scroll'][0]) && $metabox_data['_uncode_page_scroll'][0] === 'slide' ) {
		$return = $metabox_data['_uncode_page_scroll'][0];
	}

	return $return;

}
endif; //uncode_is_full_page

if ( ! function_exists( 'uncode_is_scroll_snap' ) ) :
/**
 * @since Uncode 1.7.0
 */
function uncode_is_scroll_snap() {

	global $metabox_data;
	$return = false;
	if ( isset($metabox_data['_uncode_page_scroll'][0]) && $metabox_data['_uncode_page_scroll'][0] === 'on' && isset($metabox_data['_uncode_scroll_snap'][0]) && $metabox_data['_uncode_scroll_snap'][0] === 'on' ) {
		$return = 'scroll-snap';
	}

	return $return;

}
endif; //uncode_is_scroll_snap

function uncode_let_to_num( $size ) {
	$l   = substr( $size, -1 );
	$ret = substr( $size, 0, -1 );

	switch ( strtoupper( $l ) ) {
		case 'P':
			$ret *= 1024;
		case 'T':
			$ret *= 1024;
		case 'G':
			$ret *= 1024;
		case 'M':
			$ret *= 1024;
		case 'K':
			$ret *= 1024;
	}

	return $ret;
}

/**
 * Check if Uncode Privacy is active
 */
function uncode_is_uncode_privacy_active() {
	if ( class_exists( 'Uncode_Toolkit_Privacy' ) ) {
		return true;
	}

	return false;
}

/**
 * Check if Gutenberg is active
 */
function uncode_is_gutenberg_active() {
	global $wp_version;

	if ( version_compare( $wp_version, '5', '>=' ) ) {
		return true;
	}

	if ( function_exists( 'the_gutenberg_project' ) ) {
		return true;
	}

	return false;
}

add_action( 'wp_head', 'uncode_custom_css_cb', 1000 );
function uncode_custom_css_cb() {

	if ( ! is_singular() ) {
		return;
	}

	$post_type = isset( $post->post_type ) ? $post->post_type : 'post';
	if (is_author()) {
		$post_type = 'author';
	}
	if (is_archive() || is_home()) {
		$post_type .= '_index';
	}
	if (is_404()) {
		$post_type = '404';
	}
	if (is_search()) {
		$post_type = 'search_index';
	}

	$id = get_the_ID();
	$metabox_data = get_post_custom($id);

	if ( $id ) {

		$header_block = $footer_block = false;

		if (isset($metabox_data['_uncode_blocks_list'][0]) && $metabox_data['_uncode_blocks_list'][0] !== '') {
			$header_block = $metabox_data['_uncode_blocks_list'][0];
		} else {
			$header_block = ot_get_option('_uncode_' . $post_type . '_blocks_list');
			if ($header_block === '' && $header_block !== 'none') {
				$header_block = ot_get_option('_uncode_blocks_list');
			}
		}
		if ($header_block !== '') {
			$header_block = apply_filters( 'wpml_object_id', $header_block, 'post' );
			$header_custom_css_cb = get_post_meta( $header_block, '_wpb_shortcodes_custom_css', true );
			if ( ! empty( $header_custom_css_cb ) ) {
				$header_custom_css_cb = strip_tags( $header_custom_css_cb );
				echo '<style data-type="vc_shortcodes-custom-css-cb-footer">';
				echo uncode_switch_stock_string( $header_custom_css_cb );
				echo '</style>';
			}
		}

		if (isset($metabox_data['_uncode_specific_footer_block'][0]) && $metabox_data['_uncode_specific_footer_block'][0] !== '') {
			$footer_block = $metabox_data['_uncode_specific_footer_block'][0];
		} else {
			$footer_block = ot_get_option('_uncode_' . $post_type . '_footer_block');
			if ($footer_block === '' && $footer_block !== 'none') {
				$footer_block = ot_get_option('_uncode_footer_block');
			}
		}
		if ($footer_block !== '') {
			$footer_block = apply_filters( 'wpml_object_id', $footer_block, 'post' );
			$footer_custom_css_cb = get_post_meta( $footer_block, '_wpb_shortcodes_custom_css', true );
			if ( ! empty( $footer_custom_css_cb ) ) {
				$footer_custom_css_cb = strip_tags( $footer_custom_css_cb );
				echo '<style data-type="vc_shortcodes-custom-css-cb-footer">';
				echo uncode_switch_stock_string( $footer_custom_css_cb );
				echo '</style>';
			}
		}

	}
}
