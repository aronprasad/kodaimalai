<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! defined( 'ELEMENTOR_PATH' ) ) {
	return;
}

class Ed_School_Plugin_Elementor_Widget_Menu extends \Elementor\Widget_Base {

	public function get_name() {
		return 'scp_menu';
	}

	public function get_title() {
		return __( 'Theme Menu', 'ed_school_plugin' );
	}

	public function get_icon() {
		return 'eicon-favorite';
	}

	public function get_categories() {
		return [ 'theme-elements' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'ed-school-plugin' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'menu_type',
			[
				'label' => __( 'Menu type', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'menu_main' => __( 'Main menu', 'ed-school-plugin' ),
					'menu_mobile' => __( 'Mobile menu', 'ed-school-plugin' ),
					'menu_top' => __( 'Top menu', 'ed-school-plugin' ),
					'menu_quick_sidebar' => __( 'Quick Sidebar menu', 'ed-school-plugin' ),
				],
				'default' => 'menu_main',
			]
		);

		$this->add_control(
			'menu',
			[
				'label' => __( 'Menu', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => get_registered_nav_menus(),
				'default' => 'primary_navigation',
			]
		);

		$this->add_responsive_control(
			'position',
			[
				'label' => __( 'Position', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'ed-school-plugin' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'ed-school-plugin' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'ed-school-plugin' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'left',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} li a',
			]
		);

		$this->add_control(
			'color',
			[
				'label' => __( 'Color', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .sf-menu li a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_hover',
			[
				'label' => __( 'Hover Color', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .sf-menu li:hover > a' => 'color: {{VALUE}};',
					// does not need :hover, it is only visible on hover
					'{{WRAPPER}} .sf-menu.wh-menu-main li > a:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => __( 'Top Level Item Padding', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sf-menu > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'margin',
			[
				'label' => __( 'Top Level Item Margin', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sf-menu > li > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'color_sticky',
			[
				'label' => __( 'Sticky Color', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
				// .is-sticky is in the middle of elementor {{WRAPPER}} so we cannot use it here
					'.wh-header .is-sticky .sf-menu li.menu-item a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_hover_sticky',
			[
				'label' => __( 'Sticky Hover Color', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'.wh-header .is-sticky .sf-menu li.menu-item:hover > a' => 'color: {{VALUE}};',
					// does not need :hover, it is only visible on hover
					'.wh-header .is-sticky .sf-menu.wh-menu-main li > a:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$menu_type = $settings['menu_type'];
		$menu = $settings['menu'];
		$position = $settings['position'];	
		$container_class = '';

		$this->add_render_attribute(
			'_wrapper', 'class', $position
		);

		$args = array(
			'theme_location' => $menu ? $menu : 'primary_navigation',
			'depth'          => 4,
			'fallback_cb'    => false
		);

		if ( $menu_type == 'menu_main' ) {
			$args['theme_location'] = 'primary_navigation';
			$args['menu_class']     = "sf-menu wh-menu-main {$position}";
			$args['container']      = 'div';
			$args['container_id']   = 'cbp-menu-main';

		} elseif ( $menu_type == 'menu_top' ) {
			$args['theme_location'] = 'secondary_navigation';
			$args['menu_class']     = "sf-menu wh-menu-top {$position}";
			$args['container']      = 'div';

		} elseif ( $menu_type == 'menu_mobile' ) {

			$args['theme_location'] = 'primary_navigation';

			if ( has_nav_menu( 'mobile_navigation' ) ) {
				$args['theme_location'] = 'mobile_navigation';
			}

			$args['menu_class']     = 'respmenu';
			if ( class_exists( 'Ed_School_Mobile_Menu_Walker' ) ) {
				$args['walker'] = new Ed_School_Mobile_Menu_Walker();
			}
			include 'templates/menu-mobile.php';
			return;

		} elseif ( $menu_type == 'menu_quick_sidebar' ) {
			$args['theme_location'] = 'quick_sidebar_navigation';
			$args['menu_class']     = 'sf-menu wh-menu-vertical';
			$args['container']      = 'div';
			$position = '';
		}

		global $post_id;
		if (
			($menu_type == 'menu_custom' || $menu_type == 'menu_main')
			&& $menu == 'primary_navigation'
		) {
			if ( function_exists( 'rwmb_meta' ) && (int) rwmb_meta( 'ed_school_use_custom_menu', array(), $post_id ) ) {
				$custom_menu_location = rwmb_meta( 'ed_school_custom_menu_location', array(), $post_id );
				if ( ! empty( $custom_menu_location ) ) {
					$args['theme_location'] = $custom_menu_location;
				}
			}

		}

		$args['container_class'] = $container_class;

		wp_nav_menu( $args );
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Ed_School_Plugin_Elementor_Widget_Menu() );
