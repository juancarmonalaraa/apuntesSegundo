<?php

use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR_DC_Section_Random_Limits_Pro extends WPR_DC_Section_Random_Limits {

	public function register_controls( $element ) {
		$element->add_control(
			'wpr_dc_random_enabled',
			[
				'label'        => esc_html__( 'Random Display', 'wpr-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'render_type'  => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_random_chance',
			[
				'label'     => esc_html__( 'Display Probability (%)', 'wpr-addons' ),
				'description' => esc_html__( 'Percentage chance this element shows on each page load.', 'wpr-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 50,
				],
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'condition' => [
					'wpr_dc_random_enabled' => 'yes',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_limit_enabled',
			[
				'label'        => esc_html__( 'Limit Views', 'wpr-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'separator'    => 'before',
				'render_type'  => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_limit_type',
			[
				'label'     => esc_html__( 'Limit Type', 'wpr-addons' ),
				'type'      => Controls_Manager::SELECT,
				'label_block' => true,
				'default'   => 'per_user',
				'options'   => [
					'per_user' => esc_html__( 'Per User', 'wpr-addons' ),
					'per_day'  => esc_html__( 'Per Day (all visitors)', 'wpr-addons' ),
					'total'    => esc_html__( 'Total (all time)', 'wpr-addons' ),
				],
				'condition' => [
					'wpr_dc_limit_enabled' => 'yes',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_limit_count',
			[
				'label'     => esc_html__( 'Maximum Views', 'wpr-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 10,
				'min'       => 1,
				'condition' => [
					'wpr_dc_limit_enabled' => 'yes',
				],
				'render_type' => 'none',
			]
		);
	}

	public function evaluate( $settings ) {
		$random_on = ! empty( $settings['wpr_dc_random_enabled'] ) && 'yes' === $settings['wpr_dc_random_enabled'];
		$limit_on  = ! empty( $settings['wpr_dc_limit_enabled'] ) && 'yes' === $settings['wpr_dc_limit_enabled'];

		if ( ! $random_on && ! $limit_on ) {
			return null;
		}

		$results = [];

		if ( $random_on ) {
			$chance = isset( $settings['wpr_dc_random_chance']['size'] ) ? (int) $settings['wpr_dc_random_chance']['size'] : 50;
			$results[] = wp_rand( 1, 100 ) <= $chance;
		}

		if ( $limit_on ) {
			$type  = ! empty( $settings['wpr_dc_limit_type'] ) ? $settings['wpr_dc_limit_type'] : 'per_user';
			$max   = ! empty( $settings['wpr_dc_limit_count'] ) ? (int) $settings['wpr_dc_limit_count'] : 10;
			$results[] = $this->check_view_limit( $type, $max );
		}

		if ( empty( $results ) ) {
			return null;
		}

		return ! in_array( false, $results, true );
	}

	private function check_view_limit( $type, $max ) {
		$post_id = get_the_ID();

		switch ( $type ) {
			case 'per_user':
				return $this->check_per_user_limit( $post_id, $max );

			case 'per_day':
				$key     = 'wpr_dc_daily_' . $post_id;
				$current = (int) get_transient( $key );
				if ( $current >= $max ) {
					return false;
				}
				set_transient( $key, $current + 1, DAY_IN_SECONDS );
				return true;

			case 'total':
				$key     = 'wpr_dc_total_' . $post_id;
				$current = (int) get_option( $key, 0 );
				if ( $current >= $max ) {
					return false;
				}
				update_option( $key, $current + 1, false );
				return true;

			default:
				return true;
		}
	}

	private function check_per_user_limit( $post_id, $max ) {
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
			$key     = 'wpr_dc_views_' . $post_id;
			$current = (int) get_user_meta( $user_id, $key, true );

			if ( $current >= $max ) {
				return false;
			}

			update_user_meta( $user_id, $key, $current + 1 );
			return true;
		}

		$cookie_key = 'wpr_dc_views_' . $post_id;
		$current    = isset( $_COOKIE[ $cookie_key ] ) ? (int) $_COOKIE[ $cookie_key ] : 0;

		if ( $current >= $max ) {
			return false;
		}

		if ( ! headers_sent() ) {
			setcookie( $cookie_key, $current + 1, time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
		}

		return true;
	}
}
