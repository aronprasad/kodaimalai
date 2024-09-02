<?php
$blog_single_layout       = apply_filters( 'ed_school_filter_single_is_boxed', ed_school_get_option( 'blog-single-layout', 'default' ) );
$blog_single_is_boxed     = $blog_single_layout == 'boxed' || $blog_single_layout == 'boxed-fullwidth';

$blog_single_is_fullwidth = $blog_single_layout == 'fullwidth' 
								|| $blog_single_layout == 'boxed-fullwidth'
								|| ! is_active_sidebar( 'ed-school-sidebar-primary' );

$content_class = $blog_single_is_fullwidth ? 'content-fullwidth' : 'content';
$boxed         = $blog_single_is_boxed ? 'boxed' : null;

get_header( $boxed );
?>
<?php get_template_part('templates/title'); ?>
<div class="<?php echo esc_attr( ed_school_class( 'main-wrapper' ) ) ?>">
	<div class="<?php echo esc_attr( ed_school_class( 'container' ) ) ?>">
		<?php if ( ed_school_get_option( 'single-post-sidebar-left', false ) ): ?>
			<div class="<?php echo esc_attr( ed_school_class( 'sidebar' ) ) ?>">
				<?php get_sidebar(); ?>
			</div>
			<div class="<?php echo esc_attr( ed_school_class( $content_class ) ) ?>">
				<?php get_template_part( 'templates/content-single-teacher', 'course' ); ?>
			</div>
		<?php else: ?>
			<div class="<?php echo esc_attr( ed_school_class( $content_class ) ) ?>">
				<?php get_template_part( 'templates/content-single-teacher', 'course' ); ?>
			</div>
			<div class="<?php echo esc_attr( ed_school_class( 'sidebar' ) ) ?>">
				<?php get_sidebar(); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php get_footer( $boxed ); ?>
