<?php
// Prevent direct access
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('ASCDCW_Admin_Class')) {
	class ASCDCW_Admin_Class { 
		public function __construct() {  
			add_filter('woocommerce_settings_tabs_array', array( $this, 'ASCDCW_Settings_Tabs' ), 100);
			add_action('woocommerce_settings_ascdcw-settings', array( $this, 'ASCDCW_Settings_Tabs_Sections' ), 10);
			add_action('woocommerce_settings_save_ascdcw-settings', array( $this, 'ASCDCW_Settings_Save' ), 10);  
			add_action('admin_enqueue_scripts', array( $this, 'enqueue_ascdcw_assets' )); 
		}

		//register tab in woo setting page
		public function ASCDCW_Settings_Tabs( $tabs ) {
			$tabs['ascdcw-settings'] = __('Direct Checkout', 'ka-skip-cart-direct-checkout-free');
			return $tabs;
		} 
		//show tabs in settings
		public function ASCDCW_Settings_Tabs_Sections() {
			global $current_section;
			$tab_id = 'ascdcw-settings';
			$tabs   = array(
				'' => __('General', 'ka-skip-cart-direct-checkout-free'),
				'ascdcw_premium' => __('Pro Features', 'ka-skip-cart-direct-checkout-free'),
			);
			?>
			<ul class="subsubsub"> 
			<?php
			$array_keys = array_keys( $tabs );  
			foreach ( $tabs as $id => $label ) {
				echo '<li><a href="' . esc_url(admin_url( 'admin.php?page=wc-settings&tab=' . $tab_id . '&section=' . sanitize_title( $id ) )) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . esc_html($label) . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
			}
			echo '</ul><br class="clear" />'; 
			$APSW_Settings_Input_Fields = $this->ASCDCW_Settings_Fields();
			woocommerce_admin_fields($APSW_Settings_Input_Fields);
		}
		//show the tabs content
		public function ASCDCW_Settings_Fields() {
			global $current_section; 
			// Define valid sections & tabs for url validations
			$valid_sections = array(
				'',
				'ascdcw_premium',
			); 
			if (!in_array($current_section, $valid_sections, true)) {
				wp_safe_redirect(admin_url('admin.php?page=wc-settings&tab=ascdcw-settings'));
				exit;
			} 
			$ASCDCW_Inputs_Fields = array(); 
			if ('' === $current_section ) {  
				$product_types = wc_get_product_types();
				$options       = array();

				foreach ($product_types as $key => $value) {
					// Check if the product type is not 'external'
					if ('external' !== $key) {
						$options[ 'ascdcw_' . $key . '_product' ] = $value;
					}
				} 
				$ASCDCW_Inputs_Fields = array(
					//direct checkout settings tabs & sections
					array(
						'title' => __('Direct Checkout General Settings', 'ka-skip-cart-direct-checkout-free'),
						'type'  => 'title',
						'id'    => 'section_one',
					),
					array(
						'name'     => __('Checkout Button', 'ka-skip-cart-direct-checkout-free'),
						'desc'     => __('Enable Checkout Button', 'ka-skip-cart-direct-checkout-free'),
						'id'       => 'ascdcw_enable_checkout',
						'type'     => 'checkbox',
						'desc_tip' => true, 
					),
					array(
						'name'     => __('Checkout Button Text', 'ka-skip-cart-direct-checkout-free'),
						'desc'     =>__('Enter Checkout Button Text', 'ka-skip-cart-direct-checkout-free'),
						'id'       => 'ascdcw_checkout_btn_text',
						'type'     => 'text',
						'desc_tip' => true,
					),
					array(
						'name'     => __('Checkout Button Text Color', 'ka-skip-cart-direct-checkout-free'),
						'desc'     => 'Select Checkout Button Text Color',
						'type'     => 'color',
						'desc_tip' => true,
						'id'       => 'ascdcw_checkout_btn_text_color',
					),
					array(
						'name'     => __('Checkout Button Text Color on Hover', 'ka-skip-cart-direct-checkout-free'),
						'desc'     => 'Select Checkout Button Text Color on Hover',
						'type'     => 'color',
						'desc_tip' => true,
						'id'       => 'ascdcw_checkout_btn_text_color_on_hover',
					),
					array(
						'name'     => __('Checkout Button Background Color', 'ka-skip-cart-direct-checkout-free'),
						'desc'     => 'Select Checkout Button Background Color',
						'type'     => 'color',
						'desc_tip' => true,
						'id'       => 'ascdcw_checkout_btn_bg_color',
					), 
					array(
						'name'     => __('Checkout Button Background Color on Hover', 'ka-skip-cart-direct-checkout-free'),
						'desc'     => 'Select Checkout Button Background Color on Hover',
						'type'     => 'color',
						'desc_tip' => true,
						'id'       => 'ascdcw_checkout_btn_bg_color_on_hover',
					), 
					array(
						'name'     => __('Show Checkout Button On', 'ka-skip-cart-direct-checkout-free'),
						'desc'     => 'Show Checkout Button On Products Types like Simple, Variable etc',
						'type'     => 'multiselect',
						'options'  => $options,
						'desc_tip' => true,
						'id'       => 'ascdcw_show_checkout_btn_on',
						'class'    => 'select-two-option',
					),
					array(
						'type' => 'sectionend',
						'id'   =>'section_one', 
					), 
				);  
			} elseif ('ascdcw_premium' === $current_section) { 
				?>
				<style> 
					.woocommerce-save-button{display: none;} 
				</style>
			
				<?php
				ob_start();
				?>
				
				<div class="ascdcw-table-wrapper">
					<table class="ascdcw-table">
						<thead>
							<tr>
								<th style="text-align: left;"><?php esc_html_e('Features', 'ka-skip-cart-direct-checkout-free'); ?></th>
								<th><?php esc_html_e('Free Version', 'ka-skip-cart-direct-checkout-free'); ?></th>
								<th><?php esc_html_e('Pro Version', 'ka-skip-cart-direct-checkout-free'); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php esc_html_e('Skip Cart & Direct Checkout', 'ka-skip-cart-direct-checkout-free'); ?></td>
								<td><span class="ascdcw-check">&#10003;</span></td>
								<td><span class="ascdcw-check">&#10003;</span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Customizeable Direct Checkout Button', 'ka-skip-cart-direct-checkout-free'); ?></td>
								<td><span class="ascdcw-check">&#10003;</span></td>
								<td><span class="ascdcw-check">&#10003;</span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Show or Hide direct checkout button by product types', 'ka-skip-cart-direct-checkout-free'); ?></td>
								<td><span class="ascdcw-check">&#10003;</span></td>
								<td><span class="ascdcw-check">&#10003;</span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Show or Hide direct checkout button by products', 'ka-skip-cart-direct-checkout-free'); ?></td>
								<td><span class="ascdcw-cross">&#10007;</span></td>
								<td><span class="ascdcw-check">&#10003;</span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Show or Hide direct checkout button by categories', 'ka-skip-cart-direct-checkout-free'); ?></td>
								<td><span class="ascdcw-cross">&#10007;</span></td>
								<td><span class="ascdcw-check">&#10003;</span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Show or Hide direct checkout button by pages', 'ka-skip-cart-direct-checkout-free'); ?></td>
								<td><span class="ascdcw-cross">&#10007;</span></td>
								<td><span class="ascdcw-check">&#10003;</span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Show or Hide direct checkout button by tages', 'ka-skip-cart-direct-checkout-free'); ?></td>
								<td><span class="ascdcw-cross">&#10007;</span></td>
								<td><span class="ascdcw-check">&#10003;</span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Show or Hide direct checkout button by user roles', 'ka-skip-cart-direct-checkout-free'); ?></td>
								<td><span class="ascdcw-cross">&#10007;</span></td>
								<td><span class="ascdcw-check">&#10003;</span></td>
							</tr>
				
						</tbody>
					</table>
				</div>

				<div class="ascdcw-pro-upgrade">
					<a href="https://woocommerce.com/products/skip-cart-direct-checkout/" target="_blank">
						<?php esc_html_e('Get Pro', 'ka-skip-cart-direct-checkout-free'); ?>
					</a>
				</div>
			
				<?php
				$table_content = ob_get_clean(); 
				$ASCDCW_Inputs_Fields = array(
					array(
						'title' => __('&nbsp;', 'ka-skip-cart-direct-checkout-free'),
						'type'  => 'title',
						'desc'  => $table_content,
						'id'    => 'ascdcw_premium_features',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'ascdcw_premium_features', 
					), 
				);
			} 
			

			return $ASCDCW_Inputs_Fields; 
		}
		//save settings data
		public function ASCDCW_Settings_Save() {
			$settings_fields = $this->ASCDCW_Settings_Fields();
			WC_Admin_Settings::save_fields($settings_fields);
		} 
		//assets files
		public function enqueue_ascdcw_assets() {  
			global $pagenow, $current_section; 
			$current_page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
			$current_tab  = filter_input(INPUT_GET, 'tab', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
			if ('admin.php' === $pagenow && 
				'wc-settings' === $current_page && 
				'ascdcw-settings' === $current_tab && 
				( empty($current_section) || 'ascdcw_premium' === $current_section )) { 
					// 'ascdcw_premium' === $current_section
					wp_enqueue_style('select2-css', ASCDCW_URL . 'assets/css/select2.min.css', array(), '4.1.0-beta.1');
					wp_enqueue_script('select2-js', ASCDCW_URL . 'assets/js/select2.min.js', array( 'jquery' ), '4.1.0-beta.1', true);
					wp_enqueue_script(
						'ascdcw_scripts_admin',
						ASCDCW_URL . 'assets/js/ascdcw-admin.js',
						array( 'jquery' ),
						ASCDCW_VERSION,
						true
					); 
					$stored_product_types = get_option('ascdcw_show_checkout_btn_on'); 
				if (!is_array($stored_product_types)) { 
					$stored_product_types_done = maybe_unserialize($stored_product_types); 
					if (!is_array($stored_product_types_done)) {
						$stored_product_types_done = array();
					}
				} else {
					$stored_product_types_done = $stored_product_types;
				} 
					wp_localize_script('ascdcw_scripts_admin', 'my_data', array(
						'stored_product_types' => $stored_product_types_done,
					));
					
					wp_enqueue_style(
						'ascdcw_styles_admin',
						ASCDCW_URL . 'assets/css/ascdcw-admin.css',
						array(),
						ASCDCW_VERSION
					); 
			} 
		} 
	}
	new ASCDCW_Admin_Class();
}