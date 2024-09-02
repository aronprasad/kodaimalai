<div class="table-wrapp">
	<table class="table-responsive">
		<thead>
		<tr>
			<th><?php esc_html_e( 'Course Title', 'ed-school' ); ?></th>
			<th><?php esc_html_e( 'Lessons', 'ed-school' ); ?></th>
			<th><?php esc_html_e( 'Price', 'ed-school' ); ?></th>
			<th><?php esc_html_e( 'Duration', 'ed-school' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php while ( $results->have_posts() ) : $results->the_post(); ?>
			<tr>
				<td><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>
				<td><?php echo (int) $woothemes_sensei->post_types->course->course_lesson_count( get_the_ID() ); ?></td>
				<td>Free</td>
				<td>5 hours 10 mins</td>
			</tr>
		<?php endwhile; ?>
		</tbody>
	</table>
</div>
