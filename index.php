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


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>

    <meta name="description" content="<?php echo $descricao; ?>">
    <meta name="keywords" content="<?php echo $meta; ?>">

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
                <a href="#" target="_blank">
                    <img src="images/logo.jpeg" class="logo" alt="BuscaObra">
                </a>
            </div>
            <!-- Links de Navegação -->
            <div class="parent-link">
                <a href="contato.html" class="social-links">CONTATO</a>
                <a href="sobre.html" class="social-links">SOBRE</a>
                <a href="suporte.html" class="social-links">SUPORTE</a>
              
            </div>

            <!-- Menu de Perfil do Usuário -->
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <div class="profile-menu">
                    <div class="profile-icon">
                    <?php if ($imagemPerfil): ?>
                        <img src="img/<?php echo htmlspecialchars($imagemPerfil); ?>" alt="Foto do usuário">
                    <?php else: ?>
                        <img src="images/userphoto/default-avatar.png" alt="Foto do usuário">
                    <?php endif; ?>
                        <div class="notification-dot"></div> <!-- Indicador de notificação -->
                    </div>
                    <div class="profile-dropdown">

                    <?php if ($imagemPerfil): ?>
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
                <a href="www.tiktok.com" target="_blank" class="sm-icon">
                    <ion-icon name="logo-tiktok"></ion-icon>
                </a>
                <a href="https://www.youtube.com/" target="_blank" class="sm-icon">
                    <ion-icon name="logo-youtube"></ion-icon>
                </a>
            </div>
        </nav>
    </header>

    <main>
        <section class="intro-section">
            <div class="hdr-container-inner">
                <h1>Bem Vindo Ao BuscaObra</h1>
                <p>
                    Conecte-se com os melhores profissionais de construção e manutenção, facilitando sua busca por pedreiros, eletricistas, encanadores e muito mais.
                </p>
                <div class="button-group">
                    <a href="listarProfissionais.php" class="btn">Buscar Profissionais</a>
                    <?php
                        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
                            echo '<a href="perfil.php" class="btn">Meu Perfil</a>';
                        } else {
                            echo '<a href="cadastro.html" class="btn">Fazer Cadastro</a>';
                        }
                    ?>
                </div>
            </div>
        </section>

        <section class="carousel-section">
            <div class="carousel-container">
                <div class="carousel-item active">
                    <img src="images/carousel/lake.jpg" alt="carousel 1">
                </div>
                <div class="carousel-item">
                    <img src="images/pessoas-em-filmagem-media-trabalhando-juntas_23-2150290079.avif" alt="carousel 2">
                </div>
                <div class="carousel-item">
                    <img src="images/pessoas-em-filmagem-media-trabalhando-juntas_23-2150290079.avif" alt="carousel 3">
                </div>
                <div class="carousel-item">
                    <img src="images/pessoas-em-filmagem-media-trabalhando-juntas_23-2150290079.avif" alt="carousel 4">
                </div>
                <div class="carousel-item">
                    <img src="images/pessoas-em-filmagem-media-trabalhando-juntas_23-2150290079.avif" alt="carousel 5">
                </div>
                <div class="carousel-item">
                    <img src="images/pessoas-em-filmagem-media-trabalhando-juntas_23-2150290079.avif" alt="carousel 6">
                </div>

                <!-- Controles do carrossel -->
                <div class="carousel-controls">
                    <button class="prev-button"><i class="fas fa-chevron-left"></i></button>
                    <button class="next-button"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>

            <!-- Indicadores de navegação -->
            <div class="carousel-indicators">
                <!-- Indicadores (as bolinhas) serão adicionadas via JavaScript -->
            </div>
        </section>

        <section>
            <div class="container">
                <div class="section-divider">
                    <div class="section-title">
                        <h2>Melhores Profissionais</h2>
                    </div>
                    <div class="card-container">
                        
                        <?php
                        // Consulta SQL para obter os melhores profissionais
                        $sql = "SELECT * FROM profissionais ORDER BY avaliacao_media DESC LIMIT 3";
                        
                        $pdo = getDatabaseConnection();
                        $result = $pdo->query($sql);

                        if ($result->rowCount() > 0) {
                            $profissionais = $result->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($profissionais as $profissional) {
                                $imagem = isset($profissional['imagem']) && file_exists("img/" . $profissional['imagem']) ? $profissional['imagem'] : 'default-avatar.png';
                                echo '<div class="card">';
                                echo '<img class="card-img-top" src="img/' . $imagem . '" alt="Foto do Profissional">';
                                echo '<h3>' . $profissional['pro_nome'] . '</h3>';
                                echo '<p>Profissão: ' . $profissional['pro_profissao'] . '</p>';
                                echo '<p>Avaliação: ' . $profissional['avaliacao_media'] . '</p>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>    
            </div>
        </section>
    </main>

    

    <div class="container-footer">
        <footer class="footer">
            <section class="social-section">
                <div class="social-text">
                    <span>Conecte-se conosco nas redes sociais:</span>
                </div>
                <div class="social-icons">
                    <a href="#" class="social-icon" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon" aria-label="Google"><i class="fab fa-google"></i></a>
                    <a href="#" class="social-icon" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                    <a href="#" class="social-icon" aria-label="GitHub"><i class="fab fa-github"></i></a>
                </div>
            </section>

            <section class="links-section">
                <div class="footer-container">
                    <div class="footer-column">
                        <h6>BuscaObra</h6>
                        <hr />
                        <p>BuscaObra e uma plataforma de busca de profissionais de construção e manutenção.</p>
                    </div>
                    <div class="footer-column">
                        <h6>Menu</h6>
                        <hr />
                        <p><a href="#!">Contato</a></p>
                        <p><a href="#!">Termos & Condições</a></p>
                        <p><a href="#!">Privacidade</a></p>
                        <p><a href="#!">Aviso de Privacidade</a></p>
                    </div>
                    <div class="footer-column">
                        <h6>Ajuda</h6>
                        <hr />
                        <p><a href="#!">Suporte</a></p>
                        <p><a href="#!">Privacidade</a></p>
                        <p><a href="#!">Termos & Condiciones</a></p>
                        <p><a href="#!">Ajuda</a></p>
                    </div>
                    <div class="footer-column">
                        <h6>Contato</h6>
                        <hr />
                        <p><i class="fas fa-home"></i> Rua das Flores, 123, Feira de Santana - BA</p>
                        <p><i class="fas fa-envelope"></i> buscaobra@gmail.com</p>
                        <p><i class="fas fa-phone"></i> + 75 9 8888 7777</p>
                        <p><i class="fas fa-print"></i> + 75 9 8888 7777</p>
                    </div>
                </div>
            </section>

            <div class="copyright">
                © 2024 Copyright:
                <a href="#">Buscaobra.com</a>
            </div>
        </footer>
    </div>

    <script src="js/index.js"></script>
    <script src="js/carousel.js"></script>
</body>

</html>