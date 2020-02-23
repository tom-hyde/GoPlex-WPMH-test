<?php
/**
 * Order Customer Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
?>
<header><h3><?php esc_html_e( 'Customer details', 'woocommerce' ); ?></h3></header>

<table class="shop_table customer_details">
	<?php if ( $order->get_customer_note() ) : ?>
		<tr>
			<th><?php esc_html_e( 'Note:', 'woocommerce' ); ?></th>
			<td><?php echo wptexturize( $order->get_customer_note() ); ?></td>
		</tr>
	<?php endif; ?>

	<?php if ( $order->get_billing_email() ) : ?>
		<tr>
			<th><?php esc_html_e( 'Email:', 'woocommerce' ); ?></th>
			<td><?php echo esc_html( $order->get_billing_email() ); ?></td>
		</tr>
	<?php endif; ?>

	<?php if ( $order->get_billing_phone() ) : ?>
		<tr>
			<th><?php esc_html_e( 'Telephone:', 'woocommerce' ); ?></th>
			<td><?php echo esc_html( $order->get_billing_phone() ); ?></td>
		</tr>
	<?php endif; ?>

	<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>
</table>

<?php if ( $show_shipping ) : ?>

<div class="col2-set addresses">
	<div class="col-1">

<?php endif; ?>

<header class="title">
	<h3><?php esc_html_e( 'Billing address', 'woocommerce' ); ?></h3>
</header>
<address>
	<?php echo wp_kses_post( $order->get_formatted_billing_address( __( 'N/A', 'woocommerce' ) ) ); ?>
	<?php if ( $order->get_billing_phone() ) : ?>
		<p class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_billing_phone() ); ?></p>
	<?php endif; ?>
	<?php if ( $order->get_billing_email() ) : ?>
		<p class="woocommerce-customer-details--email"><?php echo esc_html( $order->get_billing_email() ); ?></p>
	<?php endif; ?>
</address>

<?php if ( $show_shipping ) : ?>

	</div><!-- /.col-1 -->
	<div class="col-2">
		<header class="title">
			<h3><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></h3>
		</header>
		<address>
			<?php echo wp_kses_post( $order->get_formatted_shipping_address( __( 'N/A', 'woocommerce' ) ) ); ?>
		</address>
	</div><!-- /.col-2 -->
</div><!-- /.col2-set -->

<?php endif; ?>

<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>
