<?php
require_once 'config.php';
require_once 'vendor/autoload.php';  // Incluindo o autoload do MercadoPago

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\PreApproval\PreApprovalClient;

// Configuração do token de acesso
MercadoPagoConfig::setAccessToken(MP_ACCESS_TOKEN);

// Verificando se os parâmetros existem na URL
if (isset($_GET['status'], $_GET['preapproval_id'], $_GET['payer_email'])) {
    // Captura os dados de retorno enviados via GET
    $status = $_GET['status'];  // 'approved', 'pending', 'rejected'
    $preapproval_id = $_GET['preapproval_id'];
    $payer_email = $_GET['payer_email'];


    updateSubscription($status, $preapproval_id, $payer_email, $plano, $valor);

    // Verificando o pagamento usando a função processPayment
    $paymentStatus = processPayment($preapproval_id); // Função definida em function.php
} else {
    $paymentStatus = 'failure';
    $errorMessage = 'Parâmetros ausentes. Não foi possível processar o pagamento.';
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retorno do Pagamento</title>
    <link rel="stylesheet" href="css/retorno.css">
    <link rel="shortcut icon" href="<?= $favicon ?>" type="image/x-icon">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pagamento Processado</h1>
            <p>Verifique os detalhes abaixo e prossiga com a ação desejada.</p>
        </div>

        <!-- Status da transação (sucesso ou falha) -->
        <div class="status <?php echo ($paymentStatus == 'success') ? 'success' : 'failure'; ?>">
            <?php if ($paymentStatus == 'success'): ?>
                <p>Pagamento concluído com sucesso!</p>
            <?php else: ?>
                <p>Ocorreu um erro durante o pagamento. Tente novamente.</p>
            <?php endif; ?>
        </div>

        <!-- Detalhes do pagamento -->
        <div class="payment-info">
            <h3>Detalhes do Pagamento:</h3>
            <p>Plano: <strong>Plano Premium</strong></p>
            <p>Valor: <strong>R$ 25,00</strong></p>
            <p>Status: <strong><?php echo ucfirst($paymentStatus); ?></strong></p>
            <?php if (isset($errorMessage)): ?>
                <p><strong>Erro: </strong><?php echo $errorMessage; ?></p>
            <?php endif; ?>
        </div>

        <!-- Botão para redirecionar o usuário conforme o status -->
        <div class="action-button">
            <?php if ($paymentStatus == 'success'): ?>
                <a href="index.php">Voltar à Página Inicial</a>
            <?php else: ?>
                <a href="pagamento.php">Tentar Novamente</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer">
        <p>© 2024 Sua Empresa. Todos os direitos reservados. <a href="contato.php">Entre em contato</a>.</p>
    </div>
</body>
</html>
