<?php
require_once "config.php";

if (isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $profissao = $_POST['profissao'];
    $descricao = $_POST['descricao'];
    $senha = $_POST['senha'];
    $senhahash = password_hash($senha, PASSWORD_DEFAULT);
    $telefone_limpo = preg_replace('/\D/', '', $telefone);

    // Define o tipo de usuário como "profissional" (2) 
    $tipoUsuario = 2;

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
            // Verifica se o tipo de usuário é válido
            $checkTipoStmt = $pdo->prepare("SELECT usertipo_id FROM usertipo WHERE usertipo_id = :tipo");
            $checkTipoStmt->bindParam(':tipo', $tipoUsuario);
            $checkTipoStmt->execute();

            if ($checkTipoStmt->rowCount() > 0) {
                // Inserção na tabela cliente
                $stmt = $pdo->prepare("INSERT INTO cliente (cli_nome, cli_email, cli_senha, cli_tipo) VALUES (:nome, :email, :senha, :tipo)");
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':senha', $senhahash);
                $stmt->bindParam(':tipo', $tipoUsuario);

                if ($stmt->execute()) {
                    $clienteId = $pdo->lastInsertId();

                    // Inserção na tabela profissionais, incluindo o campo de descrição
                    $stmtFuncionario = $pdo->prepare("INSERT INTO profissionais (cli_id, pro_profissao, pro_telefone, pro_descricao) VALUES (:cli_id, :profissao, :telefone, :descricao)");
                    $stmtFuncionario->bindParam(':cli_id', $clienteId);
                    $stmtFuncionario->bindParam(':profissao', $profissao);
                    $stmtFuncionario->bindParam(':telefone', $telefone_limpo);
                    $stmtFuncionario->bindParam(':descricao', $descricao);

                    if ($stmtFuncionario->execute()) {
                        echo "<script>alert('Cadastrado com sucesso!');</script>";
                        header("Location: index.php"); // Redireciona para a página de login
                        exit;
                    } else {
                        echo "<script>alert('Erro ao cadastrar profissional.');</script>";
                    }
                } else {
                    echo "<script>alert('Erro ao cadastrar cliente.');</script>";
                }
            } else {
                echo "<script>alert('Tipo de usuário inválido.');</script>";
            }
        }
    } catch (PDOException $e) {
        echo "<script>alert('Erro ao cadastrar: " . $e->getMessage() . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro do Profissional</title>
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
            <!-- Novo campo para descrição -->
            <div class="form-group">
                <textarea name="descricao" class="form-control" placeholder="Fale um pouco sobre seu trabalho" rows="4" required></textarea>
            </div>
            <button type="submit" name="cadastrar" class="btn btn-primary btn-block">Cadastrar</button>
        </form>
        <div class="feedback" id="feedback-funcionario"></div>
    </div>
</body>
</html>