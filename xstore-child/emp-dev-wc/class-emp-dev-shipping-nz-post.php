<?php

/**
 * Created by EMPDEV.
 * User: web@empassion.com.au
 * Date: 6/19/2018
 * Time: 11:29 AM
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	function nz_post_po_box_method_init() {
		if ( ! class_exists( 'EMP_Shipping_NZ_POST_PO_BOX' ) ) {
			class EMP_Shipping_NZ_POST_PO_BOX extends WC_Shipping_Method {


				/**
				 * Constructor for your shipping class
				 *
				 * @access public
				 * @return void
				 */
				public function __construct() {
					$this->id                 = 'flat_rate_po_box'; // Id for your shipping method. Should be uunique.
					$this->method_title       = __( 'NZ POST (PO BOX)' );  // Title shown in admin
					$this->method_description = __( '' ); // Description shown in admin
					$this->enabled            = "yes"; // This can be added as an setting but for this example its forced enabled
					$this->title              = "Flat Rate (NZPOST)"; // This can be added as an setting but for this example its forced.
					$this->po_box_rate = '';
					$this->init();


				}
				/**
				 * Init your settings
				 *
				 * @access public
				 * @return void
				 */
				function init() {
					// Load the settings API
					$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
					$this->init_settings(); // This is part of the settings API. Loads settings you previously init.
					// Save settings in admin if you have any defined
					add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );

					$this->po_box_rate = $this->settings['shipping_rate_nzpost'];

				}

				public function init_form_fields(){
					$this->form_fields = array(
						'shipping_rate_nzpost' => array(
							'title' => __('Flat Rate (NZPOST):', 'woocommerce'),
							'type' => 'text',
							'description' => __('Shipping rate for PO BOX address', 'woocommerce')
						)
					);
				}
				/**
				 * calculate_shipping function.
				 *
				 * @access public
				 * @param mixed $package
				 * @return void
				 */
				public function calculate_shipping( $package = array() ) {
					$address        = $package['destination']['address'];
					$address_2      = $package['destination']['address_2'];
					$address_po_box = $address . ' ' . $address_2;
					$po_box_address = preg_match( '/(po box|p\.o\. box|pobox|p o box)/i', $address_po_box );

					if ( empty( $address ) || ! $po_box_address ) {
						return;
					}

					try {
						$rate = array(
							'id'       => $this->id,
							'label'    => $this->title,
							'cost'     => $this->po_box_rate,
							'taxes'    => false,
							'calc_tax' => 'per_order'
						);
						// Register the rate
						$this->add_rate( $rate );

					} catch ( Exception $e ) {

					}

				}
			}
		}
	}

	add_action( 'woocommerce_shipping_init', 'nz_post_po_box_method_init' );
	function add_nz_post_po_box_shipping_method( $methods ) {
		$methods['flat_rate_po_box'] = 'EMP_Shipping_NZ_POST_PO_BOX';
		return $methods;
	}
	add_filter( 'woocommerce_shipping_methods', 'add_nz_post_po_box_shipping_method' );
}