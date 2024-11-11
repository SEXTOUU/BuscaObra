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
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuscaObra - Perfil do Usuário</title>  
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <!-- Cabeçalho e Navegação -->
    <header>
        <nav class="container">
            <!-- Logo -->
            <div class="logo-box">
                <a href="index.php">
                    <img src="images/logo.jpeg" class="logo" alt="BuscaObra">
                </a>
            </div>

            <!-- Links de Navegação -->
            <div class="parent-link">
                <a href="contato.php" class="social-links">CONTATO</a>
                <a href="sobre.php" class="social-links">SOBRE</a>
                <a href="perfil.php" class="social-links">perfil</a>
                <a href="suporte.php" class="social-links">SUPORTE</a>
                <a href="#" class="social-links">XXXXX</a>
            </div>
                  
            <!-- Menu de Perfil do Usuário -->
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <div class="profile-menu">
                    <div class="profile-icon">
                        <img src="images/user-profile.jpeg" alt="Foto do usuário">
                        <div class="notification-dot"></div>
                    </div>
                    <div class="profile-dropdown">
                        <img src="images/user-profile.jpeg" alt="Foto do usuário">
                        <p class="welcome-message">Bem-vindo(a) de volta!</p>
                        <p>Olá, <?php echo htmlspecialchars($usuario); ?></p>
                        <hr>    
                        <ul>
                            <li><a href="perfil.php"><i class="dropdown-icon bi bi-person"></i> Perfil</a></li>
                            <li><a href="#"><i class="dropdown-icon bi bi-gear"></i> Configuração</a></li>
                            <?php if ($cli_tipo === 4): ?>
                                <li><a href="#"><i class="dropdown-icon bi bi-columns"></i> Painel de Controle</a></li>
                            <?php endif; ?>
                            <li><a href="#"><i class="dropdown-icon bi bi-question-circle"></i> Ajuda</a></li>
                            <hr>
                            <li><a href="logout.php"><i class="dropdown-icon bi bi-box-arrow-right"></i> Sair</a></li>
                        </ul>
                    </div>
                </div>
            <?php else: ?>
                <!-- Se o usuário não estiver logado, exibe opções de login e cadastro -->
                <div class="profile-menu">
                    <div class="profile-icon">
                        <img src="images/user-profile.jpeg" alt="Foto do usuário">
                        <div class="notification-dot"></div>
                    </div>
                    <div class="profile-dropdown">
                        <img src="images/user-profile.jpeg" alt="Foto do usuário">
                        <p>Seja Bem Vindo, <br>faça o Login ou o Registro</p>
                        <hr>
                        <ul>
                            <li><a href="login.php"><i class="dropdown-icon bi bi-box-arrow-in-right"></i> Login</a></li>
                            <li><a href="cadastro.html"><i class="dropdown-icon bi bi-person-plus"></i> Cadastrar-se</a></li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Ícones de Redes Sociais -->
            <div class="icon">
                <a href="https://l.instagram.com/" target="_blank" class="sm-icon">
                    <ion-icon name="logo-instagram"></ion-icon>
                </a>
                <a href="https://facebook.com" target="_blank" class="sm-icon">
                    <ion-icon name="logo-facebook"></ion-icon>
                </a>
                <a href="https://github.com" target="_blank" class="sm-icon">
                    <ion-icon name="logo-github"></ion-icon>
                </a>
                <a href="https://www.tiktok.com" target="_blank" class="sm-icon">
                    <ion-icon name="logo-tiktok"></ion-icon>
                </a>
                <a href="https://www.youtube.com/" target="_blank" class="sm-icon">
                    <ion-icon name="logo-youtube"></ion-icon>
                </a>
            </div>
        </nav>

        <!-- Texto do Cabeçalho -->
        <div class="header-container">
            <div class="hdr-container-inner">
                <h1>Bem Vindo Ao nosso site BuscaObra</h1>
                <p>
                    Conecte-se com os melhores profissionais de construção e manutenção, facilitando sua busca por pedreiros, eletricistas, encanadores e muito mais. 
                </p>
                <div class="button-group">
                    <a href="listarProfissionais.php" class="btn">Buscar Profissionais</a>
                    <a href="cadastro.html" class="btn">Fazer Cadastro</a>
                </div>
            </div>
        </div>
    </header>

    <script src="js/index.js"></script>
</body>
</html>
