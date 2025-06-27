<?php

// -----------------------------------------
// semplice
// admin/atts/modules/fluidtext.php
// -----------------------------------------

$fluidtext = array(
	'options' => array(
		'title'  	 => 'Options',
		'hide-title' => true,
		'break'		 => '1',
		'data-hide-mobile' => true,
		'edit_paragraph' => array(
			'data-input-type' 	=> 'button',
			'title'		 		=> 'Edit',
			'button-title'		=> 'Edit Paragraph',
			'size'		 		=> 'span4',
			'class'				=> 'semplice-button init-wysiwyg-ep',
		),
	),
	'mobile-notice' => array(
		'title'  	 => 'Mobile Notice',
		'hide-title' => true,
		'break'		 => '1',
		'show_options' => array(
			'data-input-type' => 'notice',
			'hide-title' => true,
			'size'		 => 'span4',
			'class'     	=> 'ep-notice',
			'data-handler'  => 'mailchimp',
			'default'    => 'The fluid text module can only be edited in the <b>Desktop Wide</b> breakpoint.',
			'notice-type'=> 'warning',
			'responsive' => true
		),
	),
);
?>