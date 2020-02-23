<?php

$title = $heading_semantic = $text_font = $text_size = $text_weight = $text_height = $text_space = $text_lead = $icon = $icon_image = $icon_automatic = $position = $icon_color = $background_style = $size = $outline = $display = $shadow = $add_margin = $text_reduced = $el_class = $link = $link_text = $media_lightbox = $css_animation = $animation_delay = $animation_speed = $background_color = $a_title = $a_target = $a_rel = $lightbox_data = $lightbox_data_title = $title_aligned_icon = $linked_title = $media_size = '';

$defaults = array(
	'title' => '',
	'heading_semantic' => 'h3',
	'text_font' => '',
	'text_size' => 'h3',
	'text_weight' => '',
	'text_height' => '',
	'text_space' => '',
	'icon' => 'fa fa-adjust',
	'icon_image' => '',
	'icon_automatic' => '',
	'position' => 'top',
	'icon_color' => 'default',
	'background_style' => '',
	'size' => 'fa-1x',
	'outline' => '',
	'display' => '',
	'shadow' => '',
	'add_margin' => '',
	'text_lead' => '',
	'text_reduced' => '',
	'el_class' => '',
	'link' => '',
	'link_text' => '',
	'media_lightbox' => '',
	'css_animation' => '',
	'animation_delay' => '',
	'animation_speed' => '',
	'title_aligned_icon' => '',
	'media_size' => '',
);
/** @var array $atts - shortcode attributes */
$atts = vc_shortcode_attribute_parse( $defaults, $atts );
extract( $atts );

// Prepare icon classes
$container_class = array();
$icon_container_class = array();
$title_class = array();
$wrapper_class = array();
$automatic_class = array();
$classes = array();
$div_data = array();

if ($icon_color === '') {
	$icon_color = 'default';
}
if ($position === '') {
	$position = 'top';
}

$class = $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class, $this->settings['base'], $atts );

if ($css_animation !== '') {
	$container_class[] = 'animate_when_almost_visible ' . $css_animation;
	if ($animation_delay !== '') {
		$div_data['data-delay'] = $animation_delay;
	}
	if ($animation_speed !== '') {
		$div_data['data-speed'] = $animation_speed;
	}
}

$container_class[] = 'icon-box';
$container_class[] = 'icon-box-' . $position;
$icon_container_class[] = 'icon-box-icon fa-container';
$icon_container_class[] = $css_class;

if ($display === 'inline') {
	$container_class[] = 'icon-inline';
}

$classes[] = $icon;

if ( strlen( $background_style ) > 0 ) {
	$has_style = true;
	$wrapper_class[] = 'fa fa-stack';
	$wrapper_class[] = $size;
	$wrapper_class[] = 'btn-' . $icon_color;
	$wrapper_class[] = $background_style;
	if ($outline === 'yes') {
		$wrapper_class[] = 'btn-outline';
	}
	if ( $icon_automatic !== '' ) {
		$wrapper_class[] = 'icon-animated';
		$automatic_class[] = 'icon-animated';
		if ($outline === 'yes') {
			$automatic_class[] = 'btn-outline';
		}
		if ($shadow === 'yes') {
			$automatic_class[] = 'btn-shadow';
		}
		$automatic_class[] = 'btn-' . $icon_color;
	} else {
		if ($shadow === 'yes') {
			$wrapper_class[] = 'btn-shadow';
		}
	}
} else {
	$wrapper_class[] = 'text-' . $icon_color . '-color';
	$classes[] = $size;
	$classes[] = 'fa-fw';
}

$title_class[] = $text_font;
$title_class[] = $text_size;
if ($text_weight !== '') {
	$title_class[] = 'font-weight-' . $text_weight;
}
if ($text_height !== '') {
	$title_class[] = $text_height;
}
if ($text_space !== '') {
	$title_class[] = $text_space;
}


$link = ( $link == '||' ) ? '' : $link;
$link = vc_build_link( $link );
$a_href = $link['url'];
if ($link['title'] !== '') {
	$a_title = ' title="' . esc_attr( $link['title'] ) . '"';
}
if ($link['target'] !== '') {
	$a_target = ' target="' . esc_attr( trim($link['target']) ) . '"';
}
if ($link['rel'] !== '') {
	$a_rel = ' rel="' . esc_attr( trim($link['rel']) ) . '"';
}

if ($media_lightbox !== '') {
	$media_attributes = uncode_get_media_info($media_lightbox);

	if (!isset($media_attributes)) {
		$media_attributes = new stdClass();
	}

	if ( isset( $media_attributes->metadata ) ) {
		$media_metavalues = unserialize($media_attributes->metadata);
		$media_mime = $media_attributes->post_mime_type;
		$media_dimensions = '';
		$video_src = '';
		if (strpos($media_mime, 'image/') !== false && $media_mime !== 'image/url' && isset($media_metavalues['width']) && isset($media_metavalues['height'])) {
			$image_orig_w = $media_metavalues['width'];
			$image_orig_h = $media_metavalues['height'];
			$big_image = uncode_resize_image($media_attributes->id, $media_attributes->guid, $media_attributes->path, $image_orig_w, $image_orig_h, 12, null, false);
			$a_href = $big_image['url'];
		} else {
			if ($media_mime === 'image/url') {
				$a_href = $media_attributes->guid;
			} else {
				$media_oembed = uncode_get_oembed($media_lightbox, $media_attributes->guid, $media_attributes->post_mime_type, false, $media_attributes->post_excerpt, $media_attributes->post_content, true);
				$consent_id = str_replace( 'oembed/', '', $media_mime );
				if ( uncode_privacy_allow_content( $consent_id ) === false ) {
					$a_href = '#inline-' . esc_attr( $media_lightbox ) . '" data-type="inline" target="#inline' . esc_attr( $media_lightbox );
					$inline_hidden = '<div id="inline-' . esc_attr( $media_lightbox ) . '" class="ilightbox-html" style="display: none;">' . $media_oembed['code'] . '</div>';
					$poster_th_id = get_post_meta($media_lightbox, "_uncode_poster_image", true);
					$poster_attributes = uncode_get_media_info($poster_th_id);
					if ( is_object($poster_attributes) ) {
						$poster_metavalues = unserialize($poster_attributes->metadata);
						$media_dimensions = 'width:' . esc_attr($poster_metavalues['width']) . ',';
						$media_dimensions .= 'height:' . esc_attr($poster_metavalues['height']) . ',';
					}
				} else {
					$a_href = $media_oembed['code'];
				}
			}
		}
		if (isset($media_metavalues['width']) && isset($media_metavalues['height'])) {
			$media_dimensions = 'width:' . $media_metavalues['width'] . ',';
			$media_dimensions .= 'height:' . $media_metavalues['height'] . ',';
		}
		if (isset($media_attributes->post_mime_type) && strpos($media_attributes->post_mime_type, 'video/') !== false) {
			$video_src .= 'html5video:{preload:\'true\',';
			$video_autoplay = get_post_meta($media_lightbox, "_uncode_video_autoplay", true);
			if ($video_autoplay) {
				$video_src .= 'autoplay:\'true\',';
			}
			$video_loop = get_post_meta($media_lightbox, "_uncode_video_loop", true);
			if ($video_loop) {
				$video_src .= 'loop:\'true\',';
			}
			$alt_videos = get_post_meta($media_lightbox, "_uncode_video_alternative", true);
			if (!empty($alt_videos)) {
				foreach ($alt_videos as $key => $value) {
					$exloded_url = explode(".", strtolower($value));
					$ext = end($exloded_url);
					if ($ext !== '') {
						$video_src .= $ext . ":'" . $value."',";
					}
				}
			}
			$video_src .= '},';
		}
		$lightbox_options = ' data-options="' . esc_attr( $media_dimensions . $video_src ) . '"';
		$lightbox_data = ' data-lbox="ilightbox_single-' . uncode_big_rand() . '"' . $lightbox_options;
		$lightbox_data_title = ' data-lbox="ilightbox_single-' . uncode_big_rand() . '"' . $lightbox_options;
	}
}

if ($a_href === '') {
	$wrapper_class[] = 'btn-disable-hover';
}

// Prepare text area
$output_text = '';
$icon_box_size = ' icon-box-'.$size.((strlen( $background_style ) > 0) ? '-back' : '');
$icon_box_size = $title_aligned_icon == '' ? $icon_box_size : '';
if ($title !== '') {
	if ( $a_href !== '' && $linked_title !== '' ) {
		$title = '<a href="'. $a_href.'"'.$a_title.$a_target.$a_rel.$lightbox_data_title.'>' . $title . '</a>';
	}
	$output_text .= '<div class="icon-box-heading' . esc_attr( $icon_box_size ) . '"><' . esc_attr( $heading_semantic ) . ' class="' . esc_attr(trim(implode(' ', $title_class))) . '">' . $title . '</' . esc_attr( $heading_semantic ) . '></div>';
}

$content_stripped = strip_tags($content, '<p>');

if ( $content_stripped === $content ) {
	if ( trim( $content ) !== '' && $content_stripped === $content ) {
		$content = trim( nl2br( $content ) );
		if ($add_margin === 'yes') {
			$add_margin = ' add-margin';
		} else {
			$add_margin = '';
		}

		$text_classes = ( $text_reduced === 'yes' ) ? 'text-top-reduced ' : '';
		$text_classes .= ( $text_lead === 'yes' ) ? 'text-lead ' : '';

		if (strpos($content,'<p') !== false) {
			if ($text_classes !== '') {
				$content = preg_replace('/<p/', '<p class="' . esc_attr( trim($text_classes) ) . '"', $content, 1);
			}
			$output_text .= $content;
		} else {
			if ($text_classes !== '') {
				$output_text .= '<p class="' . esc_attr( trim($text_classes) ) . '">' . $content . '</p>' ;
			} else {
				$output_text .= '<p>' . $content . '</p>' ;
			}
		}
	}
} else {
	$output_text .= uncode_remove_p_tag($content, true);
}

if ($link_text !== '' && $a_href !== '') {
	$output_text .= '<p class="text-bold"><a class="btn btn-link" href="'. $a_href.'"'.$a_title.$a_target.$a_rel.'>' . $link_text . '</a></p>' ;
}

if ($output_text !== '') {
	$output_text = '<div class="icon-box-content' . esc_attr( $add_margin ) . '">' . $output_text . '</div>';
}

// Prepare icon area
if ($position === 'right' || $position === 'left') {
	$media_size = floatval( $media_size ) == 0 ? 50 : $media_size;
	$icon_container_style = ( $media_size !== '' && floatval( $media_size ) != 0 && $icon_image !== '' ) ? 'width:' . floatval( $media_size ) . 'px;' : '';
} else {
	$icon_container_style = '';
}
$icon_container_style .= ( $content === '' && $title === '' ) ? 'margin-bottom: 0px;' : '';
$icon_container_style = $icon_container_style !== '' ? ' style="' . $icon_container_style . '"' : '';

$tag_start = ($a_href !== '') ? 'a href="'. $a_href .'"'.$a_title.$a_target.$a_rel.$lightbox_data : 'span';
$tag_end = ($a_href !== '') ? 'a' : 'span';
$output_icon = '';
$output_icon = '<div class="'.esc_attr(trim(implode(' ', $icon_container_class))).'"' . $icon_container_style . '>';
if ($a_href !== '') {
	$wrapper_class[] = 'custom-link';
}
$output_icon .=	'<'.$tag_start.' class="' . esc_attr(implode(' ', $wrapper_class)) . '">';
if ($icon_image === '') {
	if ( $icon_automatic !== '' ) {
		$output_icon .=	'<span class="icon-automatic-video icon-automatic-' . $size . ' ' . esc_attr(implode(' ', $automatic_class)) . '"><span class="icon-automatic-video-inner-bg"></span><span class="icon-automatic-video-outer-bg"></span></span>';
	}
	$output_icon .=	'<i class="' . esc_attr(trim(implode(' ', $classes))) . '"></i>';
} else {
	$block_data = array();
	$lightbox_classes = array();
	$block_data['media_id'] = $icon_image;
	$block_data['single_width'] = 12;
	if (isset($div_data['data-delay']) && $div_data['data-delay'] !== '') {
		$block_data['delay'] = $animation_delay;
	}
	$media_code = uncode_create_single_block($block_data, rand(), 'masonry', '', $lightbox_classes, false, false);
	$media_alt = (isset($media_code['alt'])) ? $media_code['alt'] : '';

	if ( $icon_automatic !== '' ) {
		$output_icon .= $media_code['code'];
		if ($position === 'right' || $position === 'left') {
			if (strpos($media_code['code'], 'style="width:100%"') !== false) {
				$container_class[] = 'icon-expand';
			}
		}
	} elseif ($media_code['type'] === 'image') {
		$container_class[] = 'icon-media-image';
		$output_icon .= '<img src="' . esc_attr( $media_code['code'] ) .'" width="' . esc_attr( $media_code['width'] ) .'" height="' . esc_attr( $media_code['height'] ) .'" alt="' . esc_attr( $media_alt ) . '" />';
	} else {
		$output_icon .= $media_code['code'];
		if ($position === 'right' || $position === 'left') {
			if (strpos($media_code['code'], 'style="width:100%"') !== false) {
				$container_class[] = 'icon-expand';
			}
		}
	}
}
$output_icon .=	'</'.$tag_end.'>';
$output_icon .='</div>';

$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $div_data, array_keys($div_data));

if ( isset( $inline_hidden ) && $inline_hidden !== '' ) {
	echo uncode_switch_stock_string( $inline_hidden );
}

$output ='<div class="' . esc_attr(trim(implode(' ', $container_class))) . '" ' . implode(' ', $div_data_attributes) . '>';
switch ($position) {
	case 'bottom':
	case 'right':
		$output .= $output_text . $output_icon;
		break;
	default:
		$output .= $output_icon . $output_text;
		break;
}
$output .='</div>';

echo uncode_switch_stock_string( $output );
