<?php
require_once "config.php";

// Conexão com o banco de dados
$pdo = getDatabaseConnection();

// Pega os dados do formulário
$profissional_id = isset($_POST['profissional_id']) ? $_POST['profissional_id'] : '';
$nome = isset($_POST['nome']) ? $_POST['nome'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$assunto = isset($_POST['assunto']) ? $_POST['assunto'] : '';
$mensagem = isset($_POST['mensagem']) ? $_POST['mensagem'] : '';

// Consulta para pegar o e-mail do profissional
$query = "SELECT pro_email FROM profissionais WHERE pro_id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $profissional_id);
$stmt->execute();
$profissional = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o profissional existe
if ($profissional) {
    // Enviar e-mail para o profissional
    $to = $profissional['pro_email'];
    $subject = "Contato de $nome: $assunto";
    $message = "Mensagem de: $nome\nEmail: $email\n\n$mensagem";
    $headers = "From: $email";

    // Envia o e-mail
    if (mail($to, $subject, $message, $headers)) {
        echo "Mensagem enviada com sucesso!";
    } else {
        echo "Erro ao enviar a mensagem. Tente novamente.";
    }
} else {
    echo "Profissional não encontrado.";
}
?>
