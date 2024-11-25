<?php
require_once "config.php";

if(isset($_POST['recuperarsenha'])) {
  $email = $_POST['email'];
  recuperarSenha($email);
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $titulo; ?> - Esqueceu a Senha</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/usuario.css">
  <link rel="stylesheet" href="css/recuperarsenha.css">
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
  <script src="js/sweetalert2.js"></script>
</head>
<body>
  <?php displayAlerts(); ?>
  <div class="container">
    <h2> Redifinição de Senha</h2>
    <form id="usuario-form" method="POST">
      <div class="form-group">
        <p>Informer seu email para receber o link de redefinição</p>
      </div>
      <div class="form-group">
        <input type="email" id="email-usuario" name="email" class="form-control" placeholder="Email" required>
      </div>
      <button type="submit" name="recuperarsenha" class="btn btn-primary btn-block">Enviar Link de Recuperação</button>
      <button class="btn-link"><a href="login.php">Voltar</a></button>
    </form>
    <div class="feedback" id="feedback-usuario"></div>
  </div>
  <script src="js/usuario.js"></script>
</body>
</html>
