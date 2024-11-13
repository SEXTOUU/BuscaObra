<?php
require_once "config.php";

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {

    $usuario = $_SESSION['usuario'];
    $cli_tipo = $_SESSION['cli_tipo'];
} else {
    $usuario = null;
    $cliTipo = null;
}

// Conexão com o banco de dados
$pdo = getDatabaseConnection();

// Pega o id do profissional da URL
$profissional_id = isset($_GET['id']) ? $_GET['id'] : '';

// Consulta para pegar os dados do profissional específico
$query = "SELECT pro_nome, pro_email FROM profissionais WHERE pro_id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $profissional_id);
$stmt->execute();
$profissional = $stmt->fetch(PDO::FETCH_ASSOC);

// Se não encontrar o profissional, redireciona para a lista de profissionais
if (!$profissional) {
    header("Location: listarProfissionais.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contatar Profissional</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/contato.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Entre em Contato com <?php echo htmlspecialchars($profissional['pro_nome']); ?></h2>

        <form action="enviar_contato.php" method="POST">
            <!-- Passa o id do profissional para o processamento -->
            <input type="hidden" name="profissional_id" value="<?php echo $profissional_id; ?>">

            <div class="form-group">
                <label for="nome">Seu Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>

            <div class="form-group">
                <label for="email">Seu E-mail:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="assunto">Assunto:</label>
                <input type="text" class="form-control" id="assunto" name="assunto">
            </div>

            <div class="form-group">
                <label for="mensagem">Mensagem:</label>
                <textarea class="form-control" id="mensagem" name="mensagem" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Enviar</button>
            <button type="reset" class="btn btn-danger">Cancelar</button>
        </form>
    </div>
</body>
</html>
