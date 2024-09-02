<?php

namespace Aislin_Testimonials\Activation;

use Aislin_Testimonials\Action;
use Aislin_Testimonials\Service_Provider as Provider;

class Service_Provider extends Provider {

    public function register() {
        $this->app->share( Manager::class, function() {
			return new Manager( $this->app );
		} );

        $this->actions();
    }

    protected function actions() {
        add_action( 'aislin_testimonials/activate', $this->resolve_callback( Manager::class, 'activate' ) );
        add_action( 'aislin_testimonials/deactivate', $this->resolve_callback( Manager::class, 'deactivate' ) );
    }

}
