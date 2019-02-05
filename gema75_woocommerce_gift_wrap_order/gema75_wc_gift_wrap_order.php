<?php
/*
Plugin Name: Woocommerce Gift Wrap Order - by Gema75
Plugin URI: http://codecanyon.net/user/Gema75
Description: This plugin shows Gift Wrap on the Order.
Version: 4.1
Author: Gema75
Author URI: http://codecanyon.net/user/Gema75
*/

//load translatable files
add_action('plugins_loaded', 'gema75_wc_giftwrap_language');

function gema75_wc_giftwrap_language() {
	load_plugin_textdomain( 'gema75-wc-giftwrap', false, dirname( plugin_basename( __FILE__ ) ) . '/language/' );
}


if (in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	define( 'GEMA75_GIFT_PLUGIN_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );

	add_action('admin_menu', 'register_woocommerce_gift_submenu');

	function register_woocommerce_gift_submenu() {
		add_submenu_page( 'woocommerce', 'Gift Wrap Order', 'Gift Wrap Order', 'manage_options', 'gift-settings-page', 'woocommerce_gift_submenu_callback' ); 
	}

	/*
	* ADMIN OPTION PAGE
	*/
	function woocommerce_gift_submenu_callback() {
		
		//user submitted the category 
		if(isset($_POST['gema75_wc_product_categories_list']) ) {
			//save as an option the ID of the category holding the gift wrap products
			update_option('gema75_giftwrap_giftscategoryid',$_POST['gema75_wc_product_categories_list'] );
		}

		//get saved giftwrap category ID
		$gema75_giftwrap_giftcategory  = get_option('gema75_giftwrap_giftscategoryid',true);

		?>
		
		<h3>Gift Wrap Order - Settings Page</h3>
		
		<form  action="" method="post">
		<table class="form-table">
			<tbody>

				<tr valign="top">
					<th scope="row" class="titledesc"><label for="woocommerce_gifts_select_product_id">Gift Wrap Category</label></th>
					<td class="forminp">
						
						<?php
							$args_woocommerce_categories = array(
							  'orderby' => 'id',
							  'order' => 'ASC',
							  'taxonomy' => 'product_cat',
							  'hide_empty' => '0',
							  'hierarchical' => '1'
							  );
			  
			  $gema75_gifts_woocommerce_categories = get_categories($args_woocommerce_categories);
			  
			  if (count($gema75_gifts_woocommerce_categories) > 0 && !isset($gema75_gifts_woocommerce_categories['errors'])){
			  
					  echo  '<select name="gema75_wc_product_categories_list"  class="gema75_wc_product_categories"> '; 
					  //loop all categories 

					  foreach($gema75_gifts_woocommerce_categories as $gema75_gifts_woocommerce_category) { 
					  
							if($gema75_giftwrap_giftcategory == $gema75_gifts_woocommerce_category->cat_ID) {
								$checked=' selected="selected" ';
							}else{
								$checked='';
							}
					  
							echo '<option ' . $checked . '  value="'.$gema75_gifts_woocommerce_category->cat_ID.'"> '. $gema75_gifts_woocommerce_category->cat_name .'</option>';
						
						}
					  echo '<select>' ;
			  
			  } else {
			  
					echo 'No categories found on Woocommerce. Please create a category for the gift wraps and then select it here. ';
			  }
			  
			  ?>

					<p class="description"> Select the category with the gift wraps .</p><br>
					</td>
				</tr>			
			  

				<tr valign="top">
					<th scope="row" class="titledesc"><label for="woocommerce_gifts_select_product_id">Hide Products</label></th>
					<td class="forminp">
			  

			<?php
			
				if (isset($_POST['hideprod'])) {
					
					//save if hide/show products of the gift wrap category
					//will be used to set the option on the SELECT input
					if ($_POST['hideprod']=='yes') {
						update_option('gema75_giftwrap_hide_products','yes');
					}
					
					if ($_POST['hideprod']=='no') {
						update_option('gema75_giftwrap_hide_products','no');
					}					
				}
				$gema75_giftwrap_hide_or_not='';
				//get saved option 
				$gema75_giftwrap_hide_or_not = get_option('gema75_giftwrap_hide_products',true) ;
				
			?>
				<select  name="hideprod"  > 
					<option value="yes" <?php if($gema75_giftwrap_hide_or_not=='yes'){ echo 'selected="selected"';} ?> > Yes </option>
					<option value="no"  <?php if($gema75_giftwrap_hide_or_not=='no'){ echo 'selected="selected"';} ?> > No </option>
				</select>
				
				
			<?php

			if (isset($_POST['hideprod'])) {

				
			
				//get the slug of the selected category holding the gift wrap products
				$gema75_slug_of_gifts_category = get_term( $gema75_giftwrap_giftcategory , 'product_cat' ); 
				
				$gema75_get_all_giftproducts_args = array(
					'posts_per_page' => '-1',
					'post_count' => -1,
					'post_type' =>'product',
					'tax_query' => array(
						array(
							'taxonomy' => 'product_cat',
							'field' => 'slug',
							'terms' =>  $gema75_slug_of_gifts_category->slug
						)),
				);
				
				$gema75_get_all_giftproducts_query = new WP_Query( $gema75_get_all_giftproducts_args );
				global $post;

				//loop all products in the category selected and make them hidden
				while ( $gema75_get_all_giftproducts_query->have_posts() ) {

					$gema75_get_all_giftproducts_query->the_post();
					
					$gema75_product_terms = wp_get_post_terms($post->ID , 'product_visibility');
					
					if ($_POST['hideprod']=='yes') {
						
						//there are 2 terms if product is set to HIDDEN 
						//exclude-from-catalog exclude-from-search

						$tags_for_hidden = array (
							'exclude-from-catalog',
							'exclude-from-search'
						);
							
						wp_set_post_terms( $post->ID, $tags_for_hidden, 'product_visibility' );

						//update_post_meta( $post->ID, '_visibility', 'hidden' );
						update_post_meta( $post->ID, '_sold_individually', 'yes' );
					}  
					
					if ($_POST['hideprod']=='no') {
						$tags_for_visible = array();
						wp_set_post_terms( $post->ID, $tags_for_visible, 'product_visibility' );
						
						//update_post_meta( $post->ID, '_visibility', 'catalog' );
						update_post_meta( $post->ID, '_sold_individually', 'yes' );

					}
					
				}  //end while 

				
				//save position
				if(isset($_POST['gema75_gift_wrap_position_value'])){
					if($_POST['gema75_gift_wrap_position_value'] !='' ){
						update_option('gema75_giftwrap_position',$_POST['gema75_gift_wrap_position_value']);
					}
				}
				
				//custom "Available gifts" 
				if(isset($_POST['gema75_gift_wrap_custom_available_text'])){
					update_option('gema75_giftwrap_available_gifts_option',$_POST['gema75_gift_wrap_custom_available_text']);
				}else{
					update_option('gema75_giftwrap_available_gifts_option','Available gift wraps ');
				}
				
				//custom wrap message
				if(isset($_POST['gema75_gift_wrap_custom_gift_wrap_message_text'])){
					update_option('gema75_giftwrap_giftwrap_message_option',$_POST['gema75_gift_wrap_custom_gift_wrap_message_text']);
				}else{
					update_option('gema75_giftwrap_giftwrap_message_option','Add gift wrap message');
				}

				
				//custom button text
				if(isset($_POST['gema75_gift_wrap_custom_add_gift_wrap_to_order_text'])){
					if($_POST['gema75_gift_wrap_custom_add_gift_wrap_to_order_text']!=''){
						update_option('gema75_gift_wrap_custom_add_gift_wrap_to_order_option',$_POST['gema75_gift_wrap_custom_add_gift_wrap_to_order_text']);
					}else{
						update_option('gema75_gift_wrap_custom_add_gift_wrap_to_order_option','Add gift wrap to order');
					}
				}else{
					update_option('gema75_gift_wrap_custom_add_gift_wrap_to_order_option','Add gift wrap to order');
				}	
				
				
				//show gift message box
				if(isset($_POST['gema75_giftwrap_show_gift_message'])){
					if($_POST['gema75_giftwrap_show_gift_message']=='yes'){
						update_option('gema75_giftwrap_show_gift_box','yes');
					}
					
					if($_POST['gema75_giftwrap_show_gift_message']=='no'){
						update_option('gema75_giftwrap_show_gift_box','no');
					}
				}else{
					update_option('gema75_giftwrap_show_gift_box','yes');
				}
					
			}  //end if isset $_POST

			
			  
			  ?>
			  
					<p class="description"> Should the gift wrap products be hidden on the shop page </p>
					</td>

				<tr valign="top">
					<th scope="row" class="titledesc"><label for="woocommerce_gifts_show_notes_box">Show gift message box</label></th>
					<td class="forminp">
					
					<?php
						//get saved option 
						$gema75_giftwrap_showbox = get_option('gema75_giftwrap_show_gift_box',true);
					?> 
					
					<select name="gema75_giftwrap_show_gift_message">
						<option value="yes" <?php selected($gema75_giftwrap_showbox,'yes'); ?> >Yes</option>
						<option value="no"  <?php selected($gema75_giftwrap_showbox,'no'); ?> >No</option>
					</select>
					<p class="description">Show or not the gift message box</p><br>
					</td>
				</tr>		
					
					
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc"><label for="woocommerce_gifts_select_product_id"><h3>Position Settings </h3></label></th>
				</tr>	
				
				<tr valign="top">
					<th scope="row" class="titledesc"><label for="woocommerce_gifts_select_product_id">Position of Gift Wrap Box</label></th>
					<td class="forminp">
					
						<?php
							
							//get saved option 
							$gema75_giftwrap_selpos = get_option('gema75_giftwrap_position',true) ;
							
							//set default value 
							if($gema75_giftwrap_selpos=='' || $gema75_giftwrap_selpos == false ){
								$gema75_giftwrap_selpos = 'woocommerce_before_cart';
							}

						?> 

						<select  name="gema75_gift_wrap_position_value"  > 
							<option value="woocommerce_before_cart" <?php if($gema75_giftwrap_selpos=='woocommerce_before_cart'){ echo 'selected="selected"';} ?> > Before Cart</option>
							<option value="woocommerce_after_cart_table"  <?php if($gema75_giftwrap_selpos=='woocommerce_after_cart_table'){ echo 'selected="selected"';} ?> > After Cart Table</option>
							<option value="woocommerce_after_cart_totals"  <?php if($gema75_giftwrap_selpos=='woocommerce_after_cart_totals'){ echo 'selected="selected"';} ?> > After Cart Totals</option>
							<option value="woocommerce_after_cart"  <?php if($gema75_giftwrap_selpos=='woocommerce_after_cart'){ echo 'selected="selected"';} ?> > After Cart</option>
						</select>		
						<p class="description">Select the position to display the Gift Wrap Box</p>
					</td>
				</tr>	
				<tr valign="top">
					<th scope="row" class="titledesc"><label ><h3>Custom Text Settings </h3></label></th>
				</tr>	
				<tr valign="top">
					<th scope="row" class="titledesc"><label >Available Gift Wrap Text :</label></th>
					<td class="forminp">	
						<textarea name="gema75_gift_wrap_custom_available_text" style="width:500px" placeholder="Write the text that will be shown for AVAILABLE GIFT WRAPS"><?php echo get_option('gema75_giftwrap_available_gifts_option',true);  ?></textarea>
						<p class="description"> Insert the text for :Available Gift Wrap </p>
					</td>
				</tr>	
				<tr valign="top">
					<th scope="row" class="titledesc"><label >Gift Wrap Message Text :</label></th>
					<td class="forminp">
						<textarea name="gema75_gift_wrap_custom_gift_wrap_message_text" style="width:500px" placeholder="Write the text that will be the title of GIFT WRAP MESSAGE"><?php echo get_option('gema75_giftwrap_giftwrap_message_option',true); ?></textarea>		
						<p class="description"> Insert the text for :Gift Wrap Message </p>
					</td>		
				</tr>	

				<tr valign="top">
					<th scope="row" class="titledesc"><label >Add Gift Wrap to Order Button Text :</label></th>
					<td class="forminp">
						<textarea name="gema75_gift_wrap_custom_add_gift_wrap_to_order_text" style="width:500px" placeholder="Write the text of the ADD GIFT WRAP button"><?php echo get_option('gema75_gift_wrap_custom_add_gift_wrap_to_order_option',true);?></textarea>		
						<p class="description"> Insert the text for :Add Gift Wrap to Order Button</p>
					</td>		
				</tr>	
			</tbody>
		</table>		
			<p class="submit">
				<input name="gema75_gift_save" class="button-primary" type="submit" value="Save changes">        			        
			</p>
		</form>	

		<?php
		}

		
		
	///////////////////////////////////// FRONT END 	 FRONT END 	 FRONT END 	 FRONT END 	 FRONT END 	 FRONT END 	 FRONT END 	 FRONT END 	 ////////////////////////////////


	

		//Enqueue frontend scripts/css
		add_action( 'wp_enqueue_scripts', 'gema75_gift_load_css_scripts' );
		function gema75_gift_load_css_scripts() {

			wp_enqueue_style( 'gema75-gift', GEMA75_GIFT_PLUGIN_URL .'/style.css' );

		}


		//get selected option for position 
		$gema75_giftwrap_selpos = get_option('gema75_giftwrap_position',true) ;
		
		switch($gema75_giftwrap_selpos){
			case 'woocommerce_before_cart':
				add_action('woocommerce_before_cart' , 'gema75_add_gift_wrap_to_order');
				break;
			
			case 'woocommerce_after_cart_table':
				add_action('woocommerce_after_cart_table' , 'gema75_add_gift_wrap_to_order');
				break;
			
			case 'woocommerce_after_cart':
				add_action('woocommerce_after_cart' , 'gema75_add_gift_wrap_to_order');
				break;
			
			default :
				add_action('woocommerce_after_cart_totals' , 'gema75_add_gift_wrap_to_order');
				break;
		}
		//edited --->
	    add_action('woocommerce_after_checkout_form' , 'gema75_add_gift_wrap_to_order', 100);

		function gema75_add_gift_wrap_to_order(){
			
			global  $woocommerce;
			
			//get selected category from options 
			$gema75_giftwrap_wrapcategory_id =  get_option('gema75_giftwrap_giftscategoryid',true); 
			//get the slug of the selected category holding the gift wrap products
			$gema75_slug_of_gifts_category = get_term( $gema75_giftwrap_wrapcategory_id , 'product_cat' );
			
			//get if show or not gift message box 
			$gema75_giftwrap_showbox = get_option('gema75_giftwrap_show_gift_box',true);
			

			$args = array(
				'posts_per_page' => '-1',
				'post_count' => -1,
				'post_type' =>'product',
				'tax_query' => array(
					array(
						'taxonomy' => 'product_cat',
						'field' => 'slug',
						'terms' =>  $gema75_slug_of_gifts_category->slug
						
					)),
			);
			
			
			$products_of_giftwrap = get_posts($args);
			
			
			if ( count($products_of_giftwrap) > 0 ) {	?>
				<div id="gemagiftfront">
				<p><?php _e(get_option('gema75_giftwrap_available_gifts_option',true),'gema75-wc-giftwrap'); ?></p>
				
					<form method="post" action="">
					
						<ul>
					
						<?php
						foreach( $products_of_giftwrap  as $product_of_giftwrap ){
						
							$get_product_of_giftwrap = new WC_Product($product_of_giftwrap->ID );
							
							$product_of_giftwrap_price  = $get_product_of_giftwrap->get_price_html();
							
							$product_image= wp_get_attachment_image(get_post_thumbnail_id($product_of_giftwrap->ID),'shop_catalog');
								echo '<li><input type="radio" name="giftwrapproduct" value="'.$product_of_giftwrap->ID.'"><br>' . $product_image . ''.$product_of_giftwrap->post_title .'<br> ' . $product_of_giftwrap_price .' </li>';
						   
						}
						
						?>
						
						</ul>
					
						<div style="clear:both;width:100%;position:relative;"></div>
						
						<?php if($gema75_giftwrap_showbox=='yes'){ ?>
						
							<div class="gema75_wc_giftwrap_notes_container">

								<label for="gema75_wc_giftwrap_notes"><?php _e(get_option('gema75_giftwrap_giftwrap_message_option',true),'gema75-wc-giftwrap');?></label>
								<textarea name="gema75_wc_giftwrap_notes" id="gema75_wc_giftwrap_notes" cols="30" rows="5" ><?php
										if(isset($woocommerce->session->gema75_giftwrap_notes)){
											echo $woocommerce->session->gema75_giftwrap_notes ;
										}
									?></textarea>
							</div>
						
						<?php } ?>
						
						<div style="clear:both;width:100%;position:relative;"></div>
						
						<input type="submit" class="button" name="giftwrap_btn" value="<?php _e(get_option('gema75_gift_wrap_custom_add_gift_wrap_to_order_option',true),'gema75-wc-giftwrap');?>"> 

					</form>
				</div>

			<?php

			}

		}



		add_action('wp_loaded','add_giftwrap_to_cart');
		function add_giftwrap_to_cart(){
			global $woocommerce;

			//get if show or not gift message box 
			$gema75_giftwrap_showbox = get_option('gema75_giftwrap_show_gift_box',true);
			
			$giftwrap = isset($_POST['giftwrapproduct']) && !empty($_POST['giftwrapproduct']) ? (int)$_POST['giftwrapproduct'] : false;
			
			if($giftwrap && isset($_POST['giftwrap_btn'])){

				//get saved giftwrap category ID
				$gema75_giftwrap_giftcategory  = get_option('gema75_giftwrap_giftscategoryid',true);
			
				// add giftwrap item to basket
				$giftwrap_found = false;
				
				// add to session
				if($giftwrap > 0){
					$woocommerce->session->ok_gift = $giftwrap;

					//check if giftwrap product already in cart
					if( sizeof($woocommerce->cart->get_cart()) > 0){

						foreach($woocommerce->cart->get_cart() as $cart_item_key=>$values){
		 
							$_product = $values['data'];
							
							//get ID-s of product in cart 
							
							
							//find all product categories in cart
							$terms = get_the_terms($_product->get_id() , 'product_cat' );
							if($terms ){
								foreach ($terms as $term) {
									
									if($term->term_id == $gema75_giftwrap_giftcategory){
										$giftwrap_found = true;
									}
									
								}
							}
							
							//show message to user ... you already have a giftwrap on cart 
							if($giftwrap_found){
								wc_add_notice( 'There is already a wrap product on cart. Remove it and add any other you like ','notice' );
							}
							
							//set gift wrap notes
							if($gema75_giftwrap_showbox=='yes'){
								if(isset($_POST['gema75_wc_giftwrap_notes'])){
									$woocommerce->session->gema75_giftwrap_notes = $_POST['gema75_wc_giftwrap_notes'];
								}
							}

						}
						
							
						// if  giftwrap product not found, add it
						if(!$giftwrap_found){
							$woocommerce->cart->add_to_cart($giftwrap);
							
							//set gift wrap notes
							if($gema75_giftwrap_showbox=='yes'){
								if(isset($_POST['gema75_wc_giftwrap_notes'])){
									$woocommerce->session->gema75_giftwrap_notes = $_POST['gema75_wc_giftwrap_notes'];
								}
							}
						}
							
					}else{
						// if no giftwrap products in cart, add it
						$woocommerce->cart->add_to_cart($giftwrap);
						
						//set gift wrap notes
						if($gema75_giftwrap_showbox=='yes'){
							if(isset($_POST['gema75_wc_giftwrap_notes'])){
								$woocommerce->session->gema75_giftwrap_notes = $_POST['gema75_wc_giftwrap_notes'];
							}
						}
						
					}
				}
				
			}

		}


		/**
		 * Update the order meta with field value
		 **/

		add_action('woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta');

		function my_custom_checkout_field_update_order_meta( $order_id ) {

			global $woocommerce;
			
			if(isset($woocommerce->session->gema75_giftwrap_notes)){
			
				if($woocommerce->session->gema75_giftwrap_notes !='' ){
				
					update_post_meta( $order_id, 'Giftwrap Notes', esc_attr($woocommerce->session->gema75_giftwrap_notes));
					
				}	
				
				//clear the message from session
				$woocommerce->session->gema75_giftwrap_notes = '';
			}
		}



} //If woocommerce is active

