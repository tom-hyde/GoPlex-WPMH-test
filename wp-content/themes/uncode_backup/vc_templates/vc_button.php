<?php
$url = $link = $target = $button_color = $size = $width = $text_skin = $hover_fx = $outline = $wide = $icon = $icon_position = $icon_animation = $border_animation = $radius = $shadow = $shadow_weight = $italic = $display = $top_margin = $onclick = $rel = $media_lightbox = $lbox_skin = $lbox_dir = $lbox_title = $lbox_caption = $lbox_social = $lbox_deep = $lbox_no_tmb = $lbox_no_arrows = $lbox_connected = $css_animation = $animation_delay = $animation_speed = $el_class = $lightbox_data = $custom_typo = $font_family = $font_weight = $text_transform = $letter_spacing = $border_width = $btn_link_size = '';
extract(shortcode_atts(array(
	'url' => '',
	'link' => '',
	'target' => 'self',
	'button_color' => 'default',
	'size' => '',
	'width' => '',
	'text_skin' => '',
	'outline' => '',
	'hover_fx' => '',
	'wide' => 'no',
	'icon' => '',
	'icon_position' => 'left',
	'icon_animation' => '',
	'border_animation' => '',
	'radius' => '',
	'shadow' => '',
	'shadow_weight' => '',
	'custom_typo' => '',
	'font_family' => '',
	'font_weight' => '',
	'text_transform' => 'initial',
	'letter_spacing' => '',
	'border_width' => '',
	'btn_link_size' => '',
	'italic' => '',
	'display' => '',
	'top_margin' => '',
	'onclick' => '',
	'rel' => '',
	'media_lightbox' => '',
	'lbox_skin' => '',
	'lbox_dir' => '',
	'lbox_title' => '',
	'lbox_caption' => '',
	'lbox_social' => '',
	'lbox_deep' => '',
	'lbox_no_tmb' => '',
	'lbox_no_arrows' => '',
	'lbox_connected' => '',
	'css_animation' => '',
	'animation_delay' => '',
	'animation_speed' => '',
	'el_class' => ''
) , $atts));

global $lightbox_id, $adaptive_images;

//parse link
$link = ( $link == '||' ) ? '' : $link;
$link = vc_build_link( $link );
$a_href = $link['url'];
$a_title = $link['title'];
$a_target = $link['target'];
$lightbox_data = '';

if ($media_lightbox !== '') {
	$lightbox_classes = array();
	if ( get_post_mime_type($media_lightbox) == 'oembed/gallery' && wp_get_post_parent_id($media_lightbox) ) {

		$parent_id = wp_get_post_parent_id($media_lightbox);
		$media_album_ids = get_post_meta($parent_id, '_uncode_featured_media', true);//string of images in the album
		$media_album_ids_arr = explode(',', $media_album_ids);//array of images in the album
		$a_href = '#';
		$media_album = '';
		$media_dimensions = '';//it will stay empty
		$album_item_dimensions = '';
		$inline_hidden = '';

		foreach ($media_album_ids_arr as $_key => $_value) {
			$album_item_attributes = uncode_get_album_item($_value);
			$album_th_id = $album_item_attributes['poster'];
			if ( $album_th_id == '' ) {
				continue;
			}
			$thumb_attributes = uncode_get_media_info($album_th_id);
			$album_th_metavalues = unserialize($thumb_attributes->metadata);
			$album_th_w = $album_th_metavalues['width'];
			$album_th_h = $album_th_metavalues['height'];
			if ($album_item_attributes) {
				$album_item_title = $lbox_title !== '' ? apply_filters( 'uncode_media_attribute_title', $thumb_attributes->post_title, $_value) : '';
				$album_item_caption = $lbox_caption !== '' ? apply_filters( 'uncode_media_attribute_excerpt', $thumb_attributes->post_excerpt, $_value) : '';
				if (isset($album_item_attributes['width']) && isset($album_item_attributes['height'])) {
					$album_item_dimensions .= '{';
					if (
						$album_item_attributes['mime_type'] === 'oembed/vimeo' && uncode_privacy_allow_content( 'vimeo' ) === false
						||
						$album_item_attributes['mime_type'] === 'oembed/youtube' && uncode_privacy_allow_content( 'youtube' ) === false
						||
						$album_item_attributes['mime_type'] === 'oembed/spotify' && uncode_privacy_allow_content( 'spotify' ) === false
						||
						$album_item_attributes['mime_type'] === 'oembed/soundcloud' && uncode_privacy_allow_content( 'soundcloud' ) === false
						||
						$album_item_attributes['mime_type'] === 'oembed/twitter' && get_post_meta($album_th_id, "_uncode_social_original", true) && ! uncode_privacy_allow_content( 'twitter' )
						||
						$album_item_attributes['mime_type'] === 'oembed/facebook' && uncode_privacy_allow_content( 'facebook' ) === false
					) {
						$poster_th_id = get_post_meta($album_th_id, "_uncode_poster_image", true);
						$poster_metavalues = unserialize($thumb_attributes->metadata);
						$album_item_dimensions .= '"width":"' . esc_attr($poster_metavalues['width']) . '",';
						$album_item_dimensions .= '"height":"' . esc_attr($poster_metavalues['height']) . '",';
						$resize_album_item = wp_get_attachment_image_src($poster_th_id, 'medium');
						$album_item_dimensions .= '"thumbnail":"' . esc_url($resize_album_item[0]) . '",';
						$album_item_dimensions .= '"url":"' . esc_attr('#inline-' . $parent_id . '-' . $album_th_id) . '","type":"inline"';
						$inline_hidden .= '<div id="inline-' . esc_attr( $parent_id . '-' . $album_th_id ) . '" class="ilightbox-html" style="display: none;">' . $album_item_attributes['url'] . '</div>';
						$a_href = '#inline-' . esc_attr( $parent_id . '-' . $album_th_id );
						apply_filters( 'uncode_before_checking_consent', true, $album_item_attributes['mime_type'] );
					} else {
						$album_item_dimensions .= '"width":"' . esc_attr($album_item_attributes['width']) . '",';
						$album_item_dimensions .= '"height":"' . esc_attr($album_item_attributes['height']) . '",';
						$resize_album_item = wp_get_attachment_image_src($album_th_id, 'medium');
						$album_item_dimensions .= '"thumbnail":"' . esc_url($resize_album_item[0]) . '",';
						$album_item_dimensions .= '"url":"' . esc_url($album_item_attributes['url']) . '"';
					}
					$album_item_dimensions .= '}';
				}
			}
			if ( $_key+1 < count($media_album_ids_arr) ) {
				$album_item_dimensions .= ',';
			}
		}
		$lightbox_data .= ' data-album=\'[' . $album_item_dimensions . ']\'';

		if ($lbox_skin !== '') {
			$lightbox_classes['data-skin'] = $lbox_skin;
		}
		if ($lbox_dir !== '') {
			$lightbox_classes['data-dir'] = $lbox_dir;
		}
		if ($lbox_social !== '') {
			$lightbox_classes['data-social'] = true;
		}
		if ($lbox_deep !== '') {
			$lightbox_classes['data-deep'] = 'gallery_' . $media_lightbox;
		}
		if ($lbox_no_tmb !== '') {
			$lightbox_classes['data-notmb'] = true;
		}
		if ($lbox_no_arrows !== '') {
			$lightbox_classes['data-noarr'] = true;
		}
		if (count($lightbox_classes) === 0) {
			$lightbox_classes['data-active'] = true;
		}
		if ($lbox_connected === 'yes') {
			if (!isset($lightbox_id) || $lightbox_id === '') {
				$lightbox_id = uncode_big_rand();
			}
			$lbox_id = $lightbox_id;
		} else {
			$lbox_id = $_value;
		}

		$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $lightbox_classes, array_keys($lightbox_classes));

	} else {

		$media_attributes = uncode_get_media_info($media_lightbox);
		if (isset($media_attributes)) {
			$media_metavalues = unserialize($media_attributes->metadata);
			$media_mime = $media_attributes->post_mime_type;
			$media_dimensions = '';
			$video_src = '';
			if (isset($media_metavalues['width']) && isset($media_metavalues['height'])) {
				$media_dimensions = 'width:' . $media_metavalues['width'] . ',';
				$media_dimensions .= 'height:' . $media_metavalues['height'] . ',';
			}
			if (strpos($media_mime, 'image/') !== false && $media_mime !== 'image/url' && isset($media_metavalues['width']) && isset($media_metavalues['height'])) {
				$image_orig_w = $media_metavalues['width'];
				$image_orig_h = $media_metavalues['height'];
				if ($adaptive_images === 'on') {
					$adaptive_images = 'off';
					$big_image = uncode_resize_image($media_attributes->id, $media_attributes->guid, $media_attributes->path, $image_orig_w, $image_orig_h, 12, null, false);
					$adaptive_images = 'on';
				} else {
					$big_image = uncode_resize_image($media_attributes->id, $media_attributes->guid, $media_attributes->path, $image_orig_w, $image_orig_h, 12, null, false);
				}

				$a_href = $big_image['url'];
			} elseif ($media_mime === 'oembed/iframe') {
				$lightbox_classes['data-type'] = 'inline';
				$a_href = '#inline-' . $media_lightbox;
				echo '<div id="inline-' . esc_attr( $media_lightbox ) . '" class="ilightbox-html" style="display: none;">' . $media_attributes->post_content . '</div>';
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
	    			} elseif ($media_mime === 'oembed/html' || $media_mime === 'oembed/iframe') {
						$frame_id = 'frame-' . uncode_big_rand();
						$a_href = '#' . $frame_id;
						echo '<div id="' . esc_attr( $frame_id ) . '" style="display: none;">' . $media_attributes->post_content . '</div>';
					} else {
						$a_href = $media_oembed['code'];
					}
				}
			}

			if (isset($media_attributes->post_mime_type) && strpos($media_attributes->post_mime_type, 'video/') !== false) {
				$video_src .= 'html5video:{preload:\'true\',';
				$video_autoplay = get_post_meta($media_lightbox, "_uncode_video_autoplay", true);
				if ($video_autoplay) {
					$video_src .= 'autoplay:\'true\',';
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

			if ($lbox_skin !== '') {
				$lightbox_classes['data-skin'] = $lbox_skin;
			}
			if ($lbox_title !== '' && isset($media_attributes->post_title) && $media_attributes->post_title !== '') {
				$lightbox_classes['data-title'] = $media_attributes->post_title;
			}
			if ($lbox_caption !== '' && isset($media_attributes->post_excerpt) && $media_attributes->post_excerpt !== '') {
				$lightbox_classes['data-caption'] = $media_attributes->post_excerpt;
			}
			if ($lbox_dir !== '') {
				$lightbox_classes['data-dir'] = $lbox_dir;
			}
			if ($lbox_social !== '') {
				$lightbox_classes['data-social'] = true;
			}
			if ($lbox_deep !== '') {
				$lightbox_classes['data-deep'] = $media_lightbox;
			}
			if ($lbox_no_tmb !== '') {
				$lightbox_classes['data-notmb'] = true;
			}
			if ($lbox_no_arrows !== '') {
				$lightbox_classes['data-noarr'] = true;
			}
			if (count($lightbox_classes) === 0) {
				$lightbox_classes['data-active'] = true;
			}
			if ($lbox_connected === 'yes') {
				if (!isset($lightbox_id) || $lightbox_id === '') {
					$lightbox_id = uncode_big_rand();
				}
				$lbox_id = $lightbox_id;
			} else {
				$lbox_id = $media_lightbox;
			}

			$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $lightbox_classes, array_keys($lightbox_classes));

		}

		$lightbox_data .= ' data-options="' . esc_attr( $media_dimensions . $video_src ) . '"';

	}

	$lightbox_data .= ' ' . implode(' ', $div_data_attributes);
	$lightbox_data .= ' data-lbox="ilightbox_single-' . esc_attr( $lbox_id ) . '"';

}

// Prepare button classes
$wrapper_class = array('btn-container');
$classes = array('btn');
$div_data = array();

// Size class
$bigtext = false;
if ($size) {
	if ($size === 'link') {
		unset($classes[0]);
		if ( $btn_link_size !== '' ) {
			$classes[] = $btn_link_size;
		}
		if ( $btn_link_size === 'bigtext' ) {
			$bigtext = true;
		}
	} else {
		$classes[] = $size;
	}
}

if ($custom_typo==='yes') {
	$classes[] = 'btn-custom-typo';
	$classes[] = $font_family;
	if ( $font_weight !== '' ) {
		$classes[] = 'font-weight-' . $font_weight;
	}
	$classes[] = 'text-' . $text_transform;
	$classes[] = $letter_spacing;
}

if ($border_width!=='') {
	$classes[] = 'border-width-' . intval($border_width);
}

// Additional classes
if ($el_class) {
	$classes[] = $el_class;
}

// Color class
if ($button_color === '') {
	$button_color = 'default';
}
if ($button_color !== 'default') {
	if ($text_skin === 'yes') {
		$classes[] = 'btn-text-skin';
	}
}
if ($size !== 'btn-link' && $size !== 'link') {
	$classes[] = 'btn-' . $button_color;
} else {
	$classes[] = 'text-' . $button_color . '-color';
}


// Radius class
if ($radius) {
	$classes[] = $radius;
}

// Hover effect
$hover_fx = $hover_fx=='' ? ot_get_option('_uncode_button_hover') : $hover_fx;

// Outlined and flat classes
if ( $hover_fx == '' || $hover_fx == 'outlined' ) {
	if ($outline === 'yes' ) {
		$classes[] = 'btn-outline';
	}
} else {
	$classes[] = 'btn-flat';
}

// Shadow class
if ($shadow === 'yes') {
	$classes[] = 'btn-shadow';
	if ( $shadow_weight !== '' ) {
		$classes[] = 'btn-shadow-' . $shadow_weight;
	}
}

// Italic class
if ($italic === 'yes') {
	$classes[] = 'btn-italic';
}

// Wide class
if ($wide === 'yes') {
	$wrapper_class[] = 'btn-block';
	$classes[] = 'btn-block';
}

if ($display === 'inline') {
	// Add margin class
	if ($top_margin === 'yes') {
		$classes[] = 'btn-top-margin';
	}
	$wrapper_class[] = 'btn-inline';
}


// Prepare icon
if ($icon !== '') {
	$icon = '<i class="' . esc_attr($icon) . '"></i>';
	if ($icon_animation === 'yes') {
		$classes[] = 'btn-icon-fx';
	}
} else {
	$icon = '';
}

$content = trim($content);

if ($icon_position === 'right') {
	$content = $content . $icon;
	$classes[] = 'btn-icon-right';
} else {
	$content = $icon . $content;
	$classes[] = 'btn-icon-left';
}

if ($border_animation !== '') {
	$classes[] = $border_animation;
	$classes[] = 'btn-border-animated';
}
$el_class = $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . implode($classes, ' ') . $el_class, $this->settings['base'], $atts );

// Prepare onclick action
$onclick = ($onclick !== '') ? ' onClick="' . esc_attr( $onclick ) . '"' : '';

// Prepare rel attribute
$rel = ($rel) ? ' rel="' . esc_attr($rel) . '"' : '';

if ($css_animation !== '') {
	$wrapper_class[] = 'animate_when_almost_visible ' . $css_animation;
	if ($animation_delay !== '') {
		$div_data['data-delay'] = $animation_delay;
	}
	if ($animation_speed !== '') {
		$div_data['data-speed'] = $animation_speed;
	}
}

if ($width !== '') {
	$width = ' style="min-width:' . esc_attr( $width ) . 'px"';
}

$title = ($a_title !== '') ? ' title="' . esc_attr( $a_title ) . '"' : '';
$target = (trim($a_target) !== '') ? ' target="' . esc_attr( trim($a_target) ) . '"' : '';

$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $div_data, array_keys($div_data));

$bigtext_start = $bigtext_end = '';
if ( $bigtext ) {
	$bigtext_start = '<span>';
	$bigtext_end = '</span>';
}

if ( isset( $inline_hidden ) && $inline_hidden !== '' ) {
	echo uncode_switch_stock_string( $inline_hidden );
}

echo '<span class="' . esc_attr(trim(implode($wrapper_class, ' '))) . '" '.implode(' ', $div_data_attributes).'><a href="' . $a_href . '" class="custom-link ' . esc_attr(trim(implode($classes, ' '))) . '"' . $title . $target . $onclick . $rel . $lightbox_data . $width . '>' . $bigtext_start . do_shortcode($content) . $bigtext_end . '</a></span>';
