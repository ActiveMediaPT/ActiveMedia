<?php

// -----------------------------------------
// semplice
// admin/atts/modules.php
// -----------------------------------------

// modules list
$modules = array('paragraph', 'text', 'fluidtext', 'button', 'image', 'gallery', 'oembed', 'spacer', 'video', 'portfoliogrid', 'singleproject', 'advancedportfoliogrid', 'dribbble', 'code', 'share', 'socialprofiles', 'lottie', 'blogposts', 'blogarchives', 'blogsearch', 'marquee', 'blogcomments', 'accordion', 'instagram', 'gallerygrid', 'mailchimp', 'beforeafter');

// include files
foreach ($modules as $module) {
	require get_template_directory() . '/admin/atts/modules/' . $module . '.php';
}

// modules array
$module_options = array(

	// paragraph
	'paragraph' => $paragraph,

	// text
	'text' => $text,

	// fluid text
	'fluidtext' => $fluidtext,

	// button module
	'button' => $button,

	// image module
	'image' => $image,

	// gallery
	'gallery' => $gallery,

	// oembed module
	'oembed' => $oembed,

	// spacer module
	'spacer' => $spacer,

	// video module
	'video' => $video,

	// portfolio grid
	'portfoliogrid' => $portfoliogrid,

	// single project
	'singleproject' => $singleproject,

	// advanced portfolio grid
	'advancedportfoliogrid' => $advancedportfoliogrid,

	// dribbble
	'dribbble' => $dribbble,

	// code module
	'code' => $code,

	// share module
	'share' => $share,

	// social profiles
	'socialprofiles' => $socialprofiles,

	// lottie
	'lottie' => $lottie,

	// blogposts
	'blogposts' => $blogposts,

	// blogarchives
	'blogarchives' => $blogarchives,

	// blogsearch
	'blogsearch' => $blogsearch,

	// blogcomments
	'blogcomments' => $blogcomments,

	// marquee
	'marquee' => $marquee,

	// accordion
	'accordion' => $accordion,

	// instagram
	'instagram' => $instagram,

	// gallery grid
	'gallerygrid' => $gallerygrid,

	// mailchimp
	'mailchimp' => $mailchimp,

	// before after
	'beforeafter' => $beforeafter,
);

?>