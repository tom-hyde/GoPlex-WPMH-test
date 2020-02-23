<?php
/**
 * Grouped product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $post;

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
	<table cellspacing="0" class="group_table">
		<tbody>
			<?php
				$quantites_required = false;
				$previous_post      = $post;

				foreach ( $grouped_products as $grouped_product ) :
					$product_id = $grouped_product->get_id();

					if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) && ! $product->is_in_stock() ) {
						continue;
					}

					$post_object        = get_post( $grouped_product->get_id() );
					$quantites_required = $quantites_required || ( $grouped_product->is_purchasable() && ! $grouped_product->has_options() );

					$post = $post_object;
					setup_postdata( $post );
					?>
					<tr>
						<?php do_action( 'woocommerce_grouped_product_list_before_item_total', $grouped_product ); ?>

						<td class="item_total">
							<?php
							if ( ! $grouped_product->is_purchasable() || $grouped_product->has_options() || ! $grouped_product->is_in_stock() ) {
								woocommerce_template_loop_add_to_cart();
							} elseif ( $grouped_product->is_sold_individually() ) {
								echo '<input type="checkbox" name="' . esc_attr( 'quantity[' . $grouped_product->get_id() . ']' ) . '" value="1" class="wc-grouped-product-add-to-cart-checkbox" />';
							} else {
								do_action( 'woocommerce_before_add_to_cart_quantity' );
								woocommerce_quantity_input( array(
									'input_name'  => 'quantity[' . $grouped_product->get_id() . ']',
									'input_value' => isset( $_POST['quantity'][ $grouped_product->get_id() ] ) ? wc_stock_amount( wc_clean( wp_unslash( $_POST['quantity'][ $grouped_product->get_id() ] ) ) ) : 0, // WPCS: CSRF ok, input var okay, sanitization ok.
									'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 0, $grouped_product ),
									'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $grouped_product->get_max_purchase_quantity(), $grouped_product ),
								) );
								do_action( 'woocommerce_after_add_to_cart_quantity' );
							}
							?>
						</td>

						<?php do_action( 'woocommerce_grouped_product_list_after_item_total', $grouped_product ); ?>

						<?php do_action( 'woocommerce_grouped_product_list_before_label', $grouped_product ); ?>

						<td class="label">
							<label for="product-<?php echo esc_attr($product_id); ?>">
								<?php
								if ( $grouped_product->is_visible() ) {
									echo '<a href="' . esc_url( apply_filters( 'woocommerce_grouped_product_list_link', get_permalink( $grouped_product->get_id() ), $grouped_product->get_id() ) ) . '">' . $grouped_product->get_name() . '</a>';
								} else {
									echo esc_html( $grouped_product->get_name() );
								}
								?>
							</label>
						</td>

						<?php do_action( 'woocommerce_grouped_product_list_after_label', $grouped_product ); ?>

						<?php do_action ( 'woocommerce_grouped_product_list_before_price', $grouped_product ); ?>

						<td class="price">
							<?php
								echo wp_kses_post( $grouped_product->get_price_html() );
								echo wc_get_stock_html( $grouped_product );
							?>
						</td>

						<?php do_action( 'woocommerce_grouped_product_list_after_price', $grouped_product ); ?>
					</tr>
					<?php
				endforeach;

				// Reset to parent grouped product
				$post = $previous_post;
				setup_postdata( $post );
			?>
		</tbody>
	</table>

	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" />

	<?php if ( $quantites_required ) : ?>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<button type="submit" class="single_add_to_cart_button btn btn-default alt <?php echo uncode_btn_style(); ?>"><?php echo wp_kses_post($product->single_add_to_cart_text()); ?></button>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<?php endif; ?>
</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
