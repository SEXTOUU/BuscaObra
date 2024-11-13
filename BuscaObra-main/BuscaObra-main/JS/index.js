document.addEventListener("DOMContentLoaded", function() {
    const profileMenu = document.querySelector(".profile-menu");
    const dropdown = document.querySelector(".profile-dropdown");

    // Verifica se os elementos existem antes de adicionar eventos
    if (profileMenu && dropdown) {
        // Toggle o menu dropdown ao clicar no ícone do perfil
        profileMenu.addEventListener("click", function(event) {
            event.stopPropagation();
            dropdown.classList.toggle("active");
        });

        // Fecha o dropdown ao clicar fora dele
        document.addEventListener("click", function(event) {
            if (!profileMenu.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove("active");
            }
        });
    } else {
        console.warn("Elementos de menu de perfil não encontrados.");
    }
});
