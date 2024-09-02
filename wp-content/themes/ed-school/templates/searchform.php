<form role="search" method="get" class="search-form form-inline" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="search" value="<?php if ( is_search() ) { echo esc_attr( get_search_query() ); } ?>" name="s" class="search-field" placeholder="<?php esc_attr_e( 'Search', 'ed-school' ); ?>">
	<label class="hidden"><?php esc_html_e( 'Search for:', 'ed-school' ); ?></label>
	<button type="submit" class="search-submit"><i class="fa fa-caret-right"></i></button>
</form>
