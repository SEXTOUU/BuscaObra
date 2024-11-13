<?php

function redirect($url) {
    header("Location: $url");
    die();
}

function getDatabaseConnection() {
    global $host, $dbname, $user, $pass;

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;

    } catch (PDOException $e) {
        echo "Erro na conexão com o banco de dados: " . $e->getMessage() . "<br/>";
        die();
    }
}


?>
