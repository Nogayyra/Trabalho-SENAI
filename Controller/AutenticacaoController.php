<?php

AutenticacaoController
// Coordenar cadastro, login, logout e verificação de sessão.
class AutenticacaoController
{
    public static function telaLogin(): void
    {
        if (self::estaLogado()) {
            header('Location: index.php?action=dashboard');
            exit;
        }

        $erros = [];
        require __DIR__ . '/../View/entrar.php';
    }

    public static function telaCadastro(): void
    {
        if (self::estaLogado()) {
            header('Location: index.php?action=dashboard');
            exit;
        }

        $erros = [];
        require __DIR__ . '/../View/cadastro.php';
    }

    public static function cadastrar(): void
    {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        Usuario::criar($nome, $email, $senha);

        $_SESSION['sucesso'] = 'Cadastro realizado com sucesso! Faça login para continuar.';
        header('Location: index.php?action=login');
        exit;
    }

    public static function login(): void
    {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $usuario = Usuario::autenticar($email, $senha);

        if (!$usuario) {
            $erros = ['E-mail ou senha inválidos.'];
            require __DIR__ . '/../View/entrar.php';
            return;
        }

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];

        header('Location: index.php?action=dashboard');
        exit;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();

        header('Location: index.php?action=login');
        exit;
    }

    public static function estaLogado(): bool
    {
        return !empty($_SESSION['usuario_id']);
    }

    // Protege páginas que exigem login, deve ser chamado no início da ação.
    public static function exigirLogin(): void
    {
        if (!self::estaLogado()) {
            header('Location: index.php?action=login');
            exit;
        }
    }

    public static function usuarioIdLogado(): int
    {
        return (int) ($_SESSION['usuario_id'] ?? 0);
    }
}
