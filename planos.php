<?php
require_once "config.php";

// Definir os planos com seus respectivos valores e descrições
$planos = [
    'premium' => [
        'nome' => 'Plano Premium',
        'valor' => 25.00,
        'descricao' => 'Recursos avançados para uma experiência completa.',
        'beneficios' => [
            'Maior Destaque no Carrossel',
            'Prioridade nos Resultados de Busca',
            'Anúncios Promovidos'
        ]
    ]/*,
    'basico' => [
        'nome' => 'Plano Básico',
        'valor' => 10.00,
        'descricao' => 'Plano inicial com recursos limitados.',
        'beneficios' => [
            'Visibilidade básica no site',
            'Acesso a funcionalidades essenciais'
        ]
    ]*/
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?> - Planos de Assinatura</title>
    <link rel="shortcut icon" href="<?= $favicon ?>" type="image/x-icon">
    <link rel="stylesheet" href="css/planos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 id="plano">Escolha seu Plano Ideal</h1>
            <p id="oi">Compare nossos planos e aproveite os benefícios exclusivos!</p>
        </div>
        <div class="plans">
            <?php foreach ($planos as $key => $plano): ?>
                <div class="plan <?php echo $key; ?>" id="<?php echo $key; ?>Plan">
                    <h2><?php echo $plano['nome']; ?></h2>
                    <div class="price">R$ <?php echo number_format($plano['valor'], 2, ',', '.'); ?>/mês</div>
                    <p><?php echo $plano['descricao']; ?></p>
                    <div class="benefits">
                        <h3>Benefícios <?php echo ucfirst($key); ?>:</h3>
                        <ul>
                            <?php foreach ($plano['beneficios'] as $beneficio): ?>
                                <li><i class="fas fa-check-circle"></i> <?php echo $beneficio; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <!-- Botão de assinatura -->
                    <div class="advantages">
                        <button id="subscribeButton" onclick="window.location.href='pagamento.php?plano=<?php echo $key; ?>&valor=<?php echo $plano['valor']; ?>'">Assinar Agora</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Footer -->
    <div class="footer">
        <p>© 2024 BuscaObra. Todos os direitos reservados. <a href="contato.php">Entre em contato</a>.</p>
    </div>
</body>
</html>
