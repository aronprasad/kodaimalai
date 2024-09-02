<?php
require_once get_template_directory() . '/lib/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'ed_school_register_required_plugins' );

function ed_school_register_required_plugins() {

    $plugins = array(
        // Plugins pre-packaged with a theme
        array(
            'name'               => esc_html__( 'Ed School Plugin', 'ed-school' ),
            'slug'               => 'ed-school-plugin',
            'source'             => get_template_directory() . '/extensions/ed-school-plugin.zip',
            'required'           => true,
            'version'            => '3.23.0',
            'force_activation'   => false,
            'force_deactivation' => false,
            'external_url'       => '',
        ),
        array(
            'name'               => esc_html__( 'Aislin Classroom', 'ed-school' ),
            'slug'               => 'aislin-classroom',
            'source'             => get_template_directory() . '/extensions/aislin-classroom.zip',
            'required'           => true,
            'version'            => '1.0.2',
            'force_activation'   => false,
            'force_deactivation' => false,
            'external_url'       => '',
        ),
        array(
            'name'               => esc_html__( 'Mammoth Mega Submenu', 'ed-school' ),
            'slug'               => 'mega-submenu',
            'source'             => get_template_directory() . '/extensions/mega-submenu.zip',
            'required'           => false,
            'version'            => '1.3.2',
            'force_activation'   => false,
            'force_deactivation' => false,
            'external_url'       => '',
        ),
        array(
            'name'               => esc_html__( 'Aislin Testimonials', 'ed-school' ),
            'slug'               => 'aislin-testimonials',
            'source'             => get_template_directory() . '/extensions/aislin-testimonials.zip',
            'required'           => false,
            'version'            => '1.0.0',
            'force_activation'   => false,
            'force_deactivation' => false,
            'external_url'       => '',
        ),
	    array(
		    'name'               => esc_html__( 'WPBakery Page Builder', 'ed-school' ),
		    'slug'               => 'js_composer',
		    'source'             => get_template_directory() . '/extensions/js_composer.zip',
		    'required'           => true,
		    'version'            => '7.6',
		    'force_activation'   => false,
		    'force_deactivation' => false,
		    'external_url'       => '',
	    ),
	    array(
            'name'               => esc_html__( 'Revolution Slider', 'ed-school' ),
            'slug'               => 'revslider',
            'source'             => get_template_directory() . '/extensions/revslider.zip',
            'required'           => false,
            'version'            => '6.7.11',
            'force_activation'   => false,
            'force_deactivation' => false,
            'external_url'       => '', // If set, overrides default API URL and points to an external URL
        ),
        // Plugins from the WordPress Plugin Repository
        array(
            'name'     => esc_html__( 'Elementor', 'ed-school' ),
            'slug'     => 'elementor',
            'required' => true,
        ),
        array(
            'name'     => esc_html__( 'Meta Box', 'ed-school' ),
            'slug'     => 'meta-box',
            'required' => true,
        ),
	    array(
            'name'     => esc_html__( 'Redux Framework', 'ed-school' ),
            'slug'     => 'redux-framework',
            'required' => true,
        ),
	    array(
	        'name'     => esc_html__( 'Contact Form 7', 'ed-school' ),
            'slug'     => 'contact-form-7',
            'required' => false,
        ),
        array(
            'name'     => esc_html__( 'The Events Calendar', 'ed-school' ),
            'slug'     => 'the-events-calendar',
            'required' => false,
        ),
        array(
            'name'     => esc_html__( 'Breadcrumb Trail', 'ed-school' ),
            'slug'     => 'breadcrumb-trail',
            'required' => false,
        ),
        array(
            'name'     => esc_html__( 'Optimize Image', 'ed-school' ),
            'slug'     => 'optimize-images-resizing',
            'required' => false,
        ),
        array(
            'name'        => esc_html__( 'Envato Market', 'ed-school' ),
            'slug'        => 'envato-market',
            'source'      => 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
            'required'    => true,
            'recommended' => true,
        ),
    );

	// messages
	$messages = array(
		esc_html__( 'If you are not able to complete plugin installation process due to server issues please install the plugins manually. All required plugins are located in "extensions" folder in your main download from Themeforest.', 'ed-school' ),
		sprintf( esc_html__( 'After you finish installing plugins go back to %s page to complete the installation.', 'ed-school' ), '<a href="' . admin_url( 'themes.php?page=theme_activation_options' ) . '" title="' . esc_html__( 'Theme Activation', 'ed-school' ) . '">' . esc_html__( 'Theme Activation', 'ed-school' ) . '</a>' ),
	);
	$final_message = '';
	foreach ( $messages as $message ) {
		$final_message .= sprintf( '<div class="updated fade"><p>%s</p></div>', $message );
	}

	/**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'domain'           => 'ed-school', // Text domain - likely want to be the same as your theme.
        'default_path'     => '', // Default absolute path to pre-packaged plugins
        'menu'             => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'      => 'themes.php',
        'has_notices'      => true, // Show admin notices or not
        'is_automatic'     => false, // Automatically activate plugins after installation or not
        'message'          => $final_message, // Message to output right before the plugins table

    );

    tgmpa( apply_filters( 'ed-school-tgmpa-filter-plugins', $plugins ), $config );
}
