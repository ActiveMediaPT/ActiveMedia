<?php

// -----------------------------------------
// semplice
// admin/atts/modules/blogposts.php
// -----------------------------------------

// width
$thumbnail = $atts->width;
$thumbnail['full'] = 'Fullscreen';

$date_format_help = '
	<table>
		<tbody>
			<tr>
				<th colspan="3">Day of Month</th>
			</tr>
			<tr class="bottom-border">
				<td style="width:10%;text-align:center"><code>d</code></td>
				<td style="width:55%">Numeric, with leading zeros</td>
				<td style="width:45%">01–31</td>
			</tr>
			<tr class="bottom-border">
				<td style="width:10%;text-align:center"><code>j</code></td>
				<td>Numeric, without leading zeros</td>
				<td>1–31</td>
			</tr>
			<tr>
				<td style="width:10%;text-align:center"><code>S</code></td>
				<td>English suffix for day of month</td>
				<td>1st, 2nd or 15th.</td>
			</tr>
			<tr>
				<th colspan="3">Weekday</th>
			</tr>
			<tr class="bottom-border">
				<td style="width:10%;text-align:center"><code>l</code></td>
				<td>Full name &nbsp;(lowercase ‘L’)</td>
				<td>Sunday – Saturday</td>
			</tr>
			<tr>
				<td style="width:10%;text-align:center"><code>D</code></td>
				<td>Three letter name</td>
				<td>Mon – Sun</td>
			</tr>
			<tr>
				<th colspan="3">Month</th>
			</tr>
			<tr class="bottom-border">
				<td style="width:10%;text-align:center"><code>m</code></td>
				<td>Numeric, with leading zeros</td>
				<td>01–12</td>
			</tr>
			<tr class="bottom-border">
				<td style="width:10%;text-align:center"><code>n</code></td>
				<td>Numeric, without leading zeros</td>
				<td>1–12</td>
			</tr>
			<tr class="bottom-border">
				<td style="width:10%;text-align:center"><code>F</code></td>
				<td>Textual full</td>
				<td>January – December</td>
			</tr>
			<tr>
				<td style="width:10%;text-align:center"><code>M</code></td>
				<td>Textual three letters</td>
				<td>Jan – Dec</td>
			</tr>
			<tr>
				<th colspan="3">Year</th>
			</tr>
			<tr class="bottom-border">
				<td style="width:10%;text-align:center"><code>Y</code></td>
				<td>Numeric, 4 digits</td>
				<td>Eg., 1999, 2003</td>
			</tr>
			<tr>
				<td style="width:10%;text-align:center"><code>y</code></td>
				<td>Numeric, 2 digits</td>
				<td>Eg., 99, 03</td>
			</tr>
		</body>
	</table>
';

$blogposts = array(
	'singlepost-notice' => array(
		'title' 	 => 'Singlepost Notice',
		'style-class'=> 'single-post-notice',
		'hide-title' => true,
		'data-hide-mobile' => true,
		'sp-notice' => array(
			'data-input-type' => 'notice',
			'hide-title' => true,
			'size'		 => 'span4',
			'class'     	=> 'ep-notice',
			'data-handler'  => 'blogposts',
			'default'    => 'Some blogpost editing options (assign custom styles, align paragraphs, oversized images) are only available in the default editor. Read our <a href="https://help.semplice.com/hc/en-us/articles/4418838557972" target="_blank">blog guide</a>.',
			'notice-type'=> 'warning',
		),
		
	),
	'refresh-grid' => array(
		'title'  	 => 'Refresh',
		'hide-title' => true,
		'break'		 => '1',
		'style-class'=> 'blog-button',
		'refresh' => array(
			'data-input-type' 	=> 'blog-button',
			'title'		 		=> 'Preview',
			'button-title'		=> 'Refresh preview',
			'hide-title'		=> true,
			//'help'				=> 'If you are happy with your settings, just press the regenerate button to generate a new preview with your updated settings.<br /><br />Please note that only published projects are visible in the portfolio grid.',
			'size'		 		=> 'span4',
			'class'				=> 'semplice-button regenerate-blogposts',
			'responsive'		=> true,
		),
	),
	'filter-options' => array(
		'title' => 'Blog Posts',
		'break'	=> '3,1,1,1,1,2,1',
		'style-class' => 'wp-tpl-all',
		'data-hide-mobile' => true,
		'filter_by' => array(
			'data-input-type' => 'select-box',
			'title'		 => 'Filter by',
			'size'		 => 'span2',
			'class'			=> 'editor-listen',
			'data-target'=> '.is-content',
			'data-handler'  => 'save',
			'default' 	 => '4',
			'data-visibility-switch' 	=> true,
			'data-visibility-values' 	=> 'latest,category,tag,author,post',
			'data-visibility-prefix'	=> 'ov-blog-taxonomy',
			'select-box-values' => array(
				'latest'	 => 'Latest',
				'category'   => 'Category',
				'tag'		 => 'Tag',
				'author'	 => 'Author',
				'post'		 => 'Post'
			),
		),
		'posts_per_page' => array(
			'title'			=> 'Per page',
			'help'			=> 'Define the number of posts per page. Set to 0 to show all posts without a pagination.',
			'size'			=> 'span1',
			'offset'		=> false,
			'data-input-type' 	=> 'range-slider',
			'default'		=> 10,
			'min'			=> 0,
			'max'			=> 99999,
			'class'			=> 'editor-listen',
			'data-handler'  => 'save',
		),
		'offset' => array(
			'title'			=> 'Offset',
			'help'			=> 'Number of post to displace or pass over.',
			'size'			=> 'span1',
			'offset'		=> false,
			'data-input-type' 	=> 'range-slider',
			'default'		=> 0,
			'min'			=> 0,
			'max'			=> 99999,
			'class'			=> 'editor-listen',
			'data-handler'  => 'save',
		),
		'categories' => array(
			'data-input-type' 	=> 'button',
			'title'		 		=> 'Categories',
			'button-title'		=> 'Select Categories',
			'size'		 		=> 'span4',
			'class'				=> 'semplice-button white-button expand-options admin-click-handler',
			'data-target'		=> 'category',
			'data-expand-options' => 'taxonomy',
			'style-class'		=> 'ov-blog-taxonomy-category'
		),
		'tags' => array(
			'data-input-type' 	=> 'button',
			'title'		 		=> 'Tags',
			'button-title'		=> 'Select Tags',
			'size'		 		=> 'span4',
			'class'				=> 'semplice-button white-button expand-options admin-click-handler',
			'data-target'		=> 'tag',
			'data-expand-options' => 'taxonomy',
			'style-class'		=> 'ov-blog-taxonomy-tag'
		),
		'authors' => array(
			'data-input-type' 	=> 'button',
			'title'		 		=> 'Authors',
			'button-title'		=> 'Select Authors',
			'size'		 		=> 'span4',
			'class'				=> 'semplice-button white-button expand-options admin-click-handler',
			'data-target'		=> 'author',
			'data-expand-options' => 'taxonomy',
			'style-class'		=> 'ov-blog-taxonomy-author'
		),
		'posts' => array(
			'data-input-type' 	=> 'button',
			'title'		 		=> 'Posts',
			'button-title'		=> 'Select Posts',
			'size'		 		=> 'span4',
			'class'				=> 'semplice-button white-button expand-options admin-click-handler',
			'data-target'		=> 'post',
			'data-expand-options' => 'taxonomy',
			'style-class'		=> 'ov-blog-taxonomy-post'
		),
		'order_by' => array(
			'data-input-type' => 'select-box',
			'title'		 => 'Order by',
			'size'		 => 'span2',
			'class'			=> 'editor-listen',
			'data-target'=> '.is-content',
			'data-handler'  => 'save',
			'default' 	 => 'date',
			'select-box-values' => array(
				'date'	 	=> 'Date',
				'modified'	=> 'Last modified',
				'author' 	=> 'Author',
				'ID'	 	=> 'Post ID',
				'rand'	 	=> 'Random'
			),
		),
		'order' => array(
			'data-input-type' 	=> 'switch',
			'switch-type'		=> 'twoway',
			'title'		 		=> 'Order',
			'size'		 		=> 'span2',
			'class'				=> 'editor-listen',
			'data-target'		=> '.is-content',
			'data-handler'  	=> 'save',
			'default' 	 		=> 'DESC',
			'help'				=> '<b>ASC</b> – ascending order from lowest to highest values (1, 2, 3; a, b, c).<br /><br /><b>DESC</b> – descending order from highest to lowest values (3, 2, 1; c, b, a).',
			'switch-values' => array(
				'DESC'	 => 'Desc',
				'ASC'	 => 'Asc'
			),
		),
		'pagination_visibility' => array(
			'data-input-type' 	=> 'switch',
			'switch-type'		=> 'twoway',
			'title'		 		=> 'Pagination Visibility',
			'size'		 		=> 'span4',
			'class'				=> 'editor-listen',
			'data-target'		=> '.is-content',
			'data-handler'  	=> 'save',
			'default' 	 		=> 'visible',
			'switch-values' => array(
				'visible'	 => 'Visible',
				'hidden'	 => 'Hidden'
			),
		),
	),
	'archive-per-page' => array(
		'title'  	 => 'Posts Per Page',
		'hide-title' => true,
		'break'		 => '1',
		'style-class' => 'archive-per-page-options',
		'data-hide-mobile' => true,
		'data-hide-mobile' => true,
		'template_posts_per_page' => array(
			'title'			=> 'Posts per page',
			'help'			=> 'Define the number of posts per page. Set to 0 to show all posts without a pagination.',
			'size'			=> 'span4',
			'offset'		=> false,
			'data-input-type' 	=> 'range-slider',
			'default'		=> 10,
			'min'			=> 0,
			'max'			=> 99999,
			'class'			=> 'editor-listen',
			'data-handler'  => 'save',
		),
	),
	'layout-options' => array(
		'title'  	 => 'Layout',
		'break'		 => '2,2,2,2,2,1,2,2',
		'style-class' => 'ep-collapsed',
		'data-hide-mobile' => true,
		'layout' => array(
			'data-input-type' => 'select-box',
			'title'		 => 'Grid',
			'size'		 => 'span2',
			'class'			=> 'editor-listen',
			'data-target'=> '.is-content',
			'default' 	 => 'list',
			'data-visibility-switch' 	=> true,
			'data-visibility-values' 	=> 'list,columns,fullwidth',
			'data-visibility-prefix'	=> 'ov-blog-grid',
			'style-class'	=> 'wp-tpl-singlepost',
			'data-handler'  => 'save',
			'select-box-values' => array(
				'list' 		=> 'List',
				'columns' 	=> 'Columns',
				'fullwidth' => 'Full Width'
			),
		),
		'background' => array(
			'title'				=> 'BG Color',
			'size'				=> 'span2',
			'data-input-type'	=> 'color',
			'default'			=> 'transparent',
			'class'				=> 'color-picker admin-listen-handler',
			'data-handler' 		=> 'colorPicker',
			'style-class'		=> 'wp-tpl-singlepost',
		),
		'width' => array(
			'data-input-type' => 'select-box',
			'title'		 => 'Columns per row',
			'size'		 => 'span2',
			'class'			=> 'editor-listen',
			'data-target'=> '.is-content',
			'default' 	 => '4',
			'style-class'	=> 'ov-blog-grid-columns',
			'data-handler'  => 'save',
			'select-box-values' => array(
				'12' => '1',
				'6' => '2',
				'4' => '3',
				'3' => '4',
				'2' => '6',
			),
		),
		'gutter_columns' => array(
			'title'			=> 'Column gutter',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' 	=> 'range-slider',
			'default'		=> 30,
			'min'			=> 0,
			'max'			=> 999,
			'class'			=> 'editor-listen',
			'data-handler'	=> 'save',
			'help'			=> 'Horizontal space between the columns.',
			'style-class'	=> 'ov-blog-grid-columns',
		),
		'listgrid' => array(
			'title'		 => 'Columns',
			'size'		 => 'span2',
			'data-input-type' => 'switch',
			'switch-type'=> 'icon-select',
			'class' 	 		=> 'editor-listen',
			'data-handler' 		=> 'save',
			'style-class'	=> 'ov-blog-grid-list',
			'default'	 => '4-8',
			'help'		 => 'If you choose to hide the post thumbnail this setting will be ignored and your content will be full width.',
			'tooltips'	 => array(
				'3-9'  		 => '1/4 &mdash; 3/4',
				'4-8'        => '1/3 &mdash; 2/3',
				'6-6' 		 => '1/2 &mdash; 1/2',
				'8-4'        => '2/3 &mdash; 1/3',
				'9-3'        => '3/4 &mdash; 1/4',
			),
			'switch-values' => array(
				'3-9'     	 => 'Left',
				'4-8'     	 => 'Left',
				'6-6' 		 => 'center',
				'3-9'  		 => 'Right',
				'8-4'  		 => 'Right',
				'9-3'  		 => 'Right',
			),
		),
		'gutter_list' => array(
			'title'			=> 'List gutter',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' 	=> 'range-slider',
			'default'		=> 30,
			'min'			=> 0,
			'max'			=> 999,
			'class'			=> 'editor-listen',
			'data-handler'	=> 'save',
			'help'			=> 'Horizontal space between the columns.',
			'style-class'	=> 'ov-blog-grid-list',
		),
		'fw_thumbnail_width' => array(
			'data-input-type' => 'select-box',
			'title'		 => 'Thumbnail Width',
			'size'		 => 'span2',
			'class'			=> 'editor-listen',
			'data-target'=> '.is-content',
			'default' 	 => '12',
			'style-class'	=> 'ov-blog-grid-fullwidth fw-thumb-width',
			'data-handler'  => 'save',
			'help' => 'Setting the width to \'Fullscreen\' will scale your thumbnail edge to edge in the browser and ignore any gutters or paddings. (both for the section and grid)',
			'select-box-values' => $thumbnail,
		),
		'fw_title_width' => array(
			'data-input-type' => 'select-box',
			'title'		 => 'Title Width',
			'size'		 => 'span2',
			'class'			=> 'editor-listen',
			'data-target'=> '.is-content',
			'default' 	 => '12',
			'style-class'	=> 'ov-blog-grid-fullwidth',
			'data-handler'  => 'save',
			'select-box-values' => $atts->width,
		),
		'fw_content_width' => array(
			'data-input-type' => 'select-box',
			'title'		 => 'Content Width',
			'size'		 => 'span2',
			'class'			=> 'editor-listen',
			'data-target'=> '.is-content',
			'default' 	 => '12',
			'style-class'	=> 'ov-blog-grid-fullwidth',
			'data-handler'  => 'save',
			'select-box-values' => $atts->width,
		),
		'fw_meta_width' => array(
			'data-input-type' => 'select-box',
			'title'		 => 'Meta Width',
			'size'		 => 'span2',
			'class'			=> 'editor-listen',
			'data-target'=> '.is-content',
			'default' 	 => '12',
			'style-class'	=> 'ov-blog-grid-fullwidth',
			'data-handler'  => 'save',
			'select-box-values' => $atts->width,
		),
		'content_order' => array(
			'data-input-type' 			=> 'sortable',
			'title'		 				=> 'Content order',
			'size'		 				=> 'span4',
			'class'						=> 'editor-listen',
			'data-handler'				=> 'save',
			'default' 	 				=> 'visible',
			'help'						=> 'Use drag and drop to change the order.<br /><br />Thumbnail order will be ignored for the columns &amp; list view.',
			'sortable-items' 			=> array(
				'thumb' 	=> 'Thumb',
				'category' 	=> 'Category',
				'title' 	=> 'Title',
				'content' 	=> 'Content',
				'meta' 		=> 'Meta',
				'tags'		=> 'Tags'
			),
		),
		'outer_padding' => array(
			'title'			=> 'Outer padding',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' 	=> 'range-slider',
			'default'		=> 0,
			'min'			=> 0,
			'max'			=> 999,
			'class'				=> 'editor-listen',
			'data-handler'		=> 'save',
			'data-has-unit'		=> true,
			'style-class'	=> 'wp-tpl-singlepost',
		),
		'inner_padding' => array(
			'title'			=> 'Inner padding',
			'help'			=> 'Inner padding has no effect for the full width layout.',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' 	=> 'range-slider',
			'default'		=> 0,
			'min'			=> 0,
			'max'			=> 999,
			'class'				=> 'editor-listen',
			'data-handler'		=> 'save',
			'data-has-unit'		=> true,
			'style-class'	=> 'wp-tpl-singlepost',
		),
		'bottom_spacing' => array(
			'title'			=> 'Bottom Spacing',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' 	=> 'range-slider',
			'default'		=> 0,
			'min'			=> 0,
			'max'			=> 999,
			'class'				=> 'editor-listen',
			'data-handler'		=> 'save',
			'data-has-unit'		=> true,
			'help'				=> 'Set this to 0 if you use a seperator.',
			'style-class'	=> 'wp-tpl-singlepost',
		),
	),
	// mobile layout options
	'bp_layout_responsive_lg' => $atts->get_mobile('blogposts', 'Desktop', 'lg', array('type' => 'layout')),
	'bp_layout_responsive_md' => $atts->get_mobile('blogposts', 'Tablet Landscape', 'md', array('type' => 'layout')),
	'bp_layout_responsive_sm' => $atts->get_mobile('blogposts', 'Tablet Portrait', 'sm', array('type' => 'layout')),
	'bp_layout_responsive_xs' => $atts->get_mobile('blogposts', 'Mobile', 'xs', array('type' => 'layout')),
	'archive-options' => $atts->get('blogposts', array('archive' => true, 'breakpoint' => false)),
	// mobile archive options
	'bp_archive_responsive_lg' => $atts->get('blogposts', array('archive' => true, 'breakpoint' => 'lg')),
	'bp_archive_responsive_md' => $atts->get('blogposts', array('archive' => true, 'breakpoint' => 'md')),
	'bp_archive_responsive_sm' => $atts->get('blogposts', array('archive' => true, 'breakpoint' => 'sm')),
	'bp_archive_responsive_xs' => $atts->get('blogposts', array('archive' => true, 'breakpoint' => 'xs')),
	'thumbnail-options' => array(
		'title'  	 => 'Post Thumbnail',
		'break'		 => '2',
		'style-class' => 'ep-collapsed',
		'data-hide-mobile' => true,
		'thumbnail_visibility' => array(
			'data-input-type' 			=> 'switch',
			'switch-type'				=> 'twoway',
			'title'		 				=> 'Visibility',
			'size'		 				=> 'span2',
			'class'						=> 'editor-listen',
			'data-handler'				=> 'save',
			'default' 	 				=> 'block',
			'switch-values' => array(
				'block'	=> 'Visible',
				'none'  => 'Hidden',
			),
		),
		'thumbnail_alignment' => array(
			'data-input-type' => 'switch',
			'switch-type'=> 'twoway',
			'title'		 => 'Alignment',
			'size'		 => 'span2',
			'class'      		=> 'editor-listen',
			'data-handler' 		=> 'save',
			'style-class'	=> 'ov-blog-grid-default',
			'default' 	 => 'left',
			'help'			=> 'Only applies for the \'List\' layout.',
			'switch-values' => array(
				'left'		=> 'Left',
				'right'	 	=> 'Right',
			),
		),
	),
	// mobile thumbnail options
	'bp_thumbnail_responsive_lg' => $atts->get_mobile('blogposts', '', 'lg', array('type' => 'thumbnail')),
	'bp_thumbnail_responsive_md' => $atts->get_mobile('blogposts', '', 'md', array('type' => 'thumbnail')),
	'bp_thumbnail_responsive_sm' => $atts->get_mobile('blogposts', '', 'sm', array('type' => 'thumbnail')),
	'bp_thumbnail_responsive_xs' => $atts->get_mobile('blogposts', '', 'xs', array('type' => 'thumbnail')),
	'Seperator-options' => array(
		'title'  	 => 'Post Seperator',
		'break'		 => '2,2,2',
		'data-hide-mobile' => true,
		'style-class' => 'ep-collapsed wp-tpl-singlepost',
		'help'						=> 'If you use the seperator spacings don\'t forget to remove the bottom spacing from the layout settings.',
		'post_seperator_visibility' => array(
			'data-input-type' 			=> 'switch',
			'switch-type'				=> 'twoway',
			'title'		 				=> 'Visibility',
			'size'		 				=> 'span2',
			'class'						=> 'editor-listen',
			'data-handler'				=> 'save',
			'default' 	 				=> 'visible',
			'switch-values' => array(
				'visible'  	=> 'Visible',
				'hidden'	=> 'Hidden',
			),
		),
		'post_seperator_last' => array(
			'data-input-type' 			=> 'switch',
			'switch-type'				=> 'twoway',
			'title'		 				=> 'After Last Post',
			'size'		 				=> 'span2',
			'class'						=> 'editor-listen',
			'data-handler'				=> 'save',
			'default' 	 				=> 'visible',
			'switch-values' => array(
				'visible'  	=> 'Visible',
				'hidden'	=> 'Hidden',
			),
		),
		'post_seperator_height' => array(
			'title'			=> 'Height',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' 	=> 'range-slider',
			'default'		=> 1,
			'min'			=> 0,
			'max'			=> 999,
			'class'				=> 'editor-listen',
			'data-handler'		=> 'save',
			'data-has-unit'		=> true,
		),
		'post_seperator_color' => array(
			'title'				=> 'Color',
			'data-style-option' => true,
			'size'				=> 'span2',
			'data-input-type'	=> 'color',
			'default'			=> '#b0b0b0',
			'class'				=> 'color-picker admin-listen-handler',
			'data-handler' 		=> 'colorPicker',
		),
		'post_seperator_top_spacing' => array(
			'title'			=> 'Top Spacing',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' 	=> 'range-slider',
			'default'		=> 26,
			'min'			=> 0,
			'max'			=> 999,
			'class'				=> 'editor-listen',
			'data-handler'		=> 'save',
			'data-has-unit'		=> true,
		),
		'post_seperator_bottom_spacing' => array(
			'title'			=> 'Bottom Spacing',
			'size'			=> 'span2',
			'offset'		=> false,
			'data-input-type' 	=> 'range-slider',
			'default'		=> 26,
			'min'			=> 0,
			'max'			=> 999,
			'class'				=> 'editor-listen',
			'data-handler'		=> 'save',
			'data-has-unit'		=> true,
		),
	),
	// mobile seperator options
	'bp_seperator_responsive_lg' => $atts->get_mobile('blogposts', '', 'lg', array('type' => 'seperator')),
	'bp_seperator_responsive_md' => $atts->get_mobile('blogposts', '', 'md', array('type' => 'seperator')),
	'bp_seperator_responsive_sm' => $atts->get_mobile('blogposts', '', 'sm', array('type' => 'seperator')),
	'bp_seperator_responsive_xs' => $atts->get_mobile('blogposts', '', 'xs', array('type' => 'seperator')),
	'meta-options' => array(
		'title'  	 => 'Meta Options',
		'break'		 => '1,2,2,2',
		'data-hide-mobile' => true,
		'style-class' => 'ep-collapsed',
		'meta_visibility' => array(
			'data-input-type' 			=> 'switch',
			'switch-type'				=> 'twoway',
			'title'		 				=> 'Visibility',
			'size'		 				=> 'span4',
			'class'						=> 'editor-listen',
			'data-handler'				=> 'save',
			'default' 	 				=> 'visible',
			'switch-values' => array(
				'visible'	=> 'Visible',
				'hidden'  	=> 'Hidden',
			),
		),
		'author_visibility' => array(
			'data-input-type' => 'select-box',
			'title'		 => 'Author Visibility',
			'size'		 => 'span2',
			'class'			=> 'editor-listen',
			'data-target'=> '.is-content',
			'data-handler'  => 'save',
			'default' 	 => '4',
			'select-box-values' => array(
				'both'	  => 'Show Author &amp; Avatar',
				'visible' => 'Show Author Only',
				'hidden'  => 'Hide both'
			),
		),
		'date_visibility' => array(
			'data-input-type' 			=> 'switch',
			'switch-type'				=> 'twoway',
			'title'		 				=> 'Show Date',
			'size'		 				=> 'span2',
			'class'						=> 'editor-listen',
			'data-handler'				=> 'save',
			'default' 	 				=> 'visible',
			'switch-values' => array(
				'visible'	=> 'Yes',
				'hidden'  	=> 'No',
			),
		),
		'comment_visibility' => array(
			'data-input-type' 			=> 'switch',
			'switch-type'				=> 'twoway',
			'title'		 				=> 'Show Comments',
			'size'		 				=> 'span2',
			'class'						=> 'editor-listen',
			'data-handler'				=> 'save',
			'default' 	 				=> 'hidden',
			'switch-values' => array(
				'visible'	=> 'Yes',
				'hidden'  	=> 'No',
			),
		),
		'readtime_visibility' => array(
			'data-input-type' 			=> 'switch',
			'switch-type'				=> 'twoway',
			'title'		 				=> 'Show Readtime',
			'size'		 				=> 'span2',
			'class'						=> 'editor-listen',
			'data-handler'				=> 'save',
			'default' 	 				=> 'hidden',
			'switch-values' => array(
				'visible'	=> 'Yes',
				'hidden'  	=> 'No',
			),
		),
		'date_format' => array(
			'data-input-type' => 'select-box',
			'title'		 => 'Date Format',
			'size'		 => 'span2',
			'class'			=> 'editor-listen',
			'data-target'=> '.is-content',
			'data-handler'  => 'save',
			'default' 	 => '4',
			'select-box-values' => array(
				'F j, Y' 	=> date('F j, Y'),
 				'Y-m-d'		=> date('Y-m-d'),
 				'm/d/Y'		=> date('m/d/Y'),
 				'm.d.Y'		=> date('m.d.Y'),
 				'd/m/Y'		=> date('d/m/Y'),
 				'd.m.Y'		=> date('d.m.Y'),
 				'custom'	=> 'Custom',
			),
		),
		'date_custom' => array(
			'data-input-type'	=> 'input-text',
			'title'		 	=> 'Custom Date',
			'size'		 	=> 'span2',
			'placeholder'	=> 'F j, Y',
			'default'		=> '',
			'class'      	=> 'editor-listen',
			'data-handler' 	=> 'save',
			'help'			=> $date_format_help,
		),
	),
	// mobile meta options
	'bp_meta_responsive_lg' => $atts->get_mobile('blogposts', '', 'lg', array('type' => 'meta')),
	'bp_meta_responsive_md' => $atts->get_mobile('blogposts', '', 'md', array('type' => 'meta')),
	'bp_meta_responsive_sm' => $atts->get_mobile('blogposts', '', 'sm', array('type' => 'meta')),
	'bp_meta_responsive_xs' => $atts->get_mobile('blogposts', '', 'xs', array('type' => 'meta')),
	'formatting' => $atts->get('blogposts', array('archive' => false, 'breakpoint' => false)),
	'bp_formatting_responsive_lg' => $atts->get('blogposts', array('archive' => false, 'breakpoint' => 'lg')),
	'bp_formatting_responsive_md' => $atts->get('blogposts', array('archive' => false, 'breakpoint' => 'md')),
	'bp_formatting_responsive_sm' => $atts->get('blogposts', array('archive' => false, 'breakpoint' => 'sm')),
	'bp_formatting_responsive_xs' => $atts->get('blogposts', array('archive' => false, 'breakpoint' => 'xs')),
);

?>