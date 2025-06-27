<?php

// -----------------------------------------
// semplice
// admin/atts/partials/tablegrid.php
// -----------------------------------------

// deiine options
$options = array(
	'title'  	 => 'Formatting &amp; Visibility',
	'break'		 => '1,2,2,2,2,2,2,2,2,2,2,2,2,2,2',
	'style-class' => 'ep-collapsed ov-apg-table',
	'data-hide-mobile' => true,
	'show_options' => array(
		'title'			  => 'Show options for',
		'data-input-type' => 'select-box',
		'size'		 	=> 'span4',
		'class'     	=> 'editor-listen',
		'data-handler'  => 'save',
		'data-visibility-switch' 	=> true,
		'data-visibility-values' 	=> 'columns,date,title,type,client',
		'data-visibility-prefix'	=> 'ov-table-formatting',
		'default' 	 => 'columns',
		'responsive' => true,
		'select-box-values' => array(
			'columns'	=> 'Columns',
			'date'		=> 'Date',
			'title'		=> 'Title',
			'type'		=> 'Type',
			'client'	=> 'Client'
		),
	),
);

// breakpoint
if(false === $args['breakpoint']) {
	$breakpoint = '';
	$options['data-hide-mobile'] = true;
	$responsive_option = '';
} else {
	$responsive_option = 'apg';
	$breakpoint = '_' . $args['breakpoint'];
	$options['class'] = 'mobile-option mobile-option-' . $args['breakpoint'];
	$options['style-class'] = 'ep-collapsed apg-responsive-table-' . $args['breakpoint'];
	$options['break'] = '2,1,2,2,2,2';
}

// general column options
$options['table_text_transform'] = array(
	'data-input-type' => 'select-box',
	'data-css-attribute' => 'text-transform',
	'title'		 => 'Text Transform',
	'size'		 => 'span2',
	'class'			=> 'editor-listen',
	'data-handler'	=> 'apg',
	'default' 	 => 'none',
	'data-target'=> '.apg-table-link',
	'style-class'=> 'ov-table-formatting-columns',
	'select-box-values' => array(
		'none'			=> 'None',
		'uppercase'		=> 'Uppercase',
		'lowercase'		=> 'Lowercase'
	),
);
$options['table_font_size' . $breakpoint] = array(
	'data-css-attribute' => 'font-size',
	'title'			=> 'Font Size',
	'size'			=> 'span2',
	'offset'		=> false,
	'data-input-type' 	=> 'range-slider',
	'default'		=> 22,
	'min'			=> 6,
	'max'			=> 999,
	'class'     	=> 'editor-listen',
	'data-handler'  => 'apg',
	'data-has-unit'	=> true,
	'data-range-slider' => 'apg',
	'data-target'=> '.apg-table-link',
	'style-class'=> 'ov-table-formatting-columns',
	'data-responsive-option' => $responsive_option,
	'responsive'		=> true,
);
$options['table_line_height' . $breakpoint] = array(
	'data-css-attribute' => 'line-height',
	'title'			=> 'Line Height',
	'size'			=> 'span2',
	'offset'		=> false,
	'data-input-type' 	=> 'range-slider',
	'default'		=> 32,
	'min'			=> 0,
	'max'			=> 999,
	'class'     	=> 'editor-listen',
	'data-handler'  => 'apg',
	'data-has-unit'	=> true,
	'data-range-slider' => 'apg',
	'data-target'=> '.apg-table-link',
	'style-class'=> 'ov-table-formatting-columns',
	'data-responsive-option' => $responsive_option,
	'responsive'		=> true,
);
$options['table_letter_spacing' . $breakpoint] = array(
	'data-css-attribute'=> 'letter-spacing',
	'title'				=> 'Letter Spacing',
	'size'				=> 'span2',
	'data-input-type' 	=> 'range-slider',
	'default'			=> 0,
	'min'				=> 0,
	'max'				=> 9999,
	'data-negative' 	=> true,
	'data-has-unit'		=> true,
	'data-divider'		=> 10,
	'class' 	 		=> 'editor-listen',
	'data-handler'		=> 'apg',
	'data-range-slider' => 'apg',
	'data-target'=> '.apg-table-link',
	'style-class'=> 'ov-table-formatting-columns',
	'data-responsive-option' => $responsive_option,
	'responsive'		=> true,
);
// define columns
$columns = array(
	'date' => array(
		'width' => 1,
		'color' => '#999999',
	),
	'title' => array(
		'width' => 4,
		'color' => '#000000',
	),
	'type' => array(
		'width' => 4,
		'color' => '#999999',
	),
	'client' => array(
		'width' => 3,
		'color' => '#999999',
	)
);
// iterate columns
foreach($columns as $column => $column_defaults) {
	// hover text color
	$hover_text_color = 'transparent';
	if($column == 'title') {
		$hover_text_color = '#ffffff';
	}
	// style class
	$style_class = 'ov-table-formatting-' . $column;
	// visibility
	$options['table_' . $column . '_visibility' . $breakpoint] = array(
		'data-input-type' 			=> 'switch',
		'switch-type'				=> 'twoway',
		'title'		 				=> 'Visibility',
		'size'		 				=> 'span2',
		'class'						=> 'editor-listen',
		'data-handler'				=> 'apg',
		'default' 	 				=> 'visible',
		'data-target'				=> '.table-column-' . $column,
		'style-class'				=> $style_class,
		'data-responsive-option' => $responsive_option,
		'responsive'		=> true,
		'switch-values' => array(
			'visible'	=> 'Visible',
			'hidden'  	=> 'Hidden',
		),
	);
	// width
	$options['table_' . $column . '_width' . $breakpoint] = array(
		'data-input-type' => 'select-box',
		'title'		 	=> 'Width',
		'size'		 	=> 'span2',
		'class'			=> 'editor-listen',
		'data-target'	=> '.table-column-' . $column,
		'default' 	 	=> $column_defaults['width'],
		'data-handler'  => 'apg',
		'select-box-values' => $this->width,
		'style-class'		=> $style_class,
		'help'				=> 'Your grid has a maximum of 12 columns, so make sure the combined width of your visible columns does not exceed 12.',
		'data-responsive-option' => $responsive_option,
		'responsive'		=> true,
	);
	// font family
	$options['table_' . $column . '_font_family'] = array(
		'data-input-type' 	=> 'select-fonts',
		'title'		 		=> 'Font Family',
		'size'		 		=> 'span2',
		'class'				=> 'editor-listen',
		'data-handler'		=> 'apg',
		'data-target'		=> '.table-column-' . $column,
		'default' 	 		=> 'none',
		'select-box-values' => $this->fonts,
		'style-class'		=> $style_class,
	);
	// align
	$options['table_' . $column . '_align'] = array(
		'data-input-type' 			=> 'switch',
		'switch-type'				=> 'twoway',
		'title'		 				=> 'Align',
		'size'		 				=> 'span2',
		'class'						=> 'editor-listen',
		'data-handler'				=> 'apg',
		'default' 	 				=> 'left',
		'data-target'				=> '.table-column-' . $column,
		'style-class'				=> $style_class,
		'switch-values' => array(
			'left'	=> 'Left',
			'right'  	=> 'Right',
		),
	);
	// color
	$options['table_' . $column . '_color'] = array(
		'title'				=> 'Text Color',
		'data-css-attribute'=> 'color',
		'data-style-option' => true,
		'size'				=> 'span2',
		'data-input-type'	=> 'color',
		'default'			=> $column_defaults['color'],
		'class'				=> 'color-picker admin-listen-handler',
		'data-handler' 		=> 'colorPicker',
		'data-target'		=> '.table-column-' . $column,
		'data-picker'		=> 'apg',
		'style-class'		=> $style_class,
	);
	// hover color
	$options['table_' . $column . '_hover_color'] = array(
		'title'				=> 'Mouseover Color',
		'data-css-attribute'=> 'color',
		'data-style-option' => true,
		'size'				=> 'span2',
		'data-input-type'	=> 'color',
		'default'			=> $hover_text_color,
		'class'				=> 'color-picker admin-listen-handler',
		'data-handler' 		=> 'colorPicker',
		'data-target'		=> '.table-column-' . $column,
		'data-picker'		=> 'apg',
		'style-class'		=> $style_class,
	);
}