console.log('Página inicial carregada com sucesso!');

document.getElementById('login-form').addEventListener('submit', function (e) {
    e.preventDefault();
  
    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;
    const tipoUsuario = document.getElementById('tipo-usuario').value;
  
    // Recuperando usuários cadastrados
    const usuarios = JSON.parse(localStorage.getItem('usuarios')) || [];
  
    const usuarioEncontrado = usuarios.find(
      (u) => u.email === email && u.senha === senha && u.tipo === tipoUsuario
    );
  
    if (usuarioEncontrado) {
      alert(`Bem-vindo, ${usuarioEncontrado.nome}!`);
      
      // Redireciona para a página correspondente
      switch (usuarioEncontrado.tipo) {
        case 'administrador':
          window.location.href = 'administrador.html';
          break;
        case 'funcionario':
          window.location.href = 'funcionario.html';
          break;
        case 'usuario':
          window.location.href = 'usuario.html';
          break;
      }
    } else {
      document.getElementById('feedback').textContent =
        'Email ou senha incorretos!';
    }
  });
  
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
  const feedbackLogin = document.getElementById('feedback-login');
  const campoUsuario = document.getElementById('usuario');
  const campoSenha = document.getElementById('senha');

  // Eventos de clique
  titulo.addEventListener('click', () => lerTexto(titulo.textContent));
  btnLogin.addEventListener('click', (e) => {
    e.preventDefault(); // Impede envio do formulário para leitura
    lerTexto('Tentando fazer login. Por favor, aguarde.');
  });
  linkCadastro.addEventListener('click', () => lerTexto('Você será redirecionado para a página de cadastro.'));

  // Evento de envio do formulário
  document.getElementById('login-form').addEventListener('submit', (e) => {
    e.preventDefault();
    const usuario = campoUsuario.value;
    if (usuario === "") {
      feedbackLogin.textContent = "Por favor, preencha o campo de usuário.";
    } else {
      feedbackLogin.textContent = `Bem-vindo, ${usuario}!`;
    }
    lerTexto(feedbackLogin.textContent);
  });

  // Eventos de foco nos campos de entrada
  campoUsuario.addEventListener('focus', () => lerTexto('Digite seu nome de usuário.'));
  campoSenha.addEventListener('focus', () => lerTexto('Digite sua senha.'));