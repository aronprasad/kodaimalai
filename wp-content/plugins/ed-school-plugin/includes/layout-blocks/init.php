<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', 'ed_school_plugin_layout_blocks_fonts' );

require_once ED_SCHOOL_PLUGIN_PATH . 'extensions/CPT.php';
$ed_school_plugin_layout_blocks = new CPT( 'layout_block', apply_filters( 'ed_school_plugin_filter_layout_block_settings', array(
	'show_in_rest'  => true,
	'menu_position' => 29,
	'supports'      => array( 'title', 'editor', 'revisions' )
) ) );
$ed_school_plugin_layout_blocks->register_taxonomy( array(
	'taxonomy_name' => 'layout_block_type',
	'singular'      => 'Type',
	'plural'        => 'Type',
	'slug'          => 'type',
));
$ed_school_plugin_layout_blocks->filters( array( 'layout_block_type' ) );

$ed_school_plugin_layout_blocks->columns( [
	'cb'                => '<input type="checkbox" />',
	'title'             => __( 'Title', 'ed-school-plugin' ),
	'location'          => __( 'Location', 'ed-school-plugin' ),
	'layout_block_type' => __( 'Type', 'ed-school-plugin' ),
	'date'              => __( 'Date', 'ed-school-plugin' ),
] );

$ed_school_plugin_layout_blocks->populate_column( 'location', function ( $column, $post ) {
	$map = ed_school_plugin_get_assigned_layout_blocks();
	if ( count( $map ) ) {
		foreach ( $map as $key => $value ) {
			if ( $post->ID == (int) $value ) {
				echo esc_html( ucwords( str_replace('-', ' ', $key) ) );
			}
		}
	}
} );

function ed_school_plugin_get_assigned_layout_blocks() {
	$layout_block_locations = [];
	if ( function_exists( 'ed_school_get_registered_layout_blocks' ) ) {
		foreach ( ed_school_get_registered_layout_blocks() as $lb) {
			$layout_block_locations[$lb] = ed_school_get_option( $lb, false );
		}
	}
	return $layout_block_locations;
}

function ed_school_plugin_layout_blocks_fonts() {

	$results = array();

	return;

	// check for post type
	$parsed_link = parse_url( 'https://fonts.google.com/?selection.family=Open+Sans:300i,400,600|Source+Sans+Pro:400,400i,600&selection.subset=cyrillic,greek' );


	parse_str( $parsed_link['query'], $results );

	$link = '';

	foreach ( $results as $key => $value ) {
		switch ( $key ) {
			case 'selection_family':
				$link['family'] = $value;
				break;

			case 'selection_subset':
				$link['subset'] = $value;
				break;
			
			default:
				break;
		}
	}
	$font_url = add_query_arg( 'family', urlencode( implode('&amp;', $link) ), "//fonts.googleapis.com/css" );

	wp_enqueue_style( 'layout-block-fonts', $font_url, false );
}
