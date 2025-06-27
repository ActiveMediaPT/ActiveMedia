<?php

// -----------------------------------------
// semplice
// admin/atts/partials/mobile/gallery.php
// -----------------------------------------

$options = array(
	'title'  	 => $title . ' Options',
	'break'		 => '2,2,1',
	'class'		 => 'mobile-option mobile-option-' .$breakpoint,
	'slide_width_custom_' . $breakpoint => array(
		'title'			=> 'Custom Width',
		'size'			=> 'span2',
		'offset'		=> false,
		'data-input-type' 	=> 'range-slider',
		'default'		=> 100,
		'min'			=> 1,
		'max'			=> 100,
		'class'      	=> 'editor-listen',
		'data-handler' 	=> 'save',
		'style-class'	=> 'ov-gal-slide-width-custom',
		'responsive'	=> true
	),
	'max_width_' . $breakpoint => array(
		'title'			=> 'Image Max Width (%)',
		'size'			=> 'span2',
		'offset'		=> false,
		'data-input-type' 	=> 'range-slider',
		'default'		=> 100,
		'min'			=> 1,
		'max'			=> 100,
		'class'      	=> 'editor-listen',
		'data-handler' 	=> 'save',
		'style-class'	=> 'ov-gal-image-width-max-width',
		'responsive'	=> true
	),
	'max_height_' . $breakpoint => array(
		'title'			=> 'Image Max Height (%)',
		'size'			=> 'span2',
		'offset'		=> false,
		'data-input-type' 	=> 'range-slider',
		'default'		=> 75,
		'min'			=> 1,
		'max'			=> 100,
		'class'      	=> 'editor-listen',
		'data-handler' 	=> 'save',
		'style-class'	=> 'ov-gal-image-height-max-height',
		'responsive'	=> true
	),
	'spacing_' . $breakpoint => array(
		'title'			=> 'Image Spacing',
		'size'			=> 'span2',
		'offset'		=> false,
		'data-input-type' 	=> 'range-slider',
		'default'		=> 0,
		'min'			=> 1,
		'max'			=> 999,
		'data-has-unit' => true,
		'class'      	=> 'editor-listen',
		'data-handler' 	=> 'save',
		'help'			=> 'Only takes affect if \'Slide Width\' is set to \'Image Width\' or \'Custom\'.',
		'responsive'	=> true,
		'data-supported-units' => 'rem,%,vw'
	),
	'vertical_align_' . $breakpoint => array(
		'data-input-type' => 'switch',
		'switch-type'=> 'twoway',
		'title'		 => 'Image Alignment',
		'size'		 => 'span4',
		'class'      		=> 'editor-listen',
		'data-handler' 		=> 'save',
		'default' 	 => 'flex-start',
		'help'		 => 'Define the vertical alignment of your images. Will only take affect if your images doesn\'t share the same height.',
		'responsive'	=> true,
		'switch-values' => array(
			'flex-start' => 'Top',
			'center'  => 'Center',
		),
	),
);