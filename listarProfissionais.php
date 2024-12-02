<?php
require_once "config.php";

session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $usuario = $_SESSION['usuario'];
    $cli_tipo = $_SESSION['cli_tipo'];
} else {
    header("Location: login.php");
    exit; 
}

$pdo = getDatabaseConnection();
$tipo_profissional = isset($_POST['tipo_profissional']) ? $_POST['tipo_profissional'] : '';

$query = "
    SELECT p.pro_id, p.pro_nome, p.pro_email, p.pro_profissao, p.pro_telefone, p.pro_descricao, 
           p.imagem,
           IFNULL(AVG(a.nota), 0) AS media_avaliacao
    FROM profissionais p
    LEFT JOIN avaliacoes a ON p.pro_id = a.profissional_id
";

if ($tipo_profissional) {
    $query .= " WHERE p.pro_profissao = :tipo_profissional";
}

$query .= " GROUP BY p.pro_id"; 

$stmt = $pdo->prepare($query);

if ($tipo_profissional) {
    $stmt->bindParam(':tipo_profissional', $tipo_profissional);
}

$stmt->execute();
$profissionais = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $titulo; ?> - Listagem de Profissionais</title>
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="css/listarProfissionais.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4">Consultar Profissionais</h2>
        
        <form method="POST" class="mb-4">
            <div class="form-group">
                <label for="tipo_profissional">Escolha o tipo de profissional</label>
                <select name="tipo_profissional" id="tipo_profissional" class="form-control" onchange="this.form.submit()">
                    <option value="">Selecione uma opção</option>
                    <option value="pintor" <?php echo ($tipo_profissional == 'pintor') ? 'selected' : ''; ?>>Pintor</option>
                    <option value="pedreiro" <?php echo ($tipo_profissional == 'pedreiro') ? 'selected' : ''; ?>>Pedreiro</option>
                    <option value="encanador" <?php echo ($tipo_profissional == 'encanador') ? 'selected' : ''; ?>>Encanador</option>
                    <option value="eletricista" <?php echo ($tipo_profissional == 'eletricista') ? 'selected' : ''; ?>>Eletricista</option>
                </select>
            </div>
        </form>

        <h3>Profissionais Encontrados</h3>
        <div class="row">
            <?php if (!empty($profissionais)): ?>
                <?php foreach ($profissionais as $profissional): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <?php
                                // Verifica se o campo 'imagem' contém o nome da imagem
                                if (!empty($profissional['imagem'])) {
                                    // Definindo o caminho completo para a imagem
                                    $imagemPath = $_SERVER['DOCUMENT_ROOT'] . '/img/' . $profissional['imagem'];
                                    
                                    // Verifica se a imagem realmente existe no diretório
                                    if (file_exists($imagemPath)) {
                                        // Se a imagem existir, exibe a imagem
                                        echo '<img class="card-img-top" src="/img/' . htmlspecialchars($profissional['imagem']) . '" alt="Imagem de ' . htmlspecialchars($profissional['pro_nome']) . '">';
                                    } else {
                                        // Se a imagem não existir, exibe a imagem padrão
                                        echo '<img src="images/userphoto/default-avatar.png" class="card-img-top" alt="Imagem de ' . htmlspecialchars($profissional['pro_nome']) . '">';
                                    }
                                } else {
                                    // Caso o campo 'imagem' esteja vazio, exibe a imagem padrão
                                    echo '<img src="images/userphoto/default-avatar.png" class="card-img-top" alt="Imagem de ' . htmlspecialchars($profissional['pro_nome']) . '">';
                                }
                                ?>
                            </div>

                                
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($profissional['pro_nome']); ?></h5>
                                <p class="card-text">
                                    <strong>Profissão:</strong> <?php echo htmlspecialchars($profissional['pro_profissao']); ?><br>
                                    <strong>Descrição:</strong> <?php echo htmlspecialchars($profissional['pro_descricao']); ?><br>
                                    <strong>Média de Avaliações:</strong> <?php echo number_format($profissional['media_avaliacao'], 1); ?> / 5
                                </p>
                                <a href="detalhes_profissional.php?id=<?php echo $profissional['pro_id']; ?>" class="btn btn-secondary">Ver mais</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                <div class="btn-container">
                    <a href="index.php" class="btn btn-primary">Voltar</a>
                </div>
                
            <?php else: ?>
                <p>Nenhum profissional encontrado para o tipo selecionado.</p>
            <?php endif; ?>
            
        </div>
    </div>
</body>
</html>
