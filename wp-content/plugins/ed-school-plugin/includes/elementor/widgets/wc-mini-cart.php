<?php

class Ed_School_Plugin_Elementor_Widget_WC_Mini_Cart extends \Elementor\Widget_Base {

	public function get_name() {
		return 'scp_wc_mini_cart';
	}

	public function get_title() {
		return __( 'WC Mini Cart', 'ed_school_plugin' );
	}

	public function get_icon() {
		return 'fa fa-bag';
	}

	public function get_categories() {
		return [ 'theme-elements' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'position',
			[
				'label' => __( 'Position', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'left' => __( 'Left', 'ed-school-plugin' ),
					'right' => __( 'Right', 'ed-school-plugin' ),
					'center' => __( 'Center', 'ed-school-plugin' ),
				],
				'default' => 'right',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$position = $settings['position'];
		$css_class = 'wh-minicart-wrapper ' . $position;

		if ( function_exists( 'ed_school_wc_print_mini_cart' ) ) {
		?>
		<div class="<?php echo esc_attr( $css_class ); ?>">
			<?php ed_school_wc_print_mini_cart(); ?>
		</div>
		<?php
		}

	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Ed_School_Plugin_Elementor_Widget_WC_Mini_Cart() );
