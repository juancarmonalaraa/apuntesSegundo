<?php
namespace WprAddonsPro\Modules\UnfoldPro\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Unfold_Pro extends \WprAddons\Modules\Unfold\Widgets\Wpr_Unfold {

	public function add_control_unfold_content_type() {
		$this->add_control(
			'unfold_content_type',
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
