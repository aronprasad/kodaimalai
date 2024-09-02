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
    <a href="<?php the_permalink( $post_id ); ?>" title="<?php echo esc_attr( $post_title ); ?>"><?php echo esc_html( $post_title ); ?></a>
</h4>