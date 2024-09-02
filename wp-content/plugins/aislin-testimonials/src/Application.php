<?php

namespace Aislin_Testimonials;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Application Class
 *
 * @package Aislin Testimonials
 * @since 1.0
 */
class Application extends Container {

	/**
	 * Application instance
	 *
	 * @var Application
	 */
	protected static $instance;

	/**
	 * Get Application instance
	 *
	 * @return self
	 */
	public static function get_instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new static;
		}
		return static::$instance;
	}

	/**
	 * Init application
	 */
	protected function __construct() {
		$this->load_textdomain();
		$this->setup_version();
		$this->setup_modules();
	}

	/**
	 * Load textdomain
	 */
	protected function load_textdomain() {
		load_plugin_textdomain(
			'aislin-testimonials',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

	/**
	 * Setup version
	 */
	protected function setup_version() {
		$this->share( 'version', AISLIN_TESTIMONIALS_VERSION );
	}

	/**
	 * Setup modules
	 */
	protected function setup_modules() {
		$service_providers = [
			Activation\Service_Provider::class,
			Scripts\Service_Provider::class,
			Post_Type\Service_Provider::class,
			Taxonomy\Service_Provider::class,
			Editor\Service_Provider::class,
			Shortcodes\Service_Provider::class,
			Compatibility\Service_Provider::class,
		];

		foreach ( apply_filters( 'aislin_testimonials/service-providers', $service_providers ) as $service_provider ) {
			( new $service_provider( $this ) )->register();
		}
	}

	public function get_url( $uri ) {
		return plugin_dir_url( dirname( __FILE__ ) ) . $uri;
	}

	public function get_path( $uri ) {
		return plugin_dir_path( dirname( __FILE__ ) ) . $uri;
	}

}
