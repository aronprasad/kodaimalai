<div class="price">
	<?php $course_price = function_exists( 'ed_school_sensei_simple_course_price' ) ? ed_school_sensei_simple_course_price( $post_id ) : false; ?>
	<?php if ( $course_price ): ?>
		<?php echo wp_kses_post( $course_price ); ?>
	<?php else: ?>
		<?php if ( apply_filters( 'ed_school_filter_show_course_free_label', true ) ) : ?>
			<span class="course-price free-course"><span class="amount"><?php esc_html_e( 'Free', 'ed-school-plugin' ); ?></span></span>
		<?php endif ?>
	<?php endif; ?>
</div>
