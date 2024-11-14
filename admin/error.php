<?php
// Inicia a sessão para capturar os parâmetros de erro da URL
session_start();

// Verifica se existe um erro na URL (via $_GET)
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Mensagens de erro baseadas nos códigos
switch ($error) {
    case 'invalid_credentials':
        $errorMessage = "Credenciais inválidas. Por favor, verifique seu nome de usuário e senha.";
        break;
    case 'not_admin':
        $errorMessage = "Você não tem permissões de administrador para acessar esta área.";
        break;
    case 'not_logged_in':
        $errorMessage = "Não você precisa estar conectado para acessar esta página.";
        break;
    case 'insufficient_privileges':
        $errorMessage = "Nível de acesso insuficiente para acessar essa página.";
        break;
    default:
        $errorMessage = "Ocorreu um erro desconhecido. Tente novamente mais tarde.";
        break;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro - BuscaObra</title>
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="error-container">
        <div class="logo">
            <img src="assets/images/user.jpeg" alt="Logo">
        </div>
        <h1>Erro - Acesso Não Permitido</h1>
        <div class="error-message">
            <p><?php echo htmlspecialchars($errorMessage); ?></p>
        </div>
        <a href="login.php" class="back-button">Voltar para o Login</a>
    </div>
</body>
</html>
