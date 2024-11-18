<?php
    require_once "config.php"; // Configuração do MercadoPago
    require_once 'vendor/autoload.php';
    
    use MercadoPago\MercadoPagoConfig;
    use MercadoPago\Client\Preapproval\PreapprovalClient;
    use MercadoPago\Exceptions\MPApiException;

    session_start(); // Inicia a sessão para capturar o e-mail do usuário

    // Verifica se o usuário está logado e captura o e-mail
    $emailPro = isset($_SESSION['cli_email']) ? $_SESSION['cli_email'] : 'cliente@example.com';

    // Configuração do token de acesso
    MercadoPagoConfig::setAccessToken(MP_ACCESS_TOKEN); // Token de teste

    // Definindo as variáveis do plano escolhido
    $plano = $_GET['plano']; // Exemplo de plano recebido (premium, básico, etc.)
    $valor = $_GET['valor']; // Valor do plano

    // Configuração das datas para a assinatura
    $startDate = new DateTime("+1 minute", new DateTimeZone("UTC"));
    $endDate = new DateTime("+2 years", new DateTimeZone("UTC"));
    $startDateFormatted = $startDate->format("Y-m-d\TH:i:s\Z");
    $endDateFormatted = $endDate->format("Y-m-d\TH:i:s\Z");

    // Preparando a solicitação de pré-aprovação
    $request = [
        "reason" => "Assinatura do Plano {$plano} - R$ {$valor},00/mês",
        "auto_recurring" => [
            "frequency" => 1,
            "frequency_type" => "months",
            "transaction_amount" => $valor,
            "currency_id" => "BRL",
            //"start_date" => $startDateFormatted,
            //"end_date" => $endDateFormatted,
        ],
        "payer_email" =>  $emailPro, // Email do cliente
        "back_url" => MP_NOTIFICATION_URL, // URL de retorno após pagamento
    ];

    $client = new PreapprovalClient();

    try {
        $preapproval = $client->create($request);
        $paymentLink = $preapproval->init_point;
    } catch (MPApiException $e) {
        echo "Erro da API: " . $e->getMessage();
        exit();
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento - Assinatura <?php echo $plano; ?></title>
    <link rel="shortcut icon" href="<?= $favicon ?>" type="image/x-icon">
    <link rel="stylesheet" href="css/pagamento.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pagamento de Assinatura</h1>
            <p>Finalize seu pagamento para o plano <strong><?php echo ucfirst($plano); ?></strong>.</p>
        </div>

        <div class="payment-details">
            <h2>Plano Selecionado:</h2>
            <p><strong><?php echo ucfirst($plano); ?></strong> - R$ <?php echo number_format($valor, 2, ',', '.'); ?>/mês</p>

            <h3>Benefícios:</h3>
            <ul>
                <li><i class="fas fa-check-circle"></i> Acesso exclusivo aos recursos premium</li>
                <li><i class="fas fa-check-circle"></i> Maior visibilidade nos resultados</li>
                <li><i class="fas fa-check-circle"></i> Suporte prioritário</li>
            </ul>

            <p>Para completar sua assinatura, clique no link abaixo:</p>

            <div class="payment-button">
                <a href="<?php echo $paymentLink; ?>" class="btn-pay">Ir para o Pagamento</a>
            </div>
        </div>

        <div class="footer">
            <p>© 2024 BuscaObra. Todos os direitos reservados. <a href="contato.php">Entre em contato</a>.</p>
        </div>
    </div>
</body>
</html>
