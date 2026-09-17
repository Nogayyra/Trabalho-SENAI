<?php

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
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $erros = [];

        if ($nome === '')
            $erros[] = 'Nome é obrigatório.';
        if ($email === '')
            $erros[] = 'E-mail é obrigatório.';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $erros[] = 'E-mail inválido.';
        if ($senha === '')
            $erros[] = 'Senha é obrigatória.';
        elseif (mb_strlen($senha) < 6)
            $erros[] = 'Senha deve ter no mínimo 6 caracteres.';

        if (empty($erros) && Usuario::buscarPorEmail($email) !== null) {
            $erros[] = 'E-mail já cadastrado.';
        }

        if (!empty($erros)) {
            require __DIR__ . '/../View/cadastro.php';
            return;
        }

        try {
            $id = Usuario::criar($nome, $email, $senha);
            if (!$id) {
                $erros[] = 'Erro ao criar usuário. Tente novamente.';
                require __DIR__ . '/../View/cadastro.php';
                return;
            }
        } catch (PDOException $e) {
            if ((int) ($e->getCode()) === 23000 || str_contains($e->getMessage(), 'Duplicate')) {
                $erros[] = 'E-mail já cadastrado.';
            } else {
                $erros[] = 'Erro ao criar usuário. Tente novamente.';
            }
            require __DIR__ . '/../View/cadastro.php';
            return;
        }

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
