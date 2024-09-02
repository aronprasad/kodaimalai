<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
    <div class="<?php echo esc_attr( ed_school_class( 'page-title-row' ) ) ?>">
    	<div class="<?php echo esc_attr( ed_school_class( 'container' ) ) ?>">
    		<div class="<?php echo esc_attr( ed_school_class( 'page-title-grid-wrapper' ) ) ?>">
                <h1 class="<?php echo esc_attr( ed_school_class( 'page-title' ) ) ?>"><?php echo esc_html( ed_school_title() ); ?></h1>
				<?php get_template_part( 'templates/breadcrumbs' ); ?>
    		</div>
    	</div>
    </div>

	<?php if ( is_single() && get_post_type() == 'course' ) : ?>
		<div class="wh-course-custom-sidebar hide-on-desktop">
            <?php get_template_part( 'sensei/custom/video' ); ?>
			<?php get_template_part( 'sensei/custom/purchase-button' ); ?>
		</div>
	<?php endif; ?>
    <div class="<?php echo esc_attr( ed_school_class('main-wrapper') ) ?>">
        <div class="<?php echo esc_attr( ed_school_class('container') ) ?>">
            <div class="<?php echo esc_attr( ed_school_class('content') ) ?>">

            <?php if ( is_single() && get_post_type() == 'course' ) : ?>
                <?php get_template_part( 'sensei/custom/single-course-header-meta' ); ?>
            <?php endif; ?>
