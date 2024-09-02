<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

final class Ed_School_Plugin_Elementor_Extension {

	const VERSION = '1.0.0';
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
	const MINIMUM_PHP_VERSION = '5.4';

	private static $_instance = null;

	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	public function __construct() {
		add_action( 'init', [ $this, 'init' ] );
	}

	public function init() {

		$cpt_support = ['layout_block', 'msm_mega_menu'];

		foreach ( $cpt_support as $cpt_slug ) {
			add_post_type_support( $cpt_slug, 'elementor' );
		}


		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			// add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		// Add Plugin actions
		add_action( 'elementor/widgets/widgets_registered', 'ed_school_plugin_register_elementor_widgets' );
		add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );
		add_action( 'elementor/frontend/scp_countdown/before_render', [ $this, 'init_controls' ] );

		add_action( 'elementor/element/tabs/section_tabs_style/before_section_end', function( $element, $args ) {
			/** @var \Elementor\Element_Base $element */
			$element->add_control(
				'heading_additional',
				[
					'label' => esc_html__( 'Additional', 'ed-school-plugin' ),
					'type' => Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$element->add_control(
				'content_background_color',
				[
					'label' => esc_html__( 'Content Background Color', 'ed-school-plugin' ),
					'type' => Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-tabs-content-wrapper' => 'background-color: {{VALUE}};',
					],
				]
			);

			$element->add_control(
				'regular_tab_background_color',
				[
					'label' => esc_html__( 'Regular Tab Background Color', 'ed-school-plugin' ),
					'type' => Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-tab-desktop-title' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .elementor-tab-mobile-title' => 'background-color: {{VALUE}};',
					],
				]
			);

			$element->add_control(
				'tab_hover_background_color',
				[
					'label' => esc_html__( 'Tab Hover Background Color', 'ed-school-plugin' ),
					'type' => Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-tab-desktop-title:hover' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .elementor-tab-mobile-title:hover' => 'background-color: {{VALUE}};',
					],
				]
			);

			$element->add_control(
				'title_hover_color',
				[
					'label' => esc_html__( 'Title Hover Color', 'ed-school-plugin' ),
					'type' => Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-tab-title a:hover' => 'color: {{VALUE}};',
					],
					'scheme' => [
						'type' => Elementor\Core\Schemes\Color::get_type(),
						'value' => Elementor\Core\Schemes\Color::COLOR_1,
					],
				]
			);
		}, 10, 2 );

		add_action( 'elementor/element/tabs/section_tabs/before_section_end', function( $element, $args ) {
			/** @var \Elementor\Element_Base $element */
			$control = $element->update_control( 'type', [
				'options' => [
					'horizontal'            => esc_html__( 'Horizontal', 'ed-school-plugin' ),
					'vertical'              => esc_html__( 'Vertical', 'ed-school-plugin' ),
					// called like this so we get the css class from horizontal and our additional class
					'horizontal stretched'  => esc_html__( 'Stretched', 'ed-school-plugin' ),
				],
			] );

		}, 10, 2 );

		add_action( 'elementor/element/before_parse_css', [ $this, 'filter_section_style' ], 10, 2);

		add_action( 'elementor/editor/after_enqueue_scripts', function() {
		   wp_enqueue_style( 'ed-school-theme-icons', get_template_directory_uri() . '/assets/css/theme-icons.css', false );
		} );

	}


	/**
	 * Init Controls
	 *
	 * Include controls files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_controls( $controls_manager ) {

		require_once( __DIR__ . '/widgets/custom-css.php' );

		$icons = $controls_manager->get_control( 'icon' )->get_settings('options');

		$controls_manager
			->get_control( 'icon' )
			->set_settings('options', ed_school_plugin_get_theme_icons_for_elementor( $icons ) );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'ed-school-plugin' ),
			'<strong>' . esc_html__( 'Aislin Elementor Extension', 'ed-school-plugin' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'ed-school-plugin' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ed-school-plugin' ),
			'<strong>' . esc_html__( 'Aislin Elementor Extension', 'ed-school-plugin' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'ed-school-plugin' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ed-school-plugin' ),
			'<strong>' . esc_html__( 'Aislin Elementor Extension', 'ed-school-plugin' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'ed-school-plugin' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function filter_section_style( $dynamic_css, $element ) {
		if ( ! is_a( $element, 'Elementor\Element_Section' ) ) {
			return;
		}

		$element_settings = $element->get_settings();

		if ( ! isset( $element_settings['content_width'] ) 
			|| ! isset( $element_settings['layout'] ) 
			|| $element_settings['layout'] !== 'boxed') {
			return;
		}

		$value    = $element_settings['content_width']['size'];
		$unit     = $element_settings['content_width']['unit'];
		$selector = $dynamic_css->get_element_unique_selector( $element ) . ' > .elementor-container';
		$handle   = 'elementor-post-' . $dynamic_css->get_post_id();

		if ( $value && $unit ) {
			$custom_css = "{$selector} {max-width: {$value}{$unit} !important;}";
			wp_add_inline_style( $handle, $custom_css );
		}
	}

}

Ed_School_Plugin_Elementor_Extension::instance();
