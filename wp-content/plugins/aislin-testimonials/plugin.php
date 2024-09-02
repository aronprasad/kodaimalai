<?php
/**
 * Plugin Name: Aislin Testimonials
 * Plugin URI:  http://themeforest.net/user/Aislin/portfolio
 * Description: Displays testimonials.
 * Version:     1.0.0
 * Author:      Aislin Themes
 * Author URI:  http://themeforest.net/user/Aislin/portfolio
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'AISLIN_TESTIMONIALS_VERSION', '1.0.0' );

register_activation_hook( __FILE__, 'aislin_testimonials_activate' );
register_deactivation_hook( __FILE__, 'aislin_testimonials_deactivate' );

add_action( 'plugins_loaded', 'aislin_testimonials_init' );

/**
 * Initialize plugin
 *
 * @since  1.0
 */
function aislin_testimonials_init() {
	require __DIR__ . '/vendor/autoload.php';
	aislin_testimonials_app();
}


/**
 * Activation code
 *
 * @since  1.0
 */
function aislin_testimonials_activate() {
	aislin_testimonials_init();
	do_action( 'aislin_testimonials/activate' );
}

/**
 * Deactivation code
 *
 * @since  1.0
 */
function aislin_testimonials_deactivate() {
	aislin_testimonials_init();
	do_action( 'aislin_testimonials/deactivate' );
}

