<?php
add_filter( 'post_class', 'ed_school_oddeven_post_class' );
add_filter( 'body_class', 'ed_school_filter_body_class' );
add_filter( 'msm_filter_menu_location', 'ed_school_msm_filter_menu_location' );
add_filter( 'msm_filter_load_compiled_style', 'ed_school_msm_filter_load_compiled_style' );
add_filter( 'breadcrumb_trail_labels', 'ed_school_breadcrumb_trail_labels' );

global $wp_version;
if ( version_compare( $wp_version, '5.0', '<' ) ) {
	add_filter( 'wp_link_pages_link', 'ed_school_wp_link_pages_link', 10, 2 );
}

function ed_school_is_element_empty( $element ) {
	$element = trim( $element );
	return empty( $element ) ? true : false;
}

function ed_school_get_thumbnail( $args ) {

	$defaults = array(
		'thumbnail' => 'thumbnail',
		'post_id'   => null,
		'link'      => false,
		'format'    => '',
	);

	$args = wp_parse_args( $args, $defaults );

	if ( $args['post_id'] ) {
		$post_id = $args['post_id'];
	} else {
		global $post_id;
	}

	$img_url = '';
	if ( has_post_thumbnail( $post_id ) ) {
		$img_url = get_the_post_thumbnail( $post_id, $args['thumbnail'], array(
			'class' => $args['thumbnail']
		) );
	}

	if ( '' != $img_url && $args['format'] === 'array' ) {
		/* Set up a default empty array. */
		$out = array();
		/* Get the image attributes. */
		$atts = wp_kses_hair( $img_url, array( 'http', 'https' ) );
		/* Loop through the image attributes and add them in key/value pairs for the return array. */
		foreach ( $atts as $att ) {
			$out[ $att['name'] ] = $att['value'];
		}
		/* Return the array of attributes. */
		return $out;
	}

	$out = '';
	if ( '' != $img_url ) {
		if ( $args['link'] ) {
			$out = '<a href="' . esc_url( get_permalink( $post_id ) ) . '" title="' . esc_attr( get_post_field( 'post_title', $post_id ) ) . '">' . $img_url . '</a>';
		} else {
			$out = $img_url;
		}
	}

	return $out;
}

function ed_school_pagination( $pages = '', $range = 2 ) {
	$showitems = ( $range * 2 ) + 1;

	global $paged;
	if ( empty( $paged ) ) {
		$paged = 1;
	}

	if ( $pages == '' ) {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if ( ! $pages ) {
			$pages = 1;
		}
	}

	if ( 1 != $pages ) {
		echo "<div class='pagination'>";
		if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) {
			echo "<a href='" . esc_url( get_pagenum_link( 1 ) ) . "'>&laquo;</a>";
		}
		if ( $paged > 1 && $showitems < $pages ) {
			echo "<a href='" . esc_url( get_pagenum_link( $paged - 1 ) ) . "'>&lsaquo;</a>";
		}

		for ( $i = 1; $i <= $pages; $i ++ ) {
			if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
				if ( $paged == $i ) {
					echo "<span class='current'>" . esc_html( $i ) . "</span>";
				} else {
					echo "<a href='" . esc_url( get_pagenum_link( $i ) ) . "' class='inactive' >" . esc_html( $i ) . "</a>";
				}
			}
		}

		if ( $paged < $pages && $showitems < $pages ) {
			echo "<a href='" . esc_url( get_pagenum_link( $paged + 1 ) ) . "'>&rsaquo;</a>";
		}
		if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) {
			echo "<a href='" . esc_url( get_pagenum_link( $pages ) ) . "'>&raquo;</a>";
		}
		echo "</div>\n";
	}
}

function ed_school_grid_class_map() {

	return array(
		array( 'one twelfth', 'eleven twelfths' ),  // 1/11
		array( 'one sixth', 'five sixths' ),        // 2/10
		array( 'one fourth', 'three fourths' ),     // 3/9
		array( 'one third', 'two thirds' ),         // 4/8
		array( 'five twelfths', 'seven twelfths' ), // 5/7
		array( 'one half', 'one half' ),            // 6/6
		array( 'seven twelfths', 'five twelfths' ), // 7/5
		array( 'two thirds', 'one third' ),         // 8/4
		array( 'three fourths', 'one fourth' ),     // 9/3
		array( 'five sixths', 'one sixth' ),        // 10/2
		array( 'eleven twelfths', 'one twelfth' ),  // 11/1
		array( 'one whole', 'one whole' ),          // 12/12
	);
}

function ed_school_get_grid_class( $index, $invert = false ) {
	$grid = ed_school_grid_class_map();
	return isset( $grid[ $index ] ) ? $grid[ $index ][ $invert ? 1 : 0 ] : '';
}

function ed_school_get_option( $option_name, $default = false ) {
	$options = isset( $GLOBALS[ ED_SCHOOL_THEME_OPTION_NAME ] ) ? $GLOBALS[ ED_SCHOOL_THEME_OPTION_NAME ] : false;

	if ( $options && is_string( $option_name ) ) {
		return isset( $options[ $option_name ] ) ? $options[ $option_name ] : $default;
	}
	return $default;
}

function ed_school_custom_css() {
	$custom_css = ed_school_get_option( 'custom-css', '' );

	// Get custom page title bg
	$custom_page_title_bg_image_url = ed_school_get_rwmb_meta_image_url( 'custom_page_title_background' );
	if ( $custom_page_title_bg_image_url ) {
		$custom_css .= ".wh-page-title-bar{background-image:url({$custom_page_title_bg_image_url})}";
	}

	return $custom_css;
}

function ed_school_responsive_menu_scripts() {
	$css = '.header-mobile {display: none;}';
	$respmenu_show_start = (int) ed_school_get_option( 'header-mobile-break-point', 1000 );
	if ( $respmenu_show_start ) {
		$css .= '@media screen and (max-width:' . intval( $respmenu_show_start ) . 'px) {';
		$css .= '.header-left {padding-left: 0;}';
		$css .= '.wh-header, .wh-top-bar {display: none;}';
		$css .= '.header-mobile {display: block;}';
		$css .= '}';
	}
	return $css;
}

function ed_school_filter_array( $filter_name, $default = array() ) {
	$filtered = apply_filters( $filter_name, $default );
	if ( ! is_array( $filtered ) || ! count( $filtered ) ) {
		$filtered = $default;
	}
	return array_unique( $filtered );
}

function ed_school_array_val_concat( $array = null, $postfix = '', $default = array() ) {
	if ( is_array( $array ) ) {
		$res = array();
		foreach ( $array as $val ) {
			$res[] = $val . $postfix;
		}
		return $res;
	}
	return $default;
}

function ed_school_get_rwmb_meta( $key, $post_id, $options = array() ) {
	$prefix = ed_school_get_rwmb_prefix();
	$value  = false;
	if ( function_exists( 'rwmb_meta' ) ) {
		$value = rwmb_meta( $prefix . $key, $options, $post_id );
	}
	return $value;
}

function ed_school_get_rwmb_meta_image_url( $key, $post_id = false ) {
	if ( ! $key ) {
		return '';
	}
	if ( ! $post_id ) {
		$post_id = get_queried_object_id();
	}
	if ( ! $post_id ) {
		return '';
	}
	$image_url = '';
	$image = ed_school_get_rwmb_meta( $key, $post_id, array( 'type' => 'image' ) );
	if ( is_array( $image ) && count( $image ) ) {
		$image = reset( $image );    // get first element
		$image_url = isset( $image['full_url'] ) ? $image['full_url'] : '';
	}
	return $image_url;
}

if ( ! function_exists( 'ed_school_get_logo_url' )) {

	function ed_school_get_logo_url() {
		$logo_url = '';

		// Get custom page logo
		$logo_url = ed_school_get_rwmb_meta_image_url( 'custom_logo' );
		if ( $logo_url ) {
			return $logo_url;
		}

		// Get default logo
		$logo     = ed_school_get_option( 'logo', array() );
		$logo_url = isset( $logo['url'] ) && $logo['url'] ? $logo['url'] : '';


		return $logo_url;
	}
}

function ed_school_strip_comments( $string ) {

	$regex = array(
		"`^([\t\s]+)`ism"                       => '',
		"`^\/\*(.+?)\*\/`ism"                   => "",
		"`(\A|[\n;]+)/\*.+?\*/`s"               =>"$1", 
		"`(\A|[;\s]+)//.+\R`"                   =>"$1\n", 
		"`(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+`ism" => "\n"
	);

	return preg_replace( array_keys( $regex ), $regex, $string );
}

function ed_school_get_child_pages() {
	global $post;
	$args = array(
		'child_of'    => $post->ID,
		'sort_column' => 'menu_order',
	);
	$pages = get_pages( $args );
	return count( $pages );
}

function ed_school_get_top_ancestor_id() {
	global $post;
	if ( $post->post_parent ) {
		$ancestors = array_reverse( get_post_ancestors( $post->ID ) );
		return $ancestors[0];
	}
	return $post->ID;
}

function ed_school_page_title_enabled() {
	$page_title_enabled = is_single() ? ed_school_get_option( 'archive-single-use-page-title', true ) : true;
	return apply_filters( 'ed_school_filter_page_title_enabled', $page_title_enabled );
}

function ed_school_get_rwmb_prefix() {
	$prefix = ED_SCHOOL_THEME_PREFIX;
	if ( defined( 'ED_SCHOOL_RWMB_META_PREFIX' ) ) {
		$prefix = ED_SCHOOL_RWMB_META_PREFIX;
	}
	return $prefix;
}

function ed_school_filter_body_class( $body_classes ) {
	$body_classes[] = 'header-' . ed_school_get_option( 'header-location', 'top' );
	if ( ed_school_page_title_enabled() ) {
		$body_classes[] = 'page-title-enabled';
	}
	if ( is_single() ) {
		$body_classes[] = 'single-layout-' . ed_school_get_option( 'blog-single-layout', 'default' );
	}
	return $body_classes;
}

function ed_school_msm_filter_menu_location( $menu_location ) {
	global $post_id;
	$use_custom_menu_location = ed_school_get_rwmb_meta( 'use_custom_menu', $post_id );
	if ( $use_custom_menu_location ) {
		$custom_menu_location = ed_school_get_rwmb_meta( 'custom_menu_location', $post_id );
		if ( ! empty( $custom_menu_location ) ) {
			return $custom_menu_location;
		}
	}
	return $menu_location;
}

function ed_school_msm_filter_load_compiled_style() {
	return false;
}

function ed_school_oddeven_post_class( $classes ) {
	static $current_class;
	$current_class = ( $current_class == 'odd' ) ? 'even' : 'odd';
	$classes[]     = $current_class;

	return $classes;
}

function ed_school_breadcrumb_trail_labels($labels) {

	return wp_parse_args( array(
		'browse'              => esc_html__( 'Browse:',                               'ed-school' ),
		'aria_label'          => esc_attr_x( 'Breadcrumbs', 'breadcrumbs aria label', 'ed-school' ),
		'home'                => esc_html__( 'Home',                                  'ed-school' ),
		'error_404'           => esc_html__( '404 Not Found',                         'ed-school' ),
		'archives'            => esc_html__( 'Archives',                              'ed-school' ),
		// Translators: %s is the search query. The HTML entities are opening and closing curly quotes.
		'search'              => esc_html__( 'Search results for &#8220;%s&#8221;',   'ed-school' ),
		// Translators: %s is the page number.
		'paged'               => esc_html__( 'Page %s',                               'ed-school' ),
		// Translators: Minute archive title. %s is the minute time format.
		'archive_minute'      => esc_html__( 'Minute %s',                             'ed-school' ),
		// Translators: Weekly archive title. %s is the week date format.
		'archive_week'        => esc_html__( 'Week %s',                               'ed-school' ),
	), $labels);

}

function ed_school_wp_link_pages_link( $link, $i ) {
	global $page;

	if ( $i === $page ) {
		$link = '<span class="post-page-numbers current" aria-current="page">' . $link . '</span>';
	}

	return $link;
}

function ed_school_is_post_list() {
	return is_home() || is_archive() || is_search();
}

function ed_school_is_sticky_menu_enabled() {
	return filter_var( ed_school_get_option( 'main-menu-use-menu-is-sticky', false ), FILTER_VALIDATE_BOOLEAN );
}
