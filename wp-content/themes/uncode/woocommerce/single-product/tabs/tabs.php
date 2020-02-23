<?php
/**
 * Single Product tabs
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version   2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) :

	$index = 0;
	global $limit_content_width, $page_custom_width;

	$with_builder = false;

	$the_content = get_the_content();
	if (has_shortcode($the_content, 'vc_row')) {
		$with_builder = true;
	}
	?>

	<div class="tab-container wootabs">
		<ul class="nav nav-tabs<?php echo esc_attr($limit_content_width); ?> single-h-padding" <?php echo wp_kses_post( $page_custom_width ); ?>>
			<?php foreach ( $tabs as $key => $tab ) : ?>

				<li class="<?php echo esc_attr( $key ); ?>_tab<?php if ($index === 0) { echo ' active'; } ?>" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
					<a href="#tab-<?php echo esc_attr($key); ?>-<?php echo esc_attr(get_the_id()); ?>" data-toggle="tab"><span><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html($tab['title']), $key ) ?></span></a>
				</li>

			<?php $index++;
			endforeach; ?>
		</ul>
		<div class="tab-content">
		<?php
			$index = 0;
			foreach ( $tabs as $key => $tab ) :

				ob_start();
				call_user_func( $tab['callback'], $key, $tab );
				$tab_content = ob_get_clean();

				if (substr_count($tab_content, 'row-container')) { ?>
				<div class="tab-vcomposer tab-pane fade<?php if ($index === 0) { echo ' active in'; } ?>" id="tab-<?php echo esc_attr( $key ) ?>-<?php echo esc_attr(get_the_id()); ?>">
					<?php add_filter('woocommerce_product_description_heading','uncode_no_product_description_heading'); call_user_func( $tab['callback'], $key, $tab ); ?>
				</div>
				<?php } else { ?>
				<div class="tab-pane fade<?php echo esc_attr( $limit_content_width ); ?> single-h-padding<?php if ($index === 0) { echo ' active in'; } ?>" id="tab-<?php echo esc_attr( $key ) ?>-<?php echo esc_attr(get_the_id()); ?>" <?php echo wp_kses_post( $page_custom_width ); ?>>
					<?php echo uncode_remove_p_tag( $tab_content ); ?>
				</div>
			<?php } ?>

		<?php $index++;
		endforeach; ?>
		</div>
	</div>

<?php endif; ?>
