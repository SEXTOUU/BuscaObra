<?php
require_once "config.php";

session_start();

session_unset();

session_destroy();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

unset($_SESSION['logged_in']);
unset($_SESSION['usuario']);
unset($_SESSION['cli_id']);
unset($_SESSION['cli_tipo']);

redirect("index.php"); 
exit;

?>
