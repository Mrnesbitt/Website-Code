<?php 
// Prevent direct access
if (!defined('ABSPATH')) {
	exit;
} 
if (!class_exists('ASCDCW_Front_Class')) {
	class ASCDCW_Front_Class { 
		public function __construct() {   
			add_action('wp_enqueue_scripts', array( $this, 'front_assets_enqueue' ));   
			add_action('woocommerce_after_shop_loop_item', array( $this, 'ascdcw_checkout_button_shop_page' ), 20);
			add_action('woocommerce_after_add_to_cart_button', array( $this, 'ascdcw_checkout_buttons_single_page' )); 
		}    

		//Checkout Button On Shop Page
		public function ascdcw_checkout_button_shop_page() {
			// Get the settings
			$enable_checkout_check = get_option('ascdcw_enable_checkout', 'no');  
			$show_check_btn_on     = get_option('ascdcw_show_checkout_btn_on', array());
			$checkout_btn_text     = get_option('ascdcw_checkout_btn_text', 'Checkout'); 
			if (!is_array($show_check_btn_on)) {
				$show_check_btn_on = maybe_unserialize($show_check_btn_on); 
			} 
			if ('yes' === $enable_checkout_check && !empty($show_check_btn_on) ) {
				global $product; 
				if (is_a($product, 'WC_Product')) {
					$product_type = $product->get_type();   
					//this is shop page
					if (is_shop() || is_single()) { 
						$current_product_id = $product->get_id();
						$checkout_url       = wc_get_checkout_url();
						
						// Get the product object for the specific product ID
						$current_product = wc_get_product($current_product_id);
						
						// Check if the product is of type "simple" and ensure it has a regular price
						if ('simple' === $current_product->get_type() && in_array('ascdcw_simple_product', $show_check_btn_on) && !empty($current_product->get_regular_price()) && $current_product->is_in_stock()) {
							?>
							<a href="<?php echo esc_url($checkout_url); ?>?add-to-cart=<?php echo esc_attr($current_product_id); ?>" class="ascdcw-checkout-button ascdcw-shop-page-btn button alt">
								<?php echo esc_html($checkout_btn_text ? $checkout_btn_text : 'Checkout'); ?>
							</a>
							<?php
						} 
						
						
					}
				}
					
			}
		} 
				//Show Checkout Button on Single page, For All types of Product Simple,Variable & Grouped
		public function ascdcw_checkout_buttons_single_page() { 
			// Get the settings
			$enable_checkout_check            = get_option('ascdcw_enable_checkout', 'no');  
			$show_check_btn_on                = get_option('ascdcw_show_checkout_btn_on', array());
			$checkout_btn_text                = get_option('ascdcw_checkout_btn_text', 'Checkout');   
			$show_check_btn_on_selected_roles = get_option('ascdcw_show_checkout_btn_on_user_roles', array());
			$saved_categories                 = get_option('ascdcw_show_checkout_btn_on_product_categories', array());
			$saved_tags                       = get_option('ascdcw_show_checkout_btn_on_selected_tags', array());
			$show_on_product_detail_page      = get_option('ascdcw_show_on_product_or_listing_page', array());
			//ensure it is array
			$show_check_btn_on                = is_array($show_check_btn_on) ? $show_check_btn_on : maybe_unserialize($show_check_btn_on);
			$show_check_btn_on_selected_roles = is_array($show_check_btn_on_selected_roles) ? $show_check_btn_on_selected_roles : maybe_unserialize($show_check_btn_on_selected_roles);
			$saved_categories                 = is_array($saved_categories) ? $saved_categories : maybe_unserialize($saved_categories);
			$saved_tags                       = is_array($saved_tags) ? $saved_tags : maybe_unserialize($saved_tags);
			$show_on_product_detail_page      = is_array($show_on_product_detail_page) ? $show_on_product_detail_page : maybe_unserialize($show_on_product_detail_page);
			/*
			*Show Checkout Button on Selected Roles
			*If No Roles Show to All Roles
			*if Roles Selected show only to that Roles
			*/
			// Default role for guest users
			$adcdcw_guest = 'guest'; 
			$show_button  = false; //use for role based check
			if (empty($show_check_btn_on_selected_roles)) {
				$show_button = true; 
			} 
			// if user loggedin
			if (is_user_logged_in()) { 
				$user       = wp_get_current_user();
				$user_roles = (array) $user->roles; 
				foreach ($show_check_btn_on_selected_roles as $value) {
					if (in_array($value, $user_roles, true)) {
						$show_button = true;
						break;
					}
				}
			} else {//if not loggedin
				foreach ($show_check_btn_on_selected_roles as $value) {
					if (in_array($value, (array) $adcdcw_guest, true)) {
						$show_button = true;
						break;
					}
				}
			} //end roles based logic for single page
			if ('yes' === $enable_checkout_check && $show_button) {
				global $product; 
				if (is_a($product, 'WC_Product')) {
					$product_type = $product->get_type();    
					if (is_single()) {
		
						// Logic for showing the checkout button for listing page
						if (in_array('ascdcwduct_page', $show_on_product_detail_page, true)) {
							return; // Exit directly if above condition satisfied
						}
						//check categories, if stored categories & current products categories match then return the,means do not show the button there
						$product_id         = $product->get_id();
						$terms              = get_the_terms($product_id, 'product_cat'); 
						$product_categories = array(); 
						//categories Logic
						if ($terms && !is_wp_error($terms)) {
							foreach ($terms as $term) {
								$product_categories[] = $term->term_id;
							}
						}   
						// Product tag logic
						$tags         = get_the_terms($product_id, 'product_tag'); 
						$product_tags = array();
						if ($tags && !is_wp_error($tags)) {
							foreach ($tags as $tag) {
								$product_tags[] = 'ascdcw_' . $tag->name . '_tag';
							}
						}  
						// Check if the product belongs to the saved categories or tags
						if (
							( !empty($saved_categories) && array_intersect($product_categories, $saved_categories) ) ||
							( !empty($saved_tags) && array_intersect($product_tags, $saved_tags) )
						) {
							return; // Exit if the product matches saved categories or tags
						}
		
						//end categories & tags logic
						if (in_array("ascdcw_{$product_type}_product", $show_check_btn_on)) {
							?>
						<?php
							if (is_a($product, 'WC_Product') && is_single()) {
								// Initialize button variables
								$button_class  = '';
								$product_id    = $product->get_id();
								$addtocart_url = wc_get_checkout_url();
							
								// Check product type
								if ($product->is_type('simple')) {
									$addtocart_url .= '?add-to-cart=' . $product_id;
									$button_class   = 'ascdcw-checkout-button ascdcw-simple-product button alt';
								} elseif ($product->is_type('variable')) {
									$addtocart_url .= '?add-to-cart=' . $product_id;
									$button_class   = 'ascdcw-checkout-button ascdcw-variable-product button alt';
								} elseif ($product->is_type('grouped')) {
									$addtocart_url = wc_get_checkout_url(); // Base URL, specific products are added via JS
									$button_class  = 'ascdcw-checkout-button ascdcw-grouped-product button alt';
								} else {
									return; // Do not display the button for other product types
								}
							
								echo '<a href="' . esc_url($addtocart_url) . '" class="' . esc_attr($button_class) . '">' . esc_html($checkout_btn_text) . '</a>';
								wp_enqueue_script('ascdcw-checkout', ASCDCW_URL . 'assets/js/ascdcw-detail-page.js', array( 'jquery' ), ASCDCW_VERSION, true);
								wp_localize_script('ascdcw-checkout', 'ascdcw_vars', array(
									'addtocart_url' => $addtocart_url,
									'product_id'    => $product_id,
									'product_type'  => $product->get_type(),
								));
							}
						}
					}
							 
				}
						 
			}
		}
		//Assets Enqueue
		public function front_assets_enqueue() { 
			wp_enqueue_style(
				'ascdcw_styles_front',
				ASCDCW_URL . 'assets/css/ascdcw-front.css',
				array(),
				ASCDCW_VERSION
			);
			$checkout_text_color       = get_option('ascdcw_checkout_btn_text_color', '#ffffff');  
			$checkout_bg_color         = get_option('ascdcw_checkout_btn_bg_color', '#333333');   
			$checkout_text_color_hover = get_option('ascdcw_checkout_btn_text_color_on_hover', '#ffffff');   
			$checkout_bg_color_hover   = get_option('ascdcw_checkout_btn_bg_color_on_hover', '#1a1a1a');    
			$ascdcw_inline_style       = "
                .ascdcw-checkout-button{color:{$checkout_text_color}!important;background-color:{$checkout_bg_color} !important;}
                .ascdcw-checkout-button:hover{color:{$checkout_text_color_hover}!important;background-color:{$checkout_bg_color_hover} !important;}
				  body.et_divi_theme .ascdcw-checkout-button{border: 2px solid {$checkout_text_color} !important;}
				  body.et_divi_theme .ascdcw-checkout-button:hover{border: 2px solid {$checkout_bg_color_hover} !important;} 

				  @media only screen and (max-width: 600px) {
					body.theme-woodmart.woocommerce-shop .ascdcw-shop-page-btn { 
						 background: url('" . ASCDCW_URL . "assets/images/checkout.png') no-repeat center center !important;
						background-size: 50% !important; /* Adjust size as needed */
						border: none !important;
						outline: none !important;
						box-shadow: unset !important; 
						border-radius: 50% !important;
						width: 71px !important;
						height: 34px !important;
						margin-right: 11px !important;
						text-indent: -9999px; */
						overflow: hidden !important;
						display: inline-block !important;
					}}
					@media only screen and (min-width: 601px) and (max-width: 1024px) {
					body.theme-woodmart.woocommerce-shop .ascdcw-shop-page-btn { 
						/* Add your custom styles for tablet view here */
						background: url('" . ASCDCW_URL . "assets/images/checkout.png') no-repeat center center !important;
						background-size: 50% !important; /* Adjust size as needed */
						border: none !important;
						outline: none !important;
						box-shadow: unset !important; 
						border-radius: 50% !important;
						width: 71px !important;
						height: 34px !important;
						margin-right: 11px !important;
						text-indent: -9999px; 
						overflow: hidden !important;
						display: inline-block !important;
					}
					}}
                
            ";
			wp_add_inline_style('ascdcw_styles_front', $ascdcw_inline_style);  
			wp_enqueue_script(
				'ascdcw_front-script',
				ASCDCW_URL . 'assets/js/ascdcw-front.js',
				array( 'jquery' ),
				ASCDCW_VERSION,
				true
			); 
			// if (is_checkout()) {
			//  wp_enqueue_script(
			//      'ascdcw_checkout-referesh-script',
			//      ASCDCW_URL . 'assets/js/ascdcw-checkout-referesh.js',
			//      array( 'jquery' ),
			//      ASCDCW_VERSION,
			//      true
			//  ); 
			// } 
		}
	}
	new ASCDCW_Front_Class();
} 