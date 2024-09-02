<?php

namespace Aislin_Testimonials\Post_Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Aislin_Testimonials\Render;

class Manager {

	protected $app;

	public function __construct( $app ) {
		$this->app = $app;
	}

	/**
	 * Register post types
	 *
	 * @return void
	 * 
	 * @action init
	 */
	public function register_post_types() {
		foreach ( $this->app->get( 'post_type.post_types' ) as $post_type_class ) {
			$post_type = $this->app->get( $post_type_class );
			$post_type->register();
			$post_type->register_post_meta();
			$post_type->add_columns();
		}
	}

	/**
	 * Filter JS config
	 *
	 * @param array $config
	 * @return array
	 * 
	 * @filter aislin_testimonials_js_config
	 */
	public function js_config( array $config ) {
		$config['rotator']['transitions']   = Rotator::META_TRANSITIONS;
		$config['rotator']['title_heading'] = Rotator::META_TITLE_HEADING;
		
		return $config;
	}
	
	/**
	 * Filter rotator content
	 *
	 * @param array $config
	 * @return array
	 * 
	 * @filter the_content
	 */
	public function the_content( string $content ) {
		if ( is_single() && get_post_type() === Testimonial::NAME ) {
			return Render\Factory::make( Render\Testimonial::class )->render( [
				'id'         => get_the_ID(),
				'show_image' => false,
				'hide_title' => true,
			] );
		}
		
		return $content;
	}

}