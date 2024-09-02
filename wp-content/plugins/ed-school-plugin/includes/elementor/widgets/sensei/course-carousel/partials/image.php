<?php
$img_url = '';
?>
<?php if ( has_post_thumbnail( $post_id ) ) : ?>
    <?php $img_url = get_the_post_thumbnail( $post_id, $image_size, array( 'class' => 'featured-course-thumb') ); ?>
<?php endif; ?>
<?php if ( '' != $img_url ) : ?>
    <div class="img-container">
        <a href="<?php the_permalink( $post_id ) ?>" title="<?php echo esc_attr( get_post_field( 'post_title', $post_id ) ); ?>"><?php echo wp_kses_post( $img_url ); ?></a>
    </div>
<?php else: ?>
	<div class="no-image"></div>
<?php endif; ?>
