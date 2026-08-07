<?php
// =========================================================
// --- admin/login.php ---
// Página de formulário de login para a área administrativa.
// =========================================================

// Inicia a sessão PHP (necessário para mensagens de erro/sucesso)
session_start();

// Redireciona se o usuário já estiver logado
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php'); // Redireciona para o Dashboard agora
    exit;
}

$message_text = null;
$message_type = null;
if (isset($_SESSION['login_message'])) {
    if (is_array($_SESSION['login_message']) && isset($_SESSION['login_message']['text'])) {
        $message_text = $_SESSION['login_message']['text'];
        $message_type = $_SESSION['login_message']['type'] ?? 'error';
    } else {
        $message_text = $_SESSION['login_message'];
        $message_type = 'error';
    }
    unset($_SESSION['login_message']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Admin Vedavel</title>
    <link rel="icon" href="../midias/icones/favicon-vedavel.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    </head>
<body class="login-page-body"> <div class="login-wrapper"> <div class="login-image-section">
            <div class="portal-content">
                <h2>Portal de Aplicações</h2>
                <p>Bem-vindo ao seu portal personalizado.</p>
                </div>
        </div>
        <div class="login-form-section">
            <div class="login-container">
                <img src="../midias/imagens/logo/logo-vedavel-menu-e-footer-blue.png" alt="Logo Vedavel" class="login-logo">
                
                <h2>Log In - Seu Portal</h2>
                <?php if ($message_text): ?>
                    <div class="message <?php echo htmlspecialchars($message_type); ?>">
                        <?php echo htmlspecialchars($message_text); ?>
                    </div>
                <?php endif; ?>
                <form action="../php/auth.php" method="POST">
                    <div class="form-group">
                        <label for="username">Nome de Usuário:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="remember-me">
                        <input type="checkbox" id="remember_me" name="remember_me">
                        <label for="remember_me">Lembrar-me</label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-login">Entrar</button>
                    </div>
                    <div class="register-link">
                        Não tem uma conta? <a href="#">Registre-se aqui.</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>