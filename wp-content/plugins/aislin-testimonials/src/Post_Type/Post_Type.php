<?php

namespace Aislin_Testimonials\Post_Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

abstract class Post_Type {

	const NAME = '';
	const SLUG = '';

	const POST_META_MAP = [];

    public function config() {
        return [];
    }

	/**
	 * Register post type
	 */
	public function register() {
		$args = apply_filters( sprintf( 'aislin_testimonials/post_type/%s/args', static::NAME ), $this->config() );

	    register_post_type( static::NAME, $args );
	}

	public function register_post_meta() {
		$default_meta_args = [
			'show_in_rest' => true,
			'single'       => true,
			'type'         => 'string',
		];

		foreach ( static::POST_META_MAP as $name => $args ) {
			register_post_meta( static::NAME, $name, array_merge( $default_meta_args, $args ) );
		}
	}

	public function add_columns() {
		add_filter( sprintf( 'manage_%s_posts_columns', static::NAME ), [ $this, 'columns' ] );
		add_action( sprintf( 'manage_%s_posts_custom_column', static::NAME ), [ $this, 'column' ], 10, 2 );
	}
	
	public function remove_columns() {
		remove_filter( sprintf( 'manage_%s_posts_columns', static::NAME ), [ $this, 'columns' ] );
		remove_action( sprintf( 'manage_%s_posts_custom_column', static::NAME ), [ $this, 'column' ], 10, 2 );
	}

	public function columns( $columns ) {
		return $columns;
	}
	
	public function column( $column, $post_id ) {
		
	}

}
