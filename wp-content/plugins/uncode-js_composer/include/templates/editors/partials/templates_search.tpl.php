<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// BEGIN UNCODE EDIT
//     from: <input type="search" id="vc_templates_name_filter" data-vc-templates-name-filter
//     to: <input type="search" id="vc_templates_name_filter"
//     (data-vc-templates-name-filter removed)
//
//     Plus the uncode_search_template_placeholder filter
// END UNCODE EDIT

?>
<div class="vc_ui-panel-header-actions">
	<div class="vc_ui-search-box">
		<div class="vc_ui-search-box-input">
			<input type="search" id="vc_templates_name_filter" placeholder="<?php echo apply_filters( 'uncode_search_template_placeholder', esc_attr__( 'Search template by name', 'js_composer' ) ); ?>">
			<label for="vc_templates_name_filter">
				<i class="vc-composer-icon vc-c-icon-search"></i>
			</label>
		</div>
	</div>
</div>
