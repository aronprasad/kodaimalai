<?php

namespace Aislin_Testimonials\Compatibility;

use Aislin_Testimonials\Action;
use Aislin_Testimonials\Compatibility\Plugins\Testimonial_Rotator\Migrator as Testimonials_Migrator;
use Aislin_Testimonials\Service_Provider as Provider;

class Service_Provider extends Provider {

	public function register() {
		$this->testimonial_rotator_plugin();
	}

	protected function testimonial_rotator_plugin() {
		add_action( 'admin_notices', $this->resolve_callback( Testimonials_Migrator::class, 'admin_notices' ) );
		add_action( 'admin_post_' . Testimonials_Migrator::NAME, $this->resolve_callback( Testimonials_Migrator::class, 'migrate' ) );

		if ( defined( 'TESTIMONIAL_ROTATOR_URI' ) ) {
			return;
		}
		
		add_filter( 'aislin_testimonials/shortcodes', $this->resolve_callback( Plugins\Testimonial_Rotator\Manager::class, 'add_shortcodes' ) );
	}

}
