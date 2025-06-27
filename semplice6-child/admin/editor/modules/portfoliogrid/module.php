<?php

// -----------------------------------------
// Semplice Child Theme
// admin/editor/modules/portfoliogrid/module.php
// -----------------------------------------

// Verificar se a classe já existe para evitar conflitos
if(!class_exists('sm_portfoliogrid_child')) {

	class sm_portfoliogrid_child extends sm_portfoliogrid {

		// Construtor - chama o construtor da classe pai
		public function __construct() {
			parent::__construct();
		}

		// Sobrescrever o método get_masonry_items para personalizar a aparência dos itens da grade
		public function get_masonry_items($id, $project, $atts, $is_editor, $lazy_load_class) {
			if(empty($project['image']['width'])) {
				$project['image']['width'] = 6;
			}

			// Definir as tags padrão caso não estejam definidas
			if (!isset($atts['title_tag'])) {
				$atts['title_tag'] = 'h3';
			}
			
			if (!isset($atts['category_tag'])) {
				$atts['category_tag'] = 'span';
			}

			// título e categoria com tags personalizáveis
			$title = '';
			if($atts['title_visibility'] == 'both') {
				$title = '
					<' . $atts['title_tag'] . ' class="post-title ' . $atts['title_font'] . '">
                        ' . $project['post_title'] . '
                        <' . $atts['category_tag'] . ' class="' . $atts['category_font'] . '">' . $project['project_type'] . '</' . $atts['category_tag'] . '>
                    </' . $atts['title_tag'] . '>
				'; 
			} else if($atts['title_visibility'] == 'title') {
				$title = '
					<' . $atts['title_tag'] . ' class="post-title ' . $atts['title_font'] . '">' . $project['post_title'] . '</' . $atts['title_tag'] . '>
				'; 
			} else if($atts['title_visibility'] == 'category') {
				$title = '
					<' . $atts['title_tag'] . ' class="post-title ' . $atts['title_font'] . '"><' . $atts['category_tag'] . ' class="' . $atts['category_font'] . '">' . $project['project_type'] . '</' . $atts['category_tag'] . '></' . $atts['title_tag'] . '>
				'; 
			}

			// Vincular título se abaixo
			if(false !== strpos($atts['title_position'], 'below')) {
				$title = '<a class="pg-title-link pg-link" href="' . $project['permalink'] . '" title="' . $project['post_title'] . '">' . $title . '</a>';
			}

			// mostrar link de configurações de post no admin
			if(false === $is_editor) {
				$thumb_inner = '<a href="' . $project['permalink'] . '" class="pg-link">' . $this->get_thumb_inner($id, $atts['global_hover_options'], $project, true, $title, $atts['title_position']);
			} else {
				$thumb_inner = $this->get_thumb_inner($id, $atts['global_hover_options'], $project, false, $title, $atts['title_position']);
			}

			// classes de categoria
			$category_classes = '';
			if(is_array($project['categories']) && !empty($project['categories'])) {
				$category_classes = ' ';
				foreach ($project['categories'] as $categories => $cat_id) {
					$category_classes .= 'cat-' . $cat_id . ' ';
				}
			}

			// hover de miniatura de vídeo
			$video_hover = '';
			if(isset($atts['global_hover_options']['hover_bg_type']) && $atts['global_hover_options']['hover_bg_type'] == 'vid' || isset($project['thumb_hover']['hover_bg_type']) && $project['thumb_hover']['hover_bg_type'] == 'vid') {
				$video_hover = ' video-hover';
			}

			// Adicionando classe personalizada para cada item
			$custom_class = ' custom-grid-item';

			// abrir item de masonry
			$masonry_item = '<div id="project-' . $project['post_id'] . '" class="masonry-item thumb masonry-' . $id . '-item ' . $atts['title_position'] . '' . $category_classes . '' . $video_hover . '' . $lazy_load_class . $custom_class . '" data-xl-width="' . $project['image']['grid_width'] . '" data-sm-width="6" data-xs-width="12">';

			// adicionar conteúdo interno do thumb
			$masonry_item .= $thumb_inner;

			// fechar item de masonry
			$masonry_item .= '</div>';

			// retornar item
			return $masonry_item;
		}
	}

	// Substitui a instância original pela nossa versão personalizada
	editor_api::$modules['portfoliogrid'] = new sm_portfoliogrid_child();
}
