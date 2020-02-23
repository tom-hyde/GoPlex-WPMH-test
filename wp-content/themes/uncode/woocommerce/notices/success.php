<?php
/**
 * Show messages
 *
 * @package 	WooCommerce/Templates
 * @version     3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $messages ) {
	return;
}

$class = is_product() ? ' limit-width double-top-padding no-bottom-padding' : ' no-h-padding no-top-padding double-bottom-padding';

?>

<?php foreach ( $messages as $message ) :
	$message = str_replace('button wc-forward', 'button wc-forward btn-link', $message);
	?>
	<div class="row-container row-message">
		<div class="row-parent<?php echo esc_attr($class); ?>">
			<div class="woocommerce-message woocomerce-success" role="alert"><?php echo wp_kses_post( $message ); ?></div>
		</div>
	</div>
<?php endforeach; ?>
