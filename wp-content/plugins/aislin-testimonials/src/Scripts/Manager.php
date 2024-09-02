<?php

namespace Aislin_Testimonials\Scripts;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Aislin_Testimonials\Post_Type\Testimonial;

class Manager {

	protected $scripts = [ 'app' ];
	protected $app;

	/**
	 * @param Application $app
	 */
	public function __construct( $app ) {
		$this->app = $app;
	}

	/**
	 * @action enqueue_block_assets
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'aislin-testimonials' );

		$allowed_post_types = [
			Testimonial::NAME,
		];

        if ( in_array( get_post_type(), $allowed_post_types ) ) {
			
		}

		wp_enqueue_script( 'aislin-testimonials-app' );
	}

	/**
	 * @action init
	 */
	public function register_scripts() {
		wp_register_style( 'aislin-testimonials', $this->app->get_url( 'public/css/app.css' ), [], $this->app->get( 'version' ) );

		wp_register_script( 'jquery.cycletwo', $this->app->get_url( 'public/js/vendor/jquery.cycletwo.js' ), ['jquery'], $this->app->get( 'version' ), true );
		wp_register_script( 'jquery.cycletwo.addons', $this->app->get_url( 'public/js/vendor/jquery.cycletwo.addons.js' ), ['jquery.cycletwo'], $this->app->get( 'version' ), true) ;

		foreach ( $this->scripts as $script ) {
			$asset_file = include( $this->app->get_path( "public/js/{$script}.asset.php" ) );
			wp_register_script(
				"aislin-testimonials-{$script}",
				$this->app->get_url( "public/js/{$script}.js" ),
				$asset_file['dependencies'],
				$asset_file['version'],
				true
			);
		}
	}

	/**
	 * Late add config so we can add more from blocks
	 * 
	 * @action wp_print_footer_scripts|admin_print_scripts
	 */
	public function add_config() {
		/**
		 * TODO: Should we load it for each registered script?
		 */
		foreach ( $this->scripts as $script ) {
			wp_localize_script( "aislin-testimonials-{$script}", 'aislin_testimonials', $this->app->get( 'scripts.config' ) );
		}
	}

}