<?php
add_action( 'init', 'ed_school_custom_logo_init' );
add_action( 'customize_save_after', 'ed_school_customize_save_after' );
add_action( 'redux/customizer/live_preview', 'ed_school_customizer');

function ed_school_customizer() {
	if ( class_exists( 'ReduxFrameworkInstances' ) ) {
		$redux = ReduxFrameworkInstances::get_instance( ED_SCHOOL_THEME_OPTION_NAME );
		$redux->_enqueue_output();
		wp_add_inline_style( 'ed-school-style', $redux->compilerCSS );
	}
}

function ed_school_set_option( $option_name, $value ) {
	$options = isset( $GLOBALS[ ED_SCHOOL_THEME_OPTION_NAME ] ) ? $GLOBALS[ ED_SCHOOL_THEME_OPTION_NAME ] : false;

	if ( $options && is_string( $option_name ) ) {
		$options[ $option_name ] = $value;
		update_option( ED_SCHOOL_THEME_OPTION_NAME, $options );
		return true;
	}
	return false;
}

function ed_school_get_logo_url() {
	$logo_url = '';

	// Get custom page logo
	$logo_url = ed_school_get_rwmb_meta_image_url( 'custom_logo' );
	if ( $logo_url ) {
		return $logo_url;
	}

	// Default WP Custom Logo implementation
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$image = wp_get_attachment_image_src( $custom_logo_id, 'full' );

	if ( isset( $image[0] ) ) {
		$logo_url = $image[0];
	} else {
		// Get default logo
		$logo     = ed_school_get_option( 'logo', array() );
		$logo_url = isset( $logo['url'] ) && $logo['url'] ? $logo['url'] : '';
	}
	return $logo_url;
}


function ed_school_custom_logo_init() {

	$custom_logo_id = get_theme_mod( 'custom_logo' );

	// do this only the first time when custom_logo does not exist
	if ( $custom_logo_id === false ) {
		$logo     = ed_school_get_option( 'logo', array() );
		$logo_url = isset( $logo['url'] ) && $logo['url'] ? $logo['url'] : '';

		$attachment_id = attachment_url_to_postid( $logo_url );

		if ( $attachment_id ) {
			set_theme_mod( 'custom_logo', $attachment_id );
		}
	} 
}

function ed_school_customize_save_after( $customizer ) {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$image          = wp_get_attachment_image_src( $custom_logo_id, 'full' );
	$logo           = array( 'url' => $image[0] );

	if ( isset( $image[0] ) && ! empty( $image[0] ) ) {
		ed_school_set_option( 'logo', $logo );
	}
}

function ed_school_set_custom_logo_from_theme_options( $logo ) {
	$logo_url      = isset( $logo['url'] ) && $logo['url'] ? $logo['url'] : '';
	$attachment_id = attachment_url_to_postid( $logo_url );
	if ( $attachment_id ) {
		set_theme_mod( 'custom_logo', $attachment_id );
	}
}
