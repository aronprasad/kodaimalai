<?php
add_filter( 'rwmb_meta_boxes', 'msm_register_meta_boxes' );

function msm_register_meta_boxes( $meta_boxes ) {
	$prefix = MSM_PREFIX;

	/**
	 * Mega Menus
	 */
	$meta_boxes[] = array(
		'title'  => 'Settings',
		'pages'  => array( MSM_Mega_Submenu::POST_TYPE ),
		'fields' => array(
			array(
				'id'   => $prefix . 'width',
				'type' => 'text',
				'name' => esc_html__( 'Width', 'mega-submenu' ),
				'desc' => esc_html__( 'Value in px or %.', 'mega-submenu' ),
			),
			array(
				'id'   => $prefix . 'margin',
				'type' => 'text',
				'name' => esc_html__( 'Left/Right Margin', 'mega-submenu' ),
				'desc' => esc_html__( 'Value in px. Enter number only.', 'mega-submenu' ),
			),
			array(
				'id'   => $prefix . 'bg_color',
				'type' => 'color',
				'name' => esc_html__( 'Background Color', 'mega-submenu' ),
			),
			array(
				'id'          => $prefix . 'position',
				'type'        => 'select',
				'name'        => esc_html__( 'Menu Position', 'mega-submenu' ),
				'options'     => array(
					'center'      => esc_html__( 'Center', 'mega-submenu' ),
					'center_full' => esc_html__( 'Center Full', 'mega-submenu' ),
					'left'        => esc_html__( 'Left', 'mega-submenu' ),
					'left_edge'   => esc_html__( 'Left Edge', 'mega-submenu' ),
					'right'       => esc_html__( 'Right', 'mega-submenu' ),
					'right_edge'  => esc_html__( 'Right Edge', 'mega-submenu' ),
				),
			),
			array(
				'id'          => $prefix . 'trigger',
				'type'        => 'select',
				'name'        => esc_html__( 'Trigger', 'mega-submenu' ),
				'options'     => array(
					'hover' => esc_html__( 'Hover', 'mega-submenu' ),
					'click' => esc_html__( 'Click', 'mega-submenu' ),
				),
			),

		)
	);

	return $meta_boxes;
}