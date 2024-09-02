<?php

namespace Aislin_Testimonials\Editor\WPBakery\Blocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Aislin_Testimonials\Shortcodes\Shortcodes;
use Aislin_Testimonials\Post_Type;

/**
 * Shorcode Rotator will handle the rendering
 */
class Rotator {

	public function register() {
		add_action( \vc_is_inline() ? 'init' : 'admin_init', [ $this, 'integrate' ] );
	}

	public function integrate() {
		$rotators = get_posts( [
			'post_type'   => Post_Type\Rotator::NAME,
			'numberposts' => -1,
		] );

		$rotators_mapped = [];
		foreach ( $rotators as $rotator ) {
			$rotators_mapped[ $rotator->post_title ] = $rotator->ID;
		}

		vc_map( [
			'name'        => esc_html__( 'Testimonial Rotator', 'aislin-testimonials' ),
			'description' => '',
			'base'        => Shortcodes\Rotator::NAME,
			'class'       => '',
			'controls'    => 'full',
			'icon'        => plugins_url( 'assets/wpbakery/aislin-testimonials-icon.png', __FILE__ ),
			'category'    => esc_html__( 'Aislin', 'aislin-testimonials' ),
			'params'      => [
				[
					'type'       => 'dropdown',
					'holder'     => '',
					'class'      => '',
					'heading'    => esc_html__( 'Rotator', 'aislin-testimonials' ),
					'param_name' => 'id',
					'value'      => $rotators_mapped,
				],
			]
		 ] );
	}


}