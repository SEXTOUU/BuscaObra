// Obter os modais
var viewModal = document.getElementById("viewModal");
var editModal = document.getElementById("editModal");
var deleteModal = document.getElementById("deleteModal");

// Obter os botões de fechamento
var closeViewModalBtn = document.getElementById("closeViewModal");
var closeEditModalBtn = document.getElementById("closeEditModal");
var closeDeleteModalBtn = document.getElementById("closeDeleteModal");

// Função para abrir o modal de visualização
function openViewModal() {
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

// Atribuindo eventos de clique aos botões de abrir modais
document.getElementById("openModalBtn1").onclick = function() {
    openViewModal(); // Abre o modal de visualização
};
document.getElementById("openModalBtn2").onclick = function() {
    openEditModal(); // Abre o modal de edição
};


// Botões de excluir (Abrindo o modal de confirmação)
document.getElementById("openModalBtnDelete1").onclick = function() {
    openDeleteModal(); // Abre o modal de exclusão
};
// Quando o "X" for clicado, fechar os modais
closeViewModalBtn.onclick = function() {
    viewModal.style.display = "none";
};

closeEditModalBtn.onclick = function() {
    editModal.style.display = "none";
};

closeDeleteModalBtn.onclick = function() {
    deleteModal.style.display = "none";
};

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
document.getElementById("confirmDelete").onclick = function() {
    console.log("Exclusão confirmada.");
    deleteModal.style.display = "none"; // Fechar o modal de exclusão
};

// Cancelar a exclusão
document.getElementById("cancelDelete").onclick = function() {
    deleteModal.style.display = "none"; // Fechar o modal de exclusão
};


let paginaAtual = 1;
const registrosPorPagina = 10;

function carregarDados(pagina) {
    fetch(`seu-script.php?pagina=${pagina}&limite=${registrosPorPagina}`)
        .then(response => response.json())
        .then(data => {
            atualizarTabela(data.dados);
            atualizarBotoes(data.totalPaginas, pagina);
        })
        .catch(error => console.error("Erro ao carregar dados:", error));
}

function atualizarTabela(dados) {
    const tbody = document.getElementById("tabela-dados").querySelector("tbody");
    tbody.innerHTML = "";

    dados.forEach(item => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${item.id}</td>
            <td>${item.nome}</td>
            <td>${item.email}</td>
        `;
        tbody.appendChild(row);
    });
}

function atualizarBotoes(totalPaginas, pagina) {
    document.getElementById("info-pagina").textContent = `Página ${pagina}`;
    document.getElementById("btn-anterior").disabled = pagina <= 1;
    document.getElementById("btn-proximo").disabled = pagina >= totalPaginas;
    paginaAtual = pagina;
}

function irParaPagina(pagina) {
    if (pagina > 0) {
        carregarDados(pagina);
    }
}

// Carrega a primeira página inicialmente
carregarDados(paginaAtual);
