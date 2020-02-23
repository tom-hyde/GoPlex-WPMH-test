<?php
/**
 * Gutenberg support
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Check if Gutenberg is active
// (the standalone plugin or WP5+)
if ( ! uncode_is_gutenberg_active() ) {
	return;
}

if ( ! class_exists( 'Uncode_Gutenberg' ) ) :

/**
 * Uncode_Gutenberg Class
 */
class Uncode_Gutenberg {

	/**
	 * Construct.
	 */
	public function __construct() {
		// Declare Gutenberg features
		add_action( 'after_setup_theme', array( $this, 'declare_features' ) );

		// Admin scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		// Front-end scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );

		// Editor styles
		add_action( 'admin_head', array( $this, 'editor_styles' ) );
	}

	/**
	 * Declare Gutenberg features
	 */
	public function declare_features() {
		add_theme_support( 'align-wide' );
	}

	/**
	 * Admin scripts
	 */
	public function admin_scripts( $hook_suffix ) {
		if ( 'post.php' == $hook_suffix || 'post-new.php' == $hook_suffix ) {
			wp_enqueue_style( 'uncode-gutenberg-admin', get_template_directory_uri() . '/core/inc/compatibility/gutenberg/assets/css/uncode-gutenberg-admin.css', array(), UNCODE_VERSION, 'all' );
		}
	}

	/**
	 * Front-end scripts
	 */
	public function frontend_scripts() {
		global $wp_version;

		$has_blocks = version_compare( $wp_version, '5', '>=' ) ? has_blocks( get_the_ID() ) : gutenberg_post_has_blocks( get_the_ID() );

		if ( $has_blocks ) {
			wp_enqueue_style( 'uncode-gutenberg-frontend', get_template_directory_uri() . '/core/inc/compatibility/gutenberg/assets/css/uncode-gutenberg-frontend.css', array(), UNCODE_VERSION, 'all' );
		}
	}

	/**
	 * Round width in 12 columns grid
	 */
	private function round_editor_width( $width ) {
		$width = 12 * round( $width / 12 );

		return $width;
	}

	/**
	 * Get col width
	 */
	private function get_col_width( $width, $cols ) {
		switch ( $cols ) {
			case 1:
				$width = ( $width * 8.333333333333332 ) / 100;
				break;

			case 2:
				$width = ( $width * 16.666666666666664 ) / 100;
				break;

			case 3:
				$width = ( $width * 25 ) / 100;
				break;

			case 4:
				$width = ( $width * 33.33333333333333 ) / 100;
				break;

			case 5:
				$width = ( $width * 41.66666666666667 ) / 100;
				break;

			case 6:
				$width = ( $width * 50 ) / 100;
				break;

			case 7:
				$width = ( $width * 58.333333333333336 ) / 100;
				break;

			case 8:
				$width = ( $width * 66.66666666666666 ) / 100;
				break;

			case 9:
				$width = ( $width * 75 ) / 100;
				break;

			case 10:
				$width = ( $width * 83.33333333333334 ) / 100;
				break;

			case 11:
				$width = ( $width * 91.66666666666666 ) / 100;
				break;
		}

		// Remove margin
		$width = $width - 54;

		return round( $width );
	}

	/**
	 * Editor scripts
	 */
	private function calculate_editor_width( $settings ) {
		// Add defaults
		$editor_width = array(
			// wrapper_width = content + sidebar
			// This is also the content width on mobile devices (when the sidebar goes down)
			'wrapper_width' => 1200,
			'width'         => 800, // 1200 with a sidebar of 4
			'type'          => 'px',
		);

		$has_sidebar        = $settings[ 'with_sidebar' ] === true ? true : false;
		$content_width_type = $settings[ 'content_width' ];
		$layout_width       = $settings[ 'layout_width' ][ 'width' ];
		$layout_width_type  = $settings[ 'layout_width' ][ 'type' ];

		if ( 'full' === $content_width_type ) {
			// Full content: % width type of course, and content is 100% on mobile devices
			$editor_width[ 'wrapper_width' ] = '100';
			$editor_width[ 'type' ]          = '%';
			$editor_width[ 'width' ]         = 100;

			if ( $has_sidebar ) {
				$sidebar_size            = $settings[ 'sidebar_size' ] ? $settings[ 'sidebar_size' ] : 0;
				$editor_width[ 'width' ] = ( 100 / 12 ) * ( 12 - $sidebar_size );
			}
		} else if ( 'limit' === $content_width_type ) {
			// Limit width
			$content_width_limit      = $settings[ 'content_width_limit' ][ 'width' ];
			$content_width_limit_type = $settings[ 'content_width_limit' ][ 'type' ];

			// Set editor width type
			$editor_width[ 'type' ] = $content_width_limit_type;

			if ( 'px' === $content_width_limit_type ) {
				// Content cannot be larger than the layout width
				$max_width                       = $content_width_limit > $layout_width ? $layout_width : $content_width_limit;
				$max_width                       = $this->round_editor_width( $max_width );
				$editor_width[ 'wrapper_width' ] = $max_width - 72;
				$editor_width[ 'width' ]         = $max_width - 72;

				if ( $has_sidebar ) {
					$max_width               = $max_width - 18; // 18px padding (54px - 36px)
					$sidebar_size            = $settings[ 'sidebar_size' ] ? $settings[ 'sidebar_size' ] : 0;
					$editor_width[ 'width' ] = $this->get_col_width( $max_width, 12 - $sidebar_size );
				}
			} else {
				// % type
				$editor_width[ 'wrapper_width' ] = $content_width_limit;

				if ( $has_sidebar ) {
					$sidebar_size            = $settings[ 'sidebar_size' ] ? $settings[ 'sidebar_size' ] : 0;
					$editor_width[ 'width' ] = ( $content_width_limit / 12 ) * ( 12 - $sidebar_size );
				}
			}
		} else {
			// Inherit width
			$editor_width[ 'type' ]          = $layout_width_type;
			$layout_width                    = $this->round_editor_width( $layout_width );
			$editor_width[ 'wrapper_width' ] = $layout_width - 72;
			$editor_width[ 'width' ]         = $layout_width - 72;

			if ( $has_sidebar ) {
				$max_width               = $layout_width - 18; // 18px padding (54px - 36px)
				$sidebar_size            = $settings[ 'sidebar_size' ] ? $settings[ 'sidebar_size' ] : 0;
				$editor_width[ 'width' ] = $this->get_col_width( $max_width, 12 - $sidebar_size );
			}
		}

		return $editor_width;
	}

	/**
	 * Editor scripts
	 */
	public function editor_styles() {
		global $post;

		if ( ! isset( $post ) || ! isset( $post->ID ) ) {
			return;
		}

		// Filter for disabling custom editor styles
		if ( apply_filters( 'uncode_disable_custom_gutenberg_editor_styles', false ) ) {
			return;
		}

		// Define defaults
		$text_color           = '#777';
		$dark_text_color      = '#303133';
		$accent_color         = '#006cff';
		$font_family_base     = '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif';
		$font_family_headings = '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif';
		$default_font_size    = '15';
		$h1_font_size         = '35';
		$h2_font_size         = '29';
		$h3_font_size         = '24';
		$h4_font_size         = '20';
		$h5_font_size         = '17';
		$h6_font_size         = '14';
		$post_type            = isset( $post->post_type ) ? $post->post_type : 'post';

		if ( is_multisite() ) {
			$uncode_option = get_blog_option( get_current_blog_id(), ot_options_id() );
		} else {
			$uncode_option = get_option( ot_options_id() );
		}

		global $front_background_colors;

		if ( is_array( $uncode_option ) && isset( $front_background_colors ) ) {
			// Text color
			if ( isset( $uncode_option[ '_uncode_text_color_light' ] ) ) {
				$cs_text_color_light = $uncode_option[ '_uncode_text_color_light' ];
				$text_color          = isset( $front_background_colors[ $cs_text_color_light ] ) ? $front_background_colors[ $cs_text_color_light ] : $text_color;
			}

			// Dark text color
			if ( isset( $uncode_option[ '_uncode_heading_color_light' ] ) ) {
				$cs_text_color_dark  = $uncode_option['_uncode_heading_color_light'];
				$dark_text_color     = isset( $front_background_colors[ $cs_text_color_dark ] ) ? $front_background_colors[ $cs_text_color_dark ] : $dark_text_color;
			}

			// Accent color
			if ( isset( $uncode_option[ '_uncode_accent_color' ] ) ) {
				$cs_accent_color  = $uncode_option[ '_uncode_accent_color' ];
				$accent_color     = isset( $front_background_colors[ $cs_accent_color ] ) ? $front_background_colors[ $cs_accent_color ] : $accent_color;
			}

			// Get fonts
			$cs_body_font_family    = isset( $uncode_option[ '_uncode_body_font_family' ] ) ? $uncode_option[ '_uncode_body_font_family' ] : $font_family_base;
			$cs_heading_font_family = isset( $uncode_option[ '_uncode_heading_font_family' ] ) ? $uncode_option[ '_uncode_heading_font_family' ] : $font_family_headings;
			$cs_font_fallback       = isset( $uncode_option[ '_uncode_fallback_font' ] ) ? $uncode_option[ '_uncode_fallback_font' ] : '';

			if ( isset( $uncode_option[ '_uncode_font_groups' ] ) ) {
				$fonts = $uncode_option[ '_uncode_font_groups' ];

				if ( ! empty( $fonts ) && is_array( $fonts ) ) {
					foreach ( $fonts as $key => $value ) {
						$font_class = $value[ '_uncode_font_group_unique_id' ];
						$font_name  = urldecode( $value[ '_uncode_font_group' ] );

						if ( $font_name === 'manual' ) {
							$font_name = $value[ '_uncode_font_manual' ];
						}

						if ( strpos( $font_name, ' ' ) > 0 && strpos( $font_name, "'" ) === false && strpos( $font_name, "\"" ) === false ) {
							$font_name = "'" . $font_name . "'";
						}

						if ( $font_class === $cs_body_font_family ) {
							$cs_body_font_family = $font_name;
						}

						if ( $font_class === $cs_heading_font_family ) {
							$cs_heading_font_family = $font_name;
						}

						if ( $font_class === $cs_font_fallback ) {
							$cs_font_fallback = $font_name;
						}
					}
				}
			}

			$font_family_fallback = $cs_font_fallback != '' ? ', ' . $cs_font_fallback : '';
			$font_family_base     = $cs_body_font_family . $font_family_fallback;
			$font_family_headings = $cs_heading_font_family . $font_family_fallback;

			// Font sizes
			$default_font_size = isset( $uncode_option[ '_uncode_font_size' ] ) ? $uncode_option[ '_uncode_font_size' ] : $default_font_size;
			$h1_font_size      = isset( $uncode_option[ '_uncode_heading_h1' ] ) ? $uncode_option[ '_uncode_heading_h1' ] : $h1_font_size;
			$h2_font_size      = isset( $uncode_option[ '_uncode_heading_h2' ] ) ? $uncode_option[ '_uncode_heading_h2' ] : $h2_font_size;
			$h3_font_size      = isset( $uncode_option[ '_uncode_heading_h3' ] ) ? $uncode_option[ '_uncode_heading_h3' ] : $h3_font_size;
			$h4_font_size      = isset( $uncode_option[ '_uncode_heading_h4' ] ) ? $uncode_option[ '_uncode_heading_h4' ] : $h4_font_size;
			$h5_font_size      = isset( $uncode_option[ '_uncode_heading_h5' ] ) ? $uncode_option[ '_uncode_heading_h5' ] : $h5_font_size;
			$h6_font_size      = isset( $uncode_option[ '_uncode_heading_h6' ] ) ? $uncode_option[ '_uncode_heading_h6' ] : $h6_font_size;
		}

		// Line heights
		$default_line_height  = '1.75';
		$headings_line_height = '1.2';

		// Calculate editor width
		$layout_settings   = uncode_get_post_layout_settings( $post, $uncode_option );
		$editor_width_data = $this->calculate_editor_width( $layout_settings );

		// There are two widths:
		//
		// 1. Wrapper Width
		// 		- Content + Sidebar on desktop devices
		// 		- Content width on mobile devices
		//
		// 2. Content Width
		// 		- Content width on desktop devices (with or without a sidebar)
		//
		$wrapper_width = $editor_width_data[ 'wrapper_width' ];
		$content_width = $editor_width_data[ 'width' ];
		$width_type    = $editor_width_data[ 'type' ];

		// Calculate columns width
		$content_mobile_width  = $wrapper_width;
		$content_desktop_width = $content_width;

		if ( 'px' === $width_type ) {
			// 30px padding and borders of blocks
			$content_mobile_width  = $content_mobile_width + 30;
			$content_desktop_width = $content_desktop_width + 30;
		}

		// Wide elements
		$content_mobile_wide_width  = $content_mobile_width * 1.5;
		$content_desktop_wide_width = $content_desktop_width * 1.5;

		ob_start();
		?>

		<style type="text/css" id="uncode-gutenberg-css">
			/* Headings */
			.block-editor .editor-post-title__block .editor-post-title__input,
			.block-editor .wp-block-heading h1,
			.block-editor .wp-block-heading h2,
			.block-editor .wp-block-heading h3,
			.block-editor .wp-block-heading h4,
			.block-editor .wp-block-heading h5,
			.block-editor .wp-block-heading h6 {
				color: <?php echo sanitize_text_field( $dark_text_color ); ?>;
				font-family: <?php echo sanitize_text_field( $font_family_headings ); ?>;
			}

			.block-editor .wp-block-heading h1,
			.block-editor .wp-block-heading h2,
			.block-editor .wp-block-heading h3,
			.block-editor .wp-block-heading h4,
			.block-editor .wp-block-heading h5,
			.block-editor .wp-block-heading h6 {
				line-height: <?php echo sanitize_text_field( $headings_line_height ); ?>;
			}

			.block-editor .editor-post-title__block .editor-post-title__input,
			.block-editor .wp-block-heading h1 {
				font-size: <?php echo sanitize_text_field( $h1_font_size ); ?>px;
			}

			.block-editor .wp-block-heading h2 {
				font-size: <?php echo sanitize_text_field( $h2_font_size ); ?>px;
			}

			.block-editor .wp-block-heading h3 {
				font-size: <?php echo sanitize_text_field( $h3_font_size ); ?>px;
			}

			.block-editor .wp-block-heading h4 {
				font-size: <?php echo sanitize_text_field( $h4_font_size ); ?>px;
			}

			.block-editor .wp-block-heading h5 {
				font-size: <?php echo sanitize_text_field( $h5_font_size ); ?>px;
			}

			.block-editor .wp-block-heading h6 {
				font-size: <?php echo sanitize_text_field( $h6_font_size ); ?>px;
			}

			/* Default text */
			.block-editor .edit-post-visual-editor,
			.block-editor .edit-post-visual-editor p {
				font-family: <?php echo sanitize_text_field( $font_family_base ); ?>;
				font-size: <?php echo sanitize_text_field( $default_font_size ); ?>px;
				line-height: <?php echo sanitize_text_field( $default_line_height ); ?>;
				-webkit-font-smoothing: antialiased;
			}

			.block-editor .edit-post-visual-editor a {
				color: <?php echo sanitize_text_field( $dark_text_color ); ?>;
				text-decoration: none;
				transition: color 200ms cubic-bezier(0.785, 0.135, 0.15, 0.86);
			}

			.block-editor .edit-post-visual-editor a:hover {
				color: <?php echo sanitize_text_field( $accent_color ); ?>;
			}

			.block-editor p,
			.block-editor ul,
			.block-editor ol,
			.block-editor .wp-block-pullquote,
			.block-editor .wp-block-quote__citation {
				color: <?php echo sanitize_text_field( $text_color ); ?>;
			}

			.block-editor .wp-block-quote__citation,
			.block-editor .wp-block-quote cite,
			.block-editor .wp-block-quote footer {
				margin-top: 0;
			}

			.block-editor strong,
			.block-editor b {
				color: <?php echo sanitize_text_field( $dark_text_color ); ?>;
			}

			/* Quotes */
			.block-editor .wp-block-cover .wp-block-cover-text {
				font-size: 28px;
			}

			.block-editor .wp-block-quote {
				border-left: 2px solid <?php echo sanitize_text_field( $accent_color ); ?>!important;
				padding-left: 27px;
			}

			.block-editor .wp-block-quote p,
			.block-editor .wp-block-pullquote p {
				color: <?php echo sanitize_text_field( $dark_text_color ); ?>;
				font-size: 18px;
				line-height: 1.75;
			}

			.block-editor .wp-block-quote.is-large p,
			.block-editor .wp-block-quote.is-style-large p {
				font-size: 24px;
			}

			.block-editor .wp-block-quote p:last-child {
				margin-bottom: 0;
			}

			.block-editor .wp-block-pullquote {
				border-width: 2px;
				padding: 27px 0;
			}

			.block-editor .wp-block-pullquote__citation {
				text-transform: none;
			}

			/* Separator */
			.block-editor .wp-block-separator:not(.is-style-dots) {
				border-bottom: 1px solid #eaeaea !important;
				max-width: none !important;
			}
			.block-editor .wp-block-separator.is-style-wide {
				border-top: 1px solid #eaeaea !important;
			}

			/* Links */
			.block-editor .editor-rich-text__tinymce a {
				color: <?php echo sanitize_text_field( $dark_text_color ); ?>;
				text-decoration: none;
			}

			/* Main column width */
			.block-editor .wp-block {
				max-width: <?php echo sanitize_text_field( $content_mobile_width . $width_type ); ?>;
			}

			/* Width of "wide" blocks */
			.block-editor .wp-block[data-align="wide"] {
				max-width: <?php echo sanitize_text_field( $content_mobile_wide_width . $width_type ); ?>;
			}

			/* Width of "full-wide" blocks */
			.block-editor .wp-block[data-align="full"] {
				max-width: none;
			}

			@media (min-width: 960px) {
				/* Main column width */
				.block-editor .wp-block {
					max-width: <?php echo sanitize_text_field( $content_desktop_width . $width_type ); ?>;
				}

				/* Width of "wide" blocks */
				.block-editor .wp-block[data-align="wide"] {
					max-width: <?php echo sanitize_text_field( $content_desktop_wide_width . $width_type ); ?>;
				}

				/* Width of "full-wide" blocks */
				.block-editor .wp-block[data-align="full"] {
					max-width: none;
				}
			}
		</style>
		<?php

		$style = ob_get_clean();
		echo uncode_switch_stock_string( $style );
	}
}

endif;

return new Uncode_Gutenberg();
