<?php if (!empty($erros)): ?>
    <div class="alert alert-danger">
        <?php foreach ($erros as $erro): ?><div><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></div><?php endforeach; ?>
    </div>
<?php endif; ?>
<form action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>" method="post">
    <?php if (!empty($ideia['id'])): ?><input type="hidden" name="id" value="<?= (int) $ideia['id'] ?>"><?php endif; ?>
    <div class="mb-3">
        <label class="form-label">Título</label>
        <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($ideia['titulo'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Descrição</label>
        <textarea name="descricao" class="form-control" rows="5"><?= htmlspecialchars($ideia['descricao'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Categoria</label>
            <input type="text" name="categoria" class="form-control" value="<?= htmlspecialchars($ideia['categoria'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Prioridade</label>
            <select name="prioridade" class="form-select">
                <?php foreach (Ideia::PRIORIDADES as $p): ?>
                    <option value="<?= htmlspecialchars($p, ENT_QUOTES, 'UTF-8') ?>" <?= (($ideia['prioridade'] ?? '') === $p) ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst($p), ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <?php foreach (Ideia::STATUSES as $s): ?>
                    <option value="<?= htmlspecialchars($s, ENT_QUOTES, 'UTF-8') ?>" <?= (($ideia['status'] ?? '') === $s) ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $s)), ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-primary"><?= htmlspecialchars($textoBotao, ENT_QUOTES, 'UTF-8') ?></button>
    <a href="index.php?action=ideas" class="btn btn-outline-secondary">Cancelar</a>
</form>