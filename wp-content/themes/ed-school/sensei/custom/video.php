<?php 
global $post;

$thumb_id = ed_school_get_rwmb_meta( 'video_thumb', $post->ID );

if ( $thumb_id ) {
	$video_url = false;
	$course_video_embed = get_post_meta( $post->ID, '_course_video_embed', true );

	if ( 'http' == substr( $course_video_embed, 0, 4 ) ) {
		$video_url = $course_video_embed;
	} else {
		// $course_video_embed = wp_oembed_get( esc_url( $course_video_embed ) );
		preg_match( '/src="([^"]+)"/', $course_video_embed, $match );
		if ( is_array( $match ) && isset( $match[1] ) ) {
			$video_url = $match[1];
		}	
	}

	if ( $video_url ) {
		$thumb = wp_get_attachment_image_url( $thumb_id, 'ed-school-square' );
	}

	if ( ! $thumb ) {
		return;
	}

	?>
	<div class="course-video">
		<div class="background-image" style="background-image: url(<?php echo esc_url( $thumb ); ?>)"></div>
		<a href="<?php echo esc_url( $video_url ); ?>" class="video-link" >
			<span class="play"><span class="inner"><i class="fa fa-play"></i></span></span>
		</a>
	</div>
	<?php
	$inline_js = "
	jQuery(function ($) {
        $('.course-video .video-link').magnificPopup({
          type:'iframe',
          mainClass: 'mfp-fade',
          removalDelay: 160,
        });
	});
	";
	wp_add_inline_script( 'jquery.magnific-popup', $inline_js );
} else {
	Sensei_Course::the_course_video();
}
