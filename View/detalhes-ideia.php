<?php $tituloPagina = 'Detalhes da Ideia'; ?>
<?php require __DIR__ . '/../templates/header.php'; ?>
<?php require __DIR__ . '/../templates/navbar.php'; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <h3 class="card-title"><?= $ideia['titulo'] ?></h3>

        <div class="mb-3">
            <span class="badge badge-prioridade-<?= $ideia['prioridade'] ?>">
                <?= ucfirst($ideia['prioridade']) ?>
            </span>
            <span class="badge badge-status-<?= $ideia['status'] ?>">
                <?= ucfirst(str_replace('_', ' ', $ideia['status'])) ?>
            </span>
            <span class="badge bg-light text-dark border">
                <?= $ideia['categoria'] ?>
            </span>
        </div>

        <p class="card-text" style="white-space: pre-wrap;"><?= $ideia['descricao'] ?></p>

        <p class="text-muted small mb-0">
            Criada em <?= date('d/m/Y H:i', strtotime($ideia['created_at'])) ?>
            <?php if (!empty($ideia['updated_at']) && $ideia['updated_at'] !== $ideia['created_at']): ?>
                &middot; Atualizada em <?= date('d/m/Y H:i', strtotime($ideia['updated_at'])) ?>
            <?php endif; ?>
        </p>

        <div class="mt-4">
            <a href="index.php?action=idea-edit&id=<?= $ideia['id'] ?>"
                class="btn btn-outline-secondary">Editar</a>
            <a href="index.php?action=ideas" class="btn btn-outline-primary">Voltar</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../templates/footer.php'; ?>
