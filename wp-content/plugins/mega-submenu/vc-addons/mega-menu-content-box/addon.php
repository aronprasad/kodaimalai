<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class MSM_Content_Box {

	protected $shortcode_name = 'msm_content_box';

	function __construct() {
		add_action( 'init', array( $this, 'integrateWithVC' ) );
		add_shortcode( $this->shortcode_name, array( $this, 'render' ) );

		add_action( "scp_load_styles_{$this->shortcode_name}", array( $this, 'load_css' ) );
	}

	public function integrateWithVC() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		vc_map( array(
			'name'        => esc_html__( 'Mega Menu Content Box', 'mega-submenu' ),
			'description' => '',
			'base'        => $this->shortcode_name,
			'class'       => '',
			'controls'    => 'full',
			'js_view'     => 'VcColumnView',
			'as_parent'   => array( 'except' => $this->shortcode_name ),
			'icon'        => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ),
			'category'    => esc_html__( 'Mega Menu', 'mega-submenu' ),
			'params'      => array(
				array(
					'type'        => 'vc_link',
					'heading'     => esc_html__( 'URL (Link)', 'mega-submenu' ),
					'param_name'  => 'link',
					'description' => esc_html__( 'Add link.', 'mega-submenu' ),
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'min_height',
					'heading'     => esc_html__( 'Min Height', 'mega-submenu' ),
					'description' => esc_html__( 'Minimal height of the Content Box. Use only if you need specific height. Value in px or %.', 'mega-submenu' ),
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'use_overlay',
					'heading'    => esc_html__( 'Use Overlay', 'mega-submenu' ),
					'value'      => array(
						'No'  => 'no',
						'Yes' => 'yes'
					),
					'group'      => 'Overlay',
				),
				array(
					'type'       => 'textarea',
					'param_name' => 'overlay_title',
					'heading'    => esc_html__( 'Overlay Title', 'mega-submenu' ),
					'dependency' => array( 'element' => 'use_overlay', 'value' => 'yes' ),
					'group'      => 'Overlay',
				),
				array(
					'type'       => 'textarea',
					'param_name' => 'overlay_subtitle',
					'heading'    => esc_html__( 'Overlay Subtitle', 'mega-submenu' ),
					'dependency' => array( 'element' => 'use_overlay', 'value' => 'yes' ),
					'group'      => 'Overlay',
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'el_class',
					'heading'     => esc_html__( 'Extra class name', 'mega-submenu' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'mega-submenu' ),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'submenu_top',
					'heading'    => esc_html__( 'Mega Menu Offset Top', 'mega-submenu' ),
					'value'      => '0',
					'group'      => 'Mega Menu',
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'mega_menu_width',
					'heading'     => esc_html__( 'Override Width', 'mega-submenu' ),
					'value'       => '',
					'description' => esc_html__( 'Value in px or %. If not set it will use default settings for selected Mega Menu.', 'mega-submenu' ),
					'group'       => 'Mega Menu',
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'mega_menu_position',
					'heading'     => esc_html__( 'Override Position', 'mega-submenu' ),
					'value'       => array(
						'Left'        => 'left',
						'Left Edge'   => 'left_edge',
						'Center'      => 'center',
						'Center Full' => 'center_full',
						'Right'       => 'right',
						'Right Edge'  => 'right_edge',
					),
					'group'       => 'Mega Menu',
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'mega_menu_trigger',
					'heading'     => esc_html__( 'Override Trigger', 'mega-submenu' ),
					'value'       => array(
						'Click' => 'click',
						'Hover' => 'hover',
					),
					'description' => esc_html__( 'If not set it will use default settings for selected Mega Menu.', 'mega-submenu' ),
					'group'       => 'Mega Menu',
				),
				array(
					'type'       => 'css_editor',
					'heading'    => esc_html__( 'CSS box', 'mega-submenu' ),
					'param_name' => 'css',
					'group'      => esc_html__( 'Design Options', 'mega-submenu' ),
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => esc_html__( 'Bg Color', 'mega-submenu' ),
					'param_name' => 'custom_background_color',
					'group'      => esc_html__( 'Design Options', 'mega-submenu' ),
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => esc_html__( 'Hover Bg Color', 'mega-submenu' ),
					'param_name' => 'hover_bg_color',
					'group'      => esc_html__( 'Design Options', 'mega-submenu' ),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'box_shadow_top',
					'heading'    => esc_html__( 'Top', 'mega-submenu' ),
					'group'      => esc_html__( 'Box Shadow', 'mega-submenu' ),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'box_shadow_left',
					'heading'    => esc_html__( 'Left', 'mega-submenu' ),
					'group'      => esc_html__( 'Box Shadow', 'mega-submenu' ),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'box_shadow_spread',
					'heading'    => esc_html__( 'Spread', 'mega-submenu' ),
					'group'      => esc_html__( 'Box Shadow', 'mega-submenu' ),
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => esc_html__( 'Box Shadow Color', 'mega-submenu' ),
					'param_name' => 'box_shadow_color',
					'group'      => esc_html__( 'Box Shadow', 'mega-submenu' ),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'box_shadow_top_hover',
					'heading'    => esc_html__( 'Top Hover', 'mega-submenu' ),
					'group'      => esc_html__( 'Box Shadow', 'mega-submenu' ),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'box_shadow_left_hover',
					'heading'    => esc_html__( 'Left Hover', 'mega-submenu' ),
					'group'      => esc_html__( 'Box Shadow', 'mega-submenu' ),
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'box_shadow_spread_hover',
					'heading'    => esc_html__( 'Spread Hover', 'mega-submenu' ),
					'group'      => esc_html__( 'Box Shadow', 'mega-submenu' ),
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => esc_html__( 'Box Shadow Color Hover', 'mega-submenu' ),
					'param_name' => 'box_shadow_color_hover',
					'group'      => esc_html__( 'Box Shadow', 'mega-submenu' ),
				),

			)
		) );
	}

	public function load_css( $atts ) {

		$uid = MSM_Assets::get_uid( $this->shortcode_name, $atts );

		extract( shortcode_atts( array(
			'submenu_top'             => '0',
			'min_height'              => '',
			'custom_background_color' => '', // bg_color name is vc default
			'hover_bg_color'          => '',
			'box_shadow_color'        => '',
			'box_shadow_top'          => '',
			'box_shadow_left'         => '',
			'box_shadow_spread'       => '',
			'box_shadow_color_hover'  => '',
			'box_shadow_top_hover'    => '',
			'box_shadow_left_hover'   => '',
			'box_shadow_spread_hover' => '',
		), $atts ) );

		$style = '';
		$style_hover = '';

		if ( $min_height ) {
			$style .= 'min-height:' . msm_sanitize_size( $min_height ) . ';';
		}

		/**
		 * Custom BG Color
		 */
		if ( $custom_background_color ) {
			$style .= 'background-color:' . $custom_background_color . ';';
		}
		if ( $hover_bg_color ) {
			$style_hover .= 'background-color:' . $hover_bg_color . ';';
		}

		/**
		 * Box Shadow
		 */
		$box_shadow = '';
		if ( $box_shadow_color ) {
			$box_shadow_top    = $box_shadow_top ? (int) $box_shadow_top . 'px' : '0px';
			$box_shadow_left   = $box_shadow_left ? (int) $box_shadow_left . 'px' : '0px';
			$box_shadow_spread = $box_shadow_spread ? (int) $box_shadow_spread . 'px' : '5px';
			$box_shadow        = $box_shadow_top . ' ' . $box_shadow_left . ' ' . $box_shadow_spread . ' ' . $box_shadow_color;

			$style .= 'box-shadow:' . $box_shadow . ';';
		}

		/**
		 * Box Shadow Hover
		 */
		$box_shadow_hover = '';
		if ( $box_shadow_color_hover ) {
			$box_shadow_top_hover    = $box_shadow_top_hover ? (int) $box_shadow_top_hover . 'px' : '0px';
			$box_shadow_left_hover   = $box_shadow_left_hover ? (int) $box_shadow_left_hover . 'px' : '0px';
			$box_shadow_spread_hover = $box_shadow_spread_hover ? (int) $box_shadow_spread_hover . 'px' : '5px';
			$box_shadow_hover        = $box_shadow_top_hover . ' ' . $box_shadow_left_hover . ' ' . $box_shadow_spread_hover . ' ' . $box_shadow_color_hover;
			
			$style_hover .= 'box-shadow:' . $box_shadow_hover . ';';
		}

		
		$final_style = '';
		if ( $style ) {
			$final_style .= ".$uid{{$style}}";
		}
		if ( $style_hover ) {
			$final_style .= ".$uid:hover{{$style_hover}}";
		}
		if ( $submenu_top ) {
			$mega_menu_style = 'top:' . msm_sanitize_size( $submenu_top );
			$final_style .= ".{$uid}>.msm-submenu-container{{$mega_menu_style}}";
		}
		if ( $final_style ) {
			wp_add_inline_style( 'mega-submenu', $final_style );
		}
	}

	public function render( $atts, $content = null ) {

		$uid = MSM_Assets::get_uid( $this->shortcode_name, $atts );

		extract( shortcode_atts( array(
			'mega_menu_width'         => '',
			'mega_menu_position'      => 'left',
			'mega_menu_trigger'       => 'click',
			'submenu_top'             => '0',
			'use_overlay'             => 'no',
			'overlay_title'           => '',
			'overlay_subtitle'        => '',
			'link'                    => '',
			'min_height'              => '',
			'custom_background_color' => '', // bg_color name is vc default
			'hover_bg_color'          => '',
			'box_shadow_color'        => '',
			'box_shadow_top'          => '',
			'box_shadow_left'         => '',
			'box_shadow_spread'       => '',
			'box_shadow_color_hover'  => '',
			'box_shadow_top_hover'    => '',
			'box_shadow_left_hover'   => '',
			'box_shadow_spread_hover' => '',
			'css'                     => '',
			'el_class'                => '',
		), $atts ) );

		$class_to_filter = '';
		$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' );
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter . ' ' . $el_class, $this->shortcode_name, $atts );
		$css_class .= ' ' . $uid;

		$link     = vc_build_link( $link );
		$a_href   = $link['url'];
		$a_title  = $link['title'];
		$a_target = $link['target'];

		$mega_menu_output          = '';
		$content_box_wrapper_class = 'msm-content-box';

		ob_start();
		?>
		<div class="<?php echo esc_attr( $content_box_wrapper_class ); ?>">
			<div class="<?php echo esc_attr( $css_class ); ?>">
				<?php if ( $use_overlay == 'yes' ) : ?>
					<div class="overlay">
						<div class="content">
							<?php if ( $overlay_title ) : ?>
								<div class="title">
									<?php echo wp_kses_post( $overlay_title ); ?>
								</div>
							<?php endif; ?>
							<?php if ( $overlay_subtitle ) : ?>
								<div class="subtitle">
									<?php echo wp_kses_post( $overlay_subtitle ); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
				<?php if ( $a_href ) : ?>
					<a class="wh-content-box-link"
					   href="<?php echo esc_url( $a_href ); ?>"
						<?php if ( $a_title ) : ?>
							title="<?php echo esc_attr( $a_title ); ?>"
						<?php endif; ?>
						<?php if ( $a_target ) : ?>
							target="<?php echo esc_attr( $a_target ); ?>"
						<?php endif; ?>
						></a>
				<?php endif; ?>
				<?php echo do_shortcode( $content ); ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

}

new MSM_Content_Box();

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_msm_content_box extends WPBakeryShortCodesContainer {
	}
}
