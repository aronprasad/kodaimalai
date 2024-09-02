<div class="cbp-row">
		<?php $lesson_count = $woothemes_sensei->post_types->course->course_lesson_count( $post_id ) ?>
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
					? ed_school_sensei_simple_course_price( $post_id ) 
					: false; ?>
			<?php if ( $course_price ): ?>
				<?php echo wp_kses_post( $course_price ); ?>
			<?php else: ?>
					<a href="<?php the_permalink( $post_id ); ?>" class="wh-button course-link"><?php esc_html_e( 'View', 'ed-school-plugin' ); ?></a>
			<?php endif; ?>
		</div>
</div>