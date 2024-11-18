<?php
$notificacoes = obterNotificacoes($usuario_id); // Obtem todas as notificações
?>
<div class="notification-list">
    <ul>
        <?php foreach ($notificacoes as $notificacao): ?>
            <li class="notification-item <?php echo $notificacao['lida'] ? '' : 'unread'; ?>">
                <i class="fas fa-<?php echo ($notificacao['tipo'] == 'alert' ? 'exclamation-circle' : 'info-circle'); ?> notification-icon"></i>
                <?php echo $notificacao['mensagem']; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
