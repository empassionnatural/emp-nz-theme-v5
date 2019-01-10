<?php

/**
 * Exclude products from a particular category on the shop page
 */
function empdev_exclude_cat_on_shop_page_query( $q ) {

	$tax_query = (array) $q->get( 'tax_query' );

	$tax_query[] = array(
		'taxonomy' => 'product_cat',
		'field' => 'slug',
		'terms' => array( 'uncategorised', 'black-friday-sale' ), // Don't display products in the clothing category on the shop page.
		'operator' => 'NOT IN'
	);


	$q->set( 'tax_query', $tax_query );

}
add_action( 'woocommerce_product_query', 'empdev_exclude_cat_on_shop_page_query' );

add_filter( 'woocommerce_add_to_cart_validation', 'emddev_conditional_product_in_cart_dynamic', 10, 2 );

function emddev_conditional_product_in_cart_dynamic( $passed, $product_id ) {

	// HERE define your 4 specific product Ids
	//$products_ids = array( 7131, 9026 );
	$products_ids = get_option( 'empdev_purchase_one_at_time', false );

	$addon_product_ids = get_option( 'empdev_enable_addon_checkout', false );

	// Searching in cart for IDs
	if ( ! WC()->cart->is_empty() && $products_ids != false  ) {
		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$item_pid = $cart_item['product_id'];
			$product_message_title_cart = trim( get_post_meta( $item_pid, '_empdev_purchase_product_title_message', true ) );

			$product_message_title_cart = ($product_message_title_cart != '') ? $product_message_title_cart : get_the_title( $item_pid );

			//	// If current product is from the targeted IDs and a another targeted product id in cart
			if ( in_array( $item_pid, $products_ids ) && in_array( $product_id, $products_ids ) && $product_id != $item_pid ) {
				$passed = false; // Avoid add to cart
				$message_title = "Sorry, this product can't be purchased at the same time with other special offers!";
				break; // Stop the loop
			}
		}
	}

	if ( WC()->cart->is_empty() ) {

		if ( in_array( $product_id, $addon_product_ids ) ) {
			$passed        = false; // Avoid add to cart
			$message_title = "Sorry, you can only purchase this product as an add on, please add item to your cart.";

		}
	}

//	$product_message_title = trim( get_post_meta( $product_id, '_empdev_purchase_product_title_message', true ) );
//	$product_message_title = ($product_message_title != '') ? $product_message_title : get_the_title( $product_id );

	if ( ! $passed ) {
		// Displaying a custom message
		$message = __( $message_title, "woocommerce" );
		wc_add_notice( $message, 'error' );
	}

	if( $passed ){
		return $passed;
	}

}
function emddev_conditional_product_in_cart( $passed, $product_id, $quantity) {

	// HERE define your 4 specific product Ids
	$products_ids = array( 10952, 9811 );

	// Searching in cart for IDs
	if ( ! WC()->cart->is_empty() ) {
		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$item_pid = $cart_item['product_id'];
			// If current product is from the targeted IDs and a another targeted product id in cart
			if ( in_array( $item_pid, $products_ids ) && in_array( $product_id, $products_ids ) && $product_id != $item_pid ) {
				$passed = false; // Avoid add to cart
				break; // Stop the loop
			}
		}
	}


	if ( ! $passed ) {
		// Displaying a custom message
		$message = __( "Sorry, Amazing Intro Offer and Crazy Pack Offer can't be purchased at the same time!", "woocommerce" );
		wc_add_notice( $message, 'error' );
	}

	if( $passed ){
		return $passed;
	}

}

if ( class_exists( 'WJECF_Wrap' ) ) {

	add_filter( 'woocommerce_coupon_is_valid', 'empdev_exclude_sale_free_products', 20, 2 );

	function empdev_exclude_sale_free_products( $valid, $coupon ) {

		$wrap_coupon          = WJECF_Wrap( $coupon );
		$exclude_sales_items  = $wrap_coupon->get_meta( 'exclude_sale_items' );
		$get_free_product_ids = WJECF_API()->get_coupon_free_product_ids( $coupon );

		if ( ! empty( $get_free_product_ids ) && $exclude_sales_items === true ) {

			$get_coupon_minimum_amount = $wrap_coupon->get_meta( 'minimum_amount' );

			$cart = WC()->cart->get_cart();

			//var_dump(WC()->cart->get_totals());
			//reference meta abstract-wc-product.php

			$calculate_regular_price = 0;
			foreach ( $cart as $cart_item_key => $cart_item ) {

				$cart_item_id = $cart_item['product_id'];

				if ( ! in_array( $cart_item_id, $get_free_product_ids ) ) {
					$sale_price         = $cart_item['data']->get_sale_price();
					$cart_item_quantity = $cart_item['quantity'];

					if ( empty( $sale_price ) ) {

						$regular_price = $cart_item['data']->get_regular_price();

						$calculate_regular_price += (float) $regular_price * (int) $cart_item_quantity;
					}
				}

			}

			if ( $calculate_regular_price < (float) $get_coupon_minimum_amount ) {
				return false;
			}

		}

		return $valid;
	}
}

function empdev_etheme_top_links($args = array()) {

	$links = etheme_get_links($args);
	if( ! empty($links)) :
		?>

			<?php foreach ($links as $link): ?>

				<?php

				$submenu = '';

				if( isset( $link['submenu'] ) ) {
					$submenu = $link['submenu'];
				}

				printf(
					$submenu
				);
				?>
			<?php endforeach ?>

	<?php endif;

}

function empdev_etheme_get_links($args) {
	extract(shortcode_atts(array(
		'short'  => false,
		'popups'  => true,
	), $args));
	$links = array();

	$reg_id = etheme_tpl2id('et-registration.php');

	$login_link = wp_login_url( get_permalink() );

	if( class_exists('WooCommerce')) {
		$login_link = get_permalink( get_option('woocommerce_myaccount_page_id') );
	}

	if(etheme_get_option('promo_popup')) {
		$links['popup'] = array(
			'class' => 'popup_link',
			'link_class' => 'etheme-popup',
			'href' => '#etheme-popup-holder',
			'title' => etheme_get_option('promo-link-text'),
		);
		if(!etheme_get_option('promo_link')) {
			$links['popup']['class'] .= ' hidden';
		}
		if(etheme_get_option('promo_auto_open')) {
			$links['popup']['link_class'] .= ' open-click';
		}
	}

	if( etheme_get_option('top_links') ) {
		$class = ( etheme_get_header_type() == 'hamburger-icon' ) ? ' type-icon' : '';
		if ( is_user_logged_in() ) {
			if( class_exists('WooCommerce')) {
				if ( has_nav_menu( 'my-account' ) ) {
					$submenu = wp_nav_menu(array(
						'theme_location' => 'my-account',
						'before' => '',
						'container_class' => 'menu-main-container',
						'after' => '',
						'link_before' => '',
						'link_after' => '',
						'depth' => 100,
						'fallback_cb' => false,
						'walker' => new ETheme_Navigation,
						'echo' => false
					));
				} else {
					$submenu = '<ul class="dropdown-menu">';
					$permalink = wc_get_page_permalink( 'myaccount' );

					foreach ( wc_get_account_menu_items() as $endpoint => $label ) {
						$url = ( $endpoint != 'dashboard' ) ? wc_get_endpoint_url( $endpoint, '', $permalink ) : $permalink ;
						$submenu .= '<li class="' . wc_get_account_menu_item_classes( $endpoint ) . '"><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
					}

					$submenu .= '</ul>';
				}

				$links['my-account'] = array(
					'class' => 'my-account-link' . $class,
					'link_class' => '',
					'href' => get_permalink( get_option('woocommerce_myaccount_page_id') ),
					'title' => esc_html__( 'Account', 'xstore' ),
					'submenu' => $submenu
				);

			}
			// $links['logout'] = array(
			//     'class' => 'logout-link' . $class,
			//     'link_class' => '',
			//     'href' => wp_logout_url(home_url()),
			//     'title' => esc_html__( 'Logout', 'xstore' )
			// );
		} else {

			$login_text = ($short) ? esc_html__( 'Sign In', 'xstore' ): esc_html__( 'Login | Register', 'xstore' );

//			$links['login'] = array(
//				'class' => 'login-link' . $class,
//				'link_class' => '',
//				'href' => $login_link,
//				'title' => $login_text
//			);

			if(!empty($reg_id)) {
				$links['register'] = array(
					'class' => 'register-link' . $class,
					'link_class' => '',
					'href' => get_permalink($reg_id),
					'title' => esc_html__( 'Register', 'xstore' )
				);
			}

		}
	}

	return apply_filters('etheme_get_links', $links);
}

function etheme_sign_link($class = '', $short = false, $echo = false) {
	$link = array();
	$type = etheme_get_option( 'sign_in_type' );
	$ht = get_query_var( 'et_ht', 'xstore' );
	$login_link = (etheme_woocommerce_installed()) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();

	if ( $ht == 'hamburger-icon' || $type == 'icon' ) {
		$class .= ' type-icon';
	} elseif( $type == 'text_icon' ){
		$class .= ' type-icon-text';
	}

	if ( is_user_logged_in() && etheme_woocommerce_installed() ) {
		if ( has_nav_menu( 'my-account' ) ) {
			$submenu = wp_nav_menu(array(
				'theme_location' => 'my-account',
				'before' => '',
				'container_class' => 'menu-main-container',
				'after' => '',
				'link_before' => '',
				'link_after' => '',
				'depth' => 100,
				'fallback_cb' => false,
				'walker' => new ETheme_Navigation,
				'echo' => false
			));
		} else {
			//$submenu = '<div class="">';
			$submenu = '<ul class="menu dropdown-menu">';
			foreach ( wc_get_account_menu_items() as $endpoint => $label ) {
				$url = ( $endpoint != 'dashboard' ) ? wc_get_endpoint_url( $endpoint, '', $login_link ) : $login_link ;
				$submenu .= '<li class="' . wc_get_account_menu_item_classes( $endpoint ) . '">';
				$submenu .= '<a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>';
				$submenu .= '</li>';
			}
			$submenu .= '</ul>';
			//$submenu .= '</div>';
		}

		$link = array(
			'class' => 'my-account-link' . $class,
			'link_class' => '',
			'href' => $login_link,
			'title' => esc_html__( 'My Account', 'xstore' ),
			'submenu' => $submenu
		);
		$class .= ' my-account-link';
	} else {
		$login_text = ( $short ) ? esc_html__( 'Sign In', 'xstore' ) : esc_html__( 'Sign In or Create an account', 'xstore' );
		$login_text = ( etheme_get_option( 'sign_in_text' ) != '' ) ? etheme_get_option( 'sign_in_text' ) : $login_text;

		if ( ! $short ) {
			if ( etheme_woocommerce_installed() ) {
				ob_start(); ?>
				<form class="woocommerce-form woocommerce-form-login login" method="post" action="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ) ?>">

					<?php do_action( 'woocommerce_login_form_start' ); ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="username"><?php esc_html_e( 'Username or email address', 'xstore' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
					</p>
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="password"><?php esc_html_e( 'Password', 'xstore' ); ?>&nbsp;<span class="required">*</span></label>
						<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
					</p>

					<?php do_action( 'woocommerce_login_form' ); ?>

					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="lost-password"><?php esc_html_e( 'Lost password ?', 'xstore' ); ?></a>

					<p>
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox inline">
							<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember Me', 'xstore' ); ?></span>
						</label>
					</p>

					<p class="login-submit">
						<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
						<button type="submit" class="woocommerce-Button button" name="login" value="<?php esc_attr_e( 'Log in', 'xstore' ); ?>"><?php esc_html_e( 'Log in', 'xstore' ); ?></button>
					</p>
					<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ): ?>
						<p class="text-center"><?php esc_html_e('New client', 'xstore');?> <a href="<?php echo $login_link; ?>" class="register-link"><?php esc_html_e('Register ?', 'xstore'); ?></a></p>
					<?php endif; ?>

					<?php do_action( 'woocommerce_login_form_end' ); ?>

				</form>

				<?php $login_form = ob_get_clean(); }
			else {
				$login_form = wp_login_form(
					array(
						'echo' => false,
						'label_username' => esc_html__( 'Username or email address *', 'xstore' ),
						'label_password' => esc_html__( 'Password *', 'xstore' )
					)
				);
			}
		} else {
			$login_form = '';
		}
		$link = array(
			'class' => 'login-link' . $class,
			'link_class' => '',
			'href' => $login_link,
			'title' => $login_text,
			'submenu' => '<div class="menu-main-container">' . $login_form . '</div>'
		);

		$class .= ' login-link';
	}

	if ( $echo ) {
		$out = '';
		$out .= sprintf(
			'<%1$s class="%2$s"><a href="%3$s" class="%4$s">%5$s</a>%6$s</%1$s>',
			( etheme_get_option( 'top_links') == 'menu' ) ? 'li' : 'div',
			$class,
			$link['href'],
			$link['link_class'],
			$link['title'],
			$link['submenu']
		);
		if ( $echo === 'get' ) {
			return $out;
		} else {
			echo $out;
		}
	} else {
		return $link;
	}
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