<?php

use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR_DC_Section_Url_Parameters_Pro extends WPR_DC_Section_Url_Parameters {

	public function register_controls( $element ) {
		$element->add_control(
			'wpr_dc_param_source',
			[
				'label'   => esc_html__( 'Parameter Source', 'wpr-addons' ),
				'type'    => Controls_Manager::SELECT,
				'label_block' => true,
				'default' => 'get',
				'options' => [
					'get'     => esc_html__( 'URL Query ($_GET)', 'wpr-addons' ),
					'post'    => esc_html__( 'Form Data ($_POST)', 'wpr-addons' ),
					'cookie'  => esc_html__( 'Cookie', 'wpr-addons' ),
					'request' => esc_html__( 'Any ($_REQUEST)', 'wpr-addons' ),
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_param_name',
			[
				'label'       => esc_html__( 'Parameter Name', 'wpr-addons' ),
				'description' => esc_html__( 'The name of the parameter to check.', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_param_operator',
			[
				'label'     => esc_html__( 'Condition', 'wpr-addons' ),
				'type'      => Controls_Manager::SELECT,
				'label_block' => true,
				'default'   => 'isset',
				'options'   => $this->get_comparison_operators(),
				'condition' => [
					'wpr_dc_param_name!' => '',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_param_value',
			[
				'label'       => esc_html__( 'Expected Value', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'condition'   => [
					'wpr_dc_param_name!'     => '',
					'wpr_dc_param_operator!' => [ 'isset', 'empty' ],
				],
				'render_type' => 'none',
			]
		);
	}

	public function evaluate( $settings ) {
		$name = ! empty( $settings['wpr_dc_param_name'] ) ? trim( $settings['wpr_dc_param_name'] ) : '';

		if ( '' === $name ) {
			return null;
		}

		$source   = ! empty( $settings['wpr_dc_param_source'] ) ? $settings['wpr_dc_param_source'] : 'get';
		$operator = ! empty( $settings['wpr_dc_param_operator'] ) ? $settings['wpr_dc_param_operator'] : 'isset';
		$expected = isset( $settings['wpr_dc_param_value'] ) ? $settings['wpr_dc_param_value'] : '';

		$actual = $this->get_param_value( $source, $name );

		return $this->compare( $actual, $operator, $expected );
	}

	private function get_param_value( $source, $name ) {
		switch ( $source ) {
			case 'get':
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return isset( $_GET[ $name ] ) ? sanitize_text_field( wp_unslash( $_GET[ $name ] ) ) : null;
			case 'post':
				// phpcs:ignore WordPress.Security.NonceVerification.Missing
				return isset( $_POST[ $name ] ) ? sanitize_text_field( wp_unslash( $_POST[ $name ] ) ) : null;
			case 'cookie':
				return isset( $_COOKIE[ $name ] ) ? sanitize_text_field( wp_unslash( $_COOKIE[ $name ] ) ) : null;
			case 'request':
				// phpcs:ignore WordPress.Security.NonceVerification
				return isset( $_REQUEST[ $name ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ $name ] ) ) : null;
			default:
				return null;
		}
	}
}
