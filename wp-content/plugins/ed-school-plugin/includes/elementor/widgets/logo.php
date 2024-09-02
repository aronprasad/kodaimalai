<?php

class Ed_School_Plugin_Elementor_Widget_Logo extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'scp_logo';
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
		return __( 'Logo', 'ed_school_plugin' );
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
				'description' => __( 'This widget uses logo images from Theme Options panel.', 'ed-school-plugin' ),
			]
		);

		$this->add_control(
			'color',
			[
				'label' => __( 'Editor BG Color', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'description' => __( 'Use this color in Elementor editor when logo is white. It will not be shown on front-end.', 'ed-school-plugin' ),

				'default' => '#eaeaea',
				'selectors' => [
					'.elementor-editor-active {{WRAPPER}}' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'override_logo',
			[
				'label' => __( 'Override logo image?', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
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
				'condition' => [
					'override_logo' => 'yes'
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'default' => 'full',
				'separator' => 'none',
				'condition' => [
					'override_logo' => 'yes'
				],
			]
		);

		$this->add_control(
			'hide_in_sticky',
			[
				'label' => __( 'Hide in sticky?', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
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
		$hide_in_sticky = $settings['hide_in_sticky'] == 'yes' ? true : false;

		if ( $hide_in_sticky ) {
			$this->add_render_attribute( '_wrapper', 'class', 'hide-in-sticky' );
		}

		$html = '';

		if ( ! empty( $settings['image']['url'] ) ) {
			$html = \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' );
		}

		if ( ! $html ) {
			
			$logo_url = '';
			if ( function_exists( 'ed_school_get_logo_url' ) ) {
				$logo_url = ed_school_get_logo_url();
			}
			if ( ! empty( $logo_url ) ) {
				$html = '<img src="' . esc_url( $logo_url ) . '" alt="logo"/>';
			}

		}

		if ( $html ) {
			$class = 'elementor-image';
			echo sprintf( '<div class="%1$s"><a href="%2$s">%3$s</a></div>', $class, esc_url( home_url( '/' ) ), $html );
		} else {
			?>
			<div class="wh-logo">
				<h1 class="site-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				</h1>

				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</div>
			<?php
		}
	}

}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Ed_School_Plugin_Elementor_Widget_Logo() );
