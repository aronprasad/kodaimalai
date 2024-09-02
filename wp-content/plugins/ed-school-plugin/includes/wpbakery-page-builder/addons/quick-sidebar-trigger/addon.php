<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class Ed_School_Plugin_VC_Addon_Quick_Sidebar_Trigger {

	protected $shortcode_name = 'scp_quick_sidebar_trigger';

	function __construct() {
		add_action( vc_is_inline() ? 'init' : 'admin_init', array( $this, 'integrateWithVC' ) );
		add_action( "scp_load_styles_{$this->shortcode_name}", array( $this, 'load_css' ) );

		add_shortcode( $this->shortcode_name, array( $this, 'render' ) );
	}

	public function integrateWithVC() {

		$layout_blocks       = get_posts( array( 'post_type' => 'layout_block' ) );
		$layout_blocks_array = array();
		foreach ( $layout_blocks as $layout_block ) {
			$layout_blocks_array[ $layout_block->post_title ] = $layout_block->ID;
		}

		vc_map( array(
			"name"        => esc_html__( 'Quick Sidebar Trigger', 'ed-school-plugin' ),
			"description" => esc_html__( 'A trigger button for Quick Sidebar', 'ed-school-plugin' ),
			"base"        => $this->shortcode_name,
			"class"       => "",
			"controls"    => "full",
			"icon"        => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ),
			"category"    => 'Aislin',
			"params"      => array(
				array(
					'type'        => 'dropdown',
					'holder'      => '',
					'class'       => '',
					'heading'     => esc_html__( 'Position', 'ed-school-plugin' ),
					'param_name'  => 'position',
					'value'       => array(
						'Left'   => 'vc_pull-left',
						'Right'  => 'vc_pull-right',
						'Center' => 'vc_txt_align_center',
					),
					'description' => esc_html__( 'Float.', 'ed-school-plugin' )
				),
				array(
					'type'        => 'dropdown',
					'holder'      => '',
					'class'       => '',
					'heading'     => esc_html__( 'Quick Sidebar Position', 'ed-school-plugin' ),
					'param_name'  => 'layout_block_position',
					'value'       => array(
						'Right' => 'right',
						'Left'  => 'left',
					),
					'description' => esc_html__( 'Float.', 'ed-school-plugin' ),
					'group'       => esc_html__( 'Quick Sidebar', 'ed-school-plugin' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Layout Block Width', 'ed-school-plugin' ),
					'param_name'  => 'layout_block_width',
					'description' => esc_html__( 'Value in px. Enter number only.', 'ed-school-plugin' ),
					'group'       => esc_html__( 'Quick Sidebar', 'ed-school-plugin' ),
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => esc_html__( 'Bg Color', 'ed-school-plugin' ),
					'param_name' => 'layout_block_background_color',
					'group'      => esc_html__( 'Quick Sidebar', 'ed-school-plugin' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'js_composer' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
				),
				array(
					'type'       => 'css_editor',
					'heading'    => esc_html__( 'CSS box', 'js_composer' ),
					'param_name' => 'css',
					'group'      => esc_html__( 'Design Options', 'js_composer' ),
				),

			)
		) );
	}

	public function load_css( $atts ) {

		$uid = Ed_School_Plugin_Assets::get_uid( $this->shortcode_name, $atts );

		extract( shortcode_atts( array(
			'position'                      => 'right',
			'layout_block_width'            => '350',
			'layout_block_position'         => 'right',
			'layout_block_background_color' => '',
		), $atts ) );

		$style = '';
		/**
		 * Width
		 */
		if ( $layout_block_width ) {
			$layout_block_width = (int) $layout_block_width;
			$style .= "width:{$layout_block_width}px;";
			if ( $layout_block_position == 'left' ) {
				$style .= "left:-{$layout_block_width}px;";
			} else {
				$style .= "right:-{$layout_block_width}px;";
			}
		}

		/**
		 * Background Color
		 */
		if ( $layout_block_background_color ) {
			$style .= "background-color:{$layout_block_background_color};";
		}

		if ( $style ) {
			wp_add_inline_style( 'ed_school_options_style', ".{$uid}{{$style}}" );
		}
	}

	public function render( $atts, $content = null ) {

		$uid = Ed_School_Plugin_Assets::get_uid( $this->shortcode_name, $atts );

		extract( shortcode_atts( array(
			'position'                      => 'right',
			'layout_block_width'            => '350',
			'layout_block_position'         => 'right',
			'layout_block_background_color' => '',
			'css'                           => '',
			'el_class'                      => '',
		), $atts ) );

		$class_to_filter = 'wh-quick-sidebar-toggler-wrapper ' . $position;
		$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' );
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter . ' ' . $el_class, $this->shortcode_name, $atts );

		$layout_block = false;
		if ( function_exists( 'ed_school_get_layout_block' ) ) {
			$layout_block = ed_school_get_layout_block( 'quick-sidebar-layout-block' );
		}
		if ( ! $layout_block ) {
			return;
		}

		$panel_class = "$uid wh-quick-sidebar";

		ob_start();
		?>
		<div class="<?php echo esc_attr( $css_class) ; ?>">
			<a href="#" class="wh-quick-sidebar-toggler">
				<i class="<?php echo esc_attr( apply_filters( 'ed_school_icon_class', 'bars' ) ); ?>"></i>
			</a>
		</div>
		<?php if ( $layout_block->post_content ) : ?>
			<div class="<?php echo esc_attr( $panel_class ); ?>" data-position="<?php echo esc_attr( $layout_block_position ); ?>">
				<span class="wh-close"><i class="<?php echo esc_attr( apply_filters( 'ed_school_icon_class', 'close' ) ); ?>"></i></span>
				<?php echo do_shortcode( $layout_block->post_content ); ?>
			</div>
		<?php endif; ?>
		<?php
		return ob_get_clean();
	}

}

new Ed_School_Plugin_VC_Addon_Quick_Sidebar_Trigger();
