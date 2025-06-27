<?php

// -----------------------------------------
// semplice
// admin/editor/modules/blogposts.php
// -----------------------------------------

if(!class_exists('sm_blogposts')) {
	class sm_blogposts {

		public $output;
		public $metas;
		public $is_editor;
		public $first_meta;

		// constructor
		public function __construct() {
			// define output
			$this->output = array(
				'html' => '',
				'css'  => '',
			);
			// is editor
			$this->is_editor = true;
			// define metas (auf reihenfolge achten wegen first meta)
			$this->metas = array(
				'author'	 => '',
				'date'		 => '',
				'comment'	 => 'hidden',
				'readtime'	 => 'hidden',
				'tags'		 => '',
				'category'	 => '',
				'pagination' => '',
			);
			// first meta
			$this->first_meta = true;
			// add action
			add_action('init', array(&$this, 'init_templates'));
		}

		// output editor
		public function output_editor($values, $id) {

			// content api
			global $semplice_get_content;

			// extract options
			extract( shortcode_atts(
				array(
					'layout'					=> 'list',
					'posts_per_page'			=> '10',
					'template_posts_per_page'	=> '10',
					'offset'					=> 0,
					'width' 					=> '4',
					'content_width'				=> 8,
					'listgrid'					=> '4-8',
					'fw_thumbnail_width'		=> 12,
					'fw_content_width'			=> 12,
					'fw_title_width'			=> 12,
					'fw_meta_width'				=> 12,
					'gutter_list'				=> 30,
					'gutter_columns'			=> 30,
					'content_order'				=> array('thumb', 'category', 'title', 'content', 'meta', 'tags'),
					'filter_by'					=> 'latest',
					'order_by'					=> 'date',
					'order'						=> 'DESC',
					'categories'				=> '',
					'tags'						=> '',
					'authors'					=> '',
					'thumbnail_visibility' 		=> 'block',
					'thumbnail_alignment'  		=> 'left',
					'thumbnail_order_fullwidth'	=> 'above',
					'thumbnail_order_columns'	=> 'above',
					'pagination_visibility'		=> 'visible',
					// post seperator
					'post_seperator_visibility'	=> 'visible',
					'post_seperator_last'		=> 'visible',
					// category formatting
					'category_font_family'		=> 'inter_regular',
					'category_seperator'		=> 'middot',
					'category_seperator_color'	=> '#777777',
					// title formatting
					'title_font_family'			=> 'inter_bold',
					// post content
					'content_font_family'		=> 'serif_regular',
					// content
					'content_visibility'		=> 'visible',
					'excerpt_length'			=> 34,
					'content_font_family'		=> 'serif_regular',
					// tags
					'tags_visibility'			=> 'hidden',
					'tags_font_family'			=> 'serif_regular',
					// metas
					'meta_visibility'			=> 'visible',
					'meta_seperator'			=> 'middot',
					'meta_font_family'			=> 'serif_regular',
					'meta_align'				=> 'left',
					'meta_seperator_color'  	=> '#777777',
					'meta_seperator_padding'	=> 0,
					// meta options
					'date_format'				=> 'F j, Y',
					'date_custom'				=> '',
					// pagination 
					'pagination_font_family'	=> 'inter_regular',
					// archive
					'archive_visibility'		=> 'visible',
					'archive_font_family'	 	=> 'inter_light'
				),$values['options'])
			);

			// define output
			$output_css = '';
			$css = array('xl' => '', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => '');

			// is locked?
			$is_locked = isset($values['locked']) ? true : false;

			// archives
			$archives = array('category', 'tag', 'author', 'archive');

			// wp template
			$wp_template = false;
			if(isset($values['posts_filter']) && false !== $values['posts_filter'] && isset($values['posts_filter']['type']) && true === $is_locked) {
				$wp_template = $values['posts_filter']['type'];
			}

			// url
			$url = '';
			$page_num = 1;
			if(false === $this->is_editor && false !== $values['posts_filter']) {
				//$url = get_permalink($values['post_id']);
				// get page number
				$page_num = intval($values['posts_filter']['page_num']);
			}

			// posts per page
			if($posts_per_page == 0 || $template_posts_per_page == 0) {
				$posts_per_page = -1;
			} else if(in_array($wp_template, $archives)) {
				$posts_per_page = $template_posts_per_page;
			}

			// order by
			if($order_by == 'author') {
				$order_by = array(
					'author' => $order,
					'date'	 => 'DESC',
				);
			} else {
				$order_by = array($order_by => $order);
			}
			
			// args
			$args = array(
				'posts_per_page' 		=> $posts_per_page,
				'post_type' 	 		=> 'post',
				'post_status' 	 		=> 'publish',
				'offset'		 	  	=> ((($page_num - 1) * $posts_per_page) + $offset),
				'ignore_sticky_posts' 	=> 1,
				'orderby'        		=> $order_by
			);

			// filters
			$is_single = false;
			if(false !== $wp_template && false === $this->is_editor || $wp_template == 'singlepost') {
				$args = semplice_get_blog_args($args, $values['posts_filter']);
				if($wp_template == 'singlepost') {
					$is_single = true;
					// add font family to css
					$output_css .= '#' . $id . ' .blogposts .blogposts-content p, #' . $id . ' .blogposts .blogposts-content li, #' . $id . ' .blogposts .blogposts-content li a { ' . semplice_get_font_family($content_font_family) . ' }';
					// is preview?
					if(is_preview()) {
						$args['post_status'] = array('publish', 'draft', 'pending');
					}
				}
			} else if($filter_by != 'latest') {
				$filters = array(
					'category' => 'categories',
					'tag'	   => 'tags',
					'author'   => 'authors',
					'post'	   => 'posts'
				);
				$filter = $filters[$filter_by];
				// switch filters
				switch($filter) {
					case 'categories':
						$arg = 'category__in';
					break;
					case 'tags':
						$arg = 'tag__in';
					break;
					case 'authors':
						$arg = 'author__in';
					break;
					case 'posts':
						$arg = 'post__in';
					break;
				}
				// check if array
				if(isset($values['options'][$filter]) && is_array($values['options'][$filter])) {
					$args[$arg] = $values['options'][$filter];
				}
			}

			// get posts
			$query = new WP_Query($args);

			// open post list
			$output = '<div class="is-content blogposts" data-blog-layout="' . $layout . '" data-wp-template="' . $wp_template . '">';

			// add archive and search results title
			if(false !== $wp_template && $wp_template != 'singlepost' && true === $query->have_posts() && $archive_visibility == 'visible') {
				$output .= $this->template_title($wp_template, $values['options'], $values['posts_filter'], $archive_font_family, $query->have_posts());
			}

			// add font family to css
			$output_css .= '#' . $id . ' .blogposts .blogposts-post-heading h2 { ' . semplice_get_font_family($title_font_family) . ' }';

			// get spc
			$spc = $this->spc($values['options']);

			// check if posts there
			$count = 0;
			if($query->have_posts()) {
				while($query->have_posts()) {

					// post counter
					$post_count = $query->post_count;

					// post count
					$count++;

					// the post
					$query->the_post();

					// permalink
					$permalink = get_the_permalink();

					// post id
					$post_id = get_the_ID();

					// blogpost content
					$blogpost = array();
					foreach($content_order as $type) {
						$blogpost[$type] = '';
					}

					// inner column
					$inner_column = '<div class="blogposts-inner-column"' . $fw_content_width . '>';

					// reset first meta
					$this->first_meta = true;

					// get meta
					$metas = $this->metas($post_id, $values['options'], $category_font_family, $permalink);

					// category
					$blogpost['category'] = '
						<div class="blogposts-post-category' . $spc['meta'] . '>
							' . $metas['category'] . '
						</div>
					';

					// title
					$title = get_the_title();
					$title = ($is_single === true ? $title : '<a href="' . $permalink . '">' . $title . '</a>');
					$blogpost['title'] = '
						<div class="blogposts-post-heading' . $spc['title'] . '>
							<h2 class="' . $title_font_family . '">' . $title . '</h2>
						</div>
					';
					
					// thumbnail
					if($thumbnail_visibility == 'block' || true === $this->thumbnail_visibility($values['options'])) {
						$blogpost['thumb'] .= '<div class="blogposts-thumbnail' . $spc['thumbnail'] . ' data-thumbnail-alignment="' . $thumbnail_alignment . '">' . $this->thumbnail($permalink, $post_id, $is_single) . '</div>';
					}
					
					// content for single, excerpt for overview
					if(true === $is_single) {
						$post_content = get_the_content();
						// add wpautop filter again (could be removed by other iterations of get-content before)
						add_filter('the_content', 'wpautop');
						// add to content
						$blogpost['content'] = '
							<div class="blogposts-post-content blogposts-content' . $spc['content'] . '>' . apply_filters('the_content', $post_content) . '</div>
						';
					} else if($content_visibility != 'hidden') {
						if (has_excerpt()) {
							$excerpt = wp_trim_words(get_the_excerpt(), $excerpt_length, ' &hellip;');
						} else {
							$excerpt = $this->excerpt_from_post(get_the_content(), $excerpt_length);
						}
						$blogpost['content'] = '
							<div class="blogposts-post-content blogposts-content' . $spc['content'] . ' data-font="' . $content_font_family . '">' . $excerpt . '</div>
						';
					}

					// metas, make sure that at least one meta is visible
					if($meta_visibility == 'visible') {
						$blogpost['meta'] = '
							<div class="blogposts-meta ' . $meta_font_family . $spc['meta'] . ' data-meta-align="' . $meta_align . '">
								<div class="blogposts-meta-inner">
									' . $metas['author'] . '
									' . $metas['date'] . '
									' . $metas['comment'] . '
									' . $metas['readtime'] . '
								</div>
							</div>
						';
					}

					// tags
					if($tags_visibility == 'visible') {
						// category
						$blogpost['tags'] = '
							<div class="blogposts-tags ' . $tags_font_family . $spc['content'] . '>
								' . $metas['tags'] . '
							</div>
						';						
					}

					// post seperator
					$post_seperator = '<div class="blogposts-seperator blogposts-inner-column"></div>';
					if($count == $post_count && $post_seperator_last == 'hidden') {
						$post_seperator = '';
					}
					
					// categories
					$categories = get_the_category($post_id);
					$category_classes = '';
					if(is_array($categories) && !empty($categories)) {
						foreach($categories as $category) {
							$category_classes .= ' cat-' . $category->term_id;
						}
					}

					// output
					$post_output = array('thumb' => '', 'post' => '');
					if($layout == 'fullwidth') {
						foreach($blogpost as $type => $content) {
							$post_output['post'] .= $content;
						}
						$post_seperator = ($is_single === true && $is_locked === true ? '' : $post_seperator);
						$output .= '<div class="blogposts-column' . $category_classes . '"><div class="blogposts-post">' . $post_output['post'] . '</div></div>' . $post_seperator;
					} else {
						if($layout == 'list') {
							$post_output['thumb'] .= $blogpost['thumb'] . $inner_column;
							foreach($blogpost as $type => $content) {
								if($type != 'thumb') {
									$post_output['post'] .= $content;
								}
							}
							$post_output['post'] .= '</div>';
						} else {
							foreach($blogpost as $type => $content) {
								if($type == 'thumb') {
									$post_output['thumb'] .= $content;
								} else {
									$post_output['post'] .= $inner_column . $content . '</div>';
								}
							}
						}
						// wrap post output
						$post_output['post'] = '<div class="blogposts-content-container">' . $post_output['post'] . '</div>';
						if($layout != 'columns') {
							$post_seperator = '</div>' . $post_seperator;
						} else {
							$post_seperator .= '</div>';
						}
						$output .= '
							<div class="blogposts-column' . $category_classes . '"' . $this->column_width($values['options']) . ' data-list="' . $listgrid . '">
								<div class="blogposts-post">
									' . $post_output['thumb'] . '
									' . $post_output['post'] . '
								</div>
							' . $post_seperator . '
						';
					}
					

					// reset post data
					wp_reset_postdata();
				}

				// pagination
				if($pagination_visibility != 'hidden' && $args['posts_per_page'] != -1 && false === $is_single) {
					$output .= $this->pagination($query->found_posts, $page_num, $posts_per_page, $values['options'], $values['posts_filter'], $pagination_font_family);
				}
			} else {
				// get default content if there are no posts
				if(true === $this->is_editor || false === $wp_template) {
					$output = '<div class="is-content blogposts empty-blogposts">' . semplice_module_placeholder('blogposts', 'To customize your blog templates make sure to add at least one published post.', false, $this->is_editor, $id);
				} else {
					$output .= '
						<div class="blogposts-column" data-xl-width="12">
							<div class="blogposts-noresults no-content">
								<h2>' . __('No posts found for your tag,<br />category or search term.', 'semplice') . '</h2>
							</div>
						</div>
					';
				}
			}

			// close post list
			$output .= '</div>';

			// define output
			$this->output['html'] = $output;

			// css styles, defaults[0] ist die seperater farbe, false wenn kein seperator vorhanden
			$formatting = array(
				'category' => array(
					'target' 	=> '.blogposts-categories',
					'defaults' 	=> array('#777777', '0.8333333333333333rem', 'none', '0rem', '1', '0.5555555555555556rem', '0.5555555555555556rem', 'left', 0, 0, '#777777'),
				),
				'title' => array(
					'target' 	=> '.blogposts-post-heading h2',
					'defaults' 	=> array('#000000', '2rem', 'none', '0rem', '2.444444444444444rem', '0.5555555555555556rem', '0.5555555555555556rem', 'left', 0, 0, '#000000'),
				),
				'content' => array(
					'target' 	=> '.blogposts-content',
					'defaults' 	=> array('#000000', '1rem', 'none', '0rem', '1.5556rem', '0.5555555555555556rem', '0.5555555555555556rem', false, 0, 0, '#000000'),
				),
				'meta' => array(
					'target' 	=> '.blogposts-meta-inner',
					'defaults' 	=> array('#777777', '0.8333333333333333rem', 'none', '0rem', '1.444444444444444rem', '0.5555555555555556rem', '0.5555555555555556rem', false, 0, 0, '#777777'),
				),
				'tags' => array(
					'target' 	=> '.blogposts-tags',
					'defaults' 	=> array('#777777', '0.8333333333333333rem', 'none', '0rem', '1.444444444444444rem', '0.5555555555555556rem', '0.5555555555555556rem', false, 0, 0, '#777777'),
				),
				'pagination' => array(
					'target' 	=> '.blogposts-pagination',
					'defaults' 	=> array('#777777', '1rem', 'none', '0rem', '1.444444444444444rem', '0.5555555555555556rem', '0.5555555555555556rem', 'center', 0, 0, '#777777'),
				),
				'archive' => array(
					'target' 	=> '.blogposts-archive',
					'defaults' 	=> array('#000000', '1.777777777777778rem', 'none', '0rem', false, '3rem', '3rem', 'left', 0, 0, '#777777'),
				),
				'caption' => array(
					'target' 	=> 'figcaption',
					'defaults' 	=> array('#999999', '0.7777777777777778rem', 'none', '0rem', false, '0.8333333333333333rem', '0.8333333333333333rem', 'center', 0, 0, '#777777'),
				),
			);

			// iterate breakpoints
			$breakpoints = semplice_get_breakpoints(true);
			foreach ($breakpoints as $breakpoint => $width) {
				// css
				$css = '';
				// bp
				$bp = ($breakpoint == 'xl' ? false : '_' . $breakpoint);
				// get formatting
				foreach ($formatting as $type => $options) {
					$css .= $this->formatting($id, $values['options'], $type, $options['target'], $options['defaults'], $bp);
				}
				// get breakpoint gutter
				$gutter = $this->get_gutter($values['options'], $bp);
				// general styling breakpoint
				$css .= $this->general_styling($id, $values['options'], $gutter, $content_width, $bp);
				// add to css output
				if(!empty($css) && $breakpoint != 'xl') {
					if(true === $this->is_editor) {
						$output_css .= str_replace('##breakpoint##', $breakpoint, $css);
					} else {
						$output_css .= '@media screen' . $width['min'] . $width['max'] . ' { ' . str_replace('[data-breakpoint="##breakpoint##"] ', '', $css) . '}';
					}
				} else {
					$output_css .= $css;
				}
			}

			// add to css output
			$this->output['css'] = $output_css;

			// output
			return $this->output;
		}

		// thumbnail visibility
		public function thumbnail_visibility($styles) {
			$visibility = false;
			$breakpoints = array('lg', 'md', 'sm', 'xs');
			foreach($breakpoints as $bp) {
				if(isset($styles['thumbnail_visibility_' . $bp]) && $styles['thumbnail_visibility_' . $bp] == 'block') {
					$visibility = true;
				}
			}
			return $visibility;
		}

		// column width
		public function column_width($styles) {
			// output
			$output = '';
			// defaults
			$defaults = array('xl' => 4, 'lg' => 4, 'md' => 6, 'sm' => 12, 'xs' => 12);
			// iterate defaults
			foreach($defaults as $breakpoint => $width) {
				if(isset($styles['width_' . $breakpoint])) {
					$width = $styles['width_' . $breakpoint];
				} else if($breakpoint == 'xl' && isset($styles['width'])) {
					$width = $styles['width'];
				}
				$output .= ' data-' . $breakpoint . '-width="' . $width . '"';
			}
			// ret
			return $output;
		}

		// get gutter
		public function get_gutter($styles, $bp) {
			// defaults
			$gutter = 30;
			$layout = isset($styles['layout']) ? $styles['layout'] : 'list';
			// check type
			if($layout == 'list' && isset($styles['gutter_list'. $bp])) {
				$gutter = $styles['gutter_list' . $bp];
			} else if($layout == 'columns' && isset($styles['gutter_columns' . $bp])) {
				$gutter = $styles['gutter_columns' . $bp];
			} else {
				$gutter = false;
			}
			// return
			return $gutter;
		}

		// single post columns and classes
		public function spc($styles) {
			// define output
			$output = array();
			// define types
			$spc_types = array('meta', 'title', 'thumbnail', 'content');
			// defaults
			$breakpoints = array('xl', 'lg', 'md', 'sm', 'xs');
			// iterate types
			foreach($spc_types as $type) {
				$width_output = '';
				// iterate breakpoints
				foreach($breakpoints as $bp) {
					$width = 12;
					if(isset($styles['fw_' . $type . '_width_' . $bp])) {
						$width = $styles['fw_' . $type . '_width_' . $bp];
					} else if($bp == 'xl' && isset($styles['fw_' . $type . '_width'])) {
						$width = $styles['fw_' . $type . '_width'];
					}
					$width_output .= ' data-' . $bp . '-width="' . $width . '"';
				}
				// add to output
				if(isset($styles['layout']) && $styles['layout'] == 'fullwidth') {
					$output[$type] = ' single-post-column"' . $width_output;
				} else {
					if($type == 'thumbnail') {
						$output[$type] = ' blogposts-inner-column"';
					} else {
						$output[$type] = '"';
					}
				}
			}
			// ret
			return $output;
		}

		// general styling
		public function general_styling($id, $styles, $gutter, $content_width, $bp) {
			// output
			$output = '';
			// selector
			$selector = '#' . $id . ' .blogposts ';
			$is_desktop = false;
			if(false !== $bp) {
				$selector = '[data-breakpoint="##breakpoint##"] ' . $selector;
			} else {
				$is_desktop = true;
			}
			// background color
			if(isset($styles['background' . $bp])) {
				$output .= $selector . '.blogposts-post { background-color: ' . $styles['background'] . ';}';
			}
			// thumbnail visibility
			if(isset($styles['thumbnail_visibility' . $bp])) {
				$output .= $selector . '.blogposts-post .featured-image { display: ' . $styles['thumbnail_visibility' . $bp] . ';}';
				if($styles['thumbnail_visibility' . $bp] == 'none') {
					$output .= substr($selector, 0, -1) . '[data-blog-layout="list"] .blogposts-post { display: flex !important; grid-column-gap: 0px; }';
				} else {
					$output .= substr($selector, 0, -1) . '[data-blog-layout="list"] .blogposts-post { display: grid !important;}';
				}
			}
			// inner padding
			if(isset($styles['inner_padding' . $bp])) {
				$output .= $selector . '.blogposts-content-container { padding: ' . $styles['inner_padding' . $bp] . ';}';
			}
			// outer padding
			if(isset($styles['outer_padding' . $bp])) {
				$output .= $selector . '.blogposts-post { padding: ' . $styles['outer_padding' . $bp] . ';}';
			}
			// bottom spacing
			if(isset($styles['bottom_spacing' . $bp])) {
				$output .= $selector . '.blogposts-post { margin-bottom: ' . $styles['bottom_spacing' . $bp] . ';}';
			}
			// blogposts seperator
			if(!isset($styles['post_seperator_visibility']) || isset($styles['post_seperator_visibility']) && $styles['post_seperator_visibility'] == 'visible') {
				$seperator = array(
					'height' . $bp 			=> array('height', '0.0555555555555556rem'),
					'top_spacing' . $bp		=> array('margin-top', '1.444444444444444rem'),
					'bottom_spacing' . $bp	=> array('margin-bottom', '1.444444444444444rem'),
					'color'	 . $bp			=> array('background-color', '#e0e0e0')
				);
				$sep_output = '';
				// iterate (if not breakpoint setting are set but desktop, use these and overwrite the default desktop settings)
				foreach($seperator as $attribute => $values) {
					if(isset($styles['post_seperator_' . $attribute])) {
						$sep_output .= $values[0] . ': ' . $styles['post_seperator_' . $attribute] . ';';
						$seperator[$attribute][1] = $styles['post_seperator_' . $attribute];
					} else if(isset($styles['post_seperator_' . str_replace($bp, '', $attribute)])) {
						$seperator[$attribute][1] = $styles['post_seperator_' . str_replace($bp, '', $attribute)];
					} else if(true === $is_desktop) {
						$sep_output .= $values[0] . ': ' . $values[1] . ';';
					}
				}
				// output
				if(true === $is_desktop) {
					$sep_output = 'display: block;' . $sep_output;
				}
				if(strlen($sep_output) > 0) {
					$output .= $selector . '.blogposts-seperator {' . $sep_output . '}';
				}
				// switch from flex to grid for columns layout
				if(isset($styles['layout']) && $styles['layout'] == 'columns') {
					$height = floatval(str_replace('rem', '', $seperator['height' . $bp][1]));
					$margin = array(
						'top' => floatval(str_replace('rem', '', $seperator['top_spacing' . $bp][1])),
						'bottom' => floatval(str_replace('rem', '', $seperator['bottom_spacing' . $bp][1])),
					);
					$height = $height + ($margin['top'] + $margin['bottom']);
					$output .= substr($selector, 0, -1) . '[data-blog-layout="columns"] .blogposts-column { display: grid; grid-template-columns: 100%; grid-auto-rows: 1fr ' . $height . 'rem;}';
				}
			} else {
				$output .= $selector . '.blogposts-seperator { display: none; }';
			}
			// gutter, paddings and content width
			if(true === $is_desktop && false === $gutter) {
				$gutter = 30;
			}
			if(false !== $gutter) {
				$output .= $selector . '{ margin-left: -' . ($gutter / 2) . 'px; margin-right: -' . ($gutter / 2) . 'px; }';
				if(!isset($styles['layout']) || $styles['layout'] == 'list') {
					// gutter
					$output .= $selector . '.blogposts-post { grid-column-gap: ' . $gutter . 'px; }';
					// add margin to seperator
					$output .= $selector . '.blogposts-seperator { margin-left: ' . ($gutter / 2) . 'px; margin-right: ' . ($gutter / 2) . 'px; }';
				}
				$output .= $selector . '.blogposts-column { padding-left: ' . ($gutter / 2) . 'px; padding-right: ' . ($gutter / 2) . 'px; }';
			}
			// responsive meta visibility
			if(false === $is_desktop) {
				// iterate
				foreach($this->metas as $meta => $value) {
					// first inherit value from desktop if set
					if(isset($styles[$meta . '_visibility'])) {
						$value = $styles[$meta . '_visibility'];
					}
					if(isset($styles[$meta . '_visibility' . $bp]) && $styles[$meta . '_visibility' . $bp] == 'hidden' || !isset($styles[$meta . '_visibility' . $bp]) && $value == 'hidden') {
						$output .= $selector . '.blogposts-meta .blogposts-' . $meta . ', ' . $selector . '.blogposts-meta .' . $meta . '-seperator { display: none; }';
					} else {
						$output .= $selector . '.blogposts-meta .blogposts-' . $meta . ', ' . $selector . '.blogposts-meta .' . $meta . '-seperator { display: block; }';
					}
				}
			}
			
			// return
			return $output;
		}

		// formatting
		public function formatting($id, $styles, $type, $target, $defaults, $bp) {
			// output
			$output = '';
			// hover
			$hover_default = array(
				'category' 	 => '#000000',
				'title'	   	 => '#555555',
				'meta'	   	 => '#000000',
				'tags'		 => '#000000',
				'content'	 => '#000000',
				'pagination' => '#000000'
			);
			// atts
			$atts = array(
				$type . '_color' 					=> 'color',
				$type . '_fontsize' . $bp			=> 'font-size',
				$type . '_text_transform' . $bp 	=> 'text-transform',
				$type . '_letter_spacing' . $bp 	=> 'letter-spacing',
				$type . '_line_height' . $bp 		=> 'line-height',
				$type . '_bottom_spacing' . $bp 	=> 'padding-bottom',
				$type . '_top_spacing' . $bp 		=> 'padding-top',
				$type . '_align' . $bp 				=> 'text-align',
				$type . '_underline_width' . $bp 	=> 'text-decoration-thickness',
				$type . '_underline_padding' . $bp 	=> 'text-underline-offset',
				$type . '_underline_color'			=> 'text-decoration-color',
			);
			// change css attribute for categories and tags (since its a flexbox)
			if($type == 'category' || $type == 'tags') {
				$atts[$type . '_align' . $bp] = 'justify-content';
			}
			// css
			$css = '';
			$spacing = '';
			// selector
			if($type != 'pagination' && $type != 'archive') {
				$selector = '#' . $id . ' .blogposts .blogposts-column ';
			} else {
				$selector = '#' . $id . ' .blogposts ';
			}
			$is_desktop = false;
			if(false !== $bp) {
				$selector = '[data-breakpoint="##breakpoint##"] ' . $selector;
			} else {
				$is_desktop = true;
			}
			// count
			$count = 0;
			// iterate atts
			foreach ($atts as $attribute => $css_attribute) {
				$css_style = '';
				// text decoration
				if(strpos($attribute, '_underline_width' . $bp) !== false && isset($styles[$attribute]) && $styles[$attribute] != '0rem') {
					$css .= 'text-decoration: underline;';
				}
				// look if attribute is there, if not first look if xl is set and if not then use from defaults
				if(isset($styles[$attribute])) {
					$css_style = $css_attribute . ': ' . $styles[$attribute] . ';';
				} else if(isset($styles[str_replace($bp, '', $attribute)])) {
					$css_style = $css_attribute . ': ' . $styles[str_replace($bp, '', $attribute)] . ';';
				} else if(false !== $defaults[$count]) {
					$css_style = $css_attribute . ': ' . $defaults[$count] . ';';
				}
				if(strpos($attribute, 'top_spacing' . $bp) !== false || strpos($attribute, 'bottom_spacing' . $bp) !== false) {
					$spacing .= $css_style;
				} else {
					$css .= $css_style;
				}
				$count++;
			}
			// output
			if(!empty($css)) {
				if($type != 'content') {
					$output .= $selector . $target . ', ' . $selector . $target . ' a {' . $css . '}';
				} else {
					$output .= $selector . $target . ', ' . $selector . $target . ' p, ' . $selector . $target . ' li {' . $css . '}';
				}
			}
			if(!empty($spacing)) {
				$spacing_target = $target;
				if($target == '.blogposts-content p') {
					$spacing_target = '.blogposts-content';
				}
				$output .= $selector . $spacing_target . ' {' . $spacing . '}';
			}
			// hover color
			if(isset($hover_default[$type])) {
				$hover_color = $hover_default[$type];
				$underline_color = $hover_default[$type];
				if(isset($styles[$type . '_hover_color'])) {
					$hover_color = $styles[$type . '_hover_color'];
				}
				if(isset($styles[$type . '_underline_hover'])) {
					$underline_color = $styles[$type . '_underline_hover'];
				}
				$output .= $selector . $target . ' a:hover {color: ' . $hover_color . ' !important; text-decoration-color: ' . $underline_color . ' !important;}';
			}
			// link color
			if(isset($styles[$type . '_link_color'])) {
				$output .= $selector . '.blogposts-' . $type . ' a { color: ' . $styles[$type . '_link_color'] . ';}';
			} else if($type != 'content') {
				$output .= $selector . '.blogposts-' . $type . ' a { color: #000000;}';
			}
			// text decoration seperately for content links
			if($type == 'content' && isset($styles[$type . '_underline_width' . $bp]) && $styles[$type . '_underline_width' . $bp] != '0rem') {
				$output .= $selector . '.blogposts-' . $type . ' a { text-decoration: underline; }';
				// thickness and color
				if(isset($styles[$type . '_underline_width' . $bp])) {
					$output .= $selector . '.blogposts-' . $type . ' a { text-decoration-thickness: ' . $styles[$type . '_underline_width' . $bp] . '; }';
				}
				if(isset($styles[$type . '_underline_color' . $bp])) {
					$output .= $selector . '.blogposts-' . $type . ' a { text-decoration-color: ' . $styles[$type . '_underline_color' . $bp] . '; }';
				}
			}
			// meta seperator
			if(isset($styles[$type . '_seperator_padding' . $bp])) {
				$output .= $selector . '.blogposts-' . $type . '-seperator { padding: 0rem ' . $styles[$type . '_seperator_padding' . $bp] . ';}';
			}
			if(isset($styles[$type . '_seperator_color'])) {
				$output .= $selector . '.blogposts-' . $type . '-seperator { color: ' . $styles[$type . '_seperator_color'] . ';}';
			}
			// return
			return $output;
		}

		// show meta
		public function metas($id, $options, $category_font_family, $permalink) {
			// define metas
			$metas = $this->metas;
			// iterate metas
			foreach ($metas as $meta => $value) {
				if(!isset($options[$meta . '_visibility']) && $value != 'hidden' || isset($options[$meta . '_visibility']) && $options[$meta . '_visibility'] != 'hidden') {
					// switch meta
					switch($meta) {
						case 'author':
							// get author id
							if(!isset($options[$meta . '_visibility']) || $options[$meta . '_visibility'] == 'both') {
								$author_id = get_post_field('post_author', $id);
								$author_img = get_avatar_url($author_id, array('size' => 80));
								$metas[$meta] .= '<div class="blogposts-gravatar"><img src="' . $author_img . '" alt="gravatar"></div>';
							}
							// get author id
							$author_id = get_post_field('post_author', $id);
							$metas[$meta] .= '<div class="blogposts-author">' . __('By', 'semplice') . '&nbsp;<a href="' . get_author_posts_url($author_id) . '" title="link-to-author">' . get_the_author() . '</a></div>';
						break;
						case 'category':	
							if(has_category()) {
								$category_ids = wp_get_post_categories($id);
								foreach ($category_ids as $key => $category_id) {
									$categories[$key] = '<a href="' . get_category_link($category_id) . '">' . get_the_category_by_ID($category_id) . '</a>';
								}
								$metas[$meta] = '<div class="blogposts-categories ' . $category_font_family . '">' . implode($this->meta_seperator('category', 'category', $options), $categories) . '</div>';
							}
						break;
						case 'tags':
							// get tags
							$tags_object = get_the_tags();
							if($tags_object) {
								$tags = array();
								foreach ($tags_object as $key => $tag) {
									$tags[$key] = '<a href="' . get_tag_link($tag->term_id) . '">' . $tag->name . '</a>';
								}
								$tags_label = '<span>' .  __('Tagged:', 'semplice') . '</span>&nbsp;';
								if(isset($options['tags_label']) && $options['tags_label'] == 'hidden') {
									$tags_label = '';
								}
								$metas[$meta] = '<div class="blogposts-tags">' . $tags_label . implode($this->meta_seperator('tags', 'tags', $options), $tags) . '</div>';
							}
						break;
						case 'comment':
							$comments = get_comments_number($id);
							$comments_affix = __('Comments', 'semplice');
							if($comments == 1) {
								$comments_affix = __('Comments', 'semplice');
							}
							$metas[$meta] = $this->meta_seperator('meta', 'comment', $options) . '<div class="blogposts-comment"><a href="' . $permalink . '#comments">' . $comments . ' ' . $comments_affix . '</a></div>';;
						break;
						case 'date':
							$date_format = 'F j, Y';
							if(isset($options['date_format'])) {
								if($options['date_format'] == 'custom' && isset($options['date_custom']) && !empty($options['date_custom'])) {
									$date_format = $options['date_custom'];
								} else if($options['date_format'] != 'custom') {
									$date_format = $options['date_format'];
								}
							}
							$metas[$meta] = $this->meta_seperator('meta', 'date', $options) . '<div class="blogposts-date">' . get_the_date($date_format) . '</div>';
						break;
						case 'readtime':
							$read_time = $this->read_time($id);
							$metas[$meta] = $this->meta_seperator('meta', 'readtime', $options) . '<div class="blogposts-readtime">' . $read_time . '</div>';
						break;
					}
					// is first
					$this->first_meta = false;
				} else {
					$metas[$meta] = '';
				}
			}
			// return
			return $metas;
		}

		// meta seperator
		public function meta_seperator($type, $option, $options) {
			// non meta
			$non_meta = array('category', 'pagination', 'tags');
			// define category seperator
			$meta_seperator = array(
				'space'		=> '&nbsp;',
				'comma' 	=> ',&nbsp;',
				'middot'	=> '&nbsp;&middot;&nbsp;',
				'mdash'		=> '&nbsp;&mdash;&nbsp;',
				'vertical'	=> '&nbsp;|&nbsp;'
			);
			// output
			if(in_array($type, $non_meta) || false === $this->first_meta) {
				$output = '<span class="blogposts-' . $type . '-seperator ' . $option . '-seperator">';
				if(isset($options[$type . '_seperator'])) {
					$output .= $meta_seperator[$options[$type . '_seperator']];
				} else {
					$output .= $meta_seperator['middot'];
				}
				// return
				return $output . '</span>';
			}
		}

		// template title
		public function template_title($template, $options, $filter, $font_family) {
			// intro
			$title = 'Latest in: Example';
			// has filter?
			if(false !== $filter) {
				if($filter['type'] == 'category' || $filter['type'] == 'tag') {
					$taxonomy = $filter['meta'];
					if($taxonomy) {
						$title = __('Latest in:', 'semplice') . ' '. $taxonomy->name;
					}
				} else if($filter['type'] == 'author') {
					$author = get_userdata($filter['meta']);
					$title = __('Written by:', 'semplice') . ' '. $author->display_name;
				} else if($filter['type'] == 'searchresults') {
					$search = 'Example';
					if(false !== $filter['meta']) {
						$search = $filter['meta'];
					}
					// nothing found?
					$title = __('Results for', 'semplice') . ' "' . $search . '"';
				}
			}
			return '<div class="blogposts-column template-title ' . $font_family . '" data-xl-width="12"><div class="blogposts-archive">' . $title . '</div></div>';
		}

		// get post thumbnail
		public function thumbnail($permalink, $id, $is_single) {
			// output
			$output = '';
			// has post thumbnail?
			if(has_post_thumbnail($id)) {

				// get thumbnail
				$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'single-post-thumbnail');

				// is thumbnail?
				if($thumbnail) {

					// thumbnail
					$thumbnail = '<img class="featured-image" src="' . $thumbnail[0] . '" alt="Featured Image" />';

					// add thumbnail to output (add link if not single post)
					$output .= ($is_single === true ? $thumbnail : '<a href="' . $permalink . '" title="' . get_the_title($id) . '">' . $thumbnail . '</a>');
				}
			}
			// return
			return $output;
		}

		// get excerpt
		public function excerpt_from_post($content, $length) {
			$content = strip_shortcodes( $content );
			$content = apply_filters('the_content', $content);
			$content = str_replace(']]>', ']]&gt;', $content);
			$excerpt_length = apply_filters('excerpt_length', $length);
			$excerpt_more = apply_filters('excerpt_more', ' ' . '&hellip;');
			$content = wp_trim_words($content, $excerpt_length, $excerpt_more);
			// return
			return $content;
		}

		// get pagination
		public function pagination($count, $page_num, $posts_per_page, $options, $filter, $font_family) {
			// output
			$pagination = array('newer' => '', 'older' => '', 'seperator' => '');
			// arrow
			$arrows = array(
				'none'		=> array('', ''),
				'rsaquo'	=> array('&lsaquo;', '&rsaquo;'),
				'raquo'		=> array('&laquo;', '&raquo;'),
				'rarr'		=> array('&larr;', '&rarr;')
			);
			$arrow = isset($options['pagination_arrow']) ? $options['pagination_arrow'] : 'rsaquo';
			// get url
			$url = semplice_get_blog_url($filter);
			// is editor?
			if(false === $this->is_editor) {
				// get pages
				$pages_num = ceil($count / $posts_per_page);
				//  are there more than 1 page?
				if($count > $posts_per_page) {
					// is first page?
					if($page_num > 1) {
						// add page num on all pages except 2
						$page = '';
						if($page_num != 2) {
							$page = '/page/' . ($page_num - 1);
						}
						$pagination['newer'] .= '<a href="' . $url . $page . '">' . $arrows[$arrow][0] . ' ' . __('Newer Posts', 'semplice') . '</a>';
					}
					if($page_num < $pages_num) {
						$pagination['older'] .= '<a class="older-posts" href="' . $url . '/page/' . ($page_num + 1) . '">' . __('Older Posts', 'semplice') . ' ' . $arrows[$arrow][1] . '</a>';
					}
					// seperator
					if(!empty($pagination['older']) && !empty($pagination['newer'])) {
						$pagination['seperator'] = $this->meta_seperator('pagination', 'pagination', $options);
					}
				}
			} else {
				$pagination = array(
					'newer' => '<a href="' . $url . '/page/' . ($page_num - 1) . '">' . $arrows[$arrow][0] . ' ' . __('Newer Posts', 'semplice') . '</a>',
					'older' => '<a class="older-posts" href="' . $url . '/page/' . ($page_num + 1) . '">' . __('Older Posts', 'semplice') . ' ' . $arrows[$arrow][1] . '</a>',
					'seperator' => $this->meta_seperator('pagination', 'pagination', $options),
				);
			}

			// wrap pagination output
			$pagination = '
				<div class="blogposts-pagination blogposts-column ' . $font_family . '" data-xl-width="12">
					<div class="newer">' . $pagination['newer'] . '</div>
					<div class="older">' . $pagination['older'] . '</div>
				</div>
			';
			
			return $pagination;
		}

		//estimated reading time
		public function read_time($id) {
			// get content
			$content = get_the_content($id);
			// word count
			$word_count = str_word_count( strip_tags($content));
			// calc read time
			$reading_time = ceil($word_count / 230);
			// output
			return $reading_time . ' ' . __('min', 'semplice') . ' ' . __('read', 'semplice');
		}

		// output frontend
		public function output_frontend($values, $id) {
			// set editor to false
			$this->is_editor = false;
			// same as editor
			return $this->output_editor($values, $id);
		}

		// init templates
		public function init_templates() {
			// templates
			$templates = array('singlepost', 'archive', 'searchresults');
			// iterate templates
			foreach($templates as $template) {
				// add to db if not there yet
				if(false === get_option('semplice_template_' . $template)) {
					// get template
					$content_json = file_get_contents(get_template_directory() . '/assets/json/wp-templates/' . $template . '.json');
					// save default to template
					update_option('semplice_template_' . $template, $content_json);
				}
			}
		}
	}

	// instance
	editor_api::$modules['blogposts'] = new sm_blogposts;
}