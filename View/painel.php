<?php $tituloPagina = 'Painel'; ?>
<?php require __DIR__ . '/../templates/header.php'; ?>
<?php require __DIR__ . '/../templates/navbar.php'; ?>

<h3 class="mb-4">Painel</h3>

<div class="row g-3">
    <div class="col-md-3 col-6">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total de ideias</h6>
                <h2><?= (int) $contagens['total'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Rascunhos</h6>
                <h2><?= (int) $contagens['rascunho'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Em desenvolvimento</h6>
                <h2><?= (int) $contagens['em_desenvolvimento'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Concluídas</h6>
                <h2><?= (int) $contagens['concluida'] ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="index.php?action=idea-create" class="btn btn-primary">+ Nova ideia</a>
    <a href="index.php?action=ideas" class="btn btn-outline-secondary">Ver todas as ideias</a>
    <a href="index.php?action=idea-random" class="btn btn-outline-secondary">🎲 Ideia aleatória</a>
</div>

<?php require __DIR__ . '/../templates/footer.php'; ?>