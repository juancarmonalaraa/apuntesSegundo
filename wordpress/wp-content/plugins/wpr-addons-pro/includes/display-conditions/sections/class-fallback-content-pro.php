<?php

use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR_DC_Section_Fallback_Content_Pro extends WPR_DC_Section_Fallback_Content {

	public function register_controls( $element ) {
		$element->add_control(
			'wpr_dc_fallback_enabled',
			[
				'label'        => esc_html__( 'Show Fallback Content', 'wpr-addons' ),
				'description'  => esc_html__( 'Display alternative content when this element is hidden by visibility conditions.', 'wpr-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'render_type'  => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_fallback_type',
			[
				'label'     => esc_html__( 'Fallback Type', 'wpr-addons' ),
				'type'      => Controls_Manager::SELECT,
				'label_block' => true,
				'default'   => 'text',
				'options'   => [
					'text'     => esc_html__( 'Custom Text', 'wpr-addons' ),
					'template' => esc_html__( 'Elementor Template', 'wpr-addons' ),
				],
				'condition' => [
					'wpr_dc_fallback_enabled' => 'yes',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_fallback_text',
			[
				'label'     => esc_html__( 'Fallback Text', 'wpr-addons' ),
				'type'      => Controls_Manager::WYSIWYG,
				'default'   => '',
				'condition' => [
					'wpr_dc_fallback_enabled' => 'yes',
					'wpr_dc_fallback_type'    => 'text',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_fallback_template',
			[
				'label'       => esc_html__( 'Choose Template', 'wpr-addons' ),
				'type'        => 'wpr-ajax-select2',
				'options'     => 'ajaxselect2/get_elementor_templates',
				'label_block' => true,
				'condition'   => [
					'wpr_dc_fallback_enabled' => 'yes',
					'wpr_dc_fallback_type'    => 'template',
				],
				'render_type' => 'none',
			]
		);
	}

	public function evaluate( $settings ) {
		return null;
	}
}
