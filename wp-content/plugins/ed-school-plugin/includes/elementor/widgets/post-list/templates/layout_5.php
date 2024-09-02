<?php 
$featured_post = $posts[0];
unset($posts[0]);
?>
<div class="featured">
	<div class="inner-wrap">
		<?php if ( has_post_thumbnail( $featured_post->ID ) ) : ?>
			<div class="img-container">
				<a href="<?php the_permalink( $featured_post->ID ) ?>"
				   title="<?php echo esc_attr( get_post_field( 'post_title', $featured_post->ID ) ); ?>">
				   <?php echo get_the_post_thumbnail( $featured_post->ID, $thumbnail_dimensions, array( 'class' => 'post-list-thumb' ) ); ?>
			    </a>
			</div>
		<?php endif; ?>
		<div class="data">
			<div class="meta-data">
				<?php if ( (int) $show_author ): ?>
					<span class="author">
                        <a
							href="<?php echo esc_url( get_author_posts_url( $featured_post->post_author ) ); ?>">
							<?php the_author_meta( 'display_name', $featured_post->post_author ); ?>
						</a>
                    </span>
				<?php endif; ?>
				<span class="date">
					<?php $post_date_format = $post_date_format ? $post_date_format : 'F d Y'; ?>
					<?php echo wp_kses_post( date_i18n( $post_date_format, strtotime( $featured_post->post_date ) ) ); ?>
				</span>
				<?php if ( (int) $show_comment_count && $featured_post->comment_count ): ?>
					<span class="comments">
	                    <?php esc_html_e('comments:'); ?> <?php echo esc_html( absint( $featured_post->comment_count ) ); ?>
	                </span>
				<?php endif; ?>
			</div>
			<h4>
				<a title="<?php echo esc_attr( $featured_post->post_title ); ?>"
				   href="<?php the_permalink( $featured_post->ID ); ?>">
					<?php echo esc_html( $featured_post->post_title ); ?>
				</a>
			</h4>
			<?php if ( $description_word_length ) : ?>
				<div class="content">
					<?php $text = apply_filters( 'widget_text', strip_shortcodes( $featured_post->post_content ) ); ?>
					<p><?php echo wp_kses_post( wp_trim_words( strip_shortcodes( $text ), $description_word_length, '&hellip;' ) ); ?></p>
				</div>
			<?php endif; ?>
			<?php if ( $link_text ) : ?>
				<a class="read-more" href="<?php the_permalink( $featured_post->ID ); ?>"><?php echo esc_html( $link_text ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</div>
<div class="items">
	<?php foreach ( $posts as $post ): ?>
		<div class="item">
			<?php $has_thumb = has_post_thumbnail( $post->ID ); ?>
			<?php if ( $has_thumb ) : ?>
				<div class="img-container">
					<a href="<?php the_permalink( $post->ID ) ?>"
					   title="<?php echo esc_attr( get_post_field( 'post_title', $post->ID ) ); ?>">
					   <?php echo get_the_post_thumbnail( $post->ID, $thumbnail_dimensions, array( 'class' => 'post-list-thumb' ) ); ?>
				    </a>
				</div>
			<?php endif; ?>
			<div class="data <?php if ( ! $has_thumb ) { echo esc_attr( 'full' ); }; ?>">
				<div class="meta-data">
					<?php if ( $show_author ): ?>
						<span class="author">
		                    <a
								href="<?php echo esc_url( get_author_posts_url( $post->post_author ) ); ?>">
								<?php the_author_meta( 'display_name', $post->post_author ); ?>
							</a>
		                </span>
					<?php endif; ?>
					<span class="date">
						<?php $post_date_format = $post_date_format ? $post_date_format : 'F d Y'; ?>
						<?php echo wp_kses_post( date_i18n( $post_date_format, strtotime( $post->post_date ) ) ); ?>
					</span>
					<?php if ( (int) $show_comment_count && $post->comment_count ): ?>
						<span class="comments">
		                    <?php esc_html_e('comments:'); ?> <?php echo esc_html( absint( $post->comment_count ) ); ?>
		                </span>
					<?php endif; ?>
				</div>
				<h4>
					<a title="<?php echo esc_attr( $post->post_title ); ?>"
					   href="<?php the_permalink( $post->ID ); ?>">
						<?php echo esc_html( $post->post_title ); ?>
					</a>
				</h4>
				<?php if ( $description_word_length ) : ?>
					<div class="content">
						<?php $text = apply_filters( 'widget_text', strip_shortcodes( $post->post_content ) ); ?>
						<p><?php echo wp_kses_post( wp_trim_words( strip_shortcodes( $text ), $description_word_length, '&hellip;' ) ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
