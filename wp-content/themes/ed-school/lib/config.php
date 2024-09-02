<?php
define( 'ED_SCHOOL_THEME_OPTION_NAME', 'ed_school_options' );
define( 'ED_SCHOOL_THEME_NAME', 'ed-school' );
define( 'ED_SCHOOL_THEME_PLUGIN_NAME', 'ed-school-plugin' );
define( 'ED_SCHOOL_THEME_PREFIX', 'ed_school_' );

/**
 *  Length in words for excerpt_length filter (http://codex.wordpress.org/Plugin_API/Filter_Reference/excerpt_length)
 * This is just theme default value - it is overridden from theme options
 */ 
define( 'POST_EXCERPT_LENGTH', 40 );

add_theme_support( 'title-tag' );

if ( ! isset( $content_width ) ) {
	$content_width = 1200;
}

/**
 * Woocommerce Support Declaration
 */
add_theme_support( 'woocommerce' );
add_theme_support( 'wc-product-gallery-zoom' );
add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-slider' );

/**
 * Force Visual Composer to initialize as "built into the theme". 
 * This will hide certain tabs under the Settings->Visual Composer page
 */
add_action( 'vc_before_init', 'ed_school_vc_set_as_theme' );
function ed_school_vc_set_as_theme() {
	vc_set_as_theme( true );
}

/**
 * Mega Menus
 */
add_action( 'msm_filter_use_redux', 'ed_school_use_msm_redux' );
function ed_school_use_msm_redux() {
	return false;
}
