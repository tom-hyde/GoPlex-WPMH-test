<?php

	function uncode_type_numeric_slider_settings_field($settings, $value) {
	   return '<div class="ot-numeric-slider-wrap">
	   		<input name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-textinput '.$settings['param_name'].' '.$settings['type'].'" type="hidden" value="'.$value.'"/>
	   		<span class="numeric-slider-helper-input">'.$value.'</span>
	   		<div class="ot-numeric-slider '.$settings['param_name'].'" data-value="'.$value.'" data-min="'.$settings['min'].'" data-max="'.$settings['max'].'" data-step="'.$settings['step'].'"></div>
	   	</div>';
	}
	vc_add_shortcode_param('type_numeric_slider', 'uncode_type_numeric_slider_settings_field', plugins_url( 'assets/js/fix_inputs_init.js', __FILE__ ));

	function uncode_items_settings_field($settings, $value) {
	   global $post;
	   $current_value = $value;

	   return '<input name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-textinput '.$settings['param_name'].' '.$settings['type'].' uncode_bundle_items" type="hidden" data-post="'.$_REQUEST['post_id'].'" value="'.$value.'"/>
	   		  <span class="vc_ui-wp-spinner spinner" style="float: left;width: 100%;"></span>
	   		  <ul id="uncode_items_container" data-container="" class="option-tree-setting-wrap"></ul>';
	}
	vc_add_shortcode_param('uncode_items', 'uncode_items_settings_field', plugins_url( 'assets/js/index_items_init.js', __FILE__ ));

	function uncode_matrix_set_amount_field($settings, $value) {
	   global $post;
	   $current_value = $value;

	   return '<input name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-textinput '.$settings['param_name'].' '.$settings['type'].'" type="text" data-post="'.$_REQUEST['post_id'].'" value="'.$value.'"/>
	   <button class="button refresh-matrix" disabled>' . __('Refresh', 'uncode') . '</button>';
	}
	vc_add_shortcode_param('uncode_matrix_set_amount', 'uncode_matrix_set_amount_field', plugins_url( 'assets/js/index_items_init.js', __FILE__ ));

	function uncode_matrix_items_settings_field($settings, $value) {
	   global $post;
	   $current_value = $value;

	   return '<input name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-textinput '.$settings['param_name'].' '.$settings['type'].' uncode_bundle_items" type="hidden" data-post="'.$_REQUEST['post_id'].'" value="'.$value.'"/>
	   		  <span class="vc_ui-wp-spinner spinner" style="float: left;width: 100%;"></span>
	   		  <ul id="uncode_matrix_items_container" data-container="matrix" class="option-tree-setting-wrap"></ul>';
	}
	vc_add_shortcode_param('uncode_matrix_items', 'uncode_matrix_items_settings_field', plugins_url( 'assets/js/index_items_init.js', __FILE__ ));

	function uncode_fieldAttachedMedia( $att_ids = array() ) {
		$output = '';
		if (isset($_POST['mediaid'])) $att_ids[] = $_POST['mediaid'];

		foreach ( $att_ids as $th_id ) {
			$thumb_src = wp_get_attachment_image_src( $th_id, 'thumbnail' );
			if ( $thumb_src ) {
				$thumb_src = $thumb_src[0];
				$output .= '
				<li class="added attachment">
					<div class="attachment-preview landscape">
						<div class="thumbnail" rel="' . $th_id . '">
							<div class="centered">
								<img src="' . $thumb_src . '" />
							</div>
						</div>
					</div>
					<a href="#" class="icon-remove fa fa-times"></a>
				</li>';
			} else {
				$post = get_post($th_id);
				$internal = '';
				if (isset($post->post_mime_type)) {
					$type = $post->post_mime_type;
					if (strpos($type,'audio/') !== false) {
						$internal = '<i class="fa fa-music fa-4x" style="display: block; margin-top: 30%;" /></i>';
					} else if (strpos($type,'video/') !== false) {
						$type = $post->post_mime_type;
						$poster = get_post_meta($th_id, "_uncode_poster_image", true);
						$thumb_src = wp_get_attachment_image_src( $poster, 'thumbnail' );
						if ( $thumb_src ) {
							$thumb_src = $thumb_src[0];
							$internal = '<div class="centered">
														<img src="' . $thumb_src . '" />
													</div>';
						}
						$internal .= '<i class="fa fa-play-circle-o fa-4x" style="display: block; margin-top: 30%;" /></i>';
					} else if ($type === 'oembed/gallery') {
						$poster = get_post_meta($th_id, "_uncode_gallery_cover", true);
						$thumb_src = wp_get_attachment_image_src( $poster, 'thumbnail' );
						if ( $thumb_src ) {
							$thumb_src = $thumb_src[0];
							$internal = '<div class="centered">
														<img src="' . $thumb_src . '" />
													</div>';
							$internal .= '<i class="dashicons dashicons-images-alt2" style="color:#fff;font-size:35px;display: block;text-align:center;width:100%;top:50%;-webkit-transform:translateY(-100%);transform:translateY(-100%);text-shadow:0 1px 1px rgba(0,0,0,.25);position:relative;"></i>';
						} else {
							$internal .= '<i class="dashicons dashicons-images-alt2" style="color:#444;font-size:35px;display: block;text-align:center;width:100%;top:50%;-webkit-transform:translateY(-100%);transform:translateY(-100%);position:relative;"></i>';
						}
					} else {
						switch ($type) {
							case 'image/jpeg':
							case 'image/png':
							case 'image/gif':
							case 'image/url':
								$internal = '<img src="' . $post->guid . '" />';
							break;
							case 'oembed/html':
							case 'oembed/iframe':
								$internal = '<i class="fa fa-html5 fa-4x" style="display: block; margin-top: 10%;"" /></i>';
								break;
							case 'oembed/svg':
								$internal = '<div class="oembed-svg">' . $post->post_content . '</div>';
								break;
							case 'oembed/gallery':
								$internal = '<i class="dashicons dashicons-images-alt2" style="color:#444;font-size:35px;display: block;text-align:center;width:100%;top:50%;-webkit-transform:translateY(-100%);transform:translateY(-100%);position:relative;"></i>';
								break;
							default:
								$internal = '<div class="centered"><div id="oembed-'.$post->ID.'" class="oembed"><span class="vc_ui-wp-spinner spinner" style="display: block;float: none;margin: auto;left: -50%;position: relative;margin-top: -10px;"></span></div><div class="oembed_code" style="display: none;">' . $post->guid . '</div></div>';
							break;
						}
					}
				} else {
					$internal = '';
				}
				$output .= '
				<li class="added attachment">
					<div class="attachment-preview landscape">
						<div class="thumbnail" rel="' . $th_id . '">
							'.$internal.'
						</div>
					</div>
					<a href="#" class="icon-remove fa fa-times"></a>
				</li>';
			}
		}
		if ( $output != '' ) {
			if (isset($_POST['mediaid'])) {
				echo do_shortcode( shortcode_unautop( $output ) );
				die();
			}
			return $output;
		}

	}

	add_action( 'wp_ajax_fieldAttachedMedia', 'uncode_fieldAttachedMedia');

	function uncode_media_element_settings_field($settings, $value) {
	  	return '<input type="hidden" class="wpb_vc_param_value uncode_gallery_attached_images_ids ' . $settings['param_name'] . ' ' . $settings['type'] . '" name="' . $settings['param_name'] . '" value="' . $value . '" />
	   		<div class="uncode_widget_attached_images">
				<ul class="uncode_widget_attached_images_list">
					'.(( $value != '' ) ? uncode_fieldAttachedMedia( explode( ",", $value ) ) : '').'
				</ul>
			</div>
			<div class="gallery_widget_site_images">
			</div>
	   		<a class="add_media_widget'. (isset($settings['is_custom_svg']) && $settings['is_custom_svg'] === true ? ' add_media_widget--is_custom_svg' : '' ) . (isset($settings['has_galleries']) && $settings['has_galleries'] === true ? ' add_media_widget--with-galleries' : '' ) . '" href="#" use-single="'. ($settings['param_name'] === 'medias' ? 'false' : 'true' ) .'" title="' . esc_html__( 'Add media', "uncode" ) . '">' . esc_html__( 'Add media', "uncode" ) . '</a>';
	}

	vc_add_shortcode_param('media_element', 'uncode_media_element_settings_field', plugins_url( 'assets/js/media_items.js', __FILE__ ));

	function uncode_radio_images_settings_field( $settings, $value ) {

		$output = '';
        $output .= '<div class="uncode-radio-image">';
		$output .= '<input type="hidden" class="wpb_vc_param_value '.esc_attr($settings['param_name']).' '.esc_attr($settings['type']).'" name="'.esc_attr($settings['param_name']).'" value="'.esc_attr($value).'"/>';
        $output .= '<ul class="uncode_radio_images_list '.esc_attr($settings['param_name']).'">';
        $flip = '';
        if ( isset( $settings['flip'] ) && $settings['flip'] == true ) {
        	$flip = ' flip';
        }

		foreach($settings['options'] as $key => $val) {

			foreach($val as $name => $src) {

				if($value == $key) {
					$checked = ' class="checked"';
				} else {
					$checked = '';
				}

				$output .= '<li><label' . $checked . '>
					<input type="radio" class="uncode_radio_image" value="'. $key .'" name="uncode_radio_image" ' . $checked . ' />
					<span class="uncode_radio_image_src uncode_radio_image_src_' . $key . $flip . '" style="background-image:url('. $src .')"></span>
					<span class="uncode_radio_image_title">'.$name.'</span>
				</label></li>';
			}

		}

        $output .= '</ul>';
        $output .= '</div>';

        return $output;
	}

	vc_add_shortcode_param( 'uncode_radio_image', 'uncode_radio_images_settings_field' );

	function uncode_numeric_text_field( $settings, $value ) {

		$output = '';
		$output .= '<input type="text" class="uncode_numeric_textfield wpb_vc_param_value '.esc_attr($settings['param_name']).' '.esc_attr($settings['type']).'" name="'.esc_attr($settings['param_name']).'" value="'.esc_attr($value).'"/>';

        return $output;
	}

	vc_add_shortcode_param( 'uncode_numeric_textfield', 'uncode_numeric_text_field' );

	function uncode_inner_tabs( $settings, $value ) {

		if ( !isset($settings['tabs']) )
			return;

		$output = '';
        $output .= '<div class="uncode-inner-tabs" data-parem="' . esc_attr($settings['param_name']) . '">';
        $output .= '<ul class="uncode_inner_tabs">';
        $checked = false;

		foreach($settings['tabs'] as $key => $val) {
			if ( !$checked ) {
				$selected = ' active';
				$checked = true;
			} else {
				$selected = '';
			}
			$output .= '<li class="uncode_inner_tab' . esc_attr($selected) . '" data-tab=\'{"param":"' . esc_attr($settings['param_name']) . '","tab":"'.esc_attr($val).'"}\'><span class="uncode_inner_tab_title">'.esc_attr($key).'</span></li>';

		}

        $output .= '</ul>';
        $output .= '</div>';

        return $output;

	}

	vc_add_shortcode_param( 'uncode_inner_tabs', 'uncode_inner_tabs', get_template_directory_uri().'/core/assets/js/min/uncode_params.min.js' );


	function uncode_add_tmpl_attachment() { ?>
		<script type="text/html" id="uncode_settings-media-block">
			<li class="added attachment">
				<div class="attachment-preview landscape">
					<div class="thumbnail" rel="<%= id %>">
						<% if ( mime == 'oembed/svg') { %>
							<div class="oembed-svg"><%= description %></div>
						<% } else if ( mime == 'oembed/html' || mime == 'oembed/iframe') { %>
							<i class="fa fa-html5 fa-4x" style="display: block; margin-top: 30%;" /></i>
						<% } else { %>

							<% if ( type == 'image' || type == 'svg' ) { %>
							<div class="centered">
								<img src="<%= url %>" />
							</div>
							<% } else if ( mime == 'oembed/gallery' ) { %>
								<% if ((url.toLowerCase().endsWith('.jpg') || url.toLowerCase().endsWith('.jpeg') || url.toLowerCase().endsWith('.gif') || url.toLowerCase().endsWith('.png'))) { %>
									<div class="centered">
										<img src="<%= url %>" />
									</div>
									<i class="dashicons dashicons-images-alt2" style="color:#fff;font-size:35px;display: block;text-align:center;width:100%;top:50%;-webkit-transform:translateY(-100%);transform:translateY(-100%);text-shadow:0 1px 1px rgba(0,0,0,.25);position:relative;"></i>
								<% } else { %>
									<i class="dashicons dashicons-images-alt2" style="color:#444;font-size:35px;display: block;text-align:center;width:100%;top:50%;-webkit-transform:translateY(-100%);transform:translateY(-100%);position:relative;"></i>
								<% } %>

							<% } else { %>
							<div class="centered">
								<div class="oembed"></div><div class="oembed_code" style="display: none;"><%= url %></div>
							</div>
							<% } %>

						<% } %>
					</div>
				</div>
				<a href="#" class="icon-remove fa fa-times"></a>
			</li>
		</script>

		<script type="text/html" id="tmpl-attachment-details">
		<h2>
			<?php _e( 'Attachment Details', 'uncode' ); ?>
			<span class="settings-save-status">
				<span class="vc_ui-wp-spinner spinner"></span>
				<span class="saved"><?php esc_html_e('Saved.'); ?></span>
			</span>
		</h2>
		<div class="attachment-info">
			<# if ( 'oembed/gallery' === data.mime ) { #>
			<div class="thumbnail thumbnail-image">
			<# } else { #>
			<div class="thumbnail thumbnail-{{ data.type }}">
			<# } #>
				<# if ( data.uploading ) { #>
					<div class="media-progress-bar"><div></div></div>
				<# } else if ( 'image' === data.type && data.sizes ) { #>
					<img src="{{ data.size.url }}" draggable="false" alt="" />
				<# } else if ( 'oembed/gallery' === data.mime ) { #>
					<# if ( /(jpg|jpeg|gif|png)$/i.test(data.cover_medium) ) { #>
						<img src="{{ data.cover_medium }}" class="icon" draggable="false" alt="" />
					<# } else { #>
						<i class="dashicons dashicons-images-alt2" style="color:#444;font-size:80px;display: block;text-align:center;height:100px;width:100%;line-height: 100px;" /></i>
					<# } #>
				<# } else { #>
					<img src="{{ data.icon }}" class="icon" draggable="false" alt="" />
				<# } #>
			</div>
			<div class="details">
				<# if ( 'oembed/gallery' === data.mime ) { #>
					<# function truncate(str, max) {
						return str.length > max ? str.substr(0, max-1) + '…' : str;
					} #>
					<div class="filename">{{ data.title }}</div>
					<div>{{ truncate(data.description, 100) }}</div>
					<div style="font-size:90%;">{{ truncate(data.caption, 90) }}</div>
				<# } else { #>
					<div class="filename">{{ data.filename }}</div>
					<div class="uploaded">{{ data.dateFormatted }}</div>

					<div class="file-size">{{ data.filesizeHumanReadable }}</div>
					<# if ( 'image' === data.type && ! data.uploading ) { #>
						<# if ( data.width && data.height ) { #>
							<div class="dimensions">{{ data.width }} &times; {{ data.height }}</div>
						<# } #>

						<# if ( data.can.save && data.sizes ) { #>
							<a class="edit-attachment" href="{{ data.editLink }}&amp;image-editor" target="_blank"><?php _e( 'Edit Image', 'uncode' ); ?></a>
						<# } #>
					<# } #>

					<# if ( data.fileLength ) { #>
						<div class="file-length"><?php _e( 'Length:', 'uncode' ); ?> {{ data.fileLength }}</div>
					<# } #>

					<# if ( ! data.uploading && data.can.remove ) { #>
						<?php if ( MEDIA_TRASH ): ?>
						<# if ( 'trash' === data.status ) { #>
							<button type="button" class="button-link untrash-attachment"><?php _e( 'Untrash', 'uncode' ); ?></button>
						<# } else { #>
							<button type="button" class="button-link trash-attachment"><?php _ex( 'Trash', 'verb', 'uncode' ); ?></button>
						<# } #>
						<?php else: ?>
							<button type="button" class="button-link delete-attachment"><?php _e( 'Delete Permanently', 'uncode' ); ?></button>
						<?php endif; ?>
					<# } #>
				<# } #>

				<div class="compat-meta">
					<# if ( data.compat && data.compat.meta ) { #>
						{{{ data.compat.meta }}}
					<# } #>
				</div>
			</div>
		</div>

		<# if ( 'oembed/gallery' === data.mime ) { #>
			<div><a class="edit-attachment" style="text-decoration:none;" href="{{ data.parentPostEditLink }}" target="_blank"><?php esc_html_e( 'View Gallery', 'uncode' ); ?></a></div>

			<# if ( ! data.uploading && data.can.remove ) { #>
				<?php if ( MEDIA_TRASH ): ?>
				<# if ( 'trash' === data.status ) { #>
					<button style="display: none;" type="button" class="button-link untrash-attachment"><?php _e( 'Untrash', 'uncode' ); ?></button>
				<# } else { #>
					<button style="display: none;" type="button" class="button-link trash-attachment"><?php _ex( 'Trash', 'verb', 'uncode' ); ?></button>
				<# } #>
				<?php else: ?>
					<button style="display: none;" type="button" class="button-link delete-attachment"><?php _e( 'Delete Permanently', 'uncode' ); ?></button>
				<?php endif; ?>
			<# } #>
		<# } #>

		<# if ( 'oembed/gallery' !== data.mime ) { #>
			<label class="setting" data-setting="url">
				<span class="name"><?php _e('URL', 'uncode'); ?></span>
				<input type="text" value="{{ data.url }}" readonly />
			</label>
			<# var maybeReadOnly = data.can.save || data.allowLocalEdits ? '' : 'readonly'; #>
			<?php if ( post_type_supports( 'attachment', 'title' ) ) : ?>
			<label class="setting" data-setting="title">
				<span class="name"><?php _e('Title', 'uncode'); ?></span>
				<input type="text" value="{{ data.title }}" {{ maybeReadOnly }} />
			</label>
			<?php endif; ?>
			<# if ( 'audio' === data.type ) { #>
			<?php foreach ( array(
				'artist' => __( 'Artist' ),
				'album' => __( 'Album' ),
			) as $key => $label ) : ?>
			<label class="setting" data-setting="<?php echo esc_attr( $key ) ?>">
				<span class="name"><?php echo $label ?></span>
				<input type="text" value="{{ data.<?php echo $key ?> || data.meta.<?php echo $key ?> || '' }}" />
			</label>
			<?php endforeach; ?>
			<# } #>
			<label class="setting" data-setting="caption">
				<span class="name"><?php _e('Caption', 'uncode'); ?></span>
				<textarea {{ maybeReadOnly }}>{{ data.caption }}</textarea>
			</label>
			<# if ( 'image' === data.type ) { #>
				<label class="setting" data-setting="alt">
					<span class="name"><?php _e('Alt Text', 'uncode'); ?></span>
					<input type="text" value="{{ data.alt }}" {{ maybeReadOnly }} />
				</label>
			<# } #>
			<label class="setting" data-setting="description">
				<span class="name"><?php _e('Description', 'uncode'); ?></span>
				<textarea {{ maybeReadOnly }}>{{ data.description }}</textarea>
			</label>
		<# } #>
	</script>

		<script type="text/html" id="tmpl-attachment-details-two-column">
			<div class="attachment-media-view {{ data.orientation }}">
				<div class="thumbnail thumbnail-{{ data.type }}">
					<# if ( data.uploading ) { #>
						<div class="media-progress-bar"><div></div></div>
					<# } else if ( 'image' === data.type && data.sizes && data.sizes.large ) { #>
						<img class="details-image" src="{{ data.sizes.large.url }}" draggable="false" />
					<# } else if ( 'image' === data.type && data.sizes && data.sizes.full ) { #>
						<img class="details-image" src="{{ data.sizes.full.url }}" draggable="false" />
					<# } else if ( 'image/url' === data.mime ) { #>
						<span class="vc_ui-wp-spinner spinner" style="display: block; float: left"></span>
						<img class="details-image" src="{{ data.url }}" draggable="false" />
					<# } else if ( 'oembed/svg' === data.mime ) { #>
						{{{ data.description }}}
					<# } else if (((data.mime).indexOf("oembed") >= 0) ) { #>
					<# if ( 'oembed/html' === data.mime || 'oembed/iframe' === data.mime ) { #>
						{{{ data.description }}}
					<# } else { #>
						<div class="oembed"><span class="vc_ui-wp-spinner spinner" style="display: block; float: left"></span></div><div class="oembed_code" style="display: none;">{{ data.url }}</div>
					<# } #>
					<# } else if ( 'audio' === data.type ) { #>
					<div class="wp-media-wrapper">
						<audio style="visibility: hidden" controls class="wp-audio-shortcode" width="100%" preload="none">
							<source type="{{ data.mime }}" src="{{ data.url }}"/>
						</audio>
					</div>
					<# } else if ( 'video' === data.type ) {
						var w_rule = h_rule = '';
						if ( data.width ) {
							w_rule = 'width: ' + data.width + 'px;';
						} else if ( wp.media.view.settings.contentWidth ) {
							w_rule = 'width: ' + wp.media.view.settings.contentWidth + 'px;';
						}
						if ( data.height ) {
							h_rule = 'height: ' + data.height + 'px;';
						}
					#>
					<div style="{{ w_rule }}{{ h_rule }}" class="wp-media-wrapper wp-video">
						<video controls="controls" class="wp-video-shortcode" preload="metadata"
							<# if ( data.width ) { #>width="{{ data.width }}"<# } #>
							<# if ( data.height ) { #>height="{{ data.height }}"<# } #>
							<# if ( data.image && data.image.src !== data.icon ) { #>poster="{{ data.image.src }}"<# } #>>
							<source type="{{ data.mime }}" src="{{ data.url }}"/>
						</video>
					</div>
					<# } else { #>
					<img class="details-image" src="{{ data.icon }}" class="icon" draggable="false" />
					<# } #>
					<div class="attachment-actions">
						<# if ( 'image' === data.type && ! data.uploading && data.sizes ) { #>
							<a class="button edit-attachment" href="#"><?php esc_html_e( 'Edit Image', 'uncode'); ?></a>
						<# } #>
					</div>
				</div>
			</div>
			<div class="attachment-info">
				<span class="settings-save-status">
					<span class="vc_ui-wp-spinner spinner"></span>
					<span class="saved"><?php esc_html_e('Saved.','uncode'); ?></span>
				</span>
				<div class="details">
					<div class="filename"><strong><?php esc_html_e( 'File name:' , 'uncode') ; ?></strong> {{ data.filename }}</div>
					<div class="filename"><strong><?php esc_html_e( 'File type:' , 'uncode') ; ?></strong> {{ data.mime }}</div>
					<div class="uploaded"><strong><?php esc_html_e( 'Uploaded on:' , 'uncode') ; ?></strong> {{ data.dateFormatted }}</div>

					<div class="file-size"><strong><?php esc_html_e( 'File size:' , 'uncode') ; ?></strong> {{ data.filesizeHumanReadable }}</div>
					<# if ( 'image' === data.type && ! data.uploading ) { #>
						<# if ( data.width && data.height ) { #>
							<div class="dimensions"><strong><?php esc_html_e( 'Dimensions:' , 'uncode') ; ?></strong> {{ data.width }} &times; {{ data.height }}</div>
						<# } #>
					<# } #>

					<# if ( data.fileLength ) { #>
						<div class="file-length"><strong><?php esc_html_e( 'Length:' , 'uncode') ; ?></strong> {{ data.fileLength }}</div>
					<# } #>

					<# if ( 'audio' === data.type && data.meta.bitrate ) { #>
						<div class="bitrate">
							<strong><?php esc_html_e( 'Bitrate:' , 'uncode') ; ?></strong> {{ Math.round( data.meta.bitrate / 1000 ) }}kb/s
							<# if ( data.meta.bitrate_mode ) { #>
							{{ ' ' + data.meta.bitrate_mode.toUpperCase() }}
							<# } #>
						</div>
					<# } #>

					<div class="compat-meta">
						<# if ( data.compat && data.compat.meta ) { #>
							{{{ data.compat.meta }}}
						<# } #>
					</div>
				</div>

				<div class="settings">
					<label class="setting" data-setting="url">
						<span class="name"><?php esc_html_e('URL', 'uncode') ; ?></span>
						<# if ( 'oembed' === data.type ) { #>
						<textarea>{{ data.url }}</textarea>
						<# } else { #>
						<input type="text" value="{{ data.url }}" readonly />
						<# } #>
					</label>
					<# var maybeReadOnly = data.can.save || data.allowLocalEdits ? '' : 'readonly'; #>
					<label class="setting" data-setting="title">
						<span class="name"><?php esc_html_e('Title', 'uncode') ; ?></span>
						<input type="text" value="{{ data.title }}" {{ maybeReadOnly }} />
					</label>
					<# if ( 'audio' === data.type ) { #>
					<?php foreach ( array(
						'artist' => esc_html__( 'Artist' , 'uncode') ,
						'album' => esc_html__( 'Album' , 'uncode') ,
					) as $key => $label ) : ?>
					<label class="setting" data-setting="<?php echo esc_attr( $key ) ?>">
						<span class="name"><?php echo esc_html($label); ?></span>
						<input type="text" value="{{ data.<?php echo esc_attr($key); ?> || data.meta.<?php echo esc_attr($key); ?> || '' }}" />
					</label>
					<?php endforeach; ?>
					<# } #>
					<label class="setting" data-setting="caption">
						<span class="name"><?php esc_html_e( 'Caption' , 'uncode') ; ?></span>
						<textarea {{ maybeReadOnly }}>{{ data.caption }}</textarea>
					</label>
					<# if ( 'image' === data.type ) { #>
						<label class="setting" data-setting="alt">
							<span class="name"><?php esc_html_e( 'Alt Text' , 'uncode') ; ?></span>
							<input type="text" value="{{ data.alt }}" {{ maybeReadOnly }} />
						</label>
					<# } #>
					<label class="setting" data-setting="description">
						<span class="name"><?php esc_html_e('Description', 'uncode') ; ?></span>
						<textarea {{ maybeReadOnly }}>{{ data.description }}</textarea>
					</label>
					<label class="setting">
						<span class="name"><?php esc_html_e( 'Uploaded By' , 'uncode') ; ?></span>
						<span class="value">{{ data.authorName }}</span>
					</label>
					<# if ( data.uploadedTo ) { #>
						<label class="setting">
							<span class="name"><?php esc_html_e( 'Uploaded To' , 'uncode') ; ?></span>
							<# if ( data.uploadedToLink ) { #>
								<span class="value"><a href="{{ data.uploadedToLink }}">{{ data.uploadedToTitle }}</a></span>
							<# } else { #>
								<span class="value">{{ data.uploadedToTitle }}</span>
							<# } #>
						</label>
					<# } #>
					<div class="attachment-compat"></div>
				</div>

				<div class="actions">
					<a class="view-attachment" href="{{ data.link }}"><?php esc_html_e( 'View attachment page' , 'uncode') ; ?></a> |
					<a href="post.php?post={{ data.id }}&action=edit"><?php esc_html_e( 'Edit more details' , 'uncode') ; ?></a>
					<# if ( ! data.uploading && data.can.remove ) { #> |
							<?php if ( MEDIA_TRASH ): ?>
							<# if ( 'trash' === data.status ) { #>
								<a class="untrash-attachment" href="#"><?php esc_html_e( 'Untrash' , 'uncode') ; ?></a>
							<# } else { #>
								<a class="trash-attachment" href="#"><?php esc_html_e( 'Trash' , 'uncode') ; ?></a>
							<# } #>
							<?php else: ?>
								<a class="delete-attachment" href="#"><?php esc_html_e( 'Delete Permanently' , 'uncode') ; ?></a>
							<?php endif; ?>
						<# } #>
				</div>

			</div>
		</script>

		<script type="text/html" id="tmpl-attachment">
			<div class="attachment-preview js--select-attachment type-{{ data.type }} subtype-{{ data.subtype }} {{ data.orientation }}">
				<#  if ( data.uploading ) { #>
					<div class="media-progress-bar"><div></div></div>
				<# } else if ( 'image' === data.type ) { #>
					<div class="thumbnail" rel="{{ data.id }}">
						<div class="centered">
							<img src="{{ data.size.url }}" draggable="false" />
						</div>
						<# if ( 'image/url' === data.mime ) { #>
						<div class="filename">
							<div><?php echo esc_html__('External Image','uncode'); ?></div>
						</div>
						<# } #>
					</div>
				<# } else if ( 'oembed' === data.type ) { #>
					<div class="thumbnail" rel="{{ data.id }}">
						<# if ( 'oembed/html' === data.mime || 'oembed/iframe' === data.mime ) { #>
						<i class="fa fa-html5 fa-4x" style="display: block; margin-top: 30%;" /></i>
						<div class="filename">
							<div style="text-transform: capitalize;">{{ data.title }}</div>
						</div>
						<# } else if ( 'oembed/svg' === data.mime ) { #>
						<div class="oembed-svg">{{{ data.description }}}</div>
						<div class="filename">
							<div style="text-transform: capitalize;">{{ data.title }}</div>
						</div>
						<# } else if ( 'oembed/gallery' === data.mime ) { #>
							<# if ( /(jpg|jpeg|gif|png)$/i.test(data.url) ) { #>
								<div style="background-image: url({{ data.url }}); background-repeat: no-repeat; background-position: center; background-size: cover;width: 100%; height: 100%;">
									<i class="dashicons dashicons-images-alt2" style="color:#fff;font-size:35px;display: block;text-align:center;width:100%;top:50%;-webkit-transform:translateY(-100%);transform:translateY(-100%);position:relative;text-shadow:0 1px 1px rgba(0,0,0,.25);"></i>
								</div>
							<# } else { #>
								<i class="dashicons dashicons-images-alt2" style="color:#444;font-size:35px;display: block;text-align:center;width:100%;top:50%;-webkit-transform:translateY(-100%);transform:translateY(-100%);position:relative;"></i>
							<# } #>
						<# } else { #>
						<div class="centered">
							<div class="oembed">
								<span class="vc_ui-wp-spinner spinner"></span>
							</div>
							<div class="oembed_code" style="display: none;">{{ data.url }}</div>
						</div>
						<div class="filename">
							<div style="text-transform: capitalize;">{{ data.mime }}</div>
						</div>
						<# } #>
					</div>
				<# } else { #>
					<div class="thumbnail" rel="{{ data.id }}">
						<div class="centered">
							<img src="{{ data.icon }}" class="icon" draggable="false" />
						</div>
						<div class="filename">
							<div>{{ data.filename }}</div>
						</div>
					</div>
				<# } #>

				<# if ( data.buttons.close ) { #>
					<button type="button" class="button-link attachment-close media-modal-icon"><span class="screen-reader-text"><?php _e( 'Remove' ); ?></span></button>
				<# } #>

				<# if ( data.buttons.check ) { #>
					<button type="button" class="check" tabindex="-1"><span class="media-modal-icon"></span><span class="screen-reader-text"><?php _e( 'Deselect' ); ?></span></button>
				<# } #>
			</div>
			<#
			var maybeReadOnly = data.can.save || data.allowLocalEdits ? '' : 'readonly';
			if ( data.describe ) { #>
				<# if ( 'image' === data.type ) { #>
					<input type="text" value="{{ data.caption }}" class="describe" data-setting="caption"
						placeholder="<?php esc_attr_e('Caption this image&hellip;','uncode'); ?>" {{ maybeReadOnly }} />
				<# } else { #>
					<input type="text" value="{{ data.title }}" class="describe" data-setting="title"
						<# if ( 'video' === data.type ) { #>
							placeholder="<?php esc_attr_e('Describe this video&hellip;','uncode'); ?>"
						<# } else if ( 'audio' === data.type ) { #>
							placeholder="<?php esc_attr_e('Describe this audio file&hellip;','uncode'); ?>"
						<# } else { #>
							placeholder="<?php esc_attr_e('Describe this media file&hellip;','uncode'); ?>"
						<# } #> {{ maybeReadOnly }} />
				<# } #>
			<# } #>
		</script>

		<script type="text/html" id="tmpl-uploader-uncode-media">
			<div class="edit-attachment-frame">
				<div class="attachment-media-view landscape">
					<div class="thumbnail thumbnail-image oembed_container">
						<div class="oembed"></div>
						<div class="oembed_code"></div>
					</div>
				</div>
			</div>
			<div class="media-sidebar">
				<h3><span class="vc_ui-wp-spinner spinner"></span>Media Details</h3>
				<label class="setting" data-setting="url">
					<span class="name">Title</span>
					<input type="text" name="mle-title" value="" id="title" autocomplete="off">
				</label>
				<label class="setting" data-setting="oembed">
					<span class="name">oEmbed code</span>
					<textarea id="mle-code" name="mle-code"></textarea>
				</label>
				<label class="setting" data-setting="caption">
					<span class="name">Caption</span>
					<input type="text" name="mle-caption" value="" autocomplete="off">
				</label>
				<label class="setting" data-setting="description">
					<span class="name">Description</span>
					<textarea name="mle-description" ></textarea>
				</label>
				<input type="hidden" name="mle-width" id="mle-width" value="">
				<input type="hidden" name="mle-height" id="mle-height" value="">
				<input type="hidden" name="mle-mime" id="mle-mime" value="">
				<input type="hidden" name="nonce" value="<?php echo wp_create_nonce("uncode-recordmedia-nonce"); ?>">
			</div>
		</script>

	<?php }

	add_action( 'admin_footer', 'uncode_add_tmpl_attachment' );

	add_filter('wpb_widget_title', 'uncode_override_widget_title', 10, 2);

	function uncode_override_widget_title($output = '', $params = array('')) {
		$extraclass = (isset($params['extraclass'])) ? " ".$params['extraclass'] : "";
		return '<h3 class="widget-title'.$extraclass.'">'.$params['title'].'</h3>';
	}

add_filter( 'vc_single_param_edit_holder_output', 'uncode_vc_render_field', 1, 5 );
if ( !function_exists('uncode_vc_render_field') ):
function uncode_vc_render_field( $output, $param, $value, $settings, $atts ) {
	global $vc_html_editor_already_is_use/*, $uncode_vc_html_editor_already_is_use*/;
	//if ( !$uncode_vc_html_editor_already_is_use )
		$vc_html_editor_already_is_use = false;
	//$uncode_vc_html_editor_already_is_use = true;
	$base = isset( $settings[ 'base' ] ) ? $settings[ 'base' ] : null;
	$output = '<div class="' . implode( ' ', $param['vc_single_param_edit_holder_class'] ) . '" data-vc-ui-element="panel-shortcode-param" data-vc-shortcode-param-name="' . esc_attr( $param['param_name'] ) . '" data-param_type="' . esc_attr( $param['type'] ) . '" data-param_settings="' . esc_attr( json_encode( $param ) ) . '">';
	$output_in_label = ( isset( $param['description'] ) ) ? '<span class="vc_description vc_clearfix">' . $param['description'] . '</span>' : '';
	$output_label_toggle = ( isset( $param['description'] ) ) ? '<span class="toggle-description"></span>' : '';
	$output .= ( isset( $param['heading'] ) ) ? '<div class="wpb_element_label"><span class="wpb_element_label_inner">' . $param['heading'] . $output_label_toggle . '</span>' . $output_in_label . '</div>' : '';
	$output .= '<div class="edit_form_line">';
	$value = apply_filters( 'vc_form_fields_render_field_' . $base . '_' . $param['param_name'] . '_param_value', $value, $param, $settings, $atts );
	$param = apply_filters( 'vc_form_fields_render_field_' . $base . '_' . $param['param_name'] . '_param', $param, $value, $settings, $atts );
	$output = apply_filters( 'vc_edit_form_fields_render_field_' . $param['type'] . '_before', $output );
	$output .= vc_do_shortcode_param_settings_field( $param['type'], $param, $value, $base );
	$output_after = '</div></div>';
	$output .= apply_filters( 'vc_edit_form_fields_render_field_' . $param['type'] . '_after', $output_after );

	return $output;
}
endif;//uncode_vc_render_field

// Be sure that Uncode is active first
if ( defined( 'UNCODE_SLIM' ) ) {

	if ( ! function_exists('uncode_vc_loop_include_templates') ) :
		function uncode_vc_loop_include_templates() {
			require_once (get_template_directory() . '/vc_templates/params/loop/templates.html');
		}
	endif; //uncode_vc_loop_include_templates

	remove_action( 'admin_footer', 'vc_loop_include_templates' );
	add_action( 'admin_footer', 'uncode_vc_loop_include_templates' );
}
