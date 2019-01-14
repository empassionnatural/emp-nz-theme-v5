<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//I've edited something in here: wence
global $woocommerce, $product, $etheme_global, $post;
$average = $product->get_average_rating();
$product_settings = etheme_get_option('quick_view_switcher');
$product_settings = $product_settings['enabled'];

$zoom = etheme_get_option('zoom_effect');
if( class_exists( 'YITH_WCWL_Init' ) ) {
    add_action( 'woocommerce_single_product_summary', create_function( '', 'echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );' ), 31 );
}
remove_all_actions( 'woocommerce_product_thumbnails' );

$etheme_global['quick_view'] = true;

if( get_option('yith_wcwl_button_position') == 'shortcode' ) {
    add_action( 'woocommerce_after_add_to_cart_button', 'etheme_wishlist_btn', 30 );
}

$class = '';
if ( etheme_get_option('quick_view_layout') == 'centered' ) $class = 'text-center';
if ( etheme_get_option('quick_images') == 'slider') $class .= ' swipers-couple-wrapper swiper-entry';

?>

<div class="row">
    <div class="col-md-12 col-sm-12 product-content quick-view-layout-<?php echo etheme_get_option('quick_view_layout'); ?> <?php echo ($product->is_sold_individually()) ? 'sold-individually' : '' ?>">
        <div class="row">

            <?php if (etheme_get_option('quick_images') != 'none'): ?>
                <div class="col-lg-4 col-sm-4 product-images <?php echo esc_attr($class); ?>">
                    <?php
                    if (etheme_get_option('quick_images') == 'slider'): ?>
                        <?php
                        /**
                         * woocommerce_before_single_product_summary hook
                         *
                         * @hooked woocommerce_show_product_sale_flash - 10
                         * @hooked woocommerce_show_product_images - 20
                         */
                        woocommerce_show_product_images();
                        ?>
                    <?php else: ?>
                        <?php the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) ); ?>
                    <?php endif; ?>
                </div><!-- Product images/ END -->
            <?php endif; ?>

            <div class="col-lg-<?php if (etheme_get_option('quick_images') != 'none'): ?>8<?php else: ?>12<?php endif; ?> col-sm-8 product-information">
                <?php if (etheme_get_option('quick_product_name')): ?>
                    <h3 class="product-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <?php endif ?>
                <?php if (array_key_exists('quick_product_name', $product_settings)): ?>
                    <h3 class="product-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <?php endif ?>
                <?php if (array_key_exists('quick_categories', $product_settings)): ?>
                    <?php
                    etheme_product_cats();
                    ?>
                <?php endif ?>
                <div class="quick-view-info">
                    <div class="star-rating"><span style="width:<?php echo ( ( $average / 5 ) * 100 );?>%"><strong itemprop="ratingValue" class="rating"><?php echo $average; ?></strong> <?php echo __( 'out of 5', 'woocommerce' )?></span></div>
                    <div class="rating-link">
                    <?php
                    if($average > 0) {
                        echo "<p><a href=".get_permalink($post->ID)." target='_self' rel='noopener'>Write a Review</a></p>";
                    } else {
                        echo "<p>No Reviews Yet. Be the first! <a class='double-rev' href=".get_permalink($post->ID)." target='_self' rel='noopener'>Write a Review</a></p>";
                    }
                    ?>
                    </div>
                    <?php woocommerce_template_single_excerpt(); ?>
                    <div class="price">
                        <?php if (etheme_get_option('quick_price')): ?>
                            <?php woocommerce_template_single_price(); ?>
                        <?php endif; ?>
                        <?php if (array_key_exists('quick_price', $product_settings)): ?>
                            <?php woocommerce_template_single_price(); ?>
                        <?php endif; ?>
                    </div>
                    <div class="product-to-cart">
                    <?php
//                        if (etheme_get_option('quick_add_to_cart')) {
//                            if( $product->get_type() == 'simple' ) {
//                                woocommerce_template_single_add_to_cart();
//                            } else {
//                                woocommerce_template_loop_add_to_cart();
//                            }
//                        }
                    if ( ! $product->is_purchasable() ) {
                        return;
                    }
                    if ( $product->is_in_stock() ) : ?>

                        <?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

                        <form class="cart"  method="post" enctype='multipart/form-data'>

                            <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

                            <?php
                            do_action( 'woocommerce_before_add_to_cart_quantity' );
                            echo '<p class="input-quantity">Quantity</p>';
                            woocommerce_quantity_input( array(
                                'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
                                'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
                                'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
                            ) );

                            do_action( 'woocommerce_after_add_to_cart_quantity' );

                            $stock = get_post_meta( $post->ID, '_stock', true );
                            if( $stock > 0 ) {
                                echo wc_get_stock_html( $product );
                            } else {
                                echo "<p class='stock in-stock'>In Stock</p>";
                            }
                            ?>

                            <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

                            <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
                        </form>

                        <?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
                    <?php else:

                        echo '<p class="_stock">';
                        empdev_after_shop_loop_item();
                        echo '</p>';

                        ?>
                    <?php endif; ?>

                    </div>

                    <?php
                        woocommerce_template_single_meta();
                    ?>
                    <?php if ( etheme_get_option( 'quick_share' ) ) : ?>
                        <div class="product-share">
                            <?php echo do_shortcode('[share title="' . esc_html__( 'Share:', 'xstore' ) . '" text="' . get_the_title() . '"]'); ?>
                        </div>
                    <?php endif ?>

                </div>

                <?php
                $length = etheme_get_option( 'quick_descr_length' );
                $length = ( $length ) ? $length : 50;
                $description = etheme_trunc( etheme_strip_shortcodes(get_the_content()), $length );
                $words = explode(" ", $description);
                ?>
                <?php if ( $length > 0 ) : ?>
                <div class="product-desc">
                    <p><?php echo implode(" ", array_slice($words, 0, 50)); ?>...</p>
                    <?php echo "<div><p class='read-more'><a href=".get_permalink($post->ID)." target='_self' rel='noopener'>Read more</a></p></div>"; ?>
                </div>
                <?php endif ?>
                <div class="product-footer">
                    <div class="col-lg-4 col-sm-4">
                        <i class="icon vc_icon_element-icon fa fa-thumbs-up"></i>
                        <p class="title">Hassle Free Returns</p>
                        <p class="sub-title">30 days return policy</p>
                    </div>
                    <div class="col-lg-4 col-sm-4">
                        <i class="icon vc_icon_element-icon fa fa-truck"></i>
                        <p class="title">Fast Shipping</p>
                        <p class="sub-title">1 - 3 business days</p>
                    </div>
                    <div class="col-lg-4 col-sm-4">
                        <i class="icon vc_icon_element-icon fa fa-shield"></i>
                        <p class="title">Secure Checkout</p>
                        <p class="sub-title">SSL Enabled Secure Checkout</p>
                    </div>
                </div>

            </div><!-- Product information/ END -->
        </div>

    </div> <!-- CONTENT/ END -->
</div>