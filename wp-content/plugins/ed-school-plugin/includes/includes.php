<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'widgets_init', 'ed_school_plugin_register_wp_widgets' );
add_action( 'init', 'ed_school_plugin_custom_post_type_elementor_support' );
add_action( 'admin_init', 'ed_school_plugin_remove_redux_notice' );

$ed_school_plugin_includes = array(
	'includes/functions.php',
	'includes/shortcodes.php',
	'includes/theme-icons.php',
	'includes/assets.php',
	'includes/layout-blocks/init.php',
	'extensions/teacher-post-type/teacher-post-type.php',
	'includes/elementor/init.php',
	'includes/elementor/scripts.php',
);

if ( defined( 'WPB_VC_VERSION' ) ) {
	$ed_school_plugin_includes[] = 'includes/wpbakery-page-builder/init.php';
	$ed_school_plugin_includes[] = 'includes/wpbakery-page-builder/addons/content-box/addon.php';
	$ed_school_plugin_includes[] = 'includes/wpbakery-page-builder/addons/events/addon.php';
	$ed_school_plugin_includes[] = 'includes/wpbakery-page-builder/addons/hexagon-icon/addon.php';
	$ed_school_plugin_includes[] = 'includes/wpbakery-page-builder/addons/instagram/addon.php';
	$ed_school_plugin_includes[] = 'includes/wpbakery-page-builder/addons/logo/addon.php';
	$ed_school_plugin_includes[] = 'includes/wpbakery-page-builder/addons/menu/addon.php';
	$ed_school_plugin_includes[] = 'includes/wpbakery-page-builder/addons/our-process/addon.php';
	$ed_school_plugin_includes[] = 'includes/wpbakery-page-builder/addons/post-list/addon.php';
	$ed_school_plugin_includes[] = 'includes/wpbakery-page-builder/addons/quick-sidebar-trigger/addon.php';
	$ed_school_plugin_includes[] = 'includes/wpbakery-page-builder/addons/schedule/addon.php';
	$ed_school_plugin_includes[] = 'includes/wpbakery-page-builder/addons/search/addon.php';
	$ed_school_plugin_includes[] = 'includes/wpbakery-page-builder/addons/share-this/addon.php';
	$ed_school_plugin_includes[] = 'includes/wpbakery-page-builder/addons/teachers/addon.php';
	$ed_school_plugin_includes[] = 'includes/wpbakery-page-builder/addons/theme-button/addon.php';
	$ed_school_plugin_includes[] = 'includes/wpbakery-page-builder/addons/theme-icon/addon.php';
	$ed_school_plugin_includes[] = 'includes/wpbakery-page-builder/addons/theme-map/addon.php';
	$ed_school_plugin_includes[] = 'includes/wpbakery-page-builder/addons/wc-mini-cart/addon.php';
	$ed_school_plugin_includes[] = 'includes/wpbakery-page-builder/addons/video-popup/addon.php';
}

foreach ( $ed_school_plugin_includes as $file ) {
	$filepath = plugin_dir_path( dirname( __FILE__ ) ) . $file;
	if ( ! file_exists( $filepath ) ) {
		trigger_error( sprintf( esc_html__( 'Error locating %s for inclusion', 'ed-school-plugin' ), $file ), E_USER_ERROR );
	}
	require_once $filepath;
}
unset( $file, $filepath );

function ed_school_plugin_register_wp_widgets() {
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wp-widgets/latest-posts.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wp-widgets/contact-info.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wp-widgets/banner.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wp-widgets/twitter-widget/recent-tweets-widget.php';
}

function ed_school_plugin_register_elementor_widgets() {
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/widgets/logo.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/widgets/logo-sticky.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/widgets/menu/widget.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/widgets/post-list/widget.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/widgets/events/widget.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/widgets/instagram.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/widgets/teachers.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/widgets/schedule.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/widgets/search.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/widgets/wc-mini-cart.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/widgets/contact-form-7.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/widgets/sensei/course-carousel/widget.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/widgets/countdown/widget.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/widgets/video-popup/widget.php';
}

function ed_school_plugin_custom_post_type_elementor_support() {
	$cpt_support = ['course', 'lesson'];

	foreach ( $cpt_support as $cpt_slug ) {
		add_post_type_support( $cpt_slug, 'elementor' );
	}
}

function ed_school_plugin_remove_redux_notice() {
	update_option( 'redux-framework_extendify_notice', 'hide' );

	add_option( 'use_redux_templates', false );
	add_option( 'use_extendify_templates', false );
}
