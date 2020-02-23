<?php

$items = $text_font = $text_size = $separator = $text_height = $text_space = $text_font = $text_weight = $text_transform = $text_italic = $text_color = $desktop_visibility = $medium_visibility = $mobile_visibility = $css_animation = $animation_delay = $animation_speed = '';

extract(shortcode_atts(array(
	'items' => 'Date,Categories,Author',
	'text_font' => '',
	'text_size' => '',
	'text_height' => '',
	'text_space' => '',
	'text_font' => '',
	'text_weight' => '',
	'text_transform' => '',
	'text_italic' => '',
	'text_color' => '',
	'separator' => '',
	'desktop_visibility' => '',
	'medium_visibility' => '',
	'mobile_visibility' => '',
	'css_animation' => '',
	'animation_delay' => '',
	'animation_speed' => ''
) , $atts));

$items = uncode_flatArray(vc_sorted_list_parse_value( $items ));
$output = '';

$classes = array('uncode-info-box');
if ($text_font !== '') {
	$classes[] = $text_font;
}

if ($desktop_visibility === 'yes') {
	$classes[] = 'desktop-hidden';
}
if ($medium_visibility === 'yes') {
	$classes[] = 'tablet-hidden';
}
if ($mobile_visibility === 'yes') {
	$classes[] = 'mobile-hidden';
}

$div_data = array();
if ($css_animation !== '') {
	$classes[] = $css_animation . ' animate_when_almost_visible';
	if ($animation_delay !== '') {
		$div_data['data-delay'] = $animation_delay;
	}
	if ($animation_speed !== '') {
		$div_data['data-speed'] = $animation_speed;
	}
}

if ($text_size !== '') {
	$classes[] = $text_size;
}
if ($text_height !== '') {
	$classes[] = $text_height;
}
if ($text_space !== '') {
	$classes[] = $text_space;
}
if ($text_weight !== '') {
	$classes[] = 'font-weight-' . $text_weight;
}
if ($text_color !== '') {
	$classes[] = 'text-' . $text_color . '-color';
}
if ($text_transform !== '') {
	$classes[] = 'text-' . $text_transform;
}
if ($text_italic === 'yes') {
	$classes[] = 'text-italic';
}

$top_avatar = false;

$count = count($items);
$i = 0;
foreach ($items as $key => $item) {
	$output_loop = '';

	if ( $key === 'Date' ) {
		/***************************
		*
		*  Date
		*
		***************************/
		$output_loop .= uncode_get_info_box( 'date', false );
	} elseif ( $key === 'Categories' ) {
		/***************************
		*
		*  Taxonomies
		*
		***************************/
		if (isset($item[0]) && !empty($item[0]) && $item[0]!== '') {
			$hide_prefix = true;
		} else {
			$hide_prefix = false;
		}
		$output_loop .= uncode_get_info_box( 'tax', $hide_prefix );
	} elseif ( $key === 'Author' ) {
		/***************************
		*
		*  Author
		*
		***************************/
		$avatar_atts = array();
		$avatar_att['no_prefix'] = false;
		if (isset($item[0]) && !empty($item[0]) && $item[0]!== '' && $item[0]!== 'no_avatar' && $item[0]!== 'top_avatar') {
			if ( $item[0] === 'Medium_avatar_size' ){
				$avatar_att['size'] = array(40, 'md');
			} elseif ( $item[0] === 'Large_avatar_size' ){
				$avatar_att['size'] = array(60, 'lg');
			} elseif ( $item[0] === 'Extra_avatar_size' ){
				$avatar_att['size'] = array(80, 'xl');
			} elseif ( $item[0] === 'do_not_display_prefix' ){
				$avatar_att['no_prefix'] = true;
			} else {
				$avatar_att['size'] = array(20, 'sm');
			}
		} else {
			$avatar_att['size'] = false;
		}

		if ( isset($item[1]) && !empty($item[1]) ) {
			if ( $item[1] === 'top_avatar' && $avatar_att['size'] !== false ) {
				$top_avatar = true;
			} elseif ( $item[1] === 'do_not_display_prefix' ){
				$avatar_att['no_prefix'] = true;
			}
		}

		if (isset($item[2]) && !empty($item[2]) && $item[2] === 'do_not_display_prefix') {
			$avatar_att['no_prefix'] = true;
		}
		$output_loop .= uncode_get_info_box( 'author', $avatar_att );
	} elseif ( $key === 'Comments' ) {
		/***************************
		*
		*  Comments
		*
		***************************/
		$output_loop .= uncode_get_info_box( 'comments', false );
	} elseif ( $key === 'Reading_time' ) {
		/***************************
		*
		*  Reading time
		*
		***************************/
		$output_loop .= uncode_get_info_box( 'reading', false );
	}

	if( ++$i < $count ) {
		$class_separator = $symbol = '';
		if ( $separator !== '' ) {
			$class_separator = ' uncode-ib-separator-symbol';
			switch ( $separator ) {
				case 'bullet':
					$symbol = '&bull;';
					break;

				case 'pipe':
				default:
					$symbol = '|';
					break;

			}
		}
		if ( $output_loop !== '' ) {
			$output_loop .= '<span class="uncode-ib-separator' . $class_separator . '">' . $symbol . '</span>';
		}
	}

	$output .= $output_loop;
}

if ( $top_avatar === true ) {
	$classes[] = 'top-avatar';
}

$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $div_data, array_keys($div_data));

echo '<div class="' . esc_attr(trim(implode( ' ', $classes ))) . '" '.implode(' ', $div_data_attributes).'>' . $output . '</div>';
