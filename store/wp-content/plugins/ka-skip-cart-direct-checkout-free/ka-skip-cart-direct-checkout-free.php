<?php
/**
* Plugin Name: Skip Cart and Direct Checkout Free
* Requires Plugins: woocommerce
* Plugin URI: https://woocommerce.com/products/skip-cart-direct-checkout/
* Description: Merchants can allow customers to skip cart and proceed to checkout.
* Version: 1.0.0
* Author: KoalaApps
* Developed By: KoalaApps
* Author URI:https://woocommerce.com/vendor/koalaapps/
* Support: https://woocommerce.com/vendor/koalaapps/
* Domain Path: /languages
* Text Domain: ka-skip-cart-direct-checkout-free
* License: GNU General Public License v3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
* Requires Plugins: woocommerce
* WC requires at least: 4.0
* WC tested up to: 9.*.*
* Requires at least: 6.5
* Tested up to: 6.*.*
* Requires PHP: 7.4
*
* @package ka-skip-cart-direct-checkout-free
 * Woo: 18734004967919:e35b0001235eeb1f45d2aa85bee0b976

*/

// Prevent direct access
if (!defined('ABSPATH')) {
	exit;
} 

if (!class_exists('ASCDCW_FREE_Main_Class')) {
	class ASCDCW_FREE_Main_Class { 
		public function __construct() {   


			add_action('admin_init', array( $this, 'ka_cf_check_pro_version' )); //checking pro version is installed or not
			register_deactivation_hook(__FILE__, array( $this, 'ASCDCW_Delete_Plugin_Data' ));  
			register_activation_hook(__FILE__, array( $this, 'ASCDCW_ADD_Plugin_Data' ));  
			$this->ascdcw_define_constants(); 
			
			// add_action( 'plugins_loaded', array( $this, 'ascdcw_plugin_loaded' ) );//check for multisite
			add_action('before_woocommerce_init', array( $this, 'ascdcw_HPOS_Woo_Compatibility' )); //HPOS compatibility
			add_action('init', array( $this, 'ascdcw_admin_init' )); //for language add
			add_action( 'wp_ajax_ascdcw_qty_update', array( $this, 'ascdcw_update_quantity_ajax' ));//shortcode qty box update
			add_action( 'wp_ajax_nopriv_ascdcw_qty_update', array( $this, 'ascdcw_update_quantity_ajax' ) );  
		}   

		//Checking  Pro Already install or not
		public function ka_cf_check_pro_version() { 
			if (!current_user_can('activate_plugins')) {
				return;
			} 
			if (class_exists('ASCDCW_PRO_Main_Class')) { 
				deactivate_plugins(plugin_basename(__FILE__)); 
				add_action('admin_notices', array( $this, 'ka_cf_deactivate_free_version_notice' ));
			}
		}
		public function ka_cf_deactivate_free_version_notice() {
			?>
				<div class="notice notice-error is-dismissible">
					<p>
						<?php esc_html_e('Skip Cart & Direct Checkout Pro version is already installed and activated. Please deactivate the pro version before activating or installing the free version.', 'ka-skip-cart-direct-checkout-free'); ?>
					</p>
				</div>
			<?php 
			if ('' !== filter_input(INPUT_GET, 'activate', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
				unset($_GET['activate']);
			} 
		}
		//when quantity change update quantity 
		public function ascdcw_update_quantity_ajax() {   
			if ( !isset($_POST['security']) || !wp_verify_nonce(sanitize_text_field( wp_unslash($_POST['security'])), 'ascdcw_qty_update_nonce') ) {
				die('Permission Denied');
			} 
			$values = array();
			if (isset($_POST['post_data'])) {
				$post_data =esc_url_raw(wp_unslash($_POST['post_data']));
				parse_str($post_data, $values);   
			}
			$cart = $values['cart'];  
			foreach ( $cart as $cart_key => $cart_value ) {
				WC()->cart->set_quantity( $cart_key, $cart_value['qty']);//if give any version error then use refresh_totals: false as third argument
			}
			WC()->cart->calculate_totals(); 
			woocommerce_cart_totals(); 
			wp_die();
		}  
		public function ASCDCW_ADD_Plugin_Data() { 
			$options_to_add = array(
				'ascdcw_enable_checkout'                  => 'yes', 
				'ascdcw_checkout_btn_text'                => 'Checkout',
				'ascdcw_checkout_btn_text_color'          => '#ffffff',
				'ascdcw_checkout_btn_bg_color'            => '#333333',
				'ascdcw_checkout_btn_bg_color_on_hover'   => '#1a1a1a',
				'ascdcw_checkout_btn_text_color_on_hover' => '#ffffff', 
			);  
			//check if not exist then store
			foreach ($options_to_add as $option_name => $default_value) { 
				if (false === get_option($option_name)) { 
					add_option($option_name, $default_value);
				}
			}
			//store the product types
			$product_types = wc_get_product_types();
			$options       = array(); 
			foreach ($product_types as $key => $value) {
				if ('external' !== $key) {
					$options[] = 'ascdcw_' . $key . '_product';
				}
			}  
			//serialize the product type data then store in db
			$serialized_options = serialize($options);
		
			// Check if the 'ascdcw_show_checkout_btn_on' option exists, if not, add it
			if (false === get_option('ascdcw_show_checkout_btn_on')) { 
				add_option('ascdcw_show_checkout_btn_on', $serialized_options);
			}
		}
		  

		//When Plugin Deactivated Saved Data Remove
		public function ASCDCW_Delete_Plugin_Data() { 
			$options_to_delete = array(
				'ascdcw_enable_checkout', 
				'ascdcw_checkout_btn_text', 
				'ascdcw_checkout_btn_text_color', 
				'ascdcw_checkout_btn_bg_color', 
				'ascdcw_show_checkout_btn_on', 
				'ascdcw_checkout_btn_bg_color_on_hover',
				'ascdcw_checkout_btn_text_color_on_hover',
			); 
			foreach ($options_to_delete as $option) {
				delete_option($option);
			}
		} 
		// checking WooCommerce Installed/Not
		public function ascdcw_HPOS_Woo_Compatibility() {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			} 
			//check woocommerce block compatibility
			// Check if the required class exists
			if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
				// Declare compatibility for 'cart_checkout_blocks'
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
			}
		} 
		public function ascdcw_admin_init() {
			if ( defined('WC_PLUGIN_FILE') ) { 
				if (is_admin()) {
					require_once ASCDCW_DIR . 'includes/admin/ascdcw-admin-class.php'; 
				} else { 
					require_once ASCDCW_DIR . 'includes/front/ascdcw-front-class.php';
				}
				add_action('after_setup_theme', array( $this, 'ascdcw_init_lang' ));
			}
		}
		public function ascdcw_init_lang() {
			if ( function_exists('load_plugin_textdomain') ) {
				load_plugin_textdomain( 'ka-skip-cart-direct-checkout-free', false, ASCDCW_DIR . '/languages' );
			}
		}

		   
		public function ascdcw_plugin_loaded() { 
			// Check the installation of WooCommerce module if it is not a multi site.
			if ( ! is_multisite() && ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
				add_action( 'admin_notices', array( $this, 'ascdcw_show_error_msg' ));
			}
		}

		public function ascdcw_show_error_msg() {
			// Deactivate the plugin.
			deactivate_plugins( __FILE__ );
			?>
		<div id="message" class="error">
			<p>
				<strong>
					<?php esc_html_e( 'Skip Cart and Direct Checkout Free plugin is inactive. WooCommerce plugin must be active in order to activate it.', 'ka-skip-cart-direct-checkout-free' ); ?>
				</strong>
			</p>
		</div>
		<?php
		}
		//Plugin Constants
		private function ascdcw_define_constants() {
			if (!defined('ASCDCW_DIR')) {
				define('ASCDCW_DIR', plugin_dir_path(__FILE__));
			}
			if (!defined('ASCDCW_URL')) {
				define('ASCDCW_URL', plugin_dir_url(__FILE__));
			}
			if (!defined('ASCDCW_VERSION')) {
				define('ASCDCW_VERSION', '1.0.0');
			}
		}
	}  
	new ASCDCW_FREE_Main_Class();
}
