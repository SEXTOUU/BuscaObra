document.querySelectorAll('.remove-btn').forEach(button => {
    button.addEventListener('click', function () {
      this.closest('tr').remove();
      alert('Usuário removido com sucesso!');
    });
  });
  