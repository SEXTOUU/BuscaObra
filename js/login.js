document.addEventListener('DOMContentLoaded', function () {
  console.log('Página inicial carregada com sucesso!');

  // Evento submit: Verifica se os campos estão preenchidos antes de enviar para o PHP
  document.getElementById('login-form').addEventListener('submit', function (e) {
      const usuario = document.getElementById('usuario').value;
      const senha = document.getElementById('senha').value;

      if (!usuario || !senha) {
          e.preventDefault();  // Evita envio se campos estiverem vazios
          document.getElementById('feedback-login').textContent = 'Por favor, preencha todos os campos!';
          lerTexto('Por favor, preencha todos os campos!');
          return;
      }

      // Deixe o formulário ser enviado ao PHP se os campos estiverem preenchidos
  });

  // Funções de leitura com voz
  function lerTexto(texto) {
      const synth = window.speechSynthesis;
      const utterThis = new SpeechSynthesisUtterance(texto);
      utterThis.lang = 'pt-BR';
      synth.speak(utterThis);
  }

  // Elementos que serão lidos ao serem clicados ou focados
  const titulo = document.getElementById('titulo');
  const btnLogin = document.getElementById('btn-login');
  const linkCadastro = document.getElementById('link-cadastro');
  const campoUsuario = document.getElementById('usuario');
  const campoSenha = document.getElementById('senha');

  // Eventos de clique
  titulo.addEventListener('click', () => lerTexto(titulo.textContent));
  btnLogin.addEventListener('click', () => lerTexto('Tentando fazer login. Por favor, aguarde.'));
  linkCadastro.addEventListener('click', () => lerTexto('Você será redirecionado para a página de cadastro.'));

  // Eventos de foco nos campos de entrada
  campoUsuario.addEventListener('focus', () => lerTexto('Digite seu nome de usuário.'));
  campoSenha.addEventListener('focus', () => lerTexto('Digite sua senha.'));
});
