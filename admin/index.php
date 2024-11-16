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

    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard2.css">

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

            <div class="content">
                <h2>Painel de Controle</h2>
            </div>

            <!-- Cartões do Dashboard -->
            <div class="dashboard-cards">
                <div class="card">
                    <div class="icon-container">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <h3><?php echo clientecount(); ?></h3>
                    <p>Usuários Cadastrados</p>
                    <div class="progress-bar">
                        <div class="progress" style="width: 20%;"></div>
                    </div>
                    <p class="progress-text">20 porcento completado.</p>
                </div>
                <div class="card">
                    <div class="icon-container">
                        <i class="fas fa-cube"></i>
                    </div>
                    <h3><?php echo profissionalcount(); ?></h3>
                    <p>Profissionais</p>
                    <div class="progress-bar">
                        <div class="progress" style="width: 50%;"></div>
                    </div>
                    <p class="progress-text">60 porcento completado.</p>
                </div>
                <div class="card">
                    <div class="icon-container">
                        <i class="fas fa-arrow-down trend-icon"></i>
                    </div>
                    <h3>0</h3>
                    <p>Avaliações</p>
                    <div class="progress-bar">
                        <div class="progress" style="width: 60%;"></div>
                    </div>
                    <p class="progress-text">60 porcento completado.</p>
                </div>
                <div class="card">
                    <div class="icon-container">
                        <i class="fas fa-cube" style="color: black;"></i>
                    </div>
                    <h3>0</h3>
                    <p>Plano</p>
                    <div class="progress-bar">
                        <div class="progress" style="width: 60%;"></div>
                    </div>
                    <p class="progress-text">60 porcento completado.</p>
                </div>
            </div>
            <!-- Cartões do Dashboard -->
            <div class="dashboard-cards">
                
                <div class="card performance-card"> <!-- Adicionando a classe 'performance-card' -->
                    
                    <!-- Coluna das Legendaa -->
                    <div class="legends">
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: #1E90FF;"></span> 
                            Overall Sales 
                            <span class="legend-value">12 Millions</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: #32CD32;"></span> 
                            Overall Earnings 
                            <span class="legend-value">78 Millions</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: #FFA07A;"></span> 
                            Overall Revenue 
                            <span class="legend-value">60 Millions</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: #FF4500;"></span> 
                            New Customers 
                            <span class="legend-value">23k</span>
                        </div>
                        <button class="view-reports-btn">View Reports</button>
                    </div>
            
                    <!-- Coluna do Gráfico e Botões de Intervalo -->
                    <div class="graph-and-actions">
                        <div class="graph-container">
                            <canvas id="performanceChart"></canvas>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-interval active" onclick="changeInterval('today')">Hoje</button>
                            <button class="btn btn-interval " onclick="changeInterval('yesterday')">Ontem</button>
                            <button class="btn btn-interval" onclick="changeInterval('7days')">7 dias</button>
                            <button class="btn btn-interval" onclick="changeInterval('15days')">15 dias</button>
                            <button class="btn btn-interval" onclick="changeInterval('30days')">30 dia</button>
                        </div>
                    </div>
                </div>
            </div>
            

            <!-- Aonde Pode ser editador -->
            <!-- Tabela de Dados -->
            <div class="dashboard-cards">
                <div class="card">
                
                    <table class="data-table">
                        <caption class="data-table-caption">Tabela de Clientes</caption>
                        <thead class="data-table-header">
                            <tr class="data-table-row">
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Bairro</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody class="data-table-body">
                            <?php
                            try{
                                $pdo = getDatabaseConnection();
                                $result = $pdo->prepare("SELECT cli_id, cli_nome, cli_email, cli_bairro, cli_tipo FROM cliente LIMIT 5");
                                $result->execute();

    
                                if ($result->rowCount() > 0) {
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<tr class="data-table-row">';
                                        echo '<td>' . htmlspecialchars($row['cli_id']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['cli_nome']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['cli_email']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['cli_bairro']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['cli_tipo']) . '</td>';
                                        echo '</tr>';
                                    }          
                                } else {
                                    echo '<tr><td colspan="5">Nenhum resultado encontrado.</td></tr>';
                                }
                            } catch (PDOException $e) {
                                echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
                            }
                            ?>                         
                        </tbody>
                    </table>
                
                </div>

                <div class="card">
                    <div class="card-placeholder">
                        <h3>Notificações Recentes</h3>
                        <p>Confira as últimas notificações importantes.</p>
                    </div>
                
                    <div class="notifications-card">
                        <!-- Notificação 1 -->
                        <div class="notification-item">
                            <i class="fas fa-exclamation-circle notification-icon"></i>
                            <p class="notification-text">Atenção! Sua conta foi atualizada.</p>
                        </div>
                        
                        <!-- Notificação 2 -->
                        <div class="notification-item">
                            <i class="fas fa-info-circle notification-icon"></i>
                            <p class="notification-text">Novo recurso disponível na sua conta.</p>
                        </div>
                
                        <!-- Notificação 3 -->
                        <div class="notification-item">
                            <i class="fas fa-check-circle notification-icon"></i>
                            <p class="notification-text">Sua última tarefa foi concluída com sucesso.</p>
                        </div>
                
                        <!-- Botão para ver mais -->
                        <div class="view-more">
                            <button class="btn-view-more">Ver mais</button>
                        </div>
                    </div>
                </div>
            </div> 

            <!-- Paginação Fim -->
        </main>
    </div>

    <!-- Rodapé -->
    <footer class="footer">
        <p>&copy; 2024 BuscaObra. Todos os direitos reservados. <p class="version">Versão 1.0</p></p>
    </footer>
    
    <script src="assets/js/script.js"></script>
    <?php include 'includes/scripts.php'; ?>
</body>
</html>
