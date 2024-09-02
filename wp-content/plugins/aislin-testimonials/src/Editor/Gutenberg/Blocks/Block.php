<?php

namespace Aislin_Testimonials\Editor\Gutenberg\Blocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Aislin_Testimonials\Render;

abstract class Block {

    const SLUG     = '';
    const TEMPLATE = '';

	public function register() {
		register_block_type( static::SLUG, [
			'render_callback' => [ $this, 'render' ],
			'supports'        => $this->supports(),
			'attributes'      => $this->attributes(),
		] );
	}

	public function render( $attributes ) {
		if ( static::TEMPLATE ) {
			return Render\Factory::make( static::TEMPLATE )->render( $attributes );
		}
        return '';
	}

	public function supports() {
		return [];
	}
	
	public function attributes() {
		return [];
	}
	
}
