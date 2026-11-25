<?php

use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR_DC_Section_Visitor_Location_Pro extends WPR_DC_Section_Visitor_Location {

	public function register_controls( $element ) {
		$element->add_control(
			'wpr_dc_ip_addresses',
			[
				'label'       => esc_html__( 'IP Addresses', 'wpr-addons' ),
				'description' => esc_html__( 'One IP address per line.', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => '',
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_referrer_domains',
			[
				'label'       => esc_html__( 'Referral Domains', 'wpr-addons' ),
				'description' => esc_html__( 'One domain per line, e.g. google.com.', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => '',
				'separator'   => 'before',
				'render_type' => 'none',
			]
		);

		$has_geoip = function_exists( 'geoip_detect2_get_info_from_current_ip' );

		$element->add_control(
			'wpr_dc_geo_countries',
			[
				'label'       => esc_html__( 'Visitor Country', 'wpr-addons' ),
				'description' => $has_geoip
					? esc_html__( 'Comma-separated ISO country codes, e.g. US, GB, DE.', 'wpr-addons' )
					: esc_html__( 'Requires the GeoIP Detection plugin. Install it to use this feature.', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'separator'   => 'before',
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_geo_cities',
			[
				'label'       => esc_html__( 'Visitor City', 'wpr-addons' ),
				'description' => esc_html__( 'Comma-separated city names.', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'render_type' => 'none',
			]
		);
	}

	public function evaluate( $settings ) {
		$ips       = ! empty( $settings['wpr_dc_ip_addresses'] ) ? trim( $settings['wpr_dc_ip_addresses'] ) : '';
		$referrers = ! empty( $settings['wpr_dc_referrer_domains'] ) ? trim( $settings['wpr_dc_referrer_domains'] ) : '';
		$countries = ! empty( $settings['wpr_dc_geo_countries'] ) ? trim( $settings['wpr_dc_geo_countries'] ) : '';
		$cities    = ! empty( $settings['wpr_dc_geo_cities'] ) ? trim( $settings['wpr_dc_geo_cities'] ) : '';

		if ( '' === $ips && '' === $referrers && '' === $countries && '' === $cities ) {
			return null;
		}

		$results = [];

		if ( '' !== $ips ) {
			$allowed = array_filter( array_map( 'trim', preg_split( '/[\r\n]+/', $ips ) ) );
			$visitor_ip = $this->get_visitor_ip();
			$results[] = in_array( $visitor_ip, $allowed, true );
		}

		if ( '' !== $referrers ) {
			$results[] = $this->check_referrer( $referrers );
		}

		if ( '' !== $countries || '' !== $cities ) {
			$geo_result = $this->check_geo( $countries, $cities );
			if ( null !== $geo_result ) {
				$results[] = $geo_result;
			}
		}

		if ( empty( $results ) ) {
			return null;
		}

		return ! in_array( false, $results, true );
	}

	private function get_visitor_ip() {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
		}
		if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ips = explode( ',', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) );
			return trim( $ips[0] );
		}
		return isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	}

	private function check_referrer( $domains_str ) {
		$referer = wp_get_referer();

		if ( ! $referer ) {
			$referer = isset( $_SERVER['HTTP_REFERER'] ) ? sanitize_url( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : '';
		}

		if ( empty( $referer ) ) {
			return false;
		}

		$ref_host = wp_parse_url( $referer, PHP_URL_HOST );
		if ( ! $ref_host ) {
			return false;
		}

		$ref_host = strtolower( $ref_host );
		$domains  = array_filter( array_map( 'trim', preg_split( '/[\r\n]+/', strtolower( $domains_str ) ) ) );

		foreach ( $domains as $domain ) {
			if ( $ref_host === $domain || substr( $ref_host, -strlen( '.' . $domain ) ) === '.' . $domain ) {
				return true;
			}
		}

		return false;
	}

	private function check_geo( $countries_str, $cities_str ) {
		if ( ! function_exists( 'geoip_detect2_get_info_from_current_ip' ) ) {
			return null;
		}

		$info = geoip_detect2_get_info_from_current_ip();

		if ( ! $info || true === $info->isEmpty ) {
			return null;
		}

		$results = [];

		if ( '' !== $countries_str ) {
			$allowed   = array_map( 'strtoupper', array_map( 'trim', explode( ',', $countries_str ) ) );
			$visitor   = strtoupper( $info->country->isoCode );
			$results[] = in_array( $visitor, $allowed, true );
		}

		if ( '' !== $cities_str ) {
			$allowed   = array_map( 'strtolower', array_map( 'trim', explode( ',', $cities_str ) ) );
			$visitor   = strtolower( $info->city->name );
			$results[] = in_array( $visitor, $allowed, true );
		}

		if ( empty( $results ) ) {
			return null;
		}

		return ! in_array( false, $results, true );
	}
}
