<?php

namespace Aislin_Testimonials\Editor;

use Aislin_Testimonials\Service_Provider as Provider;

class Service_Provider extends Provider {

	public function register() {
		// Gutenberg
		$this->app->share( 'editor.gutenberg.blocks', function() {
			return apply_filters( 'aislin_testimonials/gutenberg/blocks', [
				Gutenberg\Blocks\Rotator::class,
				Gutenberg\Blocks\Testimonial::class,
			] );
		} );

		// WPBakery
		$this->app->share( 'editor.wpbakery.blocks', function() {
			return apply_filters( 'aislin_testimonials/wpbakery/blocks', [
				WPBakery\Blocks\Rotator::class,
			] );
		} );

		$this->app->share( Manager::class, function() {
			return new Manager( $this->app->get( 'editor.gutenberg.blocks' ), $this->app->get( 'editor.wpbakery.blocks' ) );
		} );

		$this->gutenberg();
		$this->wpbakery();
	}
	
	protected function gutenberg() {
		add_action( 'init', $this->resolve_callback( Manager::class, 'register_gutenberg_blocks') );
	}

	protected function wpbakery() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}
		add_action( 'init', $this->resolve_callback( Manager::class, 'register_wpbakery_blocks'), 9 );
	}

}