<?php

use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR_DC_Section_Dynamic_Tags_Pro extends WPR_DC_Section_Dynamic_Tags {

	public function register_controls( $element ) {
		$element->add_control(
			'wpr_dc_dynamic_tag',
			[
				'label'       => esc_html__( 'Dynamic Value', 'wpr-addons' ),
				'description' => esc_html__( 'Click the dynamic tags icon to select a data source.', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'dynamic'     => [
					'active' => true,
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_dynamic_operator',
			[
				'label'     => esc_html__( 'Condition', 'wpr-addons' ),
				'type'      => Controls_Manager::SELECT,
				'label_block' => true,
				'default'   => 'isset',
				'options'   => $this->get_comparison_operators(),
				'condition' => [
					'wpr_dc_dynamic_tag!' => '',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_dynamic_value',
			[
				'label'       => esc_html__( 'Expected Value', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'condition'   => [
					'wpr_dc_dynamic_tag!'      => '',
					'wpr_dc_dynamic_operator!' => [ 'isset', 'empty' ],
				],
				'render_type' => 'none',
			]
		);
	}

	public function evaluate( $settings ) {
		$tag_value = isset( $settings['wpr_dc_dynamic_tag'] ) ? $settings['wpr_dc_dynamic_tag'] : '';

		if ( '' === $tag_value && ! isset( $settings['__dynamic__']['wpr_dc_dynamic_tag'] ) ) {
			return null;
		}

		$operator = ! empty( $settings['wpr_dc_dynamic_operator'] ) ? $settings['wpr_dc_dynamic_operator'] : 'isset';
		$expected = isset( $settings['wpr_dc_dynamic_value'] ) ? $settings['wpr_dc_dynamic_value'] : '';

		return $this->compare( $tag_value, $operator, $expected );
	}
}
