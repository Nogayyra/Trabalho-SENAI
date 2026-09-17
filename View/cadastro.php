<?php $tituloPagina = 'Cadastro'; ?>
<?php require __DIR__ . '/../templates/header.php'; ?>
<?php require __DIR__ . '/../templates/navbar.php'; ?>
<div class="auth-box card shadow-sm">
    <div class="card-body p-4">
        <h3 class="mb-4 text-center">Criar conta</h3>
        <?php if (!empty($erros)): ?>
            <div class="alert alert-danger">
                <?php foreach ($erros as $erro): ?><div><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></div><?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="index.php?action=register" method="post">
            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($_POST['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="text" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="password" name="senha" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
        </form>
        <p class="text-center mt-3 mb-0">Já tem conta? <a href="index.php?action=login">Entrar</a></p>
    </div>
</div>
<?php require __DIR__ . '/../templates/footer.php'; ?>