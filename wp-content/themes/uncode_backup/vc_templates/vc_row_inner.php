<?php

$el_class = $row_name = $back_image = $back_repeat = $back_attachment = $back_position = $back_size = $back_color = $overlay_color = $overlay_alpha = $overlay_color_blend = $unlock_row = $unlock_row_content = $limit_content = $row_inner_height_percent = $row_height_pixel = $inner_height = $kburns = $parallax = $equal_height = $top_padding = $bottom_padding = $h_padding = $gutter_size = $override_padding = $force_width_grid = $shift_y = $shift_y_fixed = $z_index = $css = $border_color = $border_style = $output = $row_style = $background_div = $row_inline_style = $desktop_visibility = $medium_visibility = $mobile_visibility = $sticky = $enable_bottom_divider = $shape_bottom_invert = $bottom_divider = $bottom_divider_inv = $shape_bottom_color = $shape_bottom_opacity = $shape_bottom_custom = $shape_bottom_index = $shape_bottom_h_use_pixel = $shape_bottom_height = $shape_bottom_height_percent = $shape_bottom_ratio = $shape_bottom_safe = $shape_bottom_responsive = $shape_bottom_tablet_hide = $shape_bottom_mobile_hide = $enable_top_divider = $shape_top_invert = $top_divider = $top_divider_inv = $shape_top_color = $shape_top_opacity = $shape_top_custom = $shape_top_index = $shape_top_h_use_pixel = $shape_top_height = $shape_top_height_percent = $shape_top_ratio = $shape_top_safe = $shape_top_responsive = $shape_top_tablet_hide = $shape_top_mobile_hide = $gdpr_consent_id = $gdpr_consent_logic = '';

extract(shortcode_atts(array(
	'el_class' => '',
	'row_name' => '',
	'back_image' => '',
	'back_repeat' => '',
	'back_attachment' => 'scroll',
	'back_position' => 'center center',
	'back_size' => '',
	'back_color' => '',
	'overlay_color' => '',
	'overlay_alpha' => '',
	'overlay_color_blend' => '',
	'unlock_row' => 'yes',
	'unlock_row_content' => '',
	'limit_content' => '',
	'row_inner_height_percent' => '',
	'row_height_pixel' => '',
	'inner_height' => '',
	'kburns' => '',
	'parallax' => '',
	'equal_height' => '',
	'top_padding' => '3',
	'bottom_padding' => '3',
	'h_padding' => '2',
	'gutter_size' => '',
	'override_padding' => '',
	'force_width_grid' => '',
	'desktop_visibility' => '',
	'medium_visibility' => '',
	'mobile_visibility' => '',
	'shift_y' => '',
	'shift_y_fixed' => '',
	'z_index' => '',
	'sticky' => '',
	'css' => '',
	'border_color' => '',
	'border_style' => '',
	'has_slider' => 'no',
	'enable_bottom_divider' => '',
	'shape_bottom_invert' => '',
	'bottom_divider' => 'curve',
	'bottom_divider_inv' => 'curve',
	'shape_bottom_color' => '',
	'shape_bottom_opacity' => '100',
	'shape_bottom_custom' => '',
	'shape_bottom_index' => '',
	'shape_bottom_h_use_pixel' => '',
	'shape_bottom_height' => '150',
	'shape_bottom_height_percent' => '33',
	'shape_bottom_ratio' => '',
	'shape_bottom_safe' => '',
	'shape_bottom_responsive' => '',
	'shape_bottom_tablet_hide' => '',
	'shape_bottom_mobile_hide' => '',
	'shape_bottom_flip' => '',
	'enable_top_divider' => '',
	'shape_top_invert' => '',
	'top_divider' => 'curve',
	'top_divider_inv' => 'curve',
	'shape_top_color' => '',
	'shape_top_opacity' => '100',
	'shape_top_custom' => '',
	'shape_top_index' => '',
	'shape_top_h_use_pixel' => '',
	'shape_top_height' => '150',
	'shape_top_height_percent' => '33',
	'shape_top_ratio' => '',
	'shape_top_safe' => '',
	'shape_top_responsive' => '',
	'shape_top_tablet_hide' => '',
	'shape_top_mobile_hide' => '',
	'shape_top_flip' => '',
	'gdpr_consent_id' => '',
	'gdpr_consent_logic' => 'include'
) , $atts));

if ( $gdpr_consent_id !== '' && uncode_privacy_check_needed($gdpr_consent_id) ) {
	uncode_privacy_check_needed($gdpr_consent_id);
	if ( $gdpr_consent_logic == 'include' && !uncode_privacy_has_consent($gdpr_consent_id) ) {
		return;
	} elseif ( $gdpr_consent_logic == 'exclude' && uncode_privacy_has_consent($gdpr_consent_id) ) {
		return;
	}
}

global $row_cols_md_counter, $row_cols_sm_counter, $inner_column_style, $front_background_colors, $previous_blend;
$row_cols_md_counter = $row_cols_sm_counter = 0;

$row_classes = array(
	'row'
);
$row_cont_classes = array();
$row_inner_classes = array();

if (strpos($content,'[uncode_slider') !== false || $has_slider === 'yes') {
	$with_slider = true;
} else {
	$with_slider = false;
}

$row_cont_classes[] = $this->getExtraClass($el_class);
if ($sticky === 'yes') {
	$row_cont_classes[] = 'sticky-element';
}

$el_id = '';
if ($row_name !== '') {
	$row_name = ' data-name="' . esc_attr(trim($row_name)) . '"';
}

if (!empty($back_color)) {
	$row_cont_classes[] = 'style-' . $back_color . '-bg';
}

if ( $previous_blend === true ) {
	$row_classes[] = 'row-next-to-blend';
}

/** BEGIN - background construction **/
if (!empty($back_image) || $overlay_color !== '') {

	if ($parallax === 'yes') {
		$back_attachment = '';
		$back_size = 'cover';
	} elseif ($kburns !== '') {
		$back_size = 'cover';
	} else {
		if ($back_size === '') {
			$back_size = 'cover';
		}
	}

	if ($back_repeat === '') {
		$back_repeat = 'no-repeat';
	}

	if ( !empty($back_image) ) {
		$back_array = array(
			'background-image' => $back_image,
			'background-color' => $back_color,
			'background-repeat' => $back_repeat,
			'background-position' => $back_position,
			'background-size' => $back_size,
			'background-attachment' => $back_attachment,
		);
	} else {
		$back_array = array();
	}

	if ( $overlay_color_blend !== '' ) {
		$back_array['mix-blend-mode'] = $overlay_color_blend;
		$previous_blend = true;
	}

	$back_result_array = uncode_get_back_html($back_array, $overlay_color, $overlay_alpha, '', 'row');
	$background_div = $back_result_array['back_html'];

	if ( $background_div == '' ) {

		$back_result_array = uncode_get_back_html($back_array, $overlay_color, $overlay_alpha, '', 'row');
		$background_div = $back_result_array['back_html'];

	}
}

/** END - background construction **/

/** BEGIN - shape dividers **/
$shape_positions = array('bottom', 'top');

foreach ($shape_positions as $shape_position) {
	${'divider_'.$shape_position.'_svg'} = '';
	if ( ${'enable_'.$shape_position.'_divider'} !== '' ) {
		${'shape_'.$shape_position.'_classes'} = $shape_position;

		if ( ${'shape_'.$shape_position.'_h_use_pixel'} == '' ) {
			${'style_shape_'.$shape_position.'_h'} = intval(${'shape_'.$shape_position.'_height'});
			${'style_shape_'.$shape_position.'_unit'} = 'px';
		} else {
			${'style_shape_'.$shape_position.'_h'} = intval(${'shape_'.$shape_position.'_height_percent'});
			${'style_shape_'.$shape_position.'_unit'} = '%';
			${'shape_'.$shape_position.'_safe'} = '';
		}

		${'shape_'.$shape_position.'_classes'} .= ${'shape_'.$shape_position.'_index'} != 0 ? ' z_index_' . ( ${'shape_'.$shape_position.'_index'} + 1 ) : ' z_index_' . ${'shape_'.$shape_position.'_index'};
		${'shape_'.$shape_position.'_classes'} .= ${'shape_'.$shape_position.'_ratio'} == 'yes' ? ' uncode-divider-preserve-ratio' : '';
		${'shape_'.$shape_position.'_classes'} .= ${'shape_'.$shape_position.'_safe'} == 'yes' ? ' uncode-divider-relative' : '';
		${'shape_'.$shape_position.'_classes'} .= ${'shape_'.$shape_position.'_flip'} == 'yes' ? ' uncode-divider-flip' : '';
		${'shape_'.$shape_position.'_classes'} .= ${'shape_'.$shape_position.'_responsive'} == 'yes' && ${'shape_'.$shape_position.'_tablet_hide'} == 'yes' ? ' uncode-divider-tablet-hide' : '';
		${'shape_'.$shape_position.'_classes'} .= ${'shape_'.$shape_position.'_responsive'} == 'yes' && ${'shape_'.$shape_position.'_mobile_hide'} == 'yes' ? ' uncode-divider-mobile-hide' : '';
		${'divider_'.$shape_position.'_opacity'} = ${'shape_'.$shape_position.'_opacity'} != 100 ? ' opacity: ' . intval(${'shape_'.$shape_position.'_opacity'})/100 : '';

		${'preserveAspectRatio_'.$shape_position} = ${'shape_'.$shape_position.'_ratio'} == 'yes' ? 'xMidYMid' : 'none';

		${'shape_'.$shape_position.'_divider_color'} = ${'shape_'.$shape_position.'_color'} !== '' ? $front_background_colors[${'shape_'.$shape_position.'_color'}] : ( ${'enable_'.$shape_position.'_divider'} == 'default' ? '#ffffff' : '' );

		if ( ${'enable_'.$shape_position.'_divider'} == 'default' ) {

			if ( ${'shape_'.$shape_position.'_invert'} != 'yes' ) :

				${'class_shape_'.$shape_position.'_invert'} = '';

				switch (${$shape_position.'_divider'}) {
					case 'arrow':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M132,24L119.988,0L108,23.97V24H132z"/>
		</svg>';
						break;

					case 'book':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,24C96.789,22.854,120,3.417,120,0c0,3.417,23.203,22.854,120,24H0z"/>
		</svg>';
						break;

					case 'city':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M239.941,24L240,17.346h-1.25v1.301h-0.84l-0.02,3.105h-1.681v-2.618h-1.562l0.078-8.623l-3.242-0.02
			l0.02-1.359h-2.91v0.7h-0.488l-0.039,8.521l-2.812-0.021l0.068-7.464l-1.808-0.03l-0.02,1.25l-2.031-0.03v2.308l-1.309-0.03
			l0.02-3.038h-2.725V10.41h-2.871l-0.02,8.312h-1.025l0.02-6.685l-1.816-0.03l0.039-8.763h-3.28l-0.039,8.663l-1.603-0.04
			l-0.547,0.749l-0.879-0.021v1.68h-0.781v4.099h-0.742l-0.02,2.577h-1.094v-2.809h-1.309l0.02-1.229h-1.27v1.069l-4.98-0.021v0.971
			l-1.494-0.021v1.607H192.9l0.02-3.026l-1.377-0.039v-3.247h-0.488l0.039-4.907l-3.086-0.01l-0.039,8.073h-0.43l-0.02,2.068h-0.47
			v0.84h-1.172l0.039-5.864h-0.293v-2.216h-0.508V10.26l-4.043-0.05l-0.039,7.204h-0.693l0.021-4.865h-0.519v-0.87h-1.514v0.939
			h-0.431l-0.02,5.457h-0.742v1.769h-0.391l0.049-6.404h-0.801v-1.239l-1.699-0.011v-0.989h-1.426v-1.039l-2.031-0.02v0.529h-0.918
			v0.899l-2.09-0.02v1.378l-1.035-0.029V10.76h-0.645v-0.64h-1.445l0.039-1.039h-2.09v1.029h-0.928V11.1l-0.918-0.03l0.02-1.999
			h-2.666l0.039-0.899l-2.021-0.02v1.319l-1.865-0.05l0.021-5.496l-1.758-0.02V3.038l-2.091-0.03h-1.582v1.829l-0.801-0.04
			l-0.039,9.483h-0.819v-2.63h-1.388l0.021-1.349h-3.008v1.269h-1.31l0.021-3.897h-1.113l0.021-1.588l-4.609-0.04V11l-3.184-0.02
			l-0.021,6.105h-4.159l0.098-16.466h-1.611V0h-2.803v1.309h-0.381l-0.078,15.207l-3.17-0.021l0.021-6.035h-1.211V9.083l-4.639-0.01
			v3.177h-0.742l-0.049,9.303l-2.061-0.01v-3.718l-1.411-0.029v-1.067h-1.357v1.067H108.1l-0.029,3.896h-1.333l0.093-12.318h-1.06
			l0.02-1.049l-3.423-0.03v0.779h-0.537l0.029-1.159l-2.271-0.02L99.55,21.712l-4.502-0.021l0.049-9.461l-0.889-0.03v-0.85h-1.87
			l-0.508,0.709h-0.57l-0.01,8.443h-1.318v1.658H88.98L89,14.098h-1.128l0.02-0.77h-1.318v-1.08H85.48l-0.029,2.219h-0.747
			l-0.044,7.594h-1.567l0.059-16.604h-1.519V3.647l-5.103-0.03v1.888h-0.869l-0.02,3.128h-0.552L75.04,21.242l-4.59-0.02v-3.339H69.2
			v-1.079l-1.997-0.021v1.079H66.47l-0.01,4.258h-1.172l0.044-5.866H63.56l0.029-1.14l-2.026-0.021l0.02-1.049h-1.934v1.107H58.76
			l-0.01,6.705l-2.612-0.021v-3.654l-5.029-0.02L51.08,22.18h-1.631l0.044-6.025l-5.21-0.01v1.998H43.33v-1.25H42.3v1.198h-0.981
			l0.02-6.004H40.41V11.25h-2.241v1.139l-0.747-0.02l-0.063,10.083l-1.138-0.021L36.25,11.22h-1.201v-0.899h-1.787v1.049L32.5,11.35
			l-0.049,11.272l-6.592-0.031l0.049-7.843h-1.616v-0.92L21.06,13.8v0.897h-1.392l-0.02,8.095h-1.187V21.73H17.47l0.03-5.523h-0.938
			v-0.96h-1.694l-0.029,1.8h-0.537l0.029-0.908h-1.382v0.758H11.64l-0.02,4.537H9.849v1.998L8.33,23.41v-1.719l-1.211-0.039v-0.801
			l-2.217-0.039v-0.989h-1.25l-0.044,1.069H2.319L2.29,22.861L0.01,22.84L0,23.999L239.941,24L239.941,24z"/>
		</svg>';
						break;

					case 'clouds':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,24h240V5.194c-2.617-1.615-6.191-3.108-10.605-3.108c-9.608,0-13.076,5.393-13.076,5.393
			s-3.661-2.947-8.213-0.762c-7.401,3.579-1.885,11.708-1.885,11.708s-1.104,0.161-1.543,0.742c-0.43,0.622-0.137,1.663-0.137,1.663
			s-1.006-0.432-2.07-0.189c-1.084,0.271-1.191,1.233-1.191,1.233s-0.165-2.856-2.069-4.371c-3.955-2.848-7.334-0.171-7.334-0.171
			s-0.558-0.611-0.946-0.771c-0.361-0.181-1.046-0.181-1.046-0.181s-4.413-3.127-10.526-2.266c-6.094,0.86-7.969,6.896-7.969,6.896
			s-0.929-2.786-4.181-4.291c-4.59-2.086-6.483,0-6.483,0s1.483-3.487-2.656-5.583c-4.16-2.115-5.273,1.033-5.273,1.033
			s-0.859-0.431-1.914-0.431c-1.035,0-1.855,0.933-1.855,0.933s-0.02-0.603-1.112-0.933c-1.113-0.342-1.759,0-1.759,0
			s2.169-5.945-4.413-7.75c-5.548-1.574-6.543,2.276-6.798,3.499c-0.155-0.261-0.448-0.612-0.977-0.943
			c-0.967-0.531-1.211-0.421-1.211-0.421s0.264-3.499-2.295-4.521c-2.559-1.042-3.545,0.932-3.545,0.932s-2.896-0.862-4.795,1.233
			c-1.909,2.096-1.03,4.792-1.03,4.792s-0.791,0-1.748,0.61c-0.962,0.603-0.981,1.485-0.981,1.485s-1.68-1.044-3.96-1.044
			c-2.28,0-3.77,1.044-3.77,1.044s-0.84-1.333-2.832-2.106c-1.987-0.762-3.53,0-3.53,0s1.392-5.152-5.498-7.828
			C91.804-1.943,88.201,3.63,88.201,3.63s-0.879,0-1.626,0.17c-0.742,0.261-1.182,0.772-1.182,0.772s-2.422-2.365-6.26-1.584
			c-3.809,0.772-4.15,4.953-4.15,4.953s-3.223-4.36-11.401-3.418c-8.11,0.863-8.848,5.213-8.848,5.213s-1.27-1.233-3.633-0.591
			c-0.669,0.16-1.147,0.431-1.44,0.772c-1.079-1.765-3.096-4.181-6.118-3.86c-2.759,0.341-3.188,1.935-3.188,1.935
			s-0.552-0.601-1.152-0.601h-1.23c0,0-0.581-0.772-1.699-1.223c-1.128-0.431-2.109,0-2.109,0s-2.212-1.043-4.131,0
			C28.11,7.2,27.92,9.735,27.92,9.735s-1.699-1.233-3.262-0.602c-1.558,0.602-1.25,2.446-1.25,2.446s-0.498-0.34-1.528-0.18
			c-1.04,0.18-1.128,0.61-1.128,0.61s-0.942-1.834-2.861-2.275c-2.021-0.431-3.75,0.181-3.75,0.181s-2.139-4.301-10.66-5.233
			c-1.27-0.121-2.383-0.15-3.359-0.07L0,24L0,24z"/>
		</svg>';
						break;

					case 'curve-asym-opacity':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M0,7.172V24h240v-4.516C97.769-1.212,33.652-5.596,0,7.172z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M0,14.252V24h240v-2.828C95.62-3.818,34.971-6.586,0,14.252z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,24C47.91-5.717,92.93-5,239.521,24H0z"/>
		</svg>';
						break;

					case 'curve-asym':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,24C47.998-8.383,93.12-7.614,240,24H0z"/>
		</svg>';
						break;

					case 'curve-opacity':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M0,13.229V23.87h240V13.27C212.247,6.884,170.508,0,119.81,0
			C69.18,0,27.642,6.884,0,13.229z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M0,18.904V24h240v-5.036c-21.641-6.315-64.639-16.257-120.171-16.257
			C64.351,2.707,21.572,12.589,0,18.904z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M119.829,5.115C47.852,5.115,0,24,0,24h239.961C239.961,24,191.816,5.115,119.829,5.115z"/>
		</svg>';
						break;

					case 'curve':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M119.849,0C47.861,0,0,24,0,24h240C240,24,191.855,0.021,119.849,0z"/>
		</svg>';
						break;

					case 'fan-opacity':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.25" d="M0,24h240V0L0,11.26V24z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M240,24H0V11.26L240,24z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.5" d="M240,24H0V11.26h240V24z"/>
		</svg>';
						break;

					case 'flow-opacity':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M0,24h240V0.359c-11.387-1.23-27.227,0.77-44.746,4.87
			c-19.092,4.46-23.965,12.33-36.084,11.378c-11.553-0.906-10.596-7.438-21.67-7.468c-15-0.02-25.952,11.817-40.542,12.917
			c-16.318,1.219-10-13.167-25.938-17.999C57.48-0.068,21.582,4.509,0,14.631V24L0,24z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M191.934,6.021c-18.008,4.371-22.969,12.083-34.883,11.141
			c-11.328-0.898-9.746-7.29-20.488-7.29C121.993,9.852,108.243,21.421,94.17,22.5C78.481,23.701,85.991,9.669,69.912,4.912
			C55.752,0.723,19.248,5.69,0,16.393V24h240V4.371C228.818,0.96,211.68,1.25,191.934,6.021z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M240,9.141c-10.195-5.24-32.402-5.01-52.285,0.05c-15.615,3.95-25.732,9.818-37.236,8.959
			c-11.045-0.852-8.397-6.77-18.789-6.839c-14.257-0.051-25.8,11.162-40.078,12.261C75.342,24.87,81.89,10.899,64.8,6.223
			C49.629,2.042,21.479,6.771,0,17.602V24h240V9.141L240,9.141z"/>
		</svg>';
						break;

					case 'flow':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M240,5.513c-10.195-6.548-32.402-6.238-52.285,0.07c-15.596,4.889-25.732,12.188-37.217,11.099
			c-11.045-1.039-8.398-8.449-18.789-8.499c-14.258-0.09-25.801,13.897-40.078,15.277C75.352,25.039,81.899,7.692,64.81,1.864
			C49.629-3.305,21.479,2.554,0,16.011V24h240V5.513L240,5.513z"/>
		</svg>';
						break;

					case 'hills-opacity':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M209.18,9.257c-10.176,0.18-24.004,1.61-34.697,5.44
			c-5.518,1.972-13.545,4.41-20.645,5.882c-7.002,1.479-10.225,1.581-12.861,1.87c-5.879,0.609-13.358-0.1-19.551-1.27
			c-6.133-1.221-18.364-5.722-26.025-8.652C75.771,4.677,60.771,1.696,45.532,0.436c-6.123-0.5-12.603-0.86-26.763,0.62
			c-6.958,0.691-13.682,2.21-18.75,3.541V24H240V13.259C232.422,11.168,221.348,8.967,209.18,9.257z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M207.461,10.527c-9.961,0.181-23.555,1.491-33.984,4.971
			c-5.391,1.811-13.262,4.021-20.215,5.37c-6.914,1.372-10.078,1.462-12.676,1.682c-5.723,0.55-13.096-0.101-19.16-1.149
			c-6.006-1.08-18.008-5.201-25.518-7.893C76.64,6.367,61.948,3.667,47.002,2.486c-6.001-0.48-12.363-0.8-26.25,0.54
			c-7.939,0.74-15.601,2.5-20.713,3.851v17.114H240v-9.193C232.656,12.758,220.684,10.238,207.461,10.527z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M138.75,22.619c-5.43,0.561-12.344-0.061-18.125-1.061c-5.654-1.01-17.017-4.811-24.116-7.291
			C78.34,7.667,64.438,5.167,50.312,4.087c-5.664-0.431-11.685-0.721-24.814,0.53C12.358,5.847,0,9.847,0,9.847V24h240v-6.212
			c0,0-17.285-6.67-38.018-6.23c-9.404,0.14-22.295,1.37-32.178,4.581c-5.099,1.649-12.539,3.721-19.121,4.971
			C144.17,22.359,141.191,22.43,138.75,22.619z"/>
		</svg>';
						break;

					case 'hills':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M138.75,22.41c-5.43,0.619-12.363-0.09-18.125-1.27c-5.654-1.19-17.017-5.711-24.116-8.642
			C78.34,4.689,64.438,1.708,50.312,0.429c-5.664-0.5-11.685-0.84-24.814,0.62C12.358,2.499,0,7.249,0,7.249V24h240v-7.34
			c0,0-17.285-7.921-38.018-7.381c-9.404,0.17-22.275,1.61-32.158,5.442c-5.098,1.959-12.539,4.409-19.121,5.879
			C144.17,22.061,141.191,22.17,138.75,22.41z"/>
		</svg>';
						break;

					case 'mountains':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M240,24v-3.896c-1.68-2.084-4.492-5.312-5.898-5.881c-1.836-0.678-3.086-2.103-4.647-3.528l0,0
			c0,0-0.021,0-0.021-0.06l-0.02-0.06c-0.098-0.119-0.215-0.199-0.312-0.269c-0.117-0.09-0.233-0.209-0.352-0.339
			c-1.914-1.595-9.375-8.542-9.375-8.542l-1.523,0.588l-2.266-1.515c0,0-3.613,3.797-4.004,4.375
			c-0.381,0.568-1.738,1.595-2.471,2.551c-0.205,0.25-0.557,0.697-0.986,1.186l0,0c-1.123,1.306-2.852,3.109-4.385,3.738
			c-2.109,0.837-5.732,3.309-5.732,3.309s-0.625-0.589-1.328-1.246l0,0c-0.215-0.21-0.449-0.488-0.684-0.679l0,0
			c-0.078-0.08-0.156-0.158-0.205-0.209c-0.029,0-0.029-0.062-0.049-0.062c-0.098-0.062-0.137-0.14-0.215-0.196
			c-0.029,0-0.029-0.041-0.049-0.041c-0.06-0.062-0.117-0.119-0.187-0.199c-0.021,0-0.021-0.062-0.039-0.062
			c-0.06-0.062-0.116-0.119-0.215-0.18l-0.021-0.062c-0.049-0.08-0.117-0.14-0.215-0.199c-0.957-1.125-3.809-2.699-4.922-3.309
			c-1.152-0.588-3.477,0.957-4.609,1.714c-1.133,0.737-2.285,1.713-3.457,2.182c-0.039,0-0.059,0.062-0.116,0.062
			c-0.029,0-0.029,0-0.05,0.039c-0.02,0-0.078,0.04-0.106,0.04c-0.021,0-0.021,0-0.039,0c-0.06,0.061-0.098,0.061-0.116,0.079h-0.05
			c-1.337,0.567-3.604,1.715-4.109,2.552c-0.625,0.977-2.48,2.452-4.386,3.408c-1.122,0.609-3.584,1.654-5.614,2.672
			c-1.017-0.997-1.953-1.924-2.812-2.701c-0.02-0.039-0.059-0.039-0.078-0.061c-0.127-0.1-0.215-0.208-0.293-0.27
			c-0.039-0.039-0.059-0.061-0.117-0.119c-0.059-0.08-0.176-0.158-0.215-0.199c-0.049-0.059-0.078-0.079-0.117-0.159
			c-0.098-0.06-0.137-0.119-0.215-0.14c-0.039-0.039-0.078-0.08-0.117-0.119c-0.029-0.062-0.098-0.141-0.156-0.16
			c-0.039-0.039-0.088-0.059-0.155-0.118c-0.039-0.041-0.106-0.08-0.156-0.142c-0.039-0.039-0.099-0.078-0.116-0.078
			c-0.06-0.039-0.117-0.062-0.156-0.141c-0.039-0.039-0.078-0.039-0.107-0.061c-0.049-0.062-0.088-0.062-0.127-0.08
			c-0.02,0-0.039-0.04-0.059-0.04c-0.068-0.062-0.117-0.08-0.195-0.08c-1.348-0.485-2.832-1.834-2.832-1.834s-4.59,4.865-5.195,5.022
			c-0.059,0-0.117,0.062-0.176,0.062h-0.039c-0.039,0.04-0.078,0.04-0.137,0.08l0,0l0,0c-0.605,0.271-1.387,0.797-1.387,0.797
			s-5.195-6.815-7.295-7.573c-1.847-0.697-3.097-2.104-4.64-3.539l0,0c0,0-0.021,0-0.021-0.04l-0.02-0.06
			c-0.117-0.12-0.215-0.199-0.293-0.289c0-0.114-0.098-0.203-0.234-0.323c-1.895-1.615-9.375-8.541-9.375-8.541l-1.483,0.638
			l-2.296-1.515c0,0-3.622,3.797-3.993,4.375c-0.392,0.568-1.738,1.595-2.48,2.551c-0.205,0.27-0.547,0.698-0.986,1.186l0,0
			c-1.133,1.306-2.861,3.11-4.365,3.738c-2.109,0.837-5.747,3.309-5.747,3.309s-0.62-0.568-1.318-1.246l0,0
			c-0.22-0.21-0.459-0.47-0.698-0.679l0,0c-0.073-0.08-0.142-0.159-0.21-0.209c-0.02,0-0.02-0.04-0.054-0.04
			c-0.068-0.08-0.117-0.159-0.186-0.208c-0.024,0-0.024-0.04-0.054-0.04c-0.049-0.08-0.117-0.12-0.171-0.199
			c-0.02,0-0.02-0.06-0.039-0.06c-0.049-0.061-0.117-0.12-0.171-0.18l-0.02-0.06c-0.049-0.06-0.117-0.14-0.166-0.199
			c-0.962-1.106-3.823-2.721-4.971-3.309c-1.113-0.568-3.394,0.977-4.541,1.754c-1.152,0.717-2.28,1.714-3.433,2.163
			c-0.049,0-0.068,0.06-0.117,0.06c-0.02,0-0.02,0-0.049,0.041c-0.02-0.011-0.073,0.029-0.093,0.029s-0.02,0-0.049,0
			c-0.049,0.06-0.103,0.06-0.122,0.079h0.054c-1.323,0.588-3.623,1.735-4.13,2.571c-0.581,0.957-2.471,2.432-4.39,3.408
			c-1.133,0.588-3.604,1.653-5.62,2.633c-0.962-0.959-1.943-1.924-2.812-2.692c0.083-0.021,0.054-0.021,0.029-0.06
			c-0.088-0.08-0.19-0.199-0.288-0.25c-0.039-0.039-0.068-0.061-0.122-0.119c-0.068-0.101-0.146-0.159-0.21-0.209
			c-0.049-0.049-0.068-0.061-0.117-0.119c-0.068-0.08-0.122-0.119-0.19-0.16c-0.049-0.039-0.068-0.059-0.122-0.118
			c-0.049-0.041-0.107-0.09-0.156-0.11c-0.054-0.06-0.073-0.08-0.122-0.119c-0.049-0.08-0.088-0.1-0.142-0.141
			c-0.02-0.06-0.068-0.08-0.098-0.08c-0.049-0.049-0.103-0.068-0.142-0.106c-0.02-0.062-0.068-0.062-0.098-0.08
			c-0.054-0.062-0.103-0.062-0.122-0.08c-0.02,0-0.039-0.062-0.068-0.062c-0.049-0.039-0.103-0.059-0.151-0.059
			c-1.338-0.45-2.861-1.766-2.861-1.766s-4.58,4.845-5.156,5.021c-0.044,0-0.093,0.062-0.142,0.062h-0.02
			c-0.049,0.029-0.103,0.029-0.142,0.049l0,0l0,0c-0.601,0.29-1.401,0.816-1.401,0.816s-5.107-6.776-7.197-7.494
			c-1.831-0.697-3.071-2.104-4.629-3.539l0,0c0,0-0.02,0-0.02-0.06l-0.024-0.04c-0.088-0.12-0.186-0.189-0.308-0.269
			c-0.122-0.1-0.239-0.219-0.361-0.329c-1.919-1.623-9.36-8.55-9.36-8.55l-1.539,0.569L38.111,0c0,0-3.618,3.817-4.009,4.375
			c-0.381,0.588-1.724,1.595-2.471,2.572c-0.21,0.249-0.552,0.678-0.981,1.166l0,0c-1.127,1.326-2.86,3.129-4.37,3.717
			c-2.109,0.837-5.737,3.329-5.737,3.329s-0.62-0.567-1.323-1.246l0,0c-0.21-0.199-0.449-0.468-0.688-0.679l0,0
			c-0.068-0.078-0.151-0.158-0.22-0.217c-0.02,0-0.02-0.062-0.049-0.062c-0.073-0.062-0.122-0.13-0.19-0.17
			c-0.02,0-0.02-0.061-0.054-0.061c-0.039-0.08-0.117-0.119-0.166-0.199c-0.02,0-0.02-0.042-0.054-0.042
			c-0.049-0.079-0.117-0.119-0.166-0.199l-0.02-0.05c-0.054-0.055-0.122-0.135-0.171-0.175c-0.962-1.126-3.813-2.741-4.971-3.33
			c-1.152-0.578-3.433,0.958-4.58,1.715c-1.152,0.767-2.28,1.734-3.433,2.183c-0.049,0-0.068,0.04-0.117,0.04
			c-0.02,0-0.02,0-0.049,0.061c-0.024,0-0.073,0.061-0.103,0.061s-0.02,0-0.049,0c-0.049,0.04-0.093,0.04-0.122,0.062h-0.01
			C2.779,13.404,0.669,14.48,0,15.318V24H240z"/>
		</svg>';
						break;

					case 'pyramids':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M240,24L159.961,0l-74.36,21.1l-28.56-7.31L0,24H240z"/>
		</svg>';
						break;

					case 'ray-opacity':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M240,24V0L0,24H240z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M240,0H0v24"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M0,11.611V24L240,0L0,11.611z"/>
		</svg>';
						break;

					case 'spear':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M134.698,24C124.5,24,120,13.04,120,0c0,13.04-4.5,24-14.698,24H134.698z"/>
		</svg>';
						break;

					case 'swoosh-opacity':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M240,24V0c-51.797,0-69.883,13.18-94.707,15.59c-24.691,2.4-43.872-1.17-63.765-1.08
			c-19.17,0.1-31.196,3.65-51.309,6.58C15.552,23.21,4.321,22.471,0,22.01V24H240z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M240,24V2.21c-51.797,0-69.883,11.96-94.707,14.16
			c-24.691,2.149-43.872-1.08-63.765-1.021c-19.17,0.069-31.196,3.311-51.309,5.971C15.552,23.23,4.321,22.58,0,22.189V24h239.766H240
			z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M240,24V3.72c-51.797,0-69.883,11.64-94.707,14.021c-24.691,2.359-43.872-3.25-63.765-3.17
			c-19.17,0.109-31.196,3.6-51.309,6.529C15.552,23.209,4.321,22.47,0,22.029V24H240z"/>
		</svg>';
						break;

					case 'swoosh':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M239.766,24L240,0c-51.797,0-69.883,14.65-94.707,17.68c-24.691,2.98-43.872-4.1-63.765-4
			c-19.17,0.101-31.196,4.562-51.309,8.2C15.552,24.529,4.321,23.59,0,23.04V24H239.766z"/>
		</svg>';
						break;

					case 'tilt-opacity':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M240,24V0L0,24H240z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M240,24V3.72L0,24H240z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M240,24V1.99L0,24H240z"/>
		</svg>';
						break;

					case 'tilt':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M240,24V0L0,24H240z"/>
		</svg>';
						break;

					case 'triangle':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M240,24L119.878,0L0,23.96V24H240z"/>
		</svg>';
						break;

					case 'waves-opacity':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M239.971,0.038c-4.61-0.19-9.552,0.34-15,1.32
			c-20.395,3.721-26.233,12.311-39.419,17.292c-22.208,8.379-41.546-14.521-65.619-14.521c-25.681,0-30.486,25.262-62.506,18.051
			C34.632,16.999,27.625,8.329,10.323,11.059c-2.71,0.44-6.553,1.04-10.323,1.81V24h240V0.038H239.971z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M222.049,3.889c-20.803,3.72-25.198,12.582-39.849,17.582
			c-24.611,8.399-40.728-18.493-63.342-18.493c-24.124,0-38.287,26.312-65.082,19.102C34.707,16.919,27.743,8.239,10.27,10.979
			c-3.717,0.59-7.149,1.52-10.27,2.72V24h240V2.888C234.979,2.148,229.1,2.638,222.049,3.889z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M239.99,5.699c-4.825-0.64-10.606-0.21-17.48,0.89C202.234,9.81,197.917,17.48,183.618,21.8
			c-23.967,7.28-39.71-16.011-61.787-16.011c-23.521,0-37.345,22.751-63.486,16.542C39.701,17.88,32.922,10.35,15.861,12.73
			C9.751,13.58,4.438,15.29,0,19.423V24h240V7.152L239.99,5.699z"/>
		</svg>';
						break;

					case 'waves':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M240,0.393c-4.844-0.838-10.605-0.279-17.471,1.149c-20.303,4.149-24.6,14.056-38.877,19.621
			C159.648,30.525,142.076,0.523,120,0.523c-23.516,0-35.499,29.375-61.632,21.333C39.721,16.104,32.948,6.39,15.888,9.458
			C9.751,10.566,4.438,12.747,0,15.773V24h240V0.393z"/>
		</svg>';
						break;

					case 'step':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M240,0v24H0V0H240z"/>
		</svg>';
						break;

					case 'step_1_2':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M120,0v24H0V0H120z"/>
		</svg>';
						break;

					case 'step_2_3':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,0v24h160V0H0z"/>
		</svg>';
						break;

					case 'step_3_4':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,0v24h180V0H0z"/>
		</svg>';
						break;

					case 'gradient':
						$gradient_id = 'svg-gradient-'.uncode_big_rand();
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<linearGradient id="' . $gradient_id . '" gradientUnits="userSpaceOnUse" x1="119.9995" y1="0" x2="119.9995" y2="24.0005">
			<stop  offset="0" style="stop-color:' . ${'shape_'.$shape_position.'_divider_color'} . ';stop-opacity:0"/>
			<stop  offset="1" style="stop-color:' . ${'shape_'.$shape_position.'_divider_color'} . '"/>
		</linearGradient>
		<path fill="url(#' . $gradient_id . ')" d="M240,24V0H0v24H240z"/>
		</svg>';
						break;

					default:
						${$shape_position.'_divider_svg'} = '';
						break;
				}

			else:

				${'class_shape_'.$shape_position.'_invert'} = ' uncode-row-divider-invert';

				switch (${$shape_position.'_divider_inv'}) {
					case 'arrow':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M240,24V0H119.98l22.812,24H240z M97.202,24v-0.029L119.971,0H0v24H97.202z"/>
		</svg>';
						break;

					case 'book':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,0v24c0,0,19.131-0.15,46.562-0.068C91.348,24.093,119.761,4.839,120,0.239
			c0.239,4.6,28.633,23.854,73.438,23.692C220.879,23.857,240,24,240,24V0H0z"/>
		</svg>';
						break;

					case 'city':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,0v23.99l0,0l0.02-1.061l2.28,0.04L2.329,21h1.289l0.02-1.068h1.265v1.008l2.227,0.021v0.819l1.201,0.021
			v1.739l1.528,0.021v-1.988h1.753l0.02-4.559h1.318v-0.771h1.372l-0.02,0.93h0.547l0.02-1.819h1.68v0.989H17.5l-0.02,5.498h1.001
			v1.06h1.196l0.024-8.096h1.396v-0.91l3.213,0.021v0.949h1.626l-0.059,7.825l6.572,0.062L32.5,11.445l0.771,0.02v-1.04h1.797v0.91
			h1.201L36.22,22.551l1.147,0.021l0.054-10.106l0.757,0.01v-1.13h2.231v0.84h0.938l-0.02,6.017h0.991v-1.219h1.03v1.248h0.942v-1.988
			l5.21,0.02l-0.02,6.017h1.626l0.02-4.009l5.024,0.021v3.669l2.607,0.021l0.02-6.719h0.889v-1.13h1.924l-0.01,1.04l2.036,0.02
			l-0.02,1.16h1.753l-0.039,5.836h1.147l0.02-4.228h0.732v-1.08l2.007,0.03v1.061h1.25v3.338l4.58,0.021l0.049-12.624h0.552
			l0.02-3.119h0.859v-1.9l5.093,0.02v1.829h1.528l-0.068,16.634h1.587l0.02-7.606h0.771l0.02-2.208h1.084v1.069h1.318l-0.02,0.78
			h1.147l-0.029,8.057h0.933v-1.66h1.318l0.02-8.436h0.562l0.527-0.72h1.87v0.86l0.889,0.03l-0.049,9.436l4.492,0.021L99.59,7.986
			l2.261,0.02l-0.02,1.149h0.547v-0.75l3.413,0.02l-0.01,1.05h1.06l-0.073,12.285h1.323l0.02-3.888h1.03v-1.08h1.367v1.069
			l1.421,0.031v3.717l2.061,0.021l0.049-9.306h0.723V9.146l4.648,0.02v1.369h1.201l-0.021,6.038l3.169,0.02l0.078-15.204h0.391V0.11
			h2.783v0.62h1.641l-0.098,16.454h4.141l0.021-6.078l3.175,0.02V6.147l4.608,0.02l-0.021,1.609h1.094l-0.028,3.888h1.317v-1.29h3.027
			l-0.02,1.37h1.396v2.617h0.819l0.039-9.464l0.819,0.02v-1.85h1.572l2.101,0.02v0.89l1.758,0.03l-0.021,5.468l1.836,0.02v-1.3
			l2.051,0.02l-0.038,0.91h2.655l-0.039,1.989l0.957,0.02v-0.949h0.898v-1h2.09l-0.039,1.02h1.455v0.65h0.635v2.129l1.045,0.021v-1.38
			l2.09,0.02v-0.91h0.938v-0.51l2.013,0.03v1.03h1.455v0.99l1.68,0.02v1.229h0.801l-0.02,6.418h0.36v-1.77h0.724l0.02-5.479h0.43
			v-0.918h1.514v0.86h0.537l-0.039,4.868h0.693l0.039-7.198l4.043,0.02v1.93h0.488v2.22h0.312l-0.039,5.877h1.151v-0.84h0.488
			l0.021-2.068h0.43l0.039-8.097l3.086,0.02l-0.039,4.897h0.498v3.27l1.367,0.021l-0.021,3.047h0.879v-1.588l1.476,0.02v-0.989
			l4.979,0.021v-1.06h1.271l-0.021,1.22h1.317v2.809h1.084l0.021-2.578h0.742v-4.118h0.78v-1.679l0.88,0.02l0.547-0.729l1.582,0.05
			l0.039-8.657h3.28l-0.02,8.776l1.797,0.02l-0.02,6.688h1.045l0.039-8.327h2.852v0.89h2.705l-0.02,3.057l1.309,0.021v-2.299
			l2.031,0.02l0.02-1.25l1.826,0.02l-0.068,7.447l2.793,0.038l0.039-8.535h0.488v-0.67h2.91l-0.02,1.339l3.242,0.02l-0.06,8.637h1.543
			v2.617h1.68l0.021-3.117h0.859v-1.291H240V0.01L0,0L0,0z M240,24v-6.547L239.941,24H240z"/>
		</svg>';
						break;

					case 'clouds':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0.02,5.098c0.981-0.077,2.09-0.055,3.359,0.066c8.521,1.031,10.649,5.712,10.649,5.712
			s1.733-0.669,3.75-0.197c1.924,0.471,2.861,2.488,2.861,2.488s0.103-0.481,1.128-0.668c1.03-0.187,1.543,0.187,1.543,0.187
			s-0.312-1.997,1.25-2.631c1.558-0.669,3.271,0.658,3.271,0.658s0.186-2.773,2.109-3.914c1.919-1.141,4.131,0,4.131,0
			s0.977-0.472,2.109,0c1.128,0.471,1.699,1.348,1.699,1.348H39.1c0.601,0,1.147,0.658,1.147,0.658s0.43-1.754,3.188-2.116
			c3.042-0.351,5.044,2.291,6.123,4.221c0.288-0.374,0.771-0.669,1.44-0.844c2.349-0.669,3.618,0.657,3.618,0.657
			s0.752-4.758,8.872-5.712c8.182-1.042,11.4,3.739,11.4,3.739s0.352-4.572,4.175-5.427c3.838-0.844,6.26,1.744,6.26,1.744
			s0.43-0.548,1.167-0.844c0.752-0.187,1.641-0.187,1.641-0.187S91.72-2.05,98.702,0.899c6.89,2.949,5.498,8.574,5.498,8.574
			s1.533-0.833,3.54,0c1.992,0.844,2.832,2.281,2.832,2.281s1.489-1.13,3.77-1.13c2.329,0,4.009,1.108,4.009,1.108
			s0.02-0.943,0.981-1.612c0.957-0.668,1.748-0.668,1.748-0.668s-0.889-2.949,1.04-5.241c1.919-2.281,4.795-1.337,4.795-1.337
			s0.978-2.182,3.524-1.03c2.578,1.129,2.295,4.944,2.295,4.944s0.244-0.121,1.23,0.471c0.488,0.351,0.801,0.724,0.967,1.02
			c0.226-1.337,1.25-5.526,6.797-3.826c6.562,2.006,4.404,8.464,4.404,8.464s0.635-0.36,1.768,0c1.084,0.373,1.104,1.031,1.104,1.031
			s0.802-1.031,1.886-1.031c1.045,0,1.885,0.472,1.885,0.472s1.133-3.443,5.272-1.141c4.16,2.312,2.638,6.107,2.638,6.107
			s1.934-2.291,6.494,0c3.28,1.61,4.188,4.681,4.188,4.681s1.875-6.578,7.969-7.521c6.074-0.943,10.508,2.478,10.508,2.478
			s0.703,0,1.085,0.196c0.37,0.188,0.946,0.834,0.946,0.834s3.397-2.948,7.334,0.209c1.884,1.609,2.05,4.768,2.05,4.768
			s0.107-1.043,1.211-1.338c1.084-0.264,2.051,0.207,2.051,0.207s-0.264-1.139,0.166-1.818c0.431-0.681,1.515-0.835,1.515-0.835
			s-5.537-8.901,1.885-12.793c4.57-2.39,8.242,0.844,8.242,0.844s3.457-5.888,13.047-5.888c4.385,0,8.008,1.656,10.625,3.398V0H0"/>
		</svg>';
						break;

					case 'curve-asym-opacity':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M0,0v24C47.91-5.42,92.93-4.712,239.512,24H240V0.02L0,0z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M0,0.02v14.361C34.971-6.26,95.62-3.501,240,21.203V0.02H0L0,0.02z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,0.02v7.359C33.652-5.278,97.769-0.918,240,19.553V0.02H0L0,0.02z"/>
		</svg>';
						break;

					case 'curve-asym':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,0v24C47.998-8.379,93.12-7.608,240,24l0,0V0H0z"/>
		</svg>';
						break;

					case 'curve-opacity':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M240,24V0H0v24C0,24,47.861,5.107,119.849,5.107C191.855,5.107,240,24,240,24z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M119.829,2.661c55.562,0,98.521,9.957,120.171,16.271V0H0v18.902
			C21.582,12.598,64.37,2.65,119.829,2.661z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M119.81,0.117c50.737,0,92.456,6.89,120.19,13.271V0H0v13.34C27.651,6.979,69.17,0.117,119.81,0.117z"/>
		</svg>';
						break;

					case 'curve':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,24l0.01-0.24V0H240v24c0,0-48.115-23.471-120.039-23.48C48.018,0.52,0,24,0,24L0,24z"/>
		</svg>';
						break;

					case 'fan-opacity':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.25" d="M240,0H0v24l240-11.26V0z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,0h240v12.74L0,0z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.5" d="M0,0h240v12.74H0V0z"/>
		</svg>';
						break;

					case 'flow-opacity':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,0v14.873C21.602,4.718,57.48,0.13,71.021,4.278C86.958,9.136,80.64,23.589,96.958,22.37
			C111.548,21.26,122.5,9.375,137.5,9.415c11.055,0.02,10.098,6.587,21.68,7.487c12.129,0.959,16.992-6.938,36.074-11.436
			C212.773,1.339,228.613-0.62,240,0.569V0H0z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M0,16.672C19.248,5.947,55.752,0.959,69.912,5.138
			C85.991,9.896,78.481,23.98,94.17,22.789c14.072-1.039,27.822-12.684,42.393-12.653c10.742,0.021,9.16,6.438,20.488,7.316
			c11.914,0.948,16.875-6.767,34.883-11.155C211.67,1.509,228.799,1.209,240,4.638V0H0"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M0,0v17.922C21.479,7.057,49.629,2.329,64.819,6.497
			c17.09,4.708,10.542,18.69,26.831,17.422c14.277-1.119,25.83-12.344,40.079-12.304c10.391,0.05,7.754,6.037,18.789,6.876
			c11.465,0.858,21.621-5.017,37.178-8.966c19.893-5.097,42.109-5.347,52.305-0.06V0H0z"/>
		</svg>';
						break;

					case 'flow':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M64.819,1.902c17.09,5.936,10.542,23.624,26.831,21.993c14.277-1.422,25.83-15.676,40.079-15.576
			c10.391,0.08,7.754,7.626,18.789,8.649c11.465,1.101,21.621-6.337,37.178-11.312c19.893-6.417,42.109-6.737,52.305-0.06V0H0v16.327
			C21.479,2.603,49.629-3.363,64.819,1.902z"/>
		</svg>';
						break;

					case 'hills-opacity':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,0v4.852C5.059,3.421,11.812,1.84,18.75,1.101C32.91-0.43,39.399-0.07,45.532,0.46
			C60.771,1.8,75.771,4.901,95.39,13.104c7.661,3.111,19.922,7.832,26.021,9.084c6.226,1.229,13.706,1.979,19.565,1.35
			c2.637-0.26,5.858-0.35,12.891-1.909c7.129-1.541,15.137-4.142,20.625-6.183c10.664-4.011,24.551-5.521,34.697-5.711
			c12.178-0.28,23.252,2.041,30.811,4.221V0H0z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M0,0v7.232c5.112-1.42,12.788-3.231,20.708-4.021C34.6,1.78,40.962,2.13,46.948,2.621
			c14.951,1.2,29.663,4.061,48.911,11.543c7.51,2.831,19.541,7.162,25.513,8.312c6.099,1.119,13.433,1.799,19.175,1.219
			c2.598-0.238,5.732-0.34,12.656-1.75c6.973-1.439,14.844-3.771,20.215-5.641c10.449-3.701,24.062-5.062,34.004-5.222
			c13.242-0.291,25.176,2.34,32.539,4.501V0H0z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M50.288,4.321c14.141,1.13,28.032,3.771,46.23,10.684
			c7.1,2.63,18.462,6.601,24.121,7.672c5.768,1.04,12.69,1.681,18.12,1.109c2.441-0.209,5.421-0.31,11.943-1.619
			c6.603-1.302,14.043-3.491,19.121-5.212c9.883-3.381,22.715-4.692,32.168-4.822c20.293-0.46,37.305,6.252,37.987,6.553V0H0v10.373
			c0,0,12.358-4.221,25.488-5.501C38.608,3.551,44.629,3.871,50.288,4.321z"/>
		</svg>';
						break;

					case 'hills':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,0v8.043c0,0,12.358-4.922,25.488-6.433c13.13-1.51,19.151-1.18,24.81-0.65
			c14.141,1.291,28.032,4.392,46.23,12.505c7.1,3.082,18.462,7.754,24.121,8.976c5.758,1.25,12.69,1.961,18.121,1.319
			c2.44-0.26,5.42-0.34,11.953-1.901c6.602-1.479,14.043-4.029,19.12-6.093c9.884-3.951,22.716-5.472,32.169-5.642
			c20.702-0.54,37.987,7.674,37.987,7.674V0H0z"/>
		</svg>';
						break;

					case 'mountains':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,16.754c0.668-0.888,2.768-2.017,4.017-2.62h0.02c0.024-0.052,0.073-0.052,0.122-0.072
			c0.02,0,0.02,0,0.048,0c0.029,0,0.073-0.043,0.103-0.043c0.02-0.053,0.02-0.053,0.049-0.053c0.039,0,0.068-0.053,0.122-0.053
			c1.137-0.508,2.274-1.542,3.407-2.334c1.147-0.813,3.437-2.461,4.579-1.86c1.147,0.624,3.997,2.346,4.958,3.591
			c0.049,0.043,0.117,0.116,0.166,0.189l0.024,0.055c0.039,0.072,0.117,0.127,0.166,0.209c0.024,0,0.024,0.045,0.054,0.045
			c0.049,0.072,0.117,0.125,0.176,0.209c0.024,0,0.024,0.045,0.054,0.045c0.059,0.052,0.107,0.136,0.181,0.209
			c0.02,0,0.02,0.056,0.049,0.056c0.068,0.071,0.151,0.146,0.21,0.221c0.239,0.224,0.478,0.519,0.698,0.729
			c0.693,0.74,1.333,1.343,1.333,1.343s3.597-2.642,5.715-3.57c1.508-0.655,3.241-2.608,4.368-4.036
			c0.43-0.53,0.771-0.984,0.981-1.269c0.747-1.046,2.079-2.156,2.47-2.769C34.434,4.361,38.056,0,38.056,0H0V16.754z M38.017,0
			l2.279,1.87l1.538-0.624c0,0,7.429,7.479,9.347,9.201c0.127,0.126,0.229,0.232,0.361,0.359c0.117,0.127,0.205,0.211,0.308,0.306
			l0.02,0.053c0,0.053,0.02,0.053,0.02,0.053c1.562,1.542,2.801,3.074,4.617,3.805c2.079,0.821,7.238,8.175,7.238,8.175
			s0.781-0.603,1.381-0.888c0.049-0.062,0.098-0.062,0.146-0.084h0.024c0.049,0,0.098-0.062,0.146-0.062
			c0.581-0.212,5.149-5.41,5.149-5.41s1.499,1.468,2.841,1.966c0.049,0,0.098,0.073,0.146,0.096c0.02,0,0.044,0.041,0.073,0.041
			c0.02,0.062,0.078,0.062,0.127,0.084c0.019,0.063,0.073,0.063,0.092,0.105c0.049,0.042,0.098,0.062,0.146,0.127
			c0.024,0,0.073,0.042,0.112,0.074c0.039,0.052,0.088,0.072,0.142,0.137c0.049,0.043,0.068,0.096,0.117,0.139
			c0.049,0.041,0.103,0.104,0.151,0.125c0.049,0.074,0.068,0.097,0.117,0.149c0.073,0.021,0.122,0.04,0.19,0.125
			c0.054,0.044,0.073,0.085,0.122,0.129c0.068,0.041,0.141,0.138,0.209,0.224c0.049,0.053,0.068,0.084,0.122,0.138
			c0.107,0.062,0.21,0.211,0.288,0.254c0.02,0.052,0.049,0.052,0.068,0.084c0.849,0.834,1.85,1.892,2.802,2.916
			c2.016-1.078,4.476-2.251,5.608-2.853c1.918-1.06,3.817-2.644,4.378-3.698c0.498-0.909,2.807-2.155,4.115-2.759l0,0
			c0.024-0.053,0.073-0.053,0.122-0.073c0.02,0,0.02,0,0.049,0s0.083-0.052,0.103-0.052c0.02-0.055,0.02-0.055,0.049-0.055
			c0.049,0,0.068-0.063,0.122-0.063c1.138-0.484,2.27-1.521,3.417-2.312c1.152-0.812,3.417-2.45,4.578-1.849
			c1.137,0.604,3.997,2.334,4.959,3.57c0.049,0.054,0.117,0.127,0.181,0.223l0.02,0.054c0.049,0.073,0.117,0.126,0.17,0.2
			c0.02,0,0.02,0.052,0.039,0.052c0.049,0.075,0.117,0.14,0.181,0.212c0.02,0,0.02,0.055,0.049,0.055
			c0.068,0.053,0.117,0.127,0.19,0.201c0.02,0,0.02,0.053,0.049,0.053c0.068,0.084,0.151,0.156,0.21,0.222
			c0.229,0.222,0.468,0.508,0.678,0.739c0.693,0.719,1.333,1.343,1.333,1.343s3.592-2.642,5.701-3.571
			c1.513-0.654,3.241-2.608,4.383-4.035c0.429-0.541,0.771-0.995,0.986-1.259c0.742-1.055,2.089-2.154,2.479-2.777
			c0.391-0.613,3.973-4.702,3.973-4.702l2.294,1.638l1.533-0.612c0,0,7.419,7.479,9.342,9.211c0.146,0.126,0.226,0.221,0.371,0.348
			c0.116,0.127,0.205,0.211,0.282,0.295l0.039,0.042c0,0.063,0.02,0.063,0.02,0.063c1.562,1.542,2.812,3.063,4.639,3.792
			c2.049,0.812,7.242,8.155,7.242,8.155s0.801-0.604,1.405-0.889c0.078-0.062,0.116-0.062,0.176-0.097h0.021
			c0.059,0,0.117-0.052,0.156-0.052c0.586-0.2,5.153-5.397,5.153-5.397s1.503,1.447,2.851,1.965c0.039,0,0.098,0.053,0.137,0.072
			c0.059,0,0.098,0.062,0.117,0.062c0.02,0.043,0.078,0.043,0.117,0.062c0.02,0.074,0.098,0.074,0.137,0.097
			c0.039,0.053,0.098,0.085,0.137,0.126c0.039,0,0.098,0.062,0.117,0.105c0.059,0.041,0.098,0.062,0.137,0.125
			c0.059,0.057,0.078,0.086,0.176,0.129c0.039,0.053,0.117,0.138,0.156,0.181c0.078,0.041,0.098,0.063,0.156,0.127
			c0.078,0.043,0.137,0.073,0.205,0.159c0.059,0.053,0.078,0.084,0.127,0.137c0.078,0.074,0.156,0.137,0.215,0.223
			c0.059,0.053,0.078,0.084,0.156,0.127c0.098,0.094,0.195,0.224,0.252,0.284c0.039,0.042,0.06,0.042,0.078,0.063
			c0.859,0.845,1.875,1.881,2.772,2.927c2.011-1.101,4.472-2.251,5.623-2.862c1.894-1.048,3.826-2.644,4.373-3.679
			c0.537-0.908,2.812-2.152,4.14-2.756h0.02c0.02-0.053,0.088-0.053,0.146-0.074c0.02,0,0.02,0,0.039,0
			c0.038,0,0.106-0.062,0.127-0.062c0.02-0.055,0.02-0.055,0.059-0.055s0.059-0.054,0.127-0.054c1.143-0.507,2.273-1.542,3.438-2.333
			c1.131-0.812,3.444-2.46,4.567-1.849c1.132,0.612,3.974,2.333,4.959,3.582c0.039,0.043,0.146,0.127,0.205,0.201l0.021,0.051
			c0.039,0.086,0.127,0.141,0.166,0.214c0.039,0,0.039,0.052,0.077,0.052c0.039,0.075,0.146,0.129,0.187,0.202
			c0.019,0,0.019,0.062,0.058,0.062c0.067,0.055,0.127,0.129,0.205,0.201c0.021,0,0.021,0.053,0.049,0.053
			c0.099,0.074,0.177,0.158,0.205,0.225c0.205,0.221,0.498,0.506,0.674,0.729c0.664,0.729,1.308,1.341,1.308,1.341
			s3.623-2.641,5.74-3.567c1.475-0.656,3.213-2.611,4.373-4.026c0.43-0.528,0.723-0.993,0.978-1.268
			c0.741-1.035,2.089-2.155,2.479-2.768c0.37-0.612,3.963-4.712,3.963-4.712l2.293,1.649l1.523-0.614c0,0,7.391,7.48,9.361,9.191
			c0.137,0.137,0.215,0.222,0.332,0.358c0.154,0.138,0.214,0.211,0.312,0.307l0.021,0.042c0,0.053,0.02,0.053,0.02,0.053
			c1.543,1.542,2.792,3.074,4.627,3.801c1.445,0.581,4.237,4.107,5.916,6.34V0H38.017L38.017,0z"/>
		</svg>';
						break;

					case 'pyramids':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M159.961,0L240,23.99V0H159.961z M0,24l57.051-10.216l28.56,7.315L159.961,0H0V24z"/>
		</svg>';
						break;

					case 'ray-opacity':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,0v24L240,0H0z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M0,24h240V0"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M240,12.393V0L0,24L240,12.393z"/>
		</svg>';
						break;

					case 'spear':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M240,0v24H134.698C124.5,24,120,13.04,120,0H240z M0,24h105.302C115.5,24,120,13.04,120,0H0V24z"/>
		</svg>';
						break;

					case 'swoosh-opacity':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M240,0H0v23.539c4.321,0.414,15.532,1.113,30.21-0.912
			c20.112-2.798,32.139-6.213,51.309-6.309c19.902-0.073,39.072,3.361,63.754,1.07c24.806-2.301,42.91-15.015,94.707-15.015"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M240,0H0v23.359c4.321,0.468,15.532,1.25,30.21-0.987
			c20.112-3.085,32.139-6.827,51.309-6.923c19.902-0.074,39.072,5.874,63.754,3.361c24.806-2.524,42.91-14.867,94.707-14.867"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,23.338c4.321,0.455,15.532,1.241,30.21-0.996c20.112-3.105,32.139-6.871,51.309-6.977
			c19.902-0.098,39.072,3.699,63.754,1.145C170.117,13.975,188.203,0,240,0H0V23.338z"/>
		</svg>';
						break;

					case 'swoosh':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,23.368c4.321,0.576,15.532,1.5,30.21-1.188c20.112-3.711,32.138-8.234,51.309-8.315
			c19.902-0.103,39.072,7.08,63.754,4.057C170.117,14.889,188.203,0,240,0H0V23.368z"/>
		</svg>';
						break;

					case 'tilt-opacity':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M0,0v24L240,0H0z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,0v20.279L240,0H0z"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M0,0v22.01L240,0H0z"/>
		</svg>';
						break;

					case 'tilt':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,0v24L240,0H0z"/>
		</svg>';
						break;

					case 'triangle':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M119.888,0.24L240,24V0H0v23.971L119.888,0.24z"/>
		</svg>';
						break;

					case 'waves-opacity':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,0v14.182c3.124-1.22,6.56-2.13,10.27-2.708c17.499-2.73,24.44,5.98,43.547,11.129
			C80.628,29.842,93.06,3.44,117.158,3.44c22.631,0,40.458,26.955,65.074,18.553c14.645-5.021,19.015-13.912,39.855-17.623
			c7.049-1.25,12.926-1.79,17.912-1.02V0"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M0,0v13.372c3.764-0.791,7.63-1.391,10.323-1.801
			c17.298-2.74,24.341,5.98,47.129,11.132C89.473,29.934,92.539,4.58,118.224,4.58c24.092,0,45.193,22.971,67.344,14.572
			c13.188-5.021,19.053-13.632,39.426-17.354C230.469,0.81,235.387,0.3,240,0.48V0"/>
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" fill-opacity="0.33" d="M0,0v18.102c4.434-2.317,9.751-4,15.885-4.869c17.067-2.378,23.832,5.16,42.482,9.621
			C84.5,29.109,96.594,6.26,120.117,6.26c22.078,0,39.531,23.332,63.539,16.063c14.271-4.341,18.572-12.032,38.865-15.242
			C229.402,5.98,235.171,5.55,240,6.19V0"/>
		</svg>';
						break;

					case 'waves':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . ' x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M0,0v16.426c4.438-3.019,9.751-5.239,15.889-6.328c17.07-3.109,23.833,6.718,42.48,12.496
			C84.502,30.713,96.484,1.02,120,1.02c22.075,0,39.648,30.363,63.652,20.895c14.277-5.64,18.574-15.616,38.877-19.845
			c6.865-1.43,12.628-1.99,17.472-1.15V0H0L0,0z"/>
		</svg>';
						break;

					case 'step_1_2':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M120,0v24h120V0H120z"/>
		</svg>';
						break;

					case 'step_2_3':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M240,0v24h-80V0H240z"/>
		</svg>';
						break;

					case 'step_3_4':
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<path fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" d="M240,0v24h-60V0H240z"/>
		</svg>';
						break;

					case 'gradient':
						$gradient_id = 'svg-gradient-'.uncode_big_rand();
						${$shape_position.'_divider_svg'} = '<svg version="1.1" class="uncode-row-divider uncode-row-divider-' . ${$shape_position.'_divider'} . '" x="0px" y="0px" width="240px" height="24px" viewBox="0 0 240 24" enable-background="new 0 0 240 24" xml:space="preserve" preserveAspectRatio="' . ${'preserveAspectRatio_'.$shape_position} . '">
		<linearGradient id="' . $gradient_id . '" gradientUnits="userSpaceOnUse" x1="119.9995" y1="0" x2="119.9995" y2="24.0005">
			<stop  offset="0" style="stop-color:' . ${'shape_'.$shape_position.'_divider_color'} . '"/>
			<stop  offset="1" style="stop-color:' . ${'shape_'.$shape_position.'_divider_color'} . ';stop-opacity:0"/>
		</linearGradient>
		<path fill="url(#' . $gradient_id . ')" d="M240,24V0H0v24H240z"/>
		</svg>';
						break;

					default:
						${$shape_position.'_divider_svg'} = '';
						break;
				}

			endif;

			if ( isset(${$shape_position.'_divider_svg'}) ) {
				${'divider_'.$shape_position.'_svg'} .= '<div class="uncode-divider-wrap uncode-divider-wrap-' . ${'shape_'.$shape_position.'_classes'} . ${'class_shape_'.$shape_position.'_invert'} . '" style="height: ' . ${'style_shape_'.$shape_position.'_h'} . ${'style_shape_'.$shape_position.'_unit'} . ';' . ${'divider_'.$shape_position.'_opacity'} . '" data-height="' . ${'style_shape_'.$shape_position.'_h'} . '" data-unit="' . ${'style_shape_'.$shape_position.'_unit'} . '">' . ${$shape_position.'_divider_svg'} . '</div>';
			}

		} elseif ( ${'enable_'.$shape_position.'_divider'} == 'custom' ) {
			if ( !empty(${'shape_'.$shape_position.'_custom'}) ) {
				${'shape_'.$shape_position.'_info'} = uncode_get_media_info(${'shape_'.$shape_position.'_custom'});
				if (${'shape_'.$shape_position.'_info'}->post_mime_type === 'oembed/svg') {
					$media_code = ${'shape_'.$shape_position.'_info'}->post_content;
					if ( ${'shape_'.$shape_position.'_divider_color'} != '' ) {
						$media_code = preg_replace('/ fill="(.*?)" /', ' fill="' . ${'shape_'.$shape_position.'_divider_color'} . '" ', $media_code);
					}
					${'divider_'.$shape_position.'_svg'} .= '<div class="uncode-divider-wrap uncode-divider-wrap-' . ${'shape_'.$shape_position.'_classes'} . '" style="height: ' . ${'style_shape_'.$shape_position.'_h'} . ${'style_shape_'.$shape_position.'_unit'} . ';' . ${'divider_'.$shape_position.'_opacity'} . '" data-height="' . ${'style_shape_'.$shape_position.'_h'} . '" data-unit="' . ${'style_shape_'.$shape_position.'_unit'} . '">'.$media_code.'</div>';
				} else {
					$media_code = ${'shape_'.$shape_position.'_info'}->guid;
					${'divider_'.$shape_position.'_svg'} .= '<div class="uncode-divider-wrap uncode-divider-wrap-' . ${'shape_'.$shape_position.'_classes'} . '" style="height: ' . ${'style_shape_'.$shape_position.'_h'} . ${'style_shape_'.$shape_position.'_unit'} . ';' . ${'divider_'.$shape_position.'_opacity'} . '" data-height="' . ${'style_shape_'.$shape_position.'_h'} . '" data-unit="' . ${'style_shape_'.$shape_position.'_unit'} . '"><img src="'.$media_code.'" /></div>';
				}
			}
		}

	}
}
/** END - shape dividers

/** BEGIN - shift construction **/
if ($shift_y != '0' && $shift_y != '') {
    switch ($shift_y) {
        case 1:
            $row_inner_classes[] = 'shift_y_half';
        break;
        case 2:
            $row_inner_classes[] = 'shift_y_single';
        break;
        case 3:
            $row_inner_classes[] = 'shift_y_double';
        break;
        case 4:
            $row_inner_classes[] = 'shift_y_triple';
        break;
        case 5:
            $row_inner_classes[] = 'shift_y_quad';
        break;
        case -1:
            $row_inner_classes[] = 'shift_y_neg_half';
        break;
        case -2:
            $row_inner_classes[] = 'shift_y_neg_single';
        break;
        case -3:
            $row_inner_classes[] = 'shift_y_neg_double';
        break;
        case -4:
            $row_inner_classes[] = 'shift_y_neg_triple';
        break;
        case -5:
            $row_inner_classes[] = 'shift_y_neg_quad';
        break;
    }
    if ($shift_y_fixed === 'yes') $row_inner_classes[] = 'shift_y_fixed';
}
/** END - shift construction **/

if ($row_height_pixel !== '') {
	$min_height = esc_attr(preg_replace("/[^0-9,.]/", "", $row_height_pixel));
	$row_inline_style = ' data-minheight="' . esc_attr($min_height) . '"';
}

if (!empty($row_inner_height_percent) && $row_inner_height_percent != '0') {
	$row_inner_height_percent = ' data-height="' . esc_attr( $row_inner_height_percent ) . '"';
} else {
	$row_inner_height_percent = '';
}

if ($equal_height == 'yes') {
	$row_classes[] = 'unequal';
}

if ($gutter_size == '0') {
	$row_classes[] = 'col-no-gutter';
} elseif ($gutter_size == '1') {
	$row_classes[] = 'col-one-gutter';
} elseif ($gutter_size == '2') {
	$row_classes[] = 'col-half-gutter';
} elseif ($gutter_size == '4') {
	$row_classes[] = 'col-double-gutter';
}

if (!$with_slider) {
	if ($override_padding == 'yes') {
		if ($top_padding == '0') {
			$row_classes[] = 'no-top-padding';
		} elseif ($top_padding == '1') {
			$row_classes[] = 'one-top-padding';
		} elseif ($top_padding == '2') {
			$row_classes[] = 'single-top-padding';
		} elseif ($top_padding == '3') {
			$row_classes[] = 'double-top-padding';
		} elseif ($top_padding == '4') {
			$row_classes[] = 'triple-top-padding';
		} elseif ($top_padding == '5') {
			$row_classes[] = 'quad-top-padding';
		} elseif ($top_padding == '6') {
			$row_classes[] = 'penta-top-padding';
		} elseif ($top_padding == '7') {
			$row_classes[] = 'exa-top-padding';
		}

		if ($bottom_padding == '0') {
			$row_classes[] = 'no-bottom-padding';
		} elseif ($bottom_padding == '1') {
			$row_classes[] = 'one-bottom-padding';
		} elseif ($bottom_padding == '2') {
			$row_classes[] = 'single-bottom-padding';
		} elseif ($bottom_padding == '3') {
			$row_classes[] = 'double-bottom-padding';
		} elseif ($bottom_padding == '4') {
			$row_classes[] = 'triple-bottom-padding';
		} elseif ($bottom_padding == '5') {
			$row_classes[] = 'quad-bottom-padding';
		} elseif ($bottom_padding == '6') {
			$row_classes[] = 'penta-bottom-padding';
		} elseif ($bottom_padding == '7') {
			$row_classes[] = 'exa-bottom-padding';
		}

		if ($h_padding == '0') {
			$row_classes[] = 'no-h-padding';
		} elseif ($h_padding == '1') {
			$row_classes[] = 'one-h-padding';
		} elseif ($h_padding == '2') {
			$row_classes[] = 'single-h-padding';
		} elseif ($h_padding == '3') {
			$row_classes[] = 'double-h-padding';
		} elseif ($h_padding == '4') {
			$row_classes[] = 'triple-h-padding';
		} elseif ($h_padding == '5') {
			$row_classes[] = 'quad-h-padding';
		} elseif ($h_padding == '6') {
			$row_classes[] = 'penta-h-padding';
		} elseif ($h_padding == '7'){
			$row_classes[] = 'exa-h-padding';
		}
	}
} else {
	$row_classes[] = 'no-top-padding';
	$row_classes[] = 'no-bottom-padding';
	$row_classes[] = 'no-h-padding';
	$unlock_row = 'yes';
}

if ($css !== '') {
	$row_cont_classes[] = trim(vc_shortcode_custom_css_class($css));
}
if ($border_color !== '') {
	$row_cont_classes[] = 'border-' . $border_color . '-color';
	if ($border_style !== '') {
		$row_style .= 'border-style: '.$border_style.';';
	}
}

if ($z_index !== '0' && $z_index !== '') {
	$row_style = 'z-index: '.$z_index.';';
}

if ($row_style !== '') {
	$row_style = ' style="' . esc_attr( $row_style ) . '"';
}

$row_cont_classes[] = 'row-internal';
$row_classes[] = 'row-child';
if ($limit_content === 'yes') {
	$row_classes[] = 'limit-width';
	$unlock_row = 'yes';
}

$row_cont_classes[] = 'row-container';
if ($kburns === 'yes') {
	$row_cont_classes[] = 'with-kburns';
} elseif ($kburns === 'zoom') {
	$row_cont_classes[] = 'with-zoomout';
}
if ($parallax === 'yes' && !uncode_is_full_page()) {
	$row_cont_classes[] = 'with-parallax';
}
if ($row_name !== '') {
	$row_cont_classes[] = 'onepage-section';
}

$row_inner_classes[] = 'row-inner';
if ($force_width_grid === 'yes') {
	$row_inner_classes[] = 'row-inner-force';
}

if ($desktop_visibility === 'yes') {
	$row_cont_classes[] = 'desktop-hidden';
}
if ($medium_visibility === 'yes') {
	$row_cont_classes[] = 'tablet-hidden';
}
if ($mobile_visibility === 'yes') {
	$row_cont_classes[] = 'mobile-hidden';
}

global $uncode_row_child, $uncode_row_parent;
$uncode_row_child = $uncode_row_parent = 12;

if ( ( isset($divider_top_svg) && $divider_top_svg !== '' ) || ( isset($divider_bottom_svg) && $divider_bottom_svg !== '' ) ) {
	$row_cont_classes[] = 'has-dividers';
}

$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $row_cont_classes ) ), $this->settings['base'], $atts ) );
$output.= '<div class="' . esc_attr(trim($css_class)) . '"' . $row_name . $row_style . '>';
if ($unlock_row === 'yes') {
	$output.= $background_div;
}
$output.= '<div class="' . esc_attr(trim(implode(' ', $row_classes))) . '"' . $row_inner_height_percent . $row_inline_style . '>';
if ($unlock_row !== 'yes') {
	$output.= $background_div;
}
if ( isset($divider_top_svg) && $divider_top_svg !== '' ) {
	$output.= $divider_top_svg;
}
if ( isset($divider_bottom_svg) && $divider_bottom_svg !== '' && $shape_bottom_safe != 'yes' ) {
	$output.= $divider_bottom_svg;
}
if (!$with_slider) {
	$output.= '<div class="' . esc_attr(trim(implode(' ', $row_inner_classes))) . '">';
}
$output.= $content;
if (!$with_slider) {
	$output.= '</div>';
}
$output.= '</div>';
if ( isset($divider_bottom_svg) && $divider_bottom_svg !== '' && $shape_bottom_safe == 'yes' ) {
	$output.= $divider_bottom_svg;
}
$output.= '</div>';
echo uncode_remove_p_tag($output);
