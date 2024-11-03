<?php

require_once "config.php";


// Inicie a sessão
session_start();

// Limpa todas as variáveis de sessão
session_unset();

// Destrói a sessão
session_destroy();

// Redireciona para a página de login ou inicial
header("Location: index.php"); // ou "index.php"
exit;


?>