<?php
$current_theme = wp_get_theme();

$content = '';
$content .= '<p>';
$content .= esc_html__( 'Your theme is supported. Please visit General Settings and select your Main and Mobile menu locations.', 'mega-submenu' );
$content .= '</p>';

$fields = array(
	'id'       => 'opt-raw',
	'type'     => 'raw',
	'title'    => sprintf( 'You are using %s theme', esc_html( $current_theme->get( 'Name' ) ) ),
	'content'  => $content,
);

return $fields;
