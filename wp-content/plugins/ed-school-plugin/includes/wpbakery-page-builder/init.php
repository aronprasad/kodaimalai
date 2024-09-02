<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'WPB_VC_VERSION' ) ) {
	add_action( 'admin_notices', 'ed_school_plugin_vc_not_active_message' );
}

function ed_school_plugin_vc_not_active_message() {
	$plugin_data = get_plugin_data( __FILE__ );
	echo '
    <div class="updated">
      <p>' . sprintf( __( '<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'ed-school-plugin' ), esc_html( $plugin_data['Name'] ) ) . '</p>
    </div>';
}
