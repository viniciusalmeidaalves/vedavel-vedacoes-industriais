<?php
// =========================================================
// --- admin/logout.php ---
// Script para realizar o logout do usuário.
// =========================================================

session_start(); // Inicia a sessão

// Destrói todas as variáveis de sessão
$_SESSION = array(); //

// Se a sessão for controlada por cookies, exclui o cookie da sessão
if (ini_get("session.use_cookies")) { //
    $params = session_get_cookie_params(); //
    setcookie(session_name(), '', time() - 42000, //
        $params["path"], $params["domain"], //
        $params["secure"], $params["httponly"] //
    );
}

// Finalmente, destrói a sessão
session_destroy(); //

// Redireciona para a página de login com uma mensagem de sucesso
$_SESSION['login_message'] = "Você foi desconectado com sucesso."; //
header('Location: login.php'); //
exit; //
?>