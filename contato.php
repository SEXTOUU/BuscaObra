<?php

require_once "config.php";

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
</head>
<body>
    <header class="header text-center">
        <h1>Contato</h1>
        <p class="lead">Entre em contato conosco!</p>
    </header>

    <section class="contact-section container my-5">
        <h2 class="text-center text-dark mb-4">Formulário de Contato</h2>
        <p class="text-center">Preencha o formulário abaixo e nossa equipe entrará em contato o mais breve possível.</p>
        <form method="POST">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" id="name" placeholder="Seu Nome" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Seu Email" required>
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
        <p class="text-center">Se preferir, você pode entrar em contato conosco através das seguintes opções:</p>
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
