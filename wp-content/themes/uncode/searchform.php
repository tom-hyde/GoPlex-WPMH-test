<?php
	global $use_live_search, $overlay_search;
	$dwls_live_search = get_option('daves-wordpress-live-search_uncode_activate_widget');
	$livesearch_class = $use_live_search == 'yes' ? ' form-xl' : ( ($use_live_search == '' && $dwls_live_search == true) ? '' : ' no-livesearch' );
	if ( $overlay_search === 'yes' ) {
		$livesearch_class = ' no-livesearch';
	}
?>
<form action="<?php echo esc_url(home_url( '/' )); ?>" method="get">
	<div class="search-container-inner">
		<input type="search" class="search-field form-fluid<?php echo esc_attr($livesearch_class); ?>" placeholder="<?php echo esc_html__('Search…','uncode'); ?>" value="" name="s" title="<?php echo esc_html__('Search for:','uncode'); ?>">
	  <i class="fa fa-search3"></i>
	</div>
</form>
