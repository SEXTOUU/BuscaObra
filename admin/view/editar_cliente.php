<?php
 if (isset($_POST['edit-salvar'])) {
    $cli_nome = $_POST['cli_nome'];
    $cli_email = $_POST['cli_email'];
    $cli_bairro = $_POST['cli_bairro'];
    editarCliente($cli_id, $cli_nome, $cli_email, $cli_bairro);
 }
?>
<div class="card">
    <h2>Editar Cliente</h2>
    <form class="edit-form" method="POST">
        <input type="hidden" name="cli_id" value="<?php echo $cliente['cli_id']; ?>">
        
        <label for="nome">Nome:</label>
        <input type="text" name="cli_nome" id="nome" value="<?php echo $cliente['cli_nome']; ?>" required>
        
        <label for="email">Email:</label>
        <input type="email" name="cli_email" id="email" value="<?php echo $cliente['cli_email']; ?>" required>
        
        <label for="bairro">Bairro:</label>
        <input type="text" name="cli_bairro" id="bairro" value="<?php echo $cliente['cli_bairro']; ?>">
        
        <button type="submit" name="edit-salvar">Salvar Alterações</button>
        <button type="submit" name="edit-cancelar">Cancelar</button>
    </form>
</div>