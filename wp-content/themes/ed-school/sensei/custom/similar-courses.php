<?php

global $post, $woothemes_sensei;

if ( ! $post || ! $woothemes_sensei ) {
	return;
}

$user_info             = get_userdata( absint( $post->post_author ) );
$author_link           = get_author_posts_url( absint( $post->post_author ) );
$author_display_name   = $user_info->display_name;
$author_id             = $post->post_author;
$category_output       = get_the_term_list( $post->ID, 'course-category', '', ', ', '' );
$preview_lesson_count  = intval( $woothemes_sensei->post_types->course->course_lesson_preview_count( $post->ID ) );

$categories = get_the_terms( $post, 'course-category' );
$cat_ids    = array();
if ($categories) {
	foreach ( $categories as $category ) {
		$cat_ids[] = $category->term_id;
	}
}

$search_args = array(
	'post_type'      => 'course',
	'posts_per_page' => 3,
	'offset'         => 0,
	'orderby'        => 'rand',
	'post__not_in'   => array( $post->ID ),
	'tax_query'      =>
		array(
			'taxonomy' => 'course-category',
			'field'    => 'ID',
			'terms'    => $cat_ids,
		),

);
$results     = new WP_Query( $search_args );
$image_size  = 'wh-medium';
?>
<?php if ( $results->have_posts() ): ?>
	<div class="vc_row linp-featured-courses-carousel wh-similar-courses">
		<div class="<?php echo esc_attr( ed_school_class( 'container' ) ) ?>">
			<h3><?php esc_html_e( 'Related Courses', 'ed-school' ); ?></h3>
			<div class="<?php echo esc_attr( ed_school_class( 'strecher' ) ) ?>">
				<?php while ( $results->have_posts() ) : $results->the_post(); ?>
					<div class="owl-item one third">
						
					<div class="item">
							<?php $img = ''; ?>
	                        <?php if ( has_post_thumbnail( $post->ID ) ) : ?>
	                            <?php $img = get_the_post_thumbnail( $post->ID, $image_size, array( 'class' => 'featured-course-thumb') ); ?>
	                        <?php endif; ?>
	                        <?php if ( '' != $img ) : ?>
	                            <div class="img-container">
	                                <a href="<?php the_permalink( $post->ID ) ?>" title="<?php echo esc_attr( get_post_field( 'post_title', $post->ID ) ); ?>"><?php echo wp_kses_post( $img ); ?></a>
	                            </div>
	                        <?php endif; ?>
						<div>
							<div class="item-inner-wrap">
								<?php $teacher = ed_school_get_teacher( $author_id ) ?>
								<?php if ( $teacher) : ?>
									<div class="teacher">
										<?php echo wp_kses_post( ed_school_get_teacher_thumb( $teacher ) ); ?>
										<p>
											<?php echo esc_html( $teacher->post_title ); ?>
										</p>
									</div>
								<?php endif ?>

								<h4 class="course-title">
								    <a href="<?php the_permalink( $post->ID ); ?>" title="<?php echo esc_attr( $post->post_title ); ?>"><?php echo esc_html( $post->post_title ); ?></a>
								</h4>
								<?php if ( $post->post_excerpt ): ?>
								    <p class="course-excerpt"><?php echo wp_kses_post( wp_trim_words( $post->post_excerpt, 10 ) ); ?></p>
								<?php endif ?>
							</div>
						</div>
						<div class="cbp-row">
							<?php $lesson_count = $woothemes_sensei->post_types->course->course_lesson_count( $post->ID ) ?>
							<?php if ( $lesson_count === 1 ): ?>
								<?php $lesson_text = esc_html__( '1 Lesson', 'ed-school' ); ?>
							<?php else: ?>
									<?php $lesson_text = $lesson_count . '&nbsp;' . esc_html__( 'Lessons', 'ed-school' ); ?>
							<?php endif ?>
							<div class="course-lesson-count">		
					    		<?php echo wp_kses_post( $lesson_text ); ?>
							</div>
					    	<div class="price">
								<?php $course_price = function_exists( 'ed_school_sensei_simple_course_price' ) 
										? ed_school_sensei_simple_course_price( $post->ID ) 
										: false; ?>
								<?php if ( $course_price ): ?>
									<?php echo wp_kses_post( $course_price ); ?>
								<?php else: ?>
										<a href="<?php the_permalink( $post->ID ); ?>" class="wh-button course-link"><?php esc_html_e( 'View', 'ed-school-plugin' ); ?></a>
								<?php endif; ?>
							</div>
					</div>
					</div>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
	<?php wp_reset_postdata(); ?>
<?php endif; ?>
