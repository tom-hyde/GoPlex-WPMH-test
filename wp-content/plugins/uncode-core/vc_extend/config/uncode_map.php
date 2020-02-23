<?php

global $uncode_colors, $uncode_colors_w_transp, $uncode_post_types;

if (!isset($uncode_post_types) && function_exists('uncode_get_post_types')) $uncode_post_types = uncode_get_post_types();
if (!function_exists('uncode_get_post_types')) $uncode_post_types = array();

$uncode_index_params_second = array();
foreach ($uncode_post_types as $key => $value) {
	if ($value === 'product') continue;
	$uncode_post_type_list = array(
		'type' => 'sorted_list',
		'heading' => ucfirst($value) . ' ' . esc_html__('element', 'uncode') ,
		'param_name' => $value . '_items',
		'description' => esc_html__('Enable or disable elements and place them in desired order.', 'uncode') ,
		'value' => 'title,type,media,text,category',
		"group" => esc_html__("Module", 'uncode') ,
		'options' => array(
			array(
				'media',
				esc_html__('Media', 'uncode') ,
				array(
					array(
						'featured',
						esc_html__('Featured image', 'uncode')
					) ,
					array(
						'media',
						esc_html__('Featured media', 'uncode')
					) ,
					array(
						'custom',
						esc_html__('Custom', 'uncode')
					)
				) ,
				array(
					array(
						'onpost',
						esc_html__('Link to post', 'uncode')
					) ,
					array(
						'lightbox',
						esc_html__('Lightbox', 'uncode')
					) ,
					array(
						'nolink',
						esc_html__('No link', 'uncode')
					)
				) ,
				array(
					array(
						'original',
						esc_html__('Original', 'uncode')
					) ,
					array(
						'poster',
						esc_html__('Poster', 'uncode')
					)
				)
			) ,
			array(
				'title',
				esc_html__('Title', 'uncode') ,
			) ,
			array(
				'type',
				esc_html__('Post type', 'uncode') ,
			) ,
			array(
				'author',
				esc_html__('Author', 'uncode') ,
				array(
					array(
						'sm_size',
						esc_html__('Small size', 'uncode'),
					) ,
					array(
						'md_size',
						esc_html__('Medium size', 'uncode'),
					),
					array(
						'lg_size',
						esc_html__('Large size', 'uncode'),
					),
					array(
						'xl_size',
						esc_html__('Extra large size', 'uncode')
					)
				),
				array(
					array(
						'hide_qualification',
						esc_html__('Hide qualification', 'uncode'),
					) ,
					array(
						'display_qualification',
						esc_html__('Display qualification', 'uncode'),
					),
				),
			),
			array(
				'date',
				esc_html__('Date', 'uncode') ,
			) ,
			array(
				'category',
				esc_html__('Category', 'uncode') ,
				array(
					array(
						'nobg',
						esc_html__('No color', 'uncode'),
					) ,
					array(
						'yesbg',
						esc_html__('Colored text', 'uncode'),
					),
					array(
						'bordered',
						esc_html__('Bordered', 'uncode'),
					),
					array(
						'colorbg',
						esc_html__('Colored background', 'uncode')
					),
				),
				array(
					array(
						'relative',
						esc_html__('Relative position', 'uncode'),
					) ,
					array(
						'topleft',
						esc_html__('Over the image, on top left', 'uncode'),
					),
					array(
						'topright',
						esc_html__('Over the image, on top right', 'uncode'),
					),
					array(
						'bottomleft',
						esc_html__('Over the image, on bottom left', 'uncode'),
					),
					array(
						'bottomright',
						esc_html__('Over the image, on bottom right', 'uncode'),
					),
				),
				array(
					array(
						'display-icon',
						esc_html__('Display icon (when available)', 'uncode')
					) ,
					array(
						'hide-icon',
						esc_html__('Hide icon', 'uncode')
					) ,
				)
			) ,
			array(
				'extra',
				esc_html__('Extra', 'uncode') ,
			) ,
			array(
				'meta',
				esc_html__('Default meta', 'uncode') ,
				array(
					array(
						'display-icon',
						esc_html__('Display icon', 'uncode')
					) ,
					array(
						'hide-icon',
						esc_html__('Hide icon', 'uncode')
					) ,
				)
			) ,
			array(
				'text',
				esc_html__('Text', 'uncode') ,
				array(
					array(
						'excerpt',
						esc_html__('Excerpt', 'uncode')
					) ,
					array(
						'full',
						esc_html__('Full content', 'uncode')
					) ,
				)
			) ,
			array(
				'link',
				esc_html__('Read more link', 'uncode'),
				array(
					array(
						'default',
						esc_html__('Default', 'uncode')
					) ,
					array(
						'round',
						esc_html__('Round', 'uncode')
					) ,
					array(
						'circle',
						esc_html__('Circle', 'uncode')
					) ,
					array(
						'link',
						esc_html__('Standard link', 'uncode')
					)
				),
				array(
					array(
						'default_size',
						esc_html__('Default size', 'uncode')
					) ,
					array(
						'small_size',
						esc_html__('Small size', 'uncode')
					) ,
				),
				array(
					array(
						'outline',
						esc_html__('Outline style', 'uncode')
					) ,
					array(
						'outline_inv',
						esc_html__('Outline inverse style', 'uncode')
					) ,
					array(
						'flat',
						esc_html__('Flat style', 'uncode')
					) ,
				)
			) ,
			array(
				'icon',
				esc_html__('Icon', 'uncode') ,
				array(
					array(
						'sm',
						esc_html__('Small', 'uncode')
					) ,
					array(
						'md',
						esc_html__('Medium', 'uncode')
					) ,
					array(
						'lg',
						esc_html__('Large', 'uncode')
					),
					array(
						'xl',
						esc_html__('Extra Large', 'uncode')
					)
				) ,
			) ,
			array(
				'spacer',
				esc_html__('Spacer One', 'uncode') ,
				array(
					array(
						'half',
						esc_html__('0.5x', 'uncode')
					) ,
					array(
						'one',
						esc_html__('1x', 'uncode')
					) ,
					array(
						'two',
						esc_html__('2x', 'uncode')
					)
				)
			) ,
			array(
				'spacer_two',
				esc_html__('Spacer Two', 'uncode') ,
				array(
					array(
						'half',
						esc_html__('0.5x', 'uncode')
					) ,
					array(
						'one',
						esc_html__('1x', 'uncode')
					) ,
					array(
						'two',
						esc_html__('2x', 'uncode')
					)
				)
			) ,
			array(
				'sep-one',
				esc_html__('Separator One', 'uncode') ,
				array(
					array(
						'full',
						esc_html__('Full width', 'uncode')
					) ,
					array(
						'reduced',
						esc_html__('Reduced width', 'uncode')
					),
					array(
						'extra',
						esc_html__('Extra full width', 'uncode')
					)
				)
			) ,
			array(
				'sep-two',
				esc_html__('Separator Two', 'uncode') ,
				array(
					array(
						'full',
						esc_html__('Full width', 'uncode')
					) ,
					array(
						'reduced',
						esc_html__('Reduced width', 'uncode')
					),
					array(
						'extra',
						esc_html__('Extra full width', 'uncode')
					)
				)
			) ,
		)
	);
	$get_custom_fields = (function_exists('ot_get_option')) ? ot_get_option('_uncode_'.$value.'_custom_fields') : array();
	if (isset($get_custom_fields) && !empty($get_custom_fields))
	{
		foreach ($get_custom_fields as $field_key => $field)
		{
			$uncode_post_type_list['options'][] = array($field['_uncode_cf_unique_id'], $field['title']);
		}
	}
	$uncode_index_params_second[] = $uncode_post_type_list;
}

$add_css_animation = array(
	'type' => 'dropdown',
	'heading' => esc_html__('Animation', 'uncode') ,
	'param_name' => 'css_animation',
	'admin_label' => true,
	'value' => array(
		esc_html__('No', 'uncode') => '',
		esc_html__('Opacity', 'uncode') => 'alpha-anim',
		esc_html__('Zoom in', 'uncode') => 'zoom-in',
		esc_html__('Zoom out', 'uncode') => 'zoom-out',
		esc_html__('Top to bottom', 'uncode') => 'top-t-bottom',
		esc_html__('Bottom to top', 'uncode') => 'bottom-t-top',
		esc_html__('Left to right', 'uncode') => 'left-t-right',
		esc_html__('Right to left', 'uncode') => 'right-t-left',
	) ,
	'group' => esc_html__('Animation', 'uncode') ,
	'description' => esc_html__('Specify the entrance animation.', 'uncode')
);

$add_animation_delay = array(
	'type' => 'dropdown',
	'heading' => esc_html__('Animation delay', 'uncode') ,
	'param_name' => 'animation_delay',
	'admin_label' => true,
	'value' => array(
		esc_html__('None', 'uncode') => '',
		esc_html__('ms 100', 'uncode') => 100,
		esc_html__('ms 200', 'uncode') => 200,
		esc_html__('ms 300', 'uncode') => 300,
		esc_html__('ms 400', 'uncode') => 400,
		esc_html__('ms 500', 'uncode') => 500,
		esc_html__('ms 600', 'uncode') => 600,
		esc_html__('ms 700', 'uncode') => 700,
		esc_html__('ms 800', 'uncode') => 800,
		esc_html__('ms 900', 'uncode') => 900,
		esc_html__('ms 1000', 'uncode') => 1000,
		esc_html__('ms 1100', 'uncode') => 1100,
		esc_html__('ms 1200', 'uncode') => 1200,
		esc_html__('ms 1300', 'uncode') => 1300,
		esc_html__('ms 1400', 'uncode') => 1400,
		esc_html__('ms 1500', 'uncode') => 1500,
		esc_html__('ms 1600', 'uncode') => 1600,
		esc_html__('ms 1700', 'uncode') => 1700,
		esc_html__('ms 1800', 'uncode') => 1800,
		esc_html__('ms 1900', 'uncode') => 1900,
		esc_html__('ms 2000', 'uncode') => 2000,
	) ,
	'description' => esc_html__('Specify the entrance animation delay in milliseconds.', 'uncode') ,
	'group' => esc_html__('Animation', 'uncode') ,
	'dependency' => array(
		'element' => 'css_animation',
		'not_empty' => true
	)
);

$add_animation_speed = array(
	'type' => 'dropdown',
	'heading' => esc_html__('Animation speed', 'uncode') ,
	'param_name' => 'animation_speed',
	'admin_label' => true,
	'value' => array(
		esc_html__('Default (400)', 'uncode') => '',
		esc_html__('ms 100', 'uncode') => 100,
		esc_html__('ms 200', 'uncode') => 200,
		esc_html__('ms 300', 'uncode') => 300,
		esc_html__('ms 400', 'uncode') => 400,
		esc_html__('ms 500', 'uncode') => 500,
		esc_html__('ms 600', 'uncode') => 600,
		esc_html__('ms 700', 'uncode') => 700,
		esc_html__('ms 800', 'uncode') => 800,
		esc_html__('ms 900', 'uncode') => 900,
		esc_html__('ms 1000', 'uncode') => 1000,
	) ,
	'description' => esc_html__('Specify the entrance animation speed in milliseconds.', 'uncode') ,
	'group' => esc_html__('Animation', 'uncode') ,
	'dependency' => array(
		'element' => 'css_animation',
		'not_empty' => true
	)
);

$units = array(
	'1/12' => '1',
	'2/12' => '2',
	'3/12' => '3',
	'4/12' => '4',
	'5/12' => '5',
	'6/12' => '6',
	'7/12' => '7',
	'8/12' => '8',
	'9/12' => '9',
	'10/12' => '10',
	'11/12' => '11',
	'12/12' => '12',
);

$heading_size = array(
	esc_html__('Default CSS', 'uncode') => '',
	esc_html__('h1', 'uncode') => 'h1',
	esc_html__('h2', 'uncode') => 'h2',
	esc_html__('h3', 'uncode') => 'h3',
	esc_html__('h4', 'uncode') => 'h4',
	esc_html__('h5', 'uncode') => 'h5',
	esc_html__('h6', 'uncode') => 'h6',
);

$font_sizes = (function_exists('ot_get_option')) ? ot_get_option('_uncode_heading_font_sizes') : array();
if (!empty($font_sizes)) {
	foreach ($font_sizes as $key => $value) {
		$heading_size[$value['title']] = $value['_uncode_heading_font_size_unique_id'];
	}
}

$heading_size[esc_html__('BigText', 'uncode')] = 'bigtext';

$fonts = (function_exists('ot_get_option')) ? ot_get_option('_uncode_font_groups') : array();
$heading_font = array(
	esc_html__('Default CSS', 'uncode') => '',
);

if (isset($fonts) && is_array($fonts)) {
	foreach ($fonts as $key => $value) {
		$heading_font[$value['title']] = $value['_uncode_font_group_unique_id'];
	}
}

$heading_semantic = array(
	esc_html__('h1', 'uncode') => 'h1',
	esc_html__('h2', 'uncode') => 'h2',
	esc_html__('h3', 'uncode') => 'h3',
	esc_html__('h4', 'uncode') => 'h4',
	esc_html__('h5', 'uncode') => 'h5',
	esc_html__('h6', 'uncode') => 'h6',
	esc_html__('p', 'uncode') => 'p',
	esc_html__('div', 'uncode') => 'div'
);

$heading_weight = array(
	esc_html__('Default CSS', 'uncode') => '',
	esc_html__('100', 'uncode') => 100,
	esc_html__('200', 'uncode') => 200,
	esc_html__('300', 'uncode') => 300,
	esc_html__('400', 'uncode') => 400,
	esc_html__('500', 'uncode') => 500,
	esc_html__('600', 'uncode') => 600,
	esc_html__('700', 'uncode') => 700,
	esc_html__('800', 'uncode') => 800,
	esc_html__('900', 'uncode') => 900,
);

$font_heights = (function_exists('ot_get_option')) ? ot_get_option('_uncode_heading_font_heights') : array();
$heading_height = array(
	esc_html__('Default CSS', 'uncode') => '',
);
if (!empty($font_heights)) {
	foreach ($font_heights as $key => $value) {
		$heading_height[$value['title']] = $value['_uncode_heading_font_height_unique_id'];
	}
}

$font_spacings = (function_exists('ot_get_option')) ? ot_get_option('_uncode_heading_font_spacings') : array();
$heading_space = array(
	esc_html__('Default CSS', 'uncode') => '',
);
if (!empty($font_spacings)) {
	foreach ($font_spacings as $key => $value) {
		$heading_space[$value['title']] = $value['_uncode_heading_font_spacing_unique_id'];
	}
}

global $uncode_index_map;

$uncode_post_list = array(
	'type' => 'sorted_list',
	'heading' => esc_html__('Posts', 'uncode') . ' ' . esc_html__('element', 'uncode') ,
	'param_name' => 'post_items',
	'description' => esc_html__('Enable or disable elements and place them in desired order.', 'uncode') ,
	'value' => 'media|featured|onpost|original,title,category|nobg,date,text|excerpt,link|default,author,sep-one|full,extra',
	"group" => esc_html__("Module", 'uncode') ,
	'options' => array(
		array(
			'media',
			esc_html__('Media', 'uncode') ,
			array(
				array(
					'featured',
					esc_html__('Featured image', 'uncode')
				) ,
				array(
					'media',
					esc_html__('Featured media', 'uncode')
				) ,
				array(
					'custom',
					esc_html__('Custom', 'uncode')
				)
			) ,
			array(
				array(
					'onpost',
					esc_html__('Link to post', 'uncode')
				) ,
				array(
					'lightbox',
					esc_html__('Lightbox', 'uncode')
				) ,
				array(
					'nolink',
					esc_html__('No link', 'uncode')
				)
			) ,
			array(
				array(
					'original',
					esc_html__('Original', 'uncode')
				) ,
				array(
					'poster',
					esc_html__('Poster', 'uncode')
				)
			)
		) ,
		array(
			'title',
			esc_html__('Title', 'uncode') ,
		) ,
		array(
			'type',
			esc_html__('Post type', 'uncode') ,
		) ,
		array(
			'author',
			esc_html__('Author', 'uncode') ,
			array(
				array(
					'sm_size',
					esc_html__('Small size', 'uncode'),
				) ,
				array(
					'md_size',
					esc_html__('Medium size', 'uncode'),
				),
				array(
					'lg_size',
					esc_html__('Large size', 'uncode'),
				),
				array(
					'xl_size',
					esc_html__('Extra large size', 'uncode')
				)
			),
			array(
				array(
					'hide_qualification',
					esc_html__('Hide qualification', 'uncode'),
				) ,
				array(
					'display_qualification',
					esc_html__('Display qualification', 'uncode'),
				),
			),
		),
		array(
			'date',
			esc_html__('Date', 'uncode') ,
		) ,
		array(
			'category',
			esc_html__('Category', 'uncode') ,
			array(
				array(
					'nobg',
					esc_html__('No color', 'uncode'),
				) ,
				array(
					'yesbg',
					esc_html__('Colored text', 'uncode'),
				),
				array(
					'bordered',
					esc_html__('Bordered', 'uncode'),
				),
				array(
					'colorbg',
					esc_html__('Colored background', 'uncode')
				),
			),
			array(
				array(
					'relative',
					esc_html__('Relative position', 'uncode'),
				) ,
				array(
					'topleft',
					esc_html__('Over the image, on top left', 'uncode'),
				),
				array(
					'topright',
					esc_html__('Over the image, on top right', 'uncode'),
				),
				array(
					'bottomleft',
					esc_html__('Over the image, on bottom left', 'uncode'),
				),
				array(
					'bottomright',
					esc_html__('Over the image, on bottom right', 'uncode'),
				),
			),
			array(
				array(
					'display-icon',
					esc_html__('Display icon (when available)', 'uncode')
				) ,
				array(
					'hide-icon',
					esc_html__('Hide icon', 'uncode')
				) ,
			)
		) ,
		array(
			'extra',
			esc_html__('Extra', 'uncode') ,
		) ,
		array(
			'meta',
			esc_html__('Default meta', 'uncode') ,
			array(
				array(
					'display-icon',
					esc_html__('Display icon', 'uncode')
				) ,
				array(
					'hide-icon',
					esc_html__('Hide icon', 'uncode')
				) ,
			)
		) ,
		array(
			'text',
			esc_html__('Text', 'uncode') ,
			array(
				array(
					'excerpt',
					esc_html__('Excerpt', 'uncode')
				) ,
				array(
					'full',
					esc_html__('Full content', 'uncode')
				) ,
			)
		) ,
		array(
			'link',
			esc_html__('Read more link', 'uncode'),
			array(
				array(
					'default',
					esc_html__('Default', 'uncode')
				) ,
				array(
					'round',
					esc_html__('Round', 'uncode')
				) ,
				array(
					'circle',
					esc_html__('Circle', 'uncode')
				) ,
				array(
					'link',
					esc_html__('Standard link', 'uncode')
				)
			),
			array(
				array(
					'default_size',
					esc_html__('Default size', 'uncode')
				) ,
				array(
					'small_size',
					esc_html__('Small size', 'uncode')
				) ,
			),
			array(
				array(
					'outline',
					esc_html__('Outline style', 'uncode')
				) ,
				array(
					'outline_inv',
					esc_html__('Outline inverse style', 'uncode')
				) ,
				array(
					'flat',
					esc_html__('Flat style', 'uncode')
				) ,
			)
		) ,
		array(
			'icon',
			esc_html__('Icon', 'uncode') ,
			array(
				array(
					'sm',
					esc_html__('Small', 'uncode')
				) ,
				array(
					'md',
					esc_html__('Medium', 'uncode')
				) ,
				array(
					'lg',
					esc_html__('Large', 'uncode')
				),
				array(
					'xl',
					esc_html__('Extra Large', 'uncode')
				)
			) ,
		) ,
		array(
			'spacer',
			esc_html__('Spacer One', 'uncode') ,
			array(
				array(
					'half',
					esc_html__('0.5x', 'uncode')
				) ,
				array(
					'one',
					esc_html__('1x', 'uncode')
				) ,
				array(
					'two',
					esc_html__('2x', 'uncode')
				)
			)
		) ,
		array(
			'spacer_two',
			esc_html__('Spacer Two', 'uncode') ,
			array(
				array(
					'half',
					esc_html__('0.5x', 'uncode')
				) ,
				array(
					'one',
					esc_html__('1x', 'uncode')
				) ,
				array(
					'two',
					esc_html__('2x', 'uncode')
				)
			)
		) ,
		array(
			'sep-one',
			esc_html__('Separator One', 'uncode') ,
			array(
				array(
					'full',
					esc_html__('Full width', 'uncode')
				) ,
				array(
					'reduced',
					esc_html__('Reduced width', 'uncode')
				),
				array(
					'extra',
					esc_html__('Extra full width', 'uncode')
				)
			)
		) ,
		array(
			'sep-two',
			esc_html__('Separator Two', 'uncode') ,
			array(
				array(
					'full',
					esc_html__('Full width', 'uncode')
				) ,
				array(
					'reduced',
					esc_html__('Reduced width', 'uncode')
				),
				array(
					'extra',
					esc_html__('Extra full width', 'uncode')
				)
			)
		) ,
	) ,
);

$uncode_page_list = array(
	'type' => 'sorted_list',
	'heading' => esc_html__('Pages', 'uncode') . ' ' . esc_html__('element', 'uncode') ,
	'param_name' => 'page_items',
	'description' => esc_html__('Enable or disable elements and place them in desired order.', 'uncode') ,
	'value' => 'title,type,media,text,category',
	"group" => esc_html__("Module", 'uncode') ,
	'options' => array(
		array(
			'media',
			esc_html__('Media', 'uncode') ,
			array(
				array(
					'featured',
					esc_html__('Featured image', 'uncode')
				) ,
				array(
					'media',
					esc_html__('Featured media', 'uncode')
				) ,
				array(
					'custom',
					esc_html__('Custom', 'uncode')
				)
			) ,
			array(
				array(
					'onpost',
					esc_html__('Link to post', 'uncode')
				) ,
				array(
					'lightbox',
					esc_html__('Lightbox', 'uncode')
				) ,
				array(
					'nolink',
					esc_html__('No link', 'uncode')
				)
			) ,
			array(
				array(
					'original',
					esc_html__('Original', 'uncode')
				) ,
				array(
					'poster',
					esc_html__('Poster', 'uncode')
				)
			)
		) ,
		array(
			'title',
			esc_html__('Title', 'uncode') ,
		) ,
		array(
			'type',
			esc_html__('Post type', 'uncode') ,
		) ,
		array(
			'category',
			esc_html__('Category', 'uncode') ,
			array(
				array(
					'nobg',
					esc_html__('No color', 'uncode'),
				) ,
				array(
					'yesbg',
					esc_html__('Colored text', 'uncode'),
				),
				array(
					'bordered',
					esc_html__('Bordered', 'uncode'),
				),
				array(
					'colorbg',
					esc_html__('Colored background', 'uncode')
				),
			),
			array(
				array(
					'relative',
					esc_html__('Relative position', 'uncode'),
				) ,
				array(
					'topleft',
					esc_html__('Over the image, on top left', 'uncode'),
				),
				array(
					'topright',
					esc_html__('Over the image, on top right', 'uncode'),
				),
				array(
					'bottomleft',
					esc_html__('Over the image, on bottom left', 'uncode'),
				),
				array(
					'bottomright',
					esc_html__('Over the image, on bottom right', 'uncode'),
				),
			),
			array(
				array(
					'display-icon',
					esc_html__('Display icon (when available)', 'uncode')
				) ,
				array(
					'hide-icon',
					esc_html__('Hide icon', 'uncode')
				) ,
			)
		) ,
		array(
			'text',
			esc_html__('Text', 'uncode') ,
			array(
				array(
					'excerpt',
					esc_html__('Excerpt', 'uncode')
				) ,
				array(
					'full',
					esc_html__('Full content', 'uncode')
				) ,
			)
		) ,
		array(
			'link',
			esc_html__('Read more link', 'uncode'),
			array(
				array(
					'default',
					esc_html__('Default', 'uncode')
				) ,
				array(
					'round',
					esc_html__('Round', 'uncode')
				) ,
				array(
					'circle',
					esc_html__('Circle', 'uncode')
				) ,
				array(
					'link',
					esc_html__('Standard link', 'uncode')
				)
			),
			array(
				array(
					'default_size',
					esc_html__('Default size', 'uncode')
				) ,
				array(
					'small_size',
					esc_html__('Small size', 'uncode')
				) ,
			),
			array(
				array(
					'outline',
					esc_html__('Outline style', 'uncode')
				) ,
				array(
					'outline_inv',
					esc_html__('Outline inverse style', 'uncode')
				) ,
				array(
					'flat',
					esc_html__('Flat style', 'uncode')
				) ,
			)
		) ,
		array(
			'icon',
			esc_html__('Icon', 'uncode') ,
			array(
				array(
					'sm',
					esc_html__('Small', 'uncode')
				) ,
				array(
					'md',
					esc_html__('Medium', 'uncode')
				) ,
				array(
					'lg',
					esc_html__('Large', 'uncode')
				),
				array(
					'xl',
					esc_html__('Extra Large', 'uncode')
				)
			) ,
		) ,
		array(
			'spacer',
			esc_html__('Spacer One', 'uncode') ,
			array(
				array(
					'half',
					esc_html__('0.5x', 'uncode')
				) ,
				array(
					'one',
					esc_html__('1x', 'uncode')
				) ,
				array(
					'two',
					esc_html__('2x', 'uncode')
				)
			)
		) ,
		array(
			'spacer_two',
			esc_html__('Spacer Two', 'uncode') ,
			array(
				array(
					'half',
					esc_html__('0.5x', 'uncode')
				) ,
				array(
					'one',
					esc_html__('1x', 'uncode')
				) ,
				array(
					'two',
					esc_html__('2x', 'uncode')
				)
			)
		) ,
		array(
			'sep-one',
			esc_html__('Separator One', 'uncode') ,
			array(
				array(
					'full',
					esc_html__('Full width', 'uncode')
				) ,
				array(
					'reduced',
					esc_html__('Reduced width', 'uncode')
				),
				array(
					'extra',
					esc_html__('Extra full width', 'uncode')
				)
			)
		) ,
		array(
			'sep-two',
			esc_html__('Separator Two', 'uncode') ,
			array(
				array(
					'full',
					esc_html__('Full width', 'uncode')
				) ,
				array(
					'reduced',
					esc_html__('Reduced width', 'uncode')
				),
				array(
					'extra',
					esc_html__('Extra full width', 'uncode')
				)
			)
		) ,
	)
);

$uncode_product_list = array(
	'type' => 'sorted_list',
	'heading' => esc_html__('Products', 'uncode') . ' ' . esc_html__('element', 'uncode') ,
	'param_name' => 'product_items',
	'description' => esc_html__('Enable or disable elements and place them in desired order.', 'uncode') ,
	'value' => 'title,type,media,text,category,price',
	"group" => esc_html__("Module", 'uncode') ,
	'options' => array(
		array(
			'media',
			esc_html__('Media', 'uncode') ,
			array(
				array(
					'featured',
					esc_html__('Featured image', 'uncode')
				) ,
				array(
					'media',
					esc_html__('Featured media', 'uncode')
				) ,
				array(
					'custom',
					esc_html__('Custom', 'uncode')
				) ,
			) ,
			array(
				array(
					'onpost',
					esc_html__('Link to post', 'uncode')
				) ,
				array(
					'lightbox',
					esc_html__('Lightbox', 'uncode')
				) ,
				array(
					'nolink',
					esc_html__('No link', 'uncode')
				)
			) ,
			array(
				array(
					'original',
					esc_html__('Original', 'uncode')
				) ,
				array(
					'poster',
					esc_html__('Poster', 'uncode')
				)
			) ,
			array(
				array(
					'hide-sale',
					esc_html__('Hide badge', 'uncode')
				) ,
				array(
					'show-sale',
					esc_html__('Show badge', 'uncode')
				)
			) ,
			array(
				array(
					'',
					esc_html__('Inherit', 'uncode')
				) ,
				array(
					'default-atc',
					esc_html__('Default Add To Cart button', 'uncode')
				) ,
				array(
					'enhanced-atc',
					esc_html__('Alternative Add To Cart button', 'uncode')
				)
			)
		) ,
		array(
			'title',
			esc_html__('Title', 'uncode') ,
		) ,
		array(
			'type',
			esc_html__('Post type', 'uncode') ,
		) ,
		array(
			'category',
			esc_html__('Category', 'uncode') ,
			array(
				array(
					'nobg',
					esc_html__('No color', 'uncode'),
				) ,
				array(
					'yesbg',
					esc_html__('Colored text', 'uncode'),
				),
				array(
					'bordered',
					esc_html__('Bordered', 'uncode'),
				),
				array(
					'colorbg',
					esc_html__('Colored background', 'uncode')
				),
			),
			array(
				array(
					'relative',
					esc_html__('Relative position', 'uncode'),
				) ,
				array(
					'topleft',
					esc_html__('Over the image, on top left', 'uncode'),
				),
				array(
					'topright',
					esc_html__('Over the image, on top right', 'uncode'),
				),
				array(
					'bottomleft',
					esc_html__('Over the image, on bottom left', 'uncode'),
				),
				array(
					'bottomright',
					esc_html__('Over the image, on bottom right', 'uncode'),
				),
			),
			array(
				array(
					'display-icon',
					esc_html__('Display icon (when available)', 'uncode')
				) ,
				array(
					'hide-icon',
					esc_html__('Hide icon', 'uncode')
				) ,
			)
		) ,
		array(
			'text',
			esc_html__('Text', 'uncode') ,
			array(
				array(
					'excerpt',
					esc_html__('Excerpt', 'uncode')
				) ,
				array(
					'full',
					esc_html__('Full content', 'uncode')
				) ,
			)
		) ,
		array(
			'price',
			esc_html__('Price', 'uncode') ,
			array(
				array(
					'default',
					esc_html__('Default layout', 'uncode'),
				) ,
				array(
					'inline',
					esc_html__('Inline price', 'uncode'),
				),
			),
		) ,
		array(
			'icon',
			esc_html__('Icon', 'uncode') ,
			array(
				array(
					'sm',
					esc_html__('Small', 'uncode')
				) ,
				array(
					'md',
					esc_html__('Medium', 'uncode')
				) ,
				array(
					'lg',
					esc_html__('Large', 'uncode')
				),
				array(
					'xl',
					esc_html__('Extra Large', 'uncode')
				)
			) ,
		) ,
		array(
			'spacer',
			esc_html__('Spacer One', 'uncode') ,
			array(
				array(
					'half',
					esc_html__('0.5x', 'uncode')
				) ,
				array(
					'one',
					esc_html__('1x', 'uncode')
				) ,
				array(
					'two',
					esc_html__('2x', 'uncode')
				)
			)
		) ,
		array(
			'spacer_two',
			esc_html__('Spacer Two', 'uncode') ,
			array(
				array(
					'half',
					esc_html__('0.5x', 'uncode')
				) ,
				array(
					'one',
					esc_html__('1x', 'uncode')
				) ,
				array(
					'two',
					esc_html__('2x', 'uncode')
				)
			)
		) ,
		array(
			'sep-one',
			esc_html__('Separator One', 'uncode') ,
			array(
				array(
					'full',
					esc_html__('Full width', 'uncode')
				) ,
				array(
					'reduced',
					esc_html__('Reduced width', 'uncode')
				),
				array(
					'extra',
					esc_html__('Extra full width', 'uncode')
				)
			)
		) ,
		array(
			'sep-two',
			esc_html__('Separator Two', 'uncode') ,
			array(
				array(
					'full',
					esc_html__('Full width', 'uncode')
				) ,
				array(
					'reduced',
					esc_html__('Reduced width', 'uncode')
				),
				array(
					'extra',
					esc_html__('Extra full width', 'uncode')
				)
			)
		) ,
	)
);

$get_post_custom_fields = (function_exists('ot_get_option')) ? ot_get_option('_uncode_post_custom_fields') : array();
if (isset($get_post_custom_fields) && !empty($get_post_custom_fields))
{
	foreach ($get_post_custom_fields as $field_key => $field)
	{
		$uncode_post_list['options'][] = array($field['_uncode_cf_unique_id'], $field['title']);
	}
}

$get_page_custom_fields = (function_exists('ot_get_option')) ? ot_get_option('_uncode_page_custom_fields') : array();
if (isset($get_page_custom_fields) && !empty($get_page_custom_fields))
{
	foreach ($get_page_custom_fields as $field_key => $field)
	{
		$uncode_page_list['options'][] = array($field['_uncode_cf_unique_id'], $field['title']);
	}
}

$get_product_custom_fields = (function_exists('ot_get_option')) ? ot_get_option('_uncode_product_custom_fields') : array();
if (isset($get_product_custom_fields) && !empty($get_product_custom_fields))
{
	foreach ($get_product_custom_fields as $field_key => $field)
	{
		$uncode_product_list['options'][] = array($field['_uncode_cf_unique_id'], $field['title']);
	}
}

$uncode_index_params_first = array(
	array(
		'type' => 'textfield',
		'heading' => esc_html__('Widget title', 'uncode') ,
		'param_name' => 'title',
		'admin_label' => true,
		'description' => esc_html__('Enter text which will be used as widget title. Leave blank if no title is needed.', 'uncode') ,
		'group' => esc_html__('General', 'uncode')
	) ,
	array(
		'type' => 'textfield',
		'heading' => esc_html__('Widget ID', 'uncode') ,
		'param_name' => 'el_id',
		'value' => (function_exists('uncode_big_rand')) ? uncode_big_rand() : rand(),
		'description' => esc_html__('This value has to be unique. Change it in case it\'s needed.', 'uncode') ,
		'group' => esc_html__('General', 'uncode')
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Module", 'uncode') ,
		"param_name" => "index_type",
		'admin_label' => true,
		"description" => esc_html__("Specify the layout mode: Isotope or Carousel.", 'uncode') ,
		"value" => array(
			esc_html__('Isotope', 'uncode') => 'isotope',
			esc_html__('Carousel', 'uncode') => 'carousel',
		) ,
		'group' => esc_html__('General', 'uncode')
	) ,
	array(
		'type' => 'dropdown',
		'heading' => esc_html__('Layout modes', 'uncode') ,
		'param_name' => 'isotope_mode',
		"description" => wp_kses(__("Specify the Isotope layout mode. <a href='http://isotope.metafizzy.co/layout-modes.html' target='_blank'>Check this for reference</a>", 'uncode'), array( 'a' => array( 'href' => array( ),'target' => array( ) ) ) ) ,
		"value" => array(
			esc_html__('Masonry', 'uncode') => 'masonry',
			esc_html__('Fit rows', 'uncode') => 'fitRows',
			esc_html__('Cells by row', 'uncode') => 'cellsByRow',
			esc_html__('Vertical', 'uncode') => 'vertical',
			esc_html__('Packery', 'uncode') => 'packery',
		) ,
		'group' => esc_html__('General', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'isotope',
		) ,
	) ,
	array(
		'type' => 'loop',
		'heading' => esc_html__('Index content', 'uncode') ,
		'param_name' => 'loop',
		'admin_label' => true,
		'settings' => array(
			'size' => array(
				'hidden' => false,
				'value' => 10
			) ,
			'order_by' => array(
				'value' => 'date'
			) ,
		) ,
		'value' => 'size:10|order_by:date|post_type:post',
		'description' => esc_html__('Create WordPress loop, to populate content from your site.', 'uncode') ,
		'group' => esc_html__('General', 'uncode')
	) ,
	array(
		'type' => 'textfield',
		'heading' => esc_html__('Post Offset', 'uncode') ,
		'param_name' => 'offset',
		'admin_label' => true,
		'description' => esc_html__('Enter the amount of posts that should be skipped in the beginning of the query. NB: please note that it\'s not possible to use it with the Filtering if combined also with the Pagination mode.', 'uncode') ,
		'group' => esc_html__('General', 'uncode')
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Automatic query", 'uncode') ,
		"param_name" => "auto_query",
		"description" => esc_html__("Activate this to pull automatic query when used as Content Block for categories.", 'uncode') ,
		'group' => esc_html__('General', 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Style", 'uncode') ,
		"param_name" => "style_preset",
		"description" => esc_html__("Select the visualization mode.", 'uncode') ,
		"value" => array(
			esc_html__('Masonry', 'uncode') => 'masonry',
			esc_html__('Metro', 'uncode') => 'metro',
		) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => array(
				'isotope',
			) ,
		) ,
	) ,
	array(
		"type" => "dropdown",
		"heading" => esc_html__("Index background color", 'uncode') ,
		"param_name" => "index_back_color",
		"description" => esc_html__("Specify a background color for the module.", 'uncode') ,
		"class" => 'uncode_colors',
		"value" => $uncode_colors,
		'group' => esc_html__('Module', 'uncode') ,
	) ,
	array(
		'type' => 'textfield',
		'heading' => esc_html__('Number columns ( > 960px )', 'uncode') ,
		'param_name' => 'carousel_lg',
		'value' => 3,
		'description' => esc_html__('Insert the numbers of columns for the viewport from 960px.', 'uncode') ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'carousel'
		) ,
	) ,
	array(
		'type' => 'textfield',
		'heading' => esc_html__('Number columns ( > 570px and < 960px )', 'uncode') ,
		'param_name' => 'carousel_md',
		'value' => 3,
		'description' => esc_html__('Insert the numbers of columns for the viewport from 570px to 960px.', 'uncode') ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'carousel'
		) ,
	) ,
	array(
		'type' => 'textfield',
		'heading' => esc_html__('Number columns ( > 0px and < 570px )', 'uncode') ,
		'param_name' => 'carousel_sm',
		'value' => 1,
		'description' => esc_html__('Insert the numbers of columns for the viewport from 0 to 570px.', 'uncode') ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'carousel'
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Fluid heights", 'uncode') ,
		"param_name" => "single_height_viewport",
		"description" => esc_html__("Activate this to set heights relative to the browser window height, instead of using the normal metro calculations.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'style_preset',
			'value' => 'metro',
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Remove menu height", 'uncode') ,
		"param_name" => "single_height_viewport_minus",
		"description" => esc_html__("Activate this option to remove the menu height from the fluid calculations.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'single_height_viewport',
			'not_empty' => true,
		) ,
	) ,
	array(
		'type' => 'dropdown',
		'heading' => esc_html__('Thumbnail size', 'uncode') ,
		'param_name' => 'thumb_size',
		'description' => esc_html__('Specify the aspect ratio for the media.', 'uncode') ,
		"value" => array(
			esc_html__('Regular', 'uncode') => '',
			'1:1' => 'one-one',
				'2:1' => 'two-one',
				'3:2' => 'three-two',
				'4:3' => 'four-three',
				'10:3' => 'ten-three',
				'16:9' => 'sixteen-nine',
				'21:9' => 'twentyone-nine',
				'1:2' => 'one-two',
				'2:3' => 'two-three',
				'3:4' => 'three-four',
				'3:10' => 'three-ten',
				'9:16' => 'nine-sixteen',
				esc_html__('Fluid', 'uncode') => 'fluid',
		) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'carousel',
		) ,
	) ,
	array(
		"type" => "type_numeric_slider",
		"heading" => esc_html__("Fluid height", 'uncode') ,
		"param_name" => "carousel_height_viewport",
		"description" => esc_html__("Specify the carousel height relative to the browser window.", 'uncode') ,
		"min" => 0,
		"max" => 100,
		"step" => 1,
		"value" => 0,
		"std" => "100",
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'thumb_size',
			'value' => 'fluid',
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Remove menu height", 'uncode') ,
		"param_name" => "carousel_height_viewport_minus",
		"description" => esc_html__("Activate this option to remove the menu height from the fluid calculations.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'thumb_size',
			'value' => 'fluid',
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Filtering", 'uncode') ,
		"param_name" => "filtering",
		"description" => esc_html__("Activate this to add the isotope filtering.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'isotope',
		) ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Filter skin", 'uncode') ,
		"param_name" => "filter_style",
		"description" => esc_html__("Specify the filter skin color.", 'uncode') ,
		"value" => array(
			esc_html__('Light', 'uncode') => 'light',
			esc_html__('Dark', 'uncode') => 'dark'
		) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'isotope',
		) ,
		'dependency' => array(
			'element' => 'filtering',
			'value' => 'yes',
		) ,
	) ,
	array(
		"type" => "dropdown",
		"heading" => esc_html__("Filter menu color", 'uncode') ,
		"param_name" => "filter_back_color",
		"description" => esc_html__("Specify a background color for the filter menu.", 'uncode') ,
		"class" => 'uncode_colors',
		"value" => $uncode_colors,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'isotope',
		) ,
		'dependency' => array(
			'element' => 'filtering',
			'value' => 'yes',
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Filter menu full width", 'uncode') ,
		"param_name" => "filtering_full_width",
		"description" => esc_html__("Activate this to force the full width of the filter.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'isotope',
		) ,
		'dependency' => array(
			'element' => 'filtering',
			'value' => 'yes',
		) ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Filter menu position", 'uncode') ,
		"param_name" => "filtering_position",
		"description" => esc_html__("Specify the filter menu positioning.", 'uncode') ,
		"value" => array(
			esc_html__('Left', 'uncode') => 'left',
			esc_html__('Center', 'uncode') => 'center',
			esc_html__('Right', 'uncode') => 'right',
		) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'isotope',
		) ,
		'dependency' => array(
			'element' => 'filtering',
			'value' => 'yes',
		)
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("'Show All' opposite", 'uncode') ,
		"param_name" => "filter_all_opposite",
		"description" => esc_html__("Activate this to position the 'Show All' button opposite to the rest.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'isotope',
		) ,
		'dependency' => array(
			'element' => 'filtering',
			'value' => 'yes',
		) ,
		'dependency' => array(
			'element' => 'filtering_position',
			'value' => array(
				'left',
				'right'
			)
		) ,
	) ,
	array(
		"type" => "textfield",
		"heading" => esc_html__("'Show All' text", 'uncode') ,
		"param_name" => "filter_all_text",
		"description" => esc_html__("Specify the button label. NB. The default is 'Show All'.", 'uncode') ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'type',
			'value' => 'isotope',
		) ,
		'dependency' => array(
			'element' => 'filtering',
			'value' => 'yes',
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Filter menu uppercase", 'uncode') ,
		"param_name" => "filtering_uppercase",
		"description" => esc_html__("Activate this to have the filter menu in uppercase.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'isotope',
		) ,
		'dependency' => array(
			'element' => 'filtering',
			'value' => 'yes',
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Filter menu mobile hidden", 'uncode') ,
		"param_name" => "filter_mobile",
		"description" => esc_html__("Activate this to hide the filter menu in mobile mode.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'isotope',
		) ,
		'dependency' => array(
			'element' => 'filtering',
			'value' => 'yes',
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Filter scroll", 'uncode') ,
		"param_name" => "filter_scroll",
		"description" => esc_html__("Activate this to scroll to the  module when filtering.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'isotope',
		) ,
		'dependency' => array(
			'element' => 'filtering',
			'value' => 'yes',
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Filter sticky", 'uncode') ,
		"param_name" => "filter_sticky",
		"description" => esc_html__("Activate this to have a sticky filter menu when scrolling.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'isotope',
		) ,
		'dependency' => array(
			'element' => 'filtering',
			'value' => 'yes',
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Pagination", 'uncode') ,
		"param_name" => "pagination",
		"description" => wp_kses(__("Activate this to add the pagination function.<br>NB. This option doesn't work is combination with the 'Random' order and 'Menu Order' or with multiple isotope in the same page.", 'uncode'), array( 'br' => array( ) ) ) ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => array(
				'isotope',
			) ,
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Infinite load more", 'uncode') ,
		"param_name" => "infinite",
		"description" => wp_kses(__("Activate this to load more items with scrolling.<br>NB. This option doesn't work is combination with the 'Random' order and 'Menu Order' or with multiple isotope in the same page.", 'uncode'), array( 'br' => array( ) ) ) ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'pagination',
			'is_empty' => true,
		)
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Load more button", 'uncode') ,
		"param_name" => "infinite_button",
		"description" => esc_html__("Activate this to load more items by pressing the button.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'infinite',
			'value' => 'yes',
		)
	) ,
	array(
		"type" => "dropdown",
		"heading" => esc_html__("Load more button hover effect", 'uncode') ,
		"param_name" => "infinite_hover_fx",
		"description" => esc_html__("Specify an effect on hover state.", 'uncode') ,
		"value" => array(
			'Inherit' => '',
			'Outlined' => 'outlined',
			'Flat' => 'full-colored',
		) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'infinite_button',
			'value' => 'yes',
		) ,
	) ,
	array(
		"type" => "checkbox",
		"heading" => esc_html__("Load more button outlined inverse", 'uncode') ,
		"param_name" => "infinite_button_outline",
		"description" => esc_html__("Outlined buttons don't have a full background color. NB: this option is available only with Load More Button Hover Effect > Outlined.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'infinite_button',
			'value' => 'yes',
		) ,
	) ,
  array(
		"type" => "textfield",
		"heading" => esc_html__("Load more button text", 'uncode') ,
		"param_name" => "infinite_button_text",
		"description" => esc_html__("Specify the button label. NB. The default is 'Load more'.", 'uncode') ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'infinite_button',
			'value' => 'yes',
		) ,
	) ,
	array(
		"type" => "dropdown",
		"heading" => esc_html__("Load more button shape", 'uncode') ,
		"param_name" => "infinite_button_shape",
		"description" => esc_html__("Specify the load more button shape.", 'uncode') ,
		'group' => esc_html__('Module', 'uncode') ,
		"value" => array(
			esc_html__('Default', 'uncode') => '',
			esc_html__('Round', 'uncode') => 'btn-round',
			esc_html__('Circle', 'uncode') => 'btn-circle',
			esc_html__('Square', 'uncode') => 'btn-square'
		) ,
		'dependency' => array(
			'element' => 'infinite_button',
			'value' => 'yes',
		) ,
	) ,
	array(
		"type" => "dropdown",
		"heading" => esc_html__("Load more button color", 'uncode') ,
		"param_name" => "infinite_button_color",
		"description" => esc_html__("Specify a background color for the load more button.", 'uncode') ,
		"class" => 'uncode_colors',
		"value" => $uncode_colors,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'infinite_button',
			'value' => 'yes',
		) ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Pagination or Infinite skin", 'uncode') ,
		"param_name" => "footer_style",
		"description" => esc_html__("Specify the pagination/infinite skin color.", 'uncode') ,
		"value" => array(
			esc_html__('Light', 'uncode') => 'light',
			esc_html__('Dark', 'uncode') => 'dark'
		) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => array(
				'isotope',
			) ,
		) ,
	) ,
	array(
		"type" => "dropdown",
		"heading" => esc_html__("Pagination or Infinite color", 'uncode') ,
		"param_name" => "footer_back_color",
		"description" => esc_html__("Specify a background color for the pagination/infinite.", 'uncode') ,
		"class" => 'uncode_colors',
		"value" => $uncode_colors,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => array(
				'isotope',
			) ,
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Pagination or Infinite full width", 'uncode') ,
		"param_name" => "footer_full_width",
		"description" => esc_html__("Activate this to force the full width of the pagination/infinite.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => array(
				'isotope',
			) ,
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Pagination History Disabled", 'uncode') ,
		"param_name" => "pagination_disable_history",
		"description" => esc_html__("Activate this to remove the History Hash fragment when you use multiple Posts module in the same page.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'pagination',
			'not_empty' => true,
		)
	) ,
	array(
		"type" => "type_numeric_slider",
		"heading" => esc_html__("Items gap", 'uncode') ,
		"param_name" => "gutter_size",
		"min" => 0,
		"max" => 6,
		"step" => 1,
		"value" => 3,
		"description" => esc_html__("Set the items gap.", 'uncode') ,
		"group" => esc_html__("Module", 'uncode') ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Inner module padding", 'uncode') ,
		"param_name" => "inner_padding",
		"description" => esc_html__("Activate this to have an inner padding with the same size as the items gap.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => array(
				'isotope',
				'carousel',
			) ,
		) ,
	) ,
	$uncode_post_list,
	$uncode_page_list,
	$uncode_product_list,
);

$uncode_index_params_third = array(
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Carousel items height", 'uncode') ,
		"param_name" => "carousel_height",
		"description" => esc_html__("Specify the carousel items height.", 'uncode') ,
		"value" => array(
			esc_html__('Auto', 'uncode') => '',
			esc_html__('Equal height', 'uncode') => 'equal',
		) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'thumb_size',
			'value' => array(
				'',
				'one-one',
				'two-one',
				'three-two',
				'four-three',
				'ten-three',
				'sixteen-nine',
				'twentyone-nine',
				'one-two',
				'two-three',
				'three-four',
				'three-ten',
				'nine-sixteen',
			),
		) ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Items vertical alignment", 'uncode') ,
		"param_name" => "carousel_v_align",
		"description" => esc_html__("Specify the items vertical alignment.", 'uncode') ,
		"value" => array(
			esc_html__('Top', 'uncode') => '',
			esc_html__('Middle', 'uncode') => 'middle',
			esc_html__('Bottom', 'uncode') => 'bottom'
		) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'type',
			'value' => 'carousel',
		) ,
		'dependency' => array(
			'element' => 'carousel_height',
			'is_empty' => true,
		) ,
	) ,
	array(
		'type' => 'dropdown',
		'heading' => esc_html__('Transition type', 'uncode') ,
		'param_name' => 'carousel_type',
		"value" => array(
			esc_html__('Slide', 'uncode') => '',
			esc_html__('Fade', 'uncode') => 'fade'
		) ,
		'description' => esc_html__('Specify the transition type.', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'carousel',
		) ,
		'group' => esc_html__('Module', 'uncode')
	) ,
	array(
		'type' => 'dropdown',
		'heading' => esc_html__('Auto rotate slides', 'uncode') ,
		'param_name' => 'carousel_interval',
		'value' => array(
			3000,
			5000,
			10000,
			15000,
			esc_html__('Disable', 'uncode') => 0
		) ,
		'description' => esc_html__('Specify the automatic timeout between slides in milliseconds.', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'carousel',
		) ,
		'group' => esc_html__('Module', 'uncode')
	) ,
	array(
		'type' => 'dropdown',
		'heading' => esc_html__('Navigation speed', 'uncode') ,
		'param_name' => 'carousel_navspeed',
		'value' => array(
			200,
			400,
			700,
			1000,
			esc_html__('Disable', 'uncode') => 0
		) ,
		'std' => 400,
		'description' => esc_html__('Specify the navigation speed between slides in milliseconds.', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'carousel',
		) ,
		'group' => esc_html__('Module', 'uncode')
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Loop", 'uncode') ,
		"param_name" => "carousel_loop",
		"description" => esc_html__("Activate the loop option to make the carousel infinite.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'carousel',
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Overflow visible", 'uncode') ,
		"param_name" => "carousel_overflow",
		"description" => esc_html__("Activate this option to make the element overflow its container (get rid of the cropping area).", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'carousel',
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Navigation", 'uncode') ,
		"param_name" => "carousel_nav",
		"description" => esc_html__("Activate the navigation to show navigational arrows.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'carousel_overflow',
			'is_empty' => true,
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Navigation Mobile", 'uncode') ,
		"param_name" => "carousel_nav_mobile",
		"description" => esc_html__("Activate the navigation to show navigational arrows for mobile devices.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'carousel_overflow',
			'is_empty' => true,
		) ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Navigation skin", 'uncode') ,
		"param_name" => "carousel_nav_skin",
		"description" => esc_html__("Specify the navigation arrows skin.", 'uncode') ,
		"value" => array(
			esc_html__('Light', 'uncode') => 'light',
			esc_html__('Dark', 'uncode') => 'dark'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'carousel_overflow',
			'is_empty' => true,
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Dots navigation", 'uncode') ,
		"param_name" => "carousel_dots",
		"description" => esc_html__("Activate the dots navigation to show navigational dots in the bottom.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'carousel',
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Dots Navigation Extra Top Space", 'uncode') ,
		"param_name" => "carousel_dots_space",
		"description" => esc_html__("Activate this to add extra top space to the Navigation Dots.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		'std' => '',
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'carousel_dots',
			'value' => 'yes',
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Dots Navigation Mobile", 'uncode') ,
		"param_name" => "carousel_dots_mobile",
		"description" => esc_html__("Activate the dots navigation to show navigational dots in the bottom for mobile devices.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'carousel',
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Dots navigation inside", 'uncode') ,
		"param_name" => "carousel_dots_inside",
		"description" => esc_html__("Activate to have the dots navigation inside the carousel.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'carousel',
		) ,
	) ,
	array(
		'type' => 'dropdown',
		'heading' => esc_html__('Dots Navigation Position', 'uncode') ,
		'param_name' => 'carousel_dot_position',
		"value" => array(
			esc_html__('Center', 'uncode') => '',
			esc_html__('Left', 'uncode') => 'left',
			esc_html__('Right', 'uncode') => 'right',
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'description' => esc_html__('Specify the position of dots.', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'carousel',
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Autoheight", 'uncode') ,
		"param_name" => "carousel_autoh",
		"description" => esc_html__("Activate to adjust the height automatically when possible.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'thumb_size',
			'value' => array(
				'',
				'one-one',
				'two-one',
				'three-two',
				'four-three',
				'ten-three',
				'sixteen-nine',
				'twentyone-nine',
				'one-two',
				'two-three',
				'three-four',
				'three-ten',
				'nine-sixteen',
			),
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Off-Grid", 'uncode') ,
		"param_name" => "off_grid",
		"description" => esc_html__("Active this to shift elements (even or odd).", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		'std' => '',
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'isotope_mode',
			'value' => array(
				'masonry',
				'packery'
			),
		) ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Select the set of items to shift.", 'uncode') ,
		"param_name" => "off_grid_element",
		"description" => esc_html__("Select what item to put Off-Grid.", 'uncode') ,
		'value' => array(
			esc_html__('Odd', 'uncode') => 'odd',
			esc_html__('Even', 'uncode') => 'even',
			esc_html__('Custom', 'uncode') => 'custom'
		) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'off_grid',
			'not_empty' => true,
		) ,
	) ,
	array(
		'type' => 'textfield',
		'heading' => esc_html__('Off-Grid custom value', 'uncode') ,
		'param_name' => 'off_grid_custom',
		'value' => '0,2',
		'description' => wp_kses(__('Enter a number or a series of comma separated numbers.<br />NB. The first element is identified by 0.', 'uncode'), array( 'br' => array( ) ) ) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'off_grid_element',
			'value' => array(
				'custom',
			) ,
		) ,
	) ,
	array(
		"type" => "type_numeric_slider",
		"heading" => esc_html__("Off-Grid value", 'uncode') ,
		"param_name" => "off_grid_val",
		"min" => 1,
		"max" => 7,
		"step" => 1,
		"value" => 2,
		"description" => esc_html__("Set the shift value.", 'uncode') ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'off_grid',
			'not_empty' => true,
		) ,
	) ,
	array(
		'type' => 'textfield',
		'heading' => esc_html__('Breakpoint - First step', 'uncode') ,
		'param_name' => 'screen_lg',
		'value' => 1000,
		'description' => wp_kses(__('Insert the isotope large layout breakpoint in pixel.<br />NB. This is referring to the width of the isotope container, not to the window width.', 'uncode'), array( 'br' => array( ) ) ) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => array(
				'isotope',
			) ,
		) ,
	) ,
	array(
		'type' => 'textfield',
		'heading' => esc_html__('Breakpoint - Second step', 'uncode') ,
		'param_name' => 'screen_md',
		'value' => 600,
		'description' => wp_kses(__('Insert the isotope medium layout breakpoint in pixel.<br />NB. This is referring to the width of the isotope container, not to the window width.', 'uncode'), array( 'br' => array( ) ) ) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => array(
				'isotope',
			) ,
		) ,
	) ,
	array(
		'type' => 'textfield',
		'heading' => esc_html__('Breakpoint - Third step', 'uncode') ,
		'param_name' => 'screen_sm',
		'value' => 480,
		'description' => wp_kses(__('Insert the isotope small layout breakpoint in pixel.<br />NB. This is referring to the width of the isotope container, not to the window width.', 'uncode'), array( 'br' => array( ) ) ) ,
		'group' => esc_html__('Module', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => array(
				'isotope',
			) ,
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Not Active Items Semi-Transparent", 'uncode') ,
		"param_name" => "carousel_half_opacity",
		"description" => esc_html__("Activate this option to make not active items semi-transparent.", 'uncode') ,
		"std" => '',
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'carousel',
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Not active items scaled", 'uncode') ,
		"param_name" => "carousel_scaled",
		"description" => esc_html__("Activate this option to make not active items scaled.", 'uncode') ,
		"std" => '',
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'carousel',
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Not active items not clickable", 'uncode') ,
		"param_name" => "carousel_pointer_events",
		"description" => esc_html__("Activate this option to make not active items not clickable.", 'uncode') ,
		"std" => '',
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'carousel',
		) ,
	) ,
	array(
		"type" => "type_numeric_slider",
		"heading" => esc_html__("Stage padding", 'uncode') ,
		"description" => esc_html__("Activate this option to add left and right padding style onto stage-wrapper.", 'uncode') ,
		"param_name" => "stage_padding",
		"min" => 0,
		"max" => 75,
		"step" => 5,
		"value" => 0,
		"group" => esc_html__("Module", 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'carousel' ,
		) ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Block layout", 'uncode') ,
		"param_name" => "single_text",
		"description" => esc_html__("Specify the text positioning inside the box.", 'uncode') ,
		"value" => array(
			esc_html__('Content under image', 'uncode') => 'under',
			esc_html__('Content overlay', 'uncode') => 'overlay',
			esc_html__('Content lateral', 'uncode') => 'lateral',
		) ,
		'group' => esc_html__('Blocks', 'uncode') ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Width", 'uncode') ,
		"param_name" => "single_width",
		"description" => esc_html__("Specify the box width.", 'uncode') ,
		"value" => $units,
		"std" => "4",
		'group' => esc_html__('Blocks', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => array(
				'isotope',
			) ,
		) ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Height", 'uncode') ,
		"param_name" => "single_height",
		"description" => esc_html__("Specify the box height.", 'uncode') ,
		"value" => $units,
		"std" => "4",
		'group' => esc_html__('Blocks', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => array(
				'isotope',
			) ,
		) ,
		'dependency' => array(
			'element' => 'style_preset',
			'value' => 'metro',
		) ,
		'dependency' => array(
			'element' => 'single_height_viewport',
			'is_empty' => true,
		) ,
	) ,
	array(
		"type" => "type_numeric_slider",
		"heading" => esc_html__("Height", 'uncode') ,
		"param_name" => "single_fluid_height",
		"min" => 0,
		"max" => 100,
		"step" => 1,
		"value" => 0,
		"std" => '33',
		"description" => esc_html__('Set the row height with a percent value.', 'uncode') ,
		'group' => esc_html__('Blocks', 'uncode') ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => array(
				'isotope',
			) ,
		) ,
		'dependency' => array(
			'element' => 'style_preset',
			'value' => 'metro',
		) ,
		'dependency' => array(
			'element' => 'single_height_viewport',
			'not_empty' => true,
		) ,
	) ,
	array(
		'type' => 'dropdown',
		'heading' => esc_html__('Media ratio', 'uncode') ,
		'param_name' => 'images_size',
		'description' => esc_html__('Specify the aspect ratio for the media.', 'uncode') ,
		"value" => array(
			esc_html__('Regular', 'uncode') => '',
			'1:1' => 'one-one',
			'2:1' => 'two-one',
			'3:2' => 'three-two',
			'4:3' => 'four-three',
			'10:3' => 'ten-three',
			'16:9' => 'sixteen-nine',
			'21:9' => 'twentyone-nine',
			'1:2' => 'one-two',
			'2:3' => 'two-three',
			'3:4' => 'three-four',
			'3:10' => 'three-ten',
			'9:16' => 'nine-sixteen',
		) ,
		'group' => esc_html__('Blocks', 'uncode') ,
		'dependency' => array(
			'element' => 'style_preset',
			'value' => 'masonry',
		) ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Media position", 'uncode') ,
		"param_name" => "single_image_position",
		"description" => esc_html__("Specify the image alignment.", 'uncode') ,
		"value" => array(
			esc_html__('Left', 'uncode') => '',
			esc_html__('Right', 'uncode') => 'right'
		) ,
		'group' => esc_html__('Blocks', 'uncode') ,
		'dependency' => array(
			'element' => 'single_text',
			'value' => 'lateral',
		) ,
	) ,
	array(
		"type" => "type_numeric_slider",
		"heading" => esc_html__("Media size", 'uncode') ,
		"param_name" => "single_image_size",
		"min" => 1,
		"max" => 11,
		"step" => 1,
		"std" => 6,
		"description" => esc_html__('Set the image size.', 'uncode') ,
		'group' => esc_html__('Blocks', 'uncode') ,
		'dependency' => array(
			'element' => 'single_text',
			'value' => 'lateral',
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Media above content on mobile", 'uncode') ,
		"param_name" => "single_lateral_responsive",
		"description" => esc_html__("Activate this to put the media above the content on mobile devices.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		'std' => 'yes',
		"group" => esc_html__("Blocks", 'uncode') ,
		'dependency' => array(
			'element' => 'single_text',
			'value' => 'lateral',
		) ,
	) ,
	array(
		"type" => "dropdown",
		"heading" => esc_html__("Background color", 'uncode') ,
		"param_name" => "single_back_color",
		"description" => esc_html__("Specify a background color for the box.", 'uncode') ,
		"value" => $uncode_colors,
		'group' => esc_html__('Blocks', 'uncode') ,
	) ,
	array(
		'type' => 'dropdown',
		'heading' => esc_html__('Shape', 'uncode') ,
		'param_name' => 'single_shape',
		'value' => array(
			esc_html__('Select…', 'uncode') => '',
			esc_html__('Rounded', 'uncode') => 'round',
			esc_html__('Circular', 'uncode') => 'circle'
		) ,
		'description' => esc_html__('Specify one if you want to shape the block.', 'uncode'),
		'group' => esc_html__('Blocks', 'uncode'),
	) ,
	array(
		"type" => "dropdown",
		"heading" => esc_html__("Border radius", 'uncode') ,
		"param_name" => "radius",
		"description" => esc_html__("Specify the border radius effect.", 'uncode') ,
		'group' => esc_html__('Blocks', 'uncode') ,
		"value" => array(
			esc_html__('Extra Small', 'uncode') => 'xs',
			esc_html__('Small', 'uncode') => ' ',
			esc_html__('Standard', 'uncode') => 'std',
			esc_html__('Large', 'uncode') => 'lg',
			esc_html__('Extra Large', 'uncode') => 'xl',
		),
		"std" => ' ',
		'dependency' => array(
			'element' => 'single_shape',
			'value' => 'round'
		) ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Text skin", 'uncode') ,
		"param_name" => "single_style",
		"description" => esc_html__("Specify the skin inside the content box.", 'uncode') ,
		"value" => array(
			esc_html__('Light', 'uncode') => 'light',
			esc_html__('Dark', 'uncode') => 'dark'
		) ,
		'group' => esc_html__('Blocks', 'uncode') ,
	) ,
	array(
		"type" => "dropdown",
		"heading" => esc_html__("Overlay color", 'uncode') ,
		"param_name" => "single_overlay_color",
		"description" => esc_html__("Specify a background color for the overlay.", 'uncode') ,
		"value" => $uncode_colors,
		'group' => esc_html__('Blocks', 'uncode') ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Overlay coloration", 'uncode') ,
		"param_name" => "single_overlay_coloration",
		"description" => wp_kses(__("Specify the coloration style for the overlay.<br />NB. For the gradient you can't customize the overlay color.", 'uncode'), array( 'br' => array( ) ) ) ,
		"value" => array(
			esc_html__('Fully colored', 'uncode') => '',
			esc_html__('Gradient top', 'uncode') => 'top_gradient',
			esc_html__('Gradient bottom', 'uncode') => 'bottom_gradient',
		) ,
		'group' => esc_html__('Blocks', 'uncode'),
	) ,
	array(
		"type" => "dropdown",
		"heading" => esc_html__("Overlay blend mode (experimental)", 'uncode') ,
		"param_name" => "single_overlay_blend",
		"description" => esc_html__("Specify a blending mode. NB. It does not work on IE and Edge.", 'uncode') ,
		'group' => esc_html__('Blocks', 'uncode'),
		"value" => array(
			esc_html__('None', 'uncode') => '',
			esc_html__('Multiply', 'uncode') => 'multiply',
			esc_html__('Screen', 'uncode') => 'screen',
			esc_html__('Overlay', 'uncode') => 'overlay',
			esc_html__('Darken', 'uncode') => 'darken',
			esc_html__('Lighten', 'uncode') => 'lighten',
			esc_html__('Color dodge', 'uncode') => 'color-dodge',
			esc_html__('Color burn', 'uncode') => 'color-burn',
			esc_html__('Hard light', 'uncode') => 'hard-light',
			esc_html__('Soft light', 'uncode') => 'soft-light',
			esc_html__('Difference', 'uncode') => 'difference',
			esc_html__('Exclusion', 'uncode') => 'exclusion',
		) ,
		"dependency" => array(
			'element' => "single_overlay_color",
			'not_empty' => true
		) ,
	) ,
	array(
		"type" => "type_numeric_slider",
		"heading" => esc_html__("Overlay opacity", 'uncode') ,
		"param_name" => "single_overlay_opacity",
		"min" => 1,
		"max" => 100,
		"step" => 1,
		"value" => 50,
		"description" => esc_html__("Set the overlay opacity.", 'uncode') ,
		'group' => esc_html__('Blocks', 'uncode'),
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Overlay text visibility", 'uncode') ,
		"param_name" => "single_text_visible",
		"description" => esc_html__("Activate this to show the text as starting point.", 'uncode') ,
		"value" => array(
			esc_html__('Hidden', 'uncode') => 'no',
			esc_html__('Visible', 'uncode') => 'yes',
		) ,
		"group" => esc_html__("Blocks", 'uncode') ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Overlay text animation", 'uncode') ,
		"param_name" => "single_text_anim",
		"description" => esc_html__("Activate this to animate the text on mouse over.", 'uncode') ,
		"value" => array(
			esc_html__('Animated', 'uncode') => 'yes',
			esc_html__('Static', 'uncode') => 'no',
		) ,
		"group" => esc_html__("Blocks", 'uncode') ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Overlay text animation type", 'uncode') ,
		"param_name" => "single_text_anim_type",
		"description" => esc_html__("Specify the animation type.", 'uncode') ,
		"value" => array(
			esc_html__('Opacity', 'uncode') => '',
			esc_html__('Bottom to top', 'uncode') => 'btt',
		) ,
		"group" => esc_html__("Blocks", 'uncode') ,
		'dependency' => array(
			'element' => 'single_text_anim',
			'value' => 'yes',
		) ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Overlay visibility", 'uncode') ,
		"param_name" => "single_overlay_visible",
		"description" => esc_html__("Activate this to show the overlay as starting point.", 'uncode') ,
		"value" => array(
			esc_html__('Hidden', 'uncode') => 'no',
			esc_html__('Visible', 'uncode') => 'yes',
		) ,
		"group" => esc_html__("Blocks", 'uncode') ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Overlay animation", 'uncode') ,
		"param_name" => "single_overlay_anim",
		"description" => esc_html__("Activate this to animate the overlay on mouse over.", 'uncode') ,
		"value" => array(
			esc_html__('Animated', 'uncode') => 'yes',
			esc_html__('Static', 'uncode') => 'no',
		) ,
		"group" => esc_html__("Blocks", 'uncode') ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Image coloration", 'uncode') ,
		"param_name" => "single_image_coloration",
		"description" => esc_html__("Specify the image coloration mode.", 'uncode') ,
		"value" => array(
			esc_html__('Standard', 'uncode') => '',
			esc_html__('Desaturated', 'uncode') => 'desaturated',
		) ,
		"group" => esc_html__("Blocks", 'uncode') ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Image coloration animation", 'uncode') ,
		"param_name" => "single_image_color_anim",
		"description" => esc_html__("Activate this to animate the image coloration on mouse over.", 'uncode') ,
		"value" => array(
			esc_html__('Static', 'uncode') => '',
			esc_html__('Animated', 'uncode') => 'yes',
		) ,
		"group" => esc_html__("Blocks", 'uncode') ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Image animation", 'uncode') ,
		"param_name" => "single_image_anim",
		"description" => esc_html__("Activate this to animate the image on mouse over.", 'uncode') ,
		"value" => array(
			esc_html__('Animated', 'uncode') => 'yes',
			esc_html__('Static', 'uncode') => 'no',
		) ,
		"group" => esc_html__("Blocks", 'uncode') ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Image animation on mouse move", 'uncode') ,
		"param_name" => "single_image_anim_move",
		"description" => esc_html__("Activate this to animate the image on the mouse move.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Blocks", 'uncode') ,
		'dependency' => array(
			'element' => 'single_image_anim',
			'value' => array('yes'),
		)
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Text horizontal alignment", 'uncode') ,
		"param_name" => "single_h_align",
		"description" => esc_html__("Specify the horizontal alignment.", 'uncode') ,
		"value" => array(
			esc_html__('Left', 'uncode') => 'left',
			esc_html__('Center', 'uncode') => 'center',
			esc_html__('Right', 'uncode') => 'right',
			esc_html__('Justify', 'uncode') => 'justify'
		) ,
		'group' => esc_html__('Blocks', 'uncode') ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Text horizontal alignment mobile", 'uncode') ,
		"param_name" => "single_h_align_mobile",
		"description" => esc_html__("Specify the horizontal alignment in mobile.", 'uncode') ,
		"value" => array(
			esc_html__('Inherit', 'uncode') => '',
			esc_html__('Left', 'uncode') => 'left',
			esc_html__('Center', 'uncode') => 'center',
			esc_html__('Right', 'uncode') => 'right',
			esc_html__('Justify', 'uncode') => 'justify'
		) ,
		'group' => esc_html__('Blocks', 'uncode') ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Content vertical position", 'uncode') ,
		"param_name" => "single_v_position",
		"description" => esc_html__("Specify the text vertical position.", 'uncode') ,
		"value" => array(
			esc_html__('Middle', 'uncode') => '',
			esc_html__('Top', 'uncode') => 'top',
			esc_html__('Bottom', 'uncode') => 'bottom'
		) ,
		'group' => esc_html__('Blocks', 'uncode') ,
		'dependency' => array(
			'element' => 'single_text',
			'value' => 'overlay',
		) ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Text vertical alignment", 'uncode') ,
		"param_name" => "single_vertical_text",
		"description" => esc_html__("Specify the text vertical alignment. NB: it works with Metro Layout only.", 'uncode') ,
		"value" => array(
			esc_html__('Top', 'uncode') => '',
			esc_html__('Middle', 'uncode') => 'middle',
			esc_html__('Bottom', 'uncode') => 'bottom',
		) ,
		'group' => esc_html__('Blocks', 'uncode') ,
		'dependency' => array(
			'element' => 'single_text',
			'value' => 'lateral',
		) ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Content dimension reduced", 'uncode') ,
		"param_name" => "single_reduced",
		"description" => esc_html__("Specify the text reduction amount to shrink the overlay content dimension.", 'uncode') ,
		"value" => array(
			esc_html__('100%', 'uncode') => '',
			esc_html__('75%', 'uncode') => 'three_quarter',
			esc_html__('50%', 'uncode') => 'half',
			esc_html__('Limit Width', 'uncode') => 'limit-width',
		) ,
		"group" => esc_html__("Blocks", 'uncode') ,
		'dependency' => array(
			'element' => 'single_text',
			'value' => 'overlay',
		)
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Content dimension not reduced on mobile", 'uncode') ,
		"param_name" => "single_reduced_mobile",
		"description" => esc_html__("Activate this to have 100% content wide on mobile devices.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Blocks", 'uncode') ,
		'dependency' => array(
			'element' => 'single_reduced',
			'value' => array('three_quarter', 'half'),
		)
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Content horizontal position", 'uncode') ,
		"param_name" => "single_h_position",
		"description" => esc_html__("Specify the text horizontal position.", 'uncode') ,
		"value" => array(
			esc_html__('Left', 'uncode') => 'left',
			esc_html__('Center', 'uncode') => 'center',
			esc_html__('Right', 'uncode') => 'right'
		) ,
		'group' => esc_html__('Blocks', 'uncode') ,
		'dependency' => array(
			'element' => 'single_reduced',
			'value' => array('', 'three_quarter', 'half'),
		) ,
	) ,
	array(
		"type" => "type_numeric_slider",
		"heading" => esc_html__("Text padding", 'uncode') ,
		"param_name" => "single_padding",
		"min" => 0,
		"max" => 5,
		"step" => 1,
		"value" => 2,
		"description" => esc_html__("Set the text padding", 'uncode') ,
		"group" => esc_html__("Blocks", 'uncode') ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Text large", 'uncode') ,
		"param_name" => "single_text_lead",
		"description" => esc_html__("Activate if you want to enlarge the font size.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Blocks", 'uncode') ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Reduce space between elements", 'uncode') ,
		"param_name" => "single_text_reduced",
		"description" => esc_html__("Activate this to have less space between all the text elements inside the box.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Blocks", 'uncode') ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Multiple click areas", 'uncode') ,
		"param_name" => "single_elements_click",
		"description" => esc_html__("Activate this to make every single elements clickable instead of the whole block (when availabe).", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Blocks", 'uncode') ,
		'dependency' => array(
			'element' => 'single_text',
			'value' => 'overlay',
		) ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Title text transform", 'uncode') ,
		"param_name" => "single_title_transform",
		"description" => esc_html__("Specify the title text transformation.", 'uncode') ,
		"value" => array(
			esc_html__('Default CSS', 'uncode') => '',
			esc_html__('Uppercase', 'uncode') => 'uppercase',
			esc_html__('Lowercase', 'uncode') => 'lowercase',
			esc_html__('Capitalize', 'uncode') => 'capitalize'
		) ,
		"group" => esc_html__("Blocks", 'uncode') ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Title dimension", 'uncode') ,
		"param_name" => "single_title_dimension",
		"description" => esc_html__("Specify the title dimension.", 'uncode') ,
		"value" => $heading_size,
		"group" => esc_html__("Blocks", 'uncode') ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Title semantic", 'uncode') ,
		"param_name" => "single_title_semantic",
		"description" => esc_html__("Specify element tag.", 'uncode') ,
		"value" => $heading_semantic,
		'std' => 'h3',
		"group" => esc_html__("Blocks", 'uncode') ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Title font family", 'uncode') ,
		"param_name" => "single_title_family",
		"description" => esc_html__("Specify the title font family.", 'uncode') ,
		"value" => $heading_font,
		'std' => '',
		"group" => esc_html__("Blocks", 'uncode') ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Title font weight", 'uncode') ,
		"param_name" => "single_title_weight",
		"description" => esc_html__("Specify the title font weight.", 'uncode') ,
		"value" =>$heading_weight,
		'std' => '',
		"group" => esc_html__("Blocks", 'uncode') ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Title line height", 'uncode') ,
		"param_name" => "single_title_height",
		"description" => esc_html__("Specify the title line height.", 'uncode') ,
		"value" => $heading_height,
		"group" => esc_html__("Blocks", 'uncode') ,
	) ,
	array(
		"type" => 'dropdown',
		"heading" => esc_html__("Title letter spacing", 'uncode') ,
		"param_name" => "single_title_space",
		"description" => esc_html__("Specify the title letter spacing.", 'uncode') ,
		"value" => $heading_space,
		"group" => esc_html__("Blocks", 'uncode') ,
	) ,
	array(
		'type' => 'iconpicker',
		'heading' => esc_html__('Icon', 'uncode') ,
		'param_name' => 'single_icon',
		'description' => esc_html__('Specify icon from library.', 'uncode') ,
		'settings' => array(
			'emptyIcon' => true,
			'iconsPerPage' => 1100,
			'type' => 'uncode'
		) ,
		'group' => esc_html__('Blocks', 'uncode') ,
	) ,
	array(
		'type' => 'vc_link',
		'heading' => esc_html__('Custom link', 'uncode') ,
		'param_name' => 'single_link',
		'description' => esc_html__('Enter the custom link for the item.', 'uncode') ,
		'group' => esc_html__('Blocks', 'uncode') ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Shadow", 'uncode') ,
		"param_name" => "single_shadow",
		"description" => esc_html__("Activate this for the shadow effect.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Blocks", 'uncode') ,
	) ,
	array(
		"type" => "dropdown",
		"heading" => esc_html__("Shadow type", 'uncode') ,
		"param_name" => "shadow_weight",
		"description" => esc_html__("Specify the shadow option preset.", 'uncode') ,
		"group" => esc_html__("Blocks", 'uncode') ,
		"value" => array(
			esc_html__('Extra Small', 'uncode') => '',
			esc_html__('Small', 'uncode') => 'sm',
			esc_html__('Standard', 'uncode') => 'std',
			esc_html__('Large', 'uncode') => 'lg',
			esc_html__('Extra Large', 'uncode') => 'xl',
			esc_html__('None', 'uncode') => 'none',
		) ,
		'dependency' => array(
			'element' => 'single_shadow',
			'not_empty' => true
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Shadow Darker", 'uncode') ,
		"param_name" => "shadow_darker",
		"description" => esc_html__("Activate this for the dark shadow effect.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Blocks", 'uncode') ,
		'dependency' => array(
			'element' => 'single_shadow',
			'not_empty' => true
		) ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Remove border", 'uncode') ,
		"param_name" => "single_border",
		"description" => esc_html__("Activate this to remove the border around the block.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Blocks", 'uncode') ,
	) ,
	array_merge($add_css_animation, array("group" => esc_html__("Blocks", 'uncode'), "param_name" => 'single_css_animation')),
	array_merge($add_animation_speed, array("group" => esc_html__("Blocks", 'uncode'), "param_name" => 'single_animation_speed', 'dependency' => array('element' => 'single_css_animation','not_empty' => true))),
	array_merge($add_animation_delay, array("group" => esc_html__("Blocks", 'uncode'), "param_name" => 'single_animation_delay', 'dependency' => array('element' => 'single_css_animation','not_empty' => true))),
	array(
		"type" => "checkbox",
		"heading" => esc_html__("Animation first items", 'uncode') ,
		"description" => esc_html__("Animate only first loop of items in the carousel.", 'uncode') ,
		"param_name" => "single_animation_first",
		"group" => esc_html__("Blocks", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		'dependency' => array(
			'element' => 'index_type',
			'value' => 'carousel' ,
		) ,
	) ,
	array(
		'type' => 'dropdown',
		'heading' => esc_html__('Post settings', 'uncode') ,
		'param_name' => 'post_matrix',
		'description' => esc_html__('Decide to follow the post ID or to create an independent pattern or matrix.', 'uncode'),
		'value' => array(
			esc_html__('By Post ID', 'uncode') => '',
			esc_html__('By Matrix', 'uncode') => 'matrix',
		) ,
		'std' => '',
		'group' => esc_html__('Single block', 'uncode'),
	) ,
	array(
		'type' => 'checkbox',
		'heading' => esc_html__('Custom order', 'uncode') ,
		'param_name' => 'custom_order',
		'description' => wp_kses(__('Activate this to order the items with drag & drop.<br/>NB. Custom order is only possible when the \'Infinite load more\' or pagination are deactivated.', 'uncode'), array( 'br' => array( ) ) ) ,
		'value' => Array(
			esc_html__('Yes, please', 'uncode') => 'yes'
		) ,
		'group' => esc_html__('Single block', 'uncode'),
		'dependency' => array(
			'element' => 'post_matrix',
			'is_empty' => true,
		) ,
	) ,
	array(
		'type' => 'uncode_matrix_set_amount',
		'heading' => esc_html__('Matrix amount', 'uncode') ,
		'param_name' => 'matrix_amount',
		'description' => esc_html__('Enter an integer number that will define your matrix range. If you use the pagination mode the max limit is the post count itself.', 'uncode') ,
		'group' => esc_html__('Single block', 'uncode') ,
		'value' => '5',
		'dependency' => array(
			'element' => 'post_matrix',
			'value' => 'matrix',
		) ,
	) ,
	array(
		'type' => 'textfield',
		'edit_field_class' => 'hidden',
		'param_name' => 'order_ids',
		'group' => esc_html__('Single block', 'uncode') ,
	) ,
	array(
		'type' => 'uncode_items',
		'heading' => '',
		'param_name' => 'items',
		//'description' => esc_html__('Enter text which will be used as widget title. Leave blank if no title is needed.', 'uncode') ,
		'group' => esc_html__('Single block', 'uncode') ,
		'dependency' => array(
			'element' => 'post_matrix',
			'is_empty' => true,
		) ,
	) ,
	array(
		'type' => 'uncode_matrix_items',
		'heading' => '',
		'param_name' => 'matrix_items',
		//'description' => esc_html__('Enter text which will be used as widget title. Leave blank if no title is needed.', 'uncode') ,
		'group' => esc_html__('Single block', 'uncode') ,
		'dependency' => array(
			'element' => 'post_matrix',
			'value' => 'matrix',
		) ,
	) ,
	array(
		'type' => 'dropdown',
		'heading' => 'Skin',
		'param_name' => 'lbox_skin',
		'value' => array(
			esc_html__('Dark', 'uncode') => '',
			esc_html__('Light', 'uncode') => 'white',
		) ,
		'description' => esc_html__('Specify the lightbox skin color.', 'uncode') ,
		'group' => esc_html__('Lightbox', 'uncode') ,
	) ,
	array(
		'type' => 'dropdown',
		'heading' => 'Direction',
		'param_name' => 'lbox_dir',
		'value' => array(
			esc_html__('Horizontal', 'uncode') => '',
			esc_html__('Vertical', 'uncode') => 'vertical',
		) ,
		'description' => esc_html__('Specify the lightbox sliding direction.', 'uncode') ,
		'group' => esc_html__('Lightbox', 'uncode') ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Title", 'uncode') ,
		"param_name" => "lbox_title",
		"description" => esc_html__("Activate this to add the media title.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Lightbox", 'uncode') ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Caption", 'uncode') ,
		"param_name" => "lbox_caption",
		"description" => esc_html__("Activate this to add the media caption.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Lightbox", 'uncode') ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Social", 'uncode') ,
		"param_name" => "lbox_social",
		"description" => esc_html__("Activate this for the social sharing buttons.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Lightbox", 'uncode') ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Deeplinking", 'uncode') ,
		"param_name" => "lbox_deep",
		"description" => esc_html__("Activate this for the deeplinking of every slide.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Lightbox", 'uncode') ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("No thumbnails", 'uncode') ,
		"param_name" => "lbox_no_tmb",
		"description" => esc_html__("Activate this for not showing the thumbnails.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Lightbox", 'uncode') ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("No arrows", 'uncode') ,
		"param_name" => "lbox_no_arrows",
		"description" => esc_html__("Activate this for not showing the navigation arrows.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Lightbox", 'uncode') ,
	) ,
	array(
		"type" => 'checkbox',
		"heading" => esc_html__("Remove double tap", 'uncode') ,
		"param_name" => "no_double_tap",
		"description" => esc_html__("Remove the double tap action on mobile.", 'uncode') ,
		"value" => Array(
			esc_html__("Yes, please", 'uncode') => 'yes'
		) ,
		"group" => esc_html__("Mobile", 'uncode') ,
	) ,
	array(
		'type' => 'textfield',
		'heading' => esc_html__('Extra class name', 'uncode') ,
		'param_name' => 'el_class',
		'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'uncode') ,
		'group' => esc_html__('Extra', 'uncode')
	)
);

$uncode_index_params = array_merge($uncode_index_params_first, $uncode_index_params_second, $uncode_index_params_third);

$uncode_index_map = array(
	'name' => esc_html__('Posts', 'uncode') ,
	'base' => 'uncode_index',
	'weight' => 999,
	'php_class_name' => 'uncode_index',
	'icon' => 'fa fa-th',
	'description' => esc_html__('Isotope grid or carousel layout', 'uncode') ,
	'params' => $uncode_index_params
);

vc_map($uncode_index_map);

/* Content slider
 ---------------------------------------------------------- */
vc_map(array(
    'name' => esc_html__('Content Slider', 'uncode') ,
    'description' => esc_html__('Button element', 'uncode') ,
    'base' => 'uncode_slider',
    'weight' => 96,
    'php_class_name' => 'uncode_slider',
    'show_settings_on_create' => false,
    'is_container' => true,
    'icon' => 'fa fa-fast-forward',
    'category' => esc_html__('Content', 'uncode') ,
    'description' => esc_html__('Uncode slider', 'uncode') ,
    'params' => array(
  		array(
			'type' => 'dropdown',
			'heading' => esc_html__('Transition type', 'uncode') ,
			'param_name' => 'slider_type',
			"value" => array(
				esc_html__('Slide', 'uncode') => '',
				esc_html__('Fade', 'uncode') => 'fade'
			) ,
			'description' => esc_html__('Specify the transition type.', 'uncode') ,
		) ,
		array(
			'type' => 'dropdown',
			'heading' => esc_html__('Auto rotate slides', 'uncode') ,
			'param_name' => 'slider_interval',
			'value' => array(
				3000,
				5000,
				10000,
				15000,
				esc_html__('Disable', 'uncode') => 0
			) ,
			'std' => 0,
			'description' => esc_html__('Specify the automatic timeout between slides in milliseconds.', 'uncode') ,
		) ,
		array(
			'type' => 'dropdown',
			'heading' => esc_html__('Navigation speed', 'uncode') ,
			'param_name' => 'slider_navspeed',
			'value' => array(
				200,
				400,
				700,
				1000,
				esc_html__('Disable', 'uncode') => 0
			) ,
			'std' => 400,
			'description' => esc_html__('Specify the navigation speed between slides in milliseconds.', 'uncode') ,
		) ,
		array(
			'type' => 'checkbox',
			"heading" => esc_html__("Loop", 'uncode') ,
			'param_name' => 'slider_loop',
			"value" => Array(
				esc_html__("Yes, please", 'uncode') => 'yes'
			) ,
			"description" => esc_html__("Activate the loop option to make the carousel infinite. NB. Don't activate if the slider contains an Isotope index.", 'uncode') ,
		) ,
		array(
			'type' => 'checkbox',
			"heading" => esc_html__("Arrows hidden", 'uncode') ,
			'param_name' => 'slider_hide_arrows',
			"value" => Array(
				esc_html__("Yes, please", 'uncode') => 'yes'
			) ,
			"description" => esc_html__("Activate this to hide slider arrows. NB. Arrows are visible only when you use the Content Slider in the page header.", 'uncode') ,
		) ,
		array(
			'type' => 'checkbox',
			"heading" => esc_html__("Dots hidden", 'uncode') ,
			'param_name' => 'slider_hide_dots',
			"value" => Array(
				esc_html__("Yes, please", 'uncode') => 'yes'
			) ,
			"description" => esc_html__("Activate this to hide slider pagination dots.", 'uncode') ,
		) ,
  		array(
			'type' => 'dropdown',
			'heading' => esc_html__('Dots Navigation Position', 'uncode') ,
			'param_name' => 'slider_dot_position',
			"value" => array(
				esc_html__('Center', 'uncode') => '',
				esc_html__('Left', 'uncode') => 'left',
				esc_html__('Right', 'uncode') => 'right',
			) ,
			"group" => esc_html__("Dots", 'uncode'),
			'description' => esc_html__('Specify the position of dots.', 'uncode') ,
			'dependency' => array(
				'element' => 'slider_hide_dots',
				'is_empty' => true,
			) ,
		) ,
  		array(
			'type' => 'dropdown',
			'heading' => esc_html__('Dots container width', 'uncode') ,
			'param_name' => 'slider_dot_width',
			"value" => array(
				esc_html__('Full width', 'uncode') => '',
				esc_html__('Limit width', 'uncode') => 'limit',
			) ,
			"group" => esc_html__("Dots", 'uncode'),
			'description' => esc_html__('Specify the width of the dots container.', 'uncode') ,
			'dependency' => array(
				'element' => 'slider_dot_position',
				'value' => array('left', 'right'),
			) ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Dots container unit of measure", 'uncode') ,
			"param_name" => "column_width_use_pixel",
			"edit_field_class" => 'vc_col-sm-12 vc_column row_height',
			"description" => 'Set this value if you want to constrain the container width.',
			"value" => array(
				'' => 'yes'
			),
			"group" => esc_html__("Dots", 'uncode'),
			'dependency' => array(
				'element' => 'slider_dot_position',
				'value' => array('left', 'right'),
			)
		) ,
		array(
			"type" => "type_numeric_slider",
			"heading" => '',
			"param_name" => "slider_width_percent",
			"min" => 0,
			"max" => 100,
			"step" => 1,
			"value" => 100,
			"group" => esc_html__("Dots", 'uncode'),
			"description" => esc_html__("Set the container width with a percent value.", 'uncode') ,
			'dependency' => array(
				'element' => 'column_width_use_pixel',
				'is_empty' => true,
			)
		) ,
		array(
			'type' => 'textfield',
			'heading' => '',
			"group" => esc_html__("Dots", 'uncode'),
			'param_name' => 'silder_width_pixel',
			'description' => esc_html__("Insert the container width in pixel.", 'uncode') ,
			'dependency' => array(
				'element' => 'column_width_use_pixel',
				'not_empty' => true
			)
		) ,
		array(
			"type" => "type_numeric_slider",
			'heading' => esc_html__('Dots container padding', 'uncode') ,
			"description" => esc_html__("Activate this option to add left and right padding to dots container.", 'uncode') ,
			"param_name" => "h_padding",
			"min" => 0,
			"max" => 7,
			"step" => 1,
			"value" => 2,
			"group" => esc_html__("Dots", 'uncode'),
			'dependency' => array(
				'element' => 'slider_dot_position',
				'value' => array('left', 'right'),
			) ,
		) ,
      array(
        'type' => 'textfield',
        'heading' => esc_html__('Extra class name', 'uncode') ,
        'param_name' => 'el_class',
        'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'uncode')
      )
    ) ,
    'custom_markup' => '
	<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
		%content%
	</div>
	<div class="tab_controls vc_element-icon" style="width: 100%; margin-top: 20px;">
	    <a class="add_tab" title="' . esc_html__('Add slide', 'uncode') . '" style="color: white;"><i class="fa fa-plus2"></i> <span class="tab-label">' . esc_html__('Add slide', 'uncode') . '</span></a>
	</div>',
    'default_content' => '[vc_row_inner][vc_column_inner width="1/1"][/vc_column_inner][/vc_row_inner]',
    'js_view' => 'UncodeAccordionView'
));

/* Counter
 ---------------------------------------------------------- */
vc_map(array(
	'name' => esc_html__('Counter', 'uncode') ,
	'base' => 'uncode_counter',
	'weight' => 90,
	'icon' => 'fa fa-sort-numerically',
	'php_class_name' => 'uncode_counter',
	'category' => esc_html__('Content', 'uncode') ,
	'description' => esc_html__('Animated counter', 'uncode') ,
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => esc_html__('Counter value', 'uncode') ,
			'param_name' => 'value',
			'description' => esc_html__('Input counter value here.', 'uncode') ,
			'value' => '1000',
			'admin_label' => true
		) ,
		array(
			'type' => 'textfield',
			'heading' => esc_html__('Prefix', 'uncode') ,
			'param_name' => 'prefix',
			'description' => esc_html__('Input a prefix to the value.', 'uncode') ,
			'value' => ''
		) ,
		array(
			'type' => 'textfield',
			'heading' => esc_html__('Suffix', 'uncode') ,
			'param_name' => 'suffix',
			'description' => esc_html__('Input a suffix to the value.', 'uncode') ,
		) ,
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Counter color", 'uncode') ,
			"param_name" => "counter_color",
			"description" => esc_html__("Specify a color for the counter.", 'uncode') ,
			"value" => $uncode_colors,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Counter font size", 'uncode') ,
			"param_name" => "size",
			"description" => esc_html__("Specify the counter font dimension.", 'uncode') ,
			"value" => $heading_size,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Counter line height", 'uncode') ,
			"param_name" => "height",
			"description" => esc_html__("Specify the counter line height.", 'uncode') ,
			"value" => $heading_height,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Counter font family", 'uncode') ,
			"param_name" => "font",
			"description" => esc_html__("Specify the counter font family.", 'uncode') ,
			"value" => $heading_font,
			'std' => '',
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Counter font weight", 'uncode') ,
			"param_name" => "weight",
			"description" => esc_html__("Specify the counter font weight.", 'uncode') ,
			"value" =>$heading_weight,
			'std' => '',
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Counter text transform", 'uncode') ,
			"param_name" => "transform",
			"description" => esc_html__("Specify the counter text transformation.", 'uncode') ,
			"value" => array(
				esc_html__('Default CSS', 'uncode') => '',
				esc_html__('Uppercase', 'uncode') => 'uppercase',
				esc_html__('Lowercase', 'uncode') => 'lowercase',
				esc_html__('Capitalize', 'uncode') => 'capitalize'
			) ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Separator", 'uncode') ,
			"param_name" => "separator",
			"description" => esc_html__("Activate this to add a separator between the value and the description.", 'uncode') ,
			"value" => Array(
				esc_html__("Yes, please", 'uncode') => 'yes'
			) ,
		) ,
		array(
			'type' => 'textfield',
			'heading' => esc_html__('Text under', 'uncode') ,
			'param_name' => 'text',
			'description' => esc_html__('Input a text under the counter.', 'uncode') ,
			'value' => ''
		) ,
		$add_css_animation,
		$add_animation_speed,
		$add_animation_delay,
	) ,
));

/* Countdown
 ---------------------------------------------------------- */
vc_map(array(
	'name' => esc_html__('Countdown', 'uncode') ,
	'base' => 'uncode_countdown',
	'icon' => 'fa fa-clock-o',
	'weight' => 89,
	'php_class_name' => 'uncode_countdown',
	'category' => esc_html__('Content', 'uncode') ,
	'description' => esc_html__('Animated countdown', 'uncode') ,
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => esc_html__('Final date', 'uncode') ,
			'param_name' => 'date',
			'description' => esc_html__('Input the countdown date with this format YYYY/MM/DD. ex. 2020/05/20', 'uncode') ,
			'admin_label' => true
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Countdown font size", 'uncode') ,
			"param_name" => "size",
			"description" => esc_html__("Specify the countdown font size.", 'uncode') ,
			"value" => $heading_size,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Countdown line height", 'uncode') ,
			"param_name" => "height",
			"description" => esc_html__("Specify the countdown line height.", 'uncode') ,
			"value" => $heading_height,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Countdown font family", 'uncode') ,
			"param_name" => "font",
			"description" => esc_html__("Specify the countdown font family.", 'uncode') ,
			"value" => $heading_font,
			'std' => '',
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Countdown font weight", 'uncode') ,
			"param_name" => "weight",
			"description" => esc_html__("Specify the countdown font weight.", 'uncode') ,
			"value" =>$heading_weight,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Countdown uppercase", 'uncode') ,
			"param_name" => "transform",
			"description" => esc_html__("Specify the countdown text transformation.", 'uncode') ,
			"value" => array(
				esc_html__('Default CSS', 'uncode') => '',
				esc_html__('Uppercase', 'uncode') => 'uppercase',
				esc_html__('Lowercase', 'uncode') => 'lowercase',
				esc_html__('Capitalize', 'uncode') => 'capitalize'
			) ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Separator", 'uncode') ,
			"param_name" => "separator",
			"description" => esc_html__("Activate this to add a separator between the value and the description.", 'uncode') ,
			"value" => Array(
				esc_html__("Yes, please", 'uncode') => 'yes'
			) ,
		) ,
		array(
			'type' => 'textfield',
			'heading' => esc_html__('Text under', 'uncode') ,
			'param_name' => 'text',
			'description' => esc_html__('Input a text under the countdown.', 'uncode') ,
			'value' => ''
		) ,
		$add_css_animation,
		$add_animation_speed,
		$add_animation_delay,
	) ,
));

/* List
 ---------------------------------------------------------- */
vc_map(array(
	'name' => esc_html__('List', 'uncode') ,
	'base' => 'uncode_list',
	'weight' => 91,
	'icon' => 'fa fa-list-ol',
	'php_class_name' => 'uncode_list',
	'category' => esc_html__('Content', 'uncode') ,
	'description' => esc_html__('List with icon', 'uncode') ,
	'params' => array(
		array(
			'type' => 'textarea_html',
			'heading' => esc_html__('List text', 'uncode') ,
			'param_name' => 'content',
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Larger text", 'uncode') ,
			"param_name" => "larger",
			"description" => esc_html__("Activate this to have bigger text.", 'uncode') ,
			"value" => Array(
				esc_html__("Yes, please", 'uncode') => 'yes'
			) ,
		) ,
		array(
			'type' => 'iconpicker',
			'heading' => esc_html__('Icon', 'uncode') ,
			'param_name' => 'icon',
			'description' => esc_html__('Specify icon from library.', 'uncode') ,
			'value' => '',
			'admin_label' => true,
			'settings' => array(
				'emptyIcon' => true,
				'iconsPerPage' => 1100,
				'type' => 'uncode'
			) ,
		) ,
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Icon color", 'uncode') ,
			"param_name" => "icon_color",
			"description" => esc_html__("Specify a color for the icon.", 'uncode') ,
			"value" => $uncode_colors,
			'dependency' => array(
				'element' => 'icon',
				'not_empty' => true,
			) ,
		) ,
		$add_css_animation,
		$add_animation_speed,
		$add_animation_delay,
	) ,
));

/* Pricing table
 ---------------------------------------------------------- */
vc_map(array(
	'name' => esc_html__('Pricing table', 'uncode') ,
	'base' => 'uncode_pricing',
	'weight' => 92,
	'icon' => 'fa fa-list-alt',
	'php_class_name' => 'uncode_pricing',
	'category' => esc_html__('Content', 'uncode') ,
	'description' => esc_html__('Pricing table', 'uncode') ,
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => esc_html__('Title', 'uncode') ,
			'param_name' => 'title',
			'description' => esc_html__('Insert the price table title and separate with a pipe | if you want to have subtitle.', 'uncode') ,
			'value' => esc_html__('Title|Subtitle','uncode')
		) ,
		array(
			'type' => 'textfield',
			'heading' => esc_html__('Price', 'uncode') ,
			'param_name' => 'price',
			'description' => esc_html__('Insert the price and separate with a pipe | if you want to have subtitle.', 'uncode') ,
			'value' => esc_html__('$50|per month','uncode')
		) ,
		array(
			"type" => 'textarea_safe',
			"heading" => esc_html__("Body", 'uncode') ,
			"param_name" => "body",
			"description" => esc_html__("Insert body text line. Every new line is a block. If you separate with a pipe | the first part will be with bold style.", 'uncode') ,
		) ,
		array(
			'type' => 'vc_link',
			'heading' => esc_html__('Button', 'uncode') ,
			'param_name' => 'button',
			'description' => esc_html__('Insert a link if you want a button.', 'uncode') ,
		) ,
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Button hover effect", 'uncode') ,
			"param_name" => "hover_fx",
			"description" => esc_html__("Specify an effect on hover state.", 'uncode') ,
			"value" => array(
				'Inherit' => '',
				'Outlined' => 'outlined',
				'Flat' => 'full-colored',
			) ,
		) ,
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Block color", 'uncode') ,
			"param_name" => "price_color",
			"description" => esc_html__("Specify a color for the block.", 'uncode') ,
			"value" => $uncode_colors,
		) ,
		array(
			'type' => 'dropdown',
			'heading' => 'Colored elements',
			'param_name' => 'col_elements',
			'value' => array(
				esc_html__('Inside elements', 'uncode') => '',
				esc_html__('Top and bottom', 'uncode') => 'tb',
			) ,
			'description' => esc_html__('Specify how do you want to color the block.', 'uncode') ,
			'dependency' => array(
				'element' => 'price_color',
				'not_empty' => true,
			) ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Most popular", 'uncode') ,
			"param_name" => "most",
			"description" => esc_html__("Activate this to make the block to stick out, like featured.", 'uncode') ,
			"value" => Array(
				esc_html__("Yes, please", 'uncode') => 'yes'
			) ,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Alignment", 'uncode') ,
			"param_name" => "align",
			"description" => esc_html__("Specify the text aligment.", 'uncode') ,
			"value" => array(
				esc_html__('Center', 'uncode') => '',
				esc_html__('Left', 'uncode') => 'left',
			) ,
		) ,
		$add_css_animation,
		$add_animation_speed,
		$add_animation_delay,
	) ,
));

/* Share
 ---------------------------------------------------------- */
vc_map(array(
	'name' => esc_html__('Share', 'uncode') ,
	'base' => 'uncode_share',
	'weight' => 86,
	'icon' => 'fa fa-share',
	'php_class_name' => 'uncode_share',
	'category' => esc_html__('Content', 'uncode') ,
	'description' => esc_html__('Share buttons', 'uncode') ,
	'params' => array(
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Share layout", 'uncode') ,
			"param_name" => "layout",
			"description" => esc_html__("Specify the sharing area layout.", 'uncode') ,
			"value" => array(
				esc_html__('One popup button', 'uncode') => '',
				esc_html__('Social buttons', 'uncode') => 'multiple',
			) ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Bigger icons", 'uncode') ,
			"param_name" => "bigger",
			"description" => esc_html__("Activate this to have bigger icons.", 'uncode') ,
			"value" => Array(
				esc_html__("Yes, please", 'uncode') => 'yes'
			) ,
			'dependency' => array(
				'element' => 'layout',
				'value' => 'multiple',
			) ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("No background", 'uncode') ,
			"param_name" => "no_back",
			"description" => esc_html__("Activate this to remove the background hover effect.", 'uncode') ,
			"value" => Array(
				esc_html__("Yes, please", 'uncode') => 'yes'
			) ,
			'dependency' => array(
				'element' => 'layout',
				'value' => 'multiple',
			) ,
		) ,
		array(
			'type' => 'textfield',
			'heading' => esc_html__('Label', 'uncode') ,
			'param_name' => 'title',
			'description' => esc_html__('Insert the label for the share module.', 'uncode') ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Separator", 'uncode') ,
			"param_name" => "separator",
			"description" => esc_html__("Activate this to add a separator between the value and the description.", 'uncode') ,
			"value" => Array(
				esc_html__("Yes, please", 'uncode') => 'yes'
			) ,
			'dependency' => array(
				'element' => 'layout',
				'value' => 'multiple',
			) ,
		) ,
		$add_css_animation,
		$add_animation_speed,
		$add_animation_delay,
	) ,
));

/* Twenty Twenty
 ---------------------------------------------------------- */
vc_map(array(
	'base' => 'uncode_twentytwenty',
	'name' => esc_html__('Before and After', 'uncode') ,
	'icon' => 'fa fa-adjust',
	'php_class_name' => 'uncode_twentytwenty',
	'weight' => 30,
	'category' => esc_html__('Content', 'uncode') ,
	'description' => esc_html__('Show before-and-after pictures', 'uncode') ,
	'params' => array(
		array(
			"type" => "media_element",
			"heading" => esc_html__("Media before", 'uncode') ,
			"param_name" => "media_before",
			"value" => "",
			"description" => esc_html__("Specify a media from the media library.", 'uncode') ,
			"admin_label" => true
		) ,
		array(
			"type" => "media_element",
			"heading" => esc_html__("Media after", 'uncode') ,
			"param_name" => "media_after",
			"value" => "",
			"description" => esc_html__("Specify a media from the media library.", 'uncode') ,
			"admin_label" => true
		) ,
		array(
      'type' => 'textfield',
      'heading' => esc_html__('Extra class name', 'uncode') ,
      'param_name' => 'el_class',
      'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'uncode')
    ) ,
	)
));

/* Author Profile
 ---------------------------------------------------------- */
$user_list_original = uncode_transient_users();
$user_list = array(
	esc_html__('Default user', 'uncode') => ''
);
foreach ($user_list_original as $user) {
	$count_user_post = count_user_posts( $user->ID );
	if ( $count_user_post > 0 || apply_filters( 'uncode_author_profile_no_post', false ) ) //user is author too or it doesn't matter
		$user_list[$user->display_name] = $user->ID;
}

$size_arr = array(
	esc_html__('Standard', 'uncode') => '',
	esc_html__('Small', 'uncode') => 'btn-sm',
	esc_html__('Large', 'uncode') => 'btn-lg',
	esc_html__('Extra-Large', 'uncode') => 'btn-xl',
	esc_html__('Button link', 'uncode') => 'btn-link',
	esc_html__('Standard link', 'uncode') => 'link',
);

vc_map(array(
	'base' => 'uncode_author_profile',
	'name' => esc_html__('Author Profile', 'uncode') ,
	'icon' => 'fa fa-user',
	'php_class_name' => 'uncode_author_profile',
	'weight' => 30,
	'category' => esc_html__('Content', 'uncode') ,
	'description' => esc_html__('Display author profile and info', 'uncode') ,
	'params' => array(

		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Select user", 'uncode') ,
			"param_name" => "user_id",
			"admin_label" => true,
			"description" => esc_html__("Select an option if you want to display a different user than the author.", 'uncode') ,
			"value" => $user_list ,
		) ,
		array(
			'type' => 'dropdown',
			'heading' => esc_html__('Display avatar', 'uncode') ,
			'param_name' => 'avatar',
			'description' => esc_html__("Specify whether to show the avatar or not.", 'uncode') ,
			"value" => array(
				esc_html__('Use global recognized avatar (Gravatar)', 'uncode') => 'gravatar',
				esc_html__('Use custom avatar', 'uncode') => 'custom',
				esc_html__('Do not display any avatar', 'uncode') => 'hide',
			) ,
		) ,
		array(
			"type" => "media_element",
			"heading" => esc_html__("Custom avatar", 'uncode') ,
			"param_name" => "custom_avatar",
			"value" => "",
			"description" => esc_html__("Specify an image from the media library.", 'uncode') ,
			'dependency' => array(
				'element' => 'avatar',
				'value' => array(
					'custom',
				) ,
			) ,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Avatar position", 'uncode') ,
			"param_name" => "avatar_position",
			"description" => esc_html__("Specify where to position the author avatar with respect to the text.", 'uncode') ,
			"value" => array(
				esc_html__('Left', 'uncode') => 'left',
				esc_html__('Top', 'uncode') => 'top',
				esc_html__('Right', 'uncode') => 'right',
			) ,
			'dependency' => array(
				'element' => 'avatar',
				'value' => array(
					'gravatar',
					'custom',
				) ,
			) ,
		) ,
		array(
			"type" => 'textfield',
			"heading" => esc_html__("Avatar size", 'uncode') ,
			"param_name" => "avatar_size",
			"description" => esc_html__("Intended in pixels. Enter an integer number.", 'uncode') ,
			"value" => "100",
			'dependency' => array(
				'element' => 'avatar',
				'value' => array(
					'gravatar',
					'custom',
				) ,
			) ,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Avatar style", 'uncode') ,
			"param_name" => "avatar_style",
			"description" => esc_html__("Select the look of the avatar image.", 'uncode') ,
			"value" => array(
				esc_html__('Square', 'uncode') => '',
				esc_html__('Circular', 'uncode') => 'img-circle',
				esc_html__('Rounded', 'uncode') => 'img-round',
			),
			"std" => "img-circle" ,
			'dependency' => array(
				'element' => 'avatar',
				'value' => array(
					'gravatar',
					'custom',
				) ,
			) ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Avatar border", 'uncode') ,
			"param_name" => "avatar_border",
			"description" => esc_html__("Specify whether to display a solid border around the avatar image or not.", 'uncode') ,
			'value' => array(
				esc_html__('Yes, please', 'uncode') => 'yes'
			) ,
			'dependency' => array(
				'element' => 'avatar',
				'value' => array(
					'gravatar',
					'custom',
				) ,
			) ,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Avatar Border skin", 'uncode') ,
			"param_name" => "avatar_skin",
			"description" => esc_html__("Specify the skin of the avatar box.", 'uncode') ,
			"value" => array(
				esc_html__('Light', 'uncode') => 'light',
				esc_html__('Dark', 'uncode') => 'dark'
			) ,
			'dependency' => array(
				'element' => 'avatar_border',
				'not_empty' => true,
			) ,
		) ,
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Avatar Background color", 'uncode') ,
			"param_name" => "avatar_back_color",
			"description" => esc_html__("Specify a background color for the box.", 'uncode') ,
			"value" => $uncode_colors,
			'dependency' => array(
				'element' => 'avatar_border',
				'not_empty' => true,
			) ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Author Name Link", 'uncode') ,
			"param_name" => "author_name_linked",
			"description" => esc_html__("Link the author name to the author post page.", 'uncode') ,
			'value' => array(
				esc_html__('Yes, please', 'uncode') => 'yes'
			) ,
			"std" => "yes" ,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Element semantic", 'uncode') ,
			"param_name" => "heading_semantic",
			"description" => esc_html__("Specify element tag.", 'uncode') ,
			"value" => $heading_semantic,
			'std' => 'h2',
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Text size", 'uncode') ,
			"param_name" => "text_size",
			"description" => esc_html__("Specify text size.", 'uncode') ,
			'std' => 'h2',
			"value" => $heading_size,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Line height", 'uncode') ,
			"param_name" => "text_height",
			"description" => esc_html__("Specify text line height.", 'uncode') ,
			"value" => $heading_height,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Letter spacing", 'uncode') ,
			"param_name" => "text_space",
			"description" => esc_html__("Specify letter spacing.", 'uncode') ,
			"value" => $heading_space,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Font family", 'uncode') ,
			"param_name" => "text_font",
			"description" => esc_html__("Specify text font family.", 'uncode') ,
			"value" => $heading_font,
			'std' => '',
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Font weight", 'uncode') ,
			"param_name" => "text_weight",
			"description" => esc_html__("Specify text weight.", 'uncode') ,
			"value" => $heading_weight,
			'std' => '',
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Text transform", 'uncode') ,
			"param_name" => "text_transform",
			"description" => esc_html__("Specify the author name text transformation.", 'uncode') ,
			"value" => array(
				esc_html__('Default CSS', 'uncode') => '',
				esc_html__('Uppercase', 'uncode') => 'uppercase',
				esc_html__('Lowercase', 'uncode') => 'lowercase',
				esc_html__('Capitalize', 'uncode') => 'capitalize'
			) ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Text italic", 'uncode') ,
			"param_name" => "text_italic",
			"description" => esc_html__("Transform the author name to italic.", 'uncode') ,
			"value" => Array(
				'' => 'yes'
			) ,
		) ,
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Text color", 'uncode') ,
			"param_name" => "text_color",
			"description" => esc_html__("Specify text color.", 'uncode') ,
			"value" => $uncode_colors,
		) ,
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Separator", 'uncode') ,
			"param_name" => "separator",
			"description" => esc_html__("Activate the separator. This will appear under the text.", 'uncode') ,
			"value" => array(
				esc_html__('None', 'uncode') => '',
				esc_html__('Under author name', 'uncode') => 'yes',
				esc_html__('Under author bio', 'uncode') => 'under',
				esc_html__('Over author name', 'uncode') => 'over'
			) ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Separator double space", 'uncode') ,
			"param_name" => "separator_double",
			"description" => esc_html__("Activate to increase the separator space.", 'uncode') ,
			"value" => Array(
				'' => 'yes'
			) ,
			'dependency' => array(
				'element' => 'separator',
				'not_empty' => true,
			) ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Display author bio", 'uncode') ,
			"param_name" => "author_bio",
			"description" => esc_html__("Activate to display author bio text.", 'uncode') ,
			"value" => Array(
				'' => 'yes'
			) ,
			"std" => 'yes',
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Text large", 'uncode') ,
			"param_name" => "sub_lead",
			"description" => esc_html__("Activate if you want to enlarge the font size.", 'uncode') ,
			"value" => Array(
				'' => 'yes'
			) ,
			'dependency' => array(
				'element' => 'author_bio',
				'not_empty' => true,
			) ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Reduce author bio top space", 'uncode') ,
			"param_name" => "sub_reduced",
			"description" => esc_html__("Activate this to reduce the author bio top margin.", 'uncode') ,
			"value" => Array(
				esc_html__("Yes, please", 'uncode') => 'yes'
			) ,
			'dependency' => array(
				'element' => 'author_bio',
				'not_empty' => true,
			) ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Display social and contact method icons", 'uncode') ,
			"param_name" => "social",
			"description" => esc_html__("Specify whether to display the list of author's social profiles and external urls.", 'uncode') ,
			"value" => Array(
				esc_html__("Yes, please", 'uncode') => 'yes'
			) ,
			"std" => "yes" ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Display button", 'uncode') ,
			"param_name" => "display_button",
			"description" => esc_html__("Use a button to redirect users to the author post page or a custom link.", 'uncode') ,
			"value" => Array(
				esc_html__("Yes, please", 'uncode') => 'yes'
			) ,
		) ,
		array(
			'type' => 'textfield',
			'heading' => esc_html__('Text', 'uncode') ,
			'param_name' => 'button_content',
			'value' => esc_html__('All author posts', 'uncode') ,
			'description' => esc_html__('Text on the button.', 'uncode') ,
			"group" => esc_html__("Button", 'uncode'),
			'dependency' => array(
				'element' => 'display_button',
				'not_empty' => true,
			) ,
		) ,
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Button link", 'uncode') ,
			"param_name" => "button_link",
			"description" => esc_html__("Specify whether to link the button to the author post page or a different link.", 'uncode') ,
			"value" => array(
				esc_html__('Link to the author post page', 'uncode') => '',
				esc_html__('Custom link', 'uncode') => 'custom',
			) ,
			"group" => esc_html__("Button", 'uncode') ,
			'dependency' => array(
				'element' => 'display_button',
				'not_empty' => true,
			) ,
		) ,
		array(
			'type' => 'vc_link',
			'heading' => esc_html__('URL (Link)', 'uncode') ,
			'param_name' => 'link',
			'description' => esc_html__('Button link.', 'uncode') ,
			'dependency' => array(
				'element' => 'button_link',
				'value' => 'custom',
			) ,
			"group" => esc_html__("Button", 'uncode') ,
		) ,
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Button color", 'uncode') ,
			"param_name" => "button_color",
			"description" => esc_html__("Specify button color.", 'uncode') ,
			"value" => $uncode_colors ,
			"group" => esc_html__("Button", 'uncode') ,
			'dependency' => array(
				'element' => 'display_button',
				'not_empty' => true,
			) ,
		) ,
		array(
			'type' => 'dropdown',
			'heading' => esc_html__('Size', 'uncode') ,
			'param_name' => 'size',
			'value' => $size_arr,
			'description' => esc_html__('Button size.', 'uncode') ,
			"group" => esc_html__("Button", 'uncode') ,
			'dependency' => array(
				'element' => 'display_button',
				'not_empty' => true,
			) ,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Shape", 'uncode') ,
			"param_name" => "radius",
			"description" => esc_html__("You can shape the button with the corners round, squared or circle.", 'uncode') ,
			"value" => array(
				esc_html__('Default', 'uncode') => '',
				esc_html__('Round', 'uncode') => 'btn-round',
				esc_html__('Circle', 'uncode') => 'btn-circle',
				esc_html__('Square', 'uncode') => 'btn-square'
			) ,
			"group" => esc_html__("Button", 'uncode'),
			'dependency' => array(
				'element' => 'display_button',
				'not_empty' => true,
			) ,
		) ,
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Hover effect", 'uncode') ,
			"param_name" => "hover_fx",
			"description" => esc_html__("Specify an effect on hover state.", 'uncode') ,
			"value" => array(
				'Inherit' => '',
				'Outlined' => 'outlined',
				'Flat' => 'full-colored',
			) ,
			"group" => esc_html__("Button", 'uncode') ,
			'dependency' => array(
				'element' => 'display_button',
				'not_empty' => true,
			) ,
		) ,
		array(
			'type' => 'checkbox',
			'heading' => esc_html__('Outlined inverse', 'uncode') ,
			'param_name' => 'outline',
			'description' => esc_html__("Outlined buttons don't have a full background color. NB: this option is available only with Hover Effect > Outlined.", 'uncode') ,
			'value' => array(
				esc_html__('Yes, please', 'uncode') => 'yes'
			),
			"group" => esc_html__("Button", 'uncode') ,
			'dependency' => array(
				'element' => 'display_button',
				'not_empty' => true,
			) ,
		) ,
		array(
			'type' => 'checkbox',
			'heading' => esc_html__('Skin text', 'uncode') ,
			'param_name' => 'text_skin',
			'description' => esc_html__("Keep the text color as the skin. NB: this option works well with Hover Effect > Outlined.", 'uncode') ,
			'value' => array(
				esc_html__('Yes, please', 'uncode') => 'yes'
			),
			"group" => esc_html__("Button", 'uncode') ,
			'dependency' => array(
				'element' => 'display_button',
				'not_empty' => true,
			) ,
		) ,
		array(
			'type' => 'checkbox',
			'heading' => esc_html__('Outlined', 'uncode') ,
			'param_name' => 'outline',
			'description' => esc_html__("Outlined buttons doesn't have a full background color.", 'uncode') ,
			'value' => array(
				esc_html__('Yes, please', 'uncode') => 'yes'
			) ,
			"group" => esc_html__("Button", 'uncode') ,
			'dependency' => array(
				'element' => 'display_button',
				'not_empty' => true,
			) ,
		) ,
		array(
			'type' => 'iconpicker',
			'heading' => esc_html__('Icon', 'uncode') ,
			'param_name' => 'icon',
			'description' => esc_html__('Specify icon from library.', 'uncode') ,
			'settings' => array(
				'emptyIcon' => true,
				 // default true, display an "EMPTY" icon?
				'iconsPerPage' => 1100,
				 // default 100, how many icons per/page to display
				'type' => 'uncode'
			) ,
			"group" => esc_html__("Button", 'uncode') ,
			'dependency' => array(
				'element' => 'display_button',
				'not_empty' => true,
			) ,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Icon position", 'uncode') ,
			"param_name" => "icon_position",
			"description" => esc_html__("Choose the position of the icon.", 'uncode') ,
			"value" => array(
				esc_html__('Left', 'uncode') => 'left',
				esc_html__('Right', 'uncode') => 'right',
			) ,
			'dependency' => array(
				'element' => 'icon',
				'not_empty' => true,
			) ,
			"group" => esc_html__("Button", 'uncode') ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Desktop", 'uncode') ,
			"param_name" => "desktop_visibility",
			"description" => esc_html__("Choose the visibiliy of the element in desktop layout mode (960px >).", 'uncode') ,
			'group' => esc_html__('Responsive', 'uncode') ,
			"value" => Array(
				'' => 'yes'
			) ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Tablet", 'uncode') ,
			"param_name" => "medium_visibility",
			"description" => esc_html__("Choose the visibiliy of the element in tablet layout mode (570px > < 960px).", 'uncode') ,
			'group' => esc_html__('Responsive', 'uncode') ,
			"value" => Array(
				'' => 'yes'
			) ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Mobile", 'uncode') ,
			"param_name" => "mobile_visibility",
			"description" => esc_html__("Choose the visibiliy of the element in mobile layout mode (< 570px).", 'uncode') ,
			'group' => esc_html__('Responsive', 'uncode') ,
			"value" => Array(
				'' => 'yes'
			) ,
		) ,
		$add_css_animation,
		$add_animation_speed,
		$add_animation_delay,
		array(
			'type' => 'textfield',
			'heading' => esc_html__('Extra class name', 'uncode') ,
			'param_name' => 'el_class',
			'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'uncode') ,
			'group' => esc_html__('Extra', 'uncode')
		) ,
	)
));

/* Consent Notice
 ---------------------------------------------------------- */
if ( class_exists( 'Uncode_Toolkit_Privacy' ) ) :
vc_map(array(
	'name' => esc_html__('Consent Notice', 'uncode') ,
	'base' => 'uncode_consent_notice',
	'weight' => 10,
	'icon' => 'fa fa-exclamation-circle',
	'wrapper_class' => 'clearfix',
	'php_class_name' => 'uncode_generic_admin',
	'category' => esc_html__('Content', 'uncode') ,
	'description' => esc_html__('Consent Fallback Notice Text', 'uncode') ,
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => esc_html__('Consent Notice Color', 'uncode') ,
			'param_name' => 'notice_color',
			'admin_label' => true,
			'value' => $uncode_colors,
			'description' => esc_html__('Specify consent notice color.', 'uncode') ,
		) ,
		$add_css_animation,
		$add_animation_speed,
		$add_animation_delay,
		array(
			'type' => 'textfield',
			'heading' => esc_html__('Extra class name', 'uncode') ,
			'param_name' => 'el_class',
			'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'uncode')
		)
	) ,
));
endif;

/* Socials
 ---------------------------------------------------------- */
vc_map( array(
	'base' => 'uncode_socials',
	'name' => esc_html__('Social Icons', 'uncode') ,
	'icon' => 'fa fa-twitter',
	'php_class_name' => 'uncode_socials',
	'weight' => 97,
	'category' => esc_html__('Content', 'uncode') ,
	'description' => esc_html__('Place Social Icons', 'uncode') ,
	'params' => array(
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Icon size", 'uncode') ,
			"param_name" => "size",
			"description" => esc_html__("Specify the icon dimension.", 'uncode') ,
			"value" => array(
				esc_html__('Default', 'uncode') => '',
				esc_html__('Large', 'uncode') => 'lead',
			) ,
		) ,
		array(
			'type' => 'textfield',
			'heading' => esc_html__('Extra class name', 'uncode') ,
			'param_name' => 'el_class',
			'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'uncode') ,
		) ,
		$add_css_animation,
		$add_animation_speed,
		$add_animation_delay,
	)
) );

/* Copyright
 ---------------------------------------------------------- */
vc_map( array(
	'base' => 'uncode_copyright',
	'name' => esc_html__('Copyright', 'uncode') ,
	'icon' => 'fa fa-copyright',
	'php_class_name' => 'uncode_socials',
	'weight' => 97,
	'category' => esc_html__('Content', 'uncode') ,
	'description' => esc_html__('Place Automatic Copyright Text', 'uncode') ,
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => esc_html__('Extra class name', 'uncode') ,
			'param_name' => 'el_class',
			'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'uncode') ,
		) ,
		$add_css_animation,
		$add_animation_speed,
		$add_animation_delay,
	)
) );

/* Content Block
 ---------------------------------------------------------- */
if (function_exists('uncode_get_current_post_type')) {
	$current_post_type = uncode_get_current_post_type();
	if ($current_post_type !== 'uncodeblock') {
		$cblock = get_posts( 'post_type="uncodeblock"&numberposts=-1&suppress_filters=0' );

		$conten_blocks = array();
		if ( $cblock ) {
			foreach ( $cblock as $cform ) {
				$conten_blocks[ $cform->post_title ] = $cform->ID;
			}
		} else {
			$conten_blocks[ __( 'No Content Block found', 'uncode' ) ] = 0;
		}
		vc_map( array(
			'base' => 'uncode_block',
			'php_class_name' => 'uncode_block',
			'weight' => 97,
			'name' => __( 'Content Block VC', 'uncode' ),
			'icon' => 'dashicons-before dashicons-tagcloud',
			'category' => __( 'Content', 'uncode' ),
			'description' => __( 'Place Content Block', 'uncode' ),
			'params' => array(
				array(
					'type' => 'dropdown',
					'heading' => __( 'Content Block', 'uncode' ),
					'param_name' => 'id',
					'value' => $conten_blocks,
					'admin_label' => true,
					'save_always' => true,
					'description' => __( 'Choose previously created Content Block from the drop down list.', 'uncode' ),
				),
				array(
					"type" => 'checkbox',
					"heading" => esc_html__("Column container settings", 'uncode') ,
					"param_name" => "inside_column",
					"description" => sprintf(esc_html__("Activate this to use the Content Block inside a column.%sNB. When using this option nested row (row child) inside the Content Block will not work.", 'uncode'), '<br /><br />') ,
					"value" => Array(
						esc_html__("Yes, please", 'uncode') => 'yes'
					) ,
				) ,
			),
			'js_view' => 'UncodeBlockView'
		) );
	}
}

/* Info Box
 ---------------------------------------------------------- */
$info_heading_size = $heading_size;
unset($info_heading_size['BigText']);
vc_map( array(
	'base' => 'uncode_info_box',
	'name' => esc_html__('Info Box', 'uncode') ,
	'icon' => 'fa fa-info-circle',
	'php_class_name' => 'uncode_info_box',
	'weight' => 97,
	'category' => esc_html__('Content', 'uncode') ,
	'description' => esc_html__('Place Info Box', 'uncode') ,
	'params' => array(
		array(
			'type' => 'sorted_list',
			'heading' => esc_html__('Elements', 'uncode') ,
			'param_name' => 'items',
			'description' => esc_html__('Enable or disable elements and place them in desired order.', 'uncode') ,
			'admin_label' => true,
			'value' => 'Date,Categories,Author',
			'group' => esc_html__('Elements', 'uncode') ,
			'options' => array(
				array(
					'Date',
					esc_html__('Date', 'uncode') ,
				) ,
				array(
					'Categories',
					esc_html__('Categories', 'uncode') ,
					array(
						array(
							'',
							esc_html__('Display prefix', 'uncode')
						) ,
						array(
							'do_not_display_prefix',
							esc_html__('Do not display prefix', 'uncode')
						) ,
					) ,
				) ,
				array(
					'Author',
					esc_html__('Author', 'uncode') ,
					array(
						array(
							'no_avatar',
							esc_html__('No avatar', 'uncode'),
						) ,
						array(
							'Small_avatar_size',
							esc_html__('Small avatar', 'uncode'),
						) ,
						array(
							'Medium_avatar_size',
							esc_html__('Medium avatar', 'uncode'),
						),
						array(
							'Large_avatar_size',
							esc_html__('Large avatar', 'uncode'),
						),
						array(
							'Extra_avatar_size',
							esc_html__('Extra large avatar', 'uncode')
						)
					),
					array(
						array(
							'inline_avatar',
							esc_html__('Inline avatar', 'uncode'),
						) ,
						array(
							'top_avatar',
							esc_html__('Top avatar', 'uncode'),
						) ,
					),
					array(
						array(
							'display_prefix',
							esc_html__('Display prefix', 'uncode')
						) ,
						array(
							'do_not_display_prefix',
							esc_html__('Do not display prefix', 'uncode')
						) ,
					),
				),
				array(
					'Comments',
					esc_html__('Comments', 'uncode') ,
				) ,
				array(
					'Reading_time',
					esc_html__('Reading time', 'uncode') ,
				) ,
			)
		),
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Text size", 'uncode') ,
			"param_name" => "text_size",
			"description" => esc_html__("Specify text size.", 'uncode') ,
			'std' => '',
			'group' => esc_html__('Style', 'uncode') ,
			"value" => $info_heading_size,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Line height", 'uncode') ,
			"param_name" => "text_height",
			"description" => esc_html__("Specify text line height.", 'uncode') ,
			'group' => esc_html__('Style', 'uncode') ,
			"value" => $heading_height,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Letter spacing", 'uncode') ,
			"param_name" => "text_space",
			"description" => esc_html__("Specify letter spacing.", 'uncode') ,
			'group' => esc_html__('Style', 'uncode') ,
			"value" => $heading_space,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Font family", 'uncode') ,
			"param_name" => "text_font",
			"description" => esc_html__("Specify text font family.", 'uncode') ,
			'group' => esc_html__('Style', 'uncode') ,
			"value" => $heading_font,
			'std' => '',
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Font weight", 'uncode') ,
			"param_name" => "text_weight",
			"description" => esc_html__("Specify text weight.", 'uncode') ,
			'group' => esc_html__('Style', 'uncode') ,
			"value" => $heading_weight,
			'std' => '',
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Text transform", 'uncode') ,
			"param_name" => "text_transform",
			"description" => esc_html__("Specify the text transformation.", 'uncode') ,
			'group' => esc_html__('Style', 'uncode') ,
			"value" => array(
				esc_html__('Default CSS', 'uncode') => '',
				esc_html__('Uppercase', 'uncode') => 'uppercase',
				esc_html__('Lowercase', 'uncode') => 'lowercase',
				esc_html__('Capitalize', 'uncode') => 'capitalize'
			) ,
		) ,
		array(
			"type" => 'dropdown',
			"heading" => esc_html__("Separators", 'uncode') ,
			"param_name" => "separator",
			"description" => esc_html__("Specify a separator.", 'uncode') ,
			'group' => esc_html__('Style', 'uncode') ,
			"value" => array(
				esc_html__('None', 'uncode') => '',
				esc_html__('| - Vertical bar', 'uncode') => 'pipe',
				esc_html__('• - Bullet', 'uncode') => 'bullet',
			) ,
		) ,
		$add_css_animation,
		$add_animation_speed,
		$add_animation_delay,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Desktop", 'uncode') ,
			"param_name" => "desktop_visibility",
			"description" => esc_html__("Choose the visibiliy of the element in desktop layout mode (960px >).", 'uncode') ,
			'group' => esc_html__('Responsive', 'uncode') ,
			"value" => Array(
				'' => 'yes'
			) ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Tablet", 'uncode') ,
			"param_name" => "medium_visibility",
			"description" => esc_html__("Choose the visibiliy of the element in tablet layout mode (570px > < 960px).", 'uncode') ,
			'group' => esc_html__('Responsive', 'uncode') ,
			"value" => Array(
				'' => 'yes'
			) ,
		) ,
		array(
			"type" => 'checkbox',
			"heading" => esc_html__("Mobile", 'uncode') ,
			"param_name" => "mobile_visibility",
			"description" => esc_html__("Choose the visibiliy of the element in mobile layout mode (< 570px).", 'uncode') ,
			'group' => esc_html__('Responsive', 'uncode') ,
			"value" => Array(
				'' => 'yes'
			) ,
		) ,
	)
) );

