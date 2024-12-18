<?php

require_once "config.php";

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    redirect("index.php");
    exit;
}

if (isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $profissao = $_POST['profissao'];
    $descricao = $_POST['descricao'];
    $senha = $_POST['senha'];
    $senhahash = password_hash($senha, PASSWORD_DEFAULT);
    $telefone_limpo = preg_replace('/\D/', '', $telefone);

    // Verificando se o checkbox de termos foi marcado
    if (!isset($_POST['termos'])) {
        echo "<script>alert('Você precisa concordar com os Termos de Uso.');</script>";
    } else {
        // Verifica se um arquivo de imagem foi enviado
        if (isset($_FILES["Imagem"]) && !empty($_FILES["Imagem"]["name"])) {
            $imagemTmp = $_FILES["Imagem"]["tmp_name"];
            $imagemNome = $_FILES["Imagem"]["name"];
            $imagemTipo = $_FILES["Imagem"]["type"];
            $imagemErro = $_FILES["Imagem"]["error"];
            $imagemTamanho = $_FILES["Imagem"]["size"];

            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/img')) {
                mkdir($_SERVER['DOCUMENT_ROOT'] . '/img', 0777, true);  // Cria o diretório com permissões adequadas
            }

            // Validação do tipo de imagem (apenas imagens jpg, jpeg, png são permitidas)
            $extensoesPermitidas = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($imagemTipo, $extensoesPermitidas)) {
                echo "<script>alert('Tipo de arquivo inválido. Apenas imagens JPG, JPEG e PNG são permitidas.');</script>";
                exit;
            }

            // Verifica se houve erro no envio do arquivo
            if ($imagemErro !== UPLOAD_ERR_OK) {
                echo "<script>alert('Erro no envio da imagem.');</script>";
                exit;
            }

            // Gerar um nome único para a imagem
            $imagemNomeUnico = uniqid('img_', true) . '.' . pathinfo($imagemNome, PATHINFO_EXTENSION);

            // Caminho absoluto para salvar o arquivo no servidor
            $imagemCaminhoAbsoluto = $_SERVER['DOCUMENT_ROOT'] . '/img/' . $imagemNomeUnico;

            // Move o arquivo para o diretório correto
            if (!move_uploaded_file($imagemTmp, $imagemCaminhoAbsoluto)) {
                echo "<script>alert('Erro ao salvar a imagem no servidor.');</script>";
                exit;
            }

            // Salva apenas o nome único da imagem no banco de dados
            $imagemNomeUnicoParaBanco = $imagemNomeUnico;
        } else {
            $imagemNomeUnicoParaBanco = '';  // Caso não tenha sido enviada uma imagem
        }

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
                    $stmt = $pdo->prepare("INSERT INTO cliente (cli_nome, cli_email, cli_senha, cli_tipo, imagem) VALUES (:nome, :email, :senha, :tipo, :imagem)");
                    $stmt->bindParam(':nome', $nome);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':senha', $senhahash);
                    $stmt->bindParam(':tipo', $tipoUsuario);
                    $stmt->bindParam(':imagem', $imagemNomeUnicoParaBanco); // Salva apenas o nome da imagem no banco

                    if ($stmt->execute()) {
                        $clienteId = $pdo->lastInsertId();

                        // Obter o ID da profissão
                        $profissaoStmt = $pdo->prepare("SELECT profissao_id FROM profissoes WHERE nome = :profissao");
                        $profissaoStmt->bindParam(':profissao', $profissao);
                        $profissaoStmt->execute();
                        $profissaoData = $profissaoStmt->fetch(PDO::FETCH_ASSOC);

                        if ($profissaoData) {
                            $profissaoId = $profissaoData['profissao_id'];

                            // Inserção na tabela profissionais
                            $stmtFuncionario = $pdo->prepare("INSERT INTO profissionais (cli_id, pro_nome, pro_email, profissao_id, pro_profissao, pro_telefone, pro_descricao, imagem, plano_id) VALUES (:cli_id, :nome, :email, :profissao_id, :pro_profissao, :telefone, :descricao, :imagem, 1)");
                            $stmtFuncionario->bindParam(':cli_id', $clienteId);
                            $stmtFuncionario->bindParam(':nome', $nome);
                            $stmtFuncionario->bindParam(':email', $email);
                            $stmtFuncionario->bindParam(':profissao_id', $profissaoId);
                            $stmtFuncionario->bindParam(':pro_profissao', $profissao);
                            $stmtFuncionario->bindParam(':telefone', $telefone_limpo);
                            $stmtFuncionario->bindParam(':descricao', $descricao);
                            $stmtFuncionario->bindParam(':imagem', $imagemNomeUnicoParaBanco);  // Salva apenas o nome da imagem no banco

                            if ($stmtFuncionario->execute()) {
                                echo "<script>alert('Cadastrado com sucesso!');</script>";
                                header("Location: index.php");
                                exit;
                            } else {
                                echo "<script>alert('Erro ao cadastrar profissional.');</script>";
                            }
                        } else {
                            echo "<script>alert('Profissão inválida.');</script>";
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
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro do Profissional</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/funcionario.css">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <script src="js/sweetalert2.js"></script>
</head>
<body>
    <div class="container">
        <h2>Cadastro do Profissional</h2>
        <form id="funcionario-form" method="POST" enctype="multipart/form-data">
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
                    <option value="Pedreiro">Pedreiro</option>
                    <option value="Encanador">Encanador</option>
                    <option value="Eletricista">Eletricista</option>
                    <option value="Pintor">Pintor</option>
                </select>
            </div>
            <div class="form-group">
                <label>Foto de Perfil</label>
                <input type="file" name="Imagem" class="form-control" accept="image/*">
            </div>
            <!-- Novo campo para descrição -->
            <div class="form-group">
                <textarea name="descricao" class="form-control" placeholder="Fale um pouco sobre seu trabalho" rows="4" required></textarea>
            </div>

            <div class="form-check">
                <input type="checkbox" name="termos" value="1" required>
                <label for="termos">Ao marcar essa caixa, concordo com os <a href="termos.php">Termos de Uso</a></label>
            </div>

            <button type="submit" name="cadastrar" class="btn btn-primary btn-block">Cadastrar</button>
        </form>
        <div class="feedback" id="feedback-funcionario"></div>
    </div>
</body>
</html>
