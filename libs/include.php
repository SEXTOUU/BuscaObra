<?php

// autoloader.php
function myAutoloader($class) {
    $baseDir = realpath(__DIR__ . '/../'); // Caminho até a raiz do projeto
    $class = str_replace('\\', '/', $class); // Converte as barras invertidas para barras normais
    $file = $baseDir . '/libs/' . $class . '.php'; // Caminho do arquivo da classe

    if (file_exists($file)) {
        require_once $file; // Inclui o arquivo
    }
}

spl_autoload_register('myAutoloader');

?>
