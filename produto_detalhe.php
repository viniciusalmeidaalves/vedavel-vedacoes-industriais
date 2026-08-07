<?php
// =========================================================
// --- produto_detalhe.php ---
// Página para exibir os detalhes de um produto específico.
// Adaptada para o projeto Vedavel.
// =========================================================

// A conexão com o banco de dados ($conn) é estabelecida via includes/header.php
// e deve permanecer aberta até o final do script.

// Removido: require_once './php/db_connect.php'; aqui, pois será incluído no header.php.

// ALTEARAÇÃO: Pega o SLUG do produto da URL, não mais o ID
$product_slug = $_GET['slug'] ?? null; 
$product = null; // Variável para armazenar os dados do produto

// Inclui o cabeçalho dinâmico que estabelece a conexão com o BD
// É importante que esta inclusão venha antes de qualquer uso de $conn
include 'includes/header.php';

// Se um SLUG de produto válido for fornecido, busca os detalhes do produto
// ALTERAÇÃO: Consulta por slug em vez de id
if ($product_slug && isset($conn) && $conn instanceof mysqli) { // Garante que $conn está disponível e é um objeto mysqli
    $stmt = $conn->prepare("SELECT p.*, c.nome AS categoria_nome, s.nome AS subcategoria_nome
                            FROM produtos p
                            JOIN categorias c ON p.categoria_id = c.id
                            LEFT JOIN subcategorias s ON p.subcategoria_id = s.id
                            WHERE p.slug = ?"); // ALTERAÇÃO: Busca pelo slug
    $stmt->bind_param("s", $product_slug); // ALTERAÇÃO: 's' para string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $product = $result->fetch_assoc();
    }
    $stmt->close();
}

// Se o produto não for encontrado, redireciona para a página de produtos
if (!$product) {
    // Redireciona para produtos.php se o produto não for encontrado ou SLUG inválido
    header('Location: produtos.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['seo_titulo'] ?? $product['nome'] . ' - Vedavel'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($product['seo_descricao'] ?? ($product['descricao_curta'] . ' | Soluções em vedações industriais da Vedavel.')); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($product['seo_palavras_chave'] ?? ($product['nome'] . ', vedações industriais, ' . $product['categoria_nome'] . ', ' . ($product['subcategoria_nome'] ?? '') . ', Vedavel')); ?>">
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
    
    <div class="page-header">
        <h1>Detalhes do Produto</h1>
        <p>Conheça a fundo o produto: <?php echo htmlspecialchars($product['nome']); ?></p>
    </div>

    <section class="product-detail-section container">
        <div class="product-detail-container">
            <div class="product-image-gallery">
                <?php
                $main_image_src = htmlspecialchars($product['link_imagem_principal'] ?? '');
                $image_display_src = !empty($main_image_src) ? './' . $main_image_src : './img/placeholder-product.png';
                ?>
                <img src="<?php echo $image_display_src; ?>" alt="Imagem de <?php echo htmlspecialchars($product['nome']); ?>" class="product-main-image">
                </div>
            <div class="product-info">
                <h1><?php echo htmlspecialchars($product['nome']); ?></h1>
                <?php if ($product['preco'] === null): ?>
                    <p class="price">Preço: Sob Consulta</p>
                <?php else: ?>
                    <p class="price">R$ <?php echo number_format($product['preco'], 2, ',', '.'); ?></p>
                <?php endif; ?>

                <p class="short-description"><?php echo htmlspecialchars($product['descricao_curta']); ?></p>
                <div class="full-description">
                    <h4>Descrição Completa:</h4>
                    <p><?php echo nl2br(htmlspecialchars($product['descricao_completa'])); ?></p>
                </div>

                <div class="product-meta">
                    <div class="product-meta-item">
                        <strong>Categoria:</strong>
                        <span><?php echo htmlspecialchars($product['categoria_nome']); ?></span>
                    </div>
                    <?php if (!empty($product['subcategoria_nome'])): ?>
                    <div class="product-meta-item">
                        <strong>Subcategoria:</strong>
                        <span><?php echo htmlspecialchars($product['subcategoria_nome']); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="product-meta-item">
                        <strong>SKU:</strong>
                        <span><?php echo htmlspecialchars($product['sku']); ?></span>
                    </div>
                    <?php if (!empty($product['marca'])): ?>
                    <div class="product-meta-item">
                        <strong>Marca:</strong>
                        <span><?php echo htmlspecialchars($product['marca']); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($product['gtin'])): ?>
                    <div class="product-meta-item">
                        <strong>GTIN:</strong>
                        <span><?php echo htmlspecialchars($product['gtin']); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($product['mpn'])): ?>
                    <div class="product-meta-item">
                        <strong>MPN:</strong>
                        <span><?php echo htmlspecialchars($product['mpn']); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="product-meta-item">
                        <strong>Medidas:</strong>
                        <span><?php echo htmlspecialchars($product['medidas'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="product-meta-item">
                        <strong>Peso:</strong>
                        <span><?php htmlspecialchars($product['peso'] ?? 'N/A'); ?> <?php echo htmlspecialchars($product['unidade_peso']); ?></span>
                    </div>
                    <div class="product-meta-item">
                        <strong>Estoque:</strong>
                        <?php if ($product['quantidade_estoque'] === null): ?>
                            <span>Sob Consulta</span>
                        <?php else: ?>
                            <span><?php echo htmlspecialchars($product['quantidade_estoque']); ?> unidades</span>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="https://wa.me/5571991150648?text=Ol%C3%A1! Tenho interesse no produto: <?php echo urlencode($product['nome']); ?> (SKU: <?php echo urlencode($product['sku']); ?>). Poderíamos conversar sobre ele?" class="contact-product-btn" target="_blank">
                    <i class="fab fa-whatsapp"></i> Fale Conosco sobre este Produto
                </a>
            </div>
        </div>
    </section>
    
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

    <a href="#" class="back-to-top-button"><i class="fa-solid fa-arrow-up"></i></a>

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

    <?php
    // Fechar a conexão com o banco de dados no final do script principal
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
    ?>
</body>
</html>