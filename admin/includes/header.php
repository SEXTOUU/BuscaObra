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
        <img src="assets/images/user.jpeg" alt="User">
        <span><?php echo $usuario; ?></span>
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