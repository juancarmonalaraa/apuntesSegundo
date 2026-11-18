<?php

use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR_DC_Section_Date_Time_Pro extends WPR_DC_Section_Date_Time {

	public function register_controls( $element ) {
		$element->add_control(
			'wpr_dc_date_from',
			[
				'label'       => esc_html__( 'Date From', 'wpr-addons' ),
				'description' => esc_html__( 'Leave empty for no start limit.', 'wpr-addons' ),
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => '',
				'label_block' => true,
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_date_to',
			[
				'label'       => esc_html__( 'Date To', 'wpr-addons' ),
				'description' => esc_html__( 'Leave empty for no end limit.', 'wpr-addons' ),
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => '',
				'label_block' => true,
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_weekdays',
			[
				'label'       => esc_html__( 'Days of Week', 'wpr-addons' ),
				'description' => esc_html__( 'Leave empty to skip this condition.', 'wpr-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'default'     => [],
				'options'     => [
					'1' => esc_html__( 'Monday', 'wpr-addons' ),
					'2' => esc_html__( 'Tuesday', 'wpr-addons' ),
					'3' => esc_html__( 'Wednesday', 'wpr-addons' ),
					'4' => esc_html__( 'Thursday', 'wpr-addons' ),
					'5' => esc_html__( 'Friday', 'wpr-addons' ),
					'6' => esc_html__( 'Saturday', 'wpr-addons' ),
					'7' => esc_html__( 'Sunday', 'wpr-addons' ),
				],
				'separator'   => 'before',
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_time_from',
			[
				'label'       => esc_html__( 'Time From', 'wpr-addons' ),
				'description' => esc_html__( 'Format: HH:MM (24h). Uses your site timezone.', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '09:00',
				'default'     => '',
				'separator'   => 'before',
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_time_to',
			[
				'label'       => esc_html__( 'Time To', 'wpr-addons' ),
				'description' => esc_html__( 'Supports overnight ranges, e.g. 22:00 to 06:00.', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '17:00',
				'default'     => '',
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_recurring_from',
			[
				'label'       => esc_html__( 'Recurring Period Start', 'wpr-addons' ),
				'description' => esc_html__( 'Month-Day format, e.g. 12-20 for December 20th.', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '12-20',
				'default'     => '',
				'separator'   => 'before',
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_recurring_to',
			[
				'label'       => esc_html__( 'Recurring Period End', 'wpr-addons' ),
				'description' => esc_html__( 'Supports year-wrap, e.g. 12-20 to 01-10.', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '01-10',
				'default'     => '',
				'render_type' => 'none',
			]
		);
	}

	public function evaluate( $settings ) {
		$date_from  = ! empty( $settings['wpr_dc_date_from'] ) ? $settings['wpr_dc_date_from'] : '';
		$date_to    = ! empty( $settings['wpr_dc_date_to'] ) ? $settings['wpr_dc_date_to'] : '';
		$weekdays   = ! empty( $settings['wpr_dc_weekdays'] ) ? $settings['wpr_dc_weekdays'] : [];
		$time_from  = ! empty( $settings['wpr_dc_time_from'] ) ? trim( $settings['wpr_dc_time_from'] ) : '';
		$time_to    = ! empty( $settings['wpr_dc_time_to'] ) ? trim( $settings['wpr_dc_time_to'] ) : '';
		$rec_from   = ! empty( $settings['wpr_dc_recurring_from'] ) ? trim( $settings['wpr_dc_recurring_from'] ) : '';
		$rec_to     = ! empty( $settings['wpr_dc_recurring_to'] ) ? trim( $settings['wpr_dc_recurring_to'] ) : '';

		if ( '' === $date_from && '' === $date_to && empty( $weekdays ) && '' === $time_from && '' === $time_to && '' === $rec_from && '' === $rec_to ) {
			return null;
		}

		$now = current_time( 'timestamp' );
		$results = [];

		if ( '' !== $date_from || '' !== $date_to ) {
			$results[] = $this->check_date_range( $now, $date_from, $date_to );
		}

		if ( ! empty( $weekdays ) ) {
			$current_day = (string) gmdate( 'N', $now );
			$results[] = in_array( $current_day, $weekdays, true );
		}

		if ( '' !== $time_from || '' !== $time_to ) {
			$results[] = $this->check_time_range( $now, $time_from, $time_to );
		}

		if ( '' !== $rec_from || '' !== $rec_to ) {
			$results[] = $this->check_recurring_period( $now, $rec_from, $rec_to );
		}

		if ( empty( $results ) ) {
			return null;
		}

		return ! in_array( false, $results, true );
	}

	private function check_date_range( $now, $from, $to ) {
		if ( '' !== $from ) {
			$from_ts = strtotime( $from, $now );
			if ( $from_ts && $now < $from_ts ) {
				return false;
			}
		}

		if ( '' !== $to ) {
			$to_ts = strtotime( $to, $now );
			if ( $to_ts && $now > $to_ts ) {
				return false;
			}
		}

		return true;
	}

	private function check_time_range( $now, $from, $to ) {
		$current_time = gmdate( 'H:i', $now );

		if ( '' !== $from && '' !== $to ) {
			if ( $from > $to ) {
				return $current_time >= $from || $current_time <= $to;
			}
			return $current_time >= $from && $current_time <= $to;
		}

		if ( '' !== $from ) {
			return $current_time >= $from;
		}

		if ( '' !== $to ) {
			return $current_time <= $to;
		}

		return true;
	}

	private function check_recurring_period( $now, $from, $to ) {
		$current_md = gmdate( 'm-d', $now );

		if ( '' !== $from && '' !== $to ) {
			if ( $from > $to ) {
				return $current_md >= $from || $current_md <= $to;
			}
			return $current_md >= $from && $current_md <= $to;
		}

		if ( '' !== $from ) {
			return $current_md >= $from;
		}

		if ( '' !== $to ) {
			return $current_md <= $to;
		}

		return true;
	}
}
