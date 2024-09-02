<?php

class Ed_School_Plugin_Elementor_Widget_Event_Featured extends \Elementor\Widget_Base {

	public function get_name() {
		return 'scp_featured_event';
	}

	public function get_title() {
		return __( 'Featured Event', 'ed_school_plugin' );
	}

	public function get_icon() {
		return 'eicon-favorite';
	}

	public function get_categories() {
		return [ 'theme-elements' ];
	}

	public function get_style_depends() {
		return ['elementor-widget-scp-countdown'];
	}

	public function get_script_depends() {
		return ['jquery-countdown', 'elementor-widget-scp-countdown'];
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
			'event_id',
			[
				'label' => __( 'Event ID', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'input_type' => 'text',
			]
		);

		$this->add_control(
			'start_date_format',
			[
				'label' => __( 'Date Format', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => 'F d, Y',
			]
		);

		$this->add_control(
			'use_countown',
			[
				'label' => __( 'Use Coundown', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
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
				'condition' => [
					'use_countown' => 'yes'
				],
			]
		);
		$this->add_control(
			'label_translations',
			[
				'label'       => __( 'Label Translations', 'ed-school-plugin' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'input_type'  => 'text',
				'description' => __( 'Comma separated list of label translations. They must match the number of labels above.', 'ed-school-plugin' ),
				'condition' => [
					'use_countown' => 'yes'
				],
			]
		);


		$this->add_control(
			'show_description',
			[
				'label'     => __( 'Show Description?', 'ed-school-plugin' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'elementor' ),
				'label_on'  => __( 'Show', 'elementor' ),
				'default'   => 'no',
			]
		);

		$this->add_control(
			'description_word_length',
			[
				'label'      => __( 'Description word length', 'ed-school-plugin' ),
				'type'       => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number',
				'default'    => 15,
				'condition'  => [
					'show_description' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'   => __( 'Alignment', 'elementor' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'element_space',
			[
				'label' => __( 'Element Space', 'ed-school-plugin' ),
				'type'  => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 300,
					],
				],
				'default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .title' => 'margin-bottom: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => __( 'Title', 'elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'  => __( 'Text Color', 'elementor' ),
				'type'   => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					// Stronger selector to avoid section style from overwriting
					'{{WRAPPER}} .title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'scheme'   => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .title',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'text_shadow',
				'selector' => '{{WRAPPER}} .title',
			]
		);

		$this->add_control(
			'blend_mode',
			[
				'label'   => __( 'Blend Mode', 'elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					''            => esc_html__( 'Normal', 'ed-school-plugin' ),
					'multiply'    => esc_html__( 'Multiply', 'ed-school-plugin' ),
					'screen'      => esc_html__( 'Screen', 'ed-school-plugin' ),
					'overlay'     => esc_html__( 'Overlay', 'ed-school-plugin' ),
					'darken'      => esc_html__( 'Darken', 'ed-school-plugin' ),
					'lighten'     => esc_html__( 'Lighten', 'ed-school-plugin' ),
					'color-dodge' => esc_html__( 'Color Dodge', 'ed-school-plugin' ),
					'saturation'  => esc_html__( 'Saturation', 'ed-school-plugin' ),
					'color'       => esc_html__( 'Color', 'ed-school-plugin' ),
					'difference'  => esc_html__( 'Difference', 'ed-school-plugin' ),
					'exclusion'   => esc_html__( 'Exclusion', 'ed-school-plugin' ),
					'hue'         => esc_html__( 'Hue', 'ed-school-plugin' ),
					'luminosity'  => esc_html__( 'Luminosity', 'ed-school-plugin' ),
				],
				'selectors' => [
					'{{WRAPPER}} .title' => 'mix-blend-mode: {{VALUE}}',
				],
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

		$event_id                  = (int) $settings['event_id'];
		$description_word_length   = $settings['description_word_length'];
		$start_date_format         = $settings['start_date_format'];
		$show_description          = $settings['show_description'];
		$use_countdown             = $settings['use_countown'];
		$labels                    = $settings['labels'];
		$label_translations        = $settings['label_translations'];

		if ( ! $event_id ) {
			return;
		}

		if ( ! function_exists( 'tribe_events_get_event' ) ) {
			return;
		}

		$post = tribe_events_get_event( $event_id );

		if ( ! $post ) {
			return;
		}

		$css_class = 'scp-tribe-event-featured';
		?>
		<div class="<?php echo esc_attr( $css_class ); ?>">
			<div class="title">
				<?php echo esc_html( $post->post_title ); ?>
			</div>
			<?php if ( $use_countdown ): ?>
				<?php echo \Ed_School_Plugin_Elementor_Widget_Countdown::get_html(
					tribe_get_start_date( $post, false, 'Y/m/d h:i:s' ),
					$labels,
					$label_translations
				); ?>
			<?php endif ?>
		</div>
		<?php
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Ed_School_Plugin_Elementor_Widget_Event_Featured() );
