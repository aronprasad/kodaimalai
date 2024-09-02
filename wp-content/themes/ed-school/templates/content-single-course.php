<?php 

if ( ! function_exists( 'agc_get_course' ) ) {
	return;
}

$course = agc_get_course(); 
if ( ! $course )  {
	return;
} 
	
$caledar_api_key   = get_option('ac_calendar_api_key');
$room              = $course->get_room();
$student_count     = $course->get_student_count();
$created_at        = $course->get_created_at();
$updated_at        = $course->get_updated_at();
$link              = $course->get_link();
$calendar_id       = $course->get_calendar_id();
$teacher_ids       = $course->get_teacher_ids();

$teachers = array();
if ( is_array( $teacher_ids ) && count( $teacher_ids ) ) {
	$teachers = get_posts(array(
		'post_type'      => 'teacher',
		'posts_per_page' => -1,
		'post__in'       => array_map('intval', $teacher_ids),
		'orderby'        => 'post__in'
	));
}
?>
<?php while ( have_posts() ) : the_post(); ?>
	<div <?php post_class(); ?>>
		<?php if ( ! ed_school_get_option( 'archive-single-use-page-title', false ) ) : ?>
			<div class="page-title-inner-wrap">
				<?php the_title( '<h1 class="page-title page-title-inner">', '</h1>' ); ?>
				<?php $subtitle = apply_filters( 'post_subtitle', ed_school_get_rwmb_meta( 'subtitle_single_page', get_the_ID() )); ?>
				<?php if ( $subtitle ) : ?>
					<h2 class="page-subtitle page-subtitle-inner"><?php echo esc_html( $subtitle ); ?></h2>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<ul class="teachers">
			<?php foreach ( $teachers as $teacher ): ?>
				<?php $teacher_link = get_permalink( $teacher ); ?>
				<li>
					<a href="<?php echo esc_url( $teacher_link ); ?>"><?php echo wp_kses_post( ed_school_get_thumbnail( array( 
						'post_id' => $teacher->ID, 
						'thumbnail' => 'thumbnail'
						) ) ); ?>
						</a>
					<div class="info">
						<p><?php esc_html_e( 'Teacher', 'ed-school' ); ?></p>
						<h3><a href="<?php echo esc_url( $teacher_link ); ?>"><?php echo esc_html( $teacher->post_title ); ?></a></h3>
					</div>
				</li>
			<?php endforeach ?>
		</ul>
		<ul class="course-meta">
			<?php if ( $created_at ) : ?>
				<li>
					<p class="label"><?php esc_html_e( 'Date created', 'ed-school' ); ?></p>
					<p><?php echo wp_kses_post( date_i18n( get_option( 'date_format' ), strtotime( $created_at ) ) ); ?></p>
				</li>
			<?php endif; ?>

			<?php if ( $updated_at ) : ?>
				<li>
					<p class="label"><?php esc_html_e('Last updated', 'ed-school'); ?></p>
					<p><?php echo wp_kses_post( date_i18n( get_option( 'date_format' ), strtotime( $updated_at ) ) ); ?></p>
				</li>
			<?php endif; ?>

			<?php if ( $student_count ) : ?>
				<li>
					<p class="label"><?php esc_html_e( 'Students', 'ed-school' ); ?></p>
					<p><?php echo esc_html( $student_count ); ?></p>
				</li>
			<?php endif; ?>

			<?php if ( $room ) : ?>
				<li>
					<p class="label"><?php esc_html_e( 'Room', 'ed-school' ); ?></p>
					<p><?php echo esc_html( $room ); ?></p>
				</li>
			<?php endif; ?>
		</ul>
		<div class="thumbnail">
			<?php echo wp_kses_post( ed_school_get_thumbnail( array( 'thumbnail' => 'ed-school-featured-image' ) ) ); ?>
		</div>
		<div class="content">
			<?php the_content(); ?>
		</div>
		<?php if ( function_exists( 'agc_classroom_link_html' ) ) { agc_classroom_link_html( $course ); } ?>
	</div>
<?php endwhile; ?>

<?php if ( $caledar_api_key && $calendar_id ): ?>
	<div id="calendar"></div>
	<?php
		$inline_js = "
			jQuery(document).ready(function($) {
			    $('#calendar').fullCalendar({
			    	header: {
						left: 'prev,next today',
						center: 'title',
						right: 'month,agendaWeek,agendaDay,listWeek'
					},
			        googleCalendarApiKey: '{$caledar_api_key}',
			        events: {
			            googleCalendarId: '{$calendar_id}',
			            className: 'gcal-event'
			        }
			    });
			});
		";
		wp_add_inline_script( 'fullcalendar', $inline_js );
	?>
<?php endif ?>
