<?php

if ( ! class_exists( 'Redux' ) ) {
	return;
}

$msm_opt_name     = MSM_OPTION_NAME;
$msm_display_name = esc_html__( 'Mega Submenu Settings', 'mega-submenu' );
$msm_page_parent  = 'edit.php?post_type=' . MSM_Mega_Submenu::POST_TYPE;

add_filter( 'redux/options/' . $msm_opt_name . '/compiler', 'msm_compiler_action', 10, 3 );

function msm_compiler_action( $options, $css, $changed_values ) {

	global $wp_filesystem;
    if ( ! $wp_filesystem ) {
        if ( ! WP_Filesystem() ) {
        	return;
        }
    }

	$msm_opt_name = MSM_OPTION_NAME;

	$upload_dir = wp_upload_dir();
	$filename   = $upload_dir['basedir'] . '/' . $msm_opt_name . '_style.css';
	$filename = apply_filters('wheels_redux_compiler_filename', $filename);


	$filecontent = "/********* Compiled file/Do not edit *********/\n";
	$filecontent .= $css;

	$option_name = 'submenu-offset-top';
	if ( isset( $options[ $option_name ] ) && $options[ $option_name ] ) {
		$filecontent .= '.msm-menu-item .msm-submenu{top:' . (int) $options[ $option_name ] . 'px}';
	}

	$option_name = 'submenu-top-hover-area';
	if ( isset( $options[ $option_name ] ) ) {
		$option = (int) $options[ $option_name ];
		$filecontent .= '.msm-menu-item .msm-submenu:before{';
		$filecontent .= 'top:' . (int) $options[ $option_name ] . 'px;';
		$filecontent .= 'height:' . (int) $options[ $option_name ] . 'px;';
		$filecontent .= '}';
	}


	if ( is_writable( $upload_dir['basedir'] ) ) {
		$wp_filesystem->put_contents( $filename, $filecontent );

	} else {
		wp_die( esc_html__( "It looks like your upload folder isn't writable, so PHP couldn't make any changes (CHMOD).", 'mega-submenu' ), esc_html__( 'Cannot write to file', 'mega-submenu' ), array('back_link' => true ) );
	}

}

$msm_redux_args = array(
	'opt_name'             => $msm_opt_name,
	'display_name'         => $msm_display_name,
	'display_version'      => MSM_PLUGIN_VERSION,
	'menu_type'            => 'submenu',
	'allow_sub_menu'       => false,
	'menu_title'           => esc_html__( 'Settings', 'mega-submenu' ),
	'page_title'           => esc_html__( 'Settings', 'mega-submenu' ),
	'google_api_key'       => 'AIzaSyBETK1Pd_dt2PYIGteFgKS25rp6MmQFErw',
	'google_update_weekly' => false,
	'async_typography'     => true,
	'admin_bar'            => true,
	'admin_bar_icon'       => 'dashicons-portfolio',
	'admin_bar_priority'   => 50,
	'dev_mode'             => false,
	'customizer'           => false,
	'page_parent'          => $msm_page_parent,
	'page_permissions'     => 'manage_options',
	'page_slug'            => '_mega_submenu_settings',
	'save_defaults'        => true,
	'default_show'         => false,
	'show_import_export'   => true,
	'output'               => true,
	'output_tag'           => true,
);

Redux::setArgs( $msm_opt_name, $msm_redux_args );
