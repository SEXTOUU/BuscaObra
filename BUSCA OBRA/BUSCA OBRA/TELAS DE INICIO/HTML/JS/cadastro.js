const tipoUsuarioSelect = document.getElementById('tipo-usuario');
const informacoesAdicionaisDiv = document.getElementById('informacoes-adicionais');
const formCadastro = document.getElementById('form-cadastro'); // Referência ao formulário

// Adiciona um listener para o formulário
formCadastro.addEventListener('submit', function (event) {
  event.preventDefault(); // Impede o envio do formulário padrão

  const tipoUsuario = tipoUsuarioSelect.value; // Obtém o valor selecionado

  if (tipoUsuario === 'administrador') {
    window.location.href = 'administrador.html'; // Redireciona para a página do administrador
  } else if (tipoUsuario === 'funcionario') {
    window.location.href = 'funcionario.html'; // Redireciona para a página do funcionário
  } else if (tipoUsuario === 'usuario') {
    window.location.href = 'usuario.html'; // Redireciona para a página do usuário
  } else {
    alert('Selecione um tipo de usuário para continuar.');
  }
});
