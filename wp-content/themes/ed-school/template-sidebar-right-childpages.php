<?php
/**
 * @package WordPress
 * @subpackage Ed School
 *
 * Template Name: Sidebar - Right with Child Pages
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
			<?php get_template_part( 'templates/child-pages-sidebar' ); ?>
			<?php get_sidebar( 'child-pages' ); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>
