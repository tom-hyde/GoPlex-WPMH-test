<div class="gdpr gdpr-privacy-bar" style="display:none;">
	<div class="gdpr-wrapper">
		<div class="gdpr-content">
			<p>
				<?php echo nl2br( wp_kses_post( $content ) ); ?>
			</p>
		</div>
		<div class="gdpr-right">
			<button class="gdpr-preferences" type="button"><?php esc_html_e( 'Privacy Preferences', 'uncode-privacy' ); ?></button>
			<button class="gdpr-agreement btn-accent btn-flat" type="button"><?php echo esc_html( $button_text ); ?></button>
		</div>
	</div>
</div>
