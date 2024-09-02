<div class="<?php echo esc_attr( ed_school_class( 'top-bar-menu-wrap' ) ) ?>">
	<?php
	$menu_options = array(
		'theme_location'  => 'top_navigation',
		'menu_class'      => esc_attr( ed_school_class( 'top-menu' ) ),
		'container_class' => esc_attr( ed_school_class( 'top-menu-container' ) ),
		'depth'           => 1
	);
	?>
	<?php wp_nav_menu( $menu_options ); ?>
</div>
