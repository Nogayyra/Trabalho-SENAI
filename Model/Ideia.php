<?php
namespace Model;

use Model\Conexao;
use PDO;
use PDOException;

class Ideia
{
    private $db;

    public const PRIORIDADES = ['baixa', 'media', 'alta'];
    public const STATUSES = ['rascunho', 'em_desenvolvimento', 'concluida'];

    public function __construct()
    {
        $this->db = Conexao::getInstance();
    }

    public function criarIdeia(int $userId, string $titulo, string $descricao, string $categoria, string $prioridade, string $status): int|bool
    {
        try {
            $sql = 'INSERT INTO ideas (user_id, titulo, descricao, categoria, prioridade, status) VALUES (:user_id, :titulo, :descricao, :categoria, :prioridade, :status)';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":user_id", $userId, PDO::PARAM_INT);
            $stmt->bindParam(":titulo", $titulo, PDO::PARAM_STR);
            $stmt->bindParam(":descricao", $descricao, PDO::PARAM_STR);
            $stmt->bindParam(":categoria", $categoria, PDO::PARAM_STR);
            $stmt->bindParam(":prioridade", $prioridade, PDO::PARAM_STR);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->execute();
            return (int) $this->db->lastInsertId();
        } catch (PDOException $error) {
            error_log("Erro ao criar ideia: " . $error->getMessage());
            return false;
        }
    }

    public function getIdeiasByUser(int $userId, array $filtros = []): array|bool
    {
        try {
            $sql = 'SELECT * FROM ideas WHERE user_id = :user_id';
            $params = [];
            if (!empty($filtros['categoria'])) {
                $sql .= ' AND categoria = :categoria';
            }
            if (!empty($filtros['prioridade'])) {
                $sql .= ' AND prioridade = :prioridade';
            }
            if (!empty($filtros['status'])) {
                $sql .= ' AND status = :status';
            }
            $sql .= ' ORDER BY created_at DESC';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":user_id", $userId, PDO::PARAM_INT);
            if (!empty($filtros['categoria'])) $stmt->bindValue(":categoria", $filtros['categoria'], PDO::PARAM_STR);
            if (!empty($filtros['prioridade'])) $stmt->bindValue(":prioridade", $filtros['prioridade'], PDO::PARAM_STR);
            if (!empty($filtros['status'])) $stmt->bindValue(":status", $filtros['status'], PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            error_log("Erro ao listar ideias: " . $error->getMessage());
            return false;
        }
    }

    public function getIdeiaById(int $id, int $userId): array|bool
    {
        try {
            $sql = 'SELECT * FROM ideas WHERE id = :id AND user_id = :user_id LIMIT 1';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->bindValue(":user_id", $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            error_log("Erro ao buscar ideia: " . $error->getMessage());
            return false;
        }
    }

    public function updateIdeia(int $id, int $userId, string $titulo, string $descricao, string $categoria, string $prioridade, string $status): bool
    {
        try {
            $sql = 'UPDATE ideas SET titulo = :titulo, descricao = :descricao, categoria = :categoria, prioridade = :prioridade, status = :status WHERE id = :id AND user_id = :user_id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":titulo", $titulo, PDO::PARAM_STR);
            $stmt->bindParam(":descricao", $descricao, PDO::PARAM_STR);
            $stmt->bindParam(":categoria", $categoria, PDO::PARAM_STR);
            $stmt->bindParam(":prioridade", $prioridade, PDO::PARAM_STR);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->bindValue(":user_id", $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $error) {
            error_log("Erro ao atualizar ideia: " . $error->getMessage());
            return false;
        }
    }

    public function deleteIdeia(int $id, int $userId): bool
    {
        try {
            $sql = 'DELETE FROM ideas WHERE id = :id AND user_id = :user_id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->bindValue(":user_id", $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $error) {
            error_log("Erro ao excluir ideia: " . $error->getMessage());
            return false;
        }
    }

    public function getRandomIdeia(int $userId): array|bool
    {
        try {
            $sql = 'SELECT * FROM ideas WHERE user_id = :user_id ORDER BY RAND() LIMIT 1';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":user_id", $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            error_log("Erro ao buscar ideia aleatoria: " . $error->getMessage());
            return false;
        }
    }

    public function countByStatus(int $userId): array|bool
    {
        try {
            $sql = 'SELECT status, COUNT(*) as total FROM ideas WHERE user_id = :user_id GROUP BY status';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":user_id", $userId, PDO::PARAM_INT);
            $stmt->execute();
            $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $contagens = ['total' => 0, 'rascunho' => 0, 'em_desenvolvimento' => 0, 'concluida' => 0];
            foreach ($linhas as $linha) {
                $contagens[$linha['status']] = (int) $linha['total'];
                $contagens['total'] += (int) $linha['total'];
            }
            return $contagens;
        } catch (PDOException $error) {
            error_log("Erro ao contar ideias: " . $error->getMessage());
            return false;
        }
    }

    public static function criar(int $userId, string $titulo, string $descricao, string $categoria, string $prioridade, string $status): int
    {
        $self = new self();
        $r = $self->criarIdeia($userId, $titulo, $descricao, $categoria, $prioridade, $status);
        return (int) $r;
    }

    public static function listarPorUsuario(int $userId, array $filtros = []): array
    {
        $self = new self();
        $r = $self->getIdeiasByUser($userId, $filtros);
        return $r ?: [];
    }

    public static function buscarPorIdEUsuario(int $id, int $userId): ?array
    {
        $self = new self();
        $r = $self->getIdeiaById($id, $userId);
        return $r ?: null;
    }

    public static function atualizar(int $id, int $userId, string $titulo, string $descricao, string $categoria, string $prioridade, string $status): bool
    {
        $self = new self();
        return $self->updateIdeia($id, $userId, $titulo, $descricao, $categoria, $prioridade, $status);
    }

    public static function excluir(int $id, int $userId): bool
    {
        $self = new self();
        return $self->deleteIdeia($id, $userId);
    }

    public static function aleatoriaPorUsuario(int $userId): ?array
    {
        $self = new self();
        $r = $self->getRandomIdeia($userId);
        return $r ?: null;
    }

    public static function contarPorStatus(int $userId): array
    {
        $self = new self();
        $r = $self->countByStatus($userId);
        return $r ?: ['total' => 0, 'rascunho' => 0, 'em_desenvolvimento' => 0, 'concluida' => 0];
    }
}

if (!class_exists('Ideia', false)) {
    class_alias('Model\\Ideia', 'Ideia');
}
