<?php 

if ( ! function_exists( 'agc_get_course' ) ) {
	return;
}

global $post_id;

$course = agc_get_course( $post_id, false ); 
if ( ! $course )  {
	return;
} 
	
$section           = $course->get_section();
$room              = $course->get_room();
$student_count     = $course->get_student_count();
$created_at        = $course->get_created_at();
$teacher_ids       = $course->get_teacher_ids();

$teachers = array();
if ( is_array( $teacher_ids ) && count( $teacher_ids ) ) {

	$teachers = get_posts( array(
		'post_type'      => 'teacher',
		'posts_per_page' => -1,
		'post__in'       => array_map( 'intval', $teacher_ids ),
		'orderby'        => 'post__in'
	) );
}
$teacher_links = array();
foreach ( $teachers as $teacher ) {
	$teacher_link = get_permalink( $teacher );
	$teacher_links[] = '<span class="name"><a href="' . esc_url( $teacher_link ) . '">' . esc_html( $teacher->post_title ) . '</a></span>';
}
?>
<div class="agc_course one whole wh-padding">
	<?php if ( has_post_thumbnail() ): ?>
		<div class="thumbnail one third small-tablet">
			<a href="<?php the_permalink(); ?>"><?php echo wp_kses_post( ed_school_get_thumbnail( array( 'thumbnail' => 'ed-school-square-small' ) ) ); ?></a>
		</div>
	<?php endif ?>
	<div class="item two thirds small-tablet">
		<h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php if ( $section ): ?>
			<h4><?php echo esc_html( $section ); ?></h4>
		<?php endif ?>

		<div class="teachers">
			<span class="label">
				<?php esc_html_e( 'Teachers', 'ed-school' ); ?>:
			</span>
			<?php echo wp_kses_post( implode( ' ', $teacher_links ) ); ?>
		</div>

		<div class="summary"><?php echo do_shortcode( get_the_excerpt() ); ?></div>

		<ul class="course-meta">
			<?php if ( $room ) : ?>
				<li>
					<p class="label"><?php esc_html_e( 'Room', 'ed-school' ); ?></p>
					<p><?php echo esc_html( $room ); ?></p>
				</li>
			<?php endif; ?>

			<?php if ( $student_count ) : ?>
				<li>
					<p class="label"><?php esc_html_e( 'Students', 'ed-school' ); ?></p>
					<p><?php echo esc_html( $student_count ); ?></p>
				</li>
			<?php endif; ?>
		</ul>

		<div class="links">
			<a class="course-link-btn wh-button" 
				href="<?php the_permalink( $post->ID ); ?>" 
				title="<?php echo esc_attr( $course->get_title() ); ?>">
				<?php esc_html_e( 'See details', 'ed-school' ); ?></a>
				<?php if ( function_exists( 'agc_classroom_link_html' ) ) { agc_classroom_link_html( $course ); } ?>
		</div>
	</div>
</div>
