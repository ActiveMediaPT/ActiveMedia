<?php

// -----------------------------------------
// semplice
// admin/editor/modules/marquee.php
// -----------------------------------------

if(!class_exists('sm_marquee')) {
	class sm_marquee {

		public $output;
		public $is_editor;

		// constructor
		public function __construct() {
			// define output
			$this->output = array(
				'html' => '',
				'css'  => '',
			);
			// set is editor
			$this->is_editor = true;
		}

		// output editor
		public function output_editor($values, $id) {

			$output = array(
				'html' => '',
				'css'  => '',
			);

			// extract options
			extract( shortcode_atts(
				array(
					'direction'		=> 'ltr',
					'mode'			=> 'text',
					'speed'			=> 200,
					'clones'		=> 3,
					'text'			=> 'SEMPLICE MARQUEE &mdash; CLICK TO EDIT ME',
					'color'			=> '#000000',
					'font'			=> 'inter_semibold',
					'fontsize'		=> '3rem',
					'lineheight'	=> '4rem',
					'margin_right'	=> '1rem',
					'text_transform'=> 'none',
					'letter_spacing'=> '0rem',
					'width'			=> 'original',
					'custom_width'	=> 100,
				),$values['options'])
			);

			// speed
			$speed = $this->speed($speed, $values['options']);

			// start output html
			$output['html'] = '<div class="is-content semplice-marquee"><div class="semplice-marquee-inner" data-direction="' . $direction . '" data-options=\'{"direction": "' . $direction . '", "speed": ' . json_encode($speed) . ', "mode": "' . $mode . '" }\' data-font="' . $font . '">';

			// add content by mode
			$content = $text;

			// spacing prefix
			$spacingPrefix = 'margin-right';
			if($direction == 'rtl') {
				$spacingPrefix = 'margin-left';
			}

			if($mode == 'image') {
				// get images
				$images = $values['content']['xl'];
				
				if(is_array($images)) {

					$content = '';

					foreach($images as $image) {
						
						// get img
						$img = wp_get_attachment_image_src($image, 'full');

						// is image still in library?
						if(false !== $img) {
							// image alt
							$image_alt = semplice_get_image_alt($image);

							// caption
							$image_caption = wp_get_attachment_caption($image);
							
							// add image to content output
							$content .= '<img src="' . $img[0] . '" alt="' . $image_alt . '" caption="' . $image_caption . '"/>';
						}
					}
				}
				// output css
				if($width == 'custom') {
					$custom_width = 'width: ' . $custom_width . '%;';
				} else {
					$custom_width = '';
				}
				$output['css'] .= '
					#content-holder #' . $id . ' .semplice-marquee-image img { ' . $spacingPrefix . ': ' . $margin_right . ';' . $custom_width . '}
				';
			} else {
				// output css
				$output['css'] .= '
					#content-holder #' . $id . ' .semplice-marquee-text {
						color: ' . $color . ';
						font-size: ' . $fontsize . ';
						line-height: ' . $lineheight . ';
						' . $spacingPrefix . ': ' . $margin_right . ';
						text-transform: ' . $text_transform . ';
						letter-spacing: ' . $letter_spacing . ';
					}
				';
			}

			// get breakpoint css
			$output['css'] .= $this->breakpoint_css($values['options'], '[data-breakpoint="##breakpoint##"] #content-holder #' . $id, $mode, $margin_right, $spacingPrefix);

			// has content?
			if(strlen($content) === 0) {
				$mode = 'text';
				$content = 'SEMPLICE MARQUEE &mdash; CLICK TO EDIT ME';
			}

			// clones for the frontend animation
			$clones = intval($clones);
			for($i=1; $i<=$clones; $i++) {
				$output['html'] .= '<span class="semplice-marquee-content semplice-marquee-' . $mode . '">' . $content . '</span>';
			}

			// end output html
			$output['html'] .= '</div></div>';

			// add frontend call
			if(false === $this->is_editor) {
				$output['html'] .= '
					<script>
						(function($) {
							$(document).ready(function () {
								// container
								var container = $("#' . $id . '").find(".semplice-marquee-inner");
								// get options
								var options = JSON.parse(container.attr("data-options"));
								// load images
								if(options["mode"] == "image") {
									container.imagesLoaded().always( function(instance) {
										s4.helper.marquee("' . $id . '", options, container);
									});
								} else {
									s4.helper.marquee("' . $id . '", options, container);
								}
							});
						})(jQuery);
					</script>
				';
			}
			// add to html output
			$this->output['html'] = $output['html'];
			// add to css output
			$this->output['css'] = $output['css'];
			// return
			return $this->output;
		}

		// breakpoint css
		public function breakpoint_css($styles, $selector, $mode, $margin_right, $spacingPrefix) {
			// output css
			$output_css = '';
			// get breakpoints
			$breakpoints = semplice_get_breakpoints();
			// iterate breakpoints
			foreach ($breakpoints as $breakpoint => $width) {
				// css
				$css = '';
				// spacing
				if(isset($styles['margin_right_' . $breakpoint])) {
					$spacing = $styles['margin_right_' . $breakpoint];
				} else {
					$spacing = $margin_right;
				}
				// mode
				if($mode == 'image') {
					// margin right
					$css .= $selector . ' .semplice-marquee-image img { ' . $spacingPrefix . ': ' . $spacing . '; }';
					// image width
					if(isset($styles['width']) && $styles['width'] == 'custom' && isset($styles['custom_width_' . $breakpoint])) {
						$css .= $selector . ' .semplice-marquee-image img { width: ' . $styles['custom_width_' . $breakpoint] . '%; }';
					}
					
				} else if($mode == 'text') {
					// margin right
					$css .= $selector . ' .semplice-marquee-text { ' . $spacingPrefix . ': ' . $spacing . '; }';
					// font size
					if(isset($styles['fontsize_' . $breakpoint])) {
						$css .= $selector . ' .semplice-marquee-text { font-size: ' . $styles['fontsize_' . $breakpoint] . '; }';
					}
					// line height
					if(isset($styles['lineheight_' . $breakpoint])) {
						$css .= $selector . ' .semplice-marquee-text { line-height: ' . $styles['lineheight_' . $breakpoint] . '; }';
					}
					// letter spacing
					if(isset($styles['letter_spacing_' . $breakpoint])) {
						$css .= $selector . ' .semplice-marquee-text { letter-spacing: ' . $styles['letter_spacing_' . $breakpoint] . '; }';
					}
				}
				// add to css output
				if(!empty($css)) {
					if(true === $this->is_editor) {
						$output_css .= str_replace('##breakpoint##', $breakpoint, $css);
					} else {
						$output_css .= '@media screen' . $width['min'] . $width['max'] . ' { ' . str_replace('[data-breakpoint="##breakpoint##"] ', '', $css) . '}';
					}
				}
			}
			// return
			return $output_css;
		}

		// get speed
		public function speed($speed, $options) {
			// speed
			$speed = array(
				'xl' => $speed,
				'lg' => $speed,
				'md' => $speed,
				'sm' => $speed,
				'xs' => $speed
			);
			// get breakpoints
			$breakpoints = semplice_get_breakpoints();
			// iterate breakpoints
			foreach ($breakpoints as $breakpoint => $width) {
				if(isset($options['speed_' . $breakpoint])) {
					$speed[$breakpoint] = $options['speed_' . $breakpoint];
				}
			}
			// ret
			return $speed;
		}

		// output frontend
		public function output_frontend($values, $id) {
			// same as editor
			$this->is_editor = false;
			return $this->output_editor($values, $id);
		}
	}

	// instance
	editor_api::$modules['marquee'] = new sm_marquee;
}