<?php

/**
 * Created by PhpStorm.
 * User: web
 * Date: 8/13/2018
 * Time: 1:43 PM
 */

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	class EMP_Coupon_Extension {
		public function __construct() {
			add_filter( 'woocommerce_coupon_discount_types', array( $this, 'add_buy_2_get_1_free' ) );

			//add_filter( 'woocommerce_coupon_get_discount_amount', array($this ,'get_discount_amount'), 10, 5 );
		}

		public function get_discount_amount( $discount, $discounting_amount, $cart_item, $single, $coupon ) {
			//$coupon_type = wcs_get_coupon_property( $coupon, 'discount_type' );
//			if( $coupon_type == 'buy_2_get_1_free' ){
//
//			}
			var_dump($coupon->get_discount_type());
			$discount = '25.00';
			//var_dump(wc_get_price_including_tax( $cart_item['data'] ));
			return $discount;
		}
		/**
		 * Function to add new discount type 'buy_2_get_1_free'
		 *
		 * @param array $discount_types existing discount types
		 * @return array $discount_types including buy 2 get 1 free discount type
		 */
		public function add_buy_2_get_1_free( $discount_types ) {
			$discount_types['buy_2_get_1_free'] = __( 'Buy 2 Get 1 Free', 'emp-dev' );
			return $discount_types;
		}
	}
}

new EMP_Coupon_Extension();