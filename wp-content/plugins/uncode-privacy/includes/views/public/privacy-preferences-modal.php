<div class="gdpr gdpr-privacy-preferences">
	<div class="gdpr-wrapper">
		<form method="post" class="gdpr-privacy-preferences-frm" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
			<input type="hidden" name="action" value="uncode_privacy_update_privacy_preferences">
			<?php wp_nonce_field( 'uncode-privacy-update_privacy_preferences', 'update-privacy-preferences-nonce' ); ?>
			<header>
				<div class="gdpr-box-title">
					<h3><?php esc_html_e( 'Privacy Preference Center', 'uncode-privacy' ); ?></h3>
					<span class="gdpr-close"></span>
				</div>
			</header>
			<div class="gdpr-content">
				<div class="gdpr-tab-content">
					<div class="gdpr-consent-management gdpr-active">
						<header>
							<h4><?php esc_html_e( 'Privacy Preferences', 'uncode-privacy' ); ?></h4>
						</header>
						<div class="gdpr-info">
							<p><?php echo nl2br( esc_html( $cookie_privacy_excerpt ) ); ?></p>
							<?php if ( ! empty( $consent_types ) ) : ?>
								<?php foreach ( $consent_types as $consent_key => $type ) : ?>
									<div class="gdpr-cookies-used">
										<div class="gdpr-cookie-title">
											<p><?php echo esc_html( $type['name'] ); ?></p>
											<?php if ( $type['required'] ) : ?>
												<span class="gdpr-always-active"><?php esc_html_e( 'Required', 'uncode-privacy' ); ?></span>
												<input type="hidden" name="user_consents[]" value="<?php echo esc_attr( $consent_key ); ?>" checked style="display:none;">
											<?php else : ?>
												<?php if ( isset( $type[ 'state' ] ) && $type[ 'state' ] ) : ?>

													<?php $has_consent_off = in_array( $consent_key . '-off', $user_consents ) ? true : false;
													?>
													<input type="hidden" name="consents_default_on_list[]" value="<?php echo esc_attr( $consent_key ); ?>">

													<label class="gdpr-switch">
														<input id="gdpr-consent-<?php echo esc_attr( $consent_key ); ?>" class="gdpr-consent-switch" type="checkbox" name="user_consents[]" value="<?php echo esc_attr( $consent_key ); ?>" <?php echo checked( ! $has_consent_off , 1, false ); ?> data-default-on="true">
														<span class="gdpr-slider round"></span>
													</label>

												<?php else: ?>

													<label class="gdpr-switch">
														<input id="gdpr-consent-<?php echo esc_attr( $consent_key ); ?>" class="gdpr-consent-switch" type="checkbox" name="user_consents[]" value="<?php echo esc_attr( $consent_key ); ?>" <?php echo ! empty( $user_consents ) ? checked( in_array( $consent_key, $user_consents, true ), 1, false ) : ''; ?> data-default-on="false">
														<span class="gdpr-slider round"></span>
													</label>

												<?php endif; ?>
											<?php endif; ?>
										</div>
										<div class="gdpr-cookies">
											<span><?php echo wp_kses( $type['description'], $this->allowed_html ); ?></span>
										</div>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<footer>
				<input type="submit" class="btn-accent btn-flat" value="<?php esc_attr_e( 'Save Preferences', 'uncode-privacy' ); ?>">
				<?php if ( $privacy_policy_page ) : ?>
					<span><a href="<?php echo esc_url( apply_filters( 'uncode_privacy_policy_page_link', get_permalink( $privacy_policy_page ) ) ); ?>" target="_blank"><?php esc_html_e( 'Privacy Policy', 'uncode-privacy' ); ?></a></span>
				<?php endif ?>
			</footer>
		</form>
	</div>
</div>
