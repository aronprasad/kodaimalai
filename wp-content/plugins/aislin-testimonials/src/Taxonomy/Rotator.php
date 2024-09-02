<?php

namespace Aislin_Testimonials\Taxonomy;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Aislin_Testimonials\Post_Type;

class Rotator {

	const NAME = 'aislin_rotator_tag';
	const SLUG = 'rotator-tags';

	/**
	 * Register post type
	 */
	public function register() {
	    $labels = [
			'name'                       => esc_html__( 'Rotators', 'aislin-testimonials' ),
			'singular_name'              => esc_html__( 'Rotator', 'aislin-testimonials' ),
			'menu_name'                  => esc_html__( 'Rotators', 'aislin-testimonials' ),
			'edit_item'                  => esc_html__( 'Edit Rotator', 'aislin-testimonials' ),
			'update_item'                => esc_html__( 'Update Rotator', 'aislin-testimonials' ),
			'add_new_item'               => esc_html__( 'Add New Rotator', 'aislin-testimonials' ),
			'new_item_name'              => esc_html__( 'New Rotator Name', 'aislin-testimonials' ),
			'parent_item'                => esc_html__( 'Parent Rotator', 'aislin-testimonials' ),
			'parent_item_colon'          => esc_html__( 'Parent Rotator:', 'aislin-testimonials' ),
			'all_items'                  => esc_html__( 'All Rotators', 'aislin-testimonials' ),
			'search_items'               => esc_html__( 'Search Rotators', 'aislin-testimonials' ),
			'popular_items'              => esc_html__( 'Popular Rotators', 'aislin-testimonials' ),
			'separate_items_with_commas' => esc_html__( 'Separate rotators with commas', 'aislin-testimonials' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove rotators', 'aislin-testimonials' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used rotators', 'aislin-testimonials' ),
			'not_found'                  => esc_html__( 'No rotators found.', 'aislin-testimonials' ),
		];

		$args = [
			'labels'            => $labels,
			'public'            => false,
			'show_in_nav_menus' => false,
			'show_in_menu'      => false,
			'show_ui'           => true,
			'show_tagcloud'     => false,
			'show_in_rest'      => true,
			'hierarchical'      => true, // to get the checkboxes displayed
			'rewrite'           => [
				'slug' => apply_filters( 'aislin_testimonials/taxonomy/rotator/slug', self::SLUG ),
			],
			'show_admin_column' => true,
			'query_var'         => true,

			// TODO: Check how this works
			'capabilities'      => [
                'assign_terms' => 'manage_options',
                'edit_terms'   => 'god',
                'manage_terms' => 'god',
			],
		];

		$args = apply_filters( 'aislin_testimonials/taxonomy/rotator/args', $args );

		register_taxonomy( self::NAME, [ Post_Type\Testimonial::NAME ], $args );
	}

}
