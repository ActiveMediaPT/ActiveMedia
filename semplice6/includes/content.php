<?php

// -----------------------------------------
// semplice
// /includes/content.php
// -----------------------------------------

// include editor
require get_template_directory() . '/includes/blog.php';

class semplice_get_content extends semplice_blog {

	// public vars
	public $db, $editor, $rev_table_name;

	// constructor
	public function __construct() {

		// database
		global $wpdb;
		$this->db = $wpdb;
		$this->rev_table_name = $wpdb->prefix . 'semplice_revisions';

		// editor api
		global $editor_api;
		$this->editor = $editor_api;
	}

	// get content main function
	public function get($post_id, $is_preview, $paged, $filter, $url, $script_execution) {

		// get is_semplice status
		$is_semplice = get_post_meta($post_id, '_is_semplice', true);

		// if taxonomy is set, set post id to posts
		if(false !== $filter && $filter['type'] != 'singlepost') {
			$post_id = 'posts';
		}
		
		// post exists?
		if($post_id != 'notfound') {
			// is blog overview?
			if($post_id == 'posts') {
				// show latest blog posts
				$output = $this->posts($filter, $url, $paged, $script_execution);
			} else {
				// is semplice activated?
				if($is_semplice) {
					// load content from the semplice editor
					$output = $this->semplice($post_id, $url, $is_preview, $script_execution, $paged);
				} else {
					// load wordpress wysiwyg content
					$output = $this->post($filter, $post_id, $is_preview);
				}
			}
		} else {
			// show 404
			$output = $this->default_content('not-found');
		}

		// set semplice status
		if(is_array($output)) {
			if($is_semplice) {
				$output['is_semplice'] = true;
			} else {
				$output['is_semplice'] = false;
			}
		}
		
		// return output
		return $output;
	}

	// default content
	public function default_content($type) {

		// set atts
		switch($type) {
			case 'empty-preview':
				$output = array(
					'css'  => 'body { background: #eeeeee; }',
					'html' => semplice_get_template('empty_content', false),
				);
			break;
			case 'empty-posts':
				$output = array(
					'css'  => 'body { background: #eeeeee; }',
					'html' => semplice_get_template('no_posts', false),
				);
			break;
			case 'not-found':
				$output = array(
					'css'  => 'body { background: #eeeeee; }',
					'html' => semplice_get_template('404', false),
				);
			break;
			case 'empty-semplice':
				$output = array(
					'css'  => 'body { background: #eeeeee; }',
					'html' => semplice_get_template('empty_semplice', false),
				);
			break;
		}

		// add sections wrap
		$output['html'] = '<section class="content-block">' . $output['html'] . '</section>';

		// return output
		return $output;	
	}

	// get semplice content
	public function semplice($post_id, $url, $is_preview, $script_execution, $page_num) {
		// get ram
		$ram = $this->get_ram($post_id, $is_preview);

		// is coverslider
		$is_coverslider = semplice_boolval(get_post_meta($post_id, '_is_coverslider', true));

		// check if ram is not empty
		if(null !== $ram) {
			// get content from ram
			if(isset($ram['order']) && !empty($ram['order']) || true === $is_coverslider) {
				// check if there is only a non visible cover
				if($this->has_content($ram['order'], $ram) || true === $is_coverslider) {
					// add post id to ram
					$ram['post_id'] = $post_id;
					// add paged
					$ram['posts_filter'] = array(
						'type' 			=> 'page',
						'template_type'	=> false,
						'page_num' 		=> $page_num,
						'meta'			=> false,
						'url'			=> $url
					);
					// script execution
					$ram['script_execution'] = $script_execution;
					// get content
					$output = $this->editor->get_content($ram, 'frontend', false, $is_coverslider);
					// add motion css
					if(!empty($output['motion_css'])) {
						$output['css'] .= $output['motion_css'];
					}
				} else {
					$output = $this->default_content('empty-semplice');
				}
				
			} else {
				$output = $this->default_content('empty-semplice');
			}
			// add branding
			if(isset($ram['branding'])) {
				$output['branding'] = $ram['branding'];
			}
		} else if($is_preview) {
			// show empty preview
			$output = $this->default_content('empty-preview');
		} else {
			$output = $this->default_content('empty-semplice');
		}

		// add footer
		$output = $this->content_footer($output, $post_id);

		// output
		return $output;
	}

	// get blog posts
	public function posts($filter, $url, $page_num, $script_execution) {
		// is taxonomy or search and has a custom template?
		$template = $this->get_blog_template(false, $filter, $url, false, $page_num, $script_execution);
		// check template
		if(false !== $template && is_array($template)) {
			$output = $template;
		} else {
			// posts per page
			$posts_per_page = get_option('posts_per_page');

			// pagination
			if($page_num == 0) {
				$page_num = 1;
			}

			// check if auth
			$args = array(
				'posts_per_page' => $posts_per_page,
				'offset'		 => ($page_num - 1) * $posts_per_page,
				'post_type' 	 => 'post',
			);

			// filter
			$args = semplice_get_blog_args($args, $filter);

			// define filter
			if(false === $filter) {
				$filter = array(
					'type' => 'posts',
					'meta' => false
				);
			}
			// add url to filter
			$filter['url'] = $url;

			// blog overview
			$output = $this->loop($args, $page_num, $filter, false);			
		}
		// add footer
		$output = $this->content_footer($output, false);

		// return output
		return $output;
	}

	// get single post
	public function post($filter, $post_id, $is_preview) {
		// is taxonomy or search and has a custom template?
		$template = $this->get_blog_template($post_id, $filter, false, true, 1, 'normal');
		// check template
		if(false !== $template && is_array($template)) {
			$output = $template;
		} else {
			// get post type
			$post_type = get_post_type($post_id);
			// check if auth
			$args = array(
				'p'				 => $post_id,
				'posts_per_page' => 1,
				'post_type' 	 => $post_type,
			);
			// get post
			$output = $this->loop($args, 1, false, true);
		}
		// add footer
		$output = $this->content_footer($output, false);
		// return
		return $output;
	}

	// has content?
	public function has_content($sections, $content) {

		// default set to false
		$has_content = false;
		$sections_count = count($sections);

		// more than 1 unit + cover?
		if($sections_count > 1) {
			$has_content = true;
		}

		// is only 1 section but has a visible cover?
		if($sections_count == 1 && isset($content['cover']) && isset($content['cover_visibility']) && $content['cover_visibility'] == 'visible') {
			$has_content = true;
		}

		// only 1 section but not a cover
		if($sections_count == 1 && !isset($sections['cover'])) {
			$has_content = true;
		}

		// return
		return $has_content;
	}

	// footer
	public function content_footer($output, $post_id) {
		// get post
		global $post;

		// add next prev before footer
		$output['html'] .= semplice_project_nav_html('nextprev', true, $post_id);

		// add project panel before footer
		$output['html'] .= semplice_project_nav_html('projectpanel', true, $post_id);

		// set footer id to false
		$footer_id = false;

		// show footer
		$show_footer = true;

		// set footer motions to false per default
		$parent_motions = false;

		// get blog footer nav
		$blog_footer = false;
		$blog = semplice_customize('blog');

		if(isset($output['branding']['scroll_reveal']) && $output['branding']['scroll_reveal'] == 'disabled' || isset($blog['blog_scroll_reveal']) && $blog['blog_scroll_reveal'] == 'disabled') {
			$parent_motions = true;
		}

		// if semplice look in the post settings
		if(false !== $post_id || isset($output['template_type'])) {
			if(isset($output['template_type'])) {
				$post_settings = json_decode(get_option('semplice_template_' . $output['template_type'] . '_settings'), true);
			} else {
				$post_settings = json_decode(get_post_meta($post_id, '_semplice_post_settings', true), true);
			}
			if(is_array($post_settings)) {
				if(isset($post_settings['meta']['footer_visibility']) && false === semplice_boolval($post_settings['meta']['footer_visibility'])) {
					$show_footer = false;
				} else if(isset($post_settings['meta']['footer']) && $post_settings['meta']['footer'] != 0 && false === semplice_is_removed($post_settings['meta']['footer'])) {
					$footer_id = $post_settings['meta']['footer'];
				}
			}
		}

		if(is_object($post) && $post->post_type == 'post' && isset($blog['blog_footer']) && $blog['blog_footer'] != 0 && false === semplice_is_removed($blog['blog_footer']) && !isset($output['template_type'])) {
			$footer_id = $blog['blog_footer'];
		} else if(false === $footer_id) {
			// get global footer
			$advanced = semplice_customize('advanced');

			// is array?
			if(is_array($advanced)) {
				// get global footer
				if(isset($advanced['global_footer']) && $advanced['global_footer'] != 0 && false === semplice_is_removed($advanced['global_footer'])) {
					$footer_id = $advanced['global_footer'];
				}
			}
		}

		if(false !== $footer_id && false !== $show_footer) {

			// get ram
			$ram = $this->get_ram($footer_id, false);

			// is ram?
			if(null !== $ram) {

				// assign content
				$ram = semplice_generate_ram_ids($ram, false, false);

				// set footer
				$ram['is_footer'] = true;

				// get content
				$content = $this->editor->get_content($ram, 'frontend', false, false);

				// add motion css if there and motion is enabled in parent
				if(!empty($content['motion_css']) && true === $parent_motions) {
					$output['css'] .= $content['motion_css'];
				}
			
				foreach ($content as $type => $value) {
					if(isset($output[$type])) {
						if($type != 'js' || $type == 'js' && true === $parent_motions) {
							if($type != 'images' && $type != 'module_css') {
								$output[$type] .= $content[$type];
							} else if($type == 'module_css') {
								$output = $this->editor->module_api->module_css($output, $content);
							} else {
								$output[$type] = $output[$type] + $content[$type];
							}
						}
					}
				}
			}
		}

		return $output;
	}

	// get ram
	public function get_ram($post_id, $is_preview) {
		// define ram
		$ram = false;
		// post revision
		$post_revision = $this->editor->get_post_revision($post_id);
		// make sure this is not a post
		if(get_post_type($post_id) != 'post') {
			if($is_preview) {
				// for the preview take the active revision id from the editor instead of the published one
				$revision_id = $post_revision['active'];
				// rev table name
				$this->rev_table_name = $this->db->prefix . 'semplice_revisions';
				// get ram
				$ram = $this->db->get_var("SELECT content FROM $this->rev_table_name WHERE post_id = '$post_id' AND revision_id = '$revision_id'");
				// init masterblocks
				$ram = $this->editor->init_masterblocks($ram);
				// json decode
				$ram = json_decode($ram, true);
			} else {
				// get ram
				$ram = get_post_meta($post_id, '_semplice_content', true);
				// init masterblocks if not empty
				if(!empty($ram)) {
					$ram = $this->editor->init_masterblocks($ram);
				}
				// load content from post meta if not a preview
				$ram = json_decode($ram, true);
			}
		}
		// return
		return $ram;
	}

	// get blog tempate
	public function get_blog_template($post_id, $filter, $url, $is_single, $page_num, $script_execution) {
		// vars
		$output = false;
		$template = false;
		$template_type = 'archive';
		$meta = false;
		// blog options
		$options = semplice_customize('blog');
		// is taxonomy or search?
		if(false !== $filter) {
			// change template type if search
			if($filter['type'] == 'searchresults' || $filter['type'] == 'singlepost') {
				$template_type = $filter['type'];
			}
			// is set to custom templates?
			if(is_array($options) && isset($options['blog_templates']) && $options['blog_templates'] == 'custom') {
				// check if there is a custom template for $type
				if(false !== get_option('semplice_template_' . $template_type)) {
					$template = get_option('semplice_template_' . $template_type);
				}
			}
			// masterblocks and decode
			if(false !== $template) {
				// init masterblocks if not empty
				$template = $this->editor->init_masterblocks($template);
				// load content from post meta if not a preview
				$template = json_decode($template, true);
			}
		}
		// has template?
		if(false !== $template) {
			// add post id to ram
			$template['post_id'] = $post_id;
			// add paged
			$template['posts_filter'] = array(
				'type'			=> $filter['type'],
				'template_type' => $template_type,
				'page_num' 		=> $page_num,
				'meta' 			=> $filter['meta'],
				'url'			=> $url
			);
			// script execution
			$template['script_execution'] = $script_execution;
			// get content
			$output = $this->editor->get_content($template, 'frontend', false, false);
			// add branding
			$output['branding'] = $template['branding'];
			// get navbar
			$settings = json_decode(get_option('semplice_template_' . $template_type . '_settings'), true);
			if(false !== $settings && is_array($settings)) {
				$meta = $settings['meta'];
				if(!isset($meta['navbar_visibility']) || true === semplice_boolval($meta['navbar_visibility'])) {
					if(isset($meta['navbar'])) {
						$output['navbar'] = semplice_get_navbar($meta['navbar']);
					} else {
						$output['navbar'] = semplice_get_navbar(false);
					}
				} else if(isset($meta['navbar_visibility']) && $meta['navbar_visibility'] == 'false') {
					$output['navbar'] = array(
						'html' 			=> '',
						'css'  			=> '',
						'mobile_css'	=> array(
							'lg' => '',
							'md' => '',
							'sm' => '',
							'xs' => '',
						),
					);
				} else {
					$output['navbar'] = semplice_get_navbar(false);
				}
			} else {
				$output['navbar'] = semplice_get_navbar(false);
			}
			// add motion css
			if(!empty($output['motion_css'])) {
				$output['css'] .= $output['motion_css'];
			}
			// add type to output
			$output['template_type'] = $template_type;
		}
		// return output
		return $output;
	}
}

// -----------------------------------------
// build instance of content class
// -----------------------------------------

$semplice_get_content = new semplice_get_content();

?>