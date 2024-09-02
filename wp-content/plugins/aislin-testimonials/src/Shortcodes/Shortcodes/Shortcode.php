<?php

namespace Aislin_Testimonials\Shortcodes\Shortcodes;

use Aislin_Testimonials\Render;

abstract class Shortcode {

	const NAME     = '';
	const TEMPLATE = '';

	public function register() {
		add_shortcode( static::NAME, [ $this, 'render' ] );
	}

	public function render( $attributes, $content ) {
		if ( static::TEMPLATE ) {
			return Render\Factory::make( static::TEMPLATE )->render( $attributes );
		}
        return '';
	}

    protected function get_css_classes( array $classes ) {
		return implode( ' ', array_filter( $classes ) );
	}

}
