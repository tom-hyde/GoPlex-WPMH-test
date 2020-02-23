<div class="wrap uncode-privacy-wrap">
	<h1><?php esc_html_e( 'Settings', 'uncode-privacy' ); ?></h1>

	<div class="nav-tab-wrapper">
		<?php foreach ( $tabs as $tab => $value ) : ?>
			<a href="<?php echo '#' . $tab; ?>" class="nav-tab">
				<?php echo esc_html( $value ); ?>
			</a>
		<?php endforeach; ?>
	</div>

	<?php settings_errors(); ?>

	<form action="options.php" method="post" class="uncode-privacy-settings-form">

		<?php settings_fields( 'uncode-privacy' ); ?>

		<div class="uncode-privacy-tab hidden" data-id="general">
			<h2><?php esc_html_e( 'General', 'uncode-privacy' ) ?></h2>
			<table class="form-table" data-id="general">
				<tbody>
					<tr>
						<th scope="row">
							<label for="uncode_privacy_privacy_policy_page"><?php esc_html_e( 'Privacy Policy Page', 'uncode-privacy' ) ?></label>
						</th>
						<td>
							<?php
								$pages = get_pages();
							?>
							<select name="uncode_privacy_privacy_policy_page" id="uncode_privacy_privacy_policy_page">
								<option value=""><?php esc_html_e( '-- Select --', 'uncode-privacy' ) ?></option>
								<?php foreach ( $pages as $page ): ?>
									<option value="<?php echo esc_attr( $page->ID ) ?>" <?php selected( $privacy_policy_page, $page->ID ); ?>><?php echo esc_html( $page->post_title ); ?></option>
								<?php endforeach ?>
							</select>
						</td>
					</tr>
				</tbody>
			</table>

			<h2 class="title"><?php esc_html_e( 'Privacy Center', 'uncode-privacy' ); ?></h2>

			<p>
				<?php esc_html_e( 'This section handles the Privacy Banner and Privacy Prefences main text.', 'uncode-privacy' ) ?><br>
				<strong><?php esc_html_e( 'Important:', 'uncode-privacy' ); ?></strong> <?php esc_html_e( 'If the Privacy Banner text is not filled out, the Privacy Banner will not show up.', 'uncode-privacy' ) ?>
			</p>

			<table class="form-table" data-id="general">
				<tbody>
					<tr>
						<th scope="row">
							<label for="uncode_privacy_cookie_banner_content"><?php esc_html_e( 'Privacy Banner Text', 'uncode-privacy' ) ?></label>
						</th>
						<td>
							<?php $privacy_bar_content = get_option( 'uncode_privacy_cookie_banner_content', '' ); ?>
							<textarea name="uncode_privacy_cookie_banner_content" id="uncode_privacy_cookie_banner_content" cols="80" rows="6"><?php echo esc_html( $privacy_bar_content ); ?></textarea>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="uncode_privacy_cookie_privacy_excerpt"><?php esc_html_e( 'Privacy Text', 'uncode-privacy' ) ?></label>
						</th>
						<td>
							<?php $privacy_excerpt = get_option( 'uncode_privacy_cookie_privacy_excerpt', '' ); ?>
							<textarea name="uncode_privacy_cookie_privacy_excerpt" id="uncode_privacy_cookie_privacy_excerpt" cols="80" rows="6"><?php echo esc_html( $privacy_excerpt ); ?></textarea>
							<p class="description"><?php esc_html_e( 'This will appear in the consent section of the privacy preference window.', 'uncode-privacy' ); ?></p>
						</td>
					</tr>
				</tbody>
			</table>

			<h2 class="title"><?php esc_html_e( 'Consent Notice', 'uncode-privacy' ); ?></h2>

			<p>
				<?php esc_html_e( 'This section handles the fallback text that will appear when a consent hasn\'t been approved yet.', 'uncode-privacy' ) ?><br>
				<strong><?php esc_html_e( 'Important:', 'uncode-privacy' ); ?></strong> <?php echo wp_kses( __( 'No HTML code is allowed. Use the shortcode <code>[uncode_privacy_box]Your Text[/uncode_privacy_box]</code> to print a link that opens the preferences box.', 'uncode-privacy' ), array( 'code' => array() ) ); ?>
			</p>
			<table class="form-table" data-id="general">
				<tbody>
					<tr>
						<th scope="row">
							<label for="uncode_privacy_fallback"><?php esc_html_e( 'Consent Notice Text', 'uncode-privacy' ) ?></label>
						</th>
						<td>
							<?php $privacy_bar_content = get_option( 'uncode_privacy_fallback', ( esc_html__('This content is blocked. Please review your [uncode_privacy_box]Privacy Settings[/uncode_privacy_box].', 'uncode-privacy' ) ) ); ?>
							<textarea name="uncode_privacy_fallback" id="uncode_privacy_fallback" cols="80" rows="6"><?php echo esc_html( $privacy_bar_content ); ?></textarea>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="uncode-privacy-tab hidden" data-id="consents">
			<h2><?php esc_html_e( 'Consents', 'uncode-privacy' ) ?></h2>

			<input type="text" id="type-of-consent" class="regular-text" placeholder="<?php esc_attr_e( 'Type of consent', 'uncode-privacy' ); ?>">

			<button class="button button-primary add-consent"><?php esc_html_e( 'Add consent', 'uncode-privacy' ); ?></button>

			<div id="consent-tabs">
				<?php
				$consent_types = uncode_toolkit_privacy_get_consent_types(); ?>

				<?php if ( ! empty( $consent_types ) ) : ?>
					<?php foreach ( $consent_types as $consent_key => $consent ) : ?>
						<div class="postbox" id="consent-type-content-<?php echo esc_attr( $consent_key ); ?>">
							<h2 class="hndle"><?php echo esc_html( $consent['name'] ); ?> <span>(id: <?php echo esc_html( $consent_key ); ?>)</span><button class="notice-dismiss" type="button"><span class="screen-reader-text"><?php esc_html_e( 'Unregister this consent.', 'uncode-privacy' ) ?></span></button></h2>
							<input type="hidden" name="<?php echo esc_attr( 'uncode_privacy_consent_types' ); ?>[<?php echo esc_attr( $consent_key ); ?>][name]" value="<?php echo esc_attr( $consent['name'] ); ?>" />
							<div class="inside">
								<table class="form-table">
									<tr>
										<th><label for="required-<?php echo esc_attr( $consent_key ); ?>"><?php esc_html_e( 'Required', 'uncode-privacy' ); ?></label></th>
										<td>
											<label class="uncode-privacy-switch">
												<input type="checkbox" class="uncode-privacy-switch-checkbox" name="<?php echo esc_attr( 'uncode_privacy_consent_types' ); ?>[<?php echo esc_attr( $consent_key ); ?>][required]" <?php checked( esc_attr( $consent['required'] ), 1 ); ?> id="required-<?php echo esc_attr( $consent_key ); ?>">
												<span class="uncode-privacy-slider round"></span>
											</label>
										</td>
									</tr>
									<tr>
										<th><label for="default-state-<?php echo esc_attr( $consent_key ); ?>"><?php esc_html_e( 'Active by default', 'uncode-privacy' ); ?></label></th>
										<td>
											<label class="uncode-privacy-switch">
												<?php $state_checked = isset( $consent['state'] ) && $consent['state'] ? true : false; ?>
												<input type="checkbox" name="<?php echo esc_attr( 'uncode_privacy_consent_types' ); ?>[<?php echo esc_attr( $consent_key ); ?>][state]" <?php checked( $state_checked, 1 ); ?> id="default-state-<?php echo esc_attr( $consent_key ); ?>">
												<span class="uncode-privacy-slider round"></span>
											</label>
										</td>
									</tr>
									<tr>
										<th><label for="consent-description-<?php echo esc_attr( $consent_key ); ?>"><?php esc_html_e( 'Consent description', 'uncode-privacy' ); ?></label></th>
										<td><textarea name="<?php echo esc_attr( 'uncode_privacy_consent_types' ); ?>[<?php echo esc_attr( $consent_key ); ?>][description]" id="consent-description-<?php echo esc_attr( $consent_key ); ?>" cols="53" rows="3" required><?php echo esc_html( $consent['description'] ); ?></textarea></td>
									</tr>
								</table>
							</div><!-- .inside -->
						</div><!-- .postbox -->
					<?php endforeach ?>
				<?php endif ?>
			</div>
		</div>

		<?php submit_button(); ?>
	</form>
</div>
