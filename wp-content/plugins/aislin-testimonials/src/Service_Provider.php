<?php

namespace Aislin_Testimonials;

abstract class Service_Provider {

    protected $app;

    public function __construct( $app ) {
        $this->app = $app;
    }

    public function register() {

    }

    protected function resolve_callback( $class, $method ) {
        return $this->app->get( Action_Proxy::class )->_resolve_callback( $class, $method );
    }

}
