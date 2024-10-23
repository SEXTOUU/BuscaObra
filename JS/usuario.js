document.getElementById('editar-perfil').addEventListener('click', () => {
    const novoNome = prompt('Digite o novo nome:');
    const novoEmail = prompt('Digite o novo email:');
  
    if (novoNome) document.getElementById('nome-usuario').textContent = novoNome;
    if (novoEmail) document.getElementById('email-usuario').textContent = novoEmail;
  
    alert('Perfil atualizado com sucesso!');
  });
  
  document.getElementById('ver-dados').addEventListener('click', () => {
    alert('Exibindo dados do usuário...');
  });
  
  document.getElementById('sair').addEventListener('click', () => {
    window.location.href = 'index.html';
  });
  