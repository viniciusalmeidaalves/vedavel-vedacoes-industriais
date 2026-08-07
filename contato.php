<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vedavel - Contato</title>
    <meta name="description" content="Entre em contato com a Vedavel para atendimento personalizado, suporte técnico e informações sobre nossos produtos.">
    <meta name="keywords" content="contato Vedavel, atendimento Vedavel, suporte técnico, fale conosco, SAC Vedavel, ouvidoria Vedavel, informações de contato, telefone Vedavel, e-mail Vedavel, formulário de contato, localização Vedavel, endereço Vedavel, política de privacidade, dúvidas, sugestões, reclamações, orçamento Vedavel, atendimento ao cliente, assistência técnica, vedações industriais, produtos Vedavel">
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

    <script src="./js/scripts.js"></script>
    <script src="https://kit.fontawesome.com/dc52f1179d.js" crossorigin="anonymous"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NVG6L5RZ"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    
    <?php include 'includes/header.php'; // Inclui o cabeçalho dinâmico ?>

    <div class="page-header">
        <h1>Entre em Contato</h1>
        <p>Estamos aqui para ajudar! Preencha o formulário abaixo e entraremos em contato o mais breve possível.</p>
    </div>

    <section id="contato" class="contato-section">
        <div class="container">
            <div class="contato-wrapper">
                <div class="contato-form">
                    <h3>Fale Conosco</h3>
                    <form id="form-contato" action="php/processa_contato.php" method="POST">
                        <div class="form-group">
                            <label for="nome">Nome:</label>
                            <input type="text" id="nome" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label for="sobrenome">Sobrenome:</label>
                            <input type="text" id="sobrenome" name="sobrenome" required>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail:</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="telefone">Telefone:</label>
                            <input type="tel" id="telefone" name="telefone">
                        </div>
                        <div class="form-group">
                            <label for="assunto">Assunto:</label>
                            <input type="text" id="assunto" name="assunto">
                        </div>
                        <div class="form-group">
                            <div class="g-recaptcha" data-sitekey="6LcLbXsrAAAAAHValW8Nev-SH-cd-wHY2OmVKaJB"></div>
                        </div>
                        <button type="submit">Enviar Mensagem</button>
                    </form>
                </div>
                
                <div class="contato-info">
                    <h3>Informações de Contato</h3>
                    <div class="info-item">
                        <h4>SAC</h4>
                        <p> <a href="tel:+557133014722">71 3301-4722</a> <p> (Atendimento de segunda a sexta, das 8h às 17h)</p>
                    </div>
                    <div class="info-item">
                        <h4>Ouvidoria</h4>
                        <p> <a href="tel:+557133014722">71 3301-4722</a> <p> (Canal independente para sugestões, elogios ou reclamações)</p>
                    </div>
                    <div class="info-item">
                        <h4>Dados Pessoais</h4>
                        <p>Acesse nossa <a href="https://www.seusite.com.br/politica-de-privacidade" target="_blank">Política de Privacidade</a> para saber como tratamos suas informações.
                        Para solicitações sobre dados pessoais, envie um e-mail para: <a href="mailto:vedavel@vedavel.com.br.br">vedavel@vedavel.com.br</a></p></p>
                    </div>
                </div>
            </div>
            
            <div class="mapa-google">
                <h3>Localização da Empresa</h3>
                <iframe 
                src="https://www.google.com/maps/embed?pb=!4v1744405672840!6m8!1m7!1s6z6RWJ3tj0f3FZLE0EBrfw!2m2!1d-12.68964077354629!2d-38.33106500938979!3f82!4f0!5f0.7820865974627469" 
                width="100%" 
                height="450" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
            
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
                <a href="https://www.instagram.com/vedavelvedacoes/" target="_blank" aria-label="Instagram">
                    <i class="fa-brands fa-square-instagram"></i>
                </a>

                <a href="#" target="_blank" aria-label="LinkedIn">
                    <i class="fa-brands fa-linkedin"></i>
                </a>

                <a href="#" target="_blank" aria-label="YouTube">
                    <i class="fa-brands fa-square-youtube"></i>
                </a>

                <a href="#" target="_blank" aria-label="X">
                    <i class="fa-brands fa-square-x-twitter"></i>
                </a>

                <a href="https://www.facebook.com/vedacoesvedavel/" target="_blank" aria-label="Facebook">
                    <i class="fa-brands fa-square-facebook"></i>
                </a>
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
                <div class="nav-column">
                    <h4>VEDAVEL</h4>
                    <ul>
                        <li><a href="#">Sustentabilidade</a></li>
                    </ul>
                </div>
                <div class="nav-column">
                    <h4>PRODUTOS</h4>
                    <ul>
                        <li><a href="#">Trabalhe Conosco</a></li>
                    </ul>
                </div>
                <div class="nav-column">
                    <h4>CATALOGO</h4>
                    <ul>
                        <li><a href="#">Mapa do Site</a></li>
                    </ul>
                </div>
                <div class="nav-column">
                    <h4>CONTATO</h4>
                    <ul>
                        <li><a href="#">Política de Dados</a></li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="footer-bottom">
            <p> Vedavel Comercio de Vedações industriais LTDA - CNPJ: 08.369.984/0001-94 <p>Localização: Avenida Doutor Manoel Mercês, nº 21 – Bairro Mangueiral, Camaçari – BA, CEP 42803-123 <p> Copyright © 2006 - <span id="ano-atual"></span> <strong> Vedavel </strong></p>
            <p>Desenvolvido por: <a href="http://www.vtechnologie.com.br" target="_blank" rel="noopener noreferrer">
                <img src="./midias/imagens/logo/logo-vtechnologie-white.png" alt="Logo Vtechnologie" class="vtechnologie-logo">
            </a></p>
        </div>
        </footer>

    <a href="#" class="back-to-top-button">
        <i class="fa-solid fa-arrow-up"></i>
    </a>

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