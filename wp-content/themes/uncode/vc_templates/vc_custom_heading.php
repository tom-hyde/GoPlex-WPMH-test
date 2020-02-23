<?php
$subheading = $subtext_one = $subtext_two = $heading_semantic = $text_size = $text_height = $text_space = $text_font = $text_weight = $text_transform = $text_italic = $text_color = $separator = $separator_color = $separator_double = $sub_text = $sub_lead = $sub_reduced = $desktop_visibility = $medium_visibility = $mobile_visibility = $css_animation = $animation_delay = $animation_speed = $interval_animation = $output = $el_class = $sub_class = $is_header = $auto_text = '';
extract( shortcode_atts( array(
	'subheading' => '',
	'subtext_one' => '',
	'subtext_two' => '',
	'heading_semantic' => 'h2',
	'text_size' => 'h2',
	'text_height' => '',
	'text_space' => '',
	'text_font' => '',
	'text_weight' => '',
	'text_transform' => '',
	'text_italic' => '',
	'text_color' => '',
	'separator' => '',
	'separator_color' => '',
	'separator_double' => '',
	'sub_text' => '',
	'sub_lead' => '',
	'sub_reduced' => '',
	'desktop_visibility' => '',
	'medium_visibility' => '',
	'mobile_visibility' => '',
	'css_animation' => '',
	'animation_delay' => '',
	'animation_speed' => '',
	'interval_animation' => '',
	'el_class' => '',
	'auto_text' => '',
	'is_header' => ''
), $atts ) );

$cont_classes = array('heading-text el-text');
$classes = array();
$sub_classes = array();
$separator_classes = array();
$div_data = array();
$data_size = array();

$fonts = (function_exists('ot_get_option')) ? ot_get_option('_uncode_font_groups') : array();
$headings_font = (function_exists('ot_get_option')) ? ot_get_option('_uncode_heading_font_family') : '';

$heading_font = array(
	$headings_font => ''
);

if (isset($fonts) && is_array($fonts)) {
	foreach ($fonts as $key => $value) {
		$heading_font[$value['_uncode_font_group_unique_id']] = urldecode($value['_uncode_font_group']);
		if ($value['_uncode_font_group'] === 'manual') {
			$heading_font[$value['_uncode_font_group_unique_id']] = $value['_uncode_font_manual'];
		}
	}
}

if ($text_font !== '') {
	$classes[] = $text_font;
}

if ($text_size !== '') {
	$classes[] = $text_size;
	if ($text_size === 'bigtext') {
		$cont_classes[] = 'heading-bigtext';
	}
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

if ($separator !== '') {
	$separator_classes[] = 'separator-break';
	if ($separator_color === 'yes') {
		$separator_classes[] = 'separator-accent';
	}
	if ($separator_double === 'yes') {
		$separator_classes[] = 'separator-double-padding';
	}
}

if ($desktop_visibility === 'yes') {
	$cont_classes[] = 'desktop-hidden';
}
if ($medium_visibility === 'yes') {
	$cont_classes[] = 'tablet-hidden';
}
if ($mobile_visibility === 'yes') {
	$cont_classes[] = 'mobile-hidden';
}


if ($css_animation !== '') {
	if ( $css_animation === 'curtain' || $css_animation === 'curtain-words' || $css_animation === 'single-slide' ||  $css_animation === 'single-slide-opposite' || $css_animation === 'typewriter' || $css_animation === 'single-curtain' ) {
		$cont_classes[] = $css_animation . ' animate_inner_when_almost_visible el-text-split';
		$classes[] = 'font-obs';
		if ($text_italic !== '') {
			$data_size['data-style'] = 'italic';
		} else {
			$data_size['data-style'] = 'normal';
		}
		if ($text_weight !== '') {
			$data_size['data-weight'] = esc_attr($text_weight);
		} else {
			$data_size['data-weight'] = ot_get_option('_uncode_heading_font_weight');
		}
		if ( isset($heading_font[$text_font]) ) {
			$data_font = wptexturize($heading_font[$text_font]);
		} elseif ( isset($heading_font[$headings_font]) ) {
			$data_font = wptexturize($heading_font[$headings_font]);
		}
		if ( isset($data_font) ) {
			$data_font = preg_replace( '/,\s+/', ',', $data_font );
			$data_font = preg_replace( '/\&\#(.*?);/', '', $data_font );
			$data_size['data-font'] = $data_font;
		}
	} else {
		$cont_classes[] = $css_animation . ' animate_when_almost_visible';
	}
	if ($animation_delay !== '') {
		$div_data['data-delay'] = $animation_delay;
	}
	if ($animation_speed !== '') {
		$div_data['data-speed'] = $animation_speed;
	}
	if ($interval_animation !== '') {
		$div_data['data-interval'] = $interval_animation;
	}

}

$cont_classes[] = trim($this->getExtraClass( $el_class ));

$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $div_data, array_keys($div_data));

$output .= '<div class="' . esc_attr(trim(implode( ' ', $cont_classes ))) . '" '.implode(' ', $div_data_attributes).'>';
if ($separator === 'over') {
	$output .= '<hr class="' . esc_attr(trim(implode( ' ', $separator_classes ))) . '" />';
}

if ( $is_header != 'yes' ) {
	if ( $auto_text == 'yes' ) {
		$content = get_the_title();
	} elseif ( $auto_text == 'excerpt' ) {
		$content = get_the_excerpt();
	}
}

$content = apply_filters('uncode_vc_custom_heading_content', $content, $auto_text, $is_header);

if ( strpos( $content, '[uncode_hl_text') !== false ) {
	$classes[] = 'font-obs';
}

preg_match_all("/(?:<h[0-6]>).*?(?:<\/h[0-6]>)/", $content, $tag_matches);
$content_tags = count($tag_matches[0]);

if ($content !== '') {

	$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $data_size, array_keys($data_size));

	if ( !$content_tags ) {
		$output .= '<' . $heading_semantic . ' class="' . esc_attr(trim(implode( ' ', $classes ))) . '" '.implode(' ', $div_data_attributes) .'>';
		if ($text_italic === 'yes') {
			$output .= '<i>';
		}
		if ( strpos($content, '[uncode_hl_text') !== false|| ( $css_animation === 'curtain' || $css_animation === 'curtain-words' || $css_animation === 'single-slide' ||  $css_animation === 'single-slide-opposite' || $css_animation === 'typewriter' || $css_animation === 'single-curtain' ) ) {
			$content = strip_tags($content, '<br>');
			$span_classes = ' class="heading-text-inner"';
			$split_in_words = preg_split('/(\s+)|(<[^>]*[^\/]>)|(\[|\]+)/i', $content, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
			if ( isset($split_in_words) ) {
				$content = '';
				$skip_split = false;
				$skip_tag = false;
				$empty_space = $empty_space_2 = '';
				$counter_word = 0;
				foreach ($split_in_words as $key => $word) {
					if ( $word === '[' || substr( $word, 0, 1 ) === '[' ) {
						$skip_split = true;
					}
					if ( $word === '<' || substr( $word, 0, 1 ) === '<' ) {
						$skip_tag = true;
					}
					if ( $skip_split || $skip_tag ) {
						$content .= $empty_space_2 . $word;
						$empty_space = $empty_space_2 = '';
					} elseif ( strpos($word, "\n") !== false ) {
						$content .= "\n";
					} elseif ( strlen(trim($word)) == 0 && $word !== "\n" ) {
						$empty_space = '<span class="split-word-empty">&nbsp;</span>';
						$empty_space_2 = '<span class="split-word split-word-empty">&nbsp;</span>';
					} else {
						$counter_word++;
						$content .= '<span class="split-word word' . $counter_word . '"><span class="split-word-flow"><span class="split-word-inner">' . $empty_space . $word . '</span></span></span>';
						$empty_space = $empty_space_2 = '';
					}
					if ( $word === ']' || substr($word, -1) === ']' ) {
						$skip_split = false;
					}
					if ( $word === '>' || substr($word, -1) === '>' ) {
						$skip_tag = false;
					}
				}
			}
			if ( $css_animation === 'single-curtain' || $css_animation === 'typewriter' ) {
				$split_content = preg_split('/(?<!^)(?!$)(?!&(?!(amp|gt|lt|quot))[^\s]*)/u', $content );
			}
			if ( isset($split_content) ) {
				$content = '';
				$skip_split = false;
				$skip_tag = false;
				$skip_ent = false;
				foreach ($split_content as $key => $char) {
					if ( $char === '[' || substr( $char, 0, 1 ) === '[' ) {
						$skip_split = true;
					}
					if ( $char === '<' || substr( $char, 0, 1 ) === '<' ) {
						$skip_tag = true;
					}
					if ( $char === '&' || substr( $char, 0, 1 ) === '&' ) {
						$skip_ent = true;
					}
					if ( $skip_split || $skip_tag || $skip_ent === 'continue' || ctype_space($char) ) {
						$content .= $char;
					} elseif ( $skip_ent  ) {
						$content .= '<span class="split-char char' . $key . '">' . $char;
						$skip_ent = 'continue';
					} else {
						$content .= '<span class="split-char char' . $key . '">' . $char . '</span>';
					}
					if ( $char === ']' || substr($char, -1) === ']' ) {
						$skip_split = false;
					}
					if ( $char === '>' || substr($char, -1) === '>' ) {
						$skip_tag = false;
					}
					if ( $skip_ent == 'continue' && ( $char === ';' || substr($char, -1) === ';' ) ) {
						$skip_ent = false;
						$content .= '</span>';
					}
				}
			}
		} else {
			$span_classes = '';
		}
		$output .= '<span' . $span_classes . '>';
	}
	$content = trim($content);
	$title_lines = explode("\n", $content);
	$lines_counter = count($title_lines);
	if ($lines_counter > 1 && !$content_tags) {
		foreach ($title_lines as $key => $value) {
			preg_match_all("%\[uncode_hl_text(.*?)\]%i", $value, $match_span_starts);
			preg_match_all("%\[\/uncode_hl_text\]%i", $value, $match_span_ends);
			$value = trim($value);

			if ( count( $match_span_starts[0] ) > count( $match_span_ends[0] ) ) {
				$shortcode_end = '[/uncode_hl_text]';
				$shortcode_start = $match_span_starts[0][ count($match_span_starts[0])-1 ];
			} else {
				$shortcode_end = $shortcode_start = '';
			}
			$output .= $value;
			if ($value !== '' && ($lines_counter - 1 !== $key)) {
				$output .= $shortcode_end . '</span><span' . $span_classes . '>' . $shortcode_start;
			}
		}
	} else {
		if ( $content_tags ) {
			$content = wpautop($content);
		}
		$output .= $content;
	}
	if ( !$content_tags ) {
		$output .= '</span>';
		if ($text_italic === 'yes') {
			$output .= '</i>';
		}
		$output .= '</' . $heading_semantic . '>';
	}
}
if ($separator === 'yes') {
	$output .= '<hr class="' . esc_attr(trim(implode( ' ', $separator_classes ))) . '" />';
}
$subheading = apply_filters('uncode_vc_custom_heading_subheading', $subheading, $auto_text, $is_header);
if ($subheading !== '') {
	if ($sub_lead === 'yes') {
		$sub_lead = ' text-lead';
	}
	if ($sub_reduced === 'yes') {
		$sub_reduced = ' text-top-reduced';
	}
	if ($sub_lead !== '' || $sub_reduced !== '') {
		$sub_class = ' class="'.esc_attr(trim($sub_lead.$sub_reduced)).'"';
	}
	$output .= '<div'.$sub_class.'>' . uncode_remove_p_tag($subheading, true) . '</div>';
}
if ($separator === 'under') {
	$output .= '<hr class="' . esc_attr(trim(implode( ' ', $separator_classes ))) . '" />';
}
$output .= '</div>';
$output .= '<div class="clear"></div>';

echo uncode_remove_p_tag($output);

