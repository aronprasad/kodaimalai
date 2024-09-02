<?php
$current_theme = wp_get_theme();

$content = '';
$content .= '<p>';
$content .= esc_html__( 'Your theme is supported. Please visit General Settings and select your Main and Mobile menu locations.', 'mega-submenu' );
$content .= '</p>';
$content .= '<p>';
$content .= esc_html__( 'Also please make sure that your setting for Floating Header Effect is set to Sticky.', 'mega-submenu' );
$content .= '</p>';
$content .= '<p>';
$content .= esc_html__( 'To have our templates display correctly, please check if the Visual Composer row is set to "Default".', 'mega-submenu' );
$content .= '</p>';

$fields = array(
	'id'       => 'opt-raw',
	'type'     => 'raw',
	'title'    => sprintf( 'You are using %s theme', esc_html( $current_theme->get( 'Name' ) ) ),
	'content'  => $content,
);

return $fields;