<?php

// -----------------------------------------
// semplice
// /includes/blog.php
// -----------------------------------------

class semplice_blog {

	// constructor
	public function __construct() {
	}

	// wordpress loop
	public function loop($args, $page_num, $filter, $is_single) {

		// output
		$output = array(
			'css' 			=> '',
			'html'			=> '',
			'js' 			=> '',
		);

		// get posts
		$query = new WP_Query($args);
		
		// get options
		$blog_options = $this->get_options();

		// add blog css
		global $semplice_custom_css;
		$output['css'] = $semplice_custom_css->blog(false);

		// check if posts there
		if($query->have_posts()) {

			// open post list
			$output['html'] .= '<div class="posts">';

			while($query->have_posts()) {

				// the post
				$query->the_post();

				// remove row
				$post_classes = str_replace('row ', '', implode(' ', get_post_class('row')));

				// format post
				$output['html'] .= '
					<section class="post is-content ' . $post_classes . '">
						' . $this->format_standard(get_the_ID(), $blog_options, $is_single) . '
					</section>
				';

				// divider and comments
				if(!$is_single) {
					$output['html'] .= '<div class="post-divider search-divider"></div>';
				} else {
					// comments are visible?
					if($blog_options['comments_visibility'] != 'hidden') {
						// get comments
						$comments = get_comments('post_id=' . get_the_ID() . '&orderby=comment_parent&status=approve');
						// output
						$output['html'] .= semplice_get_comments('default', $comments, false);
					}
				}

				// reset post data
				wp_reset_postdata();
			}

			// get pagination
			if($args['posts_per_page'] != -1) {
				$output['html'] .= $this->pagination($query->found_posts, $page_num, $filter, $blog_options);
			}

			// close post list
			$output['html'] .= '</div>';
		} else {
			// get default content if there are no posts
			$output = $this->default_content('empty-posts');
		}

		// return
		return $output;
	}

	// get pagination
	public function pagination($count, $page_num, $filter, $options) {
		// output
		$pagination = '';
		// get posts per page
		$posts_per_page = get_option('posts_per_page');
		// get pages
		$pages_num = ceil($count / $posts_per_page);
		// get url
		$url = semplice_get_blog_url($filter);
		//  are there more than 1 page?
		if($count > $posts_per_page) {
			// is first page?
			if($page_num > 1) {
				// add page num on all pages except 2
				if($page_num != 2) {
					$pagination .= '<a href="' . $url . '/page/' . ($page_num - 1) . '">&lsaquo; ' . __('Newer Posts', 'semplice') . '</a>';
				} else {
					$pagination .= '<a href="' . $url . '">' . __('Newer Posts', 'semplice') . '</a>';
				}
				
			}
			if($page_num < $pages_num) {
				$pagination .= '<a class="older-posts" href="' . $url . '/page/' . ($page_num + 1) . '">' . __('Older Posts', 'semplice') . ' &rsaquo;</a>';
			}
		}

		// wrap pagination output
		$pagination = '
			<section class="blog-pagination">
				<div class="container">
					<div class="row">
						<div class="column" data-xl-width="' . $options['width'] . '" data-md-width="11" data-sm-width="12" data-xs-width="12">
							' . $pagination . '
						</div>
					</div>
				</div>
			</section>
		';

		return $pagination;
	}

	// get content main function
	public function format_standard($id, $options, $is_single) {
		
		// output
		$output = '';

		// permalink
		$permalink = get_the_permalink();

		// has post thumbnail?
		if(has_post_thumbnail($id)) {

			// get thumbnail
			$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'single-post-thumbnail');

			// is thumbnail?
			if($thumbnail) {

				// featured iamge
				$featured_image = '<img src="' . $thumbnail[0] . '" alt="Featured Image" />';

				// link the image if not single post
				if(false === $is_single) {
					$featured_image = '<a href="' . $permalink . '" title="' . get_the_title() . '">' . $featured_image . '</a>';
				}

				$output .= '
					<div class="container featured-image' . $options['featured_full'] . '">
						<div class="row">
							<div class="column" data-xl-width="' . $options['featured_width'] . '">
						   		' . $featured_image . '
							</div>
						</div>
					</div>
				';
			}
		}

		// post heading and content
		$output .= '
			<div class="container">
				<div class="row">
					<div class="column" data-xl-width="' . $options['width'] . '" data-md-width="11" data-sm-width="12" data-xs-width="12">
						<div class="post-heading">
							' . $this->date_comments_link($id, $options, $permalink) . '
							<h2><a href="' . $permalink . '">' . get_the_title() . '</a></h2>
						</div>
						<div class="post-content">
							' . $this->get_content($is_single) . '
							' . $this->metas($id, $options) . '
							' . $this->share($options, $is_single) . '
						</div>
					</div>
				</div>
			</div>
		';

		return $output;
	}

	// options
	public function get_options() {

		// default options
		$options = array(
			'width' 				=> '8',
			'featured_width'		=> '12',
			'featured_full'			=> '',
			'featured_preview'  	=> 'visible',
			'has_featured_full' 	=> '',
			'head_meta_font'		=> '',
			'display_categories'	=> 'visible',
			'display_tags'			=> 'visible',
			'share_visibility'  	=> 'visible',
			'meta_font'				=> '',
			'type'					=> 'buttons',
			'icon_text_visibility'	=> 'visible',
			'icon_font_family'		=> 'regular',
			'comments_visibility'	=> 'visible',
		);

		// blog options
		$customize_blog = semplice_customize('blog');

		// cusotmize blog active
		if(is_array($customize_blog)) {
			// blog widht
			if(isset($customize_blog['blog_width'])) {
				$options['width'] = $customize_blog['blog_width'];
			}
			// blog meta font
			if(isset($customize_blog['blog_head_meta_font_family'])) {
				$options['head_meta_font'] = ' data-font="' . $customize_blog['blog_head_meta_font_family'] . '"';
			}
			// featured image width
			if(isset($customize_blog['blog_featured_width'])) {
				$options['featured_width'] = $customize_blog['blog_featured_width'];
				// is fullscreen?
				if($customize_blog['blog_featured_width'] == 'fullscreen') {
					$options['featured_full'] = ' featured-img-full';
					$options['has_featured_full'] = ' has-featured-full';
				}

			}
			// featured preview visibility
			if(isset($customize_blog['blog_featured_preview']) && $customize_blog['blog_featured_preview'] == 'hidden') {
				$options['featured_preview'] = 'hidden';
			}
			// blog meta font
			if(isset($customize_blog['blog_meta_font_family'])) {
				$options['meta_font'] = ' data-font="' . $customize_blog['blog_meta_font_family'] . '"';
			}
			// category visibility
			if(isset($customize_blog['blog_visibility_category']) && $customize_blog['blog_visibility_category'] == 'hidden') {
				$options['display_categories'] = 'hidden';
			}
			// tags visibility
			if(isset($customize_blog['blog_visibility_tags']) && $customize_blog['blog_visibility_tags'] == 'hidden') {
				$options['display_tags'] = 'hidden';
			}
			// share type
			if(isset($customize_blog['type']) && $customize_blog['type'] == 'icons') {
				$options['type'] = 'icons';
			}
			// share text visibility
			if(isset($customize_blog['icon_text_visibility']) && $customize_blog['icon_text_visibility'] == 'hidden') {
				$options['icon_text_visibility'] = 'hidden';
			}
			// share font family
			if(isset($customize_blog['icon_font_family'])) {
				$options['icon_font_family'] = $customize_blog['icon_font_family'];
			}
			// comments visibility
			if(isset($customize_blog['comments_visibility'])) {
				$options['comments_visibility'] = $customize_blog['comments_visibility'];
			}
		}

		// return
		return $options;
	}

	// get contnet
	public function get_content($is_single) {
		// is single?
		if(true === $is_single) {
			global $post;
			$content = str_replace('<!--more-->', '<div id="more-' . $post->ID . '"></div>', $post->post_content);
		} else {
			if (has_excerpt()) {
				$content = get_the_excerpt() . '<p><a class="more-link" href="' . get_the_permalink() . '">' . __('Read more', 'semplice') . '</a></p>';
			} else {
				$content = get_the_content(__('Read more', 'semplice'));
			}
		}
		// add wpautop filter again (could be removed by other iterations of get-content before)
		add_filter('the_content', 'wpautop');
		// return
		return apply_filters('the_content', $content);
	}

	// blog metas
	public function metas($id, $options) {

		// vars
		$categories = array();
		$tags = '';
		$share = '';

		// categories
		if($options['display_categories'] != 'hidden') {
			if(has_category()) {
				$category_ids = wp_get_post_categories($id);
				foreach ($category_ids as $key => $category_id) {
					$categories[$key] = '<a href="' . get_category_link($category_id) . '">' . get_the_category_by_ID($category_id) . '</a>';
				}
				$categories = __(' in</span> ', 'semplice') . implode(', ', $categories);
			} else {
				$categories = '</span>';
			}
			$categories = '
				<div class="category-meta"' . $options['meta_font'] . '>
					<span>' . __('Published by ', 'semplice') . get_the_author() . $categories . '
				</div>
			';
		} else {
			$categories = '';
		}
		

		// tags
		$tags_object = get_the_tags();

		if($options['display_tags'] == 'visible' && $tags_object) {
			$tags = array();
			foreach ($tags_object as $key => $tag) {
				$tags[$key] = '<a href="' . get_tag_link($tag->term_id) . '">' . $tag->name . '</a>';
			}
			$tags = '
				<div class="tags-meta"' . $options['meta_font'] . '>
					<span>Tags: </span> ' . implode(', ', $tags) . '
				</div>
			';
		}

		// return metas
		if($options['display_tags'] == 'hidden' && $options['display_categories'] == 'hidden') {
			return '';
		} else {
			return '
				<div class="post-meta">
					' . $categories . '
					' . $tags . '
				</div>		
			';
		}
	}

	// share
	public function share($options, $is_single) {
		if($is_single) {
			return '<div id="share-holder">' . semplice_share_box_html($options, false) . '</div>';
		}
	}

	// date comments link
	public function date_comments_link($id, $options, $permalink) {
		// return nothing if not post
		if(get_post_type($id) == 'post') {
			$comments_link = '';
			if($options['comments_visibility'] != 'hidden') {
				$num_comments = get_comments_number($id);

				if (comments_open($id)) {
					if ( $num_comments == 0 ) {
						$comments = __('No Comments', 'semplice');
					} elseif ( $num_comments > 1 ) {
						$comments = $num_comments . __(' Comments', 'semplice');
					} else {
						$comments = __('1 Comment', 'semplice');
					}
					$write_comments = '<a href="' . get_comments_link($id) .'"' . $options['head_meta_font'] . ' class="title-meta-comments">'. $comments.'</a>';
				} else {
					$write_comments =  __('Comments are off for this post.', 'semplice');
				}
				$comments_link = '<span' . $options['head_meta_font'] . ' class="title-meta-divider"> &mdash; </span>' . $write_comments . '</p>';
			}
			return '<p><a class="title-meta-date" href="' . $permalink . '"' . $options['head_meta_font'] . '>' . get_the_date() . '</a>' . $comments_link;
		} else {
			return '';
		}
	}

	// customize template
	public function customize() {

		// get options
		$options = $this->get_options();

		// feature image
		$featured_image = 'http://blocks.semplice.com/v4/images/semplice_image_6.jpg';

		// output html
		$output = '
			<div class="admin-blog-container" data-comments-visibility="' . $options['comments_visibility'] . '">
				<section class="post is-content post-1 post type-post status-publish format-standard hentry category-work' . $options['has_featured_full'] . '">
					<div class="container ' . $options['featured_preview'] . ' featured-image' . $options['featured_full'] . '">
							<div class="row">
								<div class="column" data-xl-width="' . $options['featured_width'] . '">
							   		<a href="a"><img src="' . $featured_image . '" alt="Featured Image"></a>
								</div>
							</div>
						</div>
					<div class="container">
						<div class="row">
							<div class="column post-column" data-xl-width="' . $options['width'] . '">
								<div class="post-heading">
									<p><a class="head-meta-date" href="#"' . $options['head_meta_font'] . '>April 8, 2017</a><span' . $options['head_meta_font'] . ' class="title-meta-divider"> &mdash; </span><a class="title-meta-comments" href="#"' . $options['head_meta_font'] . '>2 Comments</a></p>
									<h2><a href="#">The quick brown fox<br />jumps over the lazy dog.</a></h2>
								</div>
								<div class="post-content no-meta">
									<p>Leverage agile frameworks to provide a <a href="#">robust synopsis</a> for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.</p>
									<p>Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a <a href="#">streamlined</a> cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.
									</p>
									<p><a href="#" class="more-link">Read more</a></p>
									<div class="post-meta">
										<div class="category-meta ' . $options['display_categories'] . '"' . $options['meta_font'] . '>
											<span>Published by Semplice in </span><a href="#">Design</a>, <a href="#">Artworks</a>
										</div>
										<div class="tags-meta ' . $options['display_tags'] . '"' . $options['meta_font'] . '>
											<span>Tags: </span><a href="#">Photoshop</a>, <a href="#">Illustrator</a>
										</div>
									</div>
									<div id="share-holder">
										' . $this->share($options, true) . '
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				<section id="comments" class="comments-area">
					<div class="container">
						<div class="row">
							<div class="column" data-xl-width="8" data-md-width="11" data-sm-width="12" data-xs-width="12">
							' . semplice_comments_customize(false) . '
						</div>
					</div>
				</section>
			</div>
		';

		return $output;
	}
}

?>