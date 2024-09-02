<?php

namespace Aislin_Testimonials\Post_Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Aislin_Testimonials\Shortcodes\Shortcodes;
use Aislin_Testimonials\Taxonomy;

class Rotator extends Post_Type {

	const NAME = 'aislin_rotator';
	const SLUG = 'rotators';

	/**
	 * "_template" and "_excerpt_length" from original Testimonial Rotator are not supported
	 */
	const POST_META_MAP = [
		'fx'                => [ 'default' => 'fade' ],
		'img_size'          => [ 'default' => 'thumbnail' ],
		'timeout'           => [ 'type' => 'integer', 'default' => 4 ],
		'speed'             => [ 'type' => 'integer', 'default' => 1 ],
		'limit'             => [ 'type' => 'integer', 'default' => 0 ],
		'shuffle'           => [ 'type' => 'boolean', 'default' => false ],
		'title_heading'     => [ 'default' => 'h2' ],
		'hide_title'        => [ 'type' => 'boolean', 'default' => false ],
		'hide_stars'        => [ 'type' => 'boolean', 'default' => false ],
		'hide_body'         => [ 'type' => 'boolean', 'default' => false ],
		'hide_author'       => [ 'type' => 'boolean', 'default' => false ],
		'hide_microdata'    => [ 'type' => 'boolean', 'default' => false ],
		'verticalalign'     => [ 'type' => 'boolean', 'default' => false ],
		'prevnext'          => [ 'type' => 'boolean', 'default' => false ],
		'hidefeaturedimage' => [ 'type' => 'boolean', 'default' => false ],
		'itemreviewed'      => [],
	];

	const META_TRANSITIONS = [
		'fade', 'fadeout', 'scrollHorz', 'scrollVert', 'flipHorz', 'flipVert', 'none',
	];
	
	const META_TITLE_HEADING = [
		'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'p'
	];

	protected function get_template() {
		return [
			[ 'aislin/rotator-meta', [] ],
		];
	}

	public function config() {
	    $labels = [
			'name'               => esc_html__( 'Rotators', 'aislin-testimonials' ),
			'singular_name'      => esc_html__( 'Rotator', 'aislin-testimonials' ),
			'add_new'            => esc_html__( 'Add New Rotator', 'aislin-testimonials' ),
			'add_new_item'       => esc_html__( 'Add New Rotator', 'aislin-testimonials' ),
			'edit_item'          => esc_html__( 'Edit Rotator', 'aislin-testimonials' ),
			'new_item'           => esc_html__( 'Add New Rotator', 'aislin-testimonials' ),
			'view_item'          => esc_html__( 'View Item', 'aislin-testimonials' ),
			'search_items'       => esc_html__( 'Search Rotator', 'aislin-testimonials' ),
			'not_found'          => esc_html__( 'No items found', 'aislin-testimonials' ),
			'not_found_in_trash' => esc_html__( 'No items found in trash', 'aislin-testimonials' ),
		];

	    $args = [
			'labels'          => $labels,
			'public'          => true,
			'supports'        => [ 'title', 'editor', 'custom-fields' ], //  custom-fields is requred for post_meta to work
			'map_meta_cap'    => true,
			'capability_type' => 'post',
			'show_in_rest'    => true,
			'show_in_menu'    => 'edit.php?post_type=' . Testimonial::NAME,
			'rewrite'         => [
				'slug' => apply_filters( 'aislin_testimonials/post_type/rotator/slug', self::SLUG ),
			],
			'has_archive'     => false,
            'menu_icon'		  => 'dashicons-book',
			'template'        => $this->get_template(),
		];

		return $args;
	}

	public function columns( $columns ) {
		return [
            'cb'        => $columns['cb'],
            'title'     => $columns['title'],
            'count'     => esc_html__( 'Testimonial Count', 'aislin-testimonials' ),
            'shortcode' => esc_html__( 'Shortcode', 'aislin-testimonials' ),
            'date'      => $columns['date'],
        ];
	}
	
	public function column( $column, $post_id ) {
		if ( $column === 'count' ) {
			$slug = apply_filters( 'aislin_testimonials/rotator_slug', $post_id );
			$term = get_term_by( 'slug', $slug, Taxonomy\Rotator::NAME );

			echo esc_html( $term->count );
        }
		
		if ( $column === 'shortcode' ) {
            printf( '[%s id="%d"]', Shortcodes\Rotator::NAME, $post_id );
        }
	}

}
