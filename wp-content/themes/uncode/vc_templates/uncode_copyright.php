<?php
$size = '';
extract(shortcode_atts(array(
	'css_animation' => '',
	'animation_delay' => '',
	'animation_speed' => '',
	'el_class' => '',
) , $atts));

$cont_classes = array('uncode-vc-social');
$div_data = array();

$cont_classes[] = trim($this->getExtraClass( $el_class ));

if ($css_animation !== '') {
	$cont_classes[] = $css_animation . ' animate_when_almost_visible';
	if ($animation_delay !== '') {
		$div_data['data-delay'] = $animation_delay;
	}
	if ($animation_speed !== '') {
		$div_data['data-speed'] = $animation_speed;
	}
}

$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $div_data, array_keys($div_data));

$footer_copyright = ot_get_option('_uncode_footer_copyright');
$footer_text = ot_get_option('_uncode_footer_text');
if ($footer_copyright !== 'off') {
	$footer_text_content = '<p>&copy; '.date("Y").' '.get_bloginfo('name') . ' <span style="white-space:nowrap;">' . esc_html__('All rights reserved','uncode') . '</span></p>';
} elseif ($footer_text !== '' && $footer_copyright === 'off') {
	$footer_text = ot_get_option('_uncode_footer_text');
	$footer_text_content = uncode_the_content($footer_text);
}



$output = '<div class="' . esc_attr(trim(implode( ' ', $cont_classes ))) . '"'.implode(' ', $div_data_attributes).'>';
$output .= $footer_text_content;
$output .= '</div>';

echo uncode_remove_p_tag($output);
