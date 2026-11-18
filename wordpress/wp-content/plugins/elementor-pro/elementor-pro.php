<?php
/**
 * Plugin Name: Elementor Pro
 * Description: Elevate your designs and unlock the full power of the Atomic Editor. Gain access to dozens of Pro widgets, Website Templates, Theme Builder, Pop Ups, Forms, reusable Components, and WooCommerce building capabilities.
 * Plugin URI: https://go.elementor.com/wp-dash-wp-plugins-author-uri/
 * Version: 4.4.1
 * Author: Elementor.com
 * Author URI: https://go.elementor.com/wp-dash-wp-plugins-author-uri/
 * Requires PHP: 7.4
 * Requires at least: 6.8
 * Requires Plugins: elementor
 * Elementor tested up to: 4.4.0
 * Text Domain: elementor-pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'ELEMENTOR_PRO_VERSION', '4.4.1' );

/**
 * All versions should be `major.minor`, without patch, in order to compare them properly.
 * Therefore, we can't set a patch version as a requirement.
 * (e.g. Core 3.15.0-beta1 and Core 3.15.0-cloud2 should be fine when requiring 3.15, while
 * requiring 3.15.2 is not allowed)
 */
define( 'ELEMENTOR_PRO_REQUIRED_CORE_VERSION', '4.2' );
define( 'ELEMENTOR_PRO_RECOMMENDED_CORE_VERSION', '4.4' );

define( 'ELEMENTOR_PRO__FILE__', __FILE__ );
define( 'ELEMENTOR_PRO_PLUGIN_BASE', plugin_basename( ELEMENTOR_PRO__FILE__ ) );
define( 'ELEMENTOR_PRO_PATH', plugin_dir_path( ELEMENTOR_PRO__FILE__ ) );
define( 'ELEMENTOR_PRO_ASSETS_PATH', ELEMENTOR_PRO_PATH . 'assets/' );
define( 'ELEMENTOR_PRO_MODULES_PATH', ELEMENTOR_PRO_PATH . 'modules/' );
define( 'ELEMENTOR_PRO_URL', plugins_url( '/', ELEMENTOR_PRO__FILE__ ) );
define( 'ELEMENTOR_PRO_ASSETS_URL', ELEMENTOR_PRO_URL . 'assets/' );
define( 'ELEMENTOR_PRO_MODULES_URL', ELEMENTOR_PRO_URL . 'modules/' );

/**
 * Initialize Environment Efficiently with Atomic Loop Flags & License Bypasses
 */
function elementor_pro_initialize_local_environment() {
	$current_license_key = get_option( 'elementor_pro_license_key' );
	
	if ( $current_license_key !== 'activated' ) {
		$timeout = strtotime('+1 year', current_time('timestamp'));
		
		$features_list = [
			'custom-attributes', 'custom_code', 'custom-css', 'global-css', 'display-conditions',
			'dynamic-tags-acf', 'dynamic-tags-pods', 'dynamic-tags-toolset', 'element-manager-permissions',
			'global-widget', 'editor_comments', 'stripe-button', 'popup', 'role-manager',
			'woocommerce-menu-cart', 'product-single', 'product-archive', 'settings-woocommerce-pages',
			'settings-woocommerce-notices', 'dynamic-tags-wc', 'atomic-custom-attributes', 'theme-builder',
			'form-submissions', 'akismet', 'activity-log', 'cf7db', 'transitions', 'size-variable',
			'notes', 'atomic-custom-css', 'atomic-editor', 'atomic-elements', 'atomic-loop', 'atomic-loop-builder'
		];

		$response_payload = [
			'success'  => true, 
			'license'  => 'valid', 
			'status'   => 'valid',
			'expires'  => '10.10.2030',
			'tier'     => 'agency',
			'features' => $features_list
		];

		$license_data = [
			'timeout' => $timeout,
			'value'   => json_encode($response_payload)
		];

		delete_option( '_elementor_pro_license_data' );
		delete_transient( 'elementor_pro_remote_info_api_data' );

		update_option( 'elementor_pro_license_key', 'activated' );
		update_option( 'elementor_pro_license_status', 'valid' );
		update_option( '_elementor_pro_license_v2_data', $license_data );
	}
}
add_action( 'init', 'elementor_pro_initialize_local_environment' );

/**
 * Bypass Core Connection Meta Requirements
 */
add_filter( 'elementor/connect/additional-connect-info', '__return_empty_array', 999 );

/**
 * Filter External Validation & Remote Handlers with Complete Feature Registry
 */
add_action( 'plugins_loaded', function () {
	add_filter( 'pre_http_request', function ( $pre, $parsed_args, $url ) {
		if ( strpos( $url, 'my.elementor.com/api/v2/lic' ) !== false || strpos( $url, 'elementor.com/api/v1/license' ) !== false ) {
			$features_list = [
				'custom-attributes', 'custom_code', 'custom-css', 'global-css', 'display-conditions',
				'dynamic-tags-acf', 'dynamic-tags-pods', 'dynamic-tags-toolset', 'element-manager-permissions',
				'global-widget', 'editor_comments', 'stripe-button', 'popup', 'role-manager',
				'woocommerce-menu-cart', 'product-single', 'product-archive', 'settings-woocommerce-pages',
				'settings-woocommerce-notices', 'dynamic-tags-wc', 'atomic-custom-attributes', 'theme-builder',
				'form-submissions', 'akismet', 'activity-log', 'cf7db', 'transitions', 'size-variable',
				'notes', 'atomic-custom-css', 'atomic-editor', 'atomic-elements', 'atomic-loop', 'atomic-loop-builder'
			];

			return [
				'response' => [ 'code' => 200, 'message' => 'OK' ],
				'body'     => json_encode([
					'success'  => true, 
					'license'  => 'valid', 
					'status'   => 'valid',
					'expires'  => '10.10.2030',
					'tier'     => 'agency',
					'features' => $features_list
				])
			];
		} 

		return $pre;
	}, 10, 3 );
});

$bootstrap_file = ELEMENTOR_PRO_MODULES_PATH . 'admin-notices/bootstrap.php';
if ( file_exists( $bootstrap_file ) ) {
	require_once $bootstrap_file;
}

\ElementorPro\Modules\AdminNotices\Classes\Upgrade_Recommendation_Dismissal::register_dismiss_handlers();

/**
 * Load gettext translate for our text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function elementor_pro_load_plugin() {
	if ( ! did_action( 'elementor/loaded' ) ) {
		\ElementorPro\Modules\AdminNotices\Bootstrap::register_from_bootstrap( \ElementorPro\Modules\AdminNotices\Bootstrap::NOTICE_FAIL_LOAD );

		return;
	}

	$core_version = ELEMENTOR_VERSION;
	$core_version_required = ELEMENTOR_PRO_REQUIRED_CORE_VERSION;
	$core_version_recommended = ELEMENTOR_PRO_RECOMMENDED_CORE_VERSION;

	if ( ! elementor_pro_compare_major_version( $core_version, $core_version_required, '>=' ) ) {
		\ElementorPro\Modules\AdminNotices\Bootstrap::register_from_bootstrap( \ElementorPro\Modules\AdminNotices\Bootstrap::NOTICE_OUT_OF_DATE );

		return;
	}

	if ( ! elementor_pro_compare_major_version( $core_version, $core_version_recommended, '>=' ) ) {
		\ElementorPro\Modules\AdminNotices\Bootstrap::register_from_bootstrap( \ElementorPro\Modules\AdminNotices\Bootstrap::NOTICE_UPGRADE_RECOMMENDATION );
	}

	require ELEMENTOR_PRO_PATH . 'plugin.php';
}

function elementor_pro_compare_major_version( $left, $right, $operator ) {
	$pattern = '/^(\d+\.\d+).*/';
	$replace = '$1.0';

	$left  = preg_replace( $pattern, $replace, $left );
	$right = preg_replace( $pattern, $replace, $right );

	return version_compare( $left, $right, $operator );
}

add_action( 'plugins_loaded', 'elementor_pro_load_plugin' );

function print_error( $message ) {
	$renderer = new \ElementorPro\Modules\AdminNotices\Components\Notice_Renderer();
	$renderer->print_legacy_error( (string) $message );
}

function elementor_pro_fail_load() {
	( new \ElementorPro\Modules\AdminNotices\Notices\Fail_Load_Notice() )->print();
}

function elementor_pro_fail_load_out_of_date() {
	( new \ElementorPro\Modules\AdminNotices\Notices\Out_Of_Date_Notice() )->print();
}

function elementor_pro_admin_notice_upgrade_recommendation() {
	( new \ElementorPro\Modules\AdminNotices\Notices\Upgrade_Recommendation_Notice() )->print();
}

if ( ! function_exists( '_is_elementor_installed' ) ) {

	function _is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}
