document.addEventListener("DOMContentLoaded", function() {
    const profileMenu = document.querySelector(".profile-menu");
    const dropdown = document.querySelector(".profile-dropdown");

    // Toggle o menu dropdown ao clicar no ícone do perfil
    profileMenu.addEventListener("click", function(event) {
        event.stopPropagation();
        dropdown.classList.toggle("active");
    });

    // Fechar o dropdown ao clicar fora dele
    document.addEventListener("click", function(event) {
        if (!profileMenu.contains(event.target)) {
            dropdown.classList.remove("active");
        }
    });
});

