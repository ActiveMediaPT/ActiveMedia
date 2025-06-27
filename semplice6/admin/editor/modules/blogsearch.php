<?php

// -----------------------------------------
// semplice
// admin/editor/modules/blogsearch.php
// -----------------------------------------

if(!class_exists('sm_blogsearch')) {
	class sm_blogsearch {

		public $output;
		public $is_editor;

		// constructor
		public function __construct() {
			// define output
			$this->output = array(
				'html' => '',
				'css'  => '',
			);
			// is editor
			$this->is_editor = true;
		}

		// output editor
		public function output_editor($values, $id) {
			
			// extract options
			extract( shortcode_atts(
				array(
					'placeholder'					=> 'Search posts',
					'color'							=> '#000000',
					'bg_color' 						=> '#f5f5f5',
					'placeholder_color'				=> '#999999',
					'font_family'					=> 'inter_regular',
					'font_size'						=> '0.8888888888888889rem',
					'letter_spacing'				=> '0rem',
					'padding_ver'					=> '0.8888888888888889rem',
					'padding_hor'					=> '1.111111111111111rem',
					'icon_padding'					=> '1.111111111111111rem',
					'icon_color'					=> '#aaaaaa',
					'mouseover_color'				=> '#000000',
					'placeholder_mouseover_color'	=> '#666666',
					'bg_mouseover_color'			=> '#e9e9e9',
					'icon_mouseover_color'			=> '#666666'
				), $values['options'] )
			);

			// define output
			$output = semplice_get_blog_search($font_family, $placeholder);

			// placeholder
			$css = $this->get_placeholder_css($id, $placeholder_color, false) . $this->get_placeholder_css($id, $placeholder_mouseover_color, true);

			// search field
			$css .= '#' . $id . ' .search-field {
					color: ' . $color . ';
					background-color: ' . $bg_color . ';
					font-size: ' . $font_size . ';
					letter-spacing: ' . $letter_spacing . ';
					padding: ' . $padding_ver . ' ' . $padding_hor . ';
				}
				#' . $id . ' .blogsearch-icon {
					right: ' . $icon_padding. ';
				}
				#' . $id . ' .blogsearch-icon svg {
					fill: ' . $icon_color. ';
				}
				#' . $id . ' .search-field:hover {
					color: ' . $mouseover_color . ';
					background-color: ' . $bg_mouseover_color . ';
				}
				#' . $id . ' .search-form:hover .blogsearch-icon svg {
					fill: ' . $icon_mouseover_color . ';
				}
			';

			// get breakpoint css
			$css .= $this->breakpoint_css($values['options'], '[data-breakpoint="##breakpoint##"] #' . $id, $padding_ver, $padding_hor);

			// add to html output
			$this->output['html'] = $output;

			// add to css output
			$this->output['css'] = $css;

			// output
			return $this->output;
		}

		// get placeholder css
		public function get_placeholder_css($id, $color, $is_mouseover) {
			$hover = '';
			if(true === $is_mouseover) {
				$hover = ':hover';
			}
			return '
				#' . $id . ' input' . $hover . '::placeholder { color: ' . $color . '; }
			';
		}

		// breakpoint css
		public function breakpoint_css($styles, $selector, $padding_ver, $padding_hor) {
			// output css
			$output_css = '';
			// get breakpoints
			$breakpoints = semplice_get_breakpoints();
			// iterate breakpoints
			foreach ($breakpoints as $breakpoint => $width) {
				// css
				$css = '';
				// font size
				if(isset($styles['font_size_' . $breakpoint])) {
					$css .= $selector . ' .search-field { font-size: ' . $styles['font_size_' . $breakpoint] . '; }';
				}
				// letter spacing
				if(isset($styles['letter_spacing_' . $breakpoint])) {
					$css .= $selector . ' .search-field { letter-spacing: ' . $styles['letter_spacing_' . $breakpoint] . '; }';
				}
				// icon padding
				if(isset($styles['icon_padding_' . $breakpoint])) {
					$css .= $selector . ' .blogsearch-icon { right: ' . $styles['icon_padding_' . $breakpoint] . '; }';
				}
				// padding ver
				if(isset($styles['padding_ver_' . $breakpoint])) {
					$padding_ver = $styles['padding_ver_' . $breakpoint];
				}
				// padding hor
				if(isset($styles['padding_hor_' . $breakpoint])) {
					$padding_hor = $styles['padding_hor_' . $breakpoint];
				}
				// set padding
				$css .= $selector . ' .search-field { padding: ' . $padding_ver . ' ' . $padding_hor . '; }';			
				// add to css output
				if(!empty($css)) {
					if(true === $this->is_editor) {
						$output_css .= str_replace('##breakpoint##', $breakpoint, $css);
					} else {
						$output_css .= '@media screen' . $width['min'] . $width['max'] . ' { ' . str_replace('[data-breakpoint="##breakpoint##"] ', '', $css) . '}';
					}
				}
			}
			// return
			return $output_css;
		}

		// output frontend
		public function output_frontend($values, $id) {
			// set editor to false
			$this->is_editor = false;
			// same as editor
			return $this->output_editor($values, $id);
		}
	}

	// instance
	editor_api::$modules['blogsearch'] = new sm_blogsearch;
}