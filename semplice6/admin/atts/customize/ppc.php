<?php

// -----------------------------------------
// semplice
// admin/atts/customize/ppc.php
// -----------------------------------------

$ppc = array(
	'colors' => array(
		'title'	 		=> 'Basic Colors',
		'description' 	=> 'Define the basic colors for your password protected content.',
		'break'			=> '2',
		'background_color' => array(
			'title'			=> 'Background',
			'size'			=> 'span1',
			'data-input-type'	=> 'color',
			'default'		=> '#f5f5f5',
			'class'			=> 'color-picker admin-listen-handler',
			'data-handler'  => 'colorPicker',
			'data-picker'	=> 'advanced',
		),
		'lock_color' => array(
			'title'			=> 'Lock Color',
			'size'			=> 'span1',
			'data-input-type'	=> 'color',
			'default'		=> '#939393',
			'class'			=> 'color-picker admin-listen-handler',
			'data-handler'  => 'colorPicker',
			'data-picker'	=> 'advanced',
		),
	),
	'formatting' => array(
		'title'	 		=> 'Text Formatting',
		'description' 	=> 'Define the formatting for your title, sub title and input.<br /><br />If you want to change the text itself you can do that with a translation tool. Here is a guide on how to use <a href="https://help.semplice.com/hc/en-us/articles/4418820433556" target="_blank">loco translate with Semplice</a>',
		'break'			=> '1,3,1,3,1,3',
	),
	'input' => array(
		'title'	 		=> 'Input Formatting',
		'description' 	=> 'Submit icon resolution: 80x60px<br />Recommended Format: .svg',
		'break'			=> '3,2',
		'input_font_family' => array(
			'data-input-type' 	=> 'select-fonts',
			'title'		 		=> 'Font Family',
			'size'		 		=> 'span2',
			'class'				=> 'admin-listen-handler',
			'data-handler'		=> 'advanced',
			'default' 	 		=> 'none',
			'select-box-values' => $atts->fonts,
		),
		'input_bg_color' => array(
			'title'			=> 'Input BG Color',
			'size'			=> 'span1',
			'data-input-type'	=> 'color',
			'default'		=> '#ffffff',
			'class'			=> 'color-picker admin-listen-handler',
			'data-handler'  => 'colorPicker',
			'data-picker'	=> 'advanced',
		),
		'input_color' => array(
			'title'			=> 'Input Color',
			'size'			=> 'span1',
			'data-input-type'	=> 'color',
			'default'		=> '#000000',
			'class'			=> 'color-picker admin-listen-handler',
			'data-handler'  => 'colorPicker',
			'data-picker'	=> 'advanced',
		),
		'submit_icon' => array(
			'title'				 => 'Submit Icon',
			'size'				 => 'span2',
			'data-input-type'	 => 'admin-image-upload',
			'class'			  	 => 'admin-listen-handler',
			'data-handler'       => 'advanced',
			'data-upload'		 => 'advanced',
			'style-class'		 => 'ce-upload-small general-dropzone hide-unsplash',
		),
		'placeholder_color' => array(
			'title'			=> 'Placeholder Color',
			'size'			=> 'span2',
			'data-input-type'	=> 'color',
			'default'		=> '#cccccc',
			'class'			=> 'color-picker admin-listen-handler',
			'data-handler'  => 'colorPicker',
			'data-picker'	=> 'advanced',
		),
	),
);

// add formatting options
$formatting = array('title' => '#000000', 'subtitle' => '#939393');

// iterate
$count = 0;
foreach($formatting as $format => $default) {
	$ppc['formatting']['settings-title-' . $count] = array(
		'data-input-type' 	=> 'settings-title',
		'switch-type'		=> 'twoway',
		'title'				=> 'settings-title',
		'hide-title'		=> true,
		'size'				=> 'span4',
		'default' 	 		=> ucfirst($format) . ' Formatting',
		'style-class'		=> 'settings-title settings-title-top',
	);
	$ppc['formatting'][$format . '_font_family'] = array(
		'data-input-type' 	=> 'select-fonts',
		'title'		 		=> 'Font Family',
		'size'		 		=> 'span2',
		'class'				=> 'admin-listen-handler',
		'data-handler'		=> 'advanced',
		'default' 	 		=> 'none',
		'select-box-values' => $atts->fonts,
	);
	$ppc['formatting'][$format . '_color'] = array(
		'title'			=> 'Title',
		'size'			=> 'span1',
		'data-input-type'	=> 'color',
		'default'		=> $default,
		'class'			=> 'color-picker admin-listen-handler',
		'data-handler'  => 'colorPicker',
		'data-picker'	=> 'advanced',
	);
	$ppc['formatting'][$format . '_letter_spacing'] = array(
		'data-css-attribute'=> 'letter-spacing',
		'title'				=> 'Letter Spacing',
		'size'				=> 'span1',
		'data-input-type' 	=> 'range-slider',
		'default'			=> 0,
		'min'				=> 0,
		'max'				=> 9999,
		'data-negative' 	=> true,
		'data-has-unit'		=> true,
		'data-divider'		=> 10,
		'class'				=> 'admin-listen-handler',
		'data-handler'		=> 'advanced',
		'data-range-slider' => 'advanced',
	);
	$count++;
}