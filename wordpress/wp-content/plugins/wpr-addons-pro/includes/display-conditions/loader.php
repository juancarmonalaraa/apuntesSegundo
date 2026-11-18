<?php
/**
 * Display Conditions Pro - Section Loader
 * Loads free section stubs (parent classes) then pro overrides.
 * Uses require_once so the manager's later glob won't double-load.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wpr_display_conditions_classes_loaded', function () {
	// Load free section stubs first (parent classes for pro overrides)
	$free_dir = WPR_ADDONS_PATH . 'includes/display-conditions/sections/';
	$free_files = glob( $free_dir . 'class-*.php' );

	if ( $free_files ) {
		foreach ( $free_files as $file ) {
			require_once $file;
		}
	}

	// Now load pro overrides
	$sections_dir = WPR_ADDONS_PRO_PATH . 'includes/display-conditions/sections/';
	$files = glob( $sections_dir . 'class-*-pro.php' );

	if ( $files ) {
		foreach ( $files as $file ) {
			require_once $file;
		}
	}
} );
