// Aguarda o carregamento completo da página
document.addEventListener('DOMContentLoaded', function () {
    // Seleciona o botão do dropdown
    const dropdownButton = document.getElementById('dropdownMenuButton');
    
    // Seleciona o menu dropdown
    const dropdownMenu = document.querySelector('.dropdown-menu');
    
    // Adiciona o evento de clique ao botão para alternar a exibição do menu
    dropdownButton.addEventListener('click', function (event) {
        event.stopPropagation(); // Previne que o clique no menu feche o dropdown imediatamente
        dropdownMenu.classList.toggle('show'); // Alterna a classe que mostra ou oculta o menu
    });
    
    // Fecha o dropdown se o usuário clicar em qualquer lugar fora do dropdown
    document.addEventListener('click', function (event) {
        if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.remove('show'); // Fecha o menu se o clique for fora do dropdown
        }
    });
});
