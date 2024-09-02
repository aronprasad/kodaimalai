<?php if ( (int) $show_excerpt ): ?>
    <p class="course-excerpt"><?php echo wp_kses_post( wp_trim_words( $post_item->post_excerpt, $number_of_words ) ); ?></p>
<?php endif ?>
<?php if ( 0 < $preview_lesson_count && ! $is_user_taking_course ) : ?>
    <?php $preview_lessons = sprintf( esc_html__( '(%d preview lessons)', 'woothemes-sensei' ), $preview_lesson_count); ?>
    <p class="sensei-free-lessons"><a href="<?php the_permalink($post_id); ?>"><?php esc_html_e( 'Preview this course', 'woothemes-sensei' ) ?></a> - <?php echo esc_html( $preview_lessons ); ?></p>
<?php endif ?>
