<?php

namespace Aislin_Testimonials;

final class Action_Proxy {

    const SEPARATOR = '%%';

    /**
     * Build a method name on this class which we will resolve dynamically in the __call method.
     * Both the class name and the method name are contained in the resulting method name.
     *
     * @param string $class
     * @param string $method
     * @return void
     */
    public function _resolve_callback( $class, $method ) {
        return [ $this, $class . self::SEPARATOR . $method ];
    }

    /**
     * Proxy the call to the corresponding object
     *
     * @param string $method Holds both the class name and the method name
     * @param array  $args
     * @return mixed Object method return
     */
    public function __call( $method, $args ) {
        $parts = explode( self::SEPARATOR, $method );

        if ( count( $parts ) < 2  ) {
            throw new \BadMethodCallException( sprintf( 'Unknown method: %s::%s', self::class, $method ) );
        }

        $class  = $parts[0];
        $method = $parts[1];

        if ( ! method_exists( $class, $method ) ) {
		    throw new \BadMethodCallException( sprintf( 'Unknown method: %s::%s', $class, $method ) );
        }
        
		$instance = Application::get_instance()->get( $class );

        return call_user_func_array( [ $instance, $method ], $args );
	}

}
