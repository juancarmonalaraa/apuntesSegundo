<?php
namespace WprAddonsPro\Modules\CircleMenuPro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Circle_Menu_Pro',
		];
	}

	public function get_name() {
		return 'wpr-circle-menu-pro';
	}
}
