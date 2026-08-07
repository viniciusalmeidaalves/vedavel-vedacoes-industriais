<?php
// =========================================================
// --- politica_cookies.php ---
// Página dedicada à Política de Cookies da Vedavel.
// =========================================================

// Inclui o cabeçalho dinâmico que estabelece a conexão com o BD e o menu.
include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Cookies - Vedavel</title>
    <meta name="description" content="Saiba como a Vedavel utiliza cookies para melhorar sua experiência em nosso site. Leia nossa política de cookies completa.">
    <meta name="keywords" content="política de cookies, cookies, privacidade de dados, LGPD, Vedavel, como usamos cookies">
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
    <link rel="stylesheet" href="./css/politica-cookies.css"> 
    <link rel="stylesheet" href="./css/cookie-consent.css"> 
    <link rel="stylesheet" href="./css/accessibility.css">
    <script src="https://kit.fontawesome.com/dc52f1179d.js" crossorigin="anonymous"></script>
</head>
<body>

    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NVG6L5RZ"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    
    <div class="page-header section-header-cookies">
        <h1>Política de Cookies</h1>
        <p>Entenda como utilizamos cookies para melhorar sua navegação.</p>
    </div>

    <main class="container politica-cookies-content">
        <h2>O que são Cookies?</h2>
        <p>Cookies são pequenos arquivos de texto que são armazenados no seu computador ou dispositivo móvel quando você visita um site. Eles são amplamente utilizados para fazer com que os sites funcionem, ou funcionem de forma mais eficiente, além de fornecer informações aos proprietários do site.</p>

        <h2>Como a Vedavel Utiliza Cookies?</h2>
        <p>Utilizamos cookies por diversas razões detalhadas abaixo. Infelizmente, na maioria dos casos, não há opções padrão da indústria para desativar cookies sem desativar completamente a funcionalidade e os recursos que eles adicionam a este site. É recomendável que você deixe todos os cookies ativados se não tiver certeza se precisa deles ou não, caso sejam usados para fornecer um serviço que você utiliza.</p>

        <h3>Categorias de Cookies Utilizados:</h3>
        <ul>
            <li><strong>Cookies Essenciais:</strong> Necessários para que o site funcione corretamente. Isso inclui cookies que permitem que você navegue pelo site e use seus recursos.</li>
            <li><strong>Cookies de Desempenho:</strong> Coletam informações sobre como você usa nosso site, como as páginas que você visita e se você encontra algum erro. Esses cookies não coletam informações que o identifiquem, sendo todos anônimos. Eles são usados apenas para nos ajudar a melhorar o funcionamento do site.</li>
            <li><strong>Cookies de Funcionalidade:</strong> Permitem que o site se lembre das escolhas que você faz (como seu nome de usuário, idioma ou região) e forneçam recursos aprimorados e mais personalizados.</li>
            <li><strong>Cookies de Publicidade/Rastreamento:</strong> Usados para fornecer anúncios mais relevantes para você e seus interesses. Eles também são usados para limitar o número de vezes que você vê um anúncio, bem como para ajudar a medir a eficácia de uma campanha publicitária.</li>
        </ul>

        <h2>Tipos de Cookies:</h2>
        <ul>
            <li><strong>Cookies de Sessão:</strong> São temporários e permanecem no seu dispositivo até que você saia do site ou feche o navegador.</li>
            <li><strong>Cookies Persistentes:</strong> Permanecem no seu dispositivo por um período prolongado ou até que você os exclua manualmente.</li>
            <li><strong>Cookies de Primeira Parte:</strong> São definidos pelo site que você está visitando (no caso, Vedavel).</li>
            <li><strong>Cookies de Terceira Parte:</strong> São definidos por um domínio diferente do site que você está visitando. Podem ser, por exemplo, de serviços como Google Analytics ou redes sociais.</li>
        </ul>

        <h2>Gerenciando Seus Cookies:</h2>
        <p>Você tem o direito de decidir se aceita ou rejeita cookies. Você pode exercer suas preferências de cookies ajustando as configurações do seu navegador da web para aceitar ou recusar cookies. Para isso, consulte a seção de "Ajuda" do seu navegador para obter mais informações sobre como gerenciar as configurações de cookies. Esteja ciente de que desabilitar os cookies pode afetar a funcionalidade deste e de muitos outros sites que você visita. Portanto, é recomendável que você não desabilite os cookies.</p>

        <p>Para mais informações sobre como os dados pessoais são tratados, consulte nossa <a href="#" target="_blank">Política de Privacidade</a>.</p>

        <p>Se você tiver alguma dúvida sobre nossa política de cookies, entre em contato conosco através da nossa <a href="contato.php">página de contato</a>.</p>
    </main>

    <div id="cookie-banner" class="cookie-consent-banner">
        <div class="cookie-consent-content">
            <p>Utilizamos cookies para personalizar sua experiência aqui no site, de acordo com nossa <a href="politica_cookies.php" target="_blank">política de cookies</a>.</p>
            <button id="accept-cookies-btn" class="cookie-consent-btn">Ok, entendi!</button>
        </div>
    </div>
    
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
                <div class="nav-column"><h4>CATALOGO</h4><ul><li><a href="politica_cookies.php">Política de Dados</a></li></ul></div> </nav>
        </div>
        <div class="footer-bottom">
            <p>Vedavel Comercio de Vedações industriais LTDA - CNPJ: 08.369.984/0001-94</p>
            <p>Localização: Avenida Doutor Manoel Mercês, nº 21 – Bairro Mangueiral, Camaçari – BA, CEP 42803-123</p>
            <p>Copyright © 2006 - <span id="ano-atual"></span> <strong> Vedavel </strong></p>
            <p>Desenvolvido por: <a href="http://www.vtechnologie.com.br" target="_blank" rel="noopener noreferrer"><img src="./midias/imagens/logo/logo-vtechnologie-white.png" alt="Logo Vtechnologie" class="vtechnologie-logo"></a></p>
        </div>

        <div class="accessibility-option">
    <span>Contraste:</span>
    <button id="toggle-high-contrast" aria-label="Ativar/Desativar alto contraste">Alto Contraste</button>
</div>
</div>

    </footer>
    <a href="#" class="back-to-top-button"><i class="fa-solid fa-arrow-up"></i></a>
    <script type="module" src="js/main.js"></script>
</body>
</html>