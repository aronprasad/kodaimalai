<?php
/**
 * @package WordPress
 * @subpackage Ed School
 */
get_header();
?>
<?php get_template_part( 'templates/title' ); ?>
<div class="<?php echo esc_attr( ed_school_class( 'main-wrapper' ) ) ?>">
	<div class="<?php echo esc_attr( ed_school_class( 'container' ) ) ?>">
		<div class="<?php echo esc_attr( ed_school_class( 'content' ) ) ?>">
			<?php get_template_part( 'templates/content-page' ); ?>
		</div>
		<div class="<?php echo esc_attr( ed_school_class( 'sidebar' ) ) ?>">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>
