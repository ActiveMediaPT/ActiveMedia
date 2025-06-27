<?php

// -----------------------------------------
// semplice
// admin/atts/partials/mobile/gutter.php
// -----------------------------------------

// names
$title = array(
	'lg' => 'Grid Options Desktop',
	'md' => 'Grid Options Tablet Landscape',
	'sm' => 'Grid Options Tablet Portrait',
	'xs' => 'Grid Options Mobile',
);

$options = array(
	'title'  	 => $title[$breakpoint],
	'break'		 => '3',
	'class'		 => 'mobile-option mobile-option-' .$breakpoint,
	'hor_gutter_' . $breakpoint => array(
		'title'			=> 'H-Gutter',
		'size'			=> 'span1',
		'offset'		=> false,
		'data-input-type' 	=> 'range-slider',
		'default'		=> 30,
		'min'			=> 0,
		'max'			=> 999,
		'class'				=> 'editor-listen',
		'data-handler'		=> 'save',
		'responsive'	=> true,
	),
	'ver_gutter_' . $breakpoint => array(
		'title'			=> 'V-Gutter',
		'size'			=> 'span1',
		'offset'		=> false,
		'data-input-type' 	=> 'range-slider',
		'default'		=> 30,
		'min'			=> 0,
		'max'			=> 999,
		'class'				=> 'editor-listen',
		'data-handler'		=> 'save',
		'responsive'	=> true,
	),
);
// adding imgages per row option
if(true === $args['images_per_row']) {
	$options['col_' . $breakpoint] = array(
		'data-input-type' 	=> 'select-box',
		'title'		 		=> 'Images per Row',
		'size'		 		=> 'span2',
		'class'      		=> 'editor-listen',
		'data-handler' 		=> 'save',
		'default' 	 		=> '4',
		'responsive'		=> true,
		'select-box-values' => array(
			'12' 			=> '1 Image',
			'6' 			=> '2 Images',
			'4' 			=> '3 Images',
			'3' 			=> '4 Images',
			'2' 			=> '6 Images',
			'1' 			=> '12 Images',
		),
	);
}