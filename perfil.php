<?php
require_once "config.php"; // Inclui a configuração do banco de dados

session_start();

displayAlerts();
// Verifica se o usuário está logado
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $cli_id = $_SESSION['cli_id'];
    $cli_tipo = $_SESSION['cli_tipo'];

    if (empty($cli_id)) {
        $mensagem = "ID do cliente não está definido na sessão.";
    } else {
        try {
            $pdo = getDatabaseConnection();

            // Consulta para obter os dados do cliente
            if ($cli_tipo === 2) { // Profissional
                $sql = "SELECT c.cli_nome AS nome, c.cli_email AS email, p.pro_telefone AS telefone, 
                               p.pro_descricao AS descricao, p.imagem AS imagem, pr.nome AS profissao
                        FROM cliente c
                        JOIN profissionais p ON c.cli_id = p.cli_id
                        JOIN profissoes pr ON p.profissao_id = pr.profissao_id
                        WHERE c.cli_id = ?;";
            } else { // Contratante
                $sql = "SELECT c.cli_nome AS nome, c.cli_email AS email
                        FROM cliente c
                        WHERE c.cli_id = ?;";
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$cli_id]);
            $dadosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $novoNome = htmlspecialchars(trim($_POST['nome']));
                $novoEmail = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
                $novoTelefone = $cli_tipo === 2 ? htmlspecialchars(trim($_POST['telefone'])) : null;

                if ($cli_tipo === 2) {
                    $novaDescricao = htmlspecialchars(trim($_POST['descricao']));
                    $novaProfissao = htmlspecialchars(trim($_POST['profissao']));;
                }

                if (!$novoEmail) {
                    $mensagem = "O email informado é inválido.";
                } else {
                    // Upload da imagem, se fornecida
                    if ($cli_tipo === 2 && isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                        $fotoTmpPath = $_FILES['foto']['tmp_name'];
                        $fotoNome = basename($_FILES['foto']['name']);
                        $fotoExtensao = strtolower(pathinfo($fotoNome, PATHINFO_EXTENSION));

                        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
                        if (!in_array($fotoExtensao, $extensoesPermitidas)) {
                            $mensagem = "Apenas imagens JPG, JPEG, PNG e GIF são permitidas.";
                        } else {
                            $novoNomeFoto = uniqid("perfil_", true) . '.' . $fotoExtensao;
                            $caminhoDestino = "img/" . $novoNomeFoto;

                            if (move_uploaded_file($fotoTmpPath, $caminhoDestino)) {
                                // Atualiza a imagem no banco
                                $sqlUpdateFoto = "UPDATE profissionais SET imagem = ? WHERE cli_id = ?";
                                $stmtFoto = $pdo->prepare($sqlUpdateFoto);
                                $stmtFoto->execute([$novoNomeFoto, $cli_id]);
                                $dadosUsuario['imagem'] = $novoNomeFoto;
                                $mensagem = "Foto de perfil atualizada com sucesso!";
                            } else {
                                $mensagem = "Erro ao salvar a foto de perfil.";
                            }
                        }
                    }

                    // Atualiza os outros dados do cliente
                    if ($cli_tipo === 2) { // Profissional
                        // Buscar o ID da profissão com base no nome
                        $sqlVerificaProfissao = "SELECT profissao_id FROM profissoes WHERE nome = ?";
                        $stmtVerifica = $pdo->prepare($sqlVerificaProfissao);
                        $stmtVerifica->execute([$novaProfissao]);
                    
                        if ($stmtVerifica->rowCount() === 0) {
                            
                            $mensagem = "A profissão selecionada não é válida.";
                        } else {
                            // Recupera o profissao_id encontrado
                            $profissaoId = $stmtVerifica->fetchColumn();
                            // Agora, realiza o update usando o ID da profissão
                            $sqlUpdate = "UPDATE cliente c
                                          JOIN profissionais p ON c.cli_id = p.cli_id 
                                          SET c.cli_nome = ?, c.cli_email = ?, p.pro_telefone = ?, 
                                              p.pro_descricao = ?, p.profissao_id = ? 
                                          WHERE c.cli_id = ?";
                            $stmt = $pdo->prepare($sqlUpdate);
                            $stmt->execute([$novoNome, $novoEmail, $novoTelefone, $novaDescricao, $profissaoId, $cli_id]);
                    
                            if ($stmt->rowCount() > 0) {
                                $dadosUsuario['nome'] = $novoNome;
                                $dadosUsuario['email'] = $novoEmail;
                                $dadosUsuario['telefone'] = $novoTelefone;
                                $dadosUsuario['descricao'] = $novaDescricao;
                                $dadosUsuario['profissao'] = $novaProfissao;
                                $mensagem = "Dados atualizados com sucesso!";
                            } else {
                                $mensagem = "Nenhuma alteração detectada.";                         
                            }
                        }
                    } else { // Contratante
                        $sqlUpdate = "UPDATE cliente SET cli_nome = ?, cli_email = ? WHERE cli_id = ?";
                        $stmt = $pdo->prepare($sqlUpdate);
                        $stmt->execute([$novoNome, $novoEmail, $cli_id]);
                    
                        if ($stmt->rowCount() > 0) {
                            $dadosUsuario['nome'] = $novoNome;
                            $dadosUsuario['email'] = $novoEmail;
                            $mensagem = "Dados atualizados com sucesso!";
                        } else {
                            $mensagem = "Nenhuma alteração detectada.";
                        }
                    }   
                }
            }
        } catch (PDOException $e) {
            $mensagem = "Erro ao buscar dados do cliente: " . $e->getMessage();
        }
    }
} else {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Cliente</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/perfil.css">

    <script src="js/sweetalert2.js"></script>
</head>
<body>
    <main>
        <section class="profile-section">
            <h2>Meu Perfil</h2>

            <?php if (isset($mensagem)): ?>
                <p class="message"><?php echo $mensagem; ?></p>
            <?php endif; ?>

            <?php if ($cli_tipo === 2): ?>
                <?php if (!empty($dadosUsuario['imagem']) && file_exists('img/' . $dadosUsuario['imagem'])): ?>
                    <img class="profile-picture" src="img/<?php echo htmlspecialchars($dadosUsuario['imagem']); ?>" alt="Imagem de <?php echo htmlspecialchars($dadosUsuario['nome']); ?>" class="profile-picture">
                <?php else: ?>
                    <div class="default-profile-picture">
                        <img class="profile-picture" src="images/userphoto/default-avatar.png" alt="Imagem padrão" class="profile-picture">
                        <p>Sem imagem de perfil</p>
                    </div>

                <?php endif; ?>


                <!-- Exibe os dados do cliente -->
                <div class="user-data">
                    <p><strong>Nome:</strong> <?php echo htmlspecialchars($dadosUsuario['nome']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($dadosUsuario['email']); ?></p>
                    <?php if ($cli_tipo === 2): ?>
                        <p><strong>Telefone:</strong> <?php echo isset($dadosUsuario['telefone']) ? htmlspecialchars($dadosUsuario['telefone']) : 'Não informado'; ?></p>
                        <p><strong>Descrição:</strong> <?php echo htmlspecialchars($dadosUsuario['descricao']); ?></p>
                        <p><strong>Profissão:</strong> <?php echo htmlspecialchars($dadosUsuario['profissao']); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Formulário para editar os dados -->
                <h3>Editar Dados</h3>
                <form method="POST" action="perfil.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($dadosUsuario['nome']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($dadosUsuario['email']); ?>" required>
                    </div>

                    <?php if ($cli_tipo === 2): ?>
                        <div class="form-group">
                            <label for="telefone">Telefone:</label>
                            <input type="text" name="telefone" id="telefone" value="<?php echo htmlspecialchars($dadosUsuario['telefone']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="descricao">Descrição:</label>
                            <textarea name="descricao" id="descricao" required><?php echo htmlspecialchars($dadosUsuario['descricao']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="profissao">Profissão:</label>
                            <input type="text" name="profissao" id="profissao" value="<?php echo htmlspecialchars($dadosUsuario['profissao']); ?>" required>
                        </div>
                    <?php endif; ?>

                    <!-- Apenas permitir upload de imagem para profissionais -->
                    <?php if ($cli_tipo === 2): ?>
                        <div class="form-group">
                            <label for="foto">Foto de perfil:</label>
                            <input type="file" name="foto" id="foto">
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <button type="submit" class="btn-submit">Salvar alterações</button>
                    </div>
                </form>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
