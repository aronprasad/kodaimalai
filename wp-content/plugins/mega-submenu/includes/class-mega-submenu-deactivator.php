<?php

class MSM_Mega_Submenu_Deactivator {

	/**
	 * @since    1.0.0
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}

}
