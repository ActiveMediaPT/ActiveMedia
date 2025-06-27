<?php

// -----------------------------------------
// semplice
// admin/editor/modules/text.php
// -----------------------------------------

if(!class_exists('sm_text')) {
	class sm_text {

		public $output;

		// constructor
		public function __construct() {
			// define output
			$this->output = array(
				'html' => '',
				'css'  => '',
			);
		}

		// output editor
		public function output_editor($values, $id) {

			// get content
			$content = $values['content']['xl'];
			
			// add paragraph to content
			$this->output['html'] = '<div class="is-content wysiwyg-editor wysiwyg-edit" data-wysiwyg-id="' . $id . '">' . $content  . '</div>';

			// output
			return $this->output;
		}

		// output frontend
		public function output_frontend($values, $id) {

			// get content
			$content = $values['content']['xl'];

			// add paragraph to content
			$this->output['html'] = '<div class="is-content' . semplice_has_animate_gradient($values['motions']) . '">' . $content . '</div>';

			// output
			return $this->output;
		}
	}

	// instance
	editor_api::$modules['text'] = new sm_text;
}