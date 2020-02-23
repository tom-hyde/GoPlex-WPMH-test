<?php

$output = $message_color = $el_class = $css_animation = $animation_delay = $animation_speed = '';
extract(shortcode_atts(array(
	'notice_color' => '',
	'el_class' => '',
	'css_animation' => '',
	'animation_delay' => '',
	'animation_speed' => '',
), $atts));
$el_class = $this->getExtraClass($el_class);

$class = "";
$div_data = array();

$notice_color = ( $notice_color !== '') ? ' style-'.$notice_color . '-bg' : '';

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'uncode_consent_notice' . $class . $el_class, $this->settings['base'], $atts );

if ($css_animation !== '') {
	$css_class .= 'animate_when_almost_visible ' . $css_animation;
	if ($animation_delay !== '') {
		$div_data['data-delay'] = $animation_delay;
	}
	if ($animation_speed !== '') {
		$div_data['data-speed'] = $animation_speed;
	}
}

$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $div_data, array_keys($div_data));
$def = get_option( 'uncode_privacy_fallback', esc_html__('This content is blocked. Please review your [uncode_privacy_box]Privacy Settings[/uncode_privacy_box].', 'uncode') );

?>
<div class="<?php echo esc_attr($css_class); ?>" <?php echo implode(' ', $div_data_attributes); ?>>
	<div class="messagebox_text<?php echo esc_attr($notice_color); ?>">
		<i class="fa fa-exclamation-circle"></i>
		<?php echo wp_kses_post( do_shortcode ( $def ) ); ?>
	</div>
</div>
