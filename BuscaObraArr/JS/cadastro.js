const tipoUsuarioSelect = document.getElementById('tipo-usuario');
const informacoesAdicionaisDiv = document.getElementById('informacoes-adicionais');
const formCadastro = document.getElementById('form-cadastro'); // Referência ao formulário
const feedback = document.getElementById('feedback'); // Referência ao feedback

// Função para ler texto por voz
function lerTexto(texto) {
  const synth = window.speechSynthesis;
  const utterThis = new SpeechSynthesisUtterance(texto);
  utterThis.lang = 'pt-BR';
  synth.speak(utterThis);
}

// Adiciona um listener para o formulário
formCadastro.addEventListener('submit', function (event) {
  event.preventDefault(); // Impede o envio do formulário padrão

  const tipoUsuario = tipoUsuarioSelect.value; // Obtém o valor selecionado

  if (tipoUsuario === '') {
    feedback.textContent = "Por favor, selecione o tipo de usuário.";
    lerTexto(feedback.textContent);
  } else {
    feedback.textContent = `Usuário do tipo ${tipoUsuario} cadastrado com sucesso!`;
    lerTexto(feedback.textContent);
    
    // Exibir mensagem de "Cadastro em andamento" e redirecionar após um atraso
    lerTexto('Cadastro em andamento. Por favor, aguarde.');
    setTimeout(() => {
      // Redireciona para a página correspondente
      if (tipoUsuario === 'administrador') {
        window.location.href = 'administrador.html'; // Redireciona para a página do administrador
      } else if (tipoUsuario === 'funcionario') {
        window.location.href = 'funcionario.php'; // Redireciona para a página do funcionário
      } else if (tipoUsuario === 'usuario') {
        window.location.href = 'usuario.php'; // Redireciona para a página do usuário
      }
    }, 3000); // Atraso de 3 segundos (3000 milissegundos)
  }
});

// Elementos que terão leitura por voz
const imagemCadastro = document.getElementById('imagem-cadastro');
const tituloCadastro = document.getElementById('titulo-cadastro');
const tipoUsuario = document.getElementById('tipo-usuario');
const btnCadastrar = document.getElementById('btn-cadastrar');

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
