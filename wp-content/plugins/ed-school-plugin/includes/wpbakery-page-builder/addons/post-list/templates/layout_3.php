
<div class="item">
	<div class="meta-data">
		<div class="date">
			<?php if ( $post_date_format ) : ?>
				<?php echo wp_kses_post( date_i18n( $post_date_format, strtotime( $post->post_date ) ) ); ?>
			<?php else: ?>
				<div class="month">
					<?php echo wp_kses_post( date_i18n( 'M', strtotime( $post->post_date ) ) ); ?>
				</div>
				<div class="day">
					<?php echo wp_kses_post( date_i18n( 'd', strtotime( $post->post_date ) ) ); ?>
				</div>
			<?php endif; ?>
		</div>
		
	</div>
	<h3>
		<a title="<?php echo esc_attr( $post->post_title ); ?>"
		   href="<?php the_permalink( $post->ID ); ?>">
			<?php echo esc_html( $post->post_title ); ?>
		</a>
	</h3>
</div>
