<?php

// -----------------------------------------
// semplice
// admin/atts/partials/mobile/column_styles.php
// -----------------------------------------

$options = array(
	'break'	  => '1,1,1,1,1,1,1,1',
	'title'   => $title . ' styles',
);

// column mode
if($breakpoint == 'sm' || $breakpoint == 'xs') {
	$column_height = array(
		// height
		'title'			=> 'Height',
		'size'			=> 'span2',
		'help'			=> 'Please note that if you set your spacer column to a height of 0px, it still has 100px height in the editor just so you can click it. In the frontend the height will be 0px.',
		'offset'		=> false,
		'data-input-type' 	=> 'range-slider',
		'data-target'	=> '.column',
		'default'		=> 0,
		'min'			=> 0,
		'max'			=> 999,
		'class'			=> 'editor-listen',
		'data-handler'	=> 'default',
		'responsive'	=> true,
		'data-has-unit'	=> true,
		'data-range-slider' => 'spacerColumn',
		'data-supported-units' => 'rem,vh'
	);
	// add to atts
	$options['height'] = $column_height;
}