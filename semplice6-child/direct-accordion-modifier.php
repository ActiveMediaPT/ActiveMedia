<?php
// -----------------------------------------
// Semplice 6 Child Theme
// direct-accordion-modifier.php
// -----------------------------------------

/**
 * Solução direta para personalizar o comportamento do accordion
 * usando manipulação DOM via JavaScript
 */

// Não executar diretamente
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Adiciona script para modificar o accordion depois que a página carrega
 */
function semplice_child_direct_accordion_modifier() {
    // Não executar no admin
    if (is_admin()) {
        return;
    }
    
    // Configurações - MODIFIQUE AQUI
    $title_tag = 'h3';       // Tag para títulos dos items do accordion (h2, h3, h4, h5, h6)
    $content_tag = 'p';      // Tag para conteúdo de texto do accordion (p, div)
    $arrow_color = '#000000'; // Cor das setas do accordion
    
    ?>
    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        // Função para substituir as tags de título no accordion
        function modifyAccordionItems() {
            // Encontra todos os títulos de accordion
            var accordionTitles = document.querySelectorAll('.accordion-item .title .title-span');
            
            accordionTitles.forEach(function(titleElement) {
                // Obter o elemento pai (div.title)
                var titleParent = titleElement.parentNode;
                
                // Se o título ainda não foi modificado
                if (titleParent && !titleParent.classList.contains('title-modified')) {
                    // Criar novo elemento de título com a tag desejada
                    var newTitleElement = document.createElement('<?php echo $title_tag; ?>');
                    
                    // Copiar classes e outros atributos necessários
                    newTitleElement.className = 'title-span';
                    if (titleElement.hasAttribute('data-name')) {
                        newTitleElement.setAttribute('data-name', titleElement.getAttribute('data-name'));
                    }
                    if (titleElement.hasAttribute('contenteditable')) {
                        newTitleElement.setAttribute('contenteditable', titleElement.getAttribute('contenteditable'));
                    }
                    if (titleElement.hasAttribute('data-accordion-editable')) {
                        newTitleElement.setAttribute('data-accordion-editable', titleElement.getAttribute('data-accordion-editable'));
                    }
                    
                    // Copiar o conteúdo
                    newTitleElement.innerHTML = titleElement.innerHTML;
                    
                    // Substituir o elemento original pelo novo
                    titleParent.replaceChild(newTitleElement, titleElement);
                    
                    // Marcar o pai como modificado
                    titleParent.classList.add('title-modified');
                }
            });
            
            // Encontrar texto no accordion e substituir por tags apropriadas
            var accordionTexts = document.querySelectorAll('.accordion-item .accordion-text');
            
            accordionTexts.forEach(function(textElement) {
                // Se o conteúdo ainda não foi processado
                if (!textElement.classList.contains('content-modified')) {
                    // Obter o conteúdo HTML
                    var content = textElement.innerHTML;
                    
                    // Substituir <br> por quebras de parágrafo reais
                    var paragraphs = content.split(/<br\s*\/?>/i);
                    
                    // Criar novo conteúdo com tags de parágrafo apropriadas
                    var newContent = '';
                    paragraphs.forEach(function(paragraph) {
                        // Ignorar parágrafos vazios
                        if (paragraph.trim() !== '') {
                            newContent += '<<?php echo $content_tag; ?>>' + paragraph.trim() + '</<?php echo $content_tag; ?>>';
                        }
                    });
                    
                    // Atualizar o conteúdo
                    textElement.innerHTML = newContent;
                    
                    // Marcar como modificado
                    textElement.classList.add('content-modified');
                }
            });
        }
        
        // Executar função
        setTimeout(function() {
            modifyAccordionItems();
        }, 100);
        
        // Também executar quando o conteúdo for alterado dinamicamente (para SPA)
        if (typeof MutationObserver !== 'undefined') {
            // Observar mudanças no corpo do documento
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes && mutation.addedNodes.length > 0) {
                        // Verificar se foram adicionados elementos do accordion
                        for (var i = 0; i < mutation.addedNodes.length; i++) {
                            var node = mutation.addedNodes[i];
                            if (node.nodeType === 1 && 
                                (node.classList && node.classList.contains('accordion-item') ||
                                 node.querySelector && node.querySelector('.accordion-item'))) {
                                setTimeout(function() {
                                    modifyAccordionItems();
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
        
        // Adicionar estilos personalizados
        var customStyles = document.createElement('style');
        customStyles.textContent = `
            /* Estilização para títulos do accordion */
            .accordion-item .title <?php echo $title_tag; ?>.title-span {
                margin: 0;
                padding: 0;
                font: inherit;
                /* Preserva a formatação aplicada no Semplice */
                font-family: inherit;
                font-size: inherit;
                line-height: inherit;
                letter-spacing: inherit;
                text-transform: inherit;
            }
            
            /* Estilização para conteúdo do accordion */
            .accordion-item .accordion-text <?php echo $content_tag; ?> {
                margin-top: 0.5em;
                margin-bottom: 0.5em;
            }
            
            /* Estilização para o primeiro parágrafo */
            .accordion-item .accordion-text <?php echo $content_tag; ?>:first-child {
                margin-top: 0;
            }
            
            /* Estilização para o último parágrafo */
            .accordion-item .accordion-text <?php echo $content_tag; ?>:last-child {
                margin-bottom: 0;
            }
            
            /* Cor personalizada para os ícones do accordion */
            .accordion-item .icon svg path {
                fill: <?php echo $arrow_color; ?> !important;
            }
        `;
        document.head.appendChild(customStyles);
        
        // Também interceptar possíveis carregamentos de AJAX para modificar novos elementos
        var originalFetch = window.fetch;
        if (originalFetch) {
            window.fetch = function() {
                return originalFetch.apply(this, arguments).then(function(response) {
                    // Após cada solicitação fetch, verificar novamente os accordions
                    setTimeout(function() {
                        modifyAccordionItems();
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
                    modifyAccordionItems();
                }, 500);
            });
            originalXHROpen.apply(this, arguments);
        };
    });
    </script>
    <?php
}
add_action('wp_footer', 'semplice_child_direct_accordion_modifier', 999);
