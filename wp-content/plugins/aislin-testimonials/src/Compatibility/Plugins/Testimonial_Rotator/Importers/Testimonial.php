<?php

namespace Aislin_Testimonials\Compatibility\Plugins\Testimonial_Rotator\Importers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Aislin_Testimonials\Post_Type;
use Aislin_Testimonials\Taxonomy;

class Testimonial {

    /**
     * Old rotator ids to new rotator tax id map
     *
     * @var array
     */
    protected $tax_map = [];

    public function __construct( $tax_map = [] ) {
        $this->tax_map = $tax_map;
    }

	/**
	 * Import testimonial
	 *
	 * @param \WP_Post $post
	 * @return int New post id
	 */
	public function import( \WP_Post $post ) {
        $new_testimonial_id = $this->copy_post( $post );

        if ( is_wp_error( $new_testimonial_id ) ) {
            return 0;
        }

        $this->migrate_meta( $post, $new_testimonial_id );

        $old_rotator_ids = $this->parse_rotator_ids( $post );
        $new_tax_ids     = $this->map_to_new_tax_ids( $old_rotator_ids );

        // tag testimonial
        wp_set_object_terms( $new_testimonial_id, $new_tax_ids, Taxonomy\Rotator::NAME );

        return $new_testimonial_id;
	}

    /**
     * Extract old rotator ids
     *
     * @param \WP_Post $post
     * @return array
     */
    private function parse_rotator_ids( \WP_Post $post ) {
        $rotators = $post->_rotator_id;
        
        if ( ! empty( $rotators ) ) {
            return array_map( 'intval', array_filter( explode( '|', $rotators ) ) );
        }

        return [];
    }

    /**
     * Map old rotator ids to new rotator tax ids
     *
     * @param array $old_rotator_ids
     * @return array
     */
    private function map_to_new_tax_ids( $old_rotator_ids ) {
        $new_tax_ids = [];
        foreach ( $old_rotator_ids as $old_rotator_id ) {
            $new_tax_ids[] = $this->tax_map[ $old_rotator_id ] ?? false;
        }

        return array_filter( $new_tax_ids );
    }

    /**
	 * Make rotator copy
	 *
	 * @param \WP_Post $post
	 * @return int|\WP_Error
	 */
	private function copy_post( \WP_Post $post ) {
		return wp_insert_post( [
			'post_type'    => Post_Type\Testimonial::NAME,
			'post_title'   => $post->post_title,
			'post_content' => $post->post_content . "\n<!-- wp:aislin/testimonial-meta /-->",
            'post_excerpt' => $post->post_excerpt,
			'post_status'  => $post->post_status,
			'menu_order'   => $post->menu_order,
			'post_author'  => $post->post_author,
		] );
	}

	/**
	 * Migrate meta from the old rotator
	 *
	 * @param \WP_Post $old_post
	 * @param integer $new_post_id
	 * @return void
	 */
	private function migrate_meta( \WP_Post $old_post, int $new_post_id ) {
        // we don't copy "_rotator_id" key as we are using tax for rotator relation
		$keys = [
			'_cite',
			'_rating',
		];

		foreach ( $keys as $key ) {
			update_post_meta( $new_post_id, ltrim( $key, '_' ), $old_post->{$key} );
		}

        $thumbnail_id = get_post_thumbnail_id( $old_post );
        set_post_thumbnail( $new_post_id, $thumbnail_id );
	}

}
