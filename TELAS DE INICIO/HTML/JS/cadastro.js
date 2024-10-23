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


// Função para ler texto por voz
function lerTexto(texto) {
  const synth = window.speechSynthesis;
  const utterThis = new SpeechSynthesisUtterance(texto);
  utterThis.lang = 'pt-BR';
  synth.speak(utterThis);
}

// Elementos que terão leitura por voz
const imagemCadastro = document.getElementById('imagem-cadastro');
const tituloCadastro = document.getElementById('titulo-cadastro');
const tipoUsuario = document.getElementById('tipo-usuario');
const btnCadastrar = document.getElementById('btn-cadastrar');
const feedback = document.getElementById('feedback');

// Leitura ao clicar na imagem
imagemCadastro.addEventListener('click', () => lerTexto('Imagem de cadastro.'));

// Leitura ao clicar no título
tituloCadastro.addEventListener('click', () => lerTexto(tituloCadastro.textContent));

// Leitura ao focar no campo de seleção
tipoUsuario.addEventListener('focus', () => lerTexto('Selecione o tipo de usuário.'));

// Leitura ao alterar a seleção
tipoUsuario.addEventListener('change', () => {
  const opcao = tipoUsuario.options[tipoUsuario.selectedIndex].text;
  lerTexto(`Você selecionou: ${opcao}`);
});

// Leitura ao clicar no botão de cadastro
btnCadastrar.addEventListener('click', (e) => {
  e.preventDefault(); // Impede o envio imediato para que a mensagem seja ouvida
  lerTexto('Cadastro em andamento. Por favor, aguarde.');
});

// Exemplo de feedback após tentativa de cadastro
document.getElementById('form-cadastro').addEventListener('submit', (e) => {
  e.preventDefault();
  const tipoSelecionado = tipoUsuario.value;
  if (tipoSelecionado === "") {
    feedback.textContent = "Por favor, selecione o tipo de usuário.";
  } else {
    feedback.textContent = `Usuário do tipo ${tipoSelecionado} cadastrado com sucesso!`;
  }
  lerTexto(feedback.textContent);
});