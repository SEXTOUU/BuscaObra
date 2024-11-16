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
            <div class="logo">
                <img src="user.jpeg" alt="Logo">
                <h2>BuscaObra</h2>
            </div>

            <ul>
                <span class="title-menu">MENU</span>

                <li><a href="#" class="sidebaractive"><i
                            class="fas fa-tachometer-alt"></i>
                        Dashboard</a></li>

                <li class="dropdown">
                    <a href="#"><i class="fas fa-chart-line"></i> Relatórios <i
                            class="fas fa-chevron-down dropdown-icon"></i></a>
                    <div class="dropdown-content">
                        <a href="#">Relatório de Usuários</a>
                        <a href="#">Relatório de Avaliações</a>
                        <a href="#">Relatório Financeiro</a>
                    </div>
                </li>

                <span class="title-menu">CONFIGURAÇÕES</span>

                <li class="dropdown">
                    <a href="#"><i class="fas fa-users"></i> Profissionais <i
                            class="fas fa-chevron-down dropdown-icon"></i></a>
                    <div class="dropdown-content">
                        <a href="#">Lista de Profissionais</a>
                        <a href="#">Cadastro de Profissionais</a>
                        <a href="#">Gerenciar Destaques</a>
                        <a href="#">Solicitações de Destaque</a>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="#"><i class="fas fa-user"></i> Clientes <i
                            class="fas fa-chevron-down dropdown-icon"></i></a>
                    <div class="dropdown-content">
                        <a href="#">Lista de Clientes</a>
                        <a href="#">Favoritos e Avaliações</a>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="#"><i class="fas fa-user-shield"></i> Usuários e
                        Permissões <i
                            class="fas fa-chevron-down dropdown-icon"></i></a>
                    <div class="dropdown-content">
                        <a href="#">Gerenciamento de Administradores</a>
                        <a href="#">Permissões de Acesso</a>
                    </div>
                </li>

                <span class="title-menu">GERENCIAMENTO</span>

                <li class="dropdown">
                    <a href="#"><i class="fas fa-star"></i> Planos e Assinaturas
                        <i class="fas fa-chevron-down dropdown-icon"></i></a>
                    <div class="dropdown-content">
                        <a href="#">Gerenciamento de Planos</a>
                        <a href="#">Status das Assinaturas</a>
                        <a href="#">Relatório de Assinaturas</a>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="#"><i class="fas fa-filter"></i> Busca e Filtros <i
                            class="fas fa-chevron-down dropdown-icon"></i></a>
                    <div class="dropdown-content">
                        <a href="#">Configurar Filtros</a>
                        <a href="#">Gerenciar Categorias</a>
                        <a href="#">Configurações de Recomendações</a>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="#">
                        <i class="fas fa-comments"></i> 
                        Mensagens e Suporte <span class="badge">4</span>
                        <i class="fas fa-chevron-down dropdown-icon"></i> 
                    </a>
                    <div class="dropdown-content">
                        <a href="#">Central de Mensagens</a>
                        <a href="#">Suporte ao Cliente</a>
                        <a href="#">Suporte ao Profissional</a>
                        <a href="#">FAQ e Tutoriais</a>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="#"><i class="fas fa-thumbs-up"></i> Avaliações e
                        Feedbacks <i
                            class="fas fa-chevron-down dropdown-icon"></i></a>
                    <div class="dropdown-content">
                        <a href="#">Moderação de Avaliações</a>
                        <a href="#">Respostas de Profissionais</a>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="#"><i class="fas fa-cog"></i> Configurações <i
                            class="fas fa-chevron-down dropdown-icon"></i></a>
                    <div class="dropdown-content">
                        <a href="#">Configurações Gerais</a>
                        <a href="#">Configurações de Notificações</a>
                        <a href="#">Configurações de Segurança</a>
                        <a href="#">Configurações de Pagamento</a>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Botão de Toggle para a barra lateral em dispositivos menores -->
        <button class="sidebar-toggle">
            <i class="fas fa-bars"></i> <!-- Ícone do menu -->
        </button>

        <!-- Conteúdo Principal -->
        <main class="main-content">
            <!-- Cabeçalho -->
            <header>
                <div class="search-bar">
                    <input type="text" class="search-input" placeholder="Buscar...">
                    <button type="submit" name="search" class="search-button"><i class="fas fa-search"></i></button>
                </div>

                <div class="notification-banner">
                    <span class="notification-text">Você Tem <strong> 21</strong>
                        Notificações</span>
                    <div class="notification-icon">
                        <i class="fas fa-bell"></i>
                        <span class="notification-dot"></span>
                        
                        <!-- Indica a contagem de notificações -->
                        <div class="notification-dropdown">
                            <ul>
                                <li class="notification-item">
                                    <i class="fas fa-check-circle notification-icon"></i>
                                    Notificação 1</li>
                                <li class="notification-item unread">
                                    <i class="fas fa-exclamation-circle notification-icon"></i>
                                    Notificação 2</li>
                                <li class="notification-item">
                                    <i class="fas fa-info-circle notification-icon"></i>
                                    Notificação 3</li>
                            </ul>
                            <div class="view-more">Ver mais</div>
                        </div>
                    </div>
                </div>

                <!-- Informações do Usuário no Cabeçalho -->
                <div class="user-info">
                    <img src="user.jpeg" alt="User">
                    <span>Michelle White</span>
                    <i class="fas fa-caret-down"></i>
                    <div class="user-dropdown">
                        <ul>
                            <li><i class="fa-solid fa-user"></i> Perfil</li>
                            <li><i class="fas fa-star"></i> Planos e Assinaturas</li>
                            <hr>
                            <li href="#"><i class="fas fa-cog"></i> Configurações</li>
                            <li href="#"><i class="fas fa-question"></i> Ajuda</li>
                            <li onclick="logout()"><i class="fa-solid fa-right-to-bracket"></i> Sair</li>
                        </ul>
                    </div>
                </div>
            </header>
         
            <!-- Conteúdo -->
            <div class="content">
                <h2>Configurações</h2>
            </div>

            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-info">
                        <h2 class="card-title">Configurações Gerais</h2>
                        <p class="card-subtitle">Ajuste suas configurações gerais</p>
                    </div>
                    <div class="card-content">
                        <form action="" method="post" class="form-config">
                            <label for="delete">Titulo do Site</label>
                            <input type="text" name="Titulo" id="" placeholder="Digite o Titulo do site" required>

                            <label for="delete">URL do Site</label>
                            <input type="text" name="URL" id="" placeholder="Digite a URL do site" required>

                            <label for="delete">Tagline do Site</label>
                            <input type="text" name="tagline" id="" placeholder="Digite a Tagline do site" required>

                            <label for="delete">Favicon</label>
                            <input type="text" name="favicon" id="" placeholder="Digite o Favicon do site" required>

                            <label for="delete">Logo</label>
                            <input type="text" name="Logo" id="" placeholder="Digite o Logo do site" required>

                            <label for="delete">Thema</label>
                            <input type="text" name="Thema" id="" placeholder="Digite o Thema do site" required>

                            <label for="delete">Descricao</label>
                            <input type="text" name="descricao" id="" placeholder="Digite a Descricao do site" required>

                            <label for="delete">Meta Keywords</label>
                            <input type="text" name="meta" id="" placeholder="Digite as Meta Keywords do site" required>

                            <button type="submit" name="salvar" class="btn-delete">Salvar</button>
                        </form>
                    </div>


                </div>
                
            </div>
            
        </main>
    </div>

    <!-- Rodapé -->
    <footer class="footer">
        <p>&copy; 2024 BuscaObra. Todos os direitos reservados. <p class="version">Versão 1.0</p></p>
    </footer>

    <script src="assets/js/modal.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
