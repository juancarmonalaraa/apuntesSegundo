<?php
namespace WprAddonsPro\Modules\PasswordProtectedContentPro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Password_Protected_Content_Pro',
		];
	}

	public function get_name() {
		return 'wpr-password-protected-content-pro';
	}
}
