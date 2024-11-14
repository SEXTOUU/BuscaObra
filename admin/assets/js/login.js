document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("login-form");
  
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      const usuario = document.getElementById("usuario").value.trim();
      const senha = document.getElementById("senha").value.trim();
  
      if (usuario === "" || senha === "") {
        alert("Por favor, preencha todos os campos.");
      } else {
        form.submit();
      }
    });
  });
  