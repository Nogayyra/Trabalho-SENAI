<?php $tituloPagina = 'Detalhes da Ideia'; ?>
<?php require __DIR__ . '/../templates/header.php'; ?>
<?php require __DIR__ . '/../templates/navbar.php'; ?>
<div class="card shadow-sm">
    <div class="card-body">
        <h3 class="card-title"><?= htmlspecialchars($ideia['titulo'], ENT_QUOTES, 'UTF-8') ?></h3>
        <div class="mb-3">
            <span class="badge badge-prioridade-<?= htmlspecialchars($ideia['prioridade'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(ucfirst($ideia['prioridade']), ENT_QUOTES, 'UTF-8') ?></span>
            <span class="badge badge-status-<?= htmlspecialchars($ideia['status'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $ideia['status'])), ENT_QUOTES, 'UTF-8') ?></span>
            <span class="badge bg-light text-dark border"><?= htmlspecialchars($ideia['categoria'], ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <p class="card-text" style="white-space: pre-wrap;"><?= htmlspecialchars($ideia['descricao'], ENT_QUOTES, 'UTF-8') ?></p>
        <p class="text-muted small mb-0">
            Criada em <?= htmlspecialchars(date('d/m/Y H:i', strtotime($ideia['created_at'])), ENT_QUOTES, 'UTF-8') ?>
            <?php if (!empty($ideia['updated_at']) && $ideia['updated_at'] !== $ideia['created_at']): ?>
                &middot; Atualizada em <?= htmlspecialchars(date('d/m/Y H:i', strtotime($ideia['updated_at'])), ENT_QUOTES, 'UTF-8') ?>
            <?php endif; ?>
        </p>
        <div class="mt-4">
            <a href="index.php?action=idea-edit&id=<?= (int) $ideia['id'] ?>" class="btn btn-outline-secondary">Editar</a>
            <a href="index.php?action=ideas" class="btn btn-outline-primary">Voltar</a>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../templates/footer.php'; ?>