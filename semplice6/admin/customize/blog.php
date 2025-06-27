<?php

// -----------------------------------------
// semplice
// /admin/blog.php
// -----------------------------------------

if(!class_exists('blog')) {
	class blog {

		// pub
		public $content;

		// constructor
		public function __construct() {
			global $semplice_get_content;
			$this->content = $semplice_get_content;
		}

		// output
		public function output() {
			// output air
			$output = array(
				'html' => $this->content->customize(),
				'css'  => $this->get_css(true),
			);

			return $output;
		}

		// get css
		public function get_css($is_admin) {

			// output
			$css = '';

			// blog options
			$options = semplice_customize('blog');

			// check if blog is array
			if(is_array($options)) {
				// blog alignment
				if(isset($options['blog_alignment'])) {
					$css .= '.is-frontend .post .row, #semplice-content .post .row { justify-content: ' . $options['blog_alignment'] . '; }';
				}
				// bg color
				if(isset($options['blog_bg_color'])) {
					if($is_admin) {
						$css .= '.blog-settings #content-holder { background-color: ' .$options['blog_bg_color'] . '; }';
					} else {
						$css .= '#content-holder, #content-holder .transition-wrap, #content-holder .posts { background-color: ' . $options['blog_bg_color'] . '; }';
					}
				}
				// display content
				if(isset($options['display_content']) && $options['display_content'] == 'top') {
					$css .= '#content-holder .sections { margin-top: 0px !important; }';
				}
				// divider color
				if(isset($options['blog_divider_color'])) {
					$css .= '.post-divider { background-color: ' . $options['blog_divider_color'] . '; }';
				}
				// blog meta font size
				if(isset($options['blog_head_meta_font_size'])) {
					$css .= '.post .post-heading p a, .post .post-heading p span { font-size: ' . $options['blog_head_meta_font_size'] . '; }';
				}
				// blog meta alignment
				if(isset($options['blog_head_meta_alignment'])) {
					$css .= '.post .post-heading p { text-align: ' . $options['blog_head_meta_alignment'] . '; }';
				}
				// blog meta link color
				if(isset($options['blog_head_meta_color'])) {
					$css .= '.post .post-heading p a, .post .post-heading p span { color: ' . $options['blog_head_meta_color'] . '; }';
				}
				// blog meta mouseover color
				if(isset($options['blog_head_meta_mouseover_color'])) {
					$css .= '.post .post-heading p a:hover { color: ' . $options['blog_head_meta_mouseover_color'] . '; }';
				}
				// blog title alignment
				if(isset($options['blog_title_alignment'])) {
					$css .= '.post .post-heading h2 { text-align: ' . $options['blog_title_alignment'] . '; }';
				}
				// blog title color
				if(isset($options['blog_title_color'])) {
					$css .= '.post .post-heading h2 a, .posts .post-heading h2 { color: ' . $options['blog_title_color'] . '; }';
				}
				// blog title mouseover
				if(isset($options['blog_title_mouseover_color'])) {
					$css .= '.post .post-heading h2 a:hover { color: ' . $options['blog_title_mouseover_color'] . '; opacity: 1; }';
				}
				// content text color
				if(isset($options['blog_text_color'])) {
					$css .= '.post .post-content { color: ' . $options['blog_text_color'] . '; }';
				}
				// content link color
				if(isset($options['blog_link_color'])) {
					$css .= '.post .post-content a, .blog-pagination a { color: ' . $options['blog_link_color'] . '; }';
				}
				// content mouseover color
				if(isset($options['blog_mouseover_color'])) {
					$css .= '.post .post-content a:hover { color: ' . $options['blog_mouseover_color'] . '; }';
				}
				// blog meta font size
				if(isset($options['blog_meta_font_size'])) {
					$css .= '#content-holder .post .post-meta * { font-size: ' . $options['blog_meta_font_size'] . '; }';
				}
				// blog meta alignment
				if(isset($options['blog_meta_alignment'])) {
					$css .= '.post .post-meta * { text-align: ' . $options['blog_meta_alignment'] . '; }';
				}
				// blog meta link color
				if(isset($options['blog_meta_color'])) {
					$css .= '.post .post-meta { color: ' . $options['blog_meta_color'] . '; }';
				}
				// blog meta link color
				if(isset($options['blog_meta_link_color'])) {
					$css .= '.post .post-meta a { color: ' . $options['blog_meta_link_color'] . '; }';
				}
				// blog meta mosueover color
				if(isset($options['blog_meta_mouseover_color'])) {
					$css .= '.post .post-meta a:hover { color: ' . $options['blog_meta_mouseover_color'] . '; }';
				}
				// blog comments options
				$css .= semplice_get_comments_css($options, '#comments', $is_admin);
			}

			// correct the author display
			if($is_admin) {
				$css .= '
					cite.fn { top: -7px !important; }
					.comment-meta a { margin-top: -5px !important; }
				';
			}

			// get share box css
			$css .= semplice_share_box_css($options, 'share-holder');

			// return
			return $css;
		}
	}

	// instance
	admin_api::$customize['blog'] = new blog;
}

?>