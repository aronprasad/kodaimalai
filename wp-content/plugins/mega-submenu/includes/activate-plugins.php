<?php
add_action( 'tgmpa_register', 'msm_register_required_plugins' );

function msm_register_required_plugins() {

	$plugins = array(
		array(
			'name'      => 'Redux Framework',
			'slug'      => 'redux-framework',
			'required'  => true,
		),
	);

	$config = array(
		'id'           => 'mega-submenu',
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'parent_slug'  => 'plugins.php',
		'capability'   => 'manage_options',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => false,
		'message'      => '',
	);

	if ( function_exists( 'tgmpa' ) ) {
		tgmpa( $plugins, $config );
	}
}
