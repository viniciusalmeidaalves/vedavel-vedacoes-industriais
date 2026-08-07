<?php
// =========================================================
// --- admin/index.php ---
// Painel principal (Dashboard) da área administrativa.
// =========================================================

session_start(); // Inicia a sessão

// Verifica se o usuário NÃO está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Se não estiver logado, redireciona para a página de login
    $_SESSION['login_message'] = "Você precisa fazer login para acessar esta página.";
    header('Location: login.php');
    exit; // Termina a execução do script
}

// Você pode pegar o nome de usuário da sessão para exibir na tela
$username = $_SESSION['username'] ?? 'Admin';

// Lógica para exibir e limpar mensagens de status da sessão [ATUALIZADO]
$status_message_text = null;
$status_message_type = null;
if (isset($_SESSION['admin_message'])) {
    $status_message_text = $_SESSION['admin_message']['text'];
    $status_message_type = $_SESSION['admin_message']['type'];
    unset($_SESSION['admin_message']); // Limpa a mensagem após a exibição
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Vedavel</title>
    <link rel="icon" href="../midias/icones/favicon-vedavel.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    </head>
<body>
    <?php include 'includes/admin_header.php'; ?> <div class="admin-container">
        <?php if ($status_message_text): ?>
            <div class="message <?php echo $status_message_type; ?>">
                <?php echo htmlspecialchars($status_message_text); ?>
            </div>
        <?php endif; ?>

        <p class="dashboard-greeting">Bem-vindo, <strong><?php echo htmlspecialchars($username); ?></strong>!</p>
        <h2 style="text-align: center; color: #555; margin-bottom: 30px;">Aplicações</h2>

        <div class="dashboard-apps">
            <a href="categorias.php" class="app-card">
                <i class="fas fa-sitemap"></i>
                <h3>Gerenciar Categorias</h3>
            </a>

            <a href="produtos.php" class="app-card">
                <i class="fas fa-boxes"></i>
                <h3>Gerenciar Produtos</h3>
            </a>
            </div>
    </div>
</body>
</html>