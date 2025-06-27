<?php

// -----------------------------------------
// semplice
// /admin/customize/cursor.php
// -----------------------------------------

if(!class_exists('cursor')) {
	class cursor extends admin_api{

		// constructor
		public function __construct() {}

		// output
		public function output() {
			// return output
			return '';
		}
		// generate css
		public function generate_css($is_frontend) {

			// output
			$cursor_css = '';

			// get cursor options
			$cursor = semplice_customize('cursor');

			// selector
			$selector = '.semplice-cursor #semplice-cursor';

			// is array?
			if(is_array($cursor)) {
				// color
				if(isset($cursor['color'])) {
					$cursor_css .= $selector . ' .semplice-cursor-inner { background-color: ' . $cursor['color'] . '; }';
				}
				// size
				if(isset($cursor['size'])) {
					$cursor_css .= $selector . ' { width: ' . $cursor['size'] . 'px; height: ' . $cursor['size'] . 'px; }';
				}
				// blend mode
				if(isset($cursor['blend_mode'])) {
					$cursor_css .= $selector . ' { mix-blend-mode: ' . $cursor['blend_mode'] . '; }';
				}
				// mouseover blend mode
				if(isset($cursor['mouseover_blend_mode'])) {
					$cursor_css .= $selector . '.mouseover-cursor { mix-blend-mode: ' . $cursor['mouseover_blend_mode'] . '; }';
				}
				// font family
				if(isset($cursor['font_family'])) {
					$cursor_css .= $selector . ' .semplice-cursor-inner .cursor-text { ' . semplice_get_font_family($cursor['font_family']) . ' }';
				}
				// font size
				if(isset($cursor['font_size'])) {
					$cursor_css .= $selector . ' .semplice-cursor-inner .cursor-text { font-size: ' . $cursor['font_size'] . '; }';
				}
				// letter spacing
				if(isset($cursor['letter_spacing'])) {
					$cursor_css .= $selector . ' .semplice-cursor-inner .cursor-text { letter-spacing: ' . $cursor['letter_spacing'] . '; margin-right: -' . $cursor['letter_spacing'] . '; }';
				}
				// text transform
				if(isset($cursor['text_transform'])) {
					$cursor_css .= $selector . ' .semplice-cursor-inner .cursor-text { text-transform: ' . $cursor['text_transform'] . '; }';
				}
				// text color
				if(isset($cursor['inner_color'])) {
					$cursor_css .= $selector . ' .semplice-cursor-inner .cursor-text { color: ' . $cursor['inner_color'] . '; }';
					$cursor_css .= $selector . ' .semplice-cursor-inner .cursor-icon svg { fill: ' . $cursor['inner_color'] . '; }';
				}
			}
			
			// output
			return $cursor_css;
		}
	}

	// instance
	admin_api::$customize['cursor'] = new cursor;
}

?>