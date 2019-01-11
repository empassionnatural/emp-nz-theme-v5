<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
	etheme_child_styles();
}

//embed admin scripts
add_action( 'admin_enqueue_scripts', 'empdev_custom_admin_scripts' );

function empdev_custom_admin_scripts(){

	wp_register_script( 'select2', WC()->plugin_url() . '/assets/js/select2/select2.full.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'select2' );

	wp_register_script( 'selectWoo', WC()->plugin_url() . '/assets/js/selectWoo/selectWoo.full.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'selectWoo' );

	wp_register_script( 'wc-enhanced-select', WC()->plugin_url() . '/assets/js/admin/wc-enhanced-select.min.js', array( 'jquery', 'selectWoo' ) );
	wp_enqueue_script( 'wc-enhanced-select' );
}

add_action( 'wp_enqueue_scripts', 'plugin_scripts', 99 );
function plugin_scripts() {

	wp_enqueue_style( 'bootstrap-select', get_stylesheet_directory_uri() . '/plugins/bootstrap-select/css/bootstrap-select.css' );
	wp_enqueue_script( 'bootstrap-core', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js', false, false, true );
	wp_enqueue_script( 'bootstrap-select', get_stylesheet_directory_uri() . '/plugins/bootstrap-select/js/bootstrap-select.js', array( 'jquery' ), false, false );

	wp_enqueue_script( 'moment-datejs', 'https://momentjs.com/downloads/moment.min.js', array(), false, false );
}

//enqueue custom scripts
add_action( 'wp_enqueue_scripts', 'empdev_custom_scripts_frontend', 99 );

function empdev_custom_scripts_frontend(){
	global $post;
	$post_id = $post->ID;
    if ( is_front_page() ) {
        wp_enqueue_style('font-lobster-css-style', 'https://fonts.googleapis.com/css?family=Lobster+Two', array(), '1.1.10');
        wp_enqueue_style('home-custom-style', get_stylesheet_directory_uri() . '/css/home-custom-style.css' , array(), '1.1.10');
    }
    //wp_enqueue_style( 'fontawesome-css-style', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css' , array(), '1.1.9' );
	wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri() . '/css/custom-style.css', array(), '3.1.4' );
    wp_enqueue_style( 'product-custom-style', get_stylesheet_directory_uri() . '/css/product-view.css' , array(), '1.1.11' );
    wp_enqueue_style( 'single-product-custom-style', get_stylesheet_directory_uri() . '/css/single-product-view.css' , array(), '1.1.11' );
	wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri() . '/js/custom-script.js', array('jquery'), '1.6.3', false );

	$schedule_on_sale = get_post_meta( $post_id, '_empdev_enable_sale_schedule', true );

	if( $schedule_on_sale ){
		wp_enqueue_script( 'schedule-sale', get_stylesheet_directory_uri() . '/js/schedule_sale.js', array('jquery'), '1.1.6', false );
	}

	wp_enqueue_style( 'ultimate-animate' );
	wp_enqueue_script( 'ultimate-appear' );
	wp_enqueue_script( 'ultimate-custom' );
	wp_enqueue_script( 'ultimate-vc-params' );

}

// check for empty-cart get param to clear the cart
add_action( 'woocommerce_init', 'woocommerce_clear_cart_url' );
function woocommerce_clear_cart_url() {
	global $woocommerce;
	if ( isset( $_GET['empty-cart'] ) ) {
		$woocommerce->cart->empty_cart();
	}
}
add_action( 'woocommerce_cart_actions', 'empdev_add_clear_cart_button', 20 );
function empdev_add_clear_cart_button() {
	$blog_url = get_bloginfo('wpurl');
	echo '<button class="btn gray" onclick="if(confirm(\'Are you sure to remove all items?\'))window.location=\''.$blog_url.'/cart/?empty-cart=true\';else event.stopPropagation();event.preventDefault();">' . __( "Empty Cart", "woocommerce" ) . '</button>';

}

add_action( 'woocommerce_init', 'empdev_woocommerce_redirect_product_url' );

function empdev_woocommerce_redirect_product_url() {

	if ( is_user_logged_in() ) {

		if ( isset( $_GET['redirect_permalink'] ) ) {
			wp_safe_redirect( $_GET['redirect_permalink'], 302 );
			exit;
		}
	}
}

//wholesale notice filter
if( class_exists( 'WWP_Wholesale_Prices' ) ) {
	require_once( get_stylesheet_directory() . '/woocommerce-wholesale-prices-premium/class-wwpp-wholesale-price-requirement.php' );
}

//EMP Dev Woocommerce
require_once( get_stylesheet_directory() . '/emp-dev-wc/class-emp-dev-wc-static-helper.php' );
require_once( get_stylesheet_directory() . '/emp-dev-wc/class-emp-dev-wc-meta-option.php' );
require_once( get_stylesheet_directory() . '/emp-dev-wc/emp-dev-theme-functions.php' );
require_once( get_stylesheet_directory() . '/emp-dev-wc/emp-dev-wc-hooks.php' );
require_once( get_stylesheet_directory() . '/emp-dev-wc/emp-dev-login.php' );
require_once( get_stylesheet_directory() . '/emp-dev-wc/emp-meta.php' );

/*double check*/
if ( ! function_exists( 'rwmb_meta' ) ) {
    function rwmb_meta( $key, $args = '', $post_id = null ) {
        return false;
    }
}


add_action( 'widgets_init', 'empdev_product_banner_widget' );
function empdev_product_banner_widget() {
	register_sidebar( array(
		'name' => __( 'Before Product Top Banner', 'empassion' ),
		'id' => 'before-product-top-banner',
		'description' => __( 'Display banner after breadcrumb on product pages.', 'empassion' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>',
	) );
}

add_action( 'widgets_init', 'empdev_promotional_widget' );
function empdev_promotional_widget() {
	register_sidebar( array(
		'name' => __( 'Promotional Header Top Right', 'empassion' ),
		'id' => 'promotional-header-top-right',
		'description' => __( 'Display promotional header widget.', 'empassion' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>',
	) );
}