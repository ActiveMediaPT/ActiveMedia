<?php

// -----------------------------------------
// semplice
// admin/editor/modules/blogarchives.php
// -----------------------------------------

if(!class_exists('sm_blogarchives')) {
	class sm_blogarchives {

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
					'type'					=> 'category',
					'limit'					=> 10,
					'align'					=> 'left',
					'seperator_height'		=> '0.0555555555555556rem',
					'seperator_color'		=> '#d6d6d6',
					'padding_ver'			=> '0.5555555555555556.rem',
					'padding_hor'			=> '0rem',
					'color'					=> '#000000',
					'mouseover_color'		=> '#000000',
					'font_family'			=> 'inter_regular',
					'font_size'				=> '1rem',
					'line_height'			=> '1.444444444444444rem',
					'letter_spacing'		=> '0rem',
					'text_transform'		=> 'none',
				),$values['options'])
			);

			// define output
			$output = '<div class="blogposts-archive is-content">';

			// items
			$items = '<ul>';

			// define args
			$args = array();

			// switch type
			switch($type) {
				case 'latest':
					$posts = wp_get_recent_posts(array(
						'numberposts' => $limit,
						'post_status' => 'publish'
					));
					if(false !== $posts && is_array($posts) && !empty($posts)) {
						foreach($posts as $post) {
							$items .= $this->list_item($type, $post);
						}
					}
				break;
				case 'category':
				case 'post_tag':
					$filter = $type == 'category' ? 'categories' : 'tags';
					if(isset($values['options'][$filter]) && is_array($values['options'][$filter]) && !empty($values['options'][$filter])) {
						foreach($values['options'][$filter] as $term_id) {
							$term = get_term(intval($term_id));
							if(null !== $term) {
								$items .= $this->list_item($type, $term);
							}
						}
					} else {
						$terms = get_terms(array(
							'taxonomy' => $type,
							'number'   => $limit
						));
						if(false !== $terms && is_array($terms) && !empty($terms)) {
							foreach($terms as $term) {
								$items .= $this->list_item($type, $term);
							}
						}
					}
				break;
				case 'authors':
					if(isset($values['options'][$type]) && is_array($values['options'][$type]) && !empty($values['options'][$type])) {
						foreach($values['options'][$type] as $author_id) {
							$author = get_userdata($author_id);
							if(false !== $author) {
								$items .= $this->list_item($type, $author);
							}
						}
					} else {
						$items .= wp_list_authors(array(
							'exclude_admin' => false,
							'optioncount' 	=> false,
							'show_fullname' => true,
							'hide_empty'	=> true,
							'echo'			=> false
						));
					}
				break;
				case 'posts':
					if(isset($values['options'][$type]) && is_array($values['options'][$type]) && !empty($values['options'][$type])) {
						$posts = get_posts(array(
							'post__in'	  => $values['options'][$type],
							'numberposts' => $limit
						));
						if(false !== $posts && is_array($posts) && !empty($posts)) {
							foreach($posts as $post) {
								$items .= $this->list_item($type, $post);
							}
						}
					}
				break;
			}

			// items to output or show empty state
			if($items == '<ul>') {
				$items .= $this->empty_state($type);
			}

			// add items to output
			$output .= $items . '</ul></div>';

			// search field
			$css = '#' . $id . ' .blogposts-archive ul li {
					text-align: ' . $align . ';
					border-bottom-width: ' . $seperator_height . ';
					border-color: ' . $seperator_color . ';
					padding: ' . $padding_ver . ' ' . $padding_hor . ';
				}
				#' . $id . ' .blogposts-archive ul li a {
					' . semplice_get_font_family($font_family) . '
					color: ' . $color . ';
					font-size: ' . $font_size . ';
					line-height: ' . $line_height . ';
					letter-spacing: ' . $letter_spacing . ';
					text-transform: ' . $text_transform . ';
				}
				#' . $id . ' .blogposts-archive ul li a:hover {
					color: ' . $mouseover_color . ';
				}
			';

			// get breakpoint css
			$css .= $this->breakpoint_css($values['options'], '[data-breakpoint="##breakpoint##"] #' . $id . ' .blogposts-archive ul', $padding_ver, $padding_hor);

			// add to html output
			$this->output['html'] = $output;

			// add to css output
			$this->output['css'] = $css;

			// output
			return $this->output;
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
				// align
				if(isset($styles['align_' . $breakpoint])) {
					$css .= $selector . ' li { text-align: ' . $styles['align_' . $breakpoint] . '; }';
				}
				// seperator height
				if(isset($styles['seperator_height_' . $breakpoint])) {
					$css .= $selector . ' li { border-bottom-width: ' . $styles['seperator_height_' . $breakpoint] . '; }';
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
				$css .= $selector . ' li { padding: ' . $padding_ver . ' ' . $padding_hor . '; }';
				// formatting
				$formatting = array('font_size', 'line_height', 'letter_spacing');
				foreach($formatting as $format) {
					if(isset($styles[$format . '_' . $breakpoint])) {
						$css .= $selector . ' li a { ' . str_replace('_', '-', $format) . ': ' . $styles[$format . '_' . $breakpoint] . '; }';
					}
				}				
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

		// get list
		public function list_item($type, $item) {
			if($type == 'latest') {
				return '<li class="recent-posts-' . $item['ID'] . '"><a href="' . get_permalink($item['ID']) . '">' . $item['post_title'] . '</a></li>';
			} else if($type == 'posts') {
				return '<li class="posts-' . $item->ID . '"><a href="' . get_permalink($item->ID) . '">' . $item->post_title . '</a></li>';
			} else if($type == 'category' || $type == 'post_tag') {
				return '<li class="' . $type . '-' . $item->term_id . '"><a href="' . get_term_link($item->term_id) . '">' . $item->name . '</a></li>';
			} else if($type == 'authors') {
				return '<li class="' . $type . '-' . $item->ID . '"><a href="' . get_author_posts_url($item->ID) . '">' . $item->display_name . '</a></li>';
			}
		}

		// empty
		public function empty_state($type) {
			$empty_states = array(
				'category'	=> 'categories',
				'latest'	=> 'posts',
				'post_tag'  => 'tags',
				'authors'	=> 'authors',
				'posts'		=> 'posts'
			);
			return '<li><a class="empty-archive">No ' . $empty_states[$type] . ' found</a></li>';
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
	editor_api::$modules['blogarchives'] = new sm_blogarchives;
}