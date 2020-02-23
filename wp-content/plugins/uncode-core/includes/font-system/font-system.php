<?php

class UncodeFont {
	var $version, $fonts_page_name, $settings_page_name, $options, $font_stack, $font_stack_string, $font_directory, $font_directory_url;
	function __construct() {
		$this->options = get_option('uncode_font_options');

		if (!isset($this->options['font_stack']) || is_null($this->options['font_stack']) || trim($this->options['font_stack']) == '') {
			$this->font_stack = array();
			$this->font_stack_string = '';
		}
		else {
			$this->font_stack_string = $this->options['font_stack'];
			$this->font_stack = json_decode(str_replace('&quot;', '"', $this->font_stack_string), true);
		}

		$upload_dir = wp_upload_dir();
		$site_url = get_option( 'upload_url_path' );
		$scheme = parse_url( $site_url, PHP_URL_SCHEME );
		$upload_dir_url = set_url_scheme( $upload_dir['baseurl'], $scheme );

		$this->font_directory = $upload_dir['basedir'] .'/uncode-fonts';
		$this->font_directory_url = $upload_dir_url .'/uncode-fonts';

		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'uncodefont_download_font' && isset($_REQUEST['fileaccess']) && $_REQUEST['fileaccess'] === 'other') {
			$this->download_font();
			die();
		}

		$this->mod_file = (defined('FS_CHMOD_FILE')) ? FS_CHMOD_FILE : false;

		add_action('admin_menu', array(&$this, 'add_admin_menu'), 50);
		add_action('admin_enqueue_scripts', array(&$this, 'add_admin_scripts'));
		add_action('admin_init', array(&$this, 'add_admin_font_css'));
		add_action('admin_init', array(&$this, 'admin_init'));
		add_action('wp_enqueue_scripts', array(&$this, 'add_scripts'));
		add_action('wp_head', array(&$this, 'print_direct_scripts'));
		add_action('wp_head', array(&$this, 'print_selectors'));

		add_action('wp_ajax_uncodefont_download_font', array(&$this, 'download_font'));
		add_action('wp_ajax_uncodefont_delete_download', array(&$this, 'delete_download'));

		// Themes
		add_filter('suffusion_font_list', array(&$this, 'add_more_fonts'), 10, 4);

		//TinyMCE
		add_filter('mce_buttons', array(&$this, 'show_font_dropdown'));
		add_filter('tiny_mce_before_init', array(&$this, 'extend_tinymce_dropdown'));
	}

	function add_admin_menu() {
		if ( ! defined( 'UNCODE_SLIM' ) ) {
			return;
		}

		$this->fonts_page_name = add_submenu_page('uncode-system-status', 'Font Stacks', 'Font Stacks', 'edit_theme_options', 'uncode-font-stacks', array(&$this, 'render_options'));
		add_action('load-'.$this->fonts_page_name, array(&$this, 'add_meta_boxes'));
		add_action('admin_head-'.$this->fonts_page_name, array(&$this, 'print_direct_scripts'));
	}

	function add_admin_scripts($hook) {
		if (!is_admin()) {
			return;
		}
		if ($this->fonts_page_name == $hook) {
			wp_enqueue_script('wp-lists');
			wp_enqueue_script('postbox');
			wp_enqueue_script('uf-js', UNCODE_CORE_PLUGIN_URL . 'includes/font-system/include/js/admin.js', array('jquery'), UncodeCore_Plugin::VERSION);
			wp_enqueue_style('uf-admin', UNCODE_CORE_PLUGIN_URL . 'includes/font-system/include/css/admin.css', array(), UncodeCore_Plugin::VERSION);

			$this->enqueue_fontdeck_styles();

			$font_stack = $this->font_stack;

			$google_font_counter = $fs_font_counter = 0;
			foreach ($font_stack as $font) {
				if (isset($font['source']) && $font['source'] == 'Google Web Fonts') {
					$google_font_counter++;
					$font_family = $font['family'];
					$check_variants = explode(",", $font['variants']);
					if ( !in_array('regular', $check_variants) )
						$font_family = $font_family.':'.$check_variants[0];
					wp_enqueue_style('uf-google-font-'.$google_font_counter, '//fonts.googleapis.com/css?family='.urlencode($font_family), array(), UncodeCore_Plugin::VERSION);
				}

				if (isset($font['source']) && $font['source'] == 'Font Squirrel') {
					if (@file_exists(trailingslashit($this->font_directory).'uncodefont.css')) wp_enqueue_style('uf-font-squirrel', trailingslashit($this->font_directory_url).'uncodefont.css', array(), UncodeCore_Plugin::VERSION);
				}
			}

			$fonts = json_encode($font_stack);
			$uf_params = array(
				'font_stack' => $fonts,
				'ajaxurl' => admin_url('admin-ajax.php'),
				'font_dir_url' => trailingslashit($this->font_directory_url),
				'nonce'  => wp_create_nonce( 'uncode_font_download' ),
			);
			wp_localize_script('uf-js', 'UncodeFontJS', $uf_params);
		}
	}

	function add_admin_font_css() {
		if ( ! is_admin() ) {
			return;
		}

		$fonts = get_option('uncode_font_options');

		if (isset($fonts['font_stack']) && $fonts['font_stack'] !== '[]') {
			$upload_dir = wp_upload_dir();
			$site_url = get_option( 'upload_url_path' );
			$scheme = parse_url( $site_url, PHP_URL_SCHEME );
			$upload_dir_url = set_url_scheme( $upload_dir['baseurl'], $scheme );

			if (@file_exists(trailingslashit($upload_dir['basedir']).'uncode-fonts/uncodefont.css')) {
				wp_enqueue_style('uf-font-squirrel', $upload_dir_url .'/uncode-fonts/uncodefont.css', false ,null);
			}
		}
	}

	/**
	 * Prints scripts directly into the header. This is useful when you cannot enqueue something (e.g. JS code instead of a file, or CSS text)
	 */
	function print_direct_scripts() {
		if ((is_admin() && !isset($_GET['page'])) || (is_admin() && isset($_GET['page']) && $_GET['page'] != 'uncode-font-stacks')) {
			return;
		}

		global $uncodefont_typekit_text, $uncodefont_typekit_error;

		if (isset($this->options['typekit_kit_ID']) && $this->options['typekit_kit_ID'] !== '') {
			if (isset($this->options['typekit_api_key'])) {
				$api_key = $this->options['typekit_api_key'];
				$url = "https://typekit.com/api/v1/json/kits/" . $this->options['typekit_kit_ID'];
				$curl_args = array(
					'sslverify' => false,
					'timeout' => 20000,
				);
				$script = '';
				$response = wp_remote_request($url."?token=$api_key", $curl_args);

				$uncodefont_typekit_text = $uncodefont_typekit_error = '';
				if (!is_wp_error($response)) {
					$response = wp_remote_retrieve_body($response);
					$response = json_decode($response);

					if (isset($response->kit)) {
						$uncodefont_typekit_text .= "<div>";
						$uncodefont_typekit_text .= "<strong>".esc_html__('Your kits:', 'uncode')." </strong>";
						$kit_string = "";
						$family_string = "";
						$kit_position = 0;

						$kit = $response->kit;

						if (isset($kit->id)) {
							$script .= "<script type='text/javascript' src='//use.typekit.com/{$kit->id}.js'></script>\n";

							if (is_admin()) {
								$kit_position++;
								$kit_url = $url."?token=$api_key";
								$kit_response = wp_remote_request($kit_url, $curl_args);
								if (!is_wp_error($kit_response)) {
									$kit_response = wp_remote_retrieve_body($kit_response);
									$kit_response = json_decode($kit_response);

									if (isset($kit_response->kit->name)) {
										$families = $kit_response->kit->families;
										$kit_string .= "<a id='uf-tk-{$kit_response->kit->id}' class='uf-group-key uf-group-key-{$kit_response->kit->id}  uf-group-key-tk' href='#'>{$kit_response->kit->name} (".count($families).")</a> | ";
										if (count($families) > 0) {
											$family_string .= "<div id='uf-tk-{$kit_response->kit->id}-fonts' class='uf-fonts-for uf-fonts-for-{$kit_response->kit->id} uf-group-key-position-$kit_position uf-group-key-for-tk'><ul>";
											foreach ($families as $family) {
												$family_string .= $this->create_font_line_item(
													'Adobe Fonts',
													'tk',
													$family,
													array(
														'family' => 'name',
														'generic' => '',
														'stub' => 'css_stack',
														'variants' => 'variations',
														'subsets' => 'subset',
													)
												);
											}
											$family_string .= "</ul></div>";
										}
									}
								}
							}
						}
						if ($kit_string != '') {
							$kit_string = substr($kit_string, 0, -2);
							$uncodefont_typekit_text .= $kit_string;
						}
						$uncodefont_typekit_text .= "</div>";
						$uncodefont_typekit_text .= $family_string;
						$script .= "<script type='text/javascript'>try{Typekit.load();}catch(e){}</script>\n";
					}
					else {
						$uncodefont_typekit_text .= esc_html__('No kits found for the API key you provided.', 'uncode');
					}
				} else {
					$uncodefont_typekit_error .= $this->connection_failed('Adobe Fonts', false);
				}
				if (!is_admin()) {
					$echo_script = false;
					foreach ($this->font_stack as $key => $font) {
						if ($font['source'] === 'Adobe Fonts' || $font['source'] === 'Typekit') $echo_script = true;
					}
					if ($echo_script) {
						echo do_shortcode($script);
					}
				} else {
					echo do_shortcode($script);
				}
			}
		} else {
			$uncodefont_typekit_text .= esc_html__('No kits ID inserted.', 'uncode');
		}
	}

	function print_selectors() {
		if (is_admin()) {
			return;
		}

		$selectors = array();

		if (is_array($this->font_stack)) {
			foreach ($this->font_stack as $font) {
				if (isset($font['source'])) {
					switch ($font['source']) {
						case 'Google Web Fonts':
							if (isset($font['selectors']) && trim($font['selectors']) != '') {
								$selectors[] = $font['selectors']." { font-family: \"".$font['family']."\"; } ";
							}
							break;

						case 'Adobe Fonts':
						case 'Typekit':
							if (isset($font['selectors']) && trim($font['selectors']) != '') {
								$selectors[] = $font['selectors']." { font-family: ".$font['stub']."; } ";
							}
							break;

						case 'Fontdeck':
							if (isset($font['selectors']) && trim($font['selectors']) != '') {
								$selectors[] = $font['selectors']." { font-family: \"".$font['stub']."\"; } ";
							}
							break;

						case 'Font Squirrel':
							if (isset($font['variantselectors']) && trim($font['variantselectors']) != '') {
								$variant_selectors = explode('|', $font['variantselectors']);
								$selected_variants = explode(',', $font['selvariants']);
								$variants = explode(',', $font['variants']);
								$len = count($variants);
								for ($i=0; $i<$len; $i++) {
									if (in_array($variants[$i], $selected_variants) && trim($variant_selectors[$i]) != '') {
										$selectors[] = $variant_selectors[$i]." { font-family: {$variants[$i]}; } ";
									}
								}
							}
							break;
					}
				}
			}
		}
		if (is_array($selectors) && count($selectors) > 0) {
			$css = '<style type="text/css">'."\n";
			$css .= implode("\n", $selectors);
			$css .= "\n</style>\n";
			echo do_shortcode($css);
		}
	}

	function add_scripts() {
		if (is_admin()) {
			return;
		}

		$google_family = array();
		$google_subsets = array();
		$fontdeck_included = false;
		$selectors = array();
		if (is_array($this->font_stack)) {
			foreach ($this->font_stack as $font) {
				if (isset($font['source'])) {
					switch ($font['source']) {
						case 'Google Web Fonts':
							$family = urlencode($font['family']);
							if (isset($font['selvariants']) && trim($font['selvariants']) != '') {
								$family .= ':'.$font['selvariants'];
							}
							if (isset($font['selsubsets']) && trim($font['selsubsets']) != '') {
								$subsets = explode(',',$font['selsubsets']);
								$google_subsets = array_merge($google_subsets, $subsets);
							}
							$google_family[] = $family;
							if (isset($font['selectors']) && trim($font['selectors']) != '') {
								$selectors = $font['selectors']." { font-family: \"".$font['family']."\"; } ";
							}
							break;

						case 'Adobe Fonts':
						case 'Typekit':
							if (isset($font['selectors']) && trim($font['selectors']) != '') {
								$selectors = $font['selectors']." { font-family: ".$font['stub']."; } ";
							}
							break;

						case 'Fontdeck':
							if (isset($this->options['fontdeck_project']) && trim($this->options['fontdeck_project']) != '' && !$fontdeck_included) {
								$domain = $_SERVER['SERVER_NAME'];
								$project = $this->options['fontdeck_project'];
								$url = "http://f.fontdeck.com/s/css/json/$domain/$project.json";
								$curl_args = array(
									'sslverify' => false,
									'timeout' => 20000,
								);

								$response = wp_remote_request($url, $curl_args);
								if (!is_wp_error($response)) {
									$response = wp_remote_retrieve_body($response);
									$response = json_decode($response);
									wp_enqueue_style('uncodefont-fontdeck', $response->cssurl, array(), UncodeCore_Plugin::VERSION);
									$fontdeck_included = true;
								}
							}
							if (isset($font['selectors']) && trim($font['selectors']) != '') {
								$selectors = $font['selectors']." { font-family: \"".$font['stub']."\"; } ";
							}
							break;

						case 'Font Squirrel':
							if (@file_exists(trailingslashit($this->font_directory).'uncodefont.css') && !(isset($this->options['fontsquirrel_combine']) && $this->options['fontsquirrel_combine'] == 'dont-combine')) {
								wp_enqueue_style('uf-font-squirrel', trailingslashit($this->font_directory_url).'uncodefont.css', array(), UncodeCore_Plugin::VERSION);
							}
							else {

								// Enqueue individually
								if (@file_exists(trailingslashit($this->font_directory).$font['stub']) &&
									@file_exists(trailingslashit($this->font_directory).$font['stub'].'/stylesheet.css')) {
									wp_enqueue_style('uncodefont-font-squirrel-'.$font['stub'], trailingslashit($this->font_directory_url).$font['stub'].'/stylesheet.css', array(), UncodeCore_Plugin::VERSION);
								}
							}
							break;
					}
				}
			}
		}
		if (count(apply_filters('uncode_google_font_count', $google_family) ) > 0) {

			if ( function_exists( 'uncode_privacy_check_needed' ) && function_exists( 'uncode_privacy_allow_content' ) ) {
				/* Return early if we don't have the consent */
				uncode_privacy_check_needed( 'google-fonts' );
				if ( uncode_privacy_allow_content( 'google-fonts' ) === false ) {
					return;
				}
			}

			$google_family = 'family='.implode('|', $google_family);
			$url = '//fonts.googleapis.com/css?'.$google_family;
			if (count($google_subsets) > 0) {
				$google_subsets = '&subset='.implode(',', array_unique($google_subsets));
				$url .= $google_subsets;
			}
			$url = str_replace('|', '%7C', $url);
			wp_enqueue_style('uncodefont-google', $url, array(), UncodeCore_Plugin::VERSION);
		}
	}

	/**
	 * Registers the settings for the WP Settings API.
	 */
	function admin_init() {
		register_setting('uncode_font_options-fonts', 'uncode_font_options', array(&$this, 'validate_options'));
		register_setting('uncode_font_options-settings', 'uncode_font_options', array(&$this, 'validate_options'));
	}

	/**
	 * After form submission, save Font Squirrel CSS to the UncodeFont directory
	 * And show a success/error message in any case
	 */
	function after_form_submit() {
		if (!isset($_REQUEST['settings-updated']) || !$_REQUEST['settings-updated']) {
			return;
		}

		// Save Font Squirrel CSS to the UncodeFont directory
		$font_faces = array();
		$extensions = array(
			'eot' => 'embedded-opentype',
			'ttf' => 'truetype',
			'otf' => 'opentype',
			'woff' => 'woff',
			'svg' => 'svg',
		);
		foreach ($this->font_stack as $font) {
			if ($font['source'] == 'Font Squirrel') {
				if (isset($font['stub'])) {
					if (@is_dir(trailingslashit($this->font_directory).$font['stub'])) {
						$current_font_dir = trailingslashit($this->font_directory).$font['stub'];
						$variants = explode(',', $font['variants']);
						$selected_variants = explode(',', $font['selvariants']);
						$files = explode(',', $font['files']);
						$files = array_map(array(&$this, 'add_webfont_to_name'), $files);
						$variant_files = array();
						for ($i = 0; $i < count($variants); $i++) {
							if (in_array($variants[$i], $selected_variants)) {
								$variant_files[$variants[$i]] = $files[$i];
							}
						}

						foreach ($variant_files as $variant => $file) {
							$font_faces[$variant] = array();
							$font_faces[$variant]['family'] = $font['family'];
							$font_faces[$variant]['special'] = array();
							$font_faces[$variant]['sources'] = array();
							foreach ($extensions as $extension => $format) {
								if (@file_exists(trailingslashit($current_font_dir).$file.'.'.$extension)) {
									if ($extension == 'eot') {
										$font_faces[$variant]['special'][] = "url('".$font['stub'].'/'.$file.".eot')";
										$font_faces[$variant]['sources'][] = "url('".$font['stub'].'/'.$file.".eot?#iefix') format('embedded-opentype')";
									}
									else if ($extension == 'svg') {
										$font_faces[$variant]['sources'][] = "url('".$font['stub'].'/'.$file.".svg#$variant') format('svg')";
									}
									else {
										$font_faces[$variant]['sources'][] = "url('".$font['stub'].'/'.$file.".$extension') format('$format')";
									}
								}
							}
							if (count($font_faces[$variant]['sources']) === 0) {
								unset($font_faces[$variant]);
							}
						}
					}
				}
			}
		}

		$css = '';
		foreach ($font_faces as $font_face => $specs) {
			$css .= "@font-face {\n";
			$css .= "\tfont-family: \"".$specs['family']."\";\n";
			if (isset($specs['special'])) {
				$css .= "\tsrc: ".implode(",\n", $specs['special']).";\n";
			}
			$css .= "\tsrc: ".implode(",\n\t\t", $specs['sources']).";\n";
			if(strpos(strtolower($font_face),'hairline') !== false) {
				$weight = 100;
			} else if(strpos(strtolower($font_face),'light') !== false) {
				$weight = 200;
			} else if(strpos(strtolower($font_face),'regular') !== false) {
				$weight = 400;
			} else if(strpos(strtolower($font_face),'semibold') !== false) {
				$weight = 500;
			} else if(strpos(strtolower($font_face),'bold') !== false) {
				$weight = 600;
			} else if(strpos(strtolower($font_face),'black') !== false) {
				$weight = 800;
			} else {
				$weight = 400;
			}
			if(strpos(strtolower($font_face),'italic') !== false) {
				$style = 'italic';
			} else {
				$style = 'normal';
			}
			$css .= "\tfont-weight: ".$weight.";\n";
			$css .= "\tfont-style: ".$style.";\n";
			$css .= "}\n";
		}

		if (!@file_exists($this->font_directory)) {
			if (!wp_mkdir_p($this->font_directory)) {
				echo '<div class="uncode-ui-notice uncode-ui-notice--error">' . sprintf( wp_kses( __( 'Failed to create directory <code>%1$s</code>. Please make sure that you have permissions to create the folder.', 'uncode' ), array( 'code' => array() ) ), esc_url( $this->font_directory ) ) . '</div>';
				return;
			}
		}

		if (!(isset($this->options['fontsquirrel_combine']) && $this->options['fontsquirrel_combine'] == 'dont-combine')) {
			$this->setup_wp_filesystem();
			global $wp_filesystem;
			if (isset($wp_filesystem) && !$wp_filesystem->put_contents(trailingslashit($this->font_directory).'uncodefont.css', $css, $this->mod_file)) {
				echo '<div class="uncode-ui-notice uncode-ui-notice--error">' . esc_html__( 'Failed to save file uncodefont.css. Please check your folder permissions.', 'uncode' ) . '</div>';
				return;
			}
		}

		echo '<div class="uncode-ui-notice uncode-ui-notice--success">' . esc_html__( 'Fonts saved.', 'uncode' ) . '</div>';
	}

	function add_meta_boxes() {
		add_meta_box('uncodefont-google', 'Google Fonts', array(&$this, 'select_from_google_fonts'), $this->fonts_page_name, 'column1');
		add_meta_box('uncodefont-typekit', 'Adobe Fonts', array(&$this, 'select_from_typekit'), $this->fonts_page_name, 'column1');
		add_meta_box('uncodefont-fontdeck', 'Fontdeck', array(&$this, 'select_from_fontdeck'), $this->fonts_page_name, 'column1');
		add_meta_box('uncodefont-font-squirrel', 'Font Squirrel', array(&$this, 'select_from_font_squirrel'), $this->fonts_page_name, 'column1');
	}

	function render_options() { ?>
		<div class="wrap uncode-wrap settings-wrap" id="uncode-font">

			<?php echo uncode_admin_panel_page_title( 'fonts' ); ?>

			<div class="uncode-admin-panel">
				<?php echo uncode_admin_panel_menu( 'fonts' ); ?>

				<div class="uncode-admin-panel__content">

					<?php $this->after_form_submit(); ?>

					<div class="ui-tabs">
						<ul class="ui-tabs-nav uncode-admin-panel__left">
							<h2 class="uncode-admin-panel__heading"><?php esc_html_e( 'Options', 'uncode' ); ?></h2>
							<li id="tab_font_stack" class="ot-section-label">
								<a href="#section_font_stack"><?php esc_html_e( 'Font Imports', 'uncode' ); ?></a>
							</li>
							<li id="tab_font_source_settings" class="ot-section-label">
								<a href="#section_font_source_settings"><?php esc_html_e( 'Font Libraries', 'uncode' ); ?></a>
							</li>
						</ul>

						<div id="poststuff" class="metabox-holder uncode-admin-panel__right">
							<div id="post-body">
								<div id="post-body-content">
									<div id="section_font_stack">
										<h2 class="uncode-admin-panel__heading label"><?php esc_html_e( 'Font Imports', 'uncode' ); ?></h2>

										<form method="post" action="options.php">
											<input type="submit" name="Submit" class="button button-primary uncode-ui-button" value="<?php esc_html_e( 'Save Font Imports', 'uncode' ); ?>" />
											<?php $this->show_stack(); ?>
											<div class="metabox-holder">
												<div>
													<?php do_meta_boxes($this->fonts_page_name, 'column1', null); ?>
												</div>
												<div>
													<?php do_meta_boxes($this->fonts_page_name, 'column2', null); ?>
												</div>
											</div>
											<?php
											settings_fields('uncode_font_options-fonts');
											?>
										</form>
									</div><!-- #section_font_stack -->

									<div id="section_font_source_settings" class='font-source-settings'>
										<h2 class="uncode-admin-panel__heading label"><?php esc_html_e( 'Font Libraries', 'uncode' ); ?></h2>

										<form method="post" action="options.php">

											<input type="submit" name="Submit" class="button button-primary uncode-ui-button" value="<?php esc_html_e( 'Save Font Libraries', 'uncode' ); ?>" />

											<div class="font-sources__list">

												<div id="font-source-typekit" class="font-source">
													<div class="uncode-info-box">
														<h4 class="font-source__title"><?php esc_html_e( 'Adobe Fonts', 'uncode' ); ?></h4>
														<p class="font-source__description"><?php printf( wp_kses( __( 'You need a <a href="%1$s" target="_blank">Adobe Fonts API key</a> to access your Adobe Fonts.', 'uncode' ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_url( 'https://typekit.com/account/tokens' ) ); ?></p>

														<div class="font-source__field">
															<label class="font-source__label">
																<strong><?php esc_html_e( 'Enter your API Key', 'uncode' ); ?>:</strong>
																<input class="font-source__input widefat" type="text" id="typekit_api_key" name="uncode_font_options[typekit_api_key]" value="<?php if (isset($this->options['typekit_api_key'])) echo esc_attr($this->options['typekit_api_key']); ?>"/>
															</label>
														</div>

														<div class="font-source__field">
															<label class="font-source__label">
																<strong><?php esc_html_e( 'Enter your Kit ID', 'uncode' ); ?>:</strong>
																<input class="font-source__input widefat" type="text" id="typekit_kit_ID" name="uncode_font_options[typekit_kit_ID]" value="<?php if (isset($this->options['typekit_kit_ID'])) echo esc_attr($this->options['typekit_kit_ID']); ?>"/>
															</label>
														</div>

													</div><!-- .uncode-info-box -->
												</div><!-- .font-source -->

												<div id="font-source-fontdeck" class="font-source">
													<div class="uncode-info-box">
														<h4 class="font-source__title"><?php esc_html_e( 'Fontdeck', 'uncode' ); ?></h4>
														<p class="font-source__description"><?php printf( wp_kses( __( 'You will need to create a <a href="%1$s" target="_blank" tabindex="-1">Fontdeck</a> Project to access your fonts from Fontdeck.', 'uncode' ), array( 'a' => array( 'href' => array(), 'target' => array(), 'tabindex' => array() ) ) ), esc_url( 'http://fontdeck.com' ) ); ?></p>

														<div class="font-source__field">
															<label class="font-source__label">
																<strong><?php esc_html_e( 'Enter your project id:', 'uncode' ); ?></strong>
																<input class="font-source__input widefat" type="text" id="fondeck_project" name="uncode_font_options[fontdeck_project]" value="<?php if (isset($this->options['fontdeck_project'])) echo esc_attr($this->options['fontdeck_project']); ?>"/>
															</label>
														</div>
													</div><!-- .uncode-info-box -->
												</div><!-- .font-source -->

												<div id="font-source-squirrel" class="font-source">
													<div class="uncode-info-box">
														<h4 class="font-source__title"><?php esc_html_e( 'Font Squirrel', 'uncode' ); ?></h4>
														<div class="font-source__field">
															<p><strong><?php esc_html_e( 'Pull fonts from Font Squirrel?', 'uncode' ); ?></strong></p>
															<label class="font-source__label">
																<input class="font-source__input" type="radio" name="uncode_font_options[fontsquirrel_pull]" <?php if (isset($this->options['fontsquirrel_pull'])) { checked($this->options['fontsquirrel_pull'], 'pull'); } else { echo 'checked'; } ?> value='pull' /> <?php esc_html_e( 'Pull', 'uncode' ); ?>
															</label>
															<label class="font-source__label">
																<input class="font-source__input" type="radio" name="uncode_font_options[fontsquirrel_pull]" <?php if (isset($this->options['fontsquirrel_pull'])) checked($this->options['fontsquirrel_pull'], 'dont-pull'); ?> value='dont-pull' /> <?php esc_html_e( 'Don\'t Pull', 'uncode' ); ?>
															</label>
														</div>

													<div class="font-source__field">
														<p><strong><?php esc_html_e( 'Combine Font Squirrel CSS files?', 'uncode' ); ?></strong></p>
														<label class="font-source__label">
															<input class="font-source__input" type="radio" name="uncode_font_options[fontsquirrel_combine]" <?php if (isset($this->options['fontsquirrel_combine'])) { checked($this->options['fontsquirrel_combine'], 'combine'); } else { echo 'checked'; } ?> value='combine' /> <?php esc_html_e( 'Combine', 'uncode' ); ?>
														</label>
														<label class="font-source__label">
															<input class="font-source__input" type="radio" name="uncode_font_options[fontsquirrel_combine]" <?php if (isset($this->options['fontsquirrel_combine'])) checked($this->options['fontsquirrel_combine'], 'dont-combine'); ?> value='dont-combine' /> <?php esc_html_e( 'Don\'t Combine', 'uncode' ); ?>
														</label>
													</div>
												</div><!-- .font-source -->

											</div><!-- .font-sources__list -->

											<?php settings_fields( 'uncode_font_options-settings' ); ?>
										</form>
									</div><!-- .font-source-settings -->
								</div><!-- #post-body-content -->
							</div><!-- #post-body -->
						</div><!-- #poststuff -->

					</div><!-- .ui-tabs -->

				</div><!-- .uncode-admin-panel__content -->

			</div><!-- .uncode-admin-panel -->
		</div><!-- .uncode-wrap -->
		<?php
	}

	function add_webfont_to_name($value) {
		$ret = $value;
		if (substr_count($value, '-webfont.') === 0) {
			$ret = str_replace('.', '-webfont.', $value);
		}
		$ret = substr($ret, 0, strpos($ret, '.'));
		return $ret;
	}

	function enqueue_fontdeck_styles() {
		global $uncodefont_fontdeck_text, $uncodefont_fontdeck_error;
		$uncodefont_fontdeck_text = $uncodefont_fontdeck_error = '';
		$css_url = '';
		if (isset($this->options['fontdeck_project']) && trim($this->options['fontdeck_project']) != '') {
			$domain = $_SERVER['SERVER_NAME'];
			$project = $this->options['fontdeck_project'];
			$url = "http://f.fontdeck.com/s/css/json/$domain/$project.json";
			$curl_args = array(
				'sslverify' => false,
				'timeout' => 20000,
			);

			$response = wp_remote_request($url, $curl_args);
			if (!is_wp_error($response)) {
				$response = wp_remote_retrieve_body($response);
				$response = json_decode($response);
				wp_enqueue_style('uf-fontdeck', $response->cssurl, array(), UncodeCore_Plugin::VERSION);
				$css_url = $response->cssurl;

				if (isset($response->fonts) && count($response->fonts) > 0) {
					$fonts = $response->fonts;
					$uncodefont_fontdeck_text .= sprintf(esc_html__('The following fonts were found for project %1$s:', 'uncode'), $project);
					$uncodefont_fontdeck_text .= "<div id='uf-fd-$project-fonts' class='uf-fonts-for uf-group-key-position-1'>";
					$uncodefont_fontdeck_text .= "<ul>";
					foreach ($fonts as $font) {
						$uncodefont_fontdeck_text .= $this->create_font_line_item(
							'Fontdeck',
							'fd',
							$font,
							array(
								'family' => 'name',
								'generic' => '',
								'stub' => 'font_family',
								'variants' => 'weight',
								'subsets' => '',
							)
						);
					}
					$uncodefont_fontdeck_text .= "</ul>";
					$uncodefont_fontdeck_text .= "</div>";
				}
				else {
					$uncodefont_fontdeck_text .= sprintf(esc_html__('No fonts found for project %1$s on %2$s. Make sure your project id is correct and this domain is added to it in your Fontdeck account.', 'uncode'), $project, $domain);
				}
			}
			else {
				$uncodefont_fontdeck_error .= $this->connection_failed('Fontdeck', false);
			}
		}
		return $css_url;
	}

	function select_from_google_fonts() {

		global $wp_filesystem;
		if (empty($wp_filesystem)) {
			require_once (ABSPATH . '/wp-admin/includes/file.php');
		}
		$file = UNCODE_CORE_PLUGIN_DIR . '/includes/font-system/include/js/google_fonts.json';
		$can_read_file = true;
		if (false === ($creds = request_filesystem_credentials($file, '', false, false))) {
			$can_read_file = false;
		}
		/* initialize the API */
		if ( ! WP_Filesystem($creds) ) {
			/* any problems and we exit */
			$can_read_file = false;
		}
    /* Will result in $api_response being an array of data,
    parsed from the JSON response of the API listed above */
    if ($can_read_file) {
	    $response = $wp_filesystem->get_contents($file);

			if ($response) {

				$fonts = json_decode($response);

				if (isset($fonts) && is_array($fonts)) {
					$font_map = array();
					foreach ($fonts as $font) {
						$first_char = substr($font->family, 0, 1);
						if (!isset($font_map[$first_char])) {
							$first_char_fonts = array();
						}
						else {
							$first_char_fonts = $font_map[$first_char];
						}
						$first_char_fonts[] = $font;
						$font_map[$first_char] = $first_char_fonts;
					}

					$first_char_index = "";
					$fonts_by_first_letter = "";
					$first_char_position = 0;
					foreach ($font_map as $first_char => $first_char_fonts) {
						$first_char_position++;
						$first_char_index .= "<a href='#' id='uf-gf-$first_char' class='uf-group-key uf-group-key-$first_char uf-group-key-gf'>$first_char</a> | ";
						$fonts_by_first_letter .= "<div id='uf-gf-$first_char-fonts' class='uf-fonts-for uf-fonts-for-$first_char uf-group-key-position-$first_char_position uf-group-key-for-gf'><ul>\n";
						foreach ($first_char_fonts as $font) {
							$fonts_by_first_letter .= $this->create_font_line_item(
								'Google Web Fonts',
								'gf',
								$font,
								array(
									'family' => 'family',
									'generic' => '',
									'stub' => '',
									'variants' => 'variants',
									'subsets' => 'subsets',
								)
							);
						}
						$fonts_by_first_letter .= "</ul></div>\n";
					}

					if ($first_char_index != '') {
						$first_char_index = substr($first_char_index, 0, -2);
					}

					echo "<div class=\"uncode-index-alphabet\">".$first_char_index."</div>";
					echo do_shortcode($fonts_by_first_letter);
				}
			}
		} else {
			echo "<div>Error to read file: " . $file . "</div>";
		}
	}

	function select_from_font_squirrel() {
		if (isset($this->options['fontsquirrel_pull']) && $this->options['fontsquirrel_pull'] == 'dont-pull') {
			printf( wp_kses( __( 'You have chosen not to pull fonts from <a href="%1$s">Font Squirrel</a>. To change your settings <a href="%1$s">click here</a>.', 'uncode' ), array( 'a' => array( 'href' => array() ) ) ), '#font-source-squirrel' );
			return;
		}
		echo '<p>' . sprintf( wp_kses( __( 'Fonts will be downloaded from <a href="%1$s">Font Squirrel</a> to <code>%2$s</code>. Only downloaded fonts are available for addition to the stack.', 'uncode' ), array( 'a' => array( 'href' => array() ), 'code' => array() ) ), 'http://fontsquirrel.com', esc_url( $this->font_directory ) ) . '</p>';
		$url = "http://www.fontsquirrel.com/api/classifications";
		$args = array(
			'timeout' => 20000,
		);
		$response = wp_remote_request($url, $args);
		if (!is_wp_error($response)) {
			$response = wp_remote_retrieve_body($response);
			$classifications = json_decode($response);
			$class_string = '';
			$font_families = array();
			foreach ($classifications as $classification) {
				$sanitized_name = str_replace(' ', '-', urldecode($classification->name));
				$class_string .= "<a id='uf-fs-$sanitized_name' class='uf-group-key uf-group-key-$sanitized_name uf-group-key-fs' href='#'>".urldecode($classification->name)." (".$classification->count.")</a> | ";
				$font_families[$sanitized_name] = array();
			}
			$class_string = rtrim($class_string, ' | ');
			echo do_shortcode($class_string);

			$family_string = '';
			$family_url = 'http://www.fontsquirrel.com/api/fontlist/all';
			$family_response = wp_remote_request($family_url, $args);
			if (!is_wp_error($family_response)) {
				$family_response = wp_remote_retrieve_body($family_response);
				$fonts = json_decode($family_response);
				foreach ($fonts as $font) {
					if (isset($font->classification)) {
						$class = str_replace(' ', '-', urldecode($font->classification));
						if (isset($font_families[$class])) {
							$font_families[$class][] = $font;
						}
					}
				}

				$kit_position = 0;
				foreach ($font_families as $class => $families) {
					$kit_position++;
					$family_string .= "<div id='uf-fs-$class-fonts' class='uf-fonts-for uf-fonts-for-$class uf-group-key-position-$kit_position uf-group-key-for-fs'><ul>";
					foreach ($families as $family) {
						$family_string .= $this->create_font_line_item(
							'Font Squirrel',
							'fs',
							$family,
							array(
								'family' => 'family_name',
								'generic' => '',
								'stub' => 'family_urlname',
								'variants' => '',
								'subsets' => 'subset',
							)
						);
					}
					$family_string .= "</ul></div>";
				}
				echo do_shortcode($family_string);
			}
		}
	}

	function select_from_fontdeck() {
		if (!isset($this->options['fontdeck_project']) || trim($this->options['fontdeck_project']) == '') {
			printf( wp_kses( __( 'Please enter <a href="%1$s">your Fontdeck project id</a> to see the available Fontdeck fonts.', 'uncode' ), array( 'a' => array( 'href' => array() ) ) ), '#font-source-fontdeck' );
			return;
		}

		global $uncodefont_fontdeck_text, $uncodefont_fontdeck_error;
		if (trim($uncodefont_fontdeck_text) != '') {
			echo do_shortcode($uncodefont_fontdeck_text);
		}
		else if (trim($uncodefont_fontdeck_error) != '') {
			echo do_shortcode($uncodefont_fontdeck_error);
		}
	}

	function select_from_typekit() {
		if (!isset($this->options['typekit_api_key']) || trim($this->options['typekit_api_key']) == '') {
			printf( wp_kses( __( 'Please enter <a href="%s" target="_blank" tabindex="-1">your Adobe Fonts API Key</a> to see the available Adobe Fonts.', 'uncode' ), array( 'a' => array( 'href' => array() ) ) ), '#font-source-typekit' );
			return;
		}

		global $uncodefont_typekit_text, $uncodefont_typekit_error;
		if ($uncodefont_typekit_text != '') {
			echo do_shortcode($uncodefont_typekit_text);
		}
		else if ($uncodefont_typekit_error != '') {
			echo do_shortcode($uncodefont_typekit_error);
		}
	}

	function select_from_fonts_com() {
		printf( wp_kses( __( 'You need an <a href="%1$s">authentication key</a> to use fonts from Fonts.com.', 'uncode' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( 'https://webfonts.fonts.com/en-US/Account/AccountInformation' ) );
	}

	/**
	 * Validation function for the Settings API.
	 *
	 * @param $options
	 * @return array
	 */
	function validate_options($options) {
		$current_options = get_option('uncode_font_options');
		if (isset($current_options) && is_array($current_options)) {
			$options = array_merge($current_options, $options);
		}
		foreach ($options as $option => $option_value) {
			$options[$option] = esc_attr($option_value);
		}
		return $options;
	}

	/**
	 * Display the current font stack for the user. The left panel has a preview of the font, and the right panel has the details about variants, character subsets etc.
	 */
	function show_stack() { ?>
		<div class="uf-font-container">
			<div class="uf-font-preview">
		<?php
		$number_of_fonts = 0;
		echo '<ul id="uf-font-stack">';
		if (isset($this->options['font_stack'])) {
			$font_stack = $this->font_stack;
			$number_of_fonts = count($font_stack);
			foreach ($font_stack as $font) {
				if ($font['stub'] == '') {
					$font_family = "\"{$font['family']}\"";
				}
				else {
					$font_family = $font['stub'];
				}

				$pangram = 'Moveth, is and seas. Fly earth together so male their.';
				if ($font['source'] == 'Font Squirrel') {
					$font_family = "\"{$font['family']}\"";
				}

				echo "<li><span class='sample' style='font-family: $font_family;'>$pangram</span><span class='uf-stack-meta'><span class='uf-font-family'>{$font['family']}</span> <i class='fa fa-cross uf-remove-font'></i></span></li>";
			}
		}
		echo '</ul>';
		?>
			</div>
		<?php
		if ($number_of_fonts > 0) { ?>
			<div id="uf-font-details" class="uf-font-details">
				<h2>Preview</h2>
				<p><?php esc_html_e( 'Select a font from the left to see its details.', 'uncode' ); ?></p>

			</div>
			<?php
		}
		else { ?>
			<div id="uf-font-details" class="uf-font-details">
				<h2>Add Fonts</h2>
				<p><?php printf( wp_kses( __( 'You have no fonts in your stack. Please add a font first from the sources below. If you don\'t see any fonts below, make sure you have set up the <a href="%1$s">Font Sources</a> correctly.', 'uncode' ), array( 'a' => array( 'href' => array() ) ) ), '#font-source-typekit' ); ?></p>
			</div>
			<?php
		}
		?>

			<input type='hidden' id="font_stack" name="uncode_font_options[font_stack]" value="<?php echo esc_attr($this->font_stack_string); ?>" />
		</div>
	<?php
	}

	/**
	 * Displays a font from a source, with a "Preview" and an "Add" button.
	 *
	 * @param $source_system
	 * @param $source_system_prefix
	 * @param $font
	 * @param array $args
	 * @return string
	 */
	function create_font_line_item($source_system, $source_system_prefix, $font, $args = array()) {
		$defaults = array(
			'family' => 'family',
			'generic' => '',
			'stub' => '',
			'source' => $source_system,
			'variants' => array(),
			'subsets' => array(),
			'file_names' => array(),
		);

		$args = array_merge($defaults, $args);

		$ret = "<li>";
		$ret .= "<span class='uf-list-family'>" . $font->{$args['family']} . "</span>";
		$getSourceUrl = '#';
		switch ($source_system) {
			case 'Google Web Fonts':
				$getSourceUrl = 'https://www.google.com/fonts/specimen/' . $font->{$args['family']};
				break;
			case 'Font Squirrel':
				$getSourceUrl = 'http://www.fontsquirrel.com/fonts/' . $font->family_urlname;
				break;
			case 'Adobe Fonts':
				$getSourceUrl = 'https://fonts.adobe.com/fonts/' . $font->slug;
				break;
		}
		$preview = "<a href='".esc_url($getSourceUrl)."' target='_blank' class='uf-launch-preview' title='". esc_html__( 'Preview', 'uncode' ) . "'><i class='fa fa-search3'></i></a>";
		$add = "<a href='#' class='uf-add-font uf-add-font-$source_system_prefix' title='Add'><i class='fa fa-plus2'></i></a>";
		if ($source_system == 'Font Squirrel') {
			if (@file_exists($this->font_directory) && @file_exists(trailingslashit($this->font_directory).$font->{$args['stub']})) {
				$download = '';
				$delete_download = "<a href='#' class='uf-delete-download uf-delete-download-$source_system_prefix' title='". esc_html__( 'Delete Download', 'uncode' ) . "'><i class='fa fa-cross'></i></a>";
				$variant_information = $this->font_squirrel_get_font_information($font->{$args['stub']});
				if (isset($variant_information['variants'])) $variant_text = implode(',', $variant_information['variants']);
				$variant_files_text = implode(',', $variant_information['files']);
				$family_id_text = implode(',', array_unique($variant_information['family_ids']));
			}
			else {
				$download = "<a href='#' class='uf-download-font uf-download-font-$source_system_prefix' title='". esc_html__( 'Download', 'uncode' ) . "'><i class='fa fa-arrow-down2'></i></a>";
				$add = '';
				$delete_download = '';
			}
		}
		else {
			$download = '';
			$delete_download = '';
		}
		$ret .= "<span class='uf-prev-add'>$preview $add $download $delete_download</span>";
		if (isset($font->{$args['stub']})) {
			$ret .= "<span class='uf-font-stub'>".$font->{$args['stub']}."</span>";
		}
		if (isset($font->{$args['generic']})) {
			$ret .= "<span class='uf-font-generic'>".$font->{$args['generic']}."</span>";
		}
		if (isset($variant_text)) {
			$ret .= "<span class='uf-font-variants'>".$variant_text."</span>";
		}
		else if (isset($font->{$args['variants']})) {
			if (is_array($font->{$args['variants']})) {
				$variant = implode(',', $font->{$args['variants']});
			}
			else {
				$variant = $font->{$args['variants']};
			}
			$ret .= "<span class='uf-font-variants'>".$variant."</span>";
		}
		if (isset($variant_files_text)) {
			$ret .= "<span class='uf-font-variants-files'>".$variant_files_text."</span>";
		}
		if (isset($family_id_text)) {
			$ret .= "<span class='uf-font-family-id'>".$family_id_text."</span>";
		}
		if (isset($font->{$args['subsets']})) {
			if (is_array($font->{$args['subsets']})) {
				$subsets = implode(',', $font->{$args['subsets']});
			}
			else {
				$subsets = $font->{$args['subsets']};
			}
			$ret .= "<span class='uf-font-subsets'>".$subsets."</span>";
		}
		$ret .= "</li>";
		return $ret;
	}

	function font_squirrel_get_font_information($family) {
		$font_info_url = 'http://www.fontsquirrel.com/api/familyinfo/'.$family;
		$args = array(
			'timeout' => 20000,
		);
		$font_info = wp_remote_request($font_info_url, $args);
		if (!is_wp_error($font_info)) {
			$font_info = wp_remote_retrieve_body($font_info);
			$font_variants = json_decode($font_info);
			$variant_array = array();
			$variant_array['variants'] = array();
			$variant_array['files'] = array();
			$variant_array['family_ids'] = array();
			foreach ($font_variants as $font_variant) {
				if (isset($font_variant->fontface_name) && isset($font_variant->filename) && isset($font_variant->family_id)) {
					$variant_array['variants'][] = $font_variant->fontface_name;
					$variant_array['files'][] = $font_variant->filename;
					$variant_array['family_ids'][] = $font_variant->family_id;
				}
			}
			return $variant_array;
		}
		return array();
	}

	/**
	 * Error message to display / return if the user is not connected.
	 *
	 * @param $to_what
	 * @param bool $echo
	 * @return string
	 */
	function connection_failed($to_what, $echo = true) {
		$ret = sprintf(esc_html__('Sorry, there was an error accessing %1$s', 'uncode'), $to_what);
		if ($echo) {
			echo wp_kses_post($ret);
		}
		return $ret;
	}

	/**
	 * This method is meant for themes to invoke, so that the fonts defined by UncodeFont are added to the drop-down lists of fonts
	 * that the themes define.
	 *
	 * @param mixed $fonts The current list of fonts
	 * @param string $key_format The format of the key. This key refers to the HTML "value" attribute of the "select" element
	 * @param string $value_format This is the format of the displayed text in the drop-down
	 * @param bool $replace_stub_with_family_if_empty If the font stub isn't present (e.g. Google), this fills it in with the font family
	 * @param string $add_quotes The quote character to add to the fonts. Useful for Adobe Fonts which adds double quotes
	 * @return array
	 */
	public function add_more_fonts($fonts, $key_format = "%stub%", $value_format = "%family%", $replace_stub_with_family_if_empty = true, $add_quotes = "'") {
		if (!isset($this->options) || !is_array($this->options) || !isset($this->font_stack) || !is_array($this->font_stack)) {
			return $fonts;
		}

		if (!is_array($fonts)) {
			$fonts = array();
		}

		foreach ($this->font_stack as $font) {
			if ($font['source'] != 'Font Squirrel') {
				$mod_key = $this->substitute_font_parameters($font, $key_format, $replace_stub_with_family_if_empty, $add_quotes);
				$mod_value = $this->substitute_font_parameters($font, $value_format, $replace_stub_with_family_if_empty, $add_quotes);
				$fonts[$mod_key] = $mod_value;
			}
			else if (isset($font['selvariants'])){
				$selected_variants = explode(',', $font['selvariants']);
				foreach ($selected_variants as $selected_variant) {
					$fonts[$selected_variant] = $selected_variant;
				}
			}
		}
		return $fonts;
	}

	/**
	 * This tokenizes the format string for a font drop-down and adds UncodeFont's fonts in the specified format. The tokens
	 * are marked using % characters. E.g. %family% will be replaced by the font family.
	 *
	 * @param $font
	 * @param $lexed
	 * @param $replace_stub_with_family_if_empty
	 * @param $add_quotes
	 * @return mixed
	 */
	function substitute_font_parameters($font, $lexed, $replace_stub_with_family_if_empty, $add_quotes) {
		$parsed = $lexed;
		if ($add_quotes) {
			$family = $this->quotify_family($font['family'], $add_quotes);
			$stub = $this->quotify_family($font['stub'], $add_quotes);
		}
		else {
			$family = $font['family'];
			$stub = $font['stub'];
		}

		$parsed = str_replace("%family%", $family, $parsed);
		$parsed = ($replace_stub_with_family_if_empty && trim($stub) == '') ? str_replace("%stub%", $family, $parsed) : str_replace("%stub%", $stub, $parsed);
//		$parsed = str_replace("%stub%", $font['stub'], $parsed);
		$parsed = str_replace("%generic%", $font['generic'], $parsed);
		$parsed = str_replace("%source%", $font['source'], $parsed);
		$parsed = str_replace("%variants%", $font['selvariants'], $parsed);
		$parsed = str_replace("%subsets%", $font['selsubsets'], $parsed);
		return $parsed;
	}

	/**
	 * Changes single quotes to double quotes and vice versa in the font family name. This is used for consistency across the scripts.
	 *
	 * @param $family
	 * @param $add_quotes
	 * @return string
	 */
	function quotify_family($family, $add_quotes) {
		if (!$add_quotes) {
			return $family;
		}
		$family_parts = explode(',', $family);
		$quoted = array();
		foreach ($family_parts as $part) {
			if (stripos($part, ' ') > -1 && substr($part, 0, 1) != '"' && substr($part, 0, 1) != "'") {
				$part = $add_quotes.$part.$add_quotes;
			}
			else if ((substr($part, 0, 1) == '"' && $add_quotes == "'") || (substr($part, 0, 1) == "'" && $add_quotes == '"')) {
				$part = $add_quotes.substr($part, 1, strlen($part) - 2).$add_quotes;
			}
			$quoted[] = $part;
		}
		$family = implode(',', $quoted);
		return $family;
	}

	/**
	 * Adds UncodeFont fonts to the TinyMCE drop-down. Adobe fonts don't render properly in the drop-down and in the editor,
	 * because Adobe needs JS and TinyMCE doesn't support that.
	 *
	 * @param $opt
	 * @return array
	 */
	function extend_tinymce_dropdown($init_array) {
		if ( ! is_admin() || ! defined( 'UNCODE_SLIM' ) ) {
			return $init_array;
		}

		$google_font_counter = 0;
		$content_css = array();
		$fontdeck_included = false;

		$style_formats = array();
		$custom_fonts_array = ot_get_option('_uncode_font_groups');
		if (!empty($custom_fonts_array)) {
			foreach ($custom_fonts_array as $key => $value) {
				$style_formats[] = array(
		        		'title' => ($value['_uncode_font_group'] === 'manual') ? $value['title'] : $value['_uncode_font_group'],
		        		'classes' => $value['_uncode_font_group_unique_id'],
		        		'block' => 'span',
		        		'wrapper' => true,
		    		);
			}
		}

		foreach ($this->font_stack as $font) {
			if (@file_exists(trailingslashit($this->font_directory).'uncodefont.css')) $content_css[] = trailingslashit($this->font_directory_url).'uncodefont.css';

			if (isset($font['source']) && $font['source'] == 'Google Web Fonts') {
				$google_font_counter++;
				$font_family = $font['family'];
				$check_variants = explode(",", $font['variants']);
				if ( !in_array('regular', $check_variants) )
					$font_family = $font_family.':'.$check_variants[0];
				wp_enqueue_style('uf-google-font-'.$google_font_counter, '//fonts.googleapis.com/css?family='.urlencode($font_family), array(), null);
				$content_css[] = '//fonts.googleapis.com/css?family='.urlencode($font_family);
			}
			else if (isset($font['source']) && $font['source'] == 'Fontdeck') {
				if (!$fontdeck_included) {
					$fontdeck_css_url = $this->enqueue_fontdeck_styles();
					$fontdeck_included = true;
					$content_css[] = $fontdeck_css_url;
				}
			}
		}

		$content_css[] = get_template_directory_uri() . '/core/assets/css/admin-custom.css';
		$content_css = implode(',', $content_css);

		if (isset($init_array['content_css'])) {
		 	$init_array['content_css'] .= "," . $content_css;
		} else {
			$init_array['content_css'] = $content_css;
		}
		//theme_advanced_blockformats seems deprecated - instead the hook from Helgas post did the trick

		$init_array['style_formats'] = json_encode( $style_formats );

		return $init_array;
	}

	/**
	 * Adds the font selection drop-down to the TinyMCE editor in the admin panel.
	 *
	 * @param $buttons
	 * @return array
	 */
	function show_font_dropdown($buttons) {
		if (!is_admin()) {
			return $buttons;
		}

		array_unshift( $buttons, 'styleselect' );
		return $buttons;
	}

	/**
	 * Downloads a fontface kit from Font Squirrel and unzips the file to uploads/uncodefont. Unzipping makes use of the
	 * WP call <code>unzip_file</code>, which in turn needs <code>WP_Filesystem</code>
	 *
	 * @return bool
	 */
	function download_font() {
		if ( ! current_user_can( apply_filters( 'ot_theme_options_capability', 'edit_theme_options' ) ) )  {
        return;
    }
    if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'uncode_font_download' ) ) {
         return;
    }
		if (isset($_REQUEST['font_url'])) {
			$font_url = $_REQUEST['font_url'];

			if (!@file_exists($this->font_directory)) {
				if (!wp_mkdir_p($this->font_directory)) {
					echo json_encode(array(
						'error' => sprintf( esc_html__( 'Failed to create directory %1$s. Please make sure that you have permissions to create the folder.', 'uncode' ), esc_url( $this->font_directory ) )
					));
					die();
				}
			}

			$file_path = parse_url($font_url);
			$remote_file_info = pathinfo($file_path['path']);

			if (isset($remote_file_info['extension'])) {
				$remote_file_extension = $remote_file_info['extension'];
			}
			else {
				$remote_file_extension = 'zip';
			}

			$zip_file_name = $remote_file_info['basename'].'.'.$remote_file_extension;

			$this->setup_wp_filesystem();

			global $uncodefont_file_response;
			$args = array(
				'sslverify' => false,
				'timeout' => 20000,
			);
			if (!isset($uncodefont_file_response)) $uncodefont_file_response = wp_remote_request($font_url, $args);

			if (!is_wp_error($uncodefont_file_response)) {
				$zip_file = wp_remote_retrieve_body($uncodefont_file_response);
				global $wp_filesystem;
				if (isset($wp_filesystem) && !$wp_filesystem->put_contents(trailingslashit($this->font_directory).$zip_file_name, $zip_file, $this->mod_file)) {
					echo json_encode(array(
						'error' => sprintf( esc_html__( 'Failed to save %1$s to %2$s. Please ensure that the directory exists and is writable.', 'uncode' ), esc_url( $zip_file_name ), esc_url( $this->font_directory ) )
					));
					die();
				}
			}
			else {
				echo json_encode(array(
					'error' => sprintf( esc_html__( 'Failed to download file to %1$s. Please ensure that the directory exists and is writable.', 'uncode' ), esc_url( $this->font_directory ) )
				));
				die();
			}

			if ( ! class_exists( 'ZipArchive' ) ) {
				echo json_encode(array(
					'error' => sprintf( esc_html__( 'Failed to download file to %1$s. Please ensure that the Zip extension is enabled on your server.', 'uncode' ), esc_url( $this->font_directory ) )
				));
				die();
			}

			$zip = new ZipArchive;
			if ( @file_exists(trailingslashit($this->font_directory).$zip_file_name) && $zip->open( trailingslashit($this->font_directory).$zip_file_name, ZIPARCHIVE::CHECKCONS ) )
			{
				if ( !$wp_filesystem->is_dir( trailingslashit($this->font_directory).$remote_file_info['basename'] )) {
					$wp_filesystem->mkdir( trailingslashit($this->font_directory).$remote_file_info['basename'], FS_CHMOD_DIR );
				}

				for ( $i=0; $i < $zip->numFiles; $i++ )
				{
					$entry = $zip->getNameIndex($i);

					if ( substr( $entry, -1 ) == '/' ) continue; // skip directories
					if ( strpos($entry,'/._') !== false ) continue; // skip mac files

					$wp_filesystem->put_contents(trailingslashit($this->font_directory).$remote_file_info['basename'].'/'.basename($entry),$zip->getFromName($entry),$this->mod_file);

				}

				$zip->close();
				unlink(trailingslashit($this->font_directory).$zip_file_name);

			}

			$variants = $this->font_squirrel_get_font_information($remote_file_info['basename']);
			$variant_names = implode(',', $variants['variants']);
			$variant_files = implode(',', $variants['files']);
			$family_id = implode(',', array_unique($variants['family_ids']));

			echo json_encode(array(
				'success' => esc_html__( "Font downloaded and extracted successfully.", "uncode" ),
				'variants' => $variant_names,
				'files' => $variant_files,
				'family_id' => $family_id,
			));
		}
		die();
	}

	/**
	 * Deletes a downloaded zip file and the associated unzipped directory from uploads/uncodefont. Since the directory
	 * has been unzipped using the WP call unzip_file, the deletion requires WP_Filesystem.
	 */
	function delete_download() {
		if (isset($_REQUEST['font_family'])) {
			$font_family = $_REQUEST['font_family'];
			$font_dir = trailingslashit($this->font_directory).$font_family;
			$fontkit_zip = $font_dir.'.zip';

			if (@file_exists($fontkit_zip)) {
				if (!@unlink($fontkit_zip)) {
					echo json_encode(array(
						'error' => sprintf( esc_html__( 'Failed to delete @fontface kit zip %1$s.', 'uncode' ), $fontkit_zip )
					));
					die();
				}
			}

			// Cannot delete the directory, because unzip_file, which has created it, uses WP_Filesystem. So we use WP_Filesystem to delete it.
			$this->setup_wp_filesystem();

			global $wp_filesystem;
			if (isset($wp_filesystem)) {
				$delete_dir = $wp_filesystem->delete($font_dir, true);
				if (!$delete_dir) {
					echo json_encode(array('error' => $delete_dir['error']));
					die();
				}
			}

			echo json_encode(array('success' => "Download deleted"));
		}
		die();
	}

	/**
	 * Sets up the WP_Filesystem object for use by other functions.
	 *
	 * @return bool
	 */
	private function setup_wp_filesystem() {
		$url = wp_nonce_url($this->fonts_page_name);
		if (false === ($creds = request_filesystem_credentials($url, '', false, false))) {
			return true;
		}

		if (!WP_Filesystem($creds)) {
			request_filesystem_credentials($url, '', true, false);
			return true;
		}
		return true;
	}
}

global $uncodefont;
$uncodefont = new UncodeFont();
