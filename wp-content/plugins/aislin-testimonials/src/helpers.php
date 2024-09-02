<?php
/**
 * Helper functions
 *
 * @package Aislin Testimonials
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'aislin_testimonials_app' ) ) {
	/**
	 * Get application instance
	 *
	 * @return Aislin_Testimonials\Application
	 */
	function aislin_testimonials_app() {
		return Aislin_Testimonials\Application::get_instance();
	}
}

if ( ! function_exists( 'aislin_testimonials_template' ) ) {
	/**
	 * Render template
	 *
	 * @param  string $template Template uri.
	 * @param  array  $args     Arguments to be passed to the template.
	 */
	function aislin_testimonials_template( $template, $args ) {
		aislin_testimonials_app()->get( Aislin_Testimonials\Template::class )->render( $template, $args );
	}
}

if ( ! function_exists( 'aislin_testimonials_template_get' ) ) {
	/**
	 * Get template
	 *
	 * @param  string $template Template uri.
	 * @param  array  $args     Arguments to be passed to the template.
	 */
	function aislin_testimonials_template_get( $template, $args ) {
		return aislin_testimonials_app()->get( Aislin_Testimonials\Template::class )->get( $template, $args );
	}
}

if ( ! function_exists( 'aislin_testimonials_get_thumbnail_sizes' ) ) {
	/**
	 * Get thumbnail sizes
	 * 
	 * @return array Thumbnail sizes
	 */
	function aislin_testimonials_get_thumbnail_sizes() {
		global $_wp_additional_image_sizes;
		$thumbnail_sizes = [ 'thumbnail' => 'thumbnail' ];
		foreach ( $_wp_additional_image_sizes as $name => $settings ) {
			$thumbnail_sizes[ $name ] = "{$name} ({$settings['width']}x{$settings['height']})";
		}
		$thumbnail_sizes['full-width'] = 'full-width';
	
		return $thumbnail_sizes;
	}
}

if ( ! function_exists( 'aislin_testimonials_resolve_callback' ) ) {
	/**
	 * Resolve callback
	 *
	 * @param string $class
	 * @param string $method
	 * @return array
	 */
	function aislin_testimonials_resolve_callback( $class, $method ) {
        return aislin_testimonials_app()->app->get( Aislin_Testimonials\Action_Proxy::class )->_resolve_callback( $class, $method );
    }
}
