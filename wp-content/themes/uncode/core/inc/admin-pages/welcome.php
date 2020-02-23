<?php
/**
 * Welcome page
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="wrap uncode-wrap" id="option-tree-settings-api">

	<?php echo uncode_admin_panel_page_title( 'welcome' ); ?>

	<div class="uncode-admin-panel">
		<?php echo uncode_admin_panel_menu( 'welcome' ); ?>

		<div class="uncode-admin-panel__content uncode-admin-panel__content--two-cols">

			<div class="uncode-admin-panel__left">
				<?php if ( defined('ENVATO_HOSTED_SITE') ) { ?>
					<h2 class="uncode-admin-panel__heading"><?php esc_html_e( 'Welcome', 'uncode' ); ?></h2>
				<?php } else { ?>
					<h2 class="uncode-admin-panel__heading"><?php esc_html_e( 'Registration', 'uncode' ); ?></h2>
				<?php } ?>

				<div class="uncode-info-box">
					<p class="uncode-admin-panel__description"><?php esc_html_e('Uncode is ready to be used with your WordPress site. Please register your product to get automatic updates and to install premium plugins.','uncode'); ?></p>

					<?php
					$uncode_envato_purchase_code = uncode_get_purchase_code();
					?>

					<form method="POST" id="uncode-registration-form" class="uncode-registration-form">

						<div class="format-setting-wrap">
							<div class="format-setting-label">
								<h3 class="label"><?php esc_html_e( 'Envato Purchase Code', 'uncode' ); ?></h3>
							</div>

							<div class="format-setting has-desc">
								<div class="description"><?php printf(esc_html__( 'Please insert your Envato Purchase Code. %s.','uncode'), '<a tabindex="-1" id="uncode-purchase-code-trigger" href="#">'.esc_html__('More info','uncode').'</a>'); ?></div>
								<div class="format-setting-inner">
									<input type="text" name="envato_purchase_code" id="envato-purchase-code" class="widefat option-tree-ui-input" value="<?php echo esc_attr( $uncode_envato_purchase_code ); ?>">
								</div>
							</div>

							<div class="uncode-purchase-code-info" style="display: none;">

								<h4><?php printf(esc_html__( 'Where can I find my Envato Purchase Code?','uncode'), '<a tabindex="-1" href="' . esc_url('//help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-') . '" target="_blank">'.esc_html__('video','uncode').'</a>'); ?>:</h4>
								<ol>
									<li><?php printf( esc_html__( 'Access your %s with the account that was used to purchase Uncode','uncode'), '<a tabindex="-1" href="' . esc_url('//themeforest.net/downloads') . '" target="_blank">'.esc_html__('ThemeForest downloads','uncode').'</a>'); ?>.</li>
									<li><?php esc_html_e( 'Look for Uncode in your list of purchases, click the Download button and select \'License Certificate & Purchase Code\'', 'uncode' ); ?>.</li>
									<li><?php esc_html_e( 'Copy the \'Item Purchase Code\' into the field \'Envato Purchase Code\' of the Registration page', 'uncode' ); ?>.</li>
								</ol>
								<a class="button button-primary external-button" href="<?php echo esc_url('//themeforest.net/downloads'); ?>" target="_blank"><span><?php esc_html_e('Your ThemeForest Downloads','uncode'); ?></span></a>
							</div><!-- .uncode-purchase-code-info -->
						</div>

						<?php if ( ! $uncode_envato_purchase_code ) : ?>

							<div id="uncode-admin-registration-notice-container"></div>

							<div class="register-product-fields-container">

								<div class="format-setting-wrap">
									<div class="format-setting-inner envato-agreement">
										<label><input type="checkbox" class="alignleft" name="uncode_registration_accept_terms" id="uncode-registration-accept-terms">
											<small class="alignright"><?php printf( esc_html__( 'Confirm that, according to the Envato License Terms, each license entitles one person for a single project. Creating multiple unregistered installations is a copyright violation. %s.', 'uncode' ), '<a tabindex="-1" href="' . esc_url('//support.undsgn.com/hc/en-us/articles/360000718649') . '" target="_blank">'.esc_html__('More info','uncode').'</a>'); ?></small></label>
									</div>
								</div>

								<div>
									<button class="button button-primary" type="submit" id="envato_update_info" name="envato_update_info" disabled="disabled"><span><span class="uncode-ot-spinner"></span><?php esc_html_e( 'Register your product', 'uncode' ); ?></span></button>
								</div>
							</div>

						<?php endif; ?>

						<div class="deregister-product-fields-container" <?php if ( ! $uncode_envato_purchase_code ) { echo 'style="display: none;"'; } ?>>
							<input type="hidden" name="uncode_deregister_product" value="1" />
							<?php wp_nonce_field( 'uncode-deregister-product-nonce', 'uncode_deregister_product_nonce' ); ?>

							<button class="button button-primary" type="submit"><span><span class="uncode-ot-spinner"></span><?php esc_html_e( 'Deregister your product', 'uncode' ); ?></span></button>
						</div>

					</form>

				</div><!-- .uncode-info-box -->

			</div><!-- .uncode-admin-panel__left -->

			<div class="uncode-admin-panel__right">
				<h2 class="uncode-admin-panel__heading"><?php esc_html_e( 'System Status', 'uncode' ); ?></h2>

				<p class="uncode-admin-panel__description"><?php
					if ( defined('ENVATO_HOSTED_SITE') ) {
						 esc_html_e("Under System Status, you can find important information about your WordPress setup.", "uncode");
					} else {
						printf(esc_html__("Under System Status, you can find important information about your server setup. If you see red errors that indicate problems, it is likely that you're not in compliance with Uncode's %s.", "uncode"), '<a href="' . esc_url('//support.undsgn.com/hc/en-us/articles/213453949') . '" target="_blank">'.esc_html__('Server Requirements','uncode').'</a>');
					}
				?></p>

				<table class="widefat system-status-list" cellspacing="0" id="status">
					<tbody>
						<tr>
							<td data-export-label="License"><?php echo esc_html__("Theme version", "uncode"); ?>
							<?php echo '<span class="toggle-description"></span><small class="description">' . esc_html__( 'The version of Uncode installed on your site.', 'uncode' ) . '</small>'; ?></td>
							<td>
							<?php
								$theme_data = wp_get_theme();
								echo esc_attr(UNCODE_PARENT_VERSION);
							?>
							</td>
			            </tr>
		            <?php if ( is_child_theme() ) : ?>
						<tr>
							<td data-export-label="Child Theme"><?php echo esc_html__("Child Theme", "uncode"); ?>
							<?php echo '<span class="toggle-description"></span><small class="description">' . esc_html__( 'Name and version of child theme installed on your site.', 'uncode' ) . '</small>'; ?></td>
							<td>
							<?php printf( wp_kses_post( _x( '%s - %s', 'Child theme name and version', 'uncode' ) ), $theme_data->get( 'Name' ), $theme_data->get( 'Version' ) ) . '</small>'; ?>
							</td>
			            </tr>
			        <?php endif; ?>
			        <?php if ( ! defined('ENVATO_HOSTED_SITE') ) : ?>

						<tr>
							<td data-export-label="Product Registration"><?php echo esc_html__("Product Registration", "uncode"); ?>
							<?php echo '<span class="toggle-description"></span><small class="description">' . esc_html__( 'Please validate your product license as outlined in Envato\'s license terms.', 'uncode' ) . '</small>'; ?></td>
							<td>
							<?php
							if ( $uncode_envato_purchase_code ) {
								echo '<mark class="yes">' . esc_html__( 'Theme registered', 'uncode' ) . '</mark>';
							} else {
								echo '<mark class="yes registration-ok-message" style="display:none">' . esc_html__( 'Theme registered', 'uncode' ) . '</mark>';
								echo '<mark class="error registration-error-message">' . esc_html__( 'Not registered', 'uncode' ) . '</mark>';
							}
							?>
							</td>
						</tr>
				        <?php endif; ?>
						<?php if ( ! class_exists( 'UncodeCore_Plugin' ) ) : ?>
							<tr>
								<td data-export-label="Uncode Core"><?php esc_html_e( 'Uncode Core', 'uncode' ); ?>
								<?php echo '<span class="toggle-description"></span><small class="description">' . esc_html__( 'Whether or not you have Uncode Core active.', 'uncode' ) . '</small>'; ?></td>
								<td><mark class="error"><?php printf( __( 'Not active, please <a href="%s">activate it</a>', 'uncode' ), admin_url( 'admin.php?page=uncode-plugins' ) ); ?></mark></td>
							</tr>
						<?php endif; ?>

						<?php if ( current_user_can( 'switch_themes' ) ) : ?>
							<tr>
								<td data-export-label="Frontend Stylesheet"><?php esc_html_e( 'Frontend Stylesheet', 'uncode' ); ?>
								<?php echo '<span class="toggle-description"></span><small class="description">' . esc_html__( 'Uncode is generating a stylesheet when the options are saved. The file must be writtable.', 'uncode' ) . '</small>'; ?></td>
								<td><?php
									global $wp_filesystem;
									if (empty($wp_filesystem)) {
										require_once (ABSPATH . '/wp-admin/includes/file.php');
									}
									$mod_file = (defined('FS_CHMOD_FILE')) ? FS_CHMOD_FILE : false;
									$front_css = get_template_directory() . '/library/css/';
									$front_css_file = $front_css . 'style-custom.css';
									$creds = request_filesystem_credentials($front_css, '', false, false, array());
									$can_write_front = true;
									if (!!$creds) {
										/* initialize the API */
										if ( ! WP_Filesystem($creds) ) {
											/* any problems and we exit */
											$can_write_front = false;
										}
									}
									$filename = trailingslashit($front_css).'test.txt';
									if ( ! $wp_filesystem->put_contents( $filename, 'Test file contents', $mod_file) ) {
										$can_write_front = false;
									} else {
										$wp_filesystem->delete( $filename );
									}

									$file_is_writable = wp_is_writable($front_css_file);

									$front_css = '..' . substr($front_css, strpos($front_css,"/wp-content"));
									$front_css_file = $front_css . 'style-custom.css';

									if ($can_write_front) {
										if ( ! $file_is_writable ) {
											printf( '<div class="uncode-note">' . wp_kses(__( 'WordPress doesn\'t have direct access to this file <code>%s</code>. This is most likely due to a conflict with server file permissions. It is also possible that WordPress\' file access is not configured correctly. The custom CSS will be output inline.', 'uncode' ), array( 'code' => '' )) . '</div>', $front_css_file  );
										} else {
											echo '<mark class="yes">' . '<code class="yes">' . $front_css .'</code></mark> ';
										}
									} else {
										printf( '<div class="uncode-note">' . wp_kses(__( 'WordPress doesn\'t have direct access to this folder <code>%s</code>. This is most likely due to a conflict with server file permissions. It is also possible that WordPress\' file access is not configured correctly. The custom CSS will be output inline.', 'uncode' ), array( 'code' => '' )) . '</div>', $front_css  );
									}
								?></td>
							</tr>
							<tr>
								<td data-export-label="Backend Stylesheet"><?php esc_html_e( 'Backend Stylesheet', 'uncode' ); ?>
								<?php echo '<span class="toggle-description"></span><small class="description">' . esc_attr__( 'Uncode is generating a stylesheet when the options are saved. The file must be writtable.', 'uncode' ) . '</small>'; ?></td>
								<td><?php
									$mod_file = (defined('FS_CHMOD_FILE')) ? FS_CHMOD_FILE : false;
									$back_css = get_template_directory() . '/core/assets/css/';
									$back_css_file = $back_css . 'admin-custom.css';
									$creds = request_filesystem_credentials($back_css, '', false, false, array());
									$can_write_back = true;
									if (!!$creds) {
										/* initialize the API */
										if ( ! WP_Filesystem($creds) ) {
											/* any problems and we exit */
											$can_write_back = false;
										}
									}
									$filename = trailingslashit($back_css).'test.txt';
									if ( ! $wp_filesystem->put_contents( $filename, 'Test file contents', $mod_file) ) {
										$can_write_back = false;
									} else {
										$wp_filesystem->delete( $filename );
									}

									$back_is_writable = wp_is_writable($back_css_file);

									$back_css = '..' . substr($back_css, strpos($back_css,"/wp-content"));
									$back_css_file = $back_css . 'admin-custom.css';

									if ($can_write_back) {
										if ( ! $back_is_writable ) {
											printf( '<div class="uncode-note">' . wp_kses(__( 'WordPress doesn\'t have direct access to this file <code>%s</code>. This is most likely due to a conflict with server file permissions. It is also possible that WordPress\' file access is not configured correctly. The custom CSS will be output inline.', 'uncode' ), array( 'code' => '' )) . '</div>', $back_css_file  );
										} else {
											echo '<mark class="yes">' . '<code class="yes">' . $back_css .'</code></mark> ';
										}
									} else {
										printf( '<div class="uncode-note">' . wp_kses(__( 'WordPress doesn\'t have direct access to this folder <code>%s</code>. This is most likely due to a conflict with server file permissions. It is also possible that WordPress\' file access is not configured correctly. The custom CSS will be output inline.', 'uncode' ), array( 'code' => '' )) . '</div>', $back_css  );
									}
								?></td>
							</tr>
							<?php if ( ! defined('ENVATO_HOSTED_SITE') ) : ?>
								<?php if ( function_exists( 'phpversion' ) && version_compare( phpversion(), '5.6.0', '<' ) ) : ?>
									<tr>
										<td data-export-label="PHP Version"><?php esc_html_e( 'PHP Version', 'uncode' ); ?></td>
										<td><?php
											$php_vers = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
											echo '<mark class="error">' . sprintf(esc_html__('%s - Please update your PHP version We recommend at least PHP 5.6.0','uncode'), $php_vers) . '</mark>';
										?></td>
									</tr>
								<?php endif; ?>
							<tr>
								<td data-export-label="WP Memory Limit"><?php esc_html_e( 'WP Memory Limit', 'uncode' ); ?>
								<?php echo '<span class="toggle-description"></span><small class="description">' . esc_attr__( 'Memory Limits not satisfied may produce possible errors on the frontend of the site as blank pages.', 'uncode' ) . '</small>'; ?></td>
								<td><?php
									$memory = uncode_let_to_num( WP_MEMORY_LIMIT );

									if ( $memory < 100663296 ) {
										echo '<mark class="error">' . sprintf(esc_html__('%s - We recommend setting memory to at least 96 MB. %s','uncode'), size_format( $memory ), '<a href="' . esc_url('//support.undsgn.com/hc/en-us/articles/213459889') . '" target="_blank">'.esc_html__('More info','uncode').'</a>') . '</mark>';
									} else {
										echo '<mark class="yes">' . size_format( $memory ) . '</mark>';
									}
								?></td>
							</tr>
							<?php do_action( 'uncode_server_memory_limit' ); ?>
							<tr>
								<td data-export-label="PHP Max Input Vars"><?php esc_html_e( 'PHP Max Input Vars', 'uncode' ); ?>
								<?php echo '<span class="toggle-description"></span><small class="description">' . esc_attr__( 'Max Input Vars not satisfied may result in loss of Theme Options.', 'uncode' ) . '</small>'; ?></td>
								<td><?php
									$max_input = ini_get('max_input_vars');
									if ( $max_input < uncode_get_recommended_max_input_vars() ) {
										echo '<mark class="error">' . sprintf( wp_kses(__( '%s - We recommend setting PHP max_input_vars to at least %s. See: <a href="%s" target="_blank">Increasing the PHP max vars limit</a>', 'uncode' ), array( 'a' => array( 'href' => array(),'target' => array() ) ) ), $max_input, uncode_get_recommended_max_input_vars(), '//support.undsgn.com/hc/en-us/articles/213459869' ) . '</mark>';
									} else {
										echo '<mark class="yes">' . $max_input . '</mark>';
									}
								?></td>
							</tr>
							<tr>
								<td data-export-label="PHP Max Input Vars Allowed"><?php esc_html_e( 'PHP Max Input Vars (allowed)', 'uncode' ); ?>
								<?php echo '<span class="toggle-description"></span><small class="description">' . esc_attr__( 'The effective maximum number of variables your server can use for a single function to avoid overloads. If this value is lower than max_input_vars your server is applying restrictions on the actual number of vars that can be used.', 'uncode' ) . '<br>' . esc_attr__( 'If you modified the server settings refresh the option to test.', 'uncode' ) . '</small>'; ?></td>
								<td class="get-max-input-vars">
									<?php wp_nonce_field( 'uncode-system-status-test-input-vars-nonce', 'test_input_vars_from_system_status_nonce' ); ?>
									<?php $uncode_test_max_input_vars = intval(get_option('uncode_test_max_input_vars'));
										if ( $uncode_test_max_input_vars != '' ) : ?>
										<span class="calculating" style="display: none"><?php esc_html_e( 'Calculating…', 'uncode' ); ?></span>
										<mark class="yes" <?php if ( $uncode_test_max_input_vars < uncode_get_recommended_max_input_vars() ) { echo 'style="display: none;"'; } ?>><?php echo esc_html( $uncode_test_max_input_vars ); ?></mark>
										<mark class="error get_data" <?php if ( $uncode_test_max_input_vars >= uncode_get_recommended_max_input_vars() ) { echo 'style="display: none;"'; } ?>>
											<mark class="calculated-max-vars"><?php echo esc_html( $uncode_test_max_input_vars ); ?></mark>
											<?php printf(esc_html__(' - We recommend setting PHP max_input_vars to at least %s. %s','uncode'), uncode_get_recommended_max_input_vars(), '<a href="' . esc_url('//support.undsgn.com/hc/en-us/articles/213459869') . '" target="_blank">'.esc_html__('More info','uncode').'</a>'); ?>
										</mark>
										<mark class="error no_data" style="display: none;"><?php esc_html_e('No available data','uncode'); ?></mark>
										<a href="#" id="max_vars_checker"><i class="fa fa-refresh"></i></a>
									<?php else : ?>
										<span class="calculating"><?php esc_html_e( 'Calculating…', 'uncode' ); ?></span>
										<mark class="yes" style="display: none;"></mark>
										<mark class="error get_data" style="display: none;">
											<mark class="calculated-max-vars"></mark>
											<?php printf(esc_html__(' - We recommend setting PHP max_input_vars to at least %s. %s','uncode'), uncode_get_recommended_max_input_vars(), '<a href="' . esc_url('//support.undsgn.com/hc/en-us/articles/213459869') . '" target="_blank">'.esc_html__('More info','uncode').'</a>'); ?>
										</mark>
										<mark class="error no_data" style="display: none;"><?php esc_html_e('No available data','uncode'); ?></mark>
										<a href="#" id="max_vars_checker"><i class="fa fa-refresh"></i></a>
									<?php endif; ?>
								</td>
							</tr>
							<?php endif; ?>
						<?php endif; ?>
					</tbody>
				</table>
				<?php do_action('uncode_welcome'); ?>
			</div><!-- .uncode-admin-panel__right -->
		</div><!-- .uncode-admin-panel__content -->
	</div><!-- .uncode-admin-panel -->
</div><!-- .uncode-wrap -->

<script type="text/javascript">

	jQuery( document ).ready( function ( $ ) {
		$( '.help_tip' ).tipTip({
			attribute: 'data-tip'
		});

		$( 'a.help_tip' ).click( function() {
			return false;
		});

		var max_vars_nonce = $('#test_input_vars_from_system_status_nonce').val();
		var enable_debug = <?php echo apply_filters( 'uncode_enable_debug_on_js_scripts', false ) ? 'true' : 'false'; ?>;

		var max_vars_checker = function(){
			var $wrap = $('.get-max-input-vars'),
				$calculating = $('.calculating', $wrap),
				$errors = $('.error', $wrap),
				$yes = $('.yes', $wrap),
				$checker = $('#max_vars_checker');

			$checker.on('click', function(e){
				e.preventDefault();

				$yes.add($errors).add($checker).fadeOut(200);
				setTimeout(function(){
					$calculating.fadeIn(200);
					uncode_test_max_input_vars(10000);
				}, 200);

			});

		};
		max_vars_checker();

		<?php if ( current_user_can( 'switch_themes' ) ) { ?>

		var uncode_test_max_input_vars = function($vars){
			var param = [],
				var_string,
				intData;
			for (i = 0; i < $vars; i++) {
				param[i] = 'var_'+i;
			}

			$.ajax({
				url: ajaxurl,
				data: {
					action: 'uncode_test_vars',
					test_input_vars_from_system_status_nonce: max_vars_nonce,
					content: param,
				},
				type: 'post',
				error: function() {
					$('.get-max-input-vars .calculating').hide();
					$('.get-max-input-vars .error.no_data').fadeIn();

					if (SiteParameters.enable_debug == true) {
						// This console log is disabled by default
						// So nothing is printed in a typical installation
						//
						// It can be enabled for debugging purposes setting
						// the 'uncode_enable_debug_on_js_scripts' filter to true
						console.log('Max vars test failed');
					}
				},
				success: function(response) {
					if (response && response.success === false) {
						$('.get-max-input-vars .calculating').hide();
						$('.get-max-input-vars .error.no_data').fadeIn();

						if (SiteParameters.enable_debug == true) {
							// This console log is disabled by default
							// So nothing is printed in a typical installation
							//
							// It can be enabled for debugging purposes setting
							// the 'uncode_enable_debug_on_js_scripts' filter to true
							console.log('Max vars test failed');
						}
					} else if (response && response.success === true) {
						intData = parseInt(response.data.count);

						if ( intData < ($vars-1) ) {
							if ( intData < <?php echo uncode_get_recommended_max_input_vars(); ?> ) {
								var_string = $('.get-max-input-vars .error.get_data');
								$('.calculated-max-vars').addClass('error').html(intData);
							} else {
								var_string = $('.get-max-input-vars .yes');
								var_string.html(intData);
							}
							$('.get-max-input-vars .calculating').hide();
							var_string.add('#max_vars_checker').fadeIn();

						} else {
							uncode_test_max_input_vars($vars+10000);
						}

						$.ajax({
							url: ajaxurl,
							data: {
								action: 'uncode_update_max_input_vars',
								calculated_vars: intData,
								update_input_vars_from_system_status_nonce: max_vars_nonce
							},
							type: 'post'
						}).done(function(response) {
							if (enable_debug == true && response && response.success === false) {
								// This console log is disabled by default
								// So nothing is printed in a typical installation
								//
								// It can be enabled for debugging purposes setting
								// the 'uncode_enable_debug_on_js_scripts' filter to true
								console.log('Max vars update failed');
							}
						}).fail(function() {
							if (enable_debug == true) {
								// This console log is disabled by default
								// So nothing is printed in a typical installation
								//
								// It can be enabled for debugging purposes setting
								// the 'uncode_enable_debug_on_js_scripts' filter to true
								console.log('Max vars update failed');
							}
						});
					} else {
						// Unknow error, show the modal
						if (SiteParameters.enable_debug == true) {
							// This console log is disabled by default
							// So nothing is printed in a typical installation
							//
							// It can be enabled for debugging purposes setting
							// the 'uncode_enable_debug_on_js_scripts' filter to true
							console.log('Unknown error during max vars text');
						}
					}
				}
			});
		};

		<?php } ?>

		jQuery( function ( $ ) {
			'use strict';

			$('#uncode-purchase-code-trigger').on('click', function(event) {
				var envato_token_info = $('.uncode-purchase-code-info').html();
				$("<div />").html(envato_token_info).dialog({
					autoOpen: true,
					modal: true,
					dialogClass: 'uncode-modal',
					title: "<?php echo esc_html__('Where can I find my Envato Purchase Code?', 'uncode'); ?>",
					maxHeight: 800,
					//minHeight: 500,
					//minWidth: 500,
					width: 600,
					position: { my: "center", at: "center", of: window },
					open: function( event, ui ) {
						$('body').addClass('overflow_hidden');
					},
					close: function( event, ui ) {
						$('body').removeClass('overflow_hidden');
					}
				});
			});

			//////////////////////////////////////////////////////
			/// Theme registration
			//////////////////////////////////////////////////////

			var themeRegistrationTermsCheckbox = document.getElementById('uncode-registration-accept-terms');
			var themeRegistrationButton = document.getElementById('envato_update_info');

			if (themeRegistrationTermsCheckbox !== null && themeRegistrationTermsCheckbox.checked) {
				themeRegistrationButton.disabled = false;
			}

			if ( themeRegistrationTermsCheckbox !== null ) {
				themeRegistrationTermsCheckbox.addEventListener('change', function() {
					if (themeRegistrationTermsCheckbox.checked) {
						themeRegistrationButton.disabled = false;
					} else {
						themeRegistrationButton.disabled = true;
					}
				});
			}

		});
		<?php if ( current_user_can( 'switch_themes' ) && $uncode_test_max_input_vars == '' ) { ?>
		uncode_test_max_input_vars(10000);
		<?php } ?>

	});

</script>
