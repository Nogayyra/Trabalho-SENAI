<?php

// Coordenar o fluxo das requisições de CRUD de ideias e do dashboard.
class IdeiaController
{
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

        $titulo = $_POST['titulo'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $categoria = $_POST['categoria'] ?? '';
        $prioridade = $_POST['prioridade'] ?? '';
        $status = $_POST['status'] ?? '';

        Ideia::criar($userId, $titulo, $descricao, $categoria, $prioridade, $status);

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

        $titulo = $_POST['titulo'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $categoria = $_POST['categoria'] ?? '';
        $prioridade = $_POST['prioridade'] ?? '';
        $status = $_POST['status'] ?? '';

        Ideia::atualizar($id, $userId, $titulo, $descricao, $categoria, $prioridade, $status);

        $_SESSION['sucesso'] = 'Ideia atualizada com sucesso!';
        header('Location: index.php?action=ideas');
        exit;
    }

    public static function excluir(): void
    {
        AutenticacaoController::exigirLogin();

        $userId = AutenticacaoController::usuarioIdLogado();
        $id = (int) ($_POST['id'] ?? 0);

        Ideia::excluir($id, $userId);

        $_SESSION['sucesso'] = 'Ideia excluída com sucesso!';
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
