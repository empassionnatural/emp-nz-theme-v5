<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( class_exists( 'WWP_Wholesale_Prices' ) ){
    $wholesale_class = EMPDEV_WWPP_Wholesale_Price_Requirement::$on_wholesale;
}

$user = wp_get_current_user();
?>
<?php if ( etheme_get_option('cart_special_breadcrumbs') ) : ?>
	
<div class="cart-checkout-nav">
<a href="<?php echo wc_get_cart_url(); ?>" class="no-click active"> <?php esc_html_e('Shopping cart', 'xstore'); ?></a>

<span class="delimeter"> <?php echo etheme_get_cart_sep(); ?></span>

<a href="<?php echo wc_get_checkout_url(); ?>"> <?php esc_html_e('Checkout', 'xstore'); ?></a>

<span class="delimeter"><?php echo etheme_get_cart_sep(); ?></span>

<a href="#" class="no-click"> <?php esc_html_e('Order complete', 'xstore'); ?></a>
</div> <?php 

endif;



do_action( 'woocommerce_before_cart' );



?>

<div class="row">

	<div class="col-md-12">
        <h2>My Cart</h2>
		<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

		<?php do_action( 'woocommerce_before_cart_table' ); ?>
		<div class="table-responsive">
            <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                <thead>
                <tr>
                    <th class="product-name">&nbsp;</th>
                    <th class="product-details"><?php esc_html_e( 'Product', 'xstore' ); ?></th>
                    <th class="product-price"><?php esc_html_e( 'Price', 'xstore' ); ?></th>
                    <th class="product-quantity"><?php esc_html_e( 'Quantity', 'xstore' ); ?></th>
                    <th class="product-subtotal"><?php esc_html_e( 'Total', 'xstore' ); ?></th>
                    <th class="product-remove">&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                <?php
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                        ?>
                        <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">


                            <td class="product-name" data-title="<?php esc_attr_e( 'Product', 'xstore' ); ?>">
                                <div class="product-thumbnail">
                                    <?php
                                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image('medium'), $cart_item, $cart_item_key );

                                    if ( ! $_product->is_visible() || ! $product_permalink)
                                        echo $thumbnail;
                                    else
                                        printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );

                                    ?>
                                </div>
                            </td>
                            <td class="product-details">
                                <div class="product-thumbnail-mobile">
                                    <?php
                                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image('medium'), $cart_item, $cart_item_key );

                                    if ( ! $_product->is_visible() || ! $product_permalink)
                                        echo $thumbnail;
                                    else
                                        printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );

                                    ?>
                                </div>
                                <div class="cart-item-details">
                                    <div class="item-details-product-name">
                                        <?php
                                        if ( ! $_product->is_visible() || ! $product_permalink  )
                                            echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                                        else
                                            echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ) , $_product->get_name() ), $cart_item, $cart_item_key );

                                        // Meta data
                                        echo wc_get_formatted_cart_item_data( $cart_item );

                                        // Backorder notification
                                        if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
                                            echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'xstore' ) . '</p>';
                                        ?>
                                    </div>
                                    <span class="mobile-price">
                                        <?php
                                        echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                                        ?>
                                    </span>
                                    <?php
                                    //display in stock product quantity
                                    $stock_available = $_product->get_availability();
                                    $availability = $stock_available['availability'];
                                    $availability = preg_replace( '/(\d+)/', '<span class="stock-count">($1)</span>', $availability );
                                    echo '<span class="stock '. esc_attr( $stock_available['class'] ) .' "> '. wp_kses_post( $availability ).' </span>';
                                    ?>
                                </div>
                            </td>

                            <td class="product-price" data-title="<?php esc_attr_e( 'Price', 'xstore' ); ?>">
                                <?php
                                echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                                ?>
                            </td>

                            <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'xstore' ); ?>">
                                <?php
                                if ( $_product->is_sold_individually() ) {
                                    $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                } else {
                                    $product_quantity = woocommerce_quantity_input( array(
                                        'input_name'  => "cart[{$cart_item_key}][qty]",
                                        'input_value' => $cart_item['quantity'],
                                        'max_value'   => $_product->get_max_purchase_quantity(),
                                        'min_value'   => '0',
                                        'product_name'  => $_product->get_name(),
                                    ), $_product, false );
                                }

                                echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );

                                echo apply_filters( 'woocommerce_cart_item_remove_link_mobile', sprintf( '<a href="%s" class="remove-item-mobile" title="%s">Remove</a>', esc_url( wc_get_cart_remove_url( $cart_item_key ) ), __( 'Remove this item', 'xstore' ) ), $cart_item_key );
                                ?>



                            </td>

                            <td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'xstore' ); ?>">

                                <?php
                                echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                                ?>

                            </td>
                            <td class="product-remove">

                                <?php
                                echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="btn remove-item" title="%s"><i class="icon vc_icon_element-icon fa fa-trash"></i></a>', esc_url( wc_get_cart_remove_url( $cart_item_key ) ), __( 'Remove this item', 'xstore' ) ), $cart_item_key );
                                ?>

                            </td>
                        </tr>
                        <?php
                    }
                }

                do_action( 'woocommerce_cart_contents' );
                ?>

                <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                </tbody>
            </table>

		</div>

		<?php do_action( 'woocommerce_after_cart_table' ); ?>

				<div class="actions clearfix">

			<div class="col-md-<?php echo esc_attr($cols); ?> col-sm-<?php echo esc_attr($cols); ?> mob-center">

				<button type="submit" class="btn gray update-btn" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'xstore' ); ?>"><?php esc_html_e( 'Update cart', 'xstore' ); ?></button>
				<?php wp_nonce_field( 'woocommerce-cart' ); ?>
				<?php do_action( 'woocommerce_cart_actions' ); ?>
			</div>
		</div>
		
		</form>
	</div>

    <div class="col-md-6 add-coupon-code">
        <?php  if( ! in_array( 'wholesale_customer', $user->roles ) ) : ?>
            <div class="col-md-12 col-sm-12 text-left mob-center">
                <form class="checkout_coupon" method="post">
                    <h3 class="coupon-title"><?php esc_html_e('Apply Promo Code or Gift Coupon', 'xstore'); ?></h3>
                    <div class="coupon" style="display: block;">

                        <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_html_e( 'Coupon code', 'xstore' ); ?>" />
                        <!-- <input type="submit" class="btn" name="apply_coupon" value="&#9166;" /> -->
                        <?php do_action('woocommerce_cart_coupon'); ?>

                    </div>
                    <input type="submit" class="btn" name="apply_coupon" value="<?php esc_attr_e('Apply', 'xstore'); ?>" />
                </form>
                <div class="giftwrapper">
                    <?php do_action('woocommerce_cart_giftwrap'); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

	<div class="col-md-6 cart-order-details">
		<div class="cart-collaterals">
			<?php do_action( 'woocommerce_cart_collaterals' ); ?>
		</div>
		<?php  if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('cart-area'))): ?>
        <?php endif; ?>
	</div>

</div>
<!-- end row -->

<?php woocommerce_cross_sell_display(); ?>

<?php do_action( 'woocommerce_after_cart' ); ?>