<?php $tituloPagina = 'Minhas Ideias'; ?>
<?php require __DIR__ . '/../templates/header.php'; ?>
<?php require __DIR__ . '/../templates/navbar.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Minhas Ideias</h3>
    <a href="index.php?action=idea-create" class="btn btn-primary">+ Nova ideia</a>
</div>
<form action="index.php" method="get" class="row g-2 mb-4">
    <input type="hidden" name="action" value="ideas">
    <div class="col-md-3">
        <input type="text" name="categoria" class="form-control" placeholder="Categoria" value="<?= htmlspecialchars($_GET['categoria'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="col-md-3">
        <select name="prioridade" class="form-select">
            <option value="">Todas as prioridades</option>
            <?php foreach (Ideia::PRIORIDADES as $p): ?>
                <option value="<?= htmlspecialchars($p, ENT_QUOTES, 'UTF-8') ?>" <?= (($_GET['prioridade'] ?? '') === $p) ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst($p), ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">Todos os status</option>
            <?php foreach (Ideia::STATUSES as $s): ?>
                <option value="<?= htmlspecialchars($s, ENT_QUOTES, 'UTF-8') ?>" <?= (($_GET['status'] ?? '') === $s) ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $s)), ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-outline-secondary w-100">Filtrar</button>
    </div>
</form>
<?php if (empty($ideias)): ?>
    <p class="text-muted">Nenhuma ideia encontrada.</p>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($ideias as $ideia): ?>
            <div class="col-md-4">
                <div class="card card-idea h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($ideia['titulo'], ENT_QUOTES, 'UTF-8') ?></h5>
                        <p class="card-text text-truncate"><?= htmlspecialchars($ideia['descricao'], ENT_QUOTES, 'UTF-8') ?></p>
                        <div class="mb-3">
                            <span class="badge badge-prioridade-<?= htmlspecialchars($ideia['prioridade'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(ucfirst($ideia['prioridade']), ENT_QUOTES, 'UTF-8') ?></span>
                            <span class="badge badge-status-<?= htmlspecialchars($ideia['status'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $ideia['status'])), ENT_QUOTES, 'UTF-8') ?></span>
                            <span class="badge bg-light text-dark border"><?= htmlspecialchars($ideia['categoria'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <div class="mt-auto">
                            <a href="index.php?action=idea-details&id=<?= (int) $ideia['id'] ?>" class="btn btn-sm btn-outline-primary">Ver</a>
                            <a href="index.php?action=idea-edit&id=<?= (int) $ideia['id'] ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
                            <form action="index.php?action=idea-delete" method="post" class="d-inline">
                                <input type="hidden" name="id" value="<?= (int) $ideia['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php require __DIR__ . '/../templates/footer.php'; ?>