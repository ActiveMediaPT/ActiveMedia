<?php

// -----------------------------------------
// Semplice 6 Child Theme
// functions.php
// -----------------------------------------

// Enfileirar os estilos do tema pai e do tema filho
function semplice_child_enqueue_styles()
{
    // Carregar estilo do tema pai
    wp_enqueue_style('semplice-parent-style', get_template_directory_uri() . '/style.css', array(), semplice_theme('version'));

    // Carregar estilo do tema filho
    wp_enqueue_style('semplice-child-style', get_stylesheet_uri(), array('semplice-parent-style'), '1.0.0');

    // Carregar child js
    wp_enqueue_script('child-js', get_stylesheet_directory_uri() . '/assets/main.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'semplice_child_enqueue_styles', 20);

// Incluir a solução direta para substituição de tags de portfolio
require_once get_stylesheet_directory() . '/direct-tag-replacer.php';

// Incluir a solução direta para modificação do accordion
//require_once get_stylesheet_directory() . '/direct-accordion-modifier.php';

// Incluir o código para exibir posts recentes do journal
require_once get_stylesheet_directory() . '/recent-journal-posts.php';

// Adicionar CSS personalizado para o grid de portfólio e accordion
function semplice_child_custom_css()
{
?>
    <style type="text/css">
        /* Estilizações globais para o grid de portfólio */
        .masonry-item.thumb {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .masonry-item.thumb:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Remover borda ao redor das imagens */
        .masonry-item.thumb img {
            border: none !important;
        }

        /* Estilizações para o accordion */
        .accordion-item {
            transition: background-color 0.3s ease;
        }

        .accordion-item:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
    </style>
<?php
}
add_action('wp_head', 'semplice_child_custom_css', 100);


/**
 * Remove o skip link to template
 * É removido o elemento via JQuery @assets/main.js
 */
add_action('after_setup_theme', function () {
    remove_action('wp_footer', 'the_block_template_skip_link');
    remove_action('wp_enqueue_scripts', 'the_block_template_skip_link');
    remove_action('wp_enqueue_scripts', 'wp_enqueue_block_template_skip_link');
}, 20);

add_action('wp_footer', function () {
?>
    <script>
        jQuery(function($) {
            // Verifica se já existe o main
            if ($('#content-holder').length) {
                const skipLink = $('<a>', {
                    class: 'screen-reader-text',
                    href: '#content-holder',
                    text: 'Saltar para o conteúdo'
                });
                $('body').prepend(skipLink);
            }
        });
    </script>
<?php
});
