<?php

namespace Aislin_Testimonials\Render;

class Factory {

	public static function make( $class ) {
		return new $class;
	}

}
