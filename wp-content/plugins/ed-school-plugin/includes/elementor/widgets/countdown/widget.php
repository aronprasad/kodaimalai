<?php

class Ed_School_Plugin_Elementor_Widget_Countdown extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'scp_countdown';
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
		return __( 'Countdown', 'ed_school_plugin' );
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

	public function get_style_depends() {
		return ['elementor-widget-scp-countdown'];
	}

	public function get_script_depends() {
		return ['jquery-countdown', 'elementor-widget-scp-countdown'];
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
				'label' => __( 'Content', 'ed-school-plugin' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'target_date',
			[
				'label' => __( 'Target Date', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::DATE_TIME,
				'input_type' => 'date',
			]
		);
		$this->add_control(
			'labels',
			[
				'label' => __( 'Labels', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'input_type' => 'text',
				'placeholder' => __( 'weeks, days, hours, minutes, seconds', 'ed-school-plugin' ),
				'description' => __( 'Comma separated list of labels (weeks, days, hours, minutes, seconds).', 'ed-school-plugin' ),
				'default'       => 'weeks, days, hours, minutes, seconds',
			]
		);
		$this->add_control(
			'label_translations',
			[
				'label' => __( 'Label Translations', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'input_type' => 'text',
				'description' => __( 'Comma separated list of label translations. They must match the number of labels above.', 'ed-school-plugin' ),
			]
		);

		$this->add_control(
			'size',
			[
				'label' => esc_html__( 'Size', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'big' => esc_html__( 'Big', 'ed-school-plugin' ),
					'small' => esc_html__( 'Small', 'ed-school-plugin' ),
				],
				'default' => 'big',
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

		$this->add_control(
			'color',
			[
				'label' => __( 'Background Color', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .scp-countdown .count' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .scp-countdown .count' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Label Color', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .scp-countdown .time' => 'color: {{VALUE}};',
				],
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

		$target_date = $settings['target_date'] 
					? str_replace( '-', '/', $settings['target_date'] ) 
					: '2022/10/10 12:00:00';
		$labels = $settings['labels'];
		$label_translations = $settings['label_translations'];

		$this->add_render_attribute(
			'_wrapper', 'class', $settings['position']
		);
		$this->add_render_attribute(
			'_wrapper', 'class', $settings['size']
		);

		echo self::get_html( $target_date, $labels, $label_translations );
	}

	public static function get_html( $target_date, $labels, $label_translations = '' ) {

		$id = uniqid( 'countdown-' );
		$class = 'scp-countdown';

		return "<div id=\"{$id}\" class=\"{$class}\" data-date=\"{$target_date}\" data-labels=\"{$labels}\" data-translations=\"{$label_translations}\"></div>";
	}

}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Ed_School_Plugin_Elementor_Widget_Countdown() );
