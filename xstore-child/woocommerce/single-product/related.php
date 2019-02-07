<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

$posts_per_page = etheme_get_option('related_limit');

// updated for woocommerce v3.0
$related = array_map( 'absint', array_values( wc_get_related_products( $product->get_id(), $posts_per_page ) ) );

$get_excluded_related_ids = get_option( 'empdev_exclude_related_posts', false );

$product_addon = get_post_meta( $product->get_id(), '_empdev_display_addon_product_section', true );

$package_deals = has_term( 'package-deals', 'product_cat', 'uncategorised', $product->get_id() );

if( $product_addon ){

	$addon_products_ids = get_option( 'empdev_enable_addon_checkout', false );

	$exclude_product_addon_ids = get_option( 'empdev_enable_addon_checkout_hide', false );

	$filter_addon_product_ids = $addon_products_ids;

	if( $exclude_product_addon_ids ){
		$filter_addon_product_ids = array_diff( $addon_products_ids, $exclude_product_addon_ids );
	}

	if ( sizeof( $addon_products_ids ) == 0 || ! $addon_products_ids ) return;

	echo '<div class="rp-header related_prod_container">';

	echo '<h2 class="products-title"><span>' . esc_html__( 'Before you go, grab a Super Special!', 'xstore' ) . '</span></h2>';

	$args = array(
		'post_type'           => 'product',
		'ignore_sticky_posts' => 1,
		'no_found_rows'       => 1,
		'posts_per_page'      => 4,
		'orderby'             => 'ID',
		'post__in'            => $filter_addon_product_ids,
	);

	$slider_args = array(
		'slider_autoplay' => false,
		'slider_speed'    => false,
		'large' 		  => 4,
		'notebook' 		  => 4,
		'tablet_land' 	  => 3,
		'tablet_portrait' => 2,
		'echo' 			  => true,
		'autoheight' 	  => false
	);
	$columns = etheme_get_option('related_columns');
	etheme_products($args, false, $columns);
	//etheme_slider( $args, 'product', $slider_args );

	echo '</div>';

}
else {
	if ( sizeof( $related  || $package_deals) == 0 ) return;

	foreach( $related as $key => $value ){
		if( in_array( $value, $get_excluded_related_ids ) ){
			unset($related[$key]);
		}
	}

	echo '<div class="related_prod_container">';

	echo '<h2 class="products-title"><span>' . esc_html__( 'Related Products', 'xstore' ) . '</span></h2>';

	$args = apply_filters( 'woocommerce_related_products_args', array(
		'post_type'            => 'product',
		'ignore_sticky_posts'  => 1,
		'no_found_rows'        => 1,
		'posts_per_page'       => $posts_per_page,
		'orderby'              => $orderby,
		'post__in'             => $related,
		'post__not_in'         => array( $product->get_id() )
	) );


	if ( etheme_get_option('related_type') == 'slider' ) {

		$slider_args = array(
			'slider_autoplay' => false,
			'slider_speed'    => false,
			'large' 		  => 4,
			'notebook' 		  => 4,
			'tablet_land' 	  => 3,
			'tablet_portrait' => 2,
			'echo' 			  => true,
			'autoheight' 	  => false
		);

		$slides = etheme_get_option('related_slides');
		if ( is_array($slides) ) {
			if ( !empty($slides['padding-top']) ) {
				$slider_args['large'] = $slides['padding-top'];
			}
			if ( !empty($slides['padding-right']) ) {
				$slider_args['notebook'] = $slides['padding-right'];
			}
			if ( !empty($slides['padding-bottom']) ) {
				$slider_args['tablet_land'] = $slides['padding-bottom'];
			}
			if ( !empty($slides['padding-left']) ) {
				$slider_args['tablet_portrait'] = $slides['padding-left'];
				$slider_args['mobile'] = $slides['padding-left'];
			}
		}
		etheme_slider( $args, 'product', $slider_args );
	}
	else {
		$columns = etheme_get_option('related_columns');
		etheme_products($args, false, $columns);
	}

	echo '</div>';
}


wp_reset_postdata();
