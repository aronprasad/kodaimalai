<?php
if ( ! class_exists( 'Redux' ) ) {
	return;
}

// Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
add_filter( 'redux/options/' . ED_SCHOOL_THEME_OPTION_NAME . '/compiler', 'ed_school_compiler_action', 10, 3 );

function ed_school_compiler_action( $options, $css, $changed_values ) {

	if ( isset( $changed_values['logo'] ) && function_exists( 'ed_school_set_custom_logo_from_theme_options' ) ) {
		// changed array holds previous values
		// we need to use $options
		ed_school_set_custom_logo_from_theme_options( $options['logo'] );
	}

	global $wp_filesystem;
    if ( ! $wp_filesystem ) {
        if ( ! WP_Filesystem() ) {
        	return;
        }
    }

	$upload_dir = wp_upload_dir();

	if ( ! is_writable( $upload_dir['basedir'] ) ) {
		wp_die( esc_html__( "It looks like your upload folder isn't writable, so PHP couldn't make any changes (CHMOD).", 'ed-school' ), esc_html__( 'Cannot write to file', 'ed-school' ), array( 'back_link' => true ) );
	}

	$filename   = $upload_dir['basedir'] . '/' . ED_SCHOOL_THEME_OPTION_NAME . '_style.css';
	$filename   = apply_filters( 'wheels_redux_compiler_filename', $filename );

	$filecontent = "/********* Compiled file/Do not edit *********/\n";
	$filecontent .= $css;

	// Global accent color default
	$option_name = 'global-accent-color';
	if ( isset( $options[ $option_name ] ) && $options[ $option_name ] ) {
		$filecontent .= '#today,';
		$filecontent .= '.tagcloud a,';
		$filecontent .= '#wp-calendar caption{background-color:' . $options[ $option_name ] . '!important;}';
	}

	$option_name = 'global-accent-color-2';
	if ( isset( $options[ $option_name ] ) && $options[ $option_name ] ) {
		$filecontent .= '.widget ul li:before{color:' . $options[ $option_name ] . ';}';
	}

	$accent_colors = array( 'global-accent-color', 'global-accent-color-2' );

	foreach ( $accent_colors as $accent_color ) {

		// Global accent color
		$option_name = $accent_color;
		if ( isset( $options[ $option_name ] ) && $options[ $option_name ] ) {

			$selectors_bg_color          = array();
			$selectors_border_color      = array();
			$selectors_border_top_color  = array();
			$selectors_border_left_color = array();
			$selectors_color             = array();

			$accent_color_elements_option_name = $accent_color . '-elements';
			if ( isset( $options[$accent_color_elements_option_name] ) && count( $options[$accent_color_elements_option_name] ) ) {
				
				$accent_color_elements = array();

				if ( isset( $options[$accent_color_elements_option_name] ) && $options[$accent_color_elements_option_name] ) {
					$accent_color_elements = $options[$accent_color_elements_option_name];
				}

				foreach ( $accent_color_elements as $accent_color_element ) {

					$item = Ed_School_Accent_Colors::get_item( $accent_color_element );
					if ( $item ) {

						if ( $item['id'] === 'child_page_sidebar' ) {

							$filecontent .= '.children-links ul li .children .current_page_item > a, .children-links ul li .children a:hover{';
							$filecontent .= 'background-color:' . $options[ $option_name ] . ';';
							$filecontent .= '}';

							$filecontent .= '.children-links > ul > li > a:hover,';
							$filecontent .= '.children-links ul li.current_page_item > a,';
							$filecontent .= '.children-links ul li.page_item_has_children > a i{';
							$filecontent .= 'color:' . $options[ $option_name ] . ';';
							$filecontent .= '}';

							$filecontent .= '.children-links{';
							$filecontent .= 'border-top-color:' . $options[ $option_name ] . ';';
							$filecontent .= '}';


						} else {


							switch ( $item['type'] ) {
								case 'bg_color':
									$selectors_bg_color[] = $item['selector'];
									break;
								case 'border_color':
									$selectors_border_color[] = $item['selector'];
									break;
								case 'border_top_color':
									$selectors_border_top_color[] = $item['selector'];
									break;
								case 'border_left_color':
									$selectors_border_left_color[] = $item['selector'];
									break;
								case 'color':
									$selectors_color[] = $item['selector'];
									break;
							}
						}

					}
				}
			}

			if ( count( $selectors_bg_color ) ) {
				$filecontent .= implode( ',', $selectors_bg_color );
				$filecontent .= '{background-color:' . $options[ $option_name ] . ';}';
			}
			if ( count( $selectors_border_color ) ) {
				$filecontent .= implode( ',', $selectors_border_color );
				$filecontent .= '{border-color:' . $options[ $option_name ] . ' !important;}';
			}
			if ( count( $selectors_border_top_color ) ) {
				$filecontent .= implode( ',', $selectors_border_top_color );
				$filecontent .= '{border-top-color:' . $options[ $option_name ] . ' !important;}';
			}
			if ( count( $selectors_border_left_color ) ) {
				$filecontent .= implode( ',', $selectors_border_left_color );
				$filecontent .= '{border-left-color:' . $options[ $option_name ] . ' !important;}';
			}
			if ( count( $selectors_color ) ) {
				$filecontent .= implode( ',', $selectors_color );
				$filecontent .= '{color:' . $options[ $option_name ] . ' !important;}';
			}
		}
	}

	// Mega Menu
	$option_name = 'mega-menu-offset-top';
	if ( isset( $options[ $option_name ] ) && $options[ $option_name ] ) {
		$filecontent .= '.msm-menu-item .msm-submenu{top:' . (int) $options[ $option_name ] . 'px}';
	}

	$option_name = 'mega-menu-top-hover-area';
	if ( isset( $options[ $option_name ] ) && $options[ $option_name ] ) {
		$option = (int) $options[ $option_name ];
		$filecontent .= '.msm-menu-item .msm-submenu:before{';
		$filecontent .= 'top:-' . $option . 'px;';
		$filecontent .= 'height:' . $option . 'px;';
		$filecontent .= '}';
	}

	// Font Family from H1
	$option_name = 'headings-typography-h1';
	if ( isset( $options[ $option_name ] ) && isset( $options[ $option_name ]['font-family'] ) ) {
		$option = $options[ $option_name ]['font-family'];
		$filecontent .= '.children-links a,';
		$filecontent .= '.wh-big-icon .vc_tta-title-text,';
		$filecontent .= '.scp-tribe-events .event .info .title,';
		$filecontent .= '.scp-tribe-events .event .date,';
		$filecontent .= '.scp-tribe-events-link a,';
		$filecontent .= '.widget-banner,';
		$filecontent .= '.single-teacher .teacher .teacher-meta-data,';
		$filecontent .= '.single-teacher .teacher .text,';
		$filecontent .= '.vc_tta-title-text,';
		$filecontent .= '.prev-next-item,';
		$filecontent .= '.schedule,';
		$filecontent .= 'blockquote p,';
		$filecontent .= '.linp-post-list .item .meta-data .date';
		$filecontent .= '{';
		$filecontent .= 'font-family:' . $option . ';';
		$filecontent .= '}';
	}

	// Page Title meta data
	$option_name = 'page-title-typography';
	if ( isset( $options[ $option_name ] ) ) {

		if ( isset( $options[ $option_name ]['color'] ) ) {
			$option = $options[ $option_name ]['color'];
			$filecontent .= '.wh-page-title-bar .entry-meta span,';
			// course breadcrumbs
			$filecontent .= '.single-course .breadcrumbs,';
			$filecontent .= '.single-course .trail-items li::after,';
			$filecontent .= '.post-type-archive-course .breadcrumbs,';
			$filecontent .= '.post-type-archive-course .trail-items li::after,';
			$filecontent .= '.single-course .wh-breadcrumbs a,';
			$filecontent .= '.post-type-archive-course .wh-breadcrumbs a,';

			$filecontent .= '.single-lesson .breadcrumbs,';
			$filecontent .= '.single-lesson .trail-items li::after,';
			$filecontent .= '.post-type-archive-lesson .breadcrumbs,';
			$filecontent .= '.post-type-archive-lesson .trail-items li::after,';
			$filecontent .= '.single-lesson .wh-breadcrumbs a,';
			$filecontent .= '.post-type-archive-lesson .wh-breadcrumbs a,';

			$filecontent .= '.page-subtitle';
			$filecontent .= '{';
			$filecontent .= 'color:' . $option . ';';
			$filecontent .= '}';
		}

		if ( isset( $options[ $option_name ]['text-align'] ) && $options[ $option_name ]['text-align'] ) {
			$option = $options[ $option_name ]['text-align'];
			$filecontent .= '.wh-page-title-bar .wh-page-title-wrapper > .entry-meta,';
			$filecontent .= '.page-subtitle';
			$filecontent .= '{';
			$filecontent .= 'text-align:' . $option . ';';
			$filecontent .= '}';
		}
	}

	// Font Family from Main menu
	$option_name = 'menu-main-top-level-typography';
	if ( isset( $options[ $option_name ] ) && isset( $options[ $option_name ]['font-family'] ) ) {
		$option = $options[ $option_name ]['font-family'];
		$filecontent .= '.wh-menu-top a';
		$filecontent .= '{';
		$filecontent .= 'font-family:' . $option . ';';
		$filecontent .= '}';
	}

	// Comment hr color
	$option_name = 'content-hr';
	if ( isset( $options[ $option_name ] ) && isset( $options[ $option_name ]['border-color'] ) ) {
		$filecontent .= '.comment-list .comment hr{border-top-color:' . $options[ $option_name ]['border-color'] . ';}';
	}

	// Elementor container
	$option_name = 'container-width';
	if ( isset( $options[ $option_name ] ) && isset( $options[ $option_name ]['width'] ) ) {

		$main_padding_left = false;
		$main_padding_right = false;

		if ( isset( $options['main-padding'] ) ) {
			if ( isset( $options['main-padding']['padding-left'] ) ) {
				$main_padding_left =  $options['main-padding']['padding-left'];
			}
			if ( isset( $options['main-padding']['padding-right'] ) ) {
				$main_padding_right = $options['main-padding']['padding-right'];
			}
		}


		$filecontent .= '.elementor-section.elementor-section-boxed > .elementor-container{';
		$filecontent .= 'max-width:' . $options[ $option_name ]['width'] . ' !important;';

		if ( $main_padding_left ) {
			$filecontent .= "padding-left:{$main_padding_left};";
		}
		if ( $main_padding_right ) {
			$filecontent .= "padding-right:{$main_padding_right};";
		}
		$filecontent .= '}';

		if ( $main_padding_left || $main_padding_right ) {
			$filecontent .= '.elementor-page .wh-page-title-bar .wh-padding{';
			if ( $main_padding_left ) {
				$filecontent .= "padding-left:{$main_padding_left};";
			}
			if ( $main_padding_right ) {
				$filecontent .= "padding-right:{$main_padding_right};";
			}
			$filecontent .= '}';
		}

	}

	// Sensei Carousel Ribbon Border
	$option_name = 'linp-featured-courses-item-price-bg-color';
	if ( isset( $options[ $option_name ] ) ) {
		$filecontent .= '.linp-featured-courses-carousel .owl-item .price .course-price:before{border-color: ' . $options[ $option_name ] . ' ' . $options[ $option_name ] . ' ' . $options[ $option_name ] . ' transparent;}';
		$filecontent .= '.course-container article.course .course-price:after{border-color: ' . $options[ $option_name ] . ' transparent ' . $options[ $option_name ] . ' ' . $options[ $option_name ] . ';}';
	}
	// Sensei Carousel Ribbon Back Bg Color
	$option_name = 'linp-featured-courses-item-ribbon-back-bg-color';
	if ( isset( $options[ $option_name ] ) ) {
		$filecontent .= '.linp-featured-courses-carousel .owl-item .price .course-price:after{border-color: ' . $options[ $option_name ] . ' transparent transparent' . $options[ $option_name ] . ';}';
		$filecontent .= '.course-container article.course .course-price:before{border-color: ' . $options[ $option_name ] . $options[ $option_name ] . ' transparent transparent;}';
	}
	// Sensei Carousel Item Border Color
	$option_name = 'linp-featured-courses-item-border-color';
	if ( isset( $options[ $option_name ] ) ) {
		$filecontent .= '.linp-featured-courses-carousel .owl-item > div{border:1px solid ' . $options[ $option_name ] . ';}';
		$filecontent .= '.linp-featured-courses-carousel .owl-item .cbp-row{border-top:1px solid ' . $options[ $option_name ] . ';}';
	}

	// Other Settings Vars
	$option_name = 'other-settings-vars';
	if ( isset( $options[ $option_name ] ) ) {
		$scssphp_filepath = WP_PLUGIN_DIR . '/' . ED_SCHOOL_THEME_PLUGIN_NAME .'/extensions/scssphp/scss.inc.php';

		if ( version_compare( phpversion(), '5.3.10', '>=' ) && file_exists( $scssphp_filepath ) && class_exists( 'ScssPhp\ScssPhp\Compiler' ) ) {

			$result = '';

			$buffer = $wp_filesystem->get_contents( get_template_directory() . '/lib/integrations/redux/css/other-settings/vars.scss' );
			$buffer = ed_school_strip_comments( $buffer );
			$lines  = '';
			if ( $buffer ) {
				$lines = explode( ';', $buffer );
			}

			$default_vars = array();
			foreach ( $lines as $line ) {

				$line = explode( ':', $line );
				$key  = isset( $line[0] ) ? trim( str_replace( '$', '', $line[0] ) ) : false;

				if ( $key ) {
					$default_vars[ $key ] = trim( $line[1] );
				}

			}

			// Add theme options settings as vars
			$var_name = 'global-accent-color';
			if ( isset( $options[ $var_name ] ) && $options[ $var_name ] ) {
				$default_vars['global_accent_color'] = $options[ $var_name ];
			}

			require_once $scssphp_filepath;

			try {
				$scss = new ScssPhp\ScssPhp\Compiler();
				$scss->setImportPaths( get_template_directory() . '/lib/integrations/redux/css' );
				// set default variables
				$scss->addVariables( $default_vars );
				$scss->setOutputStyle( ScssPhp\ScssPhp\OutputStyle::COMPRESSED );
				// new line is needed at the end of the string to properly remove single line comments
				// because this is a string and not a file
				$data = ed_school_strip_comments( $options[ $option_name ] . "\n" );
				$data .= '@import "other-settings/main.scss";';
				$result = $scss->compileString( $data )->getCss();

			} catch ( Exception $e ) {

				// if it fails to compile with user settings
				// try with default settings
				try {
					$scss = new ScssPhp\ScssPhp\Compiler();
					$scss->setImportPaths( get_template_directory() . '/lib/integrations/redux/css' );
					$scss->setOutputStyle( ScssPhp\ScssPhp\OutputStyle::COMPRESSED );
					$data = '@import "other-settings/vars.scss";';
					$data .= '@import "other-settings/main.scss";';
					$result = $scss->compileString( $data )->getCss();
				} catch ( Exception $e ) {

				}
			}
			$filecontent .= $result;
		}
	}

	$filecontent = apply_filters( 'ed_school_filter_custom_css_content', $filecontent, $options );
	$wp_filesystem->put_contents( $filename, $filecontent );
}
