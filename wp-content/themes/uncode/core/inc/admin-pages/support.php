<?php
/**
 * Show support page in Uncode menu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Uncode_Menu_Support_Page' ) ) :

/**
 * Uncode_Menu_Support_Page Class
 *
 * Creates the Support page.
 */
class Uncode_Menu_Support_Page {
	/**
	 * Constructor.
	 */
	public function __construct() {
		// Add support page to WP menu
		add_action( 'admin_menu', array( $this, 'add_to_menu' ), 100 );
	}

	/**
	 * Add support page
	 */
	public function add_to_menu() {
		add_submenu_page( 'uncode-system-status', esc_html__( 'Support', 'uncode' ),  esc_html__( 'Support', 'uncode' ) , 'edit_theme_options', 'uncode-support', array( $this, 'output' ) );
	}

	/**
	 * Get cards
	 */
	public function get_cards() {
		$cards = array(
			array(
				'id'          => 'general',
				'title'       => esc_html__( 'General', 'uncode' ),
				'description' => esc_html__( 'General information about WordPress, Server Requirements and Uncode Theme versions.', 'uncode' ),
				'url'		  => '//support.undsgn.com/hc/en-us/articles/213452809',
			),
			array(
				'id'          => 'first-steps',
				'title'       => esc_html__( 'First Steps', 'uncode' ),
				'description' => esc_html__( 'Uncode Theme and plugins installation guide, Demo Contents import and theme update.', 'uncode' ),
				'url'		  => '//support.undsgn.com/hc/en-us/articles/213454309',
			),
			array(
				'id'          => 'theme-options',
				'title'       => esc_html__( 'Theme Options', 'uncode' ),
				'description' => esc_html__( 'Options are the backbone of Uncode and they give you full control over your website.', 'uncode' ),
				'url'		  => '//support.undsgn.com/hc/en-us/articles/213455189',
			),
			array(
				'id'          => 'pages-posts',
				'title'       => esc_html__( 'Page & Posts', 'uncode' ),
				'description' => esc_html__( 'Discover how to create, customise and manage pages, blog posts and portfolio items.', 'uncode' ),
				'url'		  => '//support.undsgn.com/hc/en-us/articles/214002625',
			),
			array(
				'id'          => 'page-builder',
				'title'       => esc_html__( 'Page Builder', 'uncode' ),
				'description' => esc_html__( 'Learn how to create beautiful pages quickly with the powerful Uncode’s Page Builder.', 'uncode' ),
				'url'		  => '//support.undsgn.com/hc/en-us/articles/213456589',
			),
			array(
				'id'          => 'header',
				'title'       => esc_html__( 'Headers', 'uncode' ),
				'description' => esc_html__( 'Uncode works with 4 different header types fully integrated with the Theme Options.', 'uncode' ),
				'url'		  => '//support.undsgn.com/hc/en-us/articles/213456989',
			),
			array(
				'id'          => 'extra',
				'title'       => esc_html__( 'Extra', 'uncode' ),
				'description' => esc_html__( 'Advanced features and additional plugins to boost your Uncode Theme functionalities.', 'uncode' ),
				'url'		  => '//support.undsgn.com/hc/en-us/articles/213457109',
			),
			array(
				'id'          => 'how-to',
				'title'       => esc_html__( 'How To', 'uncode' ),
				'description' => esc_html__( 'Discover how to work with some of the most common elements and features of Uncode.', 'uncode' ),
				'url'		  => '//support.undsgn.com/hc/en-us/articles/213459529',
			),
			array(
				'id'          => 'faq',
				'title'       => esc_html__( 'FAQ', 'uncode' ),
				'description' => esc_html__( 'Search the frequent asked questions from the customer community before open a ticket.', 'uncode' ),
				'url'		  => '//support.undsgn.com/hc/en-us/categories/201692765',
			),
		);

		return $cards;
	}

	/**
	 * Handles the display of the Support page in admin.
	 */
	public function output() {
		?>

		<div class="wrap uncode-wrap" id="uncode-support">

			<?php echo uncode_admin_panel_page_title( 'support' ); ?>

			<div class="uncode-admin-panel">
				<?php echo uncode_admin_panel_menu( 'support' ); ?>

				<div class="uncode-admin-panel__content uncode-admin-panel__content--two-cols">
					<div class="uncode-admin-panel__left-w">
						<h2 class="uncode-admin-panel__heading"><?php esc_html_e( 'Knowledge Base', 'uncode' ); ?></h2>

						<div class="box-cards">
							<ul class="box-cards__list uncode-ui-layout uncode-ui-layout--three-cols">

								<?php
									$card_i = 0;
									foreach ( $this->get_cards() as $card ) : ?>

									<li class="box-card box-card--<?php echo esc_attr( $card[ 'id' ] ); ?> uncode-ui-layout__item uncode-ui-layout__item--three-cols<?php echo esc_attr( ( $card_i % 3 == 0 ) ? ' clear-left' : '' ); ?>">
										<a target="_blank" tabindex="-1" href="<?php echo esc_url( $card[ 'url' ] ); ?>" class="box-card__link box-card__link--<?php echo esc_attr( $card[ 'id' ] ); ?>">
											<h3 class="box-card__title"><?php echo esc_html( $card[ 'title' ] ); ?></h3>
											<?php /*<p class="box-card__description"><?php echo esc_html( $card[ 'description' ] ); ?></p>

											<span class="box-card__button"><?php esc_html_e( 'See all guides', 'uncode' ); ?></span>*/?>
										</a>
									</li>

								<?php
									$card_i++;
									endforeach;
								?>
							</ul>
						</div>

					</div><!-- .uncode-admin-panel__left-w -->

					<div class="uncode-admin-panel__right-w">

						<h2 class="uncode-admin-panel__heading"><?php esc_html_e( 'Help Center', 'uncode' ); ?></h2>

						<div class="uncode-info-box">

							<p class="uncode-admin-panel__description"><?php printf( esc_html__( 'According to Envato’s terms, Uncode comes with 6 months of support for every purchase, and free lifetime theme updates. This support can be %s via ThemeForest.', 'uncode' ), '<a href="//themeforest.net/item/uncode-creative-multiuse-wordpress-theme/13373220?utm_source=undsgn_support&ref=undsgn&license=regular&open_purchase_for_item_id=13373220&purchasable=source" target="_blank" tabindex="-1">' . esc_html__('extended through subscriptions', 'uncode') . '</a>'); ?></p>

							<p class="uncode-admin-panel__description"><?php esc_html_e( 'Support is limited to questions regarding the Uncode\'s features, or problems with the theme. To open a support ticket, please navigate to our Help Center homepage and click the \'Submit a request\' in the top right corner. One of our Support Team members will respond to you shortly.', 'uncode' ); ?></p>

							<p class="uncode-admin-panel__description"><?php esc_html_e( 'Item Support DOES include:', 'uncode' ); ?></p>
							<ul class="checklist">
								<li><?php esc_html_e( 'Availability of the theme\'s authors to answer questions;', 'uncode' ); ?></li>
								<li><?php esc_html_e( 'Answers to technical queries about the theme\'s features;', 'uncode' ); ?></li>
								<li><?php esc_html_e( 'Assistance with reported bugs and issues.', 'uncode' ); ?></li>
							</ul>

							<p class="uncode-admin-panel__description"><?php esc_html_e( 'Item Support DOES NOT include:', 'uncode' ); ?></p>
							<ul class="errorlist">
								<li><?php esc_html_e( 'Customization services;', 'uncode' ); ?></li>
								<li><?php esc_html_e( 'Installation services;', 'uncode' ); ?></li>
								<li><?php esc_html_e( 'Help and support for non-bundled third-party plugins that you install.', 'uncode' ); ?></li>
							</ul>

							<a target="_blank" tabindex="-1" class="button button-primary button--view-documentation" href="//support.undsgn.com/hc/en-us"><?php esc_html_e( 'Submit a request', 'uncode' ); ?></a>

						</div><!-- .uncode-info-box -->

					</div><!-- .uncode-admin-panel__right-w -->
				</div><!-- .uncode-admin-panel__content -->

			</div><!-- .uncode-admin-panel -->

		</div><!-- .uncode-wrap -->

		<?php
	}
}

endif;

return new Uncode_Menu_Support_Page();
