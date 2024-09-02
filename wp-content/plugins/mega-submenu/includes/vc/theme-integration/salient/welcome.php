<?php
$current_theme = wp_get_theme();

$content = '';
$content .= '<p>';
$content .= esc_html__( 'Edit header.php around line 263 replace "wp_nav_menu" function with "msm_mobile_wp_nav_menu".', 'mega-submenu' );
$content .= '</p>';
$content .= '<p>';
$content .= esc_html__( 'Edit footer.php from line 193 to 205 replace any occurrence of "wp_nav_menu" function with "msm_mobile_wp_nav_menu". There should be 2 occurrences.', 'mega-submenu' );
$content .= '</p>';
$content .= '<p>';
$content .= esc_html__( 'Header Permanent Transparent feature is not supported.', 'mega-submenu' );
$content .= '</p>';

$fields = array(
	'id'       => 'opt-raw',
	'type'     => 'raw',
	'title'    => sprintf( 'You are using %s theme', esc_html( $current_theme->get( 'Name' ) ) ),
	'content'  => $content,
);

return $fields;
