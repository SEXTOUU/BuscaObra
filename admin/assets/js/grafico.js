// Criando o gráfico padrão com dados fictícios
const ctx = document.getElementById('performanceChart').getContext('2d');

let performanceChart = new Chart(ctx, {
    type: 'bar', // Alterado para 'bar' para criar um gráfico de barras
    data: {
        labels: ['Hoje', 'Ontem', '3 Dias', '5 Dias', '7 Dias'], // Labels de exemplo
        datasets: [{
            label: 'Performance',
            data: [10, 20, 30, 25, 40], // Dados de exemplo
            backgroundColor: 'rgba(0, 123, 255, 0.2)', // Cor de fundo das barras
            borderColor: 'rgba(0, 123, 255, 1)', // Cor da borda das barras
            borderWidth: 2, // Largura da borda das barras
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true, // Começar o eixo Y no valor 0
            }
        }
    }
});


// Função para alterar os dados com base no intervalo
function changeInterval(interval) {
    let labels = [];
    let data = [];
    
    switch (interval) {
        case 'today':
            labels = ['Hoje'];
            data = [10]; // Dados para o gráfico de "Hoje"
            break;
        case 'yesterday':
            labels = ['Ontem'];
            data = [20]; // Dados para o gráfico de "Ontem"
            break;
        case '7days':
            labels = ['1 Dia', '2 Dias', '3 Dias', '4 Dias', '5 Dias', '6 Dias', '7 Dias'];
            data = [10, 20, 30, 40, 50, 60, 70]; // Dados para o gráfico de "7 Dias"
            break;
        case '15days':
            labels = ['2 Dias', '3 Dias', '4 Dias', '5 Dias', '6 Dias'];
            data = [20, 30, 40, 50, 60]; // Dados para o gráfico de "15 Dias"
            break;
        case '30days':
            labels = ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4'];
            data = [30, 40, 50, 60]; // Dados para o gráfico de "30 Dias"
            break;
        default:
            break;
    }

    // Atualizar o gráfico com os novos dados
    performanceChart.data.labels = labels;
    performanceChart.data.datasets[0].data = data;
    performanceChart.update();
}