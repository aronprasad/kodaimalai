<?php

namespace Aislin_Testimonials\Compatibility\Plugins\Testimonial_Rotator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Manager {

	/**
	 * @return array
	 * 
	 * @filter aislin_testimonials/shortcodes
	 */
	public function add_shortcodes( $shortcodes ) {
		$shortcodes[] = Shortcodes\Rotator::class;
		$shortcodes[] = Shortcodes\Rotator_Alt::class;
		$shortcodes[] = Shortcodes\Testimonial::class;
		$shortcodes[] = Shortcodes\Testimonial_Alt::class;

		return $shortcodes;
	}

}
