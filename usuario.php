<?php
require_once "config.php";

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  redirect("index.php");
  exit;
}

if (isset($_POST['cadastrar'])) {
    $nome = $_POST['usuario'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $senhahash = password_hash($senha, PASSWORD_DEFAULT);
    $data_nascimento = $_POST['data-nascimento'];

    // Validação
      if (empty($nome) || empty($email) || empty($senha) || empty($data_nascimento)) {
          echo "<script>alert('Por favor, preencha todos os campos!')</script>";
      } else if(strlen($senha) < 6) {
          echo "<script>alert('Senha muito curta!')</script>";
      } else if(!filter_var($email , FILTER_VALIDATE_EMAIL)) {
          echo "<script>alert('Email não é valido!')</script>";
      } else {
        // Converte a data
        $data_nascimento_formatada = DateTime::createFromFormat('d/m/Y', $data_nascimento);
        if ($data_nascimento_formatada === false) {
            echo "<script>alert('Data de nascimento inválida!')</script>";
        } else {
            $data_nascimento_formatada = $data_nascimento_formatada->format('Y-m-d');

            // Conexão com o banco
            $pdo = getDatabaseConnection();

            // Verifica se o e-mail já existe
            $checkStmt = $pdo->prepare("SELECT cli_email FROM cliente WHERE cli_email = :email");
            $checkStmt->bindParam(':email', $email);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                echo "<script>alert('E-mail já cadastrado!')</script>";
            } else {
                // Inserção
                try {
                    $stmt = $pdo->prepare("INSERT INTO cliente (cli_nome, cli_email, cli_senha, cli_data_nascimento, cli_tipo) VALUES (:nome, :email, :senha, :data_nascimento, 1)");
                    $stmt->bindParam(':nome', $nome);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':senha', $senhahash);
                    $stmt->bindParam(':data_nascimento', $data_nascimento_formatada);

                    if ($stmt->execute()) {
                        redirect("login.php");
                        exit(); // Interrompe a execução após redirecionamento
                    } else {
                        echo "<script>alert('Erro ao cadastrar. Por favor, tente novamente.')</script>";
                    }
                } catch (PDOException $e) {
                    echo "<script>alert('Erro ao cadastrar: " . $e->getMessage() . "')</script>";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $titulo; ?> - Cadastro de Usuário</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/usuario.css">
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
</head>
<body>
  <div class="container">
    <h2> Cadastro de Contratante</h2>
    <form id="usuario-form" method="POST">
      <div class="form-group">
        <input type="text" id="nome-usuario" name="usuario" class="form-control" placeholder="Nome Completo" required>
      </div>
      <div class="form-group">
        <input type="email" id="email-usuario" name="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="form-group">
        <input type="password" id="senha-usuario" name="senha" class="form-control" placeholder="Senha" required>
      </div>
      <div class="form-group">
        <input type="text" id="data-nascimento" name="data-nascimento" class="form-control" placeholder="Data de Nascimento (DD/MM/AAAA)" required>
      </div>
      <button type="submit" name="cadastrar" class="btn btn-primary btn-block">Cadastrar</button>
    </form>
    <div class="feedback" id="feedback-usuario"></div>
  </div>
  <script src="js/usuario.js"></script>
</body>
</html>
