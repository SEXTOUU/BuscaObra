<header>
    <div class="search-bar">
        <input type="text" class="search-input" placeholder="Buscar...">
        <button type="submit" name="search" class="search-button"><i class="fas fa-search"></i></button>
    </div>

    <div class="notification-banner">
        <span class="notification-text">Você Tem <strong><?php echo $quantidade_notificacoes; ?></strong> Notificações</span>
        <div class="notification-icon">
            <i class="fas fa-bell"></i>
            <!-- Exibe o ponto de notificação somente se houver notificações não lidas -->
            <span class="notification-dot" style="display: <?php echo $quantidade_notificacoes > 0 ? 'inline-block' : 'none'; ?>;"></span>

            <div class="notification-dropdown">
                <ul>
                    <?php if ($quantidade_notificacoes > 0): ?>
                        <?php foreach ($notificacoes as $notificacao): ?>
                            <li class="notification-item <?php echo $notificacao['lida'] ? '' : 'unread'; ?>" data-id="<?php echo $notificacao['id']; ?>">
                                <i class="fas fa-<?php echo ($notificacao['tipo'] == 'alert' ? 'exclamation-circle' : 'info-circle'); ?> notification-icon"></i>
                                <?php echo $notificacao['mensagem']; ?>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="notification-item">
                            Nenhuma notificação.
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="view-more"><a href="notificacoes.php">Ver mais</a></div>
            </div>
        </div>
    </div>


    
    <!-- Informações do Usuário no Cabeçalho -->
    <div class="user-info">
        <img src="assets/images/user.jpeg" alt="User">
        <span><?php echo htmlspecialchars($usuario); ?></span>
        <i class="fas fa-caret-down"></i>
        <div class="user-dropdown">
            <ul class="dropdown-menu">
                <li><a href="#"><i class="fa-solid fa-user"></i> Perfil</a></li>
                <li><a href="#"><i class="fas fa-star"></i> Planos e Assinaturas</a></li>
                <hr>
                <li><a href="#"><i class="fas fa-cog"></i> Configurações</a></li>
                <li><a href="#"><i class="fas fa-question"></i> Ajuda</a></li>
                <li><a href="../logout.php"><i class="fa-solid fa-right-to-bracket"></i> Sair</a></li>
            </ul>
        </div>
    </div>
</header>

<?php include 'includes/scripts.php'; ?>