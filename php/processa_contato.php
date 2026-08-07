<?php

/**
 * Script PHP para processar o formulário de contato do site Vedavel.
 * Realiza a validação do Google reCAPTCHA, envia e-mail e possui um placeholder
 * para inserção de dados em banco de dados.
 */

// --- Configurações Importantes ---

// SUA CHAVE SECRETA do reCAPTCHA
// Esta chave é essencial para a comunicação segura com a API do Google reCAPTCHA.
// NUNCA deve ser exposta no código HTML do seu site.
// Obtenha-a no console do Google reCAPTCHA (admin.recaptcha.net) para o seu domínio.
// Substitua 'SUA_CHAVE_SECRETA_DO_RECAPTCHA_AQUI' pela sua chave secreta real!
$recaptchaSecretKey = '6LfHYnsrAAAAADTRgyz6Lb21Os3pHNoEfTBWk-Z5'; 

// Seu endereço de e-mail para onde as mensagens de contato serão enviadas
// Substitua 'seu_email@dominio.com.br' pelo seu endereço de e-mail real!
$toEmail = 'vinicius.alves@vtechnologie.com.br'; 

// Assunto padrão do e-mail que você receberá
$emailSubject = 'Nova Mensagem de Contato do Site Vedavel';

// --- Início do Processamento ---

// Verifica se a requisição é do tipo POST (ou seja, se o formulário foi enviado)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Coletar e Sanear dados do formulário
    // Usa o operador de coalescência null (??) para evitar "undefined index" se o campo não existir
    // htmlspecialchars para prevenir ataques XSS (Cross-Site Scripting)
    // trim para remover espaços em branco do início e fim
    $nome = htmlspecialchars(trim($_POST['nome'] ?? ''));
    $sobrenome = htmlspecialchars(trim($_POST['sobrenome'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $telefone = htmlspecialchars(trim($_POST['telefone'] ?? ''));
    $assunto = htmlspecialchars(trim($_POST['assunto'] ?? ''));
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? ''; // Token do reCAPTCHA

    // 2. Validação básica dos campos obrigatórios
    // Verifica se nome, e-mail e o token do reCAPTCHA estão preenchidos
    if (empty($nome)) {
        die("Erro: O campo 'Nome' é obrigatório.");
    }
    if (empty($email)) {
        die("Erro: O campo 'E-mail' é obrigatório.");
    }
    if (empty($recaptchaResponse)) {
        die("Erro: Falha na validação do reCAPTCHA. Por favor, tente novamente.");
    }

    // Valida o formato do e-mail usando FILTER_VALIDATE_EMAIL
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Erro: O formato do e-mail informado é inválido.");
    }

    // 3. Validar o reCAPTCHA no lado do servidor
    // Prepara a URL e os dados para a requisição à API do Google reCAPTCHA
    $verifyUrl = "https://www.google.com/recaptcha/api/siteverify";
    $verifyData = [
        'secret' => $recaptchaSecretKey, // Sua chave secreta
        'response' => $recaptchaResponse, // O token recebido do cliente
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '' // Opcional, mas recomendado para maior segurança: IP do usuário
    ];

    // Configurações para a requisição HTTP usando stream_context_create
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($verifyData), // Converte o array em string de query
            'timeout' => 10 // Aumenta o timeout para 10 segundos para conexões lentas
        ],
        'ssl' => [
            'verify_peer'      => true,  // Verifica o certificado SSL do Google
            'verify_peer_name' => true,  // Verifica se o nome do host corresponde ao certificado
            'cafile'           => '/path/to/cacert.pem' // Opcional: Caminho para o bundle de certificados CA (se necessário)
        ]
    ];

    $context  = stream_context_create($options);

    // Faz a requisição à API do Google reCAPTCHA
    // Removido o '@' para que erros de conexão sejam visíveis nos logs ou no navegador (durante depuração)
    $result = file_get_contents($verifyUrl, false, $context); 
    $responseKeys = json_decode($result, true); // Decodifica a resposta JSON

    // Verifica o resultado da validação do reCAPTCHA
    if ($result === FALSE || !isset($responseKeys['success']) || $responseKeys['success'] !== true) {
        // reCAPTCHA falhou ou houve um erro na comunicação com o Google
        
        $errorMessage = "Erro na verificação do reCAPTCHA. Por favor, tente novamente. Se o problema persistir, entre em contato por telefone.";
        
        // Loga a resposta completa do Google para depuração
        error_log("Falha reCAPTCHA para IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'N/A') . " - Resposta Google: " . ($result ?? 'N/A'));
        
        // Se houver códigos de erro do reCAPTCHA, adicione-os à mensagem (APENAS PARA DEBURAÇÃO, NÃO EM PRODUÇÃO)
        if (isset($responseKeys['error-codes']) && is_array($responseKeys['error-codes'])) {
            $errorMessage .= " Detalhes: " . implode(", ", $responseKeys['error-codes']);
            error_log("Códigos de erro reCAPTCHA: " . implode(", ", $responseKeys['error-codes']));
        }
        
        die($errorMessage); // Exibe o erro e encerra o script
    }

    // Se o reCAPTCHA foi validado com sucesso, prossiga com o envio do e-mail e inserção no DB

    // 4. Construir e Enviar E-mail
    $emailBody = "Nova mensagem do formulário de contato do site Vedavel:\n\n";
    $emailBody .= "Nome Completo: " . $nome . " " . $sobrenome . "\n";
    $emailBody .= "E-mail: " . $email . "\n";
    $emailBody .= "Telefone: " . ($telefone ?: 'Não informado') . "\n";
    $emailBody .= "Assunto: " . ($assunto ?: 'Não informado') . "\n";
    $emailBody .= "\n\n--- Fim da Mensagem ---";

    // Configura os cabeçalhos do e-mail
    $headers = "From: " . $nome . " <" . $email . ">\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n"; // Ajuda a evitar que o e-mail seja marcado como spam

    // Tenta enviar o e-mail usando a função mail() do PHP
    // ATENÇÃO: Para ambientes de produção, é ALTAMENTE recomendado usar uma biblioteca SMTP robusta
    // (como PHPMailer ou SwiftMailer) ou um serviço de e-mail transacional (SendGrid, Mailgun, AWS SES)
    // para maior confiabilidade, entrega e controle de erros.
    if (mail($toEmail, $emailSubject, $emailBody, $headers)) {
        // E-mail enviado com sucesso

        // 5. Inserir no Banco de Dados (Sessão Placeholder/Exemplo)
        // ESTA É UMA SEÇÃO DE EXEMPLO. VOCÊ PRECISA CONFIGURAR SUA CONEXÃO AO BANCO DE DADOS E A QUERY AQUI.
        // É CRUCIAL USAR PREPARED STATEMENTS (PDO ou MySQLi com prepare()) PARA PREVENIR SQL INJECTION.

        /*
        // Exemplo básico de conexão MySQLi (NÃO USE DIRETAMENTE EM PRODUÇÃO SEM TRATAMENTO DE ERROS ADEQUADO!)
        $dbHost = 'localhost';
        $dbUser = 'seu_usuario_db';
        $dbPass = 'sua_senha_db';
        $dbName = 'seu_banco_de_dados';

        $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

        if ($conn->connect_error) {
            error_log("Erro de conexão com o banco de dados: " . $conn->connect_error);
            // Poderia continuar mesmo sem salvar no DB, mas com erro no log
        } else {
            // Exemplo de inserção usando prepared statements (BOA PRÁTICA!)
            $stmt = $conn->prepare("INSERT INTO contatos (nome, sobrenome, email, telefone, assunto, data_envio) VALUES (?, ?, ?, ?, ?, NOW())");
            
            // 'sssss' indica que todos os parâmetros são strings
            $stmt->bind_param("sssss", $nome, $sobrenome, $email, $telefone, $assunto);

            if ($stmt->execute()) {
                error_log("Mensagem de " . $nome . " salva no DB.");
            } else {
                error_log("Erro ao salvar mensagem no DB: " . $stmt->error);
            }
            $stmt->close();
            $conn->close();
        }
        */

        // 6. Redirecionar para uma página de sucesso
        // É uma boa prática para evitar reenvio do formulário ao atualizar a página.
        header('Location: sucesso.html'); // Crie uma página sucesso.html simples
        exit(); // Garante que nenhum outro código PHP seja executado após o redirecionar
    } else {
        // Erro ao enviar e-mail pela função mail() do PHP
        error_log("Erro ao enviar e-mail para " . $toEmail . " de " . $email . ". Verifique as configurações do PHP mail().");
        
        // Redireciona para uma página de erro
        header('Location: erro.html'); // Crie uma página erro.html simples
        exit();
    }

} else {
    // Se a requisição não foi POST, alguém tentou acessar o script diretamente
    // Redireciona de volta ao formulário de contato
    header('Location: contato.html'); 
    exit();
}
?>