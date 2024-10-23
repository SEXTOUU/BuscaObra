document.querySelector('.status-btn').addEventListener('click', function () {
    const status = this.textContent === 'Pendente' ? 'Concluído' : 'Pendente';
    this.textContent = status;
    this.style.backgroundColor = status === 'Concluído' ? '#5cb85c' : '#f0ad4e';
  });