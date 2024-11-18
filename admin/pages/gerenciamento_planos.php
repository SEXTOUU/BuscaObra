<div class="dashboard-cards">
    <div class="card">
        <table class="data-table">
            <caption class="data-table-caption">Gerenciamento de Planos</caption>
            <thead class="data-table-header">
                <tr class="data-table-row">
                    <th>ID Plano</th>
                    <th>Nome</th>
                    <th>Prioridade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody class="data-table-body">
                <?php
                $stmt = $pdo->prepare("SELECT plano_id, nome, prioridade FROM planos");
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr class='data-table-row'>";
                    echo "<td>{$row['plano_id']}</td>";
                    echo "<td>{$row['nome']}</td>";
                    echo "<td>{$row['prioridade']}</td>";
                    echo "<td>
                            <a href='?page=edit_plano&plano_id={$row['plano_id']}'><i class='fa-solid fa-pen-to-square'></i></a>
                            <a href='?page=delete_plano&plano_id={$row['plano_id']}'><i class='fa-solid fa-trash'></i></a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
