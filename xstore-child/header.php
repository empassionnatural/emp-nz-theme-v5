<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="user-scalable=1, width=device-width, initial-scale=1, maximum-scale=2.0"/>
	<?php wp_head(); ?>
    <style>
        /*Fixed bugs woocommerce*/
        .shopping-container .cart-bag .badge-number{
            background-color: #000;
        }
        .woocommerce-info b, .cart-popup .woocommerce-Price-amount{
            color: #333;
        }
        .error-tr{
            outline: 1px solid #b63231;
        }
        .quantity.buttons_added span:hover, table.cart .remove-item:hover,
        input[type=submit]:hover, .btn:hover, .back-top:hover, .button:hover,
        .swiper-entry .swiper-custom-left:hover, .swiper-entry .swiper-custom-right:hover {
            background-color: #dadada;
        }
        .header-search.act-default [role=searchform] .btn:hover{
            background-color: #4a4a4a !important;
        }
        table.cart .product-details a:hover, .cart-widget-products .remove:hover, .cart-widget-products a:hover, .shipping-calculator-button, .tabs .tab-title:hover, .next-post .post-info .post-title, .prev-post .post-info .post-title, .form-submit input[type=submit]{
            color: #000 !important;
        }
        .form-submit input[type=submit]:hover{
            color: white !important;
        }
        .shipping-calculator-button:hover{
            text-decoration: underline;
        }
        .active.et-opened .tab-title.opened{
            border: 1px solid #e6e6e6;
        }
        .posts-nav-btn:hover .button:before{
            color: #cbcbcb;
        }
        #wc-stripe-payment-request-wrapper, #wc-stripe-payment-request-button-separator{
            display: none !important;
        }
        ::-moz-selection { background: #d2c5ff !important; }
        ::selection { background: #d2c5ff !important; }
    </style>
    <script>
        jQuery(document).ready(function($){
            $('#place_order').live('click', function(e){
                $('.shipping-error').remove();
                $('.shipping').removeClass('error-tr');
                var checked_shipping = $('.shipping_method:checked').length;
                //console.log(checked_shipping);

                if(checked_shipping === 0){
                    e.preventDefault();
                    e.stopPropagation();
                    var shipping_error = '<ul class="woocommerce-error" role="alert"><li>Please select your shipping method.</li></ul>';
                    $('.shipping').addClass('error-tr');
                    //console.log('NO shipping');
                    $('<div class="shipping-error">'+shipping_error+'</div>').insertAfter('#payment');

                }
            });
        });
    </script>
</head>

<body <?php body_class(); ?>>

<?php do_action( 'et_after_body', true ); ?>

<?php
$header_type = get_query_var('et_ht', 'xstore');
$my_account_mobile = etheme_get_option('mobile_account');
$pp_mobile = etheme_get_option('mobile_promo_popup');
$mob_logo = etheme_get_option('mobile_menu_logo_switcher');
$mob_menu_logo = etheme_get_option('mobile_menu_logo');
?>

<div class="template-container">
	<?php if ( is_active_sidebar('top-panel') && etheme_get_option('top_panel') && etheme_get_option('top_bar')): ?>
		<div class="top-panel-container">
			<div class="top-panel-inner">
				<div class="container">
					<?php dynamic_sidebar( 'top-panel' ); ?>
					<div class="close-panel"></div>
				</div>
			</div>
		</div>
	<?php endif ?>
	<div class="mobile-menu-wrapper">
		<div class="container">
			<div class="navbar-collapse">
				<div class="mobile-menu-header"><?php if ( $mob_logo ) { ?>
						<div class="mobile-header-logo">
						<?php if ( isset($mob_menu_logo['url']) && $mob_menu_logo['url'] != '' ) :
							echo '<img src="'.$mob_menu_logo['url'].'" alt="'.$mob_menu_logo['alt'].'">';
							else : 
						etheme_logo(); 
						endif; ?>
						</div>
				<?php } ?><?php if(etheme_get_option('search_form')): ?>
					<?php etheme_search_form( array(
						'action' => 'default'
					)); ?>
				<?php endif; ?></div>
				<div class="mobile-menu-inner">
					<?php etheme_menu( 'mobile-menu', 'custom_nav_mobile' ); ?>
					<?php etheme_top_links( array( 'short' => true ), $my_account_mobile, $pp_mobile ); ?>
					<?php dynamic_sidebar('mobile-sidebar'); ?>
				</div>
			</div><!-- /.navbar-collapse -->
		</div>
	</div>
	<div class="template-content">
		<div class="page-wrapper" data-fixed-color="<?php etheme_option( 'fixed_header_color' ); ?>">

<?php get_template_part( 'headers/' . $header_type ); ?>