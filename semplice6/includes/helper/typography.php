<?php

// -----------------------------------------
// get font
// -----------------------------------------

function semplice_get_font($id) {
	// get fonts
	$webfonts = semplice_customize('webfonts');

	if(is_array($webfonts) && !empty($webfonts['fonts'])) {
		// is font there?
		if(array_key_exists($id, $webfonts['fonts'])) {
			$font = $webfonts['fonts'][$id]['system-name'];
		} else {
			$font = false;
		}
	} else {
		$font = semplice_get_default_fontname($id);
	}

	return $font;
}

// -----------------------------------------
// get default fontname
// -----------------------------------------

function semplice_get_default_fontname($font) {
	// get fontlist
	$fonts = semplice_get_default_fonts('display', false);
	// checkc if its default font
	if(isset($fonts[$font])) {
		return $fonts[$font];
	} else {
		return false;
	}	
}

// -----------------------------------------
// get default font list
// -----------------------------------------

function semplice_get_default_fonts($mode, $get_font) {
	$fonts = array(
		'regular' => array(
			'display-name' => 'Open Sans Regular',
			'system-name'  => 'Open Sans',
			'category'	   => 'Arial, sans-serif',
			'font-weight'  => 400,
			'font-style'   => 'normal',
		),
		'regular_italic' => array(
			'display-name' => 'Open Sans Regular Italic',
			'system-name'  => 'Open Sans',
			'category'	   => 'Arial, sans-serif',
			'font-weight'  => 400,
			'font-style'   => 'italic',
		),
		'bold' => array(
			'display-name' => 'Open Sans Bold',
			'system-name'  => 'Open Sans',
			'category'	   => 'Arial, sans-serif',
			'font-weight'  => 700,
			'font-style'   => 'normal',
		),
		'bold_italic' => array(
			'display-name' => 'Open Sans Bold Italic',
			'system-name'  => 'Open Sans',
			'category'	   => 'Arial, sans-serif',
			'font-weight'  => 700,
			'font-style'   => 'italic',
		),
		'serif_regular' => array(
			'display-name' => 'Lora Regular',
			'system-name'  => 'Lora',
			'category'	   => 'Georgia, serif',
			'font-weight'  => 400,
			'font-style'   => 'normal',
		),
		'serif_regular_italic' => array(
			'display-name' => 'Lora Regular Italic',
			'system-name'  => 'Lora',
			'category'	   => 'Georgia, serif',
			'font-weight'  => 400,
			'font-style'   => 'italic',
		),
		'serif_bold' => array(
			'display-name' => 'Lora Bold',
			'system-name'  => 'Lora',
			'category'	   => 'Georgia, serif',
			'font-weight'  => 700,
			'font-style'   => 'normal',
		),
		'serif_bold_italic' => array(
			'display-name' => 'Lora Bold Italic',
			'system-name'  => 'Lora',
			'category'	   => 'Georgia, serif',
			'font-weight'  => 700,
			'font-style'   => 'italic',
		),
		'inter_light' => array(
			'display-name' => 'Inter Light',
			'system-name'  => 'Inter',
			'category'	   => 'Arial, sans-serif',
			'font-weight'  => 300,
			'font-style'   => 'normal',
		),
		'inter_regular' => array(
			'display-name' => 'Inter Regular',
			'system-name'  => 'Inter',
			'category'	   => 'Arial, sans-serif',
			'font-weight'  => 400,
			'font-style'   => 'normal',
		),
		'inter_medium' => array(
			'display-name' => 'Inter Medium',
			'system-name'  => 'Inter',
			'category'	   => 'Arial, sans-serif',
			'font-weight'  => 500,
			'font-style'   => 'normal',
		),
		'inter_semibold' => array(
			'display-name' => 'Inter Semibold',
			'system-name'  => 'Inter',
			'category'	   => 'Arial, sans-serif',
			'font-weight'  => 600,
			'font-style'   => 'normal',
		),
		'inter_bold' => array(
			'display-name' => 'Inter Bold',
			'system-name'  => 'Inter',
			'category'	   => 'Arial, sans-serif',
			'font-weight'  => 700,
			'font-style'   => 'normal',
		),
	);

	if($mode == 'display') {
		$output = array();
		// create array to display names for the atts
		foreach ($fonts as $font => $values) {
			$output[$font] = $values['display-name'];
		}
	} else if($mode == 'get') {
		$output = $fonts[$get_font];
	} else {
		$output = $fonts;
	}
	// output
	return $output;
}

// ----------------------------------------
// get font family
// ----------------------------------------

function semplice_get_font_family($font_id) {
	// get webfonts
	$webfonts = semplice_customize('webfonts');
	// define font
	$font = false;
	// set default font to true
	$default_font = true;
	// get font
	if(is_array($webfonts) && !empty($webfonts['fonts'])) {
		// get real font id if variable webfont
		if(strpos($font_id, 'style_') !== false) {
			foreach($webfonts['fonts'] as $id => $font_array) {
				if(isset($font_array['font_type']) && $font_array['font_type'] == 'variable') {
					if(isset($font_array['styles']) && is_array($font_array['styles']) && !empty($font_array['styles']) && isset($font_array['styles'][$font_id])) {
						$font = $webfonts['fonts'][$id];
					}
				}
			}
		} else if(isset($webfonts['fonts'][$font_id])) {
			$font = $webfonts['fonts'][$font_id];
		}
		// set default font to false
		if(false !== $font) {
			$default_font = false;
		}
	}
	// if font is still false use the default fonts
	if(false === $font && false !== semplice_get_default_fontname($font_id)) {
		$font = semplice_get_default_fonts('get', $font_id);
	} else if(false === $font) {
		$font = semplice_get_default_fonts('get', 'regular');
	}

	// css
	$css = '';

	// valid array?
	if(is_array($font)) {
		// font array
		$font_atts = array(
			'family'   => '',
			'weight'   => '',
			'style'	   => '',
			'variable' => ''
		);
		// font family
		if(strpos($font['system-name'],',') !== false) {
			$font_name = explode(',', $font['system-name']);
			$font_atts['family'] = 'font-family: "' . $font_name[0] . '", "' . $font_name[1] . '", ' . $font['category'] . ';';
		} else {
			$font_atts['family'] = 'font-family: "' . $font['system-name'] . '", ' . $font['category'] . ';';
		}
		// font type
		if(isset($font['font_type']) && $font['font_type'] == 'variable') {
			// variable styles
			if(isset($font['styles']) && is_array($font['styles']) && !empty($font['styles']) && isset($font['styles'][$font_id])) {
				$style = $font['styles'][$font_id];
				// css
				$style_css = '';
				$exclude = array('name', 'font-size', 'line-height', 'letter-spacing');
				foreach ($style as $axis => $val) {
					if(!in_array($axis, $exclude)) {
						$style_css .= "'" . $axis . "' " . $val . ", ";
					}
				}
				// cut off last 2 chars
				$style_css = substr($style_css, 0, -2);
				// add to variable
				$font_atts['variable'] = 'font-variation-settings: ' . $style_css . '; font-weight: normal;';
			}
		} else {
			// font weight
			if(isset($font['font-weight-usage']) && $font['font-weight-usage'] == 'css' || true === $default_font) {
				$font_atts['weight'] = 'font-weight: ' . $font['font-weight'] . ';';
			} else {
				$font_atts['weight'] = 'font-weight: normal;';
			}
			// when a variable style is set as a font family for h1, p etc. in typography, these font variation settings will overwrite our font weight and needs to be resettet
			// inter (default) is an exception and gets a font-variation-setting because otherwise the styles from typography.scss would overwrite the normal font-weight
			if($font['system-name'] == 'Inter') {
				$font_atts['weight'] .= 'font-variation-settings: "wght" ' . $font['font-weight'] . ';';
			} else {
				$font_atts['weight'] .= 'font-variation-settings: normal;';
			}
			// style
			$font_atts['style'] = 'font-style: ' . $font['font-style'] . ';';
		}
		// css
		$css = $font_atts['family'] . $font_atts['weight'] . $font_atts['style'] . $font_atts['variable'];

	}
	// return
	return $css;
}

?>