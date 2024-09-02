<?php

class Ed_School_Plugin_Elementor_Widget_Instagram extends \Elementor\Widget_Base {

	public function get_name() {
		return 'scp_instagram';
	}

	public function get_title() {
		return __( 'Instagram', 'ed_school_plugin' );
	}

	public function get_icon() {
		return 'eicon-favorite';
	}

	public function get_categories() {
		return [ 'theme-elements' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'ed-school-plugin' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'username',
			[
				'label' => __( 'Username', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
			]
		);

		$this->add_control(
			'number_of_photos',
			[
				'label' => __( 'Number of photos', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number',
				'default' => 6,
			]
		);

		$this->add_control(
			'number_of_columns',
			[
				'label' => __( 'Number of columns', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number',
				'default' => 3,
			]
		);

		$this->add_control(
			'photo_size',
			[
				'label' => __( 'Photo Size', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'thumbnail' => __( 'Thumbnail', 'ed-school-plugin' ),
					'small' => __( 'Small', 'ed-school-plugin' ),
					'large' => __( 'Large', 'ed-school-plugin' ),
				],
				'default' => 'small',
			]
		);

		$this->add_control(
			'target',
			[
				'label' => __( 'Open links in', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'_blank' => __( 'New Window', 'ed-school-plugin' ),
					'_self' => __( 'Current Window', 'ed-school-plugin' ),
				],
				'default' => '_blank',
			]
		);

		$this->add_control(
			'link_text',
			[
				'label' => __( 'Link Text', 'ed-school-plugin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$atts = $this->get_settings_for_display();

		$username = $atts['username'];
		$limit    = $atts['number_of_photos'];
		$size     = $atts['photo_size'];
		$target   = $atts['target'];
		$link     = $atts['link_text'];

		// Taken from WP Instagram Widget
		if ( $username != '' ) {

			$columns = array(
				'1'  => 'one whole',
				'2'  => 'one half',
				'3'  => 'one third',
				'4'  => 'one forth',
				'5'  => 'one fifth',
				'6'  => 'one sixth',
				'7'  => 'one seventh',
				'8'  => 'one eighth',
				'9'  => 'one ninth',
				'10' => 'one tenth',
				'11' => 'one eleventh',
				'12' => 'one twelfth',
			);

			$column_class = isset( $columns[ $atts['number_of_columns'] ] ) ? $columns[ $atts['number_of_columns'] ] : 'one third';
			$media_array = $this->scrape_instagram( $username );

			if ( is_wp_error( $media_array ) ) {
				echo wp_kses_post( $media_array->get_error_message() );
			} else {
				$css_class = 'scp-instagram-pics instagram-size-' . $size;

				// filter for images only?
//				if ( $images_only = apply_filters( 'wpiw_images_only', false ) ) {
//					$media_array = array_filter( $media_array, array( $this, 'images_only' ) );
//				}

				// slice list down to required limit
				$media_array = array_slice( $media_array, 0, $limit );

				// filters for custom classes
				$liclass       = $column_class . ' two-up-small-tablet two-up-mobile';
				$aclass        = '';
				$imgclass      = '';
				$template_part = apply_filters( 'scp_template_part', 'parts/wp-instagram-widget.php' );

				?>
				<ul class="<?php echo esc_attr( $css_class ); ?>"><?php
				foreach ( $media_array as $item ) {
					// copy the else line into a new file (parts/wp-instagram-widget.php) within your theme and customise accordingly
					if ( locate_template( $template_part ) != '' ) {
						include locate_template( $template_part );
					} else {
						echo '<li class="' . esc_attr( $liclass ) . '"><a href="' . esc_url( $item['link'] ) . '" target="' . esc_attr( $target ) . '"  class="' . esc_attr( $aclass ) . '"><img src="' . esc_url( $item[ $size ] ) . '"  alt="' . esc_attr( $item['description'] ) . '" title="' . esc_attr( $item['description'] ) . '"  class="' . esc_attr( $imgclass ) . '"/></a></li>';
					}
				}
				?></ul><?php
			}
		}

		$linkclass = apply_filters( 'wpiw_link_class', 'clear' );

		if ( $link != '' ) {
			?><p class="<?php echo esc_attr( $linkclass ); ?>"><a
				href="<?php echo esc_url( trailingslashit( '//instagram.com/' . esc_attr( trim( $username ) ) ) ); ?>" rel="me"
				target="<?php echo esc_attr( $target ); ?>"><?php echo wp_kses_post( $link ); ?></a></p><?php
		}

	}

	// based on https://gist.github.com/cosmocatalano/4544576
	function scrape_instagram( $username ) {

		$username = strtolower( $username );
		$username = str_replace( '@', '', $username );

		$url = 'http://instagram.com/' . trim( $username );

		if ( false === ( $instagram = get_transient( 'instagram-scp-' . sanitize_title_with_dashes( $username ) ) ) ) {

			$remote = wp_remote_get( $url );

			if ( is_wp_error( $remote ) ) {
				return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'ed-school-plugin' ) );
			}

			if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
				return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'ed-school-plugin' ) );
			}

			$shards      = explode( 'window._sharedData = ', $remote['body'] );
			$insta_json  = explode( ';</script>', $shards[1] );
			$insta_array = json_decode( $insta_json[0], true );

			if ( ! $insta_array ) {
				return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'ed-school-plugin' ) );
			}

			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
			} elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
			} else {
				return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'ed-school-plugin' ) );
			}

			if ( ! is_array( $images ) ) {
				return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'ed-school-plugin' ) );
			}

			$instagram = array();

			foreach ( $images as $image ) {
				if ( true === $image['node']['is_video'] ) {
					$type = 'video';
				} else {
					$type = 'image';
				}

				$caption = __( 'Instagram Image', 'ed-school-plugin' );
				if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
					$caption = wp_kses( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'], array() );
				}

				$instagram[] = array(
					'description' => $caption,
					'link'        => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
					'time'        => $image['node']['taken_at_timestamp'],
					'comments'    => $image['node']['edge_media_to_comment']['count'],
					'likes'       => $image['node']['edge_liked_by']['count'],
					'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
					'small'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
					'large'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
					'original'    => preg_replace( '/^https?\:/i', '', $image['node']['display_url'] ),
					'type'        => $type,
				);
			} // End foreach().

			// do not set an empty transient - should help catch private or empty accounts
			if ( ! empty( $instagram ) ) {
				$instagram = base64_encode( serialize( $instagram ) );
				set_transient( 'instagram-scp-' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'scp_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
			}
		}

		if ( ! empty( $instagram ) ) {

			return unserialize( base64_decode( $instagram ) );

		} else {

			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'ed-school-plugin' ) );

		}
	}

	function images_only( $media_item ) {
		if ( $media_item['type'] == 'image' ) {
			return true;
		}
		return false;
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Ed_School_Plugin_Elementor_Widget_Instagram() );
