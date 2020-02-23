<?php

/**
 * Build background HTML
 */
if (!function_exists('uncode_get_back_html')) {
	function uncode_get_back_html($background = array() , $overlay_color = '', $overlay_color_alpha = '', $overlay_pattern = '', $type)
	{

		global $front_background_colors, $adaptive_images, $adaptive_images_async, $adaptive_images_async_blur;

		$back_color = $back_url = $back_repeat = $back_position = $back_attachment = $back_size = $background_mime = $background_url = $header_background_video = $header_background_selfvideo = $header_overlay_html = $header_overlay_style = $header_overlay_pattern_style = $back_mime_css = $back_html = $content_html = $carousel_html = $overlay_html = $adaptive_async_data = $adaptive_async_class = '';
		$poster_id = $is_carousel = $content_only_text = false;
		if (!empty($background['background-image'])) {
			$media_ids = explode(',', $background['background-image']);
			if (count($media_ids) === 1) {
				$back_attributes = uncode_get_media_info($background['background-image']);
				if (isset($back_attributes->post_mime_type)) {
					$background_mime = $back_attributes->post_mime_type;
				}

				$back_repeat = (isset($background['background-repeat']) && $background['background-repeat'] !== '') ? 'background-repeat: ' . $background['background-repeat'] . ';' : '';
				$back_position = (isset($background['background-position']) && $background['background-position'] !== '') ? 'background-position: ' . $background['background-position'] . ';' : '';
				$back_attachment = (isset($background['background-attachment']) && $background['background-attachment'] !== '') ? 'background-attachment: ' . $background['background-attachment'] . ';' : '';
				$back_size = (isset($background['background-size']) && $background['background-size'] !== '') ? 'background-size: ' . $background['background-size'] . ';' : '';

				$consent_id = str_replace( 'oembed/', '', $background_mime );
				uncode_privacy_check_needed( $consent_id );

				if ( wp_is_mobile() && ( $background_mime === 'oembed/vimeo' || $background_mime === 'oembed/youtube' ) ) {
					$background_mime = 'mobile_no_video';
				}

				$background_mobile_attr = '';
				if (strpos($background_mime, 'video/') !== false) {
					$video_mobile = get_post_meta($back_attributes->id, "_uncode_video_mobile_bg", true);
					if ( !$video_mobile && wp_is_mobile() ) {
						$background_mime = 'mobile_no_video';
					} else {
						$background_mobile_attr = 'playsinline ';
					}
				}

				if (strpos($background_mime, 'image/') !== false) {
					$back_metavalues = unserialize($back_attributes->metadata);
					$image_orig_w = $back_metavalues['width'];
					$image_orig_h = $back_metavalues['height'];
					if ($background_mime === 'image/gif' || $background_mime === 'image/url') {
						$background_url = $back_attributes->guid;
					} else {
						$resized_back = uncode_resize_image($back_attributes->id, $back_attributes->guid, $back_attributes->path, $image_orig_w, $image_orig_h, 12, '', false);
						$background_url = $resized_back['url'];
					}
					$back_url = ($background_url != '') ? 'background-image: url(' . $background_url . ');' : '';
					if ($adaptive_images === 'on' && $adaptive_images_async === 'on' && $background_mime !== 'image/gif' && $background_mime !== 'image/url') {
						$adaptive_async_class = ' adaptive-async';
						if ($adaptive_images_async_blur === 'on') {
							$adaptive_async_class .= ' async-blurred';
						}
						$adaptive_async_data = ' data-uniqueid="'.$background['background-image'].'-'.uncode_big_rand().'" data-guid="'.(is_array($back_attributes->guid) ? $back_attributes->guid['url'] : $back_attributes->guid).'" data-path="'.$back_attributes->path.'" data-width="'.$image_orig_w.'" data-height="'.$image_orig_h.'" data-singlew="12" data-singleh="null" data-crop=""';
					}
				} elseif (strpos($background_mime, 'video/') !== false) {
					$poster = get_post_meta($background['background-image'], "_uncode_poster_image", true);
					if ($poster !== '') {
						$poster_attributes = uncode_get_media_info($poster);
						$media_metavalues = unserialize($poster_attributes->metadata);
						$image_orig_w = $media_metavalues['width'];
						$image_orig_h = $media_metavalues['height'];
						$resized_image = uncode_resize_image($poster_attributes->id, $poster_attributes->guid, $poster_attributes->path, $image_orig_w, $image_orig_h, 12, '', false);
						$poster_url = $resized_image['url'];
						if (isset($poster_attributes->post_mime_type)) {
							$background_mime = $poster_attributes->post_mime_type;
						}
						if (strpos($background_mime, 'image/') !== false) {
							$background_url = $poster_url;
							$back_url = ($background_url !== '') ? 'background-image: url(' . $background_url . ');' : '';
						} else {
							$back_oembed = wp_oembed_get($poster_attributes->guid);
							preg_match_all('/src="([^"]*)"/i', $back_oembed, $img_src);
							$back_url = (isset($img_src[1][0])) ? 'background-image: url(' . str_replace('"', '', $img_src[1][0]) . ');' : '';
						}
						$poster_id = $background['background-image'];
						if ($adaptive_images === 'on' && $adaptive_images_async === 'on' && $background_mime !== 'image/gif' && $background_mime !== 'image/url') {
							$adaptive_async_class = ' adaptive-async';
							if ($adaptive_images_async_blur === 'on') {
								$adaptive_async_class .= ' async-blurred';
							}
							$adaptive_async_data = ' data-uniqueid="'.$poster_id.'-'.uncode_big_rand().'" data-guid="'.(is_array($poster_attributes->guid) ? $poster_attributes->guid['url'] : $poster_attributes->guid).'" data-path="'.$poster_attributes->path.'" data-width="'.$image_orig_w.'" data-height="'.$image_orig_h.'" data-singlew="12" data-singleh="null" data-crop=""';
						}
					}

					$videos = array();
					$exloded_url = explode(".", strtolower($back_attributes->guid));
					$ext = end($exloded_url);
					$videos[(String) $ext] = $back_attributes->guid;
					$alt_videos = get_post_meta($background['background-image'], "_uncode_video_alternative", true);

					if (!empty($alt_videos)) {
						foreach ($alt_videos as $key => $value) {
							$exloded_url = explode(".", strtolower($value));
							$ext = end($exloded_url);
							$videos[(String) $ext] = $value;
						}
					} else {
						$videos = array(
							'src' => '"' . $back_attributes->guid . '"'
						);
					}

					$video_src = '';
					foreach ($videos as $key => $value) {
						$video_src.= ' ' . $key . '=' . $value;
					}

					$back_mime_css = ' self-video uncode-video-container';

					$header_background_selfvideo = do_shortcode('[video' . apply_filters( 'uncode_self_video_src', $video_src ) . ' loop="y" class="background-video-shortcode"]');
					$header_background_selfvideo = str_replace('controls="controls"','', $header_background_selfvideo);
					$header_background_selfvideo = str_replace('<video','<video loop '. $background_mobile_attr . 'muted', $header_background_selfvideo);


					$get_video_meta = unserialize($back_attributes->metadata);
					$video_ratio = $get_video_meta['width'] / $get_video_meta['height'];
					$header_background_selfvideo = str_replace('class="background-video-shortcode"','class="background-video-shortcode" data-ratio="'.$video_ratio.'"', $header_background_selfvideo);
				} else {
					switch ($background_mime) {
						case 'oembed/flickr':
						case 'oembed/Imgur':
						case 'oembed/photobucket':
							$back_oembed = wp_oembed_get($back_attributes->guid);
							preg_match_all('/src="([^"]*)"/i', $back_oembed, $img_src);
							$back_url = (isset($img_src[1][0])) ? 'background-image: url(' . str_replace('"', '', $img_src[1][0]) . ');' : '';
							if ($background_mime === 'oembed/flickr') {
								$back_url = str_replace(array('_n.','_z.'), '_b.', $back_url);
							}
							$background_url = $back_url;
							$background_mime = 'image';
						break;
						case 'oembed/instagram':
							$url = 'http://api.instagram.com/oembed?url=' . $back_attributes->guid;
							$json = wp_remote_fopen($url);
							$json_data = json_decode($json, true);
							$background_url = $back_url = $json_data['thumbnail_url'];
							$back_url = isset($json_data['thumbnail_url']) ? 'background-image: url(' . esc_url($json_data['thumbnail_url']) . ');' : '';
							$background_mime = 'image';
						break;
						case 'oembed/vimeo':
						case 'oembed/youtube':
							$back_metavalues = unserialize($back_attributes->metadata);
							$video_orig_w = $back_metavalues['width'];
							$video_orig_h = $back_metavalues['height'];
							$video_ratio = ($video_orig_h === 0) ? 1.777 : $video_orig_w / $video_orig_h;
							$parse_video_url = parse_url(html_entity_decode($back_attributes->guid));
							$video_url = strtok($back_attributes->guid, '?');
							if (isset($parse_video_url['query'])) {
								parse_str($parse_video_url['query'], $query_params);
								if (isset($query_params) && count($query_params) > 0) {
									foreach ($query_params as $key => $value) {
										$header_background_video .= ' data-' . $key . '="' . $value . '"';
									}
									if ($background_mime === 'oembed/youtube' && isset($query_params['v'])) {
										$video_url = 'https://youtu.be/' . $query_params['v'];
									}
								}
							}

							$header_background_video .= ' data-ignore data-ratio="'.$video_ratio.'" data-provider="'.($background_mime === 'oembed/vimeo' ? 'vimeo' : 'youtube' ).'" data-video="' . $video_url . '" data-id="' . rand(10000, 99999) . '"';

							//Check for consent and replace with poster image in case
							if (
								( uncode_privacy_allow_content( 'youtube' ) === false && $background_mime === 'oembed/youtube' )
								||
								( uncode_privacy_allow_content( 'vimeo' ) === false && $background_mime === 'oembed/vimeo' )
							) {
								$back_mime_css = '';
								$back_url_id = get_post_meta($back_attributes->id, "_uncode_poster_image", true);
								if ( $back_url_id ) {
									$back_url = 'background-image: url(' . wp_get_attachment_url($back_url_id) . ');';
								}
							} else {
								$back_mime_css = ' video uncode-video-container';
							}

						break;
						case 'oembed/soundcloud':
							$url = $back_attributes->guid;
							$accent_color = $front_background_colors['accent'];
							$accent_color = str_replace('#', '', $accent_color);
							$getValues = wp_remote_fopen('http://soundcloud.com/oembed?format=js&url=' . $url . '&iframe=true');
							$decodeiFrame = substr($getValues, 1, -2);
							$decodeiFrame = json_decode($decodeiFrame);
							preg_match('/src="([^"]+)"/', $decodeiFrame->html, $iframe_src);
							$iframe_url = str_replace('visual=true', 'visual=false', $iframe_src[1]);
							if ( uncode_privacy_allow_content( 'soundcloud' ) === false ) {
								$content_html = '';
								$back_url_id = get_post_meta($back_attributes->id, "_uncode_poster_image", true);
								if ( $back_url_id ) {
									$back_url = 'background-image: url(' . wp_get_attachment_url($back_url_id) . ');';
								}
							} else {
								$content_html = '<iframe width="100%" scrolling="no" frameborder="no" src="' . $iframe_url . '&color='.$accent_color.'&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false"></iframe>';
							}
						break;
						case 'oembed/twitter':
							$url = 'https://api.twitter.com/1/statuses/oembed.json?id=' . basename($back_attributes->guid);
							$json = wp_remote_fopen($url);
							$json_data = json_decode($json, true);
							$id = basename($json_data['url']);
							$html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $json_data['html']);
							$html = str_replace("&mdash; ", '', $html);
							if (function_exists('mb_convert_encoding')) {
								$html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
							}
							$dom = new domDocument;
							$dom->loadHTML($html);
							$dom->preserveWhiteSpace = false;
							$twitter_content = $dom->getElementsByTagname('blockquote');
							$twitter_blockquote = '';
							$twitter_footer = '';
							foreach ($twitter_content as $item) {
								$twitter_content_inner = $item->getElementsByTagname('p');
								foreach ($twitter_content_inner as $item_inner) {
									foreach ($item_inner->childNodes as $child) {
										$twitter_blockquote .= $child->ownerDocument->saveXML( $child );
									}
									$item_inner->parentNode->removeChild($item_inner);
								}
								foreach ($item->childNodes as $child) {
									$twitter_footer .= $child->ownerDocument->saveXML( $child );
								}
								$item->parentNode->removeChild($item);
							}
							$content_html = 	'<div class="twitter-item">
																	<div class="twitter-item-data">
																		<blockquote class="tweet-text pullquote">
																			<p>' . $twitter_blockquote . '</p>';
							$content_html .= 				'<p class="twitter-footer"><small>' . $twitter_footer . '</small></p>';
							$content_html .= 			'</blockquote>
																	</div>
																</div>';
							$poster = get_post_meta($background['background-image'], "_uncode_poster_image", true);
							if ($poster !== '') {
								$poster_attributes = uncode_get_media_info($poster);
								$media_metavalues = unserialize($poster_attributes->metadata);
								$image_orig_w = $media_metavalues['width'];
								$image_orig_h = $media_metavalues['height'];
								$resized_image = uncode_resize_image($poster_attributes->id, $poster_attributes->guid, $poster_attributes->path, $image_orig_w, $image_orig_h, 12, '', false);
								$poster_url = $resized_image['url'];
								if (isset($poster_attributes->post_mime_type)) {
									$background_mime = $poster_attributes->post_mime_type;
								}
								if (strpos($background_mime, 'image/') !== false) {
									$background_url = $poster_url;
									$back_url = ($background_url !== '') ? 'background-image: url(' . $background_url . ');' : '';
								} else {
									$back_oembed = wp_oembed_get($poster_attributes->guid);
									preg_match_all('/src="([^"]*)"/i', $back_oembed, $img_src);
									$back_url = (isset($img_src[1][0])) ? 'background-image: url(' . str_replace('"', '', $img_src[1][0]) . ');' : '';
								}
								$poster_id = $background['background-image'];
							}
							$content_only_text = true;
						break;
						case 'oembed/html':
							if (isset($back_attributes->post_excerpt) && $back_attributes->post_excerpt !== '') {
								$author = '<p><small>' . $back_attributes->post_excerpt . '</small></p>';
							} else {
								$author = '';
							}
							$content_html = '<blockquote class="pullquote"><p>' . $back_attributes->post_content . '</p>' . $author . '</blockquote>';
							$poster = get_post_meta($background['background-image'], "_uncode_poster_image", true);
							if ($poster !== '') {
								$poster_attributes = uncode_get_media_info($poster);
								$media_metavalues = unserialize($poster_attributes->metadata);
								$image_orig_w = $media_metavalues['width'];
								$image_orig_h = $media_metavalues['height'];
								$resized_image = uncode_resize_image($poster_attributes->id, $poster_attributes->guid, $poster_attributes->path, $image_orig_w, $image_orig_h, 12, '', false);
								$poster_url = $resized_image['url'];
								if (isset($poster_attributes->post_mime_type)) {
									$background_mime = $poster_attributes->post_mime_type;
								}
								if (strpos($background_mime, 'image/') !== false) {
									$background_url = $poster_url;
									$back_url = ($background_url !== '') ? 'background-image: url(' . $background_url . ');' : '';
								} else {
									$back_oembed = wp_oembed_get($poster_attributes->guid);
									preg_match_all('/src="([^"]*)"/i', $back_oembed, $img_src);
									$back_url = (isset($img_src[1][0])) ? 'background-image: url(' . str_replace('"', '', $img_src[1][0]) . ');' : '';
								}
								$poster_id = $background['background-image'];
							}
							$content_only_text = true;
						break;
						case 'oembed/svg':
							$content_html = $back_attributes->post_content;
							$content_html = preg_replace('#\s(xmlns)="([^"]+)"#', '', $content_html);
							$content_html = preg_replace('#\s(xmlns:svg)="([^"]+)"#', '', $content_html);
							$content_html = preg_replace('#\s(xmlns:xlink)="([^"]+)"#', '', $content_html);
						break;
						case 'oembed/iframe':
							$content_html = $back_attributes->post_content;
						break;
						default:
							if (strpos($background_mime, 'audio/') !== false) {
								$content_html = do_shortcode('[audio src="' . $back_attributes->guid . '"]');
							} elseif ($background_mime === 'oembed/spotify') {
								if ( uncode_privacy_allow_content( 'spotify' ) === false ) {
									$content_html = '';
									$back_url_id = get_post_meta($back_attributes->id, "_uncode_poster_image", true);
									if ( $back_url_id ) {
										$back_url = 'background-image: url(' . wp_get_attachment_url($back_url_id) . ');';
									}
								} else {
									$content_html = wp_oembed_get($back_attributes->guid);
								}
							}
							$poster = get_post_meta($background['background-image'], "_uncode_poster_image", true);
							if ($poster !== '') {
								$poster_attributes = uncode_get_media_info($poster);
								$media_metavalues = unserialize($poster_attributes->metadata);
								$image_orig_w = $media_metavalues['width'];
								$image_orig_h = $media_metavalues['height'];
								$resized_image = uncode_resize_image($poster_attributes->id, $poster_attributes->guid, $poster_attributes->path, $image_orig_w, $image_orig_h, 12, '', false);
								$poster_url = $resized_image['url'];
								if (isset($poster_attributes->post_mime_type)) {
									$background_mime = $poster_attributes->post_mime_type;
								}
								if (strpos($background_mime, 'image/') !== false) {
									$background_url = $poster_url;
									$back_url = ($background_url !== '') ? 'background-image: url(' . $background_url . ');' : '';
								} else {
									$back_oembed = wp_oembed_get($poster_attributes->guid);
									preg_match_all('/src="([^"]*)"/i', $back_oembed, $img_src);
									$back_url = (isset($img_src[1][0])) ? 'background-image: url(' . str_replace('"', '', $img_src[1][0]) . ');' : '';
								}
								$poster_id = $background['background-image'];
								if ($adaptive_images === 'on' && $adaptive_images_async === 'on' && $background_mime !== 'image/gif' && $background_mime !== 'image/url') {
									$adaptive_async_class = ' adaptive-async';
									if ($adaptive_images_async_blur === 'on') {
										$adaptive_async_class .= ' async-blurred';
									}
									$adaptive_async_data = ' data-uniqueid="'.$poster_id.'-'.uncode_big_rand().'" data-guid="'.(is_array($poster_attributes->guid) ? $poster_attributes->guid['url'] : $poster_attributes->guid).'" data-path="'.$poster_attributes->path.'" data-width="'.$image_orig_w.'" data-height="'.$image_orig_h.'" data-singlew="12" data-singleh="null" data-crop=""';
								}
							}
						break;
					}
				}
			} else {
				$carousel_html = do_shortcode('[vc_gallery el_id="gallery-'.rand().'" medias="'.$background['background-image'].'" type="carousel" style_preset="metro" single_padding="0" carousel_fluid="yes" carousel_lg="1" carousel_md="1" carousel_sm="1" gutter_size="0" media_items="media" carousel_interval="0" carousel_dots="yes" carousel_autoh="no" carousel_type="fade" carousel_nav="no" carousel_dots_inside="yes" single_text="overlay" single_border="yes" single_width="12" single_height="12" single_text_visible="no" single_text_anim="no" single_overlay_visible="no" single_overlay_anim="no" single_image_anim="no"]');
				$is_carousel = true;
			}
		}

		if (isset($background['background-color']) && $background['background-color'] !== '') {
			$back_color = ' style-' . $background['background-color'] . '-bg';
		}

		if ($overlay_color !== '') {
			$overlay_color = ' style-' . $overlay_color . '-bg';
		}

		if ($overlay_color_alpha !== '' && $overlay_color !== '') {
			$overlay_color_alpha = ' style="opacity: ' . ($overlay_color_alpha / 100) . ';"';
		} else {
			$overlay_color_alpha = '';
		}
		if ( isset($background['mix-blend-mode']) && $background['mix-blend-mode']!=='' ){
			$overlay_color_alpha_blend = ' style="mix-blend-mode:' . $background['mix-blend-mode'] . ';"';
		} else {
			$overlay_color_alpha_blend = $overlay_color_alpha;
		}
		if (!empty($overlay_pattern)) {
			$overlay_pattern = ' uncode-' . $overlay_pattern;
		}

		if (!empty($overlay_pattern) && $overlay_pattern !== '') {
			$header_overlay_pattern_style = '<div class="header-bg-overlay-inner'.$overlay_pattern.'"' . $overlay_color_alpha . '></div>';
		}
		if (!empty($overlay_color) && $overlay_color !== '') {
			$header_overlay_style = '<div class="header-bg-overlay-inner' . $overlay_color . '"' . $overlay_color_alpha . '></div>';
		}
		if ($header_overlay_style !== '' || $header_overlay_pattern_style !== '') {
			$header_overlay_html = '<div class="header-bg-overlay">' . $header_overlay_style . $header_overlay_pattern_style . ' </div>';
		}

		$back_image = ($back_url != '' || $back_repeat != '' || $back_position != '' || $back_attachment != '' || $back_size != '') ? ' style="' . $back_url . $back_repeat . $back_position . $back_attachment . $back_size . '"' : '';
		if ( $overlay_color !== '') {
			$overlay_html = '<div class="block-bg-overlay' . $overlay_color . '"' . $overlay_color_alpha . '></div>';
			if ( isset($background['mix-blend-mode']) && $background['mix-blend-mode']!=='' ){
				$overlay_html = '<div class="block-bg-overlay block-bg-blend-mode for-ie' . $overlay_color . '"' . $overlay_color_alpha . '></div>';
				$overlay_html .= '<div class="block-bg-overlay block-bg-blend-mode not-ie' . $overlay_color . '"' . $overlay_color_alpha_blend . '></div>';
			}
		}

		if ($type === 'row') {
			$back_html = '<div class="row-background background-element"'.(($back_mime_css === '' && $header_background_video === '' && $back_image === '' && $header_background_selfvideo === '' && $content_html === '') ? ' style="opacity: 1;"' : '').'>
											<div class="background-wrapper">
												<div class="background-inner' . $back_mime_css . $adaptive_async_class . '"' . $header_background_video . $back_image . $adaptive_async_data . '>' . $header_background_selfvideo . $content_html . '</div>
												'.$overlay_html.'
											</div>
										</div>';
		} elseif ($type === 'column') {
			$back_html = '<div class="column-background background-element"'.(($back_mime_css === '' && $header_background_video === '' && $back_image === '' && $header_background_selfvideo === '' && $content_html === '') ? ' style="opacity: 1;"' : '').'>
											<div class="background-wrapper">
												<div class="background-inner' . $back_mime_css . $adaptive_async_class . '"' . $header_background_video . $back_image . $adaptive_async_data . '>' . $header_background_selfvideo . $content_html . '</div>
												'.$overlay_html.'
											</div>
										</div>';
		} elseif ($type === 'div') {
			if ($header_background_video !== '' || $back_image !== '' || $header_background_selfvideo !== '' || $content_html !== '') {
				$back_html = '<div class="main-background background-element">
												<div class="' . $back_mime_css . $adaptive_async_class . '"' . $header_background_video . $back_image . $adaptive_async_data . '>' . $header_background_selfvideo . $content_html . '</div>
											</div>';
			}
		} else {
			if ($overlay_html !== '' || $header_background_video !== '' || $back_image !== '' || $header_background_selfvideo !== '' || $carousel_html !== '') {
				$back_html = 	'<div class="header-bg-wrapper' . ($carousel_html !=='' ? ' header-carousel-wrapper' : '') . '">
											<div class="header-bg' . $back_mime_css . $adaptive_async_class . '"' . $header_background_video . $back_image . $adaptive_async_data . '>' . $header_background_selfvideo . $carousel_html . '</div>
											'.$overlay_html.'
										</div>';
			}
		}

		return array(
			'back_color' => $back_color,
			'back_html' => $back_html,
			'content_html' => $content_html,
			'content_only_text' => $content_only_text,
			'back_url' => $background_url,
			'poster_id' => $poster_id,
			'is_carousel' => $is_carousel,
			'mime' => $background_mime,
			'async_class' => $adaptive_async_class,
			'async_data' => $adaptive_async_data,
		);
	}
}

/**
 * Row template
 */
if (!function_exists('uncode_get_row_template')) {
	function uncode_get_row_template($content, $limit_width, $limit_content_width, $style, $row_class = '', $padding_top = true, $padding_lr = true, $padding_bottom = true, $row_style = '') {

		if ($content === '') {
			return;
		}

		if (!$padding_top) {
			$row_padding_top = ' no-top-padding';
		} elseif ($padding_top === 'quad') {
			$row_padding_top = ' quad-top-padding';
		} elseif ($padding_top === 'triple') {
			$row_padding_top = ' triple-top-padding';
		} elseif ($padding_top === 'double') {
			$row_padding_top = ' double-top-padding';
		} elseif ($padding_top === 'std') {
			$row_padding_top = ' std-top-padding';
		} else {
			$row_padding_top = '';
		}

		if (!$padding_lr) {
			$row_padding_lr = ' no-h-padding';
		} else {
			$row_padding_lr = '';
		}

		if (!$padding_bottom) {
			$row_padding_bottom = ' no-bottom-padding';
		} elseif ($padding_bottom === 'quad') {
			$row_padding_bottom = ' quad-bottom-padding';
		} elseif ($padding_bottom === 'triple') {
			$row_padding_bottom = ' triple-bottom-padding';
		} elseif ($padding_bottom === 'double') {
			$row_padding_bottom = ' double-bottom-padding';
		} elseif ($padding_bottom === 'std') {
			$row_padding_bottom = ' std-bottom-padding';
		} else {
			$row_padding_bottom = '';
		}

		return 	'<div class="row-container'.$limit_width.$row_class.'">
	  					<div class="row row-parent style-'.$style.$limit_content_width.$row_padding_top.$row_padding_lr.$row_padding_bottom.'"'.$row_style.'>
								'.$content.'
							</div>
						</div>';
	}
}

/**
 * Thumbnail HTML creation, base for index and other components
 * @param  [type]  $block_data
 * @param  [type]  $el_id
 * @param  [type]  $style_preset
 * @param  [type]  $layout
 * @param  [type]  $lightbox_classes
 * @param  [type]  $carousel_textual
 * @param  boolean $with_html
 * @return [type]
 */
if (!function_exists('uncode_create_single_block')) {
	function uncode_create_single_block($block_data, $el_id, $style_preset, $layout, $lightbox_classes, $carousel_textual, $with_html = true)
	{
		global $adaptive_images, $adaptive_images_async, $adaptive_images_async_blur, $post;

		$image_orig_w = $image_orig_h = $crop = $item_media = $media_code = $media_mime = $create_link = $title_link = $text_content = $media_attributes = $big_image = $lightbox_data = $single_height = $single_fixed = $single_title = $nested = $media_poster = $dummy_oembed = $images_size = $single_family = $object_class = $single_back_color = $single_animation = $is_product = $single_icon = $icon_size = $single_text = $single_image_size = $single_style = $single_elements_click = $overlay_color = $overlay_opacity = $overlay_blend = $tmb_data = $adaptive_async_class = $adaptive_async_data = $sep_extra = '';
		$media_type = 'image';
		$multiple_items = false;

		$or_post = $post;
		if ( isset($block_data['id'])) {
			$post = get_post($block_data['id']);
		}

		if (isset($block_data['media_id'])) {
			$item_thumb_id = apply_filters('wpml_object_id', $block_data['media_id'], 'attachment');
			if ($item_thumb_id === '' || empty($item_thumb_id)) {
				$item_thumb_id = $block_data['media_id'];
			}
		}
		if (isset($block_data['classes'])) {
			$block_classes = $block_data['classes'];
		}
		if (isset($block_data['tmb_data'])) {
			$tmb_data = $block_data['tmb_data'];
		}
		if (isset($block_data['images_size'])) {
			$images_size = $block_data['images_size'];
		}
		if (isset($block_data['single_style'])) {
			$single_style = $block_data['single_style'];
		}
		if (isset($block_data['single_text'])) {
			$single_text = $block_data['single_text'];
		}
		if (isset($block_data['single_image_size'])) {
			$single_image_size = $block_data['single_image_size'];
		}
		if (isset($block_data['single_elements_click'])) {
			$single_elements_click = $block_data['single_elements_click'];
		}
		if (isset($block_data['overlay_color'])) {
			$overlay_color = $block_data['overlay_color'];
		}
		if (isset($block_data['overlay_opacity'])) {
			$overlay_opacity = ' style="opacity: ' . ((int) ($block_data['overlay_opacity'])) / 100 . ';"';
		}
		if (isset($block_data['overlay_blend']) && $block_data['overlay_blend']!=='' ) {
			$overlay_blend = ' style="mix-blend-mode: ' . esc_attr( $block_data['overlay_blend'] ) . ';"';
			$overlay_opacity = '';
		}
		if (isset($block_data['single_width'])) {
			$single_width = $block_data['single_width'];
		}
		if (isset($block_data['single_height'])) {
			$single_height = $block_data['single_height'];
		}
		if (isset($block_data['single_back_color'])) {
			$single_back_color = $block_data['single_back_color'];
		}
		if (isset($block_data['single_title'])) {
			$single_title = $block_data['single_title'];
		}
		if (isset($block_data['single_icon'])) {
			$single_icon = $block_data['single_icon'];
		}
		if (isset($block_data['icon_size'])) {
			$icon_size = $block_data['icon_size'];
		}
		if (isset($block_data['poster'])) {
			$media_poster = $block_data['poster'];
		}
		if (isset($block_data['title_classes'])) {
			$title_classes = (!$block_data['title_classes']) ? array('h3') : $block_data['title_classes'];
		}
		if (isset($block_data['animation'])) {
			$single_animation = $block_data['animation'];
		}
		if (isset($block_data['product']) && $block_data['product'] === true) {
			$is_product = true;
		} else {
			$is_product = false;
		}

		$single_fixed = (isset($block_data['single_fixed'])) ? $block_data['single_fixed'] : null;

		if (!isset($block_classes)) {
			$block_classes = array();
		}

		if (isset($block_data['link'])) {
			$create_link = is_array($block_data['link']) ? $block_data['link']['url'] : $block_data['link'];
			$title_link = $create_link;
		}

		$a_classes = array();
		if (isset($block_data['link_class'])) {
			$a_classes[] = $block_data['link_class'];
		}

		/*** MEDIA SECTION ***/
		if (isset($images_size) && $images_size !== '' && $style_preset !== 'metro') {
			switch ($images_size) {
				case ('one-one'):
					$single_height = $single_width;
					break;

				case ('ten-three'):
					$single_height = $single_width / (10 / 3);
					break;

				case ('four-three'):
					$single_height = $single_width / (4 / 3);
					break;

				case ('three-two'):
					$single_height = $single_width / (3 / 2);
					break;

				case ('two-one'):
					$single_height = $single_width / (2 / 1);
					break;

				case ('sixteen-nine'):
					$single_height = $single_width / (16 / 9);
					break;

				case ('twentyone-nine'):
					$single_height = $single_width / (21 / 9);
					break;

				case ('one-two'):
					$single_height = $single_width / (1 / 2);
					break;

				case ('three-four'):
					$single_height = $single_width / (3 / 4);
					break;

				case ('two-three'):
					$single_height = $single_width / (2 / 3);
					break;

				case ('nine-sixteen'):
					$single_height = $single_width / (9 / 16);
					break;

				case ('three-ten'):
					$single_height = $single_width / (3 / 10);
					break;
			}
			$block_classes[] = $has_ratio = 'tmb-img-ratio';
		}

		$items_thumb_id = explode(',', $item_thumb_id);

		if ((empty($item_thumb_id) || !get_post_mime_type( $item_thumb_id )) && ( !is_array($items_thumb_id) || $items_thumb_id[0] === '' ) ) {
			$media_attributes = new stdClass();
			$media_attributes->metadata = '';
			$media_attributes->post_mime_type = '';
			$media_attributes->post_excerpt = '';
			$media_attributes->post_content = '';
			$media_attributes->guid = '';
			if (isset($layout['media']) && isset($layout['media'][0]) && $layout['media'][0] === 'placeholder') {
				$item_media = wc_placeholder_img_src();
				$get_size_item_media = getimagesize($item_media);
				$image_orig_w = isset($get_size_item_media[0]) ? $get_size_item_media[0] : 500;
				$image_orig_h = isset($get_size_item_media[1]) ? $get_size_item_media[0] : 500;
			} else {
				$item_media = get_template_directory_uri() . '/library/img/blank.png';;
				$image_orig_w = 500;
				$image_orig_h = 500;
			}
			$consent_id = 'image/jpeg';
		}
		else {
			/** get media info **/
			if (count($items_thumb_id) > 1 ) {
				if ($media_poster) {
					$media_attributes = uncode_get_media_info($items_thumb_id[0]);
					$media_metavalues = unserialize($media_attributes->metadata);
					$media_mime = $media_attributes->post_mime_type;
				} else {
					$multiple_items = true;
				}
			} else {
				$media_attributes = uncode_get_media_info($item_thumb_id);
				if (!isset($media_attributes)) {
					$media_attributes = new stdClass();
					$media_attributes->metadata = '';
					$media_attributes->post_mime_type = '';
					$media_attributes->post_excerpt = '';
					$media_attributes->post_content = '';
					$media_attributes->guid = '';

					if ( isset($items_thumb_id[0]) && filter_var($items_thumb_id[0], FILTER_VALIDATE_EMAIL) ) {
						$media_attributes->guid = filter_var($items_thumb_id[0], FILTER_SANITIZE_EMAIL);
					}
				}
				$media_metavalues = unserialize($media_attributes->metadata);
				$media_mime = $media_attributes->post_mime_type;
			}

			$consent_id = str_replace( 'oembed/', '', $media_mime );
			uncode_privacy_check_needed( $consent_id );
			if ( uncode_privacy_allow_content( $consent_id ) === false ) {
				$block_classes[] = 'tmb-consent-blocked';
			}

			$media_alt = (isset($media_attributes->alt)) ? $media_attributes->alt : '';

			/** check if open to lightbox **/
			if ($lightbox_classes && !( isset($block_data['explode_album']) && is_array($block_data['explode_album']) && !empty($block_data['explode_album']) ) ) {
				if (isset($lightbox_classes['data-title']) && $lightbox_classes['data-title'] === true) {
					$lightbox_classes['data-title'] = apply_filters( 'uncode_media_attribute_title', $media_attributes->post_title, $items_thumb_id[0]);
				}
				if (isset($lightbox_classes['data-caption']) && $lightbox_classes['data-caption'] === true) {
					$lightbox_classes['data-caption'] = apply_filters( 'uncode_media_attribute_excerpt', $media_attributes->post_excerpt, $items_thumb_id[0]);
				}
			}

			/** shortcode carousel  **/
			if ($multiple_items) {

				$shortcode = '[vc_gallery nested="yes" el_id="gallery-'.rand().'" medias="'.$item_thumb_id.'" type="carousel" style_preset="'.$style_preset.'" single_padding="0" thumb_size="'.$images_size.'" carousel_lg="1" carousel_md="1" carousel_sm="1" gutter_size="0" media_items="media" carousel_interval="0" carousel_dots="yes" carousel_dots_mobile="yes" carousel_autoh="yes" carousel_type="fade" carousel_nav="no" carousel_nav_mobile="no" carousel_dots_inside="yes" single_text="overlay" single_border="yes" single_width="'.$single_width.'" single_height="'.$single_height.'" single_text_visible="no" single_text_anim="no" single_overlay_visible="no" single_overlay_anim="no" single_image_anim="no"]';

				$media_oembed = uncode_get_oembed($item_thumb_id, $shortcode, 'shortcode', false);
				$media_code = $media_oembed['code'];
				$media_type = $media_oembed['type'];
				if(($key = array_search('tmb-overlay-anim', $block_classes)) !== false) {
				    unset($block_classes[$key]);
				}
				if(($key = array_search('tmb-overlay-text-anim', $block_classes)) !== false) {
				    unset($block_classes[$key]);
				}
				if(($key = array_search('tmb-image-anim', $block_classes)) !== false) {
				    unset($block_classes[$key]);
				}
				$image_orig_w = $single_width;
				$image_orig_h = $single_height;
				$object_class = 'nested-carousel object-size';
			} else {
				/** This is a self-hosted image **/
				if ($media_mime !== 'image/svg+xml' && strpos($media_mime, 'image/') !== false && $media_mime !== 'image/url' && isset($media_metavalues['width']) && isset($media_metavalues['height'])) {

					$image_orig_w = $media_metavalues['width'];
					$image_orig_h = $media_metavalues['height'];

					/** check if open to lightbox **/
					if ($lightbox_classes) {
						global $adaptive_images, $adaptive_images_async;
						if ($adaptive_images === 'on' && $adaptive_images_async === 'on') {
							$create_link = (is_array($media_attributes->guid) ? $media_attributes->guid['url'] : $media_attributes->guid);
						} else {
							$big_image = uncode_resize_image($media_attributes->id, (is_array($media_attributes->guid) ? $media_attributes->guid['url'] : $media_attributes->guid), $media_attributes->path, $image_orig_w, $image_orig_h, 12, null, false);
							$create_link = $big_image['url'];
						}
						$create_link = strtok($create_link, '?');
					}

					/** calculate height ratio if masonry and thumb size **/
					if ($style_preset === 'masonry') {
						if ($images_size !== '') {
							$crop = true;
						} else {
							$crop = false;
						}
					} else {
						$crop = true;
					}

					if ($media_mime === 'image/gif' || $media_mime === 'image/url') {
						$resized_image = array(
							'url' => $media_attributes->guid,
							'width' => $image_orig_w,
							'height' => $image_orig_h,
						);
					} else {
						if ( isset( $block_data['justify_row_height'] ) ) { //if Justified-Gallery is the case
							$single_width_check = '';
							$single_height = $block_data['justify_row_height'];
							$img_ratio = $image_orig_w/$image_orig_h;
							if ( $img_ratio < 1 ) { //portrait orientation
								$single_height = $single_height * 2;
							}

						}
						if ( $single_image_size !== '' && $single_text === 'lateral' ) {
							$single_width = $single_width / ( 12 / $single_image_size );
							if ( $style_preset !== 'metro') {
								$single_height = $single_height / ( 12 / $single_image_size );
							}
						}
						global $woocommerce_loop, $uncode_vc_index, $is_footer;
 						if ( !$uncode_vc_index && !$is_footer && ( !function_exists('is_product') || !is_product() ) && ( ( isset($woocommerce_loop['columns']) && $woocommerce_loop['columns'] != '' ) || ( function_exists('is_product_category') && is_product_category() ) || ( function_exists('is_product_tag') && is_product_tag() ) ) ) {
							$WC_vers = uncode_get_WC_version();
							if ( version_compare( $WC_vers, '3.3', '<' ) ) {
								$wc_catalog_image_size = get_option('shop_catalog_image_size');
								$wc_crop = $wc_catalog_image_size['crop'];
								$wc_height = $wc_catalog_image_size['height'];
								$wc_width = $wc_catalog_image_size['width'];
							} else {
								$wc_crop = get_option('woocommerce_thumbnail_cropping') != 'uncropped';
								$wc_height = get_option('woocommerce_thumbnail_cropping') == '1:1' ? 1 : get_option('woocommerce_thumbnail_cropping_custom_height');
								$wc_width = get_option('woocommerce_thumbnail_cropping') == '1:1' ? 1 : get_option('woocommerce_thumbnail_cropping_custom_width');
							}

							$wc_catalog_image_size = get_option('shop_catalog_image_size');
							if ( $wc_crop ) {
								$crop = true;
								$wc_height = max($wc_height, 1);
								$wc_width = max($wc_width, 1);
								$single_height = ( $single_width * $wc_height ) / $wc_width;
							}
						}

						$resized_image = uncode_resize_image($media_attributes->id, $media_attributes->guid, $media_attributes->path, $image_orig_w, $image_orig_h, $single_width, $single_height, $crop, $single_fixed);
					}
					$item_media = esc_attr($resized_image['url']);
					if (strpos($media_mime, 'image/') !== false && $media_mime !== 'image/gif' && $media_mime !== 'image/url' && $adaptive_images === 'on' && $adaptive_images_async === 'on') {
						$adaptive_async_class = ' adaptive-async';
						if ($adaptive_images_async_blur === 'on') {
							$adaptive_async_class .= ' async-blurred';
						}
						$adaptive_async_data = ' data-uniqueid="'.$item_thumb_id.'-'.uncode_big_rand().'" data-guid="'.(is_array($media_attributes->guid) ? $media_attributes->guid['url'] : $media_attributes->guid).'" data-path="'.$media_attributes->path.'" data-width="'.$image_orig_w.'" data-height="'.$image_orig_h.'" data-singlew="'.$single_width.'" data-singleh="'.$single_height.'" data-crop="'.$crop.'" data-fixed="'.$single_fixed.'"';
					}
					$image_orig_w = $resized_image['width'];
					$image_orig_h = $resized_image['height'];
				} elseif ($media_mime === 'oembed/svg') {
					$media_type = 'html';
					$media_code = $media_attributes->post_content;
					if ($media_mime === 'oembed/svg') {
						$media_code = preg_replace('#\s(id)="([^"]+)"#', ' $1="$2-' .uncode_big_rand() .'"', $media_code);
						$media_code = preg_replace('#\s(xmlns)="([^"]+)"#', '', $media_code);
						$media_code = preg_replace('#\s(xmlns:svg)="([^"]+)"#', '', $media_code);
						$media_code = preg_replace('#\s(xmlns:xlink)="([^"]+)"#', '', $media_code);
						if (isset($media_metavalues['width']) && $media_metavalues['width'] !== '1') {
							$icon_width = ' style="width:'.$media_metavalues['width'].'px"';
						} else {
							$icon_width = ' style="width:100%"';
						}
						$media_code = '<div'.$icon_width.' class="icon-media">'.$media_code.'</div>';
						if ($media_attributes->animated_svg) {
							$media_metavalues = unserialize($media_attributes->metadata);
							$icon_time = (isset($media_attributes->animated_svg_time) && $media_attributes->animated_svg_time !== '') ? $media_attributes->animated_svg_time : 100;
							preg_match('/(id)=("[^"]*")/i', $media_code, $id_attr);
							if (isset($id_attr[2])) {
								$id_icon = str_replace('"', '', $id_attr[2]);
							} else {
								$id_icon = 'icon-' . uncode_big_rand();
								$media_code = preg_replace('/<svg/', '<svg id="' . $id_icon . '"', $media_code);
							}
							if (isset($block_data['delay']) && $block_data['delay'] !== '') {
								$icon_delay = 'delayStart: '.$block_data['delay'].', ';
							} else {
								$icon_delay = '';
							}
							$media_code .= "<script type='text/javascript'>new Vivus('".$id_icon."', {type: 'delayed', pathTimingFunction: Vivus.EASE_OUT, animTimingFunction: Vivus.LINEAR, ".$icon_delay."duration: ".$icon_time."});</script>";
						}
					}
				} elseif ($media_mime === 'image/svg+xml') {
					$media_type = 'other';
					$media_code = $media_attributes->guid;
					$image_orig_w = $media_metavalues['width'];
					$image_orig_h = $media_metavalues['height'];
					if (isset($media_metavalues['width']) && $media_metavalues['width'] !== '1') {
						$icon_width = ' style="width:'.$media_metavalues['width'].'px"';
					} else {
						$icon_width = ' style="width:100%"';
					}
					$id_icon = 'icon-' . uncode_big_rand();
					if ($media_attributes->animated_svg) {
						$media_metavalues = unserialize($media_attributes->metadata);
						$icon_time = (isset($media_attributes->animated_svg_time) && $media_attributes->animated_svg_time !== '') ? $media_attributes->animated_svg_time : 100;
						$media_code = '<div id="'.$id_icon.'"'.$icon_width.' class="icon-media"></div>';
						if (isset($block_data['delay']) && $block_data['delay'] !== '') {
							$icon_delay = 'delayStart: '.$block_data['delay'].', ';
						} else {
							$icon_delay = '';
						}
						$media_code .= "<script type='text/javascript'>new Vivus('".$id_icon."', {type: 'delayed', pathTimingFunction: Vivus.EASE_OUT, animTimingFunction: Vivus.LINEAR, ".$icon_delay."duration: ".$icon_time.", file: '".$media_attributes->guid."'});</script>";
					} else {
						$media_code = '<div id="'.$id_icon.'"'.$icon_width.' class="icon-media"><img src="'.$media_code.'" alt="' . $media_alt . '" /></div>';
					}
				} else { // This is an oembed
					$object_class = 'object-size';
					/** external image **/
					if ($media_mime === 'image/gif' || $media_mime === 'image/url') {
						$item_media = $media_attributes->guid;
						$image_orig_w = $media_metavalues['width'];
						$image_orig_h = $media_metavalues['height'];
						if ($lightbox_classes) {
							$create_link = $item_media;
						}
					} else { // any other oembed
						if ( !isset($has_ratio) || ( $lightbox_classes && $media_poster ) ) {
							$single_height_oembed = null;
						} else {
							$single_height_oembed = $single_height;
						}
						$is_metro = ($style_preset === 'metro');
						$media_oembed = uncode_get_oembed($item_thumb_id, $media_attributes->guid, $media_attributes->post_mime_type, $media_poster, $media_attributes->post_excerpt, $media_attributes->post_content, false, $single_width, $single_height_oembed, $single_fixed, $is_metro);
						/** check if is an image oembed  **/
						if ($media_oembed['type'] === 'image') {
							$item_media = esc_attr($media_oembed['code']);
							$image_orig_w = $media_oembed['width'];
							$image_orig_h = $media_oembed['height'];
							$media_type = 'image';
							if ($lightbox_classes) {
								$create_link = $media_oembed['code'];
							}
						} else {
							/** check if there is a poster  **/
							if (isset($media_oembed['poster']) && $media_oembed['poster'] !== '' && $media_poster) {
								/** calculate height ratio if masonry and thumb size **/
								if ($style_preset === 'masonry') {
									if ($images_size !== '') {
										$crop = true;
									} else {
										$crop = false;
									}
								} else {
									$crop = true;
								}

								if (!empty($media_oembed['poster']) && $media_oembed['poster'] !== '') {
									$poster_attributes = uncode_get_media_info($media_oembed['poster']);
									$media_metavalues = unserialize($poster_attributes->metadata);
									$image_orig_w = $media_metavalues['width'];
									$image_orig_h = $media_metavalues['height'];
									$resized_image = uncode_resize_image($poster_attributes->id, $poster_attributes->guid, $poster_attributes->path, $image_orig_w, $image_orig_h, $single_width, $single_height, $crop);
									$item_media = esc_attr($resized_image['url']);
									if (strpos($poster_attributes->post_mime_type, 'image/') !== false && $poster_attributes->post_mime_type !== 'image/gif' && $poster_attributes->post_mime_type !== 'image/url' && $adaptive_images === 'on' && $adaptive_images_async === 'on') {
										$adaptive_async_class = ' adaptive-async';
										if ($adaptive_images_async_blur === 'on') {
											$adaptive_async_class .= ' async-blurred';
										}
										$adaptive_async_data = ' data-uniqueid="'.$item_thumb_id.'-'.uncode_big_rand().'" data-guid="'.(is_array($poster_attributes->guid) ? $poster_attributes->guid['url'] : $poster_attributes->guid).'" data-path="'.$poster_attributes->path.'" data-width="'.$image_orig_w.'" data-height="'.$image_orig_h.'" data-singlew="'.$single_width.'" data-singleh="'.$single_height.'" data-crop="'.$crop.'"';
									}
									$image_orig_w = $resized_image['width'];
									$image_orig_h = $resized_image['height'];
									$media_type = 'image';
									if ($lightbox_classes) {
										switch ($media_attributes->post_mime_type) {
						    				case 'oembed/twitter':
						    				case 'oembed/facebook':
						    				case 'oembed/html':
						    					global $adaptive_images, $adaptive_images_async;
													if ($adaptive_images === 'on' && $adaptive_images_async === 'on') {
														$poster_url = $poster_attributes->guid;
													} else {
														$big_image = uncode_resize_image($poster_attributes->id, $poster_attributes->guid, $poster_attributes->path, $image_orig_w, $image_orig_h, 12, null, false);
														$create_link = $big_image['url'];
													}
							    			break;
							    			case 'oembed/iframe':
							    				$create_link = '#inline-'.$item_thumb_id.'" data-type="inline" target="#inline' . $item_thumb_id;
							    			break;
							    			case 'oembed/youtube':
							    				if ( uncode_privacy_allow_content( 'youtube' ) === false ) {
								    				$create_link = '#inline-' . esc_attr( $item_thumb_id ) . '" data-type="inline" target="#inline' . esc_attr( $item_thumb_id );
								    				$inline_hidden = '<div id="inline-' . esc_attr( $item_thumb_id ) . '" class="ilightbox-html" style="display: none;">' . $media_oembed['code'] . '</div>';
							    				} else {
								    				$create_link = $media_oembed['code'];
							    				}
							    			break;
							    			case 'oembed/vimeo':
							    				if ( uncode_privacy_allow_content( 'vimeo' ) === false ) {
								    				$create_link = '#inline-' . esc_attr( $item_thumb_id ) . '" data-type="inline" target="#inline' . esc_attr( $item_thumb_id );
								    				$inline_hidden = '<div id="inline-' . esc_attr( $item_thumb_id ) . '" class="ilightbox-html" style="display: none;">' . $media_oembed['code'] . '</div>';
							    				} else {
								    				$create_link = $media_oembed['code'];
							    				}
							    			break;
							    			case 'oembed/soundcloud':
							    				if ( uncode_privacy_allow_content( 'soundcloud' ) === false ) {
								    				$create_link = '#inline-' . esc_attr( $item_thumb_id ) . '" data-type="inline" target="#inline' . esc_attr( $item_thumb_id );
								    				$inline_hidden = '<div id="inline-' . esc_attr( $item_thumb_id ) . '" class="ilightbox-html" style="display: none;">' . $media_oembed['code'] . '</div>';
							    				} else {
								    				$create_link = $media_oembed['code'];
							    				}
							    			break;
							    			case 'oembed/spotify':
							    				if ( uncode_privacy_allow_content( 'spotify' ) === false ) {
								    				$create_link = '#inline-' . esc_attr( $item_thumb_id ) . '" data-type="inline" target="#inline' . esc_attr( $item_thumb_id );
								    				$inline_hidden = '<div id="inline-' . esc_attr( $item_thumb_id ) . '" class="ilightbox-html" style="display: none;">' . $media_oembed['code'] . '</div>';
							    				} else {
								    				$create_link = $media_oembed['code'];
							    				}
							    			break;
							    			default;
							    				$create_link = $media_oembed['code'];
							    			break;
							    		}
									}
								}
							} else {
								$media_code = $media_oembed['code'];
								$media_type = $media_oembed['type'];
								$object_class = $media_oembed['class'];
								if ($style_preset === 'metro' || $images_size != '') {
									$image_orig_w = $single_width;
									$image_orig_h = $single_height;
								} else {
									$image_orig_w = $media_oembed['width'];
									$image_orig_h = $media_oembed['height'];
								}

								if (strpos($media_mime,'audio/') !== false && isset($media_oembed['poster']) && $media_oembed['poster'] !== '') {
					      			$poster_attributes = uncode_get_media_info($media_oembed['poster']);
						    		$media_metavalues = unserialize($poster_attributes->metadata);
						    		$image_orig_w = $media_metavalues['width'];
									$image_orig_h = $media_metavalues['height'];
						    		$resized_image = uncode_resize_image($poster_attributes->id, $poster_attributes->guid, $poster_attributes->path, $image_orig_w, $image_orig_h, $single_width, $single_height, $crop);
						    		$media_oembed['dummy'] = ($image_orig_h / $image_orig_w) * 100;
					      		}

			      		/** This is an iframe **/
								if ($media_mime === 'oembed/iframe') {
									$media_type = 'other';
									$media_code = $media_attributes->post_content;
									$image_orig_w = $media_metavalues['width'];
									$image_orig_h = $media_metavalues['height'];
								}

								if ($image_orig_h === 0) {
									$image_orig_h = 1;
								}

								if ($media_oembed['dummy'] !== 0 && $style_preset !== 'metro' && uncode_privacy_allow_content( $consent_id ) !== false) {
									$dummy_oembed = ' style="padding-top: ' . $media_oembed['dummy'] . '%"';
								}
								if ($lightbox_classes && $media_type === 'image') {
									$create_link = $media_oembed['code'];
								}
							}
						}
					}
				}
			}
		}

		if ($item_media === '' && !isset($media_attributes->guid) && !$multiple_items) {
			$media_type = 'image';
			$item_media = 'http://placehold.it/500&amp;text=media+not+available';
			$image_orig_w = 500;
			$image_orig_h = 500;
		}

		if (!$with_html) {
			return array(
				'code' => (($media_type === 'image') ? esc_url($item_media) : $media_code),
				'type' => $media_type,
				'width' => $image_orig_w,
				'height' => $image_orig_h,
				'alt' => $media_alt,
				'async' => ($adaptive_async_data === '' ? false : array('class'=>$adaptive_async_class,'data'=>$adaptive_async_data))
			);
		}

		$entry = $inner_entry = $cat_over = '';

		foreach ($layout as $key => $value) {
			switch ($key) {
				case 'icon':
					if ($single_icon !== '' && $single_text === 'overlay') {
						$inner_entry.= '<i class="' . $single_icon . $icon_size . ' t-overlay-icon"></i>';
					}
				break;

				case 'title':
					$get_title = (isset($media_attributes->post_title)) ? $media_attributes->post_title : '';
					$title_tag = isset($block_data['tag']) ? $block_data['tag'] : 'h3';

					if ( class_exists( 'WooCommerce' ) && isset($layout['price']) && isset( $block_data['price_inline'] ) && $block_data['price_inline'] === 'yes' ) {
						$WC_Product = wc_get_product( $block_data['id'] );
						$price_case = '<span class="price '.trim(implode(' ', $title_classes)).'">'.$WC_Product->get_price_html().'</span>';
					} else {
						$price_case = '';
					}

					if (($single_text === 'overlay' && $single_elements_click !== 'yes') || (isset($media_attributes->team) && $media_attributes->team) || $title_link === '#') {
						$print_title = $single_title ? $single_title : ( isset($media_attributes->post_title) ? $media_attributes->post_title : '' );

						if ( isset($block_data['album_id']) && $block_data['album_id']!='' ) {//is Grouped Album
							$print_title = get_the_title( $block_data['album_id'] );
						}

						if ( isset($block_data['media_title_custom']) && $block_data['media_title_custom']!=='' ) {
							$print_title = esc_attr( $block_data['media_title_custom'] );
						}

						if ($print_title !== '') {
							$print_title .= $price_case;
							$inner_entry .= '<' . $title_tag . ' class="t-entry-title '. trim(implode(' ', $title_classes)) . '">'.$print_title.'</' . $title_tag . '>';
						}
					} else {
						$print_title = $single_title ? $single_title : $get_title;

						if ( isset($block_data['album_id']) && $block_data['album_id']!='' ) { //is Grouped Album
							$print_title = get_the_title( $block_data['album_id'] );
						}

						if ($print_title !== '') {
							$print_title .= $price_case;
							$data_values = (isset($block_data['link']['target']) && !empty($block_data['link']['target']) && is_array($block_data['link'])) ? ' target="'.trim($block_data['link']['target']).'"' : '';
							if ($title_link === '') {
								$inner_entry .= '<' . $title_tag . ' class="t-entry-title '. trim(implode(' ', $title_classes)) . '">'.$print_title.'</' . $title_tag . '>';
							} else {
								$inner_entry .= '<' . $title_tag . ' class="t-entry-title '. trim(implode(' ', $title_classes)) . '"><a href="'.$title_link.'"'.$data_values.'>'.$print_title.'</a></' . $title_tag . '>';
							}
						}
					}
				break;

				case 'type':
					$get_the_post_type = get_post_type($block_data['id']);
					if ($get_the_post_type === 'portfolio') {
						$portfolio_cpt_name = ot_get_option('_uncode_portfolio_cpt');
						if ($portfolio_cpt_name !== '') {
							$get_the_post_type = $portfolio_cpt_name;
						}
					}
					if ( !isset($portfolio_cpt_name) ) {
						$get_the_post_type = get_post_type_object($get_the_post_type);
						$get_the_post_type = $get_the_post_type->labels->singular_name;
					}
					$inner_entry .= '<p class="t-entry-type"><span>' . $get_the_post_type . '</span></p>';
				break;

				case 'category':
				case 'meta':

					$cat_over_bool = false;

					if ($key === 'category') {

						if (isset($value[0]) && $value[0] === 'bordered') {
							$border_cat = true;
						} else {
							$border_cat = false;
						}

						if (isset($value[0]) && $value[0] === 'colorbg') {
							$colorbg = true;
						} else {
							$colorbg = false;
						}

						if (isset($value[1]) && ( $value[1] === 'topleft' || $value[1] === 'topright' || $value[1] === 'bottomleft' || $value[1] === 'bottomright' ) ) {
							$cat_over_bool = 't-cat-over-' . $value[1];
						}

					}

					if (isset($value[0]) && $value[0] === 'yesbg') {
						$with_bg = true;
					} else {
						$with_bg = false;
					}

					$meta_inner = '';

					if (is_sticky()) {
						$meta_inner .= '<span class="t-entry-category t-entry-sticky"><i class="fa fa-ribbon fa-push-right"></i>' . esc_html__('Sticky','uncode').'</span><span class="small-spacer"></span>';
					}

					if ($key === 'meta') {
						$year = get_the_time( 'Y' );
						$month = get_the_time( 'm' );
						$day = get_the_time( 'd' );
						$date = get_the_date( '', $block_data['id'] );
						$date_link = '<a href="' . get_day_link( $year, $month, $day ) .'">';
						$date_link_end = '</a>';
						if (($single_text === 'overlay' && $single_elements_click !== 'yes') || (isset($media_attributes->team) && $media_attributes->team) || $title_link === '#') {
							$date_link = $date_link_end = '';
						}
						$clock_icon = '<i class="fa fa-clock fa-push-right"></i>';
						if ( isset($value[0]) && $value[0] === 'hide-icon') {
							$clock_icon = '';
						}
						$meta_inner .= '<span class="t-entry-category t-entry-date">' . $clock_icon . $date_link . $date . $date_link_end . '</span><span class="small-spacer"></span>';
					}

					$categories_array = isset($block_data['single_categories_id']) ? $block_data['single_categories_id'] : array();

					$cat_icon = $tag_icon = false;

					$cat_count = count($categories_array);
					$cat_counter = 0;
					$cat_counter_tot = 0;
					$only_cat_counter = 0;

					if ($cat_count === 0) {
						continue 2;
					}

					$first_taxonomy = is_array($block_data['single_categories'][0]) && isset($block_data['single_categories'][0]['tax']) ? $block_data['single_categories'][0]['tax'] : '';

					foreach ($block_data['single_categories'] as $cat_key => $cat) {
						if (isset($cat['tax']) && $cat['tax'] === $first_taxonomy) {
							$only_cat_counter++;
						}
					}

					foreach ($categories_array as $t_key => $tax) {
						$category = $term_color = '';

						if (isset($block_data['single_categories'][$t_key]) || $block_data['single_categories_id'][$t_key]) {
							$single_cat = $block_data['single_categories'][$t_key];
							if (gettype($single_cat) !== 'string' && isset($single_cat['link'])) {
								if ($key === 'category' && $block_data['single_categories'][$t_key]['tax'] === 'post_tag') {
									continue;
								}
							} else {
								if (isset($block_data['single_tags']) && $key === 'category' && ( isset($block_data['taxonomy_type']) && isset($block_data['taxonomy_type'][$t_key]) && $block_data['taxonomy_type'][$t_key] !== 'category' && $block_data['taxonomy_type'][$t_key] !== 'portfolio_category' && $block_data['taxonomy_type'][$t_key] !== 'product_cat' ) ) {
									continue;
								}
								if (isset($block_data['single_tags']) && $key === 'post_tag' && ( isset($block_data['taxonomy_type']) && isset($block_data['taxonomy_type'][$t_key]) && $block_data['taxonomy_type'][$t_key] !== 'post_tag' ) ) {
									continue;
								}
							}
						}

						$cat_counter_tot++;
					}

					foreach ($categories_array as $t_key => $tax) {
						$category = $term_color = '';

						if ($t_key !== $cat_count - 1 && $t_key !== $only_cat_counter - 1) {
							$add_comma = true;
						} else {
							$add_comma = false;
						}

						$cat_classes = 't-entry-category';
						if (isset($block_data['single_categories'][$t_key]) || $block_data['single_categories_id'][$t_key]) {
							$single_cat = $block_data['single_categories'][$t_key];
							if (gettype($single_cat) !== 'string' && isset($single_cat['link'])) {
								if ($key === 'category' && $block_data['single_categories'][$t_key]['tax'] === 'post_tag') {
									continue;
								}
								$cat_link = $block_data['single_categories'][$t_key]['link'];

								$hide_icon = false;
								if ($key === 'meta') {
									if ( isset($value[0]) && $value[0] === 'hide-icon') {
										$hide_icon = true;
									}
								}
								if ($key === 'category') {
									if ( isset($value[2]) && $value[2] === 'hide-icon') {
										$hide_icon = true;
									}
								}

								if ( !$cat_over_bool ) {

									if ($block_data['single_categories'][$t_key]['tax'] === 'category') {
										$cat_classes .= ' t-entry-tax';
										if ( apply_filters( 'uncode_display_category_icon', true ) && !$cat_icon && !$hide_icon ) {
											$category .= '<i class="fa fa-archive2 fa-push-right"></i>';
											$cat_icon = true;
										}
									}
									if ($block_data['single_categories'][$t_key]['tax'] === 'post_tag') {
										$cat_classes .= ' t-entry-tag';
										if ( apply_filters( 'uncode_display_tag_icon', true ) && !$tag_icon && !$hide_icon ) {
											$category .= '<i class="fa fa-tag2 fa-push-right"></i>';
											$tag_icon = true;
										}
									}
								}
							} else {
								$cat_link = '<span class="t-entry-cat-single"><span>' . $block_data['single_categories'][$t_key] . '</span></span>';
								if (isset($block_data['single_tags']) && $key === 'category' && ( isset($block_data['taxonomy_type']) && isset($block_data['taxonomy_type'][$t_key]) && $block_data['taxonomy_type'][$t_key] !== 'category' && $block_data['taxonomy_type'][$t_key] !== 'portfolio_category' && $block_data['taxonomy_type'][$t_key] !== 'product_cat' ) ) {
									continue;
								}
								if (isset($block_data['single_tags']) && $key === 'post_tag' && ( isset($block_data['taxonomy_type']) && isset($block_data['taxonomy_type'][$t_key]) && $block_data['taxonomy_type'][$t_key] !== 'post_tag' ) ) {
									continue;
								}
							}

							$no_link_cat = '';
							if ($key === 'category') {
								if (isset($block_data['single_categories'][$t_key]['cat_id'])) {
									$term_color = get_option( '_uncode_taxonomy_' . $block_data['single_categories'][$t_key]['cat_id'] );
									if (isset($term_color['term_color']) && $term_color['term_color'] !== '' && $with_bg) {
										$term_color = 'text-' . $term_color['term_color'] . '-color';
									} elseif ( $colorbg ) {
										if ( isset($term_color['term_color']) && $term_color['term_color'] !== '' ) {
											$term_color_id = $term_color['term_color'];
										} else {
											$term_color_id = 'accent';
										}
										$term_color = 'style-' . $term_color_id . '-bg tmb-term-evidence font-ui';
										$add_comma = 'none';
										$category = '';
									}  elseif ( $border_cat ) {
										$term_color = 'bordered-cat tmb-term-evidence font-ui';
										$add_comma = 'none';
										$category = '';
									}

									if ( !is_array($term_color) ) {
										$cat_link = str_replace('<a ', '<a class="'.$term_color.'" ', $cat_link);
									}
								} else {
									$term_color = get_option( '_uncode_taxonomy_' . $block_data['single_categories_id'][$t_key] );
									if (isset($term_color['term_color']) && $term_color['term_color'] !== '' && $with_bg) {
										$term_color = 'text-' . $term_color['term_color'] . '-color';
									} elseif ( $colorbg ) {
										if ( isset($term_color['term_color']) && $term_color['term_color'] !== '' ) {
											$term_color_id = $term_color['term_color'];
										} else {
											$term_color_id = 'accent';
										}
										$term_color = 'style-' . $term_color_id . '-bg tmb-term-evidence font-ui';
										$add_comma = 'none';
										$category = '';
									}  elseif ( $border_cat ) {
										$term_color = 'bordered-cat tmb-term-evidence font-ui';
										$add_comma = 'none';
										$category = '';
									}

									$no_link_cat .= ' t-cat-no-link';
									if ( !is_array($term_color) ) {
										$cat_link = str_replace('<span>', '<span class="'.$term_color.'">', $cat_link);
									}
								}
							}

							$comma_space = '';
							if ( $cat_counter+1 < $cat_counter_tot ) {
								$comma_space = '';
								if ($add_comma === true) {
									$comma_space = '<span class="cat-comma">,</span>';
								} elseif ($add_comma === false) {
									$comma_space = '<span class="small-spacer"></span>';
								}
							}

							$category .= $cat_link . $comma_space;

							$add_comma = true;
						} else {
							$category = '';
						}

						if ( !$cat_over_bool || ( isset( $block_data['single_categories'][$t_key]['tax'] ) && $block_data['single_categories'][$t_key]['tax'] === 'post_tag' ) || ( ( empty($item_thumb_id) || FALSE === get_post_mime_type( $item_thumb_id )) && !is_array($items_thumb_id) ) ) {
							$meta_inner .= '<span class="' . $cat_classes . '">' . $category . '</span>';
						} else {
							$cat_over .= '<span class="' . $cat_classes . ' t-cat-over-inner">' . $category . '</span>';
						}

						$cat_counter++;
						$category = '';
					}

					if ($meta_inner !== '') {
						$inner_entry .= '<p class="t-entry-meta">';
						$inner_entry .= $meta_inner;
						$inner_entry .= '</p>';
					}

				break;

				case 'date':
					$date = get_the_date( '', $block_data['id'] );
					$inner_entry .= '<p class="t-entry-meta">';
					$inner_entry .= '<span class="t-entry-date">'.$date.'</span>';
					$inner_entry .= '</p>';
				break;

				case 'text':

					$post_format = get_post_format($block_data['id']);
					if (isset($value[0]) && ($value[0] === 'full')) {
						$block_text = (($post_format === 'link') ? '<i class="fa fa-link fa-push-right"></i>' : '') . $block_data['content'];
						$block_text .= wp_link_pages( array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'uncode' ),
							'after'  => '</div>',
							'link_before'	=> '<span>',
	    				'link_after'	=> '</span>',
							'echo'	=> 0
						));
					} else {
						$block_text = get_post_field( 'post_excerpt', $block_data['id'] );
					}

					$block_text = apply_filters('uncode_block_data_content', $block_text, $block_data['id']);

					$block_text = apply_filters( 'uncode_filter_for_translation', $block_text );
					$block_text = uncode_remove_p_tag($block_text, true);

					$text_class = (isset($block_data['text_lead']) && ($block_data['text_lead'] === 'yes')) ? ' class="text-lead"' : '';

					$block_text = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $block_text);
					if (isset($block_data['text_length']) && $block_data['text_length'] !== '') {
						$block_text = preg_replace('#<a class="more-link(.*?)</a>#', '', $block_text);
						$block_text = '<p'.$text_class.'>' . uncode_truncate($block_text, $block_data['text_length']) . '</p>';
					} elseif (isset($value[1]) && !empty($value[1])) {
						$block_text = preg_replace('#<a class="more-link(.*?)</a>#', '', $block_text);
						$block_text = '<p'.$text_class.'>' . uncode_truncate($block_text, $value[1]) . '</p>';
					}


					if ($block_text !== '') {
						if ($text_class !== '') {
							$text_class = ' text-lead';
						}
						if ($single_text === 'overlay' && $single_elements_click !== 'yes') {
							$inner_entry .= '<div class="t-entry-excerpt'.$text_class.'">'.preg_replace('/<\/?a(.|\s)*?>/', '', $block_text).'</div>';
						} else {
							if (isset($value[0]) && ($value[0] === 'full')) {
								$inner_entry .= $block_text;
							} else {
								$inner_entry .='<div class="t-entry-excerpt'.$text_class.'">'.$block_text.'</div>';
							}
						}
					}

				break;

				case 'link':
					$btn_shape = ' btn-default';

					if (isset($value[1]) && $value[1] === 'small_size') {
						$btn_shape .= ' btn-sm';
					}

					if (isset($value[0]) && $value[0] !== 'default') {
						if ($value[0] === 'link') {
							$btn_shape = ' btn-link';
						} else {
							$btn_shape .= ' btn-' . $value[0];
						}
					}
					if ( uncode_btn_style() !== '' ) {
						$btn_shape .= ' ' . uncode_btn_style();
					}

                    $data_values = (isset($block_data['link']['target']) && !empty($block_data['link']['target']) && is_array($block_data['link'])) ? ' target="'.trim($block_data['link']['target']).'"' : '';
                    $read_more_text = esc_html__('Read More','uncode');

					if (isset($block_data['read_more_text']) && $block_data['read_more_text'] !== '') {
						$read_more_text = $block_data['read_more_text'];
					} elseif (isset($value[2]) && !empty($value[2])) {
						$read_more_text = $value[2];
					} elseif (isset($value[1]) && !empty($value[1]) && $value[1]!== 'default_size' && $value[1]!== 'small_size') {
						$read_more_text = $value[1];
					}

					if ($single_text === 'overlay' && $single_elements_click !== 'yes') {
						$inner_entry .= '<p class="t-entry-readmore"><span class="btn'.$btn_shape.'">' . $read_more_text . '</span></p>';
					} else {
						$inner_entry .= '<p class="t-entry-readmore"><a href="'.$create_link.'" class="btn'.$btn_shape.'"' . $data_values . '>' . $read_more_text . '</a></p>';
					}
				break;

				case 'author':
					$author = get_post_field( 'post_author', $block_data['id'] );
					$author_name = get_the_author_meta( 'display_name', $author );
					$author_link = get_author_posts_url( $author );
					$inner_entry .= '<p class="t-entry-author">';
					$avatar_size = 20;
					$avatar_size_class = 'sm';
					$qualification = false;
					if (isset($value[0]) && !empty($value[0]) && $value[0]!== '' && $value[0]!== 'display_qualification') {
						if ( $value[0] === 'md_size' ){
							$avatar_size = $avatar_size*2;
							$avatar_size_class = 'md';
						} elseif ( $value[0] === 'lg_size' ){
							$avatar_size = $avatar_size*3;
							$avatar_size_class = 'lg';
						} elseif ( $value[0] === 'xl_size' ){
							$avatar_size = $avatar_size*4;
							$avatar_size_class = 'xl';
						}
					}
					if ( ( isset($value[0]) && $value[0]=== 'display_qualification' ) || ( isset($value[1]) && $value[1]=== 'display_qualification' ) ) {
						$qualification = '<span class="tmb-user-qualification">' . esc_html( get_the_author_meta( 'user_qualification', $author ) ) . '</span>';
					}

					if ($single_text === 'overlay' && $single_elements_click !== 'yes') {
						$inner_entry .= '<span class="tmb-avatar-size-' . $avatar_size_class . '">' . get_avatar( $author, $avatar_size ). '<span class="tmb-username-wrap"><span class="tmb-username-text">' . esc_html__('by','uncode') . ' ' . $author_name . '</span>' . $qualification . '</span>';
					} else {
						$inner_entry .= '<a href="'.$author_link.'" class="tmb-avatar-size-' . $avatar_size_class . '">' . get_avatar( $author, $avatar_size ) . '<span class="tmb-username-wrap"><span class="tmb-username-text">' . esc_html__('by','uncode') . ' ' . $author_name.'</span>' . $qualification . '</span></a>';
					}
					$inner_entry .= '</p>';
				break;

				case 'extra':
					$inner_entry .= '<p class="t-entry-comments entry-small"><span class="extras">';

					if( function_exists('uncode_dot_irecommendthis') && apply_filters('uncode_dot_irecommendthis', false) ) {
						global $uncode_dot_irecommendthis;
						if ($single_text !== 'overlay') {
							$inner_entry .= $uncode_dot_irecommendthis->dot_recommend($block_data['id'], true);
						} else {
							if ($single_elements_click === 'yes') {
								$inner_entry .= $uncode_dot_irecommendthis->dot_recommend($block_data['id'], true);
							} else {
								$inner_entry .= $uncode_dot_irecommendthis->dot_recommend($block_data['id'], false);
							}
						}
					}

					$num_comments = get_comments_number( $block_data['id'] );
					$entry_comments = '<i class="fa fa-speech-bubble"></i><span>'.$num_comments.' '._nx( 'Comment', 'Comments', $num_comments, 'comments', 'uncode' ).'</span>';
					if ($single_text === 'overlay' && $single_elements_click !== 'yes') {
						$inner_entry .= '<span class="extras-wrap">' . $entry_comments . '</span>';
					} else {
						$inner_entry .= '<a class="extras-wrap" href="'.get_comments_link($block_data['id']).'" title="title">'.$entry_comments.'</a>';
					}
					$inner_entry .= '<span class="extras-wrap"><i class="fa fa-watch"></i><span>'.uncode_estimated_reading_time($block_data['id']).'</span></span></span></p>';
				break;

				case 'price':
					if ( class_exists( 'WooCommerce' ) && ( !isset( $block_data['price_inline'] ) || $block_data['price_inline'] !== 'yes' ) ) {
						$WC_Product = wc_get_product( $block_data['id'] );
						$inner_entry .= '<span class="price '.trim(implode(' ', $title_classes)).'">'.$WC_Product->get_price_html().'</span>';
					}
				break;

				case 'caption':
					if ( isset($block_data['album_id']) && $block_data['album_id']!='' ) { //is Grouped Album
						$inner_entry.= '<p class="t-entry-meta"><span>' . get_the_excerpt( $block_data['album_id'] ) . '</span></p>';
					} elseif (isset($media_attributes->post_excerpt) && $media_attributes->post_excerpt !== '' && !( isset($block_data['media_caption_custom']) && $block_data['media_caption_custom'] ) ) {
						$inner_entry.= '<p class="t-entry-meta"><span>' . $media_attributes->post_excerpt . '</span></p>';
					} elseif ( isset($block_data['media_caption_custom']) && $block_data['media_caption_custom'] ) {
						$inner_entry .= '<p class="t-entry-meta"><span>' . esc_attr( $block_data['media_caption_custom'] ) . '</span></p>';
					}
				break;

				case 'description':
					if ( isset($block_data['album_id']) && $block_data['album_id']!='' ) { //is Grouped Album
						$album_post = get_post($block_data['album_id']);
						$album_content = $album_post->post_content;
						$inner_entry.= '<p class="t-entry-excerpt">' . $album_content . '</p>';
					} elseif ( isset($block_data['media_subtitle_custom']) && $block_data['media_subtitle_custom'] !== '' ) {
						$inner_entry .= '<p class="t-entry-excerpt">' . esc_attr( $block_data['media_subtitle_custom'] ) . '</p>';
					} elseif (isset($media_attributes->post_content) && $media_attributes->post_content !== '') {
						$inner_entry.= '<p class="t-entry-excerpt">' . $media_attributes->post_content . '</p>';
					}
				break;

				case 'team-social':
					if ($media_attributes->team) {
						$team_socials = explode("\n", $media_attributes->team_social);
						$inner_entry .= '<p class="t-entry-comments t-entry-member-social"><span class="extras">';

						foreach ($team_socials as $key => $value) {
							$value = trim($value);
							if ($value !== '') {
								if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
									$inner_entry .= '<a href="mailto:'.$value.'"><i class="fa fa-envelope-o"></i></a>';
								} else {
									$get_host = parse_url($value);

									// Fix URLs without scheme
									if ( ! isset( $get_host[ 'scheme' ] ) ) {
										$value    = 'http://' . $value;
										$get_host = parse_url( $value );
									}

									$host = str_replace("www.", "", $get_host['host']);
									$host = explode('.', $host);
									if ( strpos( get_site_url(), $host[0] ) !== false ) {
										$inner_entry.= '<a href="'.esc_url($value).'"><i class="fa fa-user"></i></a>';
									} else {
										if ($host[0] === 'plus') {
											$host[0] = 'google-' . $host[0];
										}
										$inner_entry.= '<a href="'.esc_url($value).'" target="_blank"><i class="fa fa-'.esc_attr($host[0]).'"></i></a>';
									}
								}
							}
						}
						$inner_entry .= '</span></p>';
					}
				break;

				case 'spacer':
					if (isset($value[0])) {
						switch ($value[0]) {
							case 'half':
								$spacer_class = 'half-space';
								break;
							case 'one':
								$spacer_class = 'single-space';
								break;
							case 'two':
								$spacer_class = 'double-space';
								break;
						}
						$inner_entry.= '<div class="spacer spacer-one '.$spacer_class.'"></div>';
					}
				break;

				case 'spacer_two':
					if (isset($value[0])) {
						switch ($value[0]) {
							case 'half':
								$spacer_class = 'half-space';
								break;
							case 'one':
								$spacer_class = 'single-space';
								break;
							case 'two':
								$spacer_class = 'double-space';
								break;
						}
						$inner_entry.= '<div class="spacer spacer-two '.$spacer_class.'"></div>';
					}
				break;

				case 'sep-one':
				case 'sep-two':
					$sep_class = '';
					if ( isset($value[0]) ) {
						if ( $value[0] === 'reduced' ) {
							$sep_class = ' class="separator-reduced"';
						} elseif ( $value[0] === 'extra' ) {
							if ( isset( $block_data['media_full_width'] ) && $block_data['media_full_width'] ) {
								$sep_extra = ' separator-extra-child';
							}
							$sep_class = ' class="separator-extra"';
						}
					}
					$inner_entry.= '<hr'.$sep_class.' />';
				break;

				default:
					if ($key !== 'media') {
						$get_cf_value = get_post_meta($block_data['id'], $key, true);
						if (isset($get_cf_value) && $get_cf_value !== '') {
							$inner_entry.= '<div class="t-entry-cf-'.$key.'">' . $get_cf_value . '</div>';
						}
					}
				break;
			}
		}

		if (isset($media_attributes->team) && $media_attributes->team) {
			$single_elements_click = 'yes';
		}

		$inline_price = '';
		if (!empty($layout) && !(count($layout) === 1 && array_key_exists('media',$layout)) && $inner_entry !== '') {

			if ( isset( $block_data['price_inline'] ) && $block_data['price_inline'] === 'yes' ) {
				$inline_price = ' t-entry-inline-price';
			}
			if ($single_text !== 'overlay') {
				$entry.= '<div class="t-entry-text">
							<div class="t-entry-text-tc ' . $block_data['text_padding'] . $inline_price . '">';
			}

			$entry.= '<div class="t-entry">';

			$entry .= $inner_entry;

			$entry.= '</div>';

			if ($single_text !== 'overlay') {
				$entry.= '</div>
					</div>';
			}
		}

		if ($lightbox_classes) {
			$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $lightbox_classes, array_keys($lightbox_classes));
			$lightbox_data = ' ' . implode(' ', $div_data_attributes);
			$lightbox_data .= ' data-lbox="ilightbox_' . $el_id . '"';
			$video_src = '';
			if (isset($media_attributes->post_mime_type) && strpos($media_attributes->post_mime_type, 'video/') !== false) {
				$video_src .= 'html5video:{preload:\'true\',';
				$video_autoplay = get_post_meta($item_thumb_id, "_uncode_video_autoplay", true);
				if ($video_autoplay) {
					$video_src .= 'autoplay:\'true\',';
				}
				$alt_videos = get_post_meta($item_thumb_id, "_uncode_video_alternative", true);
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


			if (isset($media_attributes->metadata)) {
				$media_metavalues = unserialize($media_attributes->metadata);
				if ( uncode_privacy_allow_content( $consent_id ) === false ) {
					$poster_th_id = get_post_meta($item_thumb_id, "_uncode_poster_image", true);
					$poster_attributes = uncode_get_media_info($poster_th_id);
					if ( is_object($poster_attributes) ) {
						$poster_metavalues = unserialize($poster_attributes->metadata);
						$media_dimensions = 'width:' . esc_attr($poster_metavalues['width']) . ',';
						$media_dimensions .= 'height:' . esc_attr($poster_metavalues['height']) . ',';
					} else {
						$media_dimensions = '';
					}
				} else {
					if (isset($media_metavalues['width']) && isset($media_metavalues['height']) && $media_metavalues['width'] !== '' && $media_metavalues['height'] !== '') {
						$media_dimensions = 'width:' . $media_metavalues['width'] . ',';
						$media_dimensions .= 'height:' . $media_metavalues['height'] . ',';
					} else {
						$media_dimensions = '';
					}
				}

				if ( isset($poster_attributes->id) ) {
					$data_options_th = wp_get_attachment_image_src($poster_attributes->id, 'medium');
				} else {
					if ( isset($media_attributes->id) ) {
						$data_options_th = wp_get_attachment_image_src($media_attributes->id, 'medium');
					}
				}

				if( isset($data_options_th) ) {
					$lightbox_data .= ' data-options="'.$media_dimensions.$video_src.'thumbnail: \''. $data_options_th[0] .'\'"';
				}
			}
		}

		$layoutArray = array_keys($layout);
		foreach ($layoutArray as $key => $value) {
			if ($value === 'icon') {
				unset($layoutArray[$key]);
			}
		}

		if (!array_key_exists('media',$layout)) {
			$block_classes[] = 'tmb-only-text';
			$with_media = false;
		} else {
			$with_media = true;
		}

		if ($single_text === 'overlay') {
			if ($with_media) {
				$block_classes[] = 'tmb-media-first';
				$block_classes[] = 'tmb-media-last';
			}
			$block_classes[] = 'tmb-content-overlay';
		} else {
			if ( $single_text === 'lateral' ) {
				$block_classes[] = 'tmb-content-lateral';
			} else {
				$block_classes[] = 'tmb-content-under';
			}

			$layoutLast = (string) array_pop($layoutArray);
			if ($with_media) {
				if (($layoutLast === 'media' || $layoutLast === '') && $with_media) {
					$block_classes[] = 'tmb-media-last';
				} else {
					$block_classes[] = 'tmb-media-first';
				}
			}
		}

		if ($single_back_color === '') {
			$block_classes[] = 'tmb-no-bg';
		} else {
			$single_back_color = ' style-' . $single_back_color . '-bg';
		}

		$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $tmb_data, array_keys($tmb_data));

		$output = '';
		if ( ot_get_option('_uncode_woocommerce_hooks') === 'on' && $is_product ) {
			ob_start();
			global $product;
			$product = wc_get_product($block_data['id']);
			do_action( 'woocommerce_before_shop_loop_item');
			$output .= ob_get_clean();
		}

		if ( $lightbox_classes ) {
			$block_classes[] = 'tmb-lightbox';
		}

		$output .= 	'<div class="'.implode(' ', $block_classes).'">
						<div class="' . (($nested !== 'yes') ? 't-inside' : '').$single_back_color . $single_animation . '" '.implode(' ', $div_data_attributes) .'>';

		if ( ot_get_option('_uncode_woocommerce_hooks') === 'on' && $is_product ) {
			ob_start();
			global $product;
			$product = wc_get_product($block_data['id']);
			do_action( 'woocommerce_before_shop_loop_item_title');
			$output .= ob_get_clean();
		}

		if ($single_text === 'under' && $layoutLast === 'media') {
			$output .= $entry;
		}

		if (array_key_exists('media',$layout) || $single_text === 'overlay') :
			$output .= 		'<div class="t-entry-visual"><div class="t-entry-visual-tc"><div class="t-entry-visual-cont">';

			//Over image categories
			if ( isset($cat_over) && $cat_over !== '' ):
				$output .= '<span class="t-cat-over' . $no_link_cat . ' ' . $block_data['text_padding'] . ' ' . $cat_over_bool . '">' . $cat_over . '</span>';
			endif;

			if ($style_preset === 'masonry' && ($images_size !== '' || ($single_text !== 'overlay' || $single_elements_click !== 'yes')) && array_key_exists('media',$layout)):

				if ( uncode_privacy_allow_content( $consent_id ) === false && !isset($has_ratio) ) {
					$poster_th_id = get_post_meta($item_thumb_id, "_uncode_poster_image", true);
					$poster_attributes = uncode_get_media_info($poster_th_id);
					if ( is_object($poster_attributes) ) {
						$poster_metavalues = unserialize($poster_attributes->metadata);
						$image_orig_w = esc_attr($poster_metavalues['width']);
						$image_orig_h = esc_attr($poster_metavalues['height']);
					}
				}


				if ( ( $media_type === 'image' || $media_type === 'email' || uncode_privacy_allow_content( $consent_id ) === false ) && $image_orig_w != 0 && $image_orig_h != 0) :
					$dummy_padding = round(($image_orig_h / $image_orig_w) * 100, 1);
					$output .= 			'<div class="dummy" style="padding-top: '.$dummy_padding.'%;"></div>';

				endif;

			endif;

			if (($single_text !== 'overlay' || $single_elements_click !== 'yes') && $media_type === 'image'):

				if ($style_preset === 'masonry') {
					$a_classes[] = 'pushed';
				}

				$data_values = (isset($block_data['link']['target']) && !empty($block_data['link']['target']) && is_array($block_data['link'])) ? ' target="'.trim($block_data['link']['target']).'"' : '';

				//Albums
				if ( isset($block_data['explode_album']) && is_array($block_data['explode_album']) && !empty($block_data['explode_album']) ) {
					$create_link = '#';
					$album_item_dimensions = '';
					$inline_hidden = '';
					foreach ($block_data['explode_album'] as $key_album => $album_item_id) {
						$album_item_attributes = uncode_get_album_item($album_item_id);
						if ( $media_poster ) {
							$album_th_id = $album_item_attributes['poster'];
						} else {
							$album_th_id = $album_item_id;
						}

						if ( $album_th_id == '' ) {
							continue;
						}

						$thumb_attributes = uncode_get_media_info($album_th_id);
						$album_th_metavalues = unserialize($thumb_attributes->metadata);

						if ( !isset($album_th_metavalues['width']) || !isset($album_th_metavalues['height']) ) {
							continue;
						}

						$album_th_w = $album_th_metavalues['width'];
						$album_th_h = $album_th_metavalues['height'];
						if ($album_item_attributes) {
							$album_item_title = (isset($lightbox_classes['data-title']) && $lightbox_classes['data-title'] === true) ? $album_item_attributes['title'] : '';
							$album_item_caption = (isset($lightbox_classes['data-caption']) && $lightbox_classes['data-caption'] === true) ? $album_item_attributes['caption'] : '';
							if (isset($album_item_attributes['width']) && isset($album_item_attributes['height'])) {
								$album_item_dimensions .= '{';
								$album_item_dimensions .= '"title":"' . esc_attr($album_item_title) . '",';
								$album_item_dimensions .= '"caption":"' . esc_html($album_item_caption) . '",';
								//$album_item_dimensions .= '"post_mime_type":"' . esc_attr($album_item_attributes['mime_type']) . '",';
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
									$poster_attributes = uncode_get_media_info($poster_th_id);
									$poster_metavalues = unserialize($poster_attributes->metadata);
									$album_item_dimensions .= '"width":"' . esc_attr($poster_metavalues['width']) . '",';
									$album_item_dimensions .= '"height":"' . esc_attr($poster_metavalues['height']) . '",';
									$resize_album_item = wp_get_attachment_image_src($poster_th_id, 'medium');
									$album_item_dimensions .= '"thumbnail":"' . esc_url($resize_album_item[0]) . '",';
									$album_item_dimensions .= '"url":"' . esc_attr('#inline-' . $el_id . '-' . $album_th_id) . '","type":"inline"';
									$inline_hidden .= '<div id="inline-' . esc_attr( $el_id . '-' . $album_th_id ) . '" class="ilightbox-html" style="display: none;">' . $album_item_attributes['url'] . '</div>';
									apply_filters( 'uncode_before_checking_consent', true, $album_item_attributes['mime_type'] );
								} else {
									$album_item_dimensions .= '"width":"' . esc_attr($album_item_attributes['width']) . '",';
									$album_item_dimensions .= '"height":"' . esc_attr($album_item_attributes['height']) . '",';
									$resize_album_item = wp_get_attachment_image_src($thumb_attributes->id, 'medium');
									$album_item_dimensions .= '"thumbnail":"' . esc_url($resize_album_item[0]) . '",';
									$album_item_dimensions .= '"url":"' . esc_url($album_item_attributes['url']) . '"';
								}
								$album_item_dimensions .= '},';
							}
						}
					}
					$album_item_dimensions = trim(preg_replace('/\t+/', '', $album_item_dimensions));//remove tabs from string
					$data_values .= ' data-album=\'[' . rtrim($album_item_dimensions, ',') . ']\'';
				}
				if ( isset($block_data['lb_index']) ) {
					$data_values .= ' data-lb-index="' . $block_data['lb_index'] . '"';
				}

				if ( isset( $inline_hidden ) ) {
					$output .= $inline_hidden;
				}

				$output .= '<a tabindex="-1" href="'. (($media_type === 'image') ? $create_link : '').'"'.((count($a_classes) > 0 ) ? ' class="'.trim(implode(' ', $a_classes)).'"' : '').$lightbox_data.$data_values.'>';

			endif;

			if (is_object($media_attributes) && $media_attributes->post_mime_type !== 'oembed/facebook' && $media_attributes->post_mime_type !== 'oembed/twitter') :

			$single_limit_width = isset($block_data['limit-width']) && $block_data['limit-width'] === true ? ' limit-width' : '';

			$output .= 	'<div class="t-entry-visual-overlay"' . $overlay_blend . '><div class="t-entry-visual-overlay-in '.$overlay_color.'"' . $overlay_opacity . '></div></div>
									<div class="t-overlay-wrap' . $single_limit_width . '">
										<div class="t-overlay-inner">
											<div class="t-overlay-content">
												<div class="t-overlay-text '.$block_data['text_padding']. $sep_extra . $inline_price .'">';

			if ($single_text === 'overlay'):

				$output .= $entry;

			else:

				$output .=					'<div class="t-entry t-single-line">';

				if (array_key_exists('icon',$layout)) :

					if ($single_icon !== '') :

						$output .= 				'<i class="'.$single_icon. $icon_size . ' t-overlay-icon"></i>';

					endif;

				endif;

				$output .= 						'</div>';

			endif;

			$output .= 						'</div></div></div></div>';

			endif;

			if (array_key_exists('media',$layout)) :

				if ( ( isset($layout['media'][3]) && $layout['media'][3] === 'show-sale' ) || ( is_archive() && !isset($layout['media'][3]) ) ) {
					global $woocommerce;
					if ( class_exists( 'WooCommerce' ) ) {
						if (isset($block_data['id'])) {
							$product = wc_get_product($block_data['id']);
							$post_obj = get_post($product);
							if ( is_object($product) ) {
								if ( $product->is_on_sale() ) {
									$output .= apply_filters( 'woocommerce_sale_flash', '<div class="woocommerce"><span class="onsale">' . esc_html__( 'Sale!', 'woocommerce' ) . '</span></div>', $post_obj, $product );
								} elseif ( ! $product->is_in_stock() ) {
									$output .= apply_filters( 'uncode_woocommerce_out_of_stock', '<div class="font-ui"><div class="woocommerce"><span class="soldout">' . esc_html__( 'Out of stock', 'woocommerce' ) . '</span></div></div>', $post_obj, $product );
								}
							}
						}
					}
				}

				if ($style_preset === 'metro'):

					if ($single_elements_click === 'yes' && $media_type === 'image'):

						$a_classes[] = 't-background-click';

						$data_values = !empty($block_data['link']['target']) ? ' target="'.trim($block_data['link']['target']).'"' : '';

						$output .= 			'<a href="'. (($media_type === 'image') ? $create_link : '').'"'.((count($a_classes) > 0 ) ? ' class="'.trim(implode(' ', $a_classes)).'"' : '').$lightbox_data.$data_values.'>
												<div class="t-background-cover'.($adaptive_async_class !== '' ? $adaptive_async_class : '').'" style="background-image:url(\''.$item_media.'\')"'.($adaptive_async_data !== '' ? $adaptive_async_data : '').'></div>
											</a>';

					else:

						if ($media_type === 'image') :

							$output .= 		'<div class="t-background-cover'.($adaptive_async_class !== '' ? $adaptive_async_class : '').'" style="background-image:url(\''.$item_media.'\')"'.($adaptive_async_data !== '' ? $adaptive_async_data : '').'></div>';

						else:

							$output .= 		'<div class="fluid-object '. trim(implode(' ', $title_classes)) . ' '.$object_class.'"'.$dummy_oembed.'>'.$media_code.'</div>';

						endif;

					endif;

				else:

					if ($media_type === 'image') :

                        global $post;
						$media_alt = (isset($media_attributes->alt)) ? $media_attributes->alt : '';
                        if ( isset($block_data['id']) && class_exists( 'WooCommerce' ) && $product = wc_get_product($block_data['id']) ) {
                            $product_id = $product instanceof WC_Data ? $product->get_id() : $product->id;
                        } else {
                            $product_id = $post ? $post->ID : false;
                        }
						if ( $media_poster ) {
							$poster_th_id = get_post_meta($item_thumb_id, "_uncode_poster_image", true);
							$media_attributes = uncode_get_media_info($poster_th_id);
							$media_alt = (isset($media_attributes->alt)) ? $media_attributes->alt : '';
						}
                        $output .= apply_filters( 'post_thumbnail_html', '<img' . ( $adaptive_async_class !== '' ? ' class="' . trim( $adaptive_async_class ) . '"' : '' ) . ' src="' . $item_media . '" width="' . $image_orig_w . '" height="' . $image_orig_h . '" alt="' . $media_alt . '"' . ( $adaptive_async_data !== '' ? $adaptive_async_data : '' ) . ' />', $product_id, $item_thumb_id, array($image_orig_w, $image_orig_h), '');

					elseif ($media_type === 'email') :

						$output .= get_avatar($media_attributes->guid, $image_orig_w);

					else:
						if ($object_class !== '') {
							$title_classes[] = $object_class;
						}
						if (isset($media_attributes->post_mime_type)) {
							switch ($media_attributes->post_mime_type) {
								case 'oembed/svg':
								case 'image/svg+xml':
									$title_classes = array('fluid-svg');
								break;
								case 'oembed/facebook':
								case 'oembed/twitter':
									$title_classes[] = 'social-object';
									if ($media_attributes->post_mime_type === 'oembed/facebook') {
										$title_classes[] = 'facebook-object';
									} else {
										if ($media_attributes->social_original) {
											$title_classes[] = 'twitter-object';
										} else {
											$title_classes[] = 'fluid-object';
										}
									}
									$dummy_oembed = '';
								break;
								default:
									if ( uncode_privacy_allow_content( $consent_id ) !== false ) {
										$title_classes[] = 'fluid-object';
									}
									break;
							}
						} else {
							$title_classes[] = 'fluid-object';
						}

						if ( uncode_privacy_allow_content( $consent_id ) === false ) {
							$title_classes[] = 'pushed';
						}

						$output .= 			'<div class="'. trim(implode(' ', $title_classes)) . '"'.$dummy_oembed.'>'.$media_code.'</div>';

					endif;

				endif;

			endif;

			if (($single_text !== 'overlay' || $single_elements_click !== 'yes') && $media_type === 'image'):

				$output .= '</a>';

			endif;

			if (class_exists( 'WooCommerce' ) && $is_product) {
				global $product;
				$product = wc_get_product($block_data['id']);
				if (!empty($product)) {
					if ( ( get_option('woocommerce_hide_out_of_stock_items') == 'yes' && ! $product->is_in_stock() ) || ! $product->is_visible() ) {
						return;
					}
					$product_add_to_cart = sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="%s %s product_type_%s product_button_loop"><span>%s</span></a>',
						esc_url( $product->add_to_cart_url() ),
						esc_attr( $block_data['id'] ),
						esc_attr( $product->get_sku() ),
						$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
						$product->supports( 'ajax_add_to_cart' ) && get_option('woocommerce_enable_ajax_add_to_cart') == 'yes' ? 'ajax_add_to_cart' : '',
						esc_attr( $product->get_type() ),
						esc_html( $product->add_to_cart_text())
					);

					$output .= '<div class="add-to-cart-overlay">'.$product_add_to_cart.'</div>';
				}

			}

			$output .= '</div>
				</div>
			</div>';

		endif;

		if ( ( $single_text === 'under' && $layoutLast !== 'media' ) || ( $single_text === 'lateral' ) ) :

			$output .= $entry;

		endif;

		if ( ot_get_option('_uncode_woocommerce_hooks') === 'on' && $is_product ) {
			ob_start();
			do_action( 'woocommerce_after_shop_loop_item_title');
			$output .= ob_get_clean();
		}

		$output .= 		'</div>
					</div>';

		if ( ot_get_option('_uncode_woocommerce_hooks') === 'on' && $is_product ) {
			ob_start();
			do_action( 'woocommerce_after_shop_loop_item');
			$output .= ob_get_clean();
		}

		do_action( 'uncode_create_single_block' );

		$post = $or_post;
		return $output;
	}
}

/**
 * Create post info HTML
 */
if (!function_exists('uncode_post_info')) {
	function uncode_post_info() {
		$categories = get_the_category();
		$separator = ', ';
		$output = array();
		$cat_output = '';

		$output[] = '<div class="date-info">' . get_the_date() . '</div>';

		if($categories){
			foreach($categories as $category) {
				$cat_output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( esc_html__( "View all posts in %s", 'uncode' ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
			}
			$output[] = '<div class="category-info"><span>|</span>' . esc_html__('In','uncode') . ' ' . trim($cat_output, $separator) . '</div>';
		}

		$output[] = '<div class="author-info"><span>|</span>' . esc_html__('By','uncode') . ' ' . '<a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'">'.get_the_author().'</a></div>';

		return '<div class="post-info">' . implode('', $output) . '</div>';
	}
}

/**
 * Create portfolio info HTML
 */
if (!function_exists('uncode_portfolio_info')) {
	function uncode_portfolio_info() {
		$categories = wp_get_object_terms( get_the_id(), 'portfolio_category' );
		$separator = ', ';
		$output = array();
		$cat_output = '';

		if($categories){

			foreach ( $categories as $cat ) {
				$cat_output .= '<a href="'.get_term_link($cat->term_id, $cat->taxonomy).'" title="' . esc_attr( sprintf( esc_html__( "View all posts in %s", 'uncode' ), $cat->name ) ) . '">'.$cat->name.'</a>'.$separator;
			}
			$output[] = '<div class="category-info">' . esc_html__('In','uncode') . ' ' . trim($cat_output, $separator) . '</div>';
		}
		return '<div class="post-info">' . implode('', $output) . '</div>';
	}
}

/**
 * Create info box HTML
 */
if (!function_exists('uncode_get_info_box')) {
	function uncode_get_info_box($out, $atts) {
		$post_type = get_post_type();
		$separator = ', ';
		$output = '';

		switch ($out) {
			case 'date':
				return '<span class="date-info">' . get_the_date() . '</span>';
				break;

			case 'author':
				$output .= '<span class="author-wrap">';
				if ( $atts !== false && is_array( $atts ) && isset($atts['size']) && $atts['size'] !== false ) {
					$output .= '<a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'"><span class="uncode-ib-avatar uncode-ib-avatar-size-' . $atts['size'][1] . '">' . get_avatar( get_the_author_meta( 'ID' ), $atts['size'][0], false, get_the_author() ) . '</span></a>';
				}
				$by_prefix = esc_html__('By','uncode');
				if ( $atts !== false && is_array( $atts ) && isset($atts['no_prefix']) && $atts['no_prefix'] === true ) {
					$by_prefix = '';
				}
				$output .= '<span class="author-info">' . $by_prefix . ' ' . '<a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'">'.get_the_author().'</a></span>';
				$output .= '</span>';
				return $output;
				break;

			case 'comments':
				$num_comments = get_comments_number( get_the_id() );
				if ( $num_comments > 0 ) {
					$output .= '<a href="#commenta-area">';
				} else {
					$output .= '<span>';
				}
				$output .= $num_comments.' '._nx( 'Comment', 'Comments', $num_comments, 'comments', 'uncode' );
				if ( $num_comments > 0 ) {
					$output .= '</a>';
				} else {
					$output .= '</span>';
				}
				return $output;
				break;

			case 'reading':
				$time = uncode_estimated_reading_time( get_the_id() );
				return $time;
				break;

			case 'tax':
			default:
				$tax_class = '';
				if ( $post_type === 'post' ) {
					$categories = get_the_category();
				} else {
					$categories = wp_get_object_terms( get_the_id(), "{$post_type}_category" );
				}
				foreach( $categories as $category ) {
					$output .= '<a href="'.get_term_link( $category->term_id ).'" title="' . esc_attr( sprintf( esc_html__( "View all posts in %s", 'uncode' ), $category->name ) ) . '" class="' . $tax_class . '">'.$category->name.'</a>'.$separator;
				}
				if ( $output !== '' ) {
					$in_prefix = '';
					if ( $atts !== true ) {
						$in_prefix = esc_html__('In','uncode');
					}
					return '<span class="category-info">' . $in_prefix . ' ' . trim($output, $separator) . '</span>';
				}
				break;
		}

	}
}

/**
 * Build breadcrumb
 */
if (!function_exists('uncode_breadcrumbs')) {
	function uncode_breadcrumbs($navigation_index = '') {

		if ( apply_filters( 'uncode_woocommerce_breadcrumbs', false ) && function_exists( 'is_woocommerce' ) && is_woocommerce() && function_exists( 'woocommerce_breadcrumb' ) ) {

			$args = apply_filters( 'woocommerce_breadcrumb_defaults', array(
				'delimiter'   => '',
				'wrap_before' => '<ol class="breadcrumb header-subtitle">',
				'wrap_after'  => '</ol>',
				'before'      => '<li>',
				'after'       => '</li>',
				'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
			) );

			ob_start();
			woocommerce_breadcrumb($args);
			return ob_get_clean();

		}

		/* === OPTIONS === */
		$text['home'] = esc_html__('Home', 'uncode');

		// text for the 'Home' link
		$text['category'] = esc_html__('Archive by Category', 'uncode') . ' ' . '"%s"';

		// text for a category page
		$text['search'] = esc_html__('Search Results for', 'uncode') . ' ' . '"%s" Query';

		// text for a search results page
		$text['tag'] = esc_html__('Posts Tagged', 'uncode') . ' ' . '"%s"';

		// text for a tag page
		$text['author'] = esc_html__('Articles Posted by', 'uncode') . ' ' . '%s';

		// text for an author page
		$text['404'] = esc_html__('Error 404', 'uncode');

		// text for the 404 page

		$show_current = 1;

		// 1 - show current post/page/category title in breadcrumbs, 0 - don't show
		$show_on_home = 0;

		// 1 - show breadcrumbs on the homepage, 0 - don't show
		$show_home_link = 1;

		// 1 - show the 'Home' link, 0 - don't show
		$show_title = 1;

		// 1 - show the title for the links, 0 - don't show
		$delimiter = '';

		// delimiter between crumbs
		$before = '<li class="current">';

		// tag before the current crumb
		$after = '</li>';

		// tag after the current crumb
		/* === END OF OPTIONS === */

		global $post;
		$home_link = esc_url( apply_filters( 'uncode_breadcrumbs_home_url', home_url( '/' ) ) );
		$link_before = '<li>';
		$link_after = '</li>';
		$link_attr = '';
		$link = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;

		$parent_id = '';
		if (is_object($post) && isset($post->post_parent)) {
			$parent_id = $parent_id_2 = $post->post_parent;
		}

		$frontpage_id = get_option('page_on_front');
		$html = '';

		if (is_home() || is_front_page()) {

			if ($show_on_home == 1) {
				$html = '<ol><li><a href="' . $home_link . '">' . $text['home'] . '</a></li></ol>';
			}
		} else {

			$html = '<ol class="breadcrumb header-subtitle">';
			if ($show_home_link == 1) {
				$html.= '<li><a href="' . $home_link . '">' . $text['home'] . '</a></li>';
				if ($frontpage_id == 0 || $parent_id != $frontpage_id) {
					$html.= $delimiter;
				}
			}

			if (is_category()) {
				$this_cat = get_category(get_query_var('cat') , false);
				if ($this_cat ->parent != 0) {
					$cats = get_category_parents($this_cat->parent, TRUE, $delimiter);
					if ($show_current == 0) {
						$cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
					}
					$cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
					$cats = str_replace('</a>', '</a>' . $link_after, $cats);
					if ($show_title == 0) {
						$cats = preg_replace('/ title="(.*?)"/', '', $cats);
					}
					$html.= $cats;
				}
				if ($show_current == 1) {
					$html.= $before . sprintf($text['category'], single_cat_title('', false)) . $after;
				}
			} elseif (is_search()) {
				$html.= $before . sprintf($text['search'], get_search_query()) . $after;
			} elseif (is_day()) {
				$html.= sprintf($link, get_year_link(get_the_time('Y')) , get_the_time('Y')) . $delimiter;
				$html.= sprintf($link, get_month_link(get_the_time('Y') , get_the_time('m')) , get_the_time('F')) . $delimiter;
				$html.= $before . get_the_time('d') . $after;
			} elseif (is_month()) {
				$html.= sprintf($link, get_year_link(get_the_time('Y')) , get_the_time('Y')) . $delimiter;
				$html.= $before . get_the_time('F') . $after;
			} elseif (is_year()) {
				$html.= $before . get_the_time('Y') . $after;
			} elseif (is_single() && !is_attachment()) {
				if (get_post_type() != 'post') {
					$parent_link = '';
					$parent_title = '';
					if ($navigation_index !== '') {
						$parent_link = get_permalink($navigation_index);
						$parent_title = get_the_title($navigation_index);
					} else {
						$post_type = get_post_type_object(get_post_type());
						$slug = $post_type->rewrite;
						//$parent_link = esc_url( $home_link . ltrim($slug['slug'],'/') );
						$parent_link = get_post_type_archive_link(get_post_type());
						$parent_title = $post_type->labels->name;
					}
					$html .= sprintf($link, $parent_link, $parent_title);
					if ($show_current == 1) {
						$html .= $delimiter . $before . get_the_title() . $after;
					}
				} else {
					$cat = get_the_category();
					if (isset($cat[0])) {
						$cat = $cat[0];
						$cats = get_category_parents($cat, TRUE, $delimiter);
						if ($show_current == 0) {
							$cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
						}
						$cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
						$cats = str_replace('</a>', '</a>' . $link_after, $cats);
						if ($show_title == 0) {
							$cats = preg_replace('/ title="(.*?)"/', '', $cats);
						}
						$html.= $cats;
						if ($show_current == 1) {
							$html.= $before . get_the_title() . $after;
						}
					}
				}
			} elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {

				if (is_tax()) {
					$tax = get_taxonomy( get_queried_object()->taxonomy );
					if ($show_current == 1) {
						$html.= $before . sprintf(($tax->hierarchical ? $text['category'] : $text['tag']), single_cat_title('', false)) . $after;
					}
				} else {
					$post_type = get_post_type_object(get_post_type());

					if ( $post_type ) {
						$html.= $before . $post_type->labels->singular_name . $after;
					}
				}

			} elseif (is_attachment()) {
				$parent = get_post($parent_id);
				$cat = get_the_category($parent->ID);
				$cat = isset($cat[0]) ? $cat[0] : false;
				if ($cat) {
					$cats = get_category_parents($cat, TRUE, $delimiter);
					$cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
					$cats = str_replace('</a>', '</a>' . $link_after, $cats);
					if ($show_title == 0) {
						$cats = preg_replace('/ title="(.*?)"/', '', $cats);
					}
					$html.= $cats;
				}
				$html.= sprintf($link, get_permalink($parent) , $parent->post_title);
				if ($show_current == 1) {
					$html.= $delimiter . $before . get_the_title() . $after;
				}
			} elseif (is_page() && !$parent_id) {
				if ($show_current == 1) {
					$html.= $before . get_the_title() . $after;
				}
			} elseif (is_page() && $parent_id) {
				if ($parent_id != $frontpage_id) {
					$breadcrumbs = array();
					while ($parent_id) {
						$page = get_page($parent_id);
						if ($parent_id != $frontpage_id) {
							$breadcrumbs[] = sprintf($link, get_permalink($page
								->ID) , get_the_title($page->ID));
						}
						$parent_id = $page->post_parent;
					}
					$breadcrumbs = array_reverse($breadcrumbs);
					for ($i = 0;$i < count($breadcrumbs);$i++) {
						$html.= $breadcrumbs[$i];
						if ($i != count($breadcrumbs) - 1) {
							$html.= $delimiter;
						}
					}
				}
				if ($show_current == 1) {
					if ($show_home_link == 1 || ($parent_id_2 != 0 && $parent_id_2 != $frontpage_id)) {
						$html.= $delimiter;
					}
					$html.= $before . get_the_title() . $after;
				}
			} elseif (is_tag()) {
				$html.= $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
			} elseif (is_author()) {
				global $author;
				$userdata = get_userdata($author);
				$html.= $before . sprintf($text['author'], $userdata->display_name) . $after;
			} elseif (is_404()) {
				$html.= $before . $text['404'] . $after;
			} elseif (has_post_format() && !is_singular()) {
				$html.= get_post_format_string(get_post_format());
			}

			if (get_query_var('paged')) {
				if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
					$html.= ' (';
				}
				$html.= '<li class="paged">' . esc_html__('Page', 'uncode' ) . ' ' . get_query_var('paged') . '</li>';
				if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
					$html.= ')';
				}
			}

			$html.= '</ol>';
		}

		return $html;
	}
}

/**
 * Get image size for the dummy space
 */
if (!function_exists('uncode_get_dummy_size')) {
	function uncode_get_dummy_size($id, $size = null) {
		$attachment_meta = get_post_meta($id, '_wp_attachment_metadata', true);
		if ($size != null && isset($attachment_meta['sizes']) && array_key_exists($size, $attachment_meta['sizes'])) {
			$attachment_meta = $attachment_meta['sizes'][$size];
		}
		$width = (isset($attachment_meta['width']) && $attachment_meta['width'] !== '') ? $attachment_meta['width'] : 1;
		$height = (isset($attachment_meta['height']) && $attachment_meta['height'] !== '') ? $attachment_meta['height'] : 0;

		$dummy = round(($height / $width) * 100, 2);

		return array(
			'dummy' => $dummy,
			'width' => $width,
			'height' => $height,
		);
	}
}
