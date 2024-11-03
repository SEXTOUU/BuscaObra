<?php
require_once "config.php";

if (isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $profissao = $_POST['profissao'];
    $senha = $_POST['senha'];
    $senhahash = password_hash($senha, PASSWORD_DEFAULT);

    // Validação de campos
    if (empty($nome) || empty($telefone) || empty($profissao) || empty($senha)) {
        echo "<script>alert('Por favor, preencha todos os campos!');</script>";
    } elseif (strlen($senha) < 6) {
        echo "<script>alert('Senha muito curta!');</script>";
    } else {
        // Conexão com o banco
        $pdo = getDatabaseConnection();

        try {
            // Verifica se o telefone já existe
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
                    $stmtFuncionario = $pdo->prepare("INSERT INTO funcionario (fun_id, fun_profissao, fun_telefone) VALUES (:fun_id, :profissao, :telefone)");
                    $stmtFuncionario->bindParam(':fun_id', $clienteId);
                    $stmtFuncionario->bindParam(':profissao', $profissao);
                    $stmtFuncionario->bindParam(':telefone', $telefone);

                    if ($stmtFuncionario->execute()) {
                        echo "<script>alert('Funcionário cadastrado com sucesso!');</script>";
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
    <title>Cadastro de Funcionário - BuscaObra</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/funcionario.css">
    <link rel="shortcut icon" href="IMAGENS/favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="container">
        <img src="IMAGENS/homem-jovem-construtor-afro-americano-usando-colete-de-construcao-e-capacete-de-seguranca-em-pe-com-os-bracos-cruzados-segurando-uma-espatula-parecendo-confiante_141793-19066.avif" class="img-fluid mb-4" alt="Imagem de Funcionário">
        <h2>Cadastro de Funcionário</h2>
        <form id="funcionario-form" method="POST">
            <div class="form-group">
                <input type="text" name="nome" class="form-control" placeholder="Nome Completo" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="E-mail" required>
            </div>
            <div class="form-group">
                <input type="text" name="telefone" class="form-control" placeholder="Telefone" required>
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
