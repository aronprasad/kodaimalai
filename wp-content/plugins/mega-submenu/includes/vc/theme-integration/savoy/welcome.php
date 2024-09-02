<?php
$current_theme = wp_get_theme();

$content = '';
$content .= '<p>';
$content .= esc_html__( 'Edit footer.php from line 112 to 138 replace any occurrence of "wp_nav_menu" function with "msm_mobile_wp_nav_menu". There should be 3 occurrences.', 'mega-submenu' );
$content .= '</p>';

$fields = array(
	'id'       => 'opt-raw',
	'type'     => 'raw',
	'title'    => sprintf( 'You are using %s theme', esc_html( $current_theme->get( 'Name' ) ) ),
	'content'  => $content,
);

return $fields;
