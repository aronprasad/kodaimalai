<?php

class MSM_Mega_Submenu_Activator {

	/**
	 * @since    1.0.0
	 */
	public static function activate() {
		flush_rewrite_rules();
	}

}
