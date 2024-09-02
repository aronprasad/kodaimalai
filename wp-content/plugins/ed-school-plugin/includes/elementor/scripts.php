<?php

add_action( 'wp_enqueue_scripts', 'ed_school_plugin_elementor_scripts' );

function ed_school_plugin_elementor_scripts() {

	/**
	 * Countdown
	 */
	wp_register_script( 'jquery-countdown', plugins_url( 'widgets/countdown/jquery.countdown.min.js', __FILE__ ), ['jquery'] );
	wp_register_script( 'elementor-widget-scp-countdown', plugins_url( 'widgets/countdown/countdown.js', __FILE__ ), ['jquery', 'underscore'] );
	wp_register_style( 'elementor-widget-scp-countdown', plugins_url( 'widgets/countdown/countdown.css', __FILE__  ) );

	/**
	 * Course Carousel
	 */
	wp_register_script( 'owl.carousel', plugins_url( 'widgets/sensei/course-carousel/assets/owl.carousel.min.js', __FILE__ ), array( 'jquery' ), false, true );
	wp_register_script( 'sensei-course-carousel', plugins_url( 'widgets/sensei/course-carousel/assets/main.js', __FILE__ ), array( 'jquery' ), false, true );

	wp_register_style( 'owlcarousel', plugins_url( 'widgets/sensei/course-carousel/assets/owl.carousel.css', __FILE__  ) );
	wp_register_style( 'owl-theme', plugins_url( 'widgets/sensei/course-carousel/assets/owl.theme.css', __FILE__  ) );
	wp_register_style( 'owl-transitions', plugins_url( 'widgets/sensei/course-carousel/assets/owl.transitions.css', __FILE__  ) );

	/**
	 * Video Popup
	 */
	wp_register_script( 'jquery.magnific-popup', plugins_url( 'widgets/video-popup/assets/jquery.magnific-popup.min.js', __FILE__ ), ['jquery', 'underscore'] );
	wp_register_script( 'elementor-widget-scp-video-popup', plugins_url( 'widgets/video-popup/assets/video-popup.js', __FILE__ ), ['jquery', 'underscore'] );
	wp_register_style( 'magnific-popup', plugins_url( 'widgets/video-popup/assets/magnific-popup.css', __FILE__  ) );
	
	/**
	 * Main
	 */
	wp_enqueue_style( 'ed-school-plugin-elementor', plugins_url( 'assets/css/main.css', __FILE__  ) );
	wp_enqueue_script( 'jquery-parallax', plugins_url( 'assets/js/jquery-parallax.js', __FILE__  ), ['jquery'], null, true );
}
