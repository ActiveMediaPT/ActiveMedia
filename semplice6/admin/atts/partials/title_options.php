<?php

// -----------------------------------------
// semplice
// admin/atts/partials/title_options.php
// -----------------------------------------

// style classes
$style_classes = array(
	'hor_full_' 	=> 'ov-apg-horizontal-fullscreen',
	'text_'    		=> 'ov-apg-text',
	'splitscreen_' 	=> 'ov-apg-splitscreen'
);

// get style class
$style_class = false;
if(false !== $args['prefix']) {
	$style_class = $style_classes[$args['prefix']] . ' ep-collapsed';
}

// visibility options
$visibility_options = array(
	'both' 			=> 'Show both title and project type',
	'title'			=> 'Show only title',
	'category'		=> 'Show only project type',
	'hidden'		=> 'Hide Both',
);

// title position default
$title_position_default = 'bottom-center';

// type padding top
$type_padding_top = 10;

// adjust settings for splitscreen
if($args['prefix'] == 'splitscreen_') {
	$type_padding_top = 36;
	$title_position_default = 'middle-left';
	$visibility_options = array(
		'both' 			=> 'Show both title and description',
		'title'			=> 'Show only title',
		'description'	=> 'Show only description',
	);
}

// return options
if($args['options'] == 'title-options') {
	$options = array(
		'title'  	 => 'Title Options',
		'break'		 => '2,2',
		'style-class'=> $style_class,
		'data-hide-mobile' => true,
		$args['prefix'] . 'title_visibility' => array(
			'data-input-type' 	=> 'select-box',
			'title'		 		=> 'Visibility',
			'size'		 		=> 'span2',
			'class'				=> 'editor-listen',
			'data-handler'		=> 'apg',
			'default' 	 		=> 'both',
			'select-box-values' => $visibility_options,
		),
		$args['prefix'] . 'title_position' => array(
			'data-input-type' 	=> 'select-box',
			'title'		 		=> 'Position',
			'size'		 		=> 'span2',
			'class'				=> 'editor-listen',
			'data-handler'		=> 'apg',
			'default' 	 		=> $title_position_default,
			'select-box-values' => array(
				'top-left'      => 'Top Left',
				'top-center'	=> 'Top Center',
				'top-right'		=> 'Top Right',
				'middle-left'   => 'Middle Left',
				'middle-center'	=> 'Middle Center',
				'middle-right'	=> 'Middle Right',
				'bottom-left'   => 'Bottom Left',
				'bottom-center'	=> 'Bottom Center',
				'bottom-right'	=> 'Bottom Right',
			),
		),
		$args['prefix'] . 'title_padding' => array(
			'title'			=> 'Padding',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' => 'range-slider',
			'default'		=> 72,
			'min'			=> 0,
			'max'			=> 999,
			'class'				=> 'editor-listen',
			'data-handler'		=> 'apg',
			'data-range-slider' => 'apg',
			'data-style-option' => true,
			'data-has-unit'	=> true,
			'data-css-attribute'=> 'padding',
			'data-target'   => '.apg-post-title',
		),
		$args['prefix'] . 'title_offset' => array(
			'title'			=> 'Vertical Offset',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' => 'range-slider',
			'default'		=> 0,
			'min'			=> 0,
			'max'			=> 999,
			'class'				=> 'editor-listen',
			'data-handler'		=> 'apg',
			'data-range-slider' => 'apg',
			'data-style-option' => true,
			'data-has-unit'	=> true,
			'data-target'   => '.apg-post-title',
			'help'			=> 'Define the vertical offset of your title. This is an addition to the padding, as you may want a small horizontal padding but don’t want your title to stick to the top or bottom edge.',
		),
	);
} else if($args['options'] == 'title-options-text') {
	$options = array(
		'title'  	 => 'Title Options',
		'break'		 => '2,2',
		'style-class'=> $style_class,
		'data-hide-mobile' => true,
		$args['prefix'] . 'title_direction' => array(
			'data-input-type' 	=> 'switch',
			'switch-type'		=> 'twoway',
			'title'		 		=> 'Direction',
			'size'		 		=> 'span2',
			'class'				=> 'editor-listen',
			'data-handler'		=> 'apg',
			'default' 	 		=> 'column-dir',
			'switch-values' => array(
				'column-dir'	=> 'Vert',
				'row-dir'		=> 'Hor',
			),
		),
		$args['prefix'] . 'title_position' => array(
			'data-input-type' 	=> 'select-box',
			'title'		 		=> 'Position',
			'size'		 		=> 'span2',
			'class'				=> 'editor-listen',
			'data-handler'		=> 'apg',
			'default' 	 		=> 'middle-center',
			'select-box-values' => array(
				'top-left'      => 'Top Left',
				'top-center'	=> 'Top Center',
				'top-right'		=> 'Top Right',
				'middle-left'   => 'Middle Left',
				'middle-center'	=> 'Middle Center',
				'middle-right'	=> 'Middle Right',
				'bottom-left'   => 'Bottom Left',
				'bottom-center'	=> 'Bottom Center',
				'bottom-right'	=> 'Bottom Right',
			),
		),
		$args['prefix'] . 'title_padding' => array(
			'title'			=> 'Outer Padding',
			'help'			=> 'This setting has no effect if title position is set to \'Middle Center\'.',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' => 'range-slider',
			'default'		=> 72,
			'min'			=> 0,
			'max'			=> 999,
			'class'				=> 'editor-listen',
			'data-handler'		=> 'apg',
			'data-range-slider' => 'apg',
			'data-style-option' => true,
			'data-has-unit'	=> true,
			'data-css-attribute'=> 'padding',
			'data-target'   => '.apg',
		),
		$args['prefix'] . 'title_item_padding_ver' => array(
			'title'			=> 'Item Padding Vert',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' => 'range-slider',
			'default'		=> 10,
			'min'			=> 0,
			'max'			=> 999,
			'class'				=> 'editor-listen',
			'data-handler'		=> 'apg',
			'data-range-slider' => 'apg',
			'data-style-option' => true,
			'data-has-unit'	=> true,
			'data-target'   => '.apg',
		),
		$args['prefix'] . 'title_item_padding_hor' => array(
			'title'			=> 'Item Padding Hor',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' => 'range-slider',
			'default'		=> 18,
			'min'			=> 0,
			'max'			=> 999,
			'class'				=> 'editor-listen',
			'data-handler'		=> 'apg',
			'data-range-slider' => 'apg',
			'data-style-option' => true,
			'data-has-unit'	=> true,
			'data-target'   => '.apg',
		),
	);
} else if($args['options'] == 'title-formatting') {
	$options = array(
		'title'  	 => 'Title Formatting',
		'break'		 => '2,2,2',
		'style-class'=> $style_class,
		'data-hide-mobile' => true,
		$args['prefix'] . 'title_color' => array(
			'title'				=> 'Color',
			'data-style-option' => true,
			'size'				=> 'span2',
			'data-input-type'	=> 'color',
			'data-target'		=> '.spacer',
			'default'			=> 'transparent',
			'class'				=> 'color-picker admin-listen-handler',
			'data-handler' 		=> 'colorPicker',
			'data-picker'		=> 'apg',
			'data-target'		=> '.apg-post-title .title',
			'data-css-attribute'=> 'color',
		),
		$args['prefix'] . 'title_font_family' => array(
			'data-input-type' => 'select-fonts',
			'title'		 		=> 'Font Family',
			'size'		 		=> 'span2',
			'class'				=> 'editor-listen',
			'data-handler'		=> 'apg',
			'default' 	 		=> 'bold',
			'select-box-values' => $this->fonts,
		),
		$args['prefix'] . 'title_fontsize' => array(
			'title'			=> 'Font Size',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' 	=> 'range-slider',
			'default'		=> $args['font_size_title'],
			'min'			=> 0,
			'max'			=> 999,
			'class'				=> 'editor-listen',
			'data-handler'		=> 'apg',
			'data-style-option' => true,
			'data-has-unit'	=> true,
			'data-css-attribute'=> 'font-size',
			'data-target'		=> '.apg-post-title .title, .apg-text-seperator',
			'data-range-slider' => 'apg',
			//'responsive'	=> true,
		),
		$args['prefix'] . 'title_text_transform' => array(
			'title'				=> 'Text Transform',
			'size'				=> 'span2',
			'data-input-type'	=> 'select-box',
			'class'				=> 'editor-listen',
			'data-handler'		=> 'apg',
			'default'			=> 'none',
			'data-css-attribute'=> 'text-transform',
			'data-target'		=> '.apg-post-title .title, .apg-text-seperator',
			'select-box-values' => array(
				'none'			=> 'None',
				'uppercase'		=> 'Uppercase',
			),
		),
		$args['prefix'] . 'title_line_height' => array(
			'title'				=> 'Line Height (in %)',
			'size'				=> 'span2',
			'data-input-type' 	=> 'range-slider',
			'data-target'		=> '.apg-post-title .title',
			'default'			=> $args['line_height'],
			'min'				=> 0,
			'max'				=> 9999,
			'class' 	 		=> 'editor-listen',
			'data-handler'		=> 'apg',
			'data-range-slider' => 'apg',
		),
		$args['prefix'] . 'title_letter_spacing' => array(
			'title'				=> 'Letter Spacing',
			'size'				=> 'span2',
			'data-input-type' 	=> 'range-slider',
			'data-css-attribute'=> 'letter-spacing',
			'data-style-option' => true,
			'data-target'		=> '.apg-post-title .title',
			'default'			=> 0,
			'min'				=> 0,
			'max'				=> 9999,
			'data-negative' 	=> true,
			'data-has-unit'		=> true,
			'data-divider'		=> 10,
			'class' 	 		=> 'editor-listen',
			'data-handler'		=> 'apg',
			'data-range-slider' => 'apg',
		),
	);
} else if($args['options'] == 'type-formatting') {
	$options = array(
		'title'  	 => 'Type Formatting',
		'break'		 => '2,2,2',
		'style-class'=> $style_class,
		'data-hide-mobile' => true,
		$args['prefix'] . 'type_color' => array(
			'title'				=> 'Color',
			'data-style-option' => true,
			'size'				=> 'span2',
			'data-input-type'	=> 'color',
			'default'			=> 'transparent',
			'class'				=> 'color-picker admin-listen-handler',
			'data-handler' 		=> 'colorPicker',
			'data-picker'		=> 'apg',
			'data-target'		=> '.apg-post-title .type, .apg-post-title .description',
			'data-css-attribute'=> 'color',
		),
		$args['prefix'] . 'type_font_family' => array(
			'data-input-type' => 'select-fonts',
			'title'		 		=> 'Font Family',
			'size'		 		=> 'span2',
			'class'				=> 'editor-listen',
			'data-handler'		=> 'apg',
			'default' 	 		=> 'regular',
			'select-box-values' => $this->fonts,
		),
		$args['prefix'] . 'type_fontsize' => array(
			'title'			=> 'Font Size',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' 	=> 'range-slider',
			'default'		=> $args['font_size_type'],
			'min'			=> 0,
			'max'			=> 999,
			'class'				=> 'editor-listen',
			'data-handler'		=> 'apg',
			'data-target'		=> '.apg-post-title .type, .apg-post-title .description',
			'data-style-option' => true,
			'data-has-unit'		=> true,
			'data-css-attribute'=> 'font-size',
			'data-range-slider' => 'apg',
			//'responsive'	=> true,
		),
		$args['prefix'] . 'type_text_transform' => array(
			'title'				=> 'Text Transform',
			'size'				=> 'span2',
			'data-input-type'	=> 'select-box',
			'class'				=> 'editor-listen',
			'data-handler'		=> 'apg',
			'default'			=> 'none',
			'data-css-attribute'=> 'text-transform',
			'data-target'		=> '.apg-post-title .type, .apg-post-title .description',
			'select-box-values' => array(
				'none'			=> 'None',
				'uppercase'		=> 'Uppercase',
			),
		),
		$args['prefix'] . 'type_line_height' => array(
			'title'				=> 'Line Height (in %)',
			'size'				=> 'span2',
			'data-input-type' 	=> 'range-slider',
			'data-target'		=> '.apg-post-title .type, .apg-post-title .description',
			'default'			=> $args['line_height'],
			'min'				=> 0,
			'max'				=> 9999,
			'class' 	 		=> 'editor-listen',
			'data-handler'		=> 'apg',
			'data-range-slider' => 'apg',
			'style-class'		=> 'apg-type-line-height',
		),
		$args['prefix'] . 'type_padding_top' => array(
			'title'			=> 'Padding Top',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' => 'range-slider',
			'default'		=> $type_padding_top,
			'min'			=> 0,
			'max'			=> 999,
			'class'				=> 'editor-listen',
			'data-handler'		=> 'apg',
			'data-range-slider' => 'apg',
			'data-style-option' => true,
			'data-has-unit'	=> true,
			'data-css-attribute'=> 'padding-top',
			'data-target'   => '.apg-post-title .type, .apg-post-title .description',
			//'responsive'	=> true,
		),
	);
}