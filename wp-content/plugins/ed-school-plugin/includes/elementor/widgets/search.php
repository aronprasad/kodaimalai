<?php

class Ed_School_Plugin_Elementor_Widget_Search extends \Elementor\Widget_Base {

	public function get_name() {
		return 'scp_search';
	}

	public function get_title() {
		return __( 'Search', 'ed_school_plugin' );
	}

	public function get_icon() {
		return 'fa fa-magnifier';
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

	protected function render() {

		$settings = $this->get_settings_for_display();

		$position = $settings['position'];
		$css_class = 'wh-search-toggler-wrapper ' . $position;
		?>
		<div class="<?php echo esc_attr( $css_class ); ?>">
			<a href="#" class="c-btn-icon wh-search-toggler">
				<i class="fa fa-search"></i>
			</a>
			<form class="wh-quick-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="text" name="s" placeholder="Type to search..." value="<?php if ( is_search() ) { echo esc_attr( get_search_query() ); } ?>" class="form-control"
				       autocomplete="off">
				<span class="fa fa-close"></span>
			</form>
		</div>
		<?php

	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Ed_School_Plugin_Elementor_Widget_Search() );
