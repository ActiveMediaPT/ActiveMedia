<?php

// -----------------------------------------
// get blog sr mode
// -----------------------------------------

function semplice_get_blog_sr_status() {

	// get options
	$blog_options = semplice_customize('blog');

	// status
	$status = 'enabled';

	// set blog sr status individually
	if(is_array($blog_options)) {
		if(isset($blog_options['blog_scroll_reveal']) && $blog_options['blog_scroll_reveal'] == 'disabled') {
			$status = 'disabled';
		}
	}

	// return
	return $status;
}

// ----------------------------------------
// get blog navbar
// ----------------------------------------

function semplice_get_blog_navbar() {
	// define
	$navbar = false;
	// blog customization
	$blog = semplice_customize('blog');
	// check nav
	if(isset($blog['blog_navbar']) && !empty($blog['blog_navbar']) && $blog['blog_navbar'] !== 'default') {
		$navbar = $blog['blog_navbar'];
	}
	return $navbar;
}

// -----------------------------------------
// get a select list for a specific taxonomy
// -----------------------------------------

function semplice_taxonomy_checklist($taxonomy) {
	if($taxonomy != 'author') {
		// tag
		if($taxonomy == 'tag') {
			$taxonomy = 'post_tag';
		}
		$post_id = 0;
		$output = wp_terms_checklist(
			$post_id,
			array(
				'taxonomy'             => $taxonomy,
				'descendants_and_self' => 0,
				'selected_cats'        => false,
				'popular_cats'         => false,
				'walker'               => null,
				'checked_ontop'        => true,
				'echo'				   => false,
			)
		);
		return $output;
	} else {
		// output
		$output = '';
		// get users
		$authors = get_users();
		// iterate users
		foreach ($authors as $author) {
			$output .= '<li id="author-' . $author->ID . '"><label class="selectit"><input value="' . $author->ID . '" type="checkbox" name="post_author[]" id="in-author-' . $author->ID . '"/>' . $author->display_name . '</label></li>';
		}
		// return
		return $output;
	}
}

// -----------------------------------------
// semplice get blog args
// -----------------------------------------

function semplice_get_blog_args($args, $filter) {
	// filter
	if(false !== $filter) {
		if($filter['type'] == 'category' || $filter['type'] == 'tag') {
			// taxonomy
			$taxonomy = $filter['meta'];
			if($taxonomy) {
				if($taxonomy->taxonomy == 'category') {
					$args['category__in'] = array($taxonomy->term_id);
				} else {
					$args['tag__in'] = array($taxonomy->term_id);
				}
			}
		} else if($filter['type'] == 'author') {
			$args['author__in'] = array($filter['meta']);
		} else if($filter['type'] == 'searchresults') {
			$args['s'] = $filter['meta'];
			// never show a pagination for search results
			$args['posts_per_page'] = -1;
		} else if($filter['type'] == 'singlepost') {
			if(false === $filter['meta']) {
				$posts = get_posts(array('numberposts' => 1));
				if(null !== $posts) {
					foreach ($posts as $post) {
						$args['post__in'] = array($post->ID);
					}
				}
			} else {
				$args['post__in'] = array($filter['meta']);
			}
		}
	}
	// return args
	return $args;
}

// -----------------------------------------
// semplice get blog url
// -----------------------------------------

function semplice_get_blog_url($filter) {
	// url
	if(!isset($filter['url']) || false === $filter['url']) {
		global $wp;
		$url = home_url($wp->request);
	} else {
		$url = $filter['url'];
	}
	// strip out any /page/x
	$url = preg_replace('/\/page\/([0-9]+)/', '', $url);
	// filter
	if(false !== $filter) {
		if($filter['type'] == 'category' || $filter['type'] == 'tag') {
			// taxonomy
			$taxonomy = $filter['meta'];
			if($taxonomy) {
				$url = get_term_link($taxonomy);
			}
		} else if($filter['type'] == 'author') {
			$url = get_author_posts_url($filter['meta']);
		}
	}
	// return url
	return $url;
}

// -----------------------------------------
// get blog search
// -----------------------------------------

function semplice_get_blog_search($font_family, $placeholder) {
	return '
		<form role="search" method="get" class="search-form blogposts-search is-content" action="' . esc_url(home_url('/')) . '">
			<input type="search" class="search-field" placeholder="' . $placeholder . '" name="s" data-font="' . $font_family . '" />
			<span class="blogsearch-icon">' . get_svg('backend', 'icons/blog_search') . '</span>
			<input type="submit" class="search-submit" value="" />
		</form>
	';
}

// -----------------------------------------
// get comments css
// -----------------------------------------

function semplice_get_comments_css($options, $prefix, $is_admin) {
	// css
	$css = '';
	// comment bg color
	if(isset($options['comment_bg_color'])) {
		$css .= $prefix . ' { background-color: ' . $options['comment_bg_color'] . '; }';
	}
	// comment title color
	if(isset($options['comment_title_color'])) {
		$css .= $prefix . ' .comments-title { color: ' . $options['comment_title_color'] . '; }';
	}
	// comment title alignment
	if(isset($options['comment_title_align'])) {
		$css .= $prefix . ' .comments-title { text-align: ' . $options['comment_title_align'] . '; }';
	}
	// comment title font size
	if(isset($options['comment_title_font_size'])) {
		$css .= $prefix . ' .comments-title { font-size: ' . $options['comment_title_font_size'] . '; }';
	}
	// comment author font color
	if(isset($options['comment_author_color'])) {
		$css .= $prefix . ' .comments .comment .comment-author cite, ' . $prefix . ' .comments .comment .comment-author cite a { color: ' . $options['comment_author_color'] . '; }';
	}
	// comment author hover
	if(isset($options['comment_author_mouseover_color'])) {
		$css .= $prefix . ' .comments .comment .comment-author cite a:hover { color: ' . $options['comment_author_mouseover_color'] . '; }';
	}
	// comment date font color
	if(isset($options['comment_date_color'])) {
		$css .= $prefix . ' .comments .comment .comment-meta a { color: ' . $options['comment_date_color'] . '; }';
	}
	// comment date hover
	if(isset($options['comment_date_mouseover_color'])) {
		$css .= $prefix . ' .comments .comment .comment-meta a:hover { color: ' . $options['comment_date_mouseover_color'] . '; }';
	}
	// comment font color
	if(isset($options['comment_content_color'])) {
		$css .= $prefix . ' .comments .comment .comment-content { color: ' . $options['comment_content_color'] . '; }';
	}
	// comment font size
	if(isset($options['comment_content_font_size'])) {
		$css .= $prefix . ' .comments .comment .comment-content { font-size: ' . $options['comment_content_font_size'] . '; }';
	}
	// comment line height
	if(isset($options['comment_content_line_height'])) {
		$css .= $prefix . ' .comments .comment .comment-content { line-height: ' . $options['comment_content_line_height'] . '; }';
	}
	// comment divider color
	if(isset($options['comment_content_divider_color'])) {
		$css .= $prefix . ' .comments .comment, ' . $prefix . ' .comments .comment .depth-2, ' . $prefix . ' .comments .comment .depth-3 { border-color: ' . $options['comment_content_divider_color'] . '; }';
	}
	// comment reply bg color
	if(isset($options['comment_reply_bg_color'])) {
		$css .= $prefix . ' .comments .comment .reply a, ' . $prefix . ' #respond #reply-title #cancel-comment-reply-link { background-color: ' . $options['comment_reply_bg_color'] . '; }';
	}
	// comment reply color
	if(isset($options['comment_reply_color'])) {
		$css .= $prefix . ' .comments .comment .reply a, ' . $prefix . ' #respond #reply-title #cancel-comment-reply-link { color: ' . $options['comment_reply_color'] . '; }';
	}
	// comment reply border
	if(isset($options['comment_reply_border_color'])) {
		$css .= $prefix . ' .comments .comment .reply a, ' . $prefix . ' #respond #reply-title #cancel-comment-reply-link { border-color: ' . $options['comment_reply_border_color'] . '; }';
	}
	// respond color
	if(isset($options['comment_respond_color'])) {
		$css .= $prefix . ' #respond #reply-title, ' . $prefix . ' #respond #reply-title a { color: ' . $options['comment_respond_color'] . '; }';
	}

	// form open
	$css .= '
		' . $prefix . ' form#commentform .comment-input input, ' . $prefix . ' form#commentform textarea,
		' . $prefix . ' form#commentform .comment-input input:hover, ' . $prefix . ' form#commentform textarea:hover,
		' . $prefix . ' form#commentform .comment-input input:focus, ' . $prefix . ' form#commentform textarea:focus {
	';

	// form bg color
	if(isset($options['comment_form_bg_color'])) {
		$css .= 'background-color: ' . $options['comment_form_bg_color'] . ';';
	}
	// form color
	if(isset($options['comment_form_color'])) {
		$css .= 'color: ' . $options['comment_form_color'] . ';';
	}
	// border color
	if(isset($options['comment_form_border_color'])) {
		$css .= 'border-color: ' . $options['comment_form_border_color'] . ';';
	}

	// form close
	$css .= '}';

	// placeholder color
	if(isset($options['comment_form_placeholder_color'])) {
		$css .= $prefix . ' form#commentform .comment-input input::placeholder, ' . $prefix . ' form#commentform .comment-input textarea::placeholder { color: ' . $options['comment_form_placeholder_color'] . '; }';
	}

	// submit open
	$css .= '
		' . $prefix . ' form#commentform #submit, ' . $prefix . ' form#commentform #submit:hover, ' . $prefix . ' form#commentform #submit:focus {
	';

	// form bg color
	if(isset($options['comment_submit_bg_color'])) {
		$css .= 'background-color: ' . $options['comment_submit_bg_color'] . ';';
	}
	// form color
	if(isset($options['comment_submit_color'])) {
		$css .= 'color: ' . $options['comment_submit_color'] . ';';
	}
	// form color
	if(isset($options['comment_submit_border_color'])) {
		$css .= 'border-color: ' . $options['comment_submit_border_color'] . ';';
	}

	// submit close
	$css .= '}';

	// for the blogcomments module
	if($prefix != '#comments') {
		// paddings
		$paddings = array(
			'comment_title_padding_top'		=> '5rem',
			'comment_title_padding_bottom'	=> '3.333333333333333rem',
		);
		// iterate
		foreach ($paddings as $padding => $val) {
			if(isset($options[$padding])) {
				$paddings[$padding] = $options[$padding];
			}
		}
		// add to css
		$css .= $prefix . ' .comments-title { padding: ' . $paddings['comment_title_padding_top'] . ' 0rem ' . $paddings['comment_title_padding_bottom'] . ';}';
	}

	// ret
	return $css;
}

// -----------------------------------------
// semplice comment html
// -----------------------------------------

function semplice_comment_html($comment, $args, $depth){
	// get options
	extract( shortcode_atts(
		array(
			'comment_author_font_family' 	=> 'regular',
			'comment_date_font_family'	 	=> 'serif_regular',
			'comment_content_font_family'	=> 'regular',
			'comment_reply_font_family'		=> 'regular'
		),$args['options'])
	);
	// getcomment content
	ob_start();
	comment_text();
	$comment_content = ob_get_clean();
	$comment_content = str_replace(array('<p>', '</p>'), array('<div>', '</div>'), $comment_content);
	?>
	<div class="comment thread-even depth-<?php echo $depth; ?>" id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard" data-font="<?php echo $comment_author_font_family; ?>">
			<?php echo get_avatar($comment, $args['avatar_size']); ?>	
			<cite class="fn">
				<?php echo get_comment_author_link($comment); ?>
			</cite>
		</div>
		<div class="comment-meta commentmetadata">
			<a href="<?php echo esc_url(get_comment_link($comment, $args)); ?>" data-font="<?php echo $comment_date_font_family; ?>">
				<?php echo get_comment_date( '', $comment ) . __(' at ', 'semplice') . get_comment_time(); ?>
			</a>
		</div>
		<?php if('0' == $comment->comment_approved ) : ?>
			<div class="comment-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></div>
		<?php endif; ?>
		<div class="comment-content" data-font="<?php echo $comment_content_font_family; ?>"><?php echo $comment_content; ?></div>
		<?php
			comment_reply_link(array_merge($args, array(
				'add_below' => 'div-comment',
				'depth'     => $depth,
				'max_depth' => $args['max_depth'],
				'before'    => '<div class="reply" data-font="' . $comment_reply_font_family . '">',
				'after'     => '</div>'
			)));
		?>
	<?php
}

// -----------------------------------------
// get blog comments
// -----------------------------------------

function semplice_get_comments($mode, $comments, $options) {
	// has options?
	if(false === $options) {
		$options = semplice_customize('blog');
	}
	// comments args
	$list_comments_args = array(
		'avatar_size' => 48,
		'style'       => 'div',
		'short_ping'  => true,
		'reply_text'  => __('Reply', 'semplice'),
		'max_depth'   => 3,
		'per_page' 	  => -1,
		'callback'	  => 'semplice_comment_html',
		'options'	  => $options,
	);
	// width for default blog
	$width = 8;
	if(isset($options['blog_width'])) {
		$width = $options['blog_width'];
	}
	// title font
	$title_font = 'regular';
	if(isset($options['comment_title_font_family'])) {
		$title_font = $options['comment_title_font_family'];
	}
	// title visibility
	$title_visibility = 'visible';
	if(isset($options['comment_title_visibility'])) {
		$title_visibility = $options['comment_title_visibility'];
	}
	// comments open
	$output = '
		<section id="comments" class="comments-area">
			<div class="container">
				<div class="row">
					<div class="column" data-xl-width="' . $width . '" data-md-width="11" data-sm-width="12" data-xs-width="12">
	';
	// header and comment list
	$comments_html = '<div class="comments-title ' . $title_visibility . '" data-font="' . $title_font . '">' . __('Comments', 'semplice') . '</div>';
	// has commentsß
	if($comments) {
		// comments open
		$comments_html .= '<div class="comments">';
		// ob start
		ob_start();
		// get comments
		wp_list_comments($list_comments_args, $comments);
		// change p to div
		$comments_html .= ob_get_clean();
		//$comments_html = str_replace(array('<p>', '</p>'), array('<div class="comment-content">', '</div>'), $comments_html);
		// close comments list
		$comments_html .= '</div>';
	} else {
		$comments_html .= '<p class="no-comments">' . __('No Comments.', 'semplice') . '</p>';
	}
	// comments closed
	if(!comments_open() && get_comments_number() && post_type_supports(get_post_type(),'comments')) {
		$comments_html .= '<p class="no-comments">' . __('Comments are closed.', 'semplice') . '</p>';
	}

	// comments form
	$commenter = wp_get_current_commenter();
	$comments_html .= semplice_get_comment_form($options, array(
		'name'   => $commenter['comment_author'],
		'email'	 => $commenter['comment_author_email'],
		'url'	 => $commenter['comment_author_url'],
	));

	// mode
	if($mode == 'default') {
		$output .= $comments_html;
		// close comments
		$output .= '</div></div></div></section>';
	} else {
		$output = $comments_html;
	}

	// return
	return $output;
}

// -----------------------------------------
// get blog comments form
// -----------------------------------------

function semplice_get_comment_form($options, $author) {
	// get options
	extract( shortcode_atts(
		array(
			'comment_form_font_family' 		=> 'regular',
			'comment_submit_font_family'	=> 'regular',
			'comment_reply_font_family'		=> 'regular',
			'comment_respond_font_family'	=> 'regular',
			'comment_submit_font_family'	=> 'regular'
		),$options)
	);
	// comment args
	$comment_form_args = array(
		'id_form' 				=> 'commentform',
		'id_submit' 			=> 'submit',
		'title_reply' 			=> '<span class="leave-reply-span">' . __('Leave a reply', 'semplice') . '</span><span class="reply-to-span" data-base="' . __('Reply to', 'semplice') . '">' . __('Reply to', 'semplice') . '</span>',
		'title_reply_before' 	=> '<h3 id="reply-title" class="comment-reply-title" data-font="' . $comment_respond_font_family . '">',
		'cancel_reply_before' 	=> '<span data-font="' . $comment_reply_font_family . '">',
		'cancel_reply_after'	=> '</span>',
		'cancel_reply_link' 	=> __('Cancel reply', 'semplice'),
		'label_submit' 			=> __('Post Comment', 'semplice'),
		'logged_in_as' 			=> '',
		'comment_field' 		=> '<div class="comment-input"><textarea id="comment-input" class="mb10" data-font="' . $comment_form_font_family . '" name="comment" cols="45" rows="8" placeholder="' . __('Your comment*', 'semplice') . '" required email></textarea></div>',
		'comment_notes_before' 	=> '',
		'comment_notes_after' 	=> '',
		'class_submit' 			=> $comment_submit_font_family,
		'fields' => apply_filters( 'comment_form_default_fields', array(
			'author' => '<div class="comment-input"><input id="author" class="input" data-font="' . $comment_form_font_family . '" name="author" type="text" value="' . esc_attr($author['name']) . '" size="30" placeholder="' . __( 'Author*', 'semplice' ) . '" required /></div>',
			'email'  => '<div class="comment-input"><input id="email" class="input" data-font="' . $comment_form_font_family . '" name="email" type="email" value="' . esc_attr($author['email']) . '" size="30" placeholder="' . __( 'E-Mail Address*', 'semplice' ) . '" required /></div>',
			'url'    => '<div class="comment-input"><input id="url" class="input" data-font="' . $comment_form_font_family . '" name="url" type="text" value="' . esc_attr($author['url']) . '" size="30" placeholder="' . __( 'Website', 'semplice' ) . '" /></div>'
		))
	);
	// start output
	ob_start();
	// echo form
	comment_form($comment_form_args);
	// get contents
	$output = ob_get_clean();
	// return
	return $output;
}

// -----------------------------------------
// blog comments for customize
// -----------------------------------------

function semplice_comments_customize($options) {
	// has options?
	if(false === $options) {
		$options = semplice_customize('blog');
	}
	// get options
	extract( shortcode_atts(
		array(
			'comment_title_visibility'		=> 'visible',
			'comment_title_font_family'		=> 'regular',
			'comment_author_font_family' 	=> 'regular',
			'comment_date_font_family'	 	=> 'serif_regular',
			'comment_content_font_family'	=> 'regular',
			'comment_respond_font_family'	=> 'regular',
			'comment_reply_font_family'		=> 'regular',
			'comment_form_font_family'		=> 'regular',
			'comment_submit_font_family'	=> 'regular'
		),$options)
	);
	return '
		<div class="comments-title ' . $comment_title_visibility . '" data-font="' . $comment_title_font_family . '">Comments</div>
		<div class="comments">
			<div class="comment odd alt thread-odd thread-alt depth-1 parent" id="comment-14">
				<div class="comment-author vcard" data-font="' . $comment_author_font_family . '">
					<img alt="" src="' . get_template_directory_uri() . '/assets/images/admin/avatar_one.png" class="avatar avatar-48 photo" height="48" width="48">
					<cite class="fn">
						<a href="http://homenick.org" rel="external nofollow" class="url">Johnnyboy</a>
					</cite>
				</div>
				<div class="comment-meta commentmetadata">
					<a data-font="' . $comment_date_font_family . '">May 23, 2017 at 8:12 pm</a>
				</div>
				<div class="comment-content" data-font="' . $comment_content_font_family . '">Nulla est itaque velit sint sit. Cumque eos cum sit soluta sint. Laudantium et doloribus reiciendis distinctio sed repudiandae. Ad quasi qui eum optio.</div>
				<div class="reply" data-font="' . $comment_reply_font_family . '">
					<a rel="nofollow" class="comment-reply-link" aria-label="Reply to Brandyn Flatley">Reply</a>
				</div>
				<div class="comment byuser comment-author-michael bypostauthor even depth-2" id="comment-41">
					<div class="comment-author vcard" data-font="' . $comment_author_font_family . '">
						<img alt="" src="' . get_template_directory_uri() . '/assets/images/admin/avatar_two.png" class="avatar avatar-48 photo" height="48" width="48">			
						<cite class="fn">Vanessa</cite>
					</div>
					<div class="comment-meta commentmetadata">
						<a data-font="' . $comment_date_font_family . '">May 24, 2017 at 2:05 pm</a>
					</div>
					<div class="comment-content" data-font="' . $comment_content_font_family . '">Nulla est itaque velit sint sit. Cumque eos cum sit soluta sint. Laudantium et doloribus reiciendis distinctio sed repudiandae. Ad quasi qui eum optio.</div>
					<div class="reply" data-font="' . $comment_reply_font_family . '">
						<a rel="nofollow" class="comment-reply-link" aria-label="Reply to Michael">Reply</a>
					</div>
					</div><!-- #comment-## -->
			</div><!-- #comment-## -->
			<div class="comment odd alt thread-even depth-1" id="comment-15">
				<div class="comment-author vcard" data-font="' . $comment_author_font_family . '">
					<img alt="" src="' . get_template_directory_uri() . '/assets/images/admin/avatar_three.png" class="avatar avatar-48 photo" height="48" width="48">			
					<cite class="fn">
						<a rel="external nofollow" class="url">Frucade</a>
					</cite>
				</div>
				<div class="comment-meta commentmetadata">
					<a data-font="' . $comment_date_font_family . '">May 23, 2017 at 8:12 pm</a>&nbsp;&nbsp;
				</div>
				<div class="comment-content" data-font="' . $comment_content_font_family . '">Voluptatem non quae a. Minima tempora totam quaerat enim. Voluptatum sint fugiat consequatur nemo quos animi.</div>
				<div class="reply" data-font="' . $comment_reply_font_family . '">
					<a rel="nofollow" class="comment-reply-link" aria-label="Reply to Olin Schmidt">Reply</a>
				</div>
			</div><!-- #comment-## -->
		</div>
		<div id="respond" class="comment-respond">
			<h3 id="reply-title" class="comment-reply-title" data-font="' . $comment_respond_font_family . '">Reply to <small></small>
				Vanessa<a rel="nofollow" class="semplice-event" data-event-type="helper" data-event="removeCommentReplyLink" id="cancel-comment-reply-link" data-font="' . $comment_reply_font_family . '">Cancel Reply</a>
			</h3>
			<form method="post" id="commentform" class="comment-form">
				<div class="comment-input">
					<textarea id="comment-input" class="mb10" data-font="' . $comment_form_font_family . '" name="comment" cols="45" rows="8" placeholder="Your comment*" required="" email="">Dear Vanessa, i hope this comment will reach you in good health. I just stumbled upon your website and i have to say its ...</textarea>
				</div>
				<p class="form-submit">
					<input name="submit" type="submit" id="submit" class="submit" value="Post Comment" data-font="' . $comment_submit_font_family . '">
					<input type="hidden" name="comment_post_ID" value="1" id="comment_post_ID">
					<input type="hidden" name="comment_parent" id="comment_parent" value="37">
				</p>
			</form>
		</div>
	';
}
?>