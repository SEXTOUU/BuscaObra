<div class="dashboard-cards">
    <div class="card">
        <table class="data-table">
            <caption class="data-table-caption">Favoritos e Avaliações</caption>
            <thead class="data-table-header">
                <tr class="data-table-row">
                    <th>ID Cliente</th>
                    <th>Cliente</th>
                    <th>Profissional Favorito</th>
                    <th>Avaliação</th>
                    <th>Nota</th>
                </tr>
            </thead>
            <tbody class="data-table-body">
                <?php
                $query = "SELECT c.cli_id, c.cli_nome, p.pro_nome, a.comentario, a.nota
                          FROM avaliacoes a
                          JOIN cliente c ON a.cliente_id = c.cli_id
                          JOIN profissionais p ON a.profissional_id = p.pro_id";
                $stmt = $pdo->prepare($query);
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr class='data-table-row'>";
                    echo "<td>{$row['cli_id']}</td>";
                    echo "<td>{$row['cli_nome']}</td>";
                    echo "<td>{$row['pro_nome']}</td>";
                    echo "<td>{$row['comentario']}</td>";
                    echo "<td>{$row['nota']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
