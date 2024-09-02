<?php

class Ed_School_Plugin_Elementor_Widget_Schedule extends \Elementor\Widget_Base {

	public function get_name() {
		return 'scp_schedule';
	}

	public function get_title() {
		return __( 'Schedule', 'ed_school_plugin' );
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
			'schedule',
			[
				'label' => __( 'Schedule', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::CODE,
				'language'    => 'html',
				'render_type' => 'ui',
				'placeholder' => 'item 1 | time 1 %% item 2 | time 2',
			]
		);

		$this->add_control(
			'schedule_description',
			[
				'raw'             => __( 'Pipe separated list of items. %% is row separator. Example: item 1 | time 1 %% item 2 | time 2', 'ed-school-plugin' ),
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);


		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$schedule = $settings['schedule'];

		if ( ! $schedule ) {
			return;
		}

		$rows = explode( '%%', $schedule );

		$css_class = 'schedule';
		
		?>
		<ul class="<?php echo esc_attr( $css_class ); ?>">
			<?php foreach ( $rows as $key => $row ) : ?>
				<?php
				$parts = explode( '|', $row );
				?>
				<?php if ( count( $parts ) == 2 ) : ?>
					<li class="<?php echo esc_attr( $key % 2 ? 'even' : 'odd' ); ?>">
						<span class="left">
							<?php echo wp_kses_post( trim( $parts[0] ) ); ?>
						</span>
						<span class="right">
							<?php echo wp_kses_post( trim( $parts[1] ) ); ?>
						</span>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
		<?php

	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Ed_School_Plugin_Elementor_Widget_Schedule() );
