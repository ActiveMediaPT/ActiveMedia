<?php

// -----------------------------------------
// semplice
// admin/atts/partials/mobile/section_options.php
// -----------------------------------------

$options = array(
	'break'	  => '1,1,1,1,1,1,1,1',
	'title'   => $title . ' options',
	'reset-changes' => array(
		'data-input-type' 	=> 'button',
		'title'		 		=> 'Reset Mobile',
		'button-title'		=> 'Reset Mobile Changes',
		'help'				=> 'This will reset all changes made to the section in this breakpoint to the \'Desktop\' breakpoint. This includes styles, options and content changes.',
		'size'		 		=> 'span4',
		'class'				=> 'semplice-button reset-changes white-button',
		'responsive' 		=> true,
		'data-core-option' => true,
	),
	'copy-styles' => array(
		'data-input-type' 	=> 'select-box',
		'title'		 		=> 'Copy Changes from',
		'help'				=> 'This will copy all changes made to the section from your selected breakpoint. This includes styles, options and content changes.',
		'size'		 		=> 'span4',
		'class'				=> 'copy-styles',
		'responsive' 		=> true,
		'data-core-option' => true,
		'select-box-values' => array(
			'false' => 'Select Breakpoint',
			'xl' => 'Desktop Wide',
			'lg' => 'Desktop',
			'md' => 'Tablet Wide',
			'sm' => 'Tablet Portrait',
			'xs' => 'Phone',
		),
	),
	$breakpoint . '-visibility' => array(
		'title'		 		=> 'Visibility',
		'size'		 		=> 'span4',
		'data-input-type' 	=> 'switch',
		'switch-type'		=> 'twoway',
		'class' 	 		=> 'editor-listen',
		'data-handler'		=> 'layout',
		'default' 	 		=> 'visbile',
		'data-content-id'	=> '',
		'responsive' 		=> true,
		'data-core-option' => true,
		'switch-values' 	=> array(
			'visbile'		=> 'Visible',
			'hide' 			=> 'Hide',
		),
	),
);

// column mode
if($breakpoint == 'sm' || $breakpoint == 'xs') {
	$column_mode = array(
		'title'		 => 'Column Mode',
		'size'		 => 'span4',
		'data-input-type' => 'switch',
		'switch-type'=> 'twoway',
		'class' 	 	=> 'editor-listen',
		'data-handler'	=> 'layout',
		'default' 	 => 'single',
		'data-content-id' => '',
		'responsive' => true,
		'data-core-option' => true,
		'switch-values' => array(
			'single'	=> 'Single',
			'multi' => 'Multi',
		),
	);
	// add to atts
	$options['column-mode-' . $breakpoint] = $column_mode;
}
