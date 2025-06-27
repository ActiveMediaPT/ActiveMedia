<?php

// -----------------------------------------
// semplice
// admin/atts/partials/blogposts.php
// -----------------------------------------

// types
$types = array(
	'category' 		=> array('Category', 'category', array('#777777', 'serif_regular', 15, 'none', 0, 0, '#000000', 10), false, true, true),
	'title' 		=> array('Title', 'title', array('#000000', 'inter_bold', 36, 'none', 0, 44, '#555555', 18), true, false, true),
	'content' 		=> array('Content', 'content', array('#000000', 'serif_regular', 18, 'none', 0, 28, '', 18), true, false, true),
	'meta' 			=> array('Meta Formatting', 'meta', array('#777777', 'serif_regular', 15, 'none', 0, 26, '#000000', 0), true, true, true),
	'tags' 			=> array('Tags Formatting', 'tags', array('#777777', 'inter_regular', 15, 'none', 0, 26, '#000000', 0), true, true, true),
	'pagination' 	=> array('Pagination', 'pagination', array('#777777', 'inter_regular', 18, 'none', 0, 28, '#000000', 0), true, false, true),
	'archive' 		=> array('Archive Title', 'archive', array('#000000', 'inter_light', 32, 'none', 0, 42, '#000000', 18), false, false, false),
	'caption' 		=> array('Caption', 'caption', array('#999999', 'inter_regular', 14, 'none', 0, 42, '#000000', 18), false, false, false),
);

// spacing
$spacing = 15;

// options array
if(false === $args['archive']) {
	$options = array(
		'title'  	 => 'Formatting',
		'break'		 => '1,1,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,1,2,2,2,2',
		'style-class' => 'ep-collapsed',
		'show_options' => array(
			'title'			  => 'Show options for',
			'data-input-type' => 'select-box',
			'size'		 	=> 'span4',
			'class'     	=> 'editor-listen',
			'data-handler'  => 'save',
			'data-visibility-switch' 	=> true,
			'data-visibility-values' 	=> 'category,title,content,meta,tags,pagination,caption',
			'data-visibility-prefix'	=> 'ov-bp-formatting',
			'default' 	 	=> 'category',
			'responsive' 	=> true,
			'select-box-values' => array(
				'category'	=> 'Category',
				'title'		=> 'Title',
				'content'	=> 'Content',
				'meta'		=> 'Meta',
				'tags'		=> 'Tags',
				'pagination'=> 'Pagination',
				'caption'	=> 'Caption'
			),
		),
	);
	// remove archive and caption from types
	unset($types['archive']);
	//unset($types['caption']);
} else {
	$options = array(
		'title'  	 => 'Archive Title',
		'break'		 => '1,2,2,2,2',
		'style-class' => 'ep-collapsed archive-title-options',
	);
	// add desktop class
	if(false === $args['breakpoint']) {
		$options['style-class'] .= ' archive-options-desktop';
	}
	// types
	$type_archive = array('archive' => $types['archive']);
	$types = $type_archive;
	// spacing
	$spacing = 54;
}

// breakpoint
if(false === $args['breakpoint']) {
	$breakpoint = '';
	$options['data-hide-mobile'] = true;
} else {
	$breakpoint = '_' . $args['breakpoint'];
	$options['class'] = 'mobile-option mobile-option-' . $args['breakpoint'];
	if(false === $args['archive']) {
		$options['break'] = '1,2,2,2,2, 2,2,2,2, 2,2,2,2, 2,2,2,2,1, 2,2,2,2,1, 2,2,2,1';
	} else {
		$options['break'] = '2,2,1';
	}
}

// iterate types
foreach($types as $type => $values) {

	// extract atts
	extract( shortcode_atts(
		array(
			'title'					=> $values[0],
			'defaults'				=> $values[2],
			'has_line_height'		=> $values[3],
			'has_seperator'			=> $values[4],
			'has_hover'				=> $values[5]
		),$values)
	);

	// style class
	$style_class = 'ov-bp-formatting-' . $type;

	// visibility span and default
	$visibility = array(
		'default' => 'visible',
		'span'	  => 'span4',
		'class'   => '',
	);
	if($type == 'content') {
		$visibility['span'] = 'span2';
		$visibility['class']= ' ep-excerpt-visibility';
	}
	if($type == 'tags') {
		$visibility['default'] = 'hidden';
		$visibility['span'] = 'span2';
	}

	// align
	$align = 'left';
	if($type == 'pagination' || $type == 'caption') {
		$align = 'center';
	}

	$options[$type . '_visibility'] = array(
		'data-input-type' 			=> 'switch',
		'switch-type'				=> 'twoway',
		'title'		 				=> 'Visibility',
		'size'		 				=> $visibility['span'],
		'class'						=> 'editor-listen',
		'data-handler'				=> 'save',
		'default' 	 				=> $visibility['default'],
		'style-class'				=> $style_class . $visibility['class'],
		'switch-values' => array(
			'visible'	=> 'Visible',
			'hidden'  	=> 'Hidden',
		),
	);
	if($type == 'tags') {
		$options[$type . '_label'] = array(
			'data-input-type' 			=> 'switch',
			'switch-type'				=> 'twoway',
			'title'		 				=> 'Tags Label',
			'size'		 				=> 'span2',
			'class'						=> 'editor-listen',
			'data-handler'				=> 'save',
			'default' 	 				=> 'visible',
			'style-class'				=> $style_class,
			'switch-values' => array(
				'visible'	=> 'Visible',
				'hidden'  	=> 'Hidden',
			),
		);
	}
	if($type == 'content') {
		$options['excerpt_length'] = array(
			'title'			=> 'Excerpt Length',
			'help'			=> 'Define the excerpt length in words.',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' 	=> 'range-slider',
			'default'		=> 34,
			'min'			=> 0,
			'max'			=> 9999,
			'class'				=> 'editor-listen',
			'data-handler'		=> 'save',
			'style-class'		=> $style_class . ' ep-excerpt-length',
		);
	}
	if($type != 'pagination') {
		$options[$type . '_color'] = array(
			'title'				=> 'Color',
			'data-style-option' => true,
			'size'				=> 'span2',
			'data-input-type'	=> 'color',
			'default'			=> $defaults[0],
			'class'				=> 'color-picker admin-listen-handler',
			'data-handler' 		=> 'colorPicker',
			'style-class'		=> $style_class,
		);
		$options[$type . '_align' . $breakpoint] = array(
			'data-input-type' => 'select-box',
			'title'		 => 'Align',
			'size'		 => 'span2',
			'class'			=> 'editor-listen',
			'data-handler'	=> 'default',
			'data-target'=> '.ce-button',
			'default' 	 => $align,
			'style-class'				=> $style_class,
			'responsive' 	=> true,
			'select-box-values' => array(
				'left'			=> 'Left',
				'center'		=> 'Center',
				'right'			=> 'Right',
			),
		);
		// unset align for content
		if($type == 'content') {
			unset($options[$type . '_align']);
		}
	} else {
		$options[$type . '_arrow'] = array(
			'title'				=> 'Arrow',
			'size'				=> 'span2',
			'data-input-type'	=> 'select-box',
			'class'				=> 'editor-listen',
			'data-handler'		=> 'save',
			'default'			=> 'rsaquo',
			'style-class'		=> $style_class,
			'select-box-values' => array(
				'none'		=> 'None',
				'rsaquo'	=> '&lsaquo; &rsaquo;',
				'raquo'		=> '&laquo; &raquo;',
				'rarr'		=> '&larr; &rarr;',
			),
		);
	}
	$options[$type . '_font_family'] = array(
		'data-input-type' => 'select-fonts',
		'title'		 		=> 'Font Family',
		'size'		 		=> 'span2',
		'class'				=> 'editor-listen',
		'data-handler'		=> 'save',
		'default' 	 		=> $defaults[1],
		'select-box-values' => $this->fonts,
		'style-class'		=> $style_class,
	);
	$options[$type . '_fontsize' . $breakpoint] = array(
		'title'			=> 'Font Size',
		'size'			=> 'span2',
		'offset'		=> false,
		'data-input-type' 	=> 'range-slider',
		'default'		=> $defaults[2],
		'min'			=> 0,
		'max'			=> 999,
		'class'			=> 'editor-listen',
		'data-handler'	=> 'save',
		'data-has-unit'	=> true,
		'style-class'	=> $style_class,
		'responsive' 	=> true,
	);
	$options[$type . '_text_transform'] = array(
		'title'				=> 'Text Transform',
		'size'				=> 'span2',
		'data-input-type'	=> 'select-box',
		'class'				=> 'editor-listen',
		'data-handler'		=> 'save',
		'default'			=> $defaults[3],
		'data-css-attribute'=> 'text-transform',
		'style-class'		=> $style_class,
		'select-box-values' => array(
			'none'			=> 'None',
			'uppercase'		=> 'Uppercase',
			'lowercase'		=> 'Lowercase'
		),
	);
	$options[$type . '_letter_spacing' . $breakpoint] = array(
		'title'				=> 'Letter Spacing',
		'size'				=> 'span2',
		'data-input-type' 	=> 'range-slider',
		'default'			=> $defaults[4],
		'min'				=> 0,
		'max'				=> 9999,
		'data-negative' 	=> true,
		'data-has-unit'		=> true,
		'data-divider'		=> 10,
		'class' 	 		=> 'editor-listen',
		'data-handler'		=> 'save',
		'style-class'		=> $style_class,
		'responsive' 	=> true,
	);
	// remove visibility option from title
	if($type == 'title' || $type == 'pagination' || $type == 'meta') {
		unset($options[$type . '_visibility']);
	}
	// line-height
	if(true === $has_line_height) {
		$options[$type . '_line_height' . $breakpoint] = array(
			'title'				=> 'Line Height',
			'size'				=> 'span2',
			'data-input-type' 	=> 'range-slider',
			'default'			=> $defaults[5],
			'min'				=> 0,
			'max'				=> 9999,
			'class' 	 		=> 'editor-listen',
			'data-handler'		=> 'save',
			'data-has-unit'		=> true,
			'style-class'		=> $style_class,
			'responsive' 	=> true,
		);
	}
	// seperator
	if(true === $has_seperator) {
		$options[$type . '_seperator'] = array(
			'title'				=> 'Seperator',
			'size'				=> 'span2',
			'data-input-type'	=> 'select-box',
			'class'				=> 'editor-listen',
			'data-handler'		=> 'save',
			'default'			=> 'middot',
			'style-class'		=> $style_class,
			'select-box-values' => array(
				'space'		=> 'Space',
				'comma' 	=> ', ',
				'middot'	=> ' &middot; ',
				'mdash'		=> ' &mdash; ',
				'vertical'	=> ' | '
			),
		);
		$options[$type . '_seperator_padding' . $breakpoint] = array(
			'title'			=> 'Seperator Padding',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' 	=> 'range-slider',
			'default'		=> 0,
			'min'			=> 0,
			'max'			=> 999,
			'class'			=> 'editor-listen',
			'data-handler'	=> 'save',
			'data-has-unit'	=> true,
			'style-class'	=> $style_class,
			'responsive' 	=> true,
		);
		$options[$type . '_seperator_color'] = array(
			'title'				=> 'Seperator Color',
			'data-style-option' => true,
			'size'				=> 'span2',
			'data-input-type'	=> 'color',
			'default'			=> $defaults[0],
			'class'				=> 'color-picker admin-listen-handler',
			'data-handler' 		=> 'colorPicker',
			'style-class'		=> $style_class,
		);
	}
	// meta link color
	if($type == 'meta' || $type == 'pagination' || $type == 'content' || $type == 'tags') {
		$options[$type . '_link_color'] = array(
			'title'				=> 'Link Color',
			'data-style-option' => true,
			'size'				=> 'span2',
			'data-input-type'	=> 'color',
			'default'			=> '#000000',
			'class'				=> 'color-picker admin-listen-handler',
			'data-handler' 		=> 'colorPicker',
			'style-class'		=> $style_class,
		);
	}
	// hover color
	if(true === $has_hover) {
		$options[$type . '_hover_color'] = array(
			'title'				=> 'Hover Color',
			'size'				=> 'span2',
			'help'				=> 'Define the color on mouseover.',
			'data-input-type'	=> 'color',
			'default'			=> $defaults[6],
			'class'				=> 'color-picker admin-listen-handler',
			'data-handler' 		=> 'colorPicker',
			'style-class'		=> $style_class,
		);
		$options[$type . '_underline_width' . $breakpoint] = array(
			'title'			=> 'Underline Height',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' 	=> 'range-slider',
			'default'		=> 0,
			'min'			=> 0,
			'max'			=> 999,
			'class'			=> 'editor-listen',
			'data-handler'	=> 'save',
			'data-has-unit'	=> true,
			'style-class'	=> $style_class,
			'responsive' 	=> true,
		);
		$options[$type . '_underline_padding' . $breakpoint] = array(
			'title'			=> 'Underline Padding',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' 	=> 'range-slider',
			'default'		=> 0,
			'min'			=> 0,
			'max'			=> 999,
			'class'			=> 'editor-listen',
			'data-handler'	=> 'save',
			'data-has-unit'	=> true,
			'style-class'	=> $style_class,
			'responsive' 	=> true,
		);
		$options[$type . '_underline_color'] = array(
			'title'				=> 'Underline Color',
			'size'				=> 'span2',
			'data-input-type'	=> 'color',
			'default'			=> $defaults[0],
			'class'				=> 'color-picker admin-listen-handler',
			'data-handler' 		=> 'colorPicker',
			'style-class'		=> $style_class,
		);
		$options[$type . '_underline_hover'] = array(
			'title'				=> 'Underline Hover',
			'size'				=> 'span2',
			'data-input-type'	=> 'color',
			'default'			=> $defaults[6],
			'class'				=> 'color-picker admin-listen-handler',
			'data-handler' 		=> 'colorPicker',
			'style-class'		=> $style_class,
		);
	}
	$options[$type . '_top_spacing' . $breakpoint] = array(
		'title'			=> 'Top Spacing',
		'size'			=> 'span2',
		'offset'		=> false,
		'data-input-type' 	=> 'range-slider',
		'default'		=> $spacing,
		'min'			=> 0,
		'max'			=> 999,
		'class'			=> 'editor-listen',
		'data-handler'	=> 'save',
		'data-has-unit'	=> true,
		'style-class'	=> $style_class,
		'responsive' 	=> true,
	);
	$options[$type . '_bottom_spacing' . $breakpoint] = array(
		'title'			=> 'Bottom Spacing',
		'size'			=> 'span2',
		'offset'		=> false,
		'data-input-type' 	=> 'range-slider',
		'default'		=> $spacing,
		'min'			=> 0,
		'max'			=> 999,
		'class'			=> 'editor-listen',
		'data-handler'	=> 'save',
		'data-has-unit'	=> true,
		'style-class'	=> $style_class,
		'responsive' 	=> true,
	);
	// remove options not need for breakpoint
	if(false !== $args['breakpoint']) {
		// unset excerpt invidiually
		unset($options['excerpt_length']);
		// unset rest that is not needed for mobile
		$no_mobile = array('visibility', 'label', 'color', 'arrow', 'font_family', 'text_transform', 'seperator', 'seperator_color', 'link_color', 'hover_color', 'underline_color', 'underline_hover');
		foreach($no_mobile as $attribute) {
			unset($options[$type . '_' . $attribute]);
		}
	}
}