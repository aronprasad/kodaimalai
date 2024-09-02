<li class="event">
	<div class="date">
		<div class="circle">
			
			<?php if ( $start_date_format ) : ?>
				<span class="month">
					<?php echo esc_html( tribe_get_start_date( $post, false, $start_date_format ) ); ?>
				</span>
			<?php else: ?>
				<span class="month">
					<?php echo esc_html( tribe_get_start_date( $post, false, 'M' ) ); ?>
				</span>

				<span class="day">
					<?php echo esc_html( tribe_get_start_date( $post, false, 'd' ) ); ?>
				</span>
			<?php endif; ?>
		</div>
	</div>
	<div class="info">
		<div class="title">
			<a href="<?php echo esc_url( tribe_get_event_link( $post ) ); ?>"
			   rel="bookmark"><?php echo esc_html( $post->post_title ); ?></a>
		</div>
		<?php if ( (int) $show_description ) : ?>
			<div class="content">
				<?php echo wp_kses_post( wp_trim_words( strip_shortcodes( $post->post_content ), $description_word_length, '&hellip;' ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</li>