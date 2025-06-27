<?php

// -----------------------------------------
// semplice
// admin/atts/partials/cursor.php
// -----------------------------------------


// define cursor types
$cursor_types = array(
	'none'			=> 'No text or icon',
	'text' 			=> 'Text',
	'top-arrow'		=> 'Top Arrow',
	'bottom-arrow'	=> 'Bottom Arrow',
	'left-arrow' 	=> 'Left Arrow',
	'right-arrow' 	=> 'Right Arrow',
	'zoom-in'		=> 'Zoom In',
	'ba'			=> 'Before After Handle',
	'drag'			=> 'Drag'
);

// add default to blend modes
$this->blend_modes['default'] = 'Default';

// define cursor atts
$options = array(
	'title'	 		=> 'Individual Mouseovers',
	'description' 	=> 'Define the text or icon in your cursor for specific mouseovers. Custom text will only get applied if \'Text\' is selected for the mouseover.',
	'break'			=> '1,2,2,1,1,2,3,2,3,2,3,2,3,2,3,2,3,2,3,2,3,2,3,2,3,2,3,2,3,2,3,2,3,2,3,2,3',
	'style-class'	=> 'cursor-mouseover-selection',
	'settings-title-2' => array(
		'data-input-type' 	=> 'settings-title',
		'switch-type'		=> 'twoway',
		'title'				=> 'settings-title',
		'hide-title'		=> true,
		'size'				=> 'span4',
		'default' 	 		=> 'Text Formatting',
		'style-class'		=> 'settings-title settings-title-top',
	),
	'font_family' => array(
		'data-input-type' 	=> 'select-fonts',
		'title'		 		=> 'Font Family',
		'size'		 		=> 'span2',
		'class'				=> 'admin-listen-handler',
		'data-handler'		=> 'advanced',
		'default' 	 		=> 'none',
		'select-box-values' => $this->fonts,
	),
	'text_transform' => array(
		'data-input-type' => 'select-box',
		'data-css-attribute' => 'text-transform',
		'title'		 => 'Text Transform',
		'size'		 => 'span2',
		'class'				=> 'admin-listen-handler',
		'data-handler'		=> 'advanced',
		'default' 	 => 'uppercase',
		'select-box-values' => array(
			'none'			=> 'None',
			'uppercase'		=> 'Uppercase',
			'lowercase'		=> 'Lowercase'
		),
	),
	'font_size' => array(
		'data-css-attribute' => 'font-size',
		'title'			=> 'Font Size',
		'size'			=> 'span2',
		'offset'		=> false,
		'data-input-type' 	=> 'range-slider',
		'default'		=> 12,
		'min'			=> 6,
		'max'			=> 999,
		'class'				=> 'admin-listen-handler',
		'data-handler'		=> 'advanced',
		'data-has-unit'	=> true,
		'data-range-slider' => 'advanced',
	),
	'letter_spacing' => array(
		'data-css-attribute'=> 'letter-spacing',
		'title'				=> 'Letter Spacing',
		'size'				=> 'span2',
		'data-input-type' 	=> 'range-slider',
		'default'			=> 10,
		'min'				=> 0,
		'max'				=> 9999,
		'data-negative' 	=> true,
		'data-has-unit'		=> true,
		'data-divider'		=> 10,
		'class'				=> 'admin-listen-handler',
		'data-handler'		=> 'advanced',
		'data-range-slider' => 'advanced',
	),
	'settings-title-3' => array(
		'data-input-type' 	=> 'settings-title',
		'switch-type'		=> 'twoway',
		'title'				=> 'settings-title',
		'hide-title'		=> true,
		'size'				=> 'span4',
		'default' 	 		=> 'Customize Mouseover for',
		'style-class'		=> 'settings-title',
	),
	'custom_mouseovers' => array(
		'data-input-type' 	=> 'select-box',
		'title'				=> 'Customize Mouseover for',
		'hide-title'		=> true,
		'size'				=> 'span4',
		'class'				=> 'admin-listen-handler',
		'data-handler'  	=> 'advanced',
		'default' 	 		=> 'logo',
		'data-visibility-switch' 	=> true,
		'data-visibility-values' 	=> 'logo,nav,nextprev_prev,nextprev_next,pg,apg,apg_prev,apg_next,gallery_prev,gallery_next,gallery_drag,lightbox,mailchimp,ba,back_to_top,show_more',
		'data-visibility-prefix'	=> 'ov-adv-custom-mouseovers',
		'select-box-values' => array(
			'logo' 			=> 'Site Logo / Name',
			'nav'  			=> 'Navigation',
			'nextprev_prev' => 'Next / Prev &mdash; Prev',
			'nextprev_next' => 'Next / Prev &mdash; Next',
			'pg' 			=> 'Portfolio Grid',
			'apg'			=> 'Advanced Portfolio Grid',
			'apg_prev'		=> 'Advanced Portfolio Grid &mdash; Prev',
			'apg_next'		=> 'Advanced Portfolio Grid &mdash; Next',
			'gallery_prev'	=> 'Gallery Slider &mdash; Prev',
			'gallery_next'	=> 'Gallery Slider &mdash; Next',
			'gallery_drag'	=> 'Gallery Slider &mdash; Freescroll',
			//'button'		=> 'Button Module',
			'lightbox'		=> 'Lightbox Zoom-In',
			'mailchimp'		=> 'Mailchimp Submit',
			'ba'			=> 'Before/After Handle',
			'back_to_top'	=> 'Back to Top',
			'show_more'		=> 'Show More (cover)'
		),
	),
);

// mouseovers
$mouseovers = array(
	'logo' 			=> array('title' => 'Site Logo/Name', 'default' => 'none', 'text' => ''),
	'nav'  			=> array('title' => 'Navigation', 'default' => 'none', 'text' => ''),
	'nextprev_prev' => array('title' => 'Next/Prev &mdash; Prev', 'default' => 'left-arrow', 'text' => ''),
	'nextprev_next' => array('title' => 'Next/Prev &mdash; Next', 'default' => 'right-arrow', 'text' => ''),
	'pg' 			=> array('title' => 'Portfolio Grid', 'default' => 'none', 'text' => ''),
	'apg'			=> array('title' => 'Advanced Portfolio Grid', 'default' => 'text', 'text' => 'View'),
	'apg_prev'		=> array('title' => 'APG &mdash; Prev', 'default' => 'left-arrow', 'text' => ''),
	'apg_next'		=> array('title' => 'APG &mdash; Next', 'default' => 'right-arrow', 'text' => ''),
	'gallery_prev'	=> array('title' => 'Gallery Slider &mdash; Prev', 'default' => 'left-arrow', 'text' => ''),
	'gallery_next'	=> array('title' => 'Gallery Slider &mdash; Next', 'default' => 'right-arrow', 'text' => ''),
	'gallery_drag'	=> array('title' => 'Gallery Slider &mdash; Freescroll', 'default' => 'drag', 'text' => ''),
	//'button'		=> array('title' => 'Button Module', 'default' => 'none', 'text' => ''),
	'lightbox'		=> array('title' => 'Lightbox Zoom-In', 'default' => 'zoom-in', 'text' => ''),
	'mailchimp'		=> array('title' => 'Mailchimp Submit', 'default' => 'text', 'text' => 'Send'),
	'ba'			=> array('title' => 'Before / After Handle', 'default' => 'ba', 'text' => ''),
	'back_to_top'	=> array('title' => 'Back to top', 'default' => 'top-arrow', 'text' => ''),
	'show_more'		=> array('title' => 'Show More (cover)', 'default' => 'bottom-arrow', 'text' => '')
);
// iterate mouseovers
foreach($mouseovers as $mouseover => $mouseover_options) {
	$options[$mouseover . '_cursor_type'] = array(
		'title'				=> $mouseover_options['title'],
		'size'				=> 'span2',
		'data-input-type'	=> 'select-box',
		'class'				=> 'admin-listen-handler',
		'data-handler'  	=> 'advanced',
		'default'			=> $mouseover_options['default'],
		'select-box-values' => $cursor_types,
		'style-class'		=> 'ov-adv-custom-mouseovers-' . $mouseover
	);
	$options[$mouseover . '_cursor_text'] = array(
		'title'				=> 'Custom Text',
		'size'				=> 'span2',
		'data-input-type'	=> 'input-text',
		'class'				=> 'admin-listen-handler',
		'data-handler'  	=> 'advanced',
		'placeholder'		=> 'View',
		'default'			=> $mouseover_options['text'],
		'style-class'		=> 'ov-adv-custom-mouseovers-' . $mouseover
	);
	$options[$mouseover . '_cursor_blendmode'] = array(
		'title'				=> 'Mix Blend Mode',
		'size'				=> 'span2',
		'data-input-type'	=> 'select-box',
		'class'				=> 'admin-listen-handler',
		'data-handler'  	=> 'advanced',
		'default'			=> 'default',
		'select-box-values' => $this->blend_modes,
		'style-class'		=> 'ov-adv-custom-mouseovers-' . $mouseover
	);
	$options[$mouseover . '_cursor_color'] = array(
		'title'			=> 'Cursor Color',
		'size'			=> 'span1',
		'data-input-type'	=> 'color',
		'default'		=> 'transparent',
		'class'			=> 'color-picker admin-listen-handler',
		'data-handler'  => 'colorPicker',
		'data-picker'	=> 'advanced',
		'style-class'	=> 'ov-adv-custom-mouseovers-' . $mouseover
	);
	$options[$mouseover . '_cursor_inner_color'] = array(
		'title'			=> 'Text/Icon Color',
		'size'			=> 'span1',
		'data-input-type'	=> 'color',
		'default'		=> 'transparent',
		'class'			=> 'color-picker admin-listen-handler',
		'data-handler'  => 'colorPicker',
		'data-picker'	=> 'advanced',
		'style-class'	=> 'ov-adv-custom-mouseovers-' . $mouseover
	);
}