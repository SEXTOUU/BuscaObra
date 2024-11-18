<div class="dashboard-cards">
    <div class="card">
        <table class="data-table">
            <caption class="data-table-caption">Moderação de Avaliações</caption>
            <thead class="data-table-header">
                <tr class="data-table-row">
                    <th>ID Avaliação</th>
                    <th>Cliente</th>
                    <th>Profissional</th>
                    <th>Nota</th>
                    <th>Comentário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody class="data-table-body">
                <?php
                $query = "SELECT a.avaliacao_id, c.cli_nome AS cliente, p.pro_nome AS profissional, a.nota, a.comentario
                          FROM avaliacoes a
                          JOIN cliente c ON a.cliente_id = c.cli_id
                          JOIN profissionais p ON a.profissional_id = p.pro_id";
                $stmt = $pdo->prepare($query);
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr class='data-table-row'>";
                    echo "<td>{$row['avaliacao_id']}</td>";
                    echo "<td>{$row['cliente']}</td>";
                    echo "<td>{$row['profissional']}</td>";
                    echo "<td>{$row['nota']}</td>";
                    echo "<td>{$row['comentario']}</td>";
                    echo "<td>
                            <a href='?page=approve_review&avaliacao_id={$row['avaliacao_id']}'><i class='fa-solid fa-check'></i></a>
                            <a href='?page=delete_review&avaliacao_id={$row['avaliacao_id']}'><i class='fa-solid fa-trash'></i></a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
