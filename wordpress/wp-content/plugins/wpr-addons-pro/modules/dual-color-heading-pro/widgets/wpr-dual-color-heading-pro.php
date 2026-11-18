<?php
namespace WprAddonsPro\Modules\DualColorHeadingPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Dual_Color_Heading_Pro extends \WprAddons\Modules\DualColorHeading\Widgets\Wpr_Dual_Color_Heading {
	public function add_control_text_shadow_type() {
		$this->add_control(
			'text_shadow_type',
			[
				'label' => esc_html__( 'Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'long' => esc_html__( 'Long', 'wpr-addons' ),
				],
			]
		);
	}
}