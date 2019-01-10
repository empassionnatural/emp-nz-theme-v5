<?php

/**
 * Created by PhpStorm.
 * User: web
 * Date: 8/27/2018
 * Time: 11:38 AM
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( class_exists('WooCommerce' ) ) {
	class EMPDEV_WC_Static_Helper {

		/**
		 * @return object WP_Post (type order)
		 * */
		public static function get_recent_order(){
			$customer_orders = get_posts( array(
				'numberposts' => 1,
				'meta_key'    => '_customer_user',
				'meta_value'  => get_current_user_id(),
				'post_type'   => wc_get_order_types(),
				//'post_status' => array_keys( wc_get_order_statuses() ),
				'post_status' => array('wc-completed', 'wc-processing'),
			) );

			return $customer_orders;
		}
	}
}




