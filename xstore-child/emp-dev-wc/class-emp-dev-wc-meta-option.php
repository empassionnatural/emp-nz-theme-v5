<?php

/**
 * Created by PhpStorm.
 * User: web
 * Date: 8/27/2018
 * Time: 11:38 AM
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	class EMPDEV_WC_Meta_Option {

		public function __construct() {

			// Add to cart.
			//add_filter( 'woocommerce_add_cart_item', array( $this, 'add_cart_item' ), 20, 1 );

			add_action( 'woocommerce_product_options_advanced', array( $this, 'empdev_woocommerce_product_options_advanced' ) );

			add_action( 'woocommerce_process_product_meta', array( $this, 'empdev_woocommerce_advance_option_save_product' ) );
		}

		public function empdev_woocommerce_product_options_advanced() {

			echo '<div class="options_group">';

			woocommerce_wp_checkbox(
				array(
					'id' => '_empdev_exclude_related_posts',
					'label' => __( 'Hide in related products', 'woocommerce' ),
					'placeholder' => '',
					'desc_tip' => 'true',
					'description' => __( 'Check this option to exclude in related products.', 'woocommerce' )
				)

			);

			woocommerce_wp_checkbox(
				array(
					'id' => '_empdev_purchase_one_at_time',
					'label' => __( 'Enable purchase one at a time', 'woocommerce' ),
					'placeholder' => '',
					'desc_tip' => 'true',
					'description' => __( 'This will add to the list of products that can\'t be added in the cart at the same time.', 'woocommerce' )
				)

			);

			woocommerce_wp_text_input(
				array(
					'id'          => '_empdev_purchase_product_title_message',
					'label'       => __( 'Product tile to display error message.', 'wmamc-cart-limit' ),
					'placeholder' => ''
				)
			);

			woocommerce_wp_checkbox(
				array(
					'id' => '_empdev_limit_new_customers',
					'label' => __( 'Enable new customers only', 'woocommerce' ),
					'placeholder' => '',
					'desc_tip' => 'true',
					'description' => __( 'Only new customers can purchase this product.', 'woocommerce' )
				)

			);

			woocommerce_wp_text_input(
				array(
					'id'          => '_empdev_limit_new_customers_start_date',
					'label'       => __( 'Enter start date to restrict new customers limit.', 'woocommerce' ),
					'placeholder' => ''
				)
			);

			woocommerce_wp_checkbox(
				array(
					'id' => '_empdev_enable_addon_checkout',
					'label' => __( 'Enable product addon', 'woocommerce' ),
					'placeholder' => '',
					'desc_tip' => 'true',
					'description' => __( 'Display upsell as addon during checkout.', 'woocommerce' )
				)

			);

			woocommerce_wp_text_input(
				array(
					'id'          => '_empdev_product_upsell_price',
					'label'       => __( 'Enter upsell price as addon.', 'woocommerce' ),
					'placeholder' => ''
				)
			);

			woocommerce_wp_checkbox(
				array(
					'id' => '_empdev_enable_sale_schedule',
					'label' => __( 'Enable scheduled sale', 'woocommerce' ),
					'placeholder' => '',
					'desc_tip' => 'true',
					'description' => __( 'Enable set schedule on sale.', 'woocommerce' )
				)

			);

			echo '</div>';

		}

		public function empdev_woocommerce_advance_option_save_product( $post_id ) {

			//enable schedule on sale
			$schedule_on_sale = trim( get_post_meta( $post_id, '_empdev_enable_sale_schedule', true ) );
			$schedule_on_sale_update = $_POST['_empdev_enable_sale_schedule'];

			if ( $schedule_on_sale != $schedule_on_sale_update ) {

				update_post_meta( $post_id, '_empdev_enable_sale_schedule', $schedule_on_sale_update );

			}

			$product_upsell_price = trim( get_post_meta( $post_id, '_empdev_product_upsell_price', true ) );
			$product_upsell_price_update = $_POST['_empdev_product_upsell_price'];

			if ( $product_upsell_price != $product_upsell_price_update ) {

				update_post_meta( $post_id, '_empdev_product_upsell_price', $product_upsell_price_update );

			}

			$product_addon_id = trim( get_post_meta( $post_id, '_empdev_enable_addon_checkout', true ) );
			$product_addon_id_update = $_POST['_empdev_enable_addon_checkout'];

			if ( $product_addon_id != $product_addon_id_update ) {

				$this->create_post_option_array_value($product_addon_id_update, $post_id, '_empdev_enable_addon_checkout', 'empdev_enable_addon_checkout' );

			}

			$new_customers_val = trim( get_post_meta( $post_id, '_empdev_limit_new_customers', true ) );
			$new_customers_val_update = $_POST['_empdev_limit_new_customers'];

			if ( $new_customers_val != $new_customers_val_update ) {

				//update_post_meta( $post_id, '_empdev_limit_new_customers', $new_customers_val_update );
				$this->create_post_option_array_value( $new_customers_val_update, $post_id, '_empdev_limit_new_customers', 'empdev_limit_new_customers_ids' );

			}

			$start_date_val = trim( get_post_meta( $post_id, '_empdev_limit_new_customers_start_date', true ) );
			$start_date_val_update = sanitize_text_field( $_POST['_empdev_limit_new_customers_start_date'] );


			if ( $start_date_val != $start_date_val_update ) {

				update_post_meta( $post_id, '_empdev_limit_new_customers_start_date', $start_date_val_update );

			}

			$meta_related = trim( get_post_meta( $post_id, '_empdev_exclude_related_posts', true ) );
			$meta_related_new = $_POST['_empdev_exclude_related_posts'];
			//delete_option( 'empdev_exclude_related_posts');
			if ( $meta_related != $meta_related_new ) {

				$this->create_post_option_array_value($meta_related_new, $post_id, '_empdev_exclude_related_posts', 'empdev_exclude_related_posts' );

			}

			$meta_purchase = trim( get_post_meta( $post_id, '_empdev_purchase_one_at_time', true ) );
			$meta_purchase_new = $_POST['_empdev_purchase_one_at_time'];

			$meta_purchase_title = trim( get_post_meta( $post_id, '_empdev_purchase_product_title_message', true ) );
			$meta_purchase_title_new = sanitize_text_field( $_POST['_empdev_purchase_product_title_message'] );

			//	delete_option('empdev_purchase_one_at_time');

			//	delete_post_meta($post_id, 'empdev_purchase_one_at_time');

			if ( $meta_purchase != $meta_purchase_new ) {

				$this->create_post_option_array_value($meta_purchase_new, $post_id, '_empdev_purchase_one_at_time', 'empdev_purchase_one_at_time' );

			}

			if ( $meta_purchase_title != $meta_purchase_title_new ) {

				update_post_meta( $post_id, '_empdev_purchase_product_title_message', $meta_purchase_title_new );

			}

		}

		public function add_cart_item( $cart_item ) {

//			if ( ! empty( $cart_item['addons'] ) && apply_filters( 'woocommerce_product_addons_adjust_price', true, $cart_item ) ) {
//				$price = (float) $cart_item['data']->get_price( 'edit' );
//
//				// Compatibility with Smart Coupons self declared gift amount purchase.
//				if ( empty( $price ) && ! empty( $_POST['credit_called'] ) ) {
//					// $_POST['credit_called'] is an array.
//					if ( isset( $_POST['credit_called'][ $cart_item['data']->get_id() ] ) ) {
//						$price = (float) $_POST['credit_called'][ $cart_item['data']->get_id() ];
//					}
//				}
//
//				if ( empty( $price ) && ! empty( $cart_item['credit_amount'] ) ) {
//					$price = (float) $cart_item['credit_amount'];
//				}
//
//				foreach ( $cart_item['addons'] as $addon ) {
//					if ( $addon['price'] > 0 ) {
//						$price += (float) $addon['price'];
//					}
//				}
//
//				$cart_item['data']->set_price( $price );
//			}
			$WC_cart = WC()->cart;

			if( ! $WC_cart->is_emptt() ){

			}
			$cart = WC()->cart->get_cart();


			var_dump(trim( get_post_meta( $cart_item['product_id'], '_empdev_product_upsell_price', true ) ) );

			return $cart_item;
		}

		private function create_post_option_array_value( $meta_value_new, $post_id, $post_meta_name, $option_meta_name ){

			update_post_meta( $post_id, $post_meta_name, $meta_value_new );

			$product_ids     = array();
			$get_product_ids = get_option( $option_meta_name, false );

			if ( ! $get_product_ids ) {

				update_option( $option_meta_name, array($post_id) );

			} else {

				$check_product_ids = in_array( $post_id, $get_product_ids );
				$new_product_ids = array();

				if ( $check_product_ids ) {

					$i = 0;
					foreach ( $get_product_ids as $pid ) {
						if ( $pid == $post_id ) {
							unset( $get_product_ids[ $i ] );
						} else {
							$new_product_ids[] = $pid;
						}
						$i ++;

					}
					update_option( $option_meta_name, $new_product_ids );

				} else {
					//array_push($get_product_ids, $post_id)
					array_push($get_product_ids, $post_id);

					update_option( $option_meta_name, $get_product_ids );

				}
			}


		}
	}
	new EMPDEV_WC_Meta_Option();
}