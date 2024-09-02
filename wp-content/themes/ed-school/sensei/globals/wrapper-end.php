<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $post;
?>
    </div>
        <div class="<?php echo esc_attr( ed_school_class( 'sidebar' ) ) ?>">
			<?php if ( is_single() && get_post_type() == 'course' ) : ?>
                <div class="wh-course-custom-sidebar hide-on-small-tablet">
				    <?php get_template_part( 'sensei/custom/video' ); ?>
                    <?php get_template_part( 'sensei/custom/purchase-button' ); ?>
				</div>

                <?php if ( $post ): ?>
                    <div class="sidebar-text">
                        <?php echo wp_kses_post( do_shortcode( ed_school_get_rwmb_meta( 'sidebar_text', $post->ID ) ) ); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <?php get_sidebar( 'courses' ); ?>
        </div>
    </div>
</div>
