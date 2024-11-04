<?php

require_once "config.php";

if (isset($_POST['login'])) {

  $usuario = $_POST['usuario'];
  $senha = $_POST['senha'];

  if (empty($usuario) || empty($senha)) {
    echo "<script>alert('Por favor, preencha todos os campos!')</script>";
  } else {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT * FROM cliente WHERE cli_nome = :usuario");
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();

    // Verifica se o usuário foi encontrado no banco de dados
    if ($stmt->rowCount() > 0) {
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      // Verifica a senha usando password_verify
      if (password_verify($senha, $user['cli_senha'])) {
        session_start();
        $_SESSION['usuario'] = $usuario;
        $_SESSION['cli_tipo'] = $user['cli_tipo'];
        $_SESSION['logged_in'] = true;
        redirect("index.php");
        exit();
      } else {
        echo "<script>alert('Credenciais inválidas')</script>";
      }
    } else {
      echo "<script>alert('Usuário ou senha inválidos!')</script>";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $titulo; ?> - Tela de Login</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/login.css">
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
</head>
<body>
  <div class="container">
    <h1 class="text-center" id="titulo">Bem-vindo ao Sistema</h1>
    <img src="images/logo.jpeg" class="img-fluid mb-4" alt="Imagem de Boas-Vindas">

    <form id="login-form" method="POST">
      <div class="form-group">
        <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Usuário" required>
      </div>
      <div class="form-group">
        <input type="password" id="senha" name="senha" class="form-control" placeholder="Senha" required>
      </div>
      <button type="submit" name="login" class="btn btn-success btn-block" id="btn-login">Login</button>
    </form>

    <p class="t">Não tem uma conta? <a href="cadastro.html" id="link-cadastro">Cadastre-se</a></p>
    <div class="feedback" id="feedback-login"></div>
  </div>
  <script src="js/login.js"></script>
</body>
</html>
