<?php

$args = array(
	'taxonomy'        => 'agc_course_category',
	'name'            => 'course-category',
	'show_option_all' => 'Category',
	'show_count'      => true,
	'hierarchical'    => true,
);

if ( isset( $_GET['course-category'] ) ) {
	$args['selected'] = $_GET['course-category'];
}
?>
<form action="<?php echo esc_url( get_post_type_archive_link( 'ag_course' ) ); ?>" method="get" id="searchform" class="search-form-wrap search-for-courses">
	<input type="hidden" name="search-type" value="courses"/>
	<ul>
		<li>
			<?php wp_dropdown_categories( $args ); ?>
		</li>
		<li>
			<input type="text" value="<?php if ( ed_school_is_search_courses() ) {
				echo esc_attr( get_search_query() );
			} ?>" name="s" placeholder="<?php esc_html_e( 'Type Keyword', 'ed-school' ); ?>"/>
		</li>
		<li class="search-courses-button-item">
			<button type="submit" class="wh-button"><?php esc_html_e( 'Search', 'ed-school' ); ?></button>
		</li>
	</ul>
</form>
