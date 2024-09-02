<?php

namespace Aislin_Testimonials\Compatibility\Plugins\Testimonial_Rotator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Migrator {

	const NAME                  = 'aislin_testimonials_migrator';
	const POST_TYPE_ROTATOR     = 'testimonial_rotator';
	const POST_TYPE_TESTIMONIAL = 'testimonial';

	protected function is_migrated() {
		return get_option( self::NAME, false );
	}

	protected function testimonial_rotator_posts_exist() {
		return wp_count_posts( self::POST_TYPE_TESTIMONIAL );
	}

	/**
	 * @action admin_notices
	 */
	public function admin_notices() {
		$this->show_previous_notices();

		if ( $this->is_migrated() || ! $this->testimonial_rotator_posts_exist() ) {
			return;
		}

		$url = add_query_arg( [ 'action' => self::NAME, '_wpnonce' => wp_create_nonce( self::NAME ) ], admin_url( 'admin-post.php' ) );

		printf(
			'<div class="notice notice-warning"><p>%s</p></div>',
			sprintf(
				esc_html__( 'Testimonial Rotator content is ready for migration: %sMigrate now%s', 'aislin-testimonials' ),
				sprintf( '<a class="button button-primary" href="%s">', esc_url( $url ) ),
				'</a>'
			)
		);
	}

	private function show_previous_notices() {
		$_GET[ 'settings-updated' ] = 1;
		settings_errors( self::NAME );
		unset( $_GET[ 'settings-updated' ] );
	}

	/**
	 * @action admin_post_ . self::NAME
	 */
	public function migrate() {
		$submission = filter_var_array( $_GET, [
            '_wpnonce' => FILTER_SANITIZE_STRING,
        ] );
        
        if ( empty( $submission['_wpnonce'] ) || ! wp_verify_nonce( $submission['_wpnonce'], self::NAME ) ) {
            throw new \InvalidArgumentException( __( 'Invalid request. Please try again.', 'aisin-testimonials' ), 403 );
        }

		if ( $this->is_migrated() ) {
			return;
		}

		try {
			$tax_map = $this->import_rotators();
			$this->import_testimonials( $tax_map );

			update_option( self::NAME, true );
			
			add_settings_error(
				self::NAME,
				'migration_success',
				__( 'Testimonial Rotator content successfuly migrated','aislin-testimonials' ),
				'success'
			);
		} catch ( \Throwable $th ) {
			update_option( self::NAME, false );

			add_settings_error(
				self::NAME,
				'migration_error',
				__( 'There was a problem migrating Testimonial Rotator content','aislin-testimonials' ),
				'error'
			);
		}

		set_transient( 'settings_errors', get_settings_errors(), 30 );

        wp_safe_redirect( esc_url_raw( admin_url() ), 303 );
        exit();
	}

	/**
	 * Import rotators
	 *
	 * @return array Tax map
	 */
	private function import_rotators() {
		$rotators = get_posts( [
			'post_type'   => self::POST_TYPE_ROTATOR,
			'numberposts' => -1,
		] );

		if ( empty( $rotators ) ) {
			return [];
		}

		$importer = new Importers\Rotator;
		$tax_map  = [];
		foreach ( $rotators as $rotator ) {
			$tax_map[ $rotator->ID ] = $importer->import( $rotator );
		}

		return $tax_map;
	}

	/**
	 * Import testimonials
	 *
	 * @param array $tax_map
	 * @return void
	 */
	private function import_testimonials( $tax_map ) {
		$testimonials = get_posts( [
			'post_type'   => self::POST_TYPE_TESTIMONIAL,
			'numberposts' => -1,
		] );

		$importer = new Importers\Testimonial( $tax_map );

		foreach ( $testimonials as $testimonial ) {
			$importer->import( $testimonial );
		}
	}

}
