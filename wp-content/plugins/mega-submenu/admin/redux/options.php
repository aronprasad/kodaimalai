<?php

if ( ! class_exists( 'Redux' ) ) {
	return;
}

$opt_name = MSM_OPTION_NAME;

$menus       = get_registered_nav_menus();
$menus_array = array();

foreach ( $menus as $location => $description ) {
	$menus_array[ $location ] = $description;
}

// ----------------------------------
// -> Theme Integration
// ----------------------------------
if ( MSM_Theme_Integration::is_supported() ) {

	$fields = array();

	$welcome_file = MSM_PLUGIN_PATH . 'includes/vc/theme-integration/' . MSM_Theme_Integration::get_template() . '/welcome.php';

	if ( file_exists( $welcome_file ) ) {
		$fields = include_once $welcome_file;
	}

	Redux::setSection( $opt_name, array(
		'id'     => $opt_name . 'section-theme-integration',
		'title'  => esc_html__( 'Theme Integration', 'mega-submenu' ),
		'icon'   => 'el el-screen',
		'fields' => array(
			$fields
		),
	) );
	// -> End Theme Integration

}

// ----------------------------------
// -> General
// ----------------------------------
Redux::setSection( $opt_name, array(
	'id'     => $opt_name . 'section-general',
	'title'  => esc_html__( 'General Settings', 'mega-submenu' ),
	'icon'   => 'el-icon-home',
	'fields' => array(
		array(
			'compiler' => true,
			'id'       => 'menu-location',
			'type'     => 'select',
			'title'    => esc_html__( 'Select Menu Location', 'mega-submenu' ),
			'desc'     => esc_html__( 'Mega Menus will be applied only on selected menu location.', 'mega-submenu' ),
			'options'  => $menus_array,
			'default'  => '',
		),
		array(
			'compiler' => true,
			'id'       => 'theme-mobile-menu-location',
			'type'     => 'select',
			'title'    => esc_html__( 'Mobile Menu Location', 'mega-submenu' ),
			'desc'     => esc_html__( 'Select this if your theme is using a separate menu location for Mobile.', 'mega-submenu' ),
			'options'  => $menus_array,
			'default'  => '',
		),
		array(
			'compiler' => true,
			'id'       => 'submenu-offset-top',
			'type'     => 'text',
			'title'    => esc_html__( 'Offset Top', 'mega-submenu' ),
			'desc'     => esc_html__( 'Value in px. Enter number only.', 'mega-submenu' ),
			'validate' => 'number',
			'msg'      => 'Enter number only',
			'default'  => '60'
		),
		array(
			'compiler' => true,
			'id'       => 'submenu-top-hover-area',
			'type'     => 'text',
			'title'    => esc_html__( 'Submenu Top Hover Area', 'mega-submenu' ),
			'subtitle' => esc_html__( 'The space above the submenu that when hovered on will keep the submenu open.', 'mega-submenu' ),
			'desc'     => esc_html__( 'Value in px. Enter number only.', 'mega-submenu' ),
			'validate' => 'number',
			'msg'      => 'Enter number only',
			'default'  => '30'
		),
		array(
			'id'      => 'submenu-items-position-relative',
			'type'    => 'switch',
			'title'   => esc_html__( 'Submenu items position relative', 'mega-submenu' ),
			'desc'    => esc_html__( 'Enable this if mega menus in submenus are not aligned correctly.', 'mega-submenu' ),
			'default' => false,
		),
		array(
			'id'      => 'mobile-menu-trigger-click-bellow',
			'type'    => 'spinner',
			'title'   => esc_html__( 'Trigger click for mobile menus bellow', 'mega-submenu' ),
			'desc'    => esc_html__( 'When used inside theme mobile menu, when to trigger click on item', 'mega-submenu' ),
			'default' => '767',
			'min'     => '50',
			'max'     => '2000',
			'step'    => '1',
		),
		/**
		 * @since  1.1.0
		 */
		array(
			'id'       => 'mobile-menu-wrapper',
			'type'     => 'textarea',
			'title'    => esc_html__( 'Mobile menu wrapper', 'mega-submenu' ),
			'subtitle' => esc_html__( 'HTML to wrap when printed in mobile menu', 'mega-submenu' ),
			'desc'     => esc_html__( 'Open and close tags separated with |. Example: ' . htmlspecialchars( '<ul class="sub-menu"><li>|</li></ul>' ), 'mega-submenu' ),
			'validate' => 'html_custom',
			'default'  => '<ul class="sub-menu"><li>|</li></ul>',

		),
		array(
			'id'       => 'menu-wrapper',
			'type'     => 'textarea',
			'title'    => esc_html__( 'General menu wrapper', 'mega-submenu' ),
			'subtitle' => esc_html__( 'HTML', 'mega-submenu' ),
			'desc'     => esc_html__( 'Open and close tags separated with |. Example: ' . htmlspecialchars( '<ul class="sub-menu"><li>|</li></ul>' ), 'mega-submenu' ),
			'validate' => 'html_custom',
			'default'  => '',

		),
		array(
			'id'      => 'delete-data-on-uninstall',
			'type'    => 'switch',
			'title'   => esc_html__( 'Delete data on plugin uninstall?', 'mega-submenu' ),
			'desc'    => esc_html__( 'This will delete plugin options and all existing mega menus.', 'mega-submenu' ),
			'default' => false,
			'on'      => 'Yes',
			'off'     => 'No',
			'default' => false,
		),
	),
) );
// -> End General


if ( apply_filters( MSM_Mega_Submenu::FILTER_USE_STYLE_MENU, true ) ) {

	Redux::setSection( $opt_name, array(
		'subsection' => true,
		'id'         => 'subsection-header-responsive-menu',
		'title'      => esc_html__( 'Mobile Header', 'mega-submenu' ),
		'fields'     => array(
			array(
				'id'       => 'respmenu-use',
				'type'     => 'switch',
				'compiler' => 'true',
				'title'    => esc_html__( 'Use Responsive Menu?', 'mega-submenu' ),
				'default'  => false,
			),
			array(
				'id'       => 'respmenu-show-start',
				'type'     => 'spinner',
				'title'    => esc_html__( 'Display bellow', 'mega-submenu' ),
				'desc'     => esc_html__( 'Set the width of the screen in px bellow which the menu is shown and main menu is hidden', 'mega-submenu' ),
				'default'  => '767',
				'min'      => '50',
				'max'      => '2000',
				'step'     => '1',
				'required' => array(
					array( 'respmenu-use', 'equals', '1' ),
				),
			),
			array(
				'id'       => 'respmenu-logo',
				'type'     => 'media',
				'title'    => esc_html__( 'Logo', 'mega-submenu' ),
				'url'      => true,
				'mode'     => false,
				'subtitle' => esc_html__( 'Set logo image', 'mega-submenu' ),
				'required' => array(
					array( 'respmenu-use', 'equals', '1' ),
				),
			),
			array(
				'id'       => 'respmenu-logo-dimensions',
				'type'     => 'dimensions',
				'units'    => array( 'em', 'px', '%' ),
				'title'    => esc_html__( 'Logo Dimensions (Width/Height)', 'mega-submenu' ),
				'compiler' => array( '.respmenu-header .respmenu-header-logo-link' ),
				'required' => array(
					array( 'respmenu-use', 'equals', '1' ),
				),
			),
			array(
				'id'       => 'respmenu-background',
				'type'     => 'background',
				'title'    => esc_html__( 'Background', 'mega-submenu' ),
				'compiler' => array( '#msm-mobile-menu' ),
				'default'  => array(
					'background-color' => '#fff',
				),
				'required' => array(
					array( 'respmenu-use', 'equals', '1' ),
				),

			),
			array(
				'id'       => 'respmenu-link-color',
				'type'     => 'link_color',
				'title'    => esc_html__( 'Menu Link Color', 'mega-submenu' ),
				'compiler' => array(
					'#msm-mobile-menu .respmenu li a',
				),
				'active'   => false,
				'visited'  => false,
				'default'  => array(
					'regular' => '#000',
					'hover'   => '#333',
				),
				'required' => array(
					array( 'respmenu-use', 'equals', '1' ),
				),
			),
			array(
				'id'          => 'respmenu-display-switch-color',
				'type'        => 'color',
				'mode'        => 'border-color',
				'title'       => esc_html__( 'Display Toggle Color', 'mega-submenu' ),
				'compiler'    => array( '#msm-mobile-menu .respmenu-open hr' ),
				'transparent' => false,
				'default'     => '#000',
				'validate'    => 'color',
				'required'    => array(
					array( 'respmenu-use', 'equals', '1' ),
				),
			),
			array(
				'id'          => 'respmenu-display-switch-color-hover',
				'type'        => 'color',
				'mode'        => 'border-color',
				'title'       => esc_html__( 'Display Toggle Hover Color', 'mega-submenu' ),
				'compiler'    => array( '#msm-mobile-menu .respmenu-open:hover hr' ),
				'transparent' => false,
				'default'     => '#999',
				'validate'    => 'color',
				'required'    => array(
					array( 'respmenu-use', 'equals', '1' ),
				),
			),
			array(
				'id'       => 'respmenu-display-switch-img',
				'type'     => 'media',
				'title'    => esc_html__( 'Display Toggle Image', 'mega-submenu' ),
				'url'      => true,
				'mode'     => false,
				'subtitle' => esc_html__( 'Set the image to replace default 3 lines for menu toggle button.', 'mega-submenu' ),
				'required' => array(
					array( 'respmenu-use', 'equals', '1' ),
				),
			),
			array(
				'id'       => 'respmenu-display-switch-img-dimensions',
				'type'     => 'dimensions',
				'units'    => array( 'em', 'px', '%' ),
				'title'    => esc_html__( 'Display Toggle Image Dimensions (Width/Height)', 'mega-submenu' ),
				'compiler' => array( '.respmenu-header .respmenu-open img' ),
				'required' => array(
					array( 'respmenu-use', 'equals', '1' ),
				),
			),
			array(
				'id'             => 'respmenu-menu-padding',
				'type'           => 'spacing',
				'compiler'       => array( '#msm-mobile-menu' ),
				'mode'           => 'padding',
				'units'          => array( 'px' ),
				'units_extended' => 'false',
				'title'          => esc_html__( 'Padding', 'mega-submenu' ),
				'description'    => esc_html__( 'Use it to better vertical align the menu', 'mega-submenu' ),
				'default'        => array(
					'padding-top'    => '0',
					'padding-right'  => '0',
					'padding-bottom' => '0',
					'padding-left'   => '0',
					'units'          => 'px',
				),
				'required'       => array(
					array( 'respmenu-use', 'equals', '1' ),
				)
			),
		)
	) );
}


