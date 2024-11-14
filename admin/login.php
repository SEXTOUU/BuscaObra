<?php
require_once "../config.php";

session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['cli_tipo']) && $_SESSION['cli_tipo'] === 4) {
    $usuario = $_SESSION['usuario'];
    $cli_tipo = $_SESSION['cli_tipo']; 
} else {
    header("Location: error.php?error=not_admin");
    exit;
}

if (isset($_POST['login_admin'])) {
    $senha = $_POST['senha'];

    $pdo = getDatabaseConnection();

    $stmtAdmin = $pdo->prepare("SELECT * FROM admins WHERE cli_id = :cli_id");
    $stmtAdmin->bindParam(':cli_id', $_SESSION['cli_id']);
    $stmtAdmin->execute();

    if ($stmtAdmin->rowCount() > 0) {
        $admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);

        if (password_verify($senha, $admin['admin_senha'])) {
            $_SESSION['admin_departamento'] = $admin['admin_departamento'];
            $_SESSION['admin_cargo'] = $admin['admin_cargo'];

            header("Location: ../admin/index.php");
            exit;
        } else {
            header("Location: error.php?error=invalid_credentials");
            exit;
        }
    } else {
        header("Location: error.php?error=not_admin");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuscaObra - Tela de Login - Admin</title>

    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="assets/images/user.jpeg" alt="Logo">
        </div>
        <h1>Tela de Login - Admin</h1>
        <form method="POST">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" placeholder="Usuário" name="user" value="<?php echo htmlspecialchars($usuario); ?>" disabled>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" placeholder="Senha" name="senha" required>
            </div>
            <button type="submit" name="login_admin" class="login-button">Entrar</button>
        </form>
    </div>
</body>
</html>
