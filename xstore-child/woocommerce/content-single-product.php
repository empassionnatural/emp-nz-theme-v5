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
                            <div id="reviews" class="woocommerce-Reviews">
                                <div id="comments">
                                    <h2 class="woocommerce-Reviews-title"><?php
				                        if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' && ( $count = $product->get_review_count() ) ) {
					                        /* translators: 1: reviews count 2: product name */
					                        printf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'woocommerce' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );
				                        } else {
					                        _e( 'Reviews', 'woocommerce' );
				                        }
				                        ?></h2>

			                        <?php if ( have_comments() ) : ?>

                                        <ol class="commentlist">
					                        <?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
                                        </ol>

				                        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
					                        echo '<nav class="woocommerce-pagination">';
					                        paginate_comments_links( apply_filters( 'woocommerce_comment_pagination_args', array(
						                        'prev_text' => '&larr;',
						                        'next_text' => '&rarr;',
						                        'type'      => 'list',
					                        ) ) );
					                        echo '</nav>';
				                        endif; ?>

			                        <?php else : ?>

                                        <p class="woocommerce-noreviews"><?php _e( 'There are no reviews yet.', 'woocommerce' ); ?></p>

			                        <?php endif; ?>
                                </div>

		                        <?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>

                                    <div id="review_form_wrapper">
                                        <div id="review_form">
					                        <?php
					                        $commenter = wp_get_current_commenter();

					                        $comment_form = array(
						                        'title_reply'          => have_comments() ? __( 'Add a review', 'woocommerce' ) : sprintf( __( 'Be the first to review &ldquo;%s&rdquo;', 'woocommerce' ), get_the_title() ),
						                        'title_reply_to'       => __( 'Leave a Reply to %s', 'woocommerce' ),
						                        'title_reply_before'   => '<span id="reply-title" class="comment-reply-title">',
						                        'title_reply_after'    => '</span>',
						                        'comment_notes_after'  => '',
						                        'fields'               => array(
							                        'author' => '<p class="comment-form-author">' . '<label for="author">' . esc_html__( 'Name', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label> ' .
							                                    '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" required /></p>',
							                        'email'  => '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label> ' .
							                                    '<input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" aria-required="true" required /></p>',
						                        ),
						                        'label_submit'  => __( 'Submit', 'woocommerce' ),
						                        'logged_in_as'  => '',
						                        'comment_field' => '',
					                        );

					                        if ( $account_page_url = wc_get_page_permalink( 'myaccount' ) ) {
						                        $comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a review.', 'woocommerce' ), esc_url( $account_page_url ) ) . '</p>';
					                        }

					                        if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {

						                        $comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'woocommerce' ) . '</label><select name="rating" id="rating" aria-required="true" required>
							<option value="">' . esc_html__( 'Rate&hellip;', 'woocommerce' ) . '</option>
							<option value="5">' . esc_html__( 'Perfect', 'woocommerce' ) . '</option>
							<option value="4">' . esc_html__( 'Good', 'woocommerce' ) . '</option>
							<option value="3">' . esc_html__( 'Average', 'woocommerce' ) . '</option>
							<option value="2">' . esc_html__( 'Not that bad', 'woocommerce' ) . '</option>
							<option value="1">' . esc_html__( 'Very poor', 'woocommerce' ) . '</option>
						</select></div>';
					                        }

					                        $comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required></textarea></p>';

					                        comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
					                        ?>
                                        </div>
                                    </div>

		                        <?php else : ?>

                                    <p class="woocommerce-verification-required"><?php _e( 'Only logged in customers who have purchased this product may leave a review.', 'woocommerce' ); ?></p>

		                        <?php endif; ?>

                                <div class="clear"></div>
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
                                    <i class="vc_icon_element-icon fa fa-heart"></i>
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
