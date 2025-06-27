<?php

// -----------------------------------------
// semplice
// admin/atts/partials/vpbutton.php
// -----------------------------------------

// vars
$handler = 'default';
$apply_to = 'style';

// change vars on slider
if(true === $args['is_slider']) {
	$handler = 'coverSlider';
	$apply_to= 'coverSlider';
}

// general options
$general = array(
	'title'  	 => 'View Project Button',
	'hide-title' => true,
	'break'		 => '1,3,2,2',
	'data-hide-mobile' => true,
	'style-class'	=> 'ov-vp-button-custom',
	'vp_button_label' => array(
		'data-input-type'	=> 'input-text',
		'title'		 	=> 'Label',
		'size'		 	=> 'span4',
		'placeholder'	=> 'View Project',
		'default'		=> 'View Project',
		'class'			=> 'editor-listen',
		'data-handler'  => $handler,
	),
	'vp_button_font_family' => array(
		'title'				=> 'Font Family',
		'size'				=> 'span2',
		'data-input-type'	=> 'select-fonts',
		'class'				=> 'editor-listen',
		'data-handler'  	=> $handler,
		'default'			=> 'none',
		'select-box-values' => semplice_get_default_fonts('display', false),
	),
	'vp_button_font_size' => array(
		'title'				=> 'Font size',
		'size'				=> 'span1',
		'data-input-type' 	=> 'range-slider',
		'class'				=> 'editor-listen',
		'data-handler'  	=> $handler,
		'data-has-unit'		=> true,
		'default'			=> 13,
		'min'				=> 6,
		'max'				=> 9999,
		'data-range-slider' => $apply_to,
	),
	'vp_button_letter_spacing' => array(
		'data-style-option' => true,
		'title'				=> 'Spacing',
		'size'				=> 'span1',
		'data-input-type' 	=> 'range-slider',
		'class'				=> 'editor-listen',
		'data-handler' 		=> $handler,
		'data-has-unit'		=> true,
		'default'			=> 0,
		'min'				=> 0,
		'max'				=> 9999,
		'data-divider'		=> 10,
		'data-negative'		=> true,
		'data-range-slider' => $apply_to,
	),
	'vp_button_padding_ver' => array(
		'title'				=> 'Padding Ver',
		'size'				=> 'span2',
		'data-input-type' 	=> 'range-slider',
		'class'				=> 'editor-listen',
		'data-handler'  	=> $handler,
		'data-has-unit'		=> true,
		'default'			=> 8,
		'min'				=> 0,
		'max'				=> 9999,
		'data-range-slider' => $apply_to,
	),
	'vp_button_padding_hor' => array(
		'title'				=> 'Padding Hor',
		'size'				=> 'span2',
		'data-input-type' 	=> 'range-slider',
		'class'				=> 'editor-listen',
		'data-handler'  	=> $handler,
		'data-has-unit'		=> true,
		'default'			=> 30,
		'min'				=> 0,
		'max'				=> 9999,
		'data-range-slider' => $apply_to,
	),
	'vp_button_border_width' => array(
		'title'				=> 'Border Width',
		'size'				=> 'span2',
		'data-input-type' 	=> 'range-slider',
		'class'				=> 'editor-listen',
		'data-handler'  	=> $handler,
		'data-has-unit'		=> true,
		'default'			=> 1,
		'min'				=> 0,
		'max'				=> 9999,
		'data-range-slider' => $apply_to,
	),
	'vp_button_border_radius' => array(
		'title'				=> 'Border Radius',
		'size'				=> 'span2',
		'data-input-type' 	=> 'range-slider',
		'class'				=> 'editor-listen',
		'data-handler'  	=> $handler,
		'data-has-unit'		=> true,
		'default'			=> 2,
		'min'				=> 0,
		'max'				=> 9999,
		'data-range-slider' => $apply_to,
	),
);

// link options
$link = array(
	'title'  	 => 'Button Link Colors',
	'break'		 => '3',
	'data-hide-mobile' => true,
	'style-class'		=> 'ov-vp-button-custom',
	'vp_button_font_color' => array(
		'title'				=> 'Font',
		'size'				=> 'span2',
		'data-input-type'	=> 'color',
		'default'			=> '#ffffff',
		'class'				=> 'color-picker admin-listen-handler',
		'data-handler' 		=> 'colorPicker',
		'data-picker'		=> $apply_to,
	),
	'vp_button_bg_color' => array(
		'title'				=> 'Background',
		'size'				=> 'span2',
		'data-input-type'	=> 'color',
		'default'			=> 'transparent',
		'class'				=> 'color-picker admin-listen-handler',
		'data-handler' 		=> 'colorPicker',
		'data-picker'		=> $apply_to,
	),
	'vp_button_border_color' => array(
		'title'				=> 'Border',
		'size'				=> 'span2',
		'data-input-type'	=> 'color',
		'default'			=> '#ffffff',
		'class'				=> 'color-picker admin-listen-handler',
		'data-handler' 		=> 'colorPicker',
		'data-picker'		=> $apply_to,
	),
);

// link options
$hover = array(
	'title'  	 => 'Button Hover Colors',
	'break'		 => '3',
	'data-hide-mobile' => true,
	'style-class'		=> 'ov-vp-button-custom',
	'vp_button_font_mouseover_color' => array(
		'title'				=> 'Font',
		'size'				=> 'span2',
		'data-input-type'	=> 'color',
		'default'			=> '#ffffff',
		'class'				=> 'color-picker admin-listen-handler',
		'data-handler' 		=> 'colorPicker',
		'data-picker'		=> $apply_to,
	),
	'vp_button_bg_mouseover_color' => array(
		'title'				=> 'Background',
		'size'				=> 'span2',
		'data-input-type'	=> 'color',
		'default'			=> 'transparent',
		'class'				=> 'color-picker admin-listen-handler',
		'data-handler' 		=> 'colorPicker',
		'data-picker'		=> $apply_to,
	),
	'vp_button_border_mouseover_color' => array(
		'title'				=> 'Border',
		'size'				=> 'span2',
		'data-input-type'	=> 'color',
		'default'			=> '#ffffff',
		'class'				=> 'color-picker admin-listen-handler',
		'data-handler' 		=> 'colorPicker',
		'data-picker'		=> $apply_to,
	),
);

// define options array
$options = array(
	'general' => $general,
	'link'	  => $link,
	'hover'	  => $hover,
);