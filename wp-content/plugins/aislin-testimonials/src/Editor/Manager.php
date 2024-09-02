<?php

namespace Aislin_Testimonials\Editor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Manager {

	protected $gutenberg_blocks = [];
	protected $wpbakery_blocks = [];

	public function __construct( array $gutenberg_blocks, array $wpbakery_blocks ) {
		$this->gutenberg_blocks = $gutenberg_blocks;
		$this->wpbakery_blocks  = $wpbakery_blocks;
	}

	public function register_gutenberg_blocks() {
		foreach ( $this->gutenberg_blocks as $block ) {
			( new $block )->register();
		}
	}

	public function register_wpbakery_blocks() {
		foreach ( $this->wpbakery_blocks as $block ) {
			( new $block )->register();
		}
	}

}