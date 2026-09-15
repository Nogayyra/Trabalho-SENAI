<?php

/**
 * Responsabilidade: representar a ideia e suas operações de dados
 * (criar, listar, buscar, atualizar, excluir, filtrar).
 */
class Ideia
{
    public const PRIORIDADES = ['baixa', 'media', 'alta'];
    public const STATUSES = ['rascunho', 'em_desenvolvimento', 'concluida'];

    public static function criar(int $userId, string $titulo, string $descricao, string $categoria, string $prioridade, string $status): int
    {
        $pdo = Conexao::get();
        $stmt = $pdo->prepare(
            'INSERT INTO ideas (user_id, titulo, descricao, categoria, prioridade, status)
             VALUES (:user_id, :titulo, :descricao, :categoria, :prioridade, :status)'
        );
        $stmt->execute([
            'user_id' => $userId,
            'titulo' => $titulo,
            'descricao' => $descricao,
            'categoria' => $categoria,
            'prioridade' => $prioridade,
            'status' => $status,
        ]);

        return (int) $pdo->lastInsertId();
    }

    /**
     * Lista as ideias de um usuário, com filtros opcionais.
     */
    public static function listarPorUsuario(int $userId, array $filtros = []): array
    {
        $pdo = Conexao::get();

        $sql = 'SELECT * FROM ideas WHERE user_id = :user_id';
        $params = ['user_id' => $userId];

        if (!empty($filtros['categoria'])) {
            $sql .= ' AND categoria = :categoria';
            $params['categoria'] = $filtros['categoria'];
        }

        if (!empty($filtros['prioridade'])) {
            $sql .= ' AND prioridade = :prioridade';
            $params['prioridade'] = $filtros['prioridade'];
        }

        if (!empty($filtros['status'])) {
            $sql .= ' AND status = :status';
            $params['status'] = $filtros['status'];
        }

        $sql .= ' ORDER BY created_at DESC';

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Busca uma ideia por id, garantindo que pertence ao usuário informado.
     */
    public static function buscarPorIdEUsuario(int $id, int $userId): ?array
    {
        $pdo = Conexao::get();
        $stmt = $pdo->prepare('SELECT * FROM ideas WHERE id = :id AND user_id = :user_id LIMIT 1');
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        $ideia = $stmt->fetch();

        return $ideia ?: null;
    }

    public static function atualizar(int $id, int $userId, string $titulo, string $descricao, string $categoria, string $prioridade, string $status): bool
    {
        $pdo = Conexao::get();
        $stmt = $pdo->prepare(
            'UPDATE ideas
             SET titulo = :titulo, descricao = :descricao, categoria = :categoria,
                 prioridade = :prioridade, status = :status
             WHERE id = :id AND user_id = :user_id'
        );

        return $stmt->execute([
            'titulo' => $titulo,
            'descricao' => $descricao,
            'categoria' => $categoria,
            'prioridade' => $prioridade,
            'status' => $status,
            'id' => $id,
            'user_id' => $userId,
        ]);
    }

    public static function excluir(int $id, int $userId): bool
    {
        $pdo = Conexao::get();
        $stmt = $pdo->prepare('DELETE FROM ideas WHERE id = :id AND user_id = :user_id');

        return $stmt->execute(['id' => $id, 'user_id' => $userId]);
    }

    public static function aleatoriaPorUsuario(int $userId): ?array
    {
        $pdo = Conexao::get();
        $stmt = $pdo->prepare('SELECT * FROM ideas WHERE user_id = :user_id ORDER BY RAND() LIMIT 1');
        $stmt->execute(['user_id' => $userId]);
        $ideia = $stmt->fetch();

        return $ideia ?: null;
    }

    /**
     * Retorna as contagens usadas no dashboard.
     */
    public static function contarPorStatus(int $userId): array
    {
        $pdo = Conexao::get();
        $stmt = $pdo->prepare(
            'SELECT status, COUNT(*) as total FROM ideas WHERE user_id = :user_id GROUP BY status'
        );
        $stmt->execute(['user_id' => $userId]);
        $linhas = $stmt->fetchAll();

        $contagens = [
            'total' => 0,
            'rascunho' => 0,
            'em_desenvolvimento' => 0,
            'concluida' => 0,
        ];

        foreach ($linhas as $linha) {
            $contagens[$linha['status']] = (int) $linha['total'];
            $contagens['total'] += (int) $linha['total'];
        }

        return $contagens;
    }
}
