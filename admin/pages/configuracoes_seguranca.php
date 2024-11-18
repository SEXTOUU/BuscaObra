<div class="dashboard-cards">
    <div class="card">
        <form action="save_seguranca.php" method="POST">
            <h3>Configurações de Segurança</h3>
            <label for="senha_min">Tamanho Mínimo da Senha:</label>
            <input type="number" id="senha_min" name="senha_min" value="8">
            <label for="tentativas_max">Número Máximo de Tentativas:</label>
            <input type="number" id="tentativas_max" name="tentativas_max" value="5">
            <button type="submit" class="btn">Salvar</button>
        </form>
    </div>
</div>
