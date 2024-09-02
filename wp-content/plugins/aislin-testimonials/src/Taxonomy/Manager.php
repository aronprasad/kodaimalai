<?php

namespace Aislin_Testimonials\Taxonomy;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Aislin_Testimonials\Post_Type;

class Manager {

    /**
     * @action save_post_ . Post_Type\Rotator::NAME
     */
	public function save_post( $post_id, $post, $update ) {
		if ( wp_is_post_revision( $post_id ) || $post->post_status !== 'publish' ) {
			return;
		}

        $slug = $this->get_rotator_slug( $post_id );

        $term = get_term_by( 'slug', $slug, Rotator::NAME );
        if ( $term ) {
            wp_update_term( $term->term_id, Rotator::NAME, [ 'name' => $post->post_title ] );
        } else {
            $term = wp_insert_term( $post->post_title,  Rotator::NAME, [ 'slug' => $slug ] );
        }
	}
    
    /**
     * @action after_delete_post
     */
    public function after_delete_post( $post_id, $post ) {
		if ( get_post_type( $post ) !== Post_Type\Rotator::NAME ) {
			return;
		}
        
        $slug = $this->get_rotator_slug( $post_id );
        
        $term = get_term_by( 'slug', $slug, Rotator::NAME );
        if ( $term ) {
            wp_delete_term( $term->term_id, Rotator::NAME );
        }
    }

    /**
     * Get rotator slug
     *
     * @param int $id
     * @return string
     * 
     * @filter aislin_testimonials/rotator_slug
     */
    public function get_rotator_slug( $id ) {
        return 'rotator-' . $id;
    }

}