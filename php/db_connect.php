<?php

/**
 * Arquivo de conexão com o banco de dados.
 * Define as credenciais de acesso e estabelece a conexão MySQLi.
 */

// --- Configurações do Banco de Dados ---


// Host do banco de dados (geralmente 'localhost' para XAMPP)
// define('DB_SERVER', 'localhost');
// define('DB_USERNAME', 'root');
// define('DB_PASSWORD', '');
// define('DB_NAME', 'vedavel_db');


// Host do banco de dados (para Locaweb)
 define('DB_SERVER', 'vedavel_db.mysql.dbaas.com.br');
 define('DB_USERNAME', 'vedavel_db');
 define('DB_PASSWORD', 'B1b2b3b4@db');
 define('DB_NAME', 'vedavel_db');

// --- Tentar Conexão com o Banco de Dados ---
// Cria uma nova instância de mysqli
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verifica a conexão
if ($conn->connect_error) {
    // Se houver um erro na conexão, exibe uma mensagem e encerra o script
    die("Erro de conexão com o banco de dados: " . $conn->connect_error);
}

// Define o conjunto de caracteres para UTF-8 para garantir a correta exibição de caracteres especiais
// Isso é crucial para evitar problemas com acentuação e caracteres como ç
$conn->set_charset("utf8mb4");

// A conexão ($conn) agora está disponível para ser usada em outros arquivos que incluírem este.
// REMOVIDO: A linha $conn->close(); NÃO deve estar aqui.
?>