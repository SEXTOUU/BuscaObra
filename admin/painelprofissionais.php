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

if (isset($_POST['logout'])) {
    logout();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle - BuscaObra</title>

    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="style.css">
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
            <div class="logo">
                <img src="user.jpeg" alt="Logo">
                <h2>BuscaObra</h2>
            </div>

            <ul>
                <span class="title-menu">MENU</span>

                <li><a href="#" class="sidebaractive"><i
                            class="fas fa-tachometer-alt"></i>
                        Dashboard</a></li>

                <li class="dropdown">
                    <a href="#"><i class="fas fa-chart-line"></i> Relatórios <i
                            class="fas fa-chevron-down dropdown-icon"></i></a>
                    <div class="dropdown-content">
                        <a href="#">Relatório de Usuários</a>
                        <a href="#">Relatório de Avaliações</a>
                        <a href="#">Relatório Financeiro</a>
                    </div>
                </li>

                <span class="title-menu">CONFIGURAÇÕES</span>

                <li class="dropdown">
                    <a href="#"><i class="fas fa-users"></i> Profissionais <i
                            class="fas fa-chevron-down dropdown-icon"></i></a>
                    <div class="dropdown-content">
                        <a href="#">Lista de Profissionais</a>
                        <a href="#">Gerenciar Destaques</a>
                        <a href="#">Solicitações de Destaque</a>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="#"><i class="fas fa-user"></i> Clientes <i
                            class="fas fa-chevron-down dropdown-icon"></i></a>
                    <div class="dropdown-content">
                        <a href="#">Lista de Clientes</a>
                        <a href="#">Favoritos e Avaliações</a>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="#"><i class="fas fa-user-shield"></i> Usuários e
                        Permissões <i
                            class="fas fa-chevron-down dropdown-icon"></i></a>
                    <div class="dropdown-content">
                        <a href="#">Gerenciamento de Administradores</a>
                        <a href="#">Permissões de Acesso</a>
                    </div>
                </li>

                <span class="title-menu">GERENCIAMENTO</span>

                <li class="dropdown">
                    <a href="#"><i class="fas fa-star"></i> Planos e Assinaturas
                        <i class="fas fa-chevron-down dropdown-icon"></i></a>
                    <div class="dropdown-content">
                        <a href="#">Gerenciamento de Planos</a>
                        <a href="#">Status das Assinaturas</a>
                        <a href="#">Relatório de Assinaturas</a>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="#"><i class="fas fa-filter"></i> Busca e Filtros <i
                            class="fas fa-chevron-down dropdown-icon"></i></a>
                    <div class="dropdown-content">
                        <a href="#">Configurar Filtros</a>
                        <a href="#">Gerenciar Categorias</a>
                        <a href="#">Configurações de Recomendações</a>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="#">
                        <i class="fas fa-comments"></i> 
                        Mensagens e Suporte <span class="badge">4</span>
                        <i class="fas fa-chevron-down dropdown-icon"></i> 
                    </a>
                    <div class="dropdown-content">
                        <a href="#">Central de Mensagens</a>
                        <a href="#">Suporte ao Cliente</a>
                        <a href="#">Suporte ao Profissional</a>
                        <a href="#">FAQ e Tutoriais</a>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="#"><i class="fas fa-thumbs-up"></i> Avaliações e
                        Feedbacks <i
                            class="fas fa-chevron-down dropdown-icon"></i></a>
                    <div class="dropdown-content">
                        <a href="#">Moderação de Avaliações</a>
                        <a href="#">Respostas de Profissionais</a>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="#"><i class="fas fa-cog"></i> Configurações <i
                            class="fas fa-chevron-down dropdown-icon"></i></a>
                    <div class="dropdown-content">
                        <a href="#">Configurações Gerais</a>
                        <a href="#">Configurações de Notificações</a>
                        <a href="#">Configurações de Segurança</a>
                        <a href="#">Configurações de Pagamento</a>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Botão de Toggle para a barra lateral em dispositivos menores -->
        <button class="sidebar-toggle">
            <i class="fas fa-bars"></i> <!-- Ícone do menu -->
        </button>

        <!-- Conteúdo Principal -->
        <main class="main-content">
            <!-- Cabeçalho -->
            <header>
                <div class="search-bar">
                    <input type="text" class="search-input" placeholder="Buscar...">
                    <button type="submit" name="search" class="search-button"><i class="fas fa-search"></i></button>
                </div>

                <div class="notification-banner">
                    <span class="notification-text">Você Tem <strong> 21</strong>
                        Notificações</span>
                    <div class="notification-icon">
                        <i class="fas fa-bell"></i>
                        <span class="notification-dot"></span>
                        
                        <!-- Indica a contagem de notificações -->
                        <div class="notification-dropdown">
                            <ul>
                                <li class="notification-item">
                                    <i class="fas fa-check-circle notification-icon"></i>
                                    Notificação 1</li>
                                <li class="notification-item unread">
                                    <i class="fas fa-exclamation-circle notification-icon"></i>
                                    Notificação 2</li>
                                <li class="notification-item">
                                    <i class="fas fa-info-circle notification-icon"></i>
                                    Notificação 3</li>
                            </ul>
                            <div class="view-more">Ver mais</div>
                        </div>
                    </div>
                </div>

                <!-- Informações do Usuário no Cabeçalho -->
                <div class="user-info">
                    <img src="user.jpeg" alt="User">
                    <span>Michelle White</span>
                    <i class="fas fa-caret-down"></i>
                    <div class="user-dropdown">
                        <ul>
                            <li><i class="fa-solid fa-user"></i> Perfil</li>
                            <li><i class="fas fa-star"></i> Planos e Assinaturas</li>
                            <hr>
                            <li href="#"><i class="fas fa-cog"></i> Configurações</li>
                            <li href="#"><i class="fas fa-question"></i> Ajuda</li>
                            <li onclick="logout()"><i class="fa-solid fa-right-to-bracket"></i> Sair</li>
                        </ul>
                    </div>
                </div>
            </header>
         
            <!-- Tabela de Dados -->
            <div class="dashboard-cards">
                <div class="card">           
                    <table class="data-table">
                        <caption class="data-table-caption">Painel de Profissionais</caption>
                        <thead class="data-table-header">
                            <tr class="data-table-row">
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Cargo</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody class="data-table-body">
                            <tr class="data-table-row">
                                <td>1</td>
                                <td>Item 1</td>
                                <td>R$ 10,00</td>
                                <td>2</td>
                                <td>R$ 20,00</td>
                                <td>
                                    <button class="open-modal-btn" id="openModalBtn1"><i class="fa-solid fa-eye"></i></button>    
                                    <button class="open-modal-btn" id="openModalBtn2"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button class="open-modal-btn" id="openModalBtnDelete1"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr class="data-table-row">
                                <td>2</td>
                                <td>Item 2</td>
                                <td>R$ 15,00</td>
                                <td>1</td>
                                <td>R$ 15,00</td>
                                <td>
                                    <button class="open-modal-btn" id="openModalBtn1"><i class="fa-solid fa-eye"></i></button>    
                                    <button class="open-modal-btn" id="openModalBtn2"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button class="open-modal-btn" id="openModalBtnDelete1"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr class="data-table-row">
                                <td>3</td>
                                <td>Item 3</td>
                                <td>R$ 20,00</td>
                                <td>3</td>
                                <td>R$ 60,00</td>
                                <td>
                                    <button class="open-modal-btn" id="openModalBtn1"><i class="fa-solid fa-eye"></i></button>    
                                    <button class="open-modal-btn" id="openModalBtn2"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button class="open-modal-btn" id="openModalBtnDelete1"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr class="data-table-row">
                                <td>4</td>
                                <td>Item 4</td>
                                <td>R$ 20,00</td>
                                <td>3</td>
                                <td>R$ 60,00</td>
                                <td>
                                    <button class="open-modal-btn" id="openModalBtn1"><i class="fa-solid fa-eye"></i></button>    
                                    <button class="open-modal-btn" id="openModalBtn2"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button class="open-modal-btn" id="openModalBtnDelete1"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr class="data-table-row">
                                <td>5</td>
                                <td>Item 5</td>
                                <td>R$ 20,00</td>
                                <td>3</td>
                                <td>R$ 60,00</td>
                                <td>
                                    <button class="open-modal-btn" id="openModalBtn1"><i class="fa-solid fa-eye"></i></button>    
                                    <button class="open-modal-btn" id="openModalBtn2"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button class="open-modal-btn" id="openModalBtnDelete1"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                    <div id="paginacao" class="paginacao">
                        <button onclick="irParaPagina(paginaAtual - 1)" disabled id="btn-anterior">Anterior</button>
                        <span id="info-pagina">Página 1</span>
                        <button onclick="irParaPagina(paginaAtual + 1)" id="btn-proximo">Próximo</button>
                    </div>
                </div>
            </div> 
            
            <!-- Modal de Visualização -->
            <div id="viewModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" id="closeViewModal">&times;</span>
                    <h2 id="view-modal-title">Detalhes do Cliente</h2>
                    <p id="view-modal-description"></p>
                    <form method="post" class="modal-form">
                        <label for="name">Nome:</label>
                        <input type="text" id="name" name="name" value="Teste" disabled><br>

                        <label for="role">Email:</label>
                        <input type="text" id="role" name="email" value="Teste"  disabled><br>

                        <label for="role">Telefone:</label>
                        <input type="text" id="role" name="telefone" value="Teste"  disabled><br>

                        <label for="role">Endereço:</label>
                        <input type="text" id="role" name="endereco" value="Teste"  disabled><br>

                        <label for="role">Bairro:</label>
                        <input type="text" id="role" name="bairro" value="Teste"  disabled><br>

                        <label for="role">Cidade:</label>
                        <input type="text" id="role" name="cidade" value="Teste"  disabled><br>

                        <label for="role">Data de Nacimento:</label>
                        <input type="text" id="role" name="datadenacimento" value="Teste"  disabled><br>

                        <label for="role">CEP:</label>
                        <input type="text" id="role" name="cep" value="Teste"  disabled><br>

                        <label for="role">Cargo:</label>
                        <input type="text" id="role" name="cargo" value="Teste"  disabled><br>

                        <button type="submit">Fechar</button>
                    </form>
                </div>
            </div>

            <!-- Modal de Edição -->
            <div id="editModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" id="closeEditModal">&times;</span>
                    <h2 id="edit-modal-title">Editar Informações</h2>
                    <form id="edit-form" class="modal-form"method="post">                  
                        <label for="name">Nome:</label>
                        <input type="text" id="name" name="name" value="Teste" placeholder="Digite o nome" required><br>

                        <label for="role">Email:</label>
                        <input type="text" id="role" name="email" value="Teste" placeholder="Digite o email" required><br>

                        <label for="role">Telefone:</label>
                        <input type="text" id="role" name="telefone" value="Teste" placeholder="Digite o telefone" required><br>

                        <label for="role">Endereço:</label>
                        <input type="text" id="role" name="endereco" value="Teste" placeholder="Digite o endereço" required><br>

                        <label for="role">Bairro:</label>
                        <input type="text" id="role" name="bairro" value="Teste" placeholder="Digite o bairro"  required><br>

                        <label for="role">Cidade:</label>
                        <input type="text" id="role" name="cidade" value="Teste" placeholder="Digite a cidade" required><br>

                        <label for="role">Data de Nacimento:</label>
                        <input type="text" id="role" name="datadenacimento" value="Teste" placeholder="Digite a data de nacimento" required><br>

                        <label for="role">CEP:</label>
                        <input type="text" id="role" name="cep" value="Teste" placeholder="Digite o cep" required><br>

                        <label for="role">Cargo:</label>
                        <input type="text" id="role" name="cargo" value="Teste" placeholder="Digite o cargo" required><br>

                        <button type="submit" name="edit-salvar">Salvar</button>
                    </form>
                </div>
            </div>

            <!-- Modal de Confirmação de Exclusão -->
            <div id="deleteModal" class="modal">
                <div class="modal-content">
                    <form action="" method="post" class="modal-form">
                        <span class="close-btn" id="closeDeleteModal">&times;</span>
                        <h2>Tem certeza que deseja excluir?</h2>
                        <p id="delete-modal-description">Este processo não pode ser revertido.</p>
                        <button id="confirmDelete">Sim, excluir</button>
                        <button id="cancelDelete">Cancelar</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Rodapé -->
    <footer class="footer">
        <p>&copy; 2024 BuscaObra. Todos os direitos reservados. <p class="version">Versão 1.0</p></p>
    </footer>

    <script src="modal.js"></script>
    <script src="script.js"></script>
</body>
</html>
