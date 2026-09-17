<?php

class IdeiaController
{
    private static function validar(array $d): array
    {
        $erros = [];
        if (trim($d['titulo'] ?? '') === '') $erros[] = 'Título é obrigatório.';
        if (trim($d['descricao'] ?? '') === '') $erros[] = 'Descrição é obrigatória.';
        if (trim($d['categoria'] ?? '') === '') $erros[] = 'Categoria é obrigatória.';
        if (!in_array($d['prioridade'] ?? '', Ideia::PRIORIDADES, true)) $erros[] = 'Prioridade inválida.';
        if (!in_array($d['status'] ?? '', Ideia::STATUSES, true)) $erros[] = 'Status inválido.';
        return $erros;
    }

    public static function dashboard(): void
    {
        AutenticacaoController::exigirLogin();
        $userId = AutenticacaoController::usuarioIdLogado();
        $contagens = Ideia::contarPorStatus($userId);
        require __DIR__ . '/../View/painel.php';
    }

    public static function listar(): void
    {
        AutenticacaoController::exigirLogin();
        $userId = AutenticacaoController::usuarioIdLogado();
        $filtros = [
            'categoria' => $_GET['categoria'] ?? '',
            'prioridade' => $_GET['prioridade'] ?? '',
            'status' => $_GET['status'] ?? '',
        ];
        $ideias = Ideia::listarPorUsuario($userId, $filtros);
        require __DIR__ . '/../View/ideias.php';
    }

    public static function telaCriar(): void
    {
        AutenticacaoController::exigirLogin();
        $erros = [];
        $ideia = ['titulo' => '', 'descricao' => '', 'categoria' => '', 'prioridade' => 'media', 'status' => 'rascunho'];
        require __DIR__ . '/../View/criar-ideia.php';
    }

    public static function criar(): void
    {
        AutenticacaoController::exigirLogin();
        $userId = AutenticacaoController::usuarioIdLogado();
        $dados = [
            'titulo' => $_POST['titulo'] ?? '',
            'descricao' => $_POST['descricao'] ?? '',
            'categoria' => $_POST['categoria'] ?? '',
            'prioridade' => $_POST['prioridade'] ?? '',
            'status' => $_POST['status'] ?? '',
        ];
        $erros = self::validar($dados);
        $ideia = $dados;
        if (!empty($erros)) {
            require __DIR__ . '/../View/criar-ideia.php';
            return;
        }
        $ok = Ideia::criar($userId, trim($dados['titulo']), trim($dados['descricao']), trim($dados['categoria']), $dados['prioridade'], $dados['status']);
        if (!$ok) {
            $erros[] = 'Erro ao salvar ideia. Tente novamente.';
            require __DIR__ . '/../View/criar-ideia.php';
            return;
        }
        $_SESSION['sucesso'] = 'Ideia cadastrada com sucesso!';
        header('Location: index.php?action=ideas');
        exit;
    }

    public static function telaEditar(): void
    {
        AutenticacaoController::exigirLogin();
        $userId = AutenticacaoController::usuarioIdLogado();
        $id = (int) ($_GET['id'] ?? 0);
        $ideia = Ideia::buscarPorIdEUsuario($id, $userId);
        if (!$ideia) {
            $_SESSION['erro'] = 'Ideia não encontrada.';
            header('Location: index.php?action=ideas');
            exit;
        }
        $erros = [];
        require __DIR__ . '/../View/editar-ideia.php';
    }

    public static function atualizar(): void
    {
        AutenticacaoController::exigirLogin();
        $userId = AutenticacaoController::usuarioIdLogado();
        $id = (int) ($_POST['id'] ?? 0);
        $dados = [
            'titulo' => $_POST['titulo'] ?? '',
            'descricao' => $_POST['descricao'] ?? '',
            'categoria' => $_POST['categoria'] ?? '',
            'prioridade' => $_POST['prioridade'] ?? '',
            'status' => $_POST['status'] ?? '',
        ];
        $erros = self::validar($dados);
        $ideia = array_merge(['id' => $id], $dados);
        if (!empty($erros)) {
            require __DIR__ . '/../View/editar-ideia.php';
            return;
        }
        $existe = Ideia::buscarPorIdEUsuario($id, $userId);
        if (!$existe) {
            $_SESSION['erro'] = 'Ideia não encontrada.';
            header('Location: index.php?action=ideas');
            exit;
        }
        $ok = Ideia::atualizar($id, $userId, trim($dados['titulo']), trim($dados['descricao']), trim($dados['categoria']), $dados['prioridade'], $dados['status']);
        if (!$ok) {
            $erros[] = 'Erro ao atualizar ideia. Tente novamente.';
            require __DIR__ . '/../View/editar-ideia.php';
            return;
        }
        $_SESSION['sucesso'] = 'Ideia atualizada com sucesso!';
        header('Location: index.php?action=ideas');
        exit;
    }

    public static function excluir(): void
    {
        AutenticacaoController::exigirLogin();
        $userId = AutenticacaoController::usuarioIdLogado();
        $id = (int) ($_POST['id'] ?? 0);
        $ok = Ideia::excluir($id, $userId);
        if (!$ok) {
            $_SESSION['erro'] = 'Erro ao excluir ideia.';
        } else {
            $_SESSION['sucesso'] = 'Ideia excluída com sucesso!';
        }
        header('Location: index.php?action=ideas');
        exit;
    }

    public static function detalhes(): void
    {
        AutenticacaoController::exigirLogin();
        $userId = AutenticacaoController::usuarioIdLogado();
        $id = (int) ($_GET['id'] ?? 0);
        $ideia = Ideia::buscarPorIdEUsuario($id, $userId);
        if (!$ideia) {
            $_SESSION['erro'] = 'Ideia não encontrada.';
            header('Location: index.php?action=ideas');
            exit;
        }
        require __DIR__ . '/../View/detalhes-ideia.php';
    }

    public static function aleatoria(): void
    {
        AutenticacaoController::exigirLogin();
        $userId = AutenticacaoController::usuarioIdLogado();
        $ideia = Ideia::aleatoriaPorUsuario($userId);
        if (!$ideia) {
            $_SESSION['erro'] = 'Você ainda não tem nenhuma ideia cadastrada.';
            header('Location: index.php?action=ideas');
            exit;
        }
        header('Location: index.php?action=idea-details&id=' . $ideia['id']);
        exit;
    }
}
