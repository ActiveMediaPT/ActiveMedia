<?php

// -----------------------------------------
// semplice
// includes/custom_css.php
// -----------------------------------------

class custom_css {

	// -----------------------------------------
	// grid 
	// -----------------------------------------

	public function grid($mode) {
		// get grid
		if($mode == 'editor') {
			return semplice_grid('editor');
		} else {
			return semplice_grid('frontend');
		}
	}

	// -----------------------------------------
	// webfonts
	// -----------------------------------------

	public function webfonts() {
		// get fonts
		return admin_api::$customize['webfonts']->get();
	}

	// -----------------------------------------
	// typography
	// -----------------------------------------

	public function typography($is_admin) {
		// get fonts
		return admin_api::$customize['typography']->get('css', $is_admin, false);
	}
	
	// -----------------------------------------
	// navigation
	// -----------------------------------------

	public function navigation() {
		// get navigation
		global $semplice_content;
		return $semplice_content['navbar']['css'];
	}

	// -----------------------------------------
	// blog
	// -----------------------------------------

	public function blog($is_admin) {
		// get blog css
		return admin_api::$customize['blog']->get_css($is_admin);
	}

	// -----------------------------------------
	// transitions custom css
	// -----------------------------------------

	public function transitions($is_frontend) {
		// get css
		return admin_api::$customize['transitions']->get_css($is_frontend);
	}

	// -----------------------------------------
	// advanced custom css
	// -----------------------------------------

	public function advanced($is_frontend) {
		// get css
		return admin_api::$customize['advanced']->generate_css($is_frontend);
	}

	// -----------------------------------------
	// custom cursor
	// -----------------------------------------

	public function cursor($is_frontend) {
		// get css
		return admin_api::$customize['cursor']->generate_css($is_frontend);
	}

	// -----------------------------------------
	// password protected content
	// -----------------------------------------

	public function ppc($is_frontend) {
		// get css
		return admin_api::$customize['ppc']->generate_css($is_frontend);
	}

	// -----------------------------------------
	// project nav css
	// -----------------------------------------

	public function projectnav($is_frontend) {
		// get css
		return semplice_project_nav_css($is_frontend);
	}

	// -----------------------------------------
	// global thumbhover css
	// -----------------------------------------

	public function thumbhover($is_singleproject) {
		return admin_api::$customize['thumbhover']->global_thumbhover_css($is_singleproject);
	}
}

$semplice_custom_css = new custom_css;

?>