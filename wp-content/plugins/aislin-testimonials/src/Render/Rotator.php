<?php

namespace Aislin_Testimonials\Render;

use Aislin_Testimonials\Post_Type;
use Aislin_Testimonials\Taxonomy;

class Rotator {

	public function render( array $atts ) {
		$atts = shortcode_atts( [
			'id' => 0,
		], $atts );

		$id = (int) $atts['id'];
		
		if ( empty( $id ) ) {
			return '';
		}

		$rotator = get_post( $id );

		if ( empty( $rotator ) ) {
			return '';
		}

		$atts = $this->parse_atts( $atts, $rotator );
		$uid  = uniqid( 'rotator_' );

		wp_enqueue_script( 'jquery.cycletwo.addons' );
		
		return aislin_testimonials_template_get( 'rotator', [
			'testimonials'    => $this->get_testimonials( $id, (bool) $atts['shuffle'] ),
			'data_attributes' => $this->data_attributes( $atts, $uid ),
			'atts'            => $atts,
			'uid'             => $uid,
		] );
	}

	protected function get_testimonials( int $id, bool $shuffle = false ) {
		$args = [
			'post_type'   => Post_Type\Testimonial::NAME,
			'numberposts' => -1,
			'orderby'     => $shuffle ? 'rand' : 'menu_order',
			'tax_query'   => [
				'taxonomy' => Taxonomy\Rotator::NAME,
				'field'    => 'slug',
				'terms'    => apply_filters( 'aislin_testimonials/rotator_slug', $id ),
			],
		];

		return get_posts( $args );
	}

	/**
	 * Get data attributes string
	 *
	 * @param array $atts
	 * @return string
	 */
	protected function data_attributes( array $atts, $uid ) {
		// https://jquery.malsup.com/cycle2/api/
		$data = [
			'fx'          => $atts['fx'],
			'timeout'     => $this->to_miliseconds( $atts['timeout'] ),
			'speed'       => $this->to_miliseconds( $atts['speed'] ),
			'slides'      => 'div.slide', // div selector
			'log'         => false,
			'swipe'       => true,
			'auto-height' => 'calc',
		];

		if ( $atts['verticalalign'] ) {
			$data['center-horz'] = true;
			$data['center-vert'] = true;
		}
		
		if ( $atts['prevnext'] ) {
			$data['next'] = "#{$uid} .testimonial_rotator_next";
			$data['prev'] = "#{$uid} .testimonial_rotator_prev";
		}

		$data = apply_filters( 'aislin_testimonials/data-attributes', $data, $atts['id'] );

		return implode( ' ', array_map( function ( $key, $value ) {
			return sprintf( 'data-cycletwo-%s=%s', $key, json_encode( $value ) );
		}, array_keys( $data ), array_values( $data ) ) );
	}

	/**
	 * Convert seconds to miliseconds
	 *
	 * @param int $seconds
	 * @return int
	 */
	protected function to_miliseconds( int $seconds ) {
		return $seconds * 1000;
	}

	protected function parse_atts( array $atts, \WP_Post $rotator ) {
		if ( isset( $atts['title_heading'] ) && ! in_array( $atts['title_heading'], Post_Type\Rotator::META_TITLE_HEADING ) ) {
			unset( $atts['title_heading'] ); // We will take the default value from meta bellow
		}
		
		foreach ( Post_Type\Rotator::POST_META_MAP as $name => $args ) {
			// Load meta only if we don't already have the key set
			if ( ! isset( $atts[ $name ] ) ) {
				$atts[ $name ] = $rotator->{$name};
			}

			// Type booleans
			if ( isset( $args['type'] ) && $args['type'] === 'boolean' ) {
				$atts[ $name ] = (bool) $atts[ $name ];
			}
		}

		return $atts;
	}

}
