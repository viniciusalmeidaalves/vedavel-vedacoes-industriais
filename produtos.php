<?php
// =========================================================
// --- produtos.php ---
// Página para exibir o catálogo de produtos com filtro por categoria/subcategoria e paginação.
// =========================================================

// Inclui o cabeçalho dinâmico que estabelece a conexão com o BD
// É importante que esta inclusão venha antes de qualquer uso de $conn
include 'includes/header.php'; // Esta é a inclusão CORRETA e única do cabeçalho.

// --- Lógica de Paginação ---
$produtos_por_pagina = 20; // Limite de produtos por página
$pagina_atual = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página atual, padrão é 1
if ($pagina_atual < 1) $pagina_atual = 1; // Garante que a página não seja menor que 1

$offset = ($pagina_atual - 1) * $produtos_por_pagina; // Calcula o offset para a consulta SQL

// --- Lógica de Filtro ---
$filter_category_id = $_GET['categoria_id'] ?? null; // Captura ID da categoria da URL
$filter_subcategory_id = $_GET['subcategoria_id'] ?? null; // Captura ID da subcategoria da URL

// Query base para produtos
// CORREÇÃO: Adicionado p.slug à seleção de colunas
$sql_produtos = "SELECT p.id, p.nome, p.descricao_curta, p.link_imagem_principal, p.preco, p.quantidade_estoque, p.slug,
                        c.nome AS categoria_nome, s.nome AS subcategoria_nome
                 FROM produtos p
                 JOIN categorias c ON p.categoria_id = c.id
                 LEFT JOIN subcategorias s ON p.subcategoria_id = s.id";

// Query para contar o total de produtos (para paginação)
$sql_count = "SELECT COUNT(p.id) AS total FROM produtos p";

$where_clauses = []; // Array para armazenar as cláusulas WHERE
$params = []; // Array para armazenar os parâmetros para o prepared statement
$param_types = ''; // String para os tipos dos parâmetros

// Adiciona filtro por categoria, se houver
if ($filter_category_id) {
    $where_clauses[] = "p.categoria_id = ?";
    $params[] = $filter_category_id;
    $param_types .= 'i'; // 'i' para integer
}

// Adiciona filtro por subcategoria, se houver
if ($filter_subcategory_id) {
    $where_clauses[] = "p.subcategoria_id = ?";
    $params[] = $filter_subcategory_id;
    $param_types .= 'i'; // 'i' para integer
}

// Constrói a cláusula WHERE final
if (!empty($where_clauses)) {
    $sql_produtos .= " WHERE " . implode(" AND ", $where_clauses);
    $sql_count .= " WHERE " . implode(" AND ", $where_clauses);
}

// --- Total de Produtos e Paginação ---
$stmt_count = $conn->prepare($sql_count);
if (!empty($params)) {
    $stmt_count->bind_param($param_types, ...$params);
}
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$total_produtos = $result_count->fetch_assoc()['total'];
$total_paginas = ceil($total_produtos / $produtos_por_pagina);
$stmt_count->close();

// --- Busca dos Produtos para a Página Atual ---
$sql_produtos .= " ORDER BY p.nome ASC LIMIT ? OFFSET ?";
$stmt_produtos = $conn->prepare($sql_produtos);

// Adiciona os parâmetros de limite e offset aos parâmetros existentes
$params_produtos = array_merge($params, [$produtos_por_pagina, $offset]);
$param_types_produtos = $param_types . 'ii'; // Adiciona 'ii' para os dois novos inteiros (limite e offset)

$stmt_produtos->bind_param($param_types_produtos, ...$params_produtos);
$stmt_produtos->execute();
$result_produtos = $stmt_produtos->get_result();

// --- Busca de Categorias e Subcategorias para o Menu Lateral ---
$categorias_menu_lateral = [];
$sql_menu_lateral = "SELECT c.id AS categoria_id, c.nome AS categoria_nome,
                             s.id AS subcategoria_id, s.nome AS subcategoria_nome
                      FROM categorias c
                      LEFT JOIN subcategorias s ON c.id = s.categoria_id
                      ORDER BY c.nome ASC, s.nome ASC";
$result_menu_lateral = $conn->query($sql_menu_lateral);

if ($result_menu_lateral) {
    while ($row = $result_menu_lateral->fetch_assoc()) {
        $categoria_id = $row['categoria_id'];
        $categoria_nome = htmlspecialchars($row['categoria_nome']);

        // Se a categoria ainda não existe no array, adicione-a
        if (!isset($categorias_menu_lateral[$categoria_id])) {
            $categorias_menu_lateral[$categoria_id] = [
                'id' => $categoria_id,
                'nome' => $categoria_nome,
                'subcategorias' => []
            ];
        }

        // Se houver uma subcategoria para a linha atual, adicione-a
        if ($row['subcategoria_id'] !== null) {
            $categorias_menu_lateral[$categoria_id]['subcategorias'][] = [
                'id' => $row['subcategoria_id'],
                'nome' => htmlspecialchars($row['subcategoria_nome'])
            ];
        }
    }
} else {
    error_log("Erro ao buscar dados para o menu lateral: " . $conn->error);
}

// Fechar a conexão com o banco de dados no final do script principal
// A conexão $conn é aberta em `header.php` e deve ser fechada aqui
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vedavel - Produtos</title>
    <meta name="description" content="Explore a linha de produtos da Vedavel, com soluções de alta qualidade em anéis, gaxetas, retentores e elementos de vedação.">
    <meta name="keywords" content="vedações industriais, anéis o-ring, gaxetas, retentores, juntas, calços de borracha, coxins de borracha, batentes de borracha, arruelas de borracha, elementos elásticos, acoplamentos, anéis raspadores, soluções de vedação, produtos industriais, vedação para máquinas, vedação para equipamentos, Vedavel">
    <link rel="icon" href="./midias/imagens/icones/favicon-vedavel.png" type="image/png">

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-QRXKM1DF0J"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-QRXKM1DF0J');
    </script>

    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-NVG6L5RZ');</script>
    <link rel="stylesheet" href="./css/global.css">
    <link rel="stylesheet" href="./css/menu.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="./css/produtos.css">
    <link rel="stylesheet" href="./css/catalogo.css">
    <link rel="stylesheet" href="./css/contato.css">
    <link rel="stylesheet" href="./css/detalhe-produto.css">
    <link rel="stylesheet" href="./css/cookie-consent.css"> 
    <link rel="stylesheet" href="./css/politica-cookies.css">
    <link rel="stylesheet" href="./css/accessibility.css">

    <script src="https://kit.fontawesome.com/dc52f1179d.js" crossorigin="anonymous"></script>
</head>
<body>

    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NVG6L5RZ"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    
    <main>
        <div class="page-header">
            <h1>Nossos Produtos</h1>
            <p>Explore nosso vasto portfólio de soluções em vedações industriais.</p>
        </div>

        <section class="produtos-section">
            <div class="produtos-main-container">
                <aside class="sidebar-menu">
                    <h3>Categorias</h3>
                    <ul class="category-list">
                        <li>
                            <a href="produtos.php" class="<?php echo (!isset($_GET['categoria_id']) && !isset($_GET['subcategoria_id'])) ? 'active' : ''; ?>">Todos os Produtos</a>
                        </li>
                        <?php foreach ($categorias_menu_lateral as $cat): ?>
                            <li>
                                <a href="produtos.php?categoria_id=<?php echo $cat['id']; ?>"
                                   class="category-link <?php echo ($filter_category_id == $cat['id'] && !$filter_subcategory_id) ? 'active' : ''; ?>
                                        <?php echo (!empty($cat['subcategorias'])) ? 'has-subcategories' : ''; ?>"
                                   data-target-subcategories="#subcategories-<?php echo $cat['id']; ?>">
                                    <?php echo $cat['nome']; ?>
                                    <?php if (!empty($cat['subcategorias'])): ?>
                                        <span class="toggle-arrow"><i class="fas fa-chevron-down"></i></span>
                                    <?php endif; ?>
                                </a>
                                <?php if (!empty($cat['subcategorias'])): ?>
                                    <ul id="subcategories-<?php echo $cat['id']; ?>"
                                        class="subcategory-list <?php echo ($filter_category_id == $cat['id'] && $filter_subcategory_id) ? 'expanded' : ''; ?>">
                                        <?php foreach ($cat['subcategorias'] as $sub): ?>
                                            <li>
                                                <a href="produtos.php?categoria_id=<?php echo $cat['id']; ?>&subcategoria_id=<?php echo $sub['id']; ?>"
                                                   class="<?php echo ($filter_subcategory_id == $sub['id']) ? 'active' : ''; ?>">
                                                    <?php echo $sub['nome']; ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </aside>

                <div class="produtos-content">
                    <div id="lista-produtos-container">
                        <?php
                        // Iterar sobre os produtos buscados para a página atual
                        if ($result_produtos && $result_produtos->num_rows > 0) {
                            // Exibe um título da categoria/subcategoria selecionada
                            $titulo_display = "Todos os Produtos";
                            if ($filter_subcategory_id) {
                                // Encontra o nome da subcategoria
                                foreach ($categorias_menu_lateral as $cat_item) {
                                    foreach ($cat_item['subcategorias'] as $sub_item) {
                                        if ($sub_item['id'] == $filter_subcategory_id) {
                                            $titulo_display = htmlspecialchars($sub_item['nome']);
                                            break 2;
                                        }
                                    }
                                }
                            } elseif ($filter_category_id) {
                                // Encontra o nome da categoria
                                foreach ($categorias_menu_lateral as $cat_item) {
                                    if ($cat_item['id'] == $filter_category_id) {
                                        $titulo_display = htmlspecialchars($cat_item['nome']);
                                        break;
                                    }
                                }
                            }
                            echo '<h2 class="categoria-titulo texto-azul">' . $titulo_display . '</h2>';
                            echo '<div class="produtos-grid">';

                            while ($produto = $result_produtos->fetch_assoc()) {
                                // O campo 'link_imagem_principal' deve armazenar o caminho completo relativo da imagem
                                $caminho_imagem = htmlspecialchars($produto['link_imagem_principal'] ?? '');
                                $image_src = !empty($caminho_imagem) ? './' . $caminho_imagem : './img/placeholder-product.png'; // Caminho para um placeholder se a imagem não existir

                                echo '<div class="produto-card">';
                                echo '<div class="produto-imagem-container">';
                                echo '<img src="' . $image_src . '" alt="Imagem de ' . htmlspecialchars($produto['nome']) . '" class="produto-imagem">';
                                echo '</div>';
                                echo '<div class="produto-info">';
                                echo '<h3 class="produto-nome texto-azul">' . htmlspecialchars($produto['nome']) . '</h3>';
                                // Opcional: Exibe o nome da subcategoria se o produto tiver uma
                                if (!empty($produto['subcategoria_nome'])) {
                                    echo '<p class="produto-subcategoria">Subcategoria: ' . htmlspecialchars($produto['subcategoria_nome']) . '</p>';
                                }
                                echo '<p class="produto-descricao">' . htmlspecialchars($produto['descricao_curta']) . '</p>';

                                // Exibe preço ou "Sob Consulta"
                                if ($produto['preco'] === null) {
                                    echo '<p class="produto-preco">Preço: Sob Consulta</p>';
                                } else {
                                    echo '<p class="produto-preco">R$ ' . number_format($produto['preco'], 2, ',', '.') . '</p>';
                                }

                                // Exibe estoque ou "Sob Consulta"
                                if ($produto['quantidade_estoque'] === null) {
                                    echo '<p class="produto-estoque">Estoque: Sob Consulta</p>';
                                } else {
                                    echo '<p class="produto-estoque">Estoque: ' . htmlspecialchars($produto['quantidade_estoque']) . ' unidades</p>';
                                }

                                // Link para uma página de detalhes do produto, passando o SLUG
                                // CORREÇÃO: Usa o slug no link
                                echo '<a href="produto_detalhe.php?slug=' . htmlspecialchars($produto['slug']) . '" class="produto-link">Ver Mais</a>';
                                echo '</div>';
                                echo '</div>';
                            }
                            echo '</div>'; // Fecha produtos-grid
                        } else {
                            echo '<p class="no-products">Nenhum produto encontrado para os filtros selecionados.</p>';
                        }
                        ?>
                    </div>
                </div>

                <?php if ($total_paginas > 1): ?>
                    <div class="pagination">
                        <?php
                        // Constrói a base da URL para os links de paginação, mantendo os filtros
                        $base_url = 'produtos.php?';
                        if ($filter_category_id) {
                            $base_url .= 'categoria_id=' . htmlspecialchars($filter_category_id) . '&';
                        }
                        if ($filter_subcategory_id) {
                            $base_url .= 'subcategoria_id=' . htmlspecialchars($filter_subcategory_id) . '&';
                        }

                        // Link para a página anterior
                        if ($pagina_atual > 1) {
                            echo '<a href="' . $base_url . 'page=' . ($pagina_atual - 1) . '" class="page-link">&laquo; Anterior</a>';
                        }

                        // Links para as páginas
                        for ($i = 1; $i <= $total_paginas; $i++) {
                            echo '<a href="' . $base_url . 'page=' . $i . '" class="page-link ' . ($i == $pagina_atual ? 'active' : '') . '">' . $i . '</a>';
                        }

                        // Link para a próxima página
                        if ($pagina_atual < $total_paginas) {
                            echo '<a href="' . $base_url . 'page=' . ($pagina_atual + 1) . '" class="page-link">Próxima &raquo;</a>';
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
<footer class="main-footer">
    <div class="footer-top">
        <div class="footer-logo">
            <img src="./midias/imagens/logo/logo-vedavel-menu-e-footer-white.png" alt="Logo Vedavel">
            <p>Siga a Vedavel<br>nas redes sociais</p>
        </div>
        <div class="footer-social">
            <a href="https://www.instagram.com/vedavelvedacoes/" target="_blank" aria-label="Instagram"><i class="fa-brands fa-square-instagram"></i></a>
            <a href="#" target="_blank" aria-label="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>
            <a href="#" target="_blank" aria-label="YouTube"><i class="fa-brands fa-square-youtube"></i></a>
            <a href="#" target="_blank" aria-label="X"><i class="fa-brands fa-square-x-twitter"></i></a>
            <a href="https://www.facebook.com/vedacoesvedavel/" target="_blank" aria-label="Facebook"><i class="fa-brands fa-square-facebook"></i></a>
        </div>
    </div>
    <div class="footer-middle">
        <div class="footer-newsletter">
            <h3>Receba nossa newsletter</h3>
            <form>
                <input type="email" placeholder="Digite seu e-mail:">
                <button type="submit">Cadastrar</button>
            </form>
        </div>
        <nav class="footer-nav">
            <div class="nav-column"><h4>VEDAVEL</h4><ul><li><a href="#">Sustentabilidade</a></li></ul></div>
            <div class="nav-column"><h4>PRODUTOS</h4><ul><li><a href="#">Trabalhe Conosco</a></li></ul></div>
            <div class="nav-column"><h4>QUALIDADE</h4><ul><li><a href="#">Mapa do Site</a></li></ul></div>
            <div class="nav-column"><h4>CATALOGO</h4><ul><li><a href="#">Política de Dados</a></li></ul></div>
        </nav>
    </div>
    <div class="footer-bottom">
        <p>Vedavel Comercio de Vedações industriais LTDA - CNPJ: 08.369.984/0001-94</p>
        <p>Localização: Avenida Doutor Manoel Mercês, nº 21 – Bairro Mangueiral, Camaçari – BA, CEP 42803-123</p>
        <p>Copyright © 2006 - <span id="ano-atual"></span> <strong> Vedavel </strong></p>
        <p>Desenvolvido por: <a href="http://www.vtechnologie.com.br" target="_blank" rel="noopener noreferrer"><img src="./midias/imagens/logo/logo-vtechnologie-white.png" alt="Logo Vtechnologie" class="vtechnologie-logo"></a></p>
    </div>
</footer>

<div id="accessibility-widget-trigger" class="accessibility-trigger">
    <i class="fas fa-universal-access"></i>
</div>
<div id="accessibility-panel" class="accessibility-panel">
    <button class="accessibility-close-btn" aria-label="Fechar painel de acessibilidade">
        <i class="fas fa-times"></i>
    </button>
    <h3>Acessibilidade</h3>
    <div class="accessibility-option">
        <span>Tamanho da Fonte:</span>
        <button id="font-size-decrease" aria-label="Diminuir tamanho da fonte">A-</button>
        <button id="font-size-increase" aria-label="Aumentar tamanho da fonte">A+</button>
        <button id="font-size-reset" aria-label="Resetar tamanho da fonte">Padrão</button>
    </div>
    <div class="accessibility-option">
        <span>Contraste:</span>
        <button id="toggle-high-contrast" aria-label="Ativar/Desativar alto contraste">Alto Contraste</button>
    </div>
</div>

<div id="cookie-banner" class="cookie-consent-banner">
    <div class="cookie-consent-content">
        <p>Utilizamos cookies para personalizar sua experiência aqui no site, de acordo com nossa <a href="politica_cookies.php" target="_blank">política de cookies</a>.</p>
        <button id="accept-cookies-btn" class="cookie-consent-btn">Ok, entendi!</button>
    </div>
</div>
  
<script type="module" src="js/main.js"></script>

</body>
</html>