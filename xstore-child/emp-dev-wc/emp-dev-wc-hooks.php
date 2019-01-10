<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_action('woocommerce_add_to_cart', 'empdev_new_customers_redirect_purchase', 100);
function empdev_new_customers_redirect_purchase() {

	if( ! is_user_logged_in() ){
		if ( ! WC()->cart->is_empty()  ) {

			$cart = WC()->cart->get_cart();
			$empdev_limit_new_customers_ids = get_option( 'empdev_limit_new_customers_ids', false );
			$blog_link = get_bloginfo('url');

			foreach ( $cart as $cart_item_key => $cart_item ) {

				$cart_item_id = $cart_item['product_id'];

				if ( in_array( $cart_item_id, $empdev_limit_new_customers_ids ) ) {

					wp_redirect( $blog_link . '/my-account/?redirect_permalink='.$blog_link.'/cart/');
					die;

				}

			}
		}
	}

}

add_action('woocommerce_after_cart', 'empdev_new_customers_cart_restriction');
function empdev_new_customers_cart_restriction(){

	if ( ! WC()->cart->is_empty()  ) {

		$empdev_limit_new_customers_ids = get_option( 'empdev_limit_new_customers_ids', false );
		$customer_orders = EMPDEV_WC_Static_Helper::get_recent_order();

		if (is_cart() && ! empty ( $empdev_limit_new_customers_ids ) && count( $customer_orders ) > 0 ){

			$cart = WC()->cart->get_cart();
			//var_dump($cart);
			$cart_item_id = null;
			$send_error_notice = false;
			foreach ( $cart as $cart_item_key => $cart_item ) {

				$cart_item_id = $cart_item['product_id'];

				if ( in_array( $cart_item_id, $empdev_limit_new_customers_ids ) ) {

					$send_error_notice = true;
					break;
				}

			}

			if($send_error_notice){
				wc_clear_notices();
				$product_title = get_the_title($cart_item_id);
				$message_title = "Sorry, ".$product_title." is only valid for new customers!";
				$message = __( $message_title, "woocommerce" );
				wc_add_notice( $message, 'error' );
			}

		}

	}

}


/**
 * Exclude products from a particular category on the shop page
 */
function empdev_exclude_cat_on_shop_page_query( $q ) {

	$tax_query = (array) $q->get( 'tax_query' );

	$tax_query[] = array(
		'taxonomy' => 'product_cat',
		'field' => 'slug',
		'terms' => array( 'uncategorised', 'black-friday-sale' ), // Don't display products in the clothing category on the shop page.
		'operator' => 'NOT IN'
	);


	$q->set( 'tax_query', $tax_query );

}
add_action( 'woocommerce_product_query', 'empdev_exclude_cat_on_shop_page_query' );

add_filter( 'woocommerce_add_to_cart_validation', 'emddev_conditional_product_in_cart_dynamic', 10, 2 );

function emddev_conditional_product_in_cart_dynamic( $passed, $product_id ) {

	// HERE define your 4 specific product Ids
	//$products_ids = array( 7131, 9026 );
	$products_ids = get_option( 'empdev_purchase_one_at_time', false );

	$addon_product_ids = get_option( 'empdev_enable_addon_checkout', false );

	// Searching in cart for IDs
	if ( ! WC()->cart->is_empty() && $products_ids != false  ) {
		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$item_pid = $cart_item['product_id'];
			$product_message_title_cart = trim( get_post_meta( $item_pid, '_empdev_purchase_product_title_message', true ) );

			$product_message_title_cart = ($product_message_title_cart != '') ? $product_message_title_cart : get_the_title( $item_pid );

			//	// If current product is from the targeted IDs and a another targeted product id in cart
			if ( in_array( $item_pid, $products_ids ) && in_array( $product_id, $products_ids ) && $product_id != $item_pid ) {
				$passed = false; // Avoid add to cart
				$message_title = "Sorry, this product can't be purchased at the same time with other special offers!";
				break; // Stop the loop
			}
		}
	}

	if ( WC()->cart->is_empty() ) {

		if ( in_array( $product_id, $addon_product_ids ) ) {
			$passed        = false; // Avoid add to cart
			$message_title = "Sorry, you can only purchase this product as an add on, please add item to your cart.";

		}
	}

//	$product_message_title = trim( get_post_meta( $product_id, '_empdev_purchase_product_title_message', true ) );
//	$product_message_title = ($product_message_title != '') ? $product_message_title : get_the_title( $product_id );

	if ( ! $passed ) {
		// Displaying a custom message
		$message = __( $message_title, "woocommerce" );
		wc_add_notice( $message, 'error' );
	}

	if( $passed ){
		return $passed;
	}

}
function emddev_conditional_product_in_cart( $passed, $product_id, $quantity) {

	// HERE define your 4 specific product Ids
	$products_ids = array( 10952, 9811 );

	// Searching in cart for IDs
	if ( ! WC()->cart->is_empty() ) {
		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$item_pid = $cart_item['product_id'];
			// If current product is from the targeted IDs and a another targeted product id in cart
			if ( in_array( $item_pid, $products_ids ) && in_array( $product_id, $products_ids ) && $product_id != $item_pid ) {
				$passed = false; // Avoid add to cart
				break; // Stop the loop
			}
		}
	}


	if ( ! $passed ) {
		// Displaying a custom message
		$message = __( "Sorry, Amazing Intro Offer and Crazy Pack Offer can't be purchased at the same time!", "woocommerce" );
		wc_add_notice( $message, 'error' );
	}

	if( $passed ){
		return $passed;
	}

}

if ( class_exists( 'WJECF_Wrap' ) ) {

	add_filter( 'woocommerce_coupon_is_valid', 'empdev_exclude_sale_free_products', 100, 2 );

	function empdev_exclude_sale_free_products( $valid, $coupon ) {

		$wrap_coupon          = WJECF_Wrap( $coupon );
		$exclude_sales_items  = $wrap_coupon->get_meta( 'exclude_sale_items' );
		$get_free_product_ids = WJECF_API()->get_coupon_free_product_ids( $coupon );

		$get_coupon_minimum_amount = $wrap_coupon->get_meta( 'minimum_amount' );

		/*Recalculate cart to exclude sale items in minimum spend amount restriction*/
		if ( $exclude_sales_items === true && ! empty( $get_coupon_minimum_amount ) ) {

			$cart = WC()->cart->get_cart();

			//var_dump(WC()->cart->get_totals());
			//reference meta abstract-wc-product.php

			$calculate_regular_price = 0;
			foreach ( $cart as $cart_item_key => $cart_item ) {

				$cart_item_id = $cart_item['product_id'];

				if ( ! in_array( $cart_item_id, $get_free_product_ids ) ) {
					$sale_price         = $cart_item['data']->get_sale_price();
					$cart_item_quantity = $cart_item['quantity'];

					if ( empty( $sale_price ) ) {

						$regular_price = $cart_item['data']->get_regular_price();

						$calculate_regular_price += (float) $regular_price * (int) $cart_item_quantity;
					}
				}

			}

			if ( $calculate_regular_price < (float) $get_coupon_minimum_amount ) {
				return false;
			}

		}

		return $valid;
	}
}