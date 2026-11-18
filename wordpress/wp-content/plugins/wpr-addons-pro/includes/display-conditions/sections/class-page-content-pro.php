<?php

use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR_DC_Section_Page_Content_Pro extends WPR_DC_Section_Page_Content {

	public function register_controls( $element ) {
		$element->add_control(
			'wpr_dc_post_types',
			[
				'label'       => esc_html__( 'Content Types', 'wpr-addons' ),
				'description' => esc_html__( 'Select post types to match.', 'wpr-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'default'     => [],
				'options'     => $this->get_post_type_options(),
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_specific_posts',
			[
				'label'       => esc_html__( 'Specific Pages', 'wpr-addons' ),
				'description' => esc_html__( 'Enter comma-separated post/page IDs.', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_taxonomy',
			[
				'label'     => esc_html__( 'Taxonomy', 'wpr-addons' ),
				'type'      => Controls_Manager::SELECT,
				'label_block' => true,
				'default'   => '',
				'options'   => $this->get_taxonomy_options(),
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_terms',
			[
				'label'       => esc_html__( 'Required Terms', 'wpr-addons' ),
				'description' => esc_html__( 'Comma-separated term slugs or IDs.', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'condition'   => [
					'wpr_dc_taxonomy!' => '',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_meta_key',
			[
				'label'       => esc_html__( 'Custom Field Name', 'wpr-addons' ),
				'description' => esc_html__( 'Post meta key to check.', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'separator'   => 'before',
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_meta_operator',
			[
				'label'     => esc_html__( 'Condition', 'wpr-addons' ),
				'type'      => Controls_Manager::SELECT,
				'label_block' => true,
				'default'   => 'isset',
				'options'   => $this->get_comparison_operators(),
				'condition' => [
					'wpr_dc_meta_key!' => '',
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_meta_value',
			[
				'label'       => esc_html__( 'Value', 'wpr-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'condition'   => [
					'wpr_dc_meta_key!'      => '',
					'wpr_dc_meta_operator!' => [ 'isset', 'empty' ],
				],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wpr_dc_post_hierarchy',
			[
				'label'     => esc_html__( 'Post Position', 'wpr-addons' ),
				'type'      => Controls_Manager::SELECT,
				'label_block' => true,
				'default'   => '',
				'separator' => 'before',
				'options'   => [
					''         => esc_html__( 'Not Set', 'wpr-addons' ),
					'parent'   => esc_html__( 'Is Parent (has children)', 'wpr-addons' ),
					'root'     => esc_html__( 'Is Root (top level, no parent)', 'wpr-addons' ),
					'leaf'     => esc_html__( 'Is Leaf (no children)', 'wpr-addons' ),
					'child'    => esc_html__( 'Is Child (has a parent)', 'wpr-addons' ),
				],
				'render_type' => 'none',
			]
		);
	}

	public function evaluate( $settings ) {
		$post_types = ! empty( $settings['wpr_dc_post_types'] ) ? $settings['wpr_dc_post_types'] : [];
		$specific   = ! empty( $settings['wpr_dc_specific_posts'] ) ? trim( $settings['wpr_dc_specific_posts'] ) : '';
		$taxonomy   = ! empty( $settings['wpr_dc_taxonomy'] ) ? $settings['wpr_dc_taxonomy'] : '';
		$terms      = ! empty( $settings['wpr_dc_terms'] ) ? trim( $settings['wpr_dc_terms'] ) : '';
		$meta_key   = ! empty( $settings['wpr_dc_meta_key'] ) ? trim( $settings['wpr_dc_meta_key'] ) : '';
		$hierarchy  = ! empty( $settings['wpr_dc_post_hierarchy'] ) ? $settings['wpr_dc_post_hierarchy'] : '';

		if ( empty( $post_types ) && '' === $specific && '' === $taxonomy && '' === $meta_key && '' === $hierarchy ) {
			return null;
		}

		$results = [];

		if ( ! empty( $post_types ) ) {
			$results[] = in_array( get_post_type(), $post_types, true );
		}

		if ( '' !== $specific ) {
			$ids = array_map( 'absint', array_map( 'trim', explode( ',', $specific ) ) );
			$results[] = in_array( get_the_ID(), $ids, true );
		}

		if ( '' !== $taxonomy && '' !== $terms ) {
			$term_list = array_map( 'trim', explode( ',', $terms ) );
			$has_match = false;
			foreach ( $term_list as $term ) {
				if ( is_numeric( $term ) ) {
					$term = (int) $term;
				}
				if ( has_term( $term, $taxonomy ) ) {
					$has_match = true;
					break;
				}
			}
			$results[] = $has_match;
		}

		if ( '' !== $meta_key ) {
			$actual   = get_post_meta( get_the_ID(), $meta_key, true );
			$operator = ! empty( $settings['wpr_dc_meta_operator'] ) ? $settings['wpr_dc_meta_operator'] : 'isset';
			$expected = isset( $settings['wpr_dc_meta_value'] ) ? $settings['wpr_dc_meta_value'] : '';
			$results[] = $this->compare( $actual, $operator, $expected );
		}

		if ( '' !== $hierarchy ) {
			$results[] = $this->check_hierarchy( $hierarchy );
		}

		if ( empty( $results ) ) {
			return null;
		}

		return ! in_array( false, $results, true );
	}

	private function check_hierarchy( $type ) {
		$post_id = get_the_ID();

		if ( ! $post_id ) {
			return false;
		}

		switch ( $type ) {
			case 'parent':
				$children = get_children( [ 'post_parent' => $post_id, 'numberposts' => 1 ] );
				return ! empty( $children );
			case 'root':
				return 0 === (int) wp_get_post_parent_id( $post_id );
			case 'leaf':
				$children = get_children( [ 'post_parent' => $post_id, 'numberposts' => 1 ] );
				return empty( $children );
			case 'child':
				return 0 !== (int) wp_get_post_parent_id( $post_id );
			default:
				return true;
		}
	}

	private function get_post_type_options() {
		$options    = [];
		$post_types = get_post_types( [ 'public' => true ], 'objects' );

		foreach ( $post_types as $pt ) {
			$options[ $pt->name ] = $pt->label;
		}

		return $options;
	}

	private function get_taxonomy_options() {
		$options    = [ '' => esc_html__( 'Not Set', 'wpr-addons' ) ];
		$taxonomies = get_taxonomies( [ 'public' => true ], 'objects' );

		foreach ( $taxonomies as $tax ) {
			$options[ $tax->name ] = $tax->label;
		}

		return $options;
	}
}
