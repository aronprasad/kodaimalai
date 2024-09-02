<div class="item <?php echo esc_attr( $grid_class ); ?>">
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
				<?php echo wp_kses_post( wp_trim_words( strip_shortcodes( $text ), $description_word_length, '&hellip;' ) ); ?>
			</div>
		<?php endif; ?>

		<hr>

		<?php $categories_list = get_the_category_list( ', ', '', $post->ID ); ?>

		<?php if ( $categories_list ): ?>
			<div class="categories-links">
			<span><?php echo esc_html__( 'Posted in:', 'ed-school-plugin' ); ?></span> <?php echo wp_kses_post( $categories_list ); ?></div>
		<?php endif ?>

		<?php $tag_list = get_the_tag_list( '',  ', ', '', $post->ID); ?>

		<?php if ( $tag_list ): ?>
			<div class="tags-links"><span><?php echo esc_html__( 'Tags:', 'ed-school-plugin' ); ?></span> <?php echo wp_kses_post( $tag_list ); ?></div>
		<?php endif ?>

		<?php if ( $link_text ) : ?>
			<a class="wh-button hoverable" href="<?php the_permalink( $post->ID ) ?>">
				<div class="anim"></div>
				<?php echo esc_html( $link_text ); ?>
			</a>
		<?php endif; ?>
	</div>
</div>
