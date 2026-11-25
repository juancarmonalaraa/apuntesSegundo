<?php

use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR_DC_Section_User_Profile_Pro extends WPR_DC_Section_User_Profile {

	public function register_controls( $element ) {
		$element->add_control(
			'wpr_dc_user_meta_key',
			[
				'label'       => esc_html__( 'Profile Field Name', 'wpr-addons' ),
				'description' => esc_html__( 'Enter the user meta key, e.g. first_name, billing_city.', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_user_meta_operator',
			[
				'label'     => esc_html__( 'Condition', 'wpr-addons' ),
				'type'      => Controls_Manager::SELECT,
				'label_block' => true,
				'default'   => 'isset',
				'options'   => $this->get_comparison_operators(),
				'condition' => [
					'wpr_dc_user_meta_key!' => '',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_user_meta_value',
			[
				'label'       => esc_html__( 'Value', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'condition'   => [
					'wpr_dc_user_meta_key!'      => '',
					'wpr_dc_user_meta_operator!' => [ 'isset', 'empty' ],
				],
				'render_type' => 'none',
			]
		);
	}

	public function evaluate( $settings ) {
		$meta_key = ! empty( $settings['wpr_dc_user_meta_key'] ) ? trim( $settings['wpr_dc_user_meta_key'] ) : '';

		if ( '' === $meta_key ) {
			return null;
		}

		if ( ! is_user_logged_in() ) {
			return false;
		}

		$user_id  = get_current_user_id();
		$actual   = get_user_meta( $user_id, $meta_key, true );
		$operator = ! empty( $settings['wpr_dc_user_meta_operator'] ) ? $settings['wpr_dc_user_meta_operator'] : 'isset';
		$expected = isset( $settings['wpr_dc_user_meta_value'] ) ? $settings['wpr_dc_user_meta_value'] : '';

		return $this->compare( $actual, $operator, $expected );
	}
}
