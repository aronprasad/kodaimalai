<?php

if ( ! class_exists( 'Ed_School_Plugin_Post_Type_Teacher' ) ) :

class Ed_School_Plugin_Post_Type_Teacher {

	public function __construct() {

		// Add the teacher post type and taxonomies
		add_action( 'init', array( $this, 'teacher_init' ) );

		// Thumbnail support for teacher posts
		add_theme_support( 'post-thumbnails', array( 'teacher' ) );

		// Add thumbnails to column view
		add_filter( 'manage_edit-teacher_columns', array( $this, 'add_thumbnail_column'), 10, 1 );
		add_action( 'manage_posts_custom_column', array( $this, 'display_thumbnail' ), 10, 1 );

		// Allow filtering of posts by taxonomy in the admin view
		add_action( 'restrict_manage_posts', array( $this, 'add_taxonomy_filters' ) );

		// Show teacher post counts in the dashboard
		add_action( 'right_now_content_table_end', array( $this, 'add_teacher_counts' ) );

		// Add taxonomy terms as body classes
		add_filter( 'body_class', array( $this, 'add_body_classes' ) );

		add_action( 'save_post',  array( $this, 'save_teacher_meta_box' ) );
	}

	/**
	 * Initiate registrations of post type and taxonomies.
	 */
	public function teacher_init() {
		$this->register_post_type();
		$this->register_taxonomy_category();
	}

	/**
	 * Get an array of all taxonomies this plugin handles.
	 *
	 * @return array Taxonomy slugs.
	 */
	protected function get_taxonomies() {
		return array( 'teacher_category', 'teacher_tag' );
	}

	public function add_teacher_meta_boxes ( $post ) {

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        add_meta_box( 'sensei-teacher',  esc_html__( 'Link to User' , 'ed-school-plugin' ),  array( $this , 'teacher_meta_box_content' ),
            'teacher',
            'side',
            'core'
        );

    }

	public function teacher_meta_box_content ( $post ) {

        // get the current author
        $current_author = $post->post_author;

        //get the users authorised to author courses
        $users = $this->get_teachers_and_authors();

    ?>
        <select name="teacher-user">
            <?php foreach ( $users as $user_id ) { ?>
                    <?php $user = get_user_by( 'id', $user_id ); ?>
                    <option <?php selected(  $current_author , $user_id , true ); ?> value="<?php echo esc_attr( $user_id ); ?>" >
                        <?php echo esc_html( $user->display_name ); ?>
                    </option>

            <?php }// end for each ?>

        </select>

        <?php

    }

	public function save_teacher_meta_box ( $teacher_id ){

        // check if this is a post from saving the teacher, if not exit early
        if(! isset( $_POST[ 'teacher-user' ] ) || ! isset( $_POST['post_ID'] )  ){
            return;
        }


        //don't fire this hook again
        remove_action('save_post', array( $this, 'save_teacher_meta_box' ) );

        // get the current post object
        $post = get_post( $teacher_id );

        // get the current teacher/author
        $current_author = absint( $post->post_author );
        $new_author = absint( $_POST[ 'teacher-user' ] );

        // do not do any processing if the selected author is the same as the current author
        if ( $current_author == $new_author ){
            return;
        }

        // save the course  author
		// so user can edit its own teacher page
        $post_updates = array(
            'ID' => $post->ID ,
            'post_author' => $new_author
        );
        wp_update_post( $post_updates );

    } // end save_teacher_meta_box

	public function get_teachers_and_authors ( ){

        $author_query_args = array(
            'blog_id'      => $GLOBALS['blog_id'],
            'fields'       => 'any',
            'who'          => 'authors'
        );

        $authors = get_users( $author_query_args );

        $teacher_query_args = array(
            'blog_id'      => $GLOBALS['blog_id'],
            'fields'       => 'any',
            'role'         => 'teacher',
        );

        $teachers = get_users( $teacher_query_args );

        return  array_unique( array_merge( $teachers, $authors ) );

    }

	/**
	 * Enable the Teacher custom post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	protected function register_post_type() {
		$labels = array(
			'name'               => esc_html__( 'Teachers', 'ed-school-plugin' ),
			'singular_name'      => esc_html__( 'Teacher', 'ed-school-plugin' ),
			'add_new'            => esc_html__( 'Add New Teacher', 'ed-school-plugin' ),
			'add_new_item'       => esc_html__( 'Add New Teacher', 'ed-school-plugin' ),
			'edit_item'          => esc_html__( 'Edit Teacher', 'ed-school-plugin' ),
			'new_item'           => esc_html__( 'Add New Teacher', 'ed-school-plugin' ),
			'view_item'          => esc_html__( 'View Item', 'ed-school-plugin' ),
			'search_items'       => esc_html__( 'Search Teacher', 'ed-school-plugin' ),
			'not_found'          => esc_html__( 'No items found', 'ed-school-plugin' ),
			'not_found_in_trash' => esc_html__( 'No items found in trash', 'ed-school-plugin' ),
		);

		$teachers_page_id = ed_school_plugin_get_theme_option( 'teacher-archive-page' );

		$has_archive = $teachers_page_id && get_post( $teachers_page_id ) 
							? urldecode( get_page_uri( $teachers_page_id ) ) 
							: true;

		$args = array(
			'labels'          => $labels,
			'public'          => true,
			'supports'        => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				// 'comments',
				'author',
				'custom-fields',
				'revisions',
				'page-attributes',
			),
			'capability_type' => 'post',
			'rewrite'         => array( 'slug' => 'teachers' ), // Permalinks format
			'menu_position'   => 5,
			'has_archive'     => $has_archive,
            'menu_icon'		  => 'dashicons-admin-users',
			'register_meta_box_cb' => array( $this, 'add_teacher_meta_boxes'),
		);

		$args = apply_filters( 'teacherposttype_args', $args );

		register_post_type( 'teacher', $args );
	}

	/**
	 * Register a taxonomy for Teacher Tags.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	protected function register_taxonomy_tag() {
		$labels = array(
			'name'                       => esc_html__( 'Teacher Tags', 'ed-school-plugin' ),
			'singular_name'              => esc_html__( 'Teacher Tag', 'ed-school-plugin' ),
			'menu_name'                  => esc_html__( 'Teacher Tags', 'ed-school-plugin' ),
			'edit_item'                  => esc_html__( 'Edit Teacher Tag', 'ed-school-plugin' ),
			'update_item'                => esc_html__( 'Update Teacher Tag', 'ed-school-plugin' ),
			'add_new_item'               => esc_html__( 'Add New Teacher Tag', 'ed-school-plugin' ),
			'new_item_name'              => esc_html__( 'New Teacher Tag Name', 'ed-school-plugin' ),
			'parent_item'                => esc_html__( 'Parent Teacher Tag', 'ed-school-plugin' ),
			'parent_item_colon'          => esc_html__( 'Parent Teacher Tag:', 'ed-school-plugin' ),
			'all_items'                  => esc_html__( 'All Teacher Tags', 'ed-school-plugin' ),
			'search_items'               => esc_html__( 'Search Teacher Tags', 'ed-school-plugin' ),
			'popular_items'              => esc_html__( 'Popular Teacher Tags', 'ed-school-plugin' ),
			'separate_items_with_commas' => esc_html__( 'Separate teacher tags with commas', 'ed-school-plugin' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove teacher tags', 'ed-school-plugin' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used teacher tags', 'ed-school-plugin' ),
			'not_found'                  => esc_html__( 'No teacher tags found.', 'ed-school-plugin' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => false,
			'rewrite'           => array( 'slug' => 'teacher-tag' ),
			'show_admin_column' => true,
			'query_var'         => true,
		);

		$args = apply_filters( 'teacherposttype_tag_args', $args );

		register_taxonomy( 'teacher_tag', array( 'teacher' ), $args );

	}

	/**
	 * Register a taxonomy for Teacher Categories.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	protected function register_taxonomy_category() {
		$labels = array(
			'name'                       => esc_html__( 'Teacher Categories', 'ed-school-plugin' ),
			'singular_name'              => esc_html__( 'Teacher Category', 'ed-school-plugin' ),
			'menu_name'                  => esc_html__( 'Teacher Categories', 'ed-school-plugin' ),
			'edit_item'                  => esc_html__( 'Edit Teacher Category', 'ed-school-plugin' ),
			'update_item'                => esc_html__( 'Update Teacher Category', 'ed-school-plugin' ),
			'add_new_item'               => esc_html__( 'Add New Teacher Category', 'ed-school-plugin' ),
			'new_item_name'              => esc_html__( 'New Teacher Category Name', 'ed-school-plugin' ),
			'parent_item'                => esc_html__( 'Parent Teacher Category', 'ed-school-plugin' ),
			'parent_item_colon'          => esc_html__( 'Parent Teacher Category:', 'ed-school-plugin' ),
			'all_items'                  => esc_html__( 'All Teacher Categories', 'ed-school-plugin' ),
			'search_items'               => esc_html__( 'Search Teacher Categories', 'ed-school-plugin' ),
			'popular_items'              => esc_html__( 'Popular Teacher Categories', 'ed-school-plugin' ),
			'separate_items_with_commas' => esc_html__( 'Separate Teacher categories with commas', 'ed-school-plugin' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Teacher categories', 'ed-school-plugin' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Teacher categories', 'ed-school-plugin' ),
			'not_found'                  => esc_html__( 'No teacher categories found.', 'ed-school-plugin' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'teacher-category' ),
			'show_admin_column' => true,
			'query_var'         => true,
		);

		$args = apply_filters( 'teacherposttype_category_args', $args );

		register_taxonomy( 'teacher_category', array( 'teacher' ), $args );
	}

	/**
	 * Add taxonomy terms as body classes.
	 *
	 * If the taxonomy doesn't exist (has been unregistered), then get_the_terms() returns WP_Error, which is checked
	 * for before adding classes.
	 *
	 * @param array $classes Existing body classes.
	 *
	 * @return array Amended body classes.
	 */
	public function add_body_classes( $classes ) {

		// Only single posts should have the taxonomy body classes
		if ( is_single() ) {
			$taxonomies = $this->get_taxonomies();
			foreach( $taxonomies as $taxonomy ) {
				$terms = get_the_terms( get_the_ID(), $taxonomy );
				if ( $terms && ! is_wp_error( $terms ) ) {
					foreach( $terms as $term ) {
						$classes[] = sanitize_html_class( str_replace( '_', '-', $taxonomy ) . '-' . $term->slug );
					}
				}
			}
		}

		return $classes;
	}

	/**
	 * Add columns to teacher list screen.
	 *
	 * @link http://wptheming.com/2010/07/column-edit-pages/
	 *
	 * @param array $columns Existing columns.
	 *
	 * @return array Amended columns.
	 */
	public function add_thumbnail_column( $columns ) {
		$column_thumbnail = array( 'thumbnail' => esc_html__( 'Thumbnail', 'ed-school-plugin' ) );
		return array_slice( $columns, 0, 2, true ) + $column_thumbnail + array_slice( $columns, 1, null, true );
	}

	/**
	 * Custom column callback
	 *
	 * @global stdClass $post Post object.
	 *
	 * @param string $column Column ID.
	 */
	public function display_thumbnail( $column ) {
		global $post;
		switch ( $column ) {
			case 'thumbnail':
				echo get_the_post_thumbnail( $post->ID, array( 35, 35 ) );
				break;
		}
	}

	/**
	 * Add taxonomy filters to the teacher admin page.
	 *
	 * Code artfully lifted from http://pippinsplugins.com/
	 *
	 * @global string $typenow
	 */
	public function add_taxonomy_filters() {
		global $typenow;

		// An array of all the taxonomies you want to display. Use the taxonomy name or slug
		$taxonomies = $this->get_taxonomies();

		// Must set this to the post type you want the filter(s) displayed on
		if ( 'teacher' != $typenow ) {
			return;
		}

		foreach ( $taxonomies as $tax_slug ) {
			$current_tax_slug = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
			$tax_obj          = get_taxonomy( $tax_slug );
			if ( ! $tax_obj ) {
				return;
			}
			$tax_name         = $tax_obj->labels->name;
			$terms            = get_terms( $tax_slug );
			if ( 0 == count( $terms ) ) {
				return;
			}
			echo '<select name="' . esc_attr( $tax_slug ) . '" id="' . esc_attr( $tax_slug ) . '" class="postform">';
			echo '<option>' . esc_html( $tax_name ) .'</option>';
			foreach ( $terms as $term ) {
				printf(
					'<option value="%s"%s />%s</option>',
					esc_attr( $term->slug ),
					selected( $current_tax_slug, $term->slug ),
					esc_html( $term->name . '(' . $term->count . ')' )
				);
			}
			echo '</select>';
		}
	}

	/**
	 * Add teacher count to "Right Now" dashboard widget.
	 *
	 * @return null Return early if teacher post type does not exist.
	 */
	public function add_teacher_counts() {
		if ( ! post_type_exists( 'teacher' ) ) {
			return;
		}

		$num_posts = wp_count_posts( 'teacher' );

		// Published items
		$href = 'edit.php?post_type=teacher';
		$num  = number_format_i18n( $num_posts->publish );
		$num  = $this->link_if_can_edit_posts( $num, $href );
		$text = _n( 'teacher Item', 'teacher Items', intval( $num_posts->publish ) );
		$text = $this->link_if_can_edit_posts( $text, $href );
		$this->display_dashboard_count( $num, $text );

		if ( 0 == $num_posts->pending ) {
			return;
		}

		// Pending items
		$href = 'edit.php?post_status=pending&amp;post_type=teacher';
		$num  = number_format_i18n( $num_posts->pending );
		$num  = $this->link_if_can_edit_posts( $num, $href );
		$text = _n( 'teacher Item Pending', 'teacher Items Pending', intval( $num_posts->pending ) );
		$text = $this->link_if_can_edit_posts( $text, $href );
		$this->display_dashboard_count( $num, $text );
	}

	/**
	 * Wrap a dashboard number or text value in a link, if the current user can edit posts.
	 *
	 * @param  string $value Value to potentially wrap in a link.
	 * @param  string $href  Link target.
	 *
	 * @return string        Value wrapped in a link if current user can edit posts, or original value otherwise.
	 */
	protected function link_if_can_edit_posts( $value, $href ) {
		if ( current_user_can( 'edit_posts' ) ) {
			return '<a href="' . esc_url( $href ) . '">' . $value . '</a>';
		}
		return $value;
	}

	/**
	 * Display a number and text with table row and cell markup for the dashboard counters.
	 *
	 * @param  string $number Number to display. May be wrapped in a link.
	 * @param  string $label  Text to display. May be wrapped in a link.
	 */
	protected function display_dashboard_count( $number, $label ) {
		?>
		<tr>
			<td class="first b b-teacher"><?php echo esc_html( $number ); ?></td>
			<td class="t teacher"><?php echo esc_html( $label ); ?></td>
		</tr>
		<?php
	}

}

new Ed_School_Plugin_Post_Type_Teacher;

if ( class_exists( 'Redux' ) ) {
	Redux::setSection( 'ed_school_options', array(
		'priority' => 100,
		'id'     => 'section-teachers',
		'title'  => esc_html__( 'Teachers', 'ed-school-plugin' ),
		'icon'   => 'el el-user',
		'fields' => array(
			array(
				'id'    => 'teacher-archive-page',
				'type'  => 'select',
				'title' => esc_html__( 'Teacher Archive Page', 'ed-school-plugin' ),
				'data'  => 'posts',
				'args'  => array( 'post_type' => array( 'page' ), 'posts_per_page' => - 1 ),
			),
		),
	) );
}

endif;
