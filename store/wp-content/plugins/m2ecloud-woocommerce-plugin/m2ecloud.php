<?php
/**
 * Plugin Name:       M2E Multichannel Connect
 * Plugin URI:        https://m2ecloud.com/walmart-ebay-amazon-woocommerce-integration-plugin-m2e
 * Description:       A complete integration for Amazon, eBay & Walmart marketplaces
 * Version:           2.1.9
 * Requires at least: 6.1
 * Requires PHP:      7.2
 * Author:            M2E Cloud
 * Author URI:        https://m2ecloud.com/about
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Woo:                  
 * WC requires at least: 8.0.0
 * WC tested up to:      9.6.0
 * Woo: 18734003266916:e57562cf78628fb7f9541339aaa44c39

 */

defined( 'ABSPATH' ) || exit;

define( 'M2ECLOUD_NAME', 'm2ecloud' );
define( 'M2ECLOUD_VERSION', '2.1.8' );

if ( ! defined( 'M2ECLOUD_PLUGIN_FILE' ) ) {
	define( 'M2ECLOUD_PLUGIN_FILE', __FILE__ );
}

require plugin_dir_path( __FILE__ ) . 'includes/class-m2ecloud-bootstrap.php';
M2ECLOUD_Bootstrap::load_classes();
M2ECLOUD_Bootstrap::register_activate();
M2ECLOUD_Bootstrap::register_deactivate();

( new M2ECLOUD_Bootstrap() )->run();
