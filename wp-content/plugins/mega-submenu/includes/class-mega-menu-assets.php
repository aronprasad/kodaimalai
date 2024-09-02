<?php

class MSM_Assets {

	public static function parse_post_content_shortcodes() {
		$meta_key_id     = MSM_Mega_Submenu::META_ID;
		$theme_locations = get_nav_menu_locations();
		foreach ( array( msm_get_menu_location_primary(), msm_get_menu_location_theme_mobile() ) as $menu_location ) {
			
			if ( isset( $theme_locations[ $menu_location ] ) ) {

				$menu_obj = get_term( $theme_locations[ $menu_location ], 'nav_menu' );

				$meta_query = array(
					array(
						'key'     => $meta_key_id,
						'value'   => '0',
						'compare' => '>',
					),
				);

				if ( $menu_obj && property_exists( $menu_obj, 'slug' ) ) {

					$main_menu_items = wp_get_nav_menu_items( $menu_obj->slug, array( 'meta_query' => $meta_query ) );
					$mega_menu_ids = array();
					foreach ( $main_menu_items as $menu_item ) {
						$mega_menu_ids[] = get_post_meta( $menu_item->ID, $meta_key_id, true );
					}

					foreach ( $mega_menu_ids as $mega_menu_id ) {
						$content = get_post($mega_menu_id);
						self::parse_shortcodes( $content->post_content );
					}
				}
			}
		}

	}

	public static function parse_shortcodes( $content ) {
		if ( ! $content ) {
			return;
		}
		global $shortcode_tags;
		preg_match_all( '/' . get_shortcode_regex() . '/', $content, $shortcodes );
		foreach ( $shortcodes[2] as $index => $tag ) {
			$attr_array = shortcode_parse_atts( trim( $shortcodes[3][ $index ] ) );
			if ( isset( $shortcode_tags[$tag] ) ) {
				// using the same action so our addons can be used in mega menu
				do_action( "scp_load_styles_{$tag}", $attr_array );
				do_action( "aislin_shortcode_enqueue_scripts_{$tag}", $attr_array );
			}
		}
		foreach ( $shortcodes[5] as $shortcode_content ) {
			MSM_Assets::parse_shortcodes( $shortcode_content );
		}
	}

	public static function get_uid( $namespace, $atts ) {
		$class = implode('', (array) $atts);
		$class = hash('md5', $class);
		return "{$namespace}-{$class}";
	}
}