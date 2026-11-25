<?php

use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR_DC_Section_Custom_Fields_Pro extends WPR_DC_Section_Custom_Fields {

	public function register_controls( $element ) {
		$element->add_control(
			'wpr_dc_acf_field',
			[
				'label'       => esc_html__( 'ACF Field Name', 'wpr-addons' ),
				'description' => esc_html__( 'Enter the field name from your ACF field group.', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_acf_operator',
			[
				'label'     => esc_html__( 'Condition', 'wpr-addons' ),
				'type'      => Controls_Manager::SELECT,
				'label_block' => true,
				'default'   => 'isset',
				'options'   => [
					'isset'      => esc_html__( 'Has any value', 'wpr-addons' ),
					'empty'      => esc_html__( 'Is empty', 'wpr-addons' ),
					'equals'     => esc_html__( 'Equals', 'wpr-addons' ),
					'not_equals' => esc_html__( 'Does not equal', 'wpr-addons' ),
					'contains'   => esc_html__( 'Contains', 'wpr-addons' ),
					'is_true'    => esc_html__( 'Is true', 'wpr-addons' ),
					'is_false'   => esc_html__( 'Is false', 'wpr-addons' ),
				],
				'condition' => [
					'wpr_dc_acf_field!' => '',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_acf_value',
			[
				'label'       => esc_html__( 'Value', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'condition'   => [
					'wpr_dc_acf_field!'    => '',
					'wpr_dc_acf_operator!' => [ 'isset', 'empty', 'is_true', 'is_false' ],
				],
				'render_type' => 'none',
			]
		);
	}

	public function evaluate( $settings ) {
		$field = ! empty( $settings['wpr_dc_acf_field'] ) ? trim( $settings['wpr_dc_acf_field'] ) : '';

		if ( '' === $field ) {
			return null;
		}

		if ( ! function_exists( 'get_field' ) ) {
			return null;
		}

		$value    = get_field( $field );
		$operator = ! empty( $settings['wpr_dc_acf_operator'] ) ? $settings['wpr_dc_acf_operator'] : 'isset';
		$expected = isset( $settings['wpr_dc_acf_value'] ) ? $settings['wpr_dc_acf_value'] : '';

		if ( 'is_true' === $operator ) {
			return ! empty( $value );
		}

		if ( 'is_false' === $operator ) {
			return empty( $value );
		}

		return $this->compare( $value, $operator, $expected );
	}
}
