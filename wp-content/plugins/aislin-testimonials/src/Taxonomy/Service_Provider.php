<?php

namespace Aislin_Testimonials\Taxonomy;

use Aislin_Testimonials\Action;
use Aislin_Testimonials\Post_Type;
use Aislin_Testimonials\Service_Provider as Provider;

class Service_Provider extends Provider {

    public function register() {
		$this->actions();
    }

    protected function actions() {
		add_action( 'init', $this->resolve_callback( Rotator::class, 'register' ) );
		add_action( 'save_post_' . Post_Type\Rotator::NAME, $this->resolve_callback( Manager::class, 'save_post' ), 10, 3 );
		add_action( 'after_delete_post', $this->resolve_callback( Manager::class, 'after_delete_post' ), 10, 2 );
		
		add_filter( 'aislin_testimonials/rotator_slug', $this->resolve_callback( Manager::class, 'get_rotator_slug' ) );
    }

}
