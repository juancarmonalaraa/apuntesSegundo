<?php
namespace WprAddonsPro\Modules\WeatherPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Weather_Pro extends Widget_Base {

	public function get_name() {
		return 'wpr-weather-pro';
	}

	public function get_title() {
		return esc_html__( 'Weather', 'wpr-addons' );
	}

	public function get_icon() {
		// eicon-weather is not shipped with Elementor eicons; use a valid cloud icon for the panel.
		return 'wpr-icon eicon-cloud-check';
	}

	public function get_categories() {
		return [ 'wpr-premium-widgets' ];
	}

	public function get_keywords() {
		return [ 'wpr', 'royal', 'weather', 'forecast' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'weather_content_section',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'widget_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Weather Widget', 'wpr-addons' ),
			]
		);

		if ( '' === get_option( 'wpr_openweather_api_key' ) ) {
			$this->add_control(
				'weather_api_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf(
						__( 'Please enter <strong>OpenWeather API Key</strong> from <br><a href="%s" target="_blank">Dashboard > %s > Settings</a> tab to get live weather data. Demo content is shown until a key is added.', 'wpr-addons' ),
						esc_url( admin_url( 'admin.php?page=wpr-addons&tab=wpr_tab_settings' ) ),
						Utilities::get_plugin_name()
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->add_control(
			'temperature_unit',
			[
				'label' => esc_html__( 'Unit', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cel',
				'options' => [
					'cel' => esc_html__( 'Celsius (°C)', 'wpr-addons' ),
					'far' => esc_html__( 'Fahrenheit (°F)', 'wpr-addons' ),
				],
			]
		);

		$this->add_control(
			'location',
			[
				'label' => esc_html__( 'Location', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'New York',
				'description' => esc_html__( 'Example: London, GB', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'forecast',
			[
				'label' => esc_html__( 'Forecast', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'1' => esc_html__( '1 Day', 'wpr-addons' ),
					'2' => esc_html__( '2 Days', 'wpr-addons' ),
					'3' => esc_html__( '3 Days', 'wpr-addons' ),
					'4' => esc_html__( '4 Days', 'wpr-addons' ),
					'5' => esc_html__( '5 Days', 'wpr-addons' ),
					'none' => esc_html__( 'None', 'wpr-addons' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'weather_style_section',
			[
				'label' => esc_html__( 'Card', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'weather_card_bg',
			[
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-weather-wrap' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'weather_card_border',
				'selector' => '{{WRAPPER}} .wpr-weather-wrap',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
					'color' => [
						'default' => '#E8E8E8',
					],
				],
			]
		);

		$this->add_control(
			'weather_card_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-weather-wrap' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'weather_card_shadow',
				'selector' => '{{WRAPPER}} .wpr-weather-wrap',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'weather_icons_style_section',
			[
				'label' => esc_html__( 'Icons', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'weather_meta_icons_heading',
			[
				'label' => esc_html__( 'Meta Icons', 'wpr-addons' ),
				'description' => esc_html__( 'Thermometer, humidity, and wind icons beside the condition text.', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'weather_extra_info_icon_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 12,
						'max' => 48,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 28,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-weather-wrap' => '--wpr-weather-extra-info-icon-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'weather_extra_info_icon_gap',
			[
				'label' => esc_html__( 'Icon to Text Gap', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-weather-wrap' => '--wpr-weather-extra-info-icon-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'weather_extra_info_row_gap',
			[
				'label' => esc_html__( 'Rows Gap', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 6,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-weather-wrap' => '--wpr-weather-extra-info-row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'weather_icons_colors_divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'weather_icons_colors_heading',
			[
				'label' => esc_html__( 'Icon Colors', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'weather_icon_warm_color',
			[
				'label' => esc_html__( 'Sun & Warm Highlights', 'wpr-addons' ),
				'description' => esc_html__( 'Sun discs, rays, daytime sun in cloudy/rain icons, and storm lightning.', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#F59E0B',
				'selectors' => [
					'{{WRAPPER}} .wpr-weather-wrap' => '--wpr-weather-icon-warm: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'weather_icon_cloud_color',
			[
				'label' => esc_html__( 'Cloud Outline', 'wpr-addons' ),
				'description' => esc_html__( 'Main cloud shapes and overcast strokes.', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#D1D5DB',
				'selectors' => [
					'{{WRAPPER}} .wpr-weather-wrap' => '--wpr-weather-icon-cloud: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'weather_icon_cloud_secondary_color',
			[
				'label' => esc_html__( 'Cloud Shadow / Layer', 'wpr-addons' ),
				'description' => esc_html__( 'Secondary cloud layer in partly cloudy icons.', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9CA3AF',
				'selectors' => [
					'{{WRAPPER}} .wpr-weather-wrap' => '--wpr-weather-icon-cloud-secondary: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'weather_icon_rain_color',
			[
				'label' => esc_html__( 'Rain & Water', 'wpr-addons' ),
				'description' => esc_html__( 'Raindrops, humidity droplet outline, and animated rain strokes.', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#0284C7',
				'selectors' => [
					'{{WRAPPER}} .wpr-weather-wrap' => '--wpr-weather-icon-rain: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'weather_icon_night_color',
			[
				'label' => esc_html__( 'Moon & Night Sky', 'wpr-addons' ),
				'description' => esc_html__( 'Moon crescent and night variants of cloudy / rainy icons.', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#64748B',
				'selectors' => [
					'{{WRAPPER}} .wpr-weather-wrap' => '--wpr-weather-icon-night: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'weather_icon_snow_color',
			[
				'label' => esc_html__( 'Snow & Ice', 'wpr-addons' ),
				'description' => esc_html__( 'Snowflake dots and icy accents.', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7DD3FC',
				'selectors' => [
					'{{WRAPPER}} .wpr-weather-wrap' => '--wpr-weather-icon-snow: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'weather_icon_thermo_bulb_color',
			[
				'label' => esc_html__( 'Thermometer (Hot)', 'wpr-addons' ),
				'description' => esc_html__( 'Bulb fill and mercury stroke — reads as “warm”.', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#DC2626',
				'selectors' => [
					'{{WRAPPER}} .wpr-weather-wrap' => '--wpr-weather-icon-thermo-bulb: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'weather_icon_thermo_tube_color',
			[
				'label' => esc_html__( 'Thermometer (Tube)', 'wpr-addons' ),
				'description' => esc_html__( 'Glass tube outline and tick marks.', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#A3A3A3',
				'selectors' => [
					'{{WRAPPER}} .wpr-weather-wrap' => '--wpr-weather-icon-thermo-tube: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'weather_icon_wind_color',
			[
				'label' => esc_html__( 'Wind', 'wpr-addons' ),
				'description' => esc_html__( 'Wind gust curves — slightly darker reads better on white.', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#94A3B8',
				'selectors' => [
					'{{WRAPPER}} .wpr-weather-wrap' => '--wpr-weather-icon-wind: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'weather_icon_fog_lines_color',
			[
				'label' => esc_html__( 'Fog / Mist Lines', 'wpr-addons' ),
				'description' => esc_html__( 'Horizontal mist lines in fog icons.', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#CBD5E1',
				'selectors' => [
					'{{WRAPPER}} .wpr-weather-wrap' => '--wpr-weather-icon-fog-lines: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'weather_text_style_section',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'weather_title_heading',
			[
				'label' => esc_html__( 'Title Text', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'weather_title_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-weather-header' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'weather_accent_bg',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-weather-header' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'weather_title_typography',
				'selector' => '{{WRAPPER}} .wpr-weather-header',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '600',
					],
					'font_size' => [
						'default' => [
							'unit' => 'px',
							'size' => 14,
						],
					],
				],
			]
		);

		$this->add_control(
			'weather_text_divider_1',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'weather_main_heading',
			[
				'label' => esc_html__( 'Main Text', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'weather_main_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-weather-temp, {{WRAPPER}} .wpr-weather-location, {{WRAPPER}} .wpr-weather-forecast-temp, {{WRAPPER}} .wpr-weather-forecast-day' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'weather_main_typography',
				'selector' => '{{WRAPPER}} .wpr-weather-temp, {{WRAPPER}} .wpr-weather-location, {{WRAPPER}} .wpr-weather-forecast-temp, {{WRAPPER}} .wpr-weather-forecast-day',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '700',
					],
				],
			]
		);

		$this->add_control(
			'weather_text_divider_2',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'weather_meta_heading',
			[
				'label' => esc_html__( 'Meta Text', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'weather_meta_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => [
					'{{WRAPPER}} .wpr-weather-condition, {{WRAPPER}} .wpr-weather-extra-info' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'weather_meta_typography',
				'selector' => '{{WRAPPER}} .wpr-weather-condition, {{WRAPPER}} .wpr-weather-extra-info',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'unit' => 'px',
							'size' => 13,
						],
					],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$api_key = $this->get_openweather_api_key( $settings );
		$city_name = ! empty( $settings['location'] ) ? trim( $settings['location'] ) : '';

		if ( empty( $api_key ) || empty( $city_name ) ) {
			$this->render_demo_content( $settings );
			return;
		}

		$weather_data = $this->get_weather_data(
			$city_name,
			$settings['temperature_unit'],
			$api_key
		);

		if ( isset( $weather_data['now']->cod ) && 200 !== (int) $weather_data['now']->cod ) {
			$this->render_error( (string) $weather_data['now']->cod );
			return;
		}

		if ( empty( $weather_data['now'] ) || empty( $weather_data['forecast'] ) ) {
			$this->render_demo_content( $settings );
			return;
		}

		$weather_data_now = $weather_data['now'];
		$weather_data_forecast = $weather_data['forecast'];
		$icon_code = $weather_data_now->weather[0]->icon;
		$icon_name = $this->get_weather_icon_name_by_code( $icon_code );
		$condition = $weather_data_now->weather[0]->description;
		$humidity = $weather_data_now->main->humidity;
		$wind_speed = round( $weather_data_now->wind->speed );
		$temperature = round( $weather_data_now->main->temp ) . ( 'cel' === $settings['temperature_unit'] ? '°C' : '°F' );

		$today_date = gmdate( 'Ymd', current_time( 'timestamp', 0 ) );
		$min_temperature = null;
		$max_temperature = null;

		foreach ( $weather_data_forecast->list as $forecast ) {
			$forecast_date = gmdate( 'Ymd', $forecast->dt );
			if ( $today_date === $forecast_date ) {
				if ( null === $min_temperature || $forecast->main->temp_min < $min_temperature ) {
					$min_temperature = $forecast->main->temp_min;
				}
				if ( null === $max_temperature || $forecast->main->temp_max > $max_temperature ) {
					$max_temperature = $forecast->main->temp_max;
				}
			}
		}

		if ( null === $min_temperature ) {
			$min_temperature = $weather_data_now->main->temp_min;
		}
		if ( null === $max_temperature ) {
			$max_temperature = $weather_data_now->main->temp_max;
		}
		?>
		<div class="wpr-weather-wrap">
			<div class="wpr-weather-header">
				<span><?php echo esc_html( $settings['widget_title'] ); ?></span>
			</div>
			<div class="wpr-weather-content">
				<div class="wpr-weather-info-wrap">
					<div class="wpr-weather-info">
						<div class="wpr-weather-icon">
							<?php $this->weather_icon_markup( $icon_name ); ?>
						</div>
						<h2 class="wpr-weather-temp"><?php echo esc_html( $temperature ); ?></h2>
					</div>
					<div class="wpr-weather-info">
						<div>
							<h3 class="wpr-weather-location"><?php echo esc_html( ucfirst( $city_name ) ); ?></h3>
							<div class="wpr-weather-condition"><?php echo esc_html( $condition ); ?></div>
						</div>
						<div class="wpr-weather-extra-info">
							<div class="wpr-weather-extra-info-item">
								<span class="wpr-weather-extra-info-icon" aria-hidden="true">
									<?php $this->weather_icon_markup( 'thermometer' ); ?>
								</span>
								<span class="wpr-weather-extra-info-text">
									<?php
									printf(
										esc_html__( '%1$s° - %2$s°', 'wpr-addons' ),
										esc_html( round( $max_temperature ) ),
										esc_html( round( $min_temperature ) )
									);
									?>
								</span>
							</div>
							<div class="wpr-weather-extra-info-item">
								<span class="wpr-weather-extra-info-icon" aria-hidden="true">
									<?php $this->weather_icon_markup( 'raindrop' ); ?>
								</span>
								<span class="wpr-weather-extra-info-text"><?php echo esc_html( $humidity ); ?>%</span>
							</div>
							<div class="wpr-weather-extra-info-item">
								<span class="wpr-weather-extra-info-icon" aria-hidden="true">
									<?php $this->weather_icon_markup( 'windy' ); ?>
								</span>
								<span class="wpr-weather-extra-info-text"><?php echo esc_html( $wind_speed ); ?> km/h</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="wpr-weather-footer">
				<?php $this->render_forecast( $weather_data_forecast, $settings ); ?>
			</div>
		</div>
		<?php
	}

	private function render_forecast( $forecast_data, $settings ) {
		$forecast_days_count = isset( $settings['forecast'] ) ? $settings['forecast'] : '3';
		if ( 'none' === $forecast_days_count || empty( $forecast_data->list ) ) {
			return;
		}

		$forecast_days = [];
		$today_date = gmdate( 'Ymd', current_time( 'timestamp', 0 ) );
		$count = 0;

		foreach ( $forecast_data->list as $day ) {
			$day_of_week = gmdate( 'Ymd', $day->dt );
			$forecast_hour = gmdate( 'H', $day->dt );

			if ( empty( $forecast_days[ $day_of_week ] ) ) {
				$forecast_days[ $day_of_week ] = [
					'dt' => $day->dt,
					'icon' => $day->weather[0]->icon,
					'temp' => $day->main->temp,
					'is_today' => ( $today_date === $day_of_week ),
				];
			} elseif ( '12' === $forecast_hour || '13' === $forecast_hour ) {
				$forecast_days[ $day_of_week ]['temp'] = $day->main->temp;
				$forecast_days[ $day_of_week ]['icon'] = $day->weather[0]->icon;
			}
		}

		foreach ( $forecast_days as $forecast_day ) {
			if ( $forecast_day['is_today'] ) {
				continue;
			}
			if ( $count >= (int) $forecast_days_count ) {
				break;
			}

			$daily_icon_name = $this->get_weather_icon_name_by_code( $forecast_day['icon'] );
			$day_unit = 'cel' === $settings['temperature_unit'] ? '°C' : '°F';
			?>
			<div class="wpr-weather-forecast">
				<div class="wpr-weather-forecast-day"><?php echo esc_html( gmdate( 'D', $forecast_day['dt'] ) ); ?></div>
				<div class="wpr-weather-forecast-icon"><?php $this->weather_icon_markup( $daily_icon_name, 'forecast-' . $count ); ?></div>
				<div class="wpr-weather-forecast-temp"><?php echo esc_html( round( $forecast_day['temp'] ) . $day_unit ); ?></div>
			</div>
			<?php
			$count++;
		}
	}

	private function render_error( $code ) {
		$error_message = '';
		if ( '404' === $code ) {
			$error_message = esc_html__( 'City not found. Please type again.', 'wpr-addons' );
		} elseif ( '400' === $code ) {
			$error_message = esc_html__( 'Bad request. Please check your parameters.', 'wpr-addons' );
		} elseif ( '401' === $code ) {
			$error_message = esc_html__( 'Invalid API key. Please check your API key.', 'wpr-addons' );
		} elseif ( '429' === $code ) {
			$error_message = esc_html__( 'Too many requests. Please try again later.', 'wpr-addons' );
		}
		echo '<div class="wpr-weather-error">' . esc_html__( 'Weather Widget Error:', 'wpr-addons' ) . ' ' . esc_html( $error_message ) . '</div>';
	}

	private function render_demo_content( $settings ) {
		$title = ! empty( $settings['widget_title'] ) ? $settings['widget_title'] : esc_html__( 'Weather Widget', 'wpr-addons' );
		$forecast_days_count = isset( $settings['forecast'] ) ? $settings['forecast'] : '3';
		$demo_days = [
			[
				'day' => 'Mon',
				'icon' => 'rain',
				'temp' => '7°C',
			],
			[
				'day' => 'Tue',
				'icon' => 'day-sunny',
				'temp' => '4°C',
			],
			[
				'day' => 'Wed',
				'icon' => 'cloudy',
				'temp' => '5°C',
			],
			[
				'day' => 'Thu',
				'icon' => 'rain',
				'temp' => '9°C',
			],
			[
				'day' => 'Fri',
				'icon' => 'rain',
				'temp' => '10°C',
			],
		];
		?>
		<div class="wpr-weather-wrap">
			<div class="wpr-weather-header"><span><?php echo esc_html( $title ); ?></span></div>
			<div class="wpr-weather-content">
				<div class="wpr-weather-info-wrap">
					<div class="wpr-weather-info">
						<div class="wpr-weather-icon"><?php $this->weather_icon_markup( 'day-sunny' ); ?></div>
						<h2 class="wpr-weather-temp">14°C</h2>
					</div>
					<div class="wpr-weather-info">
						<div>
							<h3 class="wpr-weather-location">New York</h3>
							<div class="wpr-weather-condition">clear sky</div>
						</div>
						<div class="wpr-weather-extra-info">
							<div class="wpr-weather-extra-info-item">
								<span class="wpr-weather-extra-info-icon" aria-hidden="true"><?php $this->weather_icon_markup( 'thermometer' ); ?></span>
								<span class="wpr-weather-extra-info-text">5° - 11°</span>
							</div>
							<div class="wpr-weather-extra-info-item">
								<span class="wpr-weather-extra-info-icon" aria-hidden="true"><?php $this->weather_icon_markup( 'raindrop' ); ?></span>
								<span class="wpr-weather-extra-info-text">46%</span>
							</div>
							<div class="wpr-weather-extra-info-item">
								<span class="wpr-weather-extra-info-icon" aria-hidden="true"><?php $this->weather_icon_markup( 'windy' ); ?></span>
								<span class="wpr-weather-extra-info-text">4 km/h</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php if ( 'none' !== $forecast_days_count ) : ?>
				<div class="wpr-weather-footer">
					<?php
					$max_demo_days = min( (int) $forecast_days_count, count( $demo_days ) );
					for ( $i = 0; $i < $max_demo_days; $i++ ) :
						$demo_day = $demo_days[ $i ];
						?>
						<div class="wpr-weather-forecast">
							<div class="wpr-weather-forecast-day"><?php echo esc_html( $demo_day['day'] ); ?></div>
							<div class="wpr-weather-forecast-icon"><?php $this->weather_icon_markup( $demo_day['icon'], 'demo-' . ( $i + 1 ) ); ?></div>
							<div class="wpr-weather-forecast-temp"><?php echo esc_html( $demo_day['temp'] ); ?></div>
						</div>
					<?php endfor; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	private function get_openweather_api_key( $settings = [] ) {
		$api_key = sanitize_text_field( get_option( 'wpr_openweather_api_key', '' ) );

		if ( '' === $api_key && ! empty( $settings['weather_api_key'] ) ) {
			$api_key = sanitize_text_field( $settings['weather_api_key'] );
		}

		return trim( $api_key );
	}

	private function get_weather_data( $city_name = '', $unit = 'cel', $api_key = '', $minimal = false ) {
		$api_key = trim( (string) $api_key );
		$transient_key = 'wpr_weather_data_' . md5( $city_name . '_' . $unit . '_' . $api_key . '_' . (int) $minimal );

		$weather_data = get_transient( $transient_key );
		if ( false !== $weather_data ) {
			return $weather_data;
		}

		$weather_data = [
			'now' => [],
			'forecast' => [],
		];
		$api_unit = 'cel' === $unit ? 'metric' : 'imperial';

		$now_ping = 'https://api.openweathermap.org/data/2.5/weather?q=' . rawurlencode( $city_name ) . '&lang=en&units=' . rawurlencode( $api_unit ) . '&APPID=' . rawurlencode( $api_key );
		$now_response = wp_remote_get(
			$now_ping,
			[
				'timeout' => 120,
			]
		);

		if ( ! is_wp_error( $now_response ) ) {
			$weather_data['now'] = json_decode( wp_remote_retrieve_body( $now_response ) );
		}

		if ( ! $minimal ) {
			$forecast_ping = 'https://api.openweathermap.org/data/2.5/forecast?q=' . rawurlencode( $city_name ) . '&lang=en&units=' . rawurlencode( $api_unit ) . '&APPID=' . rawurlencode( $api_key );
			$forecast_response = wp_remote_get(
				$forecast_ping,
				[
					'timeout' => 120,
				]
			);

			if ( ! is_wp_error( $forecast_response ) ) {
				$weather_data['forecast'] = json_decode( wp_remote_retrieve_body( $forecast_response ) );
			}
		}

		if ( ! empty( $weather_data['now'] ) || ! empty( $weather_data['forecast'] ) ) {
			set_transient( $transient_key, $weather_data, HOUR_IN_SECONDS );
		}

		return $weather_data;
	}

	private function get_weather_icon_name_by_code( $code ) {
		$icon_map = [
			'01d' => 'day-sunny',
			'01n' => 'moon-full',
			'02d' => 'day-cloudy',
			'02n' => 'night-cloudy',
			'04d' => 'cloudy',
			'04n' => 'cloudy',
			'09d' => 'rain',
			'09n' => 'rain',
			'10d' => 'day-rain',
			'10n' => 'night-rain',
			'11d' => 'storm-showers',
			'11n' => 'storm-showers',
			'13d' => 'day-snow',
			'13n' => 'night-alt-snow',
			'50d' => 'day-fog',
			'50n' => 'night-fog',
		];

		return isset( $icon_map[ $code ] ) ? $icon_map[ $code ] : 'cloudy';
	}

	private function weather_icon_markup( $name = '', $id = 'weather' ) {
		switch ( $name ) {
			case 'day-sunny':
				echo '<svg class="wpr-weather-svg wpr-weather-svg-day-sunny" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><g><path fill="var(--wpr-weather-icon-warm)" d="M32 23.36A8.64 8.64 0 1123.36 32 8.66 8.66 0 0132 23.36m0-3A11.64 11.64 0 1043.64 32 11.64 11.64 0 0032 20.36z"/><path fill="none" stroke="var(--wpr-weather-icon-warm)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" d="M32 15.71V9.5"><animate attributeName="stroke-dasharray" calcMode="spline" dur="5s" keySplines="0.5 0 0.5 1; 0.5 0 0.5 1" keyTimes="0; .5; 1" repeatCount="indefinite" values="3 6; 6 6; 3 6"/></path><path fill="none" stroke="var(--wpr-weather-icon-warm)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" d="M32 48.29v6.21"><animate attributeName="stroke-dasharray" calcMode="spline" dur="5s" keySplines="0.5 0 0.5 1; 0.5 0 0.5 1" keyTimes="0; .5; 1" repeatCount="indefinite" values="3 6; 6 6; 3 6"/></path><path fill="none" stroke="var(--wpr-weather-icon-warm)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" d="M43.52 20.48l4.39-4.39"><animate attributeName="stroke-dasharray" calcMode="spline" dur="5s" keySplines="0.5 0 0.5 1; 0.5 0 0.5 1" keyTimes="0; .5; 1" repeatCount="indefinite" values="3 6; 6 6; 3 6"/></path><path fill="none" stroke="var(--wpr-weather-icon-warm)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" d="M20.48 43.52l-4.39 4.39"><animate attributeName="stroke-dasharray" calcMode="spline" dur="5s" keySplines="0.5 0 0.5 1; 0.5 0 0.5 1" keyTimes="0; .5; 1" repeatCount="indefinite" values="3 6; 6 6; 3 6"/></path><path fill="none" stroke="var(--wpr-weather-icon-warm)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" d="M20.48 20.48l-4.39-4.39"><animate attributeName="stroke-dasharray" calcMode="spline" dur="5s" keySplines="0.5 0 0.5 1; 0.5 0 0.5 1" keyTimes="0; .5; 1" repeatCount="indefinite" values="3 6; 6 6; 3 6"/></path><path fill="none" stroke="var(--wpr-weather-icon-warm)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" d="M43.52 43.52l4.39 4.39"><animate attributeName="stroke-dasharray" calcMode="spline" dur="5s" keySplines="0.5 0 0.5 1; 0.5 0 0.5 1" keyTimes="0; .5; 1" repeatCount="indefinite" values="3 6; 6 6; 3 6"/></path><path fill="none" stroke="var(--wpr-weather-icon-warm)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" d="M15.71 32H9.5"><animate attributeName="stroke-dasharray" calcMode="spline" dur="5s" keySplines="0.5 0 0.5 1; 0.5 0 0.5 1" keyTimes="0; .5; 1" repeatCount="indefinite" values="3 6; 6 6; 3 6"/></path><path fill="none" stroke="var(--wpr-weather-icon-warm)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" d="M48.29 32h6.21"><animate attributeName="stroke-dasharray" calcMode="spline" dur="5s" keySplines="0.5 0 0.5 1; 0.5 0 0.5 1" keyTimes="0; .5; 1" repeatCount="indefinite" values="3 6; 6 6; 3 6"/></path><animateTransform attributeName="transform" dur="45s" from="0 32 32" repeatCount="indefinite" to="360 32 32" type="rotate"/></g></svg>';
				break;
			case 'moon-full':
				echo '<svg class="wpr-weather-svg wpr-weather-svg-moon-full" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><g><path fill="none" stroke="var(--wpr-weather-icon-night)" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M46.66 36.2a16.66 16.66 0 01-16.78-16.55 16.29 16.29 0 01.55-4.15A16.56 16.56 0 1048.5 36.1c-.61.06-1.22.1-1.84.1z"/></g></svg>';
				break;
			case 'day-cloudy':
				echo '<svg class="wpr-weather-svg wpr-weather-svg-day-cloudy" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><defs><clipPath id="' . esc_attr( $id ) . '"><path fill="none" d="M12 35l-8-1-1-10 2-8 5-4 4.72-2.21h6L29 10l4 3v7l-6 4h-6l-3 3v4l-4 2-2 2z"/></clipPath><clipPath id="' . esc_attr( $id ) . '-2"><path fill="none" d="M41.8 20.25l4.48 6.61.22 4.64 5.31 2.45 1.69 5.97h8.08L61 27l-9.31-8.5-9.89 1.75z"/></clipPath></defs><g clip-path="url(#' . esc_attr( $id ) . ')"><g><g><path fill="var(--wpr-weather-icon-warm)" d="M19 20.05A3.95 3.95 0 1115.05 24 4 4 0 0119 20.05m0-2A5.95 5.95 0 1025 24a5.95 5.95 0 00-6-5.95z"/><path fill="none" stroke="var(--wpr-weather-icon-warm)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M19 15.67V12.5M19 35.5v-3.17M24.89 18.11l2.24-2.24M10.87 32.13l2.24-2.24M13.11 18.11l-2.24-2.24M27.13 32.13l-2.24-2.24M10.67 24H7.5M30.5 24h-3.17"/><animateTransform attributeName="transform" dur="45s" from="0 19.22 24.293" repeatCount="indefinite" to="360 19.22 24.293" type="rotate"/></g><animateTransform attributeName="transform" dur="7s" repeatCount="indefinite" type="translate" values="3 0; -3 0; 3 0"/></g><animateTransform attributeName="transform" dur="7s" repeatCount="indefinite" type="translate" values="-3 0; 3 0; -3 0"/></g><g clip-path="url(#' . esc_attr( $id ) . '-2)"><path fill="none" stroke="var(--wpr-weather-icon-cloud-secondary)" stroke-linejoin="round" stroke-width="2" d="M34.23 33.45a4.05 4.05 0 004.05 4h16.51a4.34 4.34 0 00.81-8.61 3.52 3.52 0 00.06-.66 4.06 4.06 0 00-6.13-3.48 6.08 6.08 0 00-11.25 3.19 6.34 6.34 0 00.18 1.46h-.18a4.05 4.05 0 00-4.05 4.1z"/><animateTransform attributeName="transform" dur="7s" repeatCount="indefinite" type="translate" values="-2.1 0; 2.1 0; -2.1 0"/></g><g><path fill="none" stroke="var(--wpr-weather-icon-cloud)" stroke-linejoin="round" stroke-width="3" d="M46.5 31.5h-.32a10.49 10.49 0 00-19.11-8 7 7 0 00-10.57 6 7.21 7.21 0 00.1 1.14A7.5 7.5 0 0018 45.5a4.19 4.19 0 00.5 0v0h28a7 7 0 000-14z"/><animateTransform attributeName="transform" dur="7s" repeatCount="indefinite" type="translate" values="-3 0; 3 0; -3 0"/></g></svg>';
				break;
			case 'night-cloudy':
				echo '<svg class="wpr-weather-svg wpr-weather-svg-night-cloudy" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><defs><clipPath id="' . esc_attr( $id ) . '"><path fill="none" d="M12 35l-5.28-4.21-2-6 1-7 4-5 5-3h6l5 1 3 3L33 20l-6 4h-6l-3 3v4l-4 2-2 2z"/></clipPath><clipPath id="' . esc_attr( $id ) . '-2"><path fill="none" d="M41.8 20.25l4.48 6.61.22 4.64 5.31 2.45 1.69 5.97h8.08L61 27l-9.31-8.5-9.89 1.75z"/></clipPath></defs><g clip-path="url(#' . esc_attr( $id ) . ')"><g><g><path fill="none" stroke="var(--wpr-weather-icon-night)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M29.33 26.68a10.61 10.61 0 01-10.68-10.54A10.5 10.5 0 0119 13.5a10.54 10.54 0 1011.5 13.11 11.48 11.48 0 01-1.17.07z"/><animateTransform attributeName="transform" dur="10s" repeatCount="indefinite" type="rotate" values="-10 19.22 24.293;10 19.22 24.293;-10 19.22 24.293"/></g><animateTransform attributeName="transform" dur="7s" repeatCount="indefinite" type="translate" values="3 0; -3 0; 3 0"/></g><animateTransform attributeName="transform" dur="7s" repeatCount="indefinite" type="translate" values="-3 0; 3 0; -3 0"/></g><g clip-path="url(#' . esc_attr( $id ) . '-2)"><path fill="none" stroke="var(--wpr-weather-icon-cloud-secondary)" stroke-linejoin="round" stroke-width="2" d="M34.23 33.45a4.05 4.05 0 004.05 4h16.51a4.34 4.34 0 00.81-8.61 3.52 3.52 0 00.06-.66 4.06 4.06 0 00-6.13-3.48 6.08 6.08 0 00-11.25 3.19 6.34 6.34 0 00.18 1.46h-.18a4.05 4.05 0 00-4.05 4.1z"/><animateTransform attributeName="transform" dur="7s" repeatCount="indefinite" type="translate" values="-2.1 0; 2.1 0; -2.1 0"/></g><g><path fill="none" stroke="var(--wpr-weather-icon-cloud)" stroke-linejoin="round" stroke-width="3" d="M46.5 31.5h-.32a10.49 10.49 0 00-19.11-8 7 7 0 00-10.57 6 7.21 7.21 0 00.1 1.14A7.5 7.5 0 0018 45.5a4.19 4.19 0 00.5 0v0h28a7 7 0 000-14z"/><animateTransform attributeName="transform" dur="7s" repeatCount="indefinite" type="translate" values="-3 0; 3 0; -3 0"/></g></svg>';
				break;
			case 'cloudy':
				echo '<svg class="wpr-weather-svg wpr-weather-svg-cloudy" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><defs><clipPath id="' . esc_attr( $id ) . '"><path fill="none" d="M41.8 20.25l4.48 6.61.22 4.64 5.31 2.45 1.69 5.97h8.08L61 27l-9.31-8.5-9.89 1.75z"/></clipPath></defs><g clip-path="url(#' . esc_attr( $id ) . ')"><path fill="none" stroke="var(--wpr-weather-icon-cloud-secondary)" stroke-linejoin="round" stroke-width="2" d="M34.23 33.45a4.05 4.05 0 004.05 4h16.51a4.34 4.34 0 00.81-8.61 3.52 3.52 0 00.06-.66 4.06 4.06 0 00-6.13-3.48 6.08 6.08 0 00-11.25 3.19 6.34 6.34 0 00.18 1.46h-.18a4.05 4.05 0 00-4.05 4.1z"/><animateTransform attributeName="transform" dur="7s" repeatCount="indefinite" type="translate" values="-2.1 0; 2.1 0; -2.1 0"/></g><g><path fill="none" stroke="var(--wpr-weather-icon-cloud)" stroke-linejoin="round" stroke-width="3" d="M46.5 31.5h-.32a10.49 10.49 0 00-19.11-8 7 7 0 00-10.57 6 7.21 7.21 0 00.1 1.14A7.5 7.5 0 0018 45.5a4.19 4.19 0 00.5 0v0h28a7 7 0 000-14z"/><animateTransform attributeName="transform" dur="7s" repeatCount="indefinite" type="translate" values="-3 0; 3 0; -3 0"/></g></svg>';
				break;
			case 'day-rain':
				echo '<svg class="wpr-weather-svg wpr-weather-svg-day-rain" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><defs><clipPath id="' . esc_attr( $id ) . '"><path fill="none" d="M12 35l-5.28-4.21-2-6 1-7 4-5 5-3h6l5 1 3 3L33 20l-6 4h-6l-3 3v4l-4 2-2 2z"/></clipPath></defs><g clip-path="url(#' . esc_attr( $id ) . ')"><g><path fill="var(--wpr-weather-icon-warm)" d="M19 20.05A3.95 3.95 0 1115.05 24 4 4 0 0119 20.05m0-2A5.95 5.95 0 1025 24a5.95 5.95 0 00-6-5.95z"/><path fill="none" stroke="var(--wpr-weather-icon-warm)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M19 15.67V12.5M19 35.5v-3.17M24.89 18.11l2.24-2.24M10.87 32.13l2.24-2.24M13.11 18.11l-2.24-2.24M27.13 32.13l-2.24-2.24M10.67 24H7.5M30.5 24h-3.17"/><animateTransform attributeName="transform" dur="45s" from="0 19.22 24.293" repeatCount="indefinite" to="360 19.22 24.293" type="rotate"/></g></g><path fill="none" stroke="var(--wpr-weather-icon-cloud)" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M43.67 45.5h2.83a7 7 0 000-14h-.32a10.49 10.49 0 00-19.11-8 7 7 0 00-10.57 6 7.21 7.21 0 00.1 1.14A7.5 7.5 0 0018 45.5a4.19 4.19 0 00.5 0v0"/><g><path fill="none" stroke="var(--wpr-weather-icon-rain)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M24.08 45.01l-.16.98"/><animateTransform attributeName="transform" dur="1.5s" repeatCount="indefinite" type="translate" values="1 -5; -2 10"/><animate attributeName="opacity" dur="1.5s" repeatCount="indefinite" values="0;1;1;0"/></g><g><path fill="none" stroke="var(--wpr-weather-icon-rain)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M31.08 45.01l-.16.98"/><animateTransform attributeName="transform" begin="-0.5s" dur="1.5s" repeatCount="indefinite" type="translate" values="1 -5; -2 10"/><animate attributeName="opacity" begin="-0.5s" dur="1.5s" repeatCount="indefinite" values="0;1;1;0"/></g><g><path fill="none" stroke="var(--wpr-weather-icon-rain)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M38.08 45.01l-.16.98"/><animateTransform attributeName="transform" begin="-1s" dur="1.5s" repeatCount="indefinite" type="translate" values="1 -5; -2 10"/><animate attributeName="opacity" begin="-1s" dur="1.5s" repeatCount="indefinite" values="0;1;1;0"/></g></svg>';
				break;
			case 'night-rain':
				echo '<svg class="wpr-weather-svg wpr-weather-svg-night-rain" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><defs><clipPath id="' . esc_attr( $id ) . '"><path fill="none" d="M12 35l-5.28-4.21-2-6 1-7 4-5 5-3h6l5 1 3 3L33 20l-6 4h-6l-3 3v4l-4 2-2 2z"/></clipPath></defs><g clip-path="url(#' . esc_attr( $id ) . ')"><g><path fill="none" stroke="var(--wpr-weather-icon-night)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M29.33 26.68a10.61 10.61 0 01-10.68-10.54A10.5 10.5 0 0119 13.5a10.54 10.54 0 1011.5 13.11 11.48 11.48 0 01-1.17.07z"/><animateTransform attributeName="transform" dur="10s" repeatCount="indefinite" type="rotate" values="-10 19.22 24.293;10 19.22 24.293;-10 19.22 24.293"/></g></g><path fill="none" stroke="var(--wpr-weather-icon-cloud)" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M43.67 45.5h2.83a7 7 0 000-14h-.32a10.49 10.49 0 00-19.11-8 7 7 0 00-10.57 6 7.21 7.21 0 00.1 1.14A7.5 7.5 0 0018 45.5a4.19 4.19 0 00.5 0v0"/><g><path fill="none" stroke="var(--wpr-weather-icon-rain)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M24.08 45.01l-.16.98"/><animateTransform attributeName="transform" dur="1.5s" repeatCount="indefinite" type="translate" values="1 -5; -2 10"/><animate attributeName="opacity" dur="1.5s" repeatCount="indefinite" values="0;1;1;0"/></g><g><path fill="none" stroke="var(--wpr-weather-icon-rain)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M31.08 45.01l-.16.98"/><animateTransform attributeName="transform" begin="-0.5s" dur="1.5s" repeatCount="indefinite" type="translate" values="1 -5; -2 10"/><animate attributeName="opacity" begin="-0.5s" dur="1.5s" repeatCount="indefinite" values="0;1;1;0"/></g><g><path fill="none" stroke="var(--wpr-weather-icon-rain)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M38.08 45.01l-.16.98"/><animateTransform attributeName="transform" begin="-1s" dur="1.5s" repeatCount="indefinite" type="translate" values="1 -5; -2 10"/><animate attributeName="opacity" begin="-1s" dur="1.5s" repeatCount="indefinite" values="0;1;1;0"/></g></svg>';
				break;
			case 'rain':
				echo '<svg class="wpr-weather-svg wpr-weather-svg-rain" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><path fill="none" stroke="var(--wpr-weather-icon-cloud)" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M43.67 45.5h2.83a7 7 0 000-14h-.32a10.49 10.49 0 00-19.11-8 7 7 0 00-10.57 6 7.21 7.21 0 00.1 1.14A7.5 7.5 0 0018 45.5a4.19 4.19 0 00.5 0v0"/><g><path fill="none" stroke="var(--wpr-weather-icon-rain)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M24.39 43.03l-.78 4.94"/><animateTransform attributeName="transform" dur="0.7s" repeatCount="indefinite" type="translate" values="1 -5; -2 10"/><animate attributeName="opacity" dur="0.7s" repeatCount="indefinite" values="0;1;1;0"/></g><g><path fill="none" stroke="var(--wpr-weather-icon-rain)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M31.39 43.03l-.78 4.94"/><animateTransform attributeName="transform" begin="-0.4s" dur="0.7s" repeatCount="indefinite" type="translate" values="1 -5; -2 10"/><animate attributeName="opacity" begin="-0.4s" dur="0.7s" repeatCount="indefinite" values="0;1;1;0"/></g><g><path fill="none" stroke="var(--wpr-weather-icon-rain)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M38.39 43.03l-.78 4.94"/><animateTransform attributeName="transform" begin="-0.2s" dur="0.7s" repeatCount="indefinite" type="translate" values="1 -5; -2 10"/><animate attributeName="opacity" begin="-0.2s" dur="0.7s" repeatCount="indefinite" values="0;1;1;0"/></g></svg>';
				break;
			case 'storm-showers':
				echo '<svg class="wpr-weather-svg wpr-weather-svg-storm" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><path fill="none" stroke="var(--wpr-weather-icon-cloud)" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M43.67 45.5h2.83a7 7 0 000-14h-.32a10.49 10.49 0 00-19.11-8 7 7 0 00-10.57 6 7.21 7.21 0 00.1 1.14A7.5 7.5 0 0018 45.5a4.19 4.19 0 00.5 0v0"/><path fill="var(--wpr-weather-icon-warm)" d="M30 36l-4 12h4l-2 10 10-14h-6l4-8h-6z"/></svg>';
				break;
			case 'day-snow':
			case 'night-alt-snow':
				echo '<svg class="wpr-weather-svg wpr-weather-svg-snow" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><path fill="none" stroke="var(--wpr-weather-icon-cloud)" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M43.67 45.5h2.83a7 7 0 000-14h-.32a10.49 10.49 0 00-19.11-8 7 7 0 00-10.57 6 7.21 7.21 0 00.1 1.14A7.5 7.5 0 0018 45.5a4.19 4.19 0 00.5 0v0"/><circle cx="24" cy="46" r="2" fill="var(--wpr-weather-icon-snow)"/><circle cx="31" cy="46" r="2" fill="var(--wpr-weather-icon-snow)"/><circle cx="38" cy="46" r="2" fill="var(--wpr-weather-icon-snow)"/></svg>';
				break;
			case 'day-fog':
			case 'night-fog':
				echo '<svg class="wpr-weather-svg wpr-weather-svg-fog" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><path fill="none" stroke="var(--wpr-weather-icon-cloud)" stroke-linejoin="round" stroke-width="3" d="M46.5 31.5h-.32a10.49 10.49 0 00-19.11-8 7 7 0 00-10.57 6 7.21 7.21 0 00.1 1.14A7.5 7.5 0 0018 45.5a4.19 4.19 0 00.5 0v0h28a7 7 0 000-14z"/><path fill="none" stroke="var(--wpr-weather-icon-fog-lines)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" d="M17 58h30"/><path fill="none" stroke="var(--wpr-weather-icon-fog-lines)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" d="M17 52h30"/></svg>';
				break;
			case 'thermometer':
				echo '<svg class="wpr-weather-svg wpr-weather-svg-thermometer" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><circle cx="32" cy="42" r="4" fill="var(--wpr-weather-icon-thermo-bulb)"/><path fill="none" stroke="var(--wpr-weather-icon-thermo-bulb)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M32 28.5v13"/><path fill="none" stroke="var(--wpr-weather-icon-thermo-tube)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M36 36.07v-17a4 4 0 10-8 0v17a7.12 7.12 0 00-3 5.83 7 7 0 1014 0 7.12 7.12 0 00-3-5.83zM32.5 25h3M32.5 21h3M32.5 29h3"/></svg>';
				break;
			case 'raindrop':
				echo '<svg class="wpr-weather-svg wpr-weather-svg-raindrop" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><path fill="none" stroke="var(--wpr-weather-icon-rain)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M32 17c-6.09 9-10 14.62-10 20.09a10 10 0 0020 0C42 31.62 38.09 26 32 17z"/></svg>';
				break;
			case 'windy':
				echo '<svg class="wpr-weather-svg wpr-weather-svg-windy" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 342 234"><path fill="none" stroke="var(--wpr-weather-icon-wind)" stroke-linecap="round" stroke-miterlimit="10" stroke-width="18" d="M264.2 21.3A40 40 0 11293 89H9m139.2 123.7A40 40 0 10177 145H9"/></svg>';
				break;
			default:
				echo '<svg class="wpr-weather-svg wpr-weather-svg-cloudy" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><path fill="none" stroke="var(--wpr-weather-icon-cloud)" stroke-linejoin="round" stroke-width="3" d="M46.5 31.5h-.32a10.49 10.49 0 00-19.11-8 7 7 0 00-10.57 6 7.21 7.21 0 00.1 1.14A7.5 7.5 0 0018 45.5a4.19 4.19 0 00.5 0v0h28a7 7 0 000-14z"/></svg>';
		}
	}
}
