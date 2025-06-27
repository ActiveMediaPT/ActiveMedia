<?php

// -----------------------------------------
// semplice
// admin/atts/customize/thumbhover.php
// -----------------------------------------

$thumbhover = array(
	'tabs' => array(
		'options' => array(
			'thumb-hover-background-color' => $atts->get('thumbhover', array('option' => 'background-color')),
			'thumb-hover-background-type' => $atts->get('thumbhover', array('option' => 'background-type')),
			'thumb-hover-background-image' => $atts->get('thumbhover', array('option' => 'background-image')),
			'thumb-hover-background-video' => $atts->get('thumbhover', array('option' => 'background-video')),
			'thumb-hover-scale' => $atts->get('thumbhover', array('option' => 'scale')),
			'thumb-hover-title-category' => $atts->get('thumbhover', array('option' => 'title-category')),
			'thumb-hover-title' => $atts->get('thumbhover', array('option' => 'title')),	
			'thumb-hover-category' => $atts->get('thumbhover', array('option' => 'category')),
			'thumb-hover-shadow' => $atts->get('thumbhover', array('option' => 'shadow')),
		),
	),
);