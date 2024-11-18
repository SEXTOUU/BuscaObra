<?php
require_once "config.php"; // Inclui configuração e funções adicionais
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    setAlert('warning_invalid_data');
    header("Location: login.php");
    exit;
}

$cli_id = $_SESSION['cli_id'];
$cli_tipo = $_SESSION['cli_tipo'];
 

if (empty($cli_id)) {
    setAlert('warning_invalid_data');
    header("Location: login.php");
    exit;
}

try {
    $pdo = getDatabaseConnection();

    // Consulta para obter os dados do cliente
    $sql = ($cli_tipo === 2) ? 
        "SELECT c.cli_nome AS nome, c.cli_email AS email, p.pro_telefone AS telefone, 
                p.pro_descricao AS descricao, p.imagem AS imagem, pr.nome AS profissao
         FROM cliente c
         JOIN profissionais p ON c.cli_id = p.cli_id
         JOIN profissoes pr ON p.profissao_id = pr.profissao_id
         WHERE c.cli_id = ?;" :
        "SELECT c.cli_nome AS nome, c.cli_email AS email
         FROM cliente c
         WHERE c.cli_id = ?;";



    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cli_id]);
    $dadosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Manipulação de formulário
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $novoNome = htmlspecialchars(trim($_POST['nome']));
        $novoEmail = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        if($cli_tipo === 2) { // Profissional
            $novotelefone = htmlspecialchars(trim($_POST['telefone']));
            $novodescricao = htmlspecialchars(trim($_POST['descricao']));
        }
        
        if (!$novoEmail) {
            setAlert('warning_invalid_data');
        } else {
            // Upload de imagem, se necessário
            if ($cli_tipo === 2 && isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
                $fotoExtensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

                if (!in_array($fotoExtensao, $extensoesPermitidas)) {
                    setAlert('warning_image');
                } else {
                    $novoNomeFoto = uniqid("perfil_", true) . '.' . $fotoExtensao;
                    if (move_uploaded_file($_FILES['foto']['tmp_name'], "img/$novoNomeFoto")) {
                        $stmt = $pdo->prepare("UPDATE profissionais SET imagem = ? WHERE cli_id = ?");
                        $stmt->execute([$novoNomeFoto, $cli_id]);
                        setAlert('info_message'); // Foto atualizada com sucesso
                    } else {
                        setAlert('error_image'); // Erro ao salvar a imagem
                    }
                }
            }

            // Atualiza outros dados
            if ($cli_tipo === 2) {
                $sqlUpdate = "UPDATE cliente c
                              JOIN profissionais p ON c.cli_id = p.cli_id
                              SET c.cli_nome = ?, c.cli_email = ?, p.pro_telefone = ?, p.pro_descricao = ?
                              WHERE c.cli_id = ?";
                $stmt = $pdo->prepare($sqlUpdate);
                $stmt->execute([$novoNome, $novoEmail,  $novotelefone, $novodescricao,$cli_id]);
            } else if ($cli_tipo === 1) {
                $sqlUpdate = "UPDATE cliente SET cli_nome = ?, cli_email = ? WHERE cli_id = ?";
                $stmt = $pdo->prepare($sqlUpdate);
                $stmt->execute([$novoNome, $novoEmail, $cli_id]);
            } else {
                $sqlUpdate = "UPDATE cliente SET cli_nome = ?, cli_email = ? WHERE cli_id = ?";
                $stmt = $pdo->prepare($sqlUpdate);
                $stmt->execute([$novoNome, $novoEmail, $cli_id]);
            }

            if ($stmt->rowCount() > 0) {
                setAlert('info_message'); // Dados atualizados com sucesso
            } else {
                setAlert('info_no_change'); // Nenhuma alteração detectada
            }
        }
    }
} catch (PDOException $e) {
    setAlert('db_error');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?> - Perfil de Cliente</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="css/perfil.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css">

    <script src="js/sweetalert2.js"></script>
</head>
<body>

    <main>
        <section class="profile-section">
            <h2>Meu Perfil</h2>


            <?php if ($cli_tipo === 2): ?>
                <?php if (!empty($dadosUsuario['imagem']) && file_exists('' . $dadosUsuario['imagem'])): ?>
                    <img class="profile-picture" src="<?php echo htmlspecialchars($dadosUsuario['imagem']); ?>" alt="Imagem de <?php echo htmlspecialchars($dadosUsuario['nome']); ?>" class="profile-picture">
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
                    <p><strong>Telefone:</strong> <?php echo isset($dadosUsuario['telefone']) ? htmlspecialchars($dadosUsuario['telefone']) : 'Não informado'; ?></p>
                    <p><strong>Descrição:</strong> <?php echo htmlspecialchars($dadosUsuario['descricao']); ?></p>
                    <p><strong>Profissão:</strong> <?php echo htmlspecialchars($dadosUsuario['profissao']); ?></p>
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
                            <input type="file" name="foto" id="foto" accept=".jpg, .jpeg, .png, .gif">
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <button type="submit" class="btn-submit">Salvar alterações</button>
                    </div>
                </form>
            <?php else: ?> <!-- Exibe os dados do cliente -->
                <div class="user-data"></div>
                    <p><strong>Nome:</strong> <?php echo htmlspecialchars($dadosUsuario['nome']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($dadosUsuario['email']); ?></p>
                </div>

                <h3>Editar Dados</h3>
                <form method="POST" action="perfil.php">
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($dadosUsuario['nome']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($dadosUsuario['email']); ?>" required>
                    </div>

                    <div class="form-group"></div>
                        <button type="submit" class="btn-submit">Salvar alterações</button>
                    </div>
                </form>
            <?php endif; ?>
        </section>
    </main>

    <?php displayAlerts(); ?>
</body>
</html>
