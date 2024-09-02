<?php if ( ( (int) $show_category ) && $category_output != '' ) : ?>
	<p class="sensei-course-meta">
		<span><i class="icon-skilledadd-bookmarks"></i></span>
		<span class="course-category"><?php echo wp_kses_post( $category_output ); ?></span>
	</p>
<?php endif; ?>
