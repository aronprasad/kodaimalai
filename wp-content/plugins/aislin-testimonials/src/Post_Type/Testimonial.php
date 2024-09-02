<?php

namespace Aislin_Testimonials\Post_Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Aislin_Testimonials\Shortcodes\Shortcodes;

class Testimonial extends Post_Type {

	const NAME = 'aislin_testimonial';
	const SLUG = 'testimonials';

	const POST_META_MAP = [
		'cite'   => [],
		'rating' => [ 'type' => 'integer', 'default' => 0 ],
	];

	protected function get_template() {
		return [
			[ 'aislin/testimonial-meta', [] ],
		];
	}

	public function config() {

	    $labels = [
			'name'               => esc_html__( 'Testimonials', 'aislin-testimonials' ),
			'singular_name'      => esc_html__( 'Testimonial', 'aislin-testimonials' ),
			'add_new'            => esc_html__( 'Add New Testimonial', 'aislin-testimonials' ),
			'add_new_item'       => esc_html__( 'Add New Testimonial', 'aislin-testimonials' ),
			'edit_item'          => esc_html__( 'Edit Testimonial', 'aislin-testimonials' ),
			'new_item'           => esc_html__( 'Add New Testimonial', 'aislin-testimonials' ),
			'view_item'          => esc_html__( 'View Item', 'aislin-testimonials' ),
			'search_items'       => esc_html__( 'Search Testimonial', 'aislin-testimonials' ),
			'not_found'          => esc_html__( 'No items found', 'aislin-testimonials' ),
			'not_found_in_trash' => esc_html__( 'No items found in trash', 'aislin-testimonials' ),
		];

	    $args = [
			'labels'          => $labels,
			'public'          => true,
			'supports'        => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ], //  custom-fields is requred for post_meta to work
			'map_meta_cap'    => true,
			'capability_type' => 'post',
			'show_in_rest'    => true,
			'rewrite'         => [
				'slug' => apply_filters( 'aislin_testimonials/post_type/testimonial/slug', self::SLUG ),
			],
			'has_archive'     => true,
            'menu_icon'		  => 'dashicons-book',
			'template'        => $this->get_template(),
		];

		return $args;
	}

	public function columns( $columns ) {
		return [
            'cb'        => $columns['cb'],
            'image'     => esc_html__( 'Image', 'aislin-testimonials' ),
            'title'     => $columns['title'],
            'rating'    => esc_html__( 'Rating', 'aislin-testimonials' ),
            'shortcode' => esc_html__( 'Shortcode', 'aislin-testimonials' ),
            'date'      => $columns['date'],
        ];;
	}
	
	public function column( $column, $post_id ) {
		if ( $column === 'image' ) {
			echo wp_kses_post( get_the_post_thumbnail( $post_id, [50, 50] ) );
        }
		if ( $column === 'rating' ) {
			echo wp_kses_post( get_post_meta( $post_id, 'rating', true ) );
        }
		if ( $column === 'shortcode' ) {
            printf( '[%s id="%d"]', Shortcodes\Testimonial::NAME, $post_id );
        }
	}

}
