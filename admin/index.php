<?php
session_start();
require_once "../config.php";

$admin = verificarAcesso();

$usuario = $admin['cli_nome'];
$usuario_id = $admin['cli_id'];
$notificacoes = obterNotificacoes($usuario_id);
$quantidade_notificacoes = count($notificacoes);

$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$registrosPorPagina = 10;

$resultado = getClientesPaginados($paginaAtual, $registrosPorPagina);
$clientes = $resultado['dados'];
$totalPaginas = $resultado['totalPaginas'];

$cliente = null; 

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

            <?php if(empty($_GET['page'])): ?>
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

            <?php endif; ?>

        </main>
    </div>

    <?php        
        if (empty($_GET['page'])) {
        // Nada
        } else {
        switch ($_GET['page']) {
            case 'listaprofissionais':
                require_once 'pages/listaprofissionais.php';
                break;

            case 'listaclientes':
                require_once 'pages/listaclientes.php';
                break;

            case 'gerenciamento_admins':
                require_once 'pages/gerenciamento_admins.php';
                break;

            case 'gerenciamento_planos':
                require_once 'pages/gerenciamento_planos.php';
                break;

            case 'gerenciar_destaques':
                require_once 'pages/gerenciar_destaques.php';
                break;

            case 'relatorio_usuarios':
                require_once 'pages/relatorio_usuarios.php';
                break;
            
            case 'configurar_filtros':
                require_once 'pages/configurar_filtros.php';
                break;
            
            case 'favoritos_avaliacoes':
                require_once 'pages/favoritos_avaliacoes.php';
                break;

            case 'moderacao_avaliacoes':
                require_once 'pages/moderacao_avaliacoes.php';
                break;

            case 'configuracoes_gerais':
                require_once 'pages/configuracoes_gerais.php';
                break;

            case 'configuracoes_seguranca':
                require_once 'pages/configuracoes_seguranca.php';
                break;

            case 'categorias':
                require_once 'pages/gerenciar_categorias.php'; // Nome da página para gerenciar categorias
                break;

            case 'filtros':
                require_once 'pages/configurar_filtros.php'; // Nome da página para configurar filtros
                break;

            default:
                include '404.php';
                break;
        }
        }

        
    ?>

    <!-- Rodapé -->
    <footer class="footer">
        <p>&copy; 2024 BuscaObra. Todos os direitos reservados. <p class="version">Versão 1.0</p></p>
    </footer>
    
    <script src="assets/js/script.js"></script>
    <?php include 'includes/scripts.php'; ?>
</body>
</html>
