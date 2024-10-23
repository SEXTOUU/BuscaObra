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
  
