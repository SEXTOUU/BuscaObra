<div class="dashboard-cards">
    <div class="card">
        <form action="save_filtros.php" method="POST">
            <h3>Configurar Filtros</h3>
            <label for="categorias">Categorias Disponíveis:</label>
            <select name="categorias[]" multiple>
                <?php
                $query = "SELECT categoria_id, nome FROM categorias";
                $stmt = $pdo->prepare($query);
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$row['categoria_id']}'>{$row['nome']}</option>";
                }
                ?>
            </select>
            <button type="submit" class="btn">Salvar</button>
        </form>
    </div>
</div>
