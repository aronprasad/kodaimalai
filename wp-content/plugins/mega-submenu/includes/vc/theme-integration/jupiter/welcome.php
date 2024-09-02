<?php
$current_theme = wp_get_theme();

$content = '';
$content .= '<p>';
$content .= esc_html__( 'Edit views/header/global/responsive-menu.php arround line 24 and replace the occurrence of "wp_nav_menu" function with "msm_mobile_wp_nav_menu".', 'mega-submenu' );

$content .= '</p>';

$fields = array(
	'id'       => 'opt-raw',
	'type'     => 'raw',
	'title'    => sprintf( 'You are using %s theme', esc_html( $current_theme->get( 'Name' ) ) ),
	'content'  => $content,
);

return $fields;
