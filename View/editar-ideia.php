<?php $tituloPagina = 'Editar Ideia'; ?>
<?php require __DIR__ . '/../templates/header.php'; ?>
<?php require __DIR__ . '/../templates/navbar.php'; ?>

<h3 class="mb-4">Editar Ideia</h3>

<form action="index.php?action=idea-update" method="post">
    <input type="hidden" name="id" value="<?= $ideia['id'] ?>">

    <div class="mb-3">
        <label class="form-label">Título</label>
        <input type="text" name="titulo" class="form-control" value="<?= $ideia['titulo'] ?? '' ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Descrição</label>
        <textarea name="descricao" class="form-control" rows="5"><?= $ideia['descricao'] ?? '' ?></textarea>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Categoria</label>
            <input type="text" name="categoria" class="form-control" value="<?= $ideia['categoria'] ?? '' ?>">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Prioridade</label>
            <select name="prioridade" class="form-select">
                <?php foreach (Ideia::PRIORIDADES as $p): ?>
                    <option value="<?= $p ?>" <?= (($ideia['prioridade'] ?? '') === $p) ? 'selected' : '' ?>>
                        <?= ucfirst($p) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <?php foreach (Ideia::STATUSES as $s): ?>
                    <option value="<?= $s ?>" <?= (($ideia['status'] ?? '') === $s) ? 'selected' : '' ?>>
                        <?= ucfirst(str_replace('_', ' ', $s)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Atualizar</button>
    <a href="index.php?action=ideas" class="btn btn-outline-secondary">Cancelar</a>
</form>

<?php require __DIR__ . '/../templates/footer.php'; ?>
