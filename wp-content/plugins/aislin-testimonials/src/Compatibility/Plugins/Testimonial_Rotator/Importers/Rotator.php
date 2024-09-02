<?php

namespace Aislin_Testimonials\Compatibility\Plugins\Testimonial_Rotator\Importers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Aislin_Testimonials\Post_Type;
use Aislin_Testimonials\Taxonomy;

class Rotator {

	/**
	 * Import rotator
	 *
	 * @param \WP_Post $post
	 * @return int New term id
	 */
	public function import( \WP_Post $post ) {
		$new_rotator_id = $this->copy_post( $post );
		
		$term_id = 0;
		if ( is_wp_error( $new_rotator_id ) ) {
			return $term_id;
		}
		
		$this->migrate_meta( $post, $new_rotator_id );
		
		$term = $this->maybe_create_rotator_term( $new_rotator_id );
		
		if ( ! is_wp_error( $term ) ) {
			$term_id = $term->term_id;
		}

		return $term_id;
	}

	/**
	 * Make rotator copy
	 *
	 * @param \WP_Post $post
	 * @return int|\WP_Error
	 */
	private function copy_post( \WP_Post $post ) {
		return wp_insert_post( [
			'post_type'    => Post_Type\Rotator::NAME,
			'post_title'   => $post->post_title,
			'post_content' => $post->post_content . "\n<!-- wp:aislin/rotator-meta /-->",
			'post_excerpt' => $post->post_excerpt,
			'post_status'  => $post->post_status,
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
		$keys = [
			'_timeout',
			'_speed',
			'_fx',
			'_shuffle',
			'_verticalalign',
			'_prevnext',
			'_limit',
			'_template',
			'_img_size',
			'_title_heading',
			'_excerpt_length',
			'_hidefeaturedimage',
			'_hide_title',
			'_hide_stars',
			'_hide_body',
			'_hide_author',
			'_hide_microdata',
			'_itemreviewed',
		];

		foreach ( $keys as $key ) {
			update_post_meta( $new_post_id, ltrim( $key, '_' ), $old_post->{$key} );
		}
	}

	private function maybe_create_rotator_term( int $new_rotator_id ) {
		$slug = apply_filters( 'aislin_testimonials/rotator_slug', $new_rotator_id );
		$term = get_term_by( 'slug', $slug, Taxonomy\Rotator::NAME );
		if ( $term ) {
			return $term;
		}
		
		$new_rotator = get_post( $new_rotator_id );
		return wp_insert_term( $new_rotator->post_title, Taxonomy\Rotator::NAME, [ 'slug' => $slug ] );
	}

}
