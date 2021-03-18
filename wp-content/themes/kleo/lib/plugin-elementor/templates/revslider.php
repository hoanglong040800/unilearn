<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class KleoElementorRevslider extends Widget_Base {
	
	public function get_name() {
		return 'kleo-revslider';
	}
	public function get_title() {
		return __( 'Revolution Slider', 'kleo' );
	}
	public function get_icon() {
		return 'eicon-slider-3d';
	}
	public function get_categories() {
		return [ 'kleo-elements' ];
	}
	
	public function get_fields() {
		$sliders = $this->get_slides();
		if ( empty( $sliders ) ) {
			return [ 'No Sliders found' ];
		} else {
			return $sliders;
		}
	}

	private function get_slides() {
		if ( class_exists( '\RevSlider' ) ) {
			$the_slider  = new \RevSlider();
			$arr_sliders = $the_slider->getArrSliders();
			$arrA       = array( 0 );
			$arrT       = array( esc_html__( 'Select slider', 'kleo' ) );
			foreach ( $arr_sliders as $slider ) {
				$arrA[] = $slider->getAlias();
				$arrT[] = $slider->getTitle();
			}

			$revsliders = array_combine( $arrA, $arrT );

			return $revsliders;
		} else {
			return array( esc_html__( 'You need to install Revolution Slider plugin first', 'kleo' ) );
		}
	}
	
	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_register_form',
			[
				'label' => __( 'Settings', 'sweetdate' ),
			]
		);
		
		
		$this->add_control(
			'slider',
			[
				'label' => __( 'Select slider', 'sweetdate' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_fields(),
			]
		);
		
		$this->end_controls_section();
	
	}
	protected function render() {
		$settings = $this->get_settings();
	
		if ( $settings['slider'] ) {
			if ( class_exists( 'RevSlider' ) ) {
				echo do_shortcode( '[rev_slider alias="' . $settings['slider'] . '"]' );
			} else {
				echo esc_html__( 'Revolution Slider plugin needs to be installed', 'sweetdate' );
			}
		}
	}
	
}
