<?php

namespace Aislin_Testimonials\Editor\Gutenberg\Blocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Rotator extends Block {
    const SLUG     = 'aislin/testimonial-rotator';
    const TEMPLATE = \Aislin_Testimonials\Render\Rotator::class;
}
