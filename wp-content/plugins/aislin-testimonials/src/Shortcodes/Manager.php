<?php

namespace Aislin_Testimonials\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Manager {

	protected $shortcodes;

	public function __construct( array $shortcodes ) {
		$this->shortcodes = $shortcodes;
	}

	public function register_shortcodes() {
		foreach ( $this->shortcodes as $shortcode ) {
			( new $shortcode )->register();
		}
	}

}
