<?php

// -----------------------------------------
// semplice
// admin/atts/partials/mobile/socialprofiles.php
// -----------------------------------------

$options = array(
	'title'  	 => 'Icons',
	'break'		 => '4',
	'class'		 => 'mobile-option mobile-option-' .$breakpoint,
	'icon_scale_' .$breakpoint => array(
		'title'				=> 'Scale',
		'size'				=> 'span1',
		'data-input-type' 	=> 'range-slider',
		'data-css-attribute'=> 'height',
		'data-target'		=> '.social-profile svg',
		'data-style-option' => true,
		'data-has-unit'		=> true,
		'default'			=> 26,
		'min'				=> 0,
		'max'				=> 9999,
		'class' 	 		=> 'editor-listen',
		'data-handler'		=> 'socialprofiles',
		'data-range-slider' => 'socialProfiles',
		'responsive'		=> true,
	),
	'icon_padding_' .$breakpoint => array(
		'title'				=> 'Padding',
		'size'				=> 'span1',
		'data-input-type' 	=> 'range-slider',
		'data-css-attribute'=> 'padding',
		'data-target'		=> '.social-profile a',
		'data-style-option' => true,
		'data-has-unit'		=> true,
		'default'			=> 10,
		'min'				=> 0,
		'max'				=> 9999,
		'style-class'		=> 'ov-share-icons',
		'class' 	 		=> 'editor-listen',
		'data-handler'		=> 'socialprofiles',
		'data-range-slider' => 'socialProfiles',
		'help'				=> 'Padding only works if you set \'Distributed\' to \'Default\'.',
		'responsive'		=> true,
	)
);