<?php
require_once "config.php";

session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {

    $cli_id = $_SESSION['cli_id'];
    $usuario = $_SESSION['usuario'];
    $cli_tipo = $_SESSION['cli_tipo'];
    

    $imagemPerfil = obterImagemPerfil($cli_id);

    if($imagemPerfil) {
        $imagemExibir = "/img/" . htmlspecialchars($imagemPerfil);
    } else {
        $imagemExibir = "/images/userphoto/default-avatar.png";
    }
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

    <script src="js/sweetalert2.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body>
    <!-- Cabeçalho e Navegação -->
    <header class="header">
        <div class="container-header-top">
            <a class="logo" href="index.php">
                <img src="images/logo.jpeg" alt="logo">
            </a>

            <div class="perfil-box">
                <?php if (!isset($_SESSION['logged_in'])) : ?>
                    <div class="perfil">
                        <img src="images/userphoto/default-avatar.png" alt="perfil">
                        <p class="welcome-message">Faça <a href="login.php">login</a> ou <a href="cadastro.php">cadastre-se</a></p>
                    </div>
                <?php else: ?>
                    <div class="perfil">
                        <?php 

                        if(empty($imagemExibir)) {
                            $imagemExibir = "/images/userphoto/default-avatar.png";
                        } else {
                            $imagemExibir = '/img/' . htmlspecialchars($imagemPerfil);
                        }
                        ?>
                        <img src="<?= $imagemExibir; ?>" alt="perfil">
                        <p class="welcome-message">Bem-vindo, <?= htmlspecialchars($usuario); ?>!</p>
                        <div class="dropdown">
                            <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <ion-icon name="caret-down-outline"></ion-icon>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="listarProfissionais.php">Buscar Profissionais</a>
                                <a class="dropdown-item" href="perfil.php">Perfil</a>
                                <a class="dropdown-item" href="logout.php">Sair</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <div class="slider">  
            <div class="camera_wrap">
                <img src="images/picture1.jpg" alt="">
            </div>
        </div>

        <nav class="navbar">
            <div class="container-nav">
                <ul class="nav-links">
                    <li ><a class="active" href="index.php">Inicio</a></li>
                    <li><a href="sobre.php">Sobre</a></li>
                    <li><a href="suporte.php">Suporte</a></li>
                    <li><a href="contato.php">Contato</a></li>
                    <li><a href="planos.php">Planos</a></li>
                </ul>
            </div>
        </nav>
    </header>

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
                        echo '<a href="cadastro.php" class="btn">Fazer Cadastro</a>';
                    }
                ?>
            </div>
        </div>
    </section>

    <main class="main-container">
        <section class="carousel-section">
            <div class="carousel-container">
                <div class="carousel-item active">
                    <img src="images/carousel/picture1.jpg" alt="carousel 1">
                </div>
                <div class="carousel-item">
                    <img src="images/carousel/picture2.jpg" alt="carousel 2">
                </div>
                <div class="carousel-item">
                    <img src="images/carousel/picture3.jpg" alt="carousel 3">
                </div>
                <div class="carousel-item">
                    <img src="images/carousel/picture4.jpg" alt="carousel 4">
                </div>
                <div class="carousel-item">
                    <img src="images/carousel/picture5.jpg" alt="carousel 5">
                </div>

                <!-- Controles do carrossel -->
                <div class="carousel-controls">
                    <button class="prev-button"><i class="fas fa-chevron-left"></i></button>
                    <button class="next-button"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </section>

        <section class="professionals-section">
            <div class="professionals-container">
                <div class="professionals-divider">
                    <div class="professionals-title">
                        <h2>Profissionais mais avaliados</h2>
                    </div>
                    <div class="profissionals-cards">   
                        <?php
                            $sql = "SELECT * FROM profissionais ORDER BY avaliacao_media DESC LIMIT 3";
                            $pdo = getDatabaseConnection();
                            $result = $pdo->query($sql);

                            if ($result->rowCount() > 0) {
                                $profissionais = $result->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($profissionais as $profissional) {
                                    $imagem = isset($profissional['imagem']) && file_exists("img/" . $profissional['imagem']) ? "img/" . $profissional['imagem'] : 'images/userphoto/default-avatar.png';

                                    echo '<div class="card">';
                                    echo '<img class="card-img-top" src="' . $imagem . '" alt="Foto do Profissional">';
                                    echo '<h3>' . $profissional['pro_nome'] . '</h3>';
                                    echo '<p>Profissão: ' . $profissional['pro_profissao'] . '</p>';
                                    echo '<p>Avaliação: ' . $profissional['avaliacao_media'] . '</p>';
                                    echo '</div>';
                                    echo '<hr>';
                                }
                            }
                        ?>
                    </div>
                </div>    
            </div>
        </section>
    </main>
    
    <footer class="footer">
        <div class="container-footer">
            <section class="social-section">
                <div class="social-text">
                    <span>Conecte-se conosco nas redes sociais:</span>
                </div>
                <div class="social-icons">
                    <a href="https://facebook.com" class="social-icon" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com" class="social-icon" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="https://instagram.com" class="social-icon" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://linkedin.com" class="social-icon" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
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
                        <p><a href="contato.php">Contato</a></p>
                        <p><a href="sobre.php">Sobre Nós</a></p>
                        <p><a href="blog.php">Blog</a></p>
                        <p><a href="carreiras.php">Carreiras</a></p>
                        <p><a href="mapa-do-site.php">Mapa do Site</a></p>
                    </div>
                    <div class="footer-column">
                        <h6>Ajuda</h6>
                        <hr />
                        <p><a href="suporte.php">Suporte</a></p>
                        <p><a href="faq.php">Perguntas Frequentes (FAQ)</a></p>
                        <p><a href="como-funciona.php">Como Funciona</a></p>
                        <p><a href="politica-de-devolucao.php">Política de Devolução</a></p>
                        <p><a href="contato.php">Fale Conosco</a></p>
                    </div>
                    <div class="footer-column">
                        <h6>Informações Legais</h6>
                        <hr />
                        <p><a href="termos.php">Termos & Condições</a></p>
                        <p><a href="privacidade.php">Política de Privacidade</a></p>
                        <p><a href="aviso-de-privacidade.php">Aviso de Privacidade</a></p>
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
                <a href="index.php">Buscaobra.com</a>
            </div>
        </div>
    </footer>
    

    <?php displayAlerts(); ?>
    <script src="js/index.js"></script>
    <script src="js/carousel.js"></script>
</body>

</html>