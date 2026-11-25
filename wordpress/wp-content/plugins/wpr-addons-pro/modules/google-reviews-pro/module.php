<?php
namespace WprAddonsPro\Modules\GoogleReviewsPro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Google_Reviews_Pro',
		];
	}

	public function get_name() {
		return 'wpr-google-reviews-pro';
	}
}
