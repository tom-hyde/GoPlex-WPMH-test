<?php
$size = $css_animation = $animation_delay = $animation_speed = $el_class = '';
extract( shortcode_atts( array(
	'size' => '',
	'css_animation' => '',
	'animation_delay' => '',
	'animation_speed' => '',
	'el_class' => '',
), $atts ) );

$socials = apply_filters( 'uncode_vc_socials', ot_get_option( '_uncode_social_list','',false,true) );
$output = '';

$cont_classes = array('uncode-vc-social');

$cont_classes[] = trim($this->getExtraClass( $el_class ));

if ( $size !== '' ) {
	$cont_classes[] = 'icons-lead';
}

if (isset($socials) && !empty($socials) && count($socials) > 0) {
	$output .= '<div class="' . esc_attr(trim(implode( ' ', $cont_classes ))) . '">';
	foreach ($socials as $index => $social) {
		if ($social['_uncode_social'] !== '') {
			$div_data = array();
			$icon_classes = array('social-icon icon-box icon-box-top icon-inline');
			if ($css_animation !== '') {
				$icon_classes[] = $css_animation . ' animate_when_almost_visible';
				$icon_delay = $index * 50;
				if ($animation_delay !== '') {
					$icon_delay += $animation_delay;
				}
				$div_data['data-delay'] = $icon_delay;
				if ($animation_speed !== '') {
					$div_data['data-speed'] = $animation_speed;
				}
			}
			$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $div_data, array_keys($div_data));
			$output .= '<div class="' . esc_attr(trim(implode( ' ', $icon_classes ))) . '"'.implode(' ', $div_data_attributes).'><a href="'.esc_url($social['_uncode_link']).'" target="_blank"><i class="'.esc_attr($social['_uncode_social']).'"></i></a></div>';
		}
	}
	$output .= '</div>';
}

echo uncode_remove_p_tag($output);
