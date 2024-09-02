<?php
$mega_menu_id = get_post_meta( $item->ID, MSM_Mega_Submenu::META_ID, true );
?>
<div class="menu-item-mega-menu description description-wide">
	<label for="edit-menu-item-mega-menu-<?php echo esc_attr( $item_id ); ?>">
		<?php esc_html_e( 'Mega Menu:', 'mega-submenu' ); ?>
		<?php
		wp_dropdown_pages( array(
			'post_type' => MSM_Mega_Submenu::POST_TYPE,
			'selected' => $mega_menu_id,
			'show_option_none' => esc_html__( '-- None --', 'mega-submenu' ),
			'name' => 'menu-item-mega-menu[' . $item_id . ']',
		) );
		?><br />
		<span class="description"><?php esc_html_e( 'The mega menu to display where mega menus are enabled.', 'mega-submenu' ); ?></span>
	</label>
</div>