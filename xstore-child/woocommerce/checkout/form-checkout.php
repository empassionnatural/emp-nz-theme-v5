<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php if ( etheme_get_option('cart_special_breadcrumbs') ) : ?>
<div class="cart-checkout-nav">
<a href="<?php echo wc_get_cart_url(); ?>" class="active"> <?php esc_html_e('Shopping cart', 'xstore'); ?></a>

<span class="delimeter"> <?php echo etheme_get_cart_sep(); ?></span>

<a href="<?php echo wc_get_checkout_url(); ?>" class="active no-click"> <?php esc_html_e('Checkout', 'xstore'); ?></a>

<span class="delimeter"><?php echo etheme_get_cart_sep(); ?></span>

<a href="#" class="no-click"> <?php esc_html_e('Order complete', 'xstore'); ?></a>
</div>

<?php endif; ?>

    <?php wc_print_notices(); ?>


<?php
// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'xstore' ) ) );
	return;
}

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', wc_get_checkout_url() ); ?>


    <div class="left-form col-md-5">

	    <?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>

        <div class="order-summary-form">

            <div class="order-review">
            <h3 class="header-title step-title"><span><?php esc_html_e( 'Order Summary', 'xstore' ); ?></span></h3>
            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

            <div id="order_review-1" class="woocommerce-checkout-review-order">

                <table class="shop_table woocommerce-checkout-review-order-table">
                    <thead>
                    <tr>
                        <th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
                        <th class="product-total"><?php _e( 'Total', 'woocommerce' ); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    do_action( 'woocommerce_review_order_before_cart_contents' );

                    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                        $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                            ?>
                            <tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                                <td class="product-name">
                                    <?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;'; ?>
                                    <?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); ?>
                                    <?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
                                </td>
                                <td class="product-total">
                                    <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }

                    do_action( 'woocommerce_review_order_after_cart_contents' );
                    ?>
                    </tbody>
                    <tfoot>

                    <tr class="cart-subtotal">
                        <th><?php _e( 'Subtotal', 'woocommerce' ); ?></th>
                        <td><?php wc_cart_totals_subtotal_html(); ?></td>
                    </tr>

                    <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                        <tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                            <th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
                            <td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

                        <?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

                        <?php wc_cart_totals_shipping_html(); ?>

                        <?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

                    <?php endif; ?>

                    <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                        <tr class="fee">
                            <th><?php echo esc_html( $fee->name ); ?></th>
                            <td><?php wc_cart_totals_fee_html( $fee ); ?></td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
                        <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
                            <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
                                <tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
                                    <th><?php echo esc_html( $tax->label ); ?></th>
                                    <td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr class="tax-total">
                                <th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
                                <td><?php wc_cart_totals_taxes_total_html(); ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

                    <tr class="order-total">

                        <th><?php _e( 'Total', 'woocommerce' ); ?></th>
                        <td><span class="order-totals-tax"><?php wc_cart_totals_order_total_html(); ?></span></td>

                    </tr>

                    <?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

                    </tfoot>
                </table>


            </div>

            <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
        </div>

        </div>

        <?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

    </div>

    <div class="right-form col-md-7">

    <?php if ( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>

        <h3 class="header-title step-title"><span><?php esc_html_e( 'Billing &amp; Shipping', 'xstore' ); ?></span></h3>

    <?php else : ?>
         <h3 class="header-title step-title"><span><?php esc_html_e( 'Billing Details', 'xstore' ); ?></span></h3>
    <?php endif; ?>

    <?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
        <div class="if-login">
            <div id="return-user" class="accordion" onclick="openAccordion(event, 'return-user','login-form')" >
                <h3 class="accordion-title step-title"><span><?php esc_html_e( 'Sign in Here!', 'xstore' ); ?></span><span value="-" class="minus"><i class="et-icon et-minus"></i></span><span value="+" class="plus"><i class="et-icon et-plus"></i></span></h3>
            </div>

            <div id="login-form" class="accordion-sibling" style="display: none;">
            <?php
            woocommerce_login_form(
                array(
                    'message'  => __( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing &amp; Shipping section.', 'woocommerce' ),
                    'redirect' => wc_get_page_permalink( 'checkout' ),
                    'hidden'   => true,
                )
            );
            ?>
            </div>
        </div>
    <?php endif; ?>

    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

        <div class="row">

            <div class="col-md-12">

                <?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

                    <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                    <div id="customer_details">

                        <div id="acc" class="accordion" onclick="openAccordion(event, 'acc','acctDet')" >

                            <h3 class="accordion-title step-title"><span><?php esc_html_e( 'Account Details', 'xstore' ); ?></span><span value="-" class="minus"><i class="et-icon et-minus"></i></span><span value="+" class="plus"><i class="et-icon et-plus"></i></span></h3>

                        </div>


                        <div id="acctDet" class="accordion-desc max-height-accordion"  style="display: none;">

                            <div class="col-1">

                                <?php do_action( 'woocommerce_checkout_billing' ); ?>

                            </div>

                            <div class="col-1">

                                <?php do_action( 'woocommerce_checkout_shipping' ); ?>

                            </div>

                        </div>

                    </div>

                    <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

                <?php endif; ?>

            </div>

            <div class="col-md-12 cart-order-details">

                <div class="order-review">
                    <h3 class="header-title step-title"><span><?php esc_html_e( 'Choose a Payment Method', 'xstore' ); ?></span></h3>
                    <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                    <div id="order_review" class="woocommerce-checkout-review-order">
                        <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                    </div>

                    <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                </div>

            </div>

        </div>

    </form>

    </div>

