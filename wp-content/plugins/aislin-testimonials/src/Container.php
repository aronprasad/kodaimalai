<?php

namespace Aislin_Testimonials;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Container Class
 *
 * Currently, only singletons are needed and supported.
 *
 * @package Aislin Testimonials
 * @since 1.0
 */
class Container {

	/**
	 * Singleton definitions
	 *
	 * @var array
	 */
	protected $definitions = [];

	/**
	 * Add an item to the container as singleton
	 *
	 * @param string $id       Object identifier.
	 * @param mixed  $concrete Implementation.
	 */
	public function share( $id, $concrete ) {
		return $this->definitions[ $id ] = $concrete;
	}

	/**
	 * Get definition
	 *
	 * @param string $id Object identifier.
	 *
	 * @return mixed
	 */
	public function get( $id ) {
		if ( isset( $this->definitions[ $id ] ) ) {
			if ( is_callable( $this->definitions[ $id ] ) ) {
				$this->definitions[ $id ] = $this->definitions[ $id ]();
			}
			return $this->definitions[ $id ];
		}
		
		if ( class_exists( $id ) ) {
			return $this->share( $id, new $id );
		}
	}

	public function callback( $id, $concrete = null ) {
		if ( $concrete ) {
			$this->share( $id, $concrete );
			return $concrete;
		}

		if ( isset( $this->definitions[ $id ] ) ) {
			return $this->definitions[ $id ];
		}

		return '__return_null';
	}

}
