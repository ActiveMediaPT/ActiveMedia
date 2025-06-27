<?php
// -----------------------------------------
// Semplice 6 Child Theme
// direct-tag-replacer.php
// -----------------------------------------

/**
 * Solução direta para substituir as tags dos títulos no grid de portfólio
 * usando manipulação DOM via JavaScript
 */

// Não executar diretamente
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Adiciona script para substituir as tags do DOM depois que a página carrega
 */
function semplice_child_direct_tag_replacer() {
    // Não executar no admin
    if (is_admin()) {
        return;
    }
    
    // Configurações - MODIFIQUE AQUI
    $title_tag = 'h3';       // Tag para títulos (h1, h2, h3, h4, h5, h6)
    $category_tag = 'p';     // Tag para categorias (span, div, p)
    
    ?>
    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        // Função para substituir as tags de título
        function replaceProjectTitleTags() {
            // Encontra todos os títulos de projetos
            var projectTitles = document.querySelectorAll('.masonry-item.thumb .post-title');
            
            projectTitles.forEach(function(titleElement) {
                // Verificar se é um elemento div ou se já foi substituído
                if (titleElement.tagName.toLowerCase() !== '<?php echo strtolower($title_tag); ?>') {
                    // Criar o novo elemento com a tag desejada
                    var newTitleElement = document.createElement('<?php echo $title_tag; ?>');
                    
                    // Copiar atributos
                    for (var i = 0; i < titleElement.attributes.length; i++) {
                        var attr = titleElement.attributes[i];
                        newTitleElement.setAttribute(attr.name, attr.value);
                    }
                    
                    // Copiar o conteúdo interno
                    newTitleElement.innerHTML = titleElement.innerHTML;
                    
                    // Substituir o elemento original pelo novo
                    titleElement.parentNode.replaceChild(newTitleElement, titleElement);
                }
            });
            
            // Substituir tags de categoria
            var categoryElements = document.querySelectorAll('.masonry-item.thumb .post-title span');
            
            categoryElements.forEach(function(categoryElement) {
                if (categoryElement.tagName.toLowerCase() !== '<?php echo strtolower($category_tag); ?>') {
                    // Criar o novo elemento com a tag desejada
                    var newCategoryElement = document.createElement('<?php echo $category_tag; ?>');
                    
                    // Copiar atributos
                    for (var i = 0; i < categoryElement.attributes.length; i++) {
                        var attr = categoryElement.attributes[i];
                        newCategoryElement.setAttribute(attr.name, attr.value);
                    }
                    
                    // Copiar o conteúdo interno
                    newCategoryElement.innerHTML = categoryElement.innerHTML;
                    
                    // Substituir o elemento original pelo novo
                    categoryElement.parentNode.replaceChild(newCategoryElement, categoryElement);
                }
            });
        }
        
        // Executar função
        setTimeout(function() {
            replaceProjectTitleTags();
        }, 100);
        
        // Também executar quando o conteúdo for alterado dinamicamente (para SPA)
        if (typeof MutationObserver !== 'undefined') {
            // Observar mudanças no corpo do documento
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes && mutation.addedNodes.length > 0) {
                        // Verificar se foram adicionados elementos do tipo masonry-item
                        for (var i = 0; i < mutation.addedNodes.length; i++) {
                            var node = mutation.addedNodes[i];
                            if (node.nodeType === 1 && 
                                (node.classList && node.classList.contains('masonry-item') ||
                                 node.querySelector && node.querySelector('.masonry-item'))) {
                                setTimeout(function() {
                                    replaceProjectTitleTags();
                                }, 100);
                                break;
                            }
                        }
                    }
                });
            });
            
            // Iniciar observação
            observer.observe(document.body, { 
                childList: true, 
                subtree: true 
            });
        }
        
        // Adicionar estilos para as tags
        var customStyles = document.createElement('style');
        customStyles.textContent = `
            /* Estilização para títulos */
            .masonry-item.thumb <?php echo $title_tag; ?>.post-title {
                font-weight: 600;
                margin: 0;
                <?php if ($title_tag == 'h1') echo 'font-size: 2rem;'; ?>
                <?php if ($title_tag == 'h2') echo 'font-size: 1.5rem;'; ?>
                <?php if ($title_tag == 'h3') echo 'font-size: 1.25rem;'; ?>
                <?php if ($title_tag == 'h4') echo 'font-size: 1.125rem;'; ?>
                <?php if ($title_tag == 'h5') echo 'font-size: 1rem;'; ?>
                <?php if ($title_tag == 'h6') echo 'font-size: 0.875rem;'; ?>
            }
            
            /* Estilização para categorias */
            .masonry-item.thumb <?php echo $title_tag; ?>.post-title <?php echo $category_tag; ?> {
                display: block;
                margin-top: 0.4444444444444444rem;
                font-size: 0.875em;
                color: #999;
            }
        `;
        document.head.appendChild(customStyles);
        
        // Também interceptar possíveis carregamentos de AJAX
        var originalFetch = window.fetch;
        if (originalFetch) {
            window.fetch = function() {
                return originalFetch.apply(this, arguments).then(function(response) {
                    // Após cada solicitação fetch, verificar novamente os projetos
                    setTimeout(function() {
                        replaceProjectTitleTags();
                    }, 500);
                    return response;
                });
            };
        }
        
        // Interceptar XMLHttpRequest para casos em que fetch não é usado
        var originalXHROpen = XMLHttpRequest.prototype.open;
        XMLHttpRequest.prototype.open = function() {
            this.addEventListener('load', function() {
                setTimeout(function() {
                    replaceProjectTitleTags();
                }, 500);
            });
            originalXHROpen.apply(this, arguments);
        };
    });
    </script>
    <?php
}
add_action('wp_footer', 'semplice_child_direct_tag_replacer', 999);
