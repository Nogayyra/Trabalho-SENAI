<?php if (AutenticacaoController::estaLogado()): ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php?action=dashboard">💡 <?= APP_NAME ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=dashboard">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=ideas">Minhas Ideias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=idea-create">Nova Ideia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=idea-random">Ideia Aleatória</a>
                </li>
            </ul>
            <span class="navbar-text me-3">
                Olá, <?= $_SESSION['usuario_nome'] ?? '' ?>
            </span>
            <form action="index.php?action=logout" method="post" class="d-inline">
                <button type="submit" class="btn btn-outline-light btn-sm">Sair</button>
            </form>
        </div>
    </div>
</nav>
<?php endif; ?>

<div class="container">
    <?php if (!empty($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sucesso'] ?></div>
        <?php unset($_SESSION['sucesso']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['erro'] ?></div>
        <?php unset($_SESSION['erro']); ?>
    <?php endif; ?>
