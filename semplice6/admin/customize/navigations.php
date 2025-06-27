<?php

// -----------------------------------------
// semplice
// /admin/navigations.php
// -----------------------------------------

if(!class_exists('navigations')) {
	class navigations {

		// constructor
		public function __construct() {}

		// output
		public function output() {
			// output
			$output = 'content';

			return $output;
		}

		// get navigation
		public function get($mode, $nav_id, $is_editor, $is_crawler) {

			// vars
			global $post;
			$output = array(
				'html' 			=> '',
				'css'  			=> '',
				'mobile_css'	=> array(
					'lg' => '',
					'md' => '',
					'sm' => '',
					'xs' => '',
				),
			);
			$hide_menu = false;

			// get breakpoints
			$breakpoints = semplice_get_breakpoints();

			// presets
			$presets = array(
				'logo_left_menu_right' => 'preset_one',
				'logo_left_menu_left' => 'preset_two',
				'logo_right_menu_left' => 'preset_three',
				'logo_right_menu_right' => 'preset_four',
				'logo_middle_menu_sides' => 'preset_five',
				'logo_middle_menu_stacked' => 'preset_six',
				'logo_hidden_menu_middle' => 'preset_seven',
				'logo_left_menu_vertical_right' => 'preset_eight',
				'logo_middle_menu_corners' => 'preset_nine',
				'logo_middle_menu_vertical_left_right' => 'preset_ten',
				'no_logo_menu_distributed' => 'preset_eleven',
				'container_floating' => 'preset_twelve',
				'container_expanded' => 'preset_thirteen',
			);

			// get navigation json
			$navigations = semplice_customize('navigations');

			// get customize advanced
			$advanced = semplice_customize('advanced');

			// blog customize json
			$blog_navbar = semplice_get_blog_navbar();

			// get post settings
			if(!$is_editor) {
				// just getting all the navs for the frontend
				if(!$is_crawler) {
					if(is_object($post) && !is_404()) {
						$post_settings = json_decode(get_post_meta($post->ID, '_semplice_post_settings', true), true);
					} else {
						$post_settings = '';
					}
					// get navigation
					if (is_array($post_settings) && isset($post_settings['meta']['navbar']) && isset($navigations[$post_settings['meta']['navbar']]) && $post_settings['meta']['navbar'] != 'default') {
						$navigation = $navigations[$post_settings['meta']['navbar']];
					} else if(is_object($post) && $post->post_type == 'post' && false !== $blog_navbar && isset($navigations[$blog_navbar]) && !is_404()) {
						$navigation = $navigations[$blog_navbar];	
					} else if(is_array($navigations) && isset($navigations['default'])) {
						$navigation = $navigations[$navigations['default']];
					} else {
						$navigation = 'default';
					}
				} else {
					if($nav_id == 'default' && is_array($navigations) && isset($navigations['default']) && isset($navigations[$navigations['default']])) {
						$navigation = $navigations[$navigations['default']];
					} else {
						$navigation = $navigations[$nav_id];
					}
				}
			} else {
				$navigation = $nav_id;
			}

			// hide menu?
			if(isset($post_settings) && is_array($post_settings)) {
				if(isset($post_settings['meta']['navbar_visibility']) && $post_settings['meta']['navbar_visibility'] == 'false') {
					$hide_menu = true;
				}
			}

			// check if navigation is array, otherwise do nothing because its just the semplice standard nav
			if(true !== $hide_menu) {
				if(is_array($navigation)) {

					// -----------------------------------------	
					// CSS / NAVBAR
					// -----------------------------------------

					// navbar setings
					$navbar_width = 'grid';

					// bg color
					$container_nav_inner = '';
					if(strpos($navigation['preset'], 'container_') !== false) {
						$container_nav_inner = ' .container-nav-inner .navbar-inner';
					}
					$output['css'] .= '.' . $navigation['id'] . $container_nav_inner . ' { ' . $this->get_color($navigation, 'navbar_bg') . '; }';

					// height
					$output = semplice_get_css('.' . $navigation['id'], 'navbar_height', array('height'), $navigation, false, false, $output);
					if(isset($advanced['exclusive_navbar']) && $advanced['exclusive_navbar'] == $navigation['id']) {
						$output = semplice_get_css('.is-frontend .sections', 'navbar_height', array('margin-top'), $navigation, array('exclusive-nav'), false, $output);
					} else {
						$output = semplice_get_css('.is-frontend #content-holder .sections', 'navbar_height', array('margin-top'), $navigation, false, false, $output);
					}
					
					// padding hor
					if(isset($navigation['navbar_type']) && $navigation['navbar_type'] == 'container-fluid' || $navigation['preset'] == 'logo_middle_menu_corners' || $navigation['preset'] == 'logo_middle_menu_sides') {
						// set to 0 if no value
						if(!isset($navigation['navbar_padding'])) {
							$navigation['navbar_padding'] = '0rem';
						}
						// padding left and right
						$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-inner .navbar-left, .' . $navigation['id'] . ' .navbar-inner .navbar-center, .' . $navigation['id'] . ' .navbar-inner .navbar-distributed', 'navbar_padding', array('left'), $navigation, false, false, $output);
						$output = semplice_get_css('.' . $navigation['id'] . ' .container-fluid .navbar-inner .navbar-right, .' . $navigation['id'] . ' .container-fluid .navbar-inner .navbar-distributed', 'navbar_padding', array('right'), $navigation, false, false, $output);
						// padding for the clickable area
						$output = semplice_get_css('.' . $navigation['id'] . ' .container-fluid .hamburger a:after', 'navbar_padding', array('padding-right'), $navigation, array('hamburger-area'), false, $output);
					}

					// padding hor for container floating
					if(strpos($navigation['preset'], 'container_') !== false) {
						if(!isset($navigation['navbar_type']) || $navigation['navbar_type'] == 'container-fluid') {
							$output = semplice_get_css('.' . $navigation['id'] . ' .container-nav-inner', 'navbar_padding', array('padding-left', 'padding-right'), $navigation, false, false, $output);
						}
						$output = semplice_get_css('.' . $navigation['id'] . ' .container-nav-inner', 'navbar_padding_vertical', array('padding-top', 'padding-bottom'), $navigation, false, false, $output);
					}

					// container nav options
					if(strpos($navigation['preset'], 'container_') !== false) {
						// inner paddings
						$output = semplice_get_css('.' . $navigation['id'] . ' .container-nav-inner .navbar-inner', 'navbar_inner_padding_hor', array('padding-left', 'padding-right'), $navigation, false, false, $output);
						$output = semplice_get_css('.' . $navigation['id'] . ' .container-nav-inner .navbar-inner nav ul li a, .' . $navigation['id'] . ' .container-nav-inner .navbar-inner .hamburger', 'navbar_inner_padding_ver', array('padding-top', 'padding-bottom'), $navigation, false, false, $output);
						// border width
						$output = semplice_get_css('.' . $navigation['id'] . ' .container-nav-inner .navbar-inner', 'navbar_border', array('border-width'), $navigation, false, false, $output);
						// border color and opacity
						$output['css'] .= '.' . $navigation['id'] . ' .container-nav-inner .navbar-inner { ' . $this->get_color($navigation, 'navbar_border') . ' }';
						// border radiuss
						$output = semplice_get_css('.' . $navigation['id'] . ' .container-nav-inner .navbar-inner', 'navbar_border_radius', array('border-radius'), $navigation, false, false, $output);
						// drop shadow
						if(isset($navigation['container_box_shadow'])) {
							$output['css'] .= '.' . $navigation['id'] . ' .container-nav-inner .navbar-inner { box-shadow: ' . $navigation['container_box_shadow'] . '; }';
						}
						// background blur
						if(isset($navigation['navbar_blur'])) {
							$output['css'] .= '.' . $navigation['id'] . ' .container-nav-inner .navbar-inner { backdrop-filter: blur(' . $navigation['navbar_blur'] . 'px); -webkit-backdrop-filter: blur(' . $navigation['navbar_blur'] . 'px); }';
						}
					} else {
						// padding ver
						$output = semplice_get_css('.' . $navigation['id'], 'navbar_padding_vertical', array('padding-top', 'padding-bottom'), $navigation, false, false, $output);
					}

					// customizations for the corner menu
					if($navigation['preset'] == 'logo_middle_menu_corners') {
						// header bg color
						$output['css'] .= '.' . $navigation['id'] . ' { background-color: transparent !important; }';
						// padding ver
						if(false === $is_editor) {
							$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-left ul li:nth-child(1), .' . $navigation['id'] . ' .navbar-left ul li:nth-child(2)', 'navbar_padding_vertical', array('top'), $navigation, false, false, $output);
							$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-left ul li:nth-child(3), .' . $navigation['id'] . ' .navbar-left ul li:nth-child(4)', 'navbar_padding_vertical', array('bottom'), $navigation, false, false, $output);
						}
						// padding hor
						$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-inner nav ul li', 'navbar_padding', array('padding-left', 'padding-right'), $navigation, false, false, $output);
					}

					// -----------------------------------------	
					// CSS / LOGO
					// -----------------------------------------

					// textlogo
					if(!isset($navigation['logo_type']) || isset($navigation['logo_type']) && $navigation['logo_type'] == 'text') {
						// text color
						if(isset($navigation['logo_text_color'])) {
							$output['css'] .= '.' . $navigation['id'] . ' .navbar-inner .logo a, .' . $navigation['id'] . ' .logo a { color: ' . $navigation['logo_text_color'] . '; }';
						}
						// font size
						$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-inner .logo a, .' . $navigation['id'] . ' .logo a', 'logo_text_fontsize', array('font-size'), $navigation, false, false, $output);
						// text transform
						if(isset($navigation['logo_text_text_transform'])) {
							$output['css'] .= '.logo a { text-transform: ' . $navigation['logo_text_text_transform'] . '; }';
						}
						// letter spacing
						if(isset($navigation['logo_text_letter_spacing'])) {
							$output['css'] .= '.logo a { letter-spacing: ' . $navigation['logo_text_letter_spacing'] . '; }';
						}
					}

					// logo margin
					$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-inner .logo, .' . $navigation['id'] . ' .logo', 'logo_margin', array('margin-top'), $navigation, false, false, $output);

					// logo padding
					$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-inner .logo', 'logo_padding', array('padding-left', 'padding-right'), $navigation, false, false, $output);

					// logo floating padding
					$output = semplice_get_css('.' . $navigation['id'] . ' .logo', 'logo_floating_padding', array('padding-left', 'padding-right'), $navigation, false, false, $output);
					$output = semplice_get_css('.' . $navigation['id'] . ' .logo', 'logo_floating_padding_ver', array('padding-top', 'padding-bottom'), $navigation, false, false, $output);

					// img logo
					if(isset($navigation['logo_type']) && $navigation['logo_type'] == 'img') {
						$output = semplice_get_css('.' . $navigation['id'] . ' .logo img, .' . $navigation['id'] . ' .logo svg', 'logo_img_width', array('width'), $navigation, false, false, $output);
					}

					// vert alignment
					if(isset($navigation['logo_alignment'])) {
						$output['css'] .= '.' . $navigation['id'] . ' .navbar-inner .logo { align-items: ' . $navigation['logo_alignment'] . '; }';
					}


					// -----------------------------------------	
					// CSS / HAMBURGER
					// -----------------------------------------

					$hamburger_width = 24;
					$hamburger_thickness = 2;
					$hamburger_padding = 6;
					$color = '#000000';
				
					// alignment
					if(isset($navigation['hamburger_alignment'])) {
						$output['css'] .= '.' . $navigation['id'] . ' .navbar-inner .hamburger { align-items: ' . $navigation['hamburger_alignment'] . '; }';
					}

					// color
					if(isset($navigation['hamburger_color'])) {
						$color = $navigation['hamburger_color'];
					}

					// width
					if(!isset($navigation['hamburger_width'])) {
						$navigation['hamburger_width'] = 24;
					}

					// thickness
					if(!isset($navigation['hamburger_thickness'])) {
						$navigation['hamburger_thickness'] = 2;
					}

					// padding
					if(!isset($navigation['hamburger_padding'])) {
						$navigation['hamburger_padding'] = 6;
					}
					
					// calc height
					$navigation = semplice_get_hamburger_height($navigation);

					// hamburger color
					$output['css'] .= '.' . $navigation['id'] . ' .navbar-inner .hamburger a.menu-icon span { background-color: ' . $color . '; }';
					// hamburger width
					$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-inner .hamburger a.menu-icon', 'hamburger_width', array('width'), $navigation, false, false, $output);
					// one line thickness
					$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-inner .hamburger a.menu-icon span', 'hamburger_thickness', array('height'), $navigation, array('add-px'), false, $output);
					// hamburger padding
					$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-inner .hamburger a.open-menu span::before', 'hamburger_padding', array('translateY'), $navigation, array('add-px'), true, $output);
					$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-inner .hamburger a.open-menu span::after', 'hamburger_padding', array('translateY'), $navigation, array('add-px'), false, $output);
					// hover
					$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-inner .hamburger a.open-menu:hover span::before', 'hamburger_padding', array('translateY'), $navigation, array('hamburger-hover', 'add-px'), true, $output);
					$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-inner .hamburger a.open-menu:hover span::after', 'hamburger_padding', array('translateY'), $navigation, array('hamburger-hover', 'add-px'), false, $output);
					// height
					$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-inner .hamburger a.menu-icon', 'hamburger_height', array('height'), $navigation, array('add-px'), false, $output);
					// margin top
					$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-inner .hamburger a.menu-icon span', 'hamburger_height', array('margin-top'), $navigation, array('divide-half', 'add-px'), false, $output);

					// custom hamburger width
					if(isset($navigation['hamburger_custom_width'])) {
						$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-inner .custom-hamburger img', 'hamburger_custom_width', array('width'), $navigation, false, false, $output);
					}

					// is hamburger nav?
					if(isset($navigation['menu_type']) && $navigation['menu_type'] == 'hamburger') {
						$output['css'] .= '.' . $navigation['id'] . ' .navbar-inner .hamburger { display: flex; }';
					}

					// -----------------------------------------	
					// CSS / MENU
					// -----------------------------------------

					if(strpos($navigation['preset'], 'container_') !== false) {
						$li_selector = '.' . $navigation['id'] . ' .container-nav-inner .navbar-inner nav ul li';
					} else {
						$li_selector = '.' . $navigation['id'] . ' .navbar-inner nav ul li';
					}

					$post_type_selector = array(
						'a'    => '[data-post-type="project"] .' . $navigation['id'] . ' .navbar-inner nav ul li.portfolio-grid a, [data-post-type="post"] .' . $navigation['id'] . ' .navbar-inner nav ul li.blog-overview a',
						'span' => '[data-post-type="project"] .' . $navigation['id'] . ' .navbar-inner nav ul li.portfolio-grid a span, [data-post-type="post"] .' . $navigation['id'] . ' .navbar-inner nav ul li.blog-overview a span'
					);

					// fontsize
					$output = semplice_get_css($li_selector . ' a span', 'menu_fontsize', array('font-size'), $navigation, false, false, $output);

					// font color
					if(isset($navigation['menu_color'])) {
						$output['css'] .= $li_selector . ' a span { color: ' . $navigation['menu_color'] . '; }';
					}

					// padding
					if($navigation['preset'] == 'container_floating') {
						$output = semplice_get_css($li_selector . ' a', 'menu_padding', array('padding-left', 'padding-right'), $navigation, array('rem-split'), false, $output);
					} else {
						$output = semplice_get_css($li_selector . ' a', 'menu_padding', array('padding-left', 'padding-right'), $navigation, array('rem-split'), false, $output);
					}

					// text transform
					if(isset($navigation['menu_text_transform'])) {
						$output['css'] .= $li_selector . ' a span { text-transform: ' . $navigation['menu_text_transform'] . '; }';
					}

					// text transform
					if(isset($navigation['menu_letter_spacing'])) {
						$output['css'] .= $li_selector . ' a span { letter-spacing: ' . $navigation['menu_letter_spacing'] . '; }';
					}

					// border width
					if(isset($navigation['menu_border'])) {
						$output['css'] .= $li_selector . ' a span { border-bottom-width: ' . $navigation['menu_border'] . '; }';
					}

					// border color
					if(isset($navigation['menu_border_color'])) {
						$output['css'] .= $li_selector . ' a span { border-bottom-color: ' . $navigation['menu_border_color'] . '; }';
					}

					// border padding
					if(isset($navigation['menu_border_padding'])) {
						$output['css'] .= $li_selector . ' a span { padding-bottom: ' . $navigation['menu_border_padding'] . '; }';
					}

					// menu alignment
					if(isset($navigation['menu_alignment'])) {
						$output['css'] .= '.' . $navigation['id'] . ' .navbar-inner nav.standard ul { align-items: ' . $navigation['menu_alignment'] . '; }';
					}

					// menu mouseover color
					if(isset($navigation['menu_mouseover_color'])) {
						$output['css'] .= $li_selector . ' a:hover span, .' . $navigation['id'] . ' .navbar-inner nav ul li.wrap-focus a span, ' . $li_selector . '.current-menu-item a span, ' . $li_selector . '.current_page_item a span, ' . $post_type_selector['span'] . ' { color: ' . $navigation['menu_mouseover_color'] . '; }';
					}

					// menu mouseover border
					if(isset($navigation['menu_border_mouseover_color'])) {
						$output['css'] .= $li_selector . ' a:hover span, ' . $li_selector . '.current-menu-item a span, ' . $li_selector . '.current_page_item a span, ' . $li_selector . '.wrap-focus a span, ' . $post_type_selector['span'] . ' { border-bottom-color: ' . $navigation['menu_border_mouseover_color'] . '; }';
					}

					// menu active font family
					if(isset($navigation['menu_active_font_family'])) {
						$output['css'] .= $li_selector . '.current-menu-item a span, ' . $li_selector . '.current_page_item a span, ' . $li_selector . '.wrap-focus a span, ' . $post_type_selector['span'] . ' { ' . semplice_get_font_family($navigation['menu_active_font_family']) . ' }';
					}

					// menu active color
					if(isset($navigation['menu_active_color'])) {
						$output['css'] .= $li_selector . '.current-menu-item a span, ' . $li_selector . '.current_page_item a span, ' . $li_selector . '.wrap-focus a span, ' . $post_type_selector['span'] . ' { color: ' . $navigation['menu_active_color'] . ' }';
					}

					// menu active color
					if(isset($navigation['menu_active_border_color'])) {
						$output['css'] .= $li_selector . '.current-menu-item a span, ' . $li_selector . '.current_page_item a span, ' . $li_selector . '.wrap-focus a span, ' . $post_type_selector['span'] . ' { border-bottom-color: ' . $navigation['menu_active_border_color'] . ' }';
					}

					// menu active bg color
					if(isset($navigation['menu_active_bg_color'])) {
						$output['css'] .= $li_selector . '.current-menu-item a, ' . $li_selector . '.current_page_item a, ' . $li_selector . '.wrap-focus a, ' . $post_type_selector['a'] . ' { background-color: ' . $navigation['menu_active_bg_color'] . ' }';
					}

					// menu active border radius
					if(isset($navigation['menu_active_border_radius'])) {
						$output['css'] .= $li_selector . ' a, ' . $li_selector . '.current-menu-item a, ' . $li_selector . '.current_page_item a, ' . $li_selector . '.wrap-focus a, ' . $post_type_selector['a'] . ' { border-radius: ' . $navigation['menu_active_border_radius'] . ' }';
					}

					// -----------------------------------------	
					// CSS / OVERLAY
					// -----------------------------------------

					$li_selector = '#overlay-menu .overlay-menu-inner nav ul li';

					$post_type_selector = '[data-post-type="project"] ' . $li_selector . '.portfolio-grid a span, [data-post-type="post"] ' . $li_selector . '.blog-overview a span';

					// overlay bg color
					$output['css'] .= '#overlay-menu { ' . $this->get_color($navigation, 'overlay_bg') . ' }';

					// overlay padding top
					if(isset($navigation['overlay_padding_top']) && isset($navigation['overlay_alignment_ver'])) {
						if($navigation['overlay_alignment_ver'] == 'align-top') {
							$output['css'] .= '#overlay-menu .overlay-menu-inner nav { padding-top: ' . $navigation['overlay_padding_top'] . '; }';
						}
					}

					// overlay padding
					if(isset($navigation['overlay_type']) && $navigation['overlay_type'] == 'container-fluid') {
						
						// overlay padding left
						if(isset($navigation['overlay_padding_left'])) {
							$overlay_padding_left = '#overlay-menu .overlay-menu-inner [data-justify="left"] ul li a span { left: ' . $navigation['overlay_padding_left'] . '; }';
						}

						// overlay padding right
						if(isset($navigation['overlay_padding_right'])) {
							$overlay_padding_right = '#overlay-menu .overlay-menu-inner [data-justify="right"] ul li a span { right: ' . $navigation['overlay_padding_right'] . '; }';
						}
					}

					// hor alignment
					if(isset($navigation['overlay_alignment_hor'])) {
						$output['css'] .= '#overlay-menu .overlay-menu-inner nav { text-align: ' . $navigation['overlay_alignment_hor'] . '; }';

						// padding left
						if(isset($overlay_padding_left) && $navigation['overlay_alignment_hor'] == 'left') {
							$output['css'] .= $overlay_padding_left;
						}

						// padding right
						if(isset($overlay_padding_right) && $navigation['overlay_alignment_hor'] == 'right') {
							$output['css'] .= $overlay_padding_right;
						}
					}

					// fontsize
					$output = semplice_get_css($li_selector . ' a span', 'overlay_fontsize', array('font-size'), $navigation, false, false, $output);

					// link color
					if(isset($navigation['overlay_color'])) {
						$output['css'] .= $li_selector . ' a span { color: ' . $navigation['overlay_color'] . '; }';
					}

					// items padding
					$output = semplice_get_css($li_selector . ' a', 'overlay_padding', array('padding-top', 'padding-bottom'), $navigation, array('rem-split'), false, $output);

					// text transform
					if(isset($navigation['overlay_text_transform'])) {
						$output['css'] .= $li_selector . ' a span { text-transform: ' . $navigation['overlay_text_transform'] . '; }';
					}

					// letter spacing
					if(isset($navigation['overlay_letter_spacing'])) {
						$output['css'] .= $li_selector . ' a span { letter-spacing: ' . $navigation['overlay_letter_spacing'] . '; }';
					}

					// border oclor
					if(isset($navigation['overlay_border_color'])) {
						$output['css'] .= $li_selector . ' a span { border-bottom-color: ' . $navigation['overlay_border_color'] . '; }';
					}

					// border width
					if(isset($navigation['overlay_border'])) {
						$output['css'] .= $li_selector . ' a span { border-bottom-width: ' . $navigation['overlay_border'] . '; }';
					}

					// border padding
					if(isset($navigation['overlay_border_padding'])) {
						$output['css'] .= $li_selector . ' a span { padding-bottom: ' . $navigation['overlay_border_padding'] . '; }';
					}

					// menu mouseover color
					if(isset($navigation['overlay_mouseover_color'])) {
						$output['css'] .= $li_selector . ' a:hover span, ' . $li_selector . '.current-menu-item a span, ' . $li_selector . '.current_page_item a span, ' . $li_selector . '.wrap-focus a span, ' . $post_type_selector . ' { color: ' . $navigation['overlay_mouseover_color'] . '; }';
					}

					// menu mouseover border
					if(isset($navigation['overlay_border_mouseover_color'])) {
						$output['css'] .= $li_selector . ' a:hover span, ' . $li_selector . '.current-menu-item a span, ' . $li_selector . '.current_page_item a span, ' . $li_selector . '.wrap-focus a span, ' . $post_type_selector . ' { border-bottom-color: ' . $navigation['overlay_border_mouseover_color'] . '; }';
					}

					// menu active font family
					if(isset($navigation['overlay_active_font_family'])) {
						$output['css'] .= $li_selector . '.current-menu-item a span, ' . $li_selector . '.current_page_item a span, ' . $li_selector . '.wrap-focus a span, ' . $post_type_selector . ' { ' . semplice_get_font_family($navigation['overlay_active_font_family']) . ' }';
					}

					// menu mouseover color
					if(isset($navigation['overlay_active_color'])) {
						$output['css'] .= $li_selector . '.current-menu-item a span, ' . $li_selector . '.current_page_item a span, ' . $li_selector . '.wrap-focus a span, ' . $post_type_selector . ' { color: ' . $navigation['overlay_active_color'] . '; }';
					}
					// menu mouseover color
					if(isset($navigation['overlay_border_active_color'])) {
						$output['css'] .= $li_selector . '.current-menu-item a span, ' . $li_selector . '.current_page_item a span, ' . $li_selector . '.wrap-focus a span, ' . $post_type_selector . ' { border-bottom-color: ' . $navigation['overlay_border_active_color'] . '; }';
					}

					// -----------------------------------------	
					// CSS / PRESET TWO
					// -----------------------------------------

					$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-inner .navbar-left .logo', 'logo_padding_right', array('padding-right'), $navigation, false, false, $output);

					// -----------------------------------------	
					// CSS / PRESET FOUR
					// -----------------------------------------

					$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-inner .navbar-right .logo', 'logo_padding_left', array('padding-left'), $navigation, false, false, $output);

					// -----------------------------------------	
					// CSS / PRESET SIX
					// -----------------------------------------

					$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-inner .navbar-center .logo', 'logo_margin_bottom', array('margin-bottom'), $navigation, false, false, $output);

					// -----------------------------------------	
					// HTML
					// -----------------------------------------

					// html default settings
					$nav_settings = $this->default_nav_settings();

					// is exclusive?
					if(isset($advanced['exclusive_navbar']) && $advanced['exclusive_navbar'] == $navigation['id']) {
						$nav_settings['is_exclusive'] = true;
					}

					// navbar type
					if(isset($navigation['navbar_type'])) {
						$nav_settings['navbar_type'] = $navigation['navbar_type'];
					}

					// some individual settings
					switch($navigation['preset']) {
						case 'logo_middle_menu_corners':
						case 'logo_middle_menu_sides':
							$nav_settings['navbar_type'] = 'container-fluid';
						break;
					}

					// sticky? (make sticky if exclusive)
					if(false === $nav_settings['is_exclusive'] && isset($navigation['navbar_mode']) && $navigation['navbar_mode'] == 'normal') {
						$nav_settings['navbar_mode'] = 'non-sticky-nav'; 
					} else if(isset($navigation['navbar_hideonscroll']) && $navigation['navbar_hideonscroll'] == 'enabled') {
						$nav_settings['navbar_hideonscroll'] = 'enabled'; 
					}

					// navbar opacity
					if(isset($navigation['navbar_bg_opacity']) && $navigation['navbar_bg_opacity'] < 1) {
						$nav_settings['navbar_bg_opacity'] = $navigation['navbar_bg_opacity'];
					}

					// add id to navsettings
					if(isset($navigation['id'])) {
						$nav_settings['id'] = $navigation['id'];
					} else {
						$nav_settings['id'] = $navigation['preset'];
					}
					
					// set editor
					if($is_editor) {
						$nav_settings['is_editor'] = true;
					}

					// navbar transparent while in cover
					if(isset($navigation['navbar_cover_transparent'])) {
						$nav_settings['navbar_cover_transparent'] = $navigation['navbar_cover_transparent'];
					}

					// navbar bg visility in overlay
					if(isset($navigation['navbar_bg_visibility_overlay'])) {
						$nav_settings['navbar_bg_visibility_overlay'] = $navigation['navbar_bg_visibility_overlay'];
					}

					// change nav settings defaults for container nav expanded
					if($navigation['preset'] == 'container_expanded') {
						$nav_settings['container_logo_position'] = 'left';
						$nav_settings['container_nav_position'] = 'top';
					}

					// navbar and logo position
					if(isset($navigation['container_logo_position'])) {
						$nav_settings['container_logo_position'] = $navigation['container_logo_position'];
					}
					if(isset($navigation['container_nav_position'])) {
						$nav_settings['container_nav_position'] = $navigation['container_nav_position'];
					}

					// menu type
					if(isset($navigation['menu_type'])) {
						$nav_settings['menu_type'] = $navigation['menu_type'];
					}

					// mobile fallback
					if(isset($navigation['menu_mobile_fallback'])) {
						$nav_settings['menu_mobile_fallback'] = $navigation['menu_mobile_fallback'];
					}

					// navbar type
					if(isset($navigation['overlay_type'])) {
						$nav_settings['overlay_type'] = $navigation['overlay_type'];
					}

					// logo
					if(isset($navigation['logo_type']) && $navigation['logo_type'] == 'img') {
						if(isset($navigation['logo_img'])) {
							// show logo and not found if not in the library anymore
							$logo_img = wp_get_attachment_image_src($navigation['logo_img'], 'full', false);

							if($logo_img) {
								$nav_settings['logo'] = '<img src="' . $logo_img[0] . '" alt="logo">';
							} else {
								$nav_settings['logo'] = 'No image found';
							}
							
						} else if(isset($navigation['logo_svg'])) {
							$nav_settings['logo'] = $navigation['logo_svg'];
						} else {
							$nav_settings['logo'] = 'No image uploaded';
						}
					} else {
						if(isset($navigation['logo_text'])) {
							$nav_settings['logo'] = $navigation['logo_text'];
						}
						if(isset($navigation['logo_text_font_family'])) {
							$nav_settings['logo_font_family'] .= ' data-font="' . $navigation['logo_text_font_family'] . '"';
						}
					}

					// logo visibility
					if(isset($navigation['logo_visibility'])) {
						$nav_settings['logo_visibility'] = $navigation['logo_visibility'];
					}

					// navbar font family
					if(isset($navigation['menu_font_family'])) {
						$nav_settings['menu_font_family'] .= ' data-font="' . $navigation['menu_font_family'] . '"';
					}

					// overlay font family
					if(isset($navigation['overlay_font_family'])) {
						$nav_settings['overlay_font_family'] .= ' data-font="' . $navigation['overlay_font_family'] . '"';
					}

					// overlay vert alignment
					if(isset($navigation['overlay_alignment_ver'])) {
						$nav_settings['overlay_alignment_ver'] = $navigation['overlay_alignment_ver'];
					}

					// overlay hor alignment
					if(isset($navigation['overlay_alignment_hor'])) {
						$nav_settings['overlay_alignment_hor'] = $navigation['overlay_alignment_hor'];
					}

					// custom hamburger
					if(isset($navigation['hamburger_mode']) && $navigation['hamburger_mode'] == 'custom') {
						// set mode
						$nav_settings['hamburger_mode'] = 'custom';
						// hamburger options
						$hamburger_options = array('hamburger_custom', 'hamburger_custom_close');
						// iterate options
						foreach ($hamburger_options as $option) {
							// display option
							$display_option = str_replace('_', '-', $option);
							// not found
							$not_found = '<img class="' . $display_option . '" src="' . get_template_directory_uri() . '/assets/images/admin/hamburger.png" alt="' . $display_option . '">';
							if(isset($navigation[$option])) {
								// show hamburger or not found if not in the library anymore
								$image = wp_get_attachment_image_src($navigation[$option], 'full', false);
								if($image) {
									$nav_settings[$option] = '<img class="' . $display_option . '" src="' . $image[0] . '" alt="' . $display_option . '">';
								} else {
									$nav_settings[$option] = $not_found;
								}
							} else {
								$nav_settings[$option] = $not_found;
							}
						}
						// mouseover
						if(isset($navigation['hamburger_custom_mouseover'])) {
							$nav_settings['hamburger_custom_mouseover'] = $navigation['hamburger_custom_mouseover'];
						}
					}

					// text hamburger
					if(isset($navigation['hamburger_mode']) && $navigation['hamburger_mode'] == 'text') {
						// set mode
						$nav_settings['hamburger_mode'] = 'text';
						// label
						if(isset($navigation['hamburger_text_label']) && strlen($navigation['hamburger_text_label']) > 0) {
							$nav_settings['hamburger_text_label'] = $navigation['hamburger_text_label'];
						}
						// close label
						if(isset($navigation['hamburger_text_close_label']) && strlen($navigation['hamburger_text_close_label']) > 0) {
							$nav_settings['hamburger_text_close_label'] = $navigation['hamburger_text_close_label'];
						}
						// font family
						if(isset($navigation['hamburger_text_font_family'])) {
							$nav_settings['hamburger_text_font_family'] = $navigation['hamburger_text_font_family'];
						}
						// css
						$text_css = array(
							'color' 				 => 'color',
							'fontsize'				 => 'font-size',
							'text_transform'		 => 'text-transform',
							'letter_spacing'		 => 'letter-spacing',
							'border_color'			 => 'border-color',
							'border'				 => 'border-bottom-width',
							'border_padding'		 => 'padding-bottom',
							'mouseover_color'		 => 'color',
							'mouseover_border_color' => 'border-color'
						);
						// iterate
						foreach($text_css as $attribute => $css_attribute) {
							$hover = '';
							if(strpos($attribute, 'mouseover_') !== false) {
								$hover = ':hover';
							}
							$output = semplice_get_css('.' . $navigation['id'] . ' .navbar-inner .hamburger' . $hover . ' .hamburger-text', 'hamburger_text_' . $attribute, array($css_attribute), $navigation, false, false, $output);
						}
					}

					// get nav html template
					$output['html'] .= $this->get_preset($presets[$navigation['preset']], $nav_settings);

					// iterate breakpoints
					foreach ($breakpoints as $breakpoint => $width) {
						if(!empty($output['mobile_css'][$breakpoint])) {
							// desktop
							$output['css'] .= '@media screen' . $width['min'] . $width['max'] . ' { ' . $output['mobile_css'][$breakpoint] . '}';
						}
					}

				} else {
					// html default settings
					$nav_settings = $this->default_nav_settings();
					// get nav html template
					$output['html'] .= $this->get_preset('preset_one', $nav_settings);
				}
			}

			if($mode != 'both') {
				return $output[$mode];
			} else {
				return $output;
			}
		}

		// get background color		
		public function get_color($navigation, $type) {

			// defaults
			$opacity = 1;
			$attribute = 'background-color';
			$color = array('r' => 245, 'g' => 245, 'b' => 245);
			if($type == 'navbar_border') {
				$color = array('r' => 0, 'g' => 0, 'b' => 0);
				$attribute = 'border-color';
			}

			// color
			if(isset($navigation[$type . '_color'])) {
				if($navigation[$type . '_color'] != 'transparent') {
					$color = semplice_hex_to_rgb($navigation[$type . '_color']);
				} else if($navigation[$type . '_color'] == 'transparent') {
					$color = 'transparent';
				}	
			}

			// opacity
			if(isset($navigation[$type . '_opacity'])) {
				$opacity = $navigation[$type . '_opacity'];
			}

			if($color == 'transparent') {
				return $attribute . ': transparent;';
			} else {
				return $attribute . ': rgba(' . $color['r'] . ', ' . $color['g'] . ', ' . $color['b'] . ', ' . $opacity . ');';
			}
		}

		// default nav settings
		public function default_nav_settings() {
			return array(
				'id'							=> 'create-nav',
				'is_editor'						=> false,
				'is_exclusive'					=> false,
				'navbar_type' 					=> 'container',
				'navbar_cover_transparent'		=> 'disabled',
				'navbar_bg_visibility_overlay'  => 'visible',
				'navbar_mode'					=> 'sticky-nav',
				'navbar_hideonscroll'			=> 'disabled',
				'navbar_bg_opacity'				=> 1,
				'container_logo_position'		=> 'top-center',
				'container_nav_position'		=> 'bottom-center',
				'menu_type'						=> 'text',
				'menu_mobile_fallback'			=> 'enabled',
				'overlay_type'					=> 'container',
				'overlay_alignment_ver' 		=> 'align-middle',
				'overlay_alignment_hor' 		=> 'center',
				'overlay_font_family'			=> '',
				'logo'		  					=> get_bloginfo('name'),
				'logo_font_family'				=> '',
				'logo_visibility'				=> 'visible',
				'menu_font_family'				=> '',
				'hamburger_mode'				=> 'default',
				'hamburger_custom'				=> '',
				'hamburger_custom_close'		=> '',
				'hamburger_custom_mouseover'	=> 'scale',
				'hamburger_text_label'			=> 'Menu',
				'hamburger_text_close_label'	=> 'Close',
				'hamburger_text_font_family'	=> 'regular'
			);
		}

		// get menu html
		public function get_nav_menu($mode, $menu_font, $is_editor, $alignment, $menu_class, $filter) {

			// special classes
			$classes = '';

			// first check if our menu location has a menu assigned
			if(has_nav_menu('semplice-main-menu')) {
				// ´get menu
				$menu = wp_nav_menu(
					array(
						'items_wrap' => '%3$s',
						'theme_location' => 'semplice-main-menu',
						'echo' 			 => false,
						'container' 	 => false,
						'fallback_cb'	 => false,
						'menu_class' 	 => $menu_class,
						'link_before'	 => '<span>',
						'link_after'	 => '</span>'
					)
				);
				// are there any items in our nav?
				if(empty($menu)) {
					$menu = wp_page_menu(
						array(
							'echo' => false,
							'menu_class'  => '',
							'container'   => '',
							'before'	  => '',
							'after'		  => '',
							'link_before' => '<span>',
							'link_after'  => '</span>'
						)
					);
				}
			} else {
				$menu = wp_page_menu(
					array(
						'echo' => false,
						'menu_class'  => '',
						'container'   => '',
						'before'	  => '',
						'after'		  => '',
						'link_before' => '<span>',
						'link_after'  => '</span>'
					)
				);
			}

			// remove div container
			$menu = str_replace(array('<div>', '</div>'), array('', ''), $menu);

			// filter
			if(false !== $filter) {
				switch ($filter) {
					case 'limit-four':
						// make array
						$menu_items = explode('</li>', $menu);
						// slice array
						$menu_items = array_slice($menu_items, 0, 4);
						// implode again
						$menu = implode('</li>', $menu_items);
					break;
					case 'left-side':
						// get half menu for left
						$menu = $this->get_half_menu($menu, 'left');
						// add left menu class
						$classes .= ' menu-left';
					break;
					case 'right-side':
					// get half menu for right
						$menu = $this->get_half_menu($menu, 'right');
						// add right menu class
						$classes .= ' menu-right';
					break;
				}
			}

			// wrap
			$menu = '<ul class="' . $menu_class . '">' . $menu . '</ul>';

			// nav
			$nav = '<nav class="standard' . $alignment . $classes . '"' . $menu_font . '>' . $menu . '</nav>';

			// hamburger
			if($mode != 'hamburger' || $is_editor) {
				if($mode == 'menu-only') {
					return $menu;
				} else {
					return $nav;
				}
			}
		}

		// get hamburger
		public function get_hamburger($alignment, $nav_settings) {
			// return tasty hamburger
			if($nav_settings['hamburger_mode'] == 'default') {
				return '<div class="hamburger' . $alignment . ' semplice-menu"><a class="open-menu menu-icon"><span></span></a></div>';
			} else if($nav_settings['hamburger_mode'] == 'custom') {
				return '<div class="hamburger' . $alignment . ' semplice-menu custom-hamburger" data-hamburger-mouseover="' . $nav_settings['hamburger_custom_mouseover'] . '"><a class="open-menu menu-icon">' . $nav_settings['hamburger_custom'] . $nav_settings['hamburger_custom_close'] . '</a></div>';
			} else if($nav_settings['hamburger_mode'] == 'text') {
				return '<div class="hamburger' . $alignment . ' semplice-menu"><span class="hamburger-text" data-font="' . $nav_settings['hamburger_text_font_family'] . '" data-label-open="' . $nav_settings['hamburger_text_label'] . '" data-label-close="' . $nav_settings['hamburger_text_close_label'] . '">' . $nav_settings['hamburger_text_label'] . '</div>';
			}
		}

		// get nav overlay
		public function get_nav_overlay($nav_settings) {
			return '
				<div id="overlay-menu">
					<div class="overlay-menu-inner" data-xl-width="12">
						<nav class="overlay-nav" data-justify="' . $nav_settings['overlay_alignment_hor'] . '" data-align="' . $nav_settings['overlay_alignment_ver'] . '"' . $nav_settings['overlay_font_family'] . '>
							' . $this->get_nav_menu('menu-only', false, false, false, $nav_settings['overlay_type'], false) . '
						</nav>
					</div>
				</div>
			';
		}

		// get logo
		public function get_logo($alignment, $nav_settings) {
			// return logo
			return '<div class="logo' . $alignment . '"><a ' . $nav_settings['logo_font_family'] . ' href="' . home_url() . '" title="' . get_bloginfo('blog-title') . '">' .  $nav_settings['logo'] . '</a></div>';
		}

		// header start
		public function get_header_start($nav_settings, $classes, $preset) {
			// vars
			$classes = ' ' . $classes;
			$style = '';
			$floating = '';
			$mobile_fallback = '';
			// transparent class
			if($nav_settings['navbar_cover_transparent'] == 'enabled') {
				$classes .= ' cover-transparent';
			}
			// scroll to
			if($nav_settings['navbar_bg_opacity'] < 1) {
				$classes .= ' scroll-to-top';
			}
			// only show mobile fallback option if text nav is enabled
			if($nav_settings['menu_type'] == 'text') {
				$mobile_fallback = ' data-mobile-fallback="' . $nav_settings['menu_mobile_fallback'] . '"';
			}
			// headroom
			if($nav_settings['navbar_hideonscroll'] == 'enabled' && $nav_settings['container_nav_position'] != 'bottom') {
				$classes .= ' use-headroom';
				// static transitions
				$frontend_mode = semplice_get_mode('frontend_mode');
				if(semplice_static_transitions($frontend_mode) != 'disabled') {
					$style .= 'style="transform: translateY(-100%);transition: none;"';
				}
			} else {
				$classes .= ' no-headroom';
			}
			// floating nav
			if($preset == 'preset_twelve' || $preset == 'preset_thirteen') {
				$floating = ' data-logo-visibility="' . $nav_settings['logo_visibility'] . '" data-logo-position="' . $nav_settings['container_logo_position'] . '" data-nav-position="' . $nav_settings['container_nav_position'] . '" ';
				if(strpos($nav_settings['container_nav_position'], 'bottom') !== false) {
					$classes .= ' container-bottom-nav';
				}
			} else {
				$classes .= ' non-container-nav';
			}
			// is exclusive?
			if(false === $nav_settings['is_exclusive']) {
				$classes .= ' non-exclusive';
			}
			// return header start
			return '<header class="' . $nav_settings['id'] . ' semplice-navbar active-navbar ' . $nav_settings['navbar_mode'] . $classes . '" ' . $style . ' data-cover-transparent="' . $nav_settings['navbar_cover_transparent'] . '" data-bg-overlay-visibility="' . $nav_settings['navbar_bg_visibility_overlay'] . '"' . $mobile_fallback . $floating . '>';
		}

		public function get_navbar_inner($nav_settings) {
			// navbar inner
			return '<div class="navbar-inner menu-type-' . $nav_settings['menu_type'] . '" data-xl-width="12" data-navbar-type="' . $nav_settings['navbar_type'] . '">';
		}

		// get half menu for both sides design
		public function get_half_menu($menu, $dir) {
			// make array
			$menu_items = explode('</li>', $menu);
			// count
			$count = count($menu_items);
			// output
			$output = array(
				'left' => array_slice($menu_items, 0, round($count / 2, 0, PHP_ROUND_HALF_DOWN)),
				'right' => array_slice($menu_items, round($count / 2, 0, PHP_ROUND_HALF_DOWN)),
			);
			// return 
			return implode('</li>', $output[$dir]);
		}

		// presets
		public function get_preset($preset, $nav_settings) {

			// output
			$html = '';

			switch($preset) {
				case 'preset_one':
					$html .= '
						' . $this->get_header_start($nav_settings, '', $preset) . '
							<div class="' . $nav_settings['navbar_type'] . '" data-nav="logo-left-menu-right">
								' . $this->get_navbar_inner($nav_settings) . '
									' . $this->get_logo(' navbar-left', $nav_settings) . '
									' . $this->get_nav_menu($nav_settings['menu_type'], $nav_settings['menu_font_family'], $nav_settings['is_editor'], ' navbar-right no-container', 'menu', false) . '
									' . $this->get_hamburger(' navbar-right', $nav_settings) . '
								</div>
							</div>
						</header>
						' . $this->get_nav_overlay($nav_settings) . '
					';
				break;
				case 'preset_two':
					$html .= '
						' . $this->get_header_start($nav_settings, '', $preset) . '
							<div class="' . $nav_settings['navbar_type'] . '" data-nav="logo-left-menu-left">
								' . $this->get_navbar_inner($nav_settings) . '
									<div class="navbar-left">
										' . $this->get_logo('', $nav_settings) . '
										' . $this->get_nav_menu($nav_settings['menu_type'], $nav_settings['menu_font_family'], $nav_settings['is_editor'], ' no-container', 'menu', false) . '
									</div>
									' . $this->get_hamburger(' navbar-right', $nav_settings) . '
								</div>
							</div>
						</header>
						' . $this->get_nav_overlay($nav_settings) . '
					';
				break;
				case 'preset_three':
					$html .= '
						' . $this->get_header_start($nav_settings, '', $preset) . '
							<div class="' . $nav_settings['navbar_type'] . '" data-nav="logo-right-menu-left">
								' . $this->get_navbar_inner($nav_settings) . '
									' . $this->get_nav_menu($nav_settings['menu_type'], $nav_settings['menu_font_family'], $nav_settings['is_editor'], ' navbar-left no-container', 'menu', false) . '
									' . $this->get_hamburger(' navbar-left', $nav_settings) . '
									' . $this->get_logo(' navbar-right', $nav_settings) . '
								</div>
							</div>
						</header>
						' . $this->get_nav_overlay($nav_settings) . '
					';
				break;
				case 'preset_four':
					$html .= '
						' . $this->get_header_start($nav_settings, '', $preset) . '
							<div class="' . $nav_settings['navbar_type'] . '" data-nav="logo-right-menu-right">
								' . $this->get_navbar_inner($nav_settings) . '
									' . $this->get_hamburger(' navbar-left', $nav_settings) . '
									<div class="navbar-right">
										' . $this->get_nav_menu($nav_settings['menu_type'], $nav_settings['menu_font_family'], $nav_settings['is_editor'], ' no-container', 'menu', false) . '
										' . $this->get_logo('', $nav_settings) . '
									</div>
								</div>
							</div>
						</header>
						' . $this->get_nav_overlay($nav_settings) . '
					';
				break;
				case 'preset_five':
					$html .= '
						' . $this->get_header_start($nav_settings, '', $preset) . '
							<div class="container-fluid" data-nav="logo-middle-menu-sides">
								' . $this->get_navbar_inner($nav_settings) . '
									' . $this->get_hamburger(' navbar-right', $nav_settings) . '
									<div class="navbar-center">
										' . $this->get_nav_menu($nav_settings['menu_type'], $nav_settings['menu_font_family'], $nav_settings['is_editor'], ' no-container', 'menu', 'left-side') . '
										' . $this->get_logo('', $nav_settings) . '
										' . $this->get_nav_menu($nav_settings['menu_type'], $nav_settings['menu_font_family'], $nav_settings['is_editor'], ' no-container', 'menu', 'right-side') . '
									</div>
								</div>
							</div>
						</header>
						' . $this->get_nav_overlay($nav_settings) . '
					';
				break;
				case 'preset_six':
					$html .= '
						' . $this->get_header_start($nav_settings, '', $preset) . '
							<div class="' . $nav_settings['navbar_type'] . '" data-nav="logo-middle-menu-stacked">
								' . $this->get_navbar_inner($nav_settings) . '
									' . $this->get_hamburger(' navbar-right', $nav_settings) . '
									<div class="navbar-center">
										' . $this->get_logo('', $nav_settings) . '
										' . $this->get_nav_menu($nav_settings['menu_type'], $nav_settings['menu_font_family'], $nav_settings['is_editor'], ' no-container', 'menu', false) . '
									</div>
								</div>
							</div>
						</header>
						' . $this->get_nav_overlay($nav_settings) . '
					';
				break;
				case 'preset_nine':
					$html .= '
						' . $this->get_header_start($nav_settings, 'no-menu-transition corner-navbar', $preset) . '
							<div class="container-fluid" data-nav="logo-middle-menu-corners">
								' . $this->get_navbar_inner($nav_settings) . '
									' . $this->get_hamburger(' navbar-right', $nav_settings) . '
									<div class="navbar-left">
										' . $this->get_logo('', $nav_settings) . '
										' . $this->get_nav_menu($nav_settings['menu_type'], $nav_settings['menu_font_family'], $nav_settings['is_editor'], ' no-container', 'menu', 'limit-four') . '
									</div>
								</div>
							</div>
						</header>
						' . $this->get_nav_overlay($nav_settings) . '
					';
				break;
				case 'preset_eleven':
					$html .= '
						' . $this->get_header_start($nav_settings, '', $preset) . '
							<div class="' . $nav_settings['navbar_type'] . '" data-nav="no-logo-menu-distributed">
								' . $this->get_navbar_inner($nav_settings) . '
									' . $this->get_nav_menu($nav_settings['menu_type'], $nav_settings['menu_font_family'], $nav_settings['is_editor'], ' navbar-distributed no-container', 'menu', false) . '
									' . $this->get_hamburger(' navbar-right', $nav_settings) . '
								</div>
							</div>
						</header>
						' . $this->get_nav_overlay($nav_settings) . '
					';
				break;
				case 'preset_twelve':
					$html .= '
						' . $this->get_header_start($nav_settings, 'container-nav container-nav-floating', $preset) . '
							<div class="container-nav-inner ' . $nav_settings['navbar_type'] . '" data-nav="container-floating">
								' . $this->get_navbar_inner($nav_settings) . '
									' . $this->get_nav_menu($nav_settings['menu_type'], $nav_settings['menu_font_family'], $nav_settings['is_editor'], '', 'menu', false) . '
									' . $this->get_hamburger('', $nav_settings) . '
								</div>
							</div>
							' . $this->get_logo('', $nav_settings) . '
						</header>
						' . $this->get_nav_overlay($nav_settings) . '
					';
				break;
				case 'preset_thirteen':
					$html .= '
						' . $this->get_header_start($nav_settings, 'container-nav container-nav-expanded ', $preset) . '
							<div class="container-nav-inner ' . $nav_settings['navbar_type'] . '" data-nav="container-expanded">
								' . $this->get_navbar_inner($nav_settings) . '
									' . $this->get_logo('', $nav_settings) . '
									' . $this->get_nav_menu($nav_settings['menu_type'], $nav_settings['menu_font_family'], $nav_settings['is_editor'], '', 'menu', false) . '
									' . $this->get_hamburger('', $nav_settings) . '
								</div>
							</div>
						</header>
						' . $this->get_nav_overlay($nav_settings) . '
					';
				break;
			}

			// return html
			return $html;
		}
	}

	// instance
	admin_api::$customize['navigations'] = new navigations;
}

?>