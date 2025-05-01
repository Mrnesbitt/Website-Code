<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 * @package    M2ECLOUD
 * @subpackage M2ECLOUD/includes
 */

defined( 'ABSPATH' ) || exit;

class M2ECLOUD_Bootstrap {
	public static function register_activate() {
		register_activation_hook( M2ECLOUD_PLUGIN_FILE, function () {
			require_once plugin_dir_path( M2ECLOUD_PLUGIN_FILE ) . 'admin/class-m2ecloud-activator.php';
			M2ECLOUD_Activator::activate();
		} );
	}

	public static function register_deactivate() {
		register_deactivation_hook( M2ECLOUD_PLUGIN_FILE, function () {
			require_once plugin_dir_path( M2ECLOUD_PLUGIN_FILE ) . 'admin/class-m2ecloud-deactivator.php';
			M2ECLOUD_Deactivator::deactivate();
		} );
	}

	public static function load_classes() {
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-m2ecloud-helper.php';
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-m2ecloud-facade.php';
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-m2ecloud-admin.php';
	}

	public function run() {
		$facade       = new M2ECLOUD_Facade();
		$plugin_admin = new M2ECLOUD_Admin( $facade );

		$facade->add_action( 'admin_init', function () {
			if ( get_option( 'm2ecloud_do_activation_redirect', false ) ) {
				require_once plugin_dir_path( M2ECLOUD_PLUGIN_FILE ) . 'admin/class-m2ecloud-activator.php';
				delete_option( 'm2ecloud_do_activation_redirect' );
				M2ECLOUD_Activator::install();
			}
		} );

		$facade->add_action( 'admin_enqueue_scripts', function () use ( $facade ) {
			$facade->enqueue_styles( 'admin/css/m2ecloud-admin.css' );
		} );
		$facade->add_action( 'admin_enqueue_scripts', function () use ( $facade ) {
			$facade->enqueue_scripts( 'admin/js/m2ecloud-admin.js', [ 'jquery' ] );
		} );
		$facade->add_action( 'admin_menu', [ $plugin_admin, 'init_menu' ] );
		$facade->add_filter( 'plugin_action_links_' . plugin_basename( M2ECLOUD_PLUGIN_FILE ), [$plugin_admin, 'add_plugin_links'] );

		$facade->add_action( 'before_woocommerce_init', function() {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', M2ECLOUD_PLUGIN_FILE, true );
			}
		} );

		if ( getenv( 'WORDPRESS_DEBUG' ) ) {
			$facade->add_action( 'http_api_curl', [ $plugin_admin, 'disable_ssl_check' ] );
			$facade->add_filter('http_request_host_is_external', function() {
				return true;
			} );
		}

		// Displayed order number from custom metadata
		$facade->add_filter( 'woocommerce_order_number', function( $order_id, $order ) {
			$order_number = $order->get_meta('woocommerce_order_number');
			if ( $order_number ) {
				return $order_number;
			}
			return $order_id;
		}, 10, 2 );

		$facade->run();
	}
}
