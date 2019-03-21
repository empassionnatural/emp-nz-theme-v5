<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function etheme_project_links( $atts, $content = null ) {

	global $post;
	$is_product = false;
	$exclude_category = array( '438', '103' );

	if ( $post->post_type == 'product' ) {
		$is_product = true;

		$next_post = get_adjacent_post( 1, $exclude_category, 0, 'product_cat' );
		$prev_post = get_adjacent_post( 1, $exclude_category, 1, 'product_cat' );

		if ( ! empty( $next_post ) && $next_post->post_type == 'product' ) {
			$next_post = et_visible_pruduct( $next_post->ID, 'next' );
		}

		if ( ! empty( $prev_post ) && $prev_post->post_type == 'product' ) {
			$prev_post = et_visible_pruduct( $prev_post->ID, 'prev' );
		}

	} else {


		$next_post = get_next_post( false, $exclude_category );
		$prev_post = get_previous_post( false, $exclude_category );
	}
	?>
    <div class="posts-navigation">
		<?php if(!empty($prev_post)) :
			if ( function_exists('mb_strlen') ) {
				$prev_symbols = (mb_strlen(get_the_title($prev_post->ID)) > 30) ? '...' : '';
				$title = mb_substr(get_the_title($prev_post->ID),0,30) . $prev_symbols;
			}
			else {
				$prev_symbols = (strlen(get_the_title($prev_post->ID)) > 30) ? '...' : '';
				$title = substr(get_the_title($prev_post->ID),0,30) . $prev_symbols;
			}?>
            <div class="posts-nav-btn prev-post test">
                <div class="post-info">
                    <div class="post-details">
                        <a href="<?php echo get_permalink($prev_post->ID); ?>" class="post-title">
							<?php echo esc_html($title); ?>
                        </a>
						<?php if ( $is_product ) {
							$p = wc_get_product($prev_post);
							echo '<p class="price">'.$p->get_price_html().'</p>';
						} ?>
                    </div>
                    <a href="<?php echo get_permalink($prev_post->ID); ?>">
						<?php $img = get_the_post_thumbnail( $prev_post->ID, array(90, 90));
						echo (!empty($img) ) ? $img : '<img src="'.ETHEME_BASE_URI.'images/placeholder.jpg">';  ?>
                    </a>
                </div>
            </div>
		<?php endif; ?>

		<?php if(!empty($next_post)) :
			if ( function_exists('mb_strlen') ) {
				$next_symbols = (mb_strlen(get_the_title($next_post->ID)) > 30) ? '...' : '';
				$title = mb_substr(get_the_title($next_post->ID),0,30) . $next_symbols;
			}
			else {
				$next_symbols = (strlen(get_the_title($next_post->ID)) > 30) ? '...' : '';
				$title = substr(get_the_title($next_post->ID),0,30) . $next_symbols;
			} ?>
            <div class="posts-nav-btn next-post test2">
                <div class="post-info">
                    <a href="<?php echo get_permalink($next_post->ID); ?>">
						<?php $img = get_the_post_thumbnail( $next_post->ID, array(90, 90));
						echo (!empty($img) ) ? $img : '<img src="'.ETHEME_BASE_URI.'images/placeholder.jpg">';  ?>
                    </a>
                    <div class="post-details">
                        <a href="<?php echo get_permalink($next_post->ID); ?>" class="post-title">
							<?php echo esc_html($title); ?>
                        </a>
						<?php if ( $is_product ) {
							$p = wc_get_product($next_post);
							echo '<p class="price">'.$p->get_price_html().'</p>';
						} ?>
                    </div>
                </div>
            </div>
		<?php endif; ?>
    </div>
	<?php wp_reset_query();
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


function etheme_ajax_search_action() {
	global $woocommerce, $wpdb, $wp_query, $product;
	$result = array(
		'status' => 'error',
		'html' => ''
	);
	if( isset( $_REQUEST['s'] ) && $_REQUEST['s'] != '') {

		$s = sanitize_text_field( $_REQUEST['s'] );
		$i = 0;
		$to = 8;

		// ! Get sku results
		if ( etheme_get_option('search_by_sku') ) {
			$sku = $_REQUEST['s'];

			// ! Should the query do some extra joins for WPML Enabled sites...
			$wmplEnabled = false;

			if(defined('WPML_TM_VERSION') && defined('WPML_ST_VERSION') && class_exists("woocommerce_wpml")){
				$wmplEnabled = true;
				// ! What language should we search for...
				$languageCode = ICL_LANGUAGE_CODE;
			}

			// ! Search for the sku of a variation and return the parent.
			$variationsSql = "
              SELECT p.post_parent as post_id FROM $wpdb->posts as p
              join $wpdb->postmeta pm
              on p.ID = pm.post_id
              and pm.meta_key='_sku'
              and pm.meta_value LIKE '%$sku%'
              ";

			// ! IF WPML Plugin is enabled join and get correct language product.
			if( $wmplEnabled ) {
				$variationsSql .=
					"join ".$wpdb->prefix."icl_translations t on
                     t.element_id = p.post_parent
                     and t.element_type = 'post_product'
                     and t.language_code = '$languageCode'";
				;
			}

			$variationsSql .= "
                  where 1
                  AND p.post_parent <> 0
                  and p.post_status = 'publish'
                  group by p.post_parent
              ";
			$variations = $wpdb->get_results($variationsSql);


			$regularProductsSql =
				"SELECT p.ID as post_id FROM $wpdb->posts as p
                    join $wpdb->postmeta pm
                    on p.ID = pm.post_id
                    and  pm.meta_key='_sku' 
                    AND pm.meta_value LIKE '%$sku%'
                    AND post_title NOT LIKE '%$sku%'
                ";
			// ! IF WPML Plugin is enabled join and get correct language product.
			if($wmplEnabled) {
				$regularProductsSql .=
					"join ".$wpdb->prefix."icl_translations t on
                     t.element_id = p.ID
                     and t.element_type = 'post_product'
                     and t.language_code = '$languageCode'";
			}
			$regularProductsSql .=
				"where 1
                and (p.post_parent = 0 or p.post_parent is null)
                and p.post_status = 'publish'
                group by p.ID";
			$regular_products = $wpdb->get_results($regularProductsSql);
		}

		// ! Get title/excerpt results
		// $title_q = "SELECT ID FROM $wpdb->posts WHERE post_title LIKE '%$s%' AND post_type = 'product'";
		$excerpt_q = "SELECT ID FROM $wpdb->posts WHERE post_excerpt LIKE '%$s%' AND post_title NOT LIKE '%$s%' AND post_type = 'product'";

		if ( ! $wmplEnabled ) {
			$title_q = "SELECT ID FROM $wpdb->posts WHERE post_title LIKE '%$s%' AND post_type = 'product'";
		} else {
			$title_q = "
                SELECT ID FROM $wpdb->posts
                JOIN {$wpdb->prefix}icl_translations ON 
                ($wpdb->posts.ID = {$wpdb->prefix}icl_translations.element_id)
                AND {$wpdb->prefix}icl_translations.language_code = '$languageCode'
                WHERE post_title LIKE '%$s%' AND post_type = 'product'
            ";
		}

		$title_q = $wpdb->get_results( $title_q );
		$excerpt_q = $wpdb->get_results( $excerpt_q );

		$title_q = array_reverse( $title_q );
		$excerpt_q = array_reverse( $excerpt_q );

		$products = array_merge( $title_q, $excerpt_q );

		$result['html'] .= '<div class="product-ajax-list"></ul>';

		if ( ! empty( $products ) || ! empty( $regular_products ) || ! empty( $variations ) ) {
			$result['status'] = 'success';
			$result['html'] .= '<h3 class="search-results-title">' . esc_html__('Products found', 'xstore') . '<a href="' . esc_url( home_url() ) . '/?s='. $s .'&post_type=product&product_cat=' . $_REQUEST['cat'] . '">' . esc_html__('View all', 'xstore' ) . '</a></h3>';
		}

		if ( ! empty( $products ) && count( $products ) > 0 ) {
			foreach ( $products as $post ) {
				if ( $i >= $to )  break;

				setup_postdata( $post );
				$product = wc_get_product( $post->ID );

				if ( ! $product->is_visible() ) continue;

				if ( $_REQUEST['cat'] ) {
					$terms = wp_get_post_terms( $post->ID, 'product_cat' );
					$categories = array();
					foreach ( $terms as $term ){
						$categories[] = $term->slug;
					}

					if ( ! in_array( $_REQUEST['cat'], $categories ) ) continue;
				}
				
				//skip uncategorised product category
				if ( has_term( 'uncategorised', 'product_cat', $post->ID ) ) continue;

				$result['html'] .= '<li>';
				$result['html'] .= '<a href="'.get_the_permalink($post->ID).'" title="'.get_the_title($post->ID).'" class="product-list-image">';
				$result['html'] .= ( get_the_post_thumbnail( $post->ID ) ) ? get_the_post_thumbnail( $post->ID ) : wc_placeholder_img( $size = 'shop_thumbnail' );
				$result['html'] .='</a>';
				$result['html'] .= '<p class="product-title"><a href="'.get_the_permalink($post->ID).'" title="'.get_the_title($product->post_id).'">'.get_the_title($post->ID).'</a></p>';
				$result['html'] .= '<div class="price">'.$product->get_price_html().'</div>';
				$result['html'] .= '</li>';

				$i++;
			}
		}


		if ( ( ! empty( $regular_products ) || ! empty( $variations ) ) && etheme_get_option('search_by_sku') ) {

			$products = array_merge( $variations, $regular_products );

			$arrayID = array();
			foreach ( $products as $object ) {
				array_push( $arrayID, $object->post_id );
			}
			$arrayID = array_unique( $arrayID );

			$newObjects = array();
			foreach ( $arrayID as $id ) {
				foreach ( $products as $object ) {
					if ( $object->post_id == $id ) {
						array_push($newObjects, $object);
						break;
					}
				}
			}

			foreach ( $newObjects as $product ) {
				if ( $i >= $to )  break;

				setup_postdata( $product );
				$_product = wc_get_product( $product->post_id );

				$result['html'] .= '<li>';
				$result['html'] .= '<a href="'.get_the_permalink($product->post_id).'" title="'.get_the_title($product->post_id).'" class="product-list-image">';
				$result['html'] .= ( get_the_post_thumbnail( $product->post_id ) ) ? get_the_post_thumbnail( $product->post_id ) : wc_placeholder_img( $size = 'shop_thumbnail' );
				$result['html'] .='</a>';
				$result['html'] .= '<p class="product-title"><a href="'.get_the_permalink($product->post_id).'" title="'.get_the_title($product->post_id).'">'.get_the_title($product->post_id).'</a></p>';
				$result['html'] .= '<div class="price">'.$_product->get_price_html().'</div>';
				$result['html'] .= '</li>';

				$i++;
			}
		}

		wp_reset_postdata();
		$result['html'] .= '</ul></div>';

		// ! Get posts results
		$args = array(
			's'                   => $s,
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $to,
		);

		if ( etheme_get_option( 'search_ajax_page' ) ) {
			$args['post_type'] = array( 'post', 'page' );
		}

		if ( $_REQUEST['cat'] && ! etheme_get_option( 'search_ajax_product' ) ) $args['category_name'] = $_REQUEST['cat'];

		$posts = ( etheme_get_option( 'search_ajax_post' ) ) ? get_posts( $args ) : '' ;

		if ( !empty( $posts ) ) {
			ob_start();
			foreach ( $posts as $post ) {
				?>
				<li>
					<a href="<?php echo get_the_permalink( $post->ID ); ?>" class="post-list-image"><?php echo get_the_post_thumbnail( $post->ID );?></a>
					<p class="post-title"><a href="<?php echo get_the_permalink( $post->ID ); ?>"><?php echo get_the_title( $post->ID ) ?></a></p>
					<span class="post-date"><?php echo get_the_date( '',$post->ID ); ?></span>
				</li>

				<?php
			}

			$result['status'] = 'success';
			$result['html'] .= '<div class="posts-ajax-list">';
			$result['html'] .= '<h3 class="search-results-title">' . esc_html__('Posts found', 'xstore') . '<a href="' . esc_url( home_url() ) . '/?s='. $s .'&post_type=post">' . esc_html__('View all', 'xstore' ) . '</a></h3>';
			$result['html'] .= '<ul>' . ob_get_clean() . '</ul>';
			$result['html'] .= '</div>';
		}
		wp_reset_postdata();

		if ( empty( $products ) && empty( $posts ) && empty( $regular_products ) && empty( $variations ) ) {
			$result['status'] = 'error';
			$result['html'] = '<div class="empty-category-block">';
			$result['html'] .= '<h3>' . esc_html__( 'No results were found', 'xstore' ) . '</h3>';
			$result['html'] .= '<p class="not-found-info">' . esc_html__( 'We invite you to get acquainted with an assortment of our site. Surely you can find something for yourself!', 'xstore' ). '</p>';
			$result['html'] .= '</div>';
		}

		wp_reset_postdata();

	}

	echo json_encode($result);
	die();
}

/*Import Export Woo Users*/
add_filter( 'hf_csv_customer_post_columns', 'empdev_hf_csv_customer_post_columns' );

function empdev_hf_csv_customer_post_columns( $columns ){
	$add_columns = array( 'birthday' => 'birthday' );

	return array_merge( $columns, $add_columns );
}

add_filter ( 'hf_customer_csv_export_data', 'empdev_hf_customer_csv_export_data' );

function empdev_hf_customer_csv_export_data( $customer_data ){
	foreach ($customer_data as $key => $val ){
		if ( $key == 'ID' ){
			$id = $val;
			$birthday = empdev_insert_colum_data($id);
			$customer_data['birthday'] = $birthday;
		}
	}

	return $customer_data;
}

//var_dump(insert_colum_data(2085));

function empdev_insert_colum_data( $user_id ) {
	//$user = get_userdata( $user_id );
	global $wpuef_option_model,$wpuef_shortcodes;
	$extra_fields = $wpuef_option_model->get_option('json_fields_string');
	$column_name = 'c2';
//	$user_id = 2085;

	//return $extra_fields;

	if($extra_fields)
		foreach($extra_fields->fields as $extra_field)
			//if($column_name == 'wpuef_'.$extra_field->cid)
			return $wpuef_shortcodes->wpuef_show_field_value(array('user_id' => $user_id, 'field_id' => $column_name ));

//	return $val;
}