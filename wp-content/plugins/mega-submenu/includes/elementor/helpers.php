<?php
if ( ! function_exists( 'msm_is_built_with_elementor' ) ) {
	function msm_is_built_with_elementor( $mega_menu_id ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			return \Elementor\Plugin::instance()->documents->get( $mega_menu_id )->is_built_with_elementor();
		}
		return false;
	}
}

// Valid only in the outside frame
if ( ! function_exists( 'msm_elementor_is_editor_mode' ) ) {
	function msm_elementor_is_editor_mode() {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			return \Elementor\Plugin::instance()->editor->is_edit_mode();
		}
		return false;
	}
}

if ( ! function_exists( 'msm_elementor_is_preview' ) ) {
	function msm_elementor_is_preview() {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			return \Elementor\Plugin::instance()->preview->is_preview_mode();
		}
		return false;
	}
}

if ( ! function_exists( 'msm_elementor_print_menu' ) ) {
	function msm_elementor_print_menu( $mega_menu_id ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			return \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $mega_menu_id );
		}
	}
}
