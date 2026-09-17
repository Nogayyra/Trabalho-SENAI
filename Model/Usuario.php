<?php

class Usuario
{
    public static function criar(string $nome, string $email, string $senha): int
    {
        $pdo = Conexao::get();
        $hash = password_hash($senha, PASSWORD_ARGON2ID);
        $stmt = $pdo->prepare('INSERT INTO users (nome, email, senha) VALUES (:nome, :email, :senha)');
        $stmt->execute(['nome' => $nome, 'email' => $email, 'senha' => $hash]);
        return (int) $pdo->lastInsertId();
    }

    public static function buscarPorEmail(string $email): ?array
    {
        $pdo = Conexao::get();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    public static function buscarPorId(int $id): ?array
    {
        $pdo = Conexao::get();
        $stmt = $pdo->prepare('SELECT id, nome, email, created_at FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    public static function autenticar(string $email, string $senha): ?array
    {
        $usuario = self::buscarPorEmail($email);
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            if (password_needs_rehash($usuario['senha'], PASSWORD_ARGON2ID)) {
                self::atualizarHash($usuario['id'], $senha);
            }
            unset($usuario['senha']);
            return $usuario;
        }
        return null;
    }

    private static function atualizarHash(int $id, string $senha): void
    {
        $pdo = Conexao::get();
        $hash = password_hash($senha, PASSWORD_ARGON2ID);
        $stmt = $pdo->prepare('UPDATE users SET senha = :senha WHERE id = :id');
        $stmt->execute(['senha' => $hash, 'id' => $id]);
    }
}
