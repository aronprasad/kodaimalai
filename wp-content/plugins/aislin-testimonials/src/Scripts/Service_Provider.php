<?php

namespace Aislin_Testimonials\Scripts;

use Aislin_Testimonials\Action;
use Aislin_Testimonials\Service_Provider as Provider;

class Service_Provider extends Provider {

    public function register() {
		$this->app->share( Manager::class, function() {
			return new Manager( $this->app );
		} );
		
		$this->app->share( 'scripts.config', function() {
			return ( new Config )->get_config();
		} );

		$this->actions();
    }

    protected function actions() {
		add_action( 'init', $this->resolve_callback( Manager::class, 'register_scripts' ) );
		add_action( 'enqueue_block_assets', $this->resolve_callback( Manager::class, 'enqueue_scripts' ) );
		add_action( 'wp_print_footer_scripts', $this->resolve_callback( Manager::class, 'add_config' ), 9 );
		add_action( 'admin_print_scripts', $this->resolve_callback( Manager::class, 'add_config' ) );
    }

}
