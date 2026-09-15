<?php $tituloPagina = 'Entrar'; ?>
<?php require __DIR__ . '/../templates/header.php'; ?>
<?php require __DIR__ . '/../templates/navbar.php'; ?>

<div class="auth-box card shadow-sm">
    <div class="card-body p-4">
        <h3 class="mb-4 text-center">💡 <?= APP_NAME ?></h3>

        <?php if (!empty($erros)): ?>
            <div class="alert alert-danger">
                <?php foreach ($erros as $erro): ?>
                    <div><?= $erro ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=login" method="post">
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="text" name="email" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="password" name="senha" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>

        <p class="text-center mt-3 mb-0">
            Não tem conta? <a href="index.php?action=register">Cadastre-se</a>
        </p>
    </div>
</div>

<?php require __DIR__ . '/../templates/footer.php'; ?>
