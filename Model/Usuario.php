<?php
namespace Model;

use Model\Conexao;
use PDO;
use PDOException;

class Usuario
{
    private $db;

    public function __construct()
    {
        $this->db = Conexao::getInstance();
    }

    private function hashPassword(string $password): string
    {
        $options = [
            "memory_cost" => 1 << 17,
            "time_cost" => 4,
            "threads" => 2
        ];
        return password_hash($password, PASSWORD_ARGON2ID, $options);
    }

    public function registerUser(string $nome, string $email, string $password): bool
    {
        try {
            $hash = $this->hashPassword($password);
            $sql = "INSERT INTO users(nome, email, senha) VALUES (:nome, :email, :senha)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":senha", $hash, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $error) {
            error_log("Erro ao registrar usuário: " . $error->getMessage());
            return false;
        }
    }

    public function getUserByEmail(string $email): array|bool
    {
        try {
            $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            error_log("Erro ao buscar usuário: " . $error->getMessage());
            return false;
        }
    }

    public function getUserInfo(int $id): array|bool
    {
        try {
            $sql = "SELECT id, nome, email, created_at FROM users WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            error_log("Erro ao obter informações: " . $error->getMessage());
            return false;
        }
    }

    public static function criar(string $nome, string $email, string $senha): int
    {
        $self = new self();
        $self->registerUser($nome, $email, $senha);
        return (int) $self->db->lastInsertId();
    }

    public static function buscarPorEmail(string $email): ?array
    {
        $self = new self();
        $r = $self->getUserByEmail($email);
        return $r ?: null;
    }

    public static function buscarPorId(int $id): ?array
    {
        $self = new self();
        $r = $self->getUserInfo($id);
        return $r ?: null;
    }

    public static function autenticar(string $email, string $senha): ?array
    {
        $self = new self();
        $usuario = $self->getUserByEmail($email);
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            unset($usuario['senha']);
            return $usuario;
        }
        return null;
    }
}

if (!class_exists('Usuario', false)) {
    class_alias('Model\\Usuario', 'Usuario');
}
