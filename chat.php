<?php
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

// Conectar ao banco de dados
$conn = new mysqli("host", "usuario", "senha", "database");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Salva cada resposta no banco
$respostas = $data['respostas'];
$sql = "INSERT INTO respostas (pergunta, resposta) VALUES (?, ?)";
$stmt = $conn->prepare($sql);

foreach ($respostas as $index => $resposta) {
    $pergunta = $perguntas[$index];
    $stmt->bind_param("ss", $pergunta, $resposta);
    $stmt->execute();
}

$stmt->close();
$conn->close();

echo json_encode(["status" => "success"]);
?>
