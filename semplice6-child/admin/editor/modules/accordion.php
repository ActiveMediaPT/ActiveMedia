<?php

// -----------------------------------------
// semplice
// admin/editor/modules/accordion.php
// -----------------------------------------

if(!class_exists('sm_accordion')) {
	class sm_accordion {

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

		// output frontend
		public function output_editor($values, $id) {
			// values
			extract( shortcode_atts(
				array(
					'first_item'					=> 'collapsed',
					'mode'							=> 'toggle',
					'bg_color'						=> '#ffffff',
					'padding_ver'					=> '1.666666666666667rem',
					'padding_hor'					=> '0rem',
					'border_radius'					=> '0',
					'seperator_visibility'			=> 'visible',
					'seperator_height'				=> '0.0555555555555556rem',
					'seperator_color'				=> '#cccccc',
					'seperator_mode'				=> 'hidden',
					'title_color'					=> '#000000',
					'title_font_family'				=> 'inter_bold',
					'title_font_size'				=> '2.222222222222222rem',
					'title_line_height'				=> '2.777777777777778rem',
					'title_letter_spacing'			=> '0rem',
					'title_text_transform'			=> 'none',
					'title_right_spacing'			=> '3.333333333333333rem',
					'title_align'					=> 'left',
					'content_spacing'				=> '1.666666666666667rem',
					'description_color'				=> '#777777',
					'description_font_family'		=> 'inter_light',
					'description_font_size'			=> '1.111111111111111rem',
					'description_line_height'		=> '1.666666666666667rem',
					'description_letter_spacing'	=> '0rem',
					'description_text_transform'	=> 'none',
					'description_spacing'			=> '1.111111111111111rem',
					'description_align'				=> 'left',
					'icon_type'						=> 'plus',
					'icon_color'					=> '#000000',
					'icon_width'					=> '1.666666666666667rem',
					'icon_spacing'					=> '0.5555555555555556rem',
					'icon_expand'					=> false,
					'icon_collapse'					=> false
				), $values['options'] )
			);

			// get content
			$content = $values['content']['xl'];

			// editable
			$editable = '';
			if(true === $this->is_editor) {
				$editable = ' contenteditable="true" data-accordion-editable="editable"';
			}

			// counts
			$items = '';
			$count = 0;
			$max = count($content['order']) - 1;
			// add top seperator
			$items .= '<div class="seperator top-seperator"></div>';
			// iterate order
			foreach($content['order'] as $accordion_id) {
				// check if item is still there
				if(isset($content['items'][$accordion_id])) {
					// shorthand
					$item = $content['items'][$accordion_id];
					// status
					$status = array('"', '');
					if(false === $this->is_editor && $count == 0 && $first_item == 'expanded') {
						$status = array(' expanded"', 'style="height: auto";');
					}
					// default icon
					$icon = array(
						'expand' => get_svg('frontend', 'icons/accordion_plus_expand'),
						'collapse' => get_svg('frontend', 'icons/accordion_plus_collapse')
					);
					// icon
					$types = array('expand' => $icon_expand, 'collapse' => $icon_collapse);
					foreach($types as $type => $attachment) {
						if($icon_type == 'custom' && false !== $attachment) {
							$icon[$type] = '<img src="' . semplice_get_image($attachment, 'full') . '">';
						} else if($icon_type != 'custom') {
							$icon[$type] = get_svg('frontend', 'icons/accordion_' . $icon_type . '_' . $type);
						}
					}
					// add seperator
					if($count > 0) {
						$items .= '<div class="seperator"></div>';
					}
					// get content
					$item_content = $this->get_item_content($item);
					// add to items
					$items .= '
						<div class="accordion-item' . $status[0] . ' data-accordion-id="' . $accordion_id . '">
							<div class="title" data-font="' . $title_font_family . '">
								<div class="title-span"' . $editable . ' data-name="title">' . $item['title'] . '</div>
								<div class="icon">
									<span class="expand">' . $icon['expand'] . '</span>
									<span class="collapse">' . $icon['collapse'] . '</span>
								</div>
							</div>
							<div class="description" ' . $status[1] . '><div class="description-inner" data-font="' . $description_font_family . '">' . $item_content . '</div></div>
							' . $this->get_actions($id, $accordion_id) . '
						</div>
					';
					
				}
				// inc count
				$count++;
			}
			// add bottom seperator
			$items .= '<div class="seperator bottom-seperator"></div>';
			// css
			$target = '#content-holder #' . $id;
			$css = '
				' . $target . ' .accordion-item {
					padding: ' . $padding_ver . ' ' . $padding_hor . ';
					background-color: ' . $bg_color . ';
					border-radius: ' . $border_radius . ';
				}
				' . $target . ' .seperator {
					height: ' . $seperator_height . ';
					background-color: ' . $seperator_color . ';
				}
				' . $target . ' .title {
					color: ' . $title_color . ';
					font-size: ' . $title_font_size . ';
					line-height: ' . $title_line_height . ';
					letter-spacing: ' . $title_letter_spacing . ';
					text-transform: ' . $title_text_transform . ';
					text-align: ' . $title_align . ';
				}
				' . $target . ' .title .title-span {
					width: calc(100% - ' . $icon_width . ');
					padding-right: ' . $title_right_spacing . ';
				}
				' . $target . ' .icon {
					fill: ' . $icon_color . ';
					top: ' . $icon_spacing . ';
				}
				' . $target . ' .title .icon svg,  ' . $target . ' .title .icon img {
					width: ' . $icon_width . ';
				}
				' . $target . ' .description-inner {
					color: ' . $description_color . ';
					font-size: ' . $description_font_size . ';
					line-height: ' . $description_line_height . ';
					letter-spacing: ' . $description_letter_spacing . ';
					text-transform: ' . $description_text_transform . ';
					margin-top: ' . $description_spacing . ';
					text-align: ' . $description_align . ';
				}
				' . $target . ' .accordion-content {
					margin-bottom: ' . $content_spacing . ';
				}
			';
			
			// get breakpoint css
			$css .= $this->breakpoint_css($values['options'], '[data-breakpoint="##breakpoint##"] #content-holder #' . $id, $padding_ver, $padding_hor);

			// add to html output
			$this->output['html'] = '
				<div class="is-content accordion-edit" data-seperator-visibility="' . $seperator_visibility . '" data-seperator-mode="' . $seperator_mode . '" data-accordion-id="' . $id . '" data-mode="' . $mode . '">
					' . $items . '
				</div>
			';

			// add to css output
			$this->output['css'] = $css;

			// output
			return $this->output;
		}

		// get actions
		public function get_actions($id, $accordion_id) {
			$actions = '';
			if(true === $this->is_editor) {
				$actions = '
					<div class="actions">
						<a class="accordion-add-element"></a>
						<a class="editor-action accordion-remove" data-action-type="accordion" data-action="remove" data-content-id="' . $id . '" data-remove-id="' . $accordion_id . '"></a>
						<a class="accordion-handle"></a>
						<ul>
							<li><a class="editor-action" data-action-type="accordion" data-action="addContent" data-content="text" data-item-id="' . $accordion_id . '">Text</a></li>
							<li><a data-handler="execute" class="upload-button admin-click-handler" data-action="init" data-action-type="mediaLibrary" data-content-id="' . $id . '" data-item-id="' . $accordion_id . '" data-ep-visibility="hidden" data-media-type="image" name="accordion-image" data-upload="accordionImage">Image</a></li>
						</ul>
					</div>
				';
			}
			// return
			return $actions;
		}

		// get item content
		public function get_item_content($item) {
			// content
			$content = '';
			// checken ob altes descprtion format
			if(isset($item['description'])) {
				$content .= '<div id="accordion-content_' . substr(md5(rand()), 0, 9) . '" class="accordion-text accordion-content">' . $item['description'] . '</div>';
			} else if(isset($item['content']) && is_array($item['content']) && !empty($item['content'])) {
				foreach($item['content'] as $content_id => $item_content) {
					// data atts
					$data_atts = '';
					if(isset($item_content['settings']) && !empty($item_content['settings'])) {
						$settings = array('align', 'width');
						foreach($settings as $setting) {
							if(isset($item_content['settings'][$setting])) {
								$data_atts .= ' data-' . $setting . '="' . $item_content['settings'][$setting] . '"';
							}
						}
					}					
					// add html
					$content .= '<div id="' . $content_id . '" class="accordion-' . $item_content['type'] . ' accordion-content"' . $data_atts . '>' . $item_content['html'] . '</div>';
				}
			}
			// return
			return $content;
		}

		// breakpoint css
		public function breakpoint_css($styles, $selector, $padding_ver, $padding_hor) {
			// output css
			$output_css = '';
			// get breakpoints
			$breakpoints = semplice_get_breakpoints();
			// iterate breakpoints
			foreach ($breakpoints as $breakpoint => $width) {
				// css
				$css = '';
				// padding ver
				if(isset($styles['padding_ver_' . $breakpoint])) {
					$padding_ver = $styles['padding_ver_' . $breakpoint];
				}
				// padding hor
				if(isset($styles['padding_hor_' . $breakpoint])) {
					$padding_hor = $styles['padding_hor_' . $breakpoint];
				}
				// set padding
				$css .= $selector . ' .accordion-item { padding: ' . $padding_ver . ' ' . $padding_hor . '; }';
				// border radius
				if(isset($styles['border_radius_' . $breakpoint])) {
					$css .= $selector . ' .accordion-item { border-radius: ' . $styles['border_radius_' . $breakpoint] . '; }';
				}
				// seperator width
				if(isset($styles['seperator_height_' . $breakpoint])) {
					$css .= $selector . ' .seperator { height: ' . $styles['seperator_height_' . $breakpoint] . '; }';

				}
				// title right spacing
				if(isset($styles['title_right_spacing_' . $breakpoint])) {
					$css .= $selector . ' .title .title-span { padding-right: ' . $styles['title_right_spacing_' . $breakpoint] . '; }';
				}
				// description top spacing
				if(isset($styles['description_spacing_' . $breakpoint])) {
					$css .= $selector . ' .description-inner { margin-top: ' . $styles['description_spacing_' . $breakpoint] . '; }';
				}
				// content spacing
				if(isset($styles['content_spacing_' . $breakpoint])) {
					$css .= $selector . ' .description-inner .accordion-content { margin-bottom: ' . $styles['content_spacing_' . $breakpoint] . '; }';
				}
				// icon width
				if(isset($styles['icon_width_' . $breakpoint])) {
					$css .= $selector . ' .title .title-span { width: calc(100% - ' . $styles['icon_width_' . $breakpoint] . '); }';
					$css .= $selector . ' .title .icon svg, ' . $selector . ' .title .icon img { width: ' . $styles['icon_width_' . $breakpoint] . '; }';
				}
				// icon spacing
				if(isset($styles['icon_spacing_' . $breakpoint])) {
					$css .= $selector . ' .title .icon { top: ' . $styles['icon_spacing_' . $breakpoint] . '; }';
				}
				// formatting options
				$types = array('title' => '.title', 'description' => '.description-inner');
				$formatting = array('font-size', 'line-height', 'letter-spacing');
				foreach($types as $type => $target) {
					foreach($formatting as $attribute) {
						if(isset($styles[$type . '_' . str_replace('-', '_', $attribute) . '_' . $breakpoint])) {
							$css .= $selector . ' ' . $target . ' { ' . $attribute . ': ' . $styles[$type . '_' . str_replace('-', '_', $attribute) . '_' . $breakpoint] . '; }';
						}
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

		// output frontend
		public function output_frontend($values, $id) {
			// set is frontend
			$this->is_editor = false;
			
			// same as editor
			return $this->output_editor($values, $id);
		}
	}

	// instance
	editor_api::$modules['accordion'] = new sm_accordion;
}