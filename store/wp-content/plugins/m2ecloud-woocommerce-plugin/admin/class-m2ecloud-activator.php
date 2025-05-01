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
 * @subpackage M2ECLOUD/admin
 */

defined( 'ABSPATH' ) || exit;

class M2ECLOUD_Activator {

	public static function activate() {
		$plugin_path = trailingslashit( WP_PLUGIN_DIR ) . 'woocommerce/woocommerce.php';

		if (
			in_array( $plugin_path, wp_get_active_and_valid_plugins(), true )
			|| ( is_multisite() && in_array( $plugin_path, wp_get_active_network_plugins(), true ) )
		) {

			if ( ! M2ECLOUD_Helper::has_auth_data() ) {
				add_option( 'm2ecloud_do_activation_redirect', true );
			}

			return;
		}

		wp_die(
			'M2E Multichannel Connect is not activated. Please install WooCommerce Plugin first.',
			'M2E Multichannel Connect: Plugin dependency check',
			array( 'back_link' => true )
		);
	}

	public static function install() {
		wp_safe_redirect( site_url() . '/wc-auth/v1/authorize?' . M2ECLOUD_Helper::build_authorize_params() );
	}
}
