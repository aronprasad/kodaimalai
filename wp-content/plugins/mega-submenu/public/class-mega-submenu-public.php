<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themeforest.net/user/aislin/portfolio
 * @since      1.0.0
 *
 * @package    MSM_Mega_Submenu
 * @subpackage MSM_Mega_Submenu/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    MSM_Mega_Submenu
 * @subpackage MSM_Mega_Submenu/public
 * @author     aislin <aislin.themes@gmail.com>
 */
class MSM_Mega_Submenu_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Flagging when we are rendering Mega Menus.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      bool $rendering Rendering flag.
	 */
	protected $rendering = false;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/style.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-woocommerce', plugin_dir_url( __FILE__ ) . 'css/woocommerce.css', array(), $this->version, 'all' );
		if ( apply_filters( MSM_Mega_Submenu::FILTER_LOAD_COMPILED_STYLE, true ) ) {
			msm_add_compiled_style();
		}

		wp_add_inline_style( $this->plugin_name, $this->responsive_menu_scripts() );

		if ( class_exists( 'MSM_VC' ) ) {
			wp_add_inline_style( $this->plugin_name, MSM_VC::print_vc_css( $this->get_menu_location() ) );
			wp_add_inline_style( $this->plugin_name, MSM_VC::print_vc_css( $this->get_menu_location_theme_mobile() ) );
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/msm-main.min.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'msm_mega_submenu', $this->add_global_js_object() );
	}

	/**
	 * Get filtered menu location
	 *
	 * @since    1.0.0
	 */
	public function get_menu_location() {
		return msm_get_menu_location_primary();
	}

	/**
	 * Get filtered custom theme mobile menu location
	 *
	 * @since    1.0.0
	 */
	public function get_menu_location_theme_mobile() {
		return msm_get_menu_location_theme_mobile();
	}

	/**
	 * Filtered WC Templates in Mega Menus
	 *
	 * @since    1.0.0
	 */
	public function filter_wc_get_template( $located, $template_name, $args, $template_path, $default_path ) {

		if ( ! $this->is_rendering() ) {
			return $located;
		}

		$file = plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/woocommerce/' . $template_name;

		if ( file_exists( $file ) ) {
			$located = $file;
		}

		return $located;
	}


	/**
	 * Filtered WC Template Parts in Mega Menus
	 *
	 * @since    1.0.0
	 */
	public function filter_wc_get_template_part( $template, $slug, $name ) {

		if ( ! $this->is_rendering() ) {
			return $template;
		}

		$template = plugin_dir_path( dirname( __FILE__ ) ) . "public/partials/woocommerce/{$slug}-{$name}.php";
		if ( file_exists( $template ) ) {
			return $template;
		}

		return $template;
	}

	/**
	 * Flag mobile menu start.
	 *
	 * @since    1.1.4
	 */
	public function filter_pre_nav_menu( $content, $args ) {

		$mobile_menu_location = $this->get_menu_location_theme_mobile();
		if ( isset( $args->theme_location ) && $args->theme_location == $mobile_menu_location ) {
			msm_mobile_menu_render_start();
		}

		return $content;
	}

	/**
	 * Flag mobile menu end.
	 *
	 * @since    1.1.4
	 */
	public function filter_wp_nav_menu( $nav_menu, $args ) {

		$mobile_menu_location = $this->get_menu_location_theme_mobile();
		if ( isset( $args->theme_location ) && $args->theme_location == $mobile_menu_location ) {
			msm_mobile_menu_render_end();
		}

		return $nav_menu;
	}

	/**
	 * Add custom CSS class to menu item that has mega menu assigned.
	 *
	 * $args     set to null because some themes use this filter and do not provide default number of arguments
	 * @since    1.0.0
	 */
	public function filter_nav_menu_item_css_class( $classes, $item, $args = null ) {

		if ( $item->menu_item_parent == '0' ) {
			$classes[] = MSM_Mega_Submenu::CSS_CLASS_ITEM_TOP_LEVEL;
		}

		$msm_menu_item_class = MSM_Mega_Submenu::CSS_CLASS_ITEM . ' menu-item-has-children';

		$mega_menu_id = get_post_meta( $item->ID, MSM_Mega_Submenu::META_ID, true );
		if ( ! empty( $mega_menu_id ) && ( $mega_menu = get_post( $mega_menu_id ) ) && ! is_wp_error( $mega_menu ) ) {
			// We have a mega menu to display.
			$classes[] = $msm_menu_item_class;

			if ( msm_in_mobile_menu() ) {
				$classes[] = 'msm-mobile';
			} else {
				$trigger = msm_get_rwmb_meta( 'trigger', $mega_menu_id );
				if ( $trigger == 'click' ) {
					$classes[] = 'msm-click';
				} else {
					$classes[] = 'msm-hover';
				}
			}

			$classes = apply_filters( 'msm_filter_menu_item_css_class', $classes, $args ? $args->theme_location : null );
		}

		return $classes;
	}

	public function display_mega_menu_contents( $output, $item, $depth, $args ) {
		$item = (array) $item;
		$args = (array) $args;

		// Both Elementor and WPBakery, when in the front editor, will print the page content on the_content filter.
		// We don't print mega menus in that case.
		if ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) {
			return $output;
		}

		if ( empty( $args['hide_mega_menu'] ) && empty( $item['has_children'] ) ) {
			$mega_menu_id = get_post_meta( $item['ID'], MSM_Mega_Submenu::META_ID, true );
			if ( ! empty( $mega_menu_id ) && ( $mega_menu = get_post( $mega_menu_id ) ) && ! is_wp_error( $mega_menu ) ) {

				$this->begin_render();

				// We have a mega menu to display.
				$wrapper_classes = apply_filters( MSM_Mega_Submenu::FILTER_CSS_CLASSES, MSM_Mega_Submenu::$css_classes, $item, $depth, $args );
				ob_start();

				/**
				 * @since  1.2.6
				 */
				if ( msm_is_built_with_elementor( $mega_menu_id ) ) {
					echo msm_elementor_print_menu( $mega_menu_id );
				} elseif ( class_exists( 'MSM_VC' ) ) {
					if ( class_exists( 'Elementor\Plugin' ) ) {
						\Elementor\Plugin::instance()->frontend->remove_content_filter();
					}

					echo do_shortcode( apply_filters( 'the_content', $mega_menu->post_content ) );
					
					if ( class_exists( 'Elementor\Plugin' ) ) {
						\Elementor\Plugin::instance()->frontend->add_content_filter();
					}
				} else {
					the_content();
				}
				$contents = ob_get_clean();
				wp_reset_postdata();
				if ( ! empty( $contents ) ) {

					$theme_location = isset( $args['theme_location'] ) ? $args['theme_location'] : null;

					$is_mobile_navigation_location = $theme_location && $theme_location == MSM_Mega_Submenu::NAVIGATION_MOBILE;

					/**
					 * @since  1.1.0
					 */
					if ( $is_mobile_navigation_location ) {
						$output .= '<div class="respmenu-submenu-toggle cbp-respmenu-more"><img src="' . MSM_PLUGIN_URL . 'public/img/angle-arrow-down.png"></div>';
						$output .= '<div class="sub-menu">';
					} else {
						$output .= apply_filters( 'msm_filter_submenu_before', '', $theme_location );
					}


					$output .= "<!-- {$mega_menu->post_title} -->";
					$output .= '<div class="' . esc_attr( implode( ' ', $wrapper_classes ) ) . '"';

					$output .= ' data-depth="' . $depth . '"';

					$mega_menu_item_width = msm_get_rwmb_meta( 'width', $mega_menu_id );
					if ( $mega_menu_item_width ) {
						$mega_menu_item_width = strstr( $mega_menu_item_width, '%' ) ? $mega_menu_item_width : (int) $mega_menu_item_width;

						$output .= ' data-width="' . $mega_menu_item_width . '"';
					}

					$mega_menu_item_position = msm_get_rwmb_meta( 'position', $mega_menu_id );
					if ( ! $mega_menu_item_position ) {
						$mega_menu_item_position = 'center';
					}
					$output .= ' data-position="' . $mega_menu_item_position . '"';

					$mega_menu_item_margin = msm_get_rwmb_meta( 'margin', $mega_menu_id );
					if ( $mega_menu_item_margin ) {
						$output .= ' data-margin="' . (int) $mega_menu_item_margin . '"';
					}

					$mega_menu_item_bg_color = msm_get_rwmb_meta( 'bg_color', $mega_menu_id );
					if ( $mega_menu_item_bg_color ) {
						$output .= ' data-bg-color="' . $mega_menu_item_bg_color . '"';
					}

					$output .= ">\n";
					$output .= $contents;
					$output .= "</div>\n";

					/**
					 * @since  1.1.0
					 */
					if ( $is_mobile_navigation_location ) {
						$output .= "</div>\n";
					} else {
						$output .= apply_filters( 'msm_filter_submenu_after', '', $theme_location );
					}

				}

			}
		}

		$this->end_render();

		return $output;
	}

	public function mobile_navigation() {

		if ( ! $this->use_mobile_navigation() ) {
			return;
		}
		include_once 'partials/mobile-navigation.php';
	}

	public function responsive_menu_scripts() {

		$css = '';
		if ( ! $this->use_mobile_navigation() ) {
			return $css;
		}
		$respmenu_show_start = (int) msm_get_option( 'respmenu-show-start', 767 );
		if ( $respmenu_show_start ) {

			$primary_nav_class = MSM_Mega_Submenu::CSS_CLASS_PRIMARY_NAVIGATION;

			$css .= '#msm-mobile-menu {display: none;}';
			$css .= '@media screen and (max-width:' . intval( $respmenu_show_start ) . 'px) {';
			$css .= ".{$primary_nav_class}{display: none;}";
			$css .= '#msm-mobile-menu {display: block;}';
			$css .= '}';
		}
		return $css;
	}

	protected function use_mobile_navigation() {

		$respmenu_use = (bool) msm_get_option( 'respmenu-use', false );

		return apply_filters( MSM_Mega_Submenu::FILTER_USE_MOBILE_NAVIGATION, $respmenu_use );
	}

	public function add_global_js_object() {
		return array(
			'data' => array(
				'submenu_items_position_relative'  => (int) msm_get_option( 'submenu-items-position-relative', false ),
				'mobile_menu_trigger_click_bellow' => (int) msm_get_option( 'mobile-menu-trigger-click-bellow', 768 ),
			)
		);
	}

	public function load_template($template) {
		if ( function_exists( 'msm_elementor_print_menu' ) && is_single() && get_post_type() == MSM_Mega_Submenu::POST_TYPE ) {
			return  plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/single.php';
		}

		return $template;
	}

	protected function begin_render() {
		$this->set_rendering( true );
	}

	protected function end_render() {
		$this->set_rendering( false );
	}

	/**
	 * @return boolean
	 */
	public function is_rendering() {
		return $this->rendering;
	}

	/**
	 * @param boolean $rendering
	 */
	public function set_rendering( $rendering ) {
		$this->rendering = $rendering;
	}

}
