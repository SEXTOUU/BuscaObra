<?php
require_once "config.php";

// Inicia a sessão para verificar o estado do login
session_start();

// Verifica se o usuário está logado
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $usuario = $_SESSION['usuario'];
    $cli_tipo = $_SESSION['cli_tipo'];
} else {
    header("Location: login.php");
    exit;
}

// Conexão com o banco de dados
$pdo = getDatabaseConnection();

// Verifica se o parâmetro `id` está presente na URL
$profissional_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$profissional_id) {
    die("Profissional não encontrado.");
}

// Consulta para pegar as informações do profissional
$query_profissional = "
    SELECT p.pro_id, p.pro_nome, p.pro_email, p.pro_profissao, p.pro_telefone, p.pro_descricao, p.imagem  -- Incluindo o campo imagem
    FROM profissionais p
    WHERE p.pro_id = :profissional_id
";

$stmt = $pdo->prepare($query_profissional);
$stmt->bindParam(':profissional_id', $profissional_id, PDO::PARAM_INT);
$stmt->execute();
$profissional = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$profissional) {
    die("Profissional não encontrado.");
}

// Consulta para pegar as avaliações do profissional
$query_avaliacoes = "
    SELECT a.nota, a.comentario, a.data_avaliacao, c.cli_nome
    FROM avaliacoes a
    JOIN cliente c ON a.cliente_id = c.cli_id
    WHERE a.profissional_id = :profissional_id
    ORDER BY a.data_avaliacao DESC
";

$stmt_avaliacoes = $pdo->prepare($query_avaliacoes);
$stmt_avaliacoes->bindParam(':profissional_id', $profissional_id, PDO::PARAM_INT);
$stmt_avaliacoes->execute();
$avaliacoes = $stmt_avaliacoes->fetchAll(PDO::FETCH_ASSOC);

// Verifica se o formulário de avaliação foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nota = isset($_POST['nota']) ? $_POST['nota'] : null;
    $comentario = isset($_POST['comentario']) ? $_POST['comentario'] : null;

    if ($nota && $comentario) {
        if (isset($_SESSION['cli_id'])) {
            $cliente_id = $_SESSION['cli_id'];
        } else {
            die("Erro: Cliente não encontrado. Você precisa estar logado para avaliar.");
        }

        $query_insert = "
            INSERT INTO avaliacoes (profissional_id, cliente_id, nota, comentario)
            VALUES (:profissional_id, :cliente_id, :nota, :comentario)
        ";

        $stmt_insert = $pdo->prepare($query_insert);
        $stmt_insert->bindParam(':profissional_id', $profissional_id, PDO::PARAM_INT);
        $stmt_insert->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt_insert->bindParam(':nota', $nota, PDO::PARAM_INT);
        $stmt_insert->bindParam(':comentario', $comentario, PDO::PARAM_STR);
        $stmt_insert->execute();

        header("Location: detalhes_profissional.php?id=$profissional_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Profissional</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/detalhesProfissional.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Detalhes do Profissional</h2>
        <div class="card">
            <div class="card-body">
                <!-- Exibe a imagem do profissional ou a imagem padrão -->
                <?php if (!empty($profissional['imagem'])): ?>
                    <?php
                    // Definindo o caminho correto para a imagem, assumindo que as imagens estão armazenadas na pasta "img/"
                    $imagemPath = 'img/' . $profissional['imagem']; 

                    // Verifica se o arquivo da imagem realmente existe
                    if (file_exists($imagemPath)) {
                        // Se a imagem existir, exibe a imagem
                        echo '<img src="' . htmlspecialchars($imagemPath) . '" class="card-img-top mb-3 img-profissional" alt="Imagem de ' . htmlspecialchars($profissional['pro_nome']) . '">';
                    } else {
                        // Se a imagem não existir, exibe a imagem padrão
                        echo '<img src="images/userphoto/default-avatar.png" class="card-img-top mb-3 img-profissional" alt="Imagem de ' . htmlspecialchars($profissional['pro_nome']) . '">';
                    }
                    ?>
                <?php else: ?>
                    <!-- Caso não tenha imagem no banco de dados, exibe a imagem padrão -->
                    <img src="images/userphoto/default-avatar.png" class="card-img-top mb-3 img-profissional" alt="Imagem de <?php echo htmlspecialchars($profissional['pro_nome']); ?>">
                <?php endif; ?>
                
                <h5 class="card-title"><?php echo htmlspecialchars($profissional['pro_nome']); ?></h5>
                <p><strong>Profissão:</strong> <?php echo htmlspecialchars($profissional['pro_profissao']); ?></p>
                <p><strong>Descrição:</strong> <?php echo nl2br(htmlspecialchars($profissional['pro_descricao'])); ?></p>

                <p class="text-muted">Clique em um dos botões abaixo para entrar em contato com o profissional.</p>

                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?php echo urlencode($profissional['pro_email']); ?>" target="_blank" class="btn btn-primary mt-3">Contatar via Gmail</a>
                <a href="https://api.whatsapp.com/send?phone=<?php echo urlencode($profissional['pro_telefone']); ?>" target="_blank" class="btn btn-success mt-3">Contatar via WhatsApp</a>
            </div>
        </div>

        <h3 class="mt-4">Avaliações</h3>
        <?php if (!empty($avaliacoes)): ?>
            <?php foreach ($avaliacoes as $avaliacao): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($avaliacao['cli_nome']); ?></h5>
                        <p><strong>Nota:</strong> <?php echo $avaliacao['nota']; ?> / 5</p>
                        <p><strong>Comentário:</strong> <?php echo nl2br(htmlspecialchars($avaliacao['comentario'])); ?></p>
                        <p><small><?php echo $avaliacao['data_avaliacao']; ?></small></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Este profissional ainda não tem avaliações.</p>
        <?php endif; ?>

        <h3 class="mt-4">Avaliar Profissional</h3>
        <form method="POST">
            <div class="form-group">
                <label for="nota">Nota (1 a 5)</label>
                <select name="nota" id="nota" class="form-control" required>
                    <option value="">Selecione uma nota</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <div class="form-group">
                <label for="comentario">Comentário</label>
                <textarea name="comentario" id="comentario" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar Avaliação</button>
            <a href="listarProfissionais.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</body>
</html>
