<?php

// Definindo as configurações do banco de dados como constantes
define('DB_HOST', 'localhost'); // Substitua pelo host do banco de dados
define('DB_NAME', 'buscaobra'); // Substitua pelo nome do banco de dados
define('DB_USER', 'root');      // Substitua pelo usuário do banco de dados
define('DB_PASS', '');          // Substitua pela senha do banco de dados

// MercadoPago - Token de Acesso (substitua pelo seu token)
define('MP_ACCESS_TOKEN', 'SEU TOKEN'); // Substitua pelo seu token

// URL para notificação (exemplo, pode ser personalizada)
define('MP_NOTIFICATION_URL', 'http://buscaobra.000.pe/retorno.php');

// Configurações de e-mail
define('SMTP_HOST', 'smtp.gmail.com');    // Substitua pelo host do servidor SMTP
define('SMTP_USER', 'x5Y9r@example.com');        // Substitua pelo nome de usuário do servidor SMTP
define('SMTP_PASS', 'sua_senha');        // Substitua pela senha do servidor SMTP
define('SMTP_PORT', 587);                   // Substitua pela porta SMTP (geralmente 587)

define('BASE_URL', 'http://buscaobra.000.pe/');


// Configurações Gerais
$titulo = 'BuscaObra';
$descricao = 'BuscaObra e uma plataforma de busca de profissionais de construção e manutenção.';
$meta = 'buscaobra obras construção manutenção trabalhadores civis';
$favicon = 'images/favicon.ico';
$logo = 'images/logo.png';
$baseUrl = "http://buscaobra.000.pe/";

// Inclui o arquivo de funções
require_once "function.php";
?>
