<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

$qty_val = ( isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 );

$btn_class = '';

if( $product->supports( 'ajax_add_to_cart' ) && etheme_get_option( 'ajax_add_to_cart' ) ) {
	$btn_class = 'add_to_cart_button ajax_add_to_cart';
}

$btn_class = apply_filters( 'et_single_add_to_cart_btn_class', $btn_class );

?>

<?php
echo wc_get_stock_html( $product );

$user = wp_get_current_user();

$new_customers_val = trim( get_post_meta( $product->get_id(), '_empdev_limit_new_customers', true ) );
$start_date_val    = trim( get_post_meta( $product->get_id(), '_empdev_limit_new_customers_start_date', true ) );

$start_date_restriction = date( "F j, Y, g:i a", strtotime( $start_date_val ) );
$start_date_restriction = strtotime( $start_date_restriction );
$user_registration      = strtotime( $user->user_registered );
$customer_orders = EMPDEV_WC_Static_Helper::get_recent_order();

if( $new_customers_val ): ?>

	<?php if( $user->ID && count( $customer_orders ) > 0 ) {
		echo '<p style="color:red;font-size:18px">This offer is only valid for new customers!</p>';
	}
	else {

		if ( $product->is_in_stock() ) :

		do_action( 'woocommerce_before_add_to_cart_form' ); ?>

        <form class="cart" method="post" enctype='multipart/form-data'>
			<?php
            if ( ! $product->is_sold_individually() ) {

			do_action( 'woocommerce_before_add_to_cart_button' );
			/**
			 * @since 3.0.0.
			 */


            echo "<p class='input-quantity'>Quantity</p>";

			do_action( 'woocommerce_before_add_to_cart_quantity' );

			woocommerce_quantity_input( array(
				'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
				'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product ),
				'input_value' => $qty_val
			) );

			/**
			 * @since 3.0.0.
			 */
			do_action( 'woocommerce_after_add_to_cart_quantity' );

            }
            $stock = get_post_meta( $post->ID, '_stock', true );
            if( $stock > 0 ) {
                echo wc_get_stock_html( $product );
            } else {
                echo "<span class='stock in-stock'>In Stock</span>";
            }
			?>

            <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); // updated for woocommerce v3.0 ?>" />

            <button type="submit" data-quantity="<?php echo esc_attr( $qty_val ); ?>" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" class="<?php echo esc_attr( $btn_class ); ?> single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

			<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
        </form>

		<?php do_action( 'woocommerce_after_add_to_cart_form' );

		endif;
	}

?>

<?php elseif ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" method="post" enctype='multipart/form-data'>
	 	<?php
	 		// if ( ! $product->is_sold_individually() ) {

	 			do_action( 'woocommerce_before_add_to_cart_button' );
				/**
				 * @since 3.0.0.
				 */

                echo "<p class='input-quantity'>Quantity</p>";

				do_action( 'woocommerce_before_add_to_cart_quantity' );

	 			woocommerce_quantity_input( array(
	 				'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
	 				'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product ),
	 				'input_value' => $qty_val
	 			) );

	 			/**
				 * @since 3.0.0.
				 */
				do_action( 'woocommerce_after_add_to_cart_quantity' );
	 		// }

                $stock = get_post_meta( $post->ID, '_stock', true );
                if( $stock > 0 ) {
                    echo wc_get_stock_html( $product );
                } else {
                    echo "<span class='stock in-stock'>In Stock</span>";
                }

	 	?>

	 	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); // updated for woocommerce v3.0 ?>" />

	 	<button type="submit" data-quantity="<?php echo esc_attr( $qty_val ); ?>" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" class="<?php echo esc_attr( $btn_class ); ?> single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>