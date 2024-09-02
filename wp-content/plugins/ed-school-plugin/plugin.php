<?php
/**
 * Plugin Name: Ed School Plugin
 * Plugin URI:  http://wordpress.org/plugins
 * Description: Ed School theme helper plugin
 * Version:     3.23.0
 * Author:      Aislin Themes
 * Author URI:  http://themeforest.net/user/Aislin/portfolio
 * License:     GPLv2+
 * Text Domain: ed-school-plugin
 * Domain Path: /languages
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ED_SCHOOL_PLUGIN_NAME', 'Ed School' );
define( 'ED_SCHOOL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ED_SCHOOL_PLUGIN_PATH', dirname( __FILE__ ) . '/' );

register_activation_hook( __FILE__, 'ed_school_plugin_activate' );
register_deactivation_hook( __FILE__, 'ed_school_plugin_deactivate' );

add_action( 'plugins_loaded', 'ed_school_plugin_init' );

function ed_school_plugin_init() {
	ed_school_plugin_load_textdomain();

	require_once ED_SCHOOL_PLUGIN_PATH . 'includes/includes.php';

	add_option( 'tribe_events_calendar_options', array(
		'tribeEventsTemplate' => 'template-fullwidth.php',
	) );
}

function ed_school_plugin_activate() {
	ed_school_plugin_init();
	flush_rewrite_rules();
}

function ed_school_plugin_deactivate() {

}

function ed_school_plugin_load_textdomain() {
	load_plugin_textdomain( 'ed-school-plugin', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}
