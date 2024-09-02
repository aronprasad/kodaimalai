<div class="<?php echo esc_attr( $wrapper_class ); ?>">

    <?php if ( $show_image && $has_image ): ?>
        <div class="testimonial_rotator_img img">
            <?php echo get_the_post_thumbnail( $testimonial, $img_size, [ 'class' => 'testimonial-thumb' ] ); ?>
        </div>
    <?php endif; ?>

    <div class="text testimonial_rotator_description">
        <i class="quote-icon fa fa-quote-right"></i>

        <?php if ( ! $hide_title ): ?>
            <?php $title = $testimonial->post_title; ?>
            <div class="testimonial_rotator_slide_title">
                <?php if ( $show_link ): ?>
                    <?php printf( '<a href="%s">%s</a>', esc_url( get_the_permalink( $testimonial ) ), $title ); ?>
                <?php else: ?>
                    <?php echo esc_html( $title ); ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ( ! $hide_stars && $rating = $testimonial->rating ): ?>
            <div class="testimonial_rotator_stars cf-tr">
                <?php for ( $r=1; $r <= $rating; $r++ ): ?>
                    <span class="testimonial_rotator_star testimonial_rotator_star_<?php echo esc_attr( $r ); ?>">
                        <i class="fa fa-star"></i>
                    </span>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

        <?php if ( ! $hide_body ): ?>
            <div class="testimonial_rotator_quote">
                <?php if ( $show_size === 'full' ): ?>
                    <?php echo get_the_content( null, false, $testimonial ); ?>
                <?php else: ?>
                    <?php echo wp_kses_post( $testimonial->post_excerpt ); ?>
                <?php endif; ?>
	        </div>
        <?php endif; ?>

        <?php if ( ! $hide_author && $cite = $testimonial->cite ): ?>
            <div class="testimonial_rotator_author_info cf-tr">
                <?php echo wpautop( wp_kses_post( $cite ) ); ?>
            </div>
        <?php endif; ?>
        
        <?php if ( ! $hide_microdata ): ?>
           <div class="testimonial_rotator_microdata">
                <?php if ( $itemreviewed ): ?>
                    <div class="item"><div class="fn"><?php echo esc_html( $itemreviewed ); ?></div></div>
                <?php endif; ?>
                
                <?php if ( ! $hide_stars && $rating ): ?>
                    <div class="rating"><?php echo esc_html( $rating ); ?>.0</div>
                <?php endif; ?>

               	<div class="dtreviewed"><?php echo esc_html( get_the_date('c', $testimonial ) ); ?></div>
               	<div class="reviewer">
                    <?php if ( ! $hide_author && $cite ): ?>
                        <div class="fn"><?php echo wp_kses_post( wpautop( $cite ) ); ?></div>
                    <?php endif; ?>
                    <?php if ( $has_image ): ?>
                        <?php echo get_the_post_thumbnail( $testimonial, $img_size, [ 'class' => 'photo' ] ); ?>
                    <?php endif; ?>
               	</div>

               	<div class="summary"><?php echo wp_kses_post( wp_trim_words( $testimonial->post_excerpt, 300 ) ); ?></div>
               	<div class="permalink"><?php the_permalink( $testimonial ); ?></div>
           </div>
        <?php endif; ?>

    </div>
</div>
