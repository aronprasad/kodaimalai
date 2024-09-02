<?php

class Ed_School_Plugin_Elementor_Widget_Video_Popup extends \Elementor\Widget_Base {

	public function get_name() {
		return 'scp_video_popup';
	}

	public function get_title() {
		return __( 'Video Popup', 'ed_school_plugin' );
	}

	public function get_icon() {
		return 'fa fa-video';
	}

	public function get_categories() {
		return [ 'theme-elements' ];
	}

	public function get_style_depends() {
		return ['magnific-popup'];
	}

	public function get_script_depends() {
		return ['jquery.magnific-popup', 'elementor-widget-scp-video-popup'];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'video_section',
			[
				'label' => __( 'Video', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		$this->add_control(
			'video_url',
			[
				'label' => __( 'Video Url', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'Title',
			]
		);

		$this->add_control(
			'content',
			[
				'label' => __( 'Content', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => 'Content',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title Color', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wh-video-popup h2' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .wh-video-popup' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'overlay_bg_color',
			[
				'label' => __( 'Overlay Background Color', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wh-video-popup .overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'play_button_section',
			[
				'label' => __( 'Play Button', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		$this->add_control(
			'outer_circle_bg_color',
			[
				'label' => __( 'Outer Circle Background Color', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wh-video-popup .play' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'inner_circle_bg_color',
			[
				'label' => __( 'Inner Circle Background Color', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wh-video-popup .inner' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'play_icon_color',
			[
				'label' => __( 'Play Icon Color', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wh-video-popup .inner i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$video_url = $settings['video_url'];
		$title = $settings['title'];
		$content = $settings['content'];
		?>
		<div class="wh-video-popup">
			<div></div>
			<div class="overlay">
				<?php if ( $video_url ): ?>
					<a href="<?php echo esc_url( $video_url ); ?>" class="video-link">
				<?php endif ?>
					<span class="play"><span class="inner"><i class="fa fa-play"></i></span></span>
				<?php if ( $video_url ): ?>
					</a>
				<?php endif ?>
				<h2><?php echo esc_html( $title ); ?></h2>
				<p><?php echo esc_html( $content ); ?></p>
			</div>
		</div>
		<?php
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Ed_School_Plugin_Elementor_Widget_Video_Popup() );
