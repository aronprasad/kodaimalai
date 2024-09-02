<?php

namespace Aislin_Testimonials\Render;

class Testimonial {

	public function render( array $atts ) {
		$atts = shortcode_atts( [
			'id' => 0,
			'hide_stars'     => false,
			'hide_title'     => false,
			'hide_body'      => false,
			'hide_author'    => false,
			'hide_microdata' => false,
			'img_size'       => false,
			'show_image'     => true,
		], $atts );

		$id = (int) $atts['id'];

		if ( empty( $id ) ) {
			return '';
		}

		$item = get_post( $id );

		if ( empty( $item ) ) {
			return '';
		}
		
		$has_image = has_post_thumbnail( $item ) ? 'has-image' : false;

		return aislin_testimonials_template_get( 'testimonial', [
			'testimonial'    => $item,
            'wrapper_class'  => 'testimonial_rotator testimonial_rotator_single hreview itemreviewed item cf-tr ' . $has_image,
            'has_image'      => $has_image,
            'show_link'      => true,
            'show_size'      => 'full',
            'hide_stars'     => (bool) $atts['hide_stars'],
            'hide_title'     => (bool) $atts['hide_title'],
            'hide_body'      => (bool) $atts['hide_body'],
            'hide_author'    => (bool) $atts['hide_author'],
            'hide_microdata' => (bool) $atts['hide_microdata'],
            'show_image'     => (bool) $atts['show_image'],
            'img_size'       => 'full',
		] );
	}

}
