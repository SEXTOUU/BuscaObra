<div class="dashboard-cards">
    <div class="card">
        <table class="data-table">
        <caption class="data-table-caption">Painel de Profissionais</caption>
        <thead class="data-table-header">
            <tr class="data-table-row">
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Bairro</th>
            <th>Cargo</th>
            <th>Ações</th>
            </tr>
        </thead>
        <tbody class="data-table-body">
            <?php
                if (count($clientes) > 0) {
                    foreach ($clientes as $row) {
                        echo '<tr class="data-table-row">';
                        echo '<td>' . htmlspecialchars($row['cli_id']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['cli_nome']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['cli_email']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['cli_bairro']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['cargo']) . '</td>';
                        echo '<td>
                                <a href="?page=editar&cli_id=' . $row['cli_id'] . '" ><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="?page=excluir&cli_id=' . $row['cli_id'] . '" ><i class="fa-solid fa-trash"></i></a>
                            </td>';
                        echo '</tr>';
                    }          
                } else {
                    echo '<tr><td colspan="6">Nenhum resultado encontrado.</td></tr>';
                }
            ?>  
        </tbody>
    </table>
    <div id="paginacao" class="paginacao">
        <button onclick="window.location.href='listacliente.php?pagina=<?php echo $paginaAtual - 1; ?>'" id="btn-anterior" <?php echo ($paginaAtual <= 1) ? 'disabled' : ''; ?>>Anterior</button>
        <span id="info-pagina">Página <?php echo $paginaAtual; ?> de <?php echo $totalPaginas; ?></span>
        <button onclick="window.location.href='listacliente.php?pagina=<?php echo $paginaAtual + 1; ?>'" id="btn-proximo" <?php echo ($paginaAtual >= $totalPaginas) ? 'disabled' : ''; ?>>Próximo</button>
    </div>
    </div>
</div>


