<div class="dashboard-cards">
    <div class="card">
        <table class="data-table">
            <caption class="data-table-caption">Gerenciamento de Administradores</caption>
            <thead class="data-table-header">
                <tr class="data-table-row">
                    <th>ID Admin</th>
                    <th>Nome</th>
                    <th>Departamento</th>
                    <th>Cargo</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody class="data-table-body">
                <?php
                $query = "SELECT a.admin_id, c.cli_nome, a.admin_departamento, a.admin_cargo, a.status
                          FROM admins a
                          JOIN cliente c ON a.cli_id = c.cli_id";
                $stmt = $pdo->prepare($query);
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $status = $row['status'] == 1 ? 'Ativo' : 'Inativo';
                    echo "<tr class='data-table-row'>";
                    echo "<td>{$row['admin_id']}</td>";
                    echo "<td>{$row['cli_nome']}</td>";
                    echo "<td>{$row['admin_departamento']}</td>";
                    echo "<td>{$row['admin_cargo']}</td>";
                    echo "<td>{$status}</td>";
                    echo "<td>
                            <a href='?page=edit_admin&admin_id={$row['admin_id']}'><i class='fa-solid fa-pen-to-square'></i></a>
                            <a href='?page=delete_admin&admin_id={$row['admin_id']}'><i class='fa-solid fa-trash'></i></a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
