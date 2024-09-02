<?php

namespace Aislin_Testimonials;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Template Class
 *
 * @package Aislin_Testimonials
 * @since 1.0
 */
class Template {

	protected $template_path = '';

	/**
	 * Setup template path
	 */
	public function __construct( $template_path = '' ) {
		if ( $template_path ) {
			$this->template_path = $template_path;
		} else {
			$this->template_path = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/';
		}
	}

	public function get_template_path( $filename = '' ) {
		return $this->template_path . $filename;
	}

	/**
	 * Render template
	 *
	 * @param  string $template Template uri.
	 * @param  array  $data     Data to pass to the template.
	 */
	public function render( $template, $data = [] ) {
		echo $this->get( $template, $data );
	}

	/**
	 * Get template string
	 *
	 * @param  string $template Template uri.
	 * @param  array  $data     Data to pass to the template.
	 * @return mixed
	 */
	public function get( $template, $data = [] ) {
		return $this->fetch( $template, $data );
	}

	/**
	 * Renders a template and returns the result as a string
	 *
	 * cannot contain template as a key
	 *
	 * throws RuntimeException if $template_path . $template does not exist
	 *
	 * @param string $template
	 * @param array  $data
	 *
	 * @return mixed
	 *
	 * @throws \InvalidArgumentException
	 * @throws \RuntimeException
	 */
	public function fetch( $template, array $data = [] ) {
		$template = "{$template}.php";

		$theme_context               = apply_filters( 'aislin_testimonials_theme_context', 'testimonials' );
		$template_with_theme_context = "$theme_context/$template";

		// Look in child theme and theme first
		$file = '';
		if ( file_exists( STYLESHEETPATH . '/' . $template_with_theme_context )) {
			$file = STYLESHEETPATH . '/' . $template_with_theme_context;
		} elseif ( file_exists( TEMPLATEPATH . '/' . $template_with_theme_context) ) {
			$file = TEMPLATEPATH . '/' . $template_with_theme_context;
		} elseif ( file_exists( ABSPATH . WPINC . '/theme-compat/' . $template_with_theme_context ) ) {
			$file = ABSPATH . WPINC . '/theme-compat/' . $template_with_theme_context;
		// Look in templates folder.
		} elseif ( file_exists( "$this->template_path/$template" ) ) {
			$file = "$this->template_path/$template";
		}

		if ( ! $file ) {
			throw new \RuntimeException( "Template Manager cannot render `$template` because the template does not exist" );
		}
	
		ob_start();
		$this->protected_include_scope( $file, $data );
		$output = ob_get_clean();

		return $output;
	}

	/**
	 * @param string $template
	 * @param array  $data
	 */
	protected function protected_include_scope( $template, array $data ) {
		extract( $data );
		include $template;
	}
}
