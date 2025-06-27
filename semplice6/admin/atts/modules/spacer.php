<?php

// -----------------------------------------
// semplice
// admin/atts/modules/spacer.php
// -----------------------------------------

$spacer = array(
	'options' => array(
		'title'  	 => 'Options',
		'hide-title' => true,
		'break'		 => '4',
		'data-hide-mobile' => true,
		'background-color' => array(
			'title'				=> 'Color',
			'data-style-option' => true,
			'size'				=> 'span1',
			'data-input-type'	=> 'color',
			'data-target'		=> '.spacer',
			'default'			=> '#e0e0e0',
			'class'				=> 'color-picker admin-listen-handler',
			'data-handler' 		=> 'colorPicker',
		),
		'height' => array(
			'title'				=> 'Height',
			'size'				=> 'span1',
			'offset'			=> false,
			'data-input-type' 	=> 'range-slider',
			'data-target'		=> '.spacer',
			'default'			=> 10,
			'min'				=> 0,
			'max'				=> 999,
			'class'				=> 'editor-listen',
			'data-handler'		=> 'spacer',
			'data-range-slider' => 'spacer',
			'data-has-unit' 	=> true,
			'data-supported-units' => 'rem,vh'
		),
	),
	'spacer_responsive_lg' => $atts->get_mobile('spacer', 'Desktop', 'lg', false),
	'spacer_responsive_md' => $atts->get_mobile('spacer', 'Tablet Landscape', 'md', false),
	'spacer_responsive_sm' => $atts->get_mobile('spacer', 'Tablet Portrait', 'sm', false),
	'spacer_responsive_xs' => $atts->get_mobile('spacer', 'Mobile', 'xs', false),
);

?>