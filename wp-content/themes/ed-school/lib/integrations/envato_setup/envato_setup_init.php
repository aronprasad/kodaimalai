<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'envato_setup_logo_image', 'ed_school_envato_setup_logo_image' );
function ed_school_envato_setup_logo_image( $old_image_url ) {
	return get_parent_theme_file_uri( '/lib/integrations/envato_setup/images/aislin.png' );
}

// Will be called from envato_setup.php
function envato_theme_setup_wizard() {

	if ( ! class_exists( 'Envato_Theme_Setup_Wizard' ) ) {
		return;
	}

	class Ed_School_Envato_Theme_Setup_Wizard extends Envato_Theme_Setup_Wizard {

		private static $instance = null;

		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public $site_styles = array(
			'style4' => array(
				'title'            => 'Default (Elementor)',
				'use_layer_slider' => false,
				'plugins'          => array(
					'include' => array(),
					'exclude' => array( 'js_composer' ),
				),
			),
			'style1' => array(
				'title'            => 'Default',
				'use_layer_slider' => false,
				'plugins'          => array(
					'include' => array(),
					'exclude' => array( 'elementor' ),
				),
			),
			'style2' => array(
				'title'            => 'High School',
				'use_layer_slider' => false,
				'plugins'          => array(
					'include' => array(),
					'exclude' => array( 'elementor' ),
				),
			),
			'style3' => array(
				'title'            => 'Prescolaire',
				'use_layer_slider' => false,
				'plugins'          => array(
					'include' => array(),
					'exclude' => array( 'elementor' ),
				),
			),
			'style5' => array(
				'title'            => 'Classes',
				'use_layer_slider' => false,
				'plugins'          => array(
					'exclude' => array( 'js_composer', 'aislin-classroom' ),
					'include' => array(
						array(
							'name'     => 'Sensei LMS',
							'slug'     => 'sensei-lms',
							'required' => false,
						),
					),
				),
			),
		);

		public function filter_plugins( $plugins ) {

			$style_data = $this->get_current_style_data();

			if ( $style_data && isset( $style_data['plugins'] ) ) {
				if ( isset( $style_data['plugins']['exclude'] ) && is_array( $style_data['plugins']['exclude'] ) ) {
					foreach ( $plugins as $index => $plugin ) {
						if ( in_array( $plugin['slug'], $style_data['plugins']['exclude'] ) ) {
							unset( $plugins[ $index ] );
						}
					}
				}
			}
			if ( isset( $style_data['plugins']['include'] ) && is_array( $style_data['plugins']['include'] ) ) {
				$plugins = array_merge($plugins, $style_data['plugins']['include']);
			}

			return $plugins;
		}

	}

	Ed_School_Envato_Theme_Setup_Wizard::get_instance();
}
