<?php
require_once "config.php";

if (isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];  // Definir o email corretamente
    $telefone = $_POST['telefone'];
    $profissao = $_POST['profissao'];
    $senha = $_POST['senha'];
    $senhahash = password_hash($senha, PASSWORD_DEFAULT);
    $telefone_limpo = preg_replace('/\D/', '', $telefone);

    // Validação de campos
    if (empty($nome) || empty($telefone) || empty($profissao) || empty($senha)) {
        echo "<script>alert('Por favor, preencha todos os campos!');</script>";
    } else if (strlen($senha) < 6) {
        echo "<script>alert('Senha muito curta!');</script>";
    } else if(!filter_var($email , FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Email não é valido!')</script>";
    } else if(!preg_match('/^\d{10,11}$/', $telefone_limpo)) {
        echo "<script>alert('Número de telefone válido!')</script>";
    } else {
        // Conexão com o banco
        $pdo = getDatabaseConnection();

        try {
            // Verifica se o email já existe
            $checkStmt = $pdo->prepare("SELECT cli_email FROM cliente WHERE cli_email = :email");
            $checkStmt->bindParam(':email', $email);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                echo "<script>alert('E-mail já cadastrado!');</script>";
            } else {
                // Inserção na tabela cliente
                $stmt = $pdo->prepare("INSERT INTO cliente (cli_nome, cli_email, cli_senha, cli_tipo) VALUES (:nome, :email, :senha, 2)");
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':senha', $senhahash);

                if ($stmt->execute()) {
                    $clienteId = $pdo->lastInsertId();

                    // Inserção na tabela funcionario
                    $stmtFuncionario = $pdo->prepare("INSERT INTO profissionais (pro_id, pro_nome, pro_email, pro_profissao, pro_telefone) VALUES (:pro_id, :nome, :email, :profissao, :telefone)");
                    $stmtFuncionario->bindParam(':pro_id', $clienteId);
                    $stmtFuncionario->bindParam(':nome', $nome);
                    $stmtFuncionario->bindParam(':email', $email);
                    $stmtFuncionario->bindParam(':profissao', $profissao);
                    $stmtFuncionario->bindParam(':telefone', $telefone_limpo);

                    if ($stmtFuncionario->execute()) {
                        echo "<script>alert('Cadastrado com sucesso!');</script>";
                        redirect("login.php");
                    } else {
                        echo "<script>alert('Erro ao cadastrar funcionário. Por favor, tente novamente.');</script>";
                    }
                } else {
                    echo "<script>alert('Erro ao cadastrar cliente.');</script>";
                }
            }
        } catch (PDOException $e) {
            echo "<script>alert('Erro ao cadastrar: " . $e->getMessage() . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?> - Cadastro do Profissional</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/funcionario.css">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="container">
        <img src="images/homem-jovem-construtor-afro-americano-usando-colete-de-construcao-e-capacete-de-seguranca-em-pe-com-os-bracos-cruzados-segurando-uma-espatula-parecendo-confiante_141793-19066.avif" class="img-fluid mb-4" alt="Imagem de Funcionário">
        <h2>Cadastro do Profissional</h2>
        <form id="funcionario-form" method="POST">
            <div class="form-group">
                <input type="text" name="nome" class="form-control" placeholder="Nome Completo" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="E-mail" required>
            </div>
            <div class="form-group">
                <input type="tel" name="telefone" class="form-control" placeholder="Telefone" required>
            </div>
            <div class="form-group">
                <input type="password" name="senha" class="form-control" placeholder="Senha" required>
            </div>
            <div class="form-group">
                <select name="profissao" class="form-control" required>
                    <option value="">Selecione a Profissão</option>
                    <option value="pedreiro">Pedreiro</option>
                    <option value="encanador">Encanador</option>
                    <option value="eletricista">Eletricista</option>
                    <option value="pintor">Pintor</option>
                </select>
            </div>
            <button type="submit" name="cadastrar" class="btn btn-primary btn-block">Cadastrar</button>
        </form>
        <div class="feedback" id="feedback-funcionario"></div>
    </div>
    <script src="JS/funcionario.js"></script>
</body>
</html>
