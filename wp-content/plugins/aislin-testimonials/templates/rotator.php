<div id="<?php echo esc_attr( $uid ); ?>" class="testimonial_rotator_wrap">
    <div class="cycletwo-slideshow testimonial_rotator template-default hreview-aggregate" <?php echo wp_kses_post( $data_attributes ); ?>>
        <?php foreach ( $testimonials as $testimonial ) : ?>
            <?php $has_image = has_post_thumbnail( $testimonial ) ? 'has-image' : false; ?>
            <?php aislin_testimonials_template( 'testimonial', array_merge( $atts, [
                'wrapper_class' => 'slide testimonial_rotator_slide ' . $has_image,
                'testimonial'   => $testimonial,
                'has_image'     => $has_image,
                'show_link'     => false,
                'show_size'     => 'full',
                'show_image'    => true,
            ] ) ); ?>
            <?php endforeach; ?>
    </div>
    <?php if ( $atts['prevnext'] && count( $testimonials ) ): ?>
        <div class="testimonial_rotator_nav">
            <div class="testimonial_rotator_prev"><i class="fa fa-chevron-left"></i></div>
            <div class="testimonial_rotator_next"><i class="fa fa-chevron-right"></i></div>
        </div>
    <?php endif; ?>
</div>
