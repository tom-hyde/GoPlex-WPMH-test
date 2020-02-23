<?php
/**
 * Public related functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Uncode_Toolkit_Privacy_Public' ) ) :

/**
 * Uncode_Toolkit_Privacy_Public Class
 */
class Uncode_Toolkit_Privacy_Public {

	/**
	 * The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 */
	private $version;

	/**
	 * Allowed HTML for wp_kses.
	 */
	private $allowed_html;

	/**
	 * Get things going
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name  = $plugin_name;
		$this->version      = $version;
		$this->allowed_html = array(
			'a' => array(
				'href'   => true,
				'title'  => true,
				'target' => true,
			),
		);
	}

	/**
	 * Register the stylesheets for the frontend.
	 */
	public function enqueue_styles() {
		add_thickbox();
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/uncode-privacy-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the frontend.
	 */
	public function enqueue_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'js-cookie', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/js-cookie' . $suffix . '.js', array(), '2.2.0', true );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/uncode-privacy-public' . $suffix . '.js', array( 'jquery', 'js-cookie' ), $this->version, true );

		$accent_color = false;

		if ( function_exists( 'ot_options_id' ) ) {
			if ( is_multisite() ) {
				$uncode_option = get_blog_option( get_current_blog_id(), ot_options_id() );
			} else {
				$uncode_option = get_option( ot_options_id() );
			}

			global $front_background_colors;

			if ( $front_background_colors && isset( $uncode_option[ '_uncode_accent_color' ] ) && isset ( $front_background_colors[ $uncode_option[ '_uncode_accent_color' ] ] ) ) {
				$accent_color = $front_background_colors[ $uncode_option[ '_uncode_accent_color' ] ];
			}
		}

		$accent_color = $accent_color ? $accent_color : '#006cff';

		$privacy_parameters = array(
			'accent_color' => $accent_color
		);

		wp_localize_script( $this->plugin_name, 'Uncode_Privacy_Parameters', $privacy_parameters );
	}

	/**
	 * Set plugin cookies
	 */
	public function set_plugin_cookies() {
		$user_id = get_current_user_id();

		if ( ! isset( $_COOKIE['uncode_privacy']['consent_types'] ) ) {
			if ( ! $user_id ) {
				setcookie( 'uncode_privacy[consent_types]', '[]', time() + YEAR_IN_SECONDS, "/" );
			} else {
				$user_consents = get_user_meta( $user_id, 'uncode_privacy_consents' );

				setcookie( "uncode_privacy[consent_types]", json_encode( $user_consents ), time() + YEAR_IN_SECONDS, "/" );
			}
		} else {
			if ( $user_id ) {
				$user_consents   = (array) get_user_meta( $user_id, 'uncode_privacy_consents' );
				$cookie_consents = (array) json_decode( wp_unslash( $_COOKIE['uncode_privacy']['consent_types'] ) );
				$intersect       = array_intersect( $user_consents, $cookie_consents );
				$diff            = array_merge( array_diff( $user_consents, $intersect ), array_diff( $cookie_consents, $intersect ) );

				if ( ! empty( $diff ) ) {
					setcookie( "uncode_privacy[consent_types]", json_encode( $user_consents ), time() + YEAR_IN_SECONDS, "/" );
				}
			}
		}
	}

	/**
	 * Append overlay
	 */
	public function overlay() {
		echo '<div class="gdpr-overlay"></div>';
	}

	/**
	 * Print privacy
	 */
	public function privacy_bar() {
		$content     = get_option( 'uncode_privacy_cookie_banner_content', '' );
		$button_text = apply_filters( 'uncode_privacy_privacy_bar_button_text', esc_html__( 'I Agree', 'uncode-privacy' ) );

		if ( empty( $content ) ) {
			return;
		}

		include plugin_dir_path( __FILE__ ) . 'views/public/privacy-bar.php';
	}

	/**
	 * Output privacy preferences modal.
	 */
	public function privacy_preferences_modal() {
		$cookie_privacy_excerpt = get_option( 'uncode_privacy_cookie_privacy_excerpt', '' );
		$consent_types          = uncode_toolkit_privacy_get_consent_types();
		$privacy_policy_page    = get_option( 'uncode_privacy_privacy_policy_page', 0 );
		$user_consents          = isset( $_COOKIE['uncode_privacy']['consent_types'] ) ? json_decode( wp_unslash( $_COOKIE['uncode_privacy']['consent_types'] ) ) : array();

		include plugin_dir_path( __FILE__ ) . 'views/public/privacy-preferences-modal.php';
	}

	/**
	 * Update the user allowed types of consent.
	 * If the user is logged in, we also save consent to user meta.
	 */
	public function update_privacy_preferences() {
		if ( ! isset( $_POST[ 'update-privacy-preferences-nonce' ] ) || ! wp_verify_nonce( sanitize_key( $_POST[ 'update-privacy-preferences-nonce' ] ), 'uncode-privacy-update_privacy_preferences' ) ) {
			wp_die( esc_html__( 'We could not verify the the security token. Please try again.', 'uncode-privacy' ) );
		}

		$consents_default_on_list = array_map( 'sanitize_text_field', (array) $_POST[ 'consents_default_on_list' ] );
		$consents                 = array_map( 'sanitize_text_field', (array) $_POST[ 'user_consents' ] );
		$consents_to_save         = array();

		// First save all consents that are on by default to off (if unchecked)
		foreach ( $consents_default_on_list as $consents_on ) {
			if ( ! in_array( $consents_on, $consents ) ) {
				$consents_to_save[] = $consents_on . '-off';
			}
		}

		// Then save the other consents
		foreach ( $consents as $consent_id ) {
			if ( in_array( $consent_id, $consents_default_on_list ) ) {
				$consents_to_save[] = $consent_id . '-on';
			} else {
				$consents_to_save[] = $consent_id;
			}
		}

		$consents_as_json = json_encode( $consents_to_save );

		setcookie( "uncode_privacy[consent_types]", $consents_as_json, time() + YEAR_IN_SECONDS, "/" );

		if ( is_user_logged_in() ) {
			$user = wp_get_current_user();

			if ( ! empty( $consents_to_save ) ) {
				delete_user_meta( $user->ID, 'uncode_privacy_consents' );

				foreach ( $consents_to_save as $consent ) {
					$consent = sanitize_text_field( wp_unslash( $consent ) );
					add_user_meta( $user->ID, 'uncode_privacy_consents', $consent );
				}
			}
		}

		wp_safe_redirect( esc_url_raw( wp_get_referer() ) );
		exit;
	}
}

endif;
