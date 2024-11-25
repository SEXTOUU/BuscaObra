<?php
require_once "config.php";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    if (!validarToken($token)) {
        setAlert('token_invalido');
        exit;
    }

} else {
    redirect("login.php");
}

if (isset($_POST['redefinirsenha'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmarsenha = $_POST['confirmarsenha'];

    if ($senha !== $confirmarsenha) {
        setAlert('senhas_diferentes');
    } else if(strlen($senha) < 6) {
        setAlert('senha_muito_curta');
    } else {
        if(redefinirSenha($token, $senha)) {
            setAlert('senha_redefinida');
            redirect("login.php");
            exit;
        } else {
            setAlert('erro_redefinir_senha');
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?> - Redefinir Senha</title>

    <link rel="shortcut icon" href="<?= $favicon ?>" type="image/x-icon">

    <link rel="stylesheet" href="css/redefinir-senha.css">
    <script src="js/sweetalert2.js"></script>
</head>
<body>
    <?php displayAlerts(); ?>
    <div class="container">
        <h1>Redefinir Senha</h1>

        <form method="POST" class="form">

            <label for="email">Nova Senha:</label>
            <input type="password" name="senha" placeholder="Digite sua nova senha" required>

            <label for="email">Confirmar Senha:</label>
            <input type="password" name="confirmarsenha" placeholder="Confirme sua senha" required>

            <button type="submit" name="redefinirsenha">Enviar</button>
            <button><a href="login.php">Voltar</a></button>

        </form>
    </div>
</body>
</html>