<?php
namespace WprAddonsPro\Extensions;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Column_Slider_Pro {

	public static function add_control_slides_to_show( $element ) {
		$element->add_responsive_control(
			'wpr_column_slider_slides_to_show',
			[
				'label' => esc_html__( 'Slides To Show', 'wpr-addons' ),
				'description' => esc_html__( 'Number of slides visible at once. Set different values per breakpoint for responsive behavior.', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 1,
				'condition' => [
					'wpr_enable_column_slider' => 'yes',
				],
			]
		);
	}

	public static function add_control_autoplay( $element ) {
		$element->add_control(
			'wpr_enable_column_slider_autoplay',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Autoplay', 'wpr-addons' ),
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'wpr_enable_column_slider' => 'yes',
				],
			]
		);
	}

	public static function add_control_autoplay_delay( $element ) {
		$element->add_control(
			'wpr_column_slider_delay',
			[
				'label' => __( 'Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1000,
				'condition' => [
					'wpr_enable_column_slider' => 'yes',
					'wpr_enable_column_slider_autoplay' => 'yes',
				],
			]
		);
	}

	public static function add_control_slides_to_scroll( $element ) {
		$element->add_control(
			'wpr_column_slider_slides_to_scroll',
			[
				'label' => esc_html__( 'Slides To Scroll', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 1,
				'condition' => [
					'wpr_enable_column_slider' => 'yes',
				],
			]
		);
	}

	public static function add_control_pagination_type( $element ) {
		$element->add_control(
			'wpr_cs_pag_type',
			[
				'label' => esc_html__( 'Pagination Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bullets',
				'options' => [
					'bullets' => esc_html__( 'Bullets', 'wpr-addons' ),
					'fraction' => esc_html__( 'Fraction', 'wpr-addons' ),
					'progressbar' => esc_html__( 'Progressbar', 'wpr-addons' ),
				],
				'condition' => [
					'wpr_enable_column_slider' => 'yes',
					'wpr_enable_cs_pag' => 'yes',
				],
			]
		);
	}
}
