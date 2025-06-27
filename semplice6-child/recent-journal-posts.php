<?php
// -----------------------------------------
// Semplice 6 Child Theme
// recent-journal-posts.php
// -----------------------------------------

/**
 * Código para buscar e exibir os últimos posts do blog externo
 * activemedia.pt/journal na homepage
 */

// Não executar diretamente
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Função para buscar posts de um blog externo via API REST do WordPress
 */
function semplice_child_fetch_external_posts() {
    // URL do site externo (sem trailing slash)
    $external_site = 'https://activemedia.pt/journal';
    
    // Endpoint da API
    $api_url = $external_site . '/wp-json/wp/v2/posts';
    
    // Parâmetros da requisição
    $query_args = array(
        'per_page' => 3,           // Número de posts a serem retornados
        'orderby' => 'date',       // Ordenar por data
        'order' => 'desc',         // Ordem decrescente (mais recentes primeiro)
        '_embed' => true           // Incluir mídia incorporada (para as imagens)
    );
    
    // Adicionar parâmetros à URL
    $request_url = add_query_arg($query_args, $api_url);
    
    // Fazer a requisição à API
    $response = wp_remote_get($request_url, array(
        'timeout' => 10,           // Tempo limite em segundos
        'sslverify' => true        // Verificar certificado SSL
    ));
    
    // Verificar se houve erro na requisição
    if (is_wp_error($response)) {
        return array(
            'success' => false,
            'message' => $response->get_error_message()
        );
    }
    
    // Obter o corpo da resposta
    $body = wp_remote_retrieve_body($response);
    
    // Decodificar o JSON
    $posts = json_decode($body);
    
    // Verificar se a decodificação foi bem-sucedida
    if (is_null($posts)) {
        return array(
            'success' => false,
            'message' => 'Erro ao decodificar a resposta da API'
        );
    }
    
    // Verificar se posts foram encontrados
    if (empty($posts)) {
        return array(
            'success' => false,
            'message' => 'Nenhum post encontrado'
        );
    }
    
    // Processar os posts
    $processed_posts = array();
    
    foreach ($posts as $post) {
        // Obter a URL da imagem em destaque
        $featured_image_url = '';
        if (isset($post->_embedded->{'wp:featuredmedia'}[0]->source_url)) {
            $featured_image_url = $post->_embedded->{'wp:featuredmedia'}[0]->source_url;
        }
        
        // Adicionar post ao array de posts processados
        $processed_posts[] = array(
            'id' => $post->id,
            'title' => html_entity_decode($post->title->rendered, ENT_QUOTES, 'UTF-8'),
            'excerpt' => wp_strip_all_tags(html_entity_decode($post->excerpt->rendered, ENT_QUOTES, 'UTF-8')),
            'date' => date('d M Y', strtotime($post->date)),
            'link' => $post->link,
            'featured_image' => $featured_image_url
        );
    }
    
    return array(
        'success' => true,
        'posts' => $processed_posts
    );
}

/**
 * Função para adicionar cache aos resultados da API
 */
function semplice_child_get_cached_external_posts() {
    // Verificar se há dados em cache
    $cached_posts = get_transient('semplice_child_external_posts');
    
    // Se não houver cache ou estiver expirado
    if (false === $cached_posts) {
        // Buscar posts da API
        $posts_data = semplice_child_fetch_external_posts();
        
        // Se a busca foi bem-sucedida, armazenar em cache
        if ($posts_data['success']) {
            set_transient('semplice_child_external_posts', $posts_data, 3600); // Cache por 1 hora
        }
        
        return $posts_data;
    }
    
    // Retornar dados do cache
    return $cached_posts;
}

/**
 * Shortcode para exibir os posts do journal na homepage
 */
function semplice_child_recent_journal_posts_shortcode($atts) {
    // Extrair atributos do shortcode
    $atts = shortcode_atts(array(
        'title' => 'Latest Journal Posts',
        'show_date' => 'yes',
        'show_excerpt' => 'yes',
        'excerpt_length' => 120,
        'container_class' => 'journal-posts-container'
    ), $atts);
    
    // Buscar posts
    $posts_data = semplice_child_get_cached_external_posts();
    
    // Se houve erro na busca, retornar mensagem de erro
    if (!$posts_data['success']) {
        return '<div class="journal-posts-error">Error: ' . esc_html($posts_data['message']) . '</div>';
    }
    
    // Iniciar o buffer de saída
    ob_start();
    
    // Abrir o container
    echo '<div class="' . esc_attr($atts['container_class']) . '">';
    
    // Exibir o título (se houver)
    if (!empty($atts['title'])) {
        echo '<h2 class="journal-posts-title">' . esc_html($atts['title']) . '</h2>';
    }
    
    // Abrir a grade
    echo '<div class="journal-posts-grid">';
    
    // Exibir os posts
    foreach ($posts_data['posts'] as $post) {
        // Abrir o item
        echo '<div class="journal-post-item">';
        
        // Exibir a imagem (se disponível)
        if (!empty($post['featured_image'])) {
            echo '<div class="journal-post-image">';
            echo '<a href="' . esc_url($post['link']) . '" target="_blank">';
            echo '<img src="' . esc_url($post['featured_image']) . '" alt="' . esc_attr($post['title']) . '">';
            echo '</a>';
            echo '</div>';
        }
        
        // Exibir o conteúdo
        echo '<div class="journal-post-content">';
        
        // Exibir a data (se ativado)
        if ('yes' === $atts['show_date']) {
            echo '<div class="journal-post-date">' . esc_html($post['date']) . '</div>';
        }
        
        // Exibir o título
        echo '<h3 class="journal-post-title">';
        echo '<a href="' . esc_url($post['link']) . '" target="_blank">' . esc_html($post['title']) . '</a>';
        echo '</h3>';
        
        // Exibir o resumo (se ativado)
        if ('yes' === $atts['show_excerpt']) {
            $excerpt = $post['excerpt'];
            if (strlen($excerpt) > $atts['excerpt_length']) {
                $excerpt = substr($excerpt, 0, $atts['excerpt_length']) . '...';
            }
            echo '<div class="journal-post-excerpt">' . esc_html($excerpt) . '</div>';
        }
        
        echo '</div>'; // Fechar journal-post-content
        
        echo '</div>'; // Fechar journal-post-item
    }
    
    // Fechar a grade
    echo '</div>';
    
    // Fechar o container
    echo '</div>';
    
    // Adicionar estilos CSS
    ?>
    <style>
        .journal-posts-container {
            margin: 2rem 0;
        }
        
        .journal-posts-title {
            margin-bottom: 1.5rem;
            font-size: 2rem;
            font-weight: 600;
        }
        
        .journal-posts-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }
        
        @media (max-width: 768px) {
            .journal-posts-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .journal-post-item {
            display: flex;
            flex-direction: column;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .journal-post-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .journal-post-image {
            width: 80%;
            height: 0;
            padding-bottom: 50%; /* 16:9 aspect ratio (56.25%)*/
            position: relative;
            overflow: hidden;
        }
        
        .journal-post-image img {
            position: absolute;
			margin: 2rem;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .journal-post-item:hover .journal-post-image img {
            transform: scale(1.05);
        }
        
        .journal-post-content {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .journal-post-date {
            font-size: 0.875rem;
            color: #666;
            margin-bottom: 0.5rem;
        }
        
        .journal-post-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0 0 0.75rem;
            line-height: 1.8;
        }
        
        .journal-post-title a {
            color: #000;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .journal-post-title a:hover {
            color: #d5d3dd;
        }
        
        .journal-post-excerpt {
            font-size: 0.9375rem;
            line-height: 1.5;
            color: #444;
            margin: 0;
        }
    </style>
    <?php
    
    // Retornar o conteúdo do buffer
    return ob_get_clean();
}

// Registrar o shortcode
add_shortcode('recent_journal_posts', 'semplice_child_recent_journal_posts_shortcode');

/**
 * Adicionar botão para atualizar o cache dos posts
 */
function semplice_child_add_refresh_button() {
    // Verificar se o usuário tem permissões
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Verificar se a ação de atualização foi solicitada
    if (isset($_GET['refresh_journal_posts']) && $_GET['refresh_journal_posts'] == 1) {
        // Limpar o cache
        delete_transient('semplice_child_external_posts');
        
        // Buscar novos dados
        semplice_child_get_cached_external_posts();
        
        // Redirecionar para a mesma página sem o parâmetro de consulta
        wp_redirect(remove_query_arg('refresh_journal_posts'));
        exit;
    }
    
    // Adicionar botão de atualização
    ?>
    <div class="wrap">
        <h2>Journal Posts Cache</h2>
        <p>O shortcode [recent_journal_posts] exibe os dois últimos posts do blog activemedia.pt/journal.</p>
        <p>Você pode atualizar o cache clicando no botão abaixo.</p>
        <a href="<?php echo add_query_arg('refresh_journal_posts', 1); ?>" class="button button-primary">Atualizar Posts do Journal</a>
    </div>
    <?php
}

// Adicionar página ao menu de admin
function semplice_child_add_journal_posts_page() {
    add_submenu_page(
        'tools.php',                     // Página pai
        'Journal Posts',                 // Título da página
        'Journal Posts',                 // Título do menu
        'manage_options',                // Capacidade necessária
        'journal-posts',                 // Slug
        'semplice_child_add_refresh_button' // Função de callback
    );
}
add_action('admin_menu', 'semplice_child_add_journal_posts_page');
