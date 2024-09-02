<?php

/**
 * Visual Composer post CSS
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'msm_get_vc_post_custom_css' ) ) {
	function msm_get_vc_post_custom_css( $id ) {
		$out = '';
		if ( $id ) {
			$post_custom_css = get_post_meta( $id, '_wpb_post_custom_css', true );
			if ( ! empty( $post_custom_css ) ) {
				$post_custom_css = strip_tags( $post_custom_css );
				$out .= $post_custom_css;
			}
		}
		return $out;
	}
}

/**
 * Visual Composer shortcodes CSS
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'msm_get_vc_shortcodes_custom_css' ) ) {
	function msm_get_vc_shortcodes_custom_css( $id ) {
		$out = '';
		if ( $id ) {
			$shortcodes_custom_css = get_post_meta( $id, '_wpb_shortcodes_custom_css', true );
			if ( ! empty( $shortcodes_custom_css ) ) {
				$shortcodes_custom_css = strip_tags( $shortcodes_custom_css );
				$out .= $shortcodes_custom_css;
			}
		}
		return $out;
	}
}



