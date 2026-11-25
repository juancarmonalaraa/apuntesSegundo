<?php

use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR_DC_Section_Woocommerce_Pro extends WPR_DC_Section_Woocommerce {

	public function register_controls( $element ) {
		$element->add_control(
			'wpr_dc_woo_cart',
			[
				'label'   => esc_html__( 'Cart Status', 'wpr-addons' ),
				'type'    => Controls_Manager::SELECT,
				'label_block' => true,
				'default' => '',
				'options' => [
					''          => esc_html__( 'Not Set', 'wpr-addons' ),
					'has_items' => esc_html__( 'Cart has items', 'wpr-addons' ),
					'is_empty'  => esc_html__( 'Cart is empty', 'wpr-addons' ),
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_woo_cart_products',
			[
				'label'       => esc_html__( 'Products in Cart', 'wpr-addons' ),
				'description' => esc_html__( 'Comma-separated product IDs.', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_woo_cart_categories',
			[
				'label'       => esc_html__( 'Product Categories in Cart', 'wpr-addons' ),
				'description' => esc_html__( 'Comma-separated category slugs.', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_woo_product_type',
			[
				'label'       => esc_html__( 'Product Type', 'wpr-addons' ),
				'description' => esc_html__( 'Match product pages of these types.', 'wpr-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'default'     => [],
				'options'     => [
					'simple'   => esc_html__( 'Simple', 'wpr-addons' ),
					'variable' => esc_html__( 'Variable', 'wpr-addons' ),
					'grouped'  => esc_html__( 'Grouped', 'wpr-addons' ),
					'external' => esc_html__( 'External / Affiliate', 'wpr-addons' ),
				],
				'separator'   => 'before',
				'render_type' => 'none',
			]
		);
	}

	public function evaluate( $settings ) {
		$cart_status   = ! empty( $settings['wpr_dc_woo_cart'] ) ? $settings['wpr_dc_woo_cart'] : '';
		$cart_products = ! empty( $settings['wpr_dc_woo_cart_products'] ) ? trim( $settings['wpr_dc_woo_cart_products'] ) : '';
		$cart_cats     = ! empty( $settings['wpr_dc_woo_cart_categories'] ) ? trim( $settings['wpr_dc_woo_cart_categories'] ) : '';
		$product_types = ! empty( $settings['wpr_dc_woo_product_type'] ) ? $settings['wpr_dc_woo_product_type'] : [];

		if ( '' === $cart_status && '' === $cart_products && '' === $cart_cats && empty( $product_types ) ) {
			return null;
		}

		$results = [];

		if ( '' !== $cart_status ) {
			$is_empty  = ! WC()->cart || WC()->cart->is_empty();
			$results[] = ( 'is_empty' === $cart_status ) ? $is_empty : ! $is_empty;
		}

		if ( '' !== $cart_products && WC()->cart ) {
			$results[] = $this->check_products_in_cart( $cart_products );
		}

		if ( '' !== $cart_cats && WC()->cart ) {
			$results[] = $this->check_categories_in_cart( $cart_cats );
		}

		if ( ! empty( $product_types ) ) {
			$results[] = $this->check_product_type( $product_types );
		}

		if ( empty( $results ) ) {
			return null;
		}

		return ! in_array( false, $results, true );
	}

	private function check_products_in_cart( $ids_str ) {
		$target_ids = array_map( 'absint', array_map( 'trim', explode( ',', $ids_str ) ) );
		$cart_items = WC()->cart->get_cart();

		foreach ( $cart_items as $item ) {
			if ( in_array( $item['product_id'], $target_ids, true ) ) {
				return true;
			}
			if ( ! empty( $item['variation_id'] ) && in_array( $item['variation_id'], $target_ids, true ) ) {
				return true;
			}
		}

		return false;
	}

	private function check_categories_in_cart( $cats_str ) {
		$target_cats = array_map( 'trim', explode( ',', strtolower( $cats_str ) ) );
		$cart_items  = WC()->cart->get_cart();

		foreach ( $cart_items as $item ) {
			$terms = get_the_terms( $item['product_id'], 'product_cat' );
			if ( $terms && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					if ( in_array( strtolower( $term->slug ), $target_cats, true ) ) {
						return true;
					}
				}
			}
		}

		return false;
	}

	private function check_product_type( $types ) {
		if ( ! is_singular( 'product' ) ) {
			return false;
		}

		global $product;

		if ( ! $product ) {
			$product = wc_get_product( get_the_ID() );
		}

		if ( ! $product ) {
			return false;
		}

		return in_array( $product->get_type(), $types, true );
	}
}
