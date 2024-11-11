<?php
// config.php

// Configurações do banco de dados
$host = 'localhost';
$dbname = 'buscaobra';
$user = 'root';
$pass = '';

// DSN (Data Source Name) para a conexão PDO
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// Configurações de opções para PDO
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Exibir erros de SQL
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // Modo de busca padrão: associativo
    PDO::ATTR_EMULATE_PREPARES => false,  // Usar consultas preparadas reais (evita problemas com injeções)
];

// Função para obter a conexão com o banco de dados
function getDatabaseConnection() {
    global $dsn, $user, $pass, $options; // Acessa as variáveis globais para configuração

    try {
        // Estabelecendo a conexão PDO
        return new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        // Se ocorrer um erro na conexão, exibe a mensagem
        die("Erro de conexão: " . $e->getMessage());
    }
}

// Configurações gerais da página
$titulo = 'BuscaObra';
?>
