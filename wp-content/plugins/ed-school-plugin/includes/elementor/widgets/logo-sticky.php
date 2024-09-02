<?php

class Ed_School_Plugin_Elementor_Widget_Logo_Sticky extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'scp_logo_sticky';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Sticky Logo', 'ed_school_plugin' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-favorite';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'theme-elements' ];
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Settings', 'ed-school-plugin' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				'description' => __( 'Used as alternative logo in sticky header. It will be visible in layout block editor but only visible in sticky header on the front-end.', 'ed-school-plugin' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'default' => 'full',
				'separator' => 'none',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$html = '';

		if ( ! empty( $settings['image']['url'] ) ) {
			$html = \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' );
		}

		if ( $html ) {
			$class = 'elementor-image';
			echo sprintf( '<div class="%1$s"><a href="%2$s">%3$s</a></div>', $class, esc_url( home_url( '/' ) ), $html );
		} 
	}

}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Ed_School_Plugin_Elementor_Widget_Logo_Sticky() );
