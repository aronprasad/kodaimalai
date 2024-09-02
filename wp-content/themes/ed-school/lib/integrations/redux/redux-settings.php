<?php

if ( ! class_exists( 'Redux' ) ) {
	return;
}

$ed_school_option_name = ED_SCHOOL_THEME_OPTION_NAME;

add_action( 'init', 'ed_school_redux_remove_demo_mode_link' );
add_action( 'admin_notices', 'ed_school_admin_notice_error' );
add_action( 'admin_init', 'ed_school_add_default_redux_settings' );

add_filter( 'redux/options/ed_school_options/defaults', 'ed_school_redux_filter_default_options' );
add_filter( 'redux/options/ed_school_options/reset', 'ed_school_redux_reset_default_logo' );

require_once get_template_directory() . '/lib/integrations/redux/class-accent-colors.php';
require_once get_template_directory() . '/lib/integrations/redux/compiler.php';

function ed_school_admin_notice_error() {

	$current_screen = get_current_screen();
	if ( $current_screen->id != 'toplevel_page__options' ) {
		return;
	}

	global $wp_filesystem;
    if ( $wp_filesystem || WP_Filesystem() ) {
        return;
    }
    
	$class = 'notice notice-error';
    $message = esc_html__('Theme has no direct access to the file system. Therefore, custom css file from Theme Options cannot be generated. Please try to insert the following code at the bottom of wp-config.php: define( "FS_METHOD", "direct" );', 'ed-school' );
    
	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message ); 
}

function ed_school_redux_remove_demo_mode_link() {
	if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
		remove_action( 'admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );
		remove_action( 'admin_notices', array( ReduxFrameworkPlugin::get_instance(), '_admin_notices' ) );
	}
}

function ed_school_redux_reset_default_logo() {
	set_theme_mod( 'custom_logo', '' );
}

function ed_school_add_default_redux_settings() {
	$redux_options = ed_school_get_default_redux_settings();
	if ( $redux_options ) {
		add_option( ED_SCHOOL_THEME_OPTION_NAME, $redux_options );
		add_option( ED_SCHOOL_THEME_OPTION_NAME . '-transients', array( 'run_compiler' => 1 ) );
	}
}

function ed_school_redux_filter_default_options( $default_options ) {
	$redux_options = ed_school_get_default_redux_settings();
	if ( $redux_options ) {
		return $redux_options;
	}
	return $default_options;
}

function ed_school_get_default_redux_settings() {
	$redux_options = false;
	$file = apply_filters( 'ed_school_filter_redux_default_settings_path', get_template_directory() . '/lib/integrations/envato_setup/content/style1/redux.json' );

	if ( file_exists( $file ) ) {

		global $wp_filesystem;
	    if ( ! $wp_filesystem ) {
	    	require_once ( ABSPATH . '/wp-admin/includes/file.php' );
	        if ( ! WP_Filesystem() ) {
	        	return;
	        }
	    }

		$settings = json_decode( $wp_filesystem->get_contents( $file ), true );
		if ( is_array( $settings ) ) {
			$settings['logo'] = array( 'url' => '' );
			$settings['logo-sticky'] = array( 'url' => '' );
			$settings['respmenu-logo'] = array( 'url' => '' );

			foreach (ed_school_get_registered_layout_blocks() as $layout_block) {
				$settings[$layout_block] = '';
			}
			
			$redux_options = apply_filters( 'ed_school_filter_default_redux_settings', $settings );
		}
	}

	return $redux_options;
}

$theme = wp_get_theme();

$ed_school_redux_args = array(
	'opt_name'             => $ed_school_option_name,
	'display_name'         => $theme->get( 'Name' ),
	'display_version'      => $theme->get( 'Version' ),
	'menu_type'            => 'menu',
	'allow_sub_menu'       => false,
	'menu_title'           => esc_html__( 'Theme Options', 'ed-school' ),
	'page_title'           => esc_html__( 'Theme Options', 'ed-school' ),
	'google_api_key'       => 'AIzaSyBETK1Pd_dt2PYIGteFgKS25rp6MmQFErw',
	'google_update_weekly' => false,
	'async_typography'     => false,
	'admin_bar'            => true,
	'admin_bar_icon'       => 'dashicons-portfolio',
	'admin_bar_priority'   => 50,
	'dev_mode'             => false,
	'customizer'           => true,
	'page_parent'          => 'themes.php',
	'page_permissions'     => 'manage_options',
	'page_slug'            => '_options',
	'save_defaults'        => false,
	'default_show'         => false,
	'show_import_export'   => true,
	'output'               => true,
	'output_tag'           => true,
);

if ( isset( $_GET['debug'] ) ) {
	$ed_school_redux_args['ajax_save'] = false;
}

Redux::setArgs( $ed_school_option_name, $ed_school_redux_args );
