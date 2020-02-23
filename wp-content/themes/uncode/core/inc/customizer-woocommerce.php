<?php

add_action( 'after_setup_theme', 'uncode_woocommerce_support' );
if ( ! function_exists( 'uncode_woocommerce_support' ) ) :
/**
 * @since Uncode 1.6.0
 */
function uncode_woocommerce_support() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-slider' );
}
endif;//uncode_woocommerce_support

// Change number or products per row to 3
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3; // 3 products per row
	}
}

//Remove prettyPhoto lightbox
add_action( 'wp_enqueue_scripts', 'uncode_remove_woo_scripts', 99 );
function uncode_remove_woo_scripts() {
    remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
    wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
    wp_deregister_style( 'select2');
    wp_dequeue_script( 'prettyPhoto' );
    wp_dequeue_script( 'prettyPhoto-init' );
    wp_dequeue_script( 'wc-chosen');
}

function uncode_no_product_description_heading() {
    return '';
}

function uncode_price_html( $price, $product ){
	$price = str_replace( '<span class="amount">', '<span>', $price );
	$price = '<ins class="h2">'.$price.'</ins>';
    return $price;
}
add_filter( 'woocommerce_get_price_html', 'uncode_price_html', 10, 2 );

function uncode_price_html_from_to($price, $from, $to, $instance) {
	$price = '<ins>' . ( ( is_numeric( $to ) ) ? wc_price( $to ) : $to ) . '</ins> <del>' . ( ( is_numeric( $from ) ) ? wc_price( $from ) : $from ) . '</del>';
	return $price;
}

add_filter( 'woocommerce_get_price_html_from_to', 'uncode_price_html_from_to', 10, 4 );

function uncode_woocommerce_order_button_html( $button ) {
	$button = str_replace('class="button', 'class="btn btn-default', $button);
	return $button;
}

add_filter( 'woocommerce_order_button_html', 'uncode_woocommerce_order_button_html', 10, 1 );

function uncode_woocommerce_thankyou_order_received_text( $text ) {
	return '<span class="thank-you">' . $text . '</span>';
}

add_filter( 'woocommerce_thankyou_order_received_text', 'uncode_woocommerce_thankyou_order_received_text', 10, 1 );


// Or just remove them all in one line
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

function uncode_add_uncode_cart() {

	$production_mode = ot_get_option('_uncode_production');
	$resources_version = ($production_mode === 'on') ? null : rand();

	wp_enqueue_style( 'uncode-woocommerce', get_template_directory_uri() . '/library/css/woocommerce.css', array() , $resources_version, 'all');
	if ($production_mode === 'on') {
		wp_register_script( 'uncode-menucart', get_template_directory_uri() . '/library/js/min/woocommerce-uncode.min.js', array() , $resources_version, 'all');
	} else {
		wp_register_script( 'uncode-menucart', get_template_directory_uri() . '/library/js/woocommerce-uncode.js', array() , $resources_version, 'all');
	}

	wp_enqueue_script( 'uncode-menucart' );

}

add_action('wp_enqueue_scripts', 'uncode_add_uncode_cart');

function uncode_get_cart_items() {
	global $woocommerce;

	$articles = sizeof( $woocommerce->cart->get_cart() );


	$cart = $tot_articles = '';

	if (  $articles > 0 ) {
		$tot_articles = $woocommerce->cart->cart_contents_count;
		foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

				$product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
				$thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
				$product_price = apply_filters( 'woocommerce_cart_item_price', $woocommerce->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

				$cart .= '<li class="cart-item-list clearfix">';
				if ( ! $_product->is_visible() ) {
					$cart .= str_replace( array( 'http:', 'https:' ), '', $thumbnail );
				} else {
					$cart .= apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
						'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-item_key="%s" data-product_sku="%s">&times;</a>',
						esc_url( uncode_wc_get_cart_remove_url( $cart_item_key ) ),
						__( 'Remove this item', 'woocommerce' ),
						esc_attr( $product_id ),
						esc_attr( $cart_item_key ),
						esc_attr( $_product->get_sku() )
					), $cart_item_key );
					$cart .= '<a class="cart-thumb" href="'.esc_url(get_permalink( $product_id )).'">
								'.str_replace( array( 'http:', 'https:' ), '', $thumbnail ) . '
							</a>';
				}

				$cart .= '<div class="cart-desc"><span class="cart-item">' . $product_name . '</span>';

				$cart .= '<span class="product-quantity">'. apply_filters( 'woocommerce_widget_cart_item_quantity',  '<span class="quantity-container">' . sprintf( '%s &times; %s',$cart_item['quantity'] , '</span>' . $product_price ) , $cart_item, $cart_item_key ) . '</span>';
				$cart .= '</div>';
				$cart .= '</li>';
			}
		}

		$cart .= '<li class="subtotal"><span><strong>' . esc_html__('Subtotal:', 'woocommerce') . '</strong> ' . $woocommerce->cart->get_cart_total() . '</span></li>';

		$cart .= '<li class="buttons clearfix">
								<a href="'.wc_get_cart_url().'" class="wc-forward btn btn-link"><i class="fa fa-bag"></i>'.esc_html__( 'View cart', 'woocommerce' ).'</a>
								<a href="'.wc_get_checkout_url().'" class="checkout wc-forward btn btn-link"><i class="fa fa-square-check"></i>'.esc_html__( 'Checkout', 'woocommerce' ).'</a>
							</li>';

	} else {
		$cart .= '<li><span>' . esc_html__('Your cart is currently empty.','woocommerce') . '</span></li>';
	}

	return array('cart' => $cart, 'articles' => $tot_articles);
}

function uncode_woomenucart_ajax() {

	$cart = uncode_get_cart_items();

	echo json_encode($cart);

	die();
}

add_action( 'wp_ajax_woomenucart_ajax', 'uncode_woomenucart_ajax');
add_action( 'wp_ajax_nopriv_woomenucart_ajax', 'uncode_woomenucart_ajax' );

add_action( 'wp_ajax_woomenucart_remove_ajax', 'uncode_woomenucart_remove_ajax');
add_action( 'wp_ajax_nopriv_woomenucart_remove_ajax', 'uncode_woomenucart_remove_ajax' );
if ( ! function_exists( 'uncode_woomenucart_remove_ajax' ) ) :
/**
 * @since Uncode 1.6.0
 */
function uncode_woomenucart_remove_ajax($return) {
	$cart = WC()->cart;
	$item_key = $_POST['item_key'] ? $_POST['item_key'] : 0;

	if($item_key){
		$cart->remove_cart_item( $item_key );
	}

	die();
}
endif;//uncode_woomenucart_remove_ajax

function uncode_add_cart_in_menu($woo_icon, $woo_cart_class) {
	global $woocommerce, $menutype;

	$horizontal_menu = (strpos($menutype ,'hmenu') !== false) ? true : false;
	$tot_articles = $woocommerce->cart->cart_contents_count;
	$get_cart_items = uncode_get_cart_items();

	$vertical = (strpos($menutype, 'vmenu') !== false || $menutype === 'menu-overlay' || $menutype === 'menu-overlay-center') ? true : false;

	$cart_container = '<ul role="menu" class="drop-menu sm-nowrap cart_list product_list_widget uncode-cart-dropdown">'.((isset($get_cart_items['cart']) && $get_cart_items['cart'] !=='') ? $get_cart_items['cart'] : '<li><span>' . esc_html__('Your cart is currently empty.','woocommerce') . '</span></li>').'</ul>';
	$items =						'<li class="'.$woo_cart_class.'uncode-cart menu-item-link menu-item menu-item-has-children dropdown">
												<a href="#" data-toggle="dropdown" class="dropdown-toggle" data-type="title" title="cart">
													<span class="cart-icon-container">';
	$items .= $horizontal_menu ?
									'<i class="'.$woo_icon.'"></i><span class="desktop-hidden">'.esc_html__('Cart','uncode').'</span>'
									:
									'<i class="'.$woo_icon.'"></i><span>'. esc_html__('Cart','uncode') . '</span>';
	$items .= 			(( $tot_articles !== 0 ) ? '<span class="badge">'.$tot_articles.'</span>' : '<span class="badge" style="display: none;"></span>').'<i class="fa fa-angle-down fa-dropdown'.(!$vertical ? ' desktop-hidden' : '').'"></i>
								</span>
							</a>
							'.$cart_container.
							// (( $tot_articles == 0 ) ? '<script type="text/javascript">cart_hidden();</script>' : '').
							'</li>';

    return $items;
}

/**
* uncode_is_woocommerce_page - Returns true if on a page which uses WooCommerce templates (cart and checkout are standard pages with shortcodes and which are also included)
*
* @access public
* @return bool
*/
function uncode_is_woocommerce_page () {
        if(  function_exists ( "is_woocommerce" ) && is_woocommerce()){
                return true;
        }
        $woocommerce_keys   =   array ( "woocommerce_shop_page_id" ,
                                        "woocommerce_terms_page_id" ,
                                        "woocommerce_cart_page_id" ,
                                        "woocommerce_checkout_page_id" ,
                                        "woocommerce_pay_page_id" ,
                                        "woocommerce_thanks_page_id" ,
                                        "woocommerce_myaccount_page_id" ,
                                        "woocommerce_edit_address_page_id" ,
                                        "woocommerce_view_order_page_id" ,
                                        "woocommerce_change_password_page_id" ,
                                        "woocommerce_logout_page_id" ,
                                        "woocommerce_lost_password_page_id" ) ;
        foreach ( $woocommerce_keys as $wc_page_id ) {
                if ( get_the_ID () == get_option ( $wc_page_id , 0 ) ) {
                        return true ;
                }
        }
        return false;
}

function uncode_alter_woocommerce_comment_form_fields($fields){
    $fields['class_submit'] = 'submit btn btn-default';
    return $fields;
}

add_filter('woocommerce_product_review_comment_form_args','uncode_alter_woocommerce_comment_form_fields');

function woocommerce_button_proceed_to_checkout() {
	$checkout_url = wc_get_checkout_url();

	?>
	<a href="<?php echo esc_url($checkout_url); ?>" class="checkout-button btn btn-default alt wc-forward <?php echo uncode_btn_style(); ?>"><?php esc_html_e( 'Proceed to checkout', 'woocommerce' ); ?></a>
	<?php
}

function uncode_output_related_products_args( $args ) {
	$args['columns'] = 4;
	$args['posts_per_page'] = 12;
	return $args;
}

add_filter( 'woocommerce_output_related_products_args', 'uncode_output_related_products_args');


function uncode_wc_loop_add_to_cart_scripts() {
    if ( is_shop() || is_product_category() || is_product_tag() || is_product() ) : ?>

		<script>
			window.addEventListener("load", function(){
				jQuery( document ).on( 'change', '.quantity .qty', function() {
					jQuery( this ).parent().parent().find('.add_to_cart_button').attr( 'data-quantity', jQuery( this ).val() );
				});
			}, false);
		</script>

    <?php endif;
}

add_action( 'wp_footer', 'uncode_wc_loop_add_to_cart_scripts' );

add_filter( 'woocommerce_available_variation', 'uncode_woocommerce_available_variation' );

function uncode_woocommerce_available_variation( $variations ) {
	global $wpdb;

	$shop_single = wc_get_image_size( 'shop_single' );
	$col_size = ot_get_option('_uncode_product_media_size') == '' ? 6 : ot_get_option('_uncode_product_media_size');
	$crop = false;
	if (isset($shop_single['crop']) && $shop_single['crop'] === 1) {
		$crop = true;
		$thumb_ratio = $shop_single['width'] / $shop_single['height'];
	}
	$get_media_url = (isset($variations['image_link'])) ? $variations['image_link'] : $variations['image']['url'];
	if (isset($get_media_url) && $get_media_url !== '') {
		$variations['image_link'] = $get_media_url;
		//$the_media = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE guid LIKE '%s'", '%'. $wpdb->esc_like( basename( $get_media_url ) ) . '%') );
		//$get_media_id = (isset($the_media->ID)) ? $the_media->ID : $the_media->id;
		$get_media_id = $variations['image_id'];
		if (isset($get_media_id) && $get_media_id !== '') {
			$image_attributes = uncode_get_media_info($get_media_id);
			$image_metavalues = unserialize($image_attributes->metadata);
			if ($image_attributes->post_mime_type === 'image/gif' || $image_attributes->post_mime_type === 'image/url') {
				$crop = false;
			}
			$image_resized = uncode_resize_image($image_attributes->id, $image_attributes->guid, $image_attributes->path, $image_metavalues['width'], $image_metavalues['height'], $col_size, ($crop ? $col_size / $thumb_ratio : null), $crop);
			$variations['image_src'] = $image_resized['url'];
			$variations['uncode_image_path'] = $image_attributes->path;
			$variations['uncode_image_guid'] = $image_attributes->guid;
		}
	}
	$variations['image_srcset'] = $variations['image_sizes'] = '';
	return $variations;
}

add_filter( 'woocommerce_single_product_zoom_enabled', 'uncode_woocommerce_single_product_zoom_enabled' );
if ( ! function_exists( 'uncode_woocommerce_single_product_zoom_enabled' ) ) :
/**
 * @since Uncode 1.6.0
 */
function uncode_woocommerce_single_product_zoom_enabled($return) {
	global $post;

	if ( !current_theme_supports( 'wc-product-gallery-zoom' ) ) {
		return false;
	}

	if ( ot_get_option('_uncode_product_enable_zoom') != 'on' ) {
		$return = false;
	}

	if ( !$post ) {
	    return $return;
	}

	$product_enable_zoom_meta = get_post_meta($post->ID, '_uncode_product_enable_zoom', 1);

	if ( $product_enable_zoom_meta === 'off' ) {
		$return = false;
	} elseif ( $product_enable_zoom_meta === 'on' ) {
		$return = true;
	}

    return $return;
}
endif;//uncode_woocommerce_single_product_zoom_enabled

add_filter( 'woocommerce_single_product_flexslider_enabled', 'uncode_woocommerce_single_product_slider_enabled' );
if ( ! function_exists( 'uncode_woocommerce_single_product_slider_enabled' ) ) :
/**
 * @since Uncode 1.6.0
 */
function uncode_woocommerce_single_product_slider_enabled($return) {
	global $post;

	if ( !current_theme_supports( 'wc-product-gallery-slider' ) ) {
		return false;
	}

	if ( ot_get_option('_uncode_product_enable_slider') != 'on' ) {
		$return = false;
	}

	if ( !$post ) {
	    return $return;
	}

	$product_enable_slider_meta = get_post_meta($post->ID, '_uncode_product_enable_slider', 1);
	$product_enable_stack_meta = get_post_meta($post->ID, '_uncode_product_image_layout', 1);

	if ( $product_enable_slider_meta === 'off' ) {
		$return = false;
	} elseif ( $product_enable_slider_meta === 'on' ) {
		$return = true;
	}

	if ( ( ot_get_option('_uncode_product_image_layout') === 'stack' && $product_enable_stack_meta !== 'std' ) || $product_enable_stack_meta === 'stack' ) {
		$return = false;
	}

    return $return;
}
endif;//uncode_woocommerce_single_product_slider_enabled

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

if ( ! function_exists( 'uncode_wc_get_cart_remove_url' ) ) :
/**
 * @since Uncode 1.7.3
 */
function uncode_wc_get_cart_remove_url($cart_item_key) {

	if ( function_exists( 'wc_get_cart_remove_url' ) ) {
		return wc_get_cart_remove_url($cart_item_key);
	} else {
		return WC()->cart->get_remove_url( $cart_item_key );
	}
}
endif;//uncode_wc_get_cart_remove_url

if ( ! function_exists( 'uncode_wc_get_formatted_cart_item_data' ) ) :
/**
 * @since Uncode 1.7.3
 */
function uncode_wc_get_formatted_cart_item_data($cart_item) {

	if ( function_exists( 'wc_get_formatted_cart_item_data' ) ) {
		return wc_get_formatted_cart_item_data($cart_item);
	} else {
		return WC()->cart->get_item_data( $cart_item );
	}
}
endif;//uncode_wc_get_formatted_cart_item_data

add_filter( 'loop_shop_per_page', 'uncode_wc_shop_per_page', 20 );
if ( ! function_exists( 'uncode_wc_shop_per_page' ) ) :
/**
 * @since Uncode 1.8.0
 */
function uncode_wc_shop_per_page() {
	$cols = get_option('posts_per_page');
	$def = ot_get_option('_uncode_product_index_ppp');
	if ( $def !== '0' ) {
		$cols = intval($def);
	}
	return $cols;
}
endif;//uncode_wc_shop_per_page

add_filter( 'woocommerce_loop_add_to_cart_args', 'uncode_filter_wc_loop_add_to_cart_args', 10, 2 );
function uncode_filter_wc_loop_add_to_cart_args( $args, $product ) {
    $args['class'] .= ' alt btn btn-default';
    return $args;
}

/**
 * Hide product title
 */
function uncode_woocommerce_hide_product_title() {
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
}

if ( ! function_exists( 'uncode_max_srcset_image_width' ) ) :
/**
 * @since Uncode 1.9.3
 */
function uncode_max_srcset_image_width() {
	$sizes = false;
	if ( function_exists('is_product') && is_product() ) {
		global $post, $limit_content_width, $metabox_data;
		$main_width = ot_get_option('_uncode_main_width');
		if ( isset($metabox_data['_uncode_specific_main_width_inherit'][0]) && $metabox_data['_uncode_specific_main_width_inherit'][0] != ''
			&&
			isset($metabox_data['_uncode_specific_main_width'][0]) && isset($metabox_data['_uncode_specific_main_width_inherit'][0])) {
			$main_width = unserialize($metabox_data['_uncode_specific_main_width'][0]);
		}
		if ((isset($main_width[0]) && $main_width[0] !== '') && (isset($main_width[1]) && $main_width[1] === 'px')) {
			$main_width = $main_width[0];
		} else {
			$main_width = false;
		}

		$col_size = ot_get_option('_uncode_product_media_size') == '' ? 6 : ot_get_option('_uncode_product_media_size');
		$col_size = ( get_post_meta($post->ID, '_uncode_product_media_size', 1) !== '' && get_post_meta($post->ID, '_uncode_product_media_size', 1) != 0 ) ? get_post_meta($post->ID, '_uncode_product_media_size', 1) : $col_size;

		if ( !$main_width || $limit_content_width === '' ) {
			$main_width = 2700;
		}

		$max_width = ( $main_width / 12 ) * $col_size;
	    $sizes = '(max-width: ' . $max_width . 'px) 100vw, ' . $max_width . 'px';
	}
	return $sizes;
}
endif;//uncode_max_srcset_image_width

add_filter( 'wp_get_attachment_image_attributes', 'uncode_get_attachment_image_attributes' );
if ( ! function_exists( 'uncode_get_attachment_image_attributes' ) ) :
/**
 * @since Uncode 1.9.3
 */
function uncode_get_attachment_image_attributes( $attr ) {
	$sizes = uncode_max_srcset_image_width();
	if ( $sizes !== false && $sizes !== '' ) {
		$attr['sizes'] = $sizes;
	}
	return $attr;
}
endif;//uncode_get_attachment_image_attributes

add_filter( 'woocommerce_available_variation', 'uncode_woocommerce_available_variation_img_sizes', 10, 3 );
if ( ! function_exists( 'uncode_woocommerce_available_variation_img_sizes' ) ) :
/**
 * @since Uncode 1.9.3
 */
function uncode_woocommerce_available_variation_img_sizes( $data, $product, $variation ) {
	$sizes = uncode_max_srcset_image_width();
	if ( $sizes !== false && $sizes !== '' ) {
		$data['image']['sizes'] = $sizes;
	}
	return $data;
}
endif;//uncode_woocommerce_available_variation_img_sizes

add_filter('woocommerce_sale_flash', 'uncode_woocommerce_sale_flash', 10, 3);
if ( ! function_exists( 'uncode_woocommerce_sale_flash' ) ) :
/**
 * @since Uncode 2.0.0
 */
function uncode_woocommerce_sale_flash($content, $post, $product){
   $content = '<span class="font-ui">' . $content . '</span>';
   return $content;
}
endif;//uncode_woocommerce_sale_flash

/**
 * Reviews
 */
remove_action( 'woocommerce_review_before', 'woocommerce_review_display_gravatar', 10 );
remove_action( 'woocommerce_review_meta', 'woocommerce_review_display_meta', 10 );
remove_action( 'woocommerce_review_meta', array( 'WC_Structured_Data', 'generate_review_data' ), 20 );
remove_action( 'woocommerce_review_comment_text', 'woocommerce_review_display_comment_text', 10 );

/**
 * Content Wrappers.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_before_main_content', array( 'WC_Structured_Data', 'generate_website_data' ), 30 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Breadcrumbs.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/**
 * Archive descriptions.
 */
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );

/**
 * Products Loop.
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_no_products_found', 'wc_no_products_found' );

/**
 * Pagination after shop loops.
 */
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );

/**
 * Sidebar.
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Product Loop Items.
 */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

/**
 * Subcategories.
 */
remove_action( 'woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10 );
remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
remove_action( 'woocommerce_after_subcategory', 'woocommerce_template_loop_category_link_close', 10 );

add_action( 'woocommerce_after_shop_loop_item', 'uncode_wc_single_reset_loop', 999 );
function uncode_wc_single_reset_loop() {
	unset( $GLOBALS['woocommerce_loop'] );
}
