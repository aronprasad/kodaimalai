<?php

class MSM_Mega_Submenu_Admin {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	public function load_redux_panel() {

		if ( apply_filters( MSM_Mega_Submenu::FILTER_USE_REDUX, true ) ) {
			/**
			 * Redux
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/redux/settings.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/redux/options.php';
		}
	}

	public function add_extensions() {
		/**
		 * Metabox
		 */
		if ( ! msm_is_plugin_activating( 'meta-box/meta-box.php' ) && ! defined( 'RWMB_VER' ) && ! defined( 'RWMB_URL' ) && ! defined( 'RWMB_DIR' ) ) {

			$metabox_file = plugin_dir_path( dirname( __FILE__ ) ) . 'extensions/meta-box/meta-box.php';

			if ( file_exists( $metabox_file ) ) {
				require_once $metabox_file;
			}
		}
	}

	public function register_mobile_navigation() {
		if ( apply_filters( MSM_Mega_Submenu::FILTER_USE_REDUX, true ) ) {
			register_nav_menus( array(
				MSM_Mega_Submenu::NAVIGATION_MOBILE => esc_html__( 'Mammoth Mobile Navigation', $this->plugin_name ),
			) );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		global $pagenow;

		if ( $pagenow == 'nav-menus.php' ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mega-submenu-admin.js', array( 'jquery' ), $this->version, false );
		}

	}

	/**
	 * Enable Visual Composer cf_mega_menu CPT
	 *
	 * @since    1.0.0
	 */
	public function vc_editor_set_post_types() {

		if ( class_exists( 'MSM_VC' ) && is_admin() && function_exists( 'vc_editor_post_types' ) ) {

			$new_post_types = array();

			$post_types = vc_editor_post_types();
			if ( ! in_array( MSM_Mega_Submenu::POST_TYPE, $post_types ) ) {
				$new_post_types[] = MSM_Mega_Submenu::POST_TYPE;
			}

			if ( count( $new_post_types ) ) {
				$post_types = array_merge( $post_types, $new_post_types );
				vc_editor_set_post_types( $post_types );
			}
		} 

		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			add_post_type_support( MSM_Mega_Submenu::POST_TYPE, 'elementor' );
		}

	}

	public function register_mega_menu_post_type() {

		$args = array(
			'labels' => array(
				'name'               => esc_html__( 'Mega Menus', 'mega-submenu' ),
				'singular_name'      => esc_html__( 'Mega Menu', 'mega-submenu' ),
				'menu_name'          => esc_html__( 'Mega Menus', 'mega-submenu' ),
				'all_items'          => esc_html__( 'All Mega Menus', 'mega-submenu' ),
				'add_new_item'       => esc_html__( 'Add New Mega Menu', 'mega-submenu' ),
				'edit_item'          => esc_html__( 'Edit Mega Menu', 'mega-submenu' ),
				'new_item'           => esc_html__( 'New Mega Menu', 'mega-submenu' ),
				'view_item'          => esc_html__( 'View Mega Menu', 'mega-submenu' ),
				'search_items'       => esc_html__( 'Search Mega Menus', 'mega-submenu' ),
				'not_found'          => esc_html__( 'No mega menus found', 'mega-submenu' ),
				'not_found_in_trash' => esc_html__( 'No mega menus found in trash', 'mega-submenu' ),
			),
			'description'   => esc_html__( 'A utility post type used to control contents within mega menu dropdowns', 'mega-submenu' ),
			'public'        => true,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'menu_position' => 30,
			'hierarchical'  => true,
			'supports'      => array(
				'title',
				'editor',
				'revisions',
			)
		);

		register_post_type( MSM_Mega_Submenu::POST_TYPE, $args);
	}

	public function edit_nav_menu_walker( $walker_classname ) {
		include_once( 'class-msm-mega-menu-edit-walker.php' );

		return 'MSM_Mega_Menu_Walker_Nav_Menu_Edit';
	}

	public function save_mega_menu_setting( $menu_id, $menu_item_id ) {

		$mega_menu_id = false;
		if ( ! empty( $_REQUEST['menu-item-mega-menu'] ) && ! empty( $_REQUEST['menu-item-mega-menu'][ $menu_item_id ] ) ) {
			$mega_menu_id = intval( $_REQUEST['menu-item-mega-menu'][ $menu_item_id ] );
		}
		if ( ! empty( $mega_menu_id ) ) {
			update_post_meta( $menu_item_id, MSM_Mega_Submenu::META_ID, $mega_menu_id );
		} else {
			delete_post_meta( $menu_item_id, MSM_Mega_Submenu::META_ID );
		}
	}

	public function add_carrington_build_support( $post_types ) {
		return array_merge( $post_types, array( MSM_Mega_Submenu::POST_TYPE ) );
	}

	public function ajax_get_custom_fields() {

		if ( isset( $_POST['menu_ids'] ) ) {

			$data = array();

			foreach ( $_POST['menu_ids'] as $menu_id ) {
				$data[] = $this->get_menu_item_markup( $menu_id );
			}

			// add one more empty to use for new items
			$data[] = $this->get_menu_item_markup( 'menu-item-0' );

			echo json_encode( $data );
		}
		die( 0 );

	}

	protected function get_menu_item_markup( $menu_id ) {

		$item_id = str_replace( 'menu-item-', '', $menu_id );
		$item_id = (int) $item_id;

		$mega_menu_id = get_post_meta( $item_id, MSM_Mega_Submenu::META_ID, true );
		$markup       = '';
		$markup .= '<p class="menu-item-mega-menu description description-wide">';
		$markup .= "<label for=\"edit-menu-item-mega-menu-{$item_id}\">";
		$markup .= esc_html__( 'Mega Menu:', 'mega-submenu' );

		ob_start();
		wp_dropdown_pages( array(
			'post_type'        => MSM_Mega_Submenu::POST_TYPE,
			'selected'         => $mega_menu_id,
			'show_option_none' => esc_html__( '-- None --', 'mega-submenu' ),
			'name'             => 'menu-item-mega-menu[' . $item_id . ']',
		) );
		$markup .= ob_get_clean();

		$markup .= '<br /><span class="description">';
		$markup .= esc_html__( 'The mega menu to display where mega menus are enabled.', 'mega-submenu' );
		$markup .= '</span>';
		$markup .= '</label>';
		$markup .= '</p>';

		return array(
			'menu_id' => $menu_id,
			'markup'  => $markup
		);
	}

	public function register_vc_addons() {

		if ( ! defined('WPB_VC_VERSION') ) {
			return;
		}

		$addons = apply_filters( MSM_Mega_Submenu::FILTER_VC_ADDONS, array(
			'mega-menu-content-box/addon.php',
			'mega-menu-icon/addon.php',
		) );

		foreach ( $addons as $file ) {
			$filepath = plugin_dir_path( dirname( __FILE__ ) ) . 'vc-addons/' . $file;
			if ( file_exists( $filepath ) ) {
				require_once $filepath;
			}
		}
		unset( $file, $filepath );
	}

}
