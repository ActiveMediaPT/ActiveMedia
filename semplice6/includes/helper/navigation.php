<?php

// -----------------------------------------
// semplice customize nav
// -----------------------------------------

function semplice_get_customize_nav($type, $where) {

	// vars
	$nav = '<nav><ul class="customize-list">';
	$dropdown = '';
	$count = 0;

	// settings
	$settings = array(
		'customize' => array(
			'grid'			=> 'Grid',
			'webfonts'		=> 'Webfonts',
			'typography'	=> 'Typography',
			'navigations'	=> 'Navigations',
			'thumbhover'	=> 'Thumb Hover',
			'transitions'	=> 'Transitions',
			'projectnav'	=> 'Project Nav',
			'advanced'		=> 'Advanced',
			'footer' 		=> 'Footer',
			'blog'			=> 'Blog',
		),
		'settings' => array(
			'general'		=> 'General',
			'license'		=> 'License',
		),
	);

	// customize other
	/*
	$other = array(
		'footer' 	=> 'Footer',
		'blog'		=> 'Blog',
	);
	*/

	// iterate
	foreach ($settings[$type] as $setting => $name) {

		// atts
		$atts = array(
			'type'		=> $type,
			'setting' 	=> $setting,
			'name'		=> $name,
			'where'		=> $where,
			'active'	=> '',
			'classes'	=> '',
		);

		// active
		if($count <= 0) {
			$atts['active'] = ' class="active-setting"';
		}

		// transitions icon
		if($setting == 'transitions') {
			$atts['classes'] = ' transitions-icon';
		}

		// fetch out others
		if($type == 'customize' && $setting == 'other') {

			// get other dropdown
			foreach ($other as $setting => $name) {
				// set atts
				$atts['setting'] = $setting;
				$atts['name'] 	 = $name;
				// add nav item to dropdown
				$dropdown .= semplice_get_customize_nav_item($atts);
			}

			// add customize other to dropdown
			$nav .= '
				<li class="customize-other" data-setting="' . $setting . '"' . $atts['active'] . '>
					<a>
						<span class="icon' . $atts['classes'] . '">' . get_svg('backend', '/icons/customize_other') . '</span>
						<span class="setting-name">Other <span class="co-icon">' . get_svg('backend', '/icons/customize_down_arrow') . '</span></span>
					</a>
					<ul class="customize-dropdown">' . $dropdown . '</ul>
				</li>
			';
		} else {
			$nav .= semplice_get_customize_nav_item($atts);
		}
		
		// inc count
		$count++;
	}

	// return
	return $nav . '</ul></nav>';
}

function semplice_get_customize_nav_item($atts) {

	// link
	if($atts['where'] == 'admin') {
		$link = '<a href="#' . $atts['type'] . '/' . $atts['setting'] . '">';
	} else {
		$link = '<a data-new-url="#' . $atts['type'] . '/' . $atts['setting'] . '" data-exit-mode="close" class="editor-action" data-action-type="popup" data-action="exit">';
	}

	// customize advanced sub
	$advanced_sub = '';
	if($atts['setting'] == 'advanced') {
		$advanced_sub = '
			<ul class="customize-advanced-sub">
			<li class="no-dropdown" data-setting="' . $atts['setting'] . '"' . $atts['active'] . '><a href="#' . $atts['type'] . '/' . $atts['setting'] . '"><span class="setting-name">Global customizations</span></a></li>
				<li data-setting="cursor"' . $atts['active'] . '><a href="#' . $atts['type'] . '/cursor"><span class="setting-name">Custom Cursor</span></a></li>
				<li data-setting="ppc"' . $atts['active'] . '><a href="#' . $atts['type'] . '/ppc"><span class="setting-name">Password Protected Content</span></a></li>
			</ul>
		';	
	}

	return '
		<li data-setting="' . $atts['setting'] . '"' . $atts['active'] . '>
			' . $link . '
				<span class="icon' . $atts['classes'] . '">' . get_svg('backend', '/icons/' . $atts['type'] . '_' . $atts['setting']) . '</span>
				<span class="setting-name">' . $atts['name'] . '</span>
			</a>
			' . $advanced_sub . '
		</li>
	';
}

// -----------------------------------------
// semplice update menu order
// -----------------------------------------

function semplice_update_menu_order($order) {
	
	// json decode order
	$order = json_decode($order, true);
	// get menu id
	$menu_name = 'Semplice Menu';
	$menu_object = wp_get_nav_menu_object($menu_name);
	$menu_id = $menu_object->term_id;

	if(null !== $order && is_array($order) && is_object($menu_object)) {
		foreach ($order as $i => $menu_item) {
			if($menu_item['id'] == 0) {
				// type
				$url = '';
				if($menu_item['type'] == 'custom') {
					$url = $menu_item['link'];
				}
				wp_update_nav_menu_item($menu_id, $menu_item['id'], array(
					'menu-item-title'  	 => __($menu_item['title']),
					'menu-item-position' => $i,
					'menu-item-status' 	 => 'publish',
					'menu-item-type'	 => $menu_item['type'],
					'menu-item-url'		 => $url,
				));
			} else {
				// args
				$args = array(
					'ID' => $menu_item['id'],
					'menu_order' => $i,
					'post_title' => $menu_item['title'],
				);
				wp_update_post($args);

				// update menu classes
				if(!empty($menu_item['classes'])) {
					update_post_meta($menu_item['id'], '_menu_item_classes', preg_split('/\s+/', $menu_item['classes']));
				} else {
					update_post_meta($menu_item['id'], '_menu_item_classes', '');
				}
				// update url
				if(!empty($menu_item['link'])) {
					update_post_meta($menu_item['id'], '_menu_item_url', $menu_item['link']);
				}
				// update target
				if(!empty($menu_item['target']) && $menu_item['target'] == '_blank') {
					update_post_meta($menu_item['id'], '_menu_item_target', $menu_item['target']);
				} else {
					update_post_meta($menu_item['id'], '_menu_item_target', '');
				}
			}
		}
	}
}

// -----------------------------------------
// add new menu item
// -----------------------------------------

function semplice_add_menu_item($item) {
	
	// get menu id
	$menu_name = 'Semplice Menu';
	$menu_object = wp_get_nav_menu_object($menu_name);
	
	// is menu there?
	if(!$menu_object) {
		$menu_id = wp_create_nav_menu($menu_name);
		// get menu localtions
		$locations = get_theme_mod('nav_menu_locations');
		// assign new menu
		$locations['semplice-main-menu'] = $menu_id;
		// set new menu
		set_theme_mod('nav_menu_locations', $locations);
	} else if($item['id'] == 0) {
		// menu id
		$menu_id = $menu_object->term_id;
	}

	// type
	$url = '';
	if($item['type'] == 'custom') {
		$url = $item['link'];
		$post_id = 0;
		$post_type = '';
	} else {
		$post_id = $item['post_id'];
		$post_type = $item['object_type'];
	}
	$new_item_id = wp_update_nav_menu_item($menu_id, $item['id'], array(
		'menu-item-object' => $post_type,
		'menu-item-object-id' => $post_id,
		'menu-item-title'  	 => __($item['title']),
		'menu-item-status' 	 => 'publish',
		'menu-item-type'	 => $item['type'],
		'menu-item-url'		 => $url,
		'menu-item-parent-id' => 0,
	));

	return $new_item_id;
}

// -----------------------------------------
// activate menu
// -----------------------------------------

function semplice_activate_menu() {
	// get menu id
	$menu_name = 'Semplice Menu';
	$menu_object = wp_get_nav_menu_object($menu_name);
	$menu_id = $menu_object->term_id;
	// get menu localtions
	$locations = get_theme_mod('nav_menu_locations');
	// is semplice active?
	if(isset($locations['semplice-main-menu']) && $locations['semplice-main-menu'] != $menu_id) {
		// assign new menu
		$locations['semplice-main-menu'] = $menu_id;
		// set new menu
		set_theme_mod('nav_menu_locations', $locations);
	}
}

// -----------------------------------------
// semplice get navbar
// -----------------------------------------

function semplice_get_navbar($id) {

	// semplice api
	global $admin_api;

	// get navigation json
	$navigations = semplice_customize('navigations');

	// output
	$navigation = array();

	if(false !== $id && isset($navigations[$id])) {
		$navigation = admin_api::$customize['navigations']->get('both', $id, false, true);
	} else if(isset($navigations['default'])) {
		$navigation = admin_api::$customize['navigations']->get('both', 'default', false, true);
	} else {
		$navigation = admin_api::$customize['navigations']->get('both', false, false, true);
	}

	// return
	return $navigation;
}

?>