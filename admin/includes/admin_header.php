<?php
// =========================================================
// --- admin/includes/admin_header.php ---
// Cabeçalho da área administrativa, incluindo navegação e informações do usuário.
// =========================================================

// Verifica se a sessão já está iniciada, se não, inicia.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pega o nome de usuário da sessão (já está puxando do banco via auth.php)
$current_username = $_SESSION['username'] ?? 'Usuário';
?>
<header class="admin-top-header">
    <div class="admin-header-left">
        <a href="index.php" class="admin-logo-link">
            <span>Painel Admin</span>
        </a>
        
        <div class="admin-greeting-nav">
            <span class="user-info">Bem-vindo, <strong><?php echo htmlspecialchars($current_username); ?></strong></span>
            <nav class="admin-main-nav">
                <ul>
                    <li><a href="index.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" title="Dashboard"><i class="fas fa-tachometer-alt"></i></a></li>
                    <li><a href="categorias.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'categorias.php') ? 'active' : ''; ?>" title="Gerenciar Categorias"><i class="fas fa-sitemap"></i></a></li>
                    <li><a href="produtos.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'produtos.php') ? 'active' : ''; ?>" title="Gerenciar Produtos"><i class="fas fa-boxes"></i></a></li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="admin-header-right">
        <a href="../index.php" class="view-site-link" target="_blank"><i class="fas fa-external-link-alt"></i> Ver Site</a>
        <a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </div>
</header>