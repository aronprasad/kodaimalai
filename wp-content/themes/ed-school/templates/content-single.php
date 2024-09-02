<?php while ( have_posts() ) : the_post(); ?>
	<div <?php post_class(); ?>>
		<?php if ( ! ed_school_page_title_enabled() ) : ?>
			<?php the_title( '<h1 class="page-title page-title-inner">', '</h1>' ); ?>
		<?php endif; ?>
		<?php if ( has_post_thumbnail() ): ?>
			<div class="thumbnail">
				<?php echo wp_kses_post( ed_school_get_thumbnail( array( 'thumbnail' => 'ed-school-featured-image' ) ) ); ?>
			</div>
		<?php endif ?>
		<?php if ( ! ed_school_page_title_enabled() ) : ?>
			<?php if ( is_single() ) : ?>
				<?php get_template_part( 'templates/entry-meta' ); ?>
			<?php endif; ?>
		<?php endif; ?>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>

		<?php wp_link_pages( array(
			'before' => '<nav class="page-nav"><p>' . esc_html__( 'Pages:', 'ed-school' ),
			'after'  => '</p></nav>'
		) ); ?>
		<div class="prev-next-item">
			<div class="left-cell">
				<p class="label"><?php esc_html_e( 'Previous', 'ed-school' ) ?></p>
				<?php previous_post_link( '<i class="'. esc_attr( apply_filters( 'ed_school_icon_class', 'previous_post_link' ) ) .'"></i> %link ', '%title', false ); ?>
			</div>
			<div class="right-cell">
				<p class="label"><?php esc_html_e( 'Next', 'ed-school' ) ?></p>
				<?php next_post_link( '%link <i class="'. esc_attr( apply_filters( 'ed_school_icon_class', 'next_post_link' ) ) .'"></i> ', '%title', false ); ?>
			</div>
			<div class="clearfix"></div>
		</div>

		<?php if ( ed_school_get_option( 'archive-single-use-share-this', false ) ): ?>
			<?php do_action( 'social_share_icons' ); ?>
		<?php endif; ?>

		<?php $author_meta = get_the_author_meta( 'description' ); ?>
		<?php if ( $author_meta ) : ?>
			<div class="author-info">
				<div class="author-avatar">
					<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' )) ); ?>">
						<?php echo wp_kses_post( get_avatar( get_the_author_meta( 'ID' ), apply_filters( 'ed_school_author_bio_avatar_size', 90 ) ) ); ?>
					</a>
				</div>
				<div class="author-description">
					<div class="author-tag"><?php echo esc_html__( 'Author', 'ed-school' ); ?></div>
					<h2 class="author-title"><?php echo get_the_author(); ?></h2>
					<p class="author-bio">
						<?php the_author_meta( 'description' ); ?>
					</p>
				</div>
			</div>
		<?php endif; ?>

		<?php comments_template( '/templates/comments.php' ); ?>
	</div>
<?php endwhile; ?>
