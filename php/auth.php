<?php
// =========================================================
// --- php/auth.php ---
// Script para processar o login do usuário administrativo.
// =========================================================

// Inicia a sessão PHP
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once 'db_connect.php';

// Redireciona para a página de login por padrão em caso de erro
$redirect_url = '../admin/login.php';

// Verifica se a requisição é do tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $_SESSION['login_message'] = "Por favor, preencha todos os campos.";
        header('Location: ' . $redirect_url);
        exit;
    }

    // Prepara a consulta para buscar o usuário pelo nome de usuário
    $stmt = $conn->prepare("SELECT id, username, password FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verifica a senha hashada
        if (password_verify($password, $user['password'])) {
            // Login bem-sucedido
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['login_message'] = "Login realizado com sucesso!"; // Mensagem de sucesso
            // Redireciona para o novo Dashboard (admin/index.php)
            header('Location: ../admin/index.php'); // Alterado de categorias.php para index.php
            exit;
        } else {
            // Senha incorreta
            $_SESSION['login_message'] = "Nome de usuário ou senha incorretos.";
        }
    } else {
        // Nome de usuário não encontrado
        $_SESSION['login_message'] = "Nome de usuário ou senha incorretos.";
    }

    $stmt->close();
} else {
    // Se não for POST, redireciona para a página de login
    $_SESSION['login_message'] = "Acesso inválido.";
}

// Fecha a conexão com o banco de dados
$conn->close();

// Redireciona em caso de falha no login ou acesso inválido
header('Location: ' . $redirect_url);
exit;
?>