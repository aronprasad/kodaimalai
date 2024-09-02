<li class="event">
	<div class="date">
		<?php echo tribe_events_event_schedule_details( $post ); ?>
	</div>
	<div class="info">
		<div class="title">
			<a href="<?php echo esc_url( tribe_get_event_link( $post ) ); ?>"
			   rel="bookmark"><?php echo esc_html( $post->post_title ); ?> »</a>
		</div>
		<?php if ( (int) $show_description ) : ?>
			<div class="content">
				<?php echo wp_kses_post( wp_trim_words( strip_shortcodes( $post->post_content ), $description_word_length, '&hellip;' ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</li>