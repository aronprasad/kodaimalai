<?php

namespace Aislin_Testimonials\Shortcodes;

use Aislin_Testimonials\Service_Provider as Provider;

class Service_Provider extends Provider {

    public function register() {
        $this->app->share( 'shortcodes.shortcodes', function() {
            return apply_filters( 'aislin_testimonials/shortcodes', [
                Shortcodes\Rotator::class,
            ] );
        } );

        $this->app->share( Manager::class, function() {
			return new Manager( $this->app->get( 'shortcodes.shortcodes' ) );
		} );

        $this->actions();
    }

    protected function actions() {
        add_action( 'init', $this->resolve_callback( Manager::class, 'register_shortcodes' ) );
    }

}
