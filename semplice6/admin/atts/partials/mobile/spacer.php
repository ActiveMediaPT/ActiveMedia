<?php

// -----------------------------------------
// semplice
// admin/atts/partials/mobile/spacer.php
// -----------------------------------------

$options = array(
	'title'  	 => $title . ' Options',
	'break'		 => '4',
	'class'		 => 'mobile-option mobile-option-' .$breakpoint,
	'height_' . $breakpoint => array(
		'title'				=> 'Height',
		'size'				=> 'span1',
		'offset'			=> false,
		'data-input-type' 	=> 'range-slider',
		'data-target'		=> '.spacer',
		'default'			=> 10,
		'min'				=> 0,
		'max'				=> 999,
		'class'				=> 'editor-listen',
		'data-has-unit' 	=> true,
		'responsive'		=> true,
		'data-handler'		=> 'spacer',
		'data-range-slider' => 'spacer',
		'data-supported-units' => 'rem,vh'
	),
);