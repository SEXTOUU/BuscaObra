<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['cli_tipo'] !== 4) {
    header("Location: login.php");
    exit;
} else {
    $usuario = $_SESSION['usuario'];
    $cli_tipo = $_SESSION['cli_tipo'];
}

$pdo = getDatabaseConnection();

$stmt = $pdo->prepare("SELECT * FROM admins WHERE cli_id = :cli_id AND status = 1");
$stmt->bindParam(':cli_id', $_SESSION['cli_id']);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    header("Location: error.php?error=not_admin");
    exit;
}

$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin['nivel_acesso'] < 2) { 
    header("Location: error.php?error=insufficient_privileges");
    exit;
}

if (isset($_POST['delete-salvar'])) {
    $cli_id = $_POST['cli_id'];
    deletarCliente($cli_id); // Função para excluir o cliente
    redirect("admin/listacliente.php");
}

// Configura a página atual e o número de registros por página
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$registrosPorPagina = 10;

// Obter os dados paginados usando a função
$resultado = getClientesPaginados($paginaAtual, $registrosPorPagina);
$clientes = $resultado['dados'];
$totalPaginas = $resultado['totalPaginas'];

$cliente = null; // Inicializa a variável do cliente

// Verificar se o parâmetro 'cli_id' foi passado para edição
if (isset($_GET['cli_id'])) {
    $cli_id = $_GET['cli_id'];

    // Carregar os dados do cliente para edição
    $query = "SELECT * FROM cliente WHERE cli_id = :cli_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':cli_id', $cli_id);
    $stmt->execute();
    $cliente = $stmt->fetch();

    if (!$cliente) {
        echo "Cliente não encontrado!";
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle - BuscaObra</title>   

    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
    <div class="toggle-sidebar" id="toggle-sidebar">
        <i class="fas fa-bars"></i>
    </div>

    <div class="container">
        <!-- Barra Lateral -->
        <nav class="sidebar" id="sidebar">
            <?php include 'includes/sidebar.php'; ?>
        </nav>

        <!-- Conteúdo Principal -->
        <main class="main-content">
            <!-- Cabeçalho -->
            <?php include 'includes/header.php'; ?>

            <div class="content">
                <h2>Painel de Clientes</h2>
            </div>

            <!-- Tabela de Dados -->
            <div class="dashboard-cards">
            <?php if (empty($_GET['page'])): ?>
                <div class="card">
                
                    <table class="data-table">
                        <caption class="data-table-caption">Painel de Clientes</caption>
                        <thead class="data-table-header">
                            <tr class="data-table-row">
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Bairro</th>
                                <th>Cargo</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody class="data-table-body">
                            <?php
                                if (count($clientes) > 0) {
                                    foreach ($clientes as $row) {
                                        echo '<tr class="data-table-row">';
                                        echo '<td>' . htmlspecialchars($row['cli_id']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['cli_nome']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['cli_email']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['cli_bairro']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['cargo']) . '</td>';
                                        echo '<td>
                                                <a href="?page=editar&cli_id=' . $row['cli_id'] . '" ><i class="fa-solid fa-pen-to-square"></i></a>
                                                <a href="?page=excluir&cli_id=' . $row['cli_id'] . '" ><i class="fa-solid fa-trash"></i></a>
                                            </td>';
                                        echo '</tr>';
                                    }          
                                } else {
                                    echo '<tr><td colspan="6">Nenhum resultado encontrado.</td></tr>';
                                }
                            ?>           
                        </tbody>
                    </table>
                    <div id="paginacao" class="paginacao">
                        <button onclick="window.location.href='listacliente.php?pagina=<?php echo $paginaAtual - 1; ?>'" id="btn-anterior" <?php echo ($paginaAtual <= 1) ? 'disabled' : ''; ?>>Anterior</button>
                        <span id="info-pagina">Página <?php echo $paginaAtual; ?> de <?php echo $totalPaginas; ?></span>
                        <button onclick="window.location.href='listacliente.php?pagina=<?php echo $paginaAtual + 1; ?>'" id="btn-proximo" <?php echo ($paginaAtual >= $totalPaginas) ? 'disabled' : ''; ?>>Próximo</button>
                    </div>
                
                </div>
                <?php endif; ?>
            </div> 
        </main>
    </div>


    <?php 
    
    if(empty($_GET['page'])) {
        //Nada
    } else {
        switch($_GET['page']) {
            case 'editar':
                require_once 'view/editar_cliente.php';
                break;
            case 'excluir':
                require_once 'view/excluir_cliente.php';
                break;
            default:
                echo "Ação inválida!";
                break;
        }
    }
    

    ?>

    <!-- Rodapé -->
    <footer class="footer">
        <p>&copy; 2024 BuscaObra. Todos os direitos reservados. <p class="version">Versão 1.0</p></p>
    </footer>

    <script src="assets/js/modal.js"></script>
</body>
</html>
