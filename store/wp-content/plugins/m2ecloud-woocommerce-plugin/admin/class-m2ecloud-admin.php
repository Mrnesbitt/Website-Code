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

class M2ECLOUD_Admin {

	/**
	 * The Facade object which interact with WordPress.
	 *
	 * @var M2ECLOUD_Facade
	 */
	private $facade;

	public function __construct( M2ECLOUD_Facade $facade ) {
		$this->facade = $facade;
	}

	// used only for a debugging on local installation
	// nosemgrep: audit.php.lang.misc.curl-ssl-unverified
//	public function disable_ssl_check( $curl ) {
//		curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 0 );
//		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
//	}

	public function check_auth() {
		if ( ! M2ECLOUD_Helper::has_auth_data() ) {
			wp_safe_redirect( site_url() . '/wc-auth/v1/authorize?' . M2ECLOUD_Helper::build_authorize_params() );
		}
	}

	public function add_plugin_links( $links ) {
		$action_links = [
			'listings' => '<a href="' . admin_url( 'admin.php?page=m2ecloud' ) . '" title="' . esc_html__( 'Manage Amazon, eBay & Walmart Listings', 'm2ecloud' ) . '">' . esc_html__( 'Manage Amazon, eBay & Walmart Listings', 'm2ecloud' ) . '</a>',
			'settings' => '<a href="' . admin_url( 'admin.php?page=m2ecloud-settings' ) . '" title="' . esc_html__( 'Settings', 'm2ecloud' ) . '">' . esc_html__( 'Settings', 'm2ecloud' ) . '</a>',
		];

		return array_merge( $action_links, $links );
	}

	public function init_menu() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		$this->facade->add_menu_item(
			__( 'M2E Multichannel Connect', 'm2ecloud' ),
			__( 'M2E Multichannel Connect', 'm2ecloud' ),
			'edit_posts',
			M2ECLOUD_NAME,
			function () {
				$this->render_page( '/app/dashboard' );
			},
			'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTUwIiBoZWlnaHQ9IjI3NSIgdmlld0JveD0iMCAwIDU1MCAyNzUiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxnIGNsaXAtcGF0aD0idXJsKCNjbGlwMF8zNzA2XzE5MzEpIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik00NzYuNyAyNC4xMTMzQzQ4NC40IDI0LjExMzMgNDkwLjIgMzAuMjEzMyA0OTAuMiAzNy44MTMzQzQ5MC4yIDQ1LjQxMzMgNDg0LjEgNTEuMjEzMyA0NzYuNyA1MS4yMTMzQzQ2OSA1MS4yMTMzIDQ2Mi45IDQ1LjQxMzMgNDYyLjkgMzcuODEzM0M0NjIuOSAzMC4yMTMzIDQ2OS4zIDI0LjExMzMgNDc2LjcgMjQuMTEzM1pNNDc2LjcgMjYuNTEzM0M0ODIuMiAyNi41MTMzIDQ4Ny4xIDMwLjgxMzMgNDg3LjcgMzUuOTEzM0M0ODYuMiAzMS45MTMzIDQ4Mi4yIDI4LjkxMzMgNDc3LjMgMjguOTEzM0M0NzEuMiAyOC45MTMzIDQ2Ni4zIDMzLjgxMzMgNDY2LjMgMzkuOTEzM0M0NjYuMyA0MC41MTMzIDQ2Ni42IDQwLjgxMzMgNDY2LjYgNDEuNDEzM0M0NjYgNDAuMjEzMyA0NjUuNyAzOS4wMTMzIDQ2NS43IDM3LjQxMzNDNDY1LjcgMzEuNDEzMyA0NzAuNiAyNi41MTMzIDQ3Ni43IDI2LjUxMzNaIiBmaWxsPSIjMjk2MkZGIi8+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNNDc4LjUgNzMuODEzMUw0NzQuOCA5Mi43MTMxTDQwNy41IDExMy43MTNMNDIwLjQgODUuNDEzMUM0MTcuMyA4My45MTMxIDQxNC45IDgxLjcxMzEgNDEyLjEgNzkuNjEzMUM0MTEuMiA3NS45MTMxIDQxMCA3My4yMTMxIDQxMCA2OS41MTMxTDM3OC44IDY5LjgxMzFMNDI1LjkgMTcuMTEzMUw0NDQuOSAyMS43MTMxQzQ3MS44IC0yLjA4Njg2IDQ5OS40IC0yLjM4Njg2IDUzMi40IDIuNTEzMTRDNTIyLjkgMzMuOTEzMSA1MTEuMyA1OS4yMTMxIDQ3OC41IDczLjgxMzFaTTUxOS4yIDExLjMxMzFDNDgwIDUuODEzMTQgNDUyLjUgMjguNzEzMSA0NDguMiAzNC44MTMxTDQyOCAzMC4yMTMxTDQwMy45IDU2LjcxMzFINDI0LjFDNDI0LjEgNTYuNzEzMSA0MTggNjIuNTEzMSA0MjEgNzIuOTEzMUM0MjkuMyA3OS45MTMxIDQzNy41IDc2LjYxMzEgNDM3LjUgNzYuNjEzMUw0MjkuMiA5NC42MTMxTDQ2NC4xIDgzLjkxMzFMNDY4LjEgNjQuNDEzMUM0NzUuNSA2My4xMTMxIDUwOC41IDQ4LjIxMzEgNTE5LjIgMTEuMzEzMVoiIGZpbGw9IiMyOTYyRkYiLz4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0yMDEuMyAyNDIuOTEzQzIzNy43IDIyOS44MTMgMzA5IDE5NC44MTMgNDA0LjUgMTAyLjQxM0MzNDEuOCAxNTEuNTEzIDI1Ni40IDIwMi4wMTMgMTc2LjIgMjI2LjQxM0MxNjQgMjMwLjExMyAxNTguOCAyMzIuMjEzIDE0OC4xIDIzNi4yMTNDMTUwLjIgMjM2LjIxMyAyMjUuMiAzMTYuMzEzIDI5MS45IDI0Ni45MTNDMzAwLjggMjQ5LjMxMyAzMTAgMjUwLjkxMyAzMTkuMSAyNTAuOTEzQzM3MC44IDI1MC45MTMgNDEyLjQgMjA5LjUxMyA0MTIuNCAxNTguMDEzQzQxMi40IDE0NC4zMTMgNDA5LjMgMTMxLjUxMyA0MDQuMSAxMTkuOTEzQzM5OCAxMjUuNzEzIDM5Mi4yIDEzMS41MTMgMzg3LjMgMTM2LjQxM0MzODkuNCAxNDMuNDEzIDM5MC43IDE1MC40MTMgMzkwLjcgMTU4LjAxM0MzOTAuNyAxOTcuMzEzIDM1OC42IDIyOS4zMTMgMzE5LjEgMjI5LjMxM0MzMDYuNiAyMjkuMzEzIDI5NC45IDIyNi4zMTMgMjg0LjUgMjIwLjUxM0MyNzQuMSAyMzguODEzIDI1My45IDI1MS4zMTMgMjMxLjMgMjUxLjMxM0MyMjAuNiAyNTAuODEzIDIxMC41IDI0OC4xMTMgMjAxLjMgMjQyLjkxM1oiIGZpbGw9IiMyOTYyRkYiLz4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0zODkuMiA4OS45MTMxQzM4OS4yIDg5LjkxMzEgMzI2LjUgMTI4LjYxMyAyMjYuNCAxNjMuNjEzQzE0OC43IDE5MS4wMTMgODAuOCAxOTMuMjEzIDAgMjAxLjExM0MxMTQuNCAyNjMuMDEzIDMxNyAxNTMuMDEzIDM4OS4yIDg5LjkxMzFaIiBmaWxsPSIjMjk2MkZGIi8+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNMTI3LjYgOTEuODEzNUMxNDIuNiA0Ny45MTM1IDE4NC41IDE3LjgxMzUgMjMyLjIgMTcuODEzNUMyNjguOSAxNy44MTM1IDMwMi42IDM1LjgxMzUgMzIzLjEgNjUuMzEzNUMzMzYuMyA2NS45MTM1IDM0OC41IDY5LjAxMzUgMzU5LjggNzQuNDEzNUwzMjcuNCA4Ny4yMTM1QzMyNC42IDg2LjkxMzUgMzIxLjkgODYuOTEzNSAzMTkuMSA4Ni45MTM1QzMxNi4zIDg2LjkxMzUgMzEzLjYgODcuMjEzNSAzMTAuOCA4Ny4yMTM1QzI5Ni4xIDU4LjkxMzUgMjY2LjEgMzkuNzEzNSAyMzEuOSAzOS43MTM1QzE4OS4xIDM5LjcxMzUgMTUzLjMgNjkuOTEzNSAxNDUgMTA5LjgxM0MxMjMuNiAxMTIuNTEzIDEwNS44IDEyNi4wMTMgOTcuMyAxNDQuNTEzTDEwOCAxNDMuOTEzQzIzMy4xIDE0My4wMTMgMzg0IDc1LjMxMzUgMzg0IDc1LjMxMzVDMjc3LjUgMTM2LjIxMyAxODkuNCAxNjcuMzEzIDY5LjUgMTcwLjQxM0M2OS43IDEzMy44MTMgOTMuNiAxMDIuNDEzIDEyNy42IDkxLjgxMzVaIiBmaWxsPSIjMjk2MkZGIi8+CjwvZz4KPGRlZnM+CjxjbGlwUGF0aCBpZD0iY2xpcDBfMzcwNl8xOTMxIj4KPHJlY3Qgd2lkdGg9IjgzOSIgaGVpZ2h0PSIyNzUiIGZpbGw9IndoaXRlIi8+CjwvY2xpcFBhdGg+CjwvZGVmcz4KPC9zdmc+Cg==',
			'56.501',
			[ $this, 'check_auth' ]
		);
	}

	private function render_page( $path ) {
		$params = [
			'woocommerce_embedded' => '1',
			'session_token' => M2ECLOUD_Helper::build_jwt_token(),
		];
		$url = M2ECLOUD_Helper::get_server_endpoint() . $path . '?' . http_build_query( $params );
		?>
		<iframe class="m2ecloud-frame" src="<?php echo esc_url( $url ); ?>" frameborder="0"></iframe>
		<?php
	}
}
