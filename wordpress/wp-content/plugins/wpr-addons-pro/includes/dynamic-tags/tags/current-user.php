<?php
use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag as Base_Tag;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpr_Current_User extends Base_Tag {
	public function get_name() {
		return 'wpr-current-user';
	}

	public function get_title() {
		return __( 'Current User', 'wpr-addons' );
	}

	public function get_group() {
		return 'wpr_addons_current_user';
	}

	public function get_categories() {
		return [
			'text',
			'url',
		];
	}

	protected function register_controls() {
		$this->add_control(
			'wpr_user_field',
			[
				'label' => esc_html__( 'User Field', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'display_name' => esc_html__( 'Display Name', 'wpr-addons' ),
					'first_name' => esc_html__( 'First Name', 'wpr-addons' ),
					'last_name' => esc_html__( 'Last Name', 'wpr-addons' ),
					'nickname' => esc_html__( 'Nickname', 'wpr-addons' ),
					'user_login' => esc_html__( 'Username', 'wpr-addons' ),
					'user_email' => esc_html__( 'Email', 'wpr-addons' ),
					'user_url' => esc_html__( 'Website', 'wpr-addons' ),
					'description' => esc_html__( 'Biography', 'wpr-addons' ),
					'ID' => esc_html__( 'User ID', 'wpr-addons' ),
					'author_archive_url' => esc_html__( 'Author Archive URL', 'wpr-addons' ),
					'custom_meta' => esc_html__( 'Custom Meta', 'wpr-addons' ),
				],
				'default' => 'display_name',
			]
		);

		$this->add_control(
			'wpr_user_custom_meta',
			[
				'label' => esc_html__( 'Meta Key', 'wpr-addons' ),
				'description' => esc_html__( 'Enter the user meta key, e.g. billing_city, phone.', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'condition' => [
					'wpr_user_field' => 'custom_meta',
				],
			]
		);

		$this->add_control(
			'wpr_user_guest_fallback',
			[
				'label' => esc_html__( 'Guest Fallback', 'wpr-addons' ),
				'description' => esc_html__( 'Shown when the visitor is not logged in, or when the selected field is empty.', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => '',
				'placeholder' => esc_html__( 'Guest', 'wpr-addons' ),
			]
		);
	}

	public function render() {
		$settings = $this->get_settings();
		$fallback = isset( $settings['wpr_user_guest_fallback'] ) ? $settings['wpr_user_guest_fallback'] : '';

		if ( ! is_user_logged_in() ) {
			if ( '' !== $fallback ) {
				echo wp_kses_post( $fallback );
			}
			return;
		}

		$user = wp_get_current_user();
		$field = $settings['wpr_user_field'];

		if ( 'custom_meta' === $field ) {
			$meta_key = ! empty( $settings['wpr_user_custom_meta'] ) ? trim( $settings['wpr_user_custom_meta'] ) : '';

			if ( '' === $meta_key ) {
				return;
			}

			$value = get_user_meta( $user->ID, $meta_key, true );
		} elseif ( 'author_archive_url' === $field ) {
			$value = get_author_posts_url( $user->ID );
		} elseif ( 'ID' === $field ) {
			$value = (string) $user->ID;
		} elseif ( in_array( $field, [ 'first_name', 'last_name', 'nickname', 'description' ], true ) ) {
			$value = get_user_meta( $user->ID, $field, true );
		} else {
			$value = isset( $user->$field ) ? $user->$field : '';
		}

		if ( is_array( $value ) ) {
			$value = implode( ', ', $value );
		}

		if ( '' === $value || null === $value ) {
			if ( '' !== $fallback ) {
				echo wp_kses_post( $fallback );
			}
			return;
		}

		echo wp_kses_post( $value );
	}
}
