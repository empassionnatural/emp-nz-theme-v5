<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $etheme_global;

global $post , $product;

$l = etheme_page_config();

$layout = $l['product_layout'];
$etheme_global = etheme_get_single_product_class( $layout );

$thumbs_slider_mode = etheme_get_option('thumbs_slider_mode');

if ( $thumbs_slider_mode == 'enable' || ( $thumbs_slider_mode == 'enable_mob' && get_query_var('is_mobile') ) ) {
    $gallery_slider = true;
} 
else {
    $gallery_slider = false;
}
// $gallery_slider = (etheme_get_option('thumbs_slider') == 'enable') ? true : false ;

$thumbs_slider = etheme_get_option('thumbs_slider_vertical');

$enable_slider = etheme_get_custom_field('product_slider', get_the_ID()); 

$stretch_slider = etheme_get_option('stretch_product_slider');

$slider_direction = etheme_get_custom_field('slider_direction', get_the_ID());

$vertical_slider = ($thumbs_slider == 'vertical') ? true : false;

if ( $slider_direction == 'vertical' ) {
    $vertical_slider = true;
}
elseif($slider_direction == 'horizontal') {
    $vertical_slider = false;
}

$show_thumbs = ($thumbs_slider != 'disable' ) ? true : false;

if ( $layout == 'large' && $stretch_slider ) {
    $show_thumbs = false;
    $etheme_global['class'] .= ' stretch-swiper-slider ';
}
if ( $slider_direction == 'disable' ) {
    $show_thumbs = false;
}
elseif ( in_array($slider_direction, array('vertical', 'horizontal') ) ) {
    $show_thumbs = true;
}
if ( $enable_slider == 'on' || ($enable_slider == 'on_mobile' && get_query_var('is_mobile') ) ) {
    $gallery_slider = true;
}
elseif ( $enable_slider == 'off' || ($enable_slider == 'on_mobile' && !get_query_var('is_mobile') ) ) {
    $gallery_slider = false;
    $show_thumbs = false;
}
$etheme_global['gallery_slider'] = $gallery_slider;
$etheme_global['vertical_slider'] = $vertical_slider;
$etheme_global['show_thumbs'] = $show_thumbs;

$etheme_global['class'] .= ' single-product';

/**
 * woocommerce_before_single_product hook
 *
 * @hooked wc_print_notices - 10
 */
 do_action( 'woocommerce_before_single_product' );

 if ( post_password_required() ) {
    echo get_the_password_form();
    return;
 }
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( $etheme_global['class'] ); ?>>

    <div class="row">
        <div class="<?php echo esc_attr( $l['content-class'] ); ?> product-content sidebar-position-<?php echo esc_attr( $l['sidebar'] ); ?>">
            <div class="row">
                <?php wc_get_template_part( 'single-product-content', $layout ); ?>
                <div class="single-product-footer">
                    <div class="col-lg-12 col-md-12 col-sm-12 product-footer">
                        <div class="col-lg-4 col-sm-4">
                            <i class="icon far fa-thumbs-up"></i>
                            <p class="title">Hassle Free Returns</p>
                            <p class="sub-title">30 days return policy</p>
                        </div>
                        <div class="col-lg-4 col-sm-4">
                            <i class="icon fas fa-truck"></i>
                            <p class="title">Fast Shipping</p>
                            <p class="sub-title">1 - 3 business days</p>
                        </div>
                        <div class="col-lg-4 col-sm-4">
                            <i class="icon fas fa-shield-alt"></i>
                            <p class="title">Secure Checkout</p>
                            <p class="sub-title">SSL Enabled Secure Checkout</p>
                        </div>
                    </div>
                </div>
                <div class="product-info-footer">
                    <div class="col-lg-8 col-md-8 col-sm-12 tab-content">
                        <div class="tabs">
                            <button class="tablinks active" onclick="openTabs(event, 'ProductReviews')">Reviews</button>
                            <button class="tablinks" onclick="openTabs(event, 'Usage')">Usage</button>
                            <button class="tablinks" style="border-right: 0px !important;" onclick="openTabs(event, 'Ingredients')">Ingredients</button>
                        </div>
                        <div id="ProductReviews" class="tabcontent">
                            <div class="star-rating"><span style="width:<?php echo ( ( $average / 5 ) * 100 );?>%"><strong itemprop="ratingValue" class="rating"><?php echo $average; ?></strong> <?php echo __( 'out of 5', 'woocommerce' )?></span></div>
                            <div class="rating-link">
                                <?php
                                if($average > 0) {
                                    echo "<p><a href=".get_permalink($post->ID)." target='_self' rel='noopener'>Write a Review</a></p>";
                                } else {
                                    echo "<p>This product has no reviews yet.</p>";
                                }
                                ?>
                            </div>
                        </div>
                        <div id="Usage" class="tabcontent" style="display: none;">
                            <p><?php

                                $product_ingredients = get_post_meta( $post->ID, 'field_prefix_usage', true );
                                echo apply_filters( 'the_content', $product_ingredients );

                                ?></p>
                        </div>
                        <div id="Ingredients" class="tabcontent" style="display: none;">
                            <p><?php

                                $product_ingredients = get_post_meta( $post->ID, 'field_prefix_name', true );
                                echo apply_filters( 'the_content', $product_ingredients );

                                ?></p>
                        </div>

                    </div>
                    <div  class="col-lg-4 col-md-4 col-sm-12 feedback-content">
                        <div class="pink-box">
                            <div class="white-box" style="height: 35%;">

                            </div>
                            <div class="feedbackinfo">
                                <p class="txt-from">From our last 100 customers</p>

                                <div class="heart">
                                    <i class="fas fa-heart"></i>
                                </div>

                                <p class="txt-percentage">96%</p>

                                <p class="txt-loved">Loved our products!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div> <!-- CONTENT/ END -->

        <?php if($l['sidebar'] != '' && $l['sidebar'] != 'without' && $l['sidebar'] != 'no_sidebar'): ?>

            <div class="<?php echo esc_attr( $l['sidebar-class'] ); ?> single-product-sidebar sidebar-<?php echo esc_attr( $l['sidebar'] ); ?>">
                <?php if ( etheme_get_option('brands_location') == 'sidebar' ) etheme_product_brand_image(); ?>
                <?php if(etheme_get_option('upsell_location') == 'sidebar') woocommerce_upsell_display(); ?>
                <?php dynamic_sidebar('single-sidebar'); ?>
            </div>

        <?php endif; ?>

    </div>
            
    <?php
        /**
         * woocommerce_after_single_product_summary hook
         *
         * @hooked woocommerce_output_product_data_tabs - 10
         * @hooked woocommerce_output_related_products - 20 [REMOVED in woo.php]
         */
         if(etheme_get_option('tabs_location') == 'after_content' && $layout != 'large') {
             do_action( 'woocommerce_after_single_product_summary' );
         }
    ?>

    <?php if(etheme_get_option('product_posts_links')): ?>
        <?php etheme_project_links(array()); ?>
    <?php endif; ?>
    
    <?php if(etheme_get_option('upsell_location') == 'after_content') woocommerce_upsell_display(); ?>

    <?php
        if(etheme_get_custom_field('additional_block') != '') {
            echo '<div class="product-extra-content">';
                etheme_static_block(etheme_get_custom_field('additional_block'), true);
            echo '</div>';
        }     
    ?>

    <?php if(etheme_get_option('show_related')) woocommerce_output_related_products(); ?>

    <meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
