<?php

// -----------------------------------------
// semplice
// admin/editor/modules/button/module.php
// -----------------------------------------

if(!class_exists('sm_button')) {

	class sm_button {

		public $output;
		public $is_editor;

		// constructor
		public function __construct() {
			// define output
			$this->output = array(
				'html' => '',
				'css'  => '',
			);
			// is frontend
			$this->is_editor = true;
		}

		// output editor
		public function output_editor($values, $id) {
			// editor output
			return $this->general_output($values, $id, 'editor');
		}

		// output frontend
		public function output_frontend($values, $id) {
			// set is frontend
			$this->is_editor = false;
			// frontend output
			return $this->general_output($values, $id, 'frontend');
		}

		public function general_output($values, $id, $mode) {

			// get styles
			$content_styles = $values['styles']['xl'];

			// output css
			$output_css = '';

			// extract options
			extract( shortcode_atts(
				array(
					'label'						=> 'Semplice Button',
					'font_family'				=> 'inter_medium',
					'icon'						=> false,
					'icon_height'				=> '1.111111111111111rem',
					'icon_spacing'				=> '0.5555555555555556',
					'icon_direction'			=> 'right',
					'align' 					=> 'center',
					'width'						=> 'auto',
					'link'						=> 'https://www.semplice.com',
					'link_target'				=> '_blank',
					'link_type'					=> 'url',
					'link_page'					=> '',
					'link_project'				=> '',
					'link_post'					=> '',
					'effect'					=> 'colorfade',
					'text_effect'				=> 'none',
					'text_align'				=> 'center',
					'box_shadow_opacity'		=> 100
				), $values['options'] )
			);

			// add default label if empty
			if(empty($label)) {
				$label = 'Semplice Button';
			}

			// font family
			if(!empty($font_family)) {
				$font_family = ' data-font="' . $font_family . '"';
			}

			// css atts
			$css_atts = array(
				'parent' => array('background-color', 'hover-background-color', 'border-width', 'border-radius', 'border-color', 'hover-border-color'),
				'child'  => array('font-size', 'color', 'hover-color', 'letter-spacing', 'hover-letter-spacing', 'border-radius', 'padding-bottom', 'padding-left', 'padding-right', 'padding-top'),
			);

			// selector
			$selector = '[data-breakpoint="##breakpoint##"] #content-holder #' . $id;

			// effect defaults
			$effect_defaults = array(
				'effect' 				=> 'colorfade',
				'easing' 				=> '--ease-out-expo',
				'duration' 				=> 700,
				'background-color'  	=> '#ffd300',
				'hover-background-color'=> '#ffe152'
			);
			foreach($effect_defaults as $attr => $default) {
				if(isset($values['options'][$attr])) {
					$effect_defaults[$attr] = $values['options'][$attr];
				}
			}

			// change css atts indivdiually for a preset effect
			switch($effect) {
				case 'slide-left-to-right':
				case 'slide-right-to-left':
				case 'slide-top-to-bottom':
				case 'slide-bottom-to-top':
				case 'expand-vertically':
				case 'expand-horizontally':
				case 'fill-up':
					unset($css_atts['parent'][1]);
					$output_css .= '#content-holder #' . $id . ' .is-content:after { background-color: ' . $effect_defaults['hover-background-color'] . '; }';
				break;
			}

			// easing for the frontend
			$sel = '#content-holder #' . $id;
			if(false === $this->is_editor) {
				$output_css .= $sel . ' .is-content, ' . $sel . ' .is-content:after, ' . $sel . ' .is-content a, ' . $sel . ' .is-content a:before, ' . $sel . ' .is-content a:after, ' . $sel . ' .is-content img { transition: all ' . (intval($effect_defaults['duration']) / 1000) . 's var(' . $effect_defaults['easing'] . '); }';
			} else {
				$output_css .= $sel . ' .button-mouseover, ' . $sel . ' .button-mouseover:after, ' . $sel . ' .button-mouseover a, ' . $sel . ' .button-mouseover a:before, ' . $sel . ' .button-mouseover a:after, ' . $sel . ' .button-mouseover img { transition: all ' . (intval($effect_defaults['duration']) / 1000) . 's var(' . $effect_defaults['easing'] . '); }';
			}
			// hover defaults
			$hover_defaults = $this->get_hover_defaults($values['options']);
			// iterate breakpoints
			$breakpoints = semplice_get_breakpoints(true);
			foreach ($breakpoints as $breakpoint => $bp_width) {
				// css
				$bp_css = '';
				// bp
				$bp = ($breakpoint == 'xl' ? false : '_' . $breakpoint);
				// define inline css output
				$css = array(
					'normal' => array('parent' => '','child' => ''),
					'hover' => array('parent' => '','child' => ''),
				);
				// options short
				$options = $values['options'];
				// text effect
				if($text_effect != 'none') {
					$te_label = $label;
					if(isset($options['label' . $bp]) && !empty($options['label' . $bp])) {
						$te_label = $options['label' . $bp];
					}
					$bp_css .= $selector . ' a:before, ' . $selector . ' a:after { content: "' . $te_label . '"; }';
				}
				// iterate css atts
				foreach($css_atts as $type => $attributes) {
					foreach($attributes as $attribute) {
						$val = false;
						if(isset($options[$attribute . $bp])) {
							$val = $options[$attribute . $bp];
						}
						$normal_hover = 'normal';
						$important = '';
						if (strpos($attribute, 'hover-') !== false) {
							$normal_hover = 'hover';
							$important = ' !important';
							if(false === $val && isset($hover_defaults[$attribute])) {
								$val = $hover_defaults[$attribute];
							}
						}
						// has value?
						if(false !== $val) {
							$css[$normal_hover][$type] .= str_replace('hover-', '', $attribute) . ': ' . $val . $important . ';';
							if(strpos($attribute, 'letter-spacing') !== false) {
								if(strpos($val, '-') === false) {
									$css[$normal_hover][$type] .= 'margin-right: -' . $val . ';';
								} else {
									$css[$normal_hover][$type] .= 'margin-right: ' . str_replace('-', '', $val) . ';';
								}
							}
						}
					}
				}
				// iterate css and add to output
				foreach($css as $type => $element) {
					foreach($element as $element_type => $element_css) {
						$state = '';
						if($type == 'hover') {
							$state = ':hover';
						}
						if($element_type == 'parent' && !empty($css[$type]['parent'])) {
							$bp_css .= $selector . ' .is-content' . $state . ' {' . $css[$type]['parent'] . '}';
						} else if($element_type == 'child' && !empty($css[$type]['child'])) {
							$bp_css .= $selector . ' .is-content' . $state . ' a, ' . $selector . ' .is-content' . $state . ' a:before, ' . $selector . ' .is-content' . $state . ' a:after {' . $css[$type]['child'] . '}';
						}
					}
				}
				// icon css
				$icon_css = '';
				if(false !== $icon) {
					$icon_spacing_prefix = 'margin-left';
					if($icon_direction != 'right') {
						$icon_spacing_prefix = 'margin-right';
					}
					if(isset($options['icon_height' . $bp])) {
						$icon_css .= 'height: ' . $options['icon_height' . $bp] . ';';
					}
					if(isset($options['icon_spacing' . $bp])) {
						$icon_css .= $icon_spacing_prefix . ': ' . $options['icon_spacing' . $bp] . ';';
					}
					if(!empty($icon_css)) {
						$bp_css .= '.is-frontend #content-holder #' . $id . ' .is-content a img { ' . $icon_css . '}';
					}
				}
				// add to css output
				if($breakpoint != 'xl') {
					if(true === $this->is_editor) {
						$output_css .= str_replace('##breakpoint##', $breakpoint, $bp_css);
					} else {
						$output_css .= '@media screen' . $bp_width['min'] . $bp_width['max'] . ' { ' . str_replace('[data-breakpoint="##breakpoint##"] ', '', $bp_css) . '}';
					}
				} else {
					$output_css .= str_replace('[data-breakpoint="##breakpoint##"] ', '', $bp_css);
				}
			}

			// drop shadow?
			if(isset($content_styles['box-shadow']) && !empty($content_styles['box-shadow'])) {
				// get existing box shadow without rgba
				$box_shadow = explode(",", $content_styles['box-shadow']);
				// change opacity
				$box_shadow = $box_shadow[0] . ',' . $box_shadow[1] . ',' . $box_shadow[2] . ',' . $box_shadow_opacity / 100 . ')';
				// get opacity
				$output_css .= '.is-frontend #content-holder #' . $id . ' .is-content:hover { box-shadow: ' . $box_shadow . '; }';
			}

			// $link array
			$link = array(
				'type'		=> $link_type,
				'url'		=> $link,
				'page'		=> $link_page,
				'project'	=> $link_project,
				'post'		=> $link_post, 
			);

			// only display link on e ditor
			if($mode == 'frontend') {
				// set prefix to false
				$link_prefix = false;
				// check link type
				if($link['type'] == 'url' || $link['type'] == 'email') {
					// check if its an email
					if(filter_var($link['url'], FILTER_VALIDATE_EMAIL)) {
						$link_prefix = 'mailto:';
					}
					// assign url
					$link = $link['url'];
				} else {
					if(!empty($link[$link['type']])) {
						$link = get_permalink($link[$link_type]);
					} else {
						$link = get_home_url();
					}
				}
				// link target
				if($link_target == 'same') {
					$link_target = '_self';
				} else {
					$link_target = '_blank';
				}
				// link
				$link = 'href="' . $link_prefix . $link . '" target="' . $link_target . '"';
			} else {
				$link = '';
			}

			// get icon src
			$icon_src = false;
			if($text_effect == 'none' && false !== $icon) {
				$icon_src = wp_get_attachment_image_src($icon, 'full', false);
				if(false !== $icon_src) {
					$icon_src = '<img src="' . $icon_src[0] . '" alt="button-icon">';	
				}
			}

			// label
			if(false === $this->is_editor) {
				$label = $this->label($label, $values['options'], $icon_src, $icon_direction);
			} else {
				$label = array('content' => $label . $icon_src, 'classes' => '');
			}

			// output
			$this->output['html'] = '
				<div class="ce-button" data-align="' . $align . '">
					<div class="is-content ' . $label['classes'] . '" data-width="' . $width . '" data-effect="' . $effect . '" data-icon-direction="' . $icon_direction . '">
						<a ' . $font_family . ' ' . $link . ' data-text-effect="' . $text_effect . '" data-text-align="' . $text_align . '">' . $label['content'] . '</a>
					</div>
				</div>
			';
			// output css 
			$this->output['css'] = $output_css;

			// output
			return $this->output;
		}

		// get hover defaults
		public function get_hover_defaults($styles) {
			$defaults = array(
				'hover-color'				=> '#000000',
				'hover-background-color'	=> '#ffe152',
				'hover-letter-spacing'		=> false,
			);
			// check if values set and overwrite
			foreach($defaults as $attribute => $value) {
				if(isset($styles[$attribute])) {
					$defaults[$attribute] = $styles[$attribute];
				}
			}
			// ret
			return $defaults;
		}

		// get label
		public function label($default, $options, $icon, $icon_dir) {
			// label
			$label = array('content' => '', 'classes' => '');

			// define breakpoints
			$breakpoints = array('lg', 'md', 'sm', 'xs');

			// add xl first
			if(false !== $icon) {
				if($icon_dir == 'right') {
					$default = $default . $icon;
				} else {
					$default = $icon . $default;
				}
			}
			$label['content'] = '<div data-content-for="xl"><div class="inner">' . $default . '</div></div>';

			// has other content?
			foreach ($breakpoints as $breakpoint) {
				if(isset($options['label_' . $breakpoint]) && !empty($options['label_' . $breakpoint])) {
					if($breakpoint != 'xl') {
						$label['classes'] .= ' has-' . $breakpoint;
					}
					// define content
					$content = $options['label_' . $breakpoint];
					// add xl first
					if(false !== $icon) {
						if($icon_dir == 'right') {
							$content = $content  . $icon;
						} else {
							$content = $icon . $content;
						}
					}
					$label['content'] .= '<div data-content-for="' . $breakpoint . '"><div class="inner">' . $content . '</div></div>';
				}
			}
			// return
			return $label;
		}
	}

	// instance
	editor_api::$modules['button'] = new sm_button;
}