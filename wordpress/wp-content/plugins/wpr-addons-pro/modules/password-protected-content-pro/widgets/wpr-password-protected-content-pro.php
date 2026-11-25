<?php
namespace WprAddonsPro\Modules\PasswordProtectedContentPro\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Password_Protected_Content_Pro extends \WprAddons\Modules\PasswordProtectedContent\Widgets\Wpr_Password_Protected_Content {

	public function add_control_content_type() {
		$this->add_control(
			'content_type',
			[
				'label' => esc_html__( 'Content Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'text',
				'options' => [
					'text'     => esc_html__( 'Text', 'wpr-addons' ),
					'template' => esc_html__( 'Elementor Template', 'wpr-addons' ),
				],
			]
		);
	}

}
