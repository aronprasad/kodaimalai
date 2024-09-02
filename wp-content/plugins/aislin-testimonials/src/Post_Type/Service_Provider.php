<?php

namespace Aislin_Testimonials\Post_Type;

use Aislin_Testimonials\Action;
use Aislin_Testimonials\Service_Provider as Provider;

class Service_Provider extends Provider {

    public function register() {
		$this->app->share( 'post_type.post_types', function() {
			return apply_filters( 'aislin_testimonials/post_types', [
				Testimonial::class,
				Rotator::class,
			] );
		} );

		$this->app->share( Manager::class, function() {
			return new Manager( $this->app );
		} );

		$this->actions();
    }

    protected function actions() {
		add_action( 'init', $this->resolve_callback( Manager::class, 'register_post_types' ) );
		add_filter( 'aislin_testimonials_js_config', $this->resolve_callback( Manager::class, 'js_config' ) );
		add_filter( 'the_content', $this->resolve_callback( Manager::class, 'the_content' ) );
    }

}
