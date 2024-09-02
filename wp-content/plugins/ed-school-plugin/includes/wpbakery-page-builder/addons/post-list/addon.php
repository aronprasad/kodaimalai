<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class Ed_School_Plugin_VC_Addon_Post_List {

	protected $namespace = 'linp_post_list';

	function __construct() {
		add_action( vc_is_inline() ? 'init' : 'admin_init', array( $this, 'integrateWithVC' ) );
		add_shortcode( $this->namespace, array( $this, 'render' ) );
	}

	public function integrateWithVC() {

		$thumbnail_sizes = ed_school_plugin_get_thumbnail_sizes_vc();

		$args       = array(
			'orderby' => 'name',
			'parent'  => 0
		);
		$categories = get_categories( $args );
		$cats       = array( 'All' => '' );
		foreach ( $categories as $category ) {
			$cats[ $category->name ] = $category->term_id;
		}

		vc_map( array(
			'name'        => esc_html__( 'Post List', 'ed-school-plugin' ),
			'description' => '',
			'base'        => $this->namespace,
			'class'       => '',
			'controls'    => 'full',
			'icon'        => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ),
			'category'    => esc_html__( 'Aislin', 'ed-school-plugin' ),
			'params'      => array(
				array(
					'type'       => 'dropdown',
					'holder'     => '',
					'class'      => '',
					'heading'    => esc_html__( 'Category', 'ed-school-plugin' ),
					'param_name' => 'category',
					'value'      => $cats,
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Number of Posts', 'ed-school-plugin' ),
					'param_name' => 'number_of_posts',
					'value'      => '2',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Post Date Format', 'ed-school-plugin' ),
					'param_name' => 'post_date_format',
					'value'      => 'F d, Y',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Post description word length', 'ed-school-plugin' ),
					'param_name'  => 'description_word_length',
					'description' => esc_html__( 'Enter number only.', 'ed-school-plugin' ),
					'value'       => '15'
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Link Text', 'ed-school-plugin' ),
					'param_name'  => 'link_text',
					'value'       => 'Read More',
					'description' => esc_html__( 'If you do not wish to display Read More link just leave this field blank.', 'ed-school-plugin' ),
				),
				array(
					'type'       => 'dropdown',
					'class'      => '',
					'heading'    => esc_html__( 'Layout', 'ed-school-plugin' ),
					'param_name' => 'layout',
					'value'      => array(
						'Layout 1' => 'layout_1',
						'Layout 2' => 'layout_2',
						'Layout 3' => 'layout_3',
					),
				),
				array(
					'type'       => 'dropdown',
					'class'      => '',
					'heading'    => esc_html__( 'Number of Columns', 'ed-school-plugin' ),
					'param_name' => 'number_of_columns',
					'value'      => array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
					),
				),
				array(
					'type'       => 'dropdown',
					'class'      => '',
					'heading'    => esc_html__( 'Show Author?', 'ed-school-plugin' ),
					'param_name' => 'show_author',
					'value'      => array(
						'Yes' => '1',
						'No'  => '0',
					),
				),
				array(
					'type'       => 'dropdown',
					'class'      => '',
					'heading'    => esc_html__( 'Show Comment Count?', 'ed-school-plugin' ),
					'param_name' => 'show_comment_count',
					'value'      => array(
						'Yes' => '1',
						'No'  => '0',
					),
				),
				array(
					'type'       => 'dropdown',
					'class'      => '',
					'heading'    => esc_html__( 'Thumbnail Dimensions', 'ed-school-plugin' ),
					'param_name' => 'thumbnail_dimensions',
					'value'      => $thumbnail_sizes,
				),
				array(
					'type'       => 'colorpicker',
					'class'      => '',
					'heading'    => esc_html__( 'Meta Data Color', 'ed-school-plugin' ),
					'param_name' => 'meta_data_color',
					'value'      => '',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'ed-school-plugin' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'ed-school-plugin' ),
				),
			)
		) );
	}

	public function render( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'category'                => null,
			'number_of_posts'         => 2,
			'link_text'               => 'Read More',
			'cat_link_text'           => '',
			'layout'                  => 'layout_1',
			'number_of_columns'       => 1,
			'description_word_length' => '15',
			'thumbnail_dimensions'    => 'thumbnail',
			'post_date_format'        => 'F d, Y',
			'show_comment_count'      => '1',
			'show_author'             => '1',
			'el_class'                => '',
		), $atts ) );

		$args = array(
			'numberposts'      => $number_of_posts,
			'category'         => $category,
			'orderby'          => 'post_date',
			'order'            => 'DESC',
			'suppress_filters' => false,
		);

		$posts = get_posts( $args );

		// If no posts let's bail
		if ( ! $posts ) {
			return;
		}

		$grid = array(
			'one whole',
			'one half',
			'one third',
			'one fourth',
			'one fifth',
			'one sixth',
		);

		$grid_class = $grid[ (int) $number_of_columns - 1 ];

		ob_start();
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'linp-post-list ' . $el_class, $this->namespace, $atts );
		?>
		<div class="<?php echo esc_attr( $css_class ); ?> <?php echo esc_attr( $layout ); ?>">
			<?php foreach ( array_chunk( $posts, $number_of_columns ) as $chunk ): ?>
				<div class="vc_row">
					<?php foreach ( $chunk as $post ): ?>
						<?php if ($layout == 'layout_2' || $layout == 'layout_3') : ?>
							<?php include "templates/{$layout}.php"; ?>
						<?php else: ?>
							<?php include 'templates/layout_1.php'; ?>
						<?php endif; ?>

					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
			<?php if ( $cat_link_text ): ?>
				<?php $category_link = get_category_link( $category ); ?>
				<a class="cbp_widget_link cbp_widget_button"
				   href="<?php echo esc_url( $category_link ); ?>"><?php echo esc_html( $cat_link_text ); ?></a>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}

}

new Ed_School_Plugin_VC_Addon_Post_List();
