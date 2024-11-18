<?php

// Definindo as configurações do banco de dados como constantes
define('DB_HOST', 'localhost'); // Substitua pelo host do banco de dados
define('DB_NAME', 'buscaobra'); // Substitua pelo nome do banco de dados
define('DB_USER', 'root');      // Substitua pelo usuário do banco de dados
define('DB_PASS', '');          // Substitua pela senha do banco de dados

// MercadoPago - Token de Acesso (substitua pelo seu token)
define('MP_ACCESS_TOKEN', 'TOKEN DO MERCADOPAGO'); // Substitua pelo seu token

// URL para notificação (exemplo, pode ser personalizada)
define('MP_NOTIFICATION_URL', 'http://127.0.0.1/retorno.php');

// Configurações Gerais
$titulo = 'BuscaObra';
$descricao = 'BuscaObra e uma plataforma de busca de profissionais de construção e manutenção.';
$meta = 'buscaobra obras construção manutenção trabalhadores civis';
$favicon = 'images/favicon.ico';
$logo = 'images/logo.png';
$baseUrl = "http://127.0.0.1";

// Inclui o arquivo de funções
require_once "function.php";
?>
