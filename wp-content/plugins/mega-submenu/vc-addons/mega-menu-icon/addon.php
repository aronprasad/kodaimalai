<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class MSM_Icon {

	protected $namespace = 'msm_icon';

	function __construct() {
		add_action( 'init', array( $this, 'integrateWithVC' ) );
		add_shortcode( $this->namespace, array( $this, 'render' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'loadCssAndJs' ), 9 );

		add_action( "scp_load_styles_{$this->namespace}", array( $this, 'load_css' ) );

		add_filter( 'vc_iconpicker-type-msm-icons', array( $this, 'theme_icons' ) );
	}

	public function integrateWithVC() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		vc_map( array(
			'name'             => esc_html( 'Mega Menu Icon', 'mega-submenu' ),
			'description'      => '',
			'base'             => $this->namespace,
			'class'            => '',
			'controls'         => 'full',
			'icon'             => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ),
			'category'         => esc_html__( 'Mega Menu', 'mega-submenu' ),
			'admin_enqueue_js' => array( plugins_url( 'assets/admin-theme-icon.js', __FILE__ ) ),
			'params'           => array(
				array(
					'type'        => 'iconpicker',
					'param_name'  => 'theme_icon',
					'heading'     => esc_html__( 'Icon', 'mega-submenu' ),
					'value'       => '',
					'class'       => 'msm-icon-name',
					'holder'      => 'div',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'msm-icons',
						'iconsPerPage' => 4000,
					),
					'description' => esc_html__( 'Select icon from library.', 'mega-submenu' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Font Size', 'mega-submenu' ),
					'param_name'  => 'icon_font_size',
					'description' => esc_html__( 'Value in px. Enter number only.', 'mega-submenu' ),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => esc_html__( 'Position Absolute?', 'mega-submenu' ),
					'param_name' => 'position_absolute',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Icon alignment', 'mega-submenu' ),
					'param_name'  => 'alignment',
					'value'       => array(
						esc_html__( 'Left', 'mega-submenu' )   => 'left',
						esc_html__( 'Right', 'mega-submenu' )  => 'right',
						esc_html__( 'Center', 'mega-submenu' ) => 'center',
					),
					'description' => esc_html__( 'Select alignment.', 'mega-submenu' ),
				),
				array(
					'type'        => 'vc_link',
					'heading'     => esc_html__( 'URL (Link)', 'mega-submenu' ),
					'param_name'  => 'link',
					'description' => esc_html__( 'Add link to icon.', 'mega-submenu' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_html__( 'Icon Color', 'mega-submenu' ),
					'param_name'  => 'color',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_html__( 'Icon Hover Color', 'mega-submenu' ),
					'param_name'  => 'hover_color',
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'el_class',
					'heading'     => esc_html__( 'Extra class name', 'mega-submenu' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'mega-submenu' ),
				),
				array(
					'type'       => 'css_editor',
					'heading'    => esc_html__( 'CSS box', 'mega-submenu' ),
					'param_name' => 'css',
					'group'      => esc_html__( 'Design Options', 'mega-submenu' ),
				),
			)
		) );
	}

	public function load_css( $atts ) {

		$uid = MSM_Assets::get_uid( $this->namespace, $atts );

		extract( shortcode_atts( array(
			'icon_font_size'     => '',
			'position_absolute'  => '',
			'alignment'          => 'left',
			'color'              => '',
			'hover_color'        => '',
			'hover_accent_color' => '',
		), $atts ) );

		if ( $hover_accent_color == 'true' && function_exists( 'ed_school_get_option' ) ) {
			$theme_accent_color = ed_school_get_option( 'global-accent-color' );
			if ( $theme_accent_color ) {
				$hover_color = $theme_accent_color;
			}
		}

		$final_style = '';
		$css = '';
		$css_hover = '';

		if ( $icon_font_size ) {
			$css .= 'font-size:' . (int) $icon_font_size . 'px;';
		}

		if ( $position_absolute == 'true' ) {
			$css .= 'position:absolute;';
		}

		if ( $color ) {
			// needs important to be stronger that theme options
			$css .= "color:{$color} !important;";
		}

		if ( $alignment ) {
			if ( $alignment != 'left' ) {
				$css .= "text-align:{$alignment};";
			}
		}

		/**
		 * Hover
		 */
		if ( $hover_color ) {
			$css_hover .= "color:{$hover_color} !important;";
		}

		if ( $css ) {
			$final_style .= ".{$uid}{{$css}}";
		}
		if ( $css_hover ) {
			$final_style .= ".{$uid}:hover{{$css_hover}}";
		}

		if ( $final_style ) {
			wp_add_inline_style( 'mega-submenu', $final_style );
		}
	}

	public function render( $atts, $content = null ) {

		$uid = MSM_Assets::get_uid( $this->namespace, $atts );

		wp_enqueue_style( 'mammoth-icons' );

		extract( shortcode_atts( array(
			'theme_icon'         => 'Text on the button',
			'icon_font_size'     => '',
			'position_absolute'  => '',
			'link'               => '',
			'alignment'          => 'left',
			'color'              => '',
			'hover_color'        => '',
			'hover_accent_color' => '',
			'css'                => '',
			'el_class'           => '',
		), $atts ) );

		$class_to_filter = 'msm-icon';
		$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' );
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter . ' ' . $el_class, $this->namespace, $atts );
		$css_class .= ' ' . $uid;

		$link     = vc_build_link( $link );
		$a_href   = $link['url'];
		$a_title  = $link['title'];
		$a_target = $link['target'];

		ob_start();
		?>

		<?php if ( $a_href ) : ?>
			<a
				href="<?php echo esc_url( $a_href ); ?>"
				class="<?php echo esc_attr( trim( $css_class ) ); ?>"
				<?php if ( $a_title ) : ?>
					title="<?php echo esc_attr( $a_title ); ?>"
				<?php endif; ?>
				<?php if ( $a_target ) : ?>
					target="<?php echo esc_attr( $a_target ); ?>"
				<?php endif; ?>
				><i class="<?php echo esc_attr( $theme_icon ); ?>"></i></a>
		<?php else: ?>
			<div class="<?php echo esc_attr( $css_class ); ?>">
				<i class="<?php echo esc_attr( $theme_icon ); ?>"></i>
			</div>
		<?php endif; ?>

		<?php
		return ob_get_clean();
	}

	public function loadCssAndJs() {
		wp_register_style( 'mammoth-icons', plugins_url( 'assets/mammoth-icons/style.css', __FILE__ ), false );

		if ( is_admin() ) {
			wp_enqueue_style( 'mammoth-icons' );
		}
	}

	function theme_icons( $icons ) {

		$mammoth_icons = array(
			array( 'icon-cursor3' => 'icon-cursor3' ),
			array( 'icon-download2' => 'icon-download2' ),
			array( 'icon-favorite2' => 'icon-favorite2' ),
			array( 'icon-books' => 'icon-books' ),
			array( 'icon-browser' => 'icon-browser' ),
			array( 'icon-chat3' => 'icon-chat3' ),
			array( 'icon-chat-1' => 'icon-chat-1' ),
			array( 'icon-chat-2' => 'icon-chat-2' ),
			array( 'icon-chat-3' => 'icon-chat-3' ),
			array( 'icon-chat-4' => 'icon-chat-4' ),
			array( 'icon-email-1' => 'icon-email-1' ),
			array( 'icon-email-3' => 'icon-email-3' ),
			array( 'icon-ereader' => 'icon-ereader' ),
			array( 'icon-laptop4' => 'icon-laptop4' ),
			array( 'icon-magazine' => 'icon-magazine' ),
			array( 'icon-monitor' => 'icon-monitor' ),
			array( 'icon-morse-code' => 'icon-morse-code' ),
			array( 'icon-newspaper' => 'icon-newspaper' ),
			array( 'icon-speech-bubble2' => 'icon-speech-bubble2' ),
			array( 'icon-television' => 'icon-television' ),
			array( 'icon-twitter' => 'icon-twitter' ),
			array( 'icon-video-call' => 'icon-video-call' ),
			array( 'icon-analytics' => 'icon-analytics' ),
			array( 'icon-audio' => 'icon-audio' ),
			array( 'icon-blogging' => 'icon-blogging' ),
			array( 'icon-browser6' => 'icon-browser6' ),
			array( 'icon-browser-13' => 'icon-browser-13' ),
			array( 'icon-browser-23' => 'icon-browser-23' ),
			array( 'icon-browser-3' => 'icon-browser-3' ),
			array( 'icon-browser-4' => 'icon-browser-4' ),
			array( 'icon-cloud-computing3' => 'icon-cloud-computing3' ),
			array( 'icon-coding3' => 'icon-coding3' ),
			array( 'icon-customer' => 'icon-customer' ),
			array( 'icon-design' => 'icon-design' ),
			array( 'icon-devices2' => 'icon-devices2' ),
			array( 'icon-folder4' => 'icon-folder4' ),
			array( 'icon-folder-1' => 'icon-folder-1' ),
			array( 'icon-idea2' => 'icon-idea2' ),
			array( 'icon-image3' => 'icon-image3' ),
			array( 'icon-keywords' => 'icon-keywords' ),
			array( 'icon-loupe' => 'icon-loupe' ),
			array( 'icon-monitor4' => 'icon-monitor4' ),
			array( 'icon-monitor-1' => 'icon-monitor-1' ),
			array( 'icon-newspaper2' => 'icon-newspaper2' ),
			array( 'icon-online-shop' => 'icon-online-shop' ),
			array( 'icon-quality' => 'icon-quality' ),
			array( 'icon-ranking' => 'icon-ranking' ),
			array( 'icon-search-engine' => 'icon-search-engine' ),
			array( 'icon-sitemap' => 'icon-sitemap' ),
			array( 'icon-speedometer3' => 'icon-speedometer3' ),
			array( 'icon-check' => 'icon-check' ),
			array( 'icon-circle-check' => 'icon-circle-check' ),
			array( 'icon-infinity' => 'icon-infinity' ),
			array( 'icon-task2' => 'icon-task2' ),
			array( 'icon-thumb-up' => 'icon-thumb-up' ),
			array( 'icon-eye2' => 'icon-eye2' ),
			array( 'icon-paper-clip' => 'icon-paper-clip' ),
			array( 'icon-mail3' => 'icon-mail3' ),
			array( 'icon-layout3' => 'icon-layout3' ),
			array( 'icon-bell3' => 'icon-bell3' ),
			array( 'icon-clock4' => 'icon-clock4' ),
			array( 'icon-camera' => 'icon-camera' ),
			array( 'icon-monitor6' => 'icon-monitor6' ),
			array( 'icon-cog2' => 'icon-cog2' ),
			array( 'icon-heart3' => 'icon-heart3' ),
			array( 'icon-circle-plus' => 'icon-circle-plus' ),
			array( 'icon-circle-minus' => 'icon-circle-minus' ),
			array( 'icon-circle-check2' => 'icon-circle-check2' ),
			array( 'icon-circle-cross' => 'icon-circle-cross' ),
			array( 'icon-square-plus' => 'icon-square-plus' ),
			array( 'icon-square-minus' => 'icon-square-minus' ),
			array( 'icon-square-check' => 'icon-square-check' ),
			array( 'icon-square-cross' => 'icon-square-cross' ),
			array( 'icon-upload4' => 'icon-upload4' ),
			array( 'icon-download3' => 'icon-download3' ),
			array( 'icon-box3' => 'icon-box3' ),
			array( 'icon-marquee' => 'icon-marquee' ),
			array( 'icon-marquee-plus' => 'icon-marquee-plus' ),
			array( 'icon-marquee-minus' => 'icon-marquee-minus' ),
		);

		return array_merge( $icons, $mammoth_icons );
	}
}

new MSM_Icon();
