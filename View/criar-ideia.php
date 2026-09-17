<?php $tituloPagina = 'Nova Ideia'; ?>
<?php require __DIR__ . '/../templates/header.php'; ?>
<?php require __DIR__ . '/../templates/navbar.php'; ?>
<h3 class="mb-4">Nova Ideia</h3>
<?php $action = 'index.php?action=idea-create';
$textoBotao = 'Salvar';
require __DIR__ . '/../templates/form-ideia.php'; ?>
<?php require __DIR__ . '/../templates/footer.php'; ?>