<?php
require_once 'libs/include.php';

// Incluindo o autoload do PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluindo as classes do MercadoPago
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\PreApproval\PreApprovalClient;
use MercadoPago\Client\MercadoPagoClient;
use MercadoPago\Exceptions\MPApiException;

function getDatabaseConnection() {
    try {
        // Usando as constantes definidas no config.php
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        return $pdo;
    } catch (PDOException $e) {
        echo "Erro na conexão com o banco de dados: " . $e->getMessage() . "<br/>";
        die();
    }
}

// Configuração do MercadoPago (use o token configurado no config.php)
MercadoPagoConfig::setAccessToken(MP_ACCESS_TOKEN);

// Função para processar o pagamento via MercadoPago (exemplo simples)
function processPayment($preapproval_id) {
    try {
        $client = new PreApprovalClient();
        
        // Recuperando os detalhes da pré-aprovação usando o id
        $preapproval = $client->get($preapproval_id);
        
        if ($preapproval->status == 'approved') { 
            return 'Pagamento aprovado com sucesso!';
        } else {
            return 'O pagamento não foi aprovado.';
        }
    } catch (Exception $e) {
        // Em caso de erro na consulta ao MercadoPago
        return 'Erro ao verificar pagamento: ' . $e->getMessage();
    }
}

function valorPlano($plano) {
    $pdo = getDatabaseConnection();

    $stmt = $pdo->prepare("SELECT valor FROM planos WHERE nome = :plano");
    $stmt->bindParam(':plano', $plano);
    $stmt->execute();

    return $stmt->fetchColumn();
    
}

function updateSubscription($status, $preapproval_id, $payer_email, $plano, $valor) {
    $pdo = getDatabaseConnection(); // Obtém a conexão com o banco de dados

    // Verifique se o pagamento foi aprovado
    if ($status == 'approved') {
        $query = "INSERT INTO assinaturas (preapproval_id, email_cliente, plano, valor, status, data_assinatura) 
                  VALUES (:preapproval_id, :email_cliente, :plano, :valor, :status, NOW())";
    } else {
        $query = "INSERT INTO assinaturas (preapproval_id, email_cliente, plano, valor, status, data_assinatura) 
                  VALUES (:preapproval_id, :email_cliente, :plano, :valor, :status, NOW())";
    }

    try {
        // Prepara a query e executa
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':preapproval_id', $preapproval_id);
        $stmt->bindParam(':email_cliente', $payer_email);
        $stmt->bindParam(':plano', $plano);
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
    } catch (PDOException $e) {
        // Se houver erro, captura e exibe
        echo "Erro ao registrar assinatura: " . $e->getMessage();
    }
}

function logError($message) {
    file_put_contents(__DIR__ . '/logs/errors.log', date('Y-m-d H:i:s') . " - $message\n", FILE_APPEND);
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

function verificarAcesso() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['cli_tipo'] !== 4) {
        header("Location: login.php");
        exit;
    }

    // Conectar ao banco de dados
    $pdo = getDatabaseConnection();
    
    // Query com JOIN para pegar o nome do cliente
    $stmt = $pdo->prepare("
        SELECT a.*, c.cli_nome 
        FROM admins a
        INNER JOIN cliente c ON c.cli_id = a.cli_id
        WHERE a.cli_id = :cli_id AND a.status = 1
    ");
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

    return $admin; // Retorna os dados do admin, incluindo o nome do cliente
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
        // Sucesso
        case 'login_success':
            $message = "Login realizado com sucesso!";
            $alertType = "success";
            break;
        case 'info_message':
            $message = "Dados atualizados com sucesso!";
            $alertType = "success";
            break;
        case 'image_upload_success':
            $message = "Imagem enviada e salva com sucesso!";
            $alertType = "success";
            break;
        case 'profile_updated':
            $message = "Perfil atualizado com sucesso!";
            $alertType = "success";
            break;
        case 'senha_redefinida':
            $message = "Senha redefinida com sucesso!";
            $alertType = "success";
            break;

        // Avisos
        case 'warning_invalid_data':
            $message = "Atenção! Verifique os dados informados.";
            $alertType = "warning";
            break;
        case 'warning_image':
            $message = "Apenas imagens JPG, JPEG, PNG e GIF são permitidas.";
            $alertType = "warning";
            break;
        case 'warning_empty_fields':
            $message = "Preencha todos os campos obrigatórios.";
            $alertType = "warning";
            break;
        case 'warning_session_expired':
            $message = "Sua sessão expirou. Faça login novamente.";
            $alertType = "warning";
            break;
        case 'warning_email_exists':
            $message = "O e-mail informado já está em uso.";
            $alertType = "warning";
            break;
        case 'warning_password_match':
            $message = "As senhas não coincidem. Tente novamente.";
            $alertType = "warning";
            break;
        case 'campo_vazio':
            $message = "Por favor, preencha todos os campos!";
            $alertType = "warning";
            break;
        case 'senha_muito_curta':
            $message = "A senha deve ter pelo menos 6 caracteres.";
            $alertType = "warning";
            break;
        case 'senhas_diferentes':
            $message = "As senhas informadas devem ser iguais.";
            $alertType = "warning";
            break;

        // Erros
        case 'db_error':
            $message = "Erro ao conectar ao banco de dados.";
            $alertType = "error";
            break;
        case 'file_upload_error':
            $message = "Erro ao fazer o upload do arquivo.";
            $alertType = "error";
            break;
        case 'access_denied':
            $message = "Acesso negado! Você não tem permissão para esta ação.";
            $alertType = "error";
            break;
        case 'login_failed':
            $message = "Falha no login. Verifique suas credenciais.";
            $alertType = "error";
            break;
        case 'error_image':
            $message = "Erro ao carregar a imagem.";
            $alertType = "error";
            break;
        case 'unexpected_error':
            $message = "Ocorreu um erro inesperado. Tente novamente mais tarde.";
            $alertType = "error";
            break;
        case 'email_not_found':
            $message = "O e-mail informado não foi encontrado no sistema.";
            $alertType = "error";
            break;
        case 'invalid_login':
            $message = "Nome ou senha inválida. Tente novamente.";
            $alertType = "error";
            break;
        case 'invalid_password':
            $message = "Senha incorreta! Tente novamente.";
            $alertType = "error";
            break;
        case 'invalid_email':
            $message = "E-mail inválido. Tente novamente.";
            $alertType = "error";
            break;
        case 'invalid_captcha':
            $message = "Captcha inválido. Tente novamente.";
            $alertType = "error";
            break;
        case 'empty_fields':
            $message = "Por favor, preencha todos os campos!";
            $alertType = "error";
            break;
        case 'invalid_token':
            $message = "Token inválido. Por favor, tente novamente.";
            $alertType = "error";
            break;
        case 'invalid_data':
            $message = "Dados inválidos. Por favor, verifique os dados informados.";
            $alertType = "error";
            break;
        case 'erro_redefinir_senha':
            $message = "Erro ao redefinir a senha. Por favor, tente novamente.";
            $alertType = "error";
            break;
        case 'token_invalido':
            $message = "Token inválido. Por favor, tente novamente.";
            $alertType = "error";
            break;
        case 'token_expirado':
            $message = "Token expirado. Por favor, tente novamente.";
            $alertType = "error";
            break;
        

        // Informações
        case 'info_no_change':
            $message = "Nenhuma alteração detectada.";
            $alertType = "info";
            break;
        case 'info_no_image':
            $message = "Nenhuma imagem selecionada.";
            $alertType = "info";
            break;
        case 'info_logged_out':
            $message = "Você foi desconectado com sucesso.";
            $alertType = "info";
            break;
        case 'info_password_reset_sent':
            $message = "Um link para redefinir sua senha foi enviado ao seu e-mail.";
            $alertType = "info";
            break;
        case 'info_account_activation':
            $message = "Sua conta foi ativada com sucesso! Faça o login.";
            $alertType = "info";
            break;
        case 'info_profile_view_only':
            $message = "Você está visualizando seu perfil em modo de leitura.";
            $alertType = "info";
            break;

        // Padrão
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
    if (empty($cli_id) || !is_numeric($cli_id)) {
        // Retorna a imagem padrão se o ID do cliente for inválido
        return null;
    }

    try {
        // Conectar ao banco de dados
        $pdo = getDatabaseConnection();

        // Consulta para obter a imagem do usuário logado
        $sql = "SELECT imagem FROM profissionais WHERE cli_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$cli_id]);
        $dadosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($dadosUsuario['imagem'])) {
            return $dadosUsuario['imagem']; // Retorna o nome da imagem
        }

        return null; // Caso não haja imagem, retorna null

    } catch (PDOException $e) {
        // Em caso de erro, registra o erro no log (não exibe diretamente ao usuário)
        error_log("Erro ao buscar imagem de perfil: " . $e->getMessage());
        return 'images/userphoto/default-avatar.png'; // Retorna imagem padrão em caso de erro
    }
}



function editarCliente($cli_id, $cli_nome, $cli_email, $cli_bairro) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("UPDATE cliente SET cli_nome = :cli_nome, cli_email = :cli_email, cli_bairro = :cli_bairro WHERE cli_id = :cli_id");
    $stmt->bindParam(':cli_id', $cli_id);
    $stmt->bindParam(':cli_nome', $cli_nome);
    $stmt->bindParam(':cli_email', $cli_email);
    $stmt->bindParam(':cli_bairro', $cli_bairro);
    $stmt->execute();
}

function verAdminStatus() {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE cli_id = :cli_id AND status = 1");
    $stmt->bindParam(':cli_id', $_SESSION['cli_id']);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function getAdminStatus() {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT status FROM admins WHERE cli_id = :cli_id");
    $stmt->bindParam(':cli_id', $_SESSION['cli_id']);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function criarNotificacao($usuario_id, $mensagem, $tipo = 'info') {
    $pdo = getDatabaseConnection(); // Função para conectar ao banco de dados
    $stmt = $pdo->prepare("INSERT INTO notificacoes (usuario_id, mensagem, tipo) VALUES (:usuario_id, :mensagem, :tipo)");
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':mensagem', $mensagem);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->execute();
}

function obterNotificacoes($usuario_id) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT * FROM notificacoes WHERE usuario_id = :usuario_id AND lida = 0 ORDER BY data_criacao DESC LIMIT 5");
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function marcarNotificacaoComoLida($notificacao_id) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("UPDATE notificacoes SET lida = 1 WHERE id = :id");
    $stmt->bindParam(':id', $notificacao_id);
    $stmt->execute();
}

function enviar_contato($nome, $email, $mensagem, $assunto) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("INSERT INTO contato (nome, email, mensagem, assunto, cod_data_envio) VALUES (:nome, :email, :mensagem, :assunto , NOW())");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mensagem', $mensagem);
    $stmt->bindParam(':assunto', $assunto);
    return $stmt->execute();
}

function recuperarSenha($email) {

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setAlert('email_not_found');
        return false;
    }

    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT * FROM cliente WHERE cli_email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        setAlert('email_not_found');
        return false;
    } else {

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("DELETE FROM redefinicao_senha WHERE cli_id = :cli_id");
        $stmt->bindParam(':cli_id', $usuario['cli_id']);
        $stmt->execute();

        $token = bin2hex(random_bytes(32));
        $expiracao = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $stmt = $pdo->prepare("INSERT INTO redefinicao_senha (cli_id, token, data_criacao, data_expiracao) VALUES (:cli_id, :token,  NOW(),:expiracao)");
        $stmt->bindParam(':cli_id', $usuario['cli_id']);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expiracao', $expiracao);

        if($stmt->execute()) {
            $mensagem = "Ola $usuario[cli_nome],\n\n";
            $mensagem .= "Um link para redefinir sua senha foi enviado para o seu e-mail.\n\n";
            $mensagem .= "O link expira em 1 hora.";
            $mensagem = "Clique no link abaixo para redefinir sua senha:\n\n";
            $mensagem .= BASE_URL . "/redefinir-senha.php?token=$token";
            $mensagem .= "\n\nCaso não tenha solicitado a redefinição de senha, por favor, ignore este e-mail.";
            $mensagem .= "\n\nAtenciosamente,\nSistema";

            if(enviar_email($email, $mensagem, 'Redefinir Senha')) {
                setAlert('info_password_reset_sent');
                return true;
            } else {
                setAlert('error_password_reset_failed');
                return false;
            }

        } else {
            setAlert('error_password_reset_failed');
            return false;
        }
        
    }

}

function enviar_email($email, $mensagem, $assunto) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER; // Seu e-mail
        $mail->Password = SMTP_PASS; // Sua senha
        $mail->SMTPSecure = 'tls';
        $mail->Port = SMTP_PORT;

        $mail->setFrom(SMTP_USER, 'Sistema'); // Remetente
        $mail->addAddress($email);
        $mail->Subject = $assunto;
        $mail->Body = $mensagem;

        $mail->send();

        return true;
    } catch (Exception $e) {
        echo "Erro ao enviar o e-mail: {$mail->ErrorInfo}";
        return false;
    }
}

function redefinirSenha($token, $senha) {
    $pdo = getDatabaseConnection();

    $stmt = $pdo->prepare("SELECT cli_id, data_expiracao FROM redefinicao_senha WHERE token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$tokenData) {
        setAlert('token_invalido');
        return false;
    }

    if(strtotime($tokenData['data_expiracao']) < time()) {
        setAlert('token_expired');
        return false;
    }

    $hashedPassword = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE cliente SET cli_senha = :senha WHERE cli_id = :cli_id");
    $stmt->bindParam(':senha', $hashedPassword);
    $stmt->bindParam(':cli_id', $tokenData['cli_id']);
    $stmt->execute();

    $stmt = $pdo->prepare("DELETE FROM redefinicao_senha WHERE token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    return true;

}

function validarToken($token) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("SELECT * FROM redefinicao_senha WHERE token = :token AND data_expiracao > NOW()");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}
?>
