<?php
add_filter( 'template_redirect', 'ed_school_search_template', 20 );
add_filter( 'pre_get_posts', 'ed_school_sensei_filter_custom_types' );
add_filter( 'sensei_login_logout_menu_title', 'ed_school_filter_login_menu_item_label' );
add_filter( 'pre_get_document_title', 'ed_school_sensei_search_title', 100 );

add_action( 'sensei_wc_single_add_to_cart_button_text', 'ed_school_single_add_to_cart_text' );
add_action( 'sensei_start_course_text', 'ed_school_single_add_to_cart_text' );
add_action( 'sensei_after_main_content', 'ed_school_sensei_add_similar_courses', 9 );
add_action( 'wp_enqueue_scripts', 'ed_school_sensei_scripts', 101 );

remove_action( 'sensei_single_course_content_inside_before', array( 'Sensei_Course', 'the_title' ), 10 );
remove_action( 'sensei_single_course_content_inside_before', array( 'Sensei_Course', 'the_course_video' ), 40 );
remove_action( 'sensei_single_lesson_content_inside_before', array( 'Sensei_Lesson', 'the_title' ), 15 );

if ( function_exists( 'Sensei' ) ) {
	remove_action( 'sensei_single_course_content_inside_before', array(
		Sensei()->post_types->course,
		'the_progress_statement'
	), 15 );
	remove_action( 'sensei_single_course_content_inside_before', array(
		Sensei()->post_types->course,
		'the_progress_meter'
	), 16 );
	add_action( 'sensei_single_course_content_inside_before', array(
		Sensei()->post_types->course,
		'the_progress_statement'
	), 35 );
	add_action( 'sensei_single_course_content_inside_before', array(
		Sensei()->post_types->course,
		'the_progress_meter'
	), 36 );
}

function ed_school_sensei_scripts() {
	wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/sensei/custom/assets/magnific-popup.css', false );

	wp_enqueue_script( 'jquery.magnific-popup', get_template_directory_uri() . '/sensei/custom/assets/jquery.magnific-popup.min.js', array( 'jquery' ), null, true );

	if ( is_single() && get_post_type() == 'course' ) {
		wp_dequeue_script( 'fakeLoader' );
	}
}

/**
 * Sensei Support Declaration
 */
add_action( 'after_setup_theme', 'ed_school_declare_sensei_support' );
function ed_school_declare_sensei_support() {
	add_theme_support( 'sensei' );
}

function ed_school_sensei_search_title( $title ) {
	if ( ed_school_is_search_courses() ) {
		$search_page_id  = ed_school_get_option( 'sensei-course-search-page', false );
		if ( $search_page_id ) {
			$search_page_title = get_the_title( $search_page_id );
			$site_title = get_bloginfo( 'name' );

			return "{$search_page_title} - {$site_title}";
		}
	}
	return $title;
}

function ed_school_single_add_to_cart_text( $text ) {
	if ( is_single() && get_post_type() == 'course' ) {
		return esc_html__( 'Take this course', 'ed-school' );
	}
	return $text;
}

/**
 * This is used only if a search page is not set in Theme Options
 * If it is set then the url of the page is set as form action
 */
function ed_school_search_template() {
	if ( ed_school_is_search_courses() ) {
		include_once get_template_directory() . '/search-courses.php';
		exit;
	}
}

function ed_school_is_search_courses() {
	return isset( $_GET['s'] ) && isset( $_GET['search-type'] ) && $_GET['search-type'] == 'courses';
}

/**
 * This enables Courses to turn up in post by author listing
 */
function ed_school_sensei_filter_custom_types( $query ) {
	if ( is_author() && $query->is_main_query() ) {
		$query->set( 'post_type', array(
			'post',
			'course'
		) );
	}
	return $query;
}

function ed_school_filter_login_menu_item_label( $menu_title ) {
	if ( $menu_title == 'Login' ) {
		$menu_title = esc_html__( 'Sign In/Sign Up', 'ed-school' );
	}
	return $menu_title;
}

function ed_school_course_print_stars( $id = '', $permalink = false, $newwindow = true, $alttext = true ) {

	if ( class_exists( 'WooCommerce' ) && get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {

		global $wpdb;

		if ( empty( $id ) ) {
			global $post;
			$id = $post->ID;
		}

		if ( is_bool( $permalink ) ) {
			if ( $permalink ) {
				$link_escaped = esc_url( get_permalink( $id ) );
			}
		} else {
			$link_escaped = esc_url( $permalink );
			$permalink = true;
		}

		$target = '';
		if ( $newwindow ) {
			$target = "target='_blank' ";
		}


		if ( get_post_type( $id ) == 'product' ) {
			$count = $wpdb->get_var( "
			SELECT COUNT(meta_value) FROM $wpdb->commentmeta
			LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
			WHERE meta_key = 'rating'
			AND comment_post_ID = $id
			AND comment_approved = '1'
			AND meta_value > 0
		" );

			$rating = $wpdb->get_var( "
			SELECT SUM(meta_value) FROM $wpdb->commentmeta
			LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
			WHERE meta_key = 'rating'
			AND comment_post_ID = $id
			AND comment_approved = '1'
		" );

			if ( $permalink ) {
				echo "<a href='{$link_escaped}'  {$target} >";
			}

			echo '<span class="starwrapper" itemscope itemtype="http://schema.org/AggregateRating">';

			if ( $count > 0 ) {
				$average = number_format( $rating / $count, 2 );

				echo '<span class="star-rating" title="' . sprintf( esc_html__( 'Rated %s out of 5', 'ed-school' ), $average ) . '"><span style="width:' . ( $average * 16 ) . 'px"><span itemprop="ratingValue" class="rating">' . $average
				     . '</span> </span></span>';

			} else {
				if ( $alttext ) {
					echo '<span class="star-rating-alt-text">' . esc_html__( 'Be the first to rate', 'ed-school' ) . '</span>';
				}
			}

			echo '</span>';

			if ( $permalink ) {
				echo "</a>";
			}
		}
	}
}

function ed_school_get_teacher( $user_id = 0 ) {
	if ( ! $user_id ) {
		return false;
	}

	$args = array(
		'numberposts' => 1,
		'post_type'   => 'teacher',
		'author'      => $user_id,
	);
	
	$teachers = new WP_Query( $args );
	$teacher = null;

	if ( $teachers->posts && count( $teachers->posts ) ) {
		$teacher = $teachers->posts[0];
	}

	return $teacher;
}

function ed_school_get_teacher_thumb( $user_id = 0 ) {

	if ( ! $user_id ) {
		return false;
	}

	if ( is_a( $user_id, 'WP_Post' ) ) {
		$teacher = $user_id;
	} else {
		$teacher = ed_school_get_teacher( $user_id );
	}

	if ( $teacher ) {
		$img_url = '';
		if ( has_post_thumbnail( $teacher->ID ) ) {
			$img_url = get_the_post_thumbnail( $teacher->ID, 'thumbnail' );
		}
		if ( '' != $img_url ) {
			return '<a href="' . get_permalink( $teacher->ID ) . '" title="' . esc_attr( get_post_field( 'post_title', $teacher->ID ) ) . '">' . $img_url . '</a>';
		}
	}
	return false;
}

function ed_school_sensei_simple_course_price( $post_id ) {

	if ( version_compare( ed_school_sensei_get_version(), '2.0', '>=' ) 
		&& class_exists( 'Sensei_WC_Paid_Courses\Frontend\Courses' ) ) {
		ob_start();

		\Sensei_WC_Paid_Courses\Frontend\Courses::instance()->output_course_price( $post_id );

		$content = ob_get_clean();

		if ( $content ) {
			return $content;
		}

	} elseif ( version_compare( ed_school_sensei_get_version(), '2.0', '<' ) 
		&& function_exists( 'sensei_simple_course_price' ) ) {

		ob_start();

		sensei_simple_course_price( $post_id );

		$content = ob_get_clean();

		if ( $content ) {
			return $content;
		}
	}
	return false;
}

function ed_school_sensei_add_similar_courses() {
	if ( is_single() ) {
		get_template_part( 'sensei/custom/similar-courses' );
	}
}

function ed_school_sensei_get_version() {
	if ( function_exists( 'Sensei') ) {
		return Sensei()->version;
	}
	return 0;
}
