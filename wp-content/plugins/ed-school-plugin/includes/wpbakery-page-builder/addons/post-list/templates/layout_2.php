<div class="item <?php echo esc_attr( $grid_class ); ?>">
	<div class="inner-wrap">
		<?php if ( has_post_thumbnail( $post->ID ) ) : ?>
			<div class="img-container">
				<a href="<?php the_permalink( $post->ID ) ?>"
				   title="<?php echo esc_attr( get_post_field( 'post_title', $post->ID ) ); ?>">
				   <?php echo wp_kses_post( get_the_post_thumbnail( $post->ID, $thumbnail_dimensions, array( 'class' => 'post-list-thumb' ) ) ); ?>
			    </a>
			</div>
		<?php endif; ?>
		<div class="data">
			<h3>
				<a title="<?php echo esc_attr( $post->post_title ); ?>"
				   href="<?php the_permalink( $post->ID ); ?>">
					<?php echo esc_html( $post->post_title ); ?>
				</a>
			</h3>

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
				<?php if ( (int) $show_comment_count ): ?>
					<span class="comments">
                        <i class="<?php echo esc_attr( apply_filters( 'ed_school_icon_class', 'comments' ) ); ?>"></i> <?php echo esc_html( absint( $post->comment_count ) ); ?>
                    </span>
				<?php endif; ?>
				<?php if ( (int) $show_author ): ?>
					<span class="author">
                        <i class="<?php echo esc_attr( apply_filters( 'ed_school_icon_class', 'user' ) ); ?>"></i> <?php esc_attr_e( 'by', 'ed-school-plugin' ); ?> <a
							href="<?php echo esc_url( get_author_posts_url( $post->post_author ) ); ?>">
							<?php the_author_meta( 'display_name', $post->post_author ); ?>
						</a>
                    </span>
				<?php endif; ?>
			</div>
			
			<?php if ( $description_word_length ) : ?>
				<div class="content">
					<?php $text = apply_filters( 'widget_text', strip_shortcodes( $post->post_content ) ); ?>
					<p><?php echo wp_kses_post( wp_trim_words( strip_shortcodes( $text ), $description_word_length, '&hellip;' ) ); ?></p>
				</div>
			<?php endif; ?>
			<?php if ( $link_text ) : ?>
				<a class="read-more" href="<?php the_permalink( $post->ID ); ?>"><?php echo esc_html( $link_text ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</div>