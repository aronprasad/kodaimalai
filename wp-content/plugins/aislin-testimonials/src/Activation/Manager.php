<?php

namespace Aislin_Testimonials\Activation;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Fired during plugin activation/deactivation.
 *
 * @package Aislin_Testimonials
 * @since 1.0
 */
class Manager {

	protected $app;

	/**
	 * @param Application $app
	 */
	public function __construct( $app ) {
		$this->app = $app;
	}

	public function activate() {

	}
	
	public function deactivate() {

	}

}
