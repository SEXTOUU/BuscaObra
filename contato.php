<?php
require_once "config.php";

session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {

    $cli_id = $_SESSION['cli_id'];
    $usuario = $_SESSION['usuario'];
    $cli_tipo = $_SESSION['cli_tipo'];
    

    $imagemPerfil = obterImagemPerfil($cli_id);
} else {
    $usuario = null;
    $cliTipo = null;
}

if (isset($_POST['contato'])) {
    $nome = $_POST['name'];
    $email = $_POST['email'];
    $mensagem = $_POST['message'];
    $assunto = $_POST['subject'];
    enviar_contato($nome, $email, $mensagem, $assunto);
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?> - Contato</title>
    <link rel="shortcut icon" href="<?= $favicon ?>" type="image/x-icon">

    <link rel="stylesheet" href="css/contato.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <header>
        <nav class="containernav">
            <!-- Logo -->
            <div class="logo-box">
                <a href="index.php">
                    <img src="images/logo.jpeg" class="logo" alt="BuscaObra">
                </a>
            </div>
            <!-- Links de Navegação -->
            <div class="parent-link">
                <a href="index.php" class="social-links">HOME</a>
                <a href="contato.php" class="social-links">CONTATO</a>
                <a href="sobre.php" class="social-links">SOBRE</a>
                <a href="suporte.php" class="social-links">SUPORTE</a>
                <a href="planos.php" class="social-links">PLANOS</a>
            </div>

            <!-- Menu de Perfil do Usuário -->
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <div class="profile-menu">
                    <div class="profile-icon">
                        <!-- Verifica se a imagem do perfil existe -->
                        <?php if ($imagemPerfil && file_exists("img/" . $imagemPerfil)): ?>
                            <img src="img/<?php echo htmlspecialchars($imagemPerfil); ?>" alt="Foto do usuário">
                        <?php else: ?>
                            <img src="images/userphoto/default-avatar.png" alt="Foto do usuário">
                        <?php endif; ?>
                        <div class="notification-dot"></div> <!-- Indicador de notificação -->
                    </div>
                    <div class="profile-dropdown">
                        <!-- Verifica se a imagem do perfil existe -->
                        <?php if ($imagemPerfil && file_exists("img/" . $imagemPerfil)): ?>
                            <img src="img/<?php echo htmlspecialchars($imagemPerfil); ?>" alt="Foto do usuário">
                        <?php else: ?>
                            <img src="images/userphoto/default-avatar.png" alt="Foto do usuário">
                        <?php endif; ?>
                        <p class="welcome-message">Bem-vindo(a) de volta!</p>
                        <p>Olá, <?php echo htmlspecialchars($usuario); ?></p>
                        <hr>
                        <ul>
                            <li><a href="perfil.php"><i class="dropdown-icon bi bi-person"></i> Perfil</a></li>
                            <li><a href="#"><i class="dropdown-icon bi bi-gear"></i> Configuração</a></li>
                            <?php if ($cli_tipo === 4): ?>
                                <li><a href="admin/login.php"><i class="dropdown-icon bi bi-columns"></i> Painel de Controle</a></li>
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
                            <li><a href="cadastro.php"><i class="dropdown-icon bi bi-person-plus"></i> Cadastrar-se</a></li>
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
                <a href="www.tiktok.com" target="_blank" class="sm-icon">
                    <ion-icon name="logo-tiktok"></ion-icon>
                </a>
                <a href="https://www.youtube.com/" target="_blank" class="sm-icon">
                    <ion-icon name="logo-youtube"></ion-icon>
                </a>
            </div>
        </nav>
    </header>

    <section class="contact-section container my-5">
        <h2 class="text-center text-dark mb-4">Formulário de Contato</h2>
        <p class="text-center"><strong>Preencha o formulário abaixo e nossa equipe entrará em contato o mais breve possível.</strong></p>
        <form method="POST">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" id="name" placeholder="Digite seu Nome" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Digite seu Email Valido" required>
                </div>
            </div>
            <div class="form-group">
                <label for="message">Mensagem</label>
                <textarea class="form-control" id="message" rows="4" placeholder="Digite sua mensagem aqui..." required></textarea>
            </div>
            <div class="form-group">
                <label for="subject">Assunto</label>
                <input type="text" class="form-control" id="subject" placeholder="Assunto" required>
            </div>
            <button type="submit" name="contato" class="btn btn-primary">Enviar Mensagem</button>
        </form>
    </section>

    <section class="contact-info-section container my-5">
        <h2 class="text-center text-dark mb-4">Informações de Contato</h2>
        <p class="text-center"><strong>Se preferir, você pode entrar em contato conosco através das seguintes opções:</strong></p>
        <ul class="list-unstyled text-center">
            <li><strong>Email:</strong> suporte@buscaobra.com.br</li>
            <li><strong>Telefone:</strong> (11) 1234-5678</li>
            <li><strong>WhatsApp:</strong> (11) 98765-4321</li>
            <li><strong>Endereço:</strong> Rua da CUCA, 123, FEIRA DE SANTANA - BA, 01234-567</li>
        </ul>
    </section>

    <footer class="footer bg-dark text-white text-center py-4">
        <p>&copy; 2024 BuscaObra. Todos os direitos reservados.</p>
    </footer>

    <script src="js/index.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
