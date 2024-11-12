<?php
require_once "config.php"; // Inclui a configuração do banco de dados

session_start();

// Verifica se o usuário está logado
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $cli_id = $_SESSION['cli_id']; // ID do cliente
    $cli_tipo = $_SESSION['cli_tipo']; // Tipo do cliente: "normal" ou "profissional"

    if (empty($cli_id)) {
        $mensagem = "ID do cliente não está definido na sessão.";
    } else {
        try {
            $pdo = getDatabaseConnection();

            // Consulta personalizada para cada tipo de cliente
            if ($cli_tipo === 'profissional') {
                // Busca dados de profissionais (cliente e profissionais)
                $sql = "SELECT c.cli_nome AS nome, c.cli_email AS email, p.pro_telefone AS telefone, p.pro_descricao AS descricao, pr.nome AS profissao
                        FROM cliente c
                        JOIN profissionais p ON c.cli_id = p.cli_id
                        JOIN profissoes pr ON p.profissao_id = pr.profissao_id
                        WHERE c.cli_id = ?;";
            } else {
                // Busca dados de clientes normais
                $sql = "SELECT c.cli_nome AS nome, c.cli_email AS email, p.pro_telefone AS telefone 
                        FROM cliente c
                        JOIN profissionais p ON c.cli_id = p.cli_id
                        WHERE c.cli_id = ?;";
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$cli_id]);

            // Captura os dados do cliente
            $dadosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($dadosUsuario) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $novoNome = htmlspecialchars(trim($_POST['nome']));
                    $novoEmail = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
                    $novoTelefone = htmlspecialchars(trim($_POST['telefone']));
                    
                    if ($cli_tipo === 'profissional') {
                        $novaDescricao = htmlspecialchars(trim($_POST['descricao']));
                        $novaProfissao = htmlspecialchars(trim($_POST['profissao']));
                    }

                    if (!$novoEmail) {
                        $mensagem = "O email informado é inválido.";
                    } else {
                        // Monta a atualização com base no tipo de cliente
                        if ($cli_tipo === 'profissional') {
                            $sqlUpdate = "UPDATE cliente c JOIN profissionais p ON c.cli_id = p.cli_id SET 
                                                c.cli_nome = ?, 
                                                c.cli_email = ?, 
                                                p.pro_telefone = ?,           
                                                p.pro_descricao = ?,              
                                                p.profissao_id = ?                
                                            WHERE c.cli_id = ?;";
                            $stmt = $pdo->prepare($sqlUpdate);
                            $stmt->execute([$novoNome, $novoEmail, $novoTelefone, $novaDescricao, $novaProfissao, $cli_id]);
                        } else {
                            $sqlUpdate = "UPDATE cliente SET cli_nome = ?, cli_email = ? WHERE cli_id = ?;";
                            $stmt = $pdo->prepare($sqlUpdate);
                            $stmt->execute([$novoNome, $novoEmail, $novoTelefone, $cli_id]);
                        }

                        if ($stmt->rowCount() > 0) {
                            $dadosUsuario['nome'] = $novoNome;
                            $dadosUsuario['email'] = $novoEmail;
                            $dadosUsuario['telefone'] = $novoTelefone;
                            if ($cli_tipo === 'profissional') {
                                $dadosUsuario['descricao'] = $novaDescricao;
                                $dadosUsuario['profissao'] = $novaProfissao;
                            }
                            $mensagem = "Dados atualizados com sucesso!";
                        } else {
                            $mensagem = "Nenhuma alteração detectada.";
                        }
                    }
                }
            } else {
                $mensagem = "Cliente não encontrado.";
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
    <link rel="stylesheet" href="css/perfil.css">
</head>
<body>
    <header>
        <nav class="container">
            <!-- Cabeçalho com links de navegação e outros conteúdos -->
        </nav>
    </header>

    <main>
        <section class="profile-section">
            <h2>Meu Perfil</h2>

            <?php if (isset($mensagem)): ?>
                <p class="message"><?php echo $mensagem; ?></p>
            <?php endif; ?>

            <?php if (isset($dadosUsuario) && $dadosUsuario): ?>
                <!-- Exibe os dados do cliente -->
                <div class="user-data">
                    <p><strong>Nome:</strong> <?php echo htmlspecialchars($dadosUsuario['nome']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($dadosUsuario['email']); ?></p>
                    <p><strong>Telefone:</strong> <?php echo htmlspecialchars($dadosUsuario['telefone']); ?></p>
                    <?php if ($cli_tipo === 'profissional'): ?>
                        <p><strong>Descrição:</strong> <?php echo htmlspecialchars($dadosUsuario['descricao']); ?></p>
                        <p><strong>Profissão:</strong> <?php echo htmlspecialchars($dadosUsuario['profissao']); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Formulário para editar os dados -->
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

                    <div class="form-group">
                        <label for="telefone">Telefone:</label>
                        <input type="text" name="telefone" id="telefone" value="<?php echo htmlspecialchars($dadosUsuario['telefone']); ?>">
                    </div>

                    <?php if ($cli_tipo === 'profissional'): ?>
                        <div class="form-group">
                            <label for="descricao">Descrição:</label>
                            <textarea name="descricao" id="descricao" required><?php echo htmlspecialchars($dadosUsuario['descricao']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="profissao">Profissão:</label>
                            <input type="text" name="profissao" id="profissao" value="<?php echo htmlspecialchars($dadosUsuario['profissao']); ?>" required>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn-save">Salvar Alterações</button>
                </form>
            <?php else: ?>
                <p>Cliente não encontrado.</p>
            <?php endif; ?>
        </section>
    </main>

    <script src="js/perfil.js"></script>
</body>
</html>
