<?php
require_once "config.php";

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  redirect("index.php");
  exit;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $titulo; ?> - Tela para o Cadastro</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/cadastro.css">
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
</head>
<body>
  <div class="container">
    <img src="images/jovem-artesao-construindo-uma-casa_1303-27969.avif" 
         class="img-fluid mb-4" alt="Imagem de Cadastro" id="imagem-cadastro">
    <h2 id="titulo-cadastro">Tela de Seleção do Usuário</h2>

    <form id="form-cadastro">
      <div class="form-group">
        <select id="tipo-usuario" class="form-control" required>
          <option value="">Selecione o tipo de usuário</option>
          <option value="profissional">Profissional</option>
          <option value="usuario">Contratante</option>
        </select>
      </div>
      <div id="informacoes-adicionais"></div>
      <button type="submit" class="btn btn-primary btn-block" id="btn-cadastrar">Cadastrar</button>
    </form>
    <div class="feedback" id="feedback"></div>
  </div>
  <script src="js/cadastro.js"></script>
</body>
</html>
