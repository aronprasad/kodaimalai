<?php
add_action( 'widgets_init', 'ed_school_widgets_init' );
add_filter( 'ed_school_icon_class', 'ed_school_filter_icon_class' );

function ed_school_add_image_sizes() {
	add_image_size( 'ed-school-featured-image', 990, 500, true );
	add_image_size( 'ed-school-medium', 768, 510, true );
	add_image_size( 'ed-school-medium-alt', 768, 410, true );
	add_image_size( 'ed-school-square', 768, 768, true );
	add_image_size( 'ed-school-square-small', 420, 420, true );
}

function ed_school_enqueue_third_party_styles() {
	wp_enqueue_style( 'groundwork-grid', get_template_directory_uri() . '/assets/css/groundwork-responsive.css', false );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', false );
	wp_enqueue_style( 'js_composer_front' );
}

function ed_school_enqueue_third_party_scripts() {
	wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/assets/js/vendor/modernizr-2.7.0.min.js', array(), null, false );
	wp_enqueue_script( 'fitvids', get_template_directory_uri() . '/assets/js/plugins/fitvids.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'jquery-superfish', get_template_directory_uri() . '/assets/js/plugins/superfish.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'hoverintent', get_template_directory_uri() . '/assets/js/plugins/hoverintent.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'scrollup', get_template_directory_uri() . '/assets/js/plugins/scrollup.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'jquery-sticky', get_template_directory_uri() . '/assets/js/plugins/jquery.sticky.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'natural-width-height', get_template_directory_uri() . '/assets/js/plugins/natural-width-height.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'fakeLoader', get_template_directory_uri() . '/assets/js/plugins/fakeLoader.min.js', array( 'jquery' ), null, true );
}

function ed_school_enqueue_default_fonts() {
	wp_enqueue_style( 'ed-school-fonts', "//fonts.googleapis.com/css?family=Roboto:400,700|Montserrat:400,700", false );
	wp_enqueue_style( ED_SCHOOL_THEME_OPTION_NAME . '_style', get_template_directory_uri() . '/assets/css/wheels_options_style.css', false );
}

function ed_school_filter_icon_class( $namespace ) {
	$map = array(
		'user'               => 'fa fa-user',
		'folder'             => 'fa fa-folder',
		'tag'                => 'fa fa-tag',
		'comments'           => 'fa fa-comment',
		'cart'               => 'fa fa-shopping-cart',
		'shopping_bag'       => 'icon-square-hand-bag',
		'calendar'           => 'fa fa-calendar',
		'post_list_calendar' => 'icon-edtime3',
		'bars'               => 'fa fa-bars',
		'close'              => 'fa fa-close',
		'previous_post_link' => 'theme-icon-Arrow_left',
		'next_post_link'     => 'theme-icon-Arrow_right',
		'facebook'           => 'fa fa-facebook',
		'twitter'            => 'fa fa-twitter',
		'google-plus'        => 'fa fa-google-plus',
		'pinterest'          => 'fa fa-pinterest',
		'linkedin'           => 'fa fa-linkedin',
		'check'              => 'icon-check-icon',
		'search'             => 'fa fa-search',
		'teacher_location'   => 'icon-edplaceholder',
		'teacher_job_title'  => 'icon-edbook2',
		'arrow_down'         => 'icon-edright-arrow',
	);
	if ( array_key_exists( $namespace, $map ) ) {
		return $map[ $namespace ];
	}
	return $namespace;
}

function ed_school_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Primary', 'ed-school' ),
		'id'            => 'ed-school-sidebar-primary',
		'before_widget' => '<div class="widget %1$s %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Child Pages', 'ed-school' ),
		'id'            => 'ed-school-sidebar-child-pages',
		'before_widget' => '<div class="widget %1$s %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>',
	) );
}

function ed_school_register_nav_menus() {
	register_nav_menus( array(
		'primary_navigation'       => esc_html__( 'Primary Navigation', 'ed-school' ),
		'secondary_navigation'     => esc_html__( 'Secondary Navigation', 'ed-school' ),
		'mobile_navigation'        => esc_html__( 'Mobile Navigation', 'ed-school' ),
		'quick_sidebar_navigation' => esc_html__( 'Quick Sidebar Navigation', 'ed-school' ),
		'custom_navigation_1'      => esc_html__( 'Custom Navigation 1', 'ed-school' ),
		'custom_navigation_2'      => esc_html__( 'Custom Navigation 2', 'ed-school' ),
		'custom_navigation_3'      => esc_html__( 'Custom Navigation 3', 'ed-school' ),
	) );
}
