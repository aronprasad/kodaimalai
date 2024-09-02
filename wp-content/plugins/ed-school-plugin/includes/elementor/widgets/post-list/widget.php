<?php

class Ed_School_Plugin_Elementor_Widget_Post_List extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'scp_post_list';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Post List', 'ed_school_plugin' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-toggle';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'theme-elements' ];
	}

	public function get_templates() {
		return apply_filters( 'ed-school-plugin-elementor-post-templates', [
			'layout_1' => 'Layout 1',
			'layout_2' => 'Layout 2',
			'layout_3' => 'Layout 3',
			'layout_4' => 'Layout 4',
			'layout_5' => 'Layout 5 (Featured Post)',
		] );
	}

	/**
	 * Register oEmbed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$args       = array(
			'orderby' => 'name',
			'parent'  => 0
		);
		$cats = [];
		foreach ( get_categories( $args ) as $category ) {
			$cats[ $category->term_id ] = $category->name;
		}


		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'post_id',
			[
				'label' => __( 'Post ID', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'input_type' => 'text',
				'description' => esc_html__( 'If you wish to include a specific post at the top of the list please enter id of that post.', 'ed-school-plugin' ),
			]
		);

		$this->add_control(
			'category',
			[
				'label' => __( 'Category', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $cats,
			]
		);

		$this->add_control(
			'number_of_posts',
			[
				'label' => __( 'Number of Posts', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number',
				'default' => 2,
			]
		);

		$this->add_control(
			'post_date_format',
			[
				'label' => __( 'Date Format', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => 'F d, Y',
			]
		);

		$this->add_control(
			'description_word_length',
			[
				'label' => __( 'Description word length', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number',
				'default' => 15,
			]
		);

		$this->add_control(
			'link_text',
			[
				'label' => __( 'Link Text', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => 'Read More',
				'description' => esc_html__( 'If you do not wish to display Read More link just leave this field blank.', 'ed-school-plugin' ),
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => __( 'Layout', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $this->get_templates(),
				'default' => 'layout_1',
			]
		);

		$this->add_control(
			'number_of_columns',
			[
				'label' => __( 'Number of Columns', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'default' => '3',
			]
		);

		$this->add_control(
			'show_author',
			[
				'label' => __( 'Show Author?', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'elementor' ),
				'label_on' => __( 'Show', 'elementor' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_comment_count',
			[
				'label' => __( 'Show Comment Count?', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'elementor' ),
				'label_on' => __( 'Show', 'elementor' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'thumbnail_dimensions',
			[
				'label' => __( 'Thumbnail Dimensions', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => ed_school_plugin_get_thumbnail_sizes(),
			]
		);

		$this->add_control(
			'meta_data_color',
			[
				'label' => __( 'Meta Data Color', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					// '{{WRAPPER}} .elementor-accordion .elementor-tab-title' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
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

		$post_id                 = (int) $settings['post_id'];
		$category                = $settings['category'];
		$number_of_posts         = $settings['number_of_posts'];
		$link_text               = $settings['link_text'];
		$cat_link_text           = isset( $settings['cat_link_text'] ) ? $settings['cat_link_text'] : false;
		$layout                  = $settings['layout'];
		$number_of_columns       = (int) $settings['number_of_columns'];
		$description_word_length = $settings['description_word_length'];
		$thumbnail_dimensions    = $settings['thumbnail_dimensions'];
		$post_date_format        = $settings['post_date_format'];
		$show_comment_count      = $settings['show_comment_count'] === 'yes' ? true : false;
		$show_author             = $settings['show_author'] === 'yes' ? true : false;

		// in case of featured post
		if ( $number_of_posts > 1 && $post_id ) {
			$number_of_posts--;
		}

		$args = array(
			'numberposts'      => $number_of_posts,
			'category'         => $category,
			'orderby'          => 'post_date',
			'order'            => 'DESC',
			'suppress_filters' => false,
		);

		if ( $post_id ) {
			$args['exclude'] = $post_id;
		}

		$posts = get_posts( $args );

		// If no posts let's bail
		if ( ! $posts ) {
			return;
		}

		// in case of featured post
		if ( $post_id ) {
			$featured_post = get_post( $post_id );
			if ( $featured_post ) {
				array_unshift( $posts, $featured_post );
			}
		}

		$grid = array(
			'one whole',
			'one half',
			'one third',
			'one fourth',
			'one fifth',
			'one sixth',
		);

		$templates = $this->get_templates();
		$template_file = dirname(__FILE__) . "/templates/{$layout}.php";

		// Load template if it exists and is allowed
		// otherwise load default template
		$template_file = file_exists( $template_file ) && isset( $templates[ $layout ] ) 
						? $template_file : 
						'templates/layout_1.php';

		$grid_class = $grid[ (int) $number_of_columns - 1 ];
		$css_class = 'linp-post-list';
		?>
		<div class="<?php echo esc_attr( $css_class ); ?> <?php echo esc_attr( $layout ); ?>">

			<?php if ( $layout === 'layout_5' ): ?>
				<?php include $template_file; ?>
			<?php else: ?>
				<?php foreach ( array_chunk( $posts, $number_of_columns ) as $chunk ): ?>
					<div class="vc_row">
						<?php foreach ( $chunk as $post ): ?>
							<?php include $template_file; ?>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>

			<?php if ( $cat_link_text ): ?>
				<?php $category_link = get_category_link( $category ); ?>
				<a class="cbp_widget_link cbp_widget_button"
				   href="<?php echo esc_url( $category_link ); ?>"><?php echo esc_html( $cat_link_text ); ?></a>
			<?php endif; ?>
		</div>
		<?php
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Ed_School_Plugin_Elementor_Widget_Post_List() );
