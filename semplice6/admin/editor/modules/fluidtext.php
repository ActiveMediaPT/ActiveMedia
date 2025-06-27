<?php

// -----------------------------------------
// semplice
// admin/editor/modules/fluidtext.php
// -----------------------------------------

if(!class_exists('sm_fluidtext')) {
	class sm_fluidtext {

		public $output;
		public $is_editor;

		// constructor
		public function __construct() {
			// define output
			$this->output = array(
				'html' => '',
				'css'  => '',
			);
			// is frontend
			$this->is_editor = true;
		}

		// output editor
		public function output_editor($values, $id) {

			// get content
			$content = $values['content']['xl'];
			
			// add paragraph to content
			$this->output['html'] = '<div class="is-content wysiwyg-editor wysiwyg-edit" data-wysiwyg-id="' . $id . '">' . $content  . '</div>';

			// add to css
			$this->output['css'] = $this->css($values['options'], $id);

			// output
			return $this->output;
		}

		// output frontend
		public function output_frontend($values, $id) {

			// set is frontend
			$this->is_editor = false;

			// get content
			$content = $values['content']['xl'];
			
			// add paragraph to content
			$this->output['html'] = '<div class="is-content' . semplice_has_animate_gradient($values['motions']) . '">' . $content . '</div>';

			// add to css
			$this->output['css'] = $this->css($values['options'], $id);

			// output
			return $this->output;
		}

		// css
		public function css($options, $id) {
			// output
			$output = '';
			// block element
			$block_element = 'p';
			if(isset($options['block_element'])) {
				$block_element = $options['block_element'];
			}
			$values = array(
				'min-font-size' => 18,
				'max-font-size' => 72,
				'fluid-font-size' => 20,
				'fluid-line-height' => 160,
				'fluid-letter-spacing' => 0
			);
			// iterate values
			foreach ($values as $attribute => $val) {
				// has value?
				if(isset($options[$attribute])) {
					$val = $options[$attribute];
				}
				// units
				if($attribute == 'fluid-font-size') {
					$values[$attribute] = ($val / 10) . 'vw';
				} else if($attribute == 'fluid-line-height') {
					$values[$attribute] = $val . '%';
				} else if(!empty($val)) {
					$values[$attribute] = ($val / 18) . 'rem';
				}
			}
			// add to output
			$output .= '
				#' . $id . ' .is-content ' . $block_element . ' {
					font-size: clamp(' . $values['min-font-size'] . ', ' . $values['fluid-font-size'] . ', ' . $values['max-font-size'] . ') !important;
					line-height: ' . $values['fluid-line-height'] . ' !important;
					letter-spacing: ' . $values['fluid-letter-spacing'] . ' !important;
					margin-bottom: calc(' . $values['fluid-font-size'] . ' * ' . (intval($values['fluid-line-height']) / 100) . ') !important;
				}
				#' . $id . ' .is-content ' . $block_element . ':last-child {
					margin-bottom: 0px !important;
				}
			';
			// line height and letter spacing for breakpoints on frontend
			if(false === $this->is_editor) {
				$breakpoints = semplice_get_breakpoints();
				foreach ($breakpoints as $breakpoint => $width) {
					// css
					$css = '';
					// bp
					$bp = '_' . $breakpoint;
					// line-height
					if(isset($options['fluid-line-height' . $bp])) {
						$css .= '
							line-height: ' . $options['fluid-line-height' . $bp] . '% !important;
							margin-bottom: calc(' . $values['fluid-font-size'] . ' * ' . (intval($options['fluid-line-height' . $bp]) / 100) . ') !important;
						';
					}
					// letter spacing
					if(isset($options['fluid-letter-spacing' . $bp])) {
						$css .= '
							letter-spacing: ' . (floatval($options['fluid-letter-spacing' . $bp]) / 18) . 'rem !important;
						';
					}
					// add to css output
					if(!empty($css)) {
						// selector
						$output .= '@media screen' . $width['min'] . $width['max'] . ' { #' . $id . ' .is-content ' . $block_element . ' { ' . $css . ' }}';
					}
				}
			}
			// return
			return $output;
		}
	}

	// instance
	editor_api::$modules['fluidtext'] = new sm_fluidtext;
}