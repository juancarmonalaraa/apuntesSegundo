<?php
namespace WprAddonsPro\Modules\CircleMenuPro\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Circle_Menu_Pro extends \WprAddons\Modules\CircleMenu\Widgets\Wpr_Circle_Menu {

	public function add_control_cm_trigger() {
		$this->add_control(
			'cm_trigger',
			[
				'label' => esc_html__( 'Trigger', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hover',
				'options' => [
					'hover' => esc_html__( 'Hover', 'wpr-addons' ),
					'click' => esc_html__( 'Click', 'wpr-addons' ),
				],
			]
		);
	}

	public function add_control_cm_transition() {
		$this->add_control(
			'cm_transition',
			[
				'label' => esc_html__( 'Transition', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'ease',
				'options' => [
					'ease'        => esc_html__( 'Ease', 'wpr-addons' ),
					'linear'      => esc_html__( 'Linear', 'wpr-addons' ),
					'ease-in'     => esc_html__( 'Ease In', 'wpr-addons' ),
					'ease-out'    => esc_html__( 'Ease Out', 'wpr-addons' ),
					'ease-in-out' => esc_html__( 'Ease In Out', 'wpr-addons' ),
				],
			]
		);
	}

	public function add_control_cm_hide_titles() {
		$this->add_control(
			'cm_hide_titles',
			[
				'label' => esc_html__( 'Hide Titles', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
			]
		);
	}

	public function add_control_cm_direction() {
		$this->add_control(
			'cm_direction',
			[
				'label' => esc_html__( 'Menu Direction', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'top'          => esc_html__( 'Top', 'wpr-addons' ),
					'right'        => esc_html__( 'Right', 'wpr-addons' ),
					'bottom'       => esc_html__( 'Bottom', 'wpr-addons' ),
					'left'         => esc_html__( 'Left', 'wpr-addons' ),
					'full'         => esc_html__( 'Full', 'wpr-addons' ),
					'top-left'     => esc_html__( 'Top Left', 'wpr-addons' ),
					'top-right'    => esc_html__( 'Top Right', 'wpr-addons' ),
					'top-half'     => esc_html__( 'Top Half', 'wpr-addons' ),
					'bottom-left'  => esc_html__( 'Bottom Left', 'wpr-addons' ),
					'bottom-right' => esc_html__( 'Bottom Right', 'wpr-addons' ),
					'bottom-half'  => esc_html__( 'Bottom Half', 'wpr-addons' ),
					'left-half'    => esc_html__( 'Left Half', 'wpr-addons' ),
					'right-half'   => esc_html__( 'Right Half', 'wpr-addons' ),
				],
			]
		);
	}

}
