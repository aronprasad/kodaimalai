<?php

class Ed_School_Plugin_Elementor_Widget_Sensei_Course_Carousel extends \Elementor\Widget_Base {

	public function get_name() {
		return 'scp_sensei_course_carousel';
	}

	public function get_title() {
		return __( 'Sensei Course Carousel', 'ed_school_plugin' );
	}

	public function get_icon() {
		return 'eicon-favorite';
	}

	public function get_categories() {
		return [ 'theme-elements' ];
	}

	public function get_style_depends() {
		return [
			'owlcarousel',
			'owl-theme',
			'owl-transitions',
		];
	}

	public function get_script_depends() {
		return ['owl.carousel', 'sensei-course-carousel'];
	}

	protected function register_controls() {

		$teachers       = get_posts( array( 'post_type' => 'teacher', 'numberposts' => - 1, ) );
		$teachers_array = array( '' => esc_html__( 'Select Teacher', 'ed-school-plugin' ) );
		foreach ( $teachers as $teacher ) {
			$teachers_array[ $teacher->ID ] = $teacher->post_title;
		}

		$args = array(
			'orderby' => 'name',
			'parent'  => 0,
			'taxonomy' => 'course-category',
		);
		$cats = [];
		$cats[0] = esc_html__( 'None', 'ed-school-plugin' );
		foreach ( get_categories( $args ) as $category ) {
			$cats[ $category->term_id ] = $category->name;
		}

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'ed-school-plugin' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'category_id',
			[
				'label' => esc_html__( 'Category', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $cats,
			]
		);
		
		$this->add_control(
			'teacher_id',
			[
				'label' => esc_html__( 'Teacher', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $teachers_array,
			]
		);

		$this->add_control(
			'course_type',
			[
				'label' => esc_html__( 'Course Type', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'usercourses'     => esc_html__( 'All', 'ed-school-plugin' ),
					'featuredcourses' => esc_html__( 'Featured Courses', 'ed-school-plugin' ),
				],
				'default' => '3',
			]
		);

		$this->add_control(
			'image_size',
			[
				'label' => esc_html__( 'Image Size', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => ed_school_plugin_get_thumbnail_sizes(),
			]
		);

		$this->add_control(
			'show_ratings',
			[
				'label' => esc_html__( 'Show Ratings?', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'ed-school-plugin' ),
				'label_on' => esc_html__( 'Show', 'ed-school-plugin' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_category',
			[
				'label' => esc_html__( 'Show Category?', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'ed-school-plugin' ),
				'label_on' => esc_html__( 'Show', 'ed-school-plugin' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'image_is_full_width',
			[
				'label' => esc_html__( 'Image takes the full width?', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'ed-school-plugin' ),
				'label_on' => esc_html__( 'Show', 'ed-school-plugin' ),
				'description' => esc_html__( 'Inner padding won\'t affect the image', 'ed-school-plugin' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'number_of_words',
			[
				'label' => __( 'Number of words', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number',
				'description' => esc_html__( 'Number of words of the excerpt.', 'ed-school-plugin' ),
				'default' => 10,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Style', 'ed-school-plugin' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'slider_section',
			[
				'label' => esc_html__( 'Slider Settings', 'ed-school-plugin' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'number_of_items',
			[
				'label' => __( 'Number of Items', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number',
				'description' => esc_html__( 'Number of items to show.', 'ed-school-plugin' ),
				'default' => 8,
			]
		);

		$this->add_control(
			'number_of_items_per_slide_desktop',
			[
				'label' => __( 'Number of Items per Slide (Desktop)', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number',
				'default' => 4,
			]
		);
		
		$this->add_control(
			'number_of_items_per_slide_small_desktop',
			[
				'label' => __( 'Number of Items per Slide (Small desktop)', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number',
				'default' => 3,
			]
		);
		
		$this->add_control(
			'number_of_items_per_slide_tablet',
			[
				'label' => __( 'Number of Items per Slide (Tablet)', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number',
				'default' => 2,
			]
		);
		
		$this->add_control(
			'number_of_items_per_slide_mobile',
			[
				'label' => __( 'Number of Items per Slide (Mobile)', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number',
				'default' => 1,
			]
		);

		$this->add_control(
			'slide_speed',
			[
				'label' => __( 'Slide Speed', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number',
				'default' => 500,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay?', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Yes', 'ed-school-plugin' ),
				'label_on' => esc_html__( 'No', 'ed-school-plugin' ),
				'default' => 'no',
			]
		);

		$this->add_control(
			'show_bullets',
			[
				'label' => esc_html__( 'Show Bullets?', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Yes', 'ed-school-plugin' ),
				'label_on' => esc_html__( 'No', 'ed-school-plugin' ),
				'default' => 'no',
			]
		);

		$this->add_control(
			'adaptive_height',
			[
				'label' => esc_html__( 'Adaptive Height?', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Yes', 'ed-school-plugin' ),
				'label_on' => esc_html__( 'No', 'ed-school-plugin' ),
				'default' => 'no',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		if ( ! class_exists( 'WooThemes_Sensei' ) ) {
			return;
		}

		$atts = $this->get_settings_for_display();

		$teacher_id                              = $atts['teacher_id'];
		$category_id                             = $atts['category_id'];
		$course_type                             = $atts['course_type'];
		$number_of_items                         = $atts['number_of_items'];
		$number_of_items_per_slide_desktop       = $atts['number_of_items_per_slide_desktop'];
		$number_of_items_per_slide_small_desktop = $atts['number_of_items_per_slide_small_desktop'];
		$number_of_items_per_slide_tablet        = $atts['number_of_items_per_slide_tablet'];
		$number_of_items_per_slide_mobile        = $atts['number_of_items_per_slide_mobile'];
		$slide_speed                             = $atts['slide_speed'];
		$autoplay                                = $atts['autoplay'] == 'yes' ? 'true' : 'false';
		$show_bullets                            = $atts['show_bullets'] == 'yes' ? 'true' : 'false';
		$show_ratings                            = $atts['show_ratings'] == 'yes' ? 'true' : 'false';
		$show_category                           = $atts['show_category'] == 'yes' ? 'true' : 'false';
		$adaptive_height                         = $atts['adaptive_height'] == 'yes' ? 'true' : 'false';
		$number_of_words                         = $atts['number_of_words'];
		$image_size                              = $atts['image_size'];
		$image_is_full_width                     = $atts['image_is_full_width'];

		$show_excerpt              = true;

		global $woothemes_sensei, $post, $wp_query, $current_user;
			
		$args = array(
			'numberposts'      => $number_of_items,
			'post_type'        => 'course',
			'post_status'      => 'publish',
			'suppress_filters' => 0
		);
		
		if ( $teacher_id ) {
			$teacher = get_post( (int) $teacher_id );
			if ( $teacher ) {
				$args['author'] = (int) $teacher->post_author;
			}
		}

		if ( $category_id ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'course-category',
					'field'    => 'term_id',
					'terms'    => $category_id,
				)
			);
		}

		$posts_array = get_posts( $args );

		$css_class = 'linp-featured-courses-carousel wh-sensei-courses-carousel';

		if ( count( $posts_array ) ) {
			?>
			<div class="<?php echo esc_attr( $css_class ); ?>"
				data-items-desktop="<?php echo esc_attr( (int) $number_of_items_per_slide_desktop ); ?>"
				data-items-small-desktop="<?php echo esc_attr( (int) $number_of_items_per_slide_small_desktop ); ?>"
				data-items-tablet="<?php echo esc_attr( (int) $number_of_items_per_slide_tablet ); ?>"
				data-items-mobile="<?php echo esc_attr( (int) $number_of_items_per_slide_mobile ); ?>"
				data-slide-speed="<?php echo esc_attr( (int) $slide_speed ); ?>"
				data-auto-play="<?php echo esc_attr( $autoplay ); ?>"
				data-show-bullets="<?php echo esc_attr( $show_bullets ); ?>"
				data-auto-height="<?php echo esc_attr( $adaptive_height ); ?>"
			>
				<?php
				foreach ( $posts_array as $post_item ) {
				
					// Get meta data
					$post_id               = absint( $post_item->ID );
					$post_title            = $post_item->post_title;
					$user_info             = get_userdata( absint( $post_item->post_author ) );
					$author_link           = get_author_posts_url( absint( $post_item->post_author ) );
					$author_display_name   = $user_info->display_name;
					$author_id             = $post_item->post_author;
					$category_output       = get_the_term_list( $post_id, 'course-category', '', ', ', '' );
					$preview_lesson_count  = intval( $woothemes_sensei->post_types->course->course_lesson_preview_count( $post_id ) );
					
					if ( version_compare( $woothemes_sensei->version, '3.0.0', '>=' ) ) {
						$is_user_taking_course = Sensei_Course::is_user_enrolled( $post_id, $current_user->ID );
					} else {
						$is_user_taking_course = WooThemes_Sensei_Utils::user_start_course( $post_id, $current_user->ID );
					}
					?>
					<div class="item">
						<?php if ( $image_is_full_width ): ?>
							<?php include 'partials/image.php'; ?>
						<?php endif ?>
						<div>
							<div class="item-inner-wrap">
								
							<?php
							if ( ! $image_is_full_width ) {
								include 'partials/image.php';
							}
							
							include 'partials/categories.php';
							include 'partials/title.php';
							include 'partials/excerpt.php';
							include 'partials/ratings.php';
							?>
							</div>
						</div>
						<?php include 'partials/lesson-count.php'; ?>
					</div>
				<?php
				} // End For Loop
				?>
			</div>
		<?php
		}

	}


}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Ed_School_Plugin_Elementor_Widget_Sensei_Course_Carousel() );
