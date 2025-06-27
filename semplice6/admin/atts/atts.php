<?php

// -----------------------------------------
// semplice
// /admin/atts/atts.php
// -----------------------------------------

if(!class_exists('atts')) {
	class atts {

		// vars
		public $fonts;
		public $easings;
		public $css_easings;
		public $blend_modes;
		public $width;

		// constructor
		public function __construct() {
			// Default Fonts
			$this->fonts = semplice_get_default_fonts('display', false);
			// Blend modes
			$this->blend_modes = array(
				'normal' 		=> 'Normal',
				'multiply'		=> 'Multiply',
				'screen'		=> 'Screen',
				'overlay' 		=> 'Overlay',
				'darken' 		=> 'Darken',
				'lighten' 		=> 'Lighten',
				'color-dodge' 	=> 'Color Dodge',
				'color-burn' 	=> 'Color Burn',
				'hard-light' 	=> 'Hard Light',
				'soft-light'	=> 'Soft Light',
				'difference' 	=> 'Difference',
				'exclusion'		=> 'Exclusion',
				'hue' 			=> 'Hue',
				'saturation' 	=> 'Saturation',
				'color'			=> 'Color',
				'luminosity' 	=> 'Luminosity',
			);
			// gsap easings
			$this->easings = array(
				'none'				=> 'None (linear)',
				'Power1.easeIn'		=> 'Power1.easeIn',
				'Power1.easeInOut'	=> 'Power1.easeInOut',
				'Power1.easeOut'	=> 'Power1.easeOut',
				'Power2.easeIn'		=> 'Power2.easeIn',
				'Power2.easeInOut'	=> 'Power2.easeInOut',
				'Power2.easeOut'	=> 'Power2.easeOut',
				'Power3.easeIn'		=> 'Power3.easeIn',
				'Power3.easeInOut'	=> 'Power3.easeInOut',
				'Power3.easeOut'	=> 'Power3.easeOut',
				'Power4.easeIn'		=> 'Power4.easeIn',
				'Power4.easeInOut'	=> 'Power4.easeInOut',
				'Power4.easeOut'	=> 'Power4.easeOut',
				'Bounce.easeIn'		=> 'Bounce.easeIn',
				'Bounce.easeInOut'	=> 'Bounce.easeInOut',
				'Bounce.easeOut'	=> 'Bounce.easeOut',
				'Circ.easeIn'		=> 'Circ.easeIn',
				'Circ.easeInOut'	=> 'Circ.easeInOut',
				'Circ.easeOut'		=> 'Circ.easeOut',
				'Expo.easeIn'		=> 'Expo.easeIn',
				'Expo.easeInOut'	=> 'Expo.easeInOut',
				'Expo.easeOut'		=> 'Expo.easeOut',
				'Sine.easeIn'		=> 'Sine.easeIn',
				'Sine.easeInOut'	=> 'Sine.easeInOut',
				'Sine.easeOut'		=> 'Sine.easeOut',
			);
			// css easings
			$this->css_easings = array(
				'--linear'				=> 'Linear',
				'--ease-in-sine' 		=> 'Sine.EaseIn',
				'--ease-out-sine' 		=> 'Sine.EaseOut',
				'--ease-in-out-sine' 	=> 'Sine.EaseInOut',
				'--ease-in-quad' 		=> 'Quad.In',
				'--ease-out-quad'		=> 'Quad.Out',
				'--ease-in-out-quad'	=> 'Quad.InOut',
				'--ease-in-cubic'		=> 'Cubic.In',
				'--ease-out-cubic'		=> 'Cubic.OUt',
				'--ease-in-out-cubic'	=> 'Cubic.InOut',
				'--ease-in-quart' 		=> 'Quart.In',
				'--ease-out-quart' 		=> 'Quart.Out',
				'--ease-in-out-quart' 	=> 'Quart.InOut',
				'--ease-in-quint' 		=> 'Quint.In',
				'--ease-out-quint' 		=> 'Quint.Out',
				'--ease-in-out-quint' 	=> 'Quint.InOut',
				'--ease-in-expo' 		=> 'Expo.In',
				'--ease-out-expo' 		=> 'Expo.Out',
				'--ease-in-out-expo' 	=> 'Expo.InOut',
				'--ease-in-circ' 		=> 'Circ.In',
				'--ease-out-circ' 		=> 'Circ.Out',
				'--ease-in-out-circ' 	=> 'Circ.InOut',
				'--ease-in-back' 		=> 'Back.In',
				'--ease-out-back' 		=> 'Back.Out',
				'--ease-in-out-back' 	=> 'Back.InOut',
			);
			// width
			$this->width = array(
				'12' => '12 col',
				'11' => '11 col',
				'10' => '10 col',
				'9' => '9 col',
				'8' => '8 col',
				'7' => '7 col',
				'6' => '6 col',
				'5' => '5 col',
				'4' => '4 col',
				'3' => '3 col',
				'2' => '2 col',
				'1' => '1 col',
			);
		}

		// thumb hover
		public function get($type, $args) {
			// get file
			require get_template_directory() . '/admin/atts/partials/' . $type . '.php';
			// return atts
			return $options;
		}

		// mobile options
		public function get_mobile($type, $title, $breakpoint, $args) {
			// get file
			require get_template_directory() . '/admin/atts/partials/mobile/' . $type . '.php';
			// return atts
			return $options;
		}
	}

	// instance
	$atts = new atts;

	// -----------------------------------------
	// styles
	// -----------------------------------------

	require get_template_directory() . '/admin/atts/styles.php';

	// -----------------------------------------
	// options
	// -----------------------------------------

	require get_template_directory() . '/admin/atts/options.php';

	// -----------------------------------------
	// animations
	// -----------------------------------------

	require get_template_directory() . '/admin/atts/animations.php';

	// -----------------------------------------
	// module options
	// -----------------------------------------

	require get_template_directory() . '/admin/atts/modules.php';

	// -----------------------------------------
	// branding
	// -----------------------------------------

	require get_template_directory() . '/admin/atts/branding.php';

	// -----------------------------------------
	// customize options
	// -----------------------------------------

	require get_template_directory() . '/admin/atts/customize.php';

	// -----------------------------------------
	// theme settings
	// -----------------------------------------

	require get_template_directory() . '/admin/atts/settings.php';

	// -----------------------------------------
	// page settings
	// -----------------------------------------

	require get_template_directory() . '/admin/atts/post_settings.php';

	// -----------------------------------------
	// coverslider
	// -----------------------------------------

	require get_template_directory() . '/admin/atts/coverslider.php';
}

?>