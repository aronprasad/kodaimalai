<?php

class Ed_School_Plugin_Elementor_Widget_Events extends \Elementor\Widget_Base {

	public function get_name() {
		return 'scp_events';
	}

	public function get_title() {
		return esc_html__( 'Upcoming Events', 'ed_school_plugin' );
	}

	public function get_icon() {
		return 'eicon-favorite';
	}

	public function get_categories() {
		return [ 'theme-elements' ];
	}

	public function get_templates() {
		return apply_filters( 'ed-school-plugin-elementor-event-templates', [
			'layout_1' => 'Layout 1',
			'layout_2' => 'Layout 2',
			'layout_3' => 'Layout 3',
			'layout_4' => 'Layout 4',
			'layout_5' => 'Layout 5',
			'layout_6' => 'Layout 6',
		] );
	}
	
	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'ed-school-plugin' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		$args       = array(
			'taxonomy' => 'tribe_events_cat'
		);
		$cats = [];
		foreach ( get_categories( $args ) as $category ) {
			$cats[ $category->term_id ] = $category->name;
		}

		$this->add_control(
			'category_id',
			[
				'label' => esc_html__( 'Category', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $cats,
			]
		);

		$this->add_control(
			'start_date_format',
			[
				'label' => esc_html__( 'Date Format', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => 'F d, Y',
			]
		);

		$this->add_control(
			'limit',
			[
				'label' => esc_html__( 'Nubmer of events to display', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number',
				'default' => 3,
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => esc_html__( 'Layout', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $this->get_templates(),
				'default' => 'layout_1',
			]
		);

		$this->add_control(
			'show_image',
			[
				'label' => esc_html__( 'Show Image?', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'ed-school-plugin' ),
				'label_on' => esc_html__( 'Show', 'ed-school-plugin' ),
				'default' => 'no',
				'description' => esc_html__( 'Applicable for layouts 5 and 6', 'ed-school-plugin' ),
			]
		);

		$this->add_control(
			'show_description',
			[
				'label' => esc_html__( 'Show Description?', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'ed-school-plugin' ),
				'label_on' => esc_html__( 'Show', 'ed-school-plugin' ),
				'default' => 'no',
			]
		);

		$this->add_control(
			'description_word_length',
			[
				'label' => esc_html__( 'Description word length', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number',
				'default' => 15,
				'condition' => [
					'show_description' => 'yes',
				],
			]
		);

		$this->add_control(
			'view_all_events_link_text',
			[
				'label' => esc_html__( 'View All Events Link Text', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => 'Read More',
				'description' => esc_html__( 'If Left Blank link will not show.', 'ed-school-plugin' ),
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

		$category_id               = $settings['category_id'];
		$limit                     = $settings['limit'];
		$layout                    = $settings['layout'];
		$description_word_length   = $settings['description_word_length'];
		$start_date_format         = $settings['start_date_format'];
		$view_all_events_link_text = $settings['view_all_events_link_text'];
		$show_description          = $settings['show_description'] === 'yes' ? true : false;;
		$show_image                = $settings['show_image'] === 'yes' ? true : false;
	
		if ( ! function_exists( 'tribe_get_events' ) ) {
			return;
		}

		$args = array(
			'eventDisplay'   => 'list',
			'posts_per_page' => $limit
		);

		if ( $category_id ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'tribe_events_cat',
					'field'    => 'term_id',
					'terms'    => $category_id
				)
			);
		}

		$posts = tribe_get_events( apply_filters( 'tribe_events_list_widget_query_args',  $args ) );

		$css_class = 'scp-tribe-events-wrap';

		//Check if any posts were found
		if ( $posts ) {

			$templates = $this->get_templates();
			$template_file = dirname(__FILE__) . "/templates/{$layout}.php";

			// Load template if it exists and is allowed
			// otherwise load default template
			// layout 2 and 3 use the same template, only have diff style
			$template_file = file_exists( $template_file ) && isset( $templates[ $layout ] ) 
						? $template_file : 
						'templates/layout_1.php';


			?>
			<div class="<?php echo esc_attr( $css_class ); ?> <?php echo esc_attr( $layout ); ?>">
				<ul class="scp-tribe-events">
					<?php
					foreach ( $posts as $post ) :
						setup_postdata( $post );
						?>
						<?php include $template_file; ?>
					<?php
					endforeach;
					?>
				</ul>
				<?php if ( ! empty( $view_all_events_link_text ) ) : ?>
					<p class="scp-tribe-events-link">
						<a href="<?php echo esc_url( tribe_get_events_link() ); ?>"
						   rel="bookmark"><?php echo esc_html( $view_all_events_link_text ); ?></a>
					</p>
				<?php endif; ?>
			</div>
			<?php
			//No Events were Found
		} else {
		?>
			<p><?php esc_html_e( 'There are no upcoming events at this time.', 'ed-school-plugin' ); ?></p>
		<?php
		}
		wp_reset_query();
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Ed_School_Plugin_Elementor_Widget_Events() );
