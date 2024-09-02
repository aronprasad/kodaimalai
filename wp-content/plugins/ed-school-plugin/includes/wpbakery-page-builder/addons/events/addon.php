<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class Ed_School_Plugin_VC_Addon_Events {

	protected $namespace = 'scp_events';

	function __construct() {
		add_action( vc_is_inline() ? 'init' : 'admin_init', array( $this, 'integrateWithVC' ) );
		add_shortcode( $this->namespace, array( $this, 'render' ) );
	}

	public function integrateWithVC() {

		$events_categories = get_categories( array(
			'taxonomy' => 'tribe_events_cat',
		) );

		$category_arr                    = array();
		$category_arr['Select Category'] = '';
		foreach ( $events_categories as $category ) {
			if ( is_object( $category ) && $category->term_id ) {
				$category_arr[ $category->name ] = $category->term_id;
			}
		}

		vc_map( array(
			'name'        => esc_html__( 'Tribe Events', 'ed-school-plugin' ),
			'description' => '',
			'base'        => $this->namespace,
			'class'       => '',
			'controls'    => 'full',
			'icon'        => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ),
			'category'    => 'Aislin',
			'params'      => array(
				array(
					'type'        => 'textfield',
					'holder'      => '',
					'class'       => '',
					'heading'     => esc_html__( 'Widget Title', 'ed-school-plugin' ),
					'param_name'  => 'title',
					'admin_label' => true,
				),
				array(
					'type'        => 'dropdown',
					'holder'      => '',
					'class'       => '',
					'admin_label' => true,
					'heading'     => esc_html__( 'Category', 'ed-school-plugin' ),
					'param_name'  => 'category_id',
					'value'       => $category_arr,
				),
				array(
					'type'        => 'textfield',
					'holder'      => '',
					'class'       => '',
					'heading'     => esc_html__( 'Start Date Format', 'ed-school-plugin' ),
					'param_name'  => 'start_date_format',
					'admin_label' => true,
					'value'       => 'M d, Y',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Nubmer of events to display', 'ed-school-plugin' ),
					'param_name'  => 'limit',
					'description' => esc_html__( 'Enter number only.', 'ed-school-plugin' ),
				),
				array(
					'type'       => 'dropdown',
					'class'      => '',
					'heading'    => esc_html__( 'Show featured', 'ed-school-plugin' ),
					'param_name' => 'show_featured',
					'value'      => array(
						'No'  => '0',
						'Yes' => '1',
					),
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
						'Layout 4' => 'layout_4',
					),
				),
				array(
					'type'       => 'dropdown',
					'class'      => '',
					'heading'    => esc_html__( 'Show Description', 'ed-school-plugin' ),
					'param_name' => 'show_description',
					'value'      => array(
						'No'  => '0',
						'Yes' => '1',
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Event description word length', 'ed-school-plugin' ),
					'param_name'  => 'description_word_length',
					'description' => esc_html__( 'Enter number only.', 'ed-school-plugin' ),
					'dependency'  => Array( 'element' => 'show_description', 'value' => array( '1' ) ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'View All Events Link Text', 'ed-school-plugin' ),
					'param_name'  => 'view_all_events_link_text',
					'description' => esc_html__( 'If Left Blank link will not show.', 'ed-school-plugin' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'js_composer' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'ed-school-plugin' ),
				),

			)
		) );
	}

	public function render( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'title'                     => '',
			'category_id'               => null,
			'limit'                     => '3',
			'layout'                    => 'layout_1',
			'description_word_length'   => '20',
			'start_date_format'         => '',
			'show_featured'             => '0',
			'show_description'          => '0',
			'view_all_events_link_text' => '',
			'el_class'                  => '',
		), $atts ) );


		if ( ! function_exists( 'tribe_get_events' ) ) {
			return;
		}

		$args = array(
			'eventDisplay'   => 'list',
			'posts_per_page' => $limit,
		);

		if ( (int) $show_featured ) {
			$args['featured'] = true;
		}

		if ( $category_id ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'tribe_events_cat',
					'field'    => 'term_id',
					'terms'    => $category_id
				)
			);
		}

		$posts = tribe_get_events( apply_filters( 'tribe_events_list_widget_query_args',  $args ) );

		// If no posts let's bail
		if ( ! $posts ) {
			return;
		}

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'scp-tribe-events-wrap ' . $el_class, $this->namespace, $atts );

		ob_start();
		if ( $posts ) {
			?>
			<div class="<?php echo esc_attr( $css_class ); ?> <?php echo esc_attr( $layout ); ?>">
				<?php if ( $title ) : ?>
					<h3 class="widget-title">
						<i class="<?php esc_attr( apply_filters( 'ed_school_icon_class', 'calendar' ) ); ?>"></i> <?php echo esc_html( $title ); ?>
					</h3>
				<?php endif; ?>
				<ul class="scp-tribe-events">
					<?php
					foreach ( $posts as $post ) :
						setup_postdata( $post );
						?>
						<?php if ( $layout == 'layout_2' || $layout == 'layout_3' ) : ?>
							<?php // they use the same template, only have diff style ?>
							<?php include "templates/layout_2.php"; ?>
						<?php elseif ( $layout == 'layout_4' ): ?>
							<?php include "templates/layout_4.php"; ?>
						<?php else: ?>
							<?php include 'templates/layout_1.php'; ?>
						<?php endif; ?>
					<?php
					endforeach;
					?>
				</ul>
				<?php if ( ! empty( $view_all_events_link_text ) ) : ?>
					<p class="scp-tribe-events-link">
						<a href="<?php echo esc_url( tribe_get_events_link() ); ?>"
						   rel="bookmark"><?php echo esc_html( $view_all_events_link_text ); ?></a>
					</p>
				<?php endif; ?>
			</div>
			<?php
			// No Events were Found
		} else {
		?>
			<p><?php esc_html_e( 'There are no upcoming events at this time.', 'ed-school-plugin' ); ?></p>
		<?php
		}
		wp_reset_query();
		return ob_get_clean();
	}
}

new Ed_School_Plugin_VC_Addon_Events();
