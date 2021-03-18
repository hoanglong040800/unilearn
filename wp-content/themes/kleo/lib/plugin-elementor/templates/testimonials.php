<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class KleoElementorTestimonials extends Widget_Base {

	public function get_name() {
		return 'kleo-testimonials';
	}

	public function get_title() {
		return __( 'Testimonials', 'kleo' );
	}

	public function get_icon() {
		return 'eicon-testimonial-carousel';
	}

	public function get_categories() {
		return [ 'kleo-elements' ];
	}

	public function get_testimonial_tags() {
		$testimonial_tags = [];

		$defined_tags = get_terms( 'testimonials-tag' );
		if ( is_array( $defined_tags ) && ! empty( $defined_tags ) ) {

			foreach ( $defined_tags as $tag ) {
				$testimonial_tags[ $tag->name ] = $tag->term_id;
			}

		}

		return $testimonial_tags;
	}


	protected function _register_controls() {

		$this->start_controls_section(
			'section_register_form',
			[
				'label' => __( 'Settings', 'kleo' ),
			]
		);


		$this->add_control(
			'type',
			[
				'label'   => __( 'Type', 'k-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'simple'   => 'Simple',
					'carousel' => 'Carousel',
					'boxed'    => 'Boxed with 5 star ratings'
				],
				'default' => 'simple',
			]
		);

		$this->add_control(
			'specific_id',
			[
				'label'       => __( 'By IDs', 'k-elements' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'no'  => 'No',
					'yes' => 'Yes',
				],
				'default'     => 'no',
				'description' => __( "Show specific testimonials by IDs.", "k-elements" ),

			]
		);

		$this->add_control(
			'ids',
			[
				'label'       => __( 'Testimonials IDs to show', 'k-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => 'Comma separated list of ids to display.',
				'condition'   => [
					'specific_id' => 'yes',
				],
			]
		);

		$this->add_control(
			'number',
			[
				'label'       => __( 'Number of testimonials', 'k-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '3',
				'description' => 'How many testimonials to show.',
				'condition'   => [
					'specific_id' => 'no',
				],
			]
		);

		$this->add_control(
			'offset',
			[
				'label'       => __( 'Testimonials offset', 'k-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => 'Display testimonials starting from the number you enter. Eq: if you enter 3, it will show testimonials from the 4th one.',
				'condition'   => [
					'specific_id' => 'no',
				],
			]
		);

		$this->add_control(
			'tags',
			[
				'label'     => __( 'Filter by Tags', 'k-elements' ),
				'type'      => Controls_Manager::SELECT2,
				'default'   => '',
				'options'   => $this->get_testimonial_tags(),
				'multiple'  => true,
				'condition' => [
					'specific_id' => 'no',
				],
			]
		);

		$this->add_control(
			'min_items',
			[
				'label'       => __( 'Minimum items to show', 'k-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '1',
				'condition'   => [
					'type' => 'carousel',
				],
			]
		);

		$this->add_control(
			'max_items',
			[
				'label'       => __( 'Maximum items to show', 'k-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '1',
				'condition'   => [
					'type' => 'carousel',
				],
			]
		);

		$this->add_control(
			'speed',
			[
				'label'       => __( 'Speed between slides', 'k-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '5000',
				'condition'   => [
					'type' => 'carousel',
				],
				'description' => 'In milliseconds. Default is 5000 milliseconds, meaning 5 seconds.'
			]
		);

		$this->add_control(
			'height',
			[
				'label'       => __( 'Elements height', 'k-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => 'Force a height on all elements. Expressed in pixels, eq: 300 will represent 300px.'
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$values = $this->get_settings();

		$settings   = [
			'type',
			'specific_id',
			'ids',
			'number',
			'offset',
			'tags',
			'min_items',
			'max_items',
			'speed',
			'height',
		];
		$attributes = '';
		foreach ( $settings as $setting ) {
			$attributes .= ' ' . $setting . '="' . $values[ $setting ] . '"';
		}

		echo do_shortcode( '[kleo_testimonials' . $attributes . ']' );
	}

}
