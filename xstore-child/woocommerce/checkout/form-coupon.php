<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

?>

<div class="add-coupon-code">
    <?php if ( wc_coupons_enabled() ) : $cols = 12; ?>
        <div class="col-md-<?php echo esc_attr($cols); ?> col-sm-<?php echo esc_attr($cols); ?> text-left mob-center">
            <form class="checkout_coupon" method="post" style="display: block !important;">
                <h3 class="coupon-title"><?php esc_html_e('Apply Promo Code or Gift Coupon', 'xstore'); ?></h3>
                <div class="coupon" style="display: block;">

                    <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_html_e( 'Coupon code', 'xstore' ); ?>" />
                    <!-- <input type="submit" class="btn" name="apply_coupon" value="&#9166;" /> -->
                    <?php do_action('woocommerce_cart_coupon'); ?>
                </div>
                <input type="submit" class="btn" name="apply_coupon" value="<?php esc_attr_e('Apply', 'xstore'); ?>" />
            </form>
        </div>
    <?php endif; ?>
</div>