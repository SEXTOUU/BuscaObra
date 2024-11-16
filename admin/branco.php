<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['cli_tipo'] !== 4) {
    header("Location: login.php");
    exit;
} else {
    $usuario = $_SESSION['usuario'];
    $cli_tipo = $_SESSION['cli_tipo'];
}

$pdo = getDatabaseConnection();

$stmt = $pdo->prepare("SELECT * FROM admins WHERE cli_id = :cli_id AND status = 1");
$stmt->bindParam(':cli_id', $_SESSION['cli_id']);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    header("Location: error.php?error=not_admin");
    exit;
}

$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin['nivel_acesso'] < 2) { 
    header("Location: error.php?error=insufficient_privileges");
    exit;
}

if (isset($_POST['logout'])) {
    logout();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle - BuscaObra</title>

    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
    <div class="toggle-sidebar" id="toggle-sidebar">
        <i class="fas fa-bars"></i>
    </div>

    <div class="container">
        <!-- Barra Lateral -->
        <nav class="sidebar" id="sidebar">

    
            <?php include 'includes/sidebar.php'; ?>

        </nav>

        <!-- Botão de Toggle para a barra lateral em dispositivos menores -->
        <button class="sidebar-toggle">
            <i class="fas fa-bars"></i> <!-- Ícone do menu -->
        </button>

        <!-- Conteúdo Principal -->
        <main class="main-content">
            <!-- Cabeçalho -->

            <?php include 'includes/header.php'; ?>
            
         
            <!-- Conteúdo Principal -->
            <div class="content">
                <h2>Em Branco</h2>
            </div>

            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-info">
                        <p class="card-title">Aqui pode ser editador e adicionar conteudo</p>
                    </div>
                </div>
                
            </div>
            
        </main>
    </div>

    <!-- Rodapé -->
    <footer class="footer">
        <p>&copy; 2024 BuscaObra. Todos os direitos reservados. <p class="version">Versão 1.0</p></p>
    </footer>

    <?php include 'includes/scripts.php'; ?>
</body>
</html>
