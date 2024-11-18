document.addEventListener("DOMContentLoaded", function () {
    // Obter os modais
    var viewModal = document.getElementById("viewModal");
    var editModal = document.getElementById("editModal");
    var deleteModal = document.getElementById("deleteModal");

    // Obter os botões de fechamento
    var closeViewModalBtn = document.getElementById("closeViewModal");
    var closeEditModalBtn = document.getElementById("closeEditModal");
    var closeDeleteModalBtn = document.getElementById("closeDeleteModal");

    // Função para abrir o modal de visualização
    function openViewModal(event) {
        var cliid = event.target.getAttribute("data-id");

        var xhr = new XMLHttpRequest();
        xhr.open("GET", "/admin/assets/js/modal.php?id=" + cliid, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                viewModal.innerHTML = xhr.responseText;
            }
        };
        xhr.send();

        viewModal.style.display = "block"; // Exibe o modal
    }

    // Função para abrir o modal de edição
    function openEditModal() {
        editModal.style.display = "block"; // Exibe o modal
    }

    // Função para abrir o modal de confirmação de exclusão
    function openDeleteModal() {
        deleteModal.style.display = "block"; // Exibe o modal
    }

    // Adicionar eventos aos botões de modal
    document.querySelectorAll(".open-modal-btn").forEach(function(button) {
        button.addEventListener("click", function(event) {
            event.preventDefault(); // Impede o redirecionamento da âncora
            var modalType = button.getAttribute("data-modal-type");

            if (modalType === "edit") {
                // O modal de edição será aberto automaticamente pelo PHP
                window.location.href = button.href; // Redireciona para o cliente específico para edição
            } else if (modalType === "delete") {
                // O modal de exclusão será aberto automaticamente pelo PHP
                window.location.href = button.href; // Redireciona para o cliente específico para exclusão
            }
        });
    });

    // Adicionar eventos para fechar os modais
    if (closeViewModalBtn) {
        closeViewModalBtn.onclick = function() {
            viewModal.style.display = "none";
        };
    }

    if (closeEditModalBtn) {
        closeEditModalBtn.onclick = function() {
            editModal.style.display = "none";
        };
    }

    if (closeDeleteModalBtn) {
        closeDeleteModalBtn.onclick = function() {
            deleteModal.style.display = "none";
        };
    }

    // Quando o usuário clicar fora do modal, ele também será fechado
    window.onclick = function(event) {
        if (event.target == viewModal) {
            viewModal.style.display = "none";
        } else if (event.target == editModal) {
            editModal.style.display = "none";
        } else if (event.target == deleteModal) {
            deleteModal.style.display = "none";
        }
    };

    // Confirmar a exclusão e fechar o modal
    var confirmDeleteBtn = document.getElementById("confirmDelete");
    if (confirmDeleteBtn) {
        confirmDeleteBtn.onclick = function() {
            console.log("Exclusão confirmada.");
            deleteModal.style.display = "none"; // Fechar o modal de exclusão
        };
    }

    // Cancelar a exclusão
    var cancelDeleteBtn = document.getElementById("cancelDelete");
    if (cancelDeleteBtn) {
        cancelDeleteBtn.onclick = function() {
            deleteModal.style.display = "none"; // Fechar o modal de exclusão
        };
    }
});
