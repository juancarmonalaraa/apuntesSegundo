<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether Royal Addons Pro should load its bundled ACF copy.
 *
 * @return bool
 */
function wpr_is_bundled_acf_enabled() {
	return 'on' === get_option( 'wpr-bundled-acf', 'on' );
}

/**
 * Whether the current site/user can change bundled ACF settings.
 *
 * @return bool
 */
function wpr_can_manage_bundled_acf_settings() {
	return current_user_can( 'manage_options' )
		&& defined( 'WPR_ADDONS_PRO_VERSION' )
		&& function_exists( 'wpr_fs' )
		&& wpr_fs()->can_use_premium_code()
		&& wpr_fs()->is_plan( 'expert' );
}

/**
 * Sanitize bundled ACF option (Expert-only; preserves value for others).
 *
 * @param mixed $value Submitted value.
 * @return string
 */
function wpr_sanitize_bundled_acf_option( $value ) {
	if ( ! wpr_can_manage_bundled_acf_settings() ) {
		return get_option( 'wpr-bundled-acf', 'on' );
	}

	return ( 'on' === $value ) ? 'on' : 'off';
}

/**
 * Redirect legacy Bundled ACF / support-tools slugs to Settings.
 */
function wpr_redirect_legacy_support_tools_page() {
	if ( ! is_admin() || ! isset( $_GET['page'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$page = sanitize_text_field( wp_unslash( $_GET['page'] ) );

	if ( in_array( $page, [ 'wpr-rea-support-tools', 'wpr-bundled-acf' ], true ) ) {
		wp_safe_redirect( admin_url( 'admin.php?page=wpr-addons&tab=wpr_tab_settings#general-tab' ) );
		exit;
	}
}
add_action( 'admin_init', 'wpr_redirect_legacy_support_tools_page' );
