<?php

/**
 * Savoy theme registers menu locations very late so we have to do it via filter
 */
add_filter( 'msm_filter_menu_location', 'msm_savoy_filter_menu_location' );
function msm_savoy_filter_menu_location( $menu_location ) {
	return 'main-menu';
}

add_filter( 'msm_filter_submenu_before', 'msm_savoy_filter_submenu_before', 11 );
function msm_savoy_filter_submenu_before( $before ) {
	if ( msm_in_mobile_menu() ) {
		return '<ul class="sub-menu"><li>';
	}
	return $before;
}

add_filter( 'msm_filter_submenu_after', 'msm_savoy_filter_submenu_after', 11 );
function msm_savoy_filter_submenu_after( $after ) {
	if ( msm_in_mobile_menu() ) {
		return '</li></ul>';
	}
	return $after;
}

/**
 * Mobile menu custom css
 */
add_action( 'wp_enqueue_scripts', 'msm_savoy_style', 11 );

function msm_savoy_style() {

	$style = '
	#nm-mobile-menu ul li.msm-menu-item > .nm-menu-toggle {
		display: inline-block;
	}
	#nm-mobile-menu ul li.msm-menu-item > .nm-menu-toggle:hover {
		cursor: pointer;
	}';
	wp_add_inline_style( MSM_PLUGIN_SLUG, $style );
}