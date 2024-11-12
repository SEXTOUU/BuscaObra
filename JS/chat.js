const perguntas = [
    "Qual é o seu nome?",
    "Qual serviço você precisa?",
    "Qual é o endereço do serviço?",
    "Qual é o seu orçamento estimado?"
];
let indicePergunta = 0;
const respostas = [];

enviarPergunta();

document.getElementById("chat-form").addEventListener("submit", function(e) {
    e.preventDefault();
    
    const chatInput = document.getElementById("chat-input");
    const mensagem = chatInput.value.trim();
    
    if (mensagem) {
        addMessageToChat("client", mensagem);
        respostas.push(mensagem);
        chatInput.value = "";
        
        if (indicePergunta < perguntas.length - 1) {
            indicePergunta++;
            enviarPergunta();
        } else {
            finalizarChat();
        }
    }
});

function addMessageToChat(sender, message) {
    const chatBox = document.getElementById("chat-box");
    const msgElement = document.createElement("div");
    msgElement.classList.add("chat-message", sender);
    msgElement.textContent = message;
    chatBox.appendChild(msgElement);
    chatBox.scrollTop = chatBox.scrollHeight;
}

function enviarPergunta() {
    addMessageToChat("professional", perguntas[indicePergunta]);
}

function finalizarChat() {
    addMessageToChat("professional", "Obrigado! Suas respostas foram registradas.");
    
    fetch("seu-endpoint.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ respostas })
    })
    .then(response => response.json())
    .then(data => console.log("Respostas salvas:", data))
    .catch(error => console.error("Erro ao salvar as respostas:", error));
}
