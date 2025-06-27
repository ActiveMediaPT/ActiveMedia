<?php

// -----------------------------------------
// semplice
// admin/editor/modules/gallery/module.php
// -----------------------------------------

if(!class_exists('sm_gallery')) {
	class sm_gallery {

		public $output;

		// constructor
		public function __construct() {
			// define output
			$this->output = array(
				'html' => '',
				'css'  => '',
			);
		}

		// output editor
		public function output_editor($values, $id) {

			// get media
			$media = $values['content']['xl'];

			// get videos
			$videos = false;
			if(isset($values['videos']) && is_array($values['videos'])) {
				$videos = $values['videos'];
			}

			// media src
			$src = '';

			// preview
			if(is_array($media)) {
				foreach ($media as $attachment) {
					// check if attachment is a video
					if(false !== $videos && isset($videos[$attachment])) {
						// add video placeholder
						$src = get_template_directory_uri() . '/assets/images/admin/gallery_video_preview.svg';
						break;
					} else {
						$img = wp_get_attachment_image_src($attachment, 'full');
						if(false !== $img) {
							$src = $img[0];
						}
						// already has an preview found?
						if(!empty($src)) {
							break;
						}
					}
				}
			}

			// is preview still empty?
			if(empty($src)) {
				$this->output['html'] = semplice_module_placeholder('gallery', false, true, true, $id);
			} else {
				$this->output['html'] = '<div class="gallery-preview"><img class="is-content" src="' . $src . '" alt="gallery-placeholder"></div>';
			}
			
			// return output
			return $this->output;
		}

		// output frtonend
		public function output_frontend($values, $id) {

			// output
			$output = '';
			$gallery_output = '';
			$cover = array(
				'css' 		 => '',
				'class' 	 => '',
				'object-fit' => '',
			);
			$gallery_size = 'true';
			$classes = '';
			$slide_classes = '';
			$css = '';

			// attributes
			extract( shortcode_atts(
				array(
					'images'				=> '',
					'slide_width'			=> 'grid',
					'slide_width_custom'	=> 100,
					'width'					=> 'grid-width',
					'max_width'				=> 100,
					'height'				=> 'auto',
					'max_height'			=> 75,
					'spacing'				=> '0rem',
					'vertical_align'		=> 'flex-start',
					'freescroll'			=> 'false',
					'cover_mode'			=> 'disabled',
					'autoplay'				=> false,
					'adaptive_height'		=> 'true',
					'animation_status'		=> 'enabled',
					'animation'				=> 'sgs-crossfade',
					'timeout' 				=> 4000,
					'arrows_visibility'		=> 'true',
					'arrows_shape'			=> 'default',
					'arrows_custom'			=> false,
					'arrows_color'			=> '#ffffff',
					'arrows_bg_color'		=> '#000000',
					'arrows_bg_opacity'		=> 0,
					'pagination_visibility'	=> 'false',
					'pagination_style'		=> 'dots',
					'pagination_color'		=> '#000000',
					'pagination_position'	=> 'below',
					'attraction'			=> '0.025',
					'friction'				=> '0.28',
					'caption_visibility'	=> 'hidden',
					'caption_color'			=> '#000000',
					'caption_font'			=> 'regular',
					'caption_fontsize'		=> '0.8888888888888889rem',
					'caption_text_transform'=> 'none',
				), $values['options'] )
			);
			
			// autoplay?
			if($autoplay == 'true' && is_numeric($timeout)) {
				$autoplay = $timeout;
			} else {
				$autoplay = 'false';
			}

			// freescroll, animations and classes
			$fade = 'false';
			if($freescroll == 'true') {
				$classes .= ' sgs-freescroll';
				$slide_classes = 'sgs-transform ';
			} else if($animation_status == 'disabled') {
				$classes .= ' sgs-nofade';
			} else if($animation == 'sgs-crossfade') {
				$classes .= ' sgs-crossfade';
			} else if($animation == 'sgs-slide') {
				$slide_classes = 'sgs-transform ';
			}

			// arrows
			$default_arrows = array(
				'default'	  => 'M67.37,100L28.195,50,67.37,0,71.8,5.5,37.581,50,71.8,94.5Z',
				'alternative' => 'M95.849,46.323H14.1L40.364,20.15a4.166,4.166,0,0,0-5.9-5.881L1.076,47.537a4.162,4.162,0,0,0,0,5.891L34.462,86.7a4.166,4.166,0,0,0,5.9-5.881L14.1,54.642H95.849A4.159,4.159,0,1,0,95.849,46.323Z',
			);
			if($arrows_shape == 'custom') {
				if(!empty($arrows_custom)) {
					$arrow = htmlentities($arrows_custom);	
				} else {
					$arrow = $default_arrows['default'];
				}
			} else {
				$arrow = $default_arrows[$arrows_shape];
			}

			// arrow color
			if($arrows_bg_color != 'transparent') {
				$arrow_bg_color = semplice_hex_to_rgb($arrows_bg_color);
				$arrow_bg_color = 'rgba(' . $arrow_bg_color['r'] . ', ' . $arrow_bg_color['g'] . ', ' . $arrow_bg_color['b'] . ', ' . ($arrows_bg_opacity / 100) . ')';
			} else {
				$arrow_bg_color = 'transparent';
			}

			// caption js
			$caption = array('js' => '', 'css' => '');
			if($caption_visibility == 'visible' && $cover_mode == 'disabled') {
				$caption['js'] = '
					// Flickity instance
					var flkty = $gallery.data("flickity");
					// captions
					$gallery.on("select.flickity", function() {
						var caption = $(flkty.selectedElement).find("img").attr("caption");
						$("#' . $id . '").find(".flickity-caption").text(caption);
					});
				';
				$caption['css'] = '#' . $id . ' .flickity-meta .flickity-caption { display: block; }';
			}

			// get content
			$content = $values['content']['xl'];

			if(is_array($content)) {

				// cover class and css
				if($cover_mode == 'enabled') {
					// change min-height to vh if section is fullscreen. set min height to 100% if section height is custom and therefore defined with a fixed value for .container
					$min_height_unit = 'vh';
					$content_height = '100vh';
					if($values['section_height']['mode'] == 'custom') {
						$min_height_unit = '%';
						$content_height = $values['section_height']['height'];
					}

					// set up cover
					$cover = array(
						'css' 		 => $values['section_element'] . ' .row, ' . $values['section_element'] . ' .row .column { min-height: 100' . $min_height_unit . ' !important; } ' . $values['section_element'] . ' .column-content { height: ' . $content_height . '; }',
						'class' 	 => ' sgs-cover',
						'object-fit' => ' data-object-fit="cover"',
					);
					// add to classes
					$classes .= ' sgs-cover';
					// set gallery sizing to false
					$gallery_size = 'false';
				}

				$output .= '<div id="gallery-' . $id . '" class="is-content semplice-gallery-slider ' . $classes . '">';
				foreach($content as $media) {

					// image
					if(false !== strpos(get_post_mime_type($media), 'image')) {
						// get img
						$img = wp_get_attachment_image_src($media, 'full');

						// is image still in library?
						if(false !== $img) {
							// image alt
							$image_alt = semplice_get_image_alt($media);

							// caption
							$image_caption = wp_get_attachment_caption($media);
							
							$gallery_output .= '
								<div class="sgs-slide ' . $slide_classes . $width . ' sgs-slide-width-' . $slide_width . '">
									<img src="' . $img[0] . '" alt="' . $image_alt . '" caption="' . $image_caption . '"' . $cover['object-fit'] . ' />
								</div>
							';
						}
					} else if(false !== strpos(get_post_mime_type($media), 'video')) {
						// get video url
						$src = wp_get_attachment_url($media);
						// caption
						$video_caption = wp_get_attachment_caption($media);
						if(!empty($video_caption)) {
							$video_caption = '<div class="video-caption">' . $video_caption . '</div>';
						}
						// get video type
						$type = semplice_get_video_type($src);
						// is video url?
						if(!empty($src)) {
							$gallery_output .= '
								<div class="sgs-slide ' . $slide_classes . $width . ' sgs-slide-width-' . $slide_width . '">
									<video class="video" webkit-playsinline playsinline preload="metadata" muted="muted" loop="loop">
										<source src="' . $src . '" type="video/' . $type . '">
									</video>
									' . $video_caption . '
								</div>
							';
						}
					}					
				}
				
				// add to output
				$output .= $gallery_output;

				$output .= '</div><div class="flickity-meta pagination-' . $pagination_position . ' sgs-pagination-' . $pagination_visibility . '" data-caption-visibility="' . $caption_visibility . '" data-pagination-style="' . $pagination_style . '" data-freescroll="' . $freescroll . '"><div class="flickity-caption" data-font="' . $caption_font . '"></div></div>';

				// custom css for nav and pagination
				$css .= '
					#' . $id . ' .flickity-prev-next-button .arrow { fill: ' . $arrows_color . ' !important; }
					#' . $id . ' .flickity-page-dots .dot { background: ' . $pagination_color . ' !important; }
					#' . $id . ' .flickity-meta .flickity-caption { color: ' . $caption_color . '; font-size: ' . $caption_fontsize . '; text-transform: ' . $caption_text_transform . '; }
					#' . $id . ' .flickity-button-icon path { fill: ' . $arrows_color . '; }
					#' . $id . ' .flickity-prev-next-button { background-color: ' . $arrow_bg_color . '; }
					' . $cover['css'] . $caption['css'];

				// apply layout options only if cover mode is disabled
				if($cover_mode == 'disabled') {
					// get breakpoints
					$breakpoints = semplice_get_breakpoints(true);
					// iterate breakpoints
					foreach ($breakpoints as $breakpoint => $bp_width) {
						// bp css
						$bp_css = '';
						// bp
						$bp = ($breakpoint == 'xl' ? false : '_' . $breakpoint);
						// selector
						$selector = '#content-holder #' . $id;
						// slide width
						if($slide_width == 'image') {
							$bp_css .= $selector . ' .sgs-slide { width: auto; max-width: 100%; }';
						} else if($slide_width == 'custom') {
							$slide_width_custom_bp = isset($values['options']['slide_width_custom' . $bp]) ? $values['options']['slide_width_custom' . $bp] : $slide_width_custom;
							$bp_css .= $selector . ' .sgs-slide { width: ' . $slide_width_custom_bp . '%; }';
						}
						// max image width
						if($width == 'max-width') {
							$max_width_bp = isset($values['options']['max_width' . $bp]) ? $values['options']['max_width' . $bp] : $max_width;
							$bp_css .= $selector . ' .sgs-slide img, ' . $selector . ' .sgs-slide video { max-width: ' . $max_width_bp . '%; }';
						}
						// max image height
						if($height == 'max-height') {
							$max_height_bp = isset($values['options']['max_height' . $bp]) ? $values['options']['max_height' . $bp] : $max_height;
							$bp_css .= $selector . ' .sgs-slide img, ' . $selector . ' .sgs-slide video { max-height: ' . $max_height_bp . 'vh; }';
						}
						// spacing
						if($slide_width == 'image' || $slide_width == 'custom') {
							$spacing_bp = isset($values['options']['spacing' . $bp]) ? $values['options']['spacing' . $bp] : $spacing;
							$bp_css .= $selector . ' .sgs-slide { margin-right: ' . $spacing_bp . '; }';
						}
						// vertical align
						$align_bp = isset($values['options']['vertical_align' . $bp]) ? $values['options']['vertical_align' . $bp] : $vertical_align;
						$bp_css .= $selector . ' .flickity-slider { display: flex; align-items: ' . $align_bp . '; }';
						// add to css output
						if(!empty($css)) {
							$css .= '@media screen' . $bp_width['min'] . $bp_width['max'] . ' {' . $bp_css . '}';
						}
					}
				}

				$output .='
					<script>
						(function($) {
							$(document).ready(function () {
								// videos
								function flickityVideos(isChange, _this) {
									// first stop all videos
									if(true === isChange) {
										_this.find("video").each(function() {
											$(this)[0].pause();
										});
									}
									// get current slide
									var currentSlide = _this.find(".is-selected");
									// search for video
									var video = currentSlide.find("video");
									// has video?
									if(video.length > 0) {
										video[0].play();
										// video caption
										if(currentSlide.find(".video-caption").length > 0) {
											$(".flickity-caption").text(currentSlide.find(".video-caption").text());
										} else {
											$(".flickity-caption").text("");
										}
									}
								}
								// ready event listener
								$("#gallery-' . $id . '").on("ready.flickity", function() {
									// append dots to flickity meta
									if($(this).find(".flickity-page-dots").length > 0) {
										$("#' . $id . '").find(".flickity-meta").append($(this).find(".flickity-page-dots"));
									}
									// refresh scroll trigger
									s4.helper.refreshScrollTrigger();
									// videos
									flickityVideos(false, $(this));
									// sync scroll reveal
									if(s4.srStatus == "enabled") {
										sr.sync();
									}
								});
								// videos
								$("#gallery-' . $id . '").on("change.flickity", function() {
									// videos
									flickityVideos(true, $(this));
								});
								// flickity
								var $gallery = $("#gallery-' . $id . '").flickity({
									autoPlay: ' . $autoplay . ',
									adaptiveHeight: ' . $adaptive_height . ',
									prevNextButtons: ' . $arrows_visibility . ',
									pageDots: ' . $pagination_visibility . ',
									wrapAround: true,
									freeScroll: ' . $freescroll . ',
									setGallerySize: ' . $gallery_size . ',
									selectedAttraction: ' . $attraction . ',
									friction: ' . $friction . ',
									percentPosition: true,
									imagesLoaded: true,
									arrowShape: "' . $arrow . '",
									pauseAutoPlayOnHover: false
								});
								' . $caption['js'] . '
								// resize cells after video loaded
								function onLoadeddata(event) {
									var cell = $gallery.flickity( "getParentCell", event.target );
									$gallery.flickity( "cellSizeChange", cell && cell.element );
									// refresh scroll trigger
									s4.helper.refreshScrollTrigger();
									// sync scroll reveal
									if(s4.srStatus == "enabled") {
										sr.sync();
									}
								}
								// call resize on loadeddata
								$gallery.find("video").each( function(i, video) {
									$(video).on("loadeddata", onLoadeddata);
								});
							});
						})(jQuery);
					</script>
				';
			}

			// if there are not images show placeholder
			if(empty($gallery_output)) {
				$output .= semplice_module_placeholder('gallery', false, true, false, $id);
			}

			// save output
			$this->output['html'] = $output;
			$this->output['css'] = $css;

			return $this->output;
		}
	}
	// instance
	editor_api::$modules['gallery'] = new sm_gallery;
}