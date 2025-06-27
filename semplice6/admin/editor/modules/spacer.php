<?php

// -----------------------------------------
// semplice
// admin/editor/modules/spacer/module.php
// -----------------------------------------

if(!class_exists('sm_spacer')) {
	class sm_spacer {

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
			
			// define styles
			$styles = '';

			// background-color
			if(isset($values['options']['background-color'])) {
				$styles .= '#content-holder #' . $id . ' .spacer { background-color: ' . $values['options']['background-color'] . '; }';
			}

			// height
			if(isset($values['options']['height'])) {
				$styles .= '#content-holder #' . $id . ' .spacer { height: ' . $values['options']['height'] . '; }';
			}

			// get breakpoints
			$breakpoints = semplice_get_breakpoints();
			// iterate breakpoints
			foreach ($breakpoints as $breakpoint => $width) {
				// css
				$css = '';
				// align
				if(isset($values['options']['height_' . $breakpoint])) {
					$css .= '[data-breakpoint="##breakpoint##"] #content-holder #' . $id . ' .spacer { height: ' . $values['options']['height_' . $breakpoint] . '; }';
				}			
				// add to css output
				if(!empty($css)) {
					if(true === $this->is_editor) {
						$styles .= str_replace('##breakpoint##', $breakpoint, $css);
					} else {
						$styles .= '@media screen' . $width['min'] . $width['max'] . ' { ' . str_replace('[data-breakpoint="##breakpoint##"] ', '', $css) . '}';
					}
				}
			}

			// define output
			$this->output['css'] = $styles;
			$this->output['html'] = '
				<div class="spacer-container">
					<div class="is-content">
						<div class="spacer"><!-- horizontal spacer --></div>
					</div>
				</div>
			';

			// output
			return $this->output;
		}

		// output frontend
		public function output_frontend($values, $id) {
			// is editor
			$this->is_editor = false;
			// same as editor
			return $this->output_editor($values, $id);
		}
	}

	// instance
	editor_api::$modules['spacer'] = new sm_spacer;
}