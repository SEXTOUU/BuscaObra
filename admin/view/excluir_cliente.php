<?php
if (isset($_POST['delete-salvar'])) {
    $cli_id = $_POST['cli_id'];
    deletarCliente($cli_id); // Função para excluir o cliente
    setAlert('info_message');
    exit;
}
?>
<div class="card">
    <h2>Excluir cliente</h2>
    <form class="delete-form" method="POST"></form>
        <input type="hidden" name="cli_id" value="<?php echo $cliente['cli_id']; ?>">
        <button type="submit" name="delete-salvar">Excluir</button>
    </form>
</div>