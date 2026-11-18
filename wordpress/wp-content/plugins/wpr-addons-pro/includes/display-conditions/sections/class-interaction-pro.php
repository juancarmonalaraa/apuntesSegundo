<?php

use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR_DC_Section_Interaction_Pro extends WPR_DC_Section_Interaction {

	public function register_controls( $element ) {
		$element->add_control(
			'wpr_dc_interaction_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Show or toggle this element when a visitor clicks, hovers, or touches another element. This works on the frontend only (JavaScript).', 'wpr-addons' ),
				'content_classes' => 'elementor-descriptor',
			]
		);

		$element->add_control(
			'wpr_dc_interaction_type',
			[
				'label'   => esc_html__( 'Trigger Action', 'wpr-addons' ),
				'type'    => Controls_Manager::SELECT,
				'label_block' => true,
				'default' => '',
				'options' => [
					''         => esc_html__( 'Not Set', 'wpr-addons' ),
					'click'    => esc_html__( 'Click', 'wpr-addons' ),
					'hover'    => esc_html__( 'Hover', 'wpr-addons' ),
					'dblclick' => esc_html__( 'Double Click', 'wpr-addons' ),
					'touch'    => esc_html__( 'Touch', 'wpr-addons' ),
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_interaction_target',
			[
				'label'       => esc_html__( 'Trigger Element', 'wpr-addons' ),
				'description' => esc_html__( 'CSS selector of the element that triggers visibility, e.g. .my-button or #open-panel.', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'condition'   => [
					'wpr_dc_interaction_type!' => '',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_interaction_animation',
			[
				'label'     => esc_html__( 'Animation', 'wpr-addons' ),
				'type'      => Controls_Manager::SELECT,
				'label_block' => true,
				'default'   => 'fadeIn',
				'options'   => [
					'none'      => esc_html__( 'None (instant)', 'wpr-addons' ),
					'fadeIn'    => esc_html__( 'Fade In', 'wpr-addons' ),
					'slideDown' => esc_html__( 'Slide Down', 'wpr-addons' ),
					'slideUp'   => esc_html__( 'Slide Up', 'wpr-addons' ),
				],
				'condition' => [
					'wpr_dc_interaction_type!' => '',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_interaction_speed',
			[
				'label'     => esc_html__( 'Animation Speed (ms)', 'wpr-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 300,
				'min'       => 0,
				'max'       => 3000,
				'step'      => 50,
				'condition' => [
					'wpr_dc_interaction_type!'      => '',
					'wpr_dc_interaction_animation!' => 'none',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_interaction_toggle',
			[
				'label'        => esc_html__( 'Toggle On/Off', 'wpr-addons' ),
				'description'  => esc_html__( 'Trigger again to hide the element.', 'wpr-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'condition'    => [
					'wpr_dc_interaction_type!' => '',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_interaction_auto_show',
			[
				'label'        => esc_html__( 'Show on Page Load', 'wpr-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'condition'    => [
					'wpr_dc_interaction_type!' => '',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_interaction_auto_delay',
			[
				'label'     => esc_html__( 'Auto-show Delay (ms)', 'wpr-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 0,
				'min'       => 0,
				'max'       => 10000,
				'step'      => 100,
				'condition' => [
					'wpr_dc_interaction_type!'      => '',
					'wpr_dc_interaction_auto_show'  => 'yes',
				],
				'render_type' => 'none',
			]
		);
	}

	public function evaluate( $settings ) {
		return null;
	}
}
