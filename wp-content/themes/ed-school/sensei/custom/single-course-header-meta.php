<?php
global $post, $woothemes_sensei;
$author_id              = $post->post_author;
$teacher_id             = get_post_meta( $post->ID, 'sensei-teacher', true );
$author_id              = $teacher_id ? $teacher_id : $author_id;
$show_participant_count = ed_school_get_option( 'sensei-single-course-show-participant-count', true );
?>
<div class="meta-wrap">
	<div class="meta-item author">
		<?php $teacher_thumb = ed_school_get_teacher_thumb( $author_id ); ?>
		<?php if ( $teacher_thumb ) : ?>
			<?php echo wp_kses_post( $teacher_thumb ); ?>
		<?php else: ?>
			<a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">
				<?php echo get_avatar( get_the_author_meta( 'user_email', $author_id ), apply_filters( 'ed_school_author_bio_avatar_size', 60 ) ); ?>
			</a>
		<?php endif; ?>
		<div class="author-data">
			<h4><?php esc_html_e( 'Author:', 'ed-school' ); ?></h4>
			<p><?php the_author_meta( 'display_name', $author_id ); ?></p>
		</div>
	</div>
	<div class="meta-item meta-item-price">
		<?php $course_price = ed_school_sensei_simple_course_price( $post->ID ); ?>
		<?php if ( $course_price ): ?>
			<h4><?php esc_html_e( 'Price:', 'ed-school' ); ?></h4>
			<p><?php echo wp_kses_post( $course_price ); ?></p>
		<?php else: ?>
			<?php if ( apply_filters( 'ed_school_filter_show_course_free_label', true ) ) : ?>
				<h4><?php esc_html_e( 'Price:', 'ed-school' ); ?></h4>
				<p>
					<span class="course-price free-course"><span class="amount"><?php esc_html_e( 'Free', 'ed-school' ); ?></span></span>
				</p>
			<?php endif ?>
		<?php endif; ?>
	</div>
	<?php $course_duration = ed_school_get_rwmb_meta( 'course_duration', $post->ID ); ?>
	<?php if ( (int) $course_duration ) : ?>
		<div class="meta-item duration">
			<h4><?php esc_html_e( 'Duration:', 'ed-school' ); ?></h4>
			<p><?php echo esc_html( $course_duration ); ?></p>
		</div>
	<?php endif; ?>
	<div class="meta-item lesson-count">
        <h4><?php esc_html_e( 'Lessons:', 'ed-school' ); ?></h4>
		<p><?php echo esc_html( (int) $woothemes_sensei->post_types->course->course_lesson_count( $post->ID ) ); ?></p>
	</div>
	<?php if ( $show_participant_count && class_exists( 'WooThemes_Sensei_Utils' ) ) : ?>
		<?php
		$course_learners = WooThemes_Sensei_Utils::sensei_check_for_activity( apply_filters( 'sensei_learners_course_learners', array( 'post_id' => $post->ID, 'type' => 'sensei_course_status', 'status' => 'any' ) ) );

		$course_learners = intval( $course_learners );
		?>
		<div class="meta-item">
			<h4><?php esc_html_e( 'Students:', 'ed-school' ); ?></h4>
			<p><?php echo esc_html( $course_learners ); ?></p>
		</div>
	<?php endif; ?>
</div>
