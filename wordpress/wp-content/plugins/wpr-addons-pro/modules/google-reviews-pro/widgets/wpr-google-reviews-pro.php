<?php
namespace WprAddonsPro\Modules\GoogleReviewsPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Google_Reviews_Pro extends Widget_Base {

	public function get_name() {
		return 'wpr-google-reviews-pro';
	}

	public function get_title() {
		return esc_html__( 'Google Reviews', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-star';
	}

	public function get_categories() {
		return [ 'wpr-premium-widgets' ];
	}

	public function get_keywords() {
		return [ 'royal', 'google', 'reviews', 'testimonial', 'slider' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function get_script_depends() {
		return [ 'swiper' ];
	}

	public function get_style_depends() {
		return [ 'swiper', 'e-swiper' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_google_reviews_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		// Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'data_source',
			[
				'label' => esc_html__( 'Data Source', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'google_places',
				'options' => [
					'google_places' => esc_html__( 'Google Places API', 'wpr-addons' ),
					'serpapi' => esc_html__( 'SerpApi', 'wpr-addons' ),
				],
			]
		);

		$settings_url = admin_url( 'admin.php?page=wpr-addons&tab=wpr_tab_settings' );

		if ( '' === $this->get_google_reviews_api_key() ) {
			$this->add_control(
				'gr_google_api_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf(
						__( 'Please enter <strong>Google API Key (REA)</strong> (preferred) or <strong>Google Map API Key</strong> from <br><a href="%s" target="_blank">Dashboard > %s > Settings</a> tab to get this widget working with Google Places API.', 'wpr-addons' ),
						esc_url( $settings_url ),
						Utilities::get_plugin_name()
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
					'condition' => [
						'data_source' => 'google_places',
					],
				]
			);
		}

		if ( '' === get_option( 'wpr_serpapi_key' ) ) {
			$this->add_control(
				'gr_serpapi_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf(
						__( 'Please enter <strong>SerpApi Key</strong> from <br><a href="%s" target="_blank">Dashboard > %s > Settings</a> tab to get this widget working with SerpApi.', 'wpr-addons' ),
						esc_url( $settings_url ),
						Utilities::get_plugin_name()
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
					'condition' => [
						'data_source' => 'serpapi',
					],
				]
			);
		}

		$this->add_control(
			'google_place_id',
			[
				'label' => esc_html__( 'Google Place ID', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'Insert Place ID', 'wpr-addons' ),
				'description' => sprintf(
					'%1$s <a href="%2$s" target="_blank" rel="noopener noreferrer">%3$s</a>.',
					esc_html__( 'Use Google Place ID Finder to get this value:', 'wpr-addons' ),
					esc_url( 'https://developers.google.com/maps/documentation/places/web-service/place-id' ),
					esc_html__( 'Place ID documentation', 'wpr-addons' )
				),
			]
		);

		$this->add_control(
			'cache_ttl_minutes',
			[
				'label' => esc_html__( 'Cache Duration (Minutes)', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 1440,
				'step' => 5,
				'default' => 360,
				'description' => esc_html__( 'How long fetched reviews stay cached before automatic refresh.', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'refresh_cache',
			[
				'label' => esc_html__( 'Force Refresh Cache', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
				'description' => esc_html__( 'Enable once to bypass cache and fetch fresh reviews.', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'reviews_sort',
			[
				'label' => esc_html__( 'Reviews Sort', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'newest',
				'options' => [
					'newest' => esc_html__( 'Newest', 'wpr-addons' ),
					'most_relevant' => esc_html__( 'Most Relevant', 'wpr-addons' ),
				],
			]
		);

		$this->add_control(
			'reviews_count',
			[
				'label' => esc_html__( 'Number of Reviews', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 200,
				'default' => 6,
			]
		);

		$this->add_control(
			'minimum_rating',
			[
				'label' => esc_html__( 'Minimum Rating', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0' => esc_html__( 'All', 'wpr-addons' ),
					'1' => esc_html__( '1 Star', 'wpr-addons' ),
					'2' => esc_html__( '2 Stars', 'wpr-addons' ),
					'3' => esc_html__( '3 Stars', 'wpr-addons' ),
					'4' => esc_html__( '4 Stars', 'wpr-addons' ),
					'5' => esc_html__( '5 Stars', 'wpr-addons' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_google_reviews_elements',
			[
				'label' => esc_html__( 'Elements', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'elements_summary_heading',
			[
				'label' => esc_html__( 'Summary', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'show_place_summary',
			[
				'label' => esc_html__( 'Show Place Summary', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_google_brand',
			[
				'label' => esc_html__( 'Show Google Brand', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'show_place_summary' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_place_reviews_link',
			[
				'label' => esc_html__( 'Show Reviews Link', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'place_reviews_link_text',
			[
				'label' => esc_html__( 'Reviews Link Text', 'wpr-addons' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'View all reviews', 'wpr-addons' ),
				'condition' => [
					'show_place_reviews_link' => 'yes',
				],
			]
		);

		$this->add_control(
			'elements_reviews_heading',
			[
				'label' => esc_html__( 'Reviews', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_reviewer_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_review_date',
			[
				'label' => esc_html__( 'Show Date', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_card_icon',
			[
				'label' => esc_html__( 'Show Card Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_review_stars',
			[
				'label' => esc_html__( 'Show Stars', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_review_text',
			[
				'label' => esc_html__( 'Show Review Text', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_review_link',
			[
				'label' => esc_html__( 'Show Review Link', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'review_link_text',
			[
				'label' => esc_html__( 'Review Link Text', 'wpr-addons' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'View on Google', 'wpr-addons' ),
				'condition' => [
					'show_review_link' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_google_reviews_slider',
			[
				'label' => esc_html__( 'Slider', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'slides_to_show',
			[
				'label' => esc_html__( 'Slides To Show', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
				'min' => 1,
			]
		);

		$this->add_control(
			'slides_to_scroll',
			[
				'label' => esc_html__( 'Slides To Scroll', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 1,
				'max' => 5,
			]
		);

		$this->add_control(
			'swiper_space_between',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 20,
				'min' => 0,
			]
		);

		$this->add_control(
			'swiper_loop',
			[
				'label' => esc_html__( 'Loop', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
			]
		);

		$this->add_control(
			'swiper_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
			]
		);

		$this->add_control(
			'swiper_delay',
			[
				'label' => esc_html__( 'Autoplay Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 10000,
				'default' => 3000,
				'condition' => [
					'swiper_autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'swiper_pause_on_hover',
			[
				'label' => esc_html__( 'Pause on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'condition' => [
					'swiper_autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'swiper_speed',
			[
				'label' => esc_html__( 'Carousel Speed', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5000,
				'default' => 500,
			]
		);

		$this->add_control(
			'show_swiper_navigation',
			[
				'label' => esc_html__( 'Show Navigation', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
			]
		);

		$this->add_control(
			'swiper_nav_icon',
			[
				'label' => esc_html__( 'Carousel Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fas fa-angle-left',
				'options' => Utilities::get_svg_icons_array(
					'arrows',
					[
						'fas fa-angle-left' => esc_html__( 'Angle', 'wpr-addons' ),
						'fas fa-angle-double-left' => esc_html__( 'Angle Double', 'wpr-addons' ),
						'fas fa-arrow-left' => esc_html__( 'Arrow', 'wpr-addons' ),
						'fas fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle', 'wpr-addons' ),
						'far fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle Alt', 'wpr-addons' ),
						'fas fa-long-arrow-alt-left' => esc_html__( 'Long Arrow', 'wpr-addons' ),
						'fas fa-chevron-left' => esc_html__( 'Chevron', 'wpr-addons' ),
						'svg-icons' => esc_html__( 'SVG Icons -----', 'wpr-addons' ),
					]
				),
				'condition' => [
					'show_swiper_navigation' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_google_reviews_header',
			[
				'label' => esc_html__( 'Summary Layout', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'header_direction',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'row',
				'options' => [
					'row' => [
						'title' => esc_html__( 'Horizontal', 'wpr-addons' ),
						'icon' => 'eicon-arrow-right',
					],
					'column' => [
						'title' => esc_html__( 'Vertical', 'wpr-addons' ),
						'icon' => 'eicon-arrow-down',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-header' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'row' => 'flex-direction: row; justify-content: var(--wpr-google-header-horizontal-align, space-between); align-items: var(--wpr-google-header-vertical-align, flex-start); --wpr-google-summary-align: flex-start; --wpr-google-summary-text-align: left;',
					'column' => 'flex-direction: column; justify-content: var(--wpr-google-header-vertical-align, flex-start); align-items: var(--wpr-google-header-horizontal-align, flex-start); --wpr-google-summary-align: var(--wpr-google-header-horizontal-align, flex-start); --wpr-google-summary-text-align: var(--wpr-google-header-horizontal-text-align, left);',
				],
			]
		);

		$this->add_responsive_control(
			'header_horizontal_align',
			[
				'label' => esc_html__( 'Horizontal Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'space-between',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					],
					'space-between' => [
						'title' => esc_html__( 'Space Between', 'wpr-addons' ),
						'icon' => 'eicon-justify-space-between-h',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-header' => '{{VALUE}}',
					'{{WRAPPER}} .wpr-google-place-summary' => 'align-items: var(--wpr-google-summary-align, flex-start); text-align: var(--wpr-google-summary-text-align, left);',
					'{{WRAPPER}} .wpr-google-reviews-place-link' => 'text-align: var(--wpr-google-summary-text-align, left);',
				],
				'selectors_dictionary' => [
					'flex-start' => '--wpr-google-header-horizontal-align: flex-start; --wpr-google-header-horizontal-text-align: left;',
					'center' => '--wpr-google-header-horizontal-align: center; --wpr-google-header-horizontal-text-align: center;',
					'flex-end' => '--wpr-google-header-horizontal-align: flex-end; --wpr-google-header-horizontal-text-align: right;',
					'space-between' => '--wpr-google-header-horizontal-align: space-between; --wpr-google-header-horizontal-text-align: left;',
				],
			]
		);

		$this->add_responsive_control(
			'header_vertical_align',
			[
				'label' => esc_html__( 'Vertical Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'wpr-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'wpr-addons' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'wpr-addons' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-header' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'flex-start' => '--wpr-google-header-vertical-align: flex-start;',
					'center' => '--wpr-google-header-vertical-align: center;',
					'flex-end' => '--wpr-google-header-vertical-align: flex-end;',
				],
			]
		);

		$this->add_responsive_control(
			'header_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '0',
					'right' => '0',
					'bottom' => '16',
					'left' => '0',
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_google_reviews_summary',
			[
				'label' => esc_html__( 'Summary Styles', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_place_summary' => 'yes',
				],
			]
		);

		$this->add_control(
			'summary_brand_heading',
			[
				'label' => esc_html__( 'Brand', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'summary_brand_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-google-brand-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'summary_brand_typography',
				'selector' => '{{WRAPPER}} .wpr-google-brand-label',
			]
		);

		$this->add_control(
			'summary_rating_heading',
			[
				'label' => esc_html__( 'Rating', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'summary_rating_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-google-place-summary-score' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'summary_rating_typography',
				'selector' => '{{WRAPPER}} .wpr-google-place-summary-score',
			]
		);

		$this->add_control(
			'summary_count_heading',
			[
				'label' => esc_html__( 'Reviews Count', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'summary_count_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-google-place-summary-count' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'summary_count_typography',
				'selector' => '{{WRAPPER}} .wpr-google-place-summary-count',
			]
		);

		$this->add_control(
			'summary_stars_heading',
			[
				'label' => esc_html__( 'Stars', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'summary_star_active_color',
			[
				'label' => esc_html__( 'Active Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fbbc04',
				'selectors' => [
					'{{WRAPPER}} .wpr-google-place-summary-stars .wpr-google-star.is-active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'summary_star_inactive_color',
			[
				'label' => esc_html__( 'Inactive Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#cfcfcf',
				'selectors' => [
					'{{WRAPPER}} .wpr-google-place-summary-stars .wpr-google-star' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'summary_star_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 40,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-place-summary-stars .wpr-google-star' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'place_link_heading',
			[
				'label' => esc_html__( 'Place Reviews Link', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_place_reviews_link' => 'yes',
				],
			]
		);

		$this->start_controls_tabs(
			'tabs_place_link_style',
			[
				'condition' => [
					'show_place_reviews_link' => 'yes',
				],
			]
		);

		$this->start_controls_tab(
			'tab_place_link_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'place_link_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-place-link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'place_link_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-place-link' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'place_link_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-place-link' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'place_link_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-google-reviews-place-link',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_place_link_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'place_link_color_hr',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-place-link:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'place_link_bg_color_hr',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-place-link:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'place_link_border_color_hr',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-place-link:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'place_link_box_shadow_hr',
				'selector' => '{{WRAPPER}} .wpr-google-reviews-place-link:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'place_link_border_radius',
			[
				'label' => esc_html__( 'Button Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '50',
					'right' => '50',
					'bottom' => '50',
					'left' => '50',
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-place-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_place_reviews_link' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'place_link_padding',
			[
				'label' => esc_html__( 'Button Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'top' => '10',
					'right' => '20',
					'bottom' => '10',
					'left' => '20',
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-place-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_place_reviews_link' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'place_link_typography',
				'selector' => '{{WRAPPER}} .wpr-google-reviews-place-link',
				'condition' => [
					'show_place_reviews_link' => 'yes',
				],
			]
		);

		$this->add_control(
			'place_link_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-place-link' => 'transition-duration: {{VALUE}}s',
				],
				'condition' => [
					'show_place_reviews_link' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_google_reviews_card',
			[
				'label' => esc_html__( 'Card', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'card_background_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-google-review-card' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'card_border',
				'selector' => '{{WRAPPER}} .wpr-google-review-card',
			]
		);

		$this->add_responsive_control(
			'card_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-review-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-review-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'card_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-google-review-card',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_google_reviews_card_icon',
			[
				'label' => esc_html__( 'Card Icon', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_card_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'card_icon_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 12,
						'max' => 60,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-review-card-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_icon_opacity',
			[
				'label' => esc_html__( 'Opacity', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-review-card-icon' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_google_reviews_stars',
			[
				'label' => esc_html__( 'Review Stars', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_review_stars' => 'yes',
				],
			]
		);

		$this->add_control(
			'card_star_active_color',
			[
				'label' => esc_html__( 'Active Star Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fbbc04',
				'selectors' => [
					'{{WRAPPER}} .wpr-google-review-stars .wpr-google-star.is-active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'card_star_inactive_color',
			[
				'label' => esc_html__( 'Inactive Star Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#cfcfcf',
				'selectors' => [
					'{{WRAPPER}} .wpr-google-review-stars .wpr-google-star' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_star_size',
			[
				'label' => esc_html__( 'Star Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 40,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-review-stars .wpr-google-star' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_google_reviews_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'author_heading',
			[
				'label' => esc_html__( 'Author', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'tabs_author_link_style' );

		$this->start_controls_tab(
			'tab_author_link_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'author_link_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-google-review-author-link' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-google-review-author-link .wpr-google-review-author' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-google-review-meta > .wpr-google-review-author' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_author_link_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'author_link_color_hr',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-google-review-author-link:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-google-review-author-link:hover .wpr-google-review-author' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'author_link_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-google-review-author-link' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'author_typography',
				'selector' => '{{WRAPPER}} .wpr-google-review-author, {{WRAPPER}} .wpr-google-review-author-link',
			]
		);

		$this->add_control(
			'date_heading',
			[
				'label' => esc_html__( 'Date', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-google-review-date' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'selector' => '{{WRAPPER}} .wpr-google-review-date',
			]
		);

		$this->add_control(
			'content_heading',
			[
				'label' => esc_html__( 'Review Content', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_review_text' => 'yes',
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-google-review-text' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_review_text' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .wpr-google-review-text',
				'condition' => [
					'show_review_text' => 'yes',
				],
			]
		);

		$this->add_control(
			'review_link_heading',
			[
				'label' => esc_html__( 'Review Link', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_review_link' => 'yes',
				],
			]
		);

		$this->add_control(
			'review_link_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .wpr-google-review-link' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_review_link' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'review_link_typography',
				'selector' => '{{WRAPPER}} .wpr-google-review-link',
				'condition' => [
					'show_review_link' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_google_reviews_nav',
			[
				'label' => esc_html__( 'Navigation', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_swiper_navigation' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_google_reviews_nav_colors' );

		$this->start_controls_tab(
			'tab_google_reviews_nav_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'swiper_nav_icon_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-slider .wpr-button-prev i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-google-reviews-slider .wpr-button-next i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-google-reviews-slider .wpr-button-prev svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .wpr-google-reviews-slider .wpr-button-next svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'swiper_nav_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-slider .wpr-button-prev' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-google-reviews-slider .wpr-button-next' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_google_reviews_nav_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'swiper_nav_icon_color_hr',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-slider .wpr-button-prev:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-google-reviews-slider .wpr-button-next:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-google-reviews-slider .wpr-button-prev:hover svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .wpr-google-reviews-slider .wpr-button-next:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'swiper_nav_bg_color_hr',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-slider .wpr-button-prev:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-google-reviews-slider .wpr-button-next:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'swiper_nav_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 8,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-slider .wpr-button-prev i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-google-reviews-slider .wpr-button-next i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-google-reviews-slider .wpr-button-prev svg' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-google-reviews-slider .wpr-button-next svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'swiper_nav_box_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-slider' => '--wpr-google-nav-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-google-reviews-slider .wpr-button-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-google-reviews-slider .wpr-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'swiper_nav_vertical_position',
			[
				'label' => esc_html__( 'Vertical Position', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-slider' => '--wpr-google-nav-y: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'swiper_nav_horizontal_offset',
			[
				'label' => esc_html__( 'Horizontal Offset', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-google-reviews-slider' => '--wpr-google-nav-x: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$place_id = isset( $settings['google_place_id'] ) ? sanitize_text_field( $settings['google_place_id'] ) : '';
		$data_source = isset( $settings['data_source'] ) ? sanitize_text_field( $settings['data_source'] ) : 'google_places';
		$cache_ttl_minutes = isset( $settings['cache_ttl_minutes'] ) ? (int) $settings['cache_ttl_minutes'] : 360;
		$cache_ttl_minutes = max( 5, min( 1440, $cache_ttl_minutes ) );
		$cache_ttl = $cache_ttl_minutes * MINUTE_IN_SECONDS;
		$force_refresh = isset( $settings['refresh_cache'] ) && 'yes' === $settings['refresh_cache'];

		if ( '' === $place_id ) {
			$this->render_error( esc_html__( 'Please insert a Google Place ID.', 'wpr-addons' ) );
			return;
		}

		if ( 'serpapi' === $data_source ) {
			$api_key = sanitize_text_field( get_option( 'wpr_serpapi_key', '' ) );
			if ( '' === $api_key ) {
				$this->render_error( esc_html__( 'Please insert a SerpApi Key in plugin Settings.', 'wpr-addons' ) );
				return;
			}

			$place_data = $this->get_serpapi_place_data( $api_key, $place_id, $settings['reviews_sort'], $cache_ttl, $force_refresh );
		} else {
			$api_key = $this->get_google_reviews_api_key();
			if ( '' === $api_key ) {
				$this->render_error( esc_html__( 'Please insert a Google API Key (REA) or Google Map API Key in plugin Settings.', 'wpr-addons' ) );
				return;
			}

			$place_data = $this->get_google_place_data( $api_key, $place_id, $settings['reviews_sort'], $cache_ttl, $force_refresh );
		}

		if ( is_wp_error( $place_data ) ) {
			$this->render_error( $place_data->get_error_message() );
			return;
		}

		$reviews = isset( $place_data['reviews'] ) && is_array( $place_data['reviews'] ) ? $place_data['reviews'] : [];
		$minimum_rating = isset( $settings['minimum_rating'] ) ? (float) $settings['minimum_rating'] : 0;

		if ( $minimum_rating > 0 ) {
			$reviews = array_filter(
				$reviews,
				function( $review ) use ( $minimum_rating ) {
					$rating = isset( $review['rating'] ) ? (float) $review['rating'] : 0;
					return $rating >= $minimum_rating;
				}
			);
		}

		$reviews = array_values( $reviews );
		$limit = isset( $settings['reviews_count'] ) ? (int) $settings['reviews_count'] : 6;
		$limit = $limit > 0 ? $limit : 6;
		$reviews = array_slice( $reviews, 0, $limit );

		if ( empty( $reviews ) ) {
			$this->render_error( esc_html__( 'No reviews found for the selected filters.', 'wpr-addons' ) );
			return;
		}

		$place_reviews_url = $this->resolve_place_reviews_url(
			$place_id,
			isset( $place_data['url'] ) ? $place_data['url'] : ''
		);
		$place_reviews_link_text = isset( $settings['place_reviews_link_text'] ) ? $settings['place_reviews_link_text'] : esc_html__( 'View all reviews', 'wpr-addons' );
		$show_place_reviews_link = ! isset( $settings['show_place_reviews_link'] ) || 'yes' === $settings['show_place_reviews_link'];
		$slider_id = 'wpr-google-reviews-slider-' . $this->get_id();
		$show_navigation = isset( $settings['show_swiper_navigation'] ) && 'yes' === $settings['show_swiper_navigation'];
		$slider_classes = 'wpr-google-reviews-slider swiper';
		$slider_classes .= $show_navigation ? '' : ' wpr-google-reviews-no-nav';
		?>
		<div class="wpr-google-reviews-wrap">
			<?php
			$show_header = ( isset( $settings['show_place_summary'] ) && 'yes' === $settings['show_place_summary'] )
				|| ( $show_place_reviews_link && '' !== $place_reviews_url && '' !== $place_reviews_link_text );

			if ( $show_header ) :
				$this->add_render_attribute( 'google_reviews_header', 'class', 'wpr-google-reviews-header' );
				?>
				<div <?php echo $this->get_render_attribute_string( 'google_reviews_header' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php $this->render_place_summary( $place_data, $place_reviews_url, $settings, $show_place_reviews_link ); ?>
					<?php if ( $show_place_reviews_link && '' !== $place_reviews_url && '' !== $place_reviews_link_text ) : ?>
						<a class="wpr-google-reviews-place-link" href="<?php echo esc_url( $place_reviews_url ); ?>" target="_blank" rel="noopener noreferrer">
							<?php echo esc_html( $place_reviews_link_text ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div id="<?php echo esc_attr( $slider_id ); ?>" class="<?php echo esc_attr( $slider_classes ); ?>"
				data-slidestoshow="<?php echo esc_attr( (int) $settings['slides_to_show'] ); ?>"
				data-slidestoscroll="<?php echo esc_attr( isset( $settings['slides_to_scroll'] ) ? (int) $settings['slides_to_scroll'] : 1 ); ?>"
				data-autoplay="<?php echo esc_attr( $settings['swiper_autoplay'] ); ?>"
				data-loop="<?php echo esc_attr( $settings['swiper_loop'] ); ?>"
				data-swiper-speed="<?php echo esc_attr( (int) $settings['swiper_speed'] ); ?>"
				data-swiper-delay="<?php echo esc_attr( (int) $settings['swiper_delay'] ); ?>"
				data-swiper-poh="<?php echo esc_attr( $settings['swiper_pause_on_hover'] ); ?>"
				data-swiper-space-between="<?php echo esc_attr( (int) $settings['swiper_space_between'] ); ?>"
				data-show-navigation="<?php echo esc_attr( $settings['show_swiper_navigation'] ); ?>">
				<div class="swiper-wrapper">
					<?php foreach ( $reviews as $review ) : ?>
						<?php $this->render_review_item( $review, $settings, $place_reviews_url ); ?>
					<?php endforeach; ?>
				</div>
				<?php if ( $show_navigation ) : ?>
					<div class="wpr-button-prev wpr-prev-arrow">
						<?php echo wp_kses_post( Utilities::get_wpr_icon( $settings['swiper_nav_icon'], '' ) ); ?>
					</div>
					<div class="wpr-button-next wpr-next-arrow">
						<?php echo wp_kses_post( Utilities::get_wpr_icon( $settings['swiper_nav_icon'], '' ) ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	private function get_google_place_data( $api_key, $place_id, $reviews_sort = 'newest', $cache_ttl = 6 * HOUR_IN_SECONDS, $force_refresh = false ) {
		$sort = 'most_relevant' === $reviews_sort ? 'most_relevant' : 'newest';
		$transient_key = 'wpr_google_reviews_places_' . md5( $place_id . '_' . $sort . '_' . $api_key );
		if ( ! $force_refresh ) {
			$cached_data = get_transient( $transient_key );
			if ( false !== $cached_data && is_array( $cached_data ) ) {
				return $cached_data;
			}
		}

		$request_url = add_query_arg(
			[
				'place_id' => $place_id,
				'fields' => 'name,rating,user_ratings_total,reviews,url',
				'reviews_sort' => $sort,
				'key' => $api_key,
			],
			'https://maps.googleapis.com/maps/api/place/details/json'
		);

		$response = wp_remote_get(
			$request_url,
			[
				'timeout' => 20,
			]
		);

		if ( is_wp_error( $response ) ) {
			return new \WP_Error( 'wpr_google_reviews_api_error', esc_html__( 'Unable to connect to Google Places API.', 'wpr-addons' ) );
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! is_array( $body ) ) {
			return new \WP_Error( 'wpr_google_reviews_invalid_response', esc_html__( 'Invalid Google Places API response.', 'wpr-addons' ) );
		}

		$status = isset( $body['status'] ) ? sanitize_text_field( $body['status'] ) : '';
		$error_message = isset( $body['error_message'] ) ? sanitize_text_field( $body['error_message'] ) : '';

		if ( 'OK' !== $status ) {
			$message = esc_html__( 'Google Places request failed.', 'wpr-addons' );

			if ( '' !== $status ) {
				$message .= ' ' . sprintf( esc_html__( 'Status: %s.', 'wpr-addons' ), $status );
			}

			if ( '' !== $error_message ) {
				$message .= ' ' . $error_message;
			}

			return new \WP_Error( 'wpr_google_reviews_api_status', $message );
		}

		if ( empty( $body['result'] ) || ! is_array( $body['result'] ) ) {
			return new \WP_Error( 'wpr_google_reviews_invalid_result', esc_html__( 'Google Places did not return place details for this Place ID.', 'wpr-addons' ) );
		}

		$result_data = $body['result'];
		$raw_reviews = isset( $result_data['reviews'] ) && is_array( $result_data['reviews'] ) ? $result_data['reviews'] : [];
		$place_url = isset( $result_data['url'] ) ? esc_url_raw( $result_data['url'] ) : '';
		$normalized_reviews = [];

		foreach ( $raw_reviews as $review ) {
			if ( ! is_array( $review ) ) {
				continue;
			}

			$author_url = isset( $review['author_url'] ) ? esc_url_raw( $review['author_url'] ) : '';

			$normalized_reviews[] = [
				'author_name' => isset( $review['author_name'] ) ? sanitize_text_field( $review['author_name'] ) : esc_html__( 'Google User', 'wpr-addons' ),
				'author_url' => $author_url,
				'profile_photo_url' => isset( $review['profile_photo_url'] ) ? esc_url_raw( $review['profile_photo_url'] ) : '',
				'rating' => isset( $review['rating'] ) ? (float) $review['rating'] : 0,
				'relative_time_description' => isset( $review['relative_time_description'] ) ? sanitize_text_field( $review['relative_time_description'] ) : '',
				'text' => isset( $review['text'] ) && is_string( $review['text'] ) ? $review['text'] : '',
				// Google Places API does not return a per-review URL.
				'review_link' => '',
			];
		}

		$result = [
			'name' => isset( $result_data['name'] ) ? sanitize_text_field( $result_data['name'] ) : '',
			'rating' => isset( $result_data['rating'] ) ? (float) $result_data['rating'] : 0,
			'user_ratings_total' => isset( $result_data['user_ratings_total'] ) ? (int) $result_data['user_ratings_total'] : 0,
			'url' => $place_url,
			'reviews' => $normalized_reviews,
		];

		set_transient( $transient_key, $result, $cache_ttl );

		return $result;
	}

	private function get_serpapi_place_data( $api_key, $place_id, $reviews_sort = 'newest', $cache_ttl = 6 * HOUR_IN_SECONDS, $force_refresh = false ) {
		$sort = 'most_relevant' === $reviews_sort ? 'qualityScore' : 'newestFirst';
		$transient_key = 'wpr_google_reviews_serpapi_' . md5( $place_id . '_' . $sort . '_' . $api_key );
		$max_reviews = 200;
		$max_pages = 15;

		if ( ! $force_refresh ) {
			$cached_data = get_transient( $transient_key );
			if ( false !== $cached_data && is_array( $cached_data ) ) {
				return $cached_data;
			}
		}

		$request_args = [
			'engine' => 'google_maps_reviews',
			'place_id' => $place_id,
			'sort_by' => $sort,
			'hl' => 'en',
			'api_key' => $api_key,
		];

		$request_url = add_query_arg( $request_args, 'https://serpapi.com/search.json' );

		$response = wp_remote_get(
			$request_url,
			[
				'timeout' => 20,
			]
		);

		if ( is_wp_error( $response ) ) {
			return new \WP_Error( 'wpr_google_reviews_api_error', esc_html__( 'Unable to connect to SerpApi.', 'wpr-addons' ) );
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! is_array( $body ) ) {
			return new \WP_Error( 'wpr_google_reviews_invalid_response', esc_html__( 'Invalid SerpApi response.', 'wpr-addons' ) );
		}

		if ( ! empty( $body['error'] ) ) {
			return new \WP_Error(
				'wpr_google_reviews_api_status',
				esc_html__( 'SerpApi request failed.', 'wpr-addons' ) . ' ' . sanitize_text_field( $body['error'] )
			);
		}

		if ( empty( $body['reviews'] ) || ! is_array( $body['reviews'] ) ) {
			return new \WP_Error( 'wpr_google_reviews_invalid_result', esc_html__( 'SerpApi did not return reviews for this Place ID.', 'wpr-addons' ) );
		}

		$all_reviews = $body['reviews'];
		$next_page_token = '';
		$next_page_url = '';
		$data_id = '';
		if ( isset( $body['serpapi_pagination']['next_page_token'] ) ) {
			$next_page_token = sanitize_text_field( $body['serpapi_pagination']['next_page_token'] );
		}
		if ( isset( $body['serpapi_pagination']['next'] ) ) {
			$next_page_url = esc_url_raw( $body['serpapi_pagination']['next'] );
		}
		if ( isset( $body['search_parameters']['data_id'] ) ) {
			$data_id = sanitize_text_field( $body['search_parameters']['data_id'] );
		} elseif ( isset( $body['place_info']['data_id'] ) ) {
			$data_id = sanitize_text_field( $body['place_info']['data_id'] );
		}

		$page = 1;
		while ( '' !== $next_page_token && count( $all_reviews ) < $max_reviews && $page < $max_pages ) {
			$page++;
			$page_request_url = '';

			if ( '' !== $next_page_url ) {
				$page_request_url = $next_page_url;
				if ( false === strpos( $page_request_url, 'api_key=' ) ) {
					$page_request_url = add_query_arg(
						[
							'api_key' => $api_key,
						],
						$page_request_url
					);
				}
			} else {
				$page_request_args = [
					'engine' => 'google_maps_reviews',
					'sort_by' => $sort,
					'hl' => 'en',
					'api_key' => $api_key,
					'next_page_token' => $next_page_token,
					'num' => 20,
				];

				if ( '' !== $data_id ) {
					$page_request_args['data_id'] = $data_id;
				} else {
					$page_request_args['place_id'] = $place_id;
				}

				$page_request_url = add_query_arg( $page_request_args, 'https://serpapi.com/search.json' );
			}

			$page_response = wp_remote_get(
				$page_request_url,
				[
					'timeout' => 20,
				]
			);

			if ( is_wp_error( $page_response ) ) {
				break;
			}

			$page_body = json_decode( wp_remote_retrieve_body( $page_response ), true );

			if ( ! is_array( $page_body ) || ! empty( $page_body['error'] ) ) {
				break;
			}

			if ( ! empty( $page_body['reviews'] ) && is_array( $page_body['reviews'] ) ) {
				$all_reviews = array_merge( $all_reviews, $page_body['reviews'] );
			}

			$next_page_token = '';
			$next_page_url = '';
			if ( isset( $page_body['serpapi_pagination']['next_page_token'] ) ) {
				$next_page_token = sanitize_text_field( $page_body['serpapi_pagination']['next_page_token'] );
			}
			if ( isset( $page_body['serpapi_pagination']['next'] ) ) {
				$next_page_url = esc_url_raw( $page_body['serpapi_pagination']['next'] );
			}
		}

		if ( count( $all_reviews ) > $max_reviews ) {
			$all_reviews = array_slice( $all_reviews, 0, $max_reviews );
		}

		$place_info = isset( $body['place_info'] ) && is_array( $body['place_info'] ) ? $body['place_info'] : [];
		$place_name = isset( $place_info['title'] ) ? sanitize_text_field( $place_info['title'] ) : '';
		$place_url = $this->resolve_place_reviews_url(
			$place_id,
			$this->get_serpapi_place_url_candidate( $place_info, $body, $all_reviews )
		);

		$normalized_reviews = [];
		$seen_reviews = [];
		foreach ( $all_reviews as $review ) {
			if ( ! is_array( $review ) ) {
				continue;
			}

			$user = isset( $review['user'] ) && is_array( $review['user'] ) ? $review['user'] : [];
			$review_key = '';
			if ( isset( $review['review_id'] ) ) {
				$review_key = sanitize_text_field( $review['review_id'] );
			} elseif ( isset( $review['link'] ) ) {
				$review_key = esc_url_raw( $review['link'] );
			} else {
				$review_key = md5( wp_json_encode( $review ) );
			}

			if ( '' !== $review_key && isset( $seen_reviews[ $review_key ] ) ) {
				continue;
			}

			$seen_reviews[ $review_key ] = true;
			$review_text = '';

			if ( isset( $review['snippet'] ) ) {
				$review_text = $review['snippet'];
			} elseif ( isset( $review['extracted_snippet']['original'] ) ) {
				$review_text = $review['extracted_snippet']['original'];
			}

			$normalized_reviews[] = [
				'author_name' => isset( $user['name'] ) ? sanitize_text_field( $user['name'] ) : esc_html__( 'Google User', 'wpr-addons' ),
				'author_url' => isset( $user['link'] ) ? esc_url_raw( $user['link'] ) : '',
				'profile_photo_url' => isset( $user['thumbnail'] ) ? esc_url_raw( $user['thumbnail'] ) : '',
				'rating' => isset( $review['rating'] ) ? (float) $review['rating'] : 0,
				'relative_time_description' => isset( $review['date'] ) ? sanitize_text_field( $review['date'] ) : '',
				'text' => is_string( $review_text ) ? $review_text : '',
				'review_link' => isset( $review['link'] ) ? esc_url_raw( $review['link'] ) : '',
			];
		}

		$result = [
			'name' => $place_name,
			'rating' => isset( $place_info['rating'] ) ? (float) $place_info['rating'] : 0,
			'user_ratings_total' => isset( $place_info['reviews'] ) ? (int) $place_info['reviews'] : 0,
			'url' => $place_url,
			'reviews' => $normalized_reviews,
		];

		set_transient( $transient_key, $result, $cache_ttl );

		return $result;
	}

	private function render_review_item( $review, $settings, $place_reviews_url = '' ) {
		$author_name = isset( $review['author_name'] ) ? sanitize_text_field( $review['author_name'] ) : esc_html__( 'Google User', 'wpr-addons' );
		$avatar = isset( $review['profile_photo_url'] ) ? esc_url( $review['profile_photo_url'] ) : '';
		$rating = isset( $review['rating'] ) ? (int) $review['rating'] : 0;
		$relative_time = isset( $review['relative_time_description'] ) ? sanitize_text_field( $review['relative_time_description'] ) : '';
		$text = isset( $review['text'] ) ? $review['text'] : '';
		$author_profile_url = $this->get_reviewer_profile_url( $review );
		$single_review_url = $this->get_single_review_url( $review, $place_reviews_url );
		$show_review_text = ! isset( $settings['show_review_text'] ) || 'yes' === $settings['show_review_text'];
		$show_review_link = ! isset( $settings['show_review_link'] ) || 'yes' === $settings['show_review_link'];
		$review_link_text = isset( $settings['review_link_text'] ) ? trim( $settings['review_link_text'] ) : esc_html__( 'View on Google', 'wpr-addons' );
		$show_card_icon = isset( $settings['show_card_icon'] ) && 'yes' === $settings['show_card_icon'];
		$stars_aria_label = esc_attr( sprintf( __( '%d out of 5 stars', 'wpr-addons' ), $rating ) );
		$review_text = wp_kses_post( nl2br( esc_html( $text ) ) );
		?>
		<div class="swiper-slide">
			<article class="wpr-google-review-card">
				<div class="wpr-google-review-head">
					<?php if ( 'yes' === $settings['show_reviewer_avatar'] && '' !== $avatar ) : ?>
						<div class="wpr-google-review-avatar">
							<?php if ( '' !== $author_profile_url ) : ?>
								<a class="wpr-google-review-avatar-link" href="<?php echo esc_url( $author_profile_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $author_name ); ?>">
									<img src="<?php echo esc_url( $avatar ); ?>" alt="">
								</a>
							<?php else : ?>
								<img src="<?php echo esc_url( $avatar ); ?>" alt="<?php echo esc_attr( $author_name ); ?>">
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="wpr-google-review-meta">
						<?php if ( '' !== $author_profile_url ) : ?>
							<a class="wpr-google-review-author-link" href="<?php echo esc_url( $author_profile_url ); ?>" target="_blank" rel="noopener noreferrer">
								<strong class="wpr-google-review-author"><?php echo esc_html( $author_name ); ?></strong>
							</a>
						<?php else : ?>
							<strong class="wpr-google-review-author"><?php echo esc_html( $author_name ); ?></strong>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_review_date'] && '' !== $relative_time ) : ?>
							<span class="wpr-google-review-date"><?php echo esc_html( $relative_time ); ?></span>
						<?php endif; ?>
					</div>
					<?php if ( $show_card_icon ) : ?>
						<?php if ( '' !== $single_review_url ) : ?>
							<a class="wpr-google-review-card-icon" href="<?php echo esc_url( $single_review_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $stars_aria_label ); ?>">
								<?php echo wp_kses( $this->get_google_card_icon_svg(), $this->get_google_card_icon_svg_allowed_tags() ); ?>
							</a>
						<?php else : ?>
							<span class="wpr-google-review-card-icon" aria-hidden="true">
								<?php echo wp_kses( $this->get_google_card_icon_svg(), $this->get_google_card_icon_svg_allowed_tags() ); ?>
							</span>
						<?php endif; ?>
					<?php endif; ?>
				</div>

				<?php if ( 'yes' === $settings['show_review_stars'] ) : ?>
					<?php if ( '' !== $single_review_url ) : ?>
						<a class="wpr-google-review-stars-link" href="<?php echo esc_url( $single_review_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $stars_aria_label ); ?>">
							<span class="wpr-google-review-stars"><?php echo wp_kses_post( $this->get_rating_stars_html( $rating ) ); ?></span>
						</a>
					<?php else : ?>
						<div class="wpr-google-review-stars" aria-label="<?php echo esc_attr( $stars_aria_label ); ?>">
							<?php echo wp_kses_post( $this->get_rating_stars_html( $rating ) ); ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>

				<?php if ( $show_review_text ) : ?>
					<div class="wpr-google-review-text"><?php echo wp_kses_post( $review_text ); ?></div>
				<?php endif; ?>

				<?php if ( $show_review_link && '' !== $single_review_url && '' !== $review_link_text ) : ?>
					<a class="wpr-google-review-link" href="<?php echo esc_url( $single_review_url ); ?>" target="_blank" rel="noopener noreferrer">
						<?php echo esc_html( $review_link_text ); ?>
					</a>
				<?php endif; ?>
			</article>
		</div>
		<?php
	}

	private function render_place_summary( $place_data, $place_reviews_url, $settings, $has_place_link = false ) {
		if ( ! isset( $settings['show_place_summary'] ) || 'yes' !== $settings['show_place_summary'] ) {
			return;
		}

		$rating = isset( $place_data['rating'] ) ? (float) $place_data['rating'] : 0;
		$reviews_total = isset( $place_data['user_ratings_total'] ) ? (int) $place_data['user_ratings_total'] : 0;

		if ( $rating <= 0 && $reviews_total <= 0 ) {
			return;
		}

		$rating_label = $rating > 0 ? number_format_i18n( $rating, 1 ) : '';
		$reviews_count_label = $reviews_total > 0 ? '(' . number_format_i18n( $reviews_total ) . ')' : '';
		$summary_link = '' !== $place_reviews_url && ! $has_place_link;
		$show_google_brand = ! isset( $settings['show_google_brand'] ) || 'yes' === $settings['show_google_brand'];
		?>
		<div class="wpr-google-place-summary">
			<?php if ( $show_google_brand ) : ?>
				<div class="wpr-google-place-summary-brand">
					<?php echo wp_kses_post( $this->get_google_reviews_brand_html() ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $summary_link ) : ?>
				<a class="wpr-google-place-summary-rating" href="<?php echo esc_url( $place_reviews_url ); ?>" target="_blank" rel="noopener noreferrer">
			<?php else : ?>
				<div class="wpr-google-place-summary-rating">
			<?php endif; ?>

				<?php if ( '' !== $rating_label ) : ?>
					<span class="wpr-google-place-summary-score"><?php echo esc_html( $rating_label ); ?></span>
				<?php endif; ?>

				<?php if ( $rating > 0 ) : ?>
					<span class="wpr-google-place-summary-stars" aria-hidden="true">
						<?php echo wp_kses_post( $this->get_rating_stars_html( $rating ) ); ?>
					</span>
				<?php endif; ?>

				<?php if ( '' !== $reviews_count_label ) : ?>
					<span class="wpr-google-place-summary-count"><?php echo esc_html( $reviews_count_label ); ?></span>
				<?php endif; ?>

			<?php if ( $summary_link ) : ?>
				</a>
			<?php else : ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	private function get_google_reviews_brand_html() {
		$logo = '<span class="wpr-google-wordmark" aria-hidden="true">'
			. '<span class="wpr-google-letter wpr-google-letter-g">G</span>'
			. '<span class="wpr-google-letter wpr-google-letter-o1">o</span>'
			. '<span class="wpr-google-letter wpr-google-letter-o2">o</span>'
			. '<span class="wpr-google-letter wpr-google-letter-g2">g</span>'
			. '<span class="wpr-google-letter wpr-google-letter-l">l</span>'
			. '<span class="wpr-google-letter wpr-google-letter-e">e</span>'
			. '</span>';

		return $logo . '<span class="wpr-google-brand-label">' . esc_html__( 'Reviews', 'wpr-addons' ) . '</span>';
	}

	private function get_google_card_icon_svg() {
		$icons = $this->get_svg_icons_map();

		if ( isset( $icons['google-colored'] ) ) {
			return $icons['google-colored'];
		}

		return isset( $icons['google'] ) ? $icons['google'] : '';
	}

	private function get_svg_icons_map() {
		static $icons = null;

		if ( null !== $icons ) {
			return $icons;
		}

		$icons = [];

		if ( ! defined( 'WPR_ADDONS_PATH' ) ) {
			return $icons;
		}

		$icons_file = WPR_ADDONS_PATH . 'assets/img/svg/svg-icons.json';

		if ( ! file_exists( $icons_file ) || ! is_readable( $icons_file ) ) {
			return $icons;
		}

		$decoded = json_decode( file_get_contents( $icons_file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		if ( is_array( $decoded ) ) {
			$icons = $decoded;
		}

		return $icons;
	}

	private function get_google_card_icon_svg_allowed_tags() {
		return [
			'svg' => [
				'xmlns' => [],
				'viewbox' => [],
				'viewBox' => [],
				'version' => [],
				'fill' => [],
				'class' => [],
				'aria-hidden' => [],
				'role' => [],
			],
			'path' => [
				'd' => [],
				'fill' => [],
				'transform' => [],
			],
			'g' => [
				'stroke' => [],
				'stroke-width' => [],
				'fill' => [],
				'fill-rule' => [],
				'transform' => [],
			],
		];
	}

	private function get_serpapi_place_url_candidate( $place_info, $body, $reviews ) {
		$candidates = [];

		if ( is_array( $place_info ) ) {
			if ( ! empty( $place_info['reviews_link'] ) ) {
				$candidates[] = $place_info['reviews_link'];
			}
			if ( ! empty( $place_info['link'] ) ) {
				$candidates[] = $place_info['link'];
			}
			if ( ! empty( $place_info['google_maps_url'] ) ) {
				$candidates[] = $place_info['google_maps_url'];
			}
		}

		if ( is_array( $body ) && ! empty( $body['reviews_link'] ) ) {
			$candidates[] = $body['reviews_link'];
		}

		if ( is_array( $reviews ) ) {
			foreach ( $reviews as $review ) {
				if ( ! is_array( $review ) || empty( $review['link'] ) ) {
					continue;
				}

				$candidates[] = $review['link'];
				break;
			}
		}

		foreach ( $candidates as $candidate ) {
			if ( $this->is_google_place_listing_url( $candidate ) ) {
				return $candidate;
			}
		}

		return '';
	}

	private function get_reviewer_profile_url( $review ) {
		if ( ! is_array( $review ) || empty( $review['author_url'] ) ) {
			return '';
		}

		$url = esc_url_raw( $review['author_url'] );

		if ( ! $this->is_google_place_reviews_url( $url ) || $this->is_google_single_review_url( $url ) ) {
			return '';
		}

		if ( $this->is_google_reviewer_profile_url( $url ) ) {
			return esc_url( $url );
		}

		// SerpApi may return other Google Maps profile URLs that are not /contrib/ paths.
		if ( false !== strpos( $url, 'google.com/maps' ) ) {
			return esc_url( $url );
		}

		return '';
	}

	private function get_google_reviews_api_key() {
		$google_api_key = sanitize_text_field( get_option( 'wpr_google_api_key', '' ) );
		if ( '' !== $google_api_key ) {
			return $google_api_key;
		}

		return sanitize_text_field( get_option( 'wpr_google_map_api_key', '' ) );
	}

	private function get_single_review_url( $review, $place_reviews_url = '' ) {
		// 1) Per-review URL from API data (SerpApi review.link, etc.).
		if ( is_array( $review ) && ! empty( $review['review_link'] ) ) {
			$url = esc_url_raw( $review['review_link'] );

			if ( $this->is_valid_google_review_target_url( $url ) ) {
				return esc_url( $url );
			}
		}

		// 2) Fallback: place-level reviews/listing URL (Google Places, missing review link).
		if ( '' !== $place_reviews_url ) {
			$url = esc_url_raw( $place_reviews_url );

			if ( $this->is_valid_google_review_target_url( $url ) ) {
				return esc_url( $url );
			}
		}

		return '';
	}

	private function is_valid_google_review_target_url( $url ) {
		if ( ! is_string( $url ) || '' === $url ) {
			return false;
		}

		if ( ! $this->is_google_place_reviews_url( $url ) ) {
			return false;
		}

		// Contributor profile URLs are for reviewer names, not review/stars/link targets.
		if ( $this->is_google_reviewer_profile_url( $url ) ) {
			return false;
		}

		return true;
	}

	private function is_google_reviewer_profile_url( $url ) {
		if ( ! is_string( $url ) || '' === $url || ! $this->is_google_place_reviews_url( $url ) ) {
			return false;
		}

		return false !== strpos( $url, '/maps/contrib/' ) || false !== strpos( $url, 'maps/contrib/' );
	}

	private function is_google_single_review_url( $url ) {
		if ( ! is_string( $url ) || '' === $url || ! $this->is_google_place_reviews_url( $url ) ) {
			return false;
		}

		return false !== strpos( $url, '/maps/reviews/' )
			|| false !== strpos( $url, 'maps/reviews/data=' )
			|| false !== strpos( $url, 'maps/review/' );
	}

	private function is_google_place_reviews_url( $url ) {
		if ( ! is_string( $url ) || '' === $url ) {
			return false;
		}

		$host = wp_parse_url( $url, PHP_URL_HOST );

		if ( empty( $host ) ) {
			return false;
		}

		$host = strtolower( $host );

		if ( false !== strpos( $host, 'serpapi.com' ) ) {
			return false;
		}

		return false !== strpos( $host, 'google.' );
	}

	private function is_google_place_listing_url( $url ) {
		if ( ! $this->is_google_place_reviews_url( $url ) ) {
			return false;
		}

		if ( false !== strpos( $url, 'google.com/maps/reviews/data=' ) ) {
			return false;
		}

		return true;
	}

	private function resolve_place_reviews_url( $place_id, $candidate_url = '' ) {
		if ( $this->is_google_place_reviews_url( $candidate_url ) ) {
			return esc_url( $candidate_url );
		}

		if ( '' !== $place_id ) {
			return esc_url( 'https://search.google.com/local/reviews?placeid=' . rawurlencode( $place_id ) );
		}

		return '';
	}

	private function get_rating_stars_html( $rating ) {
		$rating = max( 0, min( 5, (float) $rating ) );
		$active_stars = (int) floor( $rating + 0.25 );
		$output = '';

		for ( $i = 1; $i <= 5; $i++ ) {
			$star_class = $i <= $active_stars ? 'wpr-google-star is-active' : 'wpr-google-star';
			$output .= '<span class="' . esc_attr( $star_class ) . '">&#9733;</span>';
		}

		return $output;
	}

	private function render_error( $message ) {
		echo '<div class="wpr-google-reviews-error">' . esc_html( $message ) . '</div>';
	}
}
