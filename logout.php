<?php
require_once "config.php";

// Inicie a sessão
session_start();

// Limpa todas as variáveis de sessão
session_unset();

// Destrói a sessão
session_destroy();

// Exclui os cookies de sessão (se existir)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redireciona para a página de login
header("Location: login.php"); // Redirecionar para login
exit;
?>
