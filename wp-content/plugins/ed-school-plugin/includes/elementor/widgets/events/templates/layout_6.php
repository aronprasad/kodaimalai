<?php 
$start_date_format = $start_date_format ? $start_date_format : 'F d Y';
?>

<li class="event">
	<div class="date">
		<div class="circle">
			<span class="day">
				<?php echo tribe_get_start_date( $post, false, 'd' ); ?>
			</span>
			<span class="month">
				<?php echo tribe_get_start_date( $post, false, 'F' ); ?>
			</span>
		</div>
	</div>
	<div class="info">
		<div class="meta">
			<span>
				<?php echo tribe_get_start_time( $post ); ?> - <?php echo tribe_get_end_time( $post ); ?>
			</span>
			<span>
				<?php echo tribe_get_venue_link( $post ); ?>
			</span>
		</div>
		<h4 class="title">
			<a href="<?php echo esc_url( tribe_get_event_link( $post ) ); ?>"
			   rel="bookmark"><?php echo esc_html( $post->post_title ); ?></a>
		</h4>
		<?php if ( (int) $show_description ) : ?>
			<div class="content">
				<?php echo wp_kses_post( wp_trim_words( strip_shortcodes( $post->post_content ), $description_word_length, '&hellip;' ) ); ?>
			</div>
		<?php endif; ?>
	</div>
	<?php if ( $show_image ): ?>
		<div class="thumb">
			<?php if ( has_post_thumbnail( $post->ID ) ) : ?>
				<div class="img-container">
					<a href="<?php the_permalink( $post->ID ) ?>"
					   title="<?php echo esc_attr( get_post_field( 'post_title', $post->ID ) ); ?>">
					   <?php echo get_the_post_thumbnail( $post->ID, 'ed-school-medium-alt', array( 'class' => 'event-thumb' ) ); ?>
				    </a>
				</div>
			<?php endif; ?>
		</div>
	<?php endif ?>
</li>