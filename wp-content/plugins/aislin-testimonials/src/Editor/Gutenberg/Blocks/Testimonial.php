<?php

namespace Aislin_Testimonials\Editor\Gutenberg\Blocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Testimonial extends Block {
    const SLUG     = 'aislin/testimonial';
    const TEMPLATE = \Aislin_Testimonials\Render\Testimonial::class;
}
