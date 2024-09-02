<?php

namespace Aislin_Testimonials\Scripts;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Config {

	public function get_config() {
		return apply_filters( 'aislin_testimonials_js_config', [
			'post_type'       => get_post_type(),
			'thumbnail_sizes' => aislin_testimonials_get_thumbnail_sizes(),
		] );
    }

}