<?php

class Ed_School_Plugin_Elementor_Widget_Teachers extends \Elementor\Widget_Base {

	public function get_name() {
		return 'scp_teachers';
	}

	public function get_title() {
		return __( 'Teachers', 'ed_school_plugin' );
	}

	public function get_icon() {
		return 'fa fa-users';
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

		$thumbs_dimensions_array = array_merge( array( 'thumbnail'), ed_school_plugin_get_thumbnail_sizes() , array( 'full-width' ) );

		$teachers       = get_posts( array( 'post_type' => 'teacher', 'numberposts' => - 1, ) );
		$teachers_array = array( '' => esc_html__( 'Select Teacher', 'ed-school-plugin' ) );
		foreach ( $teachers as $teacher ) {
			$teachers_array[ $teacher->ID ] = $teacher->post_title;
		}

		$args       = array(
			'taxonomy' => 'teacher_category'
		);
		$cats = [];
		foreach ( get_categories( $args ) as $category ) {
			$cats[ $category->term_id ] = $category->name;
		}

		$this->add_control(
			'teacher_id',
			[
				'label' => __( 'Teacher', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $teachers_array,
				'description' => esc_html__( 'If specific teacher is selected, then this widget prints only that teacher.', 'ed-school-plugin' ),
			]
		);

		$this->add_control(
			'category_id',
			[
				'label' => __( 'Category', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $cats,
				'description' => esc_html__( 'If specific category is selected, then this widget prints only teachers from selected category.', 'ed-school-plugin' ),
			]
		);

		$this->add_control(
			'image_size',
			[
				'label' => __( 'Image Size', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $thumbs_dimensions_array,
			]
		);

		$this->add_control(
			'number_of_posts',
			[
				'label' => __( 'Number of Posts', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number',
				'default' => 3,
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
			'show_social_icons',
			[
				'label' => __( 'Show social icons?', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'elementor' ),
				'label_on' => __( 'Show', 'elementor' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'DESC'=> 'DESC',
					'ASC' => 'ASC',
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order By', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'title'  	 => 'title',
					'date'   	 => 'date',
					'menu_order' => 'menu_order',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$category_id       = $settings['category_id'];
		$teacher_id        = $settings['teacher_id'];
		$number_of_posts   = $settings['number_of_posts'];
		$number_of_columns = $settings['number_of_columns'];
		$image_size        = $settings['image_size'];
		$show_social_icons = $settings['show_social_icons'] === 'yes' ? true : false;
		$order             = $settings['order'];
		$orderby           = $settings['orderby'];

		$args = array(
			'numberposts'      => $number_of_posts,
			'orderby'          => $orderby,
			'order'            => $order,
			'suppress_filters' => false,
			'post_type'        => 'teacher'
		);

		$wrapper_class = "vc_row scp-teachers scp-teachers-{$number_of_columns} ";

		if ( $teacher_id ) {
			$args['include'] = $teacher_id;
			$wrapper_class   = 'scp-teachers ';
		}

		if ( $category_id ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'teacher_category',
					'field'    => 'term_id',
					'terms'    => $category_id
				)
			);
		}

		$posts = get_posts( $args );

		if ( ! $posts ) {
			return;
		}

		$grid = array(
			'one whole',
			'one half',
			'one third',
			'one fourth',
			'one fifth',
			'one sixth',
		);

		$grid_class = $grid[ (int) $number_of_columns - 1 ];
		$css_class  = $wrapper_class;

		?>
		<div class="<?php echo esc_attr( $css_class ); ?>">
			<?php foreach ( $posts as $post ) : ?>
				<div class="teacher <?php echo esc_attr( $grid_class ); ?> <?php echo esc_attr( $teacher_id ? '' : 'wh-padding' ); ?>">
					<div class="thumbnail">
						<?php
						$img_url = '';
						if ( has_post_thumbnail( $post->ID ) ) {
							$thumb_atts    = array();
							$hover_img_url = ed_school_get_rwmb_meta_image_url( 'teacher_hover_img', $post->ID );
							if ( $hover_img_url ) {
								$thumb_atts['class'] = 'top-img';
							}
							$img_url = get_the_post_thumbnail( $post->ID, $image_size, $thumb_atts );
							if ( $hover_img_url ) {
								$img_url .= "<img class=\"hover-img\" src=\"{$hover_img_url}\" />";
							}
						}
						if ( '' != $img_url ) {
							echo '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" title="' . esc_attr( get_post_field( 'post_title', $post->ID ) ) . '">' . $img_url . '</a>';
						}
						?>
					</div>
					<div class="item">
						<h5 class="entry-title"><a
								href="<?php the_permalink( $post ); ?>"><?php echo esc_html( $post->post_title ); ?></a>
						</h5>
						<?php $job_title = ed_school_get_rwmb_meta( 'job_title', $post->ID ); ?>
						<?php if ( $job_title ) : ?>
							<h6 class="job-title"><?php echo esc_html( $job_title ); ?></h6>
						<?php endif; ?>
						<?php $summary = ed_school_get_rwmb_meta( 'summary', $post->ID ); ?>
						<?php if ( $summary ) : ?>
							<div class="summary"><?php echo do_shortcode( $summary ); ?></div>
						<?php else: ?>
							<div class="summary"><?php echo do_shortcode( get_the_excerpt() ); ?></div>
						<?php endif; ?>

						<?php $social_icons = ed_school_get_rwmb_meta( 'social_icons', $post->ID ); ?>
						<?php if ( (int) $show_social_icons && ! empty( $social_icons ) ): ?>
							<div class="social">
								<div class="text"><?php esc_html_e( 'Meet me on', 'ed-school' ); ?></div>
								<?php foreach ( $social_icons as $social_icon ): ?>
									<a href="<?php echo esc_url( $social_icon[1] ); ?>">
										<i class="<?php echo esc_attr( $social_icon[0] ); ?>"></i>
									</a>
								<?php endforeach ?>
							</div>
						<?php endif ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php

	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Ed_School_Plugin_Elementor_Widget_Teachers() );
