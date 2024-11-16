<?php

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

function redirect($url) {
    header("Location: $url");
    die();
}

function logout() {
    session_start();
    session_unset();
    session_destroy();
    redirect("index.php");
    die();
}


function buscarClientes($pagina = 1, $limite = 10) {
    $offset = ($pagina - 1) * $limite;
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT cli_id, cli_nome, cli_email, cli_bairro, usertipo_nome as cargo
                           FROM cliente INNER JOIN usertipo ON cli_tipo = usertipo_id
                           LIMIT :limite OFFSET :offset");
    $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function contarClientes() {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->query("SELECT COUNT(*) FROM cliente");
    return $stmt->fetchColumn();
}

function buscarClientePorId($cli_id) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT * FROM cliente WHERE cli_id = :cli_id");
    $stmt->bindParam(':cli_id', $cli_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function deletarCliente($cli_id) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("DELETE FROM cliente WHERE cli_id = :cli_id");
    $stmt->bindParam(':cli_id', $cli_id, PDO::PARAM_INT);
    return $stmt->execute();
}

function atualizarCliente($cli_id, $nome, $email, $bairro, $tipo) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("UPDATE cliente SET cli_nome = :nome, cli_email = :email, cli_bairro = :bairro, cli_tipo = :tipo WHERE cli_id = :cli_id");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':bairro', $bairro);
    $stmt->bindParam(':tipo', $tipo, PDO::PARAM_INT);
    $stmt->bindParam(':cli_id', $cli_id, PDO::PARAM_INT);
    return $stmt->execute();
}

function adicionarCliente($nome, $email, $bairro, $tipo) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("INSERT INTO cliente (cli_nome, cli_email, cli_bairro, cli_tipo) VALUES (:nome, :email, :bairro, :tipo)");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':bairro', $bairro);
    $stmt->bindParam(':tipo', $tipo, PDO::PARAM_INT);
    return $stmt->execute();
}

function selecionarclientes() {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->query("SELECT cli_id, cli_nome, cli_email, cli_bairro, usertipo_nome as cargo FROM cliente INNER JOIN usertipo ON cli_tipo = usertipo_id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function selecionarProfissionais() {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->query("SELECT pro_id, pro_nome, pro_email, pro_profissao, profissao_nome, pro_telefone, pro_descricao FROM profissionais INNER JOIN profissoes ON profissao_id = profissao_id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getClientesPaginados($paginaAtual, $registrosPorPagina) {
    $pdo = getDatabaseConnection();
    $offset = ($paginaAtual - 1) * $registrosPorPagina;
    
    $query = "SELECT cli_id, cli_nome, cli_email, cli_bairro, usertipo_nome as cargo
              FROM cliente
              INNER JOIN usertipo ON cli_tipo = usertipo_id
              LIMIT :limite OFFSET :offset";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':limite', $registrosPorPagina, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $queryTotal = "SELECT COUNT(*) FROM cliente";
    $stmtTotal = $pdo->prepare($queryTotal);
    $stmtTotal->execute();
    $totalRegistros = $stmtTotal->fetchColumn();
    $totalPaginas = $totalRegistros > 0 ? ceil($totalRegistros / $registrosPorPagina) : 1;

    return [
        'dados' => $clientes,
        'paginaAtual' => $paginaAtual,
        'totalPaginas' => $totalPaginas
    ];
}


function getProfissionaisPaginados($paginaAtual, $registrosPorPagina) {
    $pdo = getDatabaseConnection();

    // Calcula o OFFSET para a consulta
    $offset = ($paginaAtual - 1) * $registrosPorPagina;

    // Consulta SQL para obter os dados paginados
    $query = "SELECT prof_id, prof_nome, prof_email, prof_cargo
              FROM profissionais
              LIMIT :limite OFFSET :offset";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':limite', $registrosPorPagina, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $profissionais = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Contar o total de registros
    $queryTotal = "SELECT COUNT(*) FROM profissionais";
    $stmtTotal = $pdo->prepare($queryTotal);
    $stmtTotal->execute();
    $totalRegistros = $stmtTotal->fetchColumn();

    // Calcula o total de páginas
    $totalPaginas = ($totalRegistros == 0) ? 1 : ceil($totalRegistros / $registrosPorPagina);

    return [
        'dados' => $profissionais,
        'paginaAtual' => $paginaAtual,
        'totalPaginas' => $totalPaginas
    ];
}

function clientecount() {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->query("SELECT COUNT(*) FROM cliente");
    return $stmt->fetchColumn();
}

function profissionalcount() {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->query("SELECT COUNT(*) FROM profissionais");
    return $stmt->fetchColumn();
}

function setAlert($type) {
    switch ($type) {
        case 'login_success':
            $message = "Login realizado com sucesso!";
            $alertType = "success";
            break;
        case 'db_error':
            $message = "Erro ao conectar ao banco de dados.";
            $alertType = "error";
            break;
        case 'warning_invalid_data':
            $message = "Atenção! Verifique os dados informados.";
            $alertType = "warning";
            break;
        case 'info_message':
            $message = "Este é um alerta informativo.";
            $alertType = "info";
            break;
        default:
            $message = "Alerta desconhecido.";
            $alertType = "info";
    }

    // Armazena a mensagem e o tipo de alerta na sessão
    $_SESSION['alerts'][] = ['message' => $message, 'type' => $alertType];
}


function displayAlerts() {
    if (!empty($_SESSION['alerts'])) {
        foreach ($_SESSION['alerts'] as $alert) {
            // Gerar SweetAlert usando a mensagem e o tipo do alerta
            echo "<script>
                    Swal.fire({
                        icon: '{$alert['type']}',
                        title: 'Alerta',
                        text: '{$alert['message']}',
                        showConfirmButton: true,
                        timer: 5000 // Tempo até desaparecer (opcional)
                    });
                  </script>";
        }
        unset($_SESSION['alerts']); // Limpa os alertas após a exibição
    }
}


// Função para obter a imagem de perfil do usuário
function obterImagemPerfil($cli_id) {
    try {
        // Conectar ao banco de dados
        $pdo = getDatabaseConnection();

        // Consulta para obter a imagem do usuário logado
        $sql = "SELECT p.imagem 
                FROM cliente c
                JOIN profissionais p ON c.cli_id = p.cli_id
                WHERE c.cli_id = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$cli_id]);
        $dadosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se a imagem foi encontrada, caso contrário retorna null
        return isset($dadosUsuario['imagem']) ? $dadosUsuario['imagem'] : null;

    } catch (PDOException $e) {
        // Em caso de erro, exibe uma mensagem
        echo "Erro ao buscar imagem de perfil: " . $e->getMessage();
        return null;
    }
}



?>
