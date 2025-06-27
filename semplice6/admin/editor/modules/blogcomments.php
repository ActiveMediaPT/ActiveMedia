<?php

// -----------------------------------------
// semplice
// admin/editor/modules/blogcomments.php
// -----------------------------------------

if(!class_exists('sm_blogcomments')) {
	class sm_blogcomments {

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
					'comments_visibility' => 'visible'
				), $values['options'] )
			);

			// define outpout
			$output = '';

			// is editor?
			if($comments_visibility != 'hidden' && false === $this->is_editor) {
				// get post id
				$post_id = get_the_ID();
				// args
				$args = array(
					'p'				 => $post_id,
					'posts_per_page' => 1,
					'post_type' 	 => 'post',
				);
				// get posts
				$query = new WP_Query($args);
				// check if posts there
				if($query->have_posts()) {
					while($query->have_posts()) {
						// the post
						$query->the_post();
						// get comments
						$comments = get_comments('post_id=' . get_the_ID() . '&orderby=comment_parent&status=approve');
						// output
						$output = semplice_get_comments('custom', $comments, $values['options']);
					}
				}
			} else {
				$output = semplice_comments_customize($values['options']);
			}

			// visiblity
			if($comments_visibility != 'hidden' || true === $this->is_editor) {
				$output = '
					<div class="is-content">
						<div class="blogposts-comments">
							' . $output . '
						</div>
					</div>
				';
				// is frontend?
				if(false === $this->is_editor) {
					$output = '<div id="comments">' . $output . '</div>';
				}
			} else {
				$output = '';
			}

			// add to html output
			$this->output['html'] = $output;

			// add to css
			$this->output['css'] = semplice_get_comments_css($values['options'], '#' . $id, false);

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
	editor_api::$modules['blogcomments'] = new sm_blogcomments;
}