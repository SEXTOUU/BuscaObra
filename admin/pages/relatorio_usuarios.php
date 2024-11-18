<div class="dashboard-cards">
    <div class="card">
        <table class="data-table">
            <caption class="data-table-caption">Relatório de Usuários</caption>
            <thead class="data-table-header">
                <tr class="data-table-row">
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Tipo</th>
                </tr>
            </thead>
            <tbody class="data-table-body">
                <?php
                $query = "SELECT cli_id, cli_nome, cli_email, usertipo_nome 
                          FROM cliente 
                          JOIN usertipo ON cliente.cli_tipo = usertipo.usertipo_id";
               $stmt = $pdo->prepare($query);
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr class='data-table-row'>";
                    echo "<td>{$row['cli_id']}</td>";
                    echo "<td>{$row['cli_nome']}</td>";
                    echo "<td>{$row['cli_email']}</td>";
                    echo "<td>{$row['usertipo_nome']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
