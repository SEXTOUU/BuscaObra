<div class="dashboard-cards">
    <div class="card">
        <table class="data-table">
            <caption class="data-table-caption">Gerenciar Destaques</caption>
            <thead class="data-table-header">
                <tr class="data-table-row">
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Profissão</th>
                    <th>Plano</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody class="data-table-body">
                <?php
                $query = "SELECT p.pro_id, p.pro_nome, pf.nome AS profissao, pl.nome AS plano 
                          FROM profissionais p
                          JOIN profissoes pf ON p.profissao_id = pf.profissao_id
                          JOIN planos pl ON p.plano_id = pl.plano_id";
                $stmt = $pdo->prepare ($query);
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr class='data-table-row'>";
                    echo "<td>{$row['pro_id']}</td>";
                    echo "<td>{$row['pro_nome']}</td>";
                    echo "<td>{$row['profissao']}</td>";
                    echo "<td>{$row['plano']}</td>";
                    echo "<td>
                            <a href='?page=destaque&pro_id={$row['pro_id']}'><i class='fa-solid fa-star'></i></a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
